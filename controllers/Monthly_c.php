<?php date_default_timezone_set('Asia/Kolkata');
//error_reporting(E_ALL);
class Monthly_c extends MX_Controller{
	var $PROJECT_ID, $data, $message;
	function __construct(){
		parent::__construct();
		$this->RESULT = false;
		$this->message = array();
		$this->PROJECT_ID = 0;
		$this->data = array();
      	if($this->session->userData('HOLDING_PERSON')==1) {
        	//$this->getAssignedStatusTest();
      	}
        $this->load->model('pmon_dep/dep_mi__t_monthly');
		//$this->removeExtraProgressData();
		//echo date("Y-m-d h:i:s", 1465237800);
	}
	
	function removeExtraProgressData(){
		$strSQL1 = 'UPDATE dep_pmon__t_progress SET FALTU=0 ';
		$recs = $this->db->query($strSQL1);

		$strSQL = 'SELECT MAX(MONTH_DATE)as mxDate, PROJECT_ID FROM dep_pmon__t_monthlydata GROUP BY PROJECT_ID';
		$recs = $this->db->query($strSQL);
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$strSQL1 = 'UPDATE dep_pmon__t_progress SET FALTU=1 WHERE PROJECT_ID='.$rec->PROJECT_ID.' AND PROGRESS_DATE>"'.$rec->mxDate.'"';
				//$strSQL1 = 'DELETE FROM pmon__t_progress WHERE ';
				$recs = $this->db->query($strSQL1);
			}
			$strSQL1 = 'DELETE FROM dep_pmon__t_progress WHERE FALTU=1 ';
			//$strSQL1 = 'DELETE FROM pmon__t_progress WHERE ';
			$recs = $this->db->query($strSQL1);
		}
	}

    function index(){
        $data = array();
        $this->session->set_userdata(array('MONTHLY_PROJECT_TYPE_ID'=>1));
        $data['message'] = '';
        $arrProjectTypes = array('', 'Minor', 'Medium', 'Major');
        $data['page_heading'] = pageHeading(
            'DEPOSIT PROMON - '. $arrProjectTypes[1].
            ' Project - Monthly Data Entry'
        );
        $this->load->library('office_filter');
        $data['office_list'] = $this->office_filter->office_list();
        $data['isValid'] = 0;
        if($this->session->userData('HOLDING_PERSON')==4) {
            //check for project in setup/Target
            $data['isValid'] = $this->isSetupTargetNotLocked();
        }
        //@todo; remove $data['isValid']=0; after testing complete
        $data['isValid']=0;
        if(!$data['isValid']){
            $data['project_monthly_grid'] = $this->createGrid();
        }
        $this->load->view('pmon_deposit/monthly_index_view',$data);
    }
    private function getAssignedStatusTest(){
		/*if(!IS_LOCAL_SERVER){
			$this->load->library('mycurl');
			$serverStatus = $this->mycurl->getServerStatus();
			if($serverStatus==0){
				return -1;
			}
		}*/
		//$params = array("projectCode"=>4383);
		//$params = array("projectCode"=>4383);
      //$params = array('mode'=>"UE", "id"=>'20131004');
     	$params = array("ContractorID"=>"CGeR06789");
    	//$params = array("projectCode"=>$this->PROJECT_ID);
		  
          
          
		if(!IS_LOCAL_SERVER){
          	//getContractorDetail
          	/*for($i=0;$i<50;$i++){
              $params = array("contractorID"=>"CGeR".str_pad());*/
              //$result = $this->mycurl->getAssignedStatus($params);
              $result = $this->mycurl->getContractorDetail($params);
              echo $result;
              echo '<br /><br/>' ;
            //}
			//$obj = json_decode($result);
			//echo $obj->{'success'};
			//return $obj->{'success'};
		}
	}

	private function isSetupTargetNotLocked(){
		$eeId = $this->session->userData('CURRENT_OFFICE_ID');
		$strSQL = 'SELECT PROJECT_SETUP_ID FROM rrr__v_projects_with_rrr_lock 
			WHERE (SETUP_LOCK=0 OR ( (TARGET_EXISTS>TARGET_LOCK_SESSION_ID) OR (TARGET_EXISTS=0 AND TARGET_LOCK_SESSION_ID=0)
             OR (MONTH_LOCK="2018-03-01" AND IS_COMPLETED=0 AND TARGET_LOCK_SESSION_ID<>'.$this->session->userdata('CURRENT_SESSION_ID').')) ) 
			AND EE_ID='.$eeId ;
		
      	//echo $strSQL.'::'.$this->session->userdata('CURRENT_SESSION_ID').'::';
		$recs = $this->db->query($strSQL);
		$rrrRecs = (($recs && $recs->num_rows()) ? $recs->num_rows():0);
		
		$strSQL = 'SELECT PROJECT_ID FROM dep_pmon__v_projectlist_with_lock 
			WHERE (SETUP_LOCK=0 OR ( (TARGET_EXISTS>TARGET_LOCK_SESSION_ID) OR (TARGET_EXISTS=0 AND TARGET_LOCK_SESSION_ID=0)
             OR (MONTH_LOCK="2018-03-01" AND IS_COMPLETED=0 AND TARGET_LOCK_SESSION_ID<>'.$this->session->userdata('CURRENT_SESSION_ID').')) ) 
			AND OFFICE_EE_ID='.$eeId;
		$recs = $this->db->query($strSQL);
		$pmonRecs = (($recs && $recs->num_rows()) ? $recs->num_rows():0);
		$tProj = $rrrRecs + $pmonRecs;
      	//echo $strSQL.$tProj ;
		return $tProj;
	}
	private function getMonCorrect(){
		$PID = array(2733, 2845, 2971, 2986, 3005, 3044, 3046, 3119, 3148, 3149, 3189, 3211, 3252, 3253, 3254, 3255, 3266, 3283, 3285, 3287, 3289, 3290, 3293, 3294, 3349, 3359, 3360, 3361, 3363, 3373, 3374, 3375, 3376, 3406, 3411, 3421, 3426, 3526, 3566, 3666);
		$monthlyFields = $this->getFields('dep_pmon__t_monthlydata');
		$this->db->where_in('PROJECT_ID', $PID);
		$this->db->order_by('PROJECT_ID ASC, MONTH_DATE ASC');
		$recs = $this->db->get('dep_pmon__t_monthlydata');
		if($recs){
			$prjid = 0;
			$i = 0;
			$prevmonth =0;
			foreach($recs->result() as $rec){
				if($prjid!=$rec->PROJECT_ID){
					$prjid=$rec->PROJECT_ID;
					$i = ($i==0) ? 1:0;
					echo '</p><p class="row'.$i.'">';
					$prevmonth = (int) date("m", strtotime($rec->MONTH_DATE));
					$y = 0;
				}
				$curmonth = (int) date("m", strtotime($rec->MONTH_DATE));
				if($rec->MONTH_DATE=='0000-00-00' || $rec->MONTH_DATE=='2013-04-01'){
					$prevRec = $rec;
					continue;
				}
				$suspect = 0;
				if($prevmonth==$curmonth){
					
				}else if($prevmonth==12){
					if($curmonth==1){
						//ok
					}else{
						$suspect = 1;
					}
				}else {
					if(($prevmonth+1)==$curmonth){
						//ok
					}else{
						$suspect = 1;
					}
				}

				$missing_month = array();
				if($suspect==1){
					//get missing month
					$monthDatas = array();
					$xx = ($prevmonth==12) ? 1: ($prevmonth+1);
					$SESSION_ID = $prevRec->SESSION_ID;
					$M_YEAR = $prevRec->ENTRY_YEAR + (($prevmonth==12) ? 1:0);
					$arrExclude = array('MONTHLY_DATA_ID', 'SESSION_ID', 
						'ENTRY_YEAR',  'MONTH_DATE',
						 'FINANCIAL_MONTH'
					);
					for(; $xx<$curmonth;$xx++){
						array_push($missing_month, $xx);
						$monthData = array();
						for($imCount=0; $imCount<count($monthlyFields);$imCount++){
							if( in_array($monthlyFields[$imCount], $arrExclude)){
								continue;
							}else if($monthlyFields[$imCount]=='ENTRY_MONTH'){
								$monthData[ 'ENTRY_YEAR' ] = '2014';
								$monthData[ 'ENTRY_MONTH'] = $xx;
								$monthData[ 'FINANCIAL_MONTH'] = $this->getFinancialMonthByMonth($xx);
								$monthData[ 'MONTH_DATE'] = '2014-'. str_pad($xx, 2, "0", STR_PAD_LEFT) .'-01';
								$monthData[ 'SESSION_ID' ] = (($xx>=4)? 9: 8);
								
								echo $monthData[ 'MONTH_DATE'] .'::fm : '.
								$monthData[ 'FINANCIAL_MONTH'].':: sid: '.
								$monthData[ 'SESSION_ID'].'<br />';
							}else{
								$monthData[ $monthlyFields[$imCount] ] = $prevRec->{$monthlyFields[$imCount]};
							}
						}
						
						array_push($monthDatas, $monthData);
						
					}
					//showArrayValues($monthDatas);
					$this->db->insert_batch('dep_pmon__t_monthlydata', $monthDatas);
				}
				$prevmonth = (int) date("m", strtotime($rec->MONTH_DATE));
				echo 'PR ID:'.$rec->PROJECT_ID.' month dt:'. 
					(($suspect==1)? '<span style="color:#F00">Susp:':'') . $rec->MONTH_DATE.
					(($suspect==1)? '</span>':'') .
					(($suspect==1)? ' Missing : '. implode(', ', $missing_month):'').
					'<BR />';
				$prevRec = $rec;
				$y++;
			}
		}
	}
	//Office
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
		//array_push($mfunctions , "onSelectRow: function(ids){getProjectSubType(ids);}");
		$aData = array(
			'set_columns' => array(
				array(
					'label' => 'Project',
					'name' => 'PROJECT_NAME',
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
					'label' => 'परियोजना',
					'name' => 'PROJECT_NAME_HINDI',
					'width' => 70,
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
				 	'label' => 'Exists',
					'name' => 'MONTHLY_EXISTS',
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
				 	'label' => addslashes('<span class="cus-lock"></span>Month'),
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
				 	'label' => 'Data Entry',
					'name' => 'ADD',
					'width' => 50,
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
				 	'label' => 'Action',
					'name' => 'lock',
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
				 	'label' => 'Progress',
					'name' => 'PROGRESS',
					'width' => 25,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>false,
					'hidden'=>false,
					'view'=>true,
					'search'=>false,
					'formatter'=> '',
					'searchoptions'=>''
				)
			),
			'custom' => array("button"=>$buttons, "function"=>$mfunctions),
			'div_name' => 'projectListGrid',
			'source' => 'loadProjectGrid',
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
			'caption' => addslashes('<span class="cus-date"></span>Projects for Monthly Data Entry'),
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
	private function getEESELockSettings(){
		$recs = $this->db->get('__settings');
		if($recs && $recs->num_rows()){
			return $recs->row();
		}
		return FALSE;
	}
  
	private function isTargetLocked($dateval){
		
	}
	private function getEESELockRelaxation($id)
	{
		$this->db->order_by('MONTH_DATE', 'DESC');
  		$this->db->limit(1);

		$recs = $this->db->get_where('pmon__t_lock_ee_relaxations', array('EE_ID'=>$id, 'IS_DEPOSIT'=>1));
     	// echo $this->db->last_query();
		if($recs && $recs->num_rows()){
			return $recs->row();
		}
		return FALSE;
	}
	public function loadProjectGrid()
	{
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);
		if($this->input->post('SEARCH_PROJECT_NAME')){
			array_push(
				$objFilter->SQL_PARAMETERS, 
				array('PROJECT_NAME', 'LIKE', $this->input->post('SEARCH_PROJECT_NAME'))
			);
		}
		//$SDO_ID = $this->input->post('SDO_ID');
		$EE_ID = $this->input->post('EE_ID');
		$CE_ID = $this->input->post('CE_ID');
		$SE_ID = $this->input->post('SE_ID');
		//if($EE_ID==false && $CE_ID==false && $SE_ID==false && $SDO_ID==false)
		if($EE_ID==false && $CE_ID==false && $SE_ID==false ){
			$EE_ID = $this->session->userData('EE_ID');
			$SE_ID = $this->session->userData('SE_ID');
			$CE_ID = $this->session->userData('CE_ID');
			//$SDO_ID = $this->session->userData('SDO_ID');
		}
		$EEE = '';
		if ($EE_ID==0 && $SE_ID==0 && $CE_ID==0){// && $SDO_ID==0)
			//DO NOTHING .....
		}else{
			//$EEE = ($SDO_ID==0)? '' : ' OFFICE_SDO_ID='.$SDO_ID;
			$EEE .= ($EE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_EE_ID='.$EE_ID;
			$EEE .= ($SE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SE_ID='.$SE_ID;
			$EEE .= ($CE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_CE_ID='.$CE_ID;
			//array_push(	$objFilter->WHERE, $EEE);
		}
		if($EEE!=''){
			array_push($objFilter->WHERE, $EEE);	
		}
		$currentSessionId = $this->session->userData('CURRENT_SESSION_ID');
		//echo $currentSessionId ;
		/*$objFilter->SQL = 'SELECT p.PROJECT_ID, p.PROJECT_CODE, p.AA_DATE,
			p.PROJECT_NAME, p.PROJECT_NAME_HINDI, p.PROJECT_START_DATE,
			p.SETUP_LOCK, p.TARGET_LOCK, p.TARGET_LOCK_SESSION_ID,
			p.MONTHLY_LOCK, p.MONTH_LOCK, p.MONTHLY_EXISTS, p.SESSION_ID,
			p.SESSION_START_YEAR, p.SESSION_END_YEAR, p.SE_LOCK_MONTH 
		FROM dep_pmon__v_projectlist_with_lock as p 
			WHERE p.PROJECT_TYPE_ID='.$this->session->userData('MONTHLY_PROJECT_TYPE_ID').
			' AND p.SETUP_LOCK=1 AND ((PROJECT_STATUS<5) OR ((PROJECT_STATUS>=5) AND (SE_COMPLETION=0))) 
				AND TARGET_LOCK_SESSION_ID>0 AND TARGET_LOCK_SESSION_ID<='.$currentSessionId;*/

        $objFilter->SQL = "SELECT
p.PROJECT_ID, p.PROJECT_SETUP_ID,  p.PROJECT_CODE, p.AA_DATE,
p.PROJECT_NAME, p.PROJECT_NAME_HINDI, p.PROJECT_START_DATE,
p.SETUP_LOCK, p.TARGET_LOCK_SESSION_ID,
p.MONTH_LOCK, p.MONTHLY_EXISTS, p.SESSION_ID,
p.SESSION_START_YEAR, p.SESSION_END_YEAR, p.SE_LOCK_MONTH,
p.OFFICE_EE_ID, p.OFFICE_SE_ID, p.OFFICE_CE_ID, p.IS_MI
FROM
(
SELECT
p.PROJECT_ID, p.PROJECT_SETUP_ID, p.PROJECT_CODE, p.AA_DATE,
p.PROJECT_NAME, p.PROJECT_NAME_HINDI, p.PROJECT_START_DATE,
p.SETUP_LOCK, p.TARGET_LOCK_SESSION_ID,
p.MONTH_LOCK, p.MONTHLY_EXISTS, p.SESSION_ID,
p.SESSION_START_YEAR, p.SESSION_END_YEAR, p.SE_LOCK_MONTH, p.IS_COMPLETED,
OFFICE_EE_ID, OFFICE_SE_ID, OFFICE_CE_ID, '0' as IS_MI
FROM
dep_pmon__v_projectlist_with_lock as p
WHERE
p.PROJECT_TYPE_ID=1 AND p.SETUP_LOCK=1 AND ((PROJECT_STATUS<5) OR ((PROJECT_STATUS>=5) AND (p.IS_COMPLETED=0))) AND TARGET_LOCK_SESSION_ID>0
AND TARGET_LOCK_SESSION_ID<='$currentSessionId'
UNION
SELECT
dep_mi__m_project_setup.PROJECT_ID,dep_mi__m_project_setup.PROJECT_SETUP_ID,
dep_mi__m_project_setup.PROJECT_CODE,
dep_mi__m_project_setup.AA_DATE,
dep_mi__m_project_setup.PROJECT_NAME,
dep_mi__m_project_setup.PROJECT_NAME_HINDI,
dep_mi__m_project_setup.AA_DATE AS PROJECT_START_DATE,
dep_mi__t_locks.SETUP_LOCK AS SETUP_LOCK,
ifnull( dep_mi__t_locks.TARGET_LOCK_SESSION_ID, 0 ) AS TARGET_LOCK_SESSION_ID,
ifnull( dep_mi__t_locks.MONTH_LOCK, '0000-00-00' ) AS MONTH_LOCK,
dep_mi__t_locks.MONTHLY_EXISTS,
dep_mi__m_project_setup.SESSION_ID,
ifnull( __sessions.SESSION_START_YEAR, 0 ) AS SESSION_START_YEAR,
ifnull( __sessions.SESSION_END_YEAR, 0 ) AS SESSION_END_YEAR,
dep_mi__t_locks.SE_LOCK_MONTH,
dep_mi__t_locks.IS_COMPLETED,
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
LEFT JOIN __sessions setup_session ON  ( dep_mi__m_project_setup.SESSION_ID = setup_session.SESSION_ID )
WHERE PROJECT_TYPE_ID='1'
AND dep_mi__t_locks.SETUP_LOCK=1 AND ((dep_mi__m_project_setup.WORK_STATUS<5) OR ((dep_mi__m_project_setup.WORK_STATUS>=5) AND (dep_mi__t_locks.IS_COMPLETED=0)))
AND TARGET_LOCK_SESSION_ID>0 AND TARGET_LOCK_SESSION_ID<='$currentSessionId'
)
AS p where 1  ";
		$objFilter->executeMyQuery();
		if($objFilter->TOTAL_RECORDS){
			$rows = array();
			$isEE = (($this->session->userData('HOLDING_PERSON')==4) ? true : false);
			$seLockedMonth = '';
			$seLockedMonthValue = 0;
			$canEESave = FALSE;
				$canEELock = FALSE;
			if($isEE){
              	//echo 'oid:'.$this->session->userData('CURRENT_OFFICE_ID');
		        $relaxRec = $this->getEESELockRelaxation($this->session->userData('CURRENT_OFFICE_ID'));
				$settingsRec = $this->getEESELockSettings();
				
				$currentDay = date("j");
				$currentDateValue = strtotime("now");
				$saveStartDateValue = strtotime(
					date("Y-m-").str_pad($settingsRec->SAVE_START_DAY_EE, 2, '0', STR_PAD_LEFT)
				);
				$saveEndDateValue = strtotime( 
					date("Y-m-").str_pad($settingsRec->SAVE_END_DAY_EE, 2, '0', STR_PAD_LEFT)." ".$settingsRec->LOCK_END_TIME_EE
				);

				$lockEndDateValue = strtotime(
					date("Y-m-").str_pad($settingsRec->LOCK_END_DAY_EE, 2, '0', STR_PAD_LEFT)." ".$settingsRec->LOCK_END_TIME_EE
				);
				//if current date is greater or eq to ee save day
				if($currentDateValue>=$saveStartDateValue){
					//if current date is less then ee save end time
					if($currentDateValue<=$saveEndDateValue){
						$canEESave = TRUE;
						//$canEELock = TRUE;
					} else {
						if ($relaxRec && ($relaxRec->IS_DEPOSIT)) {
							$relaxFromDateValue = strtotime($relaxRec->RELAXATION_FROM);
							$relaxToDateValue =  strtotime($relaxRec->RELAXATION_TO);
							// echo '$relaxRec->RELAXATION_TO:'.$relaxRec->RELAXATION_TO.' $currentDateValue:'.$currentDateValue.' $relaxFromDateValue:'
							//.$relaxFromDateValue.':'.' $relaxToDateValue:'.$relaxToDateValue;
							if (($currentDateValue >= $relaxFromDateValue) && ($currentDateValue <= $relaxToDateValue)) {
								$canEESave = TRUE;
                            }
                        }
                    }
				}
             	// echo '$canEESave'.$canEESave.'::';
				if($currentDateValue>=$saveEndDateValue){
					//if current date is less then ee lock end time
					if($currentDateValue<=$lockEndDateValue){
						$canEELock = TRUE;
					} else {
						if ($relaxRec && ($relaxRec->IS_DEPOSIT)) {
							$relaxFromDateValue = strtotime($relaxRec->RELAXATION_FROM);
							$relaxToDateValue =  strtotime($relaxRec->RELAXATION_TO);
							if (($currentDateValue >= $relaxFromDateValue) && ($currentDateValue <= $relaxToDateValue)) {
								$canEELock = TRUE;
                            }
                        }
                    }
				}

				$arrEE = array(); //74,  36,27  80  72, 67, 65, 69
				$arrPrj = array(); //(7399, 7396,7397,4689,4691,4692,4693);//7236,4474,4491);//7224,7223);//2790, 2817, 4610);
				$byPassMonthly = true;
				if (in_array($this->session->userData('EE_ID'), $arrEE)) {
					$canEELock = TRUE;
					$canEESave = TRUE;
					$byPassMonthly = false;
				}
				if (($currentDateValue <= $lockEndDateValue) &&  ($currentDateValue > $saveEndDateValue))
					$canEELock = TRUE; //TRUE //FALSE; // 02-09-2024 
                
				$isDebug = 0;
				if($isDebug){
					showArrayValues($settingsRec);
					echo 'currentDateValue:'.date("d-m-Y", $currentDateValue)."\n".
						'saveEndDateValue:'.date("d-m-Y H:i:s", $saveEndDateValue)."\n".
						'lockEndDateValue:'.date("d-m-Y H:i:s", $lockEndDateValue)."\n".
						'currentDay:'.$currentDay."\n".
						'canEESave:'.$canEESave. ' canEELock:'.$canEELock."\n";
				}
				//exit;
				//get last se lock data
				$this->db->order_by('LOCKED_MONTH', 'DESC');
				$this->db->limit(1, 0);
				$recs = $this->db->get_where(
					'dep_pmon__t_selocks',
					array(
						'EE_ID'=>$this->session->userData('USER_ID'),
						'PROJECT_TYPE_ID'=>$this->session->userData('MONTHLY_PROJECT_TYPE_ID')
					)
				);
				if($recs && $recs->num_rows()){
					$rec = $recs->row();
					$seLockedMonth = $rec->LOCKED_MONTH;
					$seLockedMonthValue = strtotime($seLockedMonth);
				}
			}
			//echo 'sssss:'.$seLockedMonth;
			$validEntryMonth = $this->getValidEntryMonth();
			//echo $validEntryMonth ;

			$validEntryMonthValue = strtotime($validEntryMonth);
			//echo $validEntryMonthValue;
			//echo "<br />d =". date("Y-m-d", $validEntryMonthValue);
			$oldCanEELock = $canEELock;
			$oldCanEESave = $canEESave;
			$oldByPassMonthly = $byPassMonthly; 
			foreach ($objFilter->RESULT as $row) {
				if ($row->PROJECT_ID == 7338) continue;
				$fieldValues = array();
				array_push($fieldValues, '"'.addslashes($row->PROJECT_NAME ).'"');
				array_push($fieldValues, '"'.addslashes($row->PROJECT_NAME_HINDI ).'"');
				array_push($fieldValues, '"'.addslashes($row->PROJECT_CODE ).'"');
				array_push($fieldValues, '"'.addslashes($row->MONTHLY_EXISTS ).'"');
				array_push($fieldValues, '"'.addslashes($row->MONTH_LOCK ).'"');
				$lockMonth = '';
                if($isEE){
                	if( in_array($row->PROJECT_ID, $arrPrj)){
               			$canEELock = TRUE;
                    	//$canEESave = TRUE;
	           		}
					//$canEESave = TRUE;
					//last lock date by ee
					$lockMonth = $row->MONTH_LOCK;
                  	$monthExistsValue = strtotime($row->MONTHLY_EXISTS);
					$lockMonthValue = strtotime($lockMonth);
					$nextMonthValue = "";
                  	$nextMonthValue = strtotime("+1 month", $lockMonthValue);
					//$lockDateValue = strtotime($row->MONTH_LOCK);
					//no monthly records
					
					
					if (($row->MONTHLY_EXISTS == '0000-00-00')  || ($row->MONTH_LOCK == '0000-00-00') || ($row->MONTHLY_EXISTS == NULL) || ($row->MONTH_LOCK == NULL)) 
					{
                    	
						//echo "in if <br/>";
						//$canEELock = TRUE;
                    	$canEELock = TRUE; //TRUE //FALSE; // 02-09-2024 
                    
						$canEESave = TRUE;
						$byPassMonthly = false;
					}else{
						//echo "in else <br/>";
						$canEELock = $oldCanEELock;
						$canEESave = $oldCanEESave;
						$byPassMonthly =$oldByPassMonthly;
					}
                
                /*if (in_array($this->session->userData('EE_ID'), $arrEE)) {
					$canEELock = TRUE;
					$canEESave = TRUE;
					$byPassMonthly = false;
				}*/


					if (($row->MONTHLY_EXISTS == '0000-00-00')  || ($row->MONTH_LOCK == '0000-00-00') || ($row->MONTHLY_EXISTS == NULL) || ($row->MONTH_LOCK == NULL)) {
						//echo '<br> in if';
						
						//$byPassMonthly = false; //20-05-2020, first monthly entry can be done in  any day of month
						if ($seLockedMonthValue == $validEntryMonthValue) {
							array_push($fieldValues, '"Month Locked By SE...' . '"');
						} else {
							//get AA date
							$startDateValue = strtotime( date("Y-m", strtotime($row->AA_DATE))."-01");
							//echo date(" Y-m-d", $startDateValue);
							//if($rec->PROJECT_ID==4386){echo $startDateValue;}
							//start session id
							$startSessionID = $this->getSessionIdByDate($row->AA_DATE);
							//if start date is before valid date
							//
							//if($row->PROJECT_ID=7994){
                        		//echo "in if <br/>" . $startDateValue ." -- ". $validEntryMonthValue;
                        	///}
							if($startDateValue<=$validEntryMonthValue){
								//if 0 i.e., session is before 2003
								/*if($startSessionID==0){
									//current session locked then get first date of session 
									if($row->TARGET_LOCK_SESSION_ID==$currentSessionId){
										$startDateValue = strtotime($this->getStartDateOfSession($currentSessionId));
                                      $startDateValue = $validEntryMonthValue;
										//echo $startDateValue .'=='.date("Y-m-d", );
									}else{
										//1st date of whatever session locked
										$startDateValue = strtotime($this->getStartDateOfSession($row->TARGET_LOCK_SESSION_ID));
									}
								}else if($startSessionID < $currentSessionId){
									if($startSessionID==$row->TARGET_LOCK_SESSION_ID){
										//nothing to do
									}else{
										$startDateValue = strtotime($this->getStartDateOfSession($row->TARGET_LOCK_SESSION_ID));
									}
								}
								$dt = $startDateValue;*/
								///XXXXXXXX
								$validEntryMonthValue = strtotime(date("Y-m", strtotime("-1month")) . '-01');
								$validEntryMonthValue = strtotime(date("Y-m-d", strtotime("first day of last month")));
								$arrPjjj = array();
								if (in_array($row->PROJECT_ID, $arrPjjj)) {
									$dt = $validEntryMonthValue;
									$validEntryMonthValue = strtotime("2017-04-01");
									$startDateValue;
								}
								$startDateValue = $validEntryMonthValue;
								//show set button
                              	//$curDay = (int) date("d");
                              	//XXXXX
                              	//if($curDay>15 && $byPassMonthly){ //on  2020-04-08
                              	if(($currentDateValue>$lockEndDateValue) && $byPassMonthly){
                                  	array_push(
										$fieldValues, 
										'"<span class=\"cus-time\" title=\" Unavailable (1)\"></span>"'
									);
                                }else{
                              		if ($row->IS_MI == 0) {
                                        array_push(
                                            $fieldValues,
                                            '"' . addslashes(
                                                getButton(date("M, Y", $validEntryMonthValue),
                                                    'showMonthlyStatusForm(' . $row->PROJECT_ID . ',' . $validEntryMonthValue . ')',
                                                    4, 'cus-calendar-view-day')
                                            ) . '"'
                                        );
                                    }elseif ($row->IS_MI == 1){
                                        array_push(
                                            $fieldValues,
                                            '"' . addslashes(
                                                getButton(date("M, Y", $validEntryMonthValue),
                                                    'showMonthlyStatusFormMi(' . $row->PROJECT_SETUP_ID . ',' . $validEntryMonthValue . ')',
                                                    4, 'cus-calendar-view-day')
                                            ) . '"'
                                        );
                                    }
                                }
							}

							if( ($row->MONTH_LOCK=='0000-00-00') || ($row->MONTH_LOCK==NULL)){// && ($row->MONTH_LOCK=='0000-00-00')
								//$lockMonthValue = $startDateValue;
                              $nextMonthValue = $startDateValue;
							}
						}
					}else{

						/*echo 'lockDateValue:'.date("d-m-Y", $lockDateValue) . ' validEntryMonthValue:'.
							date("d-m-Y", $validEntryMonthValue)."\n";*/
						$lockShow = false;
						//if lock month is same as se lock month
						if($seLockedMonthValue==$lockMonthValue){
							//if current month is locked
							if($lockMonthValue==$validEntryMonthValue){
								//do not show button
								array_push($fieldValues, '"'.date("m, Y", $validEntryMonthValue).'"');
								$lockMonthValue = $validEntryMonthValue;
							}else{
								//else means lock date before current month
								//
								$dt = $validEntryMonthValue;
								//if lock date is before of valid date then increate month
								//if( $row->PROJECT_ID==3007) echo date("d-m-Y", $lockDateValue);
								//EE & SE are same
								/*if($lockMonth==$row->SE_LOCK_MONTH){
									if($lockMonthValue<$validEntryMonthValue)
										$nextMonthValue = strtotime("+1 month", $lockMonthValue);
									    //if( $row->PROJECT_ID==3007) echo date("d-m-Y", $nextMonthValue);
									if( $this->isSessionLocked($nextMonthValue, $row->PROJECT_ID)){
										$nextMonthValue = strtotime( date("Y-m", $nextMonthValue).'-01');
										if($canEESave){
											array_push(
												$fieldValues, 
												'"'.addslashes(
													getButton(date("M, Y", $nextMonthValue ).'-2', 
													'showMonthlyStatusForm('.$row->PROJECT_ID.','.$nextMonthValue.')', 
													4, 'cus-calendar-view-day')
												).'"'
											);
										}else{
											array_push(
												$fieldValues, 
												'"<span class=\"cus-time\" title=\"Save Unavailable1\"></span>"'
											);
										}
										//$lockMonthValue = $nextMonthValue;
									}else{
										array_push($fieldValues, '"Check Target"');
									}
								}else{*/
									//echo 3;
									$nextMonthValue = strtotime("+1 month", $lockMonthValue);
									array_push($fieldValues, '"Wait for '.date("M, Y", $nextMonthValue).'"');
								//}
							}
						}else{
							//$nextMonthValue = $lockMonthValue;
							//echo 'SS:'.$lockMonthValue.'<'.$validEntryMonthValue;
							if($lockMonthValue<$validEntryMonthValue){
								$nextMonthValue = strtotime("+1 month", $lockMonthValue);
							}
							if($lockMonthValue==$validEntryMonthValue){
								array_push(
									$fieldValues, 
									'"<span class=\"cus-lock\" title=\"Locked 1\"></span>"'
								);
							}else{
								if($canEESave){
									$curMonth = (int) date("m", $nextMonthValue);
									if($curMonth==4){
										$monthSessionId = $this->getSessionIdByDate(date("Y-m-d", $nextMonthValue));
										//echo  '$monthSessionId:'.$monthSessionId.'=='.$row->TARGET_LOCK_SESSION_ID;
										if($monthSessionId==$row->TARGET_LOCK_SESSION_ID){

										    if ($row->IS_MI == 0) {
                                                array_push(
                                                    $fieldValues,
                                                    '"'.addslashes(
                                                        getButton(date("M, Y", $nextMonthValue ),
                                                            'showMonthlyStatusForm('.$row->PROJECT_ID.','.$nextMonthValue.')',
                                                            4, 'cus-calendar-view-day')
                                                    ).'"'
                                                );
                                            }elseif ($row->IS_MI == 1){
                                                array_push(
                                                    $fieldValues,
                                                    '"'.addslashes(
                                                        getButton(date("M, Y", $nextMonthValue ),
                                                            'showMonthlyStatusFormMi('.$row->PROJECT_SETUP_ID.','.$nextMonthValue.')',
                                                            4, 'cus-calendar-view-day')
                                                    ).'"'
                                                );
                                            }
										}else{
											array_push(
												$fieldValues, 
												'"Waiting for Target..."'
											);
										}
									}else{

									    if ($row->IS_MI == 0) {
                                            array_push(
                                                $fieldValues,
                                                '"'.addslashes(
                                                    getButton(date("M, Y", $nextMonthValue ),
                                                        'showMonthlyStatusForm('.$row->PROJECT_ID.','.$nextMonthValue.')',
                                                        4, 'cus-calendar-view-day')
                                                ).'"'
                                            );
                                        }elseif ($row->IS_MI == 1){
                                            array_push(
                                                $fieldValues,
                                                '"'.addslashes(
                                                    getButton(date("M, Y", $nextMonthValue ),
                                                        'showMonthlyStatusFormMi('.$row->PROJECT_SETUP_ID.','.$nextMonthValue.')',
                                                        4, 'cus-calendar-view-day')
                                                ).'"'
                                            );
                                        }
									}
								}else{
									array_push(
										$fieldValues, 
										'"<span class=\"cus-time\" title=\"Save Unavailable2xx\"></span>"'
									);
								}
							}
							//array_push($fieldValues, '"'.date("M, Y", $lockDateValue).'"');
						}
					}
                  	//echo 'nextMonthValue:'.$nextMonthValue .date("d-m-Y",$nextMonthValue).':'. $row->PROJECT_ID.' : '.$lockMonth.'<br/>';
					if($nextMonthValue!=""){
                    	if( ($lockMonth!='0000-00-00') && ($lockMonth!=NULL) && ($lockMonthValue<$validEntryMonthValue)){
                          	if( in_array($this->session->userData('EE_ID'), $arrEE)){
                            }else if( in_array($row->PROJECT_ID, $arrPrj)){
               				}else{
								$nextMonthValue = strtotime("+1 month", $lockMonthValue);
                            }
                        }
                        //if($row->PROJECT_ID==7910 ) echo 'nextMonthValue:'.$nextMonthValue .date("d-m-Y",$nextMonthValue).':'. $row->PROJECT_ID.'<br/>'; exit;
                      	$nextMonthValue = strtotime( date("Y-m", $nextMonthValue).'-01');
                        //echo 'lockMonthValue:'.$lockMonthValue.' <validEntryMonthValue:'.$validEntryMonthValue.'::'.$nextMonthValue;
						if( in_array($this->session->userData('EE_ID'), $arrEE)){
                           /// $monthSessionId = $this->getSessionIdByDate(date("Y-m-d", $nextMonthValue));
                            //if($monthSessionId==$row->TARGET_LOCK_SESSION_ID){
                          if( $nextMonthValue==strtotime(date("Y-m").'-01') ){
                                array_push($fieldValues, '"<span class=\"cus-lock\" title=\"Locked 2\">'.strtotime(date("Y-m").'-01').'</span>"');
                              }else {
                              		//echo '>>> '.  $monthExistsValue ."==". $nextMonthValue; 
                                  if ($monthExistsValue == $nextMonthValue) {
                                      if ($row->IS_MI == 0) {
                                          array_push(
                                              $fieldValues,
                                              '"' . addslashes(
                                                  getButton(date("M, y", $nextMonthValue),
                                                      'lockMonthly(' . $row->PROJECT_ID . ',' . $nextMonthValue . ')',
                                                      4, 'cus-lock')
                                              ) . '"'
                                          );
                                      } elseif ($row->IS_MI == 1) {
                                          array_push(
                                              $fieldValues,
                                              '"' . addslashes(
                                                  getButton(date("M, y", $nextMonthValue),
                                                      'lockMonthlyMi(' . $row->PROJECT_SETUP_ID . ',' . $nextMonthValue . ')',
                                                      4, 'cus-lock')
                                              ) . '"'
                                          );
                                      }
                                  } else {
                                      array_push($fieldValues, '"<span class=\"cus-add\" title=\"Locked 3\"></span>"');
                                  }
                            }
                            /*}else{
                              array_push(
                                $fieldValues, 
                                '"Waiting for Target..."'
                              );
                            }*/
                        }else if( in_array($row->PROJECT_ID, $arrPrj)){
                        	if($row->PROJECT_ID==4565) {
                            	//array_push($fieldValues, '"'.addslashes( getButton( date("M, y", $nextMonthValue ), 'lockMonthly('.$row->PROJECT_ID.','.$nextMonthValue.')', 4, 'cus-lock')).'"');
                            }
                          	 if( $nextMonthValue==strtotime(date("Y-m").'-01')){
                                array_push($fieldValues, '"<span class=\"cus-lock\" title=\"Locked 4\"></span>"');
                              }else{
                                 if($row->IS_MI==0){
                          	        array_push($fieldValues, '"'.addslashes(getButton( date("M, y", $nextMonthValue ), 'lockMonthly('.$row->PROJECT_ID.','.$nextMonthValue.')', 4, 'cus-lock')).'"');
                          	     }elseif($row->IS_MI==1){
                                    array_push($fieldValues, '"'.addslashes(getButton( date("M, y", $nextMonthValue ), 'lockMonthlyMi('.$row->PROJECT_SETUP_ID.','.$nextMonthValue.')', 4, 'cus-lock')).'"');
                          	     }
                             }
            			}else if($lockMonthValue==$validEntryMonthValue){
                            array_push($fieldValues, '"<span class=\"cus-lock\" title=\"Locked 5\"></span>"');
						}else if($nextMonthValue<=$lockMonthValue){
							array_push($fieldValues, '"'.addslashes('<span class="cus-lock" title="Monthly Locked"></span>').'"');
						}else{
							if($canEELock){
								//check for ready to lock
                              	//if($row->PROJECT_ID==7910 ) echo 'Hello';
                                // echo $nextMonthValue.'=='.strtotime(date("Y-m").'-01').'<br />';
                                if($row->PROJECT_ID==7910){
                                	//echo '>>'. date("Y-m").'-01'. '<<';
                                }
                              if( $nextMonthValue==strtotime(date("Y-m").'-01')){
                                array_push($fieldValues, '"<span class=\"cus-lock\" title=\"Locked 6\"></span>"');
                              }else{
                                  $x =false;
                                  if($row->IS_MI==0){
                                  	if($row->PROJECT_ID==7910){
	                                	//echo '>'. date("Y-m-d", $nextMonthValue).'<';
	                                }
                                      $x= $this->readyToLock($row->PROJECT_ID, $nextMonthValue);
                                      //showArrayValues($x);
                                  }elseif($row->IS_MI==1){
                                      $x = $this->dep_mi__t_monthly->readyToLock($row->PROJECT_SETUP_ID, $nextMonthValue);
                                      //var_dump($x);
                                  }
                                  if($row->PROJECT_ID==7910 ){
                                  	//showArrayValues($x);
                                  	//var_dump($x);
                                  }
								if($x){
                                    $monthSessionId = $this->getSessionIdByDate(date("Y-m-d", $nextMonthValue));
									if($monthSessionId==$row->TARGET_LOCK_SESSION_ID){


									    if($row->IS_MI==0){
                                            array_push(
                                                $fieldValues,
                                                '"'.addslashes(
                                                    getButton( date("M, y", $nextMonthValue ),
                                                    'lockMonthly('.$row->PROJECT_ID.','.$nextMonthValue.')',
                                                    4, 'cus-lock')
                                                ).'"'
                                            );
                                        }elseif($row->IS_MI==1){
									        array_push(
                                                $fieldValues,
                                                '"'.addslashes(
                                                    getButton( date("M, y", $nextMonthValue ),
                                                    'lockMonthlyMi('.$row->PROJECT_SETUP_ID.','.$nextMonthValue.')',
                                                    4, 'cus-lock')
                                                ).'"'
                                            );
                                        }
									}else{
										array_push(
											$fieldValues, 
											'"Waiting for Target..."'
										);
									}
								}else{

									array_push($fieldValues, '"<span class=\"cus-time\" title=\"Lock Unavailable.1\"></span>"');
								}
                              }
							}else{
								array_push($fieldValues, '"<span class=\"cus-time\" title=\"Lock Unavailable.2x\"></span>"');
							}
						}
                        //if($row->MONTHLY_EXISTS==$row->MONTH_LOCK){
                        $dd = date("Y-m-d", strtotime($row->MONTHLY_EXISTS));
                        /*}else{
                        if($row->MONTHLY_EXISTS>$row->MONTH_LOCK){
                            $dd = date("Y-m-d", strtotime($row->MONTHLY_EXISTS))
                          }else{
                          }*/
                        if($row->IS_MI==0){
                            array_push(
                                $fieldValues,
                                '"'.addslashes($this->getMonthlyProgress($row->PROJECT_ID, $dd)).'"'
                            );
                        }elseif($row->IS_MI==1){
                            array_push(
                                $fieldValues,
                                '"'.addslashes($this->dep_mi__t_monthly->getMonthlyProgress($row->PROJECT_SETUP_ID, $dd)).'"'
                            );
                        }
					}else{
						array_push($fieldValues, '""');
					}
				}else{
					array_push($fieldValues, '""');
				}
				array_push($objFilter->ROWS, '{"id":"'.$row->PROJECT_ID.'", "cell":['. implode(',', $fieldValues).']}');
			}

		}
		echo $objFilter->getJSONCodeByRow();



		//echo $objFilter->getJSONCode('PROJECT_ID', $fields);
		//echo $objFilter->PREPARED_SQL;
	}
	private function getValidEntryMonth()
	{
		date_default_timezone_set('Asia/Kolkata');
		//current day
		//$day = (int)date("j");
		//if day is >10 then
		//if($day>10){
		//	return (date("Y-m").'-01');
		//}else{
		//return date("Y-m", strtotime("last month")) . '-01';
		return
		date("Y-m-d", strtotime("first day of last month"));
		//}
	}
	private function isSessionLocked($dt, $PROJECT_ID)
	{
		$sessionId = $this->getSessionID(date("n", $dt), date("Y", $dt));
		//check target is locked or not
		$arrWhere = array('PROJECT_ID'=>$PROJECT_ID);
		$recs = $this->db->get_where('dep_pmon__t_locks', $arrWhere);
		if($recs && $recs->num_rows()){
			$row = $recs->row(); 
			if($sessionId<=$row->TARGET_LOCK_SESSION_ID)
				return true;
		}
		//return false;
		return true;
	}
	//Show Entry
	private function getAssignedStatus(){
		/*if(!IS_LOCAL_SERVER){
			$this->load->library('mycurl');
			$serverStatus = $this->mycurl->getServerStatus();
			if($serverStatus==0){
				return -1;
			}
		}*/
		$params = array("projectCode"=>$this->PROJECT_ID);
		if(!IS_LOCAL_SERVER){
			$result = $this->mycurl->getAssignedStatus($params);
			//echo $result;
			$obj = json_decode($result);
			//echo $obj->{'success'};
			return $obj->{'success'};
		}
	}
	private function getBlockwiseIP($month, $sessionId){
		$arrData = array();
		$strSQL = 'SELECT m.*, b.BLOCK_NAME, b.BLOCK_NAME_HINDI
			FROM dep_pmon__t_block_monthly_ip AS m
				INNER JOIN __blocks as b ON(m.BLOCK_ID=b.BLOCK_ID)
			WHERE m.PROJECT_ID='.$this->PROJECT_ID.' AND MONTH_DATE="'.$month.'"';
		$recs = $this->db->query($strSQL);
		$isMonthlyExists = FALSE;
		if($recs && $recs->num_rows()){
			$isMonthlyExists = TRUE;
		}else{
			//echo 'ggggggg';
			$strSQL = 'SELECT m.*, b.BLOCK_NAME, b.BLOCK_NAME_HINDI,
				(0) as KHARIF, (0) as RABI, (0) as IP_TOTAL
			FROM deposit__projects_block_served AS m
				INNER JOIN __blocks as b ON(m.BLOCK_ID=b.BLOCK_ID)
			WHERE m.PROJECT_ID='.$this->PROJECT_ID ;
			$recs = $this->db->query($strSQL);
		}
		//
		 //
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrData[$rec->BLOCK_ID] = array(
					'BLOCK_NAME'=>$rec->BLOCK_NAME,
					'BLOCK_NAME_HINDI'=>$rec->BLOCK_NAME_HINDI,
					'CUR_MONTH_IP'=>array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>$rec->IP_TOTAL),
					'PREV_MONTH_IP'=>array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0),
					'ESTIMATION_IP'=>array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0),
					'ACHIEVEMENT_IP_TLY'=>array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0),
					'ACHIEVEMENT_IP_CFY'=>array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0),
					'ACHIEVEMENT_IP'=>array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0)
				);
			}
		}
		//echo count($arrData).'<br />';
		//showArrayValues($arrData);
		//echo 'after';
		// PREV MONTH DATA
		$prevMonth = date("Y-m-d", strtotime("-1month", strtotime($month)));
		if($isMonthlyExists){
			$strSQL = 'SELECT * FROM dep_pmon__t_block_monthly_ip 
				WHERE PROJECT_ID='.$this->PROJECT_ID . 
				' AND MONTH_DATE="'.  $prevMonth .'"';
			$recs = $this->db->query($strSQL);
			if($recs && $recs->num_rows()){
				//showArrayValues($recs->result());
				foreach($recs->result() as $rec){
					$arrData[$rec->BLOCK_ID]['PREV_MONTH_IP'] = array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>$rec->IP_TOTAL);
				}
			}
		}
		//estimation
		$mEFields = array('BLOCK_ID', 'KHARIF', 'RABI', 'IP_TOTAL');
		$setupData = array();
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		//$this->db->order_by('SESSION_ID', 'DESC');
		$this->db->order_by('ID', 'DESC');
		//$this->db->limit(1,0);
        $recs = $this->db->get_where('dep_pmon__t_block_estimated_ip', $arrWhere);
		$isExists = false;
		//echo $this->db->last_query().'<br />';
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrData[$rec->BLOCK_ID]['ESTIMATION_IP'] = array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>$rec->IP_TOTAL);
			}
		}
		//showArrayValues($arrData);
		//achievement in setup session
		$strSQL = 'SELECT aip.KHARIF, aip.RABI, aip.IP_TOTAL, aip.BLOCK_ID, aip.PROJECT_ID 
			FROM dep_pmon__t_block_achievement_ip  as aip
				INNER JOIN dep_pmon__m_project_setup as ps ON(aip.PROJECT_ID=ps.PROJECT_ID AND aip.SESSION_ID<ps.SESSION_ID)
				WHERE aip.PROJECT_ID='.$this->PROJECT_ID .' ORDER BY BLOCK_ID, PROJECT_ID ';
        $recs = $this->db->query($strSQL);
		$isExists = false;
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrData[$rec->BLOCK_ID]['ACHIEVEMENT_IP_TLY'] = array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>$rec->IP_TOTAL);
			}
		}
		//
		//getFirstMonthDate of current session
      	$this->db->select('START_DATE');
      	$recs = $this->db->get_where('__sessions', array('SESSION_ID'=>$sessionId));
      	$startDateOfCurSession = '0000-00-00';
        if($recs && $recs->num_rows()){
			$rec = $recs->row();
          	$startDateOfCurSession = $rec->START_DATE;
        }
		//$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID, 'SESSION_ID'=>$sessionId-1);
		$strSQL = 'SELECT SUM(m.KHARIF) AS KHARIF, SUM(m.RABI)AS RABI, SUM(m.IP_TOTAL) AS IP_TOTAL, m.BLOCK_ID, m.PROJECT_ID 
			FROM dep_pmon__t_block_monthly_ip  as m
				WHERE PROJECT_ID='.$this->PROJECT_ID .
				' AND MONTH_DATE<'.$startDateOfCurSession.
				' GROUP BY BLOCK_ID, PROJECT_ID  
				ORDER BY BLOCK_ID, PROJECT_ID ';
        $recs = $this->db->query($strSQL);
      //if($this->PROJECT_ID==7053) echo  $this->db->last_query();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				if($rec->BLOCK_ID==NULL) continue;
				$arrData[$rec->BLOCK_ID]['ACHIEVEMENT_IP_TLY']['KHARIF'] += $rec->KHARIF;
				$arrData[$rec->BLOCK_ID]['ACHIEVEMENT_IP_TLY']['RABI'] += $rec->RABI;
				$arrData[$rec->BLOCK_ID]['ACHIEVEMENT_IP_TLY']['IP'] += $rec->IP_TOTAL;
			}
		}
		//echo count($arrData).'<br />';
		//showArrayValues($arrData);
		//echo ':after';
		//CFY
	//	if($isMonthlyExists){
			$strSQL = 'SELECT SUM(KHARIF)AS KHARIF, SUM(RABI)AS RABI, SUM(IP_TOTAL)AS IP_TOTAL, BLOCK_ID, PROJECT_ID 
				FROM dep_pmon__t_block_monthly_ip WHERE PROJECT_ID='.$this->PROJECT_ID . 
					' AND MONTH_DATE<="'.  $prevMonth .'" '.
					' GROUP BY BLOCK_ID, PROJECT_ID 
				ORDER BY BLOCK_ID, PROJECT_ID ';
			$recs = $this->db->query($strSQL);
			$isExistsPrevMonths = FALSE;
			// if($this->PROJECT_ID==7053) echo 'ssssssssssss:'.$this->db->last_query();
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					if($rec->BLOCK_ID===NULL){
						continue;
					}else{
						$isExistsPrevMonths = TRUE;
						$arrData[$rec->BLOCK_ID]['ACHIEVEMENT_IP_CFY'] = array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>$rec->IP_TOTAL);
					}
				}
				if(!$isExistsPrevMonths){
					foreach($arrData as $k=>$v){
						$arrData[$k]['ACHIEVEMENT_IP_CFY'] = array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0);
					}
				}
			}
		//}
		//echo 'ssssssssssss:'.$this->db->last_query();
		//showArrayValues($recs->result());
		
		//echo count($arrData).'<br />';
		//showArrayValues($arrData);
		//echo 'after';
		//showArrayValues($arrData);exit;
		return $arrData;
		
	}
	public function showMonthlyStatusForm(){
		//echo "post date = ". date("Y-m-d", 1472668200);
		$bypassEworks = TRUE;
		$this->PROJECT_ID = $this->input->post('PROJECT_ID');
      	if($bypassEworks){
			$assignedStatus = 1;	
        }else{
      		$assignedStatus = true;
        }
        
      	//if($this->PROJECT_ID ==4802) $assignedStatus = 1;
		//XXXX arrBlockData
		//$assignedStatus = 1;
		if($assignedStatus==-1){
			$data = array('PROJECT_NAME'=>'Unable get Assign Status from E-work Server. Try after sometime...');
			$this->load->view('utility/assign_view', $data);
			return;
		}
		if(!$assignedStatus){
			$recs = $this->db->get_where('deposit__projects', array('PROJECT_ID'=>$this->PROJECT_ID));
			if($recs && $recs->num_rows()){
				$rec = $recs->row();
				$data = array('PROJECT_NAME'=>$rec->PROJECT_NAME.'<br /><br />'.$rec->PROJECT_NAME_HINDI);
				$this->load->view('utility/assign_view', $data);
			}
			return;
		}
		
		$entryDate = (int) $this->input->post('date_val');
		//$data['sssss'] = date("Y-m-d", $entryDate);
		$data['setupData'] = $this->getSetupData();
		$data['statusData'] = $this->getStatusData();
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		$data['PROJECT_ID'] = $this->PROJECT_ID;
		
		$data['isEstimationExists'] = TRUE;// $this->isEstimationBlockDataExists();
		//$sessionId = $this->getSessionID($MONTH, $YEAR);
		$YEAR = date("Y", $entryDate);
        $MONTH = date("n", $entryDate);
		$sessionId = $this->getSessionID($MONTH, $YEAR);
		$data['SESSION_ID'] = $sessionId;
		$currentMonth = date("Y-m-d", $entryDate); //$YEAR.'-'. str_pad($MONTH, 2, '0', STR_PAD_LEFT).'-01';
		//
		$mMonthlyFields = $this->getMonthlyFields();
		$currentMonthValues = array();
		for($i=0; $i<count($mMonthlyFields); $i++){
			$currentMonthValues[ $mMonthlyFields[$i] ] = '';
		}
		//$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		$monthlyTable = 'dep_pmon__t_monthlydata';
		$this->db->select('PROJECT_ID');
		$recs = $this->db->get_where($monthlyTable, $arrWhere);
		$monthlyExists = (($recs && $recs->num_rows())?true:false);
		$data['arrBlockData'] = $this->getBlockwiseIP($currentMonth, $sessionId);
      	//if ($this->PROJECT_ID==7053) showArrayValues($data['arrBlockData']);
		//monthly remarks
		$mMonthlyRemarkFields = $this->getMonthlyRemarkFields();
		$currentMonthRemarkValues = array();
		for($i=0; $i<count($mMonthlyRemarkFields); $i++)
			$currentMonthRemarkValues[ $mMonthlyRemarkFields[$i] ] = '';
		//showArrayValues($data['arrBlockData']);
		$arrWhich = array(
			'PROJECT_ID'=>$this->PROJECT_ID,
			'MONTH_DATE'=>$currentMonth
		);
		$recs = $this->db->get_where('dep_pmon__t_monthlydata_remarks', $arrWhich);
		//$data['monthly_remarks'] = FALSE;
        if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mMonthlyRemarkFields); $i++)
				$currentMonthRemarkValues[$mMonthlyRemarkFields[$i]] = $rec->{$mMonthlyRemarkFields[$i]};
		}
		$data['monthly_remarks'] = $currentMonthRemarkValues;
		//monthly project status
		$mMonthlyStatusFields = $this->getMonthlyStatusFields();
		$currentMonthStatusValues = array();
		for($i=0; $i<count($mMonthlyStatusFields); $i++)
			$currentMonthStatusValues[ $mMonthlyStatusFields[$i] ] = '';
		$recs = $this->db->get_where('dep_pmon__m_status_date', $arrWhich);
        if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mMonthlyStatusFields); $i++)
				$currentMonthStatusValues[$mMonthlyStatusFields[$i]] =  $rec->{$mMonthlyStatusFields[$i]};
		}
		$data['monthlyStatusData'] = $currentMonthStatusValues;

		$mAFStatus = array(
			'LA_CASES_STATUS', 'SPILLWAY_STATUS', 'FLANK_STATUS', 'SLUICES_STATUS', 
			'NALLA_CLOSURE_STATUS', 'CANAL_EARTH_WORK_STATUS', 
			'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS'
		);
		$previousMonthValues = array();
		$prevMonthStatus = array();
		$prevMonthExists = false;
		$currentMonthRecordExists = false;
		$projectStatusData = array();
		if($monthlyExists){
			//current month record
			$this->db->select('*');
			$recs = $this->db->get_where($monthlyTable, $arrWhich);
			//echo $this->db->last_query();
			//showArrayValues($recs);
			if($recs && $recs->num_rows()){
				$rec = $recs->row();
				for($i=0; $i<count($mMonthlyFields); $i++){
					$currentMonthValues[$mMonthlyFields[$i]] = $rec->{$mMonthlyFields[$i]};
				}
				$currentMonthRecordExists = true;
			}
			//get previous month
			$prevMonthValue = strtotime("-1month", $entryDate);

			$recs_p = $this->db->get_where(
				$monthlyTable, 
				array(
					'PROJECT_ID'=>$this->PROJECT_ID,
					'MONTH_DATE'=>date("Y-m-d", $prevMonthValue)
				)
			);
			if($recs_p && $recs_p->num_rows()){
				$recp = $recs_p->row();
				for($i=0; $i<count($mMonthlyFields); $i++)
					$previousMonthValues[$mMonthlyFields[$i]] = $recp->{$mMonthlyFields[$i]};
				for($j=0; $j<count($mAFStatus);$j++)
					$prevMonthStatus[ $mAFStatus[$j] ] = $recp->{$mAFStatus[$j]};
				$prevMonthExists = true;
			}
		}
		if(!$prevMonthExists){
			for($i=0; $i<count($mMonthlyFields); $i++)
				$previousMonthValues[$mMonthlyFields[$i]] = '';
			//get status from project setup
			$recs_p = $this->db->get_where('dep_pmon__m_setup_status' , $arrWhere);

			if($recs_p && $recs_p->num_rows()){
				$recp = $recs_p->row();
				for($j=0; $j<count($mAFStatus);$j++)
					$prevMonthStatus[ $mAFStatus[$j] ] = $recp->{$mAFStatus[$j]};
			}
		}
		$data['currentMonthRecordExists'] = $currentMonthRecordExists;
		$data['currentMonthRecord'] = $currentMonthValues;
		$data['previousMonthRecord'] = $previousMonthValues;
		$data['prevMonthStatus'] = $prevMonthStatus;
		$data['estimationRecord'] = $this->getEstimationData($currentMonth);
		//showArrayValues($previousMonthValues);
		//showArrayValues($currentMonthValues);
		//showArrayValues($prevMonthStatus);
		//showArrayValues($currentMonthValues);
		
		$arrFields = array(
			'SESSION_ID', 'PROJECT_ID', 
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORKS',
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA',
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 'CANAL_MASONRY', 'ROAD_WORKS',  
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 'IRRIGATION_POTENTIAL'
		);
		$currentFinancialMonth = $this->getFinancialMonthByMonth($MONTH);
		$totalInCurrentFY = array();
		$achievementTillLastFY = array();
		//init
		for($i=0; $i<count($arrFields);$i++){
			$totalInCurrentFY[ $arrFields[$i] ] = 0;
			$achievementTillLastFY[ $arrFields[$i] ] = 0;
		}
		$totalInCurrentFY['SESSION_ID'] = $sessionId;
		$totalInCurrentFY['PROJECT_ID'] = $this->PROJECT_ID;
		$achievementTillLastFY['SESSION_ID'] = $sessionId;
		$achievementTillLastFY['PROJECT_ID'] = $this->PROJECT_ID;
		if($currentFinancialMonth!=0){
			//get total in this financial year
			$strSQL = 'SELECT PROJECT_ID, 
				SUM(EXPENDITURE_TOTAL) AS EXPENDITURE_TOTAL, 
				SUM(EXPENDITURE_WORKS) AS EXPENDITURE_WORKS, 
				SUM(LA_NO) AS LA_NO, 
				SUM(LA_HA) AS LA_HA, 
				SUM(LA_COMPLETED_NO) AS LA_COMPLETED_NO,
				SUM(LA_COMPLETED_HA) AS LA_COMPLETED_HA,
				SUM(FA_HA) AS FA_HA, 
				SUM(FA_COMPLETED_HA) AS FA_COMPLETED_HA,
				SUM(HEAD_WORKS_EARTHWORK) AS HEAD_WORKS_EARTHWORK, 
				SUM(HEAD_WORKS_MASONRY) AS HEAD_WORKS_MASONRY, 
				SUM(STEEL_WORKS) AS STEEL_WORKS, 
				SUM(CANAL_EARTHWORK) AS CANAL_EARTHWORK, 
				SUM(CANAL_LINING) AS CANAL_LINING, 
				SUM(CANAL_STRUCTURES) AS CANAL_STRUCTURES,
				SUM(CANAL_MASONRY) AS CANAL_MASONRY,
				SUM(ROAD_WORKS) AS ROAD_WORKS,
				SUM(IRRIGATION_POTENTIAL_KHARIF) AS IRRIGATION_POTENTIAL_KHARIF,
				SUM(IRRIGATION_POTENTIAL_RABI) AS IRRIGATION_POTENTIAL_RABI,
				SUM(IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL
				FROM dep_pmon__t_monthlydata 
					WHERE PROJECT_ID = '.$this->PROJECT_ID.' 
					AND SESSION_ID = '.$sessionId.' 
					AND FINANCIAL_MONTH < '.$currentFinancialMonth.' 
					GROUP BY PROJECT_ID';
			//SUM(CANAL_MASONRY) AS CANAL_MASONRY,
			$recs = $this->db->query($strSQL);
			if($recs && $recs->num_rows()){
				$rec = $recs->row();
				for($i=0; $i<count($arrFields);$i++)
					if( !($arrFields[$i]=='SESSION_ID' || $arrFields[$i]=='PROJECT_ID'))
						$totalInCurrentFY[ $arrFields[$i] ] = $rec->{$arrFields[$i]};
				$totalInCurrentFY['SESSION_ID'] = $sessionId;
				$totalInCurrentFY['PROJECT_ID'] = $this->PROJECT_ID;
			}
			//echo $this->db->last_query();
		}
		$data['totalInCurrentFY'] = $totalInCurrentFY;
		
		//GET DATA TILL LAST FINANCIAL YEAR
		$strSQL = 'SELECT PROJECT_ID, 
			SUM(EXPENDITURE_TOTAL) AS EXPENDITURE_TOTAL, 
			SUM(EXPENDITURE_WORKS) AS EXPENDITURE_WORKS, 
			SUM(LA_NO) AS LA_NO, 
			SUM(LA_HA) AS LA_HA, 
			SUM(LA_COMPLETED_NO) AS LA_COMPLETED_NO, 
			SUM(LA_COMPLETED_HA) AS LA_COMPLETED_HA, 
			SUM(FA_HA) AS FA_HA, 
			SUM(FA_COMPLETED_HA) AS FA_COMPLETED_HA, 
			SUM(HEAD_WORKS_EARTHWORK) AS HEAD_WORKS_EARTHWORK, 
			SUM(HEAD_WORKS_MASONRY) AS HEAD_WORKS_MASONRY, 
			SUM(STEEL_WORKS) AS STEEL_WORKS, 
			SUM(CANAL_EARTHWORK) AS CANAL_EARTHWORK, 
			SUM(CANAL_LINING) AS CANAL_LINING, 
			SUM(CANAL_STRUCTURES) AS CANAL_STRUCTURES,
			SUM(CANAL_MASONRY) AS CANAL_MASONRY,
			SUM(ROAD_WORKS) AS ROAD_WORKS,
			SUM(IRRIGATION_POTENTIAL_KHARIF) AS IRRIGATION_POTENTIAL_KHARIF,
			SUM(IRRIGATION_POTENTIAL_RABI) AS IRRIGATION_POTENTIAL_RABI,
			SUM(IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL
			FROM dep_pmon__t_achievements 
				WHERE PROJECT_ID = '.$this->PROJECT_ID.' 
				AND SESSION_ID < '.$sessionId.' 
			GROUP BY PROJECT_ID';
		//SUM(CANAL_MASONRY) AS CANAL_MASONRY,
		$recs = $this->db->query($strSQL);
		//showArrayValues($rec);
		$isExists = false;
		if($recs && $recs->num_rows()){
			$isExists = true;
			$rec = $recs->row();
			//showArrayValues($rec);
			for($i=0; $i<count($arrFields); $i++)
				if($arrFields[$i]!='SESSION_ID')
					$achievementTillLastFY[$arrFields[$i]] = $rec->{ $arrFields[$i] };
		}
		if(!$isExists){
			for($i=0; $i<count($arrFields); $i++)
				if($arrFields[$i]!='SESSION_ID')
					$achievementTillLastFY[$arrFields[$i]] = 0;
		}
		$data['achievementTillLastFY'] = $achievementTillLastFY;
		//showArrayValues($achievementTillLastFY);
		$arrFieldsForProgress = array(
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES', 'CANAL_MASONRY','ROAD_WORKS',
			'EXPENDITURE_TOTAL', 'FA_COMPLETED_HA',
			'EXPENDITURE_WORK', 'LA_NO', 'LA_HA', 'FA_HA',
			'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'IRRIGATION_POTENTIAL_KHARIF','IRRIGATION_POTENTIAL_RABI','IRRIGATION_POTENTIAL'
			//,'CANAL_MASONRY'
		);
		$arrEstimation = array();
		//init
		for($iCount=0; $iCount<count($arrFieldsForProgress);$iCount++)
			$arrEstimation[ $arrFieldsForProgress[$iCount] ] = 0;
		//ESTIMATION_DATA [start]
		//CANAL_MASONRY, 
		$this->db->select('HEAD_WORKS_EARTHWORK, HEAD_WORKS_MASONRY, 
			CANAL_EARTHWORK, CANAL_LINING, CANAL_STRUCTURES, STEEL_WORKS, CANAL_MASONRY, ROAD_WORKS,
			FA_COMPLETED_HA, EXPENDITURE_TOTAL, EXPENDITURE_WORK, 
			LA_NO, LA_HA, LA_COMPLETED_NO, LA_COMPLETED_HA, FA_HA, 
			IRRIGATION_POTENTIAL, IRRIGATION_POTENTIAL_KHARIF, IRRIGATION_POTENTIAL_RABI')
			->where_in('PROJECT_ID', $this->PROJECT_ID);
		$this->db->order_by('ESTIMATED_QTY_ID', 'DESC');
		$this->db->limit(1,0);
		$recs = $this->db->get('dep_pmon__t_estimated_qty');
		//echo $this->db->last_query();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($iCount=0; $iCount<count($arrFieldsForProgress);$iCount++)
				$arrEstimation[ $arrFieldsForProgress[$iCount] ] = $rec->{$arrFieldsForProgress[$iCount]};
		}
		//echo $this->db->last_query();
		$data['ESTIMATION_DATA'] = $arrEstimation;
		$arrWhich = array(
			'PROJECT_ID'=>$this->PROJECT_ID,
			'YEARLY_TARGET_DATE'=>$currentMonth
		);
		$data['TARGET_FLAG'] = 0;	
		$data['BUDGET_AMOUNT'] = 0;
		$data['SUBMISSION_DATE'] = '';
		$recs = $this->db->get_where('dep_pmon__t_yearlytargets', $arrWhich);
		//echo $this->db->last_query();
		//showArrayValues($rec);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$data['BUDGET_AMOUNT'] = $rec->BUDGET_AMOUNT;
			$data['SUBMISSION_DATE'] = $rec->SUBMISSION_DATE;
			$data['TARGET_FLAG'] = 1;	
		}
		//get actual completion date
		$data['ACTUAL_COMPLETION_DATE'] = '0000-00-00';
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		$this->db->select('PROJECT_COMPLETION_DATE, PROJECT_START_DATE');
		$recs = $this->db->get_where('dep_pmon__m_project_setup', $arrWhere);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$data['ACTUAL_COMPLETION_DATE'] = $rec->PROJECT_COMPLETION_DATE;
			$data['PROJECT_START_DATE'] = $rec->PROJECT_START_DATE;
		}
		$data['ACTUAL_COMPLETION_DATE'] = $this->getCompletionDate($this->PROJECT_ID);
		//get project name
		$this->db->select('PROJECT_NAME, PROJECT_CODE, PROJECT_NAME_HINDI');
		$recs = $this->db->get_where('deposit__projects', $arrWhere);
		if($recs && $recs->num_rows()){
			$prec = $recs->row();
			$data['PROJECT_NAME'] = $prec->PROJECT_NAME .' - '.$prec->PROJECT_NAME_HINDI;
			$data['PROJECT_CODE']= $prec->PROJECT_CODE;
		}		
		//$this->getMonthlyStatus($entryDate);
		$data['MONTH_DATE'] = $entryDate;
		//$data['MONTH_DATE1'] = date("Y-m-d", $entryDate);
		//showArrayValues($data);
		$data['buttons'] = $this->createButtonSet($entryDate);
      	//if($this->PROJECT_ID==4546){   showArrayValues($data);exit;} \


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

		 //showArrayValues($data);
		 //exit;
		//  echo "test" ; exit;
		$eworkData = $this->getEWorksDetailsForMonthly($this->PROJECT_ID);

		//showArrayValues($eworkData); //exit;
		/*$eworkData = array(
			'ddocode'=>$data['PROJECT_CODE'],
			'head'=>$data['PROJECT_CODE'],
			'promon_id'=>$data['PROJECT_CODE'],
			'mode'=>'DepositWorkBalance'
		);*/
		//$result = 0;
		//Get project status from epay
		$result = $this->mycurl->getDepositProjectStatus($eworkData);
		$jsonVal = json_decode($result , true);

		/**
		 * 8-7-2024, Check status of final Payment in eworks
		 */
		// $curlParams = array("mode" => "PMON_AGR_FINAL_BILL_CHK",  "Ddocode" => $eworkData['ddocode'] , "PromonID"=> $eworkData['promon_id'] ,"projectCode" =>'');
		$curlParams = array("mode" => "PMON_AGR_FINAL_BILL_CHK",  "Ddocode" => $eworkData['ddocode'] , "PromonID"=> $eworkData['promon_id'] ,"projectCode" =>'');
		// showArrayValues($curlParams); exit;
		$paymentStatusJson = $this->mycurl->savePromonData($curlParams);
		//echo '----->'. $paymentStatusJson; exit;
		$paymentStatusArr = json_decode($paymentStatusJson , true);
		//showArrayValues($paymentStatusArr); exit;
		$data['EWORK_PAYMENT_STATUS'] = $paymentStatusArr['success'];
		//echo 'ework payment status = '. $data['EWORK_PAYMENT_STATUS'];
		// $data['EWORK_PAYMENT_STATUS'] = 1; // @todo: this line for testing ; remove after testing
		//$paymentStatusArr['success'] ==1 ; Some agreement Final Bill Pending
		//$paymentStatusArr['success'] ==0 ; No Pending Final Bill of Agreement in Work

		//showArrayValues($jsonVal); exit;
		//$data['EWORK_PROJECT_STATUS'] = $jsonVal['success'];
    
    	//$byPassPrjId = array(7616, 7573, 7618, 7678, 7680, 7615,  7701, 7711, 7699, 7574, 7591, 7729, 7673, 7672, 7527, 7667, 7592, 7717, 7727, 7670, 7676, 7700,7674,7612,7576);
    	//$byPassPrjId = array(7961, 8101, 8102, 8103, 8104, 8105, 8106); //7900, 7987, 7943, 7988, 8003 // commented on 03/05/2023
    	//if($this->PROJECT_ID == 7616){
    
    	$byPassPrjId = array(7961, 8101, 8102, 8103, 8104, 8105, 8106); 
    
    	//more list added on 03/05/2024 for eemrpdd2rdr, id - 26
    	$byPassPrjId[]= 7945;
    	$byPassPrjId[]= 7946;
    	$byPassPrjId[]= 8090;
    	$byPassPrjId[]= 8040;
    	$byPassPrjId[]= 8044;    
    	$byPassPrjId[]= 8043;
    	//--------------------
    	
    	 if( in_array($this->PROJECT_ID,$byPassPrjId ) ){
        
            $data['EWORK_PROJECT_STATUS'] =0;
        }else{   
    		$data['EWORK_PROJECT_STATUS'] = $jsonVal['success'];
			if ($jsonVal['success'] ==2 ) {
				echo "<h2 style='color:#ff0000;'>चयनित परियोजना के मद में epay एवं MIS सर्वर में भिन्नता पाई गयी है , कृपया जांच कर डाटा सेन्टर को सूचित करें। </h2>"; 
				exit;
			}
    	}
		// [ CODE END ]
		$this->load->view('pmon_deposit/monthly_data_view', $data);
	}

	public function showMonthlyStatusFormMi(){
        require('mi/Monthly_mi_c.php');
        $monthly_mi_c = new Monthly_mi_c();
        $monthly_mi_c->showMonthlyStatusForm();
    }	//by showMonthlyStatusForm()
	private function getEstimationData($currentMonth){
		$this->db->order_by('RAA_DATE', 'DESC');
		$this->db->where('PROJECT_ID', $this->PROJECT_ID)
			->where('RAA_DATE <', $currentMonth);
		$this->db->limit(1,0);
		$raaId = 0;
        $recs = $this->db->get('dep_pmon__t_raa_project');
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$raaId = $rec->RAA_PROJECT_ID;
		}
		$mFields = array(
			'ESTIMATED_QTY_ID', 'PROJECT_ID', 'RAA_ID', 'SESSION_ID', 
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 
			'FA_HA', 'FA_COMPLETED_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 'CANAL_MASONRY', 'ROAD_WORKS',  
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 'IRRIGATION_POTENTIAL', 
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK'
			//,'CANAL_MASONRY'
		);
		$setupData = array();
		$this->db->where('PROJECT_ID', $this->PROJECT_ID);
		if($raaId){
			$this->db->where('RAA_ID', $raaId);
		}else{
			$this->db->order_by('SESSION_ID', 'DESC');
			$this->db->order_by('ESTIMATED_QTY_ID', 'DESC');
		}
		$this->db->limit(1,0);
        $recs = $this->db->get('dep_pmon__t_estimated_qty');
		$isExists = false;
		if($recs && $recs->num_rows()){
			$isExists = true;
			$rec = $recs->row();
			for($i=0; $i<count($mFields); $i++){
				$setupData[ $mFields[$i] ] = $rec->{ $mFields[$i] };
			}
		}
		if(!$isExists){
			for($i=0; $i<count($mFields); $i++)
				$setupData[ $mFields[$i] ] = 0;
		}
		return $setupData;
	}
	private function isEstimationBlockDataExists(){
		$this->db->where('PROJECT_ID', $this->PROJECT_ID);
        $recs = $this->db->get('dep_pmon__t_block_estimated_ip');
		return ($recs && $recs->num_rows());
	}
	private function getSetupData(){
		$mFields = array(
			'LA_NA', 'FA_NA', 
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 'STEEL_WORKS_NA', 
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA', 'CANAL_LINING_NA', 
			'ROAD_WORKS_NA', 'IRRIGATION_POTENTIAL_NA'
			//,'CANAL_MASONRY_NA'
		);
		$setupData = array();
        $recs = $this->db->get_where('dep_pmon__t_estimated_status', array('PROJECT_ID'=>$this->PROJECT_ID));
		$isExists = false;
		if($recs && $recs->num_rows()){
			$isExists = true;
			$rec = $recs->row();
			for($i=0; $i<count($mFields); $i++){
				$setupData[ $mFields[$i] ] = $rec->{ $mFields[$i] };
			}
		}
		if(!$isExists){
			for($i=0; $i<count($mFields); $i++)
				$setupData[ $mFields[$i] ] = 0;
		}
		return $setupData;
	}
	private function getStatusData(){
		$mFields = array(
			'LA_CASES_STATUS', 'CANAL_LINING_STATUS', 
			'CANAL_STRUCTURE_STATUS', 'CANAL_EARTH_WORK_STATUS', 
			'NALLA_CLOSURE_STATUS', 'SLUICES_STATUS', 
			'FLANK_STATUS', 'SPILLWAY_STATUS'
		);
		$setupData = array();
        $recs = $this->db->get_where(
			'dep_pmon__t_achievements', 
			array('PROJECT_ID'=>$this->PROJECT_ID)
		);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mFields); $i++){
				$setupData[ $mFields[$i] ] = $rec->{ $mFields[$i] };
			}
		}
		return $setupData;
	}
	private function createButtonSet($entryDate){
		$arrButtons = array();
		//echo $this->session->userData('MONTHLY_MODULE_KEY');
		$permissions = $this->getPermissions();
		//showArrayValues($permissions);
		if($permissions['SAVE'] ){
			array_push(
				$arrButtons,
				getButton('Save', "saveMonthly()", 4, 'cus-disk')
			);
		}
		array_push(
			$arrButtons,
			getButton('Close', 'closeMonthly();', 4, 'cus-cross')
		);
		return implode('&nbsp;', $arrButtons);
	}
	private function saveBlockIPData($month, $arrIPs, $ipNA){
		//monthly IP blockwise
		$tableName = 'dep_pmon__t_block_monthly_ip';
		if($ipNA){
			//delete existing records
			if($isExists){
				$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
				@$this->db->delete($tableName, $arrWhere);
			}
		}else{
			$arrBlockIds = array();
			$sessionId = getSessionIdByDate($month);
			$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID, 'MONTH_DATE'=>$month);
			$this->db->select('BLOCK_ID, ID');
			$recs = $this->db->get_where($tableName, $arrWhere);
			$isExists = false;
			if($recs && $recs->num_rows()){
				$isExists = true;
				foreach($recs->result() as $rec){
					$arrBlockIds[$rec->BLOCK_ID] = $rec->ID;
				}
				$recs->free_result();
			}
			//showArrayValues($arrIPs);
			$arrInsertData = $arrUpdateData = array();
			foreach($arrIPs as $arrIP){
				if(array_key_exists($arrIP['BLOCK_ID'], $arrBlockIds)){
					array_push(
						$arrUpdateData,
						array(
							'ID'=>$arrBlockIds[$arrIP['BLOCK_ID']], 
							'KHARIF'=>$arrIP['KHARIF'], 
							'RABI'=>$arrIP['RABI']
						)
					);
				}else{
					$arrD = array_merge(
						$arrWhere, 
						array(
							'SESSION_ID' => $sessionId,
							'BLOCK_ID' => $arrIP['BLOCK_ID'],
							'KHARIF' => $arrIP['KHARIF'], 
							'RABI' => $arrIP['RABI']
						)
					);
					array_push($arrInsertData, $arrD);
				}
			}//FOREACH
			if($arrUpdateData){
				@$this->db->update_batch($tableName, $arrUpdateData, 'ID');
			}
			if($arrInsertData){
				@$this->db->insert_batch($tableName, $arrInsertData);
			}
			//$arrData = - PROJECT_MODE, ENTRY_MODE, MONTH_DATE, PROJECT_ID, PROJECT_SETUP_ID
			$arrData = array(
				'PROJECT_MODE'=>'DEP_PMON', 'ENTRY_MODE'=>'MONTHLY', 'MONTH_DATE'=>$month, 'NA'=>$ipNA,
				'PROJECT_ID'=>$this->PROJECT_ID, 'PROJECT_SETUP_ID'=>0
			);
			$this->load->library('MyIrrigationLedger');
			$this->myirrigationledger->updateCreationLedger($arrData, $arrIPs);
			/////////////
			//showArrayValues($arrIPs);
		}
		//calculate cummulative
		//$this->setCummulativeIP();
    	//$this->setCummulativeIPForAllProjects($this->PROJECT_ID);
	}
	private function saveEstiBlockIPData($arrIPs, $ipNA){
		//Estimation IP blockwise
		$tableName = 'dep_pmon__t_block_estimated_ip';
		$arrBlockIds = array();
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		$recs = $this->db->get_where($tableName, $arrWhere);
		$isExists = false;
		if($recs && $recs->num_rows()){
			$isExists = true;
			foreach($recs->result() as $rec){
				array_push($arrBlockIds, $rec->BLOCK_ID);
			}
		}
		if($ipNA){
			//delete existing records
			if($isExists){
				$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
				@$this->db->delete($tableName, $arrWhere);
			}
		}else{
			//showArrayValues($arrEstiIPs);
			foreach($arrIPs as $arrIP){
				$arrWhich = array_merge($arrWhere, array('BLOCK_ID'=>$arrIP['BLOCK_ID']));
				$data = array('KHARIF'=>$arrIP['KHARIF'], 'RABI'=>$arrIP['RABI'], 'IP_TOTAL'=>$arrIP['TOTAL']);

				if(in_array($arrIP['BLOCK_ID'], $arrBlockIds)){
					@$this->db->update($tableName, $data, $arrWhich);
				}else{
					$data = array_merge($data, $arrWhich);
					@$this->db->insert($tableName, $data);
					//echo $this->db->last_query();
				}
			}
		}
	}
  	private function setCummulativeIP(){
		//get all blocks benefited
		$arrBlockIds = $this->getBlockIds($this->PROJECT_ID);
		//save monthly data BLOCKWISE
		$arrFields = array('KHARIF', 'RABI', 'IP_TOTAL');
		$arrSubTotalBlock = array();
		$arrAchieveDataBlock = array();
		/*foreach($arrFields as $arrField){
			${$arrField} = $this->input->post($arrField);
			$arrSubTotalBlock[$arrField] = 0;
			//$arrAchieveDataBlock[$arrField] = 0;
		}*/
		foreach($arrBlockIds as $bid){
			foreach($arrFields as $arrField){
				$arrSubTotalBlock['b'.$bid][$arrField] = 0;
			}
		}
		//$arrAchieveData['IRRIGATION_POTENTIAL'] = 0;
		//$arrSubTotal['IRRIGATION_POTENTIAL_T']=0;
		//get achievement data for setup session
		$strSQL = 'SELECT a.* FROM dep_pmon__t_block_achievement_ip AS a
				INNER JOIN dep_pmon__m_project_setup AS p 
					ON (a.PROJECT_ID=p.PROJECT_ID AND a.SESSION_ID<p.SESSION_ID )
			WHERE a.PROJECT_ID = '.$this->PROJECT_ID. ' ORDER BY BLOCK_ID';
		$recs = $this->db->query($strSQL);
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				foreach($arrFields as $arrField){
					$arrSubTotalBlock['b'.$rec->BLOCK_ID][$arrField] = $rec->{$arrField};
				}
			}
		}
		/*echo '0000';
		showArrayValues($arrSubTotalBlock);
		echo '222';*/
		//
		$arrB = array();
		$arrBlockMonthlyIds = array();
		$arrBlockMonthlyDatas = array();
		//$this->db->select('ID, BLOCK_ID');
		$this->db->order_by('BLOCK_ID', 'ASC');
		$this->db->order_by('MONTH_DATE', 'ASC');
		$recs = $this->db->get_where('dep_pmon__t_block_monthly_ip', array('PROJECT_ID'=>$this->PROJECT_ID));
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrB[$rec->ID] = $rec->BLOCK_ID;
				array_push($arrBlockMonthlyIds, $rec->ID);
				$arrBlockMonthlyDatas[$rec->ID] = $rec;
			}
		}
		//$arrBlockMonthlyIds = $this->input->post('BLOCK_MONTHLY_DATA_ID');
		/*echo '1111';
		showArrayValues($arrB);
		echo '222';
		showArrayValues($arrBlockMonthlyIds);
		echo '333';*/
		$datas = array();
		foreach($arrBlockMonthlyIds as $id){
			$data = array('ID'=>$id);
			$blockId = 'b'.$arrB[$id];
			foreach($arrFields as $arrField){
				$data[$arrField] = $arrBlockMonthlyDatas[$id]->{$arrField};
				$arrSubTotalBlock[$blockId][$arrField] += $data[$arrField];
			}
			//showArrayValues($arrSubTotalBlock);
			$data['KHARIF_T'] = $arrSubTotalBlock[$blockId]['KHARIF'];
			$data['RABI_T'] = $arrSubTotalBlock[$blockId]['RABI'];
			$data['IP_TOTAL_T'] =$data['KHARIF_T']+$data['RABI_T'];
			$data['IP_TOTAL'] =$data['KHARIF']+$data['RABI'];
			//$arrT = array_merge($data, $arrSubTotalBlock);
			array_push($datas, $data);
		}
		//showArrayValues($datas);
    	if($datas){
          @$this->db->update_batch('dep_pmon__t_block_monthly_ip', $datas, 'ID');
         // echo $this->db->last_query();
          //showArrayValues($datas);
          if ($this->db->affected_rows()){
              //echo ' <span class="cus-tick"></span> '.$this->db->affected_rows().' Monthly Block Data Updated...';
          }else{
              //echo '<span class="cus-error"></span> No Updatable or Unable to update Monthly Block Data ...';
          }
        }
	}
	
	//Save Entry
	public function saveMonthlyData(){
		date_default_timezone_set('Asia/Kolkata');
		$this->PROJECT_ID = $this->input->post('PROJECT_ID');
		$sessionId = $this->input->post('SESSION_ID');
		$entryDate = $this->input->post('MONTH_DATE');
		$monthlyDataRecordId = (int) $this->input->post('MONTHLY_DATA_ID');
		$setupData = $this->getSetupData();
		$arrEntryDate = array(
			'date'=>date("Y-m-d", $entryDate),
			'datevalue'=>$entryDate,
			'month'=>date("n", $entryDate),
			'year'=>date("Y", $entryDate),
			'FINANCIAL_MONTH' => $this->getConvertToFinancialMonth(date("n", $entryDate))
		);
		$arrBlockIds = array();
		$arrBlockDatas = array();
		$arrEBlockDatas = array();
		if(!$setupData['IRRIGATION_POTENTIAL_NA']) {
			$arrBlockIds = $this->getBlockIds($this->PROJECT_ID);
			$arrBlockDataK = $this->input->post('BLOCK_IP_K');
			$arrBlockDataR = $this->input->post('BLOCK_IP_R');
			$isEstimationExists = $this->isEstimationBlockDataExists();
			if(!$isEstimationExists){
				$arrEBlockDataK = $this->input->post('BLOCK_EIP_K');
				$arrEBlockDataR = $this->input->post('BLOCK_EIP_R');
			}
			foreach($arrBlockIds as $blockid){
				$arrBlockData = array('BLOCK_ID'=>0, 'KHARIF'=>0, 'RABI'=>0, 'TOTAL'=>0);
				$arrBlockData['BLOCK_ID'] = $blockid;
				$arrBlockData['KHARIF'] = $arrBlockDataK[$blockid];
				$arrBlockData['RABI'] = $arrBlockDataR[$blockid];
				$arrBlockData['TOTAL'] = $arrBlockData['KHARIF'] + $arrBlockData['RABI'];
				array_push($arrBlockDatas, $arrBlockData);
				//estimation
				if(!$isEstimationExists){
					$arrBlockData = array('BLOCK_ID'=>0, 'KHARIF'=>0, 'RABI'=>0, 'TOTAL'=>0);
					$arrBlockData['BLOCK_ID'] = $blockid;
					$arrBlockData['KHARIF'] = $arrEBlockDataK[$blockid];
					$arrBlockData['RABI'] = $arrEBlockDataR[$blockid];
					$arrBlockData['TOTAL'] = $arrBlockData['KHARIF'] + $arrBlockData['RABI'];
					array_push($arrEBlockDatas, $arrBlockData);
				}
			}
		}
		//showArrayValues($arrBlockDatas);
		//showArrayValues($arrEBlockDatas);
		$projectStatus = (int) $this->input->post('PROJECT_STATUS');
		$newCompletionDate = "0000-00-00";
		//if ($projectStatus==5)//"Completed"
		if ($this->input->post("COMPLETION_DATE"))
			$newCompletionDate = myDateFormat($this->input->post("COMPLETION_DATE"));
		//monthly
		$arrData = array(
			'ALLOCATED_BUDGET' => ((float)$this->input->post("ALLOCATED_BUDGET")),
			'PROJECT_STATUS' =>$projectStatus
		);
		//monthly data
		$arrMonthlyFields = $this->getMonthlyFields();
		$arrMonthlyData = array();
		$arrTotalMonthlyData = array();
		$arrDataForProgress = array();
		$arrHeadings = array(
			'HEAD_WORKS_EARTHWORK_T', 'HEAD_WORKS_MASONRY_T', 'STEEL_WORKS_T',
			'CANAL_EARTHWORK_T', 'CANAL_LINING_T', 'CANAL_STRUCTURES_T', 'CANAL_MASONRY_T', 'ROAD_WORKS_T',
			'IRRIGATION_POTENTIAL_KHARIF_T', 'IRRIGATION_POTENTIAL_RABI_T', 'IRRIGATION_POTENTIAL_T'
		);
		if(!$monthlyDataRecordId){
			$arrMonthlyData =  array(
				'MONTH_DATE'=>$arrEntryDate['date'], 
				'ENTRY_MONTH'=>$arrEntryDate['month'], 
				'FINANCIAL_MONTH'=>$arrEntryDate['FINANCIAL_MONTH'], 
				'ENTRY_YEAR'=>$arrEntryDate['year']
			);
		}
		$arrD = array('MONTH_DATE', 'ENTRY_MONTH', 'FINANCIAL_MONTH', 'ENTRY_YEAR');
		for($i=0; $i<count($arrMonthlyFields); $i++){
			if( $arrMonthlyFields[$i]=='MONTHLY_DATA_ID'){
				//skip
			}else if( in_array($arrMonthlyFields[$i], $arrD)){
				//skip
			}else if($arrMonthlyFields[$i]=='COMPLETION_DATE'){
				$arrMonthlyData['COMPLETION_DATE'] = $newCompletionDate;
			}else{
				$arrMonthlyData[$arrMonthlyFields[$i]] = $this->input->post($arrMonthlyFields[$i]);
			}
			if ( in_array($arrMonthlyFields[$i], $arrHeadings) ){
				$arrDataForProgress[ $arrMonthlyFields[$i]] = $arrMonthlyData[$arrMonthlyFields[$i]];
			}
		}
		foreach($arrData as $k=>$v){
			$arrMonthlyData[$k] = $v;
		}
		//save monthly
		$arrWhere = array(
			'PROJECT_ID'=>$this->PROJECT_ID, 
			'MONTH_DATE'=>$arrEntryDate['date']
		);
		//$monthlyRecordID = 0;
		$monthlyTableName = 'dep_pmon__t_monthlydata';
		$isMonthlyExists = false;

		$recs = $this->db->get_where($monthlyTableName, $arrWhere);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$isMonthlyExists = true;
			$goAheadForAchievementData = true;
			//$monthlyRecordID = $rec->MONTHLY_DATA_ID;
		}
		$arrStatusFields = array(
			'LA_CASES_STATUS', 'SPILLWAY_STATUS', 
			'FLANK_STATUS', 'SLUICES_STATUS', 'NALLA_CLOSURE_STATUS', 
			'CANAL_EARTH_WORK_STATUS', 'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS'
		);
		//loop through status
		switch($projectStatus){
			case 3: //ongoing
			case 5: //completed
				$allStatus = 0; break;
			case 2: //not started
				$allStatus = 2; break;
			case 4: //stopped
				$allStatus = 4; break;
			case 6: //dropped
				$allStatus = 1; break;
		}
		for($i=0; $i<count($arrStatusFields); $i++){
			if($allStatus==0){
				//nothing to do
				if($arrMonthlyData[$arrStatusFields[$i]]==0)
					$arrMonthlyData[$arrStatusFields[$i]]=1;//NA
			}else{
				//if hidden
				if($arrMonthlyData[$arrStatusFields[$i]]==0){
					$arrMonthlyData[$arrStatusFields[$i]]=1;//NA
				}else{
					$pcStatus = $this->input->post($arrStatusFields[$i]);
					if($pcStatus){
						if(in_array($pcStatus, array(5, 2))){//completed, notstarted
							$arrMonthlyData[$arrStatusFields[$i]] = $pcStatus;		
						}else{
							$arrMonthlyData[$arrStatusFields[$i]] = $allStatus;
						}
					}
				}
			}
		}
		//showArrayValues($arrMonthlyData);

		//set NA field data
		foreach($setupData as $k=>$v){
			if($v==1){
				if($k=='LA_NA'){
					$arrMonthlyData['LA_NO'] = 0;
					$arrMonthlyData['LA_HA'] = 0;
					$arrMonthlyData['LA_COMPLETED_NO'] = 0;
					$arrMonthlyData['LA_COMPLETED_HA'] = 0;
					$arrMonthlyData['LA_NO_T'] = 0;
					$arrMonthlyData['LA_HA_T'] = 0;
					$arrMonthlyData['LA_COMPLETED_NO_T'] = 0;
					$arrMonthlyData['LA_COMPLETED_HA_T'] = 0;
				}else if($k=='FA_NA'){
					$arrMonthlyData['FA_HA'] = 0;
					$arrMonthlyData['FA_COMPLETED_HA'] = 0;
					$arrMonthlyData['FA_HA_T'] = 0;
					$arrMonthlyData['FA_COMPLETED_HA_T'] = 0;
					
				}else if($k=='CANAL_STRUCTURES_NA'){
					$arrMonthlyData['CANAL_STRUCTURES'] = 0;
					$arrMonthlyData['CANAL_MASONRY'] = 0;
					$arrMonthlyData['CANAL_STRUCTURES_T'] = 0;
					$arrMonthlyData['CANAL_MASONRY_T'] = 0;
				}else if($k=='IRRIGATION_POTENTIAL_NA'){
					$arrMonthlyData['IRRIGATION_POTENTIAL_KHARIF'] = 0;
					$arrMonthlyData['IRRIGATION_POTENTIAL_RABI'] = 0;
					$arrMonthlyData['IRRIGATION_POTENTIAL'] = 0;
					$arrMonthlyData['IRRIGATION_POTENTIAL_KHARIF_T'] = 0;
					$arrMonthlyData['IRRIGATION_POTENTIAL_RABI_T'] = 0;
					$arrMonthlyData['IRRIGATION_POTENTIAL_T'] = 0;
				}else{
					$arrMonthlyData[ str_replace('_NA', '', $k) ] = 0;
					$arrMonthlyData[ str_replace('_NA', '_T', $k) ] = 0;
				}
			}
		}
		//showArrayValues($arrMonthlyData);
		$arrMonthlyData['LOCKED'] = 0;
		$arrMonthlyData['IRRIGATION_POTENTIAL'] = $arrMonthlyData['IRRIGATION_POTENTIAL_KHARIF'] + 
					$arrMonthlyData['IRRIGATION_POTENTIAL_RABI'];
		if($isMonthlyExists){
			@$this->db->update($monthlyTableName, $arrMonthlyData, $arrWhere);
		}else{
			$arrMonthlyData['SUBMISSION_DATE'] = date('Y-m-d');
			@$this->db->insert($monthlyTableName, $arrMonthlyData);
		}
		if($this->db->affected_rows()){
			if($isMonthlyExists){
				array_push($this->message, getMyArray(true, 'Monthly Data Succsessfully Updated'));
			}else{
				$monthlyDataRecordId = $this->db->insert_id();
				array_push($this->message, getMyArray(true, 'Monthly Data Succsessfully Created...'));
			}
		}else{
			if($isMonthlyExists){
				array_push($this->message, getMyArray(false, 'Monthly Data Not Updated or No Updatable Data Found...'));
			}else{
				array_push($this->message, getMyArray(false, 'Unable to create Monthly Data...'));
			}
		}
		//Save monthly blockwise data
		$this->saveEstiBlockIPData($arrEBlockDatas, $setupData['IRRIGATION_POTENTIAL_NA']);
		$this->saveBlockIPData($arrEntryDate['date'], $arrBlockDatas, $setupData['IRRIGATION_POTENTIAL_NA']);
		

		if($monthlyDataRecordId){
			$result = $this->saveAchievementData($sessionId, $this->PROJECT_ID);
			if($result){
				array_push($this->message, getMyArray(true, 'Achievement Data Succsessfully Updated...'));
			}else{
				array_push($this->message, getMyArray(false, 'Achievement Data Not Updated or No Updatable Data...'));
			}
		}
		//Update Project Master Status If Project Completed
		if($monthlyDataRecordId){
			//if completed or dropped
			if($projectStatus==5 || $projectStatus==6){
				//monthly project status
				$sdata = array(
					'PROJECT_STATUS'=>$projectStatus,
					'PROJECT_STATUS_DATE'=>$newCompletionDate, 
					'PROJECT_STATUS_DISPATCH_NO'=>$this->input->post("PROJECT_STATUS_DISPATCH_NO"), 
					'LA_PAYMENT' =>0,
					'FA_PAYMENT' =>0,
					'CL_PAYMENT' =>0
				);
				if($projectStatus==5){
					$sdata['COMPLETION_TYPE'] = $this->input->post("COMPLETION_TYPE");
					if($sdata['COMPLETION_TYPE']==2){
						$sdata['LA_PAYMENT'] = (int) $this->input->post("LA_PAYMENT");
						$sdata['FA_PAYMENT'] = (int) $this->input->post("FA_PAYMENT");
						$sdata['CL_PAYMENT'] = (int) $this->input->post("CL_PAYMENT");
					}
				}
				$recs = $this->db->get_where('dep_pmon__m_status_date', 
					array(
						'PROJECT_ID'=>$this->PROJECT_ID,
						'MONTH_DATE'=>$arrEntryDate['date']
					)
				);
				$recordId = 0;
				if($recs && ($recs->num_rows())){
					$rec = $recs->row();
					$recordId = $rec->ID;
				}
				if($recordId){
					@$this->db->update('dep_pmon__m_status_date', $sdata, array('ID'=>$recordId));
				}else{
					$sdata['PROJECT_ID'] = $this->PROJECT_ID;
					$sdata['SESSION_ID'] = $sessionId;
					$sdata['MONTH_DATE'] = $arrEntryDate['date'];
					@$this->db->insert('dep_pmon__m_status_date', $sdata);
				}
				//if monthly record exists after current month then delete it
				$arrWhichDelete = array(
					'PROJECT_ID'=>$this->PROJECT_ID,
					'MONTH_DATE >'=>$arrEntryDate['date']
				);
				$arrTables = array($monthlyTableName, 'dep_pmon__m_status_date', 'dep_pmon__t_monthlydata_remarks');
				@$this->db->delete($arrTables, $arrWhichDelete);
			}
			$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
			$pdata = array('PROJECT_STATUS'=>$projectStatus);
			//"Completed"
			if($projectStatus==5) 
				$pdata['COMPLETION_SESSION_ID'] =  $sessionId;
			
			@$this->db->update('deposit__projects', $pdata, $arrWhich);
			if($this->db->affected_rows()){
				array_push($this->message, getMyArray(true, 'Projects Master Status Updated...'));
			}else{
				array_push($this->message, getMyArray(false, 'Projects Master Status Not Updated!'));
			}//if
			//SAVE LATEST ENTRY IN LOCK TABLE
			$Lrecs = $this->db->get_where('dep_pmon__t_locks', $arrWhich);
			if($Lrecs && $Lrecs->num_rows()){
				$Lrec = $Lrecs->row();
				if($arrEntryDate['date']>$Lrec->MONTHLY_EXISTS){
					@$this->db->update(
						'dep_pmon__t_locks', 
						array('MONTHLY_EXISTS'=>$arrEntryDate['date']), 
						$arrWhich
					);
				}
			}
			///////////////////////////////////////////////////////////
			//save remarks
			$remarkTable = 'dep_pmon__t_monthlydata_remarks';
			$arrRemarkFields = $this->getMonthlyRemarkFields();
			$arrRemarkData = array();//'PROJECT_ID'=>$this->PROJECT_ID, 'MONTH_DATE'=>$arrMonthlyData['MONTH_DATE']);
			$arrF = array(
				'PROJECT_STATUS', 'LA_CASES_STATUS', 'SPILLWAY_STATUS', 
				'FLANK_STATUS', 'SLUICES_STATUS', 'NALLA_CLOSURE_STATUS', 
				'CANAL_EARTH_WORK_STATUS', 'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS'
			);
			for($i=0; $i<count($arrF); $i++){
				if($arrF[$i]=='PROJECT_STATUS'){
					if($projectStatus==3){
						if( $this->input->post('PROJECT_STATUS_REMARK')!=''){
							$arrRemarkData['PROJECT_STATUS_REMARK'] = $this->input->post('PROJECT_STATUS_REMARK');
						}
					}
					if ($projectStatus == 2 || $projectStatus == 4 || $projectStatus == 5 || $projectStatus == 3 || $projectStatus == 6) {
						$arrRemarkData['PROJECT_STATUS_REMARK'] = $this->input->post('PROJECT_STATUS_REMARK');
					}
				}else{
					if($arrMonthlyData[$arrF[$i]]==2 || $arrMonthlyData[$arrF[$i]]==4){
						$arrRemarkData[$arrF[$i].'_REMARK'] = $this->input->post($arrF[$i].'_REMARK');
					}
				}
			}
			$noData = false;
			if(count($arrRemarkData)==0)	$noData = true;
			$remWhere = array('PROJECT_ID'=>$this->PROJECT_ID, 'MONTH_DATE'=>$arrEntryDate['date']);
			$arrRemarkData['PROJECT_ID']=$this->PROJECT_ID;
			$arrRemarkData['MONTH_DATE'] = $arrEntryDate['date'];
			$remRec = $this->db->get_where($remarkTable, $remWhere);
			//showArrayValues($arrRemarkData);
			$remExists = ($remRec && $remRec->num_rows());
			if($remExists){
				if($noData){
					//delete rec
					@$this->db->delete($remarkTable, $remWhere);
					if($this->db->affected_rows()){
						array_push($this->message, getMyArray(true, 'Status Remark Deleted...'));
					}else{
						array_push($this->message, getMyArray(false, 'Status Remark Not Deleted!'));
					}//if
				}else{
					//update
					@$this->db->update($remarkTable, $arrRemarkData, $remWhere);
					if($this->db->affected_rows()){
						array_push($this->message, getMyArray(true, 'Status Remark Updated...'));
					}else{
						array_push($this->message, getMyArray(false, 'Status Remark Not Updated!'));
					}//if
				}
			}else{
				if(!$noData){
					//insert
					@$this->db->insert($remarkTable, $arrRemarkData);
					if($this->db->affected_rows()){
						array_push($this->message, getMyArray(true, 'Status Remark Created...'));
					}else{
						array_push($this->message, getMyArray(false, 'Status Remark Not Created!'));
					}//if
				}
			}
			//Save Monthly Progress
			$arrEntryDate['projectId'] = $this->PROJECT_ID;
			$arrEntryDate['sessionId'] = $sessionId;
			$arrEntryDate['moduleType'] = 'promon_deposit';
			$arrEntryDate['progressType'] = 'monthly';
			$this->load->library('myoverallprogress');
			$messages = $this->myoverallprogress->prepareForProgress($arrEntryDate);
			$this->message = array_merge($this->message, $messages);
		}
		echo createJSONResponse($this->message);
	}
	public function saveMonthlyDataMi(){
        require('mi/Monthly_mi_c.php');
        $monthly_mi_c = new Monthly_mi_c();
        $monthly_mi_c->saveMonthlyData();
	}
	private function saveAchievementData($SESSION_ID, $PROJECT_ID){
		$strSQL = 'SELECT PROJECT_ID FROM dep_pmon__t_achievements 
				WHERE SESSION_ID = '.$SESSION_ID.' AND PROJECT_ID = '.$PROJECT_ID;
		$result = $this->db->query($strSQL);
		$goAhead = false;
		//sum(CANAL_MASONRY) AS CANAL_MASONRY,
		if ($result->num_rows() > 0){//UPDATE
			$strSQL = "UPDATE dep_pmon__t_achievements as ach 
				LEFT JOIN (
					(SELECT
						PROJECT_ID,
						sum(EXPENDITURE_TOTAL) AS EXPENDITURE_TOTAL,
						sum(EXPENDITURE_WORKS) AS EXPENDITURE_WORKS,
						sum(LA_NO) AS LA_NO,					
						sum(LA_HA) AS LA_HA,
						SUM(LA_COMPLETED_NO) AS LA_COMPLETED_NO,
						SUM(LA_COMPLETED_HA) AS LA_COMPLETED_HA,		
						sum(FA_HA) AS FA_HA,
						SUM(FA_COMPLETED_HA) AS FA_COMPLETED_HA,	
						sum(HEAD_WORKS_EARTHWORK) AS HEAD_WORKS_EARTHWORK,
						sum(HEAD_WORKS_MASONRY) AS HEAD_WORKS_MASONRY,
						sum(STEEL_WORKS) AS STEEL_WORKS,
						sum(CANAL_EARTHWORK) AS CANAL_EARTHWORK,
						sum(CANAL_LINING) AS CANAL_LINING,
						sum(CANAL_STRUCTURES) AS CANAL_STRUCTURES,
						sum(CANAL_MASONRY) AS CANAL_MASONRY,
						sum(ROAD_WORKS) AS ROAD_WORKS,
						sum(IRRIGATION_POTENTIAL_KHARIF) AS IRRIGATION_POTENTIAL_KHARIF,
						sum(IRRIGATION_POTENTIAL_RABI) AS IRRIGATION_POTENTIAL_RABI,
						sum(IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL
						FROM dep_pmon__t_monthlydata 
						WHERE SESSION_ID = ".$SESSION_ID."			
						AND PROJECT_ID = ".$PROJECT_ID."
					) as mnth
				) ON  mnth.PROJECT_ID = ach.PROJECT_ID
			SET ach.EXPENDITURE_TOTAL = mnth.EXPENDITURE_TOTAL,
				ach.EXPENDITURE_WORKS = mnth.EXPENDITURE_WORKS,
				ach.LA_NO = mnth.LA_NO,
				ach.LA_HA = mnth.LA_HA,
				ach.LA_COMPLETED_NO = mnth.LA_COMPLETED_NO,
				ach.LA_COMPLETED_HA = mnth.LA_COMPLETED_HA,
				ach.FA_HA = mnth.FA_HA,
				ach.FA_COMPLETED_HA = mnth.FA_COMPLETED_HA,
				ach.HEAD_WORKS_EARTHWORK = mnth.HEAD_WORKS_EARTHWORK,
				ach.HEAD_WORKS_MASONRY = mnth.HEAD_WORKS_MASONRY,
				ach.STEEL_WORKS = mnth.STEEL_WORKS,
				ach.CANAL_EARTHWORK = mnth.CANAL_EARTHWORK,
				ach.CANAL_STRUCTURES = mnth.CANAL_STRUCTURES,
				ach.CANAL_LINING = mnth.CANAL_LINING,
				ach.CANAL_MASONRY = mnth.CANAL_MASONRY,
				ach.ROAD_WORKS = mnth.ROAD_WORKS,
				ach.IRRIGATION_POTENTIAL_KHARIF = mnth.IRRIGATION_POTENTIAL_KHARIF,
				ach.IRRIGATION_POTENTIAL_RABI = mnth.IRRIGATION_POTENTIAL_RABI,
				ach.IRRIGATION_POTENTIAL = mnth.IRRIGATION_POTENTIAL
			WHERE ach.PROJECT_ID = ".$PROJECT_ID." AND ach.SESSION_ID = ".$SESSION_ID;
			//ach.CANAL_MASONRY = mnth.CANAL_MASONRY,
			$result = $this->db->query($strSQL);
			// echo $this->db->last_query();
			// Update Achievement Status
			if( $this->db->affected_rows()>0 ){
				$goAhead = true;
			}
		}else{
			//INSERT
			//CANAL_MASONRY, 
			$strSQL = "INSERT dep_pmon__t_achievements (
					EXPENDITURE_TOTAL, EXPENDITURE_WORKS, 
					LA_NO, LA_HA, LA_COMPLETED_NO, LA_COMPLETED_HA,
					FA_HA, FA_COMPLETED_HA, HEAD_WORKS_EARTHWORK, 
					HEAD_WORKS_MASONRY, STEEL_WORKS, 
					CANAL_EARTHWORK, CANAL_LINING, CANAL_STRUCTURES, 
					CANAL_MASONRY, ROAD_WORKS,
					IRRIGATION_POTENTIAL_KHARIF,
					IRRIGATION_POTENTIAL_RABI,
					IRRIGATION_POTENTIAL,
					PROJECT_ID, SESSION_ID ) 
				SELECT	sum(EXPENDITURE_TOTAL) AS EXPENDITURE_TOTAL,
						sum(EXPENDITURE_WORKS) AS EXPENDITURE_WORKS,
						sum(LA_NO) AS LA_NO,					
						sum(LA_HA) AS LA_HA,
						SUM(LA_COMPLETED_NO) AS LA_COMPLETED_NO,
						SUM(LA_COMPLETED_HA) AS LA_COMPLETED_HA,		
						sum(FA_HA) AS FA_HA,
						SUM(FA_COMPLETED_HA) AS FA_COMPLETED_HA,	
						sum(HEAD_WORKS_EARTHWORK) AS HEAD_WORKS_EARTHWORK,
						sum(HEAD_WORKS_MASONRY) AS HEAD_WORKS_MASONRY,
						sum(STEEL_WORKS) AS STEEL_WORKS,
						sum(CANAL_EARTHWORK) AS CANAL_EARTHWORK,
						sum(CANAL_LINING) AS CANAL_LINING,
						sum(CANAL_STRUCTURES) AS CANAL_STRUCTURES,
						sum(CANAL_MASONRY) AS CANAL_MASONRY,
						sum(ROAD_WORKS) AS ROAD_WORKS,
						sum(IRRIGATION_POTENTIAL_KHARIF) AS IRRIGATION_POTENTIAL_KHARIF,
						sum(IRRIGATION_POTENTIAL_RABI) AS IRRIGATION_POTENTIAL_RABI,
						sum(IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL,
						(".$PROJECT_ID.") AS PROJECT_ID,
						(".$SESSION_ID.") AS SESSION_ID
					FROM  dep_pmon__t_monthlydata 
						WHERE SESSION_ID = ".$SESSION_ID."			
						AND PROJECT_ID = ".$PROJECT_ID;
			//sum(CANAL_MASONRY) AS CANAL_MASONRY,
			$result = $this->db->query($strSQL);
			if( $this->db->affected_rows()>0 ){
				$goAhead = true;
				return TRUE;
			}else{
				return FALSE;
			}
		}
		if($goAhead){
			$this->updateAchievementStatus($SESSION_ID, $PROJECT_ID);
			return true;
		}
		return false;
	}
	private function updateAchievementStatus($SESSION_ID, $PROJECT_ID){
		$strSQL = "UPDATE dep_pmon__t_achievements as ach 
			INNER JOIN (
				(SELECT	PROJECT_ID, LA_CASES_STATUS,
						SPILLWAY_STATUS, FLANK_STATUS,
						SLUICES_STATUS, NALLA_CLOSURE_STATUS,
						CANAL_EARTH_WORK_STATUS, CANAL_STRUCTURE_STATUS,
						CANAL_LINING_STATUS
					FROM  dep_pmon__t_monthlydata
					WHERE SESSION_ID = ".$SESSION_ID." 
					AND PROJECT_ID = ".$PROJECT_ID."
					AND FINANCIAL_MONTH = (
						SELECT MAX(FINANCIAL_MONTH) AS FINANCIAL_MONTH FROM dep_pmon__t_monthlydata 
							WHERE SESSION_ID =  ".$SESSION_ID."  AND PROJECT_ID = ".$PROJECT_ID."
						)
				)as mnth
			) ON mnth.PROJECT_ID = ach.PROJECT_ID
			SET ach.LA_CASES_STATUS = mnth.LA_CASES_STATUS,
				ach.SPILLWAY_STATUS = mnth.SPILLWAY_STATUS,	
				ach.FLANK_STATUS = mnth.FLANK_STATUS,	
				ach.SLUICES_STATUS = mnth.SLUICES_STATUS,	
				ach.NALLA_CLOSURE_STATUS = mnth.NALLA_CLOSURE_STATUS,	
				ach.CANAL_EARTH_WORK_STATUS = mnth.CANAL_EARTH_WORK_STATUS,	
				ach.CANAL_STRUCTURE_STATUS = mnth.CANAL_STRUCTURE_STATUS,	
				ach.CANAL_LINING_STATUS  = mnth.CANAL_LINING_STATUS
			WHERE ach.SESSION_ID = ".$SESSION_ID." 		
				AND ach.PROJECT_ID = ".$PROJECT_ID;
		$result = $this->db->query($strSQL);
		// echo $this->db->last_query();
		return  ( ( $this->db->affected_rows()>0 )? TRUE:FALSE );
	}
	private function getMonthlyFields(){
		return array(
			'MONTHLY_DATA_ID', 'SESSION_ID', 'ENTRY_MONTH', 'PROJECT_ID',
			'PROJECT_STATUS', 'EXPENDITURE_TOTAL', 'EXPENDITURE_WORKS',
			'LA_NO', 'LA_HA', 'FA_HA', 'FA_COMPLETED_HA',
			'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES', 'CANAL_MASONRY', 'ROAD_WORKS',  
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 'IRRIGATION_POTENTIAL',
			
			'LA_CASES_STATUS', 'SPILLWAY_STATUS', 'FLANK_STATUS',
			'SLUICES_STATUS', 'NALLA_CLOSURE_STATUS', 'CANAL_EARTH_WORK_STATUS', 
			'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS',
			
			'ENTRY_YEAR', 'LOCKED', 'COMPLETION_DATE',
			'FINANCIAL_MONTH',
			'LA_NO_T', 'LA_HA_T', 'FA_HA_T', 'FA_COMPLETED_HA_T',
			'LA_COMPLETED_NO_T', 'LA_COMPLETED_HA_T',
			'HEAD_WORKS_EARTHWORK_T', 'HEAD_WORKS_MASONRY_T', 'STEEL_WORKS_T',
			'CANAL_EARTHWORK_T', 'CANAL_LINING_T', 'CANAL_STRUCTURES_T', 'CANAL_MASONRY_T', 'ROAD_WORKS_T', 
			'IRRIGATION_POTENTIAL_KHARIF_T', 'IRRIGATION_POTENTIAL_RABI_T',	'IRRIGATION_POTENTIAL_T'
		);
	}
	private function getMonthlyRemarkFields(){
		return array(
			'ID', 'PROJECT_ID', 'MONTH_DATE', 'PROJECT_STATUS_REMARK',
			'LA_CASES_STATUS_REMARK', 'SPILLWAY_STATUS_REMARK', 'FLANK_STATUS_REMARK',
			'SLUICES_STATUS_REMARK', 'NALLA_CLOSURE_STATUS_REMARK', 'CANAL_EARTH_WORK_STATUS_REMARK', 
			'CANAL_STRUCTURE_STATUS_REMARK', 'CANAL_LINING_STATUS_REMARK'
		);
	}
	private function getMonthlyStatusFields(){
		return array(
			'ID', 'PROJECT_ID', 'PROJECT_STATUS', 
			'PROJECT_STATUS_DATE', 'PROJECT_STATUS_DISPATCH_NO', 
			'MONTH_DATE', 'COMPLETION_TYPE', 
			'LA_PAYMENT', 'FA_PAYMENT', 'CL_PAYMENT'
		);
	}
	//
	//by getProjectStartDate(), getMonthlyStatus()
	private function getSessionID($month, $year=0){
		$s = '';
		$one_month = $month;
		$month = (int) $month;
		$one_year = $year;
		if($year==0){
			$year = date('Y');
		}
		$month = $month-3;
		$session = $year."-".($year+1);
		if($month<=0){
			$month = $month+12;
			$session = ($year-1)."-".($year);
		}
		$session_ar = explode("-", $session);
		$recs = $this->db->get_where('__sessions', array(
			'SESSION_START_YEAR'=>$session_ar['0'],
			'SESSION_END_YEAR'=>$session_ar['1']
			)
		);
		$s = 0;
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$s = $rec->SESSION_ID;
		}
		return $s;
	}
	/**ok ON 07-11-2013 CALLED BY getMonthlyStatus()*/
	private function checkMonthlyLockStatus($PROJECT_ID, $date){
		$rec = $this->db->get_where('dep_pmon__t_locks', array('PROJECT_ID' => $PROJECT_ID));
		if($rec && $rec->num_rows()){
			$row = $rec->row(); 
			if($row->MONTH_LOCK<$date){
				return 0;
			}
			return 1;
		}
		return 0;
	}
	/** OK on 07-11-2013 CALLED BY getMonthlyStatus() */
	private function getFinancialMonthByMonth($month){
		return (($month>=4 and $month<=12)? ($month-3):($month+9));
	}
	//by getProjectStartDate()
	private function getSessionData($id=0){
        $recs = $this->db->get_where('__sessions', array('SESSION_ID'=>$id));
		$mfield = array('START_DATE', 'END_DATE', 'SESSION_START_YEAR', 'SESSION_END_YEAR');
		$data = array();
        if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mfield);$i++){
				 $data[ $mfield [$i]] = $rec->{$mfield [$i]};
			}
        }
		return $data;
	}
	//Save Entry
	private function saveIrrigationPotential($lockMonth){
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID, 'MONTH_DATE'=>$lockMonth);
		$data = array(
			'DI_KARIF_CROP'=>0,
			'DI_RABI_CROP'=>0,
			'DI_TOTAL'=>0
		);
		//get irr data
		$recs = $this->db->get_where('dep_pmon__t_monthlydata', $arrWhere);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$data = array(
				'DI_KARIF_CROP'=>$rec->IRRIGATION_POTENTIAL_KHARIF_T,
				'DI_RABI_CROP'=>$rec->IRRIGATION_POTENTIAL_RABI_T,
				'DI_TOTAL'=>$rec->IRRIGATION_POTENTIAL_T
			);
		}
		//1.Irrigation Potential Created
		$recs = $this->db->get_where('deposit__agriculture', $arrWhich);
		$isExists = ($recs && $recs->num_rows());
		if($isExists){
			//finally update general data
			@$this->db->update('deposit__agriculture', $data, $arrWhich);
		}else{
			$data['PROJECT_ID'] = $this->PROJECT_ID;
			@$this->db->insert('deposit__agriculture', $data);
		}
		//save general data
		$arrData = array(
			'CREATED_IRRIGATION_POTENTIAL' => $data['DI_TOTAL'],
			'CREATED_IRRIGATION_POTENTIAL_KHARIF' => $data['DI_KARIF_CROP'],
			'CREATED_IRRIGATION_POTENTIAL_RABI' => $data['DI_RABI_CROP']
		);
		$recs = $this->db->get_where('deposit__general_data', $arrWhich);
		if($recs && $recs->num_rows()){
			@$this->db->update('deposit__general_data', $arrData, $arrWhich);
		}else{
          	$arrData['PROJECT_ID'] = $this->PROJECT_ID;
          	@$this->db->insert('deposit__general_data', $arrData);
        }
		if($this->db->affected_rows()){
			echo 'Irrigation Potential Created Data Succsessfully Updated...';
		}else{
			echo 'Irrigation Potential Created Data Not Updated or No Updatable Data...';
		}
	}
	/** Ok on 08-11-2013 called by saveMonthlyData(), showTargetForm()*/
	private function getConvertToFinancialMonth($month){
		return (($month>=4 and $month<=12)? ($month-3):($month+9));
	}
	//************** Target
	public function showTargetForm(){
        $this->PROJECT_ID = $this->input->post('PROJECT_ID');
        $sessionId = $this->input->post('session_id');
        if($sessionId==0){
            $MONTH = date('n');
            $YEAR = date('Y');
            $sessionId = PMON_START_SESSION_ID;
        }
		$data = array(
			'PROJECT_ID'=> $this->PROJECT_ID,
			'BUDGET_AMOUNT' => '',
			'SUBMISSION_DATE' => '',
			'session_id' => $sessionId,
			'session_year' => $this->getSession($sessionId)
		);
		//$sessionId = PMON_START_SESSION_ID;
		$currentSessionId = $this->session->userdata('CURRENT_SESSION_ID');
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
		//Lock Record
		$targetLocked = FALSE;
		$recs = $this->db->get_where('dep_pmon__t_locks', $arrWhere);
		$entryStartMonth = '';
		if($recs && $recs->num_rows()){
			$row = $recs->row();
			$lockedMonth = date("n", strtotime($row->MONTH_LOCK));
			$lockedFinancialMonth = $this->getFinancialMonthByMonth($lockedMonth);
			//echo ':::.'.$lockedFinancialMonth.':::';
				//nothing editable
			$entryStartMonth = (($lockedFinancialMonth==12) ?  0 : ($lockedFinancialMonth + 1));
			if($row->TARGET_LOCK_SESSION_ID==$sessionId){
				$targetLocked = TRUE;//locked
			}
		}
		//
		$this->db->select('PROJECT_NAME, PROJECT_CODE');
		$recsProject = $this->db->get_where('deposit__projects', $arrWhere);
		if($recsProject && $recsProject->num_rows()){
			$recProject = $recsProject->row();
			$data['PROJECT_NAME'] = $recProject->PROJECT_NAME;
			$data['PROJECT_CODE'] = $recProject->PROJECT_CODE;
		}
		// Get AA AMOUNT to Compare with Target should no excessed 
		//if RAA exists then compare RAA Amount with Target
		$recsAAAmount = $this->db->get_where('dep_pmon__m_project_setup', $arrWhere);
		$projectCompletion = array('date'=>'', 'month'=>0, 'year'=>0, 'session'=>0);
		$projectStart = array('date'=>'', 'month'=>0, 'year'=>0, 'session'=>0);
		if($recsAAAmount && $recsAAAmount->num_rows()){
			$recAAAmount = $recsAAAmount->row();
			$data['AA_AMOUNT'] = $recAAAmount->AA_AMOUNT;
			$data['AA_RAA'] = 'AA';
			$projectStart = $this->getDateDetail($recAAAmount->PROJECT_START_DATE);
			$projectCompletion = $this->getDateDetail( $recAAAmount->PROJECT_COMPLETION_DATE);
			$strSQL = "SELECT * FROM dep_pmon__t_raa_project 
				WHERE PROJECT_ID=$this->PROJECT_ID 
				ORDER BY RAA_PROJECT_ID DESC LIMIT 0, 1";
			$ress = $this->db->query($strSQL);
			if($ress && $ress->num_rows()){
				$rrec = $ress->row();
				$data['AA_AMOUNT'] = $rrec->RAA_AMOUNT;
				$data['AA_RAA'] = 'RAA';
			}
		}
		//
		$data['setupData'] = $this->getSetupData();
		$data['SESSION_LIST'] = $this->getSessionOptions($projectCompletion['session'], $sessionId);
		//check start date is in current session then show after start month only
		//if project start in session
		$data['startRealStartMonth'] = 0;
		$data['startSession'] = $projectStart['session'];
		$data['endSession'] = $projectCompletion['session'];

		$data['startMonth'] = $entryStartMonth;
		$data['startRealStartMonth'] = $data['startMonth'];
		$data['startMonthExists'] = 1;
		
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
					$data['startMonthExists'] = $rec->FINANCIAL_MONTH;
					$i++;
				}
				$targetData[$rec->FINANCIAL_MONTH] = $rec;
			}	
		}
		$data['targetData'] = $targetData;
		$data['buttons'] = $this->createTargetButtonSet($data['session_id']);
		$data['entrymode'] = 'monthly';
        $this->load->view('pmon_deposit/target_data_view', $data);
    }
	private function getSession($session_id=0){
		$recs = $this->db->get_where('__sessions', array('SESSION_ID'=>$session_id));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_START_YEAR.'-'.$rec->SESSION_END_YEAR;
		}
		return '';
	}
	private function createTargetButtonSet($sessionId){
		$arrButtons = array();
		array_push(
			$arrButtons,
			getButton('Save', "saveTarget()", 4, 'cus-disk')
		);
		//check permission
		/*$permissions = $this->getPermissions($this->session->userData('TARGET_MODULE_KEY'));
		if($permissions['SAVE_LOCK']==1){
			//echo "Yes";
			$lockPass = false;
			//check lock buttons
			$lockData = $this->isValidForLock($sessionId);
			if($lockData){
				array_push(
					$arrButtons,
					getButton('Lock', 'lockProject();', 4, 'cus-lock')
				);
			}
		}*/
		array_push(
			$arrButtons,
			getButton('Close', 'closeMyDialog(\'modalBox\')', 4, 'cus-cross')
		);
		return implode('&nbsp;', $arrButtons);
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
	private function getFinancialMonth($month){
		$num = (($month>=4 and $month<=12)? ($month-3):($month+9));
		$arrNum = array();
		for($i=1;$i<$num; $i++){
			array_push($arrNum, $i);
		}
		//return implode(',', $arrNum);
		return $arrNum;
	}
	private function getFinancialYear($month, $year=0){
		$s = '';
		$month = (int) $month;
		$one_month = $month;
		$one_year = $year;
		if($year==0){
			$year = date('Y');
		}
		$month = $month-3;
		$session = $year."-".($year+1);
		if($month<=0){
			$month = $month+12;
			$session = ($year-1)."-".($year);
		}
	   $session_ar= explode("-",$session);
	   
	   $qry = "SELECT SESSION_ID FROM __sessions 
				WHERE SESSION_START_YEAR = '".$session_ar['0']."' AND 
					SESSION_END_YEAR ='".$session_ar['1']."'";
		$recs = $this->db->query($qry);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$s = $rec->SESSION_ID;
		}
		return $s;
	}
	/** OK on 06-11-2013 Called by showTargetForm()*/
	private function getSessionOptions($SessionProjComp, $session_id=0){
		$vlist = array();
		array_push($vlist, '<option value="0">Select Session</option>');
		$this->db->order_by('SESSION_ID', 'ASC');
		$this->db->where('SESSION_ID <=', ((int) $SessionProjComp));
		$this->db->where('SESSION_ID >=', PMON_START_SESSION_ID);
		$recs = $this->db->get('__sessions', 'ASC');
		if($recs && $recs->num_rows()){ 
			foreach($recs->result() as $rec){
				array_push($vlist, 
					'<option value="'.$rec->SESSION_ID.'" '. 
					( ($session_id==$rec->SESSION_ID)? 'selected="selected"':'').'>'.
					$rec->SESSION_START_YEAR.'-'.$rec->SESSION_END_YEAR.'</option>'
				);
			}
		}
		return implode('', $vlist);
	}
	private function getYearlyTargetFields(){
		return array(
			'YEARLY_TARGET_ID', 'SESSION_ID', 'TARGET_MONTH', 'PROJECTCODE', 
			'BUDGET_AMOUNT', 'EXPENDITURE', 
			'LA_NO', 'LA_HA', 'FA_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES', 'CANAL_MASONRY',
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 'IRRIGATION_POTENTIAL', 
			'SUBMISSION_DATE', 'LOCKED', 'TARGET_YEAR', 
			'LA_NO_CT', 'LA_HA_CT', 'FA_HA_CT', 
			'HEAD_WORKS_EARTHWORK_CT', 'HEAD_WORKS_MASONRY_CT', 'STEEL_WORKS_CT', 
			'CANAL_EARTHWORK_CT', 'CANAL_LINING_CT', 'CANAL_STRUCTURES_CT', 'CANAL_MASONRY_T',
			'EXPENDITURE_CT',
			'IRRIGATION_POTENTIAL_KHARIF_CT', 'IRRIGATION_POTENTIAL_RABI_CT', 'IRRIGATION_POTENTIAL_CT'
		);
	}
	//Save Target Entry
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
        $arrCanalMasonry = $this->input->post('CANAL_MASONRY');
        $arrRoadWorks = $this->input->post('ROAD_WORKS');
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
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 'ROAD_WORKS', 
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI', 'IRRIGATION_POTENTIAL'
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
			//showArrayValues($arrWhere);
			$recs = $this->db->get_where($targetTable, $arrWhere);
			$isExists = ($recs && $recs->num_rows());
			//echo ' isExists:'. $isExists.'<br/>';
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
				'CANAL_MASONRY'=>0,
				'ROAD_WORKS'=>0,
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
			if(!$setupData['CANAL_STRUCTURES_NA']){
				$inputArray['CANAL_STRUCTURES']=$arrCanalStructure[$i];
				$inputArray['CANAL_MASONRY'] = $arrCanalMasonry[$i];
			}
			if(!$setupData['ROAD_WORKS_NA'])
				$inputArray['ROAD_WORKS'] = $arrRoadWorks[$i];
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
		//correctMonthly Data if session = PMON_START_SESSION_ID 
		if($sessionId==PMON_START_SESSION_ID){
			
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
	/** OK on 06-11-2013 Called by saveTarget() */
	private function getYearBySessionMonth($sessionID=0, $mMonth=0){
		$this->db->select('SESSION_START_YEAR, SESSION_END_YEAR');
		$this->db->where_in('SESSION_ID', $sessionID);
		$recs = $this->db->get('__sessions');
		//echo $this->db->last_query();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return array($rec->SESSION_START_YEAR, $rec->SESSION_END_YEAR);
		}
		return array(0,0);
	}
	protected function getFields($table){
		$strSQL = 'SHOW COLUMNS FROM '.$table;
		$recs = $this->db->query($strSQL);
		$arrNames = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push($arrNames, $rec->Field);
			}
		}
		return $arrNames;
