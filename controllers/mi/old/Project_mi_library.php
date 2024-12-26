<?php
class Project_mi_library extends MX_Controller{
	protected $message, $PROJECT_SETUP_ID, $PROJECT_ID, $RAA_ID, 
		$tblSetup, $tblLock, $tblMonthly, $tblMonthlyBlock;
	function __construct(){
		parent::__construct();
		$this->PROJECT_ID = 0;
		$this->PROJECT_SETUP_ID = 0;
		$this->RAA_ID = 0;
		$this->RESULT = false;
		$this->message = array();
		$this->tblSetup = 'mi__m_project_setup';
		$this->tblLock = 'mi__t_locks';
		$this->tblMonthly = 'mi__t_monthly';
		$this->tblMonthlyBlock = 'mi__t_monthly_block';
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

    //This method moves to Mi__base.php
	/*public function getOfficeEEname($EE_ID){
		if(!$EE_ID) return '';
		$recs = $this->db->get_where('__offices', array('OFFICE_ID'=>$EE_ID));
		$opt = '';
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			 $recs->free_result();
			return $rec->OFFICE_NAME;
		}
		return '';
	}*/

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

	/** getAuthority */
	//ok
    //This method moved to Mi__base.php
	/*protected function getAuthorityOptions($AuthID=0){
		$this->db->order_by('AUTHORITY_ID', 'ASC');
		$recs = $this->db->get('pmon__m_authority');
		$arrAuth = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push(						
					$arrAuth, 
					'<option value="'.$rec->AUTHORITY_ID.'" '.
					(($rec->AUTHORITY_ID==$AuthID) ? 'selected="selected"':'').'>'.$rec->AUTHORITY_NAME.'</option>'
				);
			}
			$recs->free_result();
		}
		return $arrAuth;
	}*/

    //This method moves to Mi__base.php
	/*protected function getAuthorityName($AuthID=0){
		if($AuthID==0) return '';
		$recs = $this->db->get_where('pmon__m_authority', array('AUTHORITY_ID'=>$AuthID));
		$dd_auth = array();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$recs->free_result();
			return $rec->AUTHORITY_NAME;
		}
		return '';
	}*/

	//This method moves to Mi__base.php
	/*protected function getWorkStatus($opt=0, $na){
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
	//This method moves to Mi__base.php
	protected function getWorkStatusAsString($opt=0){
		$view = array();
		if($opt=='')$opt=0;
		$status_options = array('', 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 'Dropped');
		return $status_options[$opt];
	}*/
	/** getFinancialYear */

    //This method moves to Mi__base.php
	/*protected function getSessionOptions($selId=0, $minId=0, $maxId=0){
		$minId = (($minId==0)? PMON_MI_START_SESSION_ID :$minId);
		$maxId = (($maxId==0)? $this->getSessionIdByDate():$maxId);
		$this->db->order_by('SESSION_START_YEAR', 'ASC');
		$recs = $this->db->where('SESSION_ID >=', $minId);
		$recs = $this->db->where('SESSION_ID <=', $maxId);
		$recs = $this->db->get('__sessions');
		$dd = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				//if($restrictFY && $rec->SESSION_ID<PMON_MI_START_SESSION_ID) continue;
				array_push($dd, 
					'<option value="'.$rec->SESSION_ID.'" '.
					(($rec->SESSION_ID==$selId) ? 'selected="selected"':'').'>'.
					$rec->SESSION_START_YEAR.' - '.$rec->SESSION_END_YEAR.
					'</option>'
				);
			}
			 $recs->free_result();
		}
		return $dd;
	}*/
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
	//OK
	//This method moves to Mi__base.php
    /*protected function getDistrictOptions($DistID=0, $parentDist=array()){
        //print_r($parentDist);
		//$this->db->order_by('DISTRICT_NAME', 'ASC');
		//$recs = $this->db->get_where('__districts', array('STATE_ID'=>7));

        $this->db->select('DISTRICT_ID, DISTRICT_NAME, DISTRICT_NAME_HINDI');
        $this->db->from('__districts');
        $this->db->where('STATE_ID','7');
        if( count($parentDist)>0)
            $this->db->where_in('DISTRICT_ID', $parentDist);
        $this->db->order_by('DISTRICT_NAME', 'ASC');
        $recs = $this->db->get();

		if(!is_array($DistID)) $DistID = array($DistID);

		$vlist = array();
		array_push($vlist, '<option value="0">Select District</option>');
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($vlist, 
					'<option value="'.$rec->DISTRICT_ID.'" '.
					((in_array($rec->DISTRICT_ID, $DistID)) ? 'selected="selected"':'').'>'.$rec->DISTRICT_NAME.'('.$rec->DISTRICT_NAME_HINDI.')</option>'
				);
			}
			$recs->free_result();
		}
		return implode('', $vlist);
	}*/

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

    //This method moves to Mi__base.php
	/*protected function getDistrictBenefitedIDs($projectId){
		if(!$projectId) return array();
		$arrIDs = array();
		$recs = $this->db->get_where('mi__m_district_served', array('PROJECT_SETUP_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($arrIDs, $rec->DISTRICT_ID);
			}
			$recs->free_result();
		}
		return $arrIDs;
	}*/

    //This method is moved to model : Mi__base.php
	/*public function getDistrictBenefited($projectId,$parentDist){
		if(!$projectId) return '';
		return $this->getDistrictOptions($this->getDistrictBenefitedIDs($projectId),$parentDist);
        //return $this->getDistrictOptions($this->getDistrictBenefitedIDsPromon($projectId));
	}*/
	//Block  setting
	//ok
    //This method is moved to model : Mi__base.php
	/*protected function getBlockString($b_id=array()){
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
			$recs->free_result();
		}
		return implode(', ', $bOptions);
	}*/

	//This method is moved to model : Mi__base.php
	/*public function getBlocks($projectId){
		if( !$projectId) return '';
		$view = '';
		$blocks = array();
		$recs = $this->db->get_where('__projects_block_served', array('PROJECT_ID'=>$projectId));
		//array_push($vlist, '<option value="0">Select District</option>');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
			$recs->free_result();
		}
		return $this->getBlockString($blocks);
	}*/
	//ok
    //This method is moved to model : Mi__base.php
	/*protected function getBlockOptions($dist_id=array(), $b_id=array() , $parentBlocks=array()){
		//if not array then convert it to array
		//echo $dist_id;
		if( !is_array($dist_id) ) $dist_id = array($dist_id);
		if( !is_array($b_id) ) $b_id = array($b_id);
		//showArrayValues($dist_id);
		$bOptions = array();
		array_push($bOptions, '<option value="">Select Block</option>');
		$this->db->order_by('DISTRICT_NAME', 'ASC')->order_by('BLOCK_NAME', 'ASC');
		if(count($dist_id)>0)
		    $this->db->where_in ('DISTRICT_ID', $dist_id);
		if(count($parentBlocks)>0)
            $this->db->where_in ('BLOCK_ID', $parentBlocks);
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
			$recs->free_result();
		}
		if($i!=0) array_push($bOptions, '</optgroup>');
		return implode('', $bOptions);
	}*/

	public function getBlockOptionsByDistrict(){
        $dist_id = $this->input->post('dist_id');
        $this->load->model('mi/mi__m_project_setup');
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

    //This method moves to Mi__base.php
	/*public function getBlocksBenefited($dist, $projectId,$parentBlocks=array()){
		if(count($dist)==0) return '';
		if( !$projectId) return '';
		$blocks = array();
		$recs = $this->db->get_where('mi__m_block_served', array('PROJECT_SETUP_ID'=>$projectId));
        //$recs = $this->db->get_where('__projects_block_served', array('PROJECT_ID'=>$projectId));
        //echo $this->db->last_query();

		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
			$recs->free_result();
		}
		return $this->getBlockOptions($dist, $blocks,$parentBlocks);
	}*/

	//Tehsil  setting
	//OK
    //This method moves to Mi__base.php
	/*protected function getTehsilOptions($dist_id=array(), $b_id=array()){
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
			$recs->free_result();
		}
		if($i!=0) array_push($bOptions, '</optgroup>');
		//array_push($bOptions, '</pre>');
		//echo $i. implode('', $bOptions);
		return implode('', $bOptions);
	}*/

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

	/*//OK
	//This method moves to Mi__base.php
	protected function getAssemblyOptions($a_id=array()){
		if( !is_array($a_id) ) $a_id = array($a_id);
		$vlist = array();
		$this->db->order_by('ASSEMBLY_NAME', 'ASC');
		$recs = $this->db->get('__m_assembly_constituency');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($vlist, 
					'<option value="'.$rec->ASSEMBLY_ID.'" '.
					(( in_array($rec->ASSEMBLY_ID, $a_id))?'selected="selected"':'').'>'.
					$rec->ASSEMBLY_NAME.'('.$rec->ASSEMBLY_NAME_HINDI.') - '.$rec->ASSEMBLY_ID.
					'</option>'
				);
			}
			$recs->free_result();
		}
		return implode('', $vlist);
	}*/

    //This method moves to Mi__base.php
	/*protected function getBenefitedAssembly($projectId){
		$ids = array();
		if($projectId){
			$recs = $this->db->get_where('mi__m_assembly_const_served', array('PROJECT_SETUP_ID'=>$projectId));
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					array_push($ids, $rec->ASSEMBLY_ID);
				}
				$recs->free_result();
			}
		}
		return $this->getAssemblyOptions($ids);
	}*/
	//Village setting/
    //This method moves to Mi__base.php
	/*protected function getVillages($projectId){
		$ids = array();
		if($projectId){
			$recs = $this->db->get_where(
				'mi__v_projects_village_served_tehsil_district',
				array('PROJECT_SETUP_ID'=>$projectId)
			);
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					array_push($ids, $rec->VILLAGE_ID);
				}
				$recs->free_result();
			}
		}
		return $this->getVillageString($ids);
	}*/
    //This method moves to Mi__base.php
	/*protected function getVillageString($v_id=array()){
		if( !is_array($v_id) ) $v_id = array($v_id);
		if ( count($v_id)==0) return '';
		$this->db->where_in('VILLAGE_ID', $v_id);//->order_by('VILLAGE_NAME');
		$recs = $this->db->get('__v_village_tehsil_district');
		$vlist = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($vlist, $rec->VILLAGE_NAME. ' ('.$rec->TEHSIL_NAME.'[ '.$rec->DISTRICT_NAME.' ])');
			}
			$recs->free_result();
		}
		return implode(', &nbsp; &nbsp; ', $vlist);
	}*/
    //This method moves to Mi__base.php
	/*protected function getVillageOptions($v_id=array(), $districtIdS=array()){
		if(!is_array($districtIdS)) $districtIdS = array($districtIdS);
		if( !is_array($v_id) ) $v_id = array($v_id);
		if( count($districtIdS)==0 ) return '';
		$this->db->where_in('DISTRICT_ID', $districtIdS);//->order_by('VILLAGE_NAME');
		$recs = $this->db->get('__v_village_tehsil_district');
		$vlist ='';// array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$vlist .= '<option value="'.$rec->VILLAGE_ID.'" '.
					(( in_array($rec->VILLAGE_ID, $v_id))?'selected="selected"':'').'>'.
					$rec->VILLAGE_NAME.' '.$rec->VILLAGE_NAME_HINDI.
					', '.$rec->TEHSIL_NAME_HINDI.', '.$rec->DISTRICT_NAME_HINDI.''.
					'</option>';
			}
			$recs->free_result();
		}
		return $vlist;
	}*/
	//OK
    //This method moves to Mi__base.php
	/*protected function getBenefitedVillages($projectId, $districtIdS=array()){
		if(!is_array($districtIdS)) $districtIdS = array($districtIdS);
		$ids = array();
		if($projectId){
			$strSQL = 'SELECT vs.RECORD_ID AS RECORD_ID, vs.PROJECT_SETUP_ID AS PROJECT_SETUP_ID, vs.VILLAGE_ID AS VILLAGE_ID, 
					v.VILLAGE_NAME AS VILLAGE_NAME, v.TEHSIL_ID AS TEHSIL_ID,
					v.VILLAGE_NAME_HINDI AS VILLAGE_NAME_HINDI, t.TEHSIL_NAME AS TEHSIL_NAME, t.TEHSIL_NAME_HINDI AS TEHSIL_NAME_HINDI, 
					d.DISTRICT_ID AS DISTRICT_ID, d.DISTRICT_NAME AS DISTRICT_NAME, d.DISTRICT_NAME_HINDI AS DISTRICT_NAME_HINDI 
				FROM mi__m_villages_served  as vs
					 JOIN __villages AS v on (vs.VILLAGE_ID = v.VILLAGE_ID)
					 JOIN __tehsils as t on(v.TEHSIL_ID = t.TEHSIL_ID) 
					 JOIN __districts as d on(t.DISTRICT_ID = d.DISTRICT_ID)
				WHERE PROJECT_SETUP_ID='.$projectId;
			$recs = $this->db->query($strSQL);//('mi__v_projects_village_served_tehsil_district', array('PROJECT_SETUP_ID'=>$projectId));
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					array_push($ids, $rec->VILLAGE_ID);
					if ( !in_array($rec->DISTRICT_ID, $districtIdS) ){
						array_push($districtIdS, $rec->DISTRICT_ID);
					}
				}
				$recs->free_result();
			}
		}
		return $this->getVillageOptions($ids, $districtIdS);
	}*/



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
			'C_PIPEWORK', 'C_DRIP_PIPE', 'C_WATERPUMP', 
			'K_CONTROL_ROOMS','ENTRY_MODE'
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

	//ok
    //moving this function to model : Mi__m_project_setup
	/*public function getProjectSetupFields(){
		return array(
			'PROJECT_SETUP_ID', 'PARENT_PROJECT_ID', 'PROJECT_CODE',
			'WORK_NAME', 'WORK_NAME_HINDI',
			'OFFICE_CE_ID', 'DISTRICT_NAME', 'DISTRICT_NAME_HINDI',
			'PROJECT_TYPE_ID', 'PROJECT_SUB_TYPE_ID',
			'SESSION_ID', 'PROJECT_COMPLETION_DATE',
			'AA_NO', 'AA_DATE', 'AA_AUTHORITY_ID', 'AA_AMOUNT','AA_FILE_URL','AA_USER_FILE_NAME',
			'LONGITUDE_D', 'LONGITUDE_M', 'LONGITUDE_S', 
			'LATITUDE_D', 'LATITUDE_M', 'LATITUDE_S', 
			'DISTRICT_ID', 'BLOCK_ID',
			'ASSEMBLY_ID', 'TEHSIL_ID',
			'NO_VILLAGES_BENEFITED', 'NALLA_RIVER', 'PROJECT_SAVE_DATE','OFFICE_EE_ID'
		);
	}*/
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

	//OK
    //This method moves to Mi__base.php
	/*protected function getKeyValues($mFields, $strSQL){
	    //echo $strSQL;
		$recs = $this->db->query($strSQL);
		$isExists = false;
		$data = array();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$recs->free_result();
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
	}*/

	/*protected function getAchievement($SESSION_ID){
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
			' FROM pmon__t_achievements 
				WHERE PROJECT_ID = '.$this->PROJECT_ID.'
				AND SESSION_ID='.$SESSION_ID.
			' ORDER BY ACHIEVEMENT_ID ';
		//echo $strSQL;
		return $this->getKeyValues($mFields, $strSQL);
	}*/
	//OK
    //This method moves to Mi__base.php
    /*protected function getAchievement($SESSION_ID){
        $mFields = array(
            'PROJECT_SETUP_ID',
            'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
            'FA_HA', 'FA_COMPLETED_HA',
            'L_EARTHWORK',  'C_MASONRY',
            'C_PIPEWORK', 'C_DRIP_PIPE', 'C_WATERPUMP', 'K_CONTROL_ROOMS',
            'KHARIF', 'RABI', 'SUBMISSION_DATE'
        ); //'IP_TOTAL',
        $strSQL = 'SELECT '. implode(', ', $mFields).' FROM '.$this->tblMonthly.'
				WHERE PROJECT_SETUP_ID = '.$this->PROJECT_SETUP_ID.'
				AND SESSION_ID='.$SESSION_ID;
        //array_push($mFields ,'IP_TOTAL');
        return $this->getKeyValues($mFields, $strSQL);
    }*/

    //This method moves to Mi__base.php
    /*protected function getEstimation($projectId){
		$mFields = array(
			'ESTIMATED_QTY_ID', 'RAA_ID', 'SESSION_ID',
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA','L_EARTHWORK','C_MASONRY','C_PIPEWORK','C_DRIP_PIPE',
            'C_WATERPUMP','K_CONTROL_ROOMS','ADDED_BY'
		); //,'KHARIF','RABI','IP_TOTAL'

        $strSQL = "SELECT ". implode(', ', $mFields) . " , 
        e.PROJECT_SETUP_ID as PROJECT_SETUP_ID, SUM(RABI) AS RABI, 
        SUM(KHARIF) AS KHARIF, (SUM(KHARIF) + SUM(RABI)) AS IP_TOTAL
        FROM mi__t_estimated_qty as e
        LEFT JOIN mi__ip_design as b ON b.PROJECT_SETUP_ID=e.PROJECT_SETUP_ID
        WHERE e.PROJECT_SETUP_ID = ".$projectId." AND e.ADDED_BY = '0' ";
        //echo $strSQL;
        array_push($mFields,'PROJECT_SETUP_ID','KHARIF','RABI','IP_TOTAL');
        return $this->getKeyValues($mFields, $strSQL);
	}*/

    //This method moves to Mi__base.php
	/*protected function getEstimationStatus($projectId){
		$mFields = array(
			'PROJECT_SETUP_ID', 'LA_NA', 'FA_NA',
			'L_EARTHWORK_NA', 'C_EARTHWORK_NA', 'C_MASONRY_NA',
			'C_PIPEWORK_NA', 'C_DRIP_PIPE_NA', 'C_WATERPUMP_NA',
			'K_CONTROL_ROOMS_NA', 'IP_TOTAL_NA'
		);
		$strSQL = 'SELECT '. implode(', ', $mFields).' FROM mi__t_estimated_status
				WHERE PROJECT_SETUP_ID = '.$projectId;
		return $this->getKeyValues($mFields, $strSQL);
	}*/

    //This method moves to Mi__base.php
	/*protected function getTargetDates($projectId){
		$mFields = array(
			'PROJECT_SETUP_ID', 'LA_DATE','FA_DATE',
            'PUMPING_UNIT_DATE', 'INTAKE_WELL_DATE',
			'PVC_LIFT_SYSTEM_DATE', 'PIPE_DISTRI_DATE',
			'DRIP_SYSTEM_DATE', 'WATER_STORAGE_TANK_DATE',
			'FERTI_PESTI_CARRIER_SYSTEM_DATE', 'CONTROL_ROOMS_DATE',
			'TARGET_SUBMISSION_DATE'
		);
		$strSQL = 'SELECT '. implode(', ', $mFields).' FROM mi__t_target_date_completion
					WHERE PROJECT_SETUP_ID = '.$projectId;
		return $this->getKeyValues($mFields, $strSQL);
	}*/

	//ok
    //This method moves to Mi__base.php
    /*protected function getEWorksDetails($projectId){
        $this->db->select('PROJECT_SETUP_ID, WORK_NAME, WORK_NAME_HINDI, PROJECT_CODE, AA_NO, AA_DATE, AA_AMOUNT');
        $this->db->where('PROJECT_SETUP_ID', $projectId);
        $recs = $this->db->get('mi__v_projectlist_with_lock');
        $data = array();
        if ($recs && $recs->num_rows()) {
            $rec = $recs->row();
			$recs->free_result();
            $data = array(
                'PROJECT_NAME' => $rec->WORK_NAME,
                'PROJECT_NAME_HINDI' => $rec->WORK_NAME_HINDI,
                'PROJECT_CODE' => $rec->PROJECT_CODE,
                'AA_NO' => $rec->AA_NO,
                'AA_DATE' => $rec->AA_DATE,
                'AA_AMOUNT' => $rec->AA_AMOUNT,
                'RAA_SNO' => 0,
                'RAA_NO' => 0,
                'RAA_DATE' => '',
                'RAA_AMOUNT' => 0
            );
        }
        $this->db->select('RAA_NO, RAA_DATE, RAA_AMOUNT');
        $this->db->order_by('RAA_DATE', 'ASC');
        $this->db->where('PROJECT_SETUP_ID', $projectId);
        $this->db->where('IS_RAA', 1);
        $recs = $this->db->get('mi__t_raa_project');
        //$data = array();
        if ($recs && $recs->num_rows()) {
            $i = 1;
            foreach($recs->result() as $rec) {
                if ($i == $recs->num_rows()) {
                    $data['RAA_SNO'] = $i;
                    $data['RAA_NO'] = $rec->RAA_NO;
                    $data['RAA_DATE'] = $rec->RAA_DATE;
                    $data['RAA_AMOUNT'] = $rec->RAA_AMOUNT;
                }
                $i++;
            }
			$recs->free_result();
        }
        return $data;
    }*/
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

	/** OK Called by printSetup*/
    //This method moves to Mi__base.php
	/*protected function getRAAData($projectId){
		$mFields = array('PROJECT_SETUP_ID', 'RAA_PROJECT_ID', 'RAA_NO', 'RAA_DATE', 'RAA_AUTHORITY_ID', 'RAA_AMOUNT');
		$strSQL = 'SELECT '. implode(', ', $mFields).
			' FROM mi__t_raa_project 
				WHERE PROJECT_SETUP_ID = '.$projectId.
				' AND ADDED_BY=0';
		//echo $strSQL;
		return $this->getKeyValues($mFields, $strSQL);
	}*/

    //This method moves to Mi__base.php
	/*public function getBlockIds($projectId){
		if( !$projectId) return '';
		$view = '';
		$blocks = array();
		$recs = $this->db->get_where('mi__m_block_served', array('PROJECT_SETUP_ID'=>$projectId));
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($blocks, $rec->BLOCK_ID);
			}
			$recs->free_result();
		}
		return $blocks;
	}*/
    //This method moves to Mi__base.php
	/*protected function getEstimationBlockIP($projectId, $estiId=''){
		//$where = ($estiId) ? ('AND e.ESTIMATION_ID='.$estiId):'';
		/*$strSQL = 'SELECT IFNULL(e.ID, 0)as ID, b.BLOCK_ID, b.BLOCK_NAME, b.BLOCK_NAME_HINDI,
				IFNULL(e.KHARIF,0)as KHARIF, IFNULL(e.RABI,0)as RABI 
			FROM mi__m_block_served as p
				LEFT JOIN mi__ip_design_block as e ON(p.PROJECT_SETUP_ID=e.PROJECT_SETUP_ID AND p.BLOCK_ID=e.BLOCK_ID )
				INNER JOIN __blocks as b ON(p.BLOCK_ID=b.BLOCK_ID)
			WHERE p.PROJECT_SETUP_ID='.$projectId;
		//, IFNULL(e.IP_TOTAL,0)as IP_TOTAL
		$recs = $this->db->query($strSQL);
		//echo $strSQL;*/

        /*$this->db->select('IFNULL(e.ID,0) as ID, b.BLOCK_ID, b.BLOCK_NAME, b.BLOCK_NAME_HINDI, IFNULL(e.KHARIF,0)as KHARIF,IFNULL(e.RABI,0)as RABI')
                ->from('mi__m_block_served as p')
                ->join('mi__ip_design_block as e', 'e.PROJECT_SETUP_ID=p.PROJECT_SETUP_ID','LEFT')
                ->join('__blocks as b','p.BLOCK_ID=b.BLOCK_ID')
                ->where('p.PROJECT_SETUP_ID',$projectId);
        $recs = $this->db->get();
		$arrData = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrData[$rec->BLOCK_ID] = array(
					'BLOCK_NAME'=>$rec->BLOCK_NAME,
					'BLOCK_NAME_HINDI'=>$rec->BLOCK_NAME_HINDI,
					'ESTIMATION_IP'=>array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI,'IP'=> ($rec->KHARIF+$rec->RABI)),
					'ACHIEVEMENT_IP'=>array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0)
				);
			}
            //, 'IP'=>$rec->IP_TOTAL
         	$recs->free_result();
		}
		//showArrayValues($arrData);
		return $arrData;
	}*/

    //This method moves to Mi__base.php
	/*protected function getAchievementBlockIP($projectId, $sessionId){
		$this->db->select('ID, BLOCK_ID, KHARIF, RABI');
        $recs = $this->db->get_where($this->tblMonthlyBlock, array('ENTRY_FROM'=>1, 'PROJECT_SETUP_ID'=>$projectId));
		$arrData = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrData[$rec->BLOCK_ID] = array(
					'ACHIEVEMENT_IP'=>array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI,'IP'=> ($rec->KHARIF+$rec->RABI))
				);
			}
          	$recs->free_result();
		}
		//showArrayValues($arrData);
		return $arrData;
	}*/
}