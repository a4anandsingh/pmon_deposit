<?php
include_once("Project_library.php");
class Entry_report_c extends Project_library {
	var $proID, $OFF_ARRAY, $RAA_ID, $PROJECT_SETUP_ID,
		$SAVE_MODE, $message, $MODULE_KEY, $IS_PROJECT_LOCKED,
		$block, $data;

    function __construct(){
		parent::__construct();
		$this->PROJECT_SETUP_ID = 0;
		$this->message = array();
		$this->IS_PROJECT_LOCKED = FALSE;
		$this->load->model('mi/mi__m_report');
	}

	public function index(){
		$data = array();
		$data['message'] = '';
		$data['page_heading'] = pageHeading('PROMON - Micro Irrigation Project Entry Reports');
		$this->load->library('office_filter');
		$data['office_list'] = $this->office_filter->office_list();
		$data['message'] = '';
		$data['project_list'] = $this->createGrid();
		//$data['session_drop_down'] = $this->getSessionDropDown();
		$data['year_drop_down'] = $this->getYearDropDown();
		$data['month_drop_down'] = $this->getMonthDropDown();
		$this->load->view('mi/print/report_index_view', $data);
	}

	private function createGrid(){
		$buttons = array();
		$mfunctions = array();
		array_push($mfunctions, "onSelectRow: function(ids){fillProjectId();}");
		$aData = array(
            'set_columns' => array(
				array(
					'label' => 'Project Name',
					'name' => 'WORK_NAME',
					'width' => 100,
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
					'label' => 'परियोजना',
					'name' => 'PROJECT_NAME_HINDI',
					'width' => 120,
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
					'width' => 70,
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
					'label' => 'Status',
					'name' => 'MY_PROJECT_STATUS',
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
					'label' => 'Setup',
					'name' => 'LOCKED',
					'width' => 70,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true
				),
				array(
					'label' => 'Target',
					'name' => 'TARGET_LOCK_SESSION',
					'width' => 70,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true
				),
				array(
					'label' => 'Month',
					'name' => 'MONTH_LOCK',
					'width' => 70,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true,
					'formatter' => 'date',
					'newformat' => 'M, Y',
					'srcformat' => 'Y-m-d',
				),
				array(
					'label' => 'Setup Id',
					'name' => 'PROJECT_SETUP_ID',
					'width' => 30,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					/*'key'=>false,*/
					'search' => true,
					'view' => true,
					'formatter' => '',
					'searchoptions' => ''
				),
				array(
					'label' => 'Project Id',
					'name' => 'PROJECT_ID',
					'width' => 30,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					/*'key'=>false,*/
					'search' => true,
					'view' => true,
					'formatter' => '',
					'searchoptions' => ''
				)
            ),
            'custom' => array("button" => $buttons, "function" => $mfunctions),
            'div_name' => 'projectList',
            'source' => 'getProjectListGrid',
            'postData' => '{}',
            'rowNum' => 10,
            'width' => DEFAULT_GRID_WIDTH,
			'autowidth'=>true,
            'height' => '',
            'altRows' => true,
            'rownumbers' => true,
            'sort_name' => 'WORK_NAME',
            'sort_order' => 'asc',
            'primary_key' => 'PROJECT_SETUP_ID',
            'caption' => 'Projects परियोजनाएं',
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
            'custom_button_position' => 'bottom'
        );
        return buildGrid($aData);
    }

    /**-------- ----------*/
    /*public function getSessionDropDown()
    {
        $curSession_id = $this->session->userData('CURRENT_SESSION_ID');
        $this->db->select('SESSION_ID,SESSION');
        $this->db->from('__sessions');
        $this->db->where('SESSION_ID <=', $curSession_id);
        $optData = $this->db->get();
        //echo $this->db->last_query();
        //exit;
        $opt = array();
        //$query = $this->db->query('SELECT SESSION_ID, SESSION FROM __sessions WHERE SESSION_ID <= ' . $curSession_id);
        //$opt = array();
        //$optData = $query->result();
        //$this->db->last_query();
        foreach ($optData->result() as $val) {
            if($val->SESSION_ID < PMON_MI_START_SESSION_ID) continue;
            array_push(
                $opt,
                '<option value="' . $val->SESSION_ID . '" ' .
                (($val->SESSION_ID == $curSession_id) ? 'selected="selected"' : '')
                . '>' . $val->SESSION . '</option>'
            );
        }
        return implode('', $opt);
    }*/

    public function getYearDropDown(){
        $opt = array();
        $curYear = (int)date("Y");
        for($i = 2018; $i <= date('Y'); $i++) {
            array_push(
                $opt,
                '<option value="' . $i . '" ' .
                (($i == $curYear) ? 'selected="selected"' : '')
                . '>' . $i . '</option>');
        }
        return implode('', $opt);
    }

	public function getMonthDropDown(){
		$opt = array();
		$month = array(
			'Select month', 'January', 'February', 'March', 'April', 'May', 'June',
			'July', 'August', 'September', 'October', 'November', 'December'
		);
		$curMonth = ((int)date("m")) - 1;
		foreach ($month as $key => $val) {
			array_push($opt, '<option value="' . $key . '" ' .
				(($key == $curMonth) ? 'selected="selected"' : '')
				. '>' . $val . '</option>');
		}
		return implode('', $opt);
	}

    /**-------- Report ----------*/
    public function showOfficeFilterBox(){
        //$data['instance_name'] = 'search_office';
        $data = array();
        $data['prefix'] = 'search_office';
        $data['show_sdo'] = FALSE;
        $data['row'] = /*'<tr><td class="ui-widget-content"><strong>Project Type</strong></td>'.
			'<td class="ui-widget-content">
				<select name="SEARCH_PROJECT_TYPE_ID" style="width:600px" class="office-select" id="SEARCH_PROJECT_TYPE_ID">
			<option value="0">All Types(सभी प्रकार)</option>'.$this->getProjectTypeList().'</select></td></tr>*/
            '<tr><td class="ui-widget-content"><strong>Project Status </strong></td>' .
            '<td class="ui-widget-content">
				<select name="SEARCH_PROJECT_STATUS" style="width:400px" class="office-select" id="SEARCH_PROJECT_STATUS">
			<option value="0">All Projects (सभी परियोजनाएं)</option>
			<option value="1">Ongoing Projects (निर्माणाधीन परियोजनाएं)</option>
			<option value="2">Completed Projects (पूर्ण परियोजनाएं)</option>
			<!--<option value="6">Dropped Projects</option>-->
			</select></td>
		</tr>
		<tr>
		<td class="ui-widget-content"><strong>Project Name</strong></td>
		<td class="ui-widget-content">
			<input type="text" value="" name="SEARCH_PROJECT_NAME" id="SEARCH_PROJECT_NAME">
		</td>
		</tr>
		<tr><td colspan="2" class="ui-widget-content">' . getButton(array('caption'=>'Search', 'event'=>'refreshSearch()', 'icon'=>'cus-zoom', 'title'=>'Search')). '</td></tr>';
        $this->load->view('setup/office_filter_view', $data);
    }

