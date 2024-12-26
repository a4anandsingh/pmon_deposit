<?php
//error_reporting(0);
include_once("Project_mi_library.php");
//all variable name should be in camelcase
Class Project_mi_c extends Project_mi_library{
    protected $PARENT_PROJECT_ID;
    private $arrSetupData;
    function __construct(){
        parent::__construct();
        $this->message = array();
        $this->arrSetupData = array();
		//error_reporting(E_ALL);
        $this->load->model('pmon_dep/dep_mi__m_project_setup');
        date_default_timezone_set('Asia/Kolkata');
    	$id = getSessionDataByKey('USER_ID');
    	$isOffline = false;
	    //	echo 'test1';exit;
    	/*if($isOffline){
    		if(in_array($id, array(23, 86, 84))){
        
        	}else{
        		echo '<h1 style="color:#f00;margin-top:50px">Micro Irrigation Module Currentely Not Available</h1>';
        		exit;
        	}
        }*/
    }
 
	public function showProjectSetupEntryBox(){	
		$permissions = $this->dep_mi__m_project_setup->getPermissions();													  
		$this->PARENT_PROJECT_ID = (int)trim($this->input->post('PROJECT_ID'));
		$this->PROJECT_SETUP_ID = (int)trim($this->input->post('PROJECT_SETUP_ID'));
		$this->PROJECT_ID = (int)trim($this->input->post('PROJECT_ID'));
		$holdingPerson = getSessionDataByKey('HOLDING_PERSON');
		//Check if any Micro project setup is there and it is not locked, of this project(parent project)
		$editMode = (($this->PROJECT_SETUP_ID) ? true : false);
		 
		/*
		if(!$editMode ){
			$numProjects = $this->dep_mi__m_project_setup->getTotalMicroProjects($this->PARENT_PROJECT_ID);
			if($numProjects>0){
				$data['report'] = array('mode'=>'custom', 'message'=>'Unlocked Project already exists of this project.<br />Please lock it to proceed further.', 'icon'=>'cus-error', 'report'=>false);
				$mView= $this->load->view('utility/lock_view', $data, TRUE);
				array_push($this->message, getMyArray(null, $mView));
				//echo "hello";exit;
				echo createJSONResponse($this->message);
				return;
			}
		}
		*/
		$data['LockStatus'] = (($this->PROJECT_SETUP_ID) ? $this->getLockStatus(1) : 0);
		if($data['LockStatus'] == 1) {
			$data['report'] = array('mode' => 'lock', 'report' => true);
			$mView = $this->load->view('utility/lock_view', $data, TRUE);
			array_push($this->message, getMyArray(null, $mView));
			echo createJSONResponse($this->message);
		}else {
			$this->showProjectSetupData($this->PROJECT_SETUP_ID);
		}
	}

	public function showProjectSetupData($PS_ID)
    {
        $this->PARENT_PROJECT_ID = 0;
        $this->PROJECT_SETUP_ID = $PS_ID;
        $arrWhere = array('PROJECT_ID' => $this->PARENT_PROJECT_ID);
        $holdingPerson = getSessionDataByKey('HOLDING_PERSON');
        $arrProjectData = array();
        $param = array('parentProjectId' => 0, 'projectSetupId' => $this->PROJECT_SETUP_ID, 'holdingPerson' => $holdingPerson);
        $data = $this->dep_mi__m_project_setup->getData($param);
        //showArrayValues($data);exit;
		$myview = $this->load->view('pmon_deposit/mi/project_data_view', $data, true);
		array_push($this->message, getMyArray(null, $myview));
		echo createJSONResponse($this->message);
		return;
	}
    //OK
	public function checkProjectCode(){
		$this->load->library('Check_latlong');
		$projectSetupId = $this->input->post('PROJECT_SETUP_ID');
		$parentProjectId = $this->input->post('PROJECT_ID');
		$arrFields = array('LATITUDE_D', 'LATITUDE_M', 'LATITUDE_S', 'LONGITUDE_D', 'LONGITUDE_M', 'LONGITUDE_S');
		$searchData = array();
		foreach ($arrFields as $field) {
			$searchData[$field] = $this->input->post($field);
		}
		//echo '>>'. 
		$latLongExists=0;
		$latLongExists=$this->check_latlong->check($searchData);
		if($latLongExists==1) {						   
			$arrResponse["message"] = 'Longitude / Latitude already exists';
		}else{
			$arrResponse["success"] = 1;
		}
		echo json_encode($arrResponse);
	}
	//OK
	public function saveProjectSetup($PROJECT_ID){
		 $this->load->model('pmon_dep/dep_mi__m_project_setup');
		//return;
		$this->PROJECT_SETUP_ID = (int) trim($this->input->post('PROJECT_SETUP_ID'));
		$this->PROJECT_ID = $PROJECT_ID;
		$editMode = (($this->PROJECT_SETUP_ID) ? TRUE : FALSE);
		$this->load->model('pmon_dep/dep_mi__t_locks');
		if($editMode) {
			//0-setup lock
			$lockStatus = $this->dep_mi__t_locks->getLockStatus(0, $this->PROJECT_SETUP_ID);
			if($lockStatus) {
				array_push(
					$this->message,
					getMyArray(
						false,
						'<button class="btn-large btn-danger" onclick="closeDialog();">
						Project Locked...</button>'
					)
				);
				echo createJSONResponse($this->message);
				return;
			}
		}
	
		//saveMode	0-save & edit	1-save & close	2-modify(save)
		$saveMode = $this->input->post('saveMode');
		/**Transaction starts here*/
		//$this->db->trans_begin(); // stopped fortesting
		/** __projects */
		$arrData = array();
		$arrProjectSetup = array();
		$arrProjectSetup['PROJECT_SETUP_ID'] = $this->PROJECT_SETUP_ID;
		$arrFields = $this->getFields($this->tblSetup);
		$oneTimeFields = array(
			'CE_ID', 'WORK_STATUS', 'DISTRICT_ID', 'PROJECT_SAVE_DATE', 'PROJECT_CODE', 'AA_FILE_URL', 'AA_USER_FILE_NAME',
			'LONGITUDE_D', 'LONGITUDE_M', 'LONGITUDE_S', 'LATITUDE_D', 'LATITUDE_M', 'LATITUDE_S'
		);
		$oneTimeFields1 = array(
			'BLOCK_ID','TEHSIL_ID'
		);
		foreach($arrFields as $field){
			if(in_array($field, $oneTimeFields1))
			{
				continue;
			}
			else if($editMode && in_array($field, $oneTimeFields)){
				continue;//skip fields
			}else if($field == 'AA_DATE' || $field == 'PROJECT_COMPLETION_DATE'){
				$arrProjectSetup[$field] = myDateFormat($this->input->post($field));
			}elseif($field == 'PROJECT_SAVE_DATE'){
				$arrProjectSetup[$field] = date("Y-m-d");
			}elseif($field == 'PROJECT_ID'){
				$arrProjectSetup[$field] = $this->PROJECT_ID;
			}else{
				if($this->input->post($field)){
					$arrProjectSetup[$field] = trim($this->input->post($field));
				}else{
					$arrProjectSetup[$field] = '';
				}
			}
		}	$arrProjectSetup['DISTRICT_ID']=trim($this->input->post('HEAD_WORK_DISTRICT_ID'));
		//showArrayValues($arrProjectSetup);exit; PROJECT_SETUP_DATA
		if($saveMode!=0)
		{	
			$arrProjectSetup['TEHSIL_ID']=trim($this->input->post('HEAD_WORK_TEHSIL_ID'));
			$arrProjectSetup['BLOCK_ID']=trim($this->input->post('HEAD_WORK_BLOCK_ID'));			
		}
	
		$arrData['PROJECT_SETUP_DATA'] = $arrProjectSetup;
		//$arrProjectSetup['startDate'] = myDateFormat($this->input->post('AA_DATE'));
		$processArrData = array();
		$processArrData['OFFICE_SDO_ID'] = $this->input->post('OFFICE_SDO_ID');
		$processArrData['DISTRICT_BENEFITED'] = $this->input->post('DISTRICT_BENEFITED');
		$processArrData['BLOCKS_BENEFITED'] = $this->input->post('BLOCKS_BENEFITED');
		$processArrData['ASSEMBLY_BENEFITED'] = $this->input->post('ASSEMBLY_BENEFITED');
		$processArrData['VILLAGES_BENEFITED'] = $this->input->post('VILLAGES_BENEFITED');
		$arrData['PROCESS_DATA'] = $processArrData;

		$arrRAA = array();

		if(($this->input->post('isRAA') == 1) && ($this->input->post('RAA_NO') != '')) {
			$this->RAA_ID = $this->input->post('RAA_PROJECT_ID');
			$arrData['RAA_ID'] = $this->RAA_ID;
			//$arrRAA['RAA_ID'] = $this->input->post('RAA_PROJECT_ID');
			$raaDate = myDateFormat($this->input->post('RAA_DATE'));
			$raaFieldNames = $this->dep_mi__t_locks->getRAAFields();
			for($i = 0; $i < count($raaFieldNames); $i++) {
				if($raaFieldNames[$i] == 'RAA_PROJECT_ID') {
					continue;
				}else if($raaFieldNames[$i] == 'PROJECT_SETUP_ID') {
					$arrRAA[$raaFieldNames[$i]] = $this->PROJECT_SETUP_ID;
				}else if($raaFieldNames[$i] == 'RAA_DATE')
					$arrRAA['RAA_DATE'] = $raaDate;
				else {
					// RAA Save status will be change according to Save and Lock status 0 forSave 1 forLock
					if($raaFieldNames[$i] == 'RAA_SAVE_STATUS')
						$arrRAA['RAA_SAVE_STATUS'] = 0;
					else if($raaFieldNames[$i] == 'RAA_SAVE_DATE')
						$arrRAA['RAA_SAVE_DATE'] = date('Y-m-d');
					else if($raaFieldNames[$i] == 'ADDED_BY')
						$arrRAA['ADDED_BY'] = 0;//Added through project setup module
					else {
						$arrRAA[$raaFieldNames[$i]] = $this->input->post($raaFieldNames[$i]);
					}
				}
			}
		}
		$arrData['RAA_DATA'] = $arrRAA;
		//showArrayValues($arrData['RAA_DATA']);
		//exit; PROJECT_SETUP_DATA
		$estimationFieldNames = $this->getEstimationStatusFields();
		//showArrayValues($estimationFieldNames);exit;
		$arrEstimationData = array();
		/* `ADDED_BY 0-through setup, 1-RAA setup */

		foreach($estimationFieldNames as $f){
			if($f == 'PROJECT_SETUP_ID') {
				$arrEstimationData[$f] = $this->PROJECT_SETUP_ID;
			}else if($f == 'LA_NA')
				$arrEstimationData[$f] = trim($this->input->post('LA_NA'));
			else if($f == 'FA_NA')
				$arrEstimationData[$f] = trim($this->input->post('FA_NA'));
			else{
			    if($this->input->post($f)==''){
                    $arrEstimationData[$f] =0;
                }else{
                    $arrEstimationData[$f] = trim($this->input->post($f));
                }
            }
		}//for
        $arrEstimationData['DRINKING_PURPOSE'] = $this->input->post('DRINKING_PURPOSE');
		$arrData['ESTIMATION_DATA'] = $arrEstimationData;
	
		$arrEstiBlockData = array();
		$arrEstiBlockData['IP_NA'] = $this->input->post('IP_TOTAL_NA');
		$arrEstiBlockData['BLOCKS_BENEFITED'] = $this->input->post('BLOCKS_BENEFITED');
	
		$arrEstiBlockData['BLOCK_EIP_K'] = $this->input->post('BLOCK_EIP_K');
		$arrEstiBlockData['BLOCK_EIP_R'] = $this->input->post('BLOCK_EIP_R');
		$arrEstiBlockData['BLOCK_AIP_K'] = $this->input->post('BLOCK_AIP_K');
		$arrEstiBlockData['BLOCK_AIP_R'] = $this->input->post('BLOCK_AIP_R');
		$arrData['arrEstiBlockData'] = $arrEstiBlockData;

		$eFieldNames = $this->getEstimationFields();
		$arrEstimatedQtyData = array();
		foreach($eFieldNames as $f){
			if($f == 'PROJECT_SETUP_ID') {
				//if(!$isExists)
				$arrEstimatedQtyData[$f] = $this->PROJECT_SETUP_ID;
			}else if($f == 'RAA_ID') {
				//if($this->RAA_ID) $data['RAA_ID'] = $this->RAA_ID;
				$arrEstimatedQtyData[$f] = '';
			}else
				$arrEstimatedQtyData[$f] = trim($this->input->post($f));
		}//for
		$arrData['arrEstimatedQtyData'] = $arrEstimatedQtyData;

		$sessionId = (((int) $this->input->post('SESSION_ID')) - 1);
		$achievementFieldNames = $this->getAchivementFields();
		$mStatusFields = $this->getFields($this->dep_mi__m_project_setup->tblSetupStatus);
		$arrAchievementData = array();
		foreach($achievementFieldNames as $f){
			if(!in_array($f, $mStatusFields)){
				if($this->input->post($f . '_ACHIEVE')){
					$arrAchievementData[$f] = $this->input->post($f.'_ACHIEVE');
				}else{
					$arrAchievementData[$f] = 0;//set status to NA
				}
			}
			if($f == 'SESSION_ID')
				$arrAchievementData[$f] = $sessionId;
			else if($f == 'PROJECT_SETUP_ID') {
				//
			}else if($f== 'SUBMISSION_DATE') {
				//skip
			}
		}
	 
		$arrData['arrAchievementData'] = $arrAchievementData;		   
		$arrLastStatusData = $arrTargetDates = array();

		$arrStatusFields = array(
			'LA_CASES_STATUS', 'FA_CASES_STATUS', 'INTAKE_WELL_STATUS', 'PUMPING_UNIT_STATUS',
			'PVC_LIFT_SYSTEM_STATUS', 'PIPE_DISTRI_STATUS', 'DRIP_SYSTEM_STATUS',
			'WATER_STORAGE_TANK_STATUS', 'FERTI_PESTI_CARRIER_SYSTEM_STATUS', 'CONTROL_ROOMS_STATUS',
            'DRILLINGWORK_STATUS',
            'HOUSING_PIPE_STATUS',
            'BLIND_PIPE_STATUS',
            'SLOTTED_PIPE_STATUS',
            'SUBMERSIBLE_STATUS',
		);
		foreach($arrStatusFields as $f)
			$arrLastStatusData[$f] = $this->input->post($f);

		$arrTargetDatesFields = array(
			'LA_DATE', 'FA_DATE', 'INTAKE_WELL_DATE', 'PUMPING_UNIT_DATE',
			'PVC_LIFT_SYSTEM_DATE', 'PIPE_DISTRI_DATE',	'DRIP_SYSTEM_DATE', 
			'WATER_STORAGE_TANK_DATE', 'FERTI_PESTI_CARRIER_SYSTEM_DATE', 'CONTROL_ROOMS_DATE',
            'DRILLINGWORK_DATE',
            'HOUSING_PIPE_DATE',
            'BLIND_PIPE_DATE',
            'SLOTTED_PIPE_DATE',
            'SUBMERSIBLE_DATE',
		);
		foreach($arrTargetDatesFields as $f)
			$arrTargetDates[$f] = myDateFormat($this->input->post($f));

		$arrData['arrTargetDatesFields'] = $arrTargetDatesFields;
		$arrData['arrTargetDates'] = $arrTargetDates;
		$arrData['arrLastStatusData'] = $arrLastStatusData;
		 
		$this->dep_mi__m_project_setup->saveProjectData($arrData);
        //save & show edit mode data
        $arr['PROJECT_SETUP_ID']= 0;
			$arr['PARENT_PROJECT_ID']=0;
		if($saveMode == 0) {
			//$this->PROJECT_SETUP_ID = (int) trim($this->input->post('PROJECT_SETUP_ID'));
			$arr['PROJECT_SETUP_ID']= $this->dep_mi__m_project_setup->getProjectSetupId();
			$arr['PARENT_PROJECT_ID']= $this->PROJECT_ID;
			
		} 
		 return $arr;
		/* dep_mi__t_locks
		if($this->db->trans_status()===FALSE){
			// generate an error... or use the log_message() function to log your error
			array_push($this->message, getMyArray(false, $this->db->log_message()));
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
		} // stopped fortesting
	
		
		*/
	}

	/**-----------------------------------------------*/
    public function getSDOOffices(){
        $eeid = $this->input->post('eeid');
        echo $this->dep_mi__m_project_setup->SDOofficeOptions($eeid);
    }
	//OK
    public function getBlockBenefitedList(){
        $dist_id = $this->input->post('dist_id');
        $projectId = $this->input->post('project_id');
        if(!is_array($dist_id))
            $dist_id = array($dist_id);

        //removing array values, so that blank
      //  $dist_id = array(); dep_mi__m_project_setup
        $recs = $this->db->select('PARENT_PROJECT_ID')
                ->from($this->dep_mi__m_project_setup->tblSetup)
                ->where('PROJECT_SETUP_ID', $projectId)
                ->get();
        $superProjectId = '';
        if($recs && $recs->num_rows()){
            $rec = $recs->row();
			$superProjectId = $rec->PARENT_PROJECT_ID;
			$recs->free_result();
        }
        $parentBlocks = $this->getBlockIdsBenefited($superProjectId);
        //list with select existing block otherwise only new selection will be saved
        $arecs = $this->db->get_where($this->dep_mi__m_project_setup->tblProjectsBlockServed, array('PROJECT_ID' => $this->input->post('project_id')));
        $bid = array();
        if($arecs && $arecs->num_rows()) {
            foreach ($arecs->result() as $rec) {
                array_push($bid, $rec->BLOCK_ID);
            }
			$arecs->free_result();
        }
       echo $this->mi__m_project_setup->getBlockOptions($dist_id, $bid, $parentBlocks);
	 
    }

    public function getAssemblyBenefitedList(){
        $assemblyId = $this->input->post('assembly_id');
        $projectId = $this->input->post('project_id');
        echo $this->dep_mi__m_project_setup->getAssemblyBenefitedList($assemblyId,$projectId);
    }

	//OK
    public function getVillagesByDistrict(){
        $DISTRICT_ID = $this->input->post('DISTRICT_ID');
        if(!is_array($DISTRICT_ID)) $DISTRICT_ID = array($DISTRICT_ID);
		 echo $this->dep_mi__m_project_setup->getVillagesByDistrictList($DISTRICT_ID);
   }									 

    public function getVillagesByDistrictProjectid(){
        $DISTRICT_ID = $this->input->post('DISTRICT_ID');
        $PARENT_PROJECT_ID = $this->input->post('PARENT_PROJECT_ID');

        if(!is_array($DISTRICT_ID)) $DISTRICT_ID = array($DISTRICT_ID);
        echo $this->mi__m_project_setup->getVillagesByDistrictList($DISTRICT_ID);
        //echo $this->mi__m_project_setup->getVillagesByDistrictListProject($DISTRICT_ID, $PARENT_PROJECT_ID);
    }
	
    //Locks
    public function deleteProject(){
        $this->PROJECT_SETUP_ID = (int)$this->input->post('PROJECT_SETUP_ID');
        $arrWhich = array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID);
        $recs = $this->db->get_where($this->dep_mi__m_project_setup->tblLock, $arrWhich);
        $goAhead = false;
        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
            if($rec->SETUP_LOCK == 0)
                $goAhead = true;
        }
        if(!$goAhead) {
            echo 'Project Can Not Be Deleted, Project Is Locked';
            return true;
        }
        //if goahead
        $this->db->trans_begin();
        $countDeleted1 = 0;
        $countDeleted2 = 0;
        $countDeleted3 = 0;
        //1. delete from promon
        $projectTables = array(
            $this->dep_mi__m_project_setup->tblEstimatedStatus,
            $this->dep_mi__m_project_setup->tblMonthlyDataRemark,
            'mi__t_extensions',
            'mi__t_monthlydata',
            'mi__t_progress',
            'mi__t_yearlytargets',
            'mi__t_achievements',
            'mi__t_estimated_qty',
            'mi__t_target_date_completion',
            'mi__t_raa_project',
            'mi__m_status_date',
            'mi__m_setup_status',
            'mi__m_project_setup',
            'mi__t_locks',
            'mi__t_lock_logs'
        );
        $this->db->where('PROJECT_SETUP_ID', $this->PROJECT_SETUP_ID);
        $this->db->delete($projectTables);
        $countDeleted1 = $this->db->affected_rows();													 
        if($this->db->trans_status() === FALSE) {
            echo 'Unable to Delete Project Data...Roll Back';
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            echo 'Project Record Deleted...';
        }
    }

    public function lockProject(){
		/*if(!IS_LOCAL_SERVER) {
            $this->load->library('mycurl');
            $serverStatus = $this->mycurl->getServerStatus();
            if($serverStatus == 0) {
                echo 'Unable to lock. E-work Server Not responding. Try after sometime...';
                return;
            }
        }*/
        $this->PROJECT_SETUP_ID = $this->input->post('project_setup_id');
        $projectId = $this->PROJECT_SETUP_ID;
        //echo "In project mi :: projectId= ".$projectId; exit;
        $status = $this->setLock();
        if($status) {
            $pData = $this->dep_mi__m_project_setup->getEWorksDetails($projectId);
            $params = array(
                'mode' => 'setup',
                "projectCode" =>$pData['PROJECT_CODE'],
                "Ddocode"=>$pData['EWORK_ID'],
                "PromonID" => $pData['PROJECT_CODE'],
                "lDate" => '',
                "PLock" => 5,
                "WorkName" => $pData['PROJECT_NAME'],
                "WorkNameHindi" => $pData['PROJECT_NAME_HINDI'],
                "progressPerc" => $this->mi__m_project_setup->getMonthlyProgress($projectId),
                "AANo" => $pData['AA_NO'],
                "AADate" => $pData['AA_DATE'],
                "AAAmount" => $pData['AA_AMOUNT'],
                "RAANo" => $pData['RAA_NO'],
                "RAADate" => $pData['RAA_DATE'],
                "RAAAmount" => $pData['RAA_AMOUNT'],
                "RAASerialNo" => $pData['RAA_SNO']
            );
            //showArrayValues($params);
            /*if(!IS_LOCAL_SERVER) {
                $result = $this->mycurl->savePromonData($params);
                //echo $result;
                $obj = json_decode($result);
                if($obj->{'success'}) {
                    //echo "Setup Data Sent to E-Works Server.";
                }

            }*/
            $this->mi__m_project_setup->updateLockedStatus($params);
            $this->mi__m_project_setup->lockRAA(0);//RAA Lock
        }    
        echo $status;
    }
	
    public function getValidationForLock(){
		$this->PROJECT_ID = $this->input->post('project_id');
		echo $this->createButtonSet();         
    }

    public function removeAARAAFile(){
        $PROJECT_SETUP_ID = $this->input->post('PROJECT_SETUP_ID');
        $mode = $this->input->post('mode');
        //1-delete file from directory
        //2-update table
        if($mode == 1) {
            $this->db->select('AA_FILE_URL');
            $this->db->from('mi__m_project_setup');
            $this->db->where('PROJECT_SETUP_ID', $PROJECT_SETUP_ID);
            $recs = $this->db->get();
            if($recs && $recs->num_rows()) {
                $rec = $recs->row();
                $aaFileName = $rec->AA_FILE_URL;
                $filePath = FCPATH . 'aa_raa_uploads' . DIRECTORY_SEPARATOR . $aaFileName;
                if(file_exists($filePath)) {
                    if(@unlink($filePath)) {
                        $data = array('AA_FILE_URL' => '', 'AA_USER_FILE_NAME' => '');
                        $arrWhere = array('PROJECT_SETUP_ID' => $PROJECT_SETUP_ID);
                        @$this->db->update('mi__m_project_setup', $data, $arrWhere);

                        array_push($this->message, getMyArray(true, 'File Deleted'));
                        //showArrayValues($this->message);
                        echo createJSONResponse($this->message);
                        return;
                    }
                }
            }
        }elseif($mode == 2) {
            $this->db->select('RAA_FILE_URL');
            $this->db->from('mi__t_raa_project');
            $this->db->where(array('PROJECT_SETUP_ID' => $PROJECT_SETUP_ID, 'ADDED_BY' => '0'));
            $recs = $this->db->get();
            if($recs && $recs->num_rows()) {
                $rec = $recs->row();
				$recs->free_result();
                $raaFileName = $rec->RAA_FILE_URL;
                $filePath = FCPATH . 'aa_raa_uploads' . DIRECTORY_SEPARATOR . $raaFileName;
                if(file_exists($filePath)) {
                    if(@unlink($filePath)) {
                        $data = array('RAA_FILE_URL' => '', 'RAA_USER_FILE_NAME' => '');
                        $arrWhere = array('PROJECT_SETUP_ID'=>$PROJECT_SETUP_ID, 'ADDED_BY'=>'0');
                        @$this->db->update('mi__t_raa_project', $data, $arrWhere);
                        array_push($this->message, getMyArray(true, 'File Deleted'));
                        //showArrayValues($this->message);
                        echo createJSONResponse($this->message);
                        return;
                    }
                }
            }
        }
    }
	 								   
    public function getAASessionId(){
        $date = myDateFormat($this->input->post('date'));
        $sessionId = getSessionIdByDate($date);
        echo $sessionId;
    }
    /**
     * @todo: update logic/code of this function saveIrrigationPotential()
     */
    private function saveIrrigationPotential(){
        //1.Designed Irrigation Potential
        //2.Irrigation Potential Created
        $projId = $this->mi__m_project_setup->getParentProjectId($this->PROJECT_SETUP_ID);
        $data = array(
            'PROJECT_ID' => $projId,
            'MI_DESIGNED_KHARIF' => 0,
            'MI_DESIGNED_RABI' => 0
		);
        //1.Designed Irrigation Potential -
        //get all estimated IR
        $arrWhich = array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID);
        //$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
        $this->db->order_by('ESTIMATED_QTY_ID', 'DESC');
        $this->db->limit(1, 0);
        $recs = $this->db->get_where('mi__t_estimated_qty', $arrWhich);
        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
			$recs->free_result();
            $data['MI_DESIGNED_KHARIF'] = $rec->KHARIF;
            $data['MI_DESIGNED_RABI'] = $rec->RABI;
        }
        //achievement i.e., created data
        $dataa = array('DI_KARIF_CROP' => 0, 'DI_RABI_CROP' => 0, 'DI_TOTAL' => 0);
        //get achievement
        //if monthly exists then
        $recs = $this->db->get_where('mi__t_achievements', $arrWhich);
        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
			$recs->free_result();
            $dataa['MI_CREATED_KHARIF'] = $rec->KHARIF;
            $dataa['MI_CREATED_RABI'] = $rec->RABI;
        }
        $recs = $this->db->get_where('projects__irrigation_potential_data', array('PROJECT_ID' => $projId));
        if($recs && $recs->num_rows()) {
			$recs->free_result();
            @$this->db->update('projects__irrigation_potential_data', $dataa, array('PROJECT_ID' => $projId));
        }else {
            $data['PROJECT_ID'] = $projId;
            @$this->db->insert('projects__irrigation_potential_data', $dataa);
        }
        //save general data
        $data['MI_CREATED_KHARIF'] = $dataa['MI_CREATED_KHARIF'];
        $data['MI_CREATED_RABI'] = $dataa['MI_CREATED_RABI'];
        $recs = $this->db->get_where('projects__irrigation_potential_data', array('PROJECT_ID' => $projId));
        if($recs && $recs->num_rows()) {
			$recs->free_result();
            @$this->db->update('projects__irrigation_potential_data', $data, array('PROJECT_ID' => $projId));
        }else {
            $data['PROJECT_ID'] = $projId;
            @$this->db->insert('projects__irrigation_potential_data', $data);
        }
    }
}
