<?php
class Target_c extends MX_Controller{
    private $PROJECT_ID, $message, $PROJECT_SETUP_ID;
	function __construct(){
        parent::__construct();
        $this->message = array();
        $this->PROJECT_ID = 0;
        $this->PROJECT_SETUP_ID = 0;
        $this->load->model('mi/mi__m_target');
    }

	public function index(){
        $data = array();
        $data['message'] = '';
        $data['page_heading'] = pageHeading(
            'PROMON - Micro Irrigation Project - Financial and Physical Target Setup'
        );
        $this->load->library('office_filter');
        $data['office_list'] = $this->office_filter->office_list();
        $data['project_target_grid'] = $this->createGrid();
        $this->load->view('mi/target_index_view', $data);
    }

	public function showOfficeFilterBox(){
        //$data['instance_name'] = 'search_office';
        $data = array();
        $data['prefix'] = 'search_office';
        $data['show_sdo'] = FALSE;
        $data['row'] = '<tr>
		<td class="ui-widget-content"><strong>Project Name</strong></td>
		<td class="ui-widget-content">
			<input type="text" value="" name="SEARCH_PROJECT_NAME" id="SEARCH_PROJECT_NAME">
		</td>
		</tr>
		<tr><td colspan="2" class="ui-widget-content">' . getButton(array('caption'=>'Search', 'event'=>'refreshSearch()', 'icon'=>'cus-zoom', 'title'=>'Search')) . '</td></tr>';
        $this->load->view('setup/office_filter_view', $data);
    }