    public function getProjectListGrid(){
        $objFilter = new clsFilterData();
        $objFilter->assignCommonPara($_POST);
        /* =============== */
        if($this->input->post('project_id')) {
            array_push($objFilter->SQL_PARAMETERS, array("PROJECT_SETUP_ID" => $this->input->post('project_id')));
        }
        if($this->input->post('SEARCH_PROJECT_NAME')) {
            array_push($objFilter->SQL_PARAMETERS, array("WORK_NAME", 'LIKE', $this->input->post('SEARCH_PROJECT_NAME')));
        }
        $searchProjectStatus = $this->input->post('SEARCH_PROJECT_STATUS');
        $w = '';
        /*switch ($searchProjectStatus) {
            case 1:
                $w = ' AND ((WORK_STATUS<5) OR ((WORK_STATUS>=5) AND (SE_COMPLETION=0))) ';
                break;
            case 5:
                $w = ' AND ((WORK_STATUS=5) AND (SE_COMPLETION=1)) ';
                break;
            case 6:
                $w = ' AND ((WORK_STATUS=6) AND (SE_COMPLETION=1)) ';
                break;
        }*/
        if($searchProjectStatus==1){
            $w = ' AND WORK_STATUS <5';
        }elseif($searchProjectStatus==2){
            $w = ' AND WORK_STATUS =5';
        }
        $EE_ID = $this->input->post('EE_ID');
        $CE_ID = $this->input->post('CE_ID');
        $SE_ID = $this->input->post('SE_ID');
        //$SDO_ID = $this->input->post('SDO_ID');
        //if($EE_ID==false && $CE_ID==false && $SE_ID==false && $SDO_ID==false){
        if($EE_ID == false && $CE_ID == false && $SE_ID == false) {
            $EE_ID = $this->session->userData('EE_ID');
            $SE_ID = $this->session->userData('SE_ID');
            $CE_ID = $this->session->userData('CE_ID');
            //$SDO_ID = $this->session->userData('SDO_ID');
        }
        if($EE_ID == 0 && $SE_ID == 0 && $CE_ID == 0) {
            //NO OPTION SELECTED BY E-IN-C
            array_push($objFilter->WHERE, ' 1 GROUP BY ps.PROJECT_SETUP_ID');
            /*
            $objFilter->SQL = 'SELECT PROJECT_SETUP_ID, AA_DATE AS PROJECT_START_DATE,
					WORK_NAME, WORK_NAME_HINDI, PROJECT_CODE, MONTH_LOCK, 
					CONCAT(SESSION_START_YEAR, "-",SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
					IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					 "<span class=\'cus-bullet-green\'></span>") as LOCKED,
					 IF(WORK_STATUS<5, "<span class=\'cus-eye\'></span>",
					 IF(WORK_STATUS=5, "<span class=\'cus-thumb-up\'></span>",
					 "<span class=\'cus-cancel\'></span>")) as MY_PROJECT_STATUS 
				FROM mi__v_projectlist_with_lock 
					WHERE 1' . $w;*/
            $strSelect = ' ps.PROJECT_SETUP_ID, p.PROJECT_ID,  ps.AA_DATE AS PROJECT_START_DATE,
					ps.WORK_NAME, ps.WORK_NAME_HINDI, ps.PROJECT_CODE, locks.MONTH_LOCK, 
					CONCAT(target_session.SESSION_START_YEAR, "-",target_session.SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
					IF(locks.SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					 "<span class=\'cus-bullet-green\'></span>") as LOCKED,
					 IF(ps.WORK_STATUS<5, "<span class=\'cus-eye\'></span>",
					 IF(ps.WORK_STATUS=5, "<span class=\'cus-thumb-up\'></span>",
					 "<span class=\'cus-cancel\'></span>")) as MY_PROJECT_STATUS';
            $objFilter->SQL = $this->mi__m_report->getMIProjectListSQL($strSelect, $w);
            //WHERE PROJECT_TYPE_ID='.$this->session->userData('PROJECT_TYPE_ID') . $w;
        }else{
            $EEE = '';//($SDO_ID==0)? '' : ' OFFICE_SDO_ID='.$SDO_ID;
            $EEE .= ($EE_ID == 0) ? '' : (($EEE == '') ? '' : ' AND ') . ' office_ee.OFFICE_ID=' . $EE_ID;
            $EEE .= ($SE_ID == 0) ? '' : (($EEE == '') ? '' : ' AND ') . ' office_se.OFFICE_ID=' . $SE_ID;
            $EEE .= ($CE_ID == 0) ? '' : (($EEE == '') ? '' : ' AND ') . ' office_ce.OFFICE_ID=' . $CE_ID;
            //$EEE .= ($SDO_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SDO_ID='.$SDO_ID;
            if($this->session->userData('HOLDING_PERSON') != 4) {
                //$EEE .= ' AND LOCK_STATUS=1 ';
                //$HOLDING_PERSON = $this->session->userData('HOLDING_PERSON');
                //$EEE .= ($HOLDING_PERSON==4)? '' : ( ($EEE=='') ? '':' AND ').' LOCKED_HOLDING_PERSON='.($HOLDING_PERSON+1);
                //$EEE .= ($HOLDING_PERSON==4)? '' : ( ($EEE=='') ? '':' AND ').' LOCKED_HOLDING_PERSON='.($HOLDING_PERSON+1);
                //$EEE .= ' AND MODULE_KEY="PMON_MINOR_PROJECT_SETUP" ';
                $EEE .= ' GROUP BY ps.PROJECT_SETUP_ID';
            }
            //pmon__v_projects_setup
            array_push($objFilter->WHERE, $EEE);//. ' GROUP BY PROJECT_ID');
            /*$objFilter->SQL = 'SELECT DISTINCT PROJECT_SETUP_ID, AA_DATE AS PROJECT_START_DATE,
					WORK_NAME, WORK_NAME_HINDI, PROJECT_CODE,  MONTH_LOCK, 
					CONCAT(SESSION_START_YEAR, "-",SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
					IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					 "<span class=\'cus-bullet-green\'></span>") as LOCKED,
					 IF(WORK_STATUS<5, "<span class=\'cus-eye\'></span>",
					 IF(WORK_STATUS=5, "<span class=\'cus-thumb-up\'></span>",
					 "<span class=\'cus-cancel\'></span>")) as MY_PROJECT_STATUS  
				FROM mi__v_projectlist_with_lock 
					WHERE 1' . $w;*/

            $strSelect = 'DISTINCT ps.PROJECT_SETUP_ID, p.PROJECT_ID, ps.AA_DATE AS PROJECT_START_DATE,
					ps.WORK_NAME, ps.WORK_NAME_HINDI, ps.PROJECT_CODE,  locks.MONTH_LOCK, 
					CONCAT(target_session.SESSION_START_YEAR, "-",target_session.SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
					IF(locks.SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					 "<span class=\'cus-bullet-green\'></span>") as LOCKED,
					 IF(ps.WORK_STATUS<5, "<span class=\'cus-eye\'></span>",
					 IF(ps.WORK_STATUS=5, "<span class=\'cus-thumb-up\'></span>",
					 "<span class=\'cus-cancel\'></span>")) as MY_PROJECT_STATUS';

            $objFilter->SQL = $this->mi__m_report->getMIProjectListSQL($strSelect, $w);
			//WHERE PROJECT_TYPE_ID='.$this->session->userData('PROJECT_TYPE_ID') . $w;
        }
        /* =============== */
		$fields = array(
			array('WORK_NAME', FALSE),
			array('WORK_NAME_HINDI', FALSE),
			array('PROJECT_CODE', FALSE),
			array('MY_PROJECT_STATUS', FALSE),
			array('LOCKED', FALSE),
			array('TARGET_LOCK_SESSION', FALSE),
			array('MONTH_LOCK', FALSE),
			array('PROJECT_SETUP_ID', FALSE),
			array('PROJECT_ID', FALSE)
		);
		echo $objFilter->getJSONCode('PROJECT_SETUP_ID', $fields);
		//echo $objFilter->PREPARED_SQL;
	}
    /**-------- Setup Report ----------*/
	public function getTargetSessionOptions(){
		$PROJECT_SETUP_ID = (int)$this->input->post('PROJECT_SETUP_ID');
		$recs = $this->db->distinct()
			->select('target.SESSION_ID, concat(__sessions.SESSION_START_YEAR,"-",__sessions.SESSION_END_YEAR) as SESSION_YEAR')
			->from('mi__t_yearlytargets as target')
			->join('__sessions','__sessions.SESSION_ID=target.SESSION_ID')
			->order_by('SESSION_ID')
			->where(array('PROJECT_SETUP_ID' => $PROJECT_SETUP_ID))
			->get();
		$vOpt = '<option value="">Select Session</option>';
		if($recs && $recs->num_rows()) {
			/*foreach ($recs->result() as $rec) {
				$vOpt .= '<option value="' . $rec->SESSION_ID . '">' .
					$this->mi__m_report->getSessionYearByID($rec->SESSION_ID) .
					'</option>';
			}*/
			$totalCount = $recs->num_rows();
			$iCount = 1;
			foreach ($recs->result() as $rec) {
				$vOpt .= '<option value="' . $rec->SESSION_ID . '" '.(($iCount==$totalCount)? 'selected="selected"':'').'>' .
					$rec->SESSION_YEAR.
					'</option>';
				$iCount++;
			}
		}
		echo $vOpt;
	}

	public function getMonthlyOptions(){
		$PROJECT_SETUP_ID = (int)$this->input->post('PROJECT_SETUP_ID');
		$monthlyRecs = $this->mi__m_report->getMonthlyOptions($PROJECT_SETUP_ID);
		$vOpt = '<option value="">Select Month</option>';
		$completed = FALSE;
		$lastMonth = '';
		$curMonthValue = strtotime(date("Y-m") . '-01');
		$prjNxtMonthValue = strtotime("+1months", $curMonthValue);
		if($monthlyRecs && $monthlyRecs->num_rows()) {
			//echo 'in if';
			$totalCount = $monthlyRecs->num_rows();
			$iCount = 1;
			foreach ($monthlyRecs->result() as $rec) {
				$vOpt .= '<option label="0"  '.(($iCount==$totalCount)? 'selected="selected"':'').' value="' . $rec->MONTHLY_DATA_ID . '">' .
					date("M, Y", strtotime($rec->MONTH_DATE)) .
					'</option>';
				if($rec->WORK_STATUS == 5) $completed = TRUE;
				$lastMonth = $rec->MONTH_DATE;
				$iCount++;
			}
		}else{
			$dd = date("Y-m", strtotime("-1months")) . '-01';
			//echo 'dd= '. $dd;exit;
		}
		//echo '<br />after if else ....'.$completed; exit;
		$v1 = '';
		$curDay = (int) date("d");
		if(!$completed){
			
			if(($lastMonth == date("Y-m").'-01') && ($curDay<20)){
				//
			}else{
				$nextMonthValue = strtotime($lastMonth);
				$curMonthValue = strtotime(date("Y-m") . '-01');
				//echo 'prjnextmonth value'.$prjNxtMonthValue.'<br />lst monthvalue= '.$lastMonth. '<br />nextmonthvalue ='. $nextMonthValue. '<br />cur month value ='.$curMonthValue;exit;
				if($nextMonthValue!='') {
					$count =0;
					while (1) {
						$count++;
						/*echo 'next month value ='. $nextMonthValue .'=='. 'cur month value = '. $curMonthValue .'<br />';
						if($nextMonthValue == $curMonthValue){
							echo '<br /> in if'.$count;
						}else{
							echo '<br />in else'.$count;
						}*/
						$nextMonthValue = strtotime("+1months", $nextMonthValue);
						$v1 .= '<option label="1" '. (($nextMonthValue==$prjNxtMonthValue)?" selected='selected'": "").' value="-1">' . date("M, Y", $nextMonthValue) . '</option>';
						$vdate = $nextMonthValue;
						if($nextMonthValue == $curMonthValue) break;
						if($count>=21) break;
					}
				}
			}
		}
		echo $vOpt . '##' . $v1. '##'. $vdate;
	}

	public function printSetup(){
		$this->load->model('mi/mi__m_project_setup');
		//$PROJECT_SETUP_ID = (int) $this->input->post('PROJECT_SETUP_ID');
		$this->PROJECT_SETUP_ID = (int)$this->input->post('PROJECT_SETUP_ID');
		//$this->startTime = microtime();
		//'PROJECT_START_DATE','TARGET_LOCK','RAA_LOCK', 'RAA_DATE','RAA_EXISTS'
		$arrSetupStatus = $this->mi__m_project_setup->getEstimationStatus($this->PROJECT_SETUP_ID);
		//showArrayValues($projectSetupFields);
		//exit;
		$projectSetupFields = $this->mi__m_report->getProjectSetupFields();
	
		$projectSetupValues = array();
		foreach($projectSetupFields as $f) $projectSetupValues[$f] = '';

		$arrWhere = array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID);
		//$recs = $this->db->get_where('mi__v_projectlist_details_with_lock', $arrWhere);
		//$recs = $this->db->get_where('mi__v_projectlist_with_lock', $arrWhere);

		$recs = $this->mi__m_project_setup->getMIProjectDataAll($this->PROJECT_SETUP_ID);
		if($recs && $recs->num_rows()){
			if($recs->num_rows() == 1) {
				$rec = $recs->row();
				//showArrayValues($rec);
				foreach($projectSetupFields as $f){
					//echo $projectSetupFields[$i];
					$projectSetupValues[$f] = $rec->{$f};
				}
				$recs->free_result();
			}
		}
		//get sdo list
		$sdo = array();
		$recs = $this->db->get_where('mi__v_project_sdo', $arrWhere);
		if($recs && $recs->num_rows()){
			$xi = 1;
			foreach ($recs->result() as $recSDO) {
				array_push($sdo, $xi . '.' . $recSDO->OFFICE_NAME);
				$xi++;
			}
		}
		$projectSetupValues['SDO_OFFICE_NAME'] = implode(', ', $sdo);
		$data['projectSetupValues'] = $projectSetupValues;
		$data['arrSetupStatus'] = $arrSetupStatus;
		//showArrayValues($projectSetupValues);
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		//$RAA_FIELDS = array("RAA_NO", "RAA_DATE", "RAA_AUTHORITY_ID", "RAA_AMOUNT");
		//$RAA_VALUES = array();
	
		$recRAA = $this->mi__m_project_setup->getRAAData(1, $this->PROJECT_SETUP_ID);
		//showArrayValues($recRAA);
		$data['RAA_AUTHORITY_ID'] = $this->mi__m_project_setup->getAuthorityName($recRAA['RAA_AUTHORITY_ID']);
		//initialize
		/*for ($i = 0; $i < count($RAA_FIELDS); $i++) {
			$RAA_VALUES[$RAA_FIELDS[$i]] = '';
		}
		if($recRAA) {//if found
			for ($i = 0; $i < count($RAA_FIELDS); $i++)
				$RAA_VALUES[$RAA_FIELDS[$i]] = $recRAA[$RAA_FIELDS[$i]];
			$data['RAA_AUTHORITY_ID'] = $this->getAuthorityName($RAA_VALUES['RAA_AUTHORITY_ID']);
		}else{
			$data['RAA_AUTHORITY_ID'] = '';
		}
		$data['RAA_VALUES'] = $RAA_VALUES;*/
		$data['RAA_VALUES'] =$recRAA;
	
		/************ check point */
		$SESSION_ID = 0;
		if($projectSetupValues['SESSION_ID'])
			$SESSION_ID = $projectSetupValues['SESSION_ID'];
		if($this->PROJECT_SETUP_ID) {
			
			$recAchieve = $this->mi__m_project_setup->getAchievement($SESSION_ID - 1, $this->PROJECT_SETUP_ID);
			//showArrayValues($recAchieve);
			$recEstimation = $this->mi__m_project_setup->getEstimation($this->PROJECT_SETUP_ID);
			$arrSetupStatusData = $this->mi__m_project_setup->getSetupStatus($this->PROJECT_SETUP_ID);
			//showArrayValues($recEstimation);
			//$recEstimationStatus = $this->mi__m_report->getEstimationStatus($this->PROJECT_SETUP_ID);
			$arrTargetDates = $this->mi__m_project_setup->getTargetDates($this->PROJECT_SETUP_ID);
			/*
			$data['estimationStatus'] = $recEstimationStatus;
			$data['TARGET_DATES_VALUES'] = $recTargetDates;
			//showArrayValues($recTargetDates);
			$data['statusData'] = $this->mi__m_report->getSetupStatusData($this->PROJECT_SETUP_ID);
			*/
			$data['arrEstimationData'] = $recEstimation;
			$data['arrAchievementData'] = $recAchieve;
			$data['arrSetupStatus'] = $arrSetupStatus;
			$data['arrSetupStatusData'] = $arrSetupStatusData;
			$data['arrTargetDates'] = $arrTargetDates;
		}
		$EE_ID = 0;
		$SDO_DD = '';
		$HOLDING_PERSON = $this->session->userData('HOLDING_PERSON');
		if($HOLDING_PERSON == 4) {//ee
			$EE_ID = $this->session->userData('CURRENT_OFFICE_ID');
			$SDO_DD = '';
		}
		$data['EE_ID'] = $EE_ID;
		//echo $HOLDING_PERSON. '::'.$EE_ID.'::';
		$SDO_IDs = array();
		if($this->PROJECT_SETUP_ID > 0) {
			$recs = $this->db->get_where('mi__m_projects_office', $arrWhere);
			if($recs && $recs->num_rows()) {
				foreach ($recs->result() as $rec) {
					$EE_ID = $rec->EE_ID;
					array_push($SDO_IDs, $rec->OFFICE_ID);
				}
			}
		}
		$data['sdo_options'] = $this->mi__m_report->SDOofficeOptions($EE_ID, $SDO_IDs);
		$data['EE_NAME'] = $this->mi__m_report->getOfficeEEname($EE_ID);
		//$this->showTime('aft off');
		if($this->PROJECT_SETUP_ID) {
			$data['DISTRICT_BENEFITED'] = $this->getDistricts($this->mi__m_report->getDistrictBenefitedIDs($this->PROJECT_SETUP_ID));
			$arrBlockIds = $this->mi__m_report->getBlockIds($this->PROJECT_SETUP_ID);
			$data['BLOCKS_BENEFITED'] = $this->mi__m_project_setup->getBlockString($arrBlockIds);
			$data['ASSEMBLY_BENEFITED'] = $this->mi__m_report->getAssemblys($this->getBenefitedAssemblyIDs($this->PROJECT_SETUP_ID));
			$data['VILLAGES_BENEFITED'] = $this->mi__m_report->getVillages($this->PROJECT_SETUP_ID);

			/*$parentDists = $this->mi__m_project_setup->getBenefitedDistricts($projectSetupValues['PARENT_PROJECT_ID']);
			$parentBlocks = $this->mi__m_project_setup->getBenefitedBlocks($projectSetupValues['PARENT_PROJECT_ID']);
			$parentAssemblies = $this->mi__m_project_setup->getBenefitedAssembly($projectSetupValues['PARENT_PROJECT_ID']);
	
			$data['DISTRICT_BENEFITED'] = $this->getDistrictBenefited($this->PROJECT_SETUP_ID, $parentDists);
			$did = $this->getDistrictBenefitedIDs($this->PROJECT_SETUP_ID);
			$data['BLOCKS_BENEFITED'] = $this->getBlocksBenefited($did, $this->PROJECT_SETUP_ID, $parentBlocks);
			$data['ASSEMBLY_BENEFITED'] = $this->getBenefitedAssembly($this->PROJECT_SETUP_ID, $parentAssemblies);
			$data['VILLAGES_BENEFITED'] = $this->getBenefitedVillages($this->PROJECT_SETUP_ID, $did);*/
	
			//showArrayValues($projectSetupValues);
			//blockwise iP
			$sessionId = $projectSetupValues['SESSION_ID'];
			$arrBlockIds = $this->mi__m_report->getBlockIds($this->PROJECT_SETUP_ID);
	
			$arrBlockIps = $this->mi__m_report->getEstimationBlockIP($this->PROJECT_SETUP_ID, $recEstimation['ESTIMATED_QTY_ID']);
			$arrBlockAIps = $this->mi__m_report->getAchievementBlockIP($this->PROJECT_SETUP_ID, $sessionId - 1);
			//showArrayValues($arrBlockIps);
			//showArrayValues($arrBlockAIps);
			foreach ($arrBlockIds as $arrBlockId) {
				if(array_key_exists($arrBlockId, $arrBlockAIps)) {
					$arrBlockIps[$arrBlockId]['ACHIEVEMENT_IP'] = $arrBlockAIps[$arrBlockId]['ACHIEVEMENT_IP'];
				}else{
					$arrBlockIps[$arrBlockId]['ACHIEVEMENT_IP'] = 0;
				}
			}
			$data['BLOCK_IP_DATA'] = $arrBlockIps;
		}
		$myview = $this->load->view('mi/print/project_setup_data_print_view_table', $data, true);
		array_push($this->message, getMyArray(null, $myview));
		echo createJSONResponse($this->message);
	}

    //NOT using this function
    protected function getBenefitedAssemblyIDs($projectId){
		$ids = array();
		if($projectId){
			$recs = $this->db->get_where('mi__m_assembly_const_served', array('PROJECT_SETUP_ID'=>$projectId));
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

    /*Already in mi__base
     * public function getBlocks($projectId){
        if( !$projectId) return '';
        $view = '';
        $blocks = array();
        $recs = $this->db->get_where('mi__m_block_served', array('PROJECT_SETUP_ID'=>$projectId));
        if($recs && $recs->num_rows()){
            foreach($recs->result() as $rec){
                array_push($blocks, $rec->BLOCK_ID);
            }
        }
        return $this->mi__m_report->getBlockString($blocks);
    }*/

    /**-------- Target Report ---------*/
    public function printTarget(){
		$data = array();
		$this->PROJECT_SETUP_ID = $this->input->post('PROJECT_SETUP_ID');
		$sessionId = $this->input->post('session');
		$rec_prj = null;
		$arrWhere = array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID);
		// Get AA AMOUNT to Compare with Target should no excessed
		$miProjectData = $this->mi__m_report->getMiProjectData(array('WORK_NAME', 'PROJECT_CODE', 'AA_AMOUNT', 'AA_DATE', 'PROJECT_COMPLETION_DATE'),$arrWhere);
	
		$data['AA_AMOUNT'] = $miProjectData['AA_AMOUNT'];
		$data['AA_RAA'] = 'AA';
		$extCompletionDate = $this->mi__m_report->getExtensionDate($this->PROJECT_SETUP_ID);
		$projectCompletionDate = ($extCompletionDate=='') ? $miProjectData['PROJECT_COMPLETION_DATE']:$extCompletionDate;
		//$projectCompletionDate = $miProjectData['PROJECT_COMPLETION_DATE'];
	
		$data['PROJECT_NAME'] = $miProjectData['WORK_NAME'];
		$data['PROJECT_CODE'] = $miProjectData['PROJECT_CODE'];
	
		$data['setupData'] = $this->mi__m_report->getSetupData($this->PROJECT_SETUP_ID);
		$data['BUDGET_AMOUNT'] = '';
		$data['SUBMISSION_DATE'] = '';
		//session
		if($sessionId == 0) {
			$MONTH = date('m');
			$YEAR = date('Y');
			//$sessionId = $this->getFinancialYearIDByMonthYear($MONTH, $YEAR);
			$sessionId = getSessionIdByDate();
		}
		$data['session_year'] = $this->mi__m_report->getSessionYearByID($sessionId);
		//echo $PROJECT_COMPLETION_MONTH.' -- '.$PROJECT_COMPLETION_YEAR;
		$data['sessionId'] = $sessionId;
		//$SessionProjComp = $this->getFinancialYearIDByMonthYear($PROJECT_COMPLETION_MONTH, $PROJECT_COMPLETION_YEAR);
		$SessionProjComp = getSessionIdByDate($projectCompletionDate);
		$data['SESSION_LIST'] = $this->mi__m_report->getMySessionOptions($SessionProjComp, $sessionId);
		$records = array();
		$targetFields = $this->mi__m_report->getYearlyTargetFields();
	
		/*for($i=1; $i<=12; $i++){
			$rec = array();
			for($j=0; $j<count($targetFields); $j++){
				$rec[ $targetFields[$j] ] = '';
			}
			$records[$i] = (object) $rec;
		}*/
	
		//get RAA data
		$ress = $this->db->select('RAA_AMOUNT')
				->from('mi__t_raa_project')
				->order_by('RAA_PROJECT_ID', 'DESC')
				->where(array('PROJECT_SETUP_ID' =>$this->PROJECT_SETUP_ID))
				->limit(1, 0)
				->get();
		if($ress && $ress->num_rows()) {
			$rrec = $ress->row();
			$ress->free_result();
			$data['AA_AMOUNT'] = $rrec->RAA_AMOUNT;
			$data['AA_RAA'] = 'RAA';
		}
	
		$arrBlockIps = $this->mi__m_report->getEstimationBlockIP($this->PROJECT_SETUP_ID);
		$data['arrBlockIps'] = $arrBlockIps;
	
		for ($i = 1; $i <= 12; $i++) {
			//$targetData[$i] = (object) $rec;
			$tMonth = (($i >= 10) ? ($i - 9) : ($i + 3));
			$mYears = $this->mi__m_report->getYearBySessionMonth($sessionId, $i);
			$tYears = (($i >= 10) ? $mYears[1] : $mYears[0]);
			//$targetFields[$tYears.'-'.str_pad($tMonth, 2, '0', STR_PAD_LEFT).'-01'] = (object) $rec;
	
			$rec['TARGET_DATE'] = $tYears . '-' . str_pad($tMonth, 2, '0', STR_PAD_LEFT) . '-01';
			foreach ($targetFields as $key=>$value){
				if($value=='TARGET_DATE'){
	
				}else{
					$rec[$value] = '';
				}
			}
			$records[$tYears . '-' . str_pad($tMonth, 2, '0', STR_PAD_LEFT) . '-01'] = (object)$rec;
		}
	
		//echo $sessionId. ' = '.$this->PROJECT_SETUP_ID;
		//showArrayValues($records);
		$recs = $this->db->get_where(
			'mi__t_yearlytargets',
			array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID, 'SESSION_ID' => $sessionId)
		);
		if($recs && $recs->num_rows()) {
			$i = 0;
			foreach ($recs->result() as $rec) {
				if($i == 0) {
					//$data['BUDGET_AMOUNT'] = $rec->BUDGET_AMOUNT;
					$data['SUBMISSION_DATE'] = $rec->SUBMISSION_DATE;
					$i++;
				}
				$records[$rec->TARGET_DATE] = $rec;
			}
		}
		//showArrayValues($records);
	
		$targetBlockFields = $this->mi__m_report->getBlockWiseYearlyTargetFields();
		$rec = array();
		foreach($targetBlockFields as $f) $rec[$f] = '';
		$targetBlockData = array();
		for ($i = 1; $i <= 12; $i++) {
			$tMonth = (($i >= 10) ? ($i - 9) : ($i + 3));
			$mYears = $this->mi__m_report->getYearBySessionMonth($sessionId, $i);
			$tYears = (($i >= 10) ? $mYears[1] : $mYears[0]);
			$rec['TARGET_DATE'] = $tYears . '-' . str_pad($tMonth, 2, '0', STR_PAD_LEFT) . '-01';
	
			foreach($arrBlockIps as $k => $v) {
				$targetBlockData[$tYears . '-' . str_pad($tMonth, 2, '0', STR_PAD_LEFT) . '-01'][$k] = (object)$rec;
			}
		}
		$targetBlockRecs = $this->mi__m_report->getBlockWiseYearlyTarget($sessionId,$this->PROJECT_SETUP_ID);
		if($targetBlockRecs) {
			foreach ($targetBlockRecs as $rec) {
				$targetBlockData[$rec->TARGET_DATE][$rec->BLOCK_ID] = $rec;
			}
		}
		$data['blockCount'] = count($arrBlockIps);
		//exit;
		$data['targetBlockData'] = $targetBlockData;
	
		$data['records'] = $records;
		$myview = $this->load->view('mi/print/target_data_print_view', $data, true);
		array_push($this->message, getMyArray(null, $myview));
		echo createJSONResponse($this->message);
    }
    /**-------- Monthly Report --------*/
    public function printMonthly(){
		$this->load->model('mi/mi__m_project_setup');
        $this->PROJECT_SETUP_ID = $this->input->post('PROJECT_SETUP_ID');
        $isBlank = $this->input->post('blank_monthly');
        $monthlyDataId = 0;
        $data['isBlank'] = $isBlank;
		
		$monthlyDataId = (int)$this->input->post('monthId');
		
		$arrSetupStatus = $this->mi__m_project_setup->getEstimationStatus($this->PROJECT_SETUP_ID);
		$data['arrSetupStatus'] = $arrSetupStatus;
        if($isBlank && ($monthlyDataId==-1)) {
			$currentMonth = $this->input->post('dt');
            if($currentMonth>0)
                $currentMonth = date("Y-m-d", $currentMonth);
        }
        $monthlyTable = 'mi__t_monthly';
        $mMonthlyFields = $this->mi__m_report->getMonthlyFields();
        $currentMonthValues = array();
		if($monthlyDataId==-1){
			foreach($mMonthlyFields as $f) {
				$currentMonthValues[$f] = '';
			}
		}else{
			$recs = $this->db->get_where($monthlyTable, array('MONTHLY_DATA_ID' => $monthlyDataId));
			//echo 'Monthly query ='. $this->db->last_query();
	
			if($recs && $recs->num_rows()) {
				$rec = $recs->row();
				foreach($mMonthlyFields as $f) {
					$currentMonthValues[$f] = $rec->{$f};
				}
				$currentMonthValues['IP_TOTAL']=$currentMonthValues['KHARIF']+$currentMonthValues['RABI'];
				$currentMonth = $currentMonthValues['MONTH_DATE'];
			}
		}
        //echo "current month value =".$currentMonth ;
        //exit;
		$entryDate = strtotime($currentMonth);
        //$entryDate = $currentMonth;
		//echo $entryDate;
        $data['MONTH_DATE'] = $entryDate;
        $MONTH = date("n", $entryDate);
        $YEAR = date("Y", $entryDate);
        $sessionId = $this->mi__m_report->getSessionID($MONTH, $YEAR);
        //monthly remarks
        $mMonthlyRemarkFields = $this->mi__m_report->getMonthlyRemarkFields();
        $CURRENT_MONTH_REMARK_VALUES = array();
        for ($i = 0; $i < count($mMonthlyRemarkFields); $i++) {
            $currentMonthRemarkData[$mMonthlyRemarkFields[$i]] = '';
        }
        $arrWhich = array(
            'PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID,
            'MONTH_DATE' => $currentMonth
        );
        $recs = $this->db->get_where('mi__t_monthlydata_remarks', $arrWhich);
        $currentMonthRemarkValues = array();

        foreach($mMonthlyRemarkFields as $f)
            $currentMonthRemarkValues[$f] = '';

        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
			$recs->free_result();
			foreach($mMonthlyRemarkFields as $f)
				$currentMonthRemarkValues[$f] = $rec->{$f};
        }
        $data['monthly_remarks'] = $currentMonthRemarkValues;
        //PROGRESS
        $this->db->select('PROGRESS');
        $recs = $this->db->get_where('mi__t_progress', array(
			'PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID,
			'PROGRESS_DATE' => $currentMonth
        ));

        //echo "progress query == ". $this->db->last_query();
        $data['PROGRESS'] = 0;
        if($recs && $recs->num_rows()) {
            $prec = $recs->row();
			$recs->free_result();
            $data['PROGRESS'] = $prec->PROGRESS;
        }
        //echo 'data progress ='.$data['PROGRESS'];
        //exit;
        //monthly project status
        $mMonthlyStatusFields = $this->mi__m_report->getMonthlyStatusFields();
        $currentMonthStatusValues = array();
        foreach($mMonthlyStatusFields as $f)
            $currentMonthStatusValues[$f] = '';
        $recs = $this->db->get_where('mi__t_status_date', $arrWhich);
        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
			$recs->free_result();
            foreach($mMonthlyStatusFields as $f)
                $currentMonthStatusValues[$f] = $rec->{$f};
        }
        $data['monthlyStatusData'] = $currentMonthStatusValues;
        //get previous month
        $prevMonthValue = strtotime("-1month", $entryDate);
        $mAFStatus = array(
            'LA_CASES_STATUS', 'FA_CASES_STATUS', 'INTAKE_WELL_STATUS', 'PUMPING_UNIT_STATUS', 'PVC_LIFT_SYSTEM_STATUS',
            'PIPE_DISTRI_STATUS', 'DRIP_SYSTEM_STATUS',
            'WATER_STORAGE_TANK_STATUS', 'FERTI_PESTI_CARRIER_SYSTEM_STATUS', 'CONTROL_ROOMS_STATUS'
        );
        $previousMonthValues = $prevMonthStatus = array();
        $prevMonthExists = $currentMonthRecordExists = false;
        $recs_p = $this->db->get_where($monthlyTable, array(
			'PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID,
			'MONTH_DATE' => date("Y-m-d", $prevMonthValue)
		));
		if($recs_p && $recs_p->num_rows()){
			$recp = $recs_p->row();
			$recs->free_result();
			foreach($mMonthlyFields as $f)
				$previousMonthValues[$f] = $recp->{$f};
			$previousMonthValues['IP_TOTAL'] = $previousMonthValues['KHARIF']+$previousMonthValues['RABI'];				
			foreach($mAFStatus as $f)
				$prevMonthStatus[$f] = $recp->{$f};
			$prevMonthExists = true;
		}
        $arrWhere = array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID);
		$arrComponentStatus = array();
		$recs = $this->db->get_where('mi__m_setup_status', $arrWhere);
		if($recs && $recs->num_rows()) {
			$rec = $recs->row();
			$recs->free_result();
			foreach($mAFStatus as $f){
				$arrComponentStatus[$f] = $rec->{$f};
			}
		}
		//showArrayValues($arrComponentStatus);
		if(!$prevMonthExists) {
			foreach($mMonthlyFields as $f)
				$previousMonthValues[$f] = '';
			$previousMonthValues['IP_TOTAL'] = '';
			//get status from project setup
			foreach($mAFStatus as $f)
				$prevMonthStatus[$f] = $arrComponentStatus[$f];
		}
		//showArrayValues($previousMonthValues);
		$data['arrComponentStatus'] = $arrComponentStatus;
		$data['currentMonthRecordExists'] = $currentMonthRecordExists;
		$data['arrCurrentMonthData'] = $currentMonthValues;
		$data['arrPreviousMonthData'] = $previousMonthValues;
		$data['prevMonthStatus'] = $prevMonthStatus;
        $arrSTData = array();
		if($currentMonthRecordExists && ($currentMonthValues['WORK_STATUS'] == 5)) {
			$arrStFields = $this->getFields('mi__t_status_date');
			//$strSQL = 'SELECT * FROM mi__t_status_date WHERE PROJECT_SETUP_ID=' . $this->PROJECT_SETUP_ID;
			//$recs = $this->db->query($strSQL);
			$recs = $this->db->select('*')
					->from('mi__t_status_date')
					->where('PROJECT_SETUP_ID', $this->PROJECT_SETUP_ID)
					->get();
			if($recs && $recs->num_rows()){
				$rec = $recs->row();
				$recs->free_result();
				foreach($arrStFields as $f)
					$arrSTData[$f] = $rec->{$f};
			}
		}
        $data['completionStatusData'] = $arrSTData;
        $arrFields = array(
            'SESSION_ID',
            'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
            'FA_HA', 'FA_COMPLETED_HA',
            'L_EARTHWORK', 'C_MASONRY',
            'C_PIPEWORK', 'C_DRIP_PIPE', 'C_WATERPUMP', 'K_CONTROL_ROOMS',
            'KHARIF', 'RABI'
        );//'EXPENDITURE_TOTAL', 'EXPENDITURE_WORKS',
        $currentFinancialMonth = $this->mi__m_report->getFinancialMonthByMonth($MONTH);
        $totalInCurrentFY = $achievementTillLastFY = array();
        //init
		foreach($arrFields as $f)
			$totalInCurrentFY[$f] = $achievementTillLastFY[$f] = 0;

		$totalInCurrentFY['SESSION_ID'] = $sessionId;
		$totalInCurrentFY['PROJECT_SETUP_ID'] = $this->PROJECT_SETUP_ID;
		$achievementTillLastFY['SESSION_ID'] = $sessionId;
		$achievementTillLastFY['PROJECT_SETUP_ID'] = $this->PROJECT_SETUP_ID;

		$arrSumFields = array(
			'LA_NA'=>'LA_NO', 'FA_NA'=>'FA_HA', 'L_EARTHWORK_NA'=>'L_EARTHWORK', 'C_MASONRY_NA'=>'C_MASONRY',
			'C_PIPEWORK_NA'=>'C_PIPEWORK', 'C_DRIP_PIPE_NA'=>'C_DRIP_PIPE', 
			'C_WATERPUMP_NA'=>'C_WATERPUMP', 'K_CONTROL_ROOMS_NA'=>'K_CONTROL_ROOMS',
			'K'=>'KHARIF', 'R'=>'RABI'
		);
		$arrSF = $arrValidFields = array();
		foreach($arrSumFields as $k=>$v){
			//echo '#$arrSetupStatus[$k]:'.$arrSetupStatus[$k]."#\n";
			if(!in_array($k, array('K', 'R'))){
				if($arrSetupStatus[$k]==1) continue;
				//array_push($arrValidFields, $v);
			}
			array_push($arrValidFields, $v);
			array_push($arrSF, ' IFNULL(SUM('.$v.'), 0) AS '.$v.' ');

			//echo $k .'=='.$v."\n";
			if($k=='LA_NA'){
				array_push($arrValidFields, 'LA_HA');
				array_push($arrSF, ' IFNULL(SUM(LA_HA), 0) AS LA_HA ');
				array_push($arrValidFields, 'LA_COMPLETED_NO');
				array_push($arrSF, ' IFNULL(SUM(LA_COMPLETED_NO), 0) AS LA_COMPLETED_NO ');
				array_push($arrValidFields, 'LA_COMPLETED_HA');
				array_push($arrSF, ' IFNULL(SUM(LA_COMPLETED_HA), 0) AS LA_COMPLETED_HA ');
			}
			if($k=='FA_NA'){
				array_push($arrValidFields, 'FA_COMPLETED_HA');
				array_push($arrSF, ' IFNULL(SUM(FA_COMPLETED_HA), 0) AS FA_COMPLETED_HA ');
			}
		}
		//showArrayValues($arrValidFields);
		$sumField = implode(',', $arrSF);
		if($currentFinancialMonth != 0) {
			//get total in this financial year
			/*SELECT SUM(EXPENDITURE_TOTAL) AS EXPENDITURE_TOTAL,
				SUM(EXPENDITURE_WORKS) AS EXPENDITURE_WORKS,*/
			$strSQL = 'SELECT '.$sumField.' FROM mi__t_monthly WHERE PROJECT_SETUP_ID=' . $this->PROJECT_SETUP_ID .
				' AND SESSION_ID='.$sessionId .
				' AND MONTH_DATE <="' . date("Y-m-d", $entryDate).'"';
			$recs = $this->db->query($strSQL);
			//echo $this->db->last_query();exit;
			if($recs && $recs->num_rows()) {
				$rec = $recs->row();
				$recs->free_result();
				foreach($arrValidFields as $f){
					if(!($f == 'SESSION_ID')) $totalInCurrentFY[$f] = $rec->{$f};
				}
				$totalInCurrentFY['SESSION_ID'] = $sessionId;
				$totalInCurrentFY['PROJECT_SETUP_ID'] = $this->PROJECT_SETUP_ID;
			}
			//echo $this->db->last_query();
		}
        $data['arrCFY'] = $totalInCurrentFY;
		
		//showArrayValues($totalInCurrentFY);
		//showArrayValues($arrValidFields);
        //GET DATA TILL LAST FINANCIAL YEAR
        $strSQL = 'SELECT  '.$sumField.' FROM mi__t_monthly WHERE PROJECT_SETUP_ID=' . $this->PROJECT_SETUP_ID .
            ' AND SESSION_ID<' . $sessionId .' GROUP BY PROJECT_SETUP_ID';
        $recs = $this->db->query($strSQL);
		//echo $strSQL;
        if($recs && $recs->num_rows()) {
			$rec = $recs->row();
			$recs->free_result();
			foreach($arrValidFields as $f){
				if($f=='PROJECT_SETUP_ID') continue;
				if($f != 'SESSION_ID')
					$achievementTillLastFY[$f] = $rec->{$f};
			}
        }
		//showArrayValues($achievementTillLastFY);
		//$data['achievementTillLastFY'] = $achievementTillLastFY;
		$data['arrTLY'] = $achievementTillLastFY;
	
		$arrFieldsForProgress = array(
			'L_EARTHWORK', 'C_MASONRY',
			'C_PIPEWORK', 'C_DRIP_PIPE', 'C_WATERPUMP', 'K_CONTROL_ROOMS',
			'LA_NO', 'LA_HA', 'FA_HA',
			'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 'FA_COMPLETED_HA'
	
		); //'EXPENDITURE_TOTAL','EXPENDITURE_WORK',
        //'IP_TOTAL', 'KHARIF', 'RABI'
        $arrEstimation = array();
        //init
        for ($iCount = 0; $iCount < count($arrFieldsForProgress); $iCount++)
            $arrEstimation[$arrFieldsForProgress[$iCount]] = 0;
        //ESTIMATION_DATA [start]
        $strSQL = 'SELECT e.*, p.RAA_DATE FROM mi__t_estimated_qty as e 
				LEFT JOIN mi__t_raa_project as p on(p.RAA_PROJECT_ID=e.RAA_ID)
					WHERE e.PROJECT_SETUP_ID in (' . $this->PROJECT_SETUP_ID . ') and p.RAA_DATE<="' . $currentMonth . '"
				ORDER BY p.RAA_DATE desc 
				LIMIT 0, 1';
        $recs = $this->db->query($strSQL);
        //echo $this->db->last_query();
        //exit;
        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
        }else{
            $this->db->select( implode(',',$arrFieldsForProgress).',KHARIF,RABI, SUM(KHARIF+RABI) as IP_TOTAL')
                    ->from('mi__t_estimated_qty')
                    ->join('mi__ip_design','mi__ip_design.PROJECT_SETUP_ID=mi__t_estimated_qty.PROJECT_SETUP_ID')
                    ->where_in('mi__t_estimated_qty.PROJECT_SETUP_ID', $this->PROJECT_SETUP_ID)
                    ->where('ADDED_BY', 0);
            $recs = $this->db->get();

            //echo  $this->db->last_query();
            /*$this->db->where_in('PROJECT_SETUP_ID', $this->PROJECT_SETUP_ID);
            $this->db->where('ADDED_BY', 0);
            $recs = $this->db->get('mi__t_estimated_qty');*/

            if($recs && $recs->num_rows())
                $rec = $recs->row();
        }

        array_push($arrFieldsForProgress,'KHARIF','RABI','IP_TOTAL');
        for ($iCount = 0; $iCount < count($arrFieldsForProgress); $iCount++) {
            $arrEstimation[$arrFieldsForProgress[$iCount]] = $rec->{$arrFieldsForProgress[$iCount]};
        }
        /*$this->db->where_in('PROJECT_ID', $this->PROJECT_SETUP_ID);
        $this->db->order_by('ESTIMATED_QTY_ID', 'DESC');
        //$this->db->order_by('LIMIT', 0, 1);
        $recs = $this->db->get('pmon__t_estimated_qty', 1, 0);
        //echo $this->db->last_query();
        if($recs && $recs->num_rows()){
            $rec = $recs->row();
            for($iCount=0; $iCount<count($arrFieldsForProgress);$iCount++){
                $arrEstimation[ $arrFieldsForProgress[$iCount] ] = $rec->{$arrFieldsForProgress[$iCount]};
            }
        }*/
        //echo $this->db->last_query();
        $data['arrEstimationData'] = $arrEstimation;
        $arrWhich = array(
            'PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID,
            'TARGET_DATE' => $currentMonth
        );
        //'YEARLY_TARGET_DATE'=>$currentMonth
        $data['TARGET_FLAG'] = 0;
        $data['BUDGET_AMOUNT'] = 0;
        $data['SUBMISSION_DATE'] = '';
        $recs = $this->db->get_where('mi__t_yearlytargets', $arrWhich);
        //echo $this->db->last_query();
        //showArrayValues($rec);
        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
            //$data['BUDGET_AMOUNT'] = $rec->BUDGET_AMOUNT;
            //$data['SUBMISSION_DATE'] = $rec->SUBMISSION_DATE;
            $data['TARGET_FLAG'] = 1;
        }
        //get actual completion date
        $data['ACTUAL_COMPLETION_DATE'] = '0000-00-00';
        $arrWhere = array('PROJECT_SETUP_ID' => $this->PROJECT_SETUP_ID);
        $this->db->select('PROJECT_COMPLETION_DATE, AA_DATE AS PROJECT_START_DATE,WORK_NAME,WORK_NAME_HINDI,PROJECT_CODE');
        $recs = $this->db->get_where('mi__m_project_setup', $arrWhere);
        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
            $data['ACTUAL_COMPLETION_DATE'] = $rec->PROJECT_COMPLETION_DATE;
            $data['PROJECT_START_DATE'] = $rec->PROJECT_START_DATE;
            $data['PROJECT_NAME'] = $rec->WORK_NAME . ' - ' . $rec->WORK_NAME_HINDI;
            $data['PROJECT_CODE'] = $rec->PROJECT_CODE;
        }
        //get project name
        /*$this->db->select('PROJECT_NAME, PROJECT_CODE, PROJECT_NAME_HINDI');
        $recs = $this->db->get_where('__projects', $arrWhere);
        if($recs && $recs->num_rows()){
            $prec = $recs->row();
            $data['PROJECT_NAME'] = $prec->PROJECT_NAME .' - '.$prec->PROJECT_NAME_HINDI;
            $data['PROJECT_CODE']= $prec->PROJECT_CODE;
        }
        */
        $data['setupData'] = $this->mi__m_report->getSetupData($this->PROJECT_SETUP_ID);
        $data['statusData'] = $this->mi__m_report->getStatusData($this->PROJECT_SETUP_ID);

        //$data['arrBlockData'] = $this->mi__m_report->getBlockwiseIP($currentMonth);
        $YEAR = date("Y", $entryDate);
        $MONTH = date("n", $entryDate);
        $sessionId = $this->mi__m_report->getSessionID($MONTH, $YEAR);

        $data['arrBlockData'] = $this->mi__m_report->getBlockwiseIP($currentMonth, $sessionId, $this->PROJECT_SETUP_ID);
        //echo 'aa';


        //$myview = $this->load->view('mi/print/monthly_data_print_view', $data, true);
        $myview = $this->load->view('mi/print/monthly_data_print_view_table', $data, true);
        //echo $myview ;
        array_push($this->message, getMyArray(null, $myview));
        echo createJSONResponse($this->message);
    }

    public function printMP()
    {
        //$PROJECT_SETUP_ID = (int) $this->input->post('PROJECT_SETUP_ID');
        $projectId = (int)$this->input->post('PROJECT_ID');
        $this->db->select('PROGRESS_DATE, PROGRESS');
        $this->db->order_by('PROGRESS_DATE', 'ASC');
        $recs = $this->db->get_where('mi__t_progress', array('PROJECT_SETUP_ID' => $projectId));
        $content = '<table border="0" cellpadding="8" cellspacing="2" class="ui-widget-content" id="rptMP">
		<thead>
		<tr><th class="ui-state-default" valign="middle">Month</th>
					<th class="ui-state-default" valign="middle">Progress</th></tr>
					</thead><tbody>';
        if($recs && $recs->num_rows()) {
            foreach ($recs->result() as $rec) {
                $content .= '<tr><td class="ui-widget-content" valign="middle">' . date("M, Y", strtotime($rec->PROGRESS_DATE)) . '</td>
					<td class="ui-widget-content" valign="middle" align="center">' . $rec->PROGRESS . '</td></tr>';
            }
        }
        $content .= '</tbody></table>
		<script type="text/javascript">
			$().ready(function(){
				var $demopp = $("#rptMP");
				$demopp.floatThead({
					scrollContainer: function($table){
						return $table.closest(".wrapper");
					}
				});
			});
		</script>';
        array_push($this->message, getMyArray(null, $content));
        echo createJSONResponse($this->message);
    }

    //not using this function
    /*private function findAllDataCurFinYr($sessionId)
    {
        $strSQL = 'SELECT sum(EXPENDITURE) as EXPENDITURE,
				sum(LA_NO) as LA_NO,
				sum(LA_HA) as LA_HA,
				sum(FA_HA) as FA_HA,
				sum(HEAD_WORKS_EARTHWORK) as HEAD_WORKS_EARTHWORK,
				sum(HEAD_WORKS_MASONRY) as HEAD_WORKS_MASONRY,
				sum(CANAL_EARTHWORK) as CANAL_EARTHWORK,
				sum(CANAL_LINING) as CANAL_LINING,
				sum(HEAD_WORKS_EARTHWORK) as HEAD_WORKS_EARTHWORK,
				sum(IRRIGATION_POTENTIAL) as IRRIGATION_POTENTIAL,
                sum(CANAL_STRUCTURES) as CANAL_STRUCTURES
			FROM mi__t_yearlytargets
			WHERE SESSION_ID = ' . $sessionId . '
				AND PROJECT_SETUP_ID =' . $this->PROJECT_SETUP_ID . '
			GROUP BY SESSION_ID,PROJECT_SETUP_ID';
        $data = $this->db->query($qry)->result();
        return $data;
    }*/

    //not using this function
    /*private function checkMonthlyLockStatus($PROJECT_SETUP_ID, $date)
    {
        $rec = $this->db->get_where('mi__t_locks', array('PROJECT_SETUP_ID' => $PROJECT_SETUP_ID));
        if($rec && $rec->num_rows() == 1) {
            $row = $rec->row();
            if($row->MONTH_LOCK < $date) {
                return 0;
            }
            return 1;
        }
        return 0;
    }*/
}