//		return array('EMPLOYEE_ID', 'EDUCATION', 'BOARD_UNIVERSITY', 'MARK_OBTAINED', 'PASSING_YEAR', 'STREAM', 'GRADE', 'TYPE');
	}
	//*********************************
	public function getPermissions(){
		//$arrTargetKeys = array('', 'PMON_MINOR_PROJECT_TARGET', 'PMON_MEDIUM_PROJECT_TARGET', 'PMON_MAJOR_PROJECT_TARGET');
		$arrMonthlyKeys = array('', 'DEP_PMON_PROJECT_MONTHLY', 'PMON_MEDIUM_PROJECT_MONTHLY', 'PMON_MAJOR_PROJECT_MONTHLY');
		$key = $arrMonthlyKeys[ $this->session->userData('MONTHLY_PROJECT_TYPE_ID') ];
		return getAccessPermissions($key, $this->session->userData('USER_ID'));
	}
	protected function getCompletionDate($projectId){
		$this->db->order_by('ID', 'DESC');
		$this->db->limit(1, 0);
		$recs = $this->db->get_where('dep_pmon__t_extensions', array('PROJECT_ID'=>$projectId));
		$isExists = false;
		if($recs && $recs->num_rows()){
			$isExists = true;
			$rec = $recs->row();
			return $rec->NEW_COMPLETION_DATE;
		}
		if(!$isExists){
			$recs = $this->db->get_where('dep_pmon__v_projectlist_with_lock', array('PROJECT_ID'=>$projectId));
			if($recs && $recs->num_rows()){
				$rec = $recs->row();
				return $rec->PROJECT_COMPLETION_DATE;
			}
		}
		return '';
	}
	private function readyToLock($projectId, $dt)
	{
		$curDtValue = strtotime("now");
		//$lockStartDate = date("Y-m", strtotime("+1month", $dt)).'-09';
		//$lockStartDateValue = strtotime($lockStartDate);
		//echo date("d-m-Y", $curDtValue) . '::' . date("d-m-Y", $lockStartDateValue );
		//current dt is less than next month's 9th day
		//if($curDtValue<$lockStartDateValue) return false;
		$projectIdToCheck =0;
		//check permission
		$permissions = $this->getPermissions();
		if($projectId==$projectIdToCheck){
            echo $dt. '--------'. date("Y-m-d", $dt);
			showArrayValues($permissions);
        }
		if($permissions['SAVE_LOCK']==1){
			//check in xxxx
			//get setup status of project
			$recs = $this->db->get_where(
				'dep_pmon__m_setup_status',
				array('PROJECT_ID'=>$projectId)
			);
			//echo $this->db->last_query();exit;
			$completionDate = $this->getCompletionDate($projectId);
			$arrSetupStatus = array();
			$arrSetupValues = array();
			$arrValidFields = array('PROJECT_STATUS');
			if($recs && $recs->num_rows()){
				$arrSetupStatus = $recs->row();
				$arrFields = array(
					'LA_CASES_STATUS', 'SPILLWAY_STATUS', 
					'FLANK_STATUS', 'SLUICES_STATUS', 'NALLA_CLOSURE_STATUS', 
					'CANAL_EARTH_WORK_STATUS', 'CANAL_STRUCTURE_STATUS',
					'CANAL_LINING_STATUS'
				);
				$arrNoNeedToCheckStatus = array(0,1,5,6);
				for ($i = 0; $i < count($arrFields); $i++) {
					//echo 'KK:'.$arrSetupStatus->SLUICES_STATUS.':KK'; 
					/*if (
						$arrSetupStatus->{$arrFields[$i]} == 0 ||
						$arrSetupStatus->{$arrFields[$i]} == 1 ||
						$arrSetupStatus->{$arrFields[$i]} == 5
					) {*/
					if( in_array($arrSetupStatus->{$arrFields[$i]}, $arrNoNeedToCheckStatus) ){
						//NA or Completed
					}else{
						$arrSetupValues[$arrFields[$i]] = $arrSetupStatus->{$arrFields[$i]};
						array_push($arrValidFields, $arrFields[$i]);
					}
				}
			}
			if($projectId==$projectIdToCheck){
				//echo 'KKK';
				showArrayValues($arrValidFields);
			}
			$arrCurMonthStatus = array();
			$arrPrevMonthStatus = array();
			//if record found for that session
			$recs = $this->db->get_where(
				'dep_pmon__t_monthlydata',
				array('PROJECT_ID'=>$projectId, 'MONTH_DATE'=>date("Y-m-d", $dt))
			);

			if($projectId==$projectIdToCheck){				
					echo 'xxxxxxxxxxxx';
					 echo $this->db->last_query();
				}


         	
			if($recs && $recs->num_rows()){
				if($projectId==$projectIdToCheck){				
					echo '12@@@@@@@@@@@@@';
				}

				$currentMonthRec = $recs->row();
				if($currentMonthRec->PROJECT_STATUS ==6){
					$arrValidFields	= array('PROJECT_STATUS');
				}				
			for ($i = 0; $i < count($arrValidFields); $i++) {
					$arrCurMonthStatus[$arrValidFields[$i]] = $currentMonthRec->{$arrValidFields[$i]};
				}
				$remrecs = $this->db->get_where(
					'dep_pmon__t_monthlydata_remarks',
					array(
						'PROJECT_ID'=>$projectId, 
						'MONTH_DATE'=>date("Y-m-d", $dt)
					)
				);

				$arrRemarks = array();
				$isRemarksExists = false;
				if($remrecs && $remrecs->num_rows()){
					//echo 'JJJJJJJJJJJJ';
					$isRemarksExists = true;
					$remrec = $remrecs->row();
					for($i=0; $i<count($arrValidFields);$i++){
						$arrRemarks[$arrValidFields[$i]] = $remrec->{$arrValidFields[$i].'_REMARK'};
					}
				}
				if($projectId==$projectIdToCheck){
				//echo 'KKK';
					showArrayValues($arrRemarks);
				}
				
				if($projectId==$projectIdToCheck){
					if($arrCurMonthStatus['PROJECT_STATUS']==3){//ongoing
						//showArrayValues($arrRemarks);
						if($completionDate<$currentMonthRec->MONTH_DATE){
							//check if remarks
							if($isRemarksExists){
								if($arrRemarks['PROJECT_STATUS']=='')
									return false;
							}else{
								return false;
							}
						}
						//showArrayValues($arrRemarks);
					}
				}
				$precs = $this->db->get_where(
					'dep_pmon__t_monthlydata',
					array(
						'PROJECT_ID'=>$projectId, 
						'MONTH_DATE'=>date("Y-m-d", strtotime("-1month", $dt))
					)
				);
				$isPrevExists = false;

				if($precs && $precs->num_rows()){
					$prevMonthRec = $precs->row();
					for($i=0; $i<count($arrValidFields);$i++){
						$arrPrevMonthStatus[$arrValidFields[$i]] = $prevMonthRec->{$arrValidFields[$i]};
					}
					$isPrevExists = true;
				}
				$arrValidData = array();
				for($i=0; $i<count($arrValidFields);$i++){
					$arrValidData[$arrValidFields[$i]] = false;
				}
				$arrValidSet = array(
					0=>array(0),
					1=>array(1),
					2=>array(2,3,4),
					3=>array(3,4,5),
					4=>array(3,4),
					5=>array(5)
				);
				////////////////
				if($projectId==$projectIdToCheck){
						echo '999999999999999999999';
				}
				if($isPrevExists){
					if($projectId==$projectIdToCheck){
						echo 'dssssssssssssssss';
					}
					//echo ":::".$projectId.':::';
					if( $projectIdToCheck==$projectId){
						echo 'arrValidSet';
						showArrayValues($arrValidSet);
						echo 'Valid Fields';
						showArrayValues($arrValidFields);
						echo 'current month';
						showArrayValues($arrCurMonthStatus);
						echo 'Prev. month';
						showArrayValues($arrPrevMonthStatus);
					}
					//compare data with current month
					for($i=0; $i<count($arrValidFields);$i++){
						if( in_array($arrCurMonthStatus[$arrValidFields[$i]], $arrValidSet[$arrPrevMonthStatus[$arrValidFields[$i]]])){
							//echo "...".$arrCurMonthStatus[$arrValidFields[$i]]. "..." ;
							if( in_array($arrCurMonthStatus[$arrValidFields[$i]], array(2,4)) ){
								//check remarks
								//echo '::'.$arrValidFields[$i].'::';
								//$arrValidData[$arrValidFields[$i]] = false;
								if(count($arrRemarks)){
									if($arrRemarks[$arrValidFields[$i]]!='')
										$arrValidData[$arrValidFields[$i]] = true;
								}
							}else if( $arrCurMonthStatus[$arrValidFields[$i]]==5){
								$arrValidData[$arrValidFields[$i]] = true;
							}else{
								//echo 'P'.$arrValidFields[$i].'-'.$arrCurMonthStatus[$arrValidFields[$i]].'='."\n";
								$arrValidData[$arrValidFields[$i]] = true;
							}
						}else if($arrCurMonthStatus[$arrValidFields[$i]]==6){
							$arrValidData[$arrValidFields[$i]] = true;
						}
					}
				}else{
					//echo 'ddddddd';
					//compare data with setup data
					for($i=0; $i<count($arrValidFields);$i++){
						if($arrValidFields[$i]=='PROJECT_STATUS'){
							if(	in_array($arrCurMonthStatus['PROJECT_STATUS'], array(2,3,4))){
								$arrValidData['PROJECT_STATUS'] = true;
							}
						}else{
							switch($arrSetupValues[$arrValidFields[$i]]){
								case 2: //not started
									if(	in_array($arrSetupValues[$arrValidFields[$i]], array(2,3,4))){
										$arrValidData[$arrValidFields[$i]] = true;
									}
									break;
								case 3://ongoing
									if(	in_array($arrSetupValues[$arrValidFields[$i]], array(3,4,5))){
										$arrValidData[$arrValidFields[$i]] = true;
									}
									break;
								case 4://stopped
									if(	in_array($arrSetupValues[$arrValidFields[$i]], array(3,4))){
										$arrValidData[$arrValidFields[$i]] = true;
									}
									break;
								case 5://completed
									if(	$arrSetupValues[$arrValidFields[$i]]==1){
										$arrValidData[$arrValidFields[$i]] = true;
									}
									break;
							}
						}								
					}
				}
				if($projectId==$projectIdToCheck){
					//showArrayValues($arrValidFields);
					echo 'valid kkkkk';
					showArrayValues($arrValidData);
					//echo 'invalidCount ='. $invalidCount;
				}
				$invalidCount = 0;
				for($i=0; $i<count($arrValidFields);$i++){
					if(!$arrValidData[$arrValidFields[$i]])
						$invalidCount++;
				}
				//if( $projectIdToCheck==$projectId){
				if($projectId==$projectIdToCheck){
					showArrayValues($arrValidFields);
					showArrayValues($arrValidData);
					echo 'invalidCount ='. $invalidCount;
				}
				if($invalidCount>0) return false;
				return true;
			}
		}
		return false;
	}
	public function monthlyProgressCheck(){
		$projectId = (int) $this->input->post('project_id');
		$lockMonth = date("Y-m-d", $this->input->post('lock_month'));
		echo $this->getMonthlyProgress($projectId, $lockMonth);
	}

	public function monthlyProgressCheckMi(){
        require('mi/Monthly_mi_c.php');
        $monthly_mi_c = new Monthly_mi_c();
        $monthly_mi_c->monthlyProgressCheck();
	}
	//check
	public function lockMonthly(){
		date_default_timezone_set('Asia/Kolkata');
      	$bypassEworks = FALSE;
      
		/*if(!IS_LOCAL_SERVER){
          	if(!$bypassEworks){
              $this->load->library('mycurl');
              $serverStatus = $this->mycurl->getServerStatus();
              if($serverStatus==0){
                  echo 'Unable to lock. E-work Server Not responding. Try after sometime...';
                  return;
              }
            }
		}*/
		$projectId = (int) $this->input->post('project_id');
		$lockMonth = (int) $this->input->post('lock_month');
		$projectStatus = $this->getProjectStatus($projectId, $lockMonth);
		$arrWhere = array('PROJECT_ID'=>$projectId);
		//echo 'projectId:'.$projectId. ', lockMonth:'. $lockMonth;
		$recs = $this->db->get_where('dep_pmon__t_locks', $arrWhere);
		$monthExists = 0;//'0000-00-00';
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$monthExists = strtotime($rec->MONTHLY_EXISTS);
		}
		//validate lock
		$data = array(
			'MONTH_LOCK' => date("Y-m-d", $lockMonth),
			'IS_COMPLETED' => (($projectStatus == 5 || $projectStatus == 6) ? 1 : 0)
		);
		if($projectStatus==5 || $projectStatus==6)
			$data['MONTHLY_EXISTS'] = $data['MONTH_LOCK'];
		//showArrayValues($data);
		@$this->db->update('dep_pmon__t_locks', $data, $arrWhere);
		
		/*SEND DATA TO EWORKS on 05-04-2020 by Anand*/
		$this->load->library('mycurl');
		$this->db->select('PROJECT_ID');
		$recs = $this->db->get_where('dep_pmon__t_monthlydata', array('PROJECT_ID'=>$projectId));
		//echo $this->db->last_query(); exit;
		//$eworkData = $this->getEWorksDetailsDeposit($projectId, $data['MONTH_LOCK'], $projectStatus);
		//showArrayValues($eworkData);exit;
		// $monthlyExists = (($recs && $recs->num_rows())?true:false);
		$monthlyExists = $recs->num_rows();
		/* if($monthlyExists==1){
			//echo "in if";
			$eworkData = $this->getEWorksDetailsDeposit($projectId, $data['MONTH_LOCK'], $projectStatus);
			showArrayValues($eworkData); //exit;
			if(!IS_LOCAL_SERVER){
				//showArrayValues($eworkData); 
	          	$result = $this->mycurl->savePmonDepositData($eworkData);
			}
			//showArrayValues($result);
			//showArrayValues($result);
			//showArrayValues($result);
		} */
		
		//echo $this->db->last_query();
		$lockResult = $this->db->affected_rows();
		if( $lockResult ){
			$lockDataTime = date("Y-m-d H:i:s");
			//update locked status
			$strSQL = "UPDATE dep_pmon__t_monthlydata SET LOCKED=1
				WHERE PROJECT_ID=".$projectId." AND MONTH_DATE<='".$data['MONTH_LOCK']."'";
			$this->db->query($strSQL);
			//update lock date
			$strSQL = "UPDATE dep_pmon__t_monthlydata 
				SET SUBMISSION_DATE='".date("Y-m-d")."' 
					WHERE PROJECT_ID=".$projectId.
					" AND MONTH_DATE='".$data['MONTH_LOCK']."'";
			$this->db->query($strSQL);
			//update e-works server
			//$plock = $this->monthlyProgressLockStatus($projectId);
			$pData = $this->getEWorksDetails($projectId);
			$seLockStartMonth = '2015-04-01';
			$lockMonthValue = strtotime($data['MONTH_LOCK']);
			//before june, 2015
			if($data['MONTH_LOCK']<$seLockStartMonth){
				//if monthly exists after lockdate then 2 otherwise 0
				$plock = (($monthExists > $lockMonthValue)? 2:0);
			}else{//from june, 2015
				$plock = 8;
			}
			//$ppLock = $this->monthlyProgressLockStatus($projectId, $data['MONTH_LOCK']);
			//if($ppLock) $plock = $ppLock;
			$params = array(
				'mode'=>'monthly',
				"projectCode"=>$projectId,
				"lDate" => $data['MONTH_LOCK'],
				"PLock" => $plock,
				"progressPerc" => $this->getMonthlyProgress($projectId, $data['MONTH_LOCK'])
			);
		 	$this->updateLockedStatus($params);
			//showArrayValues($params);
			//record log for lock
			$datax = array(
				'PROJECT_ID'=>$projectId, 
				'LOCK_DATE_TIME'=>$lockDataTime, 
				'LOCK_MODE'=>4, //monthly
				'LOCK_TYPE'=>1, //lock
				'MONTH_LOCK'=>$data['MONTH_LOCK'],
				'USER_ID'=>$this->session->userdata('USER_ID'), 
				'DESCRIPTION'=>'Project Monthly Locked'.(($projectStatus==5) ? 'Project Completed':'')
			);
			$lockTable = 'dep_pmon__t_lock_logs';
			@$this->db->insert($lockTable, $datax);
			//clean monthly yearly 
			if($projectStatus==5 || $projectStatus==6){
				//delete - monthlydata
				$arrWhich = array_merge($arrWhere, array('MONTH_DATE >'=>$data['MONTH_LOCK']));
				$recs = $this->db->get_where(
					'dep_pmon__t_monthlydata', 
					$arrWhich
				);
				if($recs && $recs->num_rows())
					@$this->db->delete('dep_pmon__t_monthlydata', $arrWhich);
				//delete - remarks
				$recs = $this->db->get_where(
					'dep_pmon__t_monthlydata_remarks', 
					$arrWhich
				);
				if($recs && $recs->num_rows())
					@$this->db->delete('dep_pmon__t_monthlydata_remarks', $arrWhich);
				//delete - progress
				$arrWhich = array_merge($arrWhere, array('PROGRESS_DATE >'=>$data['MONTH_LOCK']));
				$recs = $this->db->get_where(
					'dep_pmon__t_progress', 
					$arrWhich
				);
				if($recs && $recs->num_rows())
					@$this->db->delete('dep_pmon__t_progress', $arrWhich);
				//delete - target
				$ytSessionId = $this->getSessionIdByDate($data['MONTH_LOCK']);
				$arrWhich = array_merge($arrWhere, array('SESSION_ID >'=>$ytSessionId));
				$recs = $this->db->get_where(
					'dep_pmon__t_yearlytargets', 
					$arrWhich
				);
				if($recs && $recs->num_rows())
					@$this->db->delete('dep_pmon__t_yearlytargets', $arrWhich);
				//delete - estimated
				$recs = $this->db->get_where(
					'dep_pmon__t_estimated_qty', 
					$arrWhich
				);
				if($recs && $recs->num_rows())
					@$this->db->delete('dep_pmon__t_estimated_qty', $arrWhich);
				//delete - achievement
				$recs = $this->db->get_where(
					'dep_pmon__t_achievements', 
					$arrWhich
				);
				if($recs && $recs->num_rows())
					@$this->db->delete('dep_pmon__t_achievements', $arrWhich);
			}
			$arrParams = array(
				'PROJECT_ID'=>$projectId, 
				'OFFICE_ID'=> $this->session->userData('CURRENT_OFFICE_ID'), 
				"MODE"=>1, 
				"PROJECT_STATUS"=> $projectStatus
			);
			$depositDataForEworks = prepareDepositDataForEworks($arrParams);			
			$this->mycurl->sendDepositStatusData($depositDataForEworks);
			//showArrayValues($arrParams);
			//showArrayValues($depositDataForEworks);
			sendLogDeposit($arrParams,$depositDataForEworks);
			//return true;
		}
		$arrMonth = array(
			'', 'January', 'February', 'March', 'April', 'May', 'June', 
			'July', 'August', 'September', 'October', 'November', 'December'
		);
		echo (($lockResult)? '<span class="cus-lock"></span>"'.
			$arrMonth[date("n", $lockMonth)] .'" Month Data Locked'. (($projectStatus==5) ? '<br />Project Completed':'') :
				 '<span class="cus-bullet-error"></span> Unable to Lock  "'.
				 $arrMonth[date("n", $lockMonth)] .
				 '" Month Data');
	}

	public function lockMonthlyMi(){
        require('mi/Monthly_mi_c.php');
        $monthly_mi_c = new Monthly_mi_c();
        $monthly_mi_c->lockMonthly();
    }

	private function getEWorksDetails($projectId){
		$this->db->select('PROJECT_ID, PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, EWORK_ID');
		$this->db->where('PROJECT_ID', $projectId);
		//echo $projectId;
		$recs = $this->db->get('dep_pmon__v_projectlist_with_lock');
		$data = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$data = array(
					'EWORK_ID'=>$rec->EWORK_ID,
					'PROJECT_NAME'=>$rec->PROJECT_NAME,
					'PROJECT_NAME_HINDI'=>$rec->PROJECT_NAME_HINDI,
					'PROJECT_CODE'=>$rec->PROJECT_CODE
				);
			}
		}
		//showArrayValues($data);
		return $data;
	}
	private function getEWorksDetailsDeposit($projectId, $monthLockDate, $projectStatus){
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


	private function getEWorksDetailsForMonthly($projectId){
		$this->db->select('*');
		$this->db->where('PROJECT_ID', $projectId);
		$recs = $this->db->get('dep_pmon__v_projectlist_with_lock');
		$data = array();
		//$AUTHORITY_NAME = '';
		$depositSchemeArr= array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
					//$AUTHORITY_NAME = '';
					//$AUTHORITY_NAME = $this->getAuthorityName($rec->AA_AUTHORITY_ID);
					$depositSchemeArr= array();
					$depositSchemeArr= $this->getDepositScheme($rec->DEPOSIT_SCHEME_ID);
					//showArrayValues($depositSchemeArr); exit;
					$data = array(
					'ddocode'=>$rec->EWORK_ID,
					'head'=>$depositSchemeArr['HEAD'],
					'promon_id'=>$rec->PROJECT_CODE,
					'mode'=>"DepositWorkBalance"
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
	private function getMonthlyProgress($projectID, $month){
		if($month== '-0001-11-30') return;
		$strSQL = "SELECT * FROM dep_pmon__t_progress 
			WHERE PROJECT_ID=".$projectID."
				AND PROGRESS_DATE='".$month."'";
		$recs= $this->db->query($strSQL);
		//echo $this->db->last_query();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->PROGRESS;
		}
		return 0;
	}
	private function monthlyProgressLockStatus($projectID, $validEntryMonth){
		$progressTableName = 'dep_pmon__t_progress';
		//$validEntryMonth = $this->getValidEntryMonth();
		$currentMonthValue = strtotime( $validEntryMonth );
		//$currentMonthValue = strtotime("-1 months", $currentMonthValue);
		
		$sixMonthValue = strtotime("-6 months", $currentMonthValue);
		//echo $validEntryMonth.'::'.date("Y-m-d", $sixMonthValue );
		$arrSQL = array();
		$strSQL = "SELECT * FROM dep_pmon__t_progress 
			WHERE PROJECT_ID=".$projectID."
				AND PROGRESS_DATE>='".date("Y-m-d", $sixMonthValue)."' 
				AND PROGRESS_DATE<'".$validEntryMonth."' 
			ORDER BY PROGRESS_DATE ";
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query() . ':=:'.$recs->num_rows();
		if($recs && $recs->num_rows()){
			if($recs->num_rows()<6)
				return 0;
			$firstMonthProgress = 0;
			$ii=0;
			foreach($recs->result() as $rec){
				if($ii==0)
					$firstMonthProgress = $rec->PROGRESS;
				if($rec->PROGRESS!=$firstMonthProgress)
					return 0;
				$ii++;
			}
			return 3;
		}else
			return 0;
	}
	protected function getStartDateOfSession($id){
		$strSQL = "SELECT START_DATE FROM __sessions WHERE SESSION_ID=".$id;
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->START_DATE;
		}
		return '';
	}
	protected function getSessionIdByDate($mdate=''){
		if($mdate=='') $mdate = date("Y-m-d");
		$strSQL = "SELECT SESSION_ID FROM __sessions
			WHERE START_DATE<='".$mdate."'
				AND END_DATE>='".$mdate."'";
		$recs = $this->db->query($strSQL);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_ID;
		}
		return 0;
	}
	protected function getProjectStatus($projectId, $sessionId){
		$recs = $this->db->get_where(
			'dep_pmon__t_monthlydata', 
			array(
				'PROJECT_ID'=>$projectId,
				'MONTH_DATE'=>date("Y-m-d", $sessionId)
			)
		);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->PROJECT_STATUS;
		}
		return 0;
	}
	protected function updateLockedStatus($params){
		$arrWhere = array('projectCode'=>$params['projectCode']);
		$recs = $this->db->get_where('dep_pmon__t_locked_status', $arrWhere);
		if($recs && $recs->num_rows()){
			$data = array(
				"lDate" => $params['lDate'],
				"PLock" => $params['PLock'],
				"progressPerc" => $params['progressPerc']
			);
			@$this->db->update('dep_pmon__t_locked_status', $data, $arrWhere);
		}
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
	private function getCumm(){
    	//$i=1;
    	//if($i==1){
    	//$this->setCummulativeIPForAllProjects(4487);
    	//return;
		$strSQL = 'SELECT ps.PROJECT_ID FROM dep_pmon__m_project_setup as ps 
			INNER JOIN dep_pmon__t_estimated_status as s ON (ps.PROJECT_ID=s.PROJECT_ID AND s.IRRIGATION_POTENTIAL_NA=0)
			INNER JOIN deposit__projects as p ON (ps.PROJECT_ID=p.PROJECT_ID)
			ORDER BY ps.PROJECT_ID';
			
		$recs = $this->db->query($strSQL);
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
            	//echo ' a'.$rec->PROJECT_ID;
				$this->setCummulativeIPForAllProjects($rec->PROJECT_ID);
			}
			$recs->free_result();
		}
    //}
    	//echo 'wwwwwwwwwww';
	}
	private function setCummulativeIPForAllProjects($projectId){
		//get all blocks benefited
		$arrBlockIds = $this->getBlockIds($projectId);
		//save monthly data BLOCKWISE
		$arrFields = array('KHARIF', 'RABI', 'IP_TOTAL');
		$arrSubTotalBlock = array();
		$arrAchieveDataBlock = array();
		/*foreach($arrFields as $arrField){
			${$arrField} = $this->input->post($arrField);
			$arrSubTotalBlock[$arrField] = 0;
			//$arrAchieveDataBlock[$arrField] = 0;
		}*/
		foreach($arrBlockIds as $bid){
			foreach($arrFields as $arrField){
				$arrSubTotalBlock['b'.$bid][$arrField] = 0;
			}
		}
		//$arrAchieveData['IRRIGATION_POTENTIAL'] = 0;
		//$arrSubTotal['IRRIGATION_POTENTIAL_T']=0;
		//get achievement data for setup session
		$strSQL = 'SELECT a.* FROM dep_pmon__t_block_achievement_ip AS a
				INNER JOIN dep_pmon__m_project_setup AS p 
					ON (a.PROJECT_ID=p.PROJECT_ID AND a.SESSION_ID<p.SESSION_ID )
			WHERE a.PROJECT_ID = '.$projectId. ' ORDER BY BLOCK_ID';
		$recs = $this->db->query($strSQL);
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				foreach($arrFields as $arrField){
					$arrSubTotalBlock['b'.$rec->BLOCK_ID][$arrField] = $rec->{$arrField};
				}
			}
		}
		/*echo '0000';
		showArrayValues($arrSubTotalBlock);
		echo '222';/**/
		//
		$arrB = array();
		$arrBlockMonthlyIds = array();
		$arrBlockMonthlyDatas = array();
		//$this->db->select('ID, BLOCK_ID');
		$this->db->order_by('BLOCK_ID', 'ASC');
		$this->db->order_by('MONTH_DATE', 'ASC');
		$recs = $this->db->get_where('dep_pmon__t_block_monthly_ip', array('PROJECT_ID'=>$projectId));
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrB[$rec->ID] = $rec->BLOCK_ID;
				array_push($arrBlockMonthlyIds, $rec->ID);
				$arrBlockMonthlyDatas[$rec->ID] = $rec;
			}
		}
		//$arrBlockMonthlyIds = $this->input->post('BLOCK_MONTHLY_DATA_ID');
		/*echo '1111';
		showArrayValues($arrB);
		echo '222';
		showArrayValues($arrBlockMonthlyIds);
		echo '333';*/
		$datas = array();
		foreach($arrBlockMonthlyIds as $id){
			$data = array('ID'=>$id);
			$blockId = 'b'.$arrB[$id];
			foreach($arrFields as $arrField){
				$data[$arrField] = $arrBlockMonthlyDatas[$id]->{$arrField};
				$arrSubTotalBlock[$blockId][$arrField] += $data[$arrField];
			}
			//showArrayValues($arrSubTotalBlock);
			$data['KHARIF_T'] = $arrSubTotalBlock[$blockId]['KHARIF'];
			$data['RABI_T'] = $arrSubTotalBlock[$blockId]['RABI'];
			$data['IP_TOTAL_T'] =$data['KHARIF_T']+$data['RABI_T'];
			$data['IP_TOTAL'] =$data['KHARIF']+$data['RABI'];
			//$arrT = array_merge($data, $arrSubTotalBlock);
			array_push($datas, $data);
		}
		//showArrayValues($datas);
    	if($datas){
          @$this->db->update_batch('dep_pmon__t_block_monthly_ip', $datas, 'ID');
        
         // echo $this->db->last_query();
          //showArrayValues($datas);
          if ($this->db->affected_rows()){
          
              //echo ' <span class="cus-tick"></span> '.$this->db->affected_rows().' Monthly Block Data Updated...';
          }else{
              //echo '<span class="cus-error"></span> No Updatable or Unable to update Monthly Block Data ...';
          }
        
        }
    $s = '    
    UPDATE projects__ip_blockwise as pb
    INNER JOIN (
        SELECT b.PROJECT_ID, b.BLOCK_ID, b.KHARIF_T, b.RABI_T FROM dep_pmon__t_block_monthly_ip as b
            INNER JOIN (
                SELECT MAX(MONTH_DATE)as mxDate, PROJECT_ID, BLOCK_ID FROM dep_pmon__t_block_monthly_ip 
            WHERE PROJECT_ID='.$projectId.'
            GROUP BY PROJECT_ID, BLOCK_ID 
        ) AS m1 ON(m1.PROJECT_ID=b.PROJECT_ID and m1.BLOCK_ID=b.BLOCK_ID AND b.MONTH_DATE=m1.mxDate)
        ORDER BY b.PROJECT_ID
    )as bb ON (pb.PROJECT_ID=bb.PROJECT_ID and pb.BLOCK_ID=bb.BLOCK_ID)
SET 
pb.CREATED_IP_KHARIF=bb.KHARIF_T, 
pb.CREATED_IP_RABI=bb.RABI_T,
pb.CREATED_IP=(bb.KHARIF_T+bb.RABI_T)';
    $this->db->query($s);
           $s = 'select * FROM projects__ip_blockwise WHERE PROJECT_ID='.$projectId;
    		$recs = $this->db->query($s);
          	 //echo $this->db->last_query();
          	//showArrayValues($recs->result());
	}
}?>