<?php include_once('Report_library.php');
class Reports_c extends Report_library{
	var $proID, $proCompStatus, $mySessionIDs, $lastSessionID;
	function __construct(){
		parent::__construct();
		$this->proCompStatus = 0; // If 0 Black - If 1 Red
		$this->mySessionIDs = array();
	}
	public function index(){
	    $data = array();
		$this->session->set_userdata(
			array(
				'PROJECT_TYPE_ID' => 3,
				'MODULE_KEY' => 'PMON_MICRO_PROJECT_TARGET',
				'MONTHLY_MODULE_KEY' => 'PMON_MICRO_PROJECT_MONTHLY'
			)
		);
		$data['message'] = '';
		$data['page_heading'] = pageHeading('PROMON - Micro Irrigation  -  Project Reports');
		$this->load->library('office_filter');
		$data['office_list'] = $this->office_filter->office_list();
		$data['sessionOptions'] = $this->getSessionDropDown();
		$data['sessionOptionsReport'] = $this->getSessionDropDownReport();
		$mList = array();
		$this->getMonthList($mList);
		$data['monthList'] = $mList;
		$data['lastSessionID'] = $this->lastSessionID ;
		$this->load->view('promon/reports/report_index_view', $data);
	}
	//offices
	public function showOfficeFilterBox(){
		//$data['instance_name'] = 'search_office';
		$data = array();
		$data['prefix'] = 'SEARCH_';
		$data['show_sdo'] = FALSE;
		$data['row'] = '';
		/*<tr>
			<td class="ui-widget-content" align="center" colspan="2">
			
			</td></tr>';*/
		//$data['row'] = '<tr><td colspan="2" class="ui-widget-content">'.getButton('Search', 'refreshSearch()').'</td></tr>';
		$this->load->view('setup/office_filter_view', $data);
    }
	private function getOfficeData($mode=false){
		$arrDate = explode('-', $this->input->post('REPORT_MONTH_YEAR'));
		$arrData = array(
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
		$arrData['SESSION_ID'] = $this->getSessionID($arrData['MONTH'], $arrData['YEAR']);
		$arrData['REPORT_DATE'] = $arrData['YEAR'].'-'.str_pad($arrData['MONTH'], 2, "0", STR_PAD_LEFT).'-01';
		$arrData['REPORT_LAST_DATE'] = date("Y-m-t", strtotime($arrData['REPORT_DATE']));
		$sessionId = $arrData['SESSION_ID'];
		$arrData['PREV_SESSION_ID'] = $sessionId-1; 
		return $arrData;
	}
	/** Called by index()*/
	private function getSessionDropDown(){
		$cMonth = date("n");
		$cYear = date("Y");
		$sYear = ($cMonth<4)? $cYear : ($cYear+1);
		$this->db->select('SESSION_ID, SESSION, IS_CURRENT');
		$this->db->where('SESSION_ID >=', PMON_MI_START_SESSION_ID)->where('SESSION_END_YEAR <=', $sYear);
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
		if($cMonth==4){
			if ($cDay<=10) 
				$sessionId--; 
		}
		$this->db->select('SESSION_ID, SESSION, IS_CURRENT, SESSION_START_YEAR, SESSION_END_YEAR');
		$this->db->where('SESSION_ID >=', PMON_MI_START_SESSION_ID)->where('SESSION_ID <=', $sessionId);
		$recs = $this->db->get('__sessions');
		//echo '<br /><br /><br />'.$this->db->last_query();
		$opt = array();
		array_push($opt, '');
		if($recs){
			$tCount = $recs->num_rows();
			if($tCount){
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
						( ($i==$tCount)? 'selected="selected"':'').'>'.
						$rec->SESSION.
						'</option>'
					);
					$this->lastSessionID = ($i==$tCount) ? $rec->SESSION_ID: 0;
					$i++;
				}
			}
		}
		return implode('', $opt);
	}
	//
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
	/*----- lagging_landmarks ------*/
	public function lagging_landmarks(){
		//$time_start = microtime_float();
		$searchData = $this->getOfficeData();
		$mSearchYear = $searchData['YEAR'];
		$mSearchMonth = $searchData['MONTH'];
		$sessionID = $this->getSessionID($mSearchMonth, $mSearchYear);

		$projectIDs = array();
		$reportData = array();

		$projectData = $this->getLaggingLandmarks($sessionID, $searchData, $projectIDs);
		if( count($projectIDs)==0 ){
			//end of report
			$data['reportData'] = false;//$reportData;
			//showArrayValues($reportData);
			$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
			$this->load->view('promon/reports/lagging_view', $data);
			return;
		}
		//$mMSRow = $this->getLastMonthStatus($projectIDs);
		//showArrayValues($mMain );
		$data['mSearchYear'] = $searchData['YEAR'];
		$data['mSearchMonth'] = $searchData['MONTH'];
		/**/  
		//$mMainRecords = mysql_num_rows($mMain);
		$i=0;
		$sy = $searchData['YEAR'];
		$sm = $searchData['MONTH'];
		$mlastdate = date("Y-m-t", strtotime( $sy."-".$sm."-01" ));
		//echo '::'.$mlastdate.'::';
		foreach($projectData as $row){
			//fetch last monthly record
			// echo "<br>".$i++."<br> ".$this->db->last_query();
			if($row->MONTHLY_DATA_ID){
				$goAhead = false;
				$is_la = $this->checkCompleted($row->LA_CASES_STATUS);
				$is_spill = $this->checkCompleted($row->SPILLWAY_STATUS);
				$is_flank = $this->checkCompleted($row->FLANK_STATUS);
				$is_sluce = $this->checkCompleted($row->SLUICES_STATUS);
				$is_nalla = $this->checkCompleted($row->NALLA_CLOSURE_STATUS);
				$is_cew = $this->checkCompleted($row->CANAL_EARTH_WORK_STATUS);
				$is_cs = $this->checkCompleted($row->CANAL_STRUCTURE_STATUS);
				$is_cline = $this->checkCompleted($row->CANAL_LINING_STATUS);
					
				if( (!$is_la) || (!$is_spill) || (!$is_flank) || (!$is_sluce) || 
					(!$is_nalla) || (!$is_cew)  || (!$is_cs) || (!$is_cline) ){
					$goAhead = true;
					$i++;
				}
				//echo '<br />';
				//if($row->PROJECT_ID==3722) echo '1:'.$goAhead.'<br/>'.$mlastdate.'<'.$row->LA_TARGET_DATE .'<br/>';
				/**/
				if( (($mlastdate<$row->LA_TARGET_DATE) && $is_la) || 
					 (($mlastdate<$row->SPILLWAY_TARGET_DATE) && $is_spill) ||
					 (($mlastdate<$row->FLANKS_TARGET_DATE) && $is_flank) ||
					 (($mlastdate<$row->SLUICES_TARGET_DATE) && $is_sluce) ||
					 (($mlastdate<$row->NALLA_CLOSURE_TARGET_DATE) && $is_nalla) ||
					 (($mlastdate<$row->CANAL_EARTHWORK_TARGET_DATE) && $is_cew) ||
					 (($mlastdate<$row->CANAL_STRUCTURES_TARGET_DATE) && $is_cs) ||
					 (($mlastdate<$row->CANAL_LINING_TARGET_DATE) && $is_cline)
					){
					 $goAhead = true; // echo "Aah";
				}
				else { $goAhead = false;}
				//if($row->PROJECT_ID==3722) echo '2:'.$goAhead.'<br/>';
				if( (($mlastdate>$row->LA_TARGET_DATE) && $this->isStatusNA($row->LA_CASES_STATUS)) || 
					 (($mlastdate>$row->SPILLWAY_TARGET_DATE) && $this->isStatusNA($row->SPILLWAY_STATUS)) ||
					 (($mlastdate>$row->FLANKS_TARGET_DATE) && $this->isStatusNA($row->FLANK_STATUS)) ||
					 (($mlastdate>$row->SLUICES_TARGET_DATE) && $this->isStatusNA($row->SLUICES_STATUS)) ||
					 (($mlastdate>$row->NALLA_CLOSURE_TARGET_DATE) && $this->isStatusNA($row->NALLA_CLOSURE_STATUS)) ||
					 (($mlastdate>$row->CANAL_EARTHWORK_TARGET_DATE) && $this->isStatusNA($row->CANAL_EARTH_WORK_STATUS)) ||
					 (($mlastdate>$row->CANAL_STRUCTURES_TARGET_DATE) && $this->isStatusNA($row->CANAL_STRUCTURE_STATUS)) ||
					 (($mlastdate>$row->CANAL_LINING_TARGET_DATE) && $this->isStatusNA($row->CANAL_LINING_STATUS))
					){
						$goAhead = true; //echo("Ouch");
				}else{
					$goAhead = false; 
				}
				//if($row->PROJECT_ID==3722) echo '3:'.$goAhead.'<br/>';
				if ((($mlastdate>$row->LA_TARGET_DATE) && (!$is_la)) || 
					(($mlastdate>$row->SPILLWAY_TARGET_DATE) && (!$is_spill)) ||
					(($mlastdate>$row->FLANKS_TARGET_DATE) && (!$is_flank)) ||
					(($mlastdate>$row->SLUICES_TARGET_DATE) && (!$is_sluce)) ||
					(($mlastdate>$row->NALLA_CLOSURE_TARGET_DATE) && (!$is_nalla)) ||
					(($mlastdate>$row->CANAL_EARTHWORK_TARGET_DATE) && (!$is_cew)) ||
					(($mlastdate>$row->CANAL_STRUCTURES_TARGET_DATE) && (!$is_cs)) ||
					(($mlastdate<$row->CANAL_LINING_TARGET_DATE) && (!$is_cline)))
					{
						$goAhead = true;
				}
			}else{
				$goAhead = true;
				$rowStatus = (object) array(
					'LA_CASES_STATUS'=>0, 'SPILLWAY_STATUS'=>0, 
					'FLANK_STATUS'=>0, 'SLUICES_STATUS'=>0, 'NALLA_CLOSURE_STATUS'=>0, 
					'CANAL_EARTH_WORK_STATUS'=>0, 'CANAL_STRUCTURE_STATUS'=>0, 
					'CANAL_LINING_STATUS'=>0
				);
			}
			//if($row->PROJECT_ID==3722) echo '4:'.$goAhead.'<br/>';
			//echo 'LLLL::'.$goAhead.':LLL';
			if ($goAhead){
				//$pcode =  //$this->getProjectCode($row->PROJECT_TYPE_ID, $row->PROJECT_ID);
				$reportData[$i]['SNO'] = $i;
				$reportData[$i]['pcode'] = $row->PROJECT_CODE;
				$reportData[$i]['projectname'] = $row->PROJECT_NAME;
				$reportData[$i]['eeid'] = $row->OFFICE_EE_ID;
				$reportData[$i]['seid'] = $row->OFFICE_SE_ID;
				$reportData[$i]['ceid'] = $row->OFFICE_CE_ID;
				$reportData[$i]['sename'] = $row->OFFICE_SE_NAME;
				$reportData[$i]['cename'] = $row->OFFICE_CE_NAME;
				$reportData[$i]['divisionname'] = $row->OFFICE_NAME;
				//$reportData[$i]['block'] = $row->BLOCK_NAME;
				$reportData[$i]['dirr'] = $row->IRRIGATION_POTENTIAL;
				/*$reportData[$i]['land_acquisition'] =$this->getStatus($m1, $n1, $sm, $sy, $m8);
				$reportData[$i]['spillway'] =$this->getStatus($m2, $n2, $sm, $sy, $m8);
				$reportData[$i]['flanks'] = $this->getStatus($m3, $n3, $sm, $sy, $m8);
				$reportData[$i]['nallcloser'] = $this->getStatus($m5, $n5, $sm, $sy, $m8);
				$reportData[$i]['ew'] = $this->getStatus($m6, $n6, $sm, $sy, $m8);
				$reportData[$i]['structures'] = $this->getStatus($m7, $n7, $sm, $sy, $m8);*/
				$reportData[$i]['land_acquisition'] = $this->getStatus(
					$row->LA_CASES_STATUS, $row->LA_TARGET_DATE, $sm, $sy, $mlastdate
				);
				$reportData[$i]['spillway'] = $this->getStatus(
					$row->SPILLWAY_STATUS, $row->SPILLWAY_TARGET_DATE, $sm, $sy, $mlastdate
				);
				$reportData[$i]['flanks'] = $this->getStatus(
					$row->FLANK_STATUS, $row->FLANKS_TARGET_DATE, $sm, $sy, $mlastdate
				);
				$reportData[$i]['sluice'] = $this->getStatus(
					$row->SLUICES_STATUS, $row->SLUICES_TARGET_DATE, $sm, $sy, $mlastdate
				);
				$reportData[$i]['nallcloser'] = $this->getStatus(
					$row->NALLA_CLOSURE_STATUS, $row->NALLA_CLOSURE_TARGET_DATE, $sm, $sy, $mlastdate
				);
				$reportData[$i]['ew'] = $this->getStatus(
					$row->CANAL_EARTH_WORK_STATUS, $row->CANAL_EARTHWORK_TARGET_DATE, $sm, $sy, $mlastdate
				);
				$reportData[$i]['structures'] = $this->getStatus(
					$row->CANAL_STRUCTURE_STATUS, $row->CANAL_STRUCTURES_TARGET_DATE, $sm, $sy, $mlastdate
				);
				$reportData[$i]['canal_lining'] = $this->getStatus(
					$row->CANAL_LINING_STATUS, $row->CANAL_LINING_TARGET_DATE, $sm, $sy, $mlastdate
				);
				// Project Completion Date
				// If 0 Black - If 1 Red
				if($row->MONTHLY_PROJECT_STATUS==5){
					//completed
					$reportData[$i]['pcdate'] = date("d-m-Y",strtotime($row->PROJECT_COMPLETION_DATE));
				}else{
					//on going
					if($row->PROJECT_COMPLETION_DATE>$mlastdate){
						$reportData[$i]['pcdate'] = date("d-m-Y",strtotime($row->PROJECT_COMPLETION_DATE));
					}else{
						$reportData[$i]['pcdate'] = "<font color='red'>".
							date("d-m-Y",strtotime($row->PROJECT_COMPLETION_DATE)).
							"</font>";
					}
				}
				$i++;
			}//if ($goAhead){
			//} //if ($mMSRow)
		} //foreach($mMain as $row){
		//if
		$data['reportData'] = $reportData;
		// showArrayValues($reportData);
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('promon/reports/lagging_view', $data);
	}//LagginG Landmarks
	private function getStatus($mStatus, $mStatusDate, $mmm, $myy,  $subdate){
		//$mStatus - LA_CASES_STATUS
		//mStatusDate - LA_TARGET_DATE
		//mmm - month
		//myy - year
		//subdate - last date of month
		// echo($mStatus.", <br><br>2 ".$mStatusDate.", <br><br>sm === ".$mmm.", <br><br>sy === ".$myy.", <br><br>5 ".$subdate);
		$statusOptions = array('', 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 'Dropped');
		//$reportStatus = 0-Nothing 1-ok 2-Not Ok;
		$REPORT_STATUS_NOTHING = 0;
		$REPORT_STATUS_OK = 1;
		$REPORT_STATUS_NOT_OK = 2;

		$reportStatus = $REPORT_STATUS_NOTHING;

		//if monthly status not NA
		if($mStatus!=1){// 1-NA
			//if target date is greater report date
			if ($subdate<$mStatusDate)
				$reportStatus = $REPORT_STATUS_OK;
			else{
				if($mStatus==5) //completed
					$reportStatus = $REPORT_STATUS_OK;
				else{
					if ($mStatusDate==""){
					}else{
						if ( (date("Y", strtotime($mStatusDate))>$myy)  ){
							$reportStatus = $REPORT_STATUS_OK;
						}else{
							//echo ">> ".date("d", strtotime($subdate));
							if((date("Y", strtotime($mStatusDate))==$myy)){
								$mMonth = date("m", strtotime($mStatusDate));
								$reportStatus = $REPORT_STATUS_OK;
								if($mMonth<$mmm) $reportStatus = 2;
								else if( $mMonth==$mmm){
									// echo "<br>MstatusDate ". date("d", strtotime($mStatusDate));
									// echo "<br> SubDate ".date("d", strtotime($subdate));
									if( $mMonth <= date("m", strtotime($subdate)) )
										$REPORT_STATUS_NOT_OK = 2;
								}
							}
							else
								$REPORT_STATUS_NOT_OK = 2;
						}
					}
				}
			}
		}
		if ($reportStatus!=$REPORT_STATUS_NOTHING){
			//echo $reportStatus;
			//$this->proCompStatus = 0; // If 0 Black - If 1 Red
			if ($reportStatus==$REPORT_STATUS_NOT_OK){
				$this->proCompStatus = 1;
				if($mStatusDate=='0000-00-00'){
					//echo 'SS:'.$mStatus;
					if($mStatus==''){
						return "<font color='red'>()</font>";
					}else{
						return "<font color='red'>(".$statusOptions[$mStatus].")</font>";
					}
				}
				else
					if($mStatus==''){
						return '';//"<font color='red'>()</font>";
					}else{
						return "<font color='red'>".
								date("d-m-Y",strtotime($mStatusDate)).
								"<br>(".$statusOptions[$mStatus].
								")</font>";
					}
			}else{
				$this->proCompStatus = 0;
				return date("d-m-Y", strtotime($mStatusDate)). 
					(($mStatus=='')? '' : '<br />'.$statusOptions[$mStatus]);
			}
		}
		else
			return "NA";
	}
	private function checkCompleted($st){
		return (($st==5) ? true : false);
  	}
	/*private function compareDate($date1, $date2){
		$dt1 = strtotime($date1);
		$dt2 = strtotime($date2);
		if ($dt1<$dt2)
			return -1;
		else if ($dt1>$dt2)
			return 1;
		return 0;
  	}*/
	/*private function isLessDate($dt1, $dt2){
		if( $this->compareDate($dt1, $dt2) == -1 ){
			return true;
		}else{
			return false;
		}
	}*/
	private function isStatusNA($st){
		return ( $st==1)? true : false;
	}
	//physical_progress
	public function physical_progress(){
		//$time_start = microtime_float();
		//manipulate office
		$searchData = $this->getOfficeData();
		$mSearchYear = $searchData['YEAR'];
		$mSearchMonth = $searchData['MONTH'];
		$sessionID = $this->getSessionID($mSearchMonth, $mSearchYear);
		//echo 'FY:'.$mSearchMonth.', '.$mSearchYear.'::'.$sessionID.':FY';
		$data = array();
		$reportData = array();
		$arrProjectIDs = array();
		//$time_start = microtime_float();
		//get date for checking
		//$mLastDateOfMonth = $this->getLastDateOfMonth($mSearchMonth, $mSearchYear);
		
		//$time_start = microtime_float();\
		//showArrayValues($searchData);
		$projectData = $this->getProjectRecordsForPP($searchData);
		//echo 'OK';
		//exit;
		//showArrayValues($projectData);
		if($projectData){
			foreach($projectData as $rec)
				array_push($arrProjectIDs, $rec->PROJECT_SETUP_ID);
		}
		$projectCount = count($arrProjectIDs);
		//echo 'ProjCount:'.$projectCount;
		if($projectCount==0){
			$data['reportData'] = $reportData;
			$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
			$this->load->view('promon/reports/physical_progress_view', $data);
			return;
		}
		$monthDate = $mSearchYear.'-'.str_pad($mSearchMonth, 2, "0", STR_PAD_LEFT).'-01';
		//get overall target
		$mOverAllTarget = $this->projectTarget($sessionID, $arrProjectIDs);
		//showArrayValues($mOverAllTarget);
		//get target in that month
		$mTargetInMonth = $this->TargetInMonth($searchData, $arrProjectIDs);
		//PROJECT_ID, YEAR, PROGRESS is taken in $mMonthlyStatus
		$mMonthlyStatus = $this->getMonthlyProgress($searchData, $arrProjectIDs);
		//$mMonthlyStatus = $this->MonthlyStatus($sessionID, $searchData, $arrProjectIDs);
		$mEstimate = $this->getEstimatedRecord($searchData, $arrProjectIDs);
		//showArrayValues($mEstimate);
		$mEstimatedStatus = $this->getEstimatedStatusRecord($arrProjectIDs);
		//showArrayValues($mEstimatedStatus);
		//echo $sessionID;
		$mAchievementEndOfLastFY = $this->getAchievementEndOfLastFY($searchData, $arrProjectIDs);
		//showArrayValues($mAchievementEndOfLastFY );
		//echo $monthDate;
		$achiv = $this->achivementEndOfMonth($arrProjectIDs, $monthDate, $sessionID);
		//showArrayValues($achiv );
		//echo 'BB:'.count($mAchievementEndOfLastFY).':BB';
		$i = 1;
		$mData = array();
		$mRealProgress = 0;
		//echo($mProjectRecords);
		$commonFields = $this->getCommonFields();
		showArrayValues($projectData);
		//exit;
		foreach($projectData as $prec){
			$projectId =  $prec->PROJECT_SETUP_ID;
			$myData = array();
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
			for($iCount=0; $iCount<count($commonFields); $iCount++){
				$estimation[ $commonFields[$iCount] ] = 0;
			}
			if ($mEstimate){
				foreach($mEstimate as $mRec){
					if ($mRec->PROJECT_ID==$projectId){
						for($iCount=0; $iCount<count($commonFields); $iCount++)
							$estimation[ $commonFields[$iCount] ] = $mRec->{ $commonFields[$iCount]};
						break;
					}
				}
			}
			//OVERALL TARGET
			for($iCount=0; $iCount<count($commonFields); $iCount++){
				$overallTargetForFY[ $commonFields[$iCount] ] = 0;
			}
			if ($mOverAllTarget){
				foreach($mOverAllTarget as $mTargetRec){
					if ($mTargetRec->PROJECT_ID==$projectId){
						for($iCount=0; $iCount<count($commonFields); $iCount++)
							$overallTargetForFY[ $commonFields[$iCount] ] = $mTargetRec->{ $commonFields[$iCount]};
						break;
					}
				}
			}
			if($projectId==0)
				showArrayValues($overallTargetForFY);
			//ACHEIVEMENT END OF LAST FY
			for($iCount=0; $iCount<count($commonFields); $iCount++){
				$achieveEndOfMonth[ $commonFields[$iCount] ] = 0;
			}
			//showArrayValues($mAchievementEndOfLastFY);
			if ($mAchievementEndOfLastFY){
				foreach($mAchievementEndOfLastFY as $mARec){
					if ($mARec->PROJECT_ID==$projectId){
						for($iCount=0; $iCount<count($commonFields); $iCount++)
							$achieveEndOfLFY[ $commonFields[$iCount] ] = $mARec->{ $commonFields[$iCount]};
						break;
					}
				}
			}
			//showArrayValues($achieveEndOfLFY);
			//$PROGRESS = 0;
			$myData['progress'] = 0;
			if ($mMonthlyStatus){
				$monthlyFound = false;
				foreach($mMonthlyStatus as $mMSRow){
					if ($mMSRow->PROJECT_ID==$projectId)
						$myData['progress'] = $mMSRow->PROGRESS;
				}
			}
			//initialise row
			for($iCount=0; $iCount<count($commonFields); $iCount++){
				$targetToEndOFMonth[ $commonFields[$iCount] ] = 0;
			}
			$mMTRow = (object) array(
				'LA_NO'=>0, 'LA_HA'=>0, 'FA_HA'=>0,
				'HEAD_WORKS_EARTHWORK'=>0, 'HEAD_WORKS_MASONRY'=>0, 'STEEL_WORKS'=>0, 
				'CANAL_EARTHWORK'=>0, 'CANAL_LINING'=>0, 'CANAL_STRUCTURES'=>0,  'CANAL_MASONRY'=>0, 'ROAD_WORKS'=>0, 
				'IRRIGATION_POTENTIAL'=>0, 'FINANCIAL'=>0
			);
			if ($mTargetInMonth){
				foreach($mTargetInMonth as $mMTRec){
					if ($mMTRec->PROJECT_ID==$projectId){
						for($iCount=0; $iCount<count($commonFields); $iCount++){
							$targetToEndOFMonth[ $commonFields[$iCount] ] = $mMTRec->{$commonFields[$iCount]};
						}
						//break;
					}
				}
			}
			//$achieveRow = '';
			//[ ACHEIVEMENT END OF MONTH ]//
			//initialize
			for($iCount=0; $iCount<count($commonFields); $iCount++){
				$achieveEndOfMonth[ $commonFields[$iCount] ] = 0;
			}
			if($achiv){
				foreach($achiv as $row){
					if($projectId==$row->PROJECT_ID){
						for($iCount=0; $iCount<count($commonFields); $iCount++)
							$achieveEndOfMonth[ $commonFields[$iCount] ] = $row->{ $commonFields[$iCount]};
						break; //no need to further process
					} // End If
				} // End Foreach
			}
			$arrItem = array(
				'FA_HA', 'FA_COMPLETED_HA', 
				'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
				'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
				'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES', 'CANAL_MASONRY', 'ROAD_WORKS' 
			);
			//showArrayValues($estimation);
			for($iCount=0; $iCount<count($arrItem); $iCount++){
				$itemName = $arrItem[$iCount];
				if( substr($itemName, 0, 3)=='LA_'){
					@$na_value = (int) $mEstimatedStatus[$projectId]['LA_NA'];
				}else if( substr($itemName, 0, 3)=='FA_'){
					@$na_value = (int) $mEstimatedStatus[$projectId]['FA_NA'];
				}else if( ($itemName=='CANAL_STRUCTURES') || ($itemName=='CANAL_MASONRY')){
					@$na_value = (int) $mEstimatedStatus[$projectId]['CANAL_STRUCTURE_NA'];
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
		$data['reportData'] = $mData;
		//showArrayValues($mData);
		//$time_end = microtime_float();
		//$data['mytime2'] = $time_end - $time_start;
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('promon/reports/physical_progress_view', $data);
	}//Physical Progress
	/**OK */
	private function getLastDateOfMonth($sm, $sy){
		$mDays = 30;
		if( $sm==1 ||  $sm==3 ||  $sm==5 ||  $sm==7 ||  $sm==8 || $sm==10 ||  $sm==12 ){
			$mDays = 31;
		}else if( $sm==2 ){
			$mDays = ( $sy%4==0 ) ? 29 : 28;
		}
		return $sy."-".$sm."-".$mDays;	
	}
	//Irrigation Potential
	public function irrigation_potential(){
		//$time_start = microtime_float();
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$sessionId = $this->getSessionID($mSearchMonth, $mSearchYear);
		// echo "date: ". $mSearchMonth.'-'.$mSearchYear;	
		$projectIDs = array();
		$mProjectData = $this->getProjectDataForIP($sessionId, $searchData, $projectIDs);
		if($mProjectData){
			$mProjectIP = $this->getIrrigationPotential($sessionId, $searchData, $projectIDs);
			$mProjectTarget = $this->getTargetForIP($sessionId, $searchData, $projectIDs);
			//
			$i = 0;
			foreach($mProjectData as $prec){
				$mprojectID = $prec->PROJECT_ID;
				$LFY_IP = 0;
				$CFY_IP = 0;
				$T_IP = 0;
				if(	$prec->RAA_DATE == NULL ){
					$rDate = myDateFormat($prec->AA_DATE);// date("d M Y", strtotime($rDate));
					$rAmount = $prec->AA_AMOUNT;
				}
				else{
					$rDate = myDateFormat($prec->RAA_DATE).'(R)'; //date("d M Y", strtotime($rDate))."(R)";
					$rAmount = $prec->RAA_AMOUNT;
				}
				if($mProjectIP){
					foreach($mProjectIP as $rec){
						if($rec->PROJECT_ID ==$mprojectID ){
							$LFY_IP = $rec->LY_IRRIGATION_POTENTIAL;
							$CFY_IP = $rec->CY_IRRIGATION_POTENTIAL;
							break;
						}
					}
				}
				if($mProjectTarget){
					foreach($mProjectTarget as $rec){
						if($rec->PROJECT_ID ==$mprojectID ){
							$T_IP = $rec->IRRIGATION_POTENTIAL;
							break;
						}
					}
				}
				$reportData[$i] = array(
					'SNO'=>($i+1),
					'PROJECT_ID' => $prec->PROJECT_ID,
					'NAME' => $prec->PROJECT_NAME,
					'OFFICE'=> $prec->OFFICE_NAME,
					'eeid' => $prec->OFFICE_EE_ID,
					'seid' => $prec->OFFICE_SE_ID,
					'ceid' => $prec->OFFICE_CE_ID,
					'sename' => $prec->OFFICE_SE_NAME,
					'cename' => $prec->OFFICE_CE_NAME,
					'CDATE' => myDateFormat( $prec->PROJECT_COMPLETION_DATE),
					'AA_DATE' => $rDate,
					'AA_AMOUNT' => $rAmount,
					'IP' => ( ($prec->RAA_IP==0)? $prec->AA_IP : $prec->RAA_IP),
					'LFY_IP' => $LFY_IP,
					'CFY_IP' => $CFY_IP,
					'TIP' => $T_IP
				);
				//$reportData[$i]['IRR_POT_11'] = ($mIrr==0) ? giveComma($mac, 2) : giveComma($mIrr, 2);
				$i++;
			}
		}
		$data['reportData'] = $reportData;
		//$time_end = microtime_float();
		//$data['mytime'] = $time_end - $time_start;
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('promon/reports/irrigation_potential_view', $data);
	}//Irrigation Potential
	
	public function getOngoingProjectReport(){
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$sessionId = $this->getSessionID($mSearchMonth, $mSearchYear);
		$group_by = $this->input->post('group_by');
		$data['group_by'] = $group_by;
		
		$recs = $this->db->get_where('__sessions', array('SESSION_ID' => $sessionId), 1);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$curSessionStartYear= $rec->SESSION_START_YEAR;
			$curSessionEndYear= $rec->SESSION_END_YEAR;
		}
		
		$recs = $this->db->get_where('__sessions', array('SESSION_ID' => $sessionId-1), 1);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$prevSessionStartYear= $rec->SESSION_START_YEAR;
			$prevSessionEndYear= $rec->SESSION_END_YEAR;
		}
		
		
		$data['curSessionStartYear'] = $curSessionStartYear;
		$data['curSessionEndYear'] = $curSessionEndYear;		
		$data['prevSessionStartYear']=$prevSessionStartYear;
		$data['prevSessionEndYear']=$prevSessionEndYear;
		$data['mSearchMonth']=$mSearchMonth;
		
		$projectIDs = array();
		
		$mProjectData = $this->getProjectDataForOngoingProj($sessionId, $searchData, $projectIDs, $group_by);
		if($mProjectData){
			$mProjectIP = $this->getIrrigationPotential($sessionId, $searchData, $projectIDs);
			$mProjectTarget = $this->getTargetForIP($sessionId, $searchData, $projectIDs);
			//
			$i = 0;
			foreach($mProjectData as $prec){
				$mprojectID = $prec->PROJECT_ID;
				$LFY_IP = 0;
				$CFY_IP = 0;
				$T_IP = 0;
				if(	$prec->RAA_DATE == NULL ){
					$rDate = myDateFormat($prec->AA_DATE);// date("d M Y", strtotime($rDate));
					$rAmount = $prec->AA_AMOUNT;
				}
				else{
					$rDate = myDateFormat($prec->RAA_DATE).'(R)'; //date("d M Y", strtotime($rDate))."(R)";
					$rAmount = $prec->RAA_AMOUNT;
				}
				if($mProjectIP){
					foreach($mProjectIP as $rec){
						if($rec->PROJECT_ID ==$mprojectID ){
							$LFY_IP = $rec->LY_IRRIGATION_POTENTIAL;
							$CFY_IP = $rec->CY_IRRIGATION_POTENTIAL;
							break;
						}
					}
				}
				if($mProjectTarget){
					foreach($mProjectTarget as $rec){
						if($rec->PROJECT_ID ==$mprojectID ){
							$T_IP = $rec->IRRIGATION_POTENTIAL;
							break;
						}
					}
				}
				
				$cDateTemp = DateTime::createFromFormat('Y-m-d', $prec->PROJECT_COMPLETION_DATE);
				$cDate = $cDateTemp->format('m/Y');
				$reportData[$i] = array(
					'SNO'=>($i+1),
					'PROJECT_ID' => $prec->PROJECT_ID,
					'NAME' => $prec->PROJECT_NAME,
					'PROJECT_TYPE' => $prec->PROJECT_TYPE,
					'PROJECT_SUB_TYPE'=> $prec->PROJECT_SUB_TYPE,
					'OFFICE'=> $prec->OFFICE_NAME,
					'eeid' => $prec->OFFICE_EE_ID,
					'seid' => $prec->OFFICE_SE_ID,
					'ceid' => $prec->OFFICE_CE_ID,
					'sename' => $prec->OFFICE_SE_NAME,
					'cename' => $prec->OFFICE_CE_NAME,
					'CDATE' => $cDate,
					'AA_DATE' => $rDate,
					'AA_AMOUNT' => $rAmount,
					'IP' => ( ($prec->RAA_IP==0)? $prec->AA_IP : $prec->RAA_IP),
					'LFY_IP' => $LFY_IP,
					'CFY_IP' => $CFY_IP,
					'TIP' => $T_IP,
					
					'LIVE_STORAGE_CAPACITY' => $prec->LIVE_STORAGE_CAPACITY,
					'DISTRICT_NAME' => $prec->DISTRICT_NAME,
					'BLOCK_NAME' => $prec->BLOCK_NAME,
					'TEHSIL_NAME' => $prec->TEHSIL_NAME,
					'BUDGET_PROVISION_AMOUNT' => $prec->AMOUNT,
					'PREV_EXP_WORK' => $prec->PREV_EXP_WORK,
					'CUR_EXP_WORK' => $prec->CUR_EXP_WORK
				);
				//$reportData[$i]['IRR_POT_11'] = ($mIrr==0) ? giveComma($mac, 2) : giveComma($mIrr, 2);
				$i++;
			}
		}
		$data['reportData'] = $reportData;
		//$time_end = microtime_float();
		//$data['mytime'] = $time_end - $time_start;
		//$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$data['monthYearTitle'] = $searchData['MONTH'].'/'.$searchData['YEAR'];
		$this->load->view('promon/reports/ongoing_project_view', $data);
	}//getOngoingProjectReport
	//Financial Progress
	public function financial_progress(){
		//$time_start = microtime_float();
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$mSessionID = $this->getSessionID($mSearchMonth, $mSearchYear);		

		$mce = $searchData['CE_ID'];
		$data = array(); $reportData = array();
		$mAmount = 0;
		//mi_monthlydata
		$mLastDateOfMonth = $this->getLastDateOfMonth($mSearchMonth, $mSearchYear);
		$projectData = $this->getProjectRecords($mSessionID, $mLastDateOfMonth, $searchData);
		$arrProjectIDs = array();
		if($projectData){
			foreach($projectData as $rec)
				array_push($arrProjectIDs, $rec->PROJECT_ID);
		}
		$projectCount = count($arrProjectIDs);
		if($projectCount==0){
			$data['reportData'] = $reportData;//
			$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
			$this->load->view('promon/reports/financial_progress_report_view', $data);
			return;
		}
		$monthDate = $mSearchYear.'-'.str_pad($mSearchMonth, 2, "0", STR_PAD_LEFT).'-01';
		$mProjectRecords = 0;
		$mTargetRecords = 0;
		$mEARecords = 0;
		$mTarget = $this->getTargetFinancial($arrProjectIDs, $mSessionID, $monthDate);
		$mEA = $this->achievedFinancial($arrProjectIDs, $mSessionID, $searchData, $monthDate );
		$i=0;
		$projectDataRow = array();
		// -------------------------------
		foreach($projectData as $prec){
			if ($prec->RAA_NO!=''){
				$mno = $prec->RAA_NO;
				$md = date("d-m-Y", strtotime($prec->RAA_DATE))."<br />(R)";
				$mamt = $prec->RAA_AMOUNT;
			}else{
				$mno = $prec->AA_NO;
				$md = date("d-m-Y", strtotime($prec->AA_DATE));
				$mamt = $prec->AA_AMOUNT;
			}
			$projectData = array(
				'PROJECT_ID' => $prec->PROJECT_ID,
				'eeid' => $prec->OFFICE_EE_ID,
				'seid' => $prec->OFFICE_SE_ID,
				'ceid' => $prec->OFFICE_CE_ID,
				'sename' => $prec->OFFICE_SE_NAME,
				'cename' => $prec->OFFICE_CE_NAME,
				'OFFICE_NAME' => str_replace('Executive Engineer, ', '', $prec->OFFICE_NAME),
				'PROJECT_NAME' => $prec->PROJECT_NAME . '-'.$prec->PROJECT_ID,
				'PROJECT_COMPLETION_DATE' => myDateFormat($prec->PROJECT_COMPLETION_DATE),
				'EXPENDITURE_TOTAL' => $prec->EXPENDITURE_TOTAL,
				'GRANT_NO' => $prec->GRANT_NO,
				'MINOR_BUDGET_HEAD' => $prec->MINOR_BUDGET_HEAD,
				'MAJOR_BUDGET_HEAD' => $prec->MAJOR_BUDGET_HEAD,
				'LATEST_COST' => $mamt,
				//get target amount
				'AA_NO' => $mno,
				'AA_DATE' => $md,
				'BUDGET_AMOUNT' => 0,
				'TARGET_TILL_DATE' => 0,
				'TARGET_IN_CURRENT_FY' => 0,
				'EXPENSE_IN_CURRENT_FY' => 0,
				'EXPENSE_TILL_PREV_FY' => 0
			);
			if ($mTarget){
				foreach($mTarget as $mTRow ){
					if ($projectData['PROJECT_ID'] == $mTRow->PROJECT_ID){
						//echo $mTRow->transType.':';
						if( $mTRow->transType==1){//target till date
							$projectData['TARGET_TILL_DATE'] = $mTRow->ct10;
							
						}else if( $mTRow->transType==2){//target in current fy
							$projectData['TARGET_IN_CURRENT_FY'] = $mTRow->ct10;
						}else if( $mTRow->transType==3){
							$projectData['BUDGET_AMOUNT'] = $mTRow->ct10;
						}
					}
				}
			}
			if ($mEA){
				foreach($mEA as $mERow){
					//echo "<br> >> ".$mprojectcode." - ".$mERow->PROJECT_ID."  ".$mERow->ac1;						
					if ($projectData['PROJECT_ID'] == $mERow->PROJECT_ID){
						if( $mERow->transType==1){
							$projectData['EXPENSE_TILL_PREV_FY'] = $mERow->ac1;
						}else{
							$projectData['EXPENSE_IN_CURRENT_FY'] = $mERow->ac1;
						}
					}						
				}
			}
			//12 EXPENSE_IN_CURRENT_FY
			//13
			//showArrayValues($projectData);
			if($projectData['EXPENSE_IN_CURRENT_FY']==0 || $projectData['TARGET_TILL_DATE']==0)
				$projectData['PROGRESS_IN_FY'] = '0.00';
			else
				$projectData['PROGRESS_IN_FY']=round(
					($projectData['EXPENSE_IN_CURRENT_FY']/$projectData['TARGET_TILL_DATE']) * 100.00,
					 2
				);
			if($projectData['EXPENSE_IN_CURRENT_FY']==0 || $projectData['BUDGET_AMOUNT']==0)
				$projectData['PROGRESS_VS_BUDGET'] = '0.00';
			else
				$projectData['PROGRESS_VS_BUDGET'] = round(
					($projectData['EXPENSE_IN_CURRENT_FY']/$projectData['BUDGET_AMOUNT']) *100.00, 
					2
				);
			if($projectData['EXPENSE_TILL_PREV_FY']==0 && $projectData['EXPENSE_IN_CURRENT_FY']==0)
				$projectData['UPTO_DATE_EXPENSE'] = '0.00';
			else
				$projectData['UPTO_DATE_EXPENSE'] = $projectData['EXPENSE_TILL_PREV_FY'] 
												  + $projectData['EXPENSE_IN_CURRENT_FY'] ;

			if($projectData['UPTO_DATE_EXPENSE']==0 || $projectData['LATEST_COST']==0)
				$projectData['PROGRESS_OVERALL'] = '0.00';
			else
				$projectData['PROGRESS_OVERALL'] = round(
					($projectData['UPTO_DATE_EXPENSE'] / $projectData['LATEST_COST'])*100.00, 
					2
				);
			$mAmount = $mAmount + $mamt;
			array_push($projectDataRow, $projectData);
		}// 1st Foreach 
 		$data['reportData'] = $projectDataRow;
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('reports/financial_progress_report_view', $data);
	} //Financial Progress
	//
	private function getMonthYearTitle($searchData){
		date_default_timezone_set('Asia/Kolkata');
		$month = array("-", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		$arrProjectType = array('', 'Minor', 'Medium', 'Major');
		return '<font size="-1"> Promon ('.
			$arrProjectType[ $this->session->userData('PROJECT_TYPE_ID') ].
			') Projects<br />For : '.( ($searchData['summaryReport']==false) ? 
			$month[$searchData['MONTH']].', '.$searchData['YEAR']:
			date("M, y", strtotime('-2 month', $searchData['lastMonth'] )). ' - '
					.date("M, y", $searchData['lastMonth'])
			 ).
		'</font>';
	}
	//Completed Projects
	public function project_completed(){
		//$time_start = microtime_float();
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$mce = $searchData['CE_ID'];
		$data = array(); $reportData = array();
		$sessionID = $this->getSessionID($mSearchMonth, $mSearchYear);
		$recs = $this->getCompletedProjects($sessionID, $searchData);
		$data['recs'] = $recs;
		$data['reportData'] = $reportData;
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('promon/reports/completed_project_view', $data);
	}//Projects Completed
	//Projects Received RAA
	public function project_received_raa(){
		$time_start = microtime_float();
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$mce = $searchData['CE_ID'];
		$data = array();
		$reportData = array();
		$sessionID = $this->input->post('raa_session_id');
		if($sessionID==9999){
			$recs = $this->raaReceivedAll($searchData);
		}else{
			$recs = $this->raaReceived($sessionID, $searchData);
		}
		if ($recs){
			foreach($recs as $rec){
				if($rec->PROJECT_ID==0)
					$recs = array();
			}
		}
		$data['recs'] = $recs;
		if($sessionID==9999){//all session
			$data['monthYearTitle'] = '';
		}else if($sessionID==0){
			$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		}else{
			$x = $this->getSessionDate($sessionID);
			$data['monthYearTitle'] = date("Y", strtotime($x['START_DATE'])). '-'. date("Y", strtotime($x['END_DATE']));
		}
		$this->load->view('promon/reports/received_raa_project_view', $data);
	}
	//Projects Received AA
	public function project_received_aa(){
		$time_start = microtime_float();
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth =(int) $searchData['MONTH'];
		$mce = $searchData['CE_ID'];
		$data = array(); $reportData = array();
		$sessionID = $this->input->post('aa_session_id');
		$recs = $this->aaReceived($sessionID, $searchData);
		if ($recs){
			foreach($recs as $rec){
				if($rec->PROJECT_ID==0)
					$recs = array();
			}
		}
		$data['recs'] = $recs;
		if($sessionID==9999){//all session
			$data['monthYearTitle'] = '';
		}else if($sessionID==0){
			$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		}else{
			$x = $this->getSessionDate($sessionID);
			$data['monthYearTitle'] = date("Y", strtotime($x['START_DATE'])). '-'. date("Y", strtotime($x['END_DATE']));
		}
		$this->load->view('promon/reports/received_aa_project_view', $data);
	}
	//General Report
	public function general_report(){
		//$time_start = microtime_float();
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$mce = $searchData['CE_ID'];
		$mymonth = $mSearchMonth; $i=0;
		$myyear = $mSearchYear;
		$data = array(); 
		$sessionId = $this->getSessionID($mSearchMonth, $mSearchYear);
		$data['recs'] = $this->getGeneralReport($sessionId, $searchData);
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('promon/reports/general_report_view', $data);
	}
	//Abstract Reports
	public function abstract_report(){
		//$time_start = microtime_float();
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$mce = $searchData['CE_ID'];
		$data = array(); 
		$sessionId = $this->getSessionID($mSearchMonth, $mSearchYear);
		$data['recs'] = $this->getAbstractReport($sessionId, $searchData);
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('promon/reports/abstract_report_view', $data);
	}
	//
	public function summary_report(){
		date_default_timezone_set('Asia/Kolkata');
		//$time_start = microtime_float();
		$searchData = $this->getOfficeData(1);
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$mce = $searchData['CE_ID'];
		$mymonth = $mSearchMonth; $i=0;
		$myyear = $mSearchYear;
		$data = array(); 
		$reportData = array();
		
		$lastMonth1 = date("Y-m-d", $this->input->post('lastMonth'));
		//echo $lastMonth1 ;
		$lastMonthValue = strtotime($lastMonth1);
		
		$lastMonth2 = date("Y-m", strtotime('-1 month', $lastMonthValue )).'-01';
		$lastMonth3 = date("Y-m", strtotime('-2 month', $lastMonthValue )).'-01';

		$data['columnNames'] = array(
			date("My", strtotime($lastMonth3) ),
			date("My", strtotime($lastMonth2) ),
			date("My", strtotime($lastMonth1) ),
			'NOENTRY'
		);
		$startSessionID=PMON_MI_START_SESSION_ID;
		$currentSessionID = $this->session->userdata('CURRENT_SESSION_ID');
		if( ($currentSessionID-2)<PMON_MI_START_SESSION_ID){
			$startSessionID=PMON_MI_START_SESSION_ID;
		}else{
			$startSessionID = $currentSessionID-2;
		}
		$strS = '';
		$x = 1;
		$arrTargetCols = array();
		for($i=$startSessionID; $i<=$currentSessionID; $i++){
			$strS .= ' COUNT( IF(p.PROJECT_STATUS!=5 AND t.TARGET_LOCK_SESSION_ID='.$i.', 1, NULL)) AS TARGET_'.$x.', ';
				//.	'( '.$this->getSessionYear($i).') as TARGET_YEAR_'.$x.' ,';
			$arrTargetCols['TARGET_'.$x] = $this->getSessionYear($i);
			$x++;
		}
		$data['targetCols'] = $arrTargetCols;
		$strSQL = "SELECT  p.OFFICE_CE_NAME, p.OFFICE_SE_NAME, 
				p.OFFICE_CE_ID, p.OFFICE_SE_ID, 
				(p.OFFICE_EE_NAME)as OFFICE_NAME, p.OFFICE_EE_ID, 
					COUNT( IF(t.PROJECT_ID>0, 1, NULL)) AS T_PROJECTS,
					COUNT( IF(p.PROJECT_STATUS!=5, 1, NULL)) AS O_PROJECTS,
					COUNT( IF(p.PROJECT_STATUS=5, 1, NULL)) AS C_PROJECTS,
					COUNT( IF(p.PROJECT_STATUS!=5 AND t.SETUP_LOCK=1, 1, NULL)) AS SETUP_LOCK,
					".$strS."
					COUNT( IF(p.PROJECT_STATUS!=5 AND t.MONTH_LOCK= '".date("Y-m-d", strtotime($lastMonth3) ).
						"', 1, NULL)) AS '".date("My", strtotime($lastMonth3) )."', 
					COUNT( IF(p.PROJECT_STATUS!=5 AND t.MONTH_LOCK= '".date("Y-m-d", strtotime($lastMonth2) ).
						"', 1, NULL)) AS '".date("My", strtotime($lastMonth2) )."', 
					COUNT( IF(p.PROJECT_STATUS!=5 AND t.MONTH_LOCK= '".date("Y-m-d", strtotime($lastMonth1) ).
						"', 1, NULL)) AS '".date("My", strtotime($lastMonth1) )."', 
					COUNT( IF(p.PROJECT_STATUS!=5 AND t.MONTH_LOCK<'".date("Y-m-d", strtotime($lastMonth1) ).
						"', 1, NULL)) AS NOENTRY,
					COUNT( IF(p.PROJECT_STATUS!=5 AND p.PROJECT_START_DATE>'".
						date("Y-m-t", strtotime($lastMonth1) )."', 1, NULL)) AS NOT_STARTED,
					COUNT( IF(mont.PROJECT_ID>0, 1, NULL)) AS NOT_STARTED1
				 FROM pmon__v_projectlist_with_lock as p 
					INNER JOIN pmon__t_locks as t 
						ON(t.PROJECT_ID=p.PROJECT_ID) 
					LEFT JOIN (
						SELECT PROJECT_ID FROM pmon__t_monthlydata
							WHERE PROJECT_STATUS=2 AND MONTH_DATE='".date("Y-m-d", strtotime($lastMonth1) )."' 
					)AS mont on (p.PROJECT_ID=mont.PROJECT_ID)
				WHERE PROJECT_TYPE_ID=".$this->session->userData('PROJECT_TYPE_ID')." 
				 GROUP BY OFFICE_CE_ID, OFFICE_SE_ID, OFFICE_EE_ID
				 ";
		$result = $this->db->query($strSQL);
		//echo $strSQL;
		$data['records'] = $result->result();
		//showArrayValues($data['records']);
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		$this->load->view('promon/reports/summary_report_view', $data);
	}
	//
	protected function getSessionYear($sessionid=0){
		$rec = $this->db->get_where('__sessions', array('SESSION_ID'=>$sessionid));
		if($rec && $rec->num_rows()){
			$row = $rec->row();
			return ($row->SESSION_START_YEAR-2000).'-'.($row->SESSION_END_YEAR-2000	);
		}
		return '';
	}
	public function lock_status_report(){
		$searchData = $this->getOfficeData();
		$recs = $this->getLockStatus(PMON_MI_START_SESSION_ID, $searchData);
		echo '<table border="0"  class="ui-widget-content" cellpadding="8" cellspacing="1">
			<tr>
			<th class="ui-state-default">SNo.</th>
			<th class="ui-state-default">Project</th>
			<th class="ui-state-default">Setup Lock</th>
			<th class="ui-state-default">Target Locked</th>
			<th class="ui-state-default">Monthly Lock</th>
			</tr>';
		$i =1;
		foreach($recs as $rec){
			echo '<tr>';
			echo '<td class="ui-widget-content" align="center">'.$i++.'</td>';
			echo '<td class="ui-widget-content">'.$rec->PROJECT_NAME.'</td>';
			echo '<td class="ui-widget-content" align="center">'.(($rec->SETUP_LOCK) ? '<span class="cus-lock"></span>': 
				"<span class='cus-bullet-green'></span>").
				'</td>';
			echo '<td class="ui-widget-content" align="center">'.$rec->SESSION_YEAR.'</td>';
			echo '<td class="ui-widget-content" align="center">'.date("M, Y", strtotime($rec->MONTH_LOCK)).'</td>';
			echo '</tr>';
		}
	}
	public function test_status_report(){
		$searchData = $this->getOfficeData();
		$strSQL = 'SELECT PROJECT_TYPE_ID, COUNT(PROJECT_ID) as COUNT_TOTAL
			FROM pmon__m_project_setup GROUP BY PROJECT_TYPE_ID';
		$result = $this->db->query($strSQL);
		$MINOR_PROJECTS = 0;
		$MEDIUM_PROJECTS = 0;
		if($result){
			if($result->num_rows()){
				foreach($result->result() as $row){
					if($row->PROJECT_TYPE_ID==1)
						$MINOR_PROJECTS = $row->COUNT_TOTAL;
					else
						$MEDIUM_PROJECTS = $row->COUNT_TOTAL;
				}
			}
		}
		/*$strSQL = '';
		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows()){
				foreach($result->result() as $row){
					$row->;
				}
			}
		}*/
		
		$strSQL = 'SELECT count(pmon__m_project_setup.PROJECT_ID) as COUNT_TOTAL, 
					pmon__m_project_setup.PROJECT_TYPE_ID
				FROM pmon__m_project_setup
					INNER JOIN __projects ON pmon__m_project_setup.PROJECT_ID = __projects.PROJECT_ID
				WHERE __projects.PROJECT_STATUS = 1
				GROUP BY pmon__m_project_setup.PROJECT_TYPE_ID';
		$result = $this->db->query($strSQL);
		$COMPLETED_MINOR_PROJECTS = 0;
		$COMPLETED_MEDIUM_PROJECTS = 0;
		if($result){
			if($result->num_rows()){
				foreach($result->result() as $row){
					if($row->PROJECT_TYPE_ID==1)
						$COMPLETED_MINOR_PROJECTS = $row->COUNT_TOTAL;
					else
						$COMPLETED_MEDIUM_PROJECTS = $row->COUNT_TOTAL;
				}
			}
		}
		$strSQL = 'SELECT count(t.SETUP_LOCK) as COUNT_TOTAL, 
					COUNT( IF(t.SETUP_LOCK=1, 1, NULL)) AS COUNT_SETUP_LOCK,
					COUNT( IF(t.SETUP_LOCK=0, 1, NULL)) AS COUNT_SETUP_UNLOCK,
					p.PROJECT_TYPE_ID
				FROM pmon__m_project_setup as p
					INNER JOIN pmon__t_locks as t ON p.PROJECT_ID = t.PROJECT_ID
				GROUP BY p.PROJECT_TYPE_ID';
		echo $strSQL;
		$result = $this->db->query($strSQL);
		$LOCKED_MINOR_PROJECTS = 0;
		$LOCKED_MEDIUM_PROJECTS = 0;
		$UNLOCKED_MINOR_PROJECTS = 0;
		$UNLOCKED_MEDIUM_PROJECTS = 0;
		if($result && $result->num_rows()){
			foreach($result->result() as $row){
				if($row->PROJECT_TYPE_ID==1){
					$LOCKED_MINOR_PROJECTS = $row->COUNT_SETUP_LOCK;
					$UNLOCKED_MINOR_PROJECTS = $row->COUNT_SETUP_UNLOCK;
				}else{
					$UNLOCKED_MEDIUM_PROJECTS = $row->COUNT_SETUP_UNLOCK;
					$LOCKED_MEDIUM_PROJECTS = $row->COUNT_SETUP_LOCK;
				}
			}
		}
		$strSQL = 'SELECT COUNT(pmon__m_project_setup.PROJECT_ID)AS COUNT_TOTAL ,
					pmon__m_project_setup.PROJECT_TYPE_ID
					FROM
					pmon__m_project_setup
					INNER JOIN __projects ON pmon__m_project_setup.PROJECT_ID = __projects.PROJECT_ID
					WHERE __projects.PROJECT_STATUS = 1
					and pmon__m_project_setup.PROJECT_ID IN ('.
						implode(',', $this->getProjectIDs(0, $searchData)).
					')  
					GROUP BY PROJECT_TYPE_ID
					';
					
		$result = $this->db->query($strSQL);
		$COMPLETED_LOCKED_MINOR_PROJECTS = 0;
		$COMPLETED_LOCKED_MEDIUM_PROJECTS = 0;
		//echo $this->db->last_query().'<br />';
		if($result && $result->num_rows()){
			foreach($result->result() as $row){
				if($row->PROJECT_TYPE_ID==1)
					$COMPLETED_LOCKED_MINOR_PROJECTS = $row->COUNT_TOTAL;
				else
					$COMPLETED_LOCKED_MEDIUM_PROJECTS = $row->COUNT_TOTAL;
			}
		}
		echo '<table border="1" class="ui-widget-content" cellpadding="8" cellspacing="1" >';
		/*echo '<tr>	<td class="ui-widget-content">Total Projects</td>
					<td class="ui-widget-content">'.($MEDIUM_PROJECTS + $MINOR_PROJECTS).'</td>
			 </tr>';*/
		echo '<tr>	<td class="ui-widget-content">Projects</td>
					<td class="ui-widget-content">Minor</td>
					<td class="ui-widget-content">Medium</td>
					<td class="ui-widget-content">Total</td>
			 </tr>';
		echo '<tr>	<td class="ui-widget-content">Total</td>
					<td class="ui-widget-content" align="right">'.$MINOR_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.$MEDIUM_PROJECTS .'</td>
					<td class="ui-widget-content" align="right">'.($MEDIUM_PROJECTS + $MINOR_PROJECTS).'</td>
			 </tr>';
		echo '<tr>	<td class="ui-widget-content">Completed </td>
					<td class="ui-widget-content" align="right">'.$COMPLETED_MINOR_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.$COMPLETED_MEDIUM_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.($COMPLETED_MINOR_PROJECTS+ $COMPLETED_MEDIUM_PROJECTS).'</td>
			 </tr>';
		echo '<tr>	<td class="ui-widget-content">InCompleted </td>
					<td class="ui-widget-content" align="right">'.($MINOR_PROJECTS-$COMPLETED_MINOR_PROJECTS).'</td>
					<td class="ui-widget-content" align="right">'.($MEDIUM_PROJECTS-$COMPLETED_MEDIUM_PROJECTS).'</td>
					<td class="ui-widget-content" align="right">'.(
						($MINOR_PROJECTS-$COMPLETED_MINOR_PROJECTS)
						+ ($MEDIUM_PROJECTS-$COMPLETED_MEDIUM_PROJECTS)
					).'</td>
			 </tr>';
		echo '<tr>	<td class="ui-widget-content">Setup Locked </td>
					<td class="ui-widget-content" align="right">'.$LOCKED_MINOR_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.$LOCKED_MEDIUM_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.($LOCKED_MINOR_PROJECTS+ $LOCKED_MEDIUM_PROJECTS).'</td>
			 </tr>';
		echo '<tr>	<td class="ui-widget-content">Setup UnLocked </td>
					<td class="ui-widget-content" align="right">'.$UNLOCKED_MINOR_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.$UNLOCKED_MEDIUM_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.($UNLOCKED_MINOR_PROJECTS+ $UNLOCKED_MEDIUM_PROJECTS).'</td>
			 </tr>';
		echo '<tr>	<td class="ui-widget-content">Setup Locked completed</td>
					<td class="ui-widget-content" align="right">'.$COMPLETED_LOCKED_MINOR_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.$COMPLETED_LOCKED_MEDIUM_PROJECTS.'</td>
					<td class="ui-widget-content" align="right">'.($COMPLETED_LOCKED_MINOR_PROJECTS+ $COMPLETED_LOCKED_MEDIUM_PROJECTS).'</td>
			 </tr>';
		echo '<tr>	<td class="ui-widget-content">Setup Locked ongoing</td>
					<td class="ui-widget-content" align="right">'.($LOCKED_MINOR_PROJECTS-$COMPLETED_LOCKED_MINOR_PROJECTS).'</td>
					<td class="ui-widget-content" align="right">'.($LOCKED_MEDIUM_PROJECTS-$COMPLETED_LOCKED_MEDIUM_PROJECTS).'</td>
					<td class="ui-widget-content" align="right">'.(
					($LOCKED_MINOR_PROJECTS-$COMPLETED_LOCKED_MINOR_PROJECTS) + 
					($LOCKED_MEDIUM_PROJECTS-$COMPLETED_LOCKED_MEDIUM_PROJECTS)
					).'</td>
			 </tr>';
		echo '</table>';
	}
	private function getSetupData($projectIds){
		$mFields = array(
			'LA_NA', 'FA_NA', 
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 'STEEL_WORKS_NA',
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA', 'CANAL_LINING_NA',
			'IRRIGATION_POTENTIAL_NA'
		);
		$setupData = array();
		$this->db->where_in('PROJECT_ID', $projectIds);
		$this->db->order_by('PROJECT_ID');
        $recs = $this->db->get_where('pmon__t_estimated_status', array('PROJECT_ID'=>$this->PROJECT_ID));
		$isExists = false;
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mFields); $i++){
				$setupData[ $mFields[$i] ] = $rec->{ $mFields[$i] };
			}
		}
		if(!$isExists){
			for($i=0; $i<count($mFields); $i++){
				$setupData[ $mFields[$i] ] = 0;
			}
		}
		return $setupData;
	}
	private function getCommonFields(){
		return array(
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 
			'FA_HA', 'FA_COMPLETED_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES', 'CANAL_MASONRY', 'ROAD_WORKS', 
			'IRRIGATION_POTENTIAL'
		);
	}
//SE Lock Report
	public function selockreport(){
		$searchData = $this->getOfficeData();
		$mSearchYear = (int) $searchData['YEAR'];
		$mSearchMonth = (int) $searchData['MONTH'];
		$where = " LOCKED_MONTH='". $mSearchYear.'-'.str_pad($mSearchMonth, 2, '0', STR_PAD_LEFT).'-01'."' ";
		if($searchData['EE_ID']>0) $where .= ' AND EE_OFFICE_ID='.$searchData['EE_ID'];
		if($searchData['SE_ID']>0) $where .= ' AND SE_OFFICE_ID='.$searchData['SE_ID'];
		if($searchData['CE_ID']>0) $where .= ' AND CE_OFFICE_ID='.$searchData['CE_ID'];

		$where .= ' AND PROJECT_TYPE_ID='.$this->session->userData('PROJECT_TYPE_ID');
		$strSQL = 'SELECT CE_OFFICE_ID, SE_OFFICE_ID, EE_OFFICE_ID, 
			CE_OFFICE_NAME, SE_OFFICE_NAME, EE_OFFICE_NAME, LOCKED, LOCK_DATE
		FROM pmon__v_selocks_offices 
			WHERE '.$where.
			' ORDER BY CE_OFFICE_NAME, SE_OFFICE_NAME, EE_OFFICE_NAME ';
		$recs = $this->db->query($strSQL);
		$reportData = array();
		if($recs && $recs->num_rows()){
			$i = 0;
			foreach($recs->result() as $rec){
				$reportData[$i] = array(
					'SNO'=>($i+1),
					/*'PROJECT_ID' => $rec->PROJECT_ID,
					'NAME' => $rec->PROJECT_NAME.'<BR />'.$rec->PROJECT_NAME_HINDI ,*/
					'OFFICE'=> $rec->EE_OFFICE_NAME,
					'eeid' => $rec->EE_OFFICE_ID,
					'seid' => $rec->SE_OFFICE_ID,
					'ceid' => $rec->CE_OFFICE_ID,
					'sename' => $rec->SE_OFFICE_NAME,
					'cename' => $rec->CE_OFFICE_NAME,
					'LOCK_DATE' => date("d-m-Y", strtotime($rec->LOCK_DATE))
				);
				//$reportData[$i]['IRR_POT_11'] = ($mIrr==0) ? giveComma($mac, 2) : giveComma($mIrr, 2);
				$i++;
			}
		}
		$data['reportData'] = $reportData;
		//$time_end = microtime_float();
		//$data['mytime'] = $time_end - $time_start;
		$data['monthYearTitle'] = $this->getMonthYearTitle($searchData);
		
		$this->load->view('promon/reports/se_locks_view', $data);
	}
    public function getCEReport(){
		$searchData = $this->getOfficeData();
		$ceChoice = $this->input->post('ce_choice');
		$data['anicut'] = (($ceChoice==6) ? "Anicut" : "");
		$where = ' AND ( (p.PROJECT_SUB_TYPE_ID!=4) AND '.
			(($ceChoice==6) ? '(p.PROJECT_SUB_TYPE_ID=6) ' : '(p.PROJECT_SUB_TYPE_ID!=6) ').' ) ';
		
		$projectTypeId = $this->session->userData('PROJECT_TYPE_ID');
		
		//project list order by name, district, blocks
		$ceId = (int) $searchData['CE_ID'];
		$data['reportData'] = FALSE;
		$strSQL = 'SELECT p.OFFICE_CE_NAME, p.DISTRICT_NAME, 
				(p.HEAD_WORK_DISTRICT_ID) AS DISTRICT_ID, 
				count(p.PROJECT_ID) as TOTAL_PROJECTS,
				SUM(qty.IRRIGATION_POTENTIAL)AS DIP,
				SUM(aam.AA_AMOUNT)AS AAM 
			FROM pmon__v_projectlist_with_lock as p 
				INNER JOIN pmon__t_estimated_qty as qty ON (p.PROJECT_ID=qty.PROJECT_ID)
				INNER JOIN(
					SELECT ps.PROJECT_ID,
						IF( (IFNULL(ra.RAA_AMOUNT,0)=0),
							ps.AA_AMOUNT, 
							IF(ps.AA_AMOUNT>ra.RAA_AMOUNT, 
							ps.AA_AMOUNT,
							ra.RAA_AMOUNT)	
						)AS AA_AMOUNT
					 FROM pmon__m_project_setup as ps
						LEFT JOIN pmon__t_raa_project as ra 
							ON (ps.PROJECT_ID=ra.PROJECT_ID )
				)AS aam ON (aam.PROJECT_ID = p.PROJECT_ID)
				WHERE PROJECT_TYPE_ID='.$projectTypeId .' AND PROJECT_STATUS<5 
					AND OFFICE_CE_ID='.$searchData['CE_ID'].' 
					'.$where.' 
			GROUP BY OFFICE_CE_ID, DISTRICT_NAME
			ORDER BY OFFICE_CE_NAME, DISTRICT_NAME';
		$recs = $this->db->query($strSQL);
		$ceName = '';
		$arrFields = array('SNO', 'DISTRICT', 'AA_AMOUNT', 'DIP', 'ONGOING');
		for($i=1;$i<=17;$i++){
			array_push($arrFields, 'Q'.$i);
		}
		$arrData = array();
		if($recs && $recs->num_rows()){
			$count = 1;
			foreach($recs->result() as $rec){
				if($count==1) $ceName = $rec->OFFICE_CE_NAME;
				$arrTemp = array();
				for($i=0; $i<count($arrFields);$i++){
					$arrTemp[$arrFields[$i]] = '';
				}
				$arrData['p'.$rec->DISTRICT_ID] = $arrTemp;
				$arrData['p'.$rec->DISTRICT_ID]['SNO'] = $count++;
				$arrData['p'.$rec->DISTRICT_ID]['DISTRICT'] = $rec->DISTRICT_NAME;
				$arrData['p'.$rec->DISTRICT_ID]['AA_AMOUNT'] = $rec->AAM;
				$arrData['p'.$rec->DISTRICT_ID]['DIP'] = $rec->DIP;
				$arrData['p'.$rec->DISTRICT_ID]['ONGOING'] = $rec->TOTAL_PROJECTS;
				for($i=1;$i<=17;$i++){
					//array_push($arrFields, 'Q'.$i);
					$arrData['p'.$rec->DISTRICT_ID]['Q'.$i] = 0;
				}
			}
			//////////////////////////
			$arrConditions = array(
				array(2, '2015-04-01', '2015-06-30'),
				array(3, '2015-07-01', '2015-09-30'),
				array(4, '2015-10-01', '2015-12-31'),
				array(5, '2016-01-01', '2016-03-31'),
				array(6, '2016-04-01', '2016-06-30'),
				array(7, '2016-07-01', '2016-09-30'),
				array(8, '2016-10-01', '2016-12-31'),
				array(9, '2017-01-01', '2017-03-31'),
				array(10, '2017-04-01', '2017-06-30'),
				array(11, '2017-07-01', '2017-09-30'),
				array(12, '2017-10-01', '2017-12-31'),
				array(13, '2018-01-01', '2018-03-31'),
				array(14, '2018-04-01', '2018-06-30'),
				array(15, '2018-07-01', '2018-09-30'),
				array(16, '2018-10-01', '2018-12-31')
			);
			$arrSQL = array();
			
			foreach($arrConditions as $arrCondition){
				array_push(
					$arrSQL,
					'SELECT (p.HEAD_WORK_DISTRICT_ID) AS DISTRICT_ID, count(p.PROJECT_ID) as TOTAL_PROJECTS, ('.$arrCondition[0].')AS Q1
					FROM pmon__v_projectlist_with_lock AS p
						INNER JOIN 
						(
							SELECT 
								m.PROJECT_ID,
								IF( (IFNULL(ext.COMPLETION_DATE,0)=0),
									m.PROJECT_COMPLETION_DATE, 
										IF(m.PROJECT_COMPLETION_DATE>ext.COMPLETION_DATE, 
										m.PROJECT_COMPLETION_DATE,
										ext.COMPLETION_DATE)	
								)AS COMPDATE
							FROM pmon__m_project_setup as m
							LEFT JOIN (
								SELECT max(NEW_COMPLETION_DATE) as COMPLETION_DATE, PROJECT_ID FROM pmon__t_extensions
								GROUP BY PROJECT_ID
							)as ext ON(ext.PROJECT_ID=m.PROJECT_ID)
						) AS calcp ON(calcp.PROJECT_ID=p.PROJECT_ID)
						WHERE PROJECT_TYPE_ID='.$projectTypeId .'  AND PROJECT_STATUS<5 
						AND OFFICE_CE_ID='.$searchData['CE_ID'].
						$where.' 
						AND (calcp.COMPDATE BETWEEN "'.$arrCondition[1].'" AND "'.$arrCondition[2].'")
						GROUP BY OFFICE_CE_ID, DISTRICT_ID '
				);
			}
			$strSQL = '
			SELECT (p.HEAD_WORK_DISTRICT_ID) AS DISTRICT_ID, count(p.PROJECT_ID) as TOTAL_PROJECTS, (1)AS Q1
				FROM pmon__v_projectlist_with_lock AS p
					INNER JOIN 
					(
						SELECT 
						m.PROJECT_ID,
						IF( (IFNULL(ext.COMPLETION_DATE,0)=0),
							m.PROJECT_COMPLETION_DATE, 
							
								IF(m.PROJECT_COMPLETION_DATE>ext.COMPLETION_DATE, 
								m.PROJECT_COMPLETION_DATE,
								ext.COMPLETION_DATE)	
							
						)AS COMPDATE
					
							FROM pmon__m_project_setup as m
						LEFT JOIN (
							SELECT max(NEW_COMPLETION_DATE) as COMPLETION_DATE, PROJECT_ID FROM pmon__t_extensions
							GROUP BY PROJECT_ID
						)as ext ON(ext.PROJECT_ID=m.PROJECT_ID)
					) AS calcp ON(calcp.PROJECT_ID=p.PROJECT_ID)
					WHERE PROJECT_TYPE_ID='.$projectTypeId .'  AND PROJECT_STATUS<5 
					AND OFFICE_CE_ID='.$searchData['CE_ID'].
					$where.' 
					AND calcp.COMPDATE <"2015-04-01" 
					GROUP BY OFFICE_CE_ID, DISTRICT_ID
			UNION ALL ' .implode(' UNION ALL ', $arrSQL) .
			' UNION ALL 
				SELECT (p.HEAD_WORK_DISTRICT_ID) AS DISTRICT_ID, count(p.PROJECT_ID) as TOTAL_PROJECTS, (17)AS Q1
				FROM pmon__v_projectlist_with_lock AS p
					INNER JOIN 
					(
						SELECT 
						m.PROJECT_ID,
						IF( (IFNULL(ext.COMPLETION_DATE,0)=0),
							m.PROJECT_COMPLETION_DATE, 
							
								IF(m.PROJECT_COMPLETION_DATE>ext.COMPLETION_DATE, 
								m.PROJECT_COMPLETION_DATE,
								ext.COMPLETION_DATE)	
							
						)AS COMPDATE
					
							FROM pmon__m_project_setup as m
						LEFT JOIN (
							SELECT max(NEW_COMPLETION_DATE) as COMPLETION_DATE, PROJECT_ID FROM pmon__t_extensions
							GROUP BY PROJECT_ID
						)as ext ON(ext.PROJECT_ID=m.PROJECT_ID)
					) AS calcp ON(calcp.PROJECT_ID=p.PROJECT_ID)
					WHERE PROJECT_TYPE_ID='.$projectTypeId .'  AND PROJECT_STATUS<5 
					AND OFFICE_CE_ID='.$searchData['CE_ID'].
					$where.' 
					AND calcp.COMPDATE >"2018-12-31" 
					GROUP BY OFFICE_CE_ID, DISTRICT_ID
			 ORDER BY DISTRICT_ID, Q1';
			$recs = $this->db->query($strSQL);
			//showArrayValues($recs->result());
			//$arrM = array('q1'=>'Dec', 'q10'=>'Mar', 'q14'=>'Apr', 'q15'=>'May', 'q16'=>'Jun', 'q17'=>'Jul', 'q18'=>'Aug', 'q19'=>'Sep');
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					$x = array_key_exists('p'.$rec->DISTRICT_ID, $arrData);
					//echo 'X:'.$x . ' ';
					if($x){
						$arrData['p'.$rec->DISTRICT_ID]['Q'.$rec->Q1] = $rec->TOTAL_PROJECTS;
					}
				}
			}
			$data['reportData'] = $arrData;
		}
		$data['CE_NAME'] = $ceName;
		//showArrayValues($data);
		//$time_end = microtime_float();
		//$data['mytime'] = $time_end - $time_start;
		$data['monthYearTitle'] = '<font size="-1">'.$ceName.'</fonts>';
		$this->load->view('promon/reports/ce_project_list_view', $data);
	}
}