<?php
class Project_library extends MX_Controller{
	protected $message, $PROJECT_SETUP_ID, $PROJECT_ID, $RAA_ID, 
		$tblSetup, $tblLock, $tblMonthly, $tblMonthlyBlock;
	function __construct(){
		parent::__construct();
		$this->PROJECT_ID = 0;
		$this->PROJECT_SETUP_ID = 0;
		$this->RAA_ID = 0;
		$this->RESULT = false;
		$this->message = array();
		$this->tblSetup = 'dep_mi__m_project_setup';
		$this->tblLock = 'dep_mi__t_locks';
		$this->tblMonthly = 'dep_mi__t_monthly';
		$this->tblMonthlyBlock = 'dep_mi__t_monthly_block';
	}
	//offices
	public function showOfficeFilterBox(){
		//$data['instance_name'] = 'search_office';
		$data =array();
		$data['prefix'] = 'search_office';
		$data['show_sdo'] = FALSE;
		$data['row'] = '<tr>
		<td class="ui-widget-content"><strong>Project Name</strong></td>
		<td class="ui-widget-content">
			<input type="text" value="" name="SEARCH_PROJECT_NAME" id="SEARCH_PROJECT_NAME">
		</td>
		</tr>
		<tr><td colspan="2" class="ui-widget-content">'.getButton('Search', 'refreshSearch()', 4, 'cus-zoom').'</td></tr>';
		$this->load->view('setup/office_filter_view', $data);
    }   

