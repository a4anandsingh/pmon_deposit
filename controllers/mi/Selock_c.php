<?php include_once('Report_library.php');
class Selock_c extends Report_library{
	var $proID, $proCompStatus, $mySessionIDs, $lastSessionID, $PROJECT_SETUP_ID;

    function __construct(){
		parent::__construct();
		$this->proCompStatus = 0; // If 0 Black - If 1 Red
		$this->mySessionIDs = array();
		$this->PROJECT_SETUP_ID = 0;
        date_default_timezone_set('Asia/Kolkata');
	}

	function index(){
		$data = array();
		$data['message'] = '';
		$data['page_heading'] = pageHeading('PROMON (Micro Irrigation) - Division Lock');
		$this->load->library('office_filter');
		$data['office_list'] = $this->office_filter->office_list();
		$data['sessionOptions'] = $this->getSessionDropDown();
		$data['sessionOptionsReport'] = $this->getSessionDropDownReport();
		$mList = array();
		$this->getMonthList($mList);
		//showArrayValues($mList);
		$data['monthList'] = $mList;
		$data['lastSessionID'] = $this->lastSessionID ;
		$this->load->view('mi/selock/report_index_view', $data);
	}

    public function showOfficeFilterBox(){
		//$data['instance_name'] = 'search_office';
		$data = array();
		$data['prefix'] = 'SEARCH_';
		$data['show_sdo'] = FALSE;
		$data['row'] = '<input type="hidden" name="SEARCH_SUB_TYPE_ID" id="SEARCH_SUB_TYPE_ID" value="25" />';
		$this->load->view('setup/office_filter_view', $data);
    }

    private function getOfficeData($mode=false){
		$arrDate = explode('-', $this->input->post('REPORT_MONTH_YEAR'));
		$this->session->set_userdata(array('PROJECT_TYPE_ID'=>$this->input->post('SEARCH_PROJECT_TYPE_ID')));
		$arrData = array(
			'SESSION_ID' => $this->input->post('REPORT_SESSION_ID'), 
			'SEARCH_SUB_TYPE_ID' => $this->input->post('SEARCH_SUB_TYPE_ID'),
			"CE_ID" => $this->input->post('SEARCH_CE_ID'), 
			"SE_ID" => $this->input->post('SEARCH_SE_ID'),
			"EE_ID" => $this->input->post('SEARCH_EE_ID'), 
			"SDO_ID" => $this->input->post('SEARCH_SDO_ID'),
			"YEAR" => (int) $arrDate[1], 
			"MONTH" => (int) $arrDate[0],
			"FINANCIAL_MONTH" => $this->getFinancialMonthFromMonth((int) $arrDate[0]),
			'lastMonth'=> $this->input->post('lastMonth'),
			'summaryReport'=> $mode
		);
		$arrData['REPORT_DATE'] = $arrData['YEAR'].'-'.str_pad($arrData['MONTH'], 2, "0", STR_PAD_LEFT).'-01';
		$arrData['REPORT_LAST_DATE'] = date("Y-m-t", strtotime(
			$arrData['YEAR'].'-'.str_pad($arrData['MONTH'], 2, "0", STR_PAD_LEFT).'-01')
		);
		$sessionId = $arrData['SESSION_ID'];
		$arrData['PREV_SESSION_ID'] = $sessionId-1; 
		$arrData['SESSION'] = $this->setSessionData($sessionId);
		return $arrData;
	}

