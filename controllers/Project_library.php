<?php
class Project_library extends MX_Controller{
	protected $message, $PROJECT_SETUP_ID, $PROJECT_ID, $RAA_ID,$tblOffice, $tblProjectTypes,$tblProjectSubType,$tblPmonMajor,$tblPmonMajorChild,$tblPmonAuth,$tblPmonFundAssi,$tblSession,$tblPmonAchivement,$tblEstiQty,$tblEstiStatus,$tblPmonTargetCompletion,$tblPmonLocks,$tblPmonLocksLog,$tblPmonBlockAchivement,$tblPmonRaa,$tblPmonBlockEstiIP;

	//dep_pmon__t_block_estimated_ip
	function __construct(){
		parent::__construct();
		$this->PROJECT_ID = 0;
		$this->PROJECT_SETUP_ID = 0;
		$this->RAA_ID = 0;
		$this->RESULT = false;
		$this->message = array();
		$this->tblOffice="__offices";
		//$this->tblProjectTypes="deposit__project_types";
		//$this->tblProjectSubType="deposit__project_sub_types";

		$this->tblProjectTypes="__project_types";
		$this->tblProjectSubType="__project_sub_types";
		

		$this->tblPmonMajor="dep_pmon__m_major";
		$this->tblPmonMajorChild="dep_pmon__m_major_children";
		$this->tblPmonAuth="pmon__m_authority";
		$this->tblPmonFundAssi="dep_pmon__m_fund_assi";
		$this->tblSession="__sessions";
		$this->tblPmonAchivement="dep_pmon__t_achievements";
		$this->tblEstiQty="dep_pmon__t_estimated_qty";
		$this->tblEstiStatus="dep_pmon__t_estimated_status";
		$this->tblPmonTargetCompletion="dep_pmon__t_target_date_completion";
		$this->tblPmonLocks="dep_pmon__t_locks";
		$this->tblDepMiPmonLocks="dep_mi__t_locks";
		$this->tblPmonLocksLog="dep_pmon__t_lock_logs";
		$this->tblPmonBlockAchivement="dep_pmon__t_block_achievement_ip";
		$this->tblPmonRaa="dep_pmon__t_raa_project";
		$this->tblPmonBlockEstiIP="dep_pmon__t_block_estimated_ip";
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
	public function getOfficeEEname($EE_ID){		
		if(!$EE_ID) return '';
		$recs = $this->db->get_where($this->tblOffice, array('OFFICE_ID'=>$EE_ID));
		$opt = '';
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->OFFICE_NAME;
		}
		return '';
	}
	public function getOfficeList($designation, $sel=''){
		$hp = array('CE'=>2, 'SE'=>3, 'EE'=>4, 'SDO'=>5);
		$this->db->order_by('OFFICE_NAME', 'ASC');
		$recs = $this->db->get_where($this->tblOffice, array('HOLDING_PERSON'=>$hp[$designation]));
		$opt = '';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$opt .= '<option value="'.$rec->OFFICE_ID.'" '.
					(($rec->OFFICE_ID==$sel) ? 'selected="selected"':'').
					' title="'.$rec->OFFICE_NAME_HINDI.'">'.$rec->OFFICE_NAME.
					'</option>';
			}
		}
		return $opt;
	}
	//Types
	protected function getProjectTypeList($sel=0){
		$this->db->order_by('PROJECT_TYPE', 'ASC');
		$recs = $this->db->get($this->tblProjectTypes);
		$opt = '';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$opt .= '<option value="'.$rec->PROJECT_TYPE_ID.'" '.
					(($rec->PROJECT_TYPE_ID==$sel) ? 'selected="selected"':'').' >'.
					$rec->PROJECT_TYPE.'('.$rec->PROJECT_TYPE_HINDI.
					')</option>';
			}
		}
		return $opt;
	}
	protected function getProjectSubType($id){
		$recs = $this->db->get_where(
			$this->tblProjectSubType, 
			array('PROJECT_SUB_TYPE_ID'=>$id)
		);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->PROJECT_SUB_TYPE.' ( '.$rec->PROJECT_SUB_TYPE_HINDI.' )';
		}
		return '';
	}
	protected function getProjectSubTypeList($type=0, $sel=0){
		$this->db->order_by('PROJECT_SUB_TYPE', 'ASC');
		
		$currentOfficeId= $this->session->userData('CURRENT_OFFICE_ID');

		/*if($currentOfficeId==31 || $currentOfficeId==69){

		}else{
            $this->db->where_not_in('PROJECT_SUB_TYPE_ID', array('5','25'));
        }*/

		if($type!=0){
			$recs = $this->db->get_where($this->tblProjectSubType, array('PROJECT_TYPE_ID'=>$type));
		}else{
			$recs = $this->db->get($this->tblProjectSubType);
		}

		//echo $this->db->last_query();

		$opt = '';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$opt .= '<option value="'.$rec->PROJECT_SUB_TYPE_ID.'" '.
					(($rec->PROJECT_SUB_TYPE_ID==$sel) ? 'selected="selected"':'').' >'.
					$rec->PROJECT_SUB_TYPE.'('.$rec->PROJECT_SUB_TYPE_HINDI.
					')</option>';
			}
		}
		return $opt;
	}
	protected function getMajorProjectList($sel=0){
		$this->db->order_by('PROJECT_NAME', 'ASC');
		$recs = $this->db->get($this->tblPmonMajor);
		$opt = '';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$opt .= '<option value="'.$rec->ID.'" '.
					(($rec->ID==$sel) ? 'selected="selected"':'').' >'.
					$rec->PROJECT_NAME.' ('.$rec->PROJECT_NAME_HINDI.
					')</option>';
			}
		}
		return $opt;
	}
	protected function getMajorProjectName($sel=0){
		$this->db->order_by('PROJECT_NAME', 'ASC');
		$recs = $this->db->get_where($this->tblPmonMajor, array('ID'=>$sel));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->PROJECT_NAME.' ('.$rec->PROJECT_NAME_HINDI.')';
		}
		return '';
	}
	protected function getMajorProjectId($promonid=0){
		//$this->db->order_by('PROJECT_NAME', 'ASC');
		$recs = $this->db->get_where($this->tblPmonMajorChild, array('PROJECT_ID'=>$promonid));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->MAJOR_PROJECT_ID;
		}
		return 0;
	}
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
		$recs = $this->db->get_where($this->tblPmonMajor, array('ID'=>$id));
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
	/** getAuthority */
	protected function getAuthority($AuthID=0){
		$this->db->order_by('AUTHORITY_ID', 'ASC');
		$recs = $this->db->get($this->tblPmonAuth);
		$dd_auth = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push(						
					$dd_auth, 
					'<option value="'.$rec->AUTHORITY_ID.'" '.
					(($rec->AUTHORITY_ID==$AuthID) ? 'selected="selected"':'').'>'.
					$rec->AUTHORITY_NAME.'</option>'
				);
			}
		}
		return $dd_auth;
	}
	protected function getAuthorityName($AuthID=0){
		if($AuthID==0) return '';
		$recs = $this->db->get_where($this->tblPmonAuth, array('AUTHORITY_ID'=>$AuthID));
		$dd_auth = array();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->AUTHORITY_NAME;
		}
		return '';
	}
	protected function getFundAssistance($fundID=0){
		$this->db->order_by('FUND_ASSI_ID', 'DESC');
		$recs = $this->db->get($this->tblPmonFundAssi);
		$dd_fund_assi = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($dd_fund_assi, 
					'<option value="'.$rec->FUND_ASSI_ID.'" '.
					(($rec->FUND_ASSI_ID==$fundID) ? 'selected="selected"':'').'>'.
					$rec->FUND_ASSI_NAME.'</option>'
				);
			}
		}
		return $dd_fund_assi;
	}
	/** */
	protected function getWorkStatus($opt=0, $na){
		$view = array();
		$status_options = array('Please Select', 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed');
		for($i=0; $i<count($status_options);$i++){
			$sel = "";
			if($na==1){
				if($i==1) 
					$sel = 'selected="selected"';
			}else{
				if($opt==$i) 
					$sel = 'selected="selected"';
			}
			array_push($view, 
				'<option value="'.$i.'" '.$sel.'> '.
				$status_options[$i].
				' </option>'
			);
		}
		return implode('', $view);
	}
	protected function getWorkStatusAsString($opt=0){
		$view = array();
		if($opt=='')$opt=0;
		$status_options = array('', 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 'Dropped');
		return $status_options[$opt];
	}
	/** getFinancialYear */
	protected function getSessionOptions($selId=0, $minId=0, $maxId=0){
		$minId = (($minId==0)? PMON_DEP_START_SESSION_ID:$minId);//
		$maxId = (($maxId==0)? $this->getSessionIdByDate():$maxId);
		$this->db->order_by('SESSION_START_YEAR', 'ASC');
		$recs = $this->db->where('SESSION_ID >=', $minId);
		$recs = $this->db->where('SESSION_ID <=', $maxId);
		$recs = $this->db->get($this->tblSession);
		$dd = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				//if($restrictFY && $rec->SESSION_ID<PMON_DEP_START_SESSION_ID) continue;
				array_push($dd, 
					'<option value="'.$rec->SESSION_ID.'" '.
					(($rec->SESSION_ID==$selId) ? 'selected="selected"':'').'>'.
					$rec->SESSION_START_YEAR.' - '.$rec->SESSION_END_YEAR.
					'</option>'
				);
			}
		}
		return $dd;
	}
	protected function getFinancialYear($id=0, $restrictFY=false){
		$this->db->order_by('SESSION_START_YEAR', 'ASC');
		$recs = $this->db->where('SESSION_ID >=', PMON_DEP_START_SESSION_ID);
		$recs = $this->db->where('SESSION_ID <=', $this->getSessionIdByDate());
		$recs = $this->db->get($this->tblSession);
		$dd = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				//if($restrictFY && $rec->SESSION_ID<PMON_DEP_START_SESSION_ID) continue;
				array_push($dd, 
					'<option value="'.$rec->SESSION_ID.'" '.
					(($rec->SESSION_ID==$id) ? 'selected="selected"':'').'>'.
					$rec->SESSION_START_YEAR.' - '.$rec->SESSION_END_YEAR.
					'</option>'
				);
			}
		}
		return $dd;
	}
	protected function getSessionIdByDate($mdate=''){
		if($mdate=='') $mdate = date("Y-m-d");
		$strSQL = "SELECT SESSION_ID FROM ".$this->tblSession."
			WHERE START_DATE<='".$mdate."'
				AND END_DATE>='".$mdate."'";
		$recs = $this->db->query($strSQL);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_ID;
		}
		return 0;
	}
	protected function getFinancialYearIDFromDate($mdate){
		$strSQL = "SELECT SESSION_ID FROM ".$this->tblSession."
			WHERE START_DATE<='".$mdate."'
				AND END_DATE>='".$mdate."'";
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_ID;
		}
		return 0;
	}
	protected function getFinancialMonth($month){
		if ($month>=1 and $month<=3){
			return ($month+10);
		}else{
			return ($month-3);
		}
	}
	protected function getFinancialYearIDByMonthYear($month, $year){
		if($month==0) 	$month = date('m');
		if($year==0)	$year =  date('Y');
		/*$financialMonth = $this->getFinancialMonth($month);
		$startYear = $year;
		$endYear = $year;
		if($financialMonth<10){
			$endYear = $year+1;
		}else{
			$startYear = $year-1;
		}*/
		$dt = sprintf("%4d-%2d-01", $year, $month);
		return $this->getFinancialYearIDFromDate($dt);
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
		}
		return implode(', ', $vlist);
	}
	protected function getDistrictOptions($DistID=0){
		$this->db->order_by('DISTRICT_NAME', 'ASC');
		$recs = $this->db->get_where('__districts', array('STATE_ID'=>7));
		if(!is_array($DistID)) $DistID = array($DistID);

		$vlist = array();
		array_push($vlist, '<option value="0">Select District</option>');
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($vlist, 
					'<option value="'.$rec->DISTRICT_ID.'" '.
					((in_array($rec->DISTRICT_ID, $DistID)) ? 'selected="selected"':'').'>'.
					$rec->DISTRICT_NAME.'('.$rec->DISTRICT_NAME_HINDI.
					')</option>'
				);
			}
		}
		return implode('', $vlist);
	}
	protected function getDistrictBenefitedIDs($projectId){
		if(!$projectId) return array();
		$arrIDs = array();
		$recs = $this->db->get_where('deposit__projects_district_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($arrIDs, $rec->DISTRICT_ID);
			}
		}
		return $arrIDs;
	}
	public function getDistrictBenefited($projectId){
		if(!$projectId) return '';
		return $this->getDistrictOptions($this->getDistrictBenefitedIDs($projectId));
	}
	//Block  setting
	protected function getBlockString($b_id=array()){
		if( !is_array($b_id) ) $b_id = array($b_id);
		if ( count($b_id)==0) return '';
		//showArrayValues($dist_id);
		$this->db->order_by('DISTRICT_NAME', 'ASC')->order_by('BLOCK_NAME', 'ASC');
		$this->db->where_in ('BLOCK_ID', $b_id);
		$recs = $this->db->get('__v_blocks_districts');
		$bOptions = array();
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($bOptions, $rec->BLOCK_NAME.'('.$rec->BLOCK_NAME_HINDI.')');
			}
		}
		return implode(', ', $bOptions);
	}
	public function getBlocks($projectId){
		if( !$projectId) return '';
		$view = '';
		$blocks = array();
		$recs = $this->db->get_where('deposit__projects_block_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
		}
		return $this->getBlockString($blocks);
	}
	protected function getBlockOptions($dist_id=array(), $b_id=array()){
		//if not array then convert it to array
		//echo $dist_id;
		if( !is_array($dist_id) ) $dist_id = array($dist_id);
		if( !is_array($b_id) ) $b_id = array($b_id);
		//showArrayValues($dist_id);
		$bOptions = array();
		array_push($bOptions, '<option value="">Select Block</option>');
		$this->db->order_by('DISTRICT_NAME', 'ASC')->order_by('BLOCK_NAME', 'ASC');
		$this->db->where_in ('DISTRICT_ID', $dist_id);
		$did = 0;
		$i=0;
		$recs = $this->db->get('__v_blocks_districts');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				if($did!=$rec->DISTRICT_ID){
					if($did!=0) array_push($bOptions, '</optgroup>');
					array_push($bOptions, '<optgroup label="'.$rec->DISTRICT_NAME.'">'."\n");
					$did = $rec->DISTRICT_ID;
				}
				$i++;
				array_push($bOptions, 
					'<option value="'.$rec->BLOCK_ID.'" '.
					((in_array($rec->BLOCK_ID, $b_id)) ? 'selected="selected"':'').'>'.
					$rec->BLOCK_NAME.'('.$rec->BLOCK_NAME_HINDI.
					')</option>'
				);
			}
		}
		if($i!=0) array_push($bOptions, '</optgroup>');
		//array_push($bOptions, '</pre>');
		//echo $i. implode('', $bOptions);
		return implode('', $bOptions);
	}
	public function getBlockOptionsByDistrict(){
		 $dist_id = $this->input->post('dist_id');
		 echo $this->getBlockOptions($dist_id);
	}
	//new method
	protected function getBlockIdsBenefited( $projectId){
		if( !$projectId) return '';
		$blocks = array();
		$recs = $this->db->get_where('deposit__projects_block_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
		}
		return $blocks;
	}
	public function getBlocksBenefited($dist, $projectId){
		if(count($dist)==0) return '';
		if( !$projectId) return '';
		$blocks = array();
		$recs = $this->db->get_where('deposit__projects_block_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
		}
		return $this->getBlockOptions($dist, $blocks);
	}
	//Tehsil  setting
	protected function getTehsilOptions($dist_id=array(), $b_id=array()){
		//if not array then convert it to array
		//echo $dist_id;
		if( !is_array($dist_id) ) $dist_id = array($dist_id);
		if( !is_array($b_id) ) $b_id = array($b_id);
		$bOptions = array();
		//array_push($vlist, '<option value="0">Select District</option>');
		//array_push($bOptions, '<pre>');
		$did = 0;
		$i=0;
		array_push($bOptions, '<option value="">Select Tehsil</option>');
		//showArrayValues($dist_id);
		$this->db->order_by('DISTRICT_NAME', 'ASC')->order_by('TEHSIL_NAME', 'ASC');
		$this->db->where_in ('DISTRICT_ID', $dist_id);
		$recs = $this->db->get('__v_tehsil_district');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				if($did!=$rec->DISTRICT_ID){
					if($did!=0) array_push($bOptions, '</optgroup>');
					array_push($bOptions, '<optgroup label="'.$rec->DISTRICT_NAME.'">'."\n");
					$did = $rec->DISTRICT_ID;
				}
				$i++;
				array_push($bOptions, 
					'<option value="'.$rec->TEHSIL_ID.'" '.
					((in_array($rec->TEHSIL_ID, $b_id)) ? 'selected="selected"':'').'>'.
					$rec->TEHSIL_NAME.'('.$rec->TEHSIL_NAME_HINDI.
					')</option>'
				);
			}
		}
		if($i!=0) array_push($bOptions, '</optgroup>');
		//array_push($bOptions, '</pre>');
		//echo $i. implode('', $bOptions);
		return implode('', $bOptions);
	}
	public function getTehsilOptionsByDistrict(){
		 $dist_id = $this->input->post('dist_id');
		 echo $this->getTehsilOptions($dist_id);
	}
	public function getTehsilBenefited($dist, $projectId){
		if(count($dist)==0) return '';
		if( !$projectId) return '';
		$blocks = array();
		$recs = $this->db->get_where('deposit__projects_block_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
		}
		return $this->getBlockOptions($dist, $blocks);
	}
	//Assembly setting
	public function assembly_const_benefit(){
		$all_assembly = $this->getAssemblyOptions(0); 
		echo '<select name="ASSEMBLY_BENEFITED[]" style="width:200px; border:1px #333 solid;" class="required">'.
				'<option value="">Assembly Benefited</option>'.implode('', $all_assembly).'</select>'.
				'<input type="hidden" name="ASSEMBLY_RECORD_ID[]" id="ASSEMBLY_RECORD_ID[]" value="0">';
		//echo $view;
	}
	protected function getAssemblys($a_id=array()){
		if( !is_array($a_id) ) $a_id = array($a_id);
		if ( count($a_id)==0) return '';
		$vlist = array();
		$this->db->order_by('ASSEMBLY_NAME', 'ASC');
		$this->db->where_in('ASSEMBLY_ID', $a_id);
		$recs = $this->db->get('__m_assembly_constituency');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($vlist, $rec->ASSEMBLY_NAME.'('.$rec->ASSEMBLY_NAME_HINDI.')');
			}
		}
		return implode(', ', $vlist);
	}
	protected function getBenefitedAssemblyIDs($projectId){
		$ids = array();
		if($projectId){
			$recs = $this->db->get_where('deposit__projects_assembly_const_served', array('PROJECT_ID'=>$projectId));
			//array_push($vlist, '<option value="0">Select District</option>');
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					array_push($ids, $rec->ASSEMBLY_ID);
				}
			}
		}
		return $ids;
	}
	protected function getAssemblyOptions($a_id=array()){
		if( !is_array($a_id) ) $a_id = array($a_id);
		$vlist = array();
		$this->db->order_by('ASSEMBLY_NAME', 'ASC');
		$recs = $this->db->get('__m_assembly_constituency');
		foreach($recs->result() as $rec){
			array_push($vlist, 
				'<option value="'.$rec->ASSEMBLY_ID.'" '.
				(( in_array($rec->ASSEMBLY_ID, $a_id))?'selected="selected"':'').'>'.
				$rec->ASSEMBLY_NAME.'('.$rec->ASSEMBLY_NAME_HINDI.') - '.$rec->ASSEMBLY_ID.
				'</option>'
			);
		}
		return implode('', $vlist);
	}
	protected function getBenefitedAssembly($projectId){
		$ids = array();
		if($projectId){
			$recs = $this->db->get_where('deposit__projects_assembly_const_served', array('PROJECT_ID'=>$projectId));
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					array_push($ids, $rec->ASSEMBLY_ID);
				}
			}
		}
		return $this->getAssemblyOptions($ids);
	}
	//Village setting/
	protected function getVillages($projectId){
		$ids = array();
		if($projectId){
			//$recs = $this->db->get_where('__v_projects_village_served_tehsil_district',	array('PROJECT_ID'=>$projectId));
			$recs = $this->db->select('d.PROJECT_ID AS PROJECT_ID,d.RECORD_ID AS RECORD_ID,d.VILLAGE_ID AS VILLAGE_ID,__villages.VILLAGE_NAME AS VILLAGE_NAME,__villages.VILLAGE_NAME_HINDI AS VILLAGE_NAME_HINDI,__districts.DISTRICT_ID AS DISTRICT_ID,__tehsils.TEHSIL_NAME AS TEHSIL_NAME,__tehsils.TEHSIL_NAME_HINDI AS TEHSIL_NAME_HINDI,__villages.TEHSIL_ID AS TEHSIL_ID,__districts.DISTRICT_NAME AS DISTRICT_NAME,__districts.DISTRICT_NAME_HINDI AS DISTRICT_NAME_HINDI')
					->from('deposit__projects_villages_served d')
					->join('__villages','d.VILLAGE_ID=__villages.VILLAGE_ID')
					->join('__tehsils','__villages.TEHSIL_ID = __tehsils.TEHSIL_ID')
					->join('__districts','__tehsils.DISTRICT_ID = __districts.DISTRICT_ID')
					->where(array('PROJECT_ID'=>$projectId))
					->get();
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					array_push($ids, $rec->VILLAGE_ID);
				}
			}
		}
		return $this->getVillageString($ids);
	}
	protected function getVillageString($v_id=array()){
		if( !is_array($v_id) ) $v_id = array($v_id);
		if ( count($v_id)==0) return '';
			
		//$this->db->where_in('VILLAGE_ID', $v_id);//->order_by('VILLAGE_NAME');
		//$recs = $this->db->get('__v_village_tehsil_district');
		$recs = $this->db->select('__villages.VILLAGE_ID AS VILLAGE_ID,__villages.VILLAGE_NAME AS VILLAGE_NAME,__villages.VILLAGE_NAME_HINDI AS VILLAGE_NAME_HINDI,__tehsils.TEHSIL_NAME_HINDI AS TEHSIL_NAME_HINDI,__tehsils.TEHSIL_NAME AS TEHSIL_NAME,__districts.DISTRICT_NAME AS DISTRICT_NAME,__districts.DISTRICT_NAME_HINDI AS DISTRICT_NAME_HINDI,__districts.DISTRICT_ID AS DISTRICT_ID,__tehsils.TEHSIL_ID AS TEHSIL_ID')
					->from('__villages')
					->join('__tehsils','__villages.TEHSIL_ID = __tehsils.TEHSIL_ID')
					->join('__districts','__tehsils.DISTRICT_ID = __districts.DISTRICT_ID')
					->where('__districts.STATE_ID', 7)
					->where_in('__villages.VILLAGE_ID', $v_id)
					->get();

		$vlist = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($vlist, $rec->VILLAGE_NAME. ' ('.$rec->TEHSIL_NAME.'[ '.$rec->DISTRICT_NAME.' ])');
			}
		}
		return implode(', &nbsp; &nbsp; ', $vlist);
	}
	protected function getVillageOptions($v_id=array(), $districtIdS=array()){
		if(!is_array($districtIdS)) $districtIdS = array($districtIdS);
		if( !is_array($v_id) ) $v_id = array($v_id);
		if( count($districtIdS)==0 ) return '';
			
			//$this->db->where_in('DISTRICT_ID', $districtIdS);//->order_by('VILLAGE_NAME');
			//$recs = $this->db->get('__v_village_tehsil_district');
			$recs = $this->db->select('__villages.VILLAGE_ID AS VILLAGE_ID,__villages.VILLAGE_NAME AS VILLAGE_NAME,__villages.VILLAGE_NAME_HINDI AS VILLAGE_NAME_HINDI,__tehsils.TEHSIL_NAME_HINDI AS TEHSIL_NAME_HINDI,__tehsils.TEHSIL_NAME AS TEHSIL_NAME,__districts.DISTRICT_NAME AS DISTRICT_NAME,__districts.DISTRICT_NAME_HINDI AS DISTRICT_NAME_HINDI,__districts.DISTRICT_ID AS DISTRICT_ID,__tehsils.TEHSIL_ID AS TEHSIL_ID')
					->from('__villages')
					->join('__tehsils','__villages.TEHSIL_ID = __tehsils.TEHSIL_ID')
					->join('__districts','__tehsils.DISTRICT_ID = __districts.DISTRICT_ID')
					->where('__districts.STATE_ID', 7)
					->where_in('__districts.DISTRICT_ID', $districtIdS)
					->get();

		$vlist ='';// array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$vlist .= '<option value="'.$rec->VILLAGE_ID.'" '.
					(( in_array($rec->VILLAGE_ID, $v_id))?'selected="selected"':'').'>'.
					$rec->VILLAGE_NAME.' '.$rec->VILLAGE_NAME_HINDI.
					', '.$rec->TEHSIL_NAME_HINDI.', '.$rec->DISTRICT_NAME_HINDI.''.
					'</option>';
			}
		}
		return $vlist;
	}
	protected function getBenefitedVillages($projectId, $districtIdS=array()){
		if(!is_array($districtIdS)) $districtIdS = array($districtIdS);
		$ids = array();
		if($projectId){
			//$recs = $this->db->get_where('__v_projects_village_served_tehsil_district', array('PROJECT_ID'=>$projectId));
			$recs = $this->db->select('d.PROJECT_ID AS PROJECT_ID,d.RECORD_ID AS RECORD_ID,d.VILLAGE_ID AS VILLAGE_ID,__villages.VILLAGE_NAME AS VILLAGE_NAME,__villages.VILLAGE_NAME_HINDI AS VILLAGE_NAME_HINDI,__districts.DISTRICT_ID AS DISTRICT_ID,__tehsils.TEHSIL_NAME AS TEHSIL_NAME,__tehsils.TEHSIL_NAME_HINDI AS TEHSIL_NAME_HINDI,__villages.TEHSIL_ID AS TEHSIL_ID,__districts.DISTRICT_NAME AS DISTRICT_NAME,__districts.DISTRICT_NAME_HINDI AS DISTRICT_NAME_HINDI')
					->from('deposit__projects_villages_served d')
					->join('__villages','d.VILLAGE_ID=__villages.VILLAGE_ID')
					->join('__tehsils','__villages.TEHSIL_ID = __tehsils.TEHSIL_ID')
					->join('__districts','__tehsils.DISTRICT_ID = __districts.DISTRICT_ID')
					->where(array('PROJECT_ID'=>$projectId))
					->get();

			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					array_push($ids, $rec->VILLAGE_ID);
					if ( !in_array($rec->DISTRICT_ID, $districtIdS) ){
						array_push($districtIdS, $rec->DISTRICT_ID);
					}
				}
			}
		}
		return $this->getVillageOptions($ids, $districtIdS);
	}
	public function getVillagesByDistrictList($districtId, $villageId=array()){
		if ( !is_array($villageId) ) $villageId = array($villageId);
		$vlist = array();
		$this->db->order_by('VILLAGE_NAME');
		$this->db->where_in('DISTRICT_ID', $districtId);
		$recs = $this->db->get('__v_village_tehsil_district');
		$vlist = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($vlist, 
					'<option value="'.$rec->VILLAGE_ID.'" '.
					( ( in_array($rec->VILLAGE_ID, $villageId)) ?  'selected="selected"':'').'>'.
					$rec->VILLAGE_NAME.'('.$rec->VILLAGE_NAME_HINDI.') - ['.
					$rec->TEHSIL_NAME.'('.$rec->TEHSIL_NAME_HINDI.
					')]</option>'
				);
			}
		}
		return implode('', $vlist);
	}
	//OTHER METHODS
	protected function processDetailRecord($mode){
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		switch($mode){
			/** SAVE Project DISTRICT Benefited */
			case 'DISTRICT': 
				$districtBenefited = $this->input->post('DISTRICT_BENEFITED');	
				if(!is_array($districtBenefited)) $districtBenefited = array($districtBenefited);
				//showArrayValues($districtBenefited);
				$recs = array();
				$rid = array();
				$aid = array();
				//get Existing Record id 
				$arecs = $this->db->get_where('deposit__projects_district_served', $arrWhere);
				if($arecs && $arecs->num_rows()){
					foreach($arecs->result() as $rec){
						array_push($rid, $rec->RECORD_ID);
						array_push($aid, $rec->DISTRICT_ID);
					}
				}
				for($i=0; $i<count($districtBenefited); $i++){
					//search assembly in existing list
					$k = array_search($districtBenefited[$i], $aid);
					if ( is_integer($k) ){
						//found in array
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>$rid[$k],
								"DISTRICT_ID"=>$aid[$k]
							)
						);
					}else{
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>0,
								"DISTRICT_ID"=>$districtBenefited[$i]
							)
						);
					}
				}
				break;
			case 'BLOCKS':
				$blockBenefited = $this->input->post('BLOCKS_BENEFITED');
				if( ! is_array($blockBenefited)) $blockBenefited = array($blockBenefited);
				//showArrayValues($blockBenefited);
				$recs = array();
				$rid = array();
				$aid = array();
				//get Existing Record id 
				$arecs = $this->db->get_where('deposit__projects_block_served', $arrWhere);
				if($arecs && $arecs->num_rows()){
					foreach($arecs->result() as $rec){
						array_push($rid, $rec->RECORD_ID);
						array_push($aid, $rec->BLOCK_ID);
					}
				}
				for($i=0; $i<count($blockBenefited); $i++){
					//search assembly in existing list
					$k = array_search($blockBenefited[$i], $aid);
					if ( is_integer($k) ){
						//found in array
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>$rid[$k],
								"BLOCK_ID"=>$aid[$k]
							)
						);
					}else{
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>0,
								"BLOCK_ID"=>$blockBenefited[$i]
							)
						);
					}
				}
				break;
			case 'ASSEMBLY':
				$assemblyBenefited = $this->input->post('ASSEMBLY_BENEFITED');
				if( ! is_array($assemblyBenefited)) $assemblyBenefited = array($assemblyBenefited);
				//showArrayValues($assemblyBenefited);
				$recs = array();
				$rid = array();
				$aid = array();
				//get Existing Record id 
				$arecs = $this->db->get_where('deposit__projects_assembly_const_served', $arrWhere);
				if($arecs && $arecs->num_rows()){
					foreach($arecs->result() as $rec){
						array_push($rid, $rec->RECORD_ID);
						array_push($aid, $rec->ASSEMBLY_ID);
					}
				}
				for($i=0; $i<count($assemblyBenefited); $i++){
					//search assembly in existing list
					$k = array_search($assemblyBenefited[$i], $aid);
					if ( is_integer($k) ){
						//found in array
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>$rid[$k],
								"ASSEMBLY_ID"=>$aid[$k]
							)
						);
					}else{
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>0,
								"ASSEMBLY_ID"=>$assemblyBenefited[$i]
							)
						);
					}
				}
				break;
			case 'VILLAGE':
				$villagesBenefited = $this->input->post('VILLAGES_BENEFITED');
				if( ! is_array($villagesBenefited)) $villagesBenefited = array($villagesBenefited);
				//showArrayValues($blockBenefited);
				$recs = array();
				$rid = array();
				$aid = array();
				//get Existing Record id 
				$arecs = $this->db->get_where('deposit__projects_villages_served', $arrWhere);
				if($arecs && $arecs->num_rows()){
					foreach($arecs->result() as $rec){
						array_push($rid, $rec->RECORD_ID);
						array_push($aid, $rec->VILLAGE_ID);
					}
				}
				for($i=0; $i<count($villagesBenefited); $i++){
					//search assembly in existing list
					$k = array_search($villagesBenefited[$i], $aid);
					if ( is_integer($k) ){
						//found in array
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>$rid[$k],
								"VILLAGE_ID"=>$aid[$k]
							)
						);
					}else{
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>0,
								"VILLAGE_ID"=>$villagesBenefited[$i]
							)
						);
					}
				}
				break;
			case 'OFFICE' :
				$arrEE = array();
				$EE_ID = $this->input->post('OFFICE_EE_ID');
				if($this->session->userdata('HOLDING_PERSON')==4){
					$SDOOfficeId = $this->input->post('OFFICE_SDO_ID');
				}
				if( ! is_array($SDOOfficeId)) $SDOOfficeId = array($SDOOfficeId);
				
				$this->db->select('OFFICE_ID, PARENT_OFFICE_ID');
				$this->db->where_in('OFFICE_ID', $SDOOfficeId);
				$orecs = $this->db->get('__offices');
				//echo $this->db->last_query();
				if($orecs && $orecs->num_rows()){
					foreach($orecs->result() as $orec)
						$arrEE[$orec->OFFICE_ID] = $orec->PARENT_OFFICE_ID;
				}
				//showArrayValues($assemblyBenefited);
				//get Existing Record id 
				$recs = array();
				$arecs = $this->db->get_where('deposit__projects_office', $arrWhere);
				$rid = array();
				$aid = array();
				$eid = array();
				if($arecs && $arecs->num_rows()){
					foreach($arecs->result() as $rec){
						array_push($rid, $rec->RECORD_ID);
						array_push($aid, $rec->OFFICE_ID);
						array_push($eid, $rec->EE_ID);
					}
				}
				for($i=0; $i<count($SDOOfficeId); $i++){
					//search assembly in existing list
					$k = array_search($SDOOfficeId[$i], $aid);
					if ( is_integer($k) ){
						//found in array
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>$rid[$k],
								"OFFICE_ID"=>$aid[$k],
								"EE_ID"=>$eid[$k]									
							)
						);
					}else{
						array_push($recs, 
							array(
								"PROJECT_ID"=> $this->PROJECT_ID, 
								"RECORD_ID"=>0,
								"OFFICE_ID"=>$SDOOfficeId[$i],
								"EE_ID"=>$arrEE[$SDOOfficeId[$i]]
							)
						);
					}
				}
				break;
		}
		return $recs;
	}
	protected function processData($model, $records, &$finalShow ){
		$myModels = array(	
			'DISTRICT'=>'deposit__projects_district_served', 
			'BLOCKS'=>'deposit__projects_block_served',
			'ASSEMBLY'=>'deposit__projects_assembly_const_served',
			'OFFICE' => 'deposit__projects_office',
			'VILLAGE' => 'deposit__projects_villages_served'
		);
		$modelName = $myModels[$model];
		switch($model){
			case 'DISTRICT':	$myFields = array('PROJECT_ID', 'DISTRICT_ID'); break;
			case 'BLOCKS' : 	$myFields = array('PROJECT_ID', 'BLOCK_ID'); break;
			case 'ASSEMBLY': 	$myFields = array('PROJECT_ID', 'ASSEMBLY_ID'); break;
			case 'OFFICE' : 	$myFields = array('PROJECT_ID', 'OFFICE_ID', 'EE_ID'); break;
			case 'VILLAGE' : 	$myFields = array('PROJECT_ID', 'VILLAGE_ID'); break;
		}
		//get existing ids
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
				$allExistingIDs = array();
		$this->db->select('RECORD_ID');
		$mRecs = $this->db->get_where($modelName, $arrWhere);
		if ($mRecs && $mRecs->num_rows()){
			foreach($mRecs->result() as $mrec)
				array_push($allExistingIDs, $mrec->RECORD_ID);
		}
		$countExisting = count($allExistingIDs);
		$total_records = count($records);
		
		$old_rec_index = array();
		$new_rec_index = array();
		$old_ids = array();
		$new_records = array();
		$old_records = array();

		$toBeUpdated = array();
		
		for($i=0; $i<$total_records; $i++){  
			if ($records[$i]['RECORD_ID']==0){
				array_push($new_rec_index, $i);
				array_push($new_records, $records[$i]);
			}else{
				array_push($old_ids, $records[$i]['RECORD_ID']);
				array_push($toBeUpdated, $records[$i]['RECORD_ID']);
				array_push($old_records, $records[$i]);
			}
		}
		$toBeDeleted = array();
		if( $countExisting>0 ){
			//get ids to be deleted
			for($i=0;$i<$countExisting; $i++){ 
				//if not found in updated list then add it to delete list
				if( !in_array($allExistingIDs[$i], $toBeUpdated) )
					array_push($toBeDeleted, $allExistingIDs[$i]); 
			}
		}
		$countToBeUpdated = count($toBeUpdated);
		$countNew = count($new_rec_index);
		$countDeleted = count($toBeDeleted);
		//Initialize serial no
		//$SNO = 1;
		//MODIFY RECORDS
		for($i=0;$i<count($old_records); $i++){
			$arrWhich = array('RECORD_ID'=>$old_records[$i]['RECORD_ID']);
			$recs = $this->db->get_where($modelName, $arrWhich);
			if($recs && $recs->num_rows()){
				$data = array();
				for($countF=0; $countF<count($myFields); $countF++){
					$data[$myFields[$countF]] = $old_records[$i] [$myFields [$countF] ];
				}
				//$data['SNO'] = $SNO++;
				$this->db->update($modelName, $data, $arrWhich);
				if($this->db->affected_rows()) $finalShow = 1;
			}
		}
		//ADD NEW RECORDS
		for($i=0;$i<count($new_records); $i++){
			$data = array();
			for($countF=0; $countF<count($myFields); $countF++){
				$data[$myFields[$countF]] = $new_records[$i] [$myFields [$countF] ];
			}
			//$data['SNO'] = $SNO++;
			$data['PROJECT_ID'] = $this->PROJECT_ID;
			$this->db->insert($modelName, $data);
			if($this->db->affected_rows()) $finalShow = 1;
		}
		//showArrayValues($toBeDeleted);
		//DELETE EXTRA RECORDS
		for($i=0;$i<count($toBeDeleted); $i++){
			//echo 'DD:'.$toBeDeleted[$i].':DD';
			$this->db->delete($modelName, array('RECORD_ID'=>$toBeDeleted[$i]));
			if($this->db->affected_rows()) $finalShow = 1;
		}
	}
	//FIELDS
	protected function getFields($table){
		$strSQL = 'SHOW COLUMNS FROM '.$table;
		$recs = $this->db->query($strSQL);
		$arrNames = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec)
				array_push($arrNames, $rec->Field);
		}
		return $arrNames;
	}
	protected function getAchivementFields(){
		return array(
			'ACHIEVEMENT_ID', 'SESSION_ID', 'PROJECT_ID', 
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORKS',
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 'CANAL_MASONRY', 'ROAD_WORKS',
			'IRRIGATION_POTENTIAL_KHARIF','IRRIGATION_POTENTIAL_RABI','IRRIGATION_POTENTIAL',
			'LA_CASES_STATUS', 'SPILLWAY_STATUS',
			'FLANK_STATUS', 'SLUICES_STATUS',
			'NALLA_CLOSURE_STATUS', 'CANAL_EARTH_WORK_STATUS', 
			'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS', 
			'SUBMISSION_DATE');
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
			'ESTIMATED_QTY_ID', 'PROJECT_ID', 'RAA_ID', 'SESSION_ID',
			'LA_NO', 'LA_HA','LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_MASONRY', 'ROAD_WORKS',
			'CANAL_LINING', 'IRRIGATION_POTENTIAL', 
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK');
	}
	protected function getEstimationStatusFields(){
		return array(
			'PROJECT_ID', 'LA_NA', 'FA_NA',
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 'STEEL_WORKS_NA',
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA',  'ROAD_WORKS_NA',
			'CANAL_LINING_NA', 'IRRIGATION_POTENTIAL_NA'
		);
	}
	public function getProjectSetupFields(){
		return array(
			'PROJECT_SETUP_ID', 'PROJECT_ID', 'PROJECT_CODE',
			'PROJECT_NAME', 'PROJECT_NAME_HINDI',
			'OFFICE_CE_ID', 'DISTRICT_NAME', 'DISTRICT_NAME_HINDI',
			'PROJECT_TYPE_ID', 'PROJECT_SUB_TYPE_ID',
			'SESSION_ID', 'PROJECT_START_DATE', 'PROJECT_COMPLETION_DATE', 
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK', 
			'AA_NO', 'AA_DATE', 'AA_AUTHORITY_ID', 'AA_AMOUNT', 
			'LONGITUDE_D', 'LONGITUDE_M', 'LONGITUDE_S', 
			'LATITUDE_D', 'LATITUDE_M', 'LATITUDE_S', 
			'HEAD_WORK_DISTRICT_ID', 'HEAD_WORK_BLOCK_ID', 
			'ASSEMBLY_CONST_ID', 'HEAD_WORK_TEHSIL_ID',
			'NO_VILLAGES_BENEFITED', 'NALLA_RIVER', 'LIVE_STORAGE', 'PROJECT_SAVE_DATE','DEPOSIT_SCHEME_ID'
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
	protected function getRAAFields(){
		return array(
			'RAA_PROJECT_ID', 'PROJECT_ID', 'SESSION_ID', 
			'RAA_NO', 'RAA_DATE', 'RAA_AUTHORITY_ID', 'RAA_AMOUNT', 
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK', 'PROJECT_COMPLETION_DATE', 
			'ADDED_BY', 'RAA_SAVE_DATE'
		);
	}
	//
	protected function showTime($title=''){
		$this->endTime = microtime();
		$t = ($this->endTime - $this->startTime) * 1000;
		$t = sprintf(" %07.2f : %-20s ", $t,  $title );
		array_push($this->message, getMyArray(true, $t));
		$this->startTime = $this->endTime;
	}
	//
	protected function getKeyValues($mFields, $strSQL){
		$recs = $this->db->query($strSQL);
		$isExists = false;
		$data = array();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mFields); $i++)
				$data[$mFields[$i]] = $rec->{$mFields[$i]};
			$isExists = true;
		}
		if(!$isExists){
			for($i=0; $i<count($mFields); $i++){
				$data[$mFields[$i]] = '';
			}
		}
		return $data;
	}
	protected function getAchievement($SESSION_ID){
		$mFields = array(
			'PROJECT_ID', 'ACHIEVEMENT_ID', 
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORKS', 
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 
			'FA_HA', 'FA_COMPLETED_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 'CANAL_MASONRY', 'ROAD_WORKS',
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 'IRRIGATION_POTENTIAL', 
			'LA_CASES_STATUS',
			'SPILLWAY_STATUS', 'FLANK_STATUS', 'SLUICES_STATUS', 
			'NALLA_CLOSURE_STATUS', 'CANAL_EARTH_WORK_STATUS', 
			'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS', 
			'SUBMISSION_DATE'
		);
		$strSQL = 'SELECT '. implode(', ', $mFields).
			' FROM '.$this->tblPmonAchivement.' 
				WHERE PROJECT_ID = '.$this->PROJECT_ID.'
				AND SESSION_ID='.$SESSION_ID.
			' ORDER BY ACHIEVEMENT_ID ';
		//echo $strSQL;
		return $this->getKeyValues($mFields, $strSQL);
	}
	protected function getEstimation($projectId){
		$mFields = array(
			'ESTIMATED_QTY_ID', 'PROJECT_ID', 'RAA_ID', 'SESSION_ID', 
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 
			'FA_HA', 'FA_COMPLETED_HA', 'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK',
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 'CANAL_MASONRY', 'ROAD_WORKS',
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 'IRRIGATION_POTENTIAL'
		);
		$strSQL = 'SELECT '. implode(', ', $mFields).
			' FROM '.$this->tblEstiQty.'
				WHERE PROJECT_ID = '.$projectId.' AND ADDED_BY=0';
		return $this->getKeyValues($mFields, $strSQL);
	}
	protected function getEstimationStatus($projectId){
		$mFields = array(
			'PROJECT_ID', 'LA_NA', 'FA_NA',
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 'STEEL_WORKS_NA',
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA', 'ROAD_WORKS_NA',
			'CANAL_LINING_NA', 'IRRIGATION_POTENTIAL_NA'
		);
		$strSQL = 'SELECT '. implode(', ', $mFields).
			' FROM '.$this->tblEstiStatus.'
				WHERE PROJECT_ID = '.$projectId;
		return $this->getKeyValues($mFields, $strSQL);
	}
	protected function getTargetDates($projectId){
		$mFields = array(
			'PROJECT_ID', 'LA_TARGET_DATE', 
			'TARGET_DATE_ID', 'SPILLWAY_TARGET_DATE', 
			'FLANKS_TARGET_DATE', 'SLUICES_TARGET_DATE', 
			'NALLA_CLOSURE_TARGET_DATE', 'CANAL_EARTHWORK_TARGET_DATE', 
			'CANAL_STRUCTURES_TARGET_DATE', 'CANAL_LINING_TARGET_DATE', 
			'TARGET_SUBMISSION_DATE'
		);
		$strSQL = 'SELECT '. implode(', ', $mFields).
			' FROM '.$this->tblPmonTargetCompletion.'
					WHERE PROJECT_ID = '.$projectId;
		return $this->getKeyValues($mFields, $strSQL);
	}
	//
	protected function setLock(){
		$lockTable = $this->tblPmonLocks;
		$goAhead = false;
		$arrWhere = array('PROJECT_ID' => $this->PROJECT_ID);
		$isExists = false;
		$data = array('SETUP_LOCK'=>1);
		$recs = $this->db->get_where($lockTable, $arrWhere);
		$isExists = (($recs && $recs->num_rows()) ? true:false); 
		if($isExists){
			@$this->db->update($lockTable, $data, $arrWhere);
		}else{
			@$this->db->insert($lockTable, $data);
		}
		if( $this->db->affected_rows() ){
			//echo 'Project Locked,,,,...';
			//record log for lock
			$data = array(
				'PROJECT_ID'=>$this->PROJECT_ID, 
				'LOCK_DATE_TIME'=>date("Y-m-d H:i:s"), 
				'LOCK_MODE'=>1, 
				'LOCK_TYPE'=>1, 
				'USER_ID'=>$this->session->userData('USER_ID'), 
				'DESCRIPTION'=>'Project Setup Locked'
			);
			@$this->db->insert($this->tblPmonLocksLog, $data);
			if( $this->db->affected_rows() ){			

				$Previous_session=getSessionDataByKey('CURRENT_SESSION_ID')-1;
					$session_data=$this->db->select('END_DATE')->from('__sessions')->where('SESSION_ID',$Previous_session)->get()->row();	
					$session_date='';
					if($session_data!=null)
						$session_date=$session_data->END_DATE;
				$arrData = array(
							'PROJECT_MODE'=>'DEP_PMON', 
							'ENTRY_MODE'=>'ACHIEVEMENT', 
							'MONTH_DATE'=>$session_date, 
							'NA'=>0,
							'PROJECT_ID'=>$this->PROJECT_ID,
							'PROJECT_SETUP_ID'=>''
						   );
				$sql="select * from ".$this->tblPmonBlockAchivement." where PROJECT_ID=".$this->PROJECT_ID." AND (KHARIF+RABI)>0";
				$arrD=$this->db->query($sql);
							if($arrD->num_rows()>0)
							{
								$arrBlockKharibRabi=array();
									foreach ($arrD->result() as $key) {
											$temp = array(
											'BLOCK_ID'=>$key->BLOCK_ID,
											'KHARIF'=>$key->KHARIF,
											'RABI'=>$key->RABI
										);			
									array_push($arrBlockKharibRabi, $temp);
									}
								$this->load->library('MyIrrigationLedger');
								$this->myirrigationledger->updateCreationLedger($arrData, $arrBlockKharibRabi);
								  $status=true;
							}
			}
			return true;
		}
		return false;
	}
	protected function getLockStatus($lockMode){
		$lockTable = $this->tblPmonLocks;
		$arrWhere = array('PROJECT_ID' => $this->PROJECT_ID);
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

	protected function getLockStatus1($lockMode){
		$lockTable = $this->tblDepMiPmonLocks;
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
	/** OK 10-10-2013 Called by printSetup*/
	protected function getRAAData($projectId){
		$mFields = array('PROJECT_ID', 'RAA_PROJECT_ID', 'RAA_NO', 'RAA_DATE', 'RAA_AUTHORITY_ID', 'RAA_AMOUNT');
		$strSQL = 'SELECT '. implode(', ', $mFields).
			' FROM '.$this->tblPmonRaa.' 
				WHERE PROJECT_ID = '.$projectId.
				' AND ADDED_BY=0';
		//echo $strSQL;
		return $this->getKeyValues($mFields, $strSQL);
	}
	public function getBlockIds($projectId){
		if( !$projectId) return '';
		$view = '';
		$blocks = array();
		$recs = $this->db->get_where('deposit__projects_block_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
			$recs->free_result();
		}
		return $blocks;
	}
	protected function getEstimationBlockIP($projectId, $estiId=0){
		//$where = ($estiId) ? ('AND e.ESTIMATION_ID='.$estiId):'';
		$strSQL = 'SELECT IFNULL(e.ID, 0)as ID, b.BLOCK_ID, b.BLOCK_NAME, b.BLOCK_NAME_HINDI, 
				IFNULL(e.KHARIF,0)as KHARIF, IFNULL(e.RABI,0)as RABI, IFNULL(e.IP_TOTAL,0)as IP_TOTAL 
			FROM deposit__projects_block_served as p
				LEFT JOIN '.$this->tblPmonBlockEstiIP.' as e ON(p.PROJECT_ID=e.PROJECT_ID AND p.BLOCK_ID=e.BLOCK_ID )
				INNER JOIN __blocks as b ON(p.BLOCK_ID=b.BLOCK_ID)
			WHERE p.PROJECT_ID='.$projectId;
			
		$recs = $this->db->query($strSQL);
		//echo $strSQL;
		$arrData = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrData[$rec->BLOCK_ID] = array(
					'BLOCK_NAME'=>$rec->BLOCK_NAME,
					'BLOCK_NAME_HINDI'=>$rec->BLOCK_NAME_HINDI,
					'ESTIMATION_IP'=>array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>$rec->IP_TOTAL),
					'ACHIEVEMENT_IP'=>array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0)
				);
			}
         	$recs->free_result();
		}
		//showArrayValues($arrData);
		return $arrData;
	}
	protected function getAchievementBlockIP($projectId, $sessionId){
		$mFields = array('ID', 'BLOCK_ID', 'KHARIF', 'RABI', 'IP');
		$strSQL = 'SELECT ID, BLOCK_ID, KHARIF, RABI, IP_TOTAL FROM dep_pmon__t_block_achievement_ip
			WHERE PROJECT_ID='.$projectId;
		$recs = $this->db->query($strSQL);
		$arrData = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrData[$rec->BLOCK_ID] = array(
					'ACHIEVEMENT_IP'=>array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>$rec->IP_TOTAL)
				);
			}
          	$recs->free_result();
		}
		//showArrayValues($arrData);
		return $arrData;
	}
	
}?>