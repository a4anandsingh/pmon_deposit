<?php
class Target_c extends MX_Controller{
	private $PROJECT_ID, $message;
	function __construct(){
		parent::__construct();
		$this->message = array();
		$this->PROJECT_ID = 0;
	}
    function index(){	 
		$data = array();
		$this->session->set_userdata(array('TARGET_PROJECT_TYPE_ID'=>1));
		$data['message'] = '';
		$arrProjectTypes = array('', 'Deposit', 'Medium', 'Major');
		$data['page_heading'] = pageHeading(
			'PROMON - '. $arrProjectTypes[1].
			' Project - Financial and Physical Target Setup'
		);
		/*$mm = (int) date("m");
		$dd = (int) date("d");
		if($mm==4 && $dd<=15){
			echo '<h1>Target Entry will be available After 15 April...</h1>';
			return;
		}*/
		$this->load->library('office_filter');
		$data['office_list'] = $this->office_filter->office_list();
		$data['project_target_grid'] = $this->createGrid();//
        $this->load->view('pmon_deposit/target_index_view', $data);
    }
	//
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
	//
	private function createGrid(){
		$permissions = $this->getPermissions();
		$buttons = array();
		$mfunctions = array();
		array_push($mfunctions , "loadComplete: function(){afterReload();}");
		$aData = array(
			'set_columns' => array(
				array(
					'label' => 'Project',
					'name' => 'PROJECT_NAME',
					'width' => 100,
					'align' => "left",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				),
		  		array(
					'label' => 'परियोजना',
					'name' => 'PROJECT_NAME_HINDI',
					'width' => 80,
					'align' => "left",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				),
				array(
					'label' => 'Project Code',
					'name' => 'PROJECT_CODE',
					'width' => 40,
					'align' => "left",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				),
				array(
					'label' => addslashes('<span class="cus-lock"></span>Session'),
					'name' => 'TARGET_LOCK_SESSION_ID',
					'width' => 30,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true
				),
				array(
				 	'label' =>  addslashes('<span class="cus-lock"></span>Month'),
					'name' => 'MONTH_LOCK',
					'width' => 30,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> 'date',
					'newformat'=>'M, Y',
					'searchoptions'=>''
				),
				array(
					'label' => 'Action',
					'name' => 'ADD',
					'width' => 40,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>false,
					'hidden'=>false,
					'view'=>true,
					'search'=>false,
					'formatter'=> '',
					'searchoptions'=>''
				),
				array(
					'label' => '',
					'name' => 'lock',
					'width' => 25,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>false,
					'formatter'=> '',
					'searchoptions'=>''
				)
			),
			'custom' => array("button"=>$buttons, "function"=>$mfunctions),
			'div_name' => 'projectListGrid',
			'source' => 'getProjectGrid',
			'postData' => '{}',
			'rowNum'=>10,
			'width'=>DEFAULT_GRID_WIDTH,
			'height' => '',
			'altRows'=> true,
			'rownumbers'=>true,	
			'sort_name' => 'PROJECT_NAME',
			'sort_order' => 'asc',
			'primary_key' => 'PROJECT_NAME',
			'add_url' => '',
			'edit_url' => '',
			'delete_url' => '',
			'caption' => addslashes('<span class="cus-target"></span>Projects for Target Entry'),
			'pager' => true,
			'showTotalRecords' => true,
			'toppager' =>false,
			'bottompager' =>true,
			'multiselect'=>false,
			'toolbar'=> true,
			'toolbarposition'=>'top',
			'hiddengrid'=>false,
			'editable'=>false,
			'forceFit'=>true,
			'gridview'=>true,
			'footerrow'=>false, 
			'userDataOnFooter'=>true, 
			'treeGrid'=>false, 
			'custom_button_position'=>'bottom'
		);
		return buildGrid($aData);
	}
	public function getProjectGrid(){
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);
		if ( $this->input->post('SEARCH_PROJECT_NAME') ){
			array_push(
				$objFilter->SQL_PARAMETERS, 
				array("PROJECT_NAME", 'LIKE', $this->input->post('SEARCH_PROJECT_NAME'))
			);
		}
		$EEE = '';
		$SDO_ID = $this->input->post('SDO_ID');
		$EE_ID = $this->input->post('EE_ID');
		$CE_ID = $this->input->post('CE_ID');
		$SE_ID = $this->input->post('SE_ID');
		if($EE_ID==false && $CE_ID==false && $SE_ID==false && $SDO_ID==false){
			$EE_ID = $this->session->userData('EE_ID');
			$SE_ID = $this->session->userData('SE_ID');
			$CE_ID = $this->session->userData('CE_ID');
			$SDO_ID = $this->session->userData('SDO_ID');
		}
		if ($EE_ID==0 && $SE_ID==0 && $CE_ID==0 && $SDO_ID==0){
			//DO NOTHING .....
		}else{
			$EEE = ($SDO_ID==0)? '' : ' OFFICE_SDO_ID='.$SDO_ID;
			$EEE .= ($EE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_EE_ID='.$EE_ID;
			$EEE .= ($SE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SE_ID='.$SE_ID;
			$EEE .= ($CE_ID==0)? '' : (( ($EEE=='') ? '':' AND '). 'OFFICE_CE_ID='.$CE_ID);
			//array_push(	$objFilter->WHERE, $EEE);
		}
		if($EEE!='') array_push($objFilter->WHERE, $EEE); //.' GROUP BY PROJECT_ID');
		/*$objFilter->SQL = 'SELECT PROJECT_ID, PROJECT_CODE, PROJECT_NAME, PROJECT_NAME_HINDI,
				SETUP_LOCK, TARGET_LOCK, TARGET_LOCK_SESSION_ID, PROJECT_START_DATE, 
				SESSION_START_YEAR, SESSION_END_YEAR, MONTH_LOCK, SESSION_ID 
			FROM dep_pmon__v_projectlist_with_lock 
				WHERE PROJECT_TYPE_ID='.$this->session->userData('TARGET_PROJECT_TYPE_ID').'
					  AND SETUP_LOCK=1 AND ((PROJECT_STATUS<5) OR ((PROJECT_STATUS>=5) AND (SE_COMPLETION=0))) ';*/
        $objFilter->SQL = "SELECT 
            a.PROJECT_ID, a.PROJECT_SETUP_ID, a.PROJECT_CODE, a.PROJECT_NAME, a.PROJECT_NAME_HINDI, 
            a.SETUP_LOCK, a.TARGET_LOCK_SESSION_ID, a.PROJECT_START_DATE, 
            a.SESSION_START_YEAR, a.SESSION_END_YEAR, a.MONTH_LOCK, a.SESSION_ID,a.SE_COMPLETION, a.IS_MI
        FROM(
                SELECT 
                    PROJECT_ID, PROJECT_SETUP_ID, PROJECT_CODE, PROJECT_NAME, PROJECT_NAME_HINDI, 
                    SETUP_LOCK, TARGET_LOCK_SESSION_ID, PROJECT_START_DATE, 
                    SESSION_START_YEAR, SESSION_END_YEAR, MONTH_LOCK, SESSION_ID,SE_COMPLETION,
                    OFFICE_EE_ID, OFFICE_SE_ID, OFFICE_CE_ID, '0' as IS_MI
                FROM
                    dep_pmon__v_projectlist_with_lock 
                WHERE PROJECT_TYPE_ID='1'
                    AND SETUP_LOCK=1 AND ((PROJECT_STATUS<5) OR ((PROJECT_STATUS>=5) AND (SE_COMPLETION=0)))
                UNION
                SELECT
                    dep_mi__m_project_setup.PROJECT_ID,dep_mi__m_project_setup.PROJECT_SETUP_ID,
                    dep_mi__m_project_setup.PROJECT_CODE,
                    dep_mi__m_project_setup.PROJECT_NAME,
                    dep_mi__m_project_setup.PROJECT_NAME_HINDI,
                    dep_mi__t_locks.SETUP_LOCK AS SETUP_LOCK ,
                    ifnull( dep_mi__t_locks.TARGET_LOCK_SESSION_ID, 0 ) AS TARGET_LOCK_SESSION_ID,
                    dep_mi__m_project_setup.AA_DATE AS PROJECT_START_DATE,
                    ifnull( __sessions.SESSION_START_YEAR, 0 ) AS SESSION_START_YEAR,
                    ifnull( __sessions.SESSION_END_YEAR, 0 ) AS SESSION_END_YEAR,
                    ifnull( dep_mi__t_locks.MONTH_LOCK, '0000-00-00') AS MONTH_LOCK,
                    dep_mi__m_project_setup.SESSION_ID,
                    ifnull( dep_mi__t_locks.SE_COMPLETION, 0 ) AS SE_COMPLETION,
                    office_ee.OFFICE_ID AS OFFICE_EE_ID,
                    office_se.OFFICE_ID AS OFFICE_SE_ID,	
                    office_ce.OFFICE_ID AS OFFICE_CE_ID, '1' as IS_MI
                FROM
                    dep_mi__m_project_setup 	
                LEFT JOIN dep_mi__m_projects_office pro_office ON ( dep_mi__m_project_setup.PROJECT_SETUP_ID = pro_office.PROJECT_SETUP_ID )
                LEFT JOIN __offices office_ee ON  ( pro_office.EE_ID = office_ee.OFFICE_ID )  
                LEFT JOIN __offices office_se ON  ( office_ee.PARENT_OFFICE_ID = office_se.OFFICE_ID ) 
                LEFT JOIN __offices office_ce ON  ( office_se.PARENT_OFFICE_ID = office_ce.OFFICE_ID ) 
                LEFT JOIN dep_mi__t_locks ON  ( dep_mi__m_project_setup.PROJECT_SETUP_ID = dep_mi__t_locks.PROJECT_SETUP_ID ) 
                LEFT JOIN __sessions ON  ( dep_mi__t_locks.TARGET_LOCK_SESSION_ID = __sessions.SESSION_ID ) 
                LEFT JOIN __sessions setup_session ON ( dep_mi__m_project_setup.SESSION_ID = setup_session.SESSION_ID ) 
                WHERE PROJECT_TYPE_ID='1'
                AND dep_mi__t_locks.SETUP_LOCK=1 AND ((dep_mi__m_project_setup.WORK_STATUS<5) OR ((dep_mi__m_project_setup.WORK_STATUS>=5) AND (SE_COMPLETION=0)))			
            ) AS  a WHERE 1";
		$objFilter->executeMyQuery();
		//echo $objFilter->PREPARED_SQL;
		$rows = array();
		$currentSessionId = $this->session->userdata('CURRENT_SESSION_ID');
		$previousSessionId = $currentSessionId-1;
		$currentSessionYear = $this->getSession($currentSessionId);
		$previousSessionYear = $this->getSession($previousSessionId);
		if($objFilter->TOTAL_RECORDS){
			foreach($objFilter->RESULT as $row){
				$icon = 'cus-target';
				$fieldValues = array();
				array_push($fieldValues, '"'.addslashes(cleanDataForGrid($row->PROJECT_NAME)).'"');
				array_push($fieldValues, '"'.addslashes($row->PROJECT_NAME_HINDI ).'"');
				array_push($fieldValues, '"'.addslashes($row->PROJECT_CODE ).'"');
				$endYear = str_replace('20', '', $row->SESSION_END_YEAR);
				if($row->TARGET_LOCK_SESSION_ID>0){
					array_push($fieldValues, '"'.addslashes($row->SESSION_START_YEAR.'-'. $row->SESSION_END_YEAR).'"');
					array_push($fieldValues, '"'.addslashes($row->MONTH_LOCK ).'"');
				}else{
					array_push($fieldValues, '"'.addslashes('<span class="cus-bullet-green"></span>').'"');
					array_push($fieldValues, '""');
				}
				//
				$showTargetButton = true;
				$lockedMonth = 0;
				if($row->MONTH_LOCK=='0000-00-00' || $row->MONTH_LOCK==0){
					//echo $row->TARGET_LOCK_SESSION_ID.'::'.$row->PROJECT_START_DATE;
					if($row->TARGET_LOCK_SESSION_ID==0){
						$startSessionID = $this->getSessionIDFromDate($row->PROJECT_START_DATE);
						$targetEntrySessionId = $startSessionID;
						if($startSessionID<$currentSessionId){
							$sessionId = $row->SESSION_ID;
							$caption = str_replace('-20', '-', $this->getSession($sessionId));
							$targetEntrySessionId = $sessionId;
                        	//if($row->PROJECT_ID==7338) echo $startSessionID . '-' . $currentSessionId. '-'.$sessionId. '-'.$targetEntrySessionId.'-'.$caption ;
						}else if($startSessionID<=PMON_DEP_START_SESSION_ID){
							$sessionId=PMON_DEP_START_SESSION_ID;
							$caption = str_replace('-20', '-', $this->getSession($sessionId));
						}else{
							$caption = str_replace('-20', '-', $this->getSession($startSessionID));
							$sessionId = $startSessionID;
						}
					}else{
						if ($row->TARGET_LOCK_SESSION_ID==$currentSessionId){
							$caption = $currentSessionYear;
							$sessionId = $currentSessionId;
							$icon = 'cus-lock';
							$showTargetButton = false;
						}else if($row->TARGET_LOCK_SESSION_ID==$previousSessionId){
							$sessionId = $previousSessionId;
							if($lockedMonth>=2){
								$caption = str_replace('-20', '-', $currentSessionYear);
							}else{
								$caption = $previousSessionYear;
								$icon = 'cus-lock';
								$showTargetButton = false;
							}
						}else{
							$caption = ($row->SESSION_START_YEAR+1).'-'.($endYear+1);
							$sessionId = $row->TARGET_LOCK_SESSION_ID+1;
						}
						$targetEntrySessionId = $sessionId;
					}
					$targetEntrySessionId = $sessionId;
				}else{
					//$icon = 'cus-wrench-orange';
					$icon = 'cus-target';
					$endOfSessionDate = strtotime($row->SESSION_END_YEAR.'03-01');
					$lockedMonth = (int) date("Y-m-d", strtotime($row->MONTH_LOCK));
					$lockedMonthValue = strtotime($row->MONTH_LOCK);
					$sessionId = $row->TARGET_LOCK_SESSION_ID;
					$lockMonthSessionId = $this->getSessionIDFromDate($row->MONTH_LOCK);
					$readyToLock = false;
					$showTargetButton = false;
					$caption = '';
					$targetEntrySessionId = 0;
					if($lockedMonthValue<=$endOfSessionDate){
						$lockedMonth = (int) date("n", $lockedMonthValue);
						if($lockedMonth==3){
							if($lockMonthSessionId<$sessionId){
								//do nothing
							}else{
								$showTargetButton = true;
								$caption = str_replace('-20', '-', $this->getSession($sessionId+1));
								$targetEntrySessionId = $sessionId+1;
							}
						}
					}else if($row->TARGET_LOCK_SESSION_ID==$currentSessionId){
						array_push($fieldValues, '"'.addslashes($row->SESSION_START_YEAR.'-'.$row->SESSION_END_YEAR.'2').'"');
						array_push($fieldValues, '"'.addslashes('<span class="cus-lock"></span>').'"');
					}else{
						if($row->TARGET_LOCK_SESSION_ID<$currentSessionId){
							//if feb locked then current sesssion otherwise previous session
							if($lockedMonth>=2) $sessionId = $currentSessionId;
						}
						if ($row->TARGET_LOCK_SESSION_ID==0){
							$startSessionID = $this->getSessionIDFromDate($row->PROJECT_START_DATE);
							if($startSessionID<=PMON_DEP_START_SESSION_ID){
								$sessionId=PMON_DEP_START_SESSION_ID;
								$targetEntrySessionId = PMON_DEP_START_SESSION_ID;
								$caption = str_replace('-20', '-', $this->getSession($sessionId));
							}else{
                            	//if($row->PROJECT_ID==7338) echo
								$caption = str_replace('-20', '-',$this->getSession($startSessionID));
								$sessionId = $startSessionID;
								$targetEntrySessionId = $startSessionID;
							}
							$showTargetButton = true;
						}else{
							if ($row->TARGET_LOCK_SESSION_ID==$currentSessionId){
								$caption = $currentSessionYear;
								$icon = 'cus-lock';
								//$targetEntrySessionId = $previousSessionId;
							}else if($row->TARGET_LOCK_SESSION_ID==$previousSessionId){
								$targetEntrySessionId = $previousSessionId;
								if($lockedMonth>=2){
									$caption = str_replace('-20', '-', $currentSessionYear);
									$showTargetButton = true;
								}else{
									$caption = $previousSessionYear;
									$icon = 'cus-lock';
								}
							}
						}
					}
				}
				if($showTargetButton) {
                    if ($row->IS_MI == 0){
                        array_push(
                            $fieldValues,
                            '"' . addslashes(
                                getButton(
                                    $caption,
                                    'showTargetForm(' . $row->PROJECT_ID . ', ' . $targetEntrySessionId . ')',
                                    4,
                                    $icon
                                )
                            ) . '"'
                        );
                    }elseif ($row->IS_MI == 1){
                        array_push(
                            $fieldValues,
                            '"' . addslashes(
                                getButton(
                                    $caption,
                                    'showTargetFormMi(' . $row->PROJECT_SETUP_ID . ', ' . $targetEntrySessionId . ')',
                                    4,
                                    $icon
                                )
                            ) . '"'
                        );
                    }
				}else{
					/*array_push($fieldValues, '"'.addslashes($row->SESSION_START_YEAR.'-'.$endYear.'<br/>'.
					date("d-m-Y", $lockedMonthValue).'<br/>'.date("d-m-Y", $endOfSessionDate)).'"');*/
					array_push($fieldValues, '"'.addslashes($row->SESSION_START_YEAR.'-'.$row->SESSION_END_YEAR).'"');
					array_push($fieldValues, '"'.addslashes('<span class="cus-lock"></span>').'"');
				}
				//echo $sessionId;
                if($row->IS_MI==0) {
                    $x = $this->readyToLock($row->PROJECT_ID, $targetEntrySessionId);
                }elseif ($row->IS_MI == 1){
                    $x = $this->readyToLockMi($row->PROJECT_SETUP_ID, $targetEntrySessionId);
                }
				//echo '::'.$x.'::';
				if($x){
                    if($row->IS_MI==0) {
                        array_push(
                            $fieldValues,
                            '"'.addslashes(
                                getButton(
                                    'Lock',
                                    'lockProject('.$row->PROJECT_ID.', '.$targetEntrySessionId.')',
                                    4,
                                    'cus-lock'
                                )
                            ).'"'
                        );
                    }elseif ($row->IS_MI == 1){
                        array_push(
                            $fieldValues,
                            '"'.addslashes(
                                getButton(
                                    'Lock',
                                    'lockProjectMi('.$row->PROJECT_SETUP_ID.', '.$targetEntrySessionId.')',
                                    4,
                                    'cus-lock'
                                )
                            ).'"'
                        );
                    }
				}
				array_push($objFilter->ROWS, '{"id":"'.$row->PROJECT_ID.'", "cell":['. implode(',', $fieldValues).']}');
			}
		}
		echo $objFilter->getJSONCodeByRow();
		//echo $objFilter->PREPARED_SQL;
	}
	//CALLED BY getProjectGrid
	private function getSession($session_id=0){
		$recs = $this->db->get_where('__sessions', array('SESSION_ID'=>$session_id));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_START_YEAR.'-'.$rec->SESSION_END_YEAR;
		}
		return '';
	}
	private function getFinancialYear($month, $year=0){
		$s = '';
		$one_month = $month;
		$one_year = $year;
		if($year==0){
			$year =  date('Y');
		}
		$month = $month-3;
		$session = $year."-".($year+1);
		if($month<=0){
			$month = $month+12;
			$session = ($year-1)."-".($year);
		}
		$session_ar= explode("-",$session);
		$this->db->select('SESSION_ID');
		$recs = $this->db->get_where(
			'__sessions', 
			array(
				'SESSION_START_YEAR'=>$session_ar['0'],
				'SESSION_END_YEAR'=>$session_ar['1']
			)
		);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_ID;
		}
		return 0;
	}
	private function getSessionIDFromDate($date){
	   $strSQL = "SELECT SESSION_ID FROM __sessions 
				WHERE START_DATE<='".$date."' AND 
					END_DATE >='".$date."'";
		$recs = $this->db->query($strSQL);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_ID;
		}
		return 0;
	}
	//
	public function showTargetForm(){
        $this->PROJECT_ID = $this->input->post('PROJECT_ID');
        $sessionId = $this->input->post('session_id');
        if($sessionId==0){
            $MONTH = date('n');
            $YEAR = date('Y');
            $sessionId = PMON_DEP_START_SESSION_ID;
        }
		$data = array(
			'PROJECT_ID'=> $this->PROJECT_ID,
			'BUDGET_AMOUNT' => '',
			'SUBMISSION_DATE' => '',
			'session_id' => $sessionId,
			'session_year' => $this->getSession($sessionId)
		);
		//$sessionId = PMON_DEP_START_SESSION_ID;
		$currentSessionId = $this->session->userdata('CURRENT_SESSION_ID');
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		//Lock Record
		$targetLocked = FALSE;
		$recs = $this->db->get_where('dep_pmon__t_locks', $arrWhere);
		if($recs && $recs->num_rows()){
			$row = $recs->row();
			if($row->TARGET_LOCK_SESSION_ID==0) $targetLocked = FALSE;
			if($row->TARGET_LOCK_SESSION_ID==$sessionId){
				$targetLocked = TRUE;//locked
			}
		}
		//
		$this->db->select('PROJECT_NAME, PROJECT_CODE');
		$recsProject = $this->db->get_where('deposit__projects', $arrWhere);
		if($recsProject  && $recsProject->num_rows()){
			$recProject = $recsProject->row();
			$data['PROJECT_NAME'] = $recProject->PROJECT_NAME;
			$data['PROJECT_CODE'] = $recProject->PROJECT_CODE;
		}
		// Get AA AMOUNT to Compare with Target should no excessed 
		//if RAA exists then compare RAA Amount with Target
		$recsAAAmount = $this->db->get_where('dep_pmon__m_project_setup', $arrWhere);
		$extCompletionDate = $this->getExtensionDate($this->PROJECT_ID);
		$projectCompletion = array('date'=>'', 'month'=>0, 'year'=>0, 'session'=>0);
		$projectStart = array('date'=>'', 'month'=>0, 'year'=>0, 'session'=>0);
		if($recsAAAmount && $recsAAAmount->num_rows()){
			$recAAAmount = $recsAAAmount->row();
			$data['AA_AMOUNT'] = $recAAAmount->AA_AMOUNT;
			$data['AA_RAA'] = 'AA';
			$projectStart = $this->getDateDetail($recAAAmount->PROJECT_START_DATE);
			if($extCompletionDate==''){
				$projectCompletion = $this->getDateDetail( $recAAAmount->PROJECT_COMPLETION_DATE);
			}else{
				$projectCompletion = $this->getDateDetail( $extCompletionDate );
			}

			$strSQL = "SELECT * FROM dep_pmon__t_raa_project 
				WHERE PROJECT_ID=$this->PROJECT_ID 
				ORDER BY RAA_PROJECT_ID DESC
				LIMIT 0, 1";
			$ress = $this->db->query($strSQL);
			if($ress && $ress->num_rows()){
				$rrec = $ress->row();
				$data['AA_AMOUNT'] = $rrec->RAA_AMOUNT;
				$data['AA_RAA'] = 'RAA';
			}
		}
		//
		$data['setupData'] = $this->getSetupData();
		//check start date is in current session then show after start month only
		//if project start in session
		$data['startRealStartMonth'] = 0;
		$data['startSession'] = $projectStart['session'];
		$data['endSession'] = $projectCompletion['session'];
		if($projectStart['session']==$sessionId){
			//assign project start month as start month
			$data['startMonth'] = $this->getFinancialMonth($projectStart['month']);
			$data['startRealStartMonth'] = $data['startMonth'];
		}else
			$data['startMonth'] = 1;
		$endMonth = 12;
		//if project completion in session
		if($projectCompletion['session']==$sessionId){
			//show target till completion month
			$data['endMonth'] = $this->getFinancialMonth($projectCompletion['month']);
		}else
			$data['endMonth'] = 12;
		//
		$targetData = array();
		$targetFields = $this->getYearlyTargetFields();
		$rec = array();
		//init
		for($i=0; $i<count($targetFields); $i++)
	        $rec[ $targetFields[$i] ] = '';
		//assign
        for($i=1; $i<=12; $i++)
			$targetData[$i] = (object) $rec;
        //get targets
		$targetRecs = $this->getYearlyTarget($sessionId);
		//showArrayValues($records);
		if($targetRecs){
			$i=0;
			foreach($targetRecs as $rec){
				if($i==0){
					$data['BUDGET_AMOUNT'] = $rec->BUDGET_AMOUNT;
					$data['SUBMISSION_DATE'] = $rec->SUBMISSION_DATE;
					$i++;
				}
				$targetData[$rec->FINANCIAL_MONTH] = $rec;
			}
		}
		$data['targetData'] = $targetData;
		$data['buttons'] = $this->createButtonSet($data['session_id']);
		$data['entrymode'] = 'target';
        $this->load->view('pmon_deposit/target_data_view', $data);
    }

    public function showTargetFormMi(){
        require('mi/Target_mi_c.php');
        $target_mi_c = new Target_mi_c();
        $target_mi_c->showTargetForm();
    }

	protected function getExtensionDate($projectId){
		$this->db->order_by('NEW_COMPLETION_DATE', 'DESC');
		$this->db->limit(1, 0);
		$recs = $this->db->get_where('dep_pmon__t_extensions', array('PROJECT_ID'=>$projectId));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->NEW_COMPLETION_DATE;
		}
		return '';
	}
	protected function getDates($projectId){
		$recs = $this->db->get_where('dep_pmon__m_project_setup', array('PROJECT_ID'=>$projectId));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return array('start'=>$rec->PROJECT_START_DATE, 'end'=>$rec->PROJECT_COMPLETION_DATE);
		}
		return array('start'=>'', 'end'=>'');
	}
	protected function getDateDetail($date){
		return array(
			'date'=>$date, 
			'month'=>date("n", strtotime($date)),
			'year'=>date("Y", strtotime($date)),
			'session'=> $this->getFinancialYear(
				date("n", strtotime($date)), 
				date("Y", strtotime($date))
			)
		);
	}
	protected function getFinancialMonth($month){
		return (($month>=1 and $month<=3) ? ($month+9) : ($month-3));
	}
	//
	private function createButtonSet($sessionId){
		$arrButtons = array();
		array_push(
			$arrButtons,
			getButton('Save', "saveTarget()", 4, 'cus-disk')
		);
		array_push(
			$arrButtons,
			getButton('Close', 'closeTargetForm();', 4, 'cus-cross')
		);
		return implode('&nbsp;', $arrButtons);
	}
	private function isValidForLock($sessionId){
		//if record found for that session
		$this->db->select('YEARLY_TARGET_ID');
		$recs = $this->db->get_where(
			'dep_pmon__t_yearlytargets', 
			array('PROJECT_ID'=>$this->PROJECT_ID, 'SESSION_ID'=>$sessionId)
		);
		return ($recs && $recs->num_rows());
	}
	//
	private function getSetupData(){
        $recs = $this->db->get_where(
			'dep_pmon__t_estimated_status', 
			array('PROJECT_ID'=>$this->PROJECT_ID)
		);
		$mFields = array(
			'LA_NA', 'FA_NA', 
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 'STEEL_WORKS_NA', 
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA', 'CANAL_LINING_NA',
			'IRRIGATION_POTENTIAL_NA'
		);
		$setupData = array();
		$isExists =false;
		if($recs && $recs->num_rows()){
			$isExists = true;
			$rec = $recs->row();
			for($i=0; $i<count($mFields); $i++)
				$setupData[ $mFields[$i] ] = $rec->{ $mFields[$i] };
		}
		if(!$isExists){
			for($i=0; $i<count($mFields); $i++)
				$setupData[ $mFields[$i] ] = '';
		}
		return $setupData;
	}
	private function getYearlyTargetFields(){
		return array(
			'YEARLY_TARGET_ID', 'SESSION_ID', 'TARGET_MONTH', 'PROJECT_CODE', 
			'BUDGET_AMOUNT', 'EXPENDITURE', 
			'LA_NO', 'LA_HA', 'FA_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES',
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 
			'IRRIGATION_POTENTIAL', 
			'SUBMISSION_DATE', 'LOCKED', 'TARGET_YEAR', 
			'LA_NO_CT', 'LA_HA_CT', 'FA_HA_CT', 
			'HEAD_WORKS_EARTHWORK_CT', 'HEAD_WORKS_MASONRY_CT', 'STEEL_WORKS_CT',
			'CANAL_EARTHWORK_CT', 'CANAL_LINING_CT', 'CANAL_STRUCTURES_CT', 
			'IRRIGATION_POTENTIAL_KHARIF_CT', 
			'IRRIGATION_POTENTIAL_RABI_CT', 
			'IRRIGATION_POTENTIAL_CT', 
			'EXPENDITURE_CT'
		);
	}
	private function getYearlyTarget($sessionId){
		$this->db->order_by('FINANCIAL_MONTH', 'ASC');
		$recs = $this->db->get_where(
			'dep_pmon__t_yearlytargets', 
			array(
				'PROJECT_ID'=>$this->PROJECT_ID, 
				'SESSION_ID'=>$sessionId
			)
		);
		return (($recs && $recs->num_rows())? $recs->result() : FALSE);
	}
	/*--------------------------------------------------------------------------------*/
    public function saveTarget(){
		$targetTable = 'dep_pmon__t_yearlytargets';
		$arrMonths = array("zero", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		
        $this->PROJECT_ID = $this->input->post('PROJECT_ID');
		$setupData = $this->getSetupData();

        $startSession = $this->input->post('startSession');
        $startMonth = $this->input->post('startMonth');
        $endSession = $this->input->post('endSession');
        $endMonth = $this->input->post('endMonth');
		$sessionId = $this->input->post('SESSION');
		
		$budgetAmount = $this->input->post('BUDGET_AMOUNT');
        //$SUBMISSION_DATE = $this->input->post('SUBMISSION_DATE');
		//get targets as array
        $arrExpenditure = $this->input->post('EXPENDITURE');
		$arrLANo = $this->input->post('LA_NO');
		$arrLAHa = $this->input->post('LA_HA');
		$arrFAHa = $this->input->post('FA_HA');
        $arrHWEarthwork = $this->input->post('HEAD_WORKS_EARTHWORK');
        $arrHWMasonry = $this->input->post('HEAD_WORKS_MASONRY');
        $arrSteelWorks = $this->input->post('STEEL_WORKS');
        $arrCanalEarthwork = $this->input->post('CANAL_EARTHWORK');
        $arrCanalLining = $this->input->post('CANAL_LINING');
        $arrCanalStructure = $this->input->post('CANAL_STRUCTURES');
        $arrIrrigationPotentialKharif = $this->input->post('IRRIGATION_POTENTIAL_KHARIF');
        $arrIrrigationPotentialRabi = $this->input->post('IRRIGATION_POTENTIAL_RABI');
        $arrIrrigationPotential = $this->input->post('IRRIGATION_POTENTIAL');
		$recs = $this->db->get_where(
			$targetTable, 
			array('SESSION_ID'=>$sessionId, 'PROJECT_ID'=>$this->PROJECT_ID)
		);
		$isExists = false;
		//Get existing and remove unwanted
		if($recs && $recs->num_rows()){
			$isExists = true;
			//clean up record
			if($sessionId==$startSession){
				$arrIDs = array();
				foreach($recs->result() as $rec){
					//if before start month
					if( $rec->FINANCIAL_MONTH<$startMonth){
						array_push($arrIDs, $rec->YEARLY_TARGET_ID); 
					}
				}
				if(count($arrIDs)){
					$this->db->where_in('YEARLY_TARGET_ID', $arrIDs);
					@$this->db->delete($targetTable);
				}
			}
			if($sessionId==$endSession){
				$arrIDs = array();
				foreach($recs->result() as $rec){
					//if after end month
					if($rec->FINANCIAL_MONTH>$endMonth){
						array_push($arrIDs, $rec->YEARLY_TARGET_ID); 
					}
				}
				if(count($arrIDs)){
					$this->db->where_in('YEARLY_TARGET_ID', $arrIDs);
					@$this->db->delete($targetTable);
				}
			}
		}
		//cummulative total
		$arrCT = array(
			'EXPENDITURE', 'LA_NO', 'LA_HA', 'FA_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 
			'IRRIGATION_POTENTIAL'
		);
		$arrCTValue = array();
		//init
		for($iCount=0; $iCount<count($arrCT);$iCount++)
			$arrCTValue[$arrCT[$iCount]] = 0;
		/**Transaction starts here*/
		$this->db->trans_start();
		//saving
		for($i=1;$i<=12;$i++){
			if ($i<$startMonth){
				//no need to save month b4 start month
				continue;
			}
			$tMonth = (($i>=10)? ($i-9) : ($i+3));
			$mYears = $this->getYearBySessionMonth($sessionId, $i);
			$tYears = (($i>=10) ? $mYears[1]:$mYears[0]);
			$arrWhere = array(
				'SESSION_ID'=>$sessionId,
				'PROJECT_ID'=>$this->PROJECT_ID,
				'FINANCIAL_MONTH'=>$i
			);
			$recs = $this->db->get_where($targetTable, $arrWhere);
			$isExists = ($recs && $recs->num_rows());
			$inputArray = array(
				'TARGET_MONTH'=> $tMonth,
				'BUDGET_AMOUNT'=>$budgetAmount, 
				'EXPENDITURE'=>$arrExpenditure[$i],
				'TARGET_YEAR'=>$tYears,
				'YEARLY_TARGET_DATE'=> $tYears.'-'. str_pad($tMonth, 2, '0', STR_PAD_LEFT).'-01',
				'LA_NO'=>0,
				'LA_HA'=>0,
				'FA_HA'=>0,
				'HEAD_WORKS_EARTHWORK'=>0,
				'HEAD_WORKS_MASONRY'=>0, 
				'STEEL_WORKS'=>0, 
				'CANAL_EARTHWORK'=>0,
				'CANAL_LINING'=>0,
				'CANAL_STRUCTURES'=>0,
				'IRRIGATION_POTENTIAL_KHARIF'=>0,
				'IRRIGATION_POTENTIAL_RABI'=>0,
				'IRRIGATION_POTENTIAL'=>0
			);
			if(!$setupData['LA_NA']){
				$inputArray['LA_NO'] = $arrLANo[$i];
				$inputArray['LA_HA'] = $arrLAHa[$i];
			}
			if(!$setupData['FA_NA'])
				$inputArray['FA_HA'] = $arrFAHa[$i];
			if(!$setupData['HEAD_WORKS_EARTHWORK_NA'])
				$inputArray['HEAD_WORKS_EARTHWORK']=$arrHWEarthwork[$i];
			if(!$setupData['HEAD_WORKS_MASONRY_NA'])
				$inputArray['HEAD_WORKS_MASONRY']=$arrHWMasonry[$i];
			if(!$setupData['STEEL_WORKS_NA'])
				$inputArray['STEEL_WORKS']=$arrSteelWorks[$i];
			if(!$setupData['CANAL_EARTHWORK_NA'])
				$inputArray['CANAL_EARTHWORK']=$arrCanalEarthwork[$i];
			if(!$setupData['CANAL_LINING_NA'])
				$inputArray['CANAL_LINING']=$arrCanalLining[$i];
			if(!$setupData['CANAL_STRUCTURES_NA'])
				$inputArray['CANAL_STRUCTURES']=$arrCanalStructure[$i];
			if(!$setupData['IRRIGATION_POTENTIAL_NA']){
				$inputArray['IRRIGATION_POTENTIAL_KHARIF']=$arrIrrigationPotentialKharif[$i];
				$inputArray['IRRIGATION_POTENTIAL_RABI']=$arrIrrigationPotentialRabi[$i];
				$inputArray['IRRIGATION_POTENTIAL']=$arrIrrigationPotential[$i];
			}
			//CUMMULATIVE TOTAL
			for($ic=0; $ic<count($arrCT);$ic++){
				$arrCTValue[ $arrCT[$ic] ] += $inputArray[ $arrCT[$ic] ];
				$inputArray[ $arrCT[$ic].'_CT' ] = $arrCTValue[ $arrCT[$ic] ] ;
			}
			if($isExists){
				@$this->db->update($targetTable, $inputArray, $arrWhere);
			}else{
				//insert
				$inputArray['SESSION_ID'] = $sessionId;
				$inputArray['PROJECT_ID'] = $this->PROJECT_ID;
				$inputArray['SUBMISSION_DATE'] = date("Y-m-d");
				$inputArray['FINANCIAL_MONTH'] = $i;
				@$this->db->insert($targetTable, $inputArray);
			}
			if($this->db->affected_rows()){
				array_push(
					$this->message, 
					getMyArray(true, 'Target Data for the Month \''.$arrMonths[$tMonth].'\' Updated...')
				);
			}else{
				array_push(
					$this->message, 
					getMyArray(false, 'No Updatable Data for the Month \''.$arrMonths[$tMonth].'\'...')
				);
			}
		}
		//correctMonthly Data if session = PMON_DEP_START_SESSION_ID 
		if($sessionId==PMON_DEP_START_SESSION_ID){
			
		}
		if ($this->db->trans_status()===FALSE){
	    	//generate an error... or use the log_message() function to log your error
			array_push($this->message, getMyArray(false, $this->db->log_message()));
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
		}
		echo createJSONResponse( $this->message );
	}

	public function saveTargetMi(){
        require('mi/Target_mi_c.php');
        $target_mi_c = new Target_mi_c();
        $target_mi_c->saveTarget();
    }

	private function getYearBySessionMonth($sessionID=0, $mMonth=0){
		$this->db->select('SESSION_START_YEAR, SESSION_END_YEAR');
		$recs = $this->db->get_where('__sessions', array('SESSION_ID'=>$sessionID));
		//echo $this->db->last_query();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return array($rec->SESSION_START_YEAR, $rec->SESSION_END_YEAR);
		}
		return array(0, 0);
	}
	private function isMonthlyExists($sessionID){
		$this->db->select('MONTHLY_DATA_ID, FINANCIAL_MONTH, SESSION_ID');
		$recs = $this->db->get_where(
			'dep_pmon__t_monthlydata', 
			array(
				'SESSION_ID'=>$sessionID, 
				'PROJECT_ID'=>$this->PROJECT_ID
			)
		);
		return (($recs && $recs->num_rows())? $recs->result() : false);
	}
	//
	private function getMFields(){
		return array('SESSION_ID', 'ENTRY_MONTH', 'PROJECT_ID', 'EXPENDITURE_TOTAL',
		'EXPENDITURE_WORKS', 'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 
		'FA_HA', 'FA_COMPLETED_HA', 'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
		'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 
		'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 
		'IRRIGATION_POTENTIAL', 
		'SUBMISSION_DATE', 'ENTRY_YEAR', 'LOCKED', 
		'COMPLETION_DATE', 'EXPENDITURE_TOTAL_T', 'EXPENDITURE_WORKS_T', 'LA_NO_T', 
		'LA_HA_T', 'LA_COMPLETED_NO_T', 'LA_COMPLETED_HA_T',
		'FA_COMPLETED_HA_T', 'FA_HA_T', 
		'HEAD_WORKS_EARTHWORK_T', 'HEAD_WORKS_MASONRY_T', 'STEEL_WORKS_T',
		'CANAL_EARTHWORK_T', 'CANAL_LINING_T', 'CANAL_STRUCTURES_T', 
		'IRRIGATION_POTENTIAL_T', 
		'ALLOCATED_BUDGET', 'MONTH_DATE', 'LA_CASES_STATUS', 'SPILLWAY_STATUS', 
		'FLANK_STATUS', 'SLUICES_STATUS', 'NALLA_CLOSURE_STATUS', 'CANAL_EARTH_WORK_STATUS', 
		'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS', 'PROJECT_STATUS', 'FINANCIAL_MONTH'
		);
	}
	//
	public function getPermissions(){
		$arrTargetKeys = array('', 'DEP_PMON_PROJECT_TARGET', 'PMON_MEDIUM_PROJECT_TARGET', 'PMON_MAJOR_PROJECT_TARGET');
		//$arrTargetKeys = array('', 'PMON_MINOR_PROJECT_MONTHLY', 'PMON_MEDIUM_PROJECT_MONTHLY', 'PMON_MAJOR_PROJECT_MONTHLY');
		$key = $arrTargetKeys[ $this->session->userData('TARGET_PROJECT_TYPE_ID') ];
		return getAccessPermissions($key, $this->session->userData('USER_ID'));
	}
	//
	public function lockProject(){
		//exit;	
		/*if(!IS_LOCAL_SERVER){
			$this->load->library('mycurl');
			$serverStatus = $this->mycurl->getServerStatus();
			if($serverStatus==0){
				echo 'Unable to lock. E-work Server Not responding. Try after sometime...';
				return;
			}
		}*/
		$projectId = (int) $this->input->post('project_id');
		$sessionId = (int) $this->input->post('session_id');

		/*SEND DATA TO EWORKS on 20-04-2020 by Anand*/
		$this->load->library('mycurl');
		$this->db->select('PROJECT_ID');
		$recs = $this->db->get_where('dep_pmon__t_monthlydata', array('PROJECT_ID'=>$projectId));
		$eworkData = $this->getEWorksDetailsDeposit($projectId);
		//showArrayValues($eworkData);//exit;
		// $monthlyExists = (($recs && $recs->num_rows())?true:false);
		$result_arr= array();
		$monthlyExists = $recs->num_rows();
		if($monthlyExists==0){
			//exit;
			//echo "in if";
			$eworkData = $this->getEWorksDetailsDeposit($projectId);
			// showArrayValues($eworkData); //exit;
			if(!IS_LOCAL_SERVER){
				//showArrayValues($eworkData); 
	          	$result = $this->mycurl->savePmonDepositData($eworkData);
            	$result_arr =json_decode($result, true);
            	if( isset($result_arr['success'] ) ){
                	
                }else{
                	echo '<span class="cus-lock"></span> Unable to Lock Target'; exit; 
                }
			}
		}
		/* SEND DATA TO EWORKS CODE END HERE*/

		$arrWhere = array('PROJECT_ID'=>$projectId);
		$data = array(
			'TARGET_LOCK'=>1,
			'TARGET_LOCK_SESSION_ID'=>$sessionId
		);
		$recs = $this->db->get_where('dep_pmon__t_locks', $arrWhere);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			if($sessionId>$rec->TARGET_EXISTS)
				$data['TARGET_EXISTS'] = $sessionId;
		}
		//showArrayValues($data);
		@$this->db->update('dep_pmon__t_locks', $data, $arrWhere);
		//$this->db->last_query();
		$lockResult = $this->db->affected_rows();
		if( $this->db->affected_rows() ){
			$pData = $this->getEWorksDetails($projectId);
			$recsMonthly = $this->db->get_where('dep_pmon__t_monthlydata',$arrWhere);
			//if monthly exists then send 0 otherwise 18 means monthly needed
			$params = array(
				'mode'=>'target',
				"projectCode"=>$projectId,
				"PLock" => (($recsMonthly && $recsMonthly->num_rows()) ? 0:18)
			);
			/*	if(!IS_LOCAL_SERVER){
				$result = $this->mycurl->savePromonData($params);
				//echo $result;
				$obj = json_decode($result);
				if($obj->{'success'}){
					echo "<br />Target Status Sent to E-Works Server.<br />";
				}
			}*/
			$this->updateLockedStatus($params);
			//record log for lock
			$data = array(
				'PROJECT_ID'=>$projectId, 
				'LOCK_DATE_TIME'=>date("Y-m-d H:i:s"), 
				'LOCK_MODE'=>3, //target
				'LOCK_TYPE'=>1, //lock
				'USER_ID'=>$this->session->userData('USER_ID'), 
				'SESSION_ID'=>(($sessionId==0)? PMON_DEP_START_SESSION_ID : $sessionId), //lock
				'DESCRIPTION'=>'Project Target Locked'
			);
			$lockTable = 'dep_pmon__t_lock_logs';
			@$this->db->insert($lockTable, $data);
			//return true;
		}
		//if in 2013-14
		/*if($sessionId==PMON_DEP_START_SESSION_ID)
			$this->populateMonthlyData($projectId, $sessionId);*/
    	$workCode ='';
    	
    	if( isset($result_arr['success'] ) ){
        	$workCode = $result_arr['success'] ;
        }
		
    echo (($lockResult)? '<span class="cus-lock"></span> Target Locked '.$workCode :
			 '<span class="cus-lock"></span> Unable to Lock');
	}

	public function lockProjectMi(){
        require('mi/Target_mi_c.php');
        $target_mi_c = new Target_mi_c();
        $target_mi_c->lockProject();
    }

	private function getEWorksDetails($projectId){
		$this->db->select('PROJECT_ID, PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, EWORK_ID');
		$this->db->where('PROJECT_ID', $projectId);
		$recs = $this->db->get('dep_pmon__v_projectlist_with_lock');
		$data = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$data = array(
					'PROJECT_ID'=>$rec->PROJECT_ID,
					'EWORK_ID'=>$rec->EWORK_ID,
					'PROJECT_NAME'=>$rec->PROJECT_NAME,
					'PROJECT_NAME_HINDI'=>$rec->PROJECT_NAME_HINDI,
					'PROJECT_CODE'=>$rec->PROJECT_CODE
				);
			}
		}
		return $data;
	}
	private function getSetupStatus(){
		$mFields = array(
			'LA_CASES_STATUS', 'SPILLWAY_STATUS', 'FLANK_STATUS', 'SLUICES_STATUS', 
			'NALLA_CLOSURE_STATUS', 'CANAL_EARTH_WORK_STATUS', 
			'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS'
		);
		//get setup status
		$recs = $this->db->get_where(
			'dep_pmon__m_setup_status', 
			array('PROJECT_ID'=>$this->PROJECT_ID)
		);
		$data = array();
		for($i=0; $i<count($mFields); $i++){
			$data[$mFields[$i]] = '';
		}
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mFields); $i++){
				$data[$mFields[$i]] = $rec->{$mFields[$i]};
			}
		}
		return $data;
	}
	private function readyToLock($projectId, $sessionId){
		//check permission
		$permissions = $this->getPermissions();
		//showArrayValues($permissions);
		//echo 'PROJECT_ID:'.$projectId.'ss:'.$sessionId.' = '.$permissions['SAVE_LOCK']."\n";
		if($permissions['SAVE_LOCK']==1){
			//if record found for that session
			$this->db->select('YEARLY_TARGET_ID');
			$recs = $this->db->get_where(
				'dep_pmon__t_yearlytargets',
				array('PROJECT_ID'=>$projectId, 'SESSION_ID'=>$sessionId)
			);
			if($recs && $recs->num_rows())
				return true;
		}
		return false;
	}
    private function readyToLockMi($projectId, $sessionId){
        $permissions = $this->getPermissions();
        if ($permissions['SAVE_LOCK'] == 1) {
            //if record found for that session
            $this->db->select('ID');
            $recs = $this->db->get_where(
                'dep_mi__t_yearlytargets',
                array('PROJECT_SETUP_ID' => $projectId, 'SESSION_ID' => $sessionId)
            );
            if ($recs && $recs->num_rows()){
                $recs->free_result();
                return true;
            }
        }
        return false;
    }
	private function populateMonthlyData($projectId, $sessionId){
		$this->PROJECT_ID = $projectId;
		//get project start date & its session id
		$dates = $this->getDates($projectId);
		$projectStart = $this->getDateDetail($dates['start']);
		$projectCompletion = $this->getDateDetail( $dates['end']);
		//start month april
		$startMonth = 4;
		//if startdate in same session then 
		if($sessionId==PMON_DEP_START_SESSION_ID){
			if($projectStart['session']<=PMON_DEP_START_SESSION_ID){
				$startMonth = $projectStart['month'];
			}
		}else{
			//if start session<PMON_DEP_START_SESSION_ID
			//if($prjData['session']<PMON_DEP_START_SESSION_ID){
			//}
		}
		$data = array();
		$achData = $this->getAchieveDataOfSession7();

		foreach($achData as $k=>$v){
			$data[$k] = $v;
		}
		$statData = $this->getSetupStatus();
		foreach($statData as $k=>$v){
			$data[$k] = $v;
		}

		$endMonth = 12;
		$monthlyTable = 'dep_pmon__t_monthlydata';

		//$monthDate = '2013-'.str_pad($startMonth, 2, '0', STR_PAD_LEFT).'-01';
		//delete record for <monthdate
		//$strSQL = "DELETE FROM ".$monthlyTable." WHERE MONTH_DATE<'".$monthDate."'";
		//$this->db->query($strSQL);
		//create monthly records for start month to end month
		for($i=$startMonth;$i<=$endMonth;$i++){
			$monthDate = '2013-'.str_pad($i, 2, '0', STR_PAD_LEFT).'-01';
			$monthData = $data;
			$monthData['MONTH_DATE'] = $monthDate;
			$monthData['ENTRY_YEAR'] = 2013;
			$monthData['ENTRY_MONTH'] = $i;
			$monthData['FINANCIAL_MONTH'] = $this->getFinancialMonth($i);
			$monthData['SESSION_ID'] = PMON_DEP_START_SESSION_ID;
			//check existance of record
			$arrWhich = array(
				'PROJECT_ID'=>$this->PROJECT_ID,
				'MONTH_DATE'=>$monthDate
			);
			$mRecs = $this->db->get_where($monthlyTable, $arrWhich);
			$isExists = (($mRecs && $mRecs->num_rows()) ? true : false);
			if($isExists){
				//update
				@$this->db->update($monthlyTable, $monthData,  $arrWhich);
			}else{
				//insert
				@$this->db->insert($monthlyTable, $monthData);
			}
		}
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		$data = array(
			'PROJECT_ID'=>$this->PROJECT_ID,
			'MONTHLY_EXISTS'=>'2013-12-01', 
			'MONTH_LOCK' =>	'2013-12-01'
		);
		@$this->db->update('dep_pmon__t_locks', $data, $arrWhere);
	}
	private function getAchieveDataOfSession7(){
		$mAFT = array(
			'EXPENDITURE_TOTAL_T', 'EXPENDITURE_WORKS_T', 'LA_NO_T', 
			'LA_HA_T', 'LA_COMPLETED_NO_T', 'LA_COMPLETED_HA_T', 
			'FA_COMPLETED_HA_T', 'FA_HA_T', 'HEAD_WORKS_EARTHWORK_T', 'STEEL_WORKS_T', 
			'HEAD_WORKS_MASONRY_T', 'CANAL_EARTHWORK_T', 'CANAL_LINING_T', 
			'CANAL_STRUCTURES_T', 'IRRIGATION_POTENTIAL_T'
		);
		$mAF1 = array(
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORKS', 'LA_NO', 'LA_HA', 
			'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 'FA_COMPLETED_HA', 'FA_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES', 
			'IRRIGATION_POTENTIAL'
		);
		$mA1 = array();
		$mA2 = array();
		for($i=0; $i<count($mAF1); $i++){
			$mA1[$mAF1[$i].'_T'] = 0;
		}
		//get achievement data for last year 2012-13
		$recs = $this->db->get_where(
			'dep_pmon__t_achievements', 
			array('SESSION_ID'=>7, 'PROJECT_ID'=>$this->PROJECT_ID)
		);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mAF1); $i++){
				$mA1[$mAF1[$i].'_T'] = $rec->{$mAF1[$i]};
			}
		}
		return $mA1;
	}
	protected function updateLockedStatus($params){
		$arrWhere = array('projectCode'=>$params['projectCode']);
		$recs = $this->db->get_where('dep_pmon__t_locked_status', $arrWhere);
		if($recs && $recs->num_rows()){
			$data = array("PLock"=>$params['PLock']);
			@$this->db->update('dep_pmon__t_locked_status', $data, $arrWhere);
		}
	}
	private function getEWorksDetailsDeposit($projectId){
		$this->db->select('*');
		$this->db->where('PROJECT_ID', $projectId);
		$recs = $this->db->get('dep_pmon__v_projectlist_with_lock');
		$data = array();
		$AUTHORITY_NAME = '';
		$depositSchemeArr= array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
					$AUTHORITY_NAME = '';
					$AUTHORITY_NAME = $this->getAuthorityName($rec->AA_AUTHORITY_ID);
					$depositSchemeArr= array();
					$depositSchemeArr= $this->getDepositScheme($rec->DEPOSIT_SCHEME_ID);
					//showArrayValues($depositSchemeArr); exit;
					$data = array(
					'mode'=>'NonBudgetedWorks',
					'ddocode'=>$rec->EWORK_ID,
					'work_cat'=>'Minor',
					'work_type'=>'Deposit',
					'name_of_work_Eng'=>$rec->PROJECT_NAME,
					'name_of_work_hindi'=>$rec->PROJECT_NAME_HINDI,
					'amount_of_aa'=>$rec->AA_AMOUNT,
					'date_of_aa'=>$rec->AA_DATE,
					'aa_issued_by'=>$AUTHORITY_NAME,
					'amount_of_ts'=>$rec->AA_AMOUNT,
					'reference_of_aa'=>$rec->AA_NO,
					'ts_by'=>$AUTHORITY_NAME,
					'reference_of_ts'=>$rec->AA_NO,
					'date_of_ts'=>$rec->AA_DATE,
					'allocation'=>$depositSchemeArr['HEAD'],
					'deposited_by'=>$AUTHORITY_NAME,
					'name_of_scheme'=>$depositSchemeArr['SCHEME_NAME_HINDI'],
					'entry_date'=>$rec->PROJECT_SAVE_DATE,
					//'SchemeCode'=>'8443',
					'AllocationID'=>$depositSchemeArr['OLD_ALLOCATION_ID'],
					'PROMON_ID'=>$rec->PROJECT_CODE,
					'PROJECT_CODE'=>$rec->PROJECT_ID
				);
			}
		}
		//showArrayValues($data);
		return $data;
	}
	public function getAuthorityName($AuthID=0){
        if($AuthID==0) return '';
        $recs = $this->db->select('AUTHORITY_NAME')
                    ->from('pmon__m_authority')
                    ->where(array('AUTHORITY_ID'=>$AuthID))
                    ->get();
        //$dd_auth = array();
        if($recs && $recs->num_rows()){
            $rec = $recs->row();
            $recs->free_result();
            return $rec->AUTHORITY_NAME;
        }
        return '';
    }

    public function getDepositScheme($id=0)
    {
         $recs =$this->db->select('*')
                ->from(AGREEMENT_DB.".dep__m_scheme")
                ->where(array('ID'=>$id))
                ->get();
         //return $recs->result_array();
         $arr = array();
         if($recs && $recs->num_rows()){
            $rec = $recs->row();
            $arr['ID']= $rec->ID;
            $arr['HEAD']= $rec->HEAD;
            $arr['SCHEME_NAME_HINDI']= $rec->SCHEME_NAME_HINDI;
            $arr['SCHEME_NAME_ENGLISH']= $rec->SCHEME_NAME_ENGLISH;
            $arr['SCHEME_ABBREVIATION']= $rec->SCHEME_ABBREVIATION;
            $arr['ALLOCATION_ID']= $rec->ALLOCATION_ID;
            $arr['OLD_ALLOCATION_ID']= $rec->OLD_ALLOCATION_ID;            
            $recs->free_result();
            return $arr;
        }
    }
}?>