    private function setSessionData($sessionId){
		$arrData = array();
		$this->db->where_in('SESSION_ID', array($sessionId, $sessionId-1));
		$recs = $this->db->get('__sessions');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				if($rec->SESSION_ID==$sessionId){
					$arrData['CURRENT_START_YEAR'] = $rec->SESSION_START_YEAR;
					$arrData['CURRENT_START_DATE'] = $rec->START_DATE;
					$arrData['CURRENT_END_YEAR'] = $rec->SESSION_END_YEAR;
					$arrData['CURRENT_END_DATE'] = $rec->END_DATE;
					$arrData['CURRENT_SESSION'] = $rec->SESSION_START_YEAR.'-' .str_replace('20', '', $rec->SESSION_END_YEAR);
				}else{
					$arrData['PREV_START_YEAR'] = $rec->SESSION_START_YEAR;
					$arrData['PREV_START_DATE'] = $rec->START_DATE;
					$arrData['PREV_END_YEAR'] = $rec->SESSION_END_YEAR;
					$arrData['PREV_END_DATE'] = $rec->END_DATE;
				}
			}
		}
		return $arrData;
	}

    private function getSessionDropDown(){
		$cMonth = date("n");
		$cYear = date("Y");
		$sYear = ($cMonth<4)? $cYear : ($cYear+1);
		$this->db->select('SESSION_ID, SESSION, IS_CURRENT');
		$this->db->where('SESSION_ID >=', 8)->where('SESSION_END_YEAR <=', $sYear);
		$recs = $this->db->get('__sessions');
		$opt = array();
		array_push(
			$opt, 
			'<option value="0" >No Session</option><option value="9999" >All Session</option>'
		);
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push(
					$opt, 
					'<option value="'.$rec->SESSION_ID.'" >'.
					$rec->SESSION.
					'</option>'
				);
			}
		}
		//$this->db->last_query();
		return implode('', $opt);
	}

    private function getSessionDropDownReport(){
		$cDay = date("j");
		$cMonth = date("n");
		$cYear = date("Y");
		$sessionId = $this->getSessionID($cMonth, $cYear);
		if($cMonth==4) $sessionId--;
        //$settingsRec = $this->getEESELockSettings();
		$this->db->select('SESSION_ID, SESSION, IS_CURRENT, SESSION_START_YEAR, SESSION_END_YEAR');
		$this->db->where('SESSION_ID >=', 8)->where('SESSION_ID <=', $sessionId);
		$recs = $this->db->get('__sessions');
		$opt = array();
		$tCount = 0;
		array_push($opt, '');
		if($recs && $recs->num_rows()){
			$tCount = $recs->num_rows();
			$i = 1;
			foreach($recs->result() as $rec){
				array_push(
					$this->mySessionIDs, 
					array(
						'ID'=>$rec->SESSION_ID, 
						'sYear'=>$rec->SESSION_START_YEAR, 
						'eYear'=>$rec->SESSION_END_YEAR
					)
				);
				array_push(
					$opt, 
					'<option value="'.$rec->SESSION_ID.'" '.
					( ($sessionId==$rec->SESSION_ID)? 'selected="selected"':'').'>'.
					$rec->SESSION.
					'</option>'
				);
				$this->lastSessionID = ($i==$tCount) ? $rec->SESSION_ID: 0;
				$i++;
			}
		}
		return implode('', $opt);
	}

    private function getValidMonth(){
		//current day
		$day = (int)date("j");
		$mm = (int)date("n");
		$yy = (int)date("Y");
		//if day is >10 then valid month = curMonth-1
		$settingsRec = $this->getEESELockSettings();
		if($day>=$settingsRec->LOCK_START_DAY_SE){
			if($mm==1){
				$yy--;
				$mm = 12;
			}else{
				$mm--;
			}
			return ( $yy."-".str_pad($mm, 2,"0", STR_PAD_LEFT).'-01');
		}else if($day<=$settingsRec->LOCK_START_DAY_SE){
			$mm = (($mm==1)? 11: (($mm==2)? 12: ($mm-1)));
			return (date("Y-").str_pad($mm, 2,"0", STR_PAD_LEFT).'-01');
		}else{
			return date("Y-m").'-01';
		}
	}

    private function getMonthList(&$mList){
		$mList = array();
		$vlist = '';
		foreach($this->mySessionIDs as $sid){
			if($sid['ID']==$this->session->userData('CURRENT_SESSION_ID')){
				$mDay = (int) date("d");
				$mMonth = (int) date("m");
				$goAhead = true;
				if($mMonth==4){
					if ($mDay<=10) {
						//nothing to do
						$goAhead = false;
					}
				}
				if($goAhead){
					$financialMonth = $this->getFinancialMonthFromMonth($mMonth);
					$financialMonth = (($mDay>13) ?  $financialMonth: $financialMonth-1);
					for($i=1; $i<=$financialMonth;$i++){
						$myMonth = (($i<=9)? ($i+3): ($i-9));
						if($i>9){
							$showYear = $sid['eYear'];
						}else{
							$showYear = $sid['sYear'];
						}
						//$j = ($i<9) ? ($i+4) : ($i-8);
						$dt = DateTime::createFromFormat('!m', $myMonth);
						array_push(
							$mList,
							array(
								'SESSION_ID'=>$sid['ID'],
								'OPTION_VALUE'=>$myMonth.'-'.$showYear,
								'OPTION_TEXT'=>$dt->format('F').', '.$showYear
							)
						);
					}
				}
			}else{
				for($i=0; $i<12;$i++){
					$j = ($i<9) ? ($i+4) : ($i-8);
					$dt = DateTime::createFromFormat('!m', $j);
					array_push(
						$mList,
						array(
							'SESSION_ID'=>$sid['ID'],
							'OPTION_VALUE'=>$j.'-'.(($i>8)? $sid['eYear'] : $sid['sYear']),
							'OPTION_TEXT'=>$dt->format('F').', '.(($i>8)? $sid['eYear'] : $sid['sYear'])
						)
					);
					//$vlist .= '<option value="'.$j.'-'.(($i>8)? $sid['sYear']:$sid['eYear']).'">'.$dt->format('F').'</option>';
				}
			}
		} 
		//return $vlist;
	}

    private function checkTarget($searchData){
		$projectIDs = $this->getOnGoingOrNewProjectID($searchData);
		if(count($projectIDs)==0) return 0;
		$targetSessionId = $searchData['SESSION_ID']+1;

		/**
         * check for project which are going to be completed in the current month
        */
		$iCount = 0;
		$this->db->where_in('PROJECT_SETUP_ID', $projectIDs);
		//$this->db->where_in('PROJECT_ID', $projectIDs);
		$this->db->select('PROJECT_SETUP_ID, TARGET_LOCK_SESSION_ID, TARGET_EXISTS, IS_COMPLETED');
		$recs = $this->db->get('mi__t_locks');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				//if not in completed status
				if($rec->IS_COMPLETED==0){
					if($rec->TARGET_LOCK_SESSION_ID!=$targetSessionId)
						$iCount++;
				}
			}
		}
		return $iCount;
	}

    private function getEESELockSettings(){
		$recs = $this->db->get('__settings');
		if($recs && $recs->num_rows()){
			return $recs->row();
		}
		return FALSE;
	}

    public function physical_progress(){
		//date_default_timezone_set('Asia/Kolkata');
		//manipulate office
		$searchData = $this->getOfficeData();
		$sessionID = $searchData['SESSION_ID'];
		//echo 'FY:'.$mSearchMonth.', '.$mSearchYear.'::'.$sessionID.':FY';
		$data = array();
		$reportData = array();
		$arrProjectIDs = array();
		$data['validMonth'] = $this->getValidMonth();
		$data['searchData'] = $searchData;
		$canSELock = FALSE;
		$settingsRec = $this->getEESELockSettings();
		$currentDay = date("j");
		$currentDateValue = strtotime("now");
      	$lockStartDateValue = strtotime( 
			date("Y-m-").str_pad($settingsRec->LOCK_START_DAY_SE, 2, '0', STR_PAD_LEFT)." 18:00:00"
		);
      	//.$settingsRec->LOCK_END_TIME_SE
		$lockEndDateValue = strtotime(
			date("Y-m-").str_pad($settingsRec->LOCK_END_DAY_SE, 2, '0', STR_PAD_LEFT)." 18:00:00"
		);
      //echo $this->session->userdata('CURRENT_OFFICE_ID');
      //if( in_array($searchData['EE_ID'], array(37,38, 40))) $data['canSELock'] = 1;
      $arrEE = array();
      if( in_array($searchData['EE_ID'], $arrEE)){
          $lockStartDateValue = strtotime( 
              date("Y-m-").str_pad($currentDay, 2, '0', STR_PAD_LEFT)//." ".$settingsRec->SAVE_END_TIME_EE
          );
          $lockEndDateValue = strtotime(
              date("Y-m-").str_pad($currentDay, 2, '0', STR_PAD_LEFT)." ".$settingsRec->LOCK_END_TIME_SE
          );
       }
		//if current date is greater or eq to ee save day
		if($currentDateValue>=$lockStartDateValue){
			//if current date is less then ee save end time
			if($currentDateValue<=$lockEndDateValue){
				$canSELock = TRUE;
			}
		}
		$isDebug = 0;
		if($isDebug){
			showArrayValues($settingsRec);
			echo 'currentDateValue:'.date("d-m-Y", $currentDateValue)."\n".
				'lockStartDateValue:'.date("d-m-Y H:i:s", $lockStartDateValue)."\n".
				'lockEndDateValue:'.date("d-m-Y H:i:s", $lockEndDateValue)."\n".
				'currentDay:'.$currentDay."\n".
				' canEELock:'.$canSELock."\n";
		}
		$data['canSELock'] = $canSELock;
      	if($searchData['EE_ID']==0) {
      	    $data['canSELock'] = 1;
        }
        if( in_array($searchData['EE_ID'], $arrEE)) {
            $data['canSELock'] = 1;
        }

      	//echo $data['validMonth'] ;
		//$time_start = microtime_float();
		if($searchData['MONTH']==3 && date("d")>26){
			//Check for Target Locked or Not
			$countTarget = $this->checkTarget($searchData);
			if($countTarget){
				echo '<div class="ui-state-error" style="padding:10px"><h1>
				<span class="cus-unlock"></span> '. 
				$countTarget.' Project\'s Financial & Physical Target Not Locked for the Session 20'.
				$this->getSessionYear($sessionID+1).'...</h1></div>';
				return;
			}
		}
		//$monthDate = $mSearchYear.'-'.str_pad($mSearchMonth, 2, "0", STR_PAD_LEFT).'-01';
		$arrWhere = array(
			'EE_ID'=> $searchData['EE_ID'], 
			'LOCKED_MONTH'=>$searchData['REPORT_DATE'] /*,
			'PROJECT_SUB_TYPE_ID'=>$searchData['SEARCH_SUB_TYPE_ID']*/
		);
		$SELockTableName = 'mi__t_selocks';
		$this->db->order_by('LOCK_DATE', 'DESC');
		$this->db->limit(1, 0);
		$recs = $this->db->get_where($SELockTableName, $arrWhere);

		$data['isLocked'] = false;
		$data['lockData'] = false;
		if($recs && $recs->num_rows()) {
			$data['isLocked'] = true;
			$data['lockData'] = $recs->row();
		}

		//print_r($searchData);
		$projectData = $this->getProjectRecordsForPP($searchData);
        //echo "alsdjfkkkkkkkkkkk".$projectData;
		//print_r($projectData);
		//exit;

		//showArrayValues($projectData);
		if($projectData){
			foreach($projectData as $rec)
				array_push($arrProjectIDs, $rec->PROJECT_SETUP_ID);
		}
		$projectCount = count($arrProjectIDs);
		$maxMonthlyLockDate = '0000-00-00';
      	//showArrayValues($arrProjectIDs);
		//echo 'ProjCount:'.$projectCount;
		if(!$projectCount){
			$data['reportData'] = $reportData;
			$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
			$this->load->view('mi/selock/physical_progress_view', $data);
			return;
		}
		$commonFields = $this->getCommonFields();
		//$data['EE_ID'] = $searchData['EE_ID'];
		//$data['MONTH_DATE'] = $monthDate;
		//get overall target
		$mOverAllTarget = $this->projectTarget($searchData['SESSION_ID'], $arrProjectIDs);
		//showArrayValues($mOverAllTarget);
		$arrTarget = array();
		if($mOverAllTarget){
			foreach($mOverAllTarget as $mTargetRec){
				$arrMyRecord = array();
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$arrMyRecord[ $commonFields[$iCount] ] = $mTargetRec->{ $commonFields[$iCount]};
				$arrTarget[$mTargetRec->PROJECT_SETUP_ID] = $arrMyRecord;
			}
		}
		//get target in that month
		$mTargetInMonth = $this->TargetInMonth($searchData, $arrProjectIDs);
		$arrTargetInMonth = array();
		if($mTargetInMonth){
			foreach($mTargetInMonth as $mRec){
				$arrMyRecord = array();
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$arrMyRecord[ $commonFields[$iCount] ] = $mRec->{ $commonFields[$iCount]};
				$arrTargetInMonth[$mRec->PROJECT_SETUP_ID] = $arrMyRecord;
			}
		}
		//PROJECT_ID, YEAR, PROGRESS is taken in $mMonthlyStatus
		$mMonthlyStatus = $this->getMonthlyProgress($searchData, $arrProjectIDs);
		//$mMonthlyStatus = $this->MonthlyStatus($sessionID, $searchData, $arrProjectIDs);
		$arrMonthlyProgress = array();
		if($mMonthlyStatus){
			foreach($mMonthlyStatus as $mARec)
				$arrMonthlyProgress[$mARec->PROJECT_SETUP_ID] = $mARec->PROGRESS;
		}
		$mEstimatedStatus = $this->getEstimatedStatusRecord($arrProjectIDs);
		//showArrayValues($mEstimatedStatus);
		$mEstimate = $this->getEstimatedRecord($searchData, $arrProjectIDs);
		//showArrayValues($mEstimate);
		$arrEstimation = array();
		if($mEstimate){
			foreach($mEstimate as $mRec){
				$arrMyRecord = array();
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$arrMyRecord[ $commonFields[$iCount] ] = $mRec->{ $commonFields[$iCount]};
				$arrEstimation[$mRec->PROJECT_SETUP_ID] = $arrMyRecord;
			}
		}
		//echo $sessionID;
		$mAchievementEndOfLastFY = $this->getAchievementEndOfLastFY($searchData, $arrProjectIDs);
		//showArrayValues($mAchievementEndOfLastFY );
		$arrAchieveLFY = array();
		if($mAchievementEndOfLastFY){
			foreach($mAchievementEndOfLastFY as $mARec){
				$arrMyRecord = array();
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$arrMyRecord[ $commonFields[$iCount] ] = $mARec->{ $commonFields[$iCount]};
				$arrAchieveLFY[$mARec->PROJECT_SETUP_ID] = $arrMyRecord;
			}
		}
		//echo $monthDate;
		$achiv = $this->achivementEndOfMonth($arrProjectIDs, $searchData['REPORT_DATE'], $searchData['SESSION_ID']);
		$arrAchieveEndOfMonth = array();
		if($achiv){
			foreach($achiv as $mARec){
				$arrMyRecord = array();
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$arrMyRecord[ $commonFields[$iCount] ] = $mARec->{$commonFields[$iCount]};
				$arrAchieveEndOfMonth[$mARec->PROJECT_SETUP_ID] = $arrMyRecord;
			}
		}

		$lockRecords = $this->getLockRecords($arrProjectIDs);
		$arrLockRecords = array();
		$arrMaxMonthlyLockDate = array();
		if($lockRecords){
			foreach($lockRecords as $rec){
				if($maxMonthlyLockDate<$rec->SUBMISSION_DATE)
					$arrMaxMonthlyLockDate[$rec->PROJECT_SETUP_ID] = $rec->SUBMISSION_DATE;
				$arrLockRecords[$rec->PROJECT_SETUP_ID] = array(
					'MONTH_LOCK'=>$rec->MONTH_LOCK,
					'MONTHLY_EXISTS'=>$rec->MONTHLY_EXISTS,
					'SETUP_LOCK'=>$rec->SETUP_LOCK,
					'IS_COMPLETED'=>$rec->IS_COMPLETED,
					'SUBMISSION_DATE'=>$rec->SUBMISSION_DATE
				);
			}
		}
		//showArrayValues($lockRecords );
		//echo 'BB:'.count($mAchievementEndOfLastFY).':BB';
		$i = 1;
		$mData = array();
		$mRealProgress = 0;
		//echo($mProjectRecords);
		//showArrayValues($lockRecords);
		//showArrayValues($arrLockRecords);
		foreach($projectData as $prec){
			$projectId =  $prec->PROJECT_SETUP_ID;
			if($projectId==3005) continue;
			$isLockExists = false;
			$myData = array();
			//showArrayValues($arrLockRecords['p'.$projectId]);
			if( array_key_exists($projectId, $arrLockRecords)){
				$isLockExists = true;
				if($maxMonthlyLockDate<$arrLockRecords[$projectId]['SUBMISSION_DATE'])
					$maxMonthlyLockDate = $arrLockRecords[$projectId]['SUBMISSION_DATE'];
				$myData['lockData'] = $arrLockRecords[$projectId];
			}else{
				$myData['lockData'] = false;
			}
			/*if( array_key_exists('p'.$projectId, $arrLockRecords)){
				$isLockExists = true;
				if($maxMonthlyLockDate<$arrLockRecords['p'.$projectId]['SUBMISSION_DATE'])
					$maxMonthlyLockDate = $arrLockRecords['p'.$projectId]['SUBMISSION_DATE'];
				$myData['lockData'] = $arrLockRecords['p'.$projectId];
			}else{
				$myData['lockData'] = false;
			}*/
			$estimation = array();
			$progressLastFY = array();
			$overallTargetForFY = array();
			$targetToEndOFMonth = array();
			$achieveEndOfMonth = array();
			$achieveEndOfLFY = array();
			
			$mData[$i]['SNO'] = $i;
			$mData[$i]['PROJECT_NAME'] = $prec->WORK_NAME.'<br /><br />'.$projectId;
			$mData[$i]['OFFICE_EE_ID'] = $prec->OFFICE_EE_ID;
			$mData[$i]['OFFICE_SE_ID'] = $prec->OFFICE_SE_ID;
			$mData[$i]['OFFICE_CE_ID'] = $prec->OFFICE_CE_ID;
			$mData[$i]['OFFICE_SE_NAME'] = $prec->OFFICE_SE_NAME;
			$mData[$i]['OFFICE_CE_NAME'] = $prec->OFFICE_CE_NAME;
			$mData[$i]['OFFICE_NAME'] = $prec->OFFICE_NAME;
			//$mData[$i]['PROJECT_SUB_TYPE'] = $prec->PROJECT_SUB_TYPE;
			$mData[$i]['TARGET_DATE'] = myDateFormat($prec->PROJECT_COMPLETION_DATE);
			//ESTIMATION
			if(array_key_exists($projectId, $arrEstimation)){
				$estimation = $arrEstimation[$projectId];
			}else{
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$estimation[ $commonFields[$iCount] ] = 0;
			}
			//OVERALL TARGET
			if( array_key_exists($projectId, $arrTarget)){
				$overallTargetForFY = $arrTarget[$projectId];
			}else{
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$overallTargetForFY[ $commonFields[$iCount] ] = 0;
			}
			if($projectId==3238) showArrayValues($overallTargetForFY);
			//ACHEIVEMENT END OF LAST FY
			for($iCount=0; $iCount<count($commonFields); $iCount++){
				$achieveEndOfMonth[ $commonFields[$iCount] ] = 0;
			}
			//showArrayValues($mAchievementEndOfLastFY);
			if(array_key_exists($projectId, $arrAchieveLFY)){
				$achieveEndOfLFY = $arrAchieveLFY[$projectId];
			}else{
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$achieveEndOfLFY[ $commonFields[$iCount] ] = 0;
			}
			/*if(!$isLockExists){
				$myData['lockData'] = array(
					'MONTH_LOCK'=>'0000-00-00',
					'MONTHLY_EXISTS'=>'0000-00-00',
					'SETUP_LOCK'=>$Lrec->SETUP_LOCK,
					'IS_COMPLETED'=>$Lrec->IS_COMPLETED,
					'SUBMISSION_DATE'=>$Lrec->SUBMISSION_DATE
				);
			}*/
			$myData['PROJECT_SETUP_ID'] = $projectId;
			//showArrayValues($myData['lockData']);
			//showArrayValues($achieveEndOfLFY);
			//$PROGRESS = 0;
			$myData['progress'] = 0;
			if(array_key_exists($projectId, $arrMonthlyProgress)){
				$myData['progress'] = $arrMonthlyProgress[$projectId];
			}
			//initialise row
			$mMTRow = (object) array(
				'LA_NO'=>0, 'LA_HA'=>0, 'FA_HA'=>0,
				'HEAD_WORKS_EARTHWORK'=>0, 'HEAD_WORKS_MASONRY'=>0, 'STEEL_WORKS'=>0, 
				'CANAL_EARTHWORK'=>0, 'CANAL_LINING'=>0, 'CANAL_STRUCTURES'=>0, 
				'IRRIGATION_POTENTIAL'=>0, 'FINANCIAL'=>0
			);
			if(array_key_exists($projectId, $arrTargetInMonth)){
				$targetToEndOFMonth = $arrTargetInMonth[$projectId];
			}else{
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$targetToEndOFMonth[ $commonFields[$iCount] ] = 0;
			}
			//$achieveRow = '';
			//[ ACHEIVEMENT END OF MONTH ]//
			//initialize
			
			if(array_key_exists($projectId, $arrAchieveEndOfMonth)){
				$achieveEndOfMonth = $arrAchieveEndOfMonth[$projectId];
			}else{
				for($iCount=0; $iCount<count($commonFields); $iCount++)
					$achieveEndOfMonth[ $commonFields[$iCount] ] = 0;
			}
			/*$arrItem = array(
				'FA_HA', 'FA_COMPLETED_HA', 
				'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
				'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
				'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES'
			);*/
            $arrItem = array(
                'FA_HA', 'FA_COMPLETED_HA',
                'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
                'L_EARTHWORK', 'C_MASONRY','C_PIPEWORK','C_DRIP_PIPE','C_WATERPUMP','K_CONTROL_ROOMS'
            );
			//showArrayValues($estimation);

			for($iCount=0; $iCount<count($arrItem); $iCount++){
				$itemName = $arrItem[$iCount];
				if( substr($itemName, 0, 3)=='LA_'){
					@$na_value = (int) $mEstimatedStatus[$projectId]['LA_NA'];
				}else if( substr($itemName, 0, 3)=='FA_'){
					@$na_value = (int) $mEstimatedStatus[$projectId]['FA_NA'];
				}else{
					@$na_value = (int) $mEstimatedStatus[$projectId][$itemName.'_NA'];
				}
				if($na_value==1){
					$myData[$itemName]['estQuantity'] = 'NA';
					$myData[$itemName]['progLastFY'] = 'NA';
					$myData[$itemName]['overallTargetFY'] = 'NA';
					$myData[$itemName]['TargetEndMonthFY'] = 'NA';
					$myData[$itemName]['achivEndMonthFY'] = 'NA';
					$myData[$itemName]['targetProgEndMonth'] = 'NA';
					$myData[$itemName]['progOverallYearly'] = 'NA';
					$myData[$itemName]['overallAchivEndMonth'] = 'NA';
					$myData[$itemName]['percentOverallAchivEndMonth'] = 'NA';
					continue;
				}
				if($itemName=='LA_COMPLETED_NO' || $itemName=='LA_COMPLETED_HA' || $itemName=='FA_COMPLETED_HA'){
					$arrIName = array(
						'LA_COMPLETED_HA'=>'LA_HA',
						'LA_COMPLETED_NO'=>'LA_NO',
						'FA_COMPLETED_HA'=>'FA_HA'
					);
					//echo '..'.$arrIName[$itemName].'..';
					$myData[$itemName]['estQuantity'] = $myData[$arrIName[$itemName]]['overallAchivEndMonth'];
					$myData[$itemName]['progLastFY'] = (($achieveEndOfLFY) ? $achieveEndOfLFY[ $itemName ]:0);
					$myData[$itemName]['overallTargetFY'] = '-';
					$myData[$itemName]['TargetEndMonthFY'] = '-';
					$myData[$itemName]['achivEndMonthFY'] = $achieveEndOfMonth[ $itemName ];
					$myData[$itemName]['targetProgEndMonth'] = '-';
					$myData[$itemName]['progOverallYearly'] = '-';
					$myData[$itemName]['overallAchivEndMonth'] = $myData[$itemName]['achivEndMonthFY'] + $myData[$itemName]['progLastFY'];
				}else{
					$myData[$itemName]['estQuantity'] = $estimation[ $itemName ];
					$myData[$itemName]['progLastFY'] = (($achieveEndOfLFY) ? $achieveEndOfLFY[ $itemName ]:0);
					$myData[$itemName]['overallTargetFY'] = $overallTargetForFY[ $itemName ];
					$myData[$itemName]['TargetEndMonthFY'] = $targetToEndOFMonth[ $itemName ];
					$myData[$itemName]['achivEndMonthFY'] = $achieveEndOfMonth[ $itemName ];
					$myData[$itemName]['targetProgEndMonth'] = 
						($myData[$itemName]['TargetEndMonthFY']==0 || $myData[$itemName]['achivEndMonthFY']==0) ? 0 
											: round($myData[$itemName]['achivEndMonthFY']/$myData[$itemName]['TargetEndMonthFY'] *100, 2);
					$myData[$itemName]['progOverallYearly'] = 
						(($myData[$itemName]['overallTargetFY']==0) || ($myData[$itemName]['achivEndMonthFY']==0)) ? 0 
											: round($myData[$itemName]['achivEndMonthFY']/$myData[$itemName]['overallTargetFY'] *100, 2);
					$myData[$itemName]['overallAchivEndMonth'] = $myData[$itemName]['achivEndMonthFY'] + $myData[$itemName]['progLastFY'];
				}
				/*echo 'p1:'.$myData[$itemName]['overallAchivEndMonth'].' ::p2::'.$myData[$itemName]['estQuantity'].
					'Todiv:'.($myData[$itemName]['overallAchivEndMonth']/$myData[$itemName]['estQuantity']).'<br />';*/
				$myData[$itemName]['percentOverallAchivEndMonth'] = 
					( ($myData[$itemName]['overallAchivEndMonth']==0) || ($myData[$itemName]['estQuantity']==0)) ? 0 
										: round( ($myData[$itemName]['overallAchivEndMonth']/$myData[$itemName]['estQuantity']) *100, 2);
			}
			$mData[$i]['mydata'] = $myData;//
			$i++;
		}
		$data['maxMonthlyLockDate'] = $maxMonthlyLockDate;
		$data['reportData'] = $mData;
		//showArrayValues($mData);
		//$time_end = microtime_float();
		//$data['mytime2'] = $time_end - $time_start;
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('mi/selock/physical_progress_view', $data);
	}//Physical Progress

    private function getMonthYearTitle($searchData){
		//date_default_timezone_set('Asia/Kolkata');
		$month = array("-", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		$arrProjectType = array('', 'Minor', 'Medium', 'Major');
		return '<font size="-1"> Promon (Micro Irrigation) Projects<br />For : '.( ($searchData['summaryReport']==false) ?
			$month[$searchData['MONTH']].', '.$searchData['YEAR']:
			date("M, y", strtotime('-2 month', $searchData['lastMonth'] )). ' - '
					.date("M, y", $searchData['lastMonth'])
			 ).
		'</font>';
	}

    protected function getSessionYear($sessionid=0){
		$rec = $this->db->get_where('__sessions', array('SESSION_ID'=>$sessionid));
		if($rec && $rec->num_rows()){
			$row = $rec->row();
			return ($row->SESSION_START_YEAR-2000).'-'.($row->SESSION_END_YEAR-2000	);
		}
		return '';
	}

    private function getCommonFields(){
		/*return array(
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 
			'FA_HA', 'FA_COMPLETED_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES',
			'IRRIGATION_POTENTIAL'
		);*/
		return array(
            'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
            'FA_HA','FA_COMPLETED_HA', 'L_EARTHWORK', 'C_MASONRY', 'C_PIPEWORK',
            'C_DRIP_PIPE', 'C_WATERPUMP', 'K_CONTROL_ROOMS'
        );
		//, 'IP_TOTAL'
	}

    protected function getMonthlyProgressForLock($date, $arrProjectIDs){
		$strSQL = 'SELECT PROGRESS, PROJECT_SETUP_ID FROM mi__t_progress 
			WHERE PROJECT_SETUP_ID IN ('. implode(',', $arrProjectIDs).')
				AND PROGRESS_DATE = "'.$date.'" ORDER BY PROJECT_SETUP_ID ';
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
    	$arrProgress = array();
		if($recs && $recs->num_rows()){
          	foreach($recs->result() as $rec){
              	$arrProgress [ $rec->PROJECT_SETUP_ID ] = $rec->PROGRESS;
           	}
        }
    	return $arrProgress;
	}

    public function lockProjects(){
		//date_default_timezone_set('Asia/Kolkata');
		if(!IS_LOCAL_SERVER){
			$this->load->library('mycurl');
			$serverStatus = $this->mycurl->getServerStatus();
			if($serverStatus==0){
				$arrResponse = array(
					"success" => 0, 
					"message" => 'Unable to lock. E-work Server Not responding. Try after sometime...'
				);
				echo json_encode($arrResponse);
				return;
			}
		}
		$eeId = $this->input->post('EE_ID');
		$seId = $this->session->userdata('CURRENT_OFFICE_ID');
		$lockedMonth = $this->input->post('MONTH_DATE');
		$selectedProjectIds = $this->input->post('chkProject');
		$projectStatus = $this->getProjectStatus($selectedProjectIds, $lockedMonth);

		$arrWhere = array('SE_ID'=>$seId, 'EE_ID'=>$eeId, 'LOCKED_MONTH'=>$lockedMonth);
		$tableName = 'mi__t_selocks';
		$recs = $this->db->get_where($tableName, $arrWhere);
		$isExists = (($recs && $recs->num_rows())? true:false);
		$seLockDateTime = date("Y-m-d H:i:s"); 
		$data = array(
			'LOCK_DATE'=>$seLockDateTime, 
			'LOCKED'=>1,
			'LOCKED_PROJECTS'=>implode(',', $selectedProjectIds),
			'UNLOCKED'=>0,
			'UNLOCK_DATE'=>'0000-00-00'
		);
		$goAhead = false;
		/*if($isExists){
			$this->db->update($tableName, $data, $arrWhere);
			$goAhead = true;
		}else{*/
			@$this->db->insert($tableName, array_merge($data, $arrWhere));
			if($this->db->affected_rows()) $goAhead = true;
		//}
		if($goAhead){
			//get completed projects
			$arrCompletedIds = array();
			foreach($selectedProjectIds as $projectId){
				if($projectStatus['p'.$projectId]==5)
					array_push($arrCompletedIds, $projectId);
			}
			if(count($arrCompletedIds)){
				$completedStatus = $this->getCompletedStatus($arrCompletedIds, $lockedMonth);
			}
			$countProjects = count($selectedProjectIds);
			$countSuccess = 0;
			$arrProjectCodes = $this->getProjectCodes($selectedProjectIds);
      $arrProgress = $this->getMonthlyProgressForLock($lockedMonth, $selectedProjectIds);
			//showArrayValues($arrProgress);
			$arrParams = array();
			foreach($selectedProjectIds as $projectId){
				//check project status
				$arrStatusLock = array(
					5=>4, //completed
					6=>7, //dropped
					4=>6  //stopped
				);
				if(array_key_exists($projectStatus['p'.$projectId], $arrStatusLock)){
					$PLock = $arrStatusLock[$projectStatus['p'.$projectId]];
					if(in_array($projectId, $arrCompletedIds)){
						$PLock = $completedStatus['p'.$projectId];
					}
				}else{
					//check six month same value
					$PLock = 0;// $this->monthlyProgressLockStatus($projectId);
				}
       	$iProgress = 0;
       	if (array_key_exists($projectId, $arrProgress)){
           $iProgress = $arrProgress[$projectId];
        }
				$params = array(
					'mode'=>'selock',
					"projectCode"=>$arrProjectCodes['p'.$projectId],
					"lDate" => $lockedMonth,
					"PLock" => $PLock,
          'progressPerc'=>$iProgress
				);
		
				//if($projectId==10)	showArrayValues($params);
				$arrWhereLock = array(
					'SE_LOCK_MONTH = "'. $lockedMonth .'" ', 
					'SE_LOCK_DATE_TIME = "'.$seLockDateTime.'" '
				);
				if(!IS_LOCAL_SERVER){
					$result = $this->mycurl->savePromonData($params);
					//if($projectId==10) echo $result;
					$obj = json_decode($result);
					if($obj->{'success'}){
						array_push($arrParams, array(
							'projectCode'=>$params['projectCode'],
							"lDate" => $params['lDate'],
							"PLock" => $params['PLock'],
							"progressPerc" => $params['progressPerc']
						));
						//update lock table 
						if(($PLock==4) || ($PLock>9))
							$arrWhereLock[] = ' SE_COMPLETION=1 ';
						$countSuccess++;
					}
				}
				$strSQL = 'UPDATE mi__t_locks SET '. implode(',', $arrWhereLock).
					' WHERE PROJECT_SETUP_ID='.$projectId;
				$this->db->query($strSQL);
			}
			//$this->updateLockedStatus($arrParams);
			if(count($arrParams))
				@$this->db->update_batch('mi__t_locked_status', $arrParams, 'projectCode');
			if($countProjects==$countSuccess){
              
			}
			$arrResponse = array(
				"success" => 1, 
				"message" => 'Monthly Progress of '.$countSuccess.' Project(s) Sent to E-Works Server.'
			);
		}else{
			$arrResponse = array(
				"success"=>0,
				"message"=>'Something Wrong.'
			);
		}
		echo json_encode($arrResponse);		
	}

    protected function getCompletedStatus($projectIds, $lockedMonth){
		$this->db->where_in('PROJECT_SETUP_ID', $projectIds);
		$this->db->where('MONTH_DATE', $lockedMonth);
		$recs = $this->db->get('mi__t_status_date');
		$data = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$completionType = $rec->COMPLETION_TYPE;
				if($completionType==2){
					$LA_PAYMENT = $rec->LA_PAYMENT;
					$FA_PAYMENT = $rec->FA_PAYMENT;
					$CL_PAYMENT = $rec->CL_PAYMENT;
					$paycode = $LA_PAYMENT.$FA_PAYMENT.$CL_PAYMENT;
					$arrPayCode = array(
						'100'=>10, '010'=>11, '001'=>12, 
						'110'=>13, '011'=>14, '101'=>15, '111'=>16
					);
					$plock = $arrPayCode[$paycode];
				}else{
					$plock = 4;
				}
				$data['p'.$rec->PROJECT_SETUP_ID] = $plock;
			}
		}
		return $data;
	}

    protected function getProjectStatus($projectIds, $lockedMonth){
		$this->db->where_in('PROJECT_SETUP_ID', $projectIds);
		$this->db->where('MONTH_DATE', $lockedMonth);
		$recs = $this->db->get('mi__t_monthly');
		$data = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$data['p'.$rec->PROJECT_SETUP_ID] = $rec->WORK_STATUS;
			}
		}
		return $data;
	}

    protected function getProjectCodes($projectIds){
		$this->db->select('PROJECT_SETUP_ID, PROJECT_CODE');
		$this->db->where_in('PROJECT_SETUP_ID', $projectIds);
		$recs = $this->db->get('mi__m_project_setup');
		$data = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$data['p'.$rec->PROJECT_SETUP_ID] = $rec->PROJECT_CODE;
			}
		}
		return $data;
	}
}
