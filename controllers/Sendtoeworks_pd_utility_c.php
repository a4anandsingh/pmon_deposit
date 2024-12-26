<?php include_once("Project_library.php");
class Sendtoeworks_pd_utility_c extends Project_library
{
	var $proID, $OFF_ARRAY, $RAA_ID, $PROJECT_SETUP_ID,
		$SAVE_MODE, $message, $MODULE_KEY, $IS_PROJECT_LOCKED,
		$block, $PROJECT_ID, $data, $previousEstimation, $currentEstimation;
	function __construct()
	{
		parent::__construct();
		$this->PROJECT_ID = 0;
		$this->message = array();
		$this->IS_PROJECT_LOCKED = FALSE;
	}
	public function index()
	{
		$data = array();
		$this->session->set_userdata(array('PROJECT_TYPE_ID' => 1));
		$arrProjectType = array('', 'Minor', 'Medium', 'Major');
		$data['message'] = '';
		$data['page_heading'] = pageHeading('DEPOSIT PROMON -   Send Data to Eworks');
		$this->load->library('office_filter');
		$data['office_list'] = $this->office_filter->office_list();
		$data['message'] = '';
		$data['project_list'] = $this->createGrid();
		$data['session_drop_down'] = $this->getSessionDropDown();
		$data['year_drop_down'] = $this->getYearDropDown();
		$data['month_drop_down'] = $this->getMonthDropDown();
		$this->load->view('pmon_deposit/utility/report_index_view', $data);
	}
	/**-------- Report ----------*/
	public function showOfficeFilterBox()
	{
		//$data['instance_name'] = 'search_office';
		$data = array();
		$data['prefix'] = 'search_office';
		$data['show_sdo'] = FALSE;
		$data['row'] = '<tr><td class="ui-widget-content"><strong>Project Status </strong></td>' .
			'<td class="ui-widget-content">
				<select name="SEARCH_PROJECT_STATUS" style="width:400px" class="office-select" id="SEARCH_PROJECT_STATUS">
				<option value="0">All Projects (सभी परियोजनाएं)</option>
				<option value="1">Ongoing Projects (निर्माणाधीन परियोजनाएं)</option>
				<option value="5">Completed Projects (पूर्ण परियोजनाएं)</option>
				<option value="6">Dropped Projects</option>
				</select>
				</td>
			</tr>
			<tr>
			<td class="ui-widget-content"><strong>Project Name</strong></td>
			<td class="ui-widget-content">
				<input type="text" value="" name="SEARCH_PROJECT_NAME" id="SEARCH_PROJECT_NAME">
			</td>
			</tr>
			<tr><td colspan="2" class="ui-widget-content">' . getButton('Search', 'refreshSearch()', 4, 'cus-zoom') . '</td></tr>';
		$this->load->view('setup/office_filter_view', $data);
	}
	private function createGrid()
	{
		$buttons = array();
		$mfunctions = array();
		array_push($mfunctions, "onSelectRow: function(ids){fillProjectId();}");
		$aData = array(
			'set_columns' => array(
				array(
					'label' => 'Project Name',
					'name' => 'PROJECT_NAME',
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
				/*array(
					'label' => 'परियोजना',
					'name' => 'PROJECT_NAME_HINDI',
					'width' => 70,
					'align' => "left",
					'resizable' => false,
					'sortable' => true,
					'hidden' => false,
					'view' => true,
					'search' => true,
					'formatter' => '',
					'searchoptions' => ''
				),*/
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
					'label' => 'Head',
					'name' => 'DEPOSIT_SCHEME_ID',
					'width' => 50,
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
					'width' => 15,
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
					'width' => 15,
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
					'width' => 20,
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
					'width' => 20,
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
					'label' => 'Action',
					'name' => 'ACTION',
					'width' => 20,
					'align' => "center",
					'resizable' => false,
					'sortable' => false,
					'hidden' => false,
					'view' => true,
					'search' => true
				),
				array(
					'label' => 'id',
					'name' => 'PROJECT_ID',
					'width' => 70,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => true,
					/*'key'=>false,*/
					'search' => true,
					'view' => true,
					'formatter' => '',
					'searchoptions' => ''
				),
				array(
					'label' => 'project_setup_id',
					'name' => 'PROJECT_SETUP_ID',
					'width' => 70,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => true,
					/*'key'=>false,*/
					'search' => true,
					'view' => true,
					'formatter' => '',
					'searchoptions' => ''
				),
				array(
					'label' => 'is_mi',
					'name' => 'IS_MI',
					'width' => 70,
					'align' => "center",
					'resizable' => false,
					'sortable' => true,
					'hidden' => true,
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
			'height' => '',
			'altRows' => true,
			'rownumbers' => true,
			'sort_name' => 'PROJECT_NAME',
			'sort_order' => 'asc',
			'primary_key' => 'PROJECT_ID',
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
	public function getProjectListGrid()
	{
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);
		//$SDO_ID = $this->input->post('SDO_ID');
		/* =============== */
		if ($this->input->post('project_id')) {
			array_push($objFilter->SQL_PARAMETERS, array("PROJECT_ID" => $this->input->post('project_id')));
		}
		if ($this->input->post('SEARCH_PROJECT_NAME')) {
			array_push($objFilter->SQL_PARAMETERS, array("PROJECT_NAME", 'LIKE', $this->input->post('SEARCH_PROJECT_NAME')));
		}
		$searchProjectStatus = $this->input->post('SEARCH_PROJECT_STATUS');
		$w = '';
		switch ($searchProjectStatus) {
			case 1:
				$w = ' AND ((PROJECT_STATUS<5) OR ((PROJECT_STATUS>=5) AND (SE_COMPLETION=0))) ';
				break;
			case 5:
				$w = ' AND ((PROJECT_STATUS=5) AND (SE_COMPLETION=1)) ';
				break;
			case 6:
				$w = ' AND ((PROJECT_STATUS=6) AND (SE_COMPLETION=1)) ';
				break;
		}

		$EE_ID = $this->input->post('EE_ID');
		$CE_ID = $this->input->post('CE_ID');
		$SE_ID = $this->input->post('SE_ID');
		//$SDO_ID = $this->input->post('SDO_ID');
		//if($EE_ID==false && $CE_ID==false && $SE_ID==false && $SDO_ID==false){
		if ($EE_ID == false && $CE_ID == false && $SE_ID == false) {
			$EE_ID = $this->session->userData('EE_ID');
			$SE_ID = $this->session->userData('SE_ID');
			$CE_ID = $this->session->userData('CE_ID');
			//$SDO_ID = $this->session->userData('SDO_ID');
		}
		if ($EE_ID == 0 && $SE_ID == 0 && $CE_ID == 0) {
			//NO OPTION SELECTED BY E-IN-C
			//array_push(	$objFilter->WHERE,  ' 1 GROUP BY PROJECT_ID');
			/*$objFilter->SQL = 'SELECT DISTINCT(PROJECT_SETUP_ID), PROJECT_ID, PROJECT_START_DATE,
					PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, MONTH_LOCK, 
					CONCAT(SESSION_START_YEAR, "-",SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
					IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					 "<span class=\'cus-bullet-green\'></span>") as LOCKED,
					 IF(PROJECT_STATUS<5, "<span class=\'cus-eye\'></span>",
					 IF(PROJECT_STATUS=5, "<span class=\'cus-thumb-up\'></span>",
					 "<span class=\'cus-cancel\'></span>")) as MY_PROJECT_STATUS 
				FROM dep_pmon__v_projectlist_with_lock 
				WHERE PROJECT_TYPE_ID='.$this->session->userData('PROJECT_TYPE_ID') . $w;*/
			$objFilter->SQL = "SELECT 
                    a.PROJECT_SETUP_ID, 
                    a.PROJECT_ID, 
                    a.PROJECT_START_DATE,
                    a.PROJECT_NAME,
                    a.PROJECT_NAME_HINDI,
                    a.PROJECT_CODE, 
                    a.MONTH_LOCK, 
                    a.TARGET_LOCK_SESSION,
                    a.LOCKED,
                    a.MY_PROJECT_STATUS,
                    OFFICE_EE_ID, OFFICE_SE_ID, OFFICE_CE_ID, IS_MI,HEAD,SETUP_LOCK
                FROM(
                        SELECT 
                            DISTINCT(PROJECT_SETUP_ID), 
                            PROJECT_ID, 
                            PROJECT_START_DATE,
                            PROJECT_NAME,
                            PROJECT_NAME_HINDI,
                            PROJECT_CODE, 
                            MONTH_LOCK, SETUP_LOCK,
                            CONCAT(SESSION_START_YEAR, \"-\",SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
                            IF(SETUP_LOCK=1, \"<span class=\'cus-lock\'></span>\", \"<span class=\'cus-bullet-green\'></span>\") as LOCKED,
                            IF(PROJECT_STATUS<5, \"<span class=\'cus-eye\'></span>\", IF(PROJECT_STATUS=5, \"<span class=\'cus-thumb-up\'></span>\", \"<span class=\'cus-cancel\'></span>\")) as MY_PROJECT_STATUSx,
							PROJECT_STATUS as MY_PROJECT_STATUS,
                            OFFICE_EE_ID, OFFICE_SE_ID, OFFICE_CE_ID,
                            '0' as IS_MI, CONCAT(HEAD,' ',SCHEME_NAME_HINDI) AS HEAD
                        FROM dep_pmon__v_projectlist_with_lock
                        LEFT JOIN " . AGREEMENT_DB . ".dep__m_scheme AS tblscheme on tblscheme.ID = dep_pmon__v_projectlist_with_lock.DEPOSIT_SCHEME_ID 
                            WHERE PROJECT_TYPE_ID=1
                        UNION ALL 
                        SELECT
                                dep_mi__m_project_setup.PROJECT_SETUP_ID,
                                dep_mi__m_project_setup.PROJECT_ID,
                                dep_mi__m_project_setup.AA_DATE AS PROJECT_START_DATE,
                                dep_mi__m_project_setup.PROJECT_NAME,
                                dep_mi__m_project_setup.PROJECT_NAME_HINDI,
                                dep_mi__m_project_setup.PROJECT_CODE,
                                dep_mi__t_locks.MONTH_LOCK,	SETUP_LOCK,
                                CONCAT(__sessions.SESSION_START_YEAR, \"-\",__sessions.SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
                                IF(dep_mi__t_locks.SETUP_LOCK =1, \"<span class=\'cus-lock\'></span>\", \"<span class=\'cus-bullet-green\'></span>\") as LOCKED,
                                IF(dep_mi__m_project_setup.WORK_STATUS<5, \"<span class=\'cus-eye\'></span>\", 
                                IF(dep_mi__m_project_setup.WORK_STATUS=5, \"<span class=\'cus-thumb-up\'></span>\", \"<span class=\'cus-cancel\'></span>\")) as MY_PROJECT_STATUSx,
								dep_mi__m_project_setup.WORK_STATUS as MY_PROJECT_STATUS,
								office_ee.OFFICE_ID AS OFFICE_EE_ID,
                                office_se.OFFICE_ID AS OFFICE_SE_ID,	
                                office_ce.OFFICE_ID AS OFFICE_CE_ID,					
                                '1' as IS_MI, CONCAT(HEAD,' ',SCHEME_NAME_HINDI) AS HEAD
                        FROM
                                dep_mi__m_project_setup 	
                        LEFT JOIN dep_mi__m_projects_office pro_office ON ( dep_mi__m_project_setup.PROJECT_SETUP_ID = pro_office.PROJECT_SETUP_ID )
                        LEFT JOIN __offices office_ee ON  ( pro_office.EE_ID = office_ee.OFFICE_ID )  
                        LEFT JOIN __offices office_se ON  ( office_ee.PARENT_OFFICE_ID = office_se.OFFICE_ID ) 
                        LEFT JOIN __offices office_ce ON  ( office_se.PARENT_OFFICE_ID = office_ce.OFFICE_ID ) 
                        LEFT JOIN dep_mi__t_locks ON  ( dep_mi__m_project_setup.PROJECT_SETUP_ID = dep_mi__t_locks.PROJECT_SETUP_ID ) 
                        LEFT JOIN __sessions ON  ( dep_mi__t_locks.TARGET_LOCK_SESSION_ID = __sessions.SESSION_ID ) 
                        LEFT JOIN __sessions setup_session ON  ( dep_mi__m_project_setup.SESSION_ID = setup_session.SESSION_ID )
                        LEFT JOIN " . AGREEMENT_DB . ".dep__m_scheme AS tblscheme on tblscheme.ID = dep_mi__m_project_setup.DEPOSIT_SCHEME_ID 
                        WHERE PROJECT_TYPE_ID='1' AND dep_mi__t_locks.SETUP_LOCK=1 
                        ) as a  where 1 $w";
		} else {
			$EEE = ''; //($SDO_ID==0)? '' : ' OFFICE_SDO_ID='.$SDO_ID;
			$EEE .= ($EE_ID == 0) ? '' : (($EEE == '') ? '' : ' AND ') . 'OFFICE_EE_ID=' . $EE_ID;
			$EEE .= ($SE_ID == 0) ? '' : (($EEE == '') ? '' : ' AND ') . 'OFFICE_SE_ID=' . $SE_ID;
			$EEE .= ($CE_ID == 0) ? '' : (($EEE == '') ? '' : ' AND ') . 'OFFICE_CE_ID=' . $CE_ID;
			//$EEE .= ($SDO_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SDO_ID='.$SDO_ID;
			if ($this->session->userData('HOLDING_PERSON') != 4) {
				//$EEE .= ' GROUP BY PROJECT_ID';
			}
			//pmon__v_projects_setup 
			array_push($objFilter->WHERE, $EEE); //. ' GROUP BY PROJECT_ID');
			/*$objFilter->SQL = 'SELECT DISTINCT PROJECT_SETUP_ID, PROJECT_ID, PROJECT_START_DATE,
					PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE,  MONTH_LOCK, 
					CONCAT(SESSION_START_YEAR, "-",SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
					IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					 "<span class=\'cus-bullet-green\'></span>") as LOCKED,
					 IF(PROJECT_STATUS<5, "<span class=\'cus-eye\'></span>",
					 IF(PROJECT_STATUS=5, "<span class=\'cus-thumb-up\'></span>",
					 "<span class=\'cus-cancel\'></span>")) as MY_PROJECT_STATUS  
				FROM dep_pmon__v_projectlist_with_lock 
				WHERE PROJECT_TYPE_ID='.$this->session->userData('PROJECT_TYPE_ID') . $w;*/

			$objFilter->SQL = "SELECT 
                    a.PROJECT_SETUP_ID, 
                    a.PROJECT_ID, 
                    a.PROJECT_START_DATE,
                    a.PROJECT_NAME,
                    a.PROJECT_NAME_HINDI,
                    a.PROJECT_CODE, 
                    a.MONTH_LOCK, 
                    a.TARGET_LOCK_SESSION,
                    a.LOCKED,
                    a.MY_PROJECT_STATUS,
                    OFFICE_EE_ID, OFFICE_SE_ID, OFFICE_CE_ID, IS_MI,HEAD,SETUP_LOCK
                FROM(			
                        SELECT 
                            DISTINCT(PROJECT_SETUP_ID), 
                            PROJECT_ID, 
                            PROJECT_START_DATE,
                            PROJECT_NAME,
                            PROJECT_NAME_HINDI,
                            PROJECT_CODE, 
                            MONTH_LOCK, SETUP_LOCK,
                            CONCAT(SESSION_START_YEAR, \"-\",SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
                            IF(SETUP_LOCK=1, \"<span class=\'cus-lock\'></span>\", \"<span class=\'cus-bullet-green\'></span>\") as LOCKED,
                            IF(PROJECT_STATUS<5, \"<span class=\'cus-eye\'></span>\", IF(PROJECT_STATUS=5, \"<span class=\'cus-thumb-up\'></span>\", \"<span class=\'cus-cancel\'></span>\")) as MY_PROJECT_STATUSx,
							PROJECT_STATUS as MY_PROJECT_STATUS,
							OFFICE_EE_ID, OFFICE_SE_ID, OFFICE_CE_ID,
                            '0' as IS_MI, CONCAT(HEAD,' ',SCHEME_NAME_HINDI) AS HEAD
                        FROM dep_pmon__v_projectlist_with_lock 
                        LEFT JOIN " . AGREEMENT_DB . ".dep__m_scheme AS tblscheme on tblscheme.ID = dep_pmon__v_projectlist_with_lock.DEPOSIT_SCHEME_ID
                            WHERE PROJECT_TYPE_ID=1
                        UNION ALL 
                        SELECT
                                dep_mi__m_project_setup.PROJECT_SETUP_ID,
                                dep_mi__m_project_setup.PROJECT_ID,
                                dep_mi__m_project_setup.AA_DATE AS PROJECT_START_DATE,
                                dep_mi__m_project_setup.PROJECT_NAME,
                                dep_mi__m_project_setup.PROJECT_NAME_HINDI,
                                dep_mi__m_project_setup.PROJECT_CODE,
                                dep_mi__t_locks.MONTH_LOCK,		SETUP_LOCK,
                                CONCAT(__sessions.SESSION_START_YEAR, \"-\",__sessions.SESSION_END_YEAR)AS TARGET_LOCK_SESSION,
                                IF(dep_mi__t_locks.SETUP_LOCK =1, \"<span class=\'cus-lock\'></span>\", \"<span class=\'cus-bullet-green\'></span>\") as LOCKED,
                                IF(dep_mi__m_project_setup.WORK_STATUS<5, \"<span class=\'cus-eye\'></span>\", 
								IF(dep_mi__m_project_setup.WORK_STATUS=5, \"<span class=\'cus-thumb-up\'></span>\", \"<span class=\'cus-cancel\'></span>\")) as MY_PROJECT_STATUSx,
								dep_mi__m_project_setup.WORK_STATUS as MY_PROJECT_STATUS,
                                office_ee.OFFICE_ID AS OFFICE_EE_ID,
                                office_se.OFFICE_ID AS OFFICE_SE_ID,	
                                office_ce.OFFICE_ID AS OFFICE_CE_ID,					
                                '1' as IS_MI,CONCAT(HEAD,' ',SCHEME_NAME_HINDI) AS HEAD
                        FROM
                        dep_mi__m_project_setup
                        LEFT JOIN dep_mi__m_projects_office pro_office ON ( dep_mi__m_project_setup.PROJECT_SETUP_ID = pro_office.PROJECT_SETUP_ID )
                        LEFT JOIN __offices office_ee ON  ( pro_office.EE_ID = office_ee.OFFICE_ID )  
                        LEFT JOIN __offices office_se ON  ( office_ee.PARENT_OFFICE_ID = office_se.OFFICE_ID ) 
                        LEFT JOIN __offices office_ce ON  ( office_se.PARENT_OFFICE_ID = office_ce.OFFICE_ID ) 
                        LEFT JOIN dep_mi__t_locks ON  ( dep_mi__m_project_setup.PROJECT_SETUP_ID = dep_mi__t_locks.PROJECT_SETUP_ID ) 
                        LEFT JOIN __sessions ON  ( dep_mi__t_locks.TARGET_LOCK_SESSION_ID = __sessions.SESSION_ID ) 
                        LEFT JOIN __sessions setup_session ON  ( dep_mi__m_project_setup.SESSION_ID = setup_session.SESSION_ID )
                        LEFT JOIN " . AGREEMENT_DB . ".dep__m_scheme AS tblscheme on tblscheme.ID = dep_mi__m_project_setup.DEPOSIT_SCHEME_ID 
                        WHERE PROJECT_TYPE_ID='1' AND dep_mi__t_locks.SETUP_LOCK=1 
                        ) as a  where 1 $w";
		}
		/* =============== */
		/*$fields = array(
			array('PROJECT_NAME', FALSE),
			array('PROJECT_NAME_HINDI', FALSE),
			array('PROJECT_CODE', FALSE),
			array('HEAD', FALSE),
			array('MY_PROJECT_STATUS', FALSE),
			array('LOCKED', FALSE),
			array('TARGET_LOCK_SESSION', FALSE),
			array('MONTH_LOCK', FALSE),
			array('PROJECT_ID', FALSE),
			array('PROJECT_SETUP_ID', FALSE),
			array('IS_MI', FALSE),
		);
		echo $objFilter->getJSONCode('PROJECT_SETUP_ID', $fields);
		*/
		$objFilter->executeMyQuery();
		if ($objFilter->TOTAL_RECORDS) {
			foreach ($objFilter->RESULT as $row) {
				$fieldValues = array();
				array_push($fieldValues, '"' . addslashes($row->PROJECT_NAME ."<br />".$row->PROJECT_NAME_HINDI ) . '"');
				
				//array_push($fieldValues, '"' . addslashes($row->PROJECT_NAME_HINDI) . '"');

				array_push($fieldValues, '"' . addslashes($row->PROJECT_CODE) . '"');
				array_push($fieldValues, '"' . addslashes($row->HEAD) . '"');
				// array_push($fieldValues, '"' . addslashes($row->MY_PROJECT_STATUS) . '"');
				// array_push($fieldValues, '"' . addslashes($row->LOCKED) . '"');

				if($row->MY_PROJECT_STATUS<5){
					array_push($fieldValues, '"<span class=\"cus-eye\"></span>"');
				}else if($row->MY_PROJECT_STATUS<5){
					array_push($fieldValues, '"<span class=\"cus-thumb-up\"></span>"');
				}else{
					array_push($fieldValues, '"<span class=\"cus-cancel\"></span>"');
				}

				if($row->SETUP_LOCK==1){
					array_push($fieldValues, '"<span class=\"cus-lock\"></span>"');
				}else{
					array_push($fieldValues, '"<span class=\"cus-bullet-green\"></span>"');
				}
				
				array_push($fieldValues, '"' . addslashes($row->TARGET_LOCK_SESSION) . '"');
				array_push($fieldValues, '"' . addslashes($row->MONTH_LOCK) . '"');
				array_push(
					$fieldValues,
					'"' . addslashes(
						getButton(
							'Send Data',
							'sendData(' . $row->PROJECT_ID . ',' . $row->PROJECT_SETUP_ID . ','. $row->IS_MI .')',
							4,
							'cus-calendar-view-day'
						)
					) . '"'
				);
				array_push($fieldValues, '"' . addslashes($row->PROJECT_ID) . '"');
				array_push($fieldValues, '"' . addslashes($row->PROJECT_SETUP_ID) . '"');
				array_push($fieldValues, '"' . addslashes($row->IS_MI) . '"');
				

				array_push($objFilter->ROWS, '{"id":"' . $row->PROJECT_SETUP_ID . '", "cell":[' . implode(',', $fieldValues) . ']}');
			}
		}
		echo $objFilter->getJSONCodeByRow();
		
		//echo '<br /><br /><br /><br />'. $objFilter->PREPARED_SQL;
	}
	/**-------- Setup Report ----------*/
	public function getTargetSessionOptions()
	{
		$PROJECT_ID = (int) $this->input->post('PROJECT_ID');
		$this->db->distinct();
		$this->db->select('SESSION_ID');
		$this->db->order_by('SESSION_ID');
		$recs = $this->db->get_where('dep_pmon__t_yearlytargets', array('PROJECT_ID' => $PROJECT_ID));
		//echo $this->db->last_query();exit;
		$vOpt = '<option value="">Select Session</option>';
		if ($recs && $recs->num_rows()) {
			foreach ($recs->result() as $rec) {
				$vOpt .= '<option value="' . $rec->SESSION_ID . '">' .
					$this->getSessionYearByID($rec->SESSION_ID) .
					'</option>';
			}
		}
		echo $vOpt;
	}
	public function getTargetSessionOptionsMi()
	{
		require('mi/Entry_report_mi_c.php');
		$entry_report_mi_c = new Entry_report_mi_c();
		$entry_report_mi_c->getTargetSessionOptions();
	}
	public function getMonthlyOptions()
	{
		$PROJECT_ID = (int) $this->input->post('PROJECT_ID');
		$this->db->distinct();
		$this->db->select('MONTHLY_DATA_ID, MONTH_DATE, PROJECT_STATUS');
		$this->db->order_by('MONTH_DATE');
		$recs = $this->db->get_where('dep_pmon__t_monthlydata', array('PROJECT_ID' => $PROJECT_ID));
		//echo $this->db->last_query(); exit;
		$vOpt = '<option value="">Select Month</option>';
		$completed = FALSE;
		$lastMonth = '';
		$curMonthValue = strtotime(date("Y-m") . '-01');
		$prjNxtMonthValue = strtotime("+1months", $curMonthValue);
		$dd = '';
		if ($recs && $recs->num_rows()) {
			foreach ($recs->result() as $rec) {
				$vOpt .= '<option value="' . $rec->MONTHLY_DATA_ID . '">' .
					date("F, Y", strtotime($rec->MONTH_DATE)) .
					'</option>';
				if ($rec->PROJECT_STATUS == 5) $completed = TRUE;
				$lastMonth = $rec->MONTH_DATE;
			}
		} else {
			$dd = date("Y-m", strtotime("-1months")) . '-01';
		}
		//exit;
		$v1 = '';
		if (!$completed) {
			//echo "in if";
			if ($lastMonth == date("Y-m-d")) {
			} else {
				/*$nextMonthValue = strtotime($lastMonth);
				$curMonthValue = strtotime(date("Y-m").'-01');
                echo "<br> in inner else".$nextMonthValue.' ------- '.$curMonthValue.'<<<<';exit;
				while(1){
					$nextMonthValue = strtotime("+1months", $nextMonthValue);
					$v1 .= '<option value="a'.$nextMonthValue.'">'.date("F, Y", $nextMonthValue).'</option>';
					if($nextMonthValue==$curMonthValue) break;
				}*/
				$nextMonthValue = strtotime($lastMonth);
				$curMonthValue = strtotime(date("Y-m") . '-01');
				//echo 'prjnextmonth value'.$prjNxtMonthValue.'<br />lst monthvalue= '.$lastMonth. '<br />nextmonthvalue ='. $nextMonthValue. '<br />cur month value ='.$curMonthValue;exit;
				if ($nextMonthValue != '') {
					$count = 0;
					while (1) {
						$count++;
						$nextMonthValue = strtotime("+1months", $nextMonthValue);
						$v1 .= '<option label="1" ' . (($nextMonthValue == $prjNxtMonthValue) ? " selected='selected'" : "") . ' value="-1">' . date("M, Y", $nextMonthValue) . '</option>';
						$vdate = $nextMonthValue;
						if ($nextMonthValue == $curMonthValue) break;
						if ($count >= 21) break;
					}
				}
			}
		}
		echo $vOpt . '##' . $v1;
	}

	public function getMonthlyOptionsMi()
	{
		require('mi/Entry_report_mi_c.php');
		$entry_report_mi_c = new Entry_report_mi_c();
		$entry_report_mi_c->getMonthlyOptions();
	}

	public function printSetup()
	{
		$IS_MI = (int) $this->input->post('IS_MI');
		if ($IS_MI == 1) {
			require('mi/Entry_report_mi_c.php');
			$entry_report_mi_c = new Entry_report_mi_c();
			$entry_report_mi_c->printSetup();
			return;
		}
		//$PROJECT_SETUP_ID = (int) $this->input->post('PROJECT_SETUP_ID');
		$this->PROJECT_ID = (int) $this->input->post('PROJECT_ID');
		$xno = (int) $this->input->post('xno');
		//$this->startTime = microtime();
		$projectSetupFields = array(
			'PROJECT_ID', 'PROJECT_NAME', 'PROJECT_NAME_HINDI', 'PROJECT_TYPE_ID',
			'OFFICE_EE_ID', 'OFFICE_EE_NAME', 'OFFICE_SE_ID', 'OFFICE_SE_NAME',
			'OFFICE_CE_ID', 'OFFICE_CE_NAME', 'PROJECT_CODE', 'PROJECT_STATUS',
			'PROJECT_COMPLETION_YEAR', 'PROJECT_START_DATE', 'PROJECT_SETUP_ID',
			'AA_NO', 'PROJECT_SUB_TYPE_ID', 'AA_DATE', 'AA_AMOUNT', 'AA_AUTHORITY_ID',
			'SETUP_LOCK', 'TARGET_LOCK', 'RAA_LOCK', 'TARGET_LOCK_SESSION_ID', 'MONTHLY_LOCK',
			'LOCK_RECORD_ID', 'SESSION_START_YEAR', 'SESSION_END_YEAR',
			'MONTH_LOCK', 'MONTHLY_EXISTS', 'RAA_DATE', 'RAA_EXISTS',
			'PROJECT_COMPLETION_DATE', 'LIVE_STORAGE', 'HEAD_WORK_DISTRICT_ID',
			'PROJECT_SUB_TYPE', 'PROJECT_SUB_TYPE_HINDI', 'SESSION_ID',
			'NO_VILLAGES_BENEFITED', 'TEHSIL_NAME', 'TEHSIL_NAME_HINDI',
			'BLOCK_NAME', 'BLOCK_NAME_HINDI', 'DISTRICT_NAME', 'DISTRICT_NAME_HINDI',
			'AUTHORITY_NAME', 'AUTHORITY_NAME_HINDI',
			'LONGITUDE_D', 'LONGITUDE_M', 'LONGITUDE_S', 'LATITUDE_D', 'LATITUDE_M', 'LATITUDE_S',
			'ASSEMBLY_NAME', 'ASSEMBLY_NAME_HINDI',
			'NALLA_RIVER', 'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK', 'SETUP_SESSION', 'PROJECT_SAVE_DATE',
			'DEPOSIT_SCHEME_ID', 'HEAD', 'SCHEME_NAME_HINDI', 'SCHEME_NAME_ENGLISH'
		);
		$projectSetupValues = array();
		for ($i = 0; $i < count($projectSetupFields); $i++) {
			$projectSetupValues[$projectSetupFields[$i]] = '';
		}
		$arrWhere = array('PROJECT_ID' => $this->PROJECT_ID);
		//$recs = $this->db->get_where('dep_pmon__v_projectlist_details_with_lock', $arrWhere);
		$recs = $this->db->select(implode(",", $projectSetupFields))
			->from('dep_pmon__v_projectlist_details_with_lock as p')
			->join(AGREEMENT_DB . ".dep__m_scheme as scheme", "p.DEPOSIT_SCHEME_ID= scheme.ID", 'left')
			->where($arrWhere)
			->get();
		//echo $this->db->last_query();exit;
		if ($recs) {
			if ($recs->num_rows() == 1) {
				$rec = $recs->row();
				//showArrayValues($rec);
				for ($i = 0; $i < count($projectSetupFields); $i++) {
					//echo $projectSetupFields[$i];
					$projectSetupValues[$projectSetupFields[$i]] = $rec->{$projectSetupFields[$i]};
				}
			}
		}
		//showArrayValues($projectSetupValues); exit;
		//get sdo list
		$sdo = array();
		$recs = $this->db->get_where('__v_project_sdo', $arrWhere);
		if ($recs) {
			if ($recs->num_rows()) {
				$xi = 1;
				foreach ($recs->result() as $recSDO) {
					array_push($sdo, $xi . '.' . $recSDO->OFFICE_NAME);
					$xi++;
				}
			}
		}
		$projectSetupValues['SDO_OFFICE_NAME'] = implode(', ', $sdo);
		$data['projectSetupValues'] = $projectSetupValues;
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		$RAA_FIELDS = array("RAA_NO", "RAA_DATE", "RAA_AUTHORITY_ID", "RAA_AMOUNT");
		$RAA_VALUES = array();

		$recRAA = $this->getRAAData($this->PROJECT_ID);
		//initialize
		for ($i = 0; $i < count($RAA_FIELDS); $i++) {
			$RAA_VALUES[$RAA_FIELDS[$i]] = '';
		}
		if ($recRAA) { //if found
			for ($i = 0; $i < count($RAA_FIELDS); $i++)
				$RAA_VALUES[$RAA_FIELDS[$i]] = $recRAA[$RAA_FIELDS[$i]];
			$data['RAA_AUTHORITY_ID'] = $this->getAuthorityName($RAA_VALUES['RAA_AUTHORITY_ID']);
		} else {
			$data['RAA_AUTHORITY_ID'] = '';
		}
		$data['RAA_VALUES'] = $RAA_VALUES;
		/************ check point */
		$SESSION_ID = 0;
		if ($projectSetupValues['SESSION_ID'])
			$SESSION_ID = $projectSetupValues['SESSION_ID'];
		if ($this->PROJECT_ID) {
			$recAchieve = $this->getAchievement($SESSION_ID - 1);
			//showArrayValues($recAchieve);
			$recEstimation = $this->getEstimation($this->PROJECT_ID);
			//showArrayValues($recEstimation);
			$recEstimationStatus = $this->getEstimationStatus($this->PROJECT_ID);
			$recTargetDates = $this->getTargetDates($this->PROJECT_ID);
			$data['estimationData'] = $recEstimation;
			$data['estimationStatus'] = $recEstimationStatus;
			$data['TARGET_DATES_VALUES'] = $recTargetDates;
			//showArrayValues($recTargetDates);
			$data['statusData'] = $this->getSetupStatusData($this->PROJECT_ID);
			$data['achievementData'] = $recAchieve;
		}
		$EE_ID = 0;
		$SDO_DD = '';
		$HOLDING_PERSON = $this->session->userData('HOLDING_PERSON');
		if ($HOLDING_PERSON == 4) { //ee
			$EE_ID = $this->session->userData('CURRENT_OFFICE_ID');
			$SDO_DD = '';
		}
		$data['EE_ID'] = $EE_ID;
		//echo $HOLDING_PERSON. '::'.$EE_ID.'::';
		$SDO_IDs = array();
		if ($this->PROJECT_ID > 0) {
			$recs = $this->db->get_where('deposit__projects_office', $arrWhere);
			if ($recs && $recs->num_rows()) {
				foreach ($recs->result() as $rec) {
					$EE_ID = $rec->EE_ID;
					array_push($SDO_IDs, $rec->OFFICE_ID);
				}
			}
		}
		$data['sdo_options'] = $this->SDOofficeOptions($EE_ID, $SDO_IDs);
		$data['EE_NAME'] = $this->getOfficeEEname($EE_ID);
		//$this->showTime('aft off');
		if ($this->PROJECT_ID) {
			$data['DISTRICT_BENEFITED'] = $this->getDistricts($this->getDistrictBenefitedIDs($this->PROJECT_ID));
			$data['BLOCKS_BENEFITED'] = $this->getBlocks($this->PROJECT_ID);
			$data['ASSEMBLY_BENEFITED'] = $this->getAssemblys($this->getBenefitedAssemblyIDs($this->PROJECT_ID));
			$data['VILLAGES_BENEFITED'] = $this->getVillages($this->PROJECT_ID);
			//blockwise iP
			$sessionId = $projectSetupValues['SESSION_ID'];
			$arrBlockIds = $this->getBlockIds($this->PROJECT_ID);

			$arrBlockIps = $this->getEstimationBlockIP($this->PROJECT_ID, $recEstimation['ESTIMATED_QTY_ID']);
			$arrBlockAIps = $this->getAchievementBlockIP($this->PROJECT_ID, $sessionId - 1);
			//showArrayValues($arrBlockIps);
			//showArrayValues($arrBlockAIps);
			foreach ($arrBlockIds as $arrBlockId) {
				if (array_key_exists($arrBlockId, $arrBlockAIps)) {
					if (array_key_exists('ACHIEVEMENT_IP', $arrBlockAIps[$arrBlockId])) {
						$arrBlockIps[$arrBlockId]['ACHIEVEMENT_IP'] = $arrBlockAIps[$arrBlockId]['ACHIEVEMENT_IP'];
					}
				} //else{
				//	$arrBlockIps[$arrBlockId]['ACHIEVEMENT_IP'] = FALSE;
				//}
			}
			$data['BLOCK_IP_DATA'] = $arrBlockIps;
		}
		$myview = $this->load->view('pmon_deposit/print/project_setup_data_print_view', $data, true);
		array_push($this->message, getMyArray(null, $myview));
		echo $xno . '####' . createJSONResponse($this->message);
	}
	private function getSetupStatusData($projectId)
	{
		$recs =  $this->db->get_where('dep_pmon__m_setup_status', array('PROJECT_ID' => $projectId));
		return (($recs && $recs->num_rows()) ? $recs->row() : false);
	}
	/**-------- Target Report ---------*/
	public function printTarget()
	{
		$IS_MI = (int) $this->input->post('IS_MI');
		if ($IS_MI == 1) {
			require('mi/Entry_report_mi_c.php');
			$entry_report_mi_c = new Entry_report_mi_c();
			$entry_report_mi_c->printTarget();
			return;
		}

		$data = array();
		$this->PROJECT_ID = $this->input->post('PROJECT_ID');
		$xno = (int) $this->input->post('xno');
		$sessionId = $this->input->post('session');

		$rec_prj = null;
		$arrWhere = array('PROJECT_ID' => $this->PROJECT_ID);
		$recs = $this->db->get_where('deposit__projects', $arrWhere);
		if ($recs && $recs->num_rows()) {
			$rec_prj = $recs->row();
		}
		$data['PROJECT_NAME'] = $rec_prj->PROJECT_NAME;
		$data['PROJECT_CODE'] = $rec_prj->PROJECT_CODE;
		// Get AA AMOUNT to Compare with Target should no excessed 
		$recs = $this->db->get_where('dep_pmon__m_project_setup', $arrWhere);
		if ($recs && $recs->num_rows()) {
			$recAAAmount = $recs->row();
			$data['AA_AMOUNT'] = $recAAAmount->AA_AMOUNT;
			$PROJECT_COMPLETION_MONTH = date("m", strtotime($recAAAmount->PROJECT_COMPLETION_DATE));
			$PROJECT_COMPLETION_YEAR = date("Y", strtotime($recAAAmount->PROJECT_COMPLETION_DATE));
		}
		$data['setupData'] = $this->getSetupData();
		$data['BUDGET_AMOUNT'] = '';
		$data['SUBMISSION_DATE'] = '';
		//session
		if ($sessionId == 0) {
			$MONTH = date('m');
			$YEAR = date('Y');
			$sessionId = $this->getFinancialYearIDByMonthYear($MONTH, $YEAR);
		}
		$data['session_year'] =  $this->getSessionYearByID($sessionId);
		//echo $PROJECT_COMPLETION_MONTH.' -- '.$PROJECT_COMPLETION_YEAR;
		$data['sessionId'] = $sessionId;
		$SessionProjComp = $this->getFinancialYearIDByMonthYear($PROJECT_COMPLETION_MONTH, $PROJECT_COMPLETION_YEAR);
		$data['SESSION_LIST'] = $this->getMySessionOptions($SessionProjComp, $sessionId);
		$records = array();
		$targetFields = $this->getYearlyTargetFields();
		for ($i = 1; $i <= 12; $i++) {
			$rec = array();
			for ($j = 0; $j < count($targetFields); $j++) {
				$rec[$targetFields[$j]] = '';
			}
			$records[$i] = (object) $rec;
		}
		//echo $sessionId. ' = '.$this->PROJECT_ID;
		//showArrayValues($records);
		$recs = $this->db->get_where(
			'dep_pmon__t_yearlytargets',
			array('PROJECT_ID' => $this->PROJECT_ID, 'SESSION_ID' => $sessionId)
		);
		if ($recs && $recs->num_rows()) {
			$i = 0;
			foreach ($recs->result() as $rec) {
				if ($i == 0) {
					$data['BUDGET_AMOUNT'] = $rec->BUDGET_AMOUNT;
					$data['SUBMISSION_DATE'] = $rec->SUBMISSION_DATE;
					$i++;
				}
				$records[$rec->FINANCIAL_MONTH] = $rec;
			}
		}
		//showArrayValues($records);
		$data['records'] = $records;
		$myview = $this->load->view('pmon_deposit/print/target_data_print_view', $data, true);
		array_push($this->message, getMyArray(null, $myview));
		echo $xno . '####' . createJSONResponse($this->message);
	}
	private function getSessionYearByID($sessionId = 0)
	{
		$recs = $this->db->get_where('__sessions', array('SESSION_ID' => $sessionId));
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			return $rec->SESSION_START_YEAR . '-' . $rec->SESSION_END_YEAR;
		}
		return '';
	}
	//by printTarget()
	private function findAllDataCurFinYr($sessionId)
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
			FROM dep_pmon__t_yearlytargets 
			WHERE SESSION_ID = ' . $sessionId . ' 
				AND PROJECT_ID =' . $this->PROJECT_ID . '
			GROUP BY SESSION_ID,PROJECT_ID';
		$data = $this->db->query($qry)->result();
		return $data;
	}
	//by printTarget(), printMonthly()
	private function getSetupData()
	{
		$mFields = array(
			'LA_NA', 'FA_NA',
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 'STEEL_WORKS_NA',
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA', 'CANAL_LINING_NA', 'ROAD_WORKS_NA',
			'IRRIGATION_POTENTIAL_NA'
		);
		$recs = $this->db->get_where(
			'dep_pmon__t_estimated_status',
			array('PROJECT_ID' => $this->PROJECT_ID)
		);
		$setupData = array();
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			for ($i = 0; $i < count($mFields); $i++) {
				$setupData[$mFields[$i]] = $rec->{$mFields[$i]};
			}
		}
		return $setupData;
	}
	private function getStatusData()
	{
		$mFields = array(
			'LA_CASES_STATUS', 'CANAL_LINING_STATUS',
			'CANAL_STRUCTURE_STATUS', 'CANAL_EARTH_WORK_STATUS',
			'NALLA_CLOSURE_STATUS', 'SLUICES_STATUS',
			'FLANK_STATUS', 'SPILLWAY_STATUS'
		);
		$recs = $this->db->get_where(
			'dep_pmon__t_achievements',
			array('PROJECT_ID' => $this->PROJECT_ID)
		);
		$setupData = array();
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			for ($i = 0; $i < count($mFields); $i++) {
				$setupData[$mFields[$i]] = $rec->{$mFields[$i]};
			}
		}
		return $setupData;
	}
	private function getYearlyTargetFields()
	{
		return array(
			'YEARLY_TARGET_ID', 'SESSION_ID', 'TARGET_MONTH', 'PROJECTCODE',
			'BUDGET_AMOUNT', 'EXPENDITURE',
			'LA_NO', 'LA_HA', 'FA_HA',
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_LINING',
			'CANAL_STRUCTURES', 'IRRIGATION_POTENTIAL',
			'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI',
			'SUBMISSION_DATE', 'LOCKED', 'TARGET_YEAR',
			'LA_NO_CT', 'LA_HA_CT', 'FA_HA_CT',
			'HEAD_WORKS_EARTHWORK_CT', 'HEAD_WORKS_MASONRY_CT',
			'CANAL_EARTHWORK_CT', 'CANAL_LINING_CT', 'STEEL_WORKS_CT',
			'CANAL_STRUCTURES_CT', 'IRRIGATION_POTENTIAL_CT',
			'EXPENDITURE_CT'
		);
	}
	/**-------- Monthly Report --------*/
	public function printMonthly()
	{
		$IS_MI = (int) $this->input->post('IS_MI');
		if ($IS_MI == 1) {
			require('mi/Entry_report_mi_c.php');
			$entry_report_mi_c = new Entry_report_mi_c();
			$entry_report_mi_c->printMonthly();
			return;
		}
		$this->PROJECT_ID = $this->input->post('PROJECT_ID');
		$xno = (int) $this->input->post('xno');
		$isBlank = ($xno == 4) ? $this->input->post('blank_monthly') : 0;
		$monthlyDataId = 0;
		$data['isBlank'] = $isBlank;
		$mMonthlyFields = $this->getMonthlyFields();
		$currentMonthValues = array();
		$monthlyTable = 'dep_pmon__t_monthlydata';
		$currentMonthRecordExists = FALSE;
		if ($isBlank) {
			$blankMonth = $this->input->post('blank_month');
			if (substr($blankMonth, 0, 1) == 'a') {
				$reportMonthValue = substr($blankMonth, 1);
				$currentMonth = date("Y-m-d", $reportMonthValue);
			} else {
				$monthlyDataId = $blankMonth; //(int) $this->input->post('month');
				$recs = $this->db->get_where($monthlyTable, array('MONTHLY_DATA_ID' => $monthlyDataId));
				if ($recs && $recs->num_rows()) {
					$rec = $recs->row();
					for ($i = 0; $i < count($mMonthlyFields); $i++) {
						$currentMonthValues[$mMonthlyFields[$i]] = $rec->{$mMonthlyFields[$i]};
					}
					$currentMonthRecordExists = TRUE;
					$currentMonth = $currentMonthValues['MONTH_DATE'];
				}
			}
			for ($i = 0; $i < count($mMonthlyFields); $i++) {
				$currentMonthValues[$mMonthlyFields[$i]] = '';
			}
		} else {
			$monthlyDataId = (int) $this->input->post('month');
			$recs = $this->db->get_where(
				$monthlyTable,
				array('MONTHLY_DATA_ID' => $monthlyDataId)
			);
			if ($recs && $recs->num_rows()) {
				$rec = $recs->row();
				for ($i = 0; $i < count($mMonthlyFields); $i++) {
					$currentMonthValues[$mMonthlyFields[$i]] = $rec->{$mMonthlyFields[$i]};
				}
				$currentMonthRecordExists = TRUE;
				$currentMonth = $currentMonthValues['MONTH_DATE'];
			}
		}
		$entryDate = strtotime($currentMonth);
		$data['MONTH_DATE'] = $entryDate;
		$MONTH = date("n", $entryDate);
		$YEAR = date("Y", $entryDate);
		$sessionId = $this->getSessionID($MONTH, $YEAR);
		//monthly remarks
		$mMonthlyRemarkFields = $this->getMonthlyRemarkFields();
		$CURRENT_MONTH_REMARK_VALUES = array();
		for ($i = 0; $i < count($mMonthlyRemarkFields); $i++) {
			$currentMonthRemarkData[$mMonthlyRemarkFields[$i]] = '';
		}
		$arrWhich = array(
			'PROJECT_ID' => $this->PROJECT_ID,
			'MONTH_DATE' => $currentMonth
		);
		$recs = $this->db->get_where(
			'dep_pmon__t_monthlydata_remarks',
			$arrWhich
		);
		$currentMonthRemarkValues = array();
		for ($i = 0; $i < count($mMonthlyRemarkFields); $i++) {
			$currentMonthRemarkValues[$mMonthlyRemarkFields[$i]] = '';
		}
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			for ($i = 0; $i < count($mMonthlyRemarkFields); $i++) {
				$currentMonthRemarkValues[$mMonthlyRemarkFields[$i]] =  $rec->{$mMonthlyRemarkFields[$i]};
			}
		}
		$data['monthly_remarks'] = $currentMonthRemarkValues;
		//PROGRESS
		$this->db->select('PROGRESS');
		$recs = $this->db->get_where('dep_pmon__t_progress', array(
			'PROJECT_ID' => $this->PROJECT_ID,
			'PROGRESS_DATE' => $currentMonth
		));
		$data['PROGRESS'] = 0;
		if ($recs && $recs->num_rows()) {
			$prec = $recs->row();
			$data['PROGRESS'] = $prec->PROGRESS;
		}
		//monthly project status
		$mMonthlyStatusFields = $this->getMonthlyStatusFields();
		$currentMonthStatusValues = array();
		for ($i = 0; $i < count($mMonthlyStatusFields); $i++)
			$currentMonthStatusValues[$mMonthlyStatusFields[$i]] = '';
		$recs = $this->db->get_where('dep_pmon__m_status_date', $arrWhich);
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			for ($i = 0; $i < count($mMonthlyStatusFields); $i++) {
				$currentMonthStatusValues[$mMonthlyStatusFields[$i]] =  $rec->{$mMonthlyStatusFields[$i]};
			}
		}
		$data['monthlyStatusData'] = $currentMonthStatusValues;
		//get previous month
		$prevMonthValue = strtotime("-1month", $entryDate);
		$mAFStatus = array(
			'LA_CASES_STATUS', 'SPILLWAY_STATUS', 'FLANK_STATUS', 'SLUICES_STATUS',
			'NALLA_CLOSURE_STATUS', 'CANAL_EARTH_WORK_STATUS',
			'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS'
		);
		$previousMonthValues = array();
		$prevMonthStatus = array();
		$prevMonthExists = false;

		$recs_p = $this->db->get_where(
			$monthlyTable,
			array(
				'PROJECT_ID' => $this->PROJECT_ID,
				'MONTH_DATE' => date("Y-m-d", $prevMonthValue)
			)
		);
		if ($recs_p && $recs_p->num_rows()) {
			$recp = $recs_p->row();
			for ($i = 0; $i < count($mMonthlyFields); $i++)
				$previousMonthValues[$mMonthlyFields[$i]] = $recp->{$mMonthlyFields[$i]};
			for ($j = 0; $j < count($mAFStatus); $j++)
				$prevMonthStatus[$mAFStatus[$j]] = $recp->{$mAFStatus[$j]};
			$prevMonthExists = true;
		}
		$arrWhere = array('PROJECT_ID' => $this->PROJECT_ID);
		if (!$prevMonthExists) {
			for ($i = 0; $i < count($mMonthlyFields); $i++)
				$previousMonthValues[$mMonthlyFields[$i]] = '';
			//get status from project setup
			$recs_p = $this->db->get_where('dep_pmon__m_setup_status', $arrWhere);
			if ($recs_p && $recs_p->num_rows()) {
				$recp = $recs_p->row();
				for ($j = 0; $j < count($mAFStatus); $j++)
					$prevMonthStatus[$mAFStatus[$j]] = $recp->{$mAFStatus[$j]};
			}
		}
		$data['currentMonthRecordExists'] = $currentMonthRecordExists;
		$data['currentMonthRecord'] = $currentMonthValues;
		$data['previousMonthRecord'] = $previousMonthValues;
		$data['prevMonthStatus'] = $prevMonthStatus;
		$arrSTData = array();
		if ($currentMonthRecordExists && ($currentMonthValues['PROJECT_STATUS'] == 5)) {
			$arrStFields = $this->getFields('dep_pmon__m_status_date');
			$strSQL = 'SELECT * FROM dep_pmon__m_status_date WHERE PROJECT_ID=' . $this->PROJECT_ID;
			$recs = $this->db->query($strSQL);
			if ($recs && $recs->num_rows()) {
				$rec = $recs->row();
				for ($i = 0; $i < count($arrStFields); $i++) {
					$arrSTData[$arrStFields[$i]] = $rec->{$arrStFields[$i]};
				}
			}
		}
		$data['completionStatusData'] = $arrSTData;
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
		for ($i = 0; $i < count($arrFields); $i++) {
			$totalInCurrentFY[$arrFields[$i]] = 0;
			$achievementTillLastFY[$arrFields[$i]] = 0;
		}
		$totalInCurrentFY['SESSION_ID'] = $sessionId;
		$totalInCurrentFY['PROJECT_ID'] = $this->PROJECT_ID;
		$achievementTillLastFY['SESSION_ID'] = $sessionId;
		$achievementTillLastFY['PROJECT_ID'] = $this->PROJECT_ID;

		if ($currentFinancialMonth != 0) {
			//get total in this financial year
			$strSQL = 'SELECT SUM(EXPENDITURE_TOTAL) AS EXPENDITURE_TOTAL,
				SUM(EXPENDITURE_WORKS) AS EXPENDITURE_WORKS, 
				SUM(LA_NO)AS LA_NO, SUM(LA_HA) AS LA_HA, 
				SUM(LA_COMPLETED_NO) AS LA_COMPLETED_NO,
				SUM(LA_COMPLETED_HA) AS LA_COMPLETED_HA, 
				SUM(FA_HA)AS FA_HA, SUM(FA_COMPLETED_HA) AS FA_COMPLETED_HA,
				SUM(HEAD_WORKS_EARTHWORK)AS HEAD_WORKS_EARTHWORK, 
				SUM(HEAD_WORKS_MASONRY) AS HEAD_WORKS_MASONRY,
				SUM(STEEL_WORKS) AS STEEL_WORKS,
				SUM(ROAD_WORKS) AS ROAD_WORKS,
				SUM(CANAL_EARTHWORK) AS CANAL_EARTHWORK, 
				SUM(CANAL_STRUCTURES)AS CANAL_STRUCTURES,
				SUM(CANAL_MASONRY)AS CANAL_MASONRY,
				SUM(CANAL_LINING)AS CANAL_LINING, 
				SUM(IRRIGATION_POTENTIAL_KHARIF)AS IRRIGATION_POTENTIAL_KHARIF,
				SUM(IRRIGATION_POTENTIAL_RABI)AS IRRIGATION_POTENTIAL_RABI,
				SUM(IRRIGATION_POTENTIAL)AS IRRIGATION_POTENTIAL 
			FROM dep_pmon__t_monthlydata 
			WHERE PROJECT_ID=' . $this->PROJECT_ID .
				' AND SESSION_ID=' . $sessionId .
				' AND MONTH_DATE <="' . date("Y-m-d", $entryDate) . '"';;
			$recs = $this->db->query($strSQL);
			/*$this->db->select_sum('EXPENDITURE_TOTAL')->select_sum('EXPENDITURE_WORKS')
					->select_sum('LA_NO')->select_sum('LA_HA') 
					->select_sum('LA_COMPLETED_NO')->select_sum('LA_COMPLETED_HA')
					->select_sum('FA_HA')->select_sum('FA_COMPLETED_HA')
					->select_sum('HEAD_WORKS_EARTHWORK')->select_sum('HEAD_WORKS_MASONRY')
					->select_sum('STEEL_WORKS')
					->select_sum('CANAL_EARTHWORK')->select_sum('CANAL_STRUCTURES')->select_sum('CANAL_LINING')
					->select_sum('IRRIGATION_POTENTIAL_KHARIF')
					->select_sum('IRRIGATION_POTENTIAL_RABI')
					->select_sum('IRRIGATION_POTENTIAL')
					;
			$this->db->where_in('PROJECT_ID', $this->PROJECT_ID);
			$this->db->where_in('SESSION_ID', $sessionId);
			$this->db->where('FINANCIAL_MONTH <', $currentFinancialMonth);
			$recs = $this->db->get('dep_pmon__t_monthlydata');*/
			if ($recs && $recs->num_rows()) {
				$rec = $recs->row();
				for ($i = 0; $i < count($arrFields); $i++)
					if (!($arrFields[$i] == 'SESSION_ID' || $arrFields[$i] == 'PROJECT_ID'))
						$totalInCurrentFY[$arrFields[$i]] = $rec->{$arrFields[$i]};
				$totalInCurrentFY['SESSION_ID'] = $sessionId;
				$totalInCurrentFY['PROJECT_ID'] = $this->PROJECT_ID;
			}
			//echo $this->db->last_query();
		}
		$data['totalInCurrentFY'] = $totalInCurrentFY;

		//GET DATA TILL LAST FINANCIAL YEAR
		$strSQL = 'SELECT PROJECT_ID, SUM(EXPENDITURE_TOTAL) AS EXPENDITURE_TOTAL,
				SUM(EXPENDITURE_WORKS) AS EXPENDITURE_WORKS, 
				SUM(LA_NO)AS LA_NO, SUM(LA_HA) AS LA_HA, 
				SUM(LA_COMPLETED_NO) AS LA_COMPLETED_NO,
				SUM(LA_COMPLETED_HA) AS LA_COMPLETED_HA, 
				SUM(FA_HA)AS FA_HA, SUM(FA_COMPLETED_HA) AS FA_COMPLETED_HA,
				SUM(HEAD_WORKS_EARTHWORK)AS HEAD_WORKS_EARTHWORK, 
				SUM(HEAD_WORKS_MASONRY) AS HEAD_WORKS_MASONRY,
				SUM(STEEL_WORKS) AS STEEL_WORKS,
				SUM(ROAD_WORKS) AS ROAD_WORKS,
				SUM(CANAL_EARTHWORK) AS CANAL_EARTHWORK, 
				SUM(CANAL_STRUCTURES)AS CANAL_STRUCTURES,
				SUM(CANAL_MASONRY)AS CANAL_MASONRY,
				SUM(CANAL_LINING)AS CANAL_LINING, 
				SUM(IRRIGATION_POTENTIAL_KHARIF)AS IRRIGATION_POTENTIAL_KHARIF,
				SUM(IRRIGATION_POTENTIAL_RABI)AS IRRIGATION_POTENTIAL_RABI,
				SUM(IRRIGATION_POTENTIAL)AS IRRIGATION_POTENTIAL 
			FROM dep_pmon__t_achievements  
			WHERE PROJECT_ID=' . $this->PROJECT_ID .
			' AND SESSION_ID<' . $sessionId .
			' GROUP BY PROJECT_ID';
		$recs = $this->db->query($strSQL);

		/*$this->db->select('PROJECT_ID')
			->select_sum('EXPENDITURE_TOTAL')
			->select_sum('EXPENDITURE_WORKS')
			->select_sum('LA_NO')
			->select_sum('LA_HA')
			->select_sum('LA_COMPLETED_NO')
			->select_sum('LA_COMPLETED_HA')
			->select_sum('FA_HA')
			->select_sum('FA_COMPLETED_HA')
			->select_sum('HEAD_WORKS_EARTHWORK')
			->select_sum('HEAD_WORKS_MASONRY')
			->select_sum('STEEL_WORKS')
			->select_sum('CANAL_EARTHWORK')
			->select_sum('CANAL_STRUCTURES')
			->select_sum('CANAL_LINING')
			->select_sum('IRRIGATION_POTENTIAL_KHARIF')
			->select_sum('IRRIGATION_POTENTIAL_RABI')
			->select_sum('IRRIGATION_POTENTIAL');*/

		//$PREVIOUS_SESSION_ID = $SESSION_ID - 1;
		/*$recs = $this->db->get_where(
			'dep_pmon__t_achievements', 
			array('PROJECT_ID'=>$this->PROJECT_ID, 'SESSION_ID <'=>$sessionId)
		);*/
		//showArrayValues($rec);
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			for ($i = 0; $i < count($arrFields); $i++)
				if ($arrFields[$i] != 'SESSION_ID')
					$achievementTillLastFY[$arrFields[$i]] = $rec->{$arrFields[$i]};
		}
		//showArrayValues($achievementTillLastFY);
		$data['achievementTillLastFY'] = $achievementTillLastFY;

		$arrFieldsForProgress = array(
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES', 'CANAL_MASONRY', 'ROAD_WORKS',
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK',
			'LA_NO', 'LA_HA', 'FA_HA',
			'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 'FA_COMPLETED_HA',
			'IRRIGATION_POTENTIAL', 'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI'
		);
		$arrEstimation = array();
		//init
		for ($iCount = 0; $iCount < count($arrFieldsForProgress); $iCount++)
			$arrEstimation[$arrFieldsForProgress[$iCount]] = 0;
		//ESTIMATION_DATA [start]
		$strSQL = 'SELECT e.*, p.RAA_DATE FROM dep_pmon__t_estimated_qty as e 
				LEFT JOIN dep_pmon__t_raa_project as p on(p.RAA_PROJECT_ID=e.RAA_ID)
					WHERE e.PROJECT_ID in (' . $this->PROJECT_ID . ') and p.RAA_DATE<="' . $currentMonth . '"
				ORDER BY p.RAA_DATE desc 
				LIMIT 0, 1';
		$recs = $this->db->query($strSQL);
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
		} else {
			$this->db->where_in('PROJECT_ID', $this->PROJECT_ID);
			$this->db->where('ADDED_BY', 0);
			$recs = $this->db->get('dep_pmon__t_estimated_qty');
			if ($recs && $recs->num_rows()) $rec = $recs->row();
		}
		for ($iCount = 0; $iCount < count($arrFieldsForProgress); $iCount++) {
			$arrEstimation[$arrFieldsForProgress[$iCount]] = $rec->{$arrFieldsForProgress[$iCount]};
		}
		/*$this->db->where_in('PROJECT_ID', $this->PROJECT_ID);
		$this->db->order_by('ESTIMATED_QTY_ID', 'DESC');
		//$this->db->order_by('LIMIT', 0, 1);
		$recs = $this->db->get('dep_pmon__t_estimated_qty', 1, 0);
		//echo $this->db->last_query();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($iCount=0; $iCount<count($arrFieldsForProgress);$iCount++){
				$arrEstimation[ $arrFieldsForProgress[$iCount] ] = $rec->{$arrFieldsForProgress[$iCount]};
			}
		}*/
		//echo $this->db->last_query();
		$data['estimationData'] = $arrEstimation;
		$arrWhich = array(
			'PROJECT_ID' => $this->PROJECT_ID,
			'YEARLY_TARGET_DATE' => $currentMonth
		);
		$data['TARGET_FLAG'] = 0;
		$data['BUDGET_AMOUNT'] = 0;
		$data['SUBMISSION_DATE'] = '';
		$recs = $this->db->get_where('dep_pmon__t_yearlytargets', $arrWhich);
		//echo $this->db->last_query();
		//showArrayValues($rec);
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			$data['BUDGET_AMOUNT'] = $rec->BUDGET_AMOUNT;
			$data['SUBMISSION_DATE'] = $rec->SUBMISSION_DATE;
			$data['TARGET_FLAG'] = 1;
		}
		//get actual completion date
		$data['ACTUAL_COMPLETION_DATE'] = '0000-00-00';
		$arrWhere = array('PROJECT_ID' => $this->PROJECT_ID);
		$this->db->select('PROJECT_COMPLETION_DATE, PROJECT_START_DATE');
		$recs = $this->db->get_where('dep_pmon__m_project_setup', $arrWhere);
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			$data['ACTUAL_COMPLETION_DATE'] = $rec->PROJECT_COMPLETION_DATE;
			$data['PROJECT_START_DATE'] = $rec->PROJECT_START_DATE;
		}
		//get project name
		$this->db->select('PROJECT_NAME, PROJECT_CODE, PROJECT_NAME_HINDI');
		$recs = $this->db->get_where('deposit__projects', $arrWhere);
		if ($recs && $recs->num_rows()) {
			$prec = $recs->row();
			$data['PROJECT_NAME'] = $prec->PROJECT_NAME . ' - ' . $prec->PROJECT_NAME_HINDI;
			$data['PROJECT_CODE'] = $prec->PROJECT_CODE;
		}
		$data['setupData'] = $this->getSetupData();
		$data['statusData'] = $this->getStatusData();

		$data['arrBlockData'] = $this->getBlockwiseIP($currentMonth);

		$myview = $this->load->view('pmon_deposit/print/monthly_data_print_view', $data, true);
		//echo $myview ;
		array_push($this->message, getMyArray(null, $myview));
		echo $xno . '####' . createJSONResponse($this->message);
	}
	private function getBlockwiseIP($month)
	{
		$arrData = array();
		$strSQL = 'SELECT m.*, b.BLOCK_NAME, b.BLOCK_NAME_HINDI
			FROM dep_pmon__t_block_monthly_ip AS m
				INNER JOIN __blocks as b ON(m.BLOCK_ID=b.BLOCK_ID)
			WHERE m.PROJECT_ID=' . $this->PROJECT_ID . ' AND MONTH_DATE="' . $month . '"';
		$recs = $this->db->query($strSQL);
		$isMonthlyExists = FALSE;
		if ($recs && $recs->num_rows()) {
			$isMonthlyExists = TRUE;
		} else {
			//echo 'ggggggg';
			$strSQL = 'SELECT m.*, b.BLOCK_NAME, b.BLOCK_NAME_HINDI,
				(0) as KHARIF, (0) as RABI, (0) as IP_TOTAL
			FROM deposit__projects_block_served AS m
				INNER JOIN __blocks as b ON(m.BLOCK_ID=b.BLOCK_ID)
			WHERE m.PROJECT_ID=' . $this->PROJECT_ID;
			$recs = $this->db->query($strSQL);
		}
		//
		//echo $this->db->last_query().'<br />';
		if ($recs && $recs->num_rows()) {
			foreach ($recs->result() as $rec) {
				$arrData[$rec->BLOCK_ID] = array(
					'BLOCK_NAME' => $rec->BLOCK_NAME,
					'BLOCK_NAME_HINDI' => $rec->BLOCK_NAME_HINDI,
					'CUR_MONTH_IP' => array('KHARIF' => $rec->KHARIF, 'RABI' => $rec->RABI, 'IP' => $rec->IP_TOTAL),
					'PREV_MONTH_IP' => array('KHARIF' => 0, 'RABI' => 0, 'IP' => 0),
					'ESTIMATION_IP' => array('KHARIF' => 0, 'RABI' => 0, 'IP' => 0),
					'ACHIEVEMENT_IP_TLY' => array('KHARIF' => 0, 'RABI' => 0, 'IP' => 0),
					'ACHIEVEMENT_IP_CFY' => array('KHARIF' => 0, 'RABI' => 0, 'IP' => 0),
					'ACHIEVEMENT_IP' => array('KHARIF' => 0, 'RABI' => 0, 'IP' => 0)
				);
			}
		}
		//echo count($arrData).'<br />';
		//showArrayValues($arrData);
		//echo 'after';
		// PREV MONTH DATA
		$prevMonth = date("Y-m-d", strtotime("-1month", strtotime($month)));
		if ($isMonthlyExists) {
			$strSQL = 'SELECT * FROM dep_pmon__t_block_monthly_ip 
				WHERE PROJECT_ID=' . $this->PROJECT_ID .
				' AND MONTH_DATE="' .  $prevMonth . '"';
			$recs = $this->db->query($strSQL);
			if ($recs && $recs->num_rows()) {
				//showArrayValues($recs->result());
				foreach ($recs->result() as $rec) {
					$arrData[$rec->BLOCK_ID]['PREV_MONTH_IP'] = array('KHARIF' => $rec->KHARIF, 'RABI' => $rec->RABI, 'IP' => $rec->IP_TOTAL);
				}
			}
		}
		//estimation
		$mEFields = array('BLOCK_ID', 'KHARIF', 'RABI', 'IP_TOTAL');
		$setupData = array();
		$arrWhere = array('PROJECT_ID' => $this->PROJECT_ID);
		//$this->db->order_by('SESSION_ID', 'DESC');
		$this->db->order_by('ID', 'DESC');
		$this->db->limit(1, 0);
		$recs = $this->db->get_where('dep_pmon__t_block_estimated_ip', $arrWhere);
		$isExists = false;
		//echo $this->db->last_query().'<br />';
		if ($recs && $recs->num_rows()) {
			foreach ($recs->result() as $rec) {
				$arrData[$rec->BLOCK_ID]['ESTIMATION_IP'] = array('KHARIF' => $rec->KHARIF, 'RABI' => $rec->RABI, 'IP' => $rec->IP_TOTAL);
			}
		}
		//showArrayValues($arrData);
		//achievement in setup sessionxxx
		$sessionId = getSessionIdByDate($month);
		$arrWhich = array('PROJECT_ID' => $this->PROJECT_ID, 'SESSION_ID' => $sessionId - 1);
		$recs = $this->db->get_where('dep_pmon__t_block_achievement_ip', $arrWhich);
		$isExists = false;
		if ($recs && $recs->num_rows()) {
			foreach ($recs->result() as $rec) {
				$arrData[$rec->BLOCK_ID]['ACHIEVEMENT_IP_TLY'] = array('KHARIF' => $rec->KHARIF, 'RABI' => $rec->RABI, 'IP' => $rec->IP_TOTAL);
			}
		}
		//
		//$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID, 'SESSION_ID'=>$sessionId-1);
		$strSQL = 'SELECT SUM(KHARIF)AS KHARIF, SUM(RABI)AS RABI, SUM(IP_TOTAL)AS IP_TOTAL, BLOCK_ID, PROJECT_ID 
			FROM dep_pmon__t_block_monthly_ip WHERE SESSION_ID <' . $sessionId .
			' AND PROJECT_ID=' . $this->PROJECT_ID .
			' GROUP BY BLOCK_ID, PROJECT_ID 
			ORDER BY BLOCK_ID, PROJECT_ID ';
		$recs = $this->db->query($strSQL);
		//echo $strSQL;
		if ($recs && $recs->num_rows()) {
			foreach ($recs->result() as $rec) {
				$arrData[$rec->BLOCK_ID]['ACHIEVEMENT_IP_TLY'] = array('KHARIF' => $rec->KHARIF, 'RABI' => $rec->RABI, 'IP' => $rec->IP_TOTAL);
			}
		}
		//echo count($arrData).'<br />';
		//showArrayValues($arrData);
		//echo ':after';
		//CFY
		$endDate = '';
		$this->db->select('END_DATE, START_DATE');
		$recs = $this->db->get_where('__sessions', array('SESSION_ID' => $sessionId));
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			$endDate = $rec->END_DATE;
			$startDate = $rec->START_DATE;
		}
		if ($isMonthlyExists) {
			$strSQL = 'SELECT SUM(KHARIF)AS KHARIF, SUM(RABI)AS RABI, SUM(IP_TOTAL)AS IP_TOTAL, BLOCK_ID, PROJECT_ID 
				FROM dep_pmon__t_block_monthly_ip WHERE PROJECT_ID=' . $this->PROJECT_ID .
				' AND MONTH_DATE>="' .  $startDate . '"
                     AND MONTH_DATE<="' .  $month . '"
                    
                    ';
			' GROUP BY BLOCK_ID, PROJECT_ID 
				ORDER BY BLOCK_ID, PROJECT_ID ';
			$recs = $this->db->query($strSQL);
			$isExistsPrevMonths = FALSE;
			//echo 'ssssssssssss:'.$this->db->last_query();
			if ($recs && $recs->num_rows()) {
				foreach ($recs->result() as $rec) {
					if ($rec->BLOCK_ID === NULL) {
						continue;
					} else {
						$isExistsPrevMonths = TRUE;
						$arrData[$rec->BLOCK_ID]['ACHIEVEMENT_IP_CFY'] = array('KHARIF' => $rec->KHARIF, 'RABI' => $rec->RABI, 'IP' => $rec->IP_TOTAL);
					}
				}
				if (!$isExistsPrevMonths) {
					foreach ($arrData as $k => $v) {
						$arrData[$k]['ACHIEVEMENT_IP_CFY'] = array('KHARIF' => 0, 'RABI' => 0, 'IP' => 0);
					}
				}
			}
		}
		//echo 'ssssssssssss:'.$this->db->last_query();
		//showArrayValues($recs->result());

		//echo count($arrData).'<br />';
		//showArrayValues($arrData);
		//echo 'after';
		//showArrayValues($arrData);
		return $arrData;
	}
	private function getMonthlyStatusFields()
	{
		return array(
			'ID', 'PROJECT_ID', 'PROJECT_STATUS',
			'PROJECT_STATUS_DATE', 'PROJECT_STATUS_DISPATCH_NO', 'MONTH_DATE'
		);
	}
	private function getSessionIDx($month, $year = 0)
	{
		$s = '';
		$one_month = $month;
		$month = (int) $month;
		$one_year = $year;
		if ($year == 0) {
			$year = date('Y');
		}
		$month = $month - 3;
		$session = $year . "-" . ($year + 1);
		if ($month <= 0) {
			$month = $month + 12;
			$session = ($year - 1) . "-" . ($year);
		}
		$session_ar = explode("-", $session);
		$qry = "SELECT SESSION_ID FROM __sessions 
				WHERE SESSION_START_YEAR = '" . $session_ar['0'] . "' AND 
					SESSION_END_YEAR ='" . $session_ar['1'] . "'";
		//echo $qry;
		$data = $this->db->query($qry)->result();
		$s = 0;
		foreach ($data as $v) {
			$s = $v->SESSION_ID;
		}
		return $s;
	}
	private function isMonthlyExists($id, $dt)
	{
		$recs = $this->db->get_where('dep_pmon__t_monthlydata', array('PROJECT_ID' => $id, 'MONTH_DATE' => $dt));
		return ($recs && $recs->num_rows());
	}
	/***/
	private function getMonthlyRemarkFields()
	{
		return array(
			'ID', 'PROJECT_ID', 'MONTH_DATE', 'PROJECT_STATUS_REMARK',
			'LA_CASES_STATUS_REMARK', 'SPILLWAY_STATUS_REMARK', 'FLANK_STATUS_REMARK',
			'SLUICES_STATUS_REMARK', 'NALLA_CLOSURE_STATUS_REMARK', 'CANAL_EARTH_WORK_STATUS_REMARK',
			'CANAL_STRUCTURE_STATUS_REMARK', 'CANAL_LINING_STATUS_REMARK',
		);
	}
	private function getMonthlyFields()
	{
		return array(
			'MONTHLY_DATA_ID', 'SESSION_ID', 'ENTRY_MONTH', 'PROJECT_ID',
			'PROJECT_STATUS', 'EXPENDITURE_TOTAL', 'EXPENDITURE_WORKS',
			'LA_NO', 'LA_HA', 'FA_HA', 'FA_COMPLETED_HA',
			'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES', 'CANAL_MASONRY', 'ROAD_WORKS',
			'IRRIGATION_POTENTIAL', 'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI',

			'LA_CASES_STATUS', 'SPILLWAY_STATUS', 'FLANK_STATUS',
			'SLUICES_STATUS', 'NALLA_CLOSURE_STATUS', 'CANAL_EARTH_WORK_STATUS',
			'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS',

			'SUBMISSION_DATE', 'ENTRY_YEAR', 'LOCKED', 'COMPLETION_DATE',
			'FINANCIAL_MONTH', 'MONTH_DATE',
			'LA_NO_T', 'LA_HA_T', 'FA_HA_T', 'FA_COMPLETED_HA_T',
			'LA_COMPLETED_NO_T', 'LA_COMPLETED_HA_T',
			'HEAD_WORKS_EARTHWORK_T', 'HEAD_WORKS_MASONRY_T',
			'CANAL_EARTHWORK_T', 'CANAL_LINING_T', 'CANAL_STRUCTURES_T', 'CANAL_MASONRY_T', 'ROAD_WORKS_T', 'STEEL_WORKS_T',
			'IRRIGATION_POTENTIAL_T', 'IRRIGATION_POTENTIAL_KHARIF_T', 'IRRIGATION_POTENTIAL_RABI_T'
		);
	}
	/**-------- ----------*/
	public function getSessionDropDown()
	{
		$curSession_id = $this->session->userData('CURRENT_SESSION_ID');
		$query = $this->db->query('SELECT SESSION_ID, SESSION FROM __sessions WHERE SESSION_ID <= ' . $curSession_id);
		$opt = array();
		$optData = $query->result();
		//$this->db->last_query();
		foreach ($optData as $val) {
			if ($val->SESSION_ID < PMON_START_SESSION_ID) continue;
			array_push(
				$opt,
				'<option value="' . $val->SESSION_ID . '" ' .
					(($val->SESSION_ID == $curSession_id) ? 'selected="selected"' : '')
					. '>' . $val->SESSION . '</option>'
			);
		}
		return implode('', $opt);
	}
	public function getYearDropDown()
	{
		$opt = array();
		$curYear = (int) date("Y");
		for ($i = 2013; $i <= date('Y'); $i++) {
			array_push(
				$opt,
				'<option value="' . $i . '" ' .
					(($i == $curYear) ? 'selected="selected"' : '')
					. '>' . $i . '</option>'
			);
		}
		return implode('', $opt);
	}
	public function getMonthDropDown()
	{
		$opt = array();
		$month = array(
			'Select month', 'January', 'February', 'March', 'April', 'May', 'June',
			'July', 'August', 'September', 'October', 'November', 'December'
		);
		$curMonth = ((int) date("m")) - 1;
		foreach ($month as $key => $val) {
			array_push($opt, '<option value="' . $key . '" ' .
				(($key == $curMonth) ? 'selected="selected"' : '')
				. '>' . $val . '</option>');
		}
		return implode('', $opt);
	}
	/** */
	private function SDOofficeOptions($eeId, $sel = array())
	{
		$query = $this->db->query(
			'SELECT OFFICE_ID, OFFICE_NAME, OFFICE_NAME_HINDI 
				FROM __offices 
			 WHERE HOLDING_PERSON = 5 AND PARENT_OFFICE_ID = ' . $eeId .
				' ORDER BY OFFICE_NAME ASC'
		);
		$opt = array();
		//array_push($opt, '<option value=0>Select SDO</option>');
		$optData = $query->result();
		//$this->db->last_query();
		foreach ($optData as $val) {
			array_push($opt, '<option value="' . $val->OFFICE_ID . '" ' .
				((in_array($val->OFFICE_ID, $sel)) ? 'selected="selected"' : '') . ' >' .
				$val->OFFICE_NAME . '-' . $val->OFFICE_NAME_HINDI .
				'</option>');
		}
		return implode('', $opt);
	}

	protected function getMySessionOptions($SessionProjComp, $sessionId = 0)
	{
		$vlist = array();
		array_push($vlist, '<option value="0">Select Session</option>');
		$this->db->order_by('SESSION_ID');
		$recs = $this->db->get('__sessions');
		if ($recs && $recs->num_rows()) {
			foreach ($recs->result() as $rec) {
				if ($rec->SESSION_ID < PMON_START_SESSION_ID) continue;
				if ($rec->SESSION_ID <= $SessionProjComp) {
					array_push(
						$vlist,
						'<option value="' . $rec->SESSION_ID . '" ' .
							(($sessionId == $rec->SESSION_ID) ? 'selected="selected"' : '') . '>' .
							$rec->SESSION_START_YEAR . '-' . $rec->SESSION_END_YEAR . '</option>'
					);
				} else {
					break;
				}
			}
		}
		return implode('', $vlist);
	}
	private function getSessionID($month, $year = 0)
	{
		$s = '';
		$one_month = $month;
		$one_year = $year;
		if ($year == 0) {
			$year = date('Y');
		}
		$month = $month - 3;
		$session = $year . "-" . ($year + 1);
		if ($month <= 0) {
			$month = $month + 12;
			$session = ($year - 1) . "-" . ($year);
		}
		$session_ar = explode("-", $session);
		$strSQL = "SELECT SESSION_ID FROM __sessions WHERE SESSION_START_YEAR = '" . $session_ar['0'] . "' AND SESSION_END_YEAR ='" . $session_ar['1'] . "'";
		$recs = $this->db->query($strSQL);
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			$s = $rec->SESSION_ID;
		}
		return $s;
	}

	private function checkMonthlyLockStatus($PROJECT_ID, $date)
	{
		$rec = $this->db->get_where('dep_pmon__t_locks', array('PROJECT_ID' => $PROJECT_ID));
		if ($rec && $rec->num_rows() == 1) {
			$row = $rec->row();
			if ($row->MONTH_LOCK < $date) {
				return 0;
			}
			return 1;
		}
		return 0;
	}
	/**OK - 07-09-2013*/

	public function getRAAList()
	{
		$PROJECT_ID = (int) $this->input->post('PROJECT_ID');
		$this->db->distinct();
		$this->db->select('RAA_PROJECT_ID, RAA_DATE, RAA_NO, IS_RAA');
		$this->db->order_by('RAA_DATE');
		$recs = $this->db->get_where('dep_pmon__t_raa_project', array('PROJECT_ID' => $PROJECT_ID));
		$vOpt = '<option value="">Select</option>';
		$completed = FALSE;
		$lastMonth = '';
		$arrET = array('', 'RAA', 'EQ', 'TS');
		if ($recs && $recs->num_rows()) {
			foreach ($recs->result() as $rec) {
				$vOpt .= '<option value="' . $rec->RAA_PROJECT_ID . '">' . $arrET[$rec->IS_RAA] . ' - ' .
					$rec->RAA_NO . ' [' . date("d-m-Y", strtotime($rec->RAA_DATE)) .
					']</option>';
			}
		}
		echo $vOpt;
	}

	public function printRAA()
	{
		$projectId = (int) $this->input->post('PROJECT_ID');
		$raaId = (int) $this->input->post('raaid');
		$xno = (int) $this->input->post('xno');
		$recs = $this->db->get_where(
			'dep_pmon__v_projectlist_with_lock',
			array('PROJECT_ID' => $projectId)
		);
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			$arrProjectData = array(
				'PROJECT_CODE' => $rec->PROJECT_CODE,
				'PROJECT_NAME' => $rec->PROJECT_NAME,
				'PROJECT_NAME_HINDI' => $rec->PROJECT_NAME_HINDI,
				'AA_NO' => $rec->AA_NO,
				'AA_AUTHORITY_ID' => $rec->AA_AUTHORITY_ID,
				'AA_DATE' => $rec->AA_DATE,
				'AA_AMOUNT' => $rec->AA_AMOUNT
			);
		}
		$data['projectData'] = $arrProjectData;
		$arrFields = $this->getFields('dep_pmon__t_raa_project');
		//$this->db->select('RAA_PROJECT_ID, RAA_DATE, RAA_NO');
		$recs = $this->db->get_where('dep_pmon__t_raa_project', array('RAA_PROJECT_ID' => $raaId));
		$data['raaData'] = FALSE;
		$arrD = array();
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			foreach ($arrFields as $k) {
				$arrD[$k] = $rec->{$k};
			}
			$data['raaData'] = $arrD;
		}
		$data['AuthorityName'] = $this->getAuthority($arrD['RAA_AUTHORITY_ID']);

		$this->getPreviousEstimated($raaId, $projectId);
		$data['estimationStatus'] = $this->getEstimationStatus($projectId);
		$data['previousEstimation'] = $this->previousEstimation;
		$data['currentEstimation'] = $this->currentEstimation;
		/*echo 'Esti';
		showArrayValues($data['estimationStatus']);
		echo 'Prev Esti';
		showArrayValues($data['previousEstimation']);
		echo 'Curr Esti';
		showArrayValues($data['currentEstimation']);*/

		$myview = $this->load->view('promon_deposit/print/raa_data_print_view', $data, true);
		//echo $myview ;
		array_push($this->message, getMyArray(null, $myview));
		echo $xno . '####' . createJSONResponse($this->message);
	}
	protected function getAuthority($AuthID = 0)
	{
		$this->db->select('AUTHORITY_NAME');
		$recs = $this->db->get_where('dep_pmon__m_authority', array('AUTHORITY_ID' => $AuthID));
		$a = '';
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			$a = $rec->AUTHORITY_NAME;
			$recs->free_result();
		}
		return $a;
	}
	protected function getEstimationStatus($projectId)
	{
		$mFields = array(
			'PROJECT_ID', 'LA_NA', 'FA_NA',
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 'STEEL_WORKS_NA',
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA', 'ROAD_WORKS_NA',
			'CANAL_LINING_NA', 'IRRIGATION_POTENTIAL_NA'
		);
		$recs = $this->db->get_where('dep_pmon__t_estimated_status', array('PROJECT_ID' => $projectId));
		if ($recs && $recs->num_rows()) {
			$rec = $recs->row();
			for ($i = 0; $i < count($mFields); $i++) {
				$data[$mFields[$i]] = $rec->{$mFields[$i]};
			}
		} else {
			for ($i = 0; $i < count($mFields); $i++) {
				$data[$mFields[$i]] = 0;
			}
		}
		return $data;
	}
	private function getPreviousEstimated($raaProjectId, $projectId)
	{
		$prevRAAId = 0;
		if ($raaProjectId == 0) {
			//search last record in RAA for project id
			$strSQL = 'SELECT RAA_PROJECT_ID 
					FROM dep_pmon__t_raa_project WHERE PROJECT_ID=' . $projectId .
				' ORDER BY RAA_PROJECT_ID DESC LIMIT 0, 1';
			$recs = $this->db->query($strSQL);
			if ($recs && $recs->num_rows()) {
				foreach ($recs->result() as $rec) {
					$prevRAAId = $rec->RAA_PROJECT_ID;
				}
			}
		} else {
			//search previous RAA record for 1 less than raa record
			$strSQL = 'SELECT RAA_PROJECT_ID 
					FROM dep_pmon__t_raa_project WHERE RAA_PROJECT_ID<' . $raaProjectId .
				' AND PROJECT_ID=' . $projectId .
				' ORDER BY RAA_PROJECT_ID DESC LIMIT 0, 1';
			$recs = $this->db->query($strSQL);
			if ($recs && $recs->num_rows()) {
				foreach ($recs->result() as $rec) {
					$prevRAAId = $rec->RAA_PROJECT_ID;
				}
			}
		}
		$arrEFields = $this->getEstimationFields();
		$arrEValues = array();
		for ($i = 0; $i < count($arrEFields); $i++) {
			$arrEValues[$arrEFields[$i]] = 0;
		}
		//if not found then show estimation aa record
		if ($prevRAAId) {
			$strSQL = 'SELECT * FROM dep_pmon__t_estimated_qty 
				WHERE RAA_ID=' . $prevRAAId . ' AND PROJECT_ID=' . $projectId;
			//echo $strSQL;
			$result = $this->db->query($strSQL);
			if ($result && ($result->num_rows() == 0)) {
				$prevRAAId = 0;
			}
		}
		$strSQL = 'SELECT * FROM dep_pmon__t_estimated_qty 
			WHERE RAA_ID=' . $prevRAAId . ' AND PROJECT_ID=' . $projectId;
		//echo $strSQL;
		$result = $this->db->query($strSQL);
		if ($result && $result->num_rows()) {
			foreach ($result->result() as $rec) {
				for ($i = 0; $i < count($arrEFields); $i++) {
					$arrEValues[$arrEFields[$i]] = $rec->{$arrEFields[$i]};
				}
			}
		}
		//echo 'estimation:'.$prevRAAId. '::';
		//showArrayValues($arrEValues);
		$this->previousEstimation = $arrEValues;
		//now capture current values
		$arrEValues = array();
		for ($i = 0; $i < count($arrEFields); $i++) {
			$arrEValues[$arrEFields[$i]] = 0;
		}
		if ($raaProjectId == 0) {
			//do nothing
		} else {
			$strSQL = 'SELECT * FROM dep_pmon__t_estimated_qty 
				WHERE RAA_ID=' . $raaProjectId . ' AND PROJECT_ID=' . $projectId;
			$result = $this->db->query($strSQL);
			if ($result && $result->num_rows()) {
				foreach ($result->result() as $rec) {
					for ($i = 0; $i < count($arrEFields); $i++) {
						$arrEValues[$arrEFields[$i]] = $rec->{$arrEFields[$i]};
					}
				}
			}
		}
		//,$previousEstimation $currentEstimation;
		$this->currentEstimation = $arrEValues;
		//showArrayValues($arrEValues);
	}
	protected function getEstimationFields()
	{
		return array(
			'ESTIMATED_QTY_ID', 'PROJECT_ID', 'RAA_ID', 'SESSION_ID',
			'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA',
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS',
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING',
			'IRRIGATION_POTENTIAL_KHARIF',
			'IRRIGATION_POTENTIAL_RABI',
			'IRRIGATION_POTENTIAL',
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK'
		);
	}
	/** getMonthlyStatus()*/
	private function getFinancialMonthByMonth($month)
	{
		return (($month >= 4 and $month <= 12) ? ($month - 3) : ($month + 9));
	}
	public function printMP()
	{
		$IS_MI = (int) $this->input->post('IS_MI');
		if ($IS_MI == 1) {
			require('mi/Entry_report_mi_c.php');
			$entry_report_mi_c = new Entry_report_mi_c();
			$entry_report_mi_c->printMP();
			return;
		}
		//$PROJECT_SETUP_ID = (int) $this->input->post('PROJECT_SETUP_ID');
		$projectId = (int) $this->input->post('PROJECT_ID');
		$xno = (int) $this->input->post('xno');
		$this->db->select('PROGRESS_DATE, PROGRESS');
		$this->db->order_by('PROGRESS_DATE', 'ASC');
		$recs = $this->db->get_where('dep_pmon__t_progress', array('PROJECT_ID' => $projectId));
		$content = '<table border="0" cellpadding="8" cellspacing="2" class="ui-widget-content" id="rptMP">
		<thead>
		<tr><th class="ui-state-default" valign="middle">Month</th>
					<th class="ui-state-default" valign="middle">Progress</th></tr>
					</thead><tbody>';
		if ($recs && $recs->num_rows()) {
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
		echo $xno . '####' . createJSONResponse($this->message);
	}

	public function showProjectDataBox(){
		$PROJECT_ID = $this->input->post('PROJECT_ID');
		$PROJECT_SETUP_ID =$this->input->post('PROJECT_SETUP_ID');
		$IS_MI = $this->input->post('IS_MI');
		if($IS_MI==1){
			$this->load->model('pmon_dep/dep_mi__m_target');
			$eworkData = $this->dep_mi__m_target->getEWorksDetailsDeposit($PROJECT_SETUP_ID);
		}else{
			$eworkData = $this->getEWorksDetailsDeposit($PROJECT_ID);
		}
		showArrayValues($eworkData); //exit;
		$this->load->library('mycurl');

		// exit;
		if(!IS_LOCAL_SERVER){
			//showArrayValues($eworkData);
			// 
			//below code is commented on 17-06-2020 , uncomment it before live update
        	$result = $this->mycurl->savePmonDepositData($eworkData);
			$result_arr =json_decode($result, true);
			showArrayValues($result_arr);
			/*if( isset($result_arr['success'] ) ){
				
			}else{
				echo '<span class="cus-lock"></span> Unable to Lock Target'; exit; 
			}*/			
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
}