	private function createGrid(){
		$permissions = $this->mi__m_target->getPermissions();
		$buttons = array();
		$mfunctions = array();
		array_push($mfunctions, "loadComplete: function(){afterReload();}");
		$aData = array(
			'set_columns' => array(
				array(
					'label' => 'Work',
					'name' => 'WORK_NAME',
					'width' => 80,
					'align' => "left",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true,
					'formatter' => '',
					'searchoptions' => ''
				),
				array(
					'label' => 'Project',
					'name' => 'PROJECT_NAME',
					'width' => 70,
					'align' => "left",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true,
					'formatter' => '',
					'searchoptions' => ''
				),
				array(
					'label' => 'Project Code',
					'name' => 'PROJECT_CODE',
					'width' => 40,
					'align' => "left",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true,
					'formatter' => '',
					'searchoptions' => ''
				),
				array(
					'label' => addslashes('<span class="cus-lock"></span>Session'),
					'name' => 'TARGET_LOCK_SESSION_ID',
					'width' => 30,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true
				),
				array(
					'label' => addslashes('<span class="cus-lock"></span>Month'),
					'name' => 'MONTH_LOCK',
					'width' => 30,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true,
					'formatter' => 'date',
					'newformat' => 'M, Y',
					'searchoptions' => ''
				),
				array(
					'label' => 'Action',
					'name' => 'ADD',
					'width' => 40,
					'align' => "center",
					'resizable' => false,
					'sortable' => false,
					'hidden' => false,
					'view' => true,
					'search' => false,
					'formatter' => '',
					'searchoptions' => ''
				),
				array(
					'label' => '',
					'name' => 'lock',
					'width' => 35,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => false,
					'formatter' => '',
					'searchoptions' => ''
				),
				array(
					'label' => 'id',
					'name' => 'PROJECT_ID',
					'width' => 20,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => false,
					'formatter' => '',
					'searchoptions' => ''
				)
			),
			'custom' => array("button" => $buttons, "function" => $mfunctions),
			'div_name' => 'projectListGrid',
			'source' => 'getProjectGrid',
			'postData' => '{}',
			'rowNum' => 10,
			'width' => DEFAULT_GRID_WIDTH,
			'height' => '',
			'altRows' => true,
			'rownumbers' => true,
			'autowidth'=>true,
			'sort_name' => 'WORK_NAME',
			'sort_order' => 'asc',
			'primary_key' => 'WORK_NAME',
			'add_url' => '',
			'edit_url' => '',
			'delete_url' => '',
			'caption' => addslashes('<span class="cus-target"></span>Projects for Target Entry'),
			'pager' => true,
			'showTotalRecords' => true,
			'toppager' => false,
			'bottompager' => true,
			'multiselect' => false,
			'toolbar' => true,
			'toolbarposition' => 'top',
			'hiddengrid' => false,
			'editable' => false,
			'forceFit' => true,
			'gridview' => true,
			'footerrow' => false,
			'userDataOnFooter' => true,
			'treeGrid' => false,
			'custom_button_position' => 'bottom'
		);
		return buildGrid($aData);
	}
	public function getProjectGrid(){
        $objFilter = new clsFilterData();
        $objFilter->assignCommonPara($_POST);
        if ($this->input->post('SEARCH_PROJECT_NAME')) {
            array_push(
                $objFilter->SQL_PARAMETERS,
                array("WORK_NAME", 'LIKE', $this->input->post('SEARCH_PROJECT_NAME'))
            );
        }
        //$EEE = '';
        //$SDO_ID = $this->input->post('SDO_ID');
        $EE_ID = $this->input->post('EE_ID');
        $CE_ID = $this->input->post('CE_ID');
        $SE_ID = $this->input->post('SE_ID');
        if ($EE_ID == false && $CE_ID == false && $SE_ID == false) {//&& $SDO_ID == false
             $EE_ID = getSessionDataByKey('EE_ID');
            $SE_ID = getSessionDataByKey('SE_ID');
            $CE_ID = getSessionDataByKey('CE_ID');
        }
        if ($EE_ID == 0 && $SE_ID == 0 && $CE_ID == 0 ) {//&& $SDO_ID == 0
            //DO NOTHING .....
        } else {
			$arrOfficeWhere = array();
			if($EE_ID)									array_push($arrOfficeWhere,  ' OFFICE_EE_ID=' . $EE_ID);
			if($SE_ID && (!$EE_ID))						array_push($arrOfficeWhere,  ' OFFICE_SE_ID=' . $SE_ID);
			if($CE_ID && ( (!$SE_ID) && (!$EE_ID) ))	array_push($arrOfficeWhere,  ' OFFICE_CE_ID=' . $CE_ID);
			if($arrOfficeWhere)							array_push($objFilter->WHERE, implode(' AND ', $arrOfficeWhere));
        }
        $objFilter->SQL =
            'SELECT DISTINCT PROJECT_SETUP_ID, PROJECT_CODE, WORK_NAME, WORK_NAME_HINDI, PARENT_PROJECT_NAME,PARENT_PROJECT_NAME_HINDI, 
				SETUP_LOCK, TARGET_LOCK_SESSION_ID, AA_DATE as PROJECT_START_DATE, AA_DATE,
				SESSION_START_YEAR, SESSION_END_YEAR, MONTH_LOCK, SESSION_ID 
			FROM mi__v_projectlist_with_lock 
			WHERE SETUP_LOCK=1 ';
        //AND ((WORK_STATUS<5) OR ((WORK_STATUS>=5) AND (SE_COMPLETION=0)))
        $objFilter->executeMyQuery();
        //echo $objFilter->PREPARED_SQL; exit;
        $rows = array();
        $currentSessionId = $this->session->userdata('CURRENT_SESSION_ID');
        $previousSessionId = $currentSessionId - 1;
        $currentSessionYear = $this->mi__m_target->getSession($currentSessionId);
        $previousSessionYear = $this->mi__m_target->getSession($previousSessionId);
        if ($objFilter->TOTAL_RECORDS) {
            foreach ($objFilter->RESULT as $row) {
                $icon = 'cus-target';
                $fieldValues = array();
                array_push($fieldValues, '"' . addslashes($row->WORK_NAME) . '"');
                array_push($fieldValues, '"' . addslashes($row->PARENT_PROJECT_NAME) . '"');
                array_push($fieldValues, '"' . addslashes($row->PROJECT_CODE) . '"');
                $endYear = str_replace('20', '', $row->SESSION_END_YEAR);
                if ($row->TARGET_LOCK_SESSION_ID > 0) {
                    array_push($fieldValues, '"' . addslashes($row->SESSION_START_YEAR . '-' . $endYear) . '"');
                    array_push($fieldValues, '"' . addslashes($row->MONTH_LOCK) . '"');
                } else {
                    array_push($fieldValues, '"' . addslashes('<span class="cus-bullet-green"></span>') . '"');
                    array_push($fieldValues, '""');
                }
                //
                $showTargetButton = true;
                $lockedMonth = 0;
                if ($row->MONTH_LOCK == '0000-00-00' || $row->MONTH_LOCK == NULL) {
                    //echo "in if";
                    //echo $row->TARGET_LOCK_SESSION_ID.'::'.$row->PROJECT_START_DATE;
                    if ($row->TARGET_LOCK_SESSION_ID == 0) {
                        //$startSessionID = $this->getSessionIDFromDate($row->PROJECT_START_DATE);
                        $startSessionID = getSessionIdByDate($row->PROJECT_START_DATE);
                        $targetEntrySessionId = $startSessionID;
                        if ($startSessionID < $currentSessionId) {
                            $sessionId = $row->SESSION_ID;
                            $caption = str_replace('-20', '-', $this->mi__m_target->getSession($sessionId));
                            $targetEntrySessionId = $sessionId;
                        } else if ($startSessionID <= PMON_MI_START_SESSION_ID) {
                            $sessionId = PMON_MI_START_SESSION_ID;
                            $caption = str_replace('-20', '-', $this->mi__m_target->getSession($sessionId));
                        } else {
                            $caption = str_replace('-20', '-', $this->mi__m_target->getSession($startSessionID));
                            $sessionId = $startSessionID;
                        }
                    } else {
                        if ($row->TARGET_LOCK_SESSION_ID == $currentSessionId) {
                            $caption = $currentSessionYear;
                            $sessionId = $currentSessionId;
                            $icon = 'cus-lock';
                            $showTargetButton = false;
                        } else if ($row->TARGET_LOCK_SESSION_ID == $previousSessionId) {
                            $sessionId = $previousSessionId;
                            if ($lockedMonth >= 2) {
                                $caption = str_replace('-20', '-', $currentSessionYear);
                            } else {
                                $caption = $previousSessionYear;
                                $icon = 'cus-lock';
                                $showTargetButton = false;
                            }
                        } else {
                            $caption = ($row->SESSION_START_YEAR + 1) . '-' . ($endYear + 1);
                            $sessionId = $row->TARGET_LOCK_SESSION_ID + 1;
                        }
                        $targetEntrySessionId = $sessionId;
                    }
                    $targetEntrySessionId = $sessionId;
                } else {
                    //echo "else";
                    //$icon = 'cus-wrench-orange';
                    $icon = 'cus-target';
                    $endOfSessionDate = strtotime($row->SESSION_END_YEAR . '03-01');
                    $lockedMonth = (int)date("Y-m-d", strtotime($row->MONTH_LOCK));
                    $lockedMonthValue = strtotime($row->MONTH_LOCK);
                    $sessionId = $row->TARGET_LOCK_SESSION_ID;
                    $lockMonthSessionId = getSessionIdByDate($row->MONTH_LOCK);
                    $readyToLock = false;
                    $showTargetButton = false;
                    $caption = '';
                    $targetEntrySessionId = 0;
                    if ($lockedMonthValue <= $endOfSessionDate) {
                        $lockedMonth = (int)date("n", $lockedMonthValue);
                        if ($lockedMonth == 3) {
                            if ($lockMonthSessionId < $sessionId) {
                                //do nothing
                            } else {
                                $showTargetButton = true;
                                $caption = str_replace('-20', '-', $this->mi__m_target->getSession($sessionId + 1));
                                $targetEntrySessionId = $sessionId + 1;
                            }
                        }

                    } else if ($row->TARGET_LOCK_SESSION_ID == $currentSessionId) {
                        array_push($fieldValues, '"' . addslashes($row->SESSION_START_YEAR . '-' . $endYear . '2') . '"');
                        array_push($fieldValues, '"' . addslashes('<span class="12cus-lock"></span>') . '"');
                    } else {
                        if ($row->TARGET_LOCK_SESSION_ID < $currentSessionId) {
                            //if feb locked then current sesssion otherwise previous session
                            if ($lockedMonth >= 2) $sessionId = $currentSessionId;
                        }
                        if ($row->TARGET_LOCK_SESSION_ID == 0) {
                            //$startSessionID = $this->getSessionIDFromDate($row->PROJECT_START_DATE);
                            $startSessionID = getSessionIdByDate($row->AA_DATE);
                            if ($startSessionID <= PMON_MI_START_SESSION_ID) {
                                $sessionId = PMON_MI_START_SESSION_ID;
                                $targetEntrySessionId = PMON_MI_START_SESSION_ID;
                                $caption = str_replace('-20', '-', $this->mi__m_target->getSession($sessionId));
                            } else {
                                $caption = str_replace('-20', '-', $this->mi__m_target->getSession($startSessionID));
                                $sessionId = $startSessionID;
                                $targetEntrySessionId = $startSessionID;
                            }
                            $showTargetButton = true;
                        } else {
                            if ($row->TARGET_LOCK_SESSION_ID == $currentSessionId) {
                                $caption = $currentSessionYear;
                                $icon = 'cus-lock';
                                //$targetEntrySessionId = $previousSessionId;
                            } else if ($row->TARGET_LOCK_SESSION_ID == $previousSessionId) {
                                $targetEntrySessionId = $previousSessionId;
                                if ($lockedMonth >= 2) {
                                    $caption = str_replace('-20', '-', $currentSessionYear);
                                    $showTargetButton = true;
                                } else {
                                    $caption = $previousSessionYear;
                                    $icon = 'cus-lock';
                                }
                            }
                        }
                    }
                }
				//TARGT BUTTON
                if($showTargetButton){
					array_push(
                        $fieldValues,
                        '"' . addslashes(
                            getButton(array('caption'=>$caption, 'event'=>'showTargetForm(' . $row->PROJECT_SETUP_ID . ', ' . $targetEntrySessionId . ')', 'icon'=>$icon, 'title'=>$caption))
                        ) . '"'
                    );
                }else{
                    /*array_push($fieldValues, '"'.addslashes($row->SESSION_START_YEAR.'-'.$endYear.'<br/>'.
                    date("d-m-Y", $lockedMonthValue).'<br/>'.date("d-m-Y", $endOfSessionDate)).'"');*/
                    array_push($fieldValues, '"' . addslashes($row->SESSION_START_YEAR . '-' . $endYear) . '"');
                    
                }
				//LOCK BUTTON
				$content = '';
				if($row->TARGET_LOCK_SESSION_ID==$currentSessionId){
					$content = '<span class="cus-lock"></span>';
				}else{
					//echo $sessionId;
					if(!isOperator()){
						$x = $this->mi__m_target->readyToLock($row->PROJECT_SETUP_ID, $targetEntrySessionId);
						//echo '::readyTolock= '.$x.'::'.$targetEntrySessionId;
						if($x){
							$content = getButton(
								'Lock',
								'lockProject(' . $row->PROJECT_SETUP_ID . ', ' . $targetEntrySessionId . ')',
								4, 'cus-lock'
							);
						}
					}
				}
				array_push($fieldValues, '"' . addslashes($content) . '"');

				array_push($fieldValues, '"' . addslashes($row->PROJECT_SETUP_ID) . '"');
                array_push($objFilter->ROWS, '{"id":"' . $row->PROJECT_SETUP_ID . '", "cell":[' . implode(',', $fieldValues) . ']}');
            }
        }
        echo $objFilter->getJSONCodeByRow();
        //echo $objFilter->PREPARED_SQL;
    }
	//ok
	public function showTargetForm(){
		$projectSetupId = $this->input->post('PROJECT_ID');
		$sessionId = $this->input->post('session_id');
		$data = $this->mi__m_target->getData($projectSetupId, $sessionId);
		$this->load->view('mi/target_data_view', $data);
    }
    //not using so commenting it for MI
    /*private function isValidForLock($sessionId){

        //if record found for that session
        $this->db->select('YEARLY_TARGET_ID');
        $recs = $this->db->get_where(
            'mi__t_yearlytargets',
            array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID, 'SESSION_ID' => $sessionId)
        );
        return ($recs && $recs->num_rows());
    }*/
	//OK
	public function saveTarget(){
		// Data for mi__ip_target_block
		$arrTargetData = array(
			'PROJECT_SETUP_ID' => $this->input->post('PROJECT_SETUP_ID'),
			'arrKharif'=>$this->input->post('KHARIF'),
			'arrRabi' => $this->input->post('RABI'),
			'arrTargetDates' => $this->input->post('TARGET_DATE'),
			'startMonth'=> $this->input->post('startMonth'), 
			'SESSION_ID' => $this->input->post('SESSION'),
			'startSession' => $this->input->post('startSession'),
			'endSession' => $this->input->post('endSession'),
			'endMonth' => $this->input->post('endMonth'),
			'arrLANo' => $this->input->post('LA_NO'),
			'arrLAHa' => $this->input->post('LA_HA'),
			'arrFAHa' => $this->input->post('FA_HA'),
			'arrLEarthwork' => $this->input->post('L_EARTHWORK'),
			/*'arrCEarthwork' => $this->input->post('C_EARTHWORK'),*/
			'arrCMasonry' => $this->input->post('C_MASONRY'),
			'arrCPipeWork' => $this->input->post('C_PIPEWORK'),
			'arrCDripPipe' => $this->input->post('C_DRIP_PIPE'),
			'arrCWaterPump' => $this->input->post('C_WATERPUMP'),
			'arrControlRooms' => $this->input->post('K_CONTROL_ROOMS'),
            'BUDGET_AMOUNT'=>$this->input->post('BUDGET_AMOUNT'),
            'arrExpenditure'=>$this->input->post('EXPENDITURE')
		);
		//showArrayValues($arrTargetData);
		$result = $this->mi__m_target->saveTargetData($arrTargetData);
		echo $result ;
	}
	public function lockProject(){
        if (!IS_LOCAL_SERVER) {
            $this->load->library('mycurl');
            $serverStatus = $this->mycurl->getServerStatus();
            if ($serverStatus == 0) {
                echo 'Unable to lock. E-work Server Not responding. Try after sometime...';
                return;
            }
        }
        $projectSetupId = (int)$this->input->post('project_id');
        $sessionId = (int)$this->input->post('session_id');
		$arrParams = $this->mi__m_target->prepareDataToSend($projectSetupId, $sessionId);
		showArrayValues($arrParams);
		$goAhead = FALSE;
		if($arrParams){
            if (!IS_LOCAL_SERVER) {
                $result = $this->mycurl->savePromonData($params);
                //echo $result;
                $obj = json_decode($result);
                if ($obj->{'success'}) {
					$goAhead = TRUE;
                }
            }else
				$goAhead = TRUE;
			if($goAhead){
				echo "<br />Target Status Sent to E-Works Server.<br />";	
				$this->mi__m_target->afterLockStatus($projectSetupId, $arrParams);			
			}
		}
        //if in 2013-14
        //if ($sessionId == PMON_MI_START_SESSION_ID)
          //  $this->populateMonthlyData($projectId, $sessionId);
        echo(($goAhead) ? '<span class="cus-lock"></span> Target Locked':'<span class="cus-lock"></span> Unable to Lock');
    }
}
