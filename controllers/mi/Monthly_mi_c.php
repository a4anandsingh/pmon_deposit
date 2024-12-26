<?php date_default_timezone_set('Asia/Kolkata');
//error_reporting(E_ALL);
class Monthly_mi_c extends MX_Controller{
    var $PROJECT_ID, $data, $message, $PROJECT_SETUP_ID;
	//ok
    function __construct(){
        parent::__construct();
        $this->RESULT = false;
        $this->message = array();
        $this->PROJECT_ID = 0;
        $this->PROJECT_SETUP_ID = 0;
        $this->data = array();
        //$this->load->model('mi/mi__t_monthly');
        $this->load->model('pmon_dep/dep_mi__t_monthly', 'mi__t_monthly');
        date_default_timezone_set('Asia/Kolkata');
				if(getSessionDataByKey('CURRENT_OFFICE_ID')==0){
						echo '<h1 style="text-align:center;color:#f00">Please Contact SE MIS For Monthly Entry.</h1>';
					exit;
				}
    }
	//ok
    public function index(){
        $data = array();
        $data['message'] = '';
        $data['page_heading'] = pageHeading('PROMON - Micro Irrigation Project - Monthly Data Entry');
        $this->load->library('office_filter');
        $data['office_list'] = $this->office_filter->office_list();
        $data['isValid'] = 0;
        if (getSessionDataByKey('HOLDING_PERSON') == 4) {
			$data['isValid'] = (IS_LOCAL_SERVER) ? 0: $this->mi__t_monthly->isSetupTargetNotLocked();
        }
        //echo 'isValid ='. $data['isValid'];exit;
        if (!$data['isValid']) {
            $data['project_monthly_grid'] = $this->createGrid();
        }
        $this->load->view('mi/monthly_index_view', $data);
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
		<tr><td colspan="2" class="ui-widget-content">' . getButton('Search', 'refreshSearch()', 4, 'cus-zoom') . '</td></tr>';
        $this->load->view('setup/office_filter_view', $data);
    }
	//
    private function createGrid(){
        $permissions = $this->mi__t_monthly->getPermissions();
        $buttons = array();
        $mfunctions = array();
        array_push($mfunctions, "loadComplete: function(){afterReload();}");
        //array_push($mfunctions , "onSelectRow: function(ids){getProjectSubType(ids);}");
        $aData = array(
            'set_columns' => array(
                array(
                    'label' => 'Work Name',
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
                    'label' => 'Parent Project',
                    'name' => 'PARENT_PROJECT',
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
                    'align' => "center",
                    'resizable' => false,
                    'sortable' => true,
                    'hidden' => false,
                    'view' => true,
                    'search' => true,
                    'formatter' => '',
                    'searchoptions' => ''
                ),
                array(
                    'label' => 'Exists',
                    'name' => 'MONTHLY_EXISTS',
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
                    'label' => 'Data Entry',
                    'name' => 'ADD',
                    'width' => 50,
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
                    'label' => 'Action',
                    'name' => 'lock',
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
                    'label' => 'Progress',
                    'name' => 'PROGRESS',
                    'width' => 25,
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
                    'label' => 'Setup Id',
                    'name' => 'PROJECT_SETUP_ID',
                    'width' => 25,
                    'align' => "center",
                    'resizable' => false,
                    'sortable' => false,
                    'hidden' => false,
                    'view' => true,
                    'search' => false,
                    'formatter' => '',
                    'searchoptions' => ''
                )
            ),
            'custom' => array("button" => $buttons, "function" => $mfunctions),
            'div_name' => 'projectListGrid',
            'source' => 'loadProjectGrid',
            'postData' => '{}',
            'rowNum' => 10,
			'autowidth'=>true,
            'width' => DEFAULT_GRID_WIDTH,
            'height' => '',
            'altRows' => true,
            'rownumbers' => true,
            'sort_name' => 'WORK_NAME',
            'sort_order' => 'asc',
            'primary_key' => 'WORK_NAME',
            'add_url' => '',
            'edit_url' => '',
            'delete_url' => '',
            'caption' => addslashes('<span class="cus-date"></span>Projects for Monthly Data Entry'),
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

    public function loadProjectGrid(){
        $objFilter = new clsFilterData();
        $objFilter->assignCommonPara($_POST);
				$permissions = $this->mi__t_monthly->getPermissions();
        if ($this->input->post('SEARCH_PROJECT_NAME')) {
            array_push($objFilter->SQL_PARAMETERS, array('WORK_NAME', 'LIKE', $this->input->post('SEARCH_PROJECT_NAME')));
        }
        //$SDO_ID = $this->input->post('SDO_ID');
        $EE_ID = $this->input->post('EE_ID');
        $CE_ID = $this->input->post('CE_ID');
        $SE_ID = $this->input->post('SE_ID');
        if ($EE_ID == false && $CE_ID == false && $SE_ID == false) {
            $EE_ID = getSessionDataByKey('EE_ID');
            $SE_ID = getSessionDataByKey('SE_ID');
            $CE_ID = getSessionDataByKey('CE_ID');
            //$SDO_ID = $this->session->userData('SDO_ID');
        }
        if ($EE_ID == 0 && $SE_ID == 0 && $CE_ID == 0) {// && $SDO_ID==0)
            //DO NOTHING .....
        } else {
           $arrOfficeWhere = array();
			if($EE_ID)									array_push($arrOfficeWhere,  ' EE_ID=' . $EE_ID);
			if($SE_ID && (!$EE_ID))						array_push($arrOfficeWhere,  ' SE_ID=' . $SE_ID);
			if($CE_ID && ( (!$SE_ID) && (!$EE_ID) ))	array_push($arrOfficeWhere,  ' CE_ID=' . $CE_ID);
			if($arrOfficeWhere)							array_push($objFilter->WHERE, implode(' AND ', $arrOfficeWhere));
        }
        $currentSessionId = getSessionDataByKey('CURRENT_SESSION_ID');
        $eeId = getSessionDataByKey('CURRENT_OFFICE_ID');
		//echo $currentSessionId ;
		
		$arrFields = array('PROJECT_SETUP_ID', 'PROJECT_CODE', 'AA_DATE', 
			'CONCAT(WORK_NAME,"<br />", WORK_NAME_HINDI)AS PROJECT_NAME',
			'CONCAT(PARENT_PROJECT_NAME,"<br />", PARENT_PROJECT_NAME_HINDI,"<br />", PARENT_PROJECT_ID)AS PARENT_PROJECT_NAME',
			'AA_DATE AS PROJECT_START_DATE','SETUP_LOCK',  'TARGET_LOCK_SESSION_ID', 'MONTH_LOCK', 'MONTHLY_EXISTS', 
			'SESSION_ID','SESSION_START_YEAR', 'SESSION_END_YEAR', 'SE_LOCK_MONTH'
		);
        $strWhere = 'WHERE SETUP_LOCK=1 AND ((WORK_STATUS<5) OR ((WORK_STATUS>=5) AND (SE_COMPLETION=0))) 
				AND TARGET_LOCK_SESSION_ID>0 AND TARGET_LOCK_SESSION_ID<=' . $currentSessionId;
		$this->load->library('MiCommonSQLs');
		//$arrFields, $strWhere, $lang='english', $orderBy='')
		$strSQL = $this->micommonsqls->getProjectLockDataSQL($arrFields, $strWhere, 'english');
        $objFilter->SQL = $strSQL;
        //p.TARGET_LOCK,
        $objFilter->executeMyQuery();
        //echo $objFilter->PREPARED_SQL;
        if ($objFilter->TOTAL_RECORDS) {
            $rows = array();
            $isEE = ((getSessionDataByKey('HOLDING_PERSON') == 4) ? true : false);
            $seLockedMonth = '';
            $seLockedMonthValue = 0;
						//echo '::'.$isEE.'::';
            if($isEE) {
								$isOpt = isOperator();
                $canEESave = FALSE;
                $canEELock = FALSE;
						//echo 'sssss:'.$seLockedMonth;
            	$validEntryMonth = $this->mi__t_monthly->getValidEntryMonth();
						//if($eeId==45){	$validEntryMonth = '2018-09-01';	}
						//$validEntryMonth = '2018-12-01';
           	 	//echo 'valid entry month = '. $validEntryMonth ;
				$validEntrySessionId = getSessionIdByDate($validEntryMonth);
           		//exit;
            	$validEntryMonthValue = strtotime($validEntryMonth);
	            //echo $validEntryMonthValue;
    	        //echo "<br />d =". date("Y-m-d", $validEntryMonthValue);
                //echo 'oid:'.$this->session->userData('CURRENT_OFFICE_ID');
                $relaxRec = $this->mi__t_monthly->getEESELockRelaxation(getSessionDataByKey('CURRENT_OFFICE_ID'),  $validEntryMonth);
            	//showArrayValues(	$relaxRec);
            	//exit;
                $settingsRec = $this->mi__t_monthly->getEESELockSettings();
				//showArrayValues($settingsRec);
                $currentDay = date("j");
                $currentDateValue = strtotime("now");
                $saveStartDateValue = strtotime(date("Y-m-").str_pad($settingsRec->SAVE_START_DAY_EE, 2, '0', STR_PAD_LEFT));
                $saveEndDateValue = strtotime(date("Y-m-") . str_pad($settingsRec->SAVE_END_DAY_EE, 2, '0', STR_PAD_LEFT)." ".$settingsRec->LOCK_END_TIME_EE);
                $lockEndDateValue = strtotime(date("Y-m-") . str_pad($settingsRec->LOCK_END_DAY_EE, 2, '0', STR_PAD_LEFT)." ".$settingsRec->LOCK_END_TIME_EE);
                //SAVING
				//if current date is between EE date (start date and end date)
                if( ($currentDateValue >= $saveStartDateValue) && ($currentDateValue <= $saveEndDateValue) ) {
                     $canEESave = TRUE;
					 					//echo '1. ';
                    //$canEELock = TRUE;
                }else{
					//echo '2. ';
					//echo 'inner else --1';
					if ($relaxRec && ($relaxRec->IS_MI)) {
						$relaxFromDateValue = strtotime($relaxRec->RELAXATION_FROM);
						$relaxToDateValue = strtotime($relaxRec->RELAXATION_TO.' 23:59:00');
						// echo '$relaxRec->RELAXATION_TO:'.$relaxRec->RELAXATION_TO.' $currentDateValue:'.$currentDateValue.' $relaxFromDateValue:'
						//.$relaxFromDateValue.':'.' $relaxToDateValue:'.$relaxToDateValue;
						if (($currentDateValue>= $relaxFromDateValue) && ($currentDateValue <= $relaxToDateValue)) {
							$canEESave = TRUE;
						}
					}
                }

                //echo '$canEESave'.$canEESave.'::';
      	         // echo "<br>". date("d-m-Y H:i:s", $currentDateValue).' '. $currentDateValue  .">=".date("d-m-Y H:i:s", $saveEndDateValue).' '. $saveEndDateValue;
                //exit;
				//LOCKING
                if(($currentDateValue >= $saveEndDateValue) && ($currentDateValue <= $lockEndDateValue)) {
				    $canEELock = TRUE;
                }else{
					if ($relaxRec && ($relaxRec->IS_MI)) {
						$relaxFromDateValue = strtotime($relaxRec->RELAXATION_FROM);
						$relaxToDateValue = strtotime($relaxRec->RELAXATION_TO.' 23:59:00');
						if(($currentDateValue >= $relaxFromDateValue) && ($currentDateValue <= $relaxToDateValue)) {
							$canEELock = TRUE;
					      }
			        }
                }
				//echo '::'.$canEELock.'::';
                /*$arrEE = array();
                $arrPrj = array();//7236,4474,4491);//7224,7223);//2790, 2817, 4610);
                if (in_array(getSessionDataByKey('EE_ID'), $arrEE)) {
                    $canEELock = TRUE;
                    $canEESave = TRUE;
                }*/
                $isDebug = 0;
                if ($isDebug) {
                    showArrayValues($settingsRec);
                    echo 'currentDateValue:' . date("d-m-Y", $currentDateValue) . "\n" .
                        'saveEndDateValue:' . date("d-m-Y H:i:s", $saveEndDateValue) . "\n" .
                        'lockEndDateValue:' . date("d-m-Y H:i:s", $lockEndDateValue) . "\n" .
                        'currentDay:' . $currentDay . "\n" .
                        'canEESave:' . $canEESave . ' canEELock:' . $canEELock . "\n";
                }
                //get last SE lock data
                $this->db->order_by('LOCKED_MONTH', 'DESC');
                $this->db->limit(1, 0);
                $recs = $this->db->get_where('mi__t_selocks', array('EE_ID'=>getSessionDataByKey('USER_ID')));
                if ($recs && $recs->num_rows()) {
                    $rec = $recs->row();
										$recs->free_result();
                    $seLockedMonth = $rec->LOCKED_MONTH;
                    $seLockedMonthValue = strtotime($seLockedMonth);
                }
            }
			$curDay = (int) date("d");
			$debug = FALSE;
			//$canEESave = TRUE;
			//echo $permissions['SAVE_LOCK'];
			//if(getSessionDataByKey('CURRENT_OFFICE_ID')==21)$debug = TRUE;
			//if($eeId==45){	$canEESave = $canEELock  = TRUE;	}
            foreach($objFilter->RESULT as $row) {
				//echo $row->SESSION_ID;
                $fieldValues = array();
                array_push($fieldValues, '"' . addslashes($row->PROJECT_NAME) . '"');
                array_push($fieldValues, '"' . addslashes($row->PARENT_PROJECT_NAME) . '"');
                array_push($fieldValues, '"' . addslashes($row->PROJECT_CODE) . '"');
                array_push($fieldValues, '"' . addslashes($row->MONTHLY_EXISTS) . '"');
                array_push($fieldValues, '"' . addslashes($row->MONTH_LOCK) . '"');
				//array_push($objFilter->ROWS, '{"id":"' . $row->PROJECT_SETUP_ID . '", "cell":[' . implode(',', $fieldValues) . ']}');
				//continue;
                $lockMonth = '';
				$showEntryCaption = '';
				$showEntryButton = FALSE;
				$showLockCaption = '';
				$showLockButton = FALSE;
				$x = 'x';
                if($isEE){
                    //last lock date by EE
                    $lockMonth = $row->MONTH_LOCK;
                    $lockMonthValue = strtotime($lockMonth);
                    $nextMonthValue = "";
                    $nextMonthValue = strtotime("+1 month", $lockMonthValue);
                    //$lockDateValue = strtotime($row->MONTH_LOCK);
					//echo 'MONTHLY_EXISTS :'.$row->MONTHLY_EXISTS .'== MONTH_LOCK: '.$row->MONTH_LOCK.'<br />'; 
                    //no monthly records
					if(($row->MONTHLY_EXISTS == NULL) || ($row->MONTH_LOCK == NULL)){
						//echo 'in if...??';exit;
						$startDateValue = strtotime(date("Y-m", strtotime($row->AA_DATE)) . "-01");
						$startSessionID = getSessionIdByDate($row->AA_DATE);
						if($startDateValue <= $validEntryMonthValue) {
							//if($row->PROJECT_SETUP_ID==11) echo 'validEntrySessionId : '.$validEntrySessionId .'< '.$row->SESSION_ID;
							if($validEntrySessionId < $row->SESSION_ID) {
								$showEntryCaption = '"<span class=\"cus-time\" title=\" \"></span> Not Ready"';
                 //array_push($fieldValues,'"<span class=\"cus-time\" title=\" \"></span> Not Ready"');
            	}else{
								$validEntryMonthValue = strtotime(date("Y-m", strtotime("-1month")) . '-01');
						    $arrPjjj = array(11);
						    if (in_array($row->PROJECT_SETUP_ID, $arrPjjj)) {
                    $dt = $validEntryMonthValue;
                    $validEntryMonthValue = strtotime("2019-03-01");
                    $startDateValue;
                }
								$startDateValue = $nextMonthValue = $validEntryMonthValue;

								if($row->MONTHLY_EXISTS!=NULL){
									$monthlyExistsValue = strtotime($row->MONTHLY_EXISTS);
									if($monthlyExistsValue<$validEntryMonthValue){
										$nextMonthValue = $monthlyExistsValue;
									}
								}
								//show set button
								if($canEESave && $isOpt){
									$showEntryButton = TRUE;
									$x = 'y';
								}else{
									$showEntryCaption = '"<span class=\"cus-time\" title=\" Unavailable\"></span>"';
								}//else
                            }//else
						}else{
							$showEntryCaption = '"<span class=\"cus-time\" title=\" Wait for Next Month\"></span>"';
						}
						/*if($row->MONTH_LOCK == NULL){
							//$lockMonthValue = $startDateValue;
							//echo "we are in 0000000";
							//echo 'start date xxxxxxxxxxx= '. date('Y-m-d',$startDateValue);
							$nextMonthValue = $startDateValue;
							//echo 'start date xxxxxxxxxxx= '. date('Y-m-d',$nextMonthValue);
						}*/
                    }else{
                        //echo "in else ...??";exit;
                        /*echo 'lockDateValue:'.date("d-m-Y", $lockDateValue) . ' validEntryMonthValue:'.
							date("d-m-Y", $validEntryMonthValue)."\n";*/
                        $lockShow = false;
                        //if lock month is same as se lock month
                        if($seLockedMonthValue == $lockMonthValue) {
                            //if current month is locked
                            if ($lockMonthValue == $validEntryMonthValue) {
                                //do not show button
								$showEntryCaption = '"'.date("m, Y", $validEntryMonthValue).'"';
                                //array_push($fieldValues, '"'.date("m, Y", $validEntryMonthValue).'"');
                                $lockMonthValue = $validEntryMonthValue;
                            }else{
                                //else means lock date before current month
                                $dt = $validEntryMonthValue;
                                //if lock date is before of valid date then increate month
                                //if( $row->PROJECT_ID==3007) echo date("d-m-Y", $lockDateValue);
                                //EE & SE LOCK DATES are same
                                if($lockMonth==$row->SE_LOCK_MONTH){
									//if current entry month is not locked
                                    if($lockMonthValue < $validEntryMonthValue)
                                        $nextMonthValue = strtotime("+1 month", $lockMonthValue);
									//physical target is locked of current month session
                                    if($this->mi__t_monthly->isSessionLocked($nextMonthValue, $row->PROJECT_SETUP_ID)) {

                                        $nextMonthValue = strtotime(date("Y-m", $nextMonthValue) . '-01');
                                        //rk
			                             //$nextMonthValue = strtotime(date("Y-m") . '-01');
                                        if($canEESave) {
											$showEntryButton = TRUE;
                                            //array_push($fieldValues, '"' . addslashes($this->getMonthlyButton($row->PROJECT_SETUP_ID, $nextMonthValue)). '"');
                                        }else{
											$showEntryCaption = '"<span class=\"cus-time\" title=\"Save Unavailablex1\"></span>"';
											//array_push($fieldValues, '"<span class=\"cus-time\" title=\"Save Unavailable1\"></span>"');
                                        }
                                        //$lockMonthValue = $nextMonthValue;
                                    }else{
										$showEntryCaption = '"Check Target"';
                                        //array_push($fieldValues, '"Check Target"');
                                    }
                                }else{
                                    //echo 3;
                                    $nextMonthValue = strtotime("+1 month", $lockMonthValue);
									$showEntryCaption = '"Wait for ' . date("M, Y", $nextMonthValue) . '"';
                                    //array_push($fieldValues, '"Wait for ' . date("M, Y", $nextMonthValue) . '"');
                                }//else
                            }//else
                        }else{
                            //$nextMonthValue = $lockMonthValue;
                           //echo 'SS:'.$lockMonthValue.'<'.$validEntryMonthValue;
                            if($lockMonthValue < $validEntryMonthValue){
                                //echo '99999999999999';
                                $nextMonthValue = strtotime("+1 month", $lockMonthValue);
                            }
                            if($lockMonthValue == $validEntryMonthValue){
								$showEntryCaption = '"<span class=\"cus-lock\" title=\"Locked\"></span>"';
                                //array_push($fieldValues, '"<span class=\"cus-lock\" title=\"Locked\"></span>"');
                            }else{
                                if($canEESave){
                                    $curMonth = (int)date("m", $nextMonthValue);
                                    if($curMonth == 4) {
                                        $monthSessionId = getSessionIdByDate(date("Y-m-d", $nextMonthValue));
                                        //echo  '$monthSessionId:'.$monthSessionId.'=='.$row->TARGET_LOCK_SESSION_ID;
                                        if($monthSessionId == $row->TARGET_LOCK_SESSION_ID){
											$showEntryButton = TRUE;
                                            //array_push($fieldValues, '"'.addslashes(
											//getButton(date("M, Y", $nextMonthValue),'showMonthlyStatusForm('.$row->PROJECT_SETUP_ID.','.$nextMonthValue.')', 4, 'cus-calendar-view-day')) . '"');
                                        }else{
											$showEntryCaption = '"22-Waiting for Target..."';
                                            //array_push($fieldValues,'"22-Waiting for Target..."');
                                        }
                                    }else{
										$showEntryButton = TRUE;
                                        //array_push($fieldValues, '"' . addslashes(
                                        //       getButton(date("M, Y", $nextMonthValue), 
										//'showMonthlyStatusForm(' . $row->PROJECT_SETUP_ID . ',' . $nextMonthValue . ')', 4, 'cus-calendar-view-day')) . '"');
                                    }
                                }else{
									$showEntryCaption = '"<span class=\"cus-time\" title=\"Save Unavailable2\"></span>"';
                                    //array_push($fieldValues, '"<span class=\"cus-time\" title=\"Save Unavailable2\"></span>"');
                                }//else
                            }//else
                        }//if
                    }//else
					//lock ready
					////////////////////////////////////////////////////////////////////////////////////////
					//echo 'L:'.$lockMonthValue.' == '.$validEntryMonthValue.'<br />';
					if($debug) echo 'nextMonthValue:'.date("Y-m-d", $nextMonthValue)."\n";
					if($lockMonthValue == $validEntryMonthValue) {
						$showLockCaption = 'Locked';
						// array_push($fieldValues, '"<span class=\"cus-lock\" title=\"Locked\"></span>"');
					}else if($nextMonthValue <= $lockMonthValue) {
						$showLockCaption = 'Locked';
                        //array_push($fieldValues, '"' . addslashes('<span class="cus-lock" title="Monthly Locked"></span>') . '"');
                    }else{
						$monthSessionId = getSessionIdByDate(date("Y-m-d", $nextMonthValue));
						//if($row->PROJECT_SETUP_ID==3) 
						if($debug) echo 'monthSessionId:'.$monthSessionId .'=='. $row->TARGET_LOCK_SESSION_ID.'::';
						if($monthSessionId != $row->TARGET_LOCK_SESSION_ID) {
							$showLockCaption = '"' . addslashes('Waiting for Target...'). '"';
						}else if($nextMonthValue == strtotime(date("Y-m") . '-01')) {
							$showLockCaption = 'Locked';
							//array_push($fieldValues, '"<span class=\"cus-lock\" title=\"Locked\"></span>"');
						}
						//echo 'ee lock = '. $canEELock .'<<';
						if($canEELock && $permissions['SAVE_LOCK']){
							//check for ready to lock
							$isReady = $this->mi__t_monthly->readyToLock($row->PROJECT_SETUP_ID, $nextMonthValue);
							//$isReady =true;
							if($isReady) {
								$showLockButton = TRUE;
							}
						}//if
                    }//else
					////////////////////////////////////////////////////////////////////////////////////////
				}//if($isEE)
				//
				if($showEntryButton){
					//$nextMonthValue = strtotime("2018-09-01");
					array_push($fieldValues, '"'.addslashes(getButton(date("M, Y", $nextMonthValue).$x, 'showMonthlyStatusForm('.$row->PROJECT_SETUP_ID.', '.$nextMonthValue . ')', 4, 'cus-calendar-view-day')).'"');
				}else{
					//array_push($fieldValues, '"'.addslashes($row->PROJECT_SETUP_ID).'"');
					if($showEntryCaption=='') $showEntryCaption = '"<span class=\"cus-time\" title=\"Unavailable.1\"></span>"';
					array_push($fieldValues, $showEntryCaption);
				}
				if($showLockButton){
					array_push($fieldValues, '"'.addslashes(
						getButton(date("M, Y", $nextMonthValue), 'lockMonthly('.$row->PROJECT_SETUP_ID.','.$nextMonthValue.')', 4, 'cus-lock')).'"'
					);
				}else{
					//array_push($fieldValues, '"'.addslashes($row->PROJECT_SETUP_ID).'"');
					if($showLockCaption=='') $showLockCaption = '"<span class=\"cus-time\" title=\"Lock Unavailable.1\"></span>"';
					if($showLockCaption=='Locked') $showLockCaption =  '"'.addslashes('<span class="cus-lock" title="Month Locked"></span>').'"';
					array_push($fieldValues, $showLockCaption);
				}
				//Monthly progress
                array_push($fieldValues, '"'.addslashes($this->mi__t_monthly->getMonthlyProgress($row->PROJECT_SETUP_ID, $row->MONTHLY_EXISTS)).'"');
                array_push($fieldValues, '"'.addslashes($row->PROJECT_SETUP_ID).'"');
                array_push($objFilter->ROWS, '{"id":"' . $row->PROJECT_SETUP_ID . '", "cell":[' . implode(',', $fieldValues) . ']}');
            }
        }
        echo $objFilter->getJSONCodeByRow();
        //echo $objFilter->getJSONCode('PROJECT_ID', $fields);
        //echo $objFilter->PREPARED_SQL;
    }
	//OK
    public function showMonthlyStatusForm(){
        //echo "post date = ". date("Y-m-d", 1472668200);
		$projectSetupId = $this->input->post('PROJECT_ID');
        $arrData = array('PROJECT_SETUP_ID'=>$projectSetupId, 'entryDate'=>$this->input->post('date_val'));
		//$assignedStatus = (IS_LOCAL_SERVER) ? 1 : $this->mi__t_monthly->getAssignedStatus($projectSetupId);
        $assignedStatus =1;
        //if($this->PROJECT_ID ==4802) $assignedStatus = 1;
        //$assignedStatus = 1;
        if($assignedStatus == -1) {
            $data = array('PROJECT_NAME' => 'Unable get Assign Status from E-work Server. Try after sometime...');
            $this->load->view('utility/assign_view', $data);
            return;
        }
        if(!$assignedStatus){
            $recs = $this->db->get_where('mi__m_project_setup', array('PROJECT_SETUP_ID' => $projectSetupId));
            if ($recs && $recs->num_rows()) {
                $rec = $recs->row();
				$recs->free_result();
                $data = array('PROJECT_NAME' => $rec->WORK_NAME . '<br /><br />' . $rec->WORK_NAME_HINDI);
                $this->load->view('utility/assign_view', $data);
            }
            return;
        }
		$data = $this->mi__t_monthly->getData($arrData);
    
   		$entryDate = $this->input->post('date_val');
        $prevMonthValue = strtotime("-1month", $entryDate);

        $arrWhich = array(  
                'PROJECT_SETUP_ID'=>$projectSetupId, 
                'MONTH_DATE' => date("Y-m-d", $prevMonthValue),
                'ENTRY_FROM' =>2
        );

        $this->db->select('PROJECT_SETUP_ID');
        $recs = $this->db->get_where('dep_mi__t_monthly', $arrWhich);
        //echo $this->db->last_query(); //exit;
        $prevmonthExists = 0;
        if($recs && $recs->num_rows()) {
            $prevmonthExists = 1;            
        }else{
            $data['arrPreviousMonthData']['WORK_STATUS']='';
        }       

        
        //code to get project status from epay servers //27-04-2020
        // [ CODE STARTS ]
        //if(!$bypassEworks){
          $this->load->library('mycurl');
          $serverStatus = $this->mycurl->getServerStatus();
          if($serverStatus==0){
              echo 'E-work Server Not responding. Try after sometime...';
              return;
          }
        //}     
        $eworkData = $this->mi__t_monthly->getEWorksDetailsForMonthly($projectSetupId);
        //showArrayValues($eworkData); //exit; 
        //exit;
        $result = $this->mycurl->getDepositProjectStatus($eworkData);
        //var_dump($result) ; exit;
        $jsonVal = json_decode($result , true);


        /**
		 * 8-7-2024, Check status of final Payment in eworks
		 */
		$curlParams = array("mode" => "PMON_AGR_FINAL_BILL_CHK",  "Ddocode" => $eworkData['ddocode'] , "PromonID"=> $eworkData['promon_id'] ,"projectCode" =>'');
		$paymentStatusJson = $this->mycurl->savePromonData($curlParams);
		$paymentStatusArr = json_decode($paymentStatusJson , true);
		$data['EWORK_PAYMENT_STATUS'] = $paymentStatusArr['success'];
        // echo 'ework status = '. $data['EWORK_PAYMENT_STATUS']; 
    	$byPassPrjId = array(); //38,39,37
    	 if( in_array($projectSetupId,$byPassPrjId ) ){        
            $data['EWORK_PROJECT_STATUS'] =0;
        }else{
        	$data['EWORK_PROJECT_STATUS'] = $jsonVal['success'];	
        	if ($jsonVal['success'] ==2 ) {
            	echo "<h2 style='color:#ff0000;'>चयनित परियोजना के मद में epay एवं MIS सर्वर में भिन्नता पाई गयी है , कृपया जांच कर डाटा सेन्टर को सूचित करें। </h2>"; 
            	exit;
        	}
         }         
        // [ CODE END ] 
        //showArrayValues($data ); exit;
        if($data['arrProjectData']['PROJECT_SUB_TYPE_ID']==5){
            $this->load->view('pmon_deposit/mi/tubewell_monthly_data_view', $data);
        }else{
            $this->load->view('pmon_deposit/mi/monthly_data_view', $data);
        }
    }
	//
    public function saveMonthlyData(){
		date_default_timezone_set('Asia/Kolkata');
		$arrData = array();
		foreach($this->input->post() as $k=>$v)	$arrData[$k] = $v;
		$message = $this->mi__t_monthly->saveData($arrData);
		echo createJSONResponse($message);
    }

    //not calling this method
    /*public function getBlockIds($projectId){
        if (!$projectId) return '';
        $view = '';
        $blocks = array();
        $recs = $this->db->get_where('mi__m_block_served', array('PROJECT_SETUP_ID' => $projectId));
        //array_push($vlist, '<option value="0">Select District</option>');
        if ($recs && $recs->num_rows()){
            foreach ($recs->result() as $rec){
                array_push($blocks, $rec->BLOCK_ID);
            }
            $recs->free_result();
        }
        return $blocks;
    }*/

    //not using this method
    /*protected function getDateDetail($date){
        return array(
            'date' => $date,
            'month' => date("n", strtotime($date)),
            'year' => date("Y", strtotime($date)),
            'session' => getSessionIdByDate($date)
        );
    }*/
    //OK
    public function monthlyProgressCheck(){
        $projectId = (int)$this->input->post('project_id');
        $lockMonth = date("Y-m-d", $this->input->post('lock_month'));
        echo $this->mi__t_monthly->getMonthlyProgress($projectId, $lockMonth);
    }

	//ok
    public function lockMonthly(){
        date_default_timezone_set('Asia/Kolkata');
        /*if(!IS_LOCAL_SERVER) {
            $this->load->library('mycurl');
            $serverStatus = $this->mycurl->getServerStatus();
            if ($serverStatus == 0) {
                echo 'Unable to lock. E-work Server Not responding. Try after sometime...';
                return;
            }
        }*/
        $projectSetupId = (int)$this->input->post('project_id');
        $lockMonth = (int)$this->input->post('lock_month');//AS value
		$arrParams = $this->mi__t_monthly->prepareDataForLock($projectSetupId, $lockMonth);
		//showArrayValues($arrParams);
		if(!$arrParams){
			return ;
		}
		$lockResult = FALSE;
		/*if(!IS_LOCAL_SERVER){
			$result = $this->mycurl->savePromonData($arrParams);
			//echo $result;
			$obj = json_decode($result);
			if($obj->{'success'}){
				$lockResult = TRUE;
				echo "<br />Monthly Progress Sent to E-Works Server.<br />";
				$this->mi__t_monthly->updateLockedStatus($projectSetupId, $arrParams);
				//$this->mi__t_monthly->saveIrrigationPotential($lockMonth, $this->PROJECT_SETUP_ID);
				//clean monthly yearly
				if(in_array($arrParams['projectStatus'], array(5, 6))){
					$arrParams = $this->mi__t_monthly->cleanupMonthlyData($projectSetupId, $lockMonth);
				}
			}
		}*/

        $lockResult = TRUE;
        echo "<br />Monthly Locked.<br />";
        $this->mi__t_monthly->updateLockedStatus($projectSetupId, $arrParams);
        //$this->mi__t_monthly->saveIrrigationPotential($lockMonth, $this->PROJECT_SETUP_ID);
        //clean monthly yearly
        if(in_array($arrParams['projectStatus'], array(5, 6))){
            //$arrParams = $this->mi__t_monthly->cleanupMonthlyData($projectSetupId, $lockMonth);
        }

        $arrMonth = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        echo(($lockResult) ? '<span class="cus-lock"></span>"' .
            $arrMonth[date("n", $lockMonth)] . '" Month Data Locked' . (($arrParams['projectStatus']==5) ? '<br />Project Completed' : '') :
            '<span class="cus-bullet-error"></span> Unable to Lock  "'.$arrMonth[date("n", $lockMonth)].'" Month Data');
    }

    //not using this method
    /*protected function getStartDateOfSession($id){
        //$strSQL = "SELECT START_DATE FROM __sessions WHERE SESSION_ID=" . $id;
		$this->db->select('START_DATE');
        $recs = $this->db->get_where('__sessions', array('SESSION_ID'=>$id));
        //echo $this->db->last_query();
        if ($recs && $recs->num_rows()) {
            $rec = $recs->row();
			$recs->free_result();
            return $rec->START_DATE;
        }
        return '';
    }*/
    // not using this method
    private function getMonCorrect(){
        $PID = array(2733, 2845, 2971, 2986, 3005, 3044, 3046, 3119, 3148, 3149, 3189, 3211, 3252, 3253, 3254, 3255, 3266, 3283, 3285, 3287, 3289, 3290, 3293, 3294, 3349, 3359, 3360, 3361, 3363, 3373, 3374, 3375, 3376, 3406, 3411, 3421, 3426, 3526, 3566, 3666);
        $monthlyFields = $this->mi__t_monthly->getFields('mi__t_monthlydata');
        $this->db->where_in('PROJECT_ID', $PID);
        $this->db->order_by('PROJECT_ID ASC, MONTH_DATE ASC');
        $recs = $this->db->get('mi__t_monthlydata');
        if ($recs) {
            $prjid = 0;
            $i = 0;
            $prevmonth = 0;
            foreach ($recs->result() as $rec) {
                if ($prjid != $rec->PROJECT_ID) {
                    $prjid = $rec->PROJECT_ID;
                    $i = ($i == 0) ? 1 : 0;
                    echo '</p><p class="row' . $i . '">';
                    $prevmonth = (int)date("m", strtotime($rec->MONTH_DATE));
                    $y = 0;
                }
                $curmonth = (int)date("m", strtotime($rec->MONTH_DATE));
                if ($rec->MONTH_DATE == '0000-00-00' || $rec->MONTH_DATE == '2013-04-01') {
                    $prevRec = $rec;
                    continue;
                }
                $suspect = 0;
                if ($prevmonth == $curmonth) {

                } else if ($prevmonth == 12) {
                    if ($curmonth == 1) {
                        //ok
                    } else {
                        $suspect = 1;
                    }
                } else {
                    if (($prevmonth + 1) == $curmonth) {
                        //ok
                    } else {
                        $suspect = 1;
                    }
                }

                $missing_month = array();
                if ($suspect == 1) {
                    //get missing month
                    $monthDatas = array();
                    $xx = ($prevmonth == 12) ? 1 : ($prevmonth + 1);
                    $SESSION_ID = $prevRec->SESSION_ID;
                    $M_YEAR = $prevRec->ENTRY_YEAR + (($prevmonth == 12) ? 1 : 0);
                    $arrExclude = array('MONTHLY_DATA_ID', 'SESSION_ID',
                        'ENTRY_YEAR', 'MONTH_DATE',
                        'FINANCIAL_MONTH'
                    );
                    for (; $xx < $curmonth; $xx++) {
                        array_push($missing_month, $xx);
                        $monthData = array();
                        for ($imCount = 0; $imCount < count($monthlyFields); $imCount++) {
                            if (in_array($monthlyFields[$imCount], $arrExclude)) {
                                continue;
                            } else if ($monthlyFields[$imCount] == 'ENTRY_MONTH') {
                                $monthData['ENTRY_YEAR'] = '2014';
                                $monthData['ENTRY_MONTH'] = $xx;
                                $monthData['FINANCIAL_MONTH'] = getFinancialMonth($xx);
                                $monthData['MONTH_DATE'] = '2014-' . str_pad($xx, 2, "0", STR_PAD_LEFT) . '-01';
                                $monthData['SESSION_ID'] = (($xx >= 4) ? 9 : 8);

                                /*echo $monthData[ 'MONTH_DATE'] .'::fm : '.
								$monthData[ 'FINANCIAL_MONTH'].':: sid: '.
								$monthData[ 'SESSION_ID'].'<br />';*/
                            } else {
                                $monthData[$monthlyFields[$imCount]] = $prevRec->{$monthlyFields[$imCount]};
                            }
                        }

                        array_push($monthDatas, $monthData);

                    }
                    //showArrayValues($monthDatas);
                    $this->db->insert_batch('mi__t_monthlydata', $monthDatas);
                }
                $prevmonth = (int)date("m", strtotime($rec->MONTH_DATE));
                echo 'PR ID:' . $rec->PROJECT_ID . ' month dt:' .
                    (($suspect == 1) ? '<span style="color:#F00">Susp:' : '') . $rec->MONTH_DATE .
                    (($suspect == 1) ? '</span>' : '') .
                    (($suspect == 1) ? ' Missing : ' . implode(', ', $missing_month) : '') .
                    '<BR />';
                $prevRec = $rec;
                $y++;
            }
        }
    }

}