	public function getOfficeList($designation, $sel=''){
		$hp = array('CE'=>2, 'SE'=>3, 'EE'=>4, 'SDO'=>5);
		$this->db->order_by('OFFICE_NAME', 'ASC');
		$recs = $this->db->get_where('__offices', array('HOLDING_PERSON'=>$hp[$designation]));
		$opt = '';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$opt .= '<option value="'.$rec->OFFICE_ID.'" '.
					(($rec->OFFICE_ID==$sel) ? 'selected="selected"':'').
					' title="'.$rec->OFFICE_NAME_HINDI.'">'.$rec->OFFICE_NAME.
					'</option>';
			}
			$recs->free_result();
		}
		return $opt;
	}
	//Types
	protected function getProjectTypeList($sel=0){
		$this->db->order_by('PROJECT_TYPE', 'ASC');
		$recs = $this->db->get('__project_types');
		$opt = '';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$opt .= '<option value="'.$rec->PROJECT_TYPE_ID.'" '.
					(($rec->PROJECT_TYPE_ID==$sel) ? 'selected="selected"':'').' >'.
					$rec->PROJECT_TYPE.'('.$rec->PROJECT_TYPE_HINDI.
					')</option>';
			}
			 $recs->free_result();
		}
		return $opt;
	}
	protected function getProjectSubType($id){
		$recs = $this->db->get_where(
			'__project_sub_types', 
			array('PROJECT_SUB_TYPE_ID'=>$id)
		);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			 $recs->free_result();
			return $rec->PROJECT_SUB_TYPE.' ( '.$rec->PROJECT_SUB_TYPE_HINDI.' )';
		}
		return '';
	}
	protected function getProjectSubTypeList($type=0, $sel=0){
		$this->db->order_by('PROJECT_SUB_TYPE', 'ASC');
		if($type!=0){
			$recs = $this->db->get_where('__project_sub_types', array('PROJECT_TYPE_ID'=>$type));
		}else{
			$recs = $this->db->get('__project_sub_types');
		}
		$opt = '';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$opt .= '<option value="'.$rec->PROJECT_SUB_TYPE_ID.'" '.
					(($rec->PROJECT_SUB_TYPE_ID==$sel) ? 'selected="selected"':'').' >'.
					$rec->PROJECT_SUB_TYPE.'('.$rec->PROJECT_SUB_TYPE_HINDI.
					')</option>';
			}
			 $recs->free_result();
		}
		return $opt;
	}
	protected function getMajorProjectList($sel=0){
		$this->db->order_by('PROJECT_NAME', 'ASC');
		$recs = $this->db->get('pmon__m_major');
		$opt = '';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$opt .= '<option value="'.$rec->ID.'" '.
					(($rec->ID==$sel) ? 'selected="selected"':'').' >'.
					$rec->PROJECT_NAME.' ('.$rec->PROJECT_NAME_HINDI.
					')</option>';
			}
			 $recs->free_result();
		}
		return $opt;
	}
	/*
	 * @todo:Delete below functions
	 */
	/*protected function getMajorProjectName($sel=0){
		$this->db->order_by('PROJECT_NAME', 'ASC');
		$recs = $this->db->get_where('pmon__m_major', array('ID'=>$sel));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->PROJECT_NAME.' ('.$rec->PROJECT_NAME_HINDI.')';
		}
		return '';
	}
	protected function getMajorProjectId($promonid=0){
		//$this->db->order_by('PROJECT_NAME', 'ASC');
		$recs = $this->db->get_where('pmon__m_major_children', array('PROJECT_ID'=>$promonid));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->MAJOR_PROJECT_ID;
		}
		return 0;
	}*/
	public function showAAData(){
		$id = $this->input->post('id');
		echo json_encode($this->getAAData($id));
		//$this->load->view('pmaj/aa_data_view', $data);
	}
	protected function getAAData($id){
		$aaDataF = array(
			'ID', 'AA_NO', 'AA_DATE', 'AA_AMOUNT', 'AA_AUTHORITY_ID', 
			'RAA_NO', 'RAA_DATE', 'RAA_AMOUNT', 'RAA_AUTHORITY_ID'
		);
		$aaData = array();
		for($i=0;$i<count($aaData);$i++){
			$aaData [ $aaData[$i] ] = '';
		}
		$recs = $this->db->get_where('pmon__m_major', array('ID'=>$id));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0;$i<count($aaDataF);$i++){
				if($aaDataF[$i]=='AA_DATE' || $aaDataF[$i]=='RAA_DATE'){
					$aaData [ $aaDataF[$i] ] = myDateFormat( $rec->{$aaDataF[$i]} );
				}else{
					$aaData [ $aaDataF[$i] ] = $rec->{$aaDataF[$i]};
				}
			}
			$aaData['AUTHORITY_NAME'] = $this->getAuthorityName($aaData['AA_AUTHORITY_ID']);
			$aaData['RAUTHORITY_NAME'] = $this->getAuthorityName($aaData['RAA_AUTHORITY_ID']);
		}else{
			$aaData['AUTHORITY_NAME'] ='';
			$aaData['RAUTHORITY_NAME'] ='';
		}
		//showArrayValues($aaData);
		return $aaData;
	}

	protected function getFinancialYear($id=0, $restrictFY=false){
		$this->db->order_by('SESSION_START_YEAR', 'ASC');
		$recs = $this->db->where('SESSION_ID >=', PMON_MI_START_SESSION_ID);
		$recs = $this->db->where('SESSION_ID <=', $this->getSessionIdByDate());
		$recs = $this->db->get('__sessions');
		$dd = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				//if($restrictFY && $rec->SESSION_ID<PMON_MI_START_SESSION_ID) continue;
				array_push($dd, 
					'<option value="'.$rec->SESSION_ID.'" '.
					(($rec->SESSION_ID==$id) ? 'selected="selected"':'').'>'.
					$rec->SESSION_START_YEAR.' - '.$rec->SESSION_END_YEAR.
					'</option>'
				);
			}
			$recs->free_result();
		}
		return $dd;
	}
	//District setting
	protected function getDistricts($DistID=0){
		if(!is_array($DistID)) $DistID = array($DistID);
		if ( count($DistID)==0) return '';
		$this->db->order_by('DISTRICT_NAME', 'ASC');
		$this->db->where_in('DISTRICT_ID', $DistID);
		$recs = $this->db->get('__districts');
		$vlist = array();
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($vlist, $rec->DISTRICT_NAME);
			}
			$recs->free_result();
		}
		return implode(', ', $vlist);
	}
	

    //not using this function
	protected function getDistrictBenefitedIDsPromon($projectId){
        if(!$projectId) return array();
        $arrIDs = array();
        $recs = $this->db->get_where('__projects_district_served', array('PROJECT_ID'=>$projectId));
        //array_push($vlist, '<option value="0">Select District</option>');
        if($recs && $recs->num_rows()){
            foreach($recs->result() as $rec){
                array_push($arrIDs, $rec->DISTRICT_ID);
            }
			$recs->free_result();
        }
        return $arrIDs;
    }


	public function getBlockOptionsByDistrict(){
        $dist_id = $this->input->post('dist_id');
        $this->load->model('pmon_dep/dep_mi__m_project_setup','mi__m_project_setup');
        echo $this->mi__m_project_setup->getBlockOptions($dist_id);
	}
	//new method
	protected function getBlockIdsBenefited( $projectId){
		if( !$projectId) return '';
		$blocks = array();
		$recs = $this->db->get_where('__projects_block_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
			$recs->free_result();
		}
		return $blocks;
	}

	public function getTehsilOptionsByDistrict(){
		 $dist_id = $this->input->post('dist_id');
		 echo $this->getTehsilOptions($dist_id);
	}
	public function getTehsilBenefited($dist, $projectId){
		if(count($dist)==0) return '';
		if( !$projectId) return '';
		$blocks = array();
		$recs = $this->db->get_where('__projects_block_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
			$recs->free_result();
		}
		return $this->getBlockOptions($dist, $blocks);
	}

	protected function getBenefitedAssemblyIDs($projectId){
		$ids = array();
		if($projectId){
			$recs = $this->db->get_where('__projects_assembly_const_served', array('PROJECT_ID'=>$projectId));
			//array_push($vlist, '<option value="0">Select District</option>');
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					array_push($ids, $rec->ASSEMBLY_ID);
				}
				$recs->free_result();
			}
		}
		return $ids;
	}

	//OTHER METHODS
	//FIELDS
	protected function getFields($table){
		$strSQL = 'SHOW COLUMNS FROM '.$table;
		$recs = $this->db->query($strSQL);
		$arrNames = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec)
				array_push($arrNames, $rec->Field);
			$recs->free_result();
		}
		return $arrNames;
	}
	protected function getAchivementFields(){
		return array(
			'SESSION_ID', 'PROJECT_SETUP_ID',
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA', 'L_EARTHWORK',  'C_MASONRY',
			'C_PIPEWORK', 'C_DRIP_PIPE', 'C_WATERPUMP', 'K_CONTROL_ROOMS','KHARIF','RABI', 'SUBMISSION_DATE');
		// 'IP_TOTAL',
	}
	protected function getTargetDateFields(){
		return array(
			'TARGET_DATE_ID', 'PROJECT_ID', 'SESSION_ID',
			'LA_TARGET_DATE', 'SPILLWAY_TARGET_DATE', 
			'FLANKS_TARGET_DATE', 'SLUICES_TARGET_DATE', 
			'NALLA_CLOSURE_TARGET_DATE', 'CANAL_EARTHWORK_TARGET_DATE', 
			'CANAL_STRUCTURES_TARGET_DATE',
			'CANAL_LINING_TARGET_DATE', 'TARGET_SUBMISSION_DATE'
		);
	}
	protected function getEstimationFields(){
		return array(
			'ESTIMATED_QTY_ID', 'PROJECT_SETUP_ID', 'RAA_ID', 'SESSION_ID',
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA', 'L_EARTHWORK',  'C_MASONRY',
			'C_PIPEWORK', 'C_DRIP_PIPE', 'C_WATERPUMP', 'K_CONTROL_ROOMS'
        ); //,'KHARIF', 'RABI', 'IP_TOTAL'
	}

	protected function getEstimationStatusFields(){
		return array(
			'PROJECT_SETUP_ID', 'LA_NA', 'FA_NA',
			'L_EARTHWORK_NA', 'C_MASONRY_NA',
			'C_PIPEWORK_NA', 'C_DRIP_PIPE_NA',  'C_WATERPUMP_NA',
			'K_CONTROL_ROOMS_NA', 'IP_TOTAL_NA'
		);
	}

	protected function getProjecMasterFields(){
		return array("PROJECT_ID","PROJECT_CODE","PROJECT_TYPE_ID", "PROJECT_SUB_TYPE_ID",
					"PROJECT_NAME","PROJECT_NAME_HINDI", "NO_VILLAGES_COVERED",
					"LONGITUDE_D", "LONGITUDE_M", "LONGITUDE_S",
					"LATITUDE_D", "LATITUDE_M", "LATITUDE_S", 
					"PROJECT_START_YEAR", "PROJECT_START_MONTH",
					"DESIGNED_IRRIGATION", "PROJECT_STATUS", "DISTRICT_ID", 
					"CE_ID", "DIVISION_ID", "LIVE_STORAGE", "LOCK");
	}	


	protected function showTime($title=''){
		$this->endTime = microtime();
		$t = ($this->endTime - $this->startTime) * 1000;
		$t = sprintf(" %07.2f : %-20s ", $t,  $title );
		array_push($this->message, getMyArray(true, $t));
		$this->startTime = $this->endTime;
	}

	//ok
	protected function setLock(){
	    //echo "in setLOCK";
		$lockTable = 'mi__t_locks';
		$arrWhere = array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID);
		$goAhead = $isExists = false;
		$data = array('SETUP_LOCK'=>1);
		$recs = $this->db->get_where($lockTable, $arrWhere);
		//echo  $this->db->last_query();exit;
		if($recs && $recs->num_rows()){
			$isExists = TRUE;
			$recs->free_result();
			@$this->db->update($lockTable, $data, $arrWhere);
		}else{
			$data['PROJECT_SETUP_ID'] = $this->PROJECT_SETUP_ID;
			@$this->db->insert($lockTable, $data);
		}
		if( $this->db->affected_rows() ){
			$data = array(
				'PROJECT_SETUP_ID'=>$this->PROJECT_SETUP_ID,
				'LOCK_DATE_TIME'=>date("Y-m-d H:i:s"), 
				'LOCK_MODE'=>1, 
				'LOCK_TYPE'=>1, 
				'USER_ID'=>getSessionDataByKey('USER_ID'), 
				'DESCRIPTION'=>'Project Setup Locked'
			);
			@$this->db->insert('mi__t_lock_logs', $data);
			return TRUE;
		}
		return FALSE;
	}
	protected function getLockStatus($lockMode){
		$lockTable = 'mi__t_locks';
		$arrWhere = array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID);
		$status = false;
		$recs = $this->db->get_where($lockTable, $arrWhere);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			switch($lockMode){
				case 1: $status = (($rec->SETUP_LOCK==1)? true:false); break;
				case 2: $status = (($rec->SETUP_LOCK==1)? true:false); break;
			}
		}
		return $status;
	}
	public function getModuleID($key){
		//get module id from module key
		$recs = $this->db->get_where('__modules', array('MODULE_KEY'=>$key));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->MODULE_ID;
		}
		return 0;
	}	
}