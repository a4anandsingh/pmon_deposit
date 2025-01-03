<?php include_once("Project_library.php");
//all variable name should be in camelcase
class Project_c extends Project_library{
	private $arrSetupData;
	function __construct(){
		parent::__construct();
		$this->message = array();
		$this->arrSetupData = array();
	}
	function index(){
		//print_r($_SESSION);
		$arrProjectType = array('', 'Minor', 'Medium', 'Major');
		$this->session->set_userdata(array('SETUP_PROJECT_TYPE_ID' => 1));
		$data['page_heading'] = pageHeading('DEPOSIT PROMON - '. $arrProjectType[1].' Project Setup Master');
		$this->load->library('office_filter');
		$data['office_list'] = $this->office_filter->office_list();
		$data['message'] = '';
		//$data['project_grid'] = $this->createGrid().$this->createGrid1().$this->createCompletedGrid().$this->createCompletedGrid(1);
		
		// >>> Testing pupose only for :-  Executive Engineer, E/M Heavy Machinery Division, Raipur
		$currentOfficeId= $this->session->userData('CURRENT_OFFICE_ID');
		$data['project_grid'] = $this->createGrid();
			if($currentOfficeId==31 || $currentOfficeId==69 ){
				$data['project_grid'].=$this->createGrid1();
			}
		$data['project_grid'].=$this->createCompletedGrid().$this->createCompletedGrid(1);
		// <<<
		$data['PROJECT_TYPE_ID'] = 1;
		$this->load->view('pmon_deposit/project_index_view', $data);
	}
	//
	private function createLockRecord(){
		$this->db->select('PROJECT_ID');
		$this->db->order_by('PROJECT_ID', 'ASC');
		$recs = $this->db->get('pmon__m_project_setup');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrWhere = array('PROJECT_ID'=>$rec->PROJECT_ID);
				$prec = $this->db->get_where('pmon__t_locks', $arrWhere);
				$isExists = (($prec && $prec->num_rows())? true : false);
				if(!$isExists){
					@$this->db->insert('pmon__t_locks', $arrWhere);
				}
			}
		}
	}
	//
	private function createGrid(){
		$permissions = $this->getPermissions();
		$buttons = array();
		/*if ($permissions['DELETE']){
			array_push(
				$buttons, 
				"{ caption:'', title:'Delete Record', position :'first', 
					buttonicon : 'ui-icon-trash', 
					onClickButton:function(){projectOperation(BUTTON_DELETE, 0);}, cursor: 'pointer'}"
			);
		}*/
		if ($permissions['MODIFY']){
			array_push(
				$buttons, 
				"{ caption:'', title:'Edit Record',position :'first', 
				buttonicon : 'ui-icon-pencil', 
				onClickButton:function(){projectOperation(BUTTON_MODIFY, 0);}, cursor: 'pointer'}"
			);
		}
		if ($permissions['ADD']){
			array_push(
				$buttons, 
				"{ caption:'', title:'Add New Record',position :'first', 
				buttonicon : 'ui-icon-plus', 
				onClickButton:function(){projectOperation(BUTTON_ADD_NEW, 0);}, cursor: 'pointer'}"
			);
		}
		$mfunctions = array();
		array_push(
			$mfunctions , 
			"ondblClickRow: function(ids){projectOperation(BUTTON_MODIFY, 0);}"
		);
		$aData = array(
			'set_columns' => array(
				array(
					'label' => 'Project Name',
					'name' => 'PROJECT_NAME',
					'width' => 130,
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
					'label' => 'Project Code',
					'name' => 'PROJECT_CODE',
					'width' => 40,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				),
				array(
				 	'label' => 'Start Date',
					'name' => 'PROJECT_START_DATE',
					'width' => 30,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> 'date',
					'newformat'=> 'd M, Y',
					'srcformat'=> 'Y-m-d',
					'searchoptions'=>''
				),
				array(
					'label' => 'Locked',
					'name' => 'LOCKED',
					'width' => 15,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true
				),
				array(
				 	'label' => 'id',
					'name' => 'PROJECT_ID',
					'width' => 70,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>true,
					'search'=>true,
					'view'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				)
			),
			'custom' => array("button"=>$buttons, "function"=>$mfunctions),
			'div_name' => 'projectList',
			'source' => 'getProjectListGrid',
			'postData' => '{}',
			'rowNum'=>10,
			'width'=>DEFAULT_GRID_WIDTH,
			'height' => '',
			'altRows'=> true,
			'rownumbers'=>true,	
			'sort_name' => 'PROJECT_NAME',
			'sort_order' => 'asc',
			'primary_key' => 'PROJECT_ID',
			'caption' => '<span class="cus-dam-1"></span> Ongoing Projects - निर्माणाधीन परियोजनाएं',
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
			'custom_button_position'=>'bottom'
		);
		return buildGrid($aData);
	}
	private function createGrid1(){
		$permissions = $this->getPermissions();
		$buttons = array();
		if ($permissions['MODIFY']){
			array_push(
				$buttons, 
				"{ caption:'', title:'Edit Record',position :'first', 
				buttonicon : 'ui-icon-pencil', 
				onClickButton:function(){projectOperation1(BUTTON_MODIFY, 0);}, cursor: 'pointer'}"
			);
		}
	 
		$mfunctions = array();
		array_push(
			$mfunctions , 
			"ondblClickRow: function(ids){projectOperation1(BUTTON_MODIFY, 0);}"
		);
		$aData = array(
			'set_columns' => array(
				array(
					'label' => 'Project Name',
					'name' => 'PROJECT_NAME',
					'width' => 130,
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
					'label' => 'Project Code',
					'name' => 'PROJECT_CODE',
					'width' => 40,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				),
				array(
				 	'label' => 'Start Date',
					'name' => 'PROJECT_START_DATE',
					'width' => 30,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> 'date',
					'newformat'=> 'd M, Y',
					'srcformat'=> 'Y-m-d',
					'searchoptions'=>''
				),
				array(
					'label' => 'Locked',
					'name' => 'LOCKED',
					'width' => 15,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true
				),
				array(
				 	'label' => 'id',
					'name' => 'PROJECT_SETUP_ID]',
					'width' => 70,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>true,
					'search'=>true,
					'view'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				)
			),
			'custom' => array("button"=>$buttons, "function"=>$mfunctions),
			'div_name' => 'projectList1',
			'source' => 'getProjectListGrid1',
			'postData' => '{}',
			'rowNum'=>10,
			'width'=>DEFAULT_GRID_WIDTH,
			'height' => '',
			'altRows'=> true,
			'rownumbers'=>true,	
			'sort_name' => 'PROJECT_NAME',
			'sort_order' => 'asc',
			'primary_key' => 'PROJECT_SETUP_ID',
			'caption' => '<span class="cus-drop"></span> Ongoing Projects - निर्माणाधीन परियोजनाएं - नलकूप एवं सूक्ष्म सिंचाई',
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
			'custom_button_position'=>'bottom'
		);
		return buildGrid($aData);
	}
	private function createCompletedGrid($mode=0){
		$buttons = array();
		$mfunctions = array();
		$aData = array(
			'set_columns' => array(
				array(
					'label' => 'Project Name',
					'name' => 'PROJECT_NAME',
					'width' => 130,
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
					'width' => 90,
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
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				),
				array(
				 	'label' => 'Start Date',
					'name' => 'PROJECT_START_DATE',
					'width' => 30,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> 'date',
					'newformat'=> 'd M, Y',
					'srcformat'=> 'Y-m-d',
					'searchoptions'=>''
				),
				array(
					'label' => 'Locked',
					'name' => 'LOCKED',
					'width' => 15,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true
				),
				array(
				 	'label' => 'id',
					'name' => 'PROJECT_SETUP_ID',
					'width' => 70,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>true,
					/*'key'=>false,*/
					'search'=>true,
					'view'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				)
			),
			'custom' => array("button"=>$buttons, "function"=>$mfunctions),
			'div_name' => (($mode)? 'dprojectList':'cprojectList'),
			'source' =>'getCProjectListGrid/'.$mode,
			'postData' => '{}',
			'rowNum'=>10,
			'width'=>DEFAULT_GRID_WIDTH,
			'height' => '',
			'altRows'=> true,
			'rownumbers'=>true,	
			'sort_name' => 'PROJECT_NAME',
			'sort_order' => 'asc',
			'primary_key' => 'PROJECT_ID',
			'caption' => '<span class="cus-dam"></span> '.(($mode)? ' Dropped Projects':' Completed Projects - निर्मित परियोजनाएं'),
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
			'custom_button_position'=>'bottom' 
		);
		return buildGrid($aData);
	}	
	//
	public function getProjectListGrid(){
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);
		//$SDO_ID = $this->input->post('SDO_ID');
		/* =============== */
		if ( $this->input->post('SEARCH_PROJECT_NAME') ){
			array_push(
				$objFilter->SQL_PARAMETERS, 
				array("PROJECT_NAME", 'LIKE', $this->input->post('SEARCH_PROJECT_NAME'))
			);
		}
		$EE_ID = $this->input->post('EE_ID');
		$CE_ID = $this->input->post('CE_ID');
		$SE_ID = $this->input->post('SE_ID');
		$SDO_ID = $this->input->post('SDO_ID');
		if($EE_ID==false && $CE_ID==false && $SE_ID==false && $SDO_ID==false){
			$EE_ID = $this->session->userData('EE_ID');
			$SE_ID = $this->session->userData('SE_ID');
			$CE_ID = $this->session->userData('CE_ID');
			$SDO_ID = $this->session->userData('SDO_ID');
		}
		$w = ' AND ((PROJECT_STATUS<5) OR ((PROJECT_STATUS>=5) AND (SE_COMPLETION=0))) ';
		if ($EE_ID==0 && $SE_ID==0 && $CE_ID==0){
			//NO OPTION SELECTED BY E-IN-C
			//array_push($objFilter->WHERE,  ' 1 GROUP BY PROJECT_ID');
			$objFilter->SQL = 'SELECT PROJECT_SETUP_ID, PROJECT_ID, PROJECT_START_DATE,
				PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, 
				IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					"<span class=\'cus-bullet-green\'></span>") as LOCKED 
				FROM dep_pmon__v_projectlist_with_lock 
					WHERE PROJECT_TYPE_ID = '.$this->session->userData('SETUP_PROJECT_TYPE_ID')
					.$w;
			//pmon__v_project_list_with_setup_lock_status
		}else{
			$EEE = '';//($SDO_ID==0)? '' : ' OFFICE_SDO_ID='.$SDO_ID;
			$EEE .= ($EE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_EE_ID='.$EE_ID;
			$EEE .= ($SE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SE_ID='.$SE_ID;
			$EEE .= ($CE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_CE_ID='.$CE_ID;
			$EEE .= ($SDO_ID==0)?  '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SDO_ID='.$SDO_ID;
			if($this->session->userData('HOLDING_PERSON')!=4){
				//$EEE .= ' AND LOCK_STATUS=1 ';
				$HOLDING_PERSON = $this->session->userData('HOLDING_PERSON');
			}
			//pmon__v_projects_setup 
			//array_push($objFilter->WHERE, $EEE);//. ' GROUP BY PROJECT_ID'); OFFICE_CE_ID
			$objFilter->SQL = 'SELECT DISTINCT(PROJECT_SETUP_ID), PROJECT_ID, PROJECT_START_DATE,
					PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, 
					IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					"<span class=\'cus-bullet-green\'></span>") as LOCKED 
				FROM dep_pmon__v_projectlist_with_lock 
					WHERE PROJECT_TYPE_ID = '.$this->session->userData('SETUP_PROJECT_TYPE_ID')
					.$w. ' AND '.$EEE;

			//pmon__v_project_list_with_setup_lock_status
		}
		$objFilter->executeMyQuery();

		/* =============== */
		$fields = array(
			array('PROJECT_NAME', FALSE),
			array('PROJECT_NAME_HINDI', FALSE),
			array('PROJECT_CODE', FALSE),
			array('PROJECT_START_DATE', FALSE),
			array('LOCKED', FALSE),
			array('PROJECT_ID', FALSE)
		);
		echo $objFilter->getJSONCode('PROJECT_ID', $fields);
		//echo $objFilter->PREPARED_SQL;
		//echo $objFilter->getJSONCodeByRow();
	}

	public function getProjectListGrid1(){
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);
		//$SDO_ID = $this->input->post('SDO_ID');
		/* =============== */
		if ( $this->input->post('SEARCH_PROJECT_NAME') ){
			array_push(
				$objFilter->SQL_PARAMETERS, 
				array("PROJECT_NAME", 'LIKE', $this->input->post('SEARCH_PROJECT_NAME'))
			);
		}
		$EE_ID = $this->input->post('EE_ID');
		$CE_ID = $this->input->post('CE_ID');
		$SE_ID = $this->input->post('SE_ID');
		$SDO_ID = $this->input->post('SDO_ID');
		if($EE_ID==false && $CE_ID==false && $SE_ID==false && $SDO_ID==false){
			$EE_ID = $this->session->userData('EE_ID');
			$SE_ID = $this->session->userData('SE_ID');
			$CE_ID = $this->session->userData('CE_ID');
			$SDO_ID = $this->session->userData('SDO_ID');
		}
		$w = ' AND ((PROJECT_STATUS<5) OR ((PROJECT_STATUS>=5) AND (SE_COMPLETION=0))) ';
		if ($EE_ID==0 && $SE_ID==0 && $CE_ID==0){
			//NO OPTION SELECTED BY E-IN-C
			//array_push($objFilter->WHERE,  ' 1 GROUP BY PROJECT_ID');
			$objFilter->SQL = 'SELECT 
					DISTINCT(ps.PROJECT_SETUP_ID), ps.AA_DATE 
					as PROJECT_START_DATE,ps.PROJECT_NAME,ps.PROJECT_NAME_HINDI ,ps.PROJECT_CODE,IF(l.SETUP_LOCK=1,  "<span class=\'cus-lock\'></span>",
					"<span class=\'cus-bullet-green\'></span>") as LOCKED 
					FROM dep_mi__m_project_setup ps 
					LEFT JOIN dep_mi__t_locks l on l.PROJECT_SETUP_ID=ps.PROJECT_SETUP_ID 
					LEFT JOIN dep_mi__m_projects_office off on off.PROJECT_SETUP_ID=ps.PROJECT_SETUP_ID  
					WHERE ((ps.WORK_STATUS<5) OR (ps.WORK_STATUS>=5))';
			//pmon__v_project_list_with_setup_lock_status
		}else{
			$EEE = '';	//($SDO_ID==0)? '' : ' OFFICE_SDO_ID='.$SDO_ID;
			$EEE .= ($EE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_EE_ID='.$EE_ID;
			$EEE .= ($SE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SE_ID='.$SE_ID;
			$EEE .= ($CE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_CE_ID='.$CE_ID;
			$EEE .= ($SDO_ID==0)?  '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SDO_ID='.$SDO_ID;
			if($this->session->userData('HOLDING_PERSON')!=4){
				//$EEE .= ' AND LOCK_STATUS=1 ';
				$HOLDING_PERSON = $this->session->userData('HOLDING_PERSON');
			}
			//pmon__v_projects_setup 
			//array_push($objFilter->WHERE, $EEE);//. ' GROUP BY PROJECT_ID'); OFFICE_CE_ID
			$objFilter->SQL = '	SELECT 
				DISTINCT(ps.PROJECT_SETUP_ID),ps.AA_DATE as PROJECT_START_DATE,
				ps.PROJECT_NAME,ps.PROJECT_NAME_HINDI,ps.PROJECT_CODE,
				IF(l.SETUP_LOCK=1,  "<span class=\'cus-lock\'></span>",
					"<span class=\'cus-bullet-green\'></span>") as LOCKED
				FROM dep_mi__m_project_setup ps 
				LEFT JOIN dep_mi__t_locks l 
					ON l.PROJECT_SETUP_ID=ps.PROJECT_SETUP_ID 
				LEFT JOIN dep_mi__m_projects_office off 
					ON off.PROJECT_SETUP_ID=ps.PROJECT_SETUP_ID  
				WHERE 
					((ps.WORK_STATUS<5) OR (ps.WORK_STATUS>=5)) 
					AND off.EE_ID='.$EE_ID;
			//pmon__v_project_list_with_setup_lock_status
		}
		$objFilter->executeMyQuery();
		/* =============== */
		$fields = array(
			array('PROJECT_NAME', FALSE),
			array('PROJECT_NAME_HINDI', FALSE),
			array('PROJECT_CODE', FALSE),
			array('PROJECT_START_DATE', FALSE),
			array('LOCKED', FALSE),
			array('PROJECT_SETUP_ID', FALSE)
		);
		echo $objFilter->getJSONCode('PROJECT_SETUP_ID', $fields);
		//echo $objFilter->PREPARED_SQL;
		//echo $objFilter->getJSONCodeByRow();
	}

	public function getCProjectListGrid($mode){
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);		
		//$SDO_ID = $this->input->post('SDO_ID');
		/* =============== */
		if ( $this->input->post('SEARCH_PROJECT_NAME') ){
			array_push(
				$objFilter->SQL_PARAMETERS, 
				array("PROJECT_NAME", 'LIKE', $this->input->post('SEARCH_PROJECT_NAME'))
			);
		}
		$EE_ID = $this->input->post('EE_ID');
		$CE_ID = $this->input->post('CE_ID');
		$SE_ID = $this->input->post('SE_ID');
		$SDO_ID = $this->input->post('SDO_ID');
		if($EE_ID==false && $CE_ID==false && $SE_ID==false && $SDO_ID==false){
			$EE_ID = $this->session->userData('EE_ID');
			$SE_ID = $this->session->userData('SE_ID');
			$CE_ID = $this->session->userData('CE_ID');
			$SDO_ID = $this->session->userData('SDO_ID');
		}
		if ($EE_ID==0 && $SE_ID==0 && $CE_ID==0){
			//NO OPTION SELECTED BY E-IN-C
			array_push($objFilter->WHERE,  ' 1 GROUP BY PROJECT_ID');
			$objFilter->SQL = 'SELECT PROJECT_SETUP_ID, PROJECT_ID, PROJECT_START_DATE,
				PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, 
				IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
						 "<span class=\'cus-bullet-green\'></span>") as LOCKED
				FROM dep_pmon__v_projectlist_with_lock 
					WHERE PROJECT_TYPE_ID = '.$this->session->userData('SETUP_PROJECT_TYPE_ID'). 
						' AND PROJECT_STATUS='.(($mode)? 6:5) . ' AND IS_COMPLETED=1 and SE_COMPLETION=1 ';
			//			pmon__v_project_list_with_setup_lock_status
		}else{
			$EEE = '';//($SDO_ID==0)? '' : ' OFFICE_SDO_ID='.$SDO_ID;
			$EEE .= ($EE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_EE_ID='.$EE_ID;
			$EEE .= ($SE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SE_ID='.$SE_ID;
			$EEE .= ($CE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_CE_ID='.$CE_ID;
			$EEE .= ($SDO_ID==0)?  '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SDO_ID='.$SDO_ID;
			if($this->session->userData('HOLDING_PERSON')!=4){
				//$EEE .= ' AND LOCK_STATUS=1 ';
				$HOLDING_PERSON = $this->session->userData('HOLDING_PERSON');
				//$EEE .= ($HOLDING_PERSON==4)? '' : ( ($EEE=='') ? '':' AND ').' LOCKED_HOLDING_PERSON='.($HOLDING_PERSON+1);
				//$EEE .= ($HOLDING_PERSON==4)? '' : ( ($EEE=='') ? '':' AND ').' LOCKED_HOLDING_PERSON='.($HOLDING_PERSON+1);
				//$EEE .= ' AND MODULE_KEY="PMON_MINOR_PROJECT_SETUP" ';
				$EEE .= ' GROUP BY PROJECT_ID';
			}
			//pmon__v_projects_setup 
			array_push($objFilter->WHERE, $EEE);//. ' GROUP BY PROJECT_ID');
			$objFilter->SQL = 'SELECT DISTINCT PROJECT_SETUP_ID, PROJECT_ID, PROJECT_START_DATE,
					PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, 
					IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					"<span class=\'cus-bullet-green\'></span>") as LOCKED
				FROM dep_pmon__v_projectlist_with_lock
					WHERE PROJECT_TYPE_ID = '.$this->session->userData('SETUP_PROJECT_TYPE_ID'). 
						' AND PROJECT_STATUS='.(($mode)? 6:5).  ' AND IS_COMPLETED=1 and SE_COMPLETION=1 ';
			//pmon__v_project_list_with_setup_lock_status
		}
		/* =============== */
		$fields = array(
			array('PROJECT_NAME', FALSE),
			array('PROJECT_NAME_HINDI', FALSE),
			array('PROJECT_CODE', FALSE),
			array('PROJECT_START_DATE', FALSE),
			array('LOCKED', FALSE),
			array('PROJECT_ID', FALSE)
		);
		echo $objFilter->getJSONCode('PROJECT_SETUP_ID', $fields);
		//echo $objFilter->PREPARED_SQL;
	}
	//showProjectSetupEntryBox
	public function showProjectSetupEntryBox(){
		$this->PROJECT_SETUP_ID = (int) trim($this->input->post('PROJECT_SETUP_ID'));
		$this->PROJECT_ID = (int) trim($this->input->post('PROJECT_ID'));
	    //	echo $this->PROJECT_SETUP_ID."ggggg".$this->PROJECT_ID;exit;
		$permissions = $this->getPermissions();
		//check if ee
		if($this->session->userdata('HOLDING_PERSON')!=4){
			//if SE
			$goAhead = false;
			if($this->session->userdata('HOLDING_PERSON')==3){
				if ($permissions['ADD'])
					$goAhead = (($this->PROJECT_ID)? false:true);
			}
			if(!$goAhead){
				$data['report'] = array('mode'=>'permission', 'report'=>true);
				$mView= $this->load->view('utility/lock_view', $data, TRUE);
				array_push($this->message, getMyArray(null, $mView));
				echo createJSONResponse( $this->message );
				return;
			}
		}
		if($this->PROJECT_ID!=0 && $this->PROJECT_SETUP_ID!=0)
			$data['LockStatus'] = (($this->PROJECT_ID) ? $this->getLockStatus(1):0);
		else if($this->PROJECT_ID==0 && $this->PROJECT_SETUP_ID!=0)
			$data['LockStatus'] = (($this->PROJECT_ID) ? $this->getLockStatus1(1):0);
		else
			$data['LockStatus'] = 0;
		if($data['LockStatus']==1){
			$data['report'] = array('mode'=>'lock', 'report'=>true);
			$mView = $this->load->view('utility/lock_view', $data, TRUE);
			array_push($this->message, getMyArray(null, $mView));
			echo createJSONResponse( $this->message );
		}else{			
			$this->showProjectSetupData();
		}
	}

	public function projectType($projectId,$PROJECT_SETUP_ID){
		$rec=$this->db->where(array('PROJECT_SETUP_ID'=>$PROJECT_SETUP_ID, 'PROJECT_ID'=>'0'))->get('dep_mi__m_project_setup');
		if($rec->num_rows()>0)
			return 1;
		else
			return 2;
	}

	private function showProjectSetupData(){
		$projectSetupFields = $this->getProjectSetupFields();
		$projectSetupValues = array();
		for($i=0; $i<count($projectSetupFields);$i++){
			$projectSetupValues[ $projectSetupFields[$i] ] = '';
		}
		$editMode = (($this->PROJECT_ID)?true:false);
		$ProjectType=$this->projectType($this->PROJECT_ID,$this->PROJECT_SETUP_ID);
		$data['oper'] = 'add';
		$data['SESSION_START_YEAR'] = 0;
		$data['SESSION_END_YEAR'] = 0;
		//ProjectType ==1 means MI module , ProjectType==2 means PMON module}
        //echo 'Project Type ='. $ProjectType; exit;
		if($ProjectType==1){
            require('mi/Project_mi_c.php');
            $Project_mi_c = new Project_mi_c();
            $Project_mi_c->showProjectSetupEntryBox();
		}else{
			if($editMode){
				$arrWhere =	 array('PROJECT_ID'=>$this->PROJECT_ID);
				$recs = $this->db->get_where('dep_pmon__v_projectlist_details_with_lock', $arrWhere);
				if($recs && $recs->num_rows()){
					$rec = $recs->row();
					for($i=0; $i<count($projectSetupFields);$i++){
						$projectSetupValues[ $projectSetupFields[$i] ] = $rec->{$projectSetupFields[$i]};
					}
					$projectSetupValues['DISTRICT_NAME'] = $projectSetupValues['DISTRICT_NAME'];
				}			
			}
			$projectSetupValues['AA_SESSION_ID'] = $this->getSessionIdByDate($projectSetupValues['AA_DATE']);
			$data['monthlyRecordExists'] = $this->isMonthlyExists();
			$data['SESSION_OPTIONS'] = '';

            /** New Modification for Add Edit and Office Fillter */
            if($projectSetupValues['OFFICE_CE_ID']){
                $data['OFFICE_LIST'] = $this->getOfficeList('CE', $projectSetupValues['OFFICE_CE_ID']);
            }else{
                $data['OFFICE_LIST'] = $this->getOfficeList('CE');
            }
            $data['PROJECT_TYPE_ID'] = $this->session->userData('SETUP_PROJECT_TYPE_ID');
            $data['PROJECT_TYPE_LIST'] = $this->getProjectTypeList($projectSetupValues['PROJECT_TYPE_ID']);

            if($projectSetupValues['PROJECT_SUB_TYPE_ID']){
                $data['PROJECT_SUB_TYPE_LIST'] = $this->getProjectSubTypeList(
                    $data['PROJECT_TYPE_ID'], $projectSetupValues['PROJECT_SUB_TYPE_ID']
                );
            }else{
                $data['PROJECT_SUB_TYPE_LIST'] = $this->getProjectSubTypeList($data['PROJECT_TYPE_ID']);
            }

            $data['projectSubType'] = $this->getProjectSubType($projectSetupValues['PROJECT_SUB_TYPE_ID']);
            /** AA */
            //echo 'BB:'.$this->PROJECT_ID.':BB';
            $data['AUTH_VALUES'] = $this->getAuthority($projectSetupValues['AA_AUTHORITY_ID']);
            $data['DIST_HEAD'] = $this->getDistrictOptions($projectSetupValues['HEAD_WORK_DISTRICT_ID']);
            if($editMode){
                $data['optDistricts'] = $this->getDistrictOptions();
                 $data['BLOCK_HEAD'] = $this->getBlockOptions(
                    $projectSetupValues['HEAD_WORK_DISTRICT_ID'],
                    $projectSetupValues['HEAD_WORK_BLOCK_ID']
                );
                $data['TEHSIL_HEAD'] = $this->getTehsilOptions(
                    $projectSetupValues['HEAD_WORK_DISTRICT_ID'],
                    $projectSetupValues['HEAD_WORK_TEHSIL_ID']
                );
                $data['ASSEMBLY_CONST'] = $this->getAssemblyOptions($projectSetupValues['ASSEMBLY_CONST_ID']);
                if($data['monthlyRecordExists']){
                    $data['SESSION_OPTIONS'] = $this->getSession($projectSetupValues['SESSION_ID']);
                }else{
                    if($projectSetupValues['SESSION_ID']==0){
                        $projectSetupValues['SESSION_ID'] = $this->session->userData('CURRENT_SESSION_ID');
                        $data['SESSION_OPTIONS'] = $this->getSession($projectSetupValues['SESSION_ID']);
                        //force setup to enter data for current session
                        $data['monthlyRecordExists'] = true;
                    }else{
                        $aaSessionId = $this->getSessionIdByDate($projectSetupValues['AA_DATE']);
                        if($aaSessionId<PMON_DEP_START_SESSION_ID) $aaSessionId=PMON_DEP_START_SESSION_ID;
                        $data['SESSION_OPTIONS'] = $this->getSessionOptions(
                            $projectSetupValues['SESSION_ID'],
                            $aaSessionId
                        );
                    }
                }
            }

            $data['DEPOSIT_SCHEME'] = $this->getDepositScheme($projectSetupValues['DEPOSIT_SCHEME_ID']);
            $data['DEPOSIT_SCHEME_DETAILS'] = $this->getDepositSchemeHead();
            $data['projectSetupValues'] = $projectSetupValues;
            /*----------------[RAA]------------------*/
            $RAA_FIELDS = array('RAA_NO', 'RAA_DATE', 'RAA_AUTHORITY_ID', 'RAA_AMOUNT', 'RAA_PROJECT_ID');
            $raaValues = array();
            //initialize
            for($i=0; $i<count($RAA_FIELDS);$i++){
                $raaValues[ $RAA_FIELDS[$i] ] = '';
            }
            $raaValues['RAA_AUTHORITY_ID'] = 0;
            if($editMode){
                $recsRAA = $this->db->get_where(
                    'dep_pmon__t_raa_project',
                    array('PROJECT_ID'=>$this->PROJECT_ID, 'ADDED_BY'=>0)
                );
                if($recsRAA && $recsRAA->num_rows()){//if found
                    $recRAA =  $recsRAA->row();
                    //showArrayValues($recRAA);
                    for($i=0; $i<count($RAA_FIELDS);$i++)
                        $raaValues[ $RAA_FIELDS[$i] ] = $recRAA->{$RAA_FIELDS[$i]};
                }
            }
            $data['RAA_AUTHORITY_ID'] = $this->getAuthority($raaValues['RAA_AUTHORITY_ID']);
            $data['raaValues'] = $raaValues;
            /*----------------[]------------------*/
            $sessionId = 0;
            if($projectSetupValues['SESSION_ID'])
                $sessionId = $projectSetupValues['SESSION_ID'];

            $recEstimation = array();
            if ($editMode){
                $sresult = $this->db->get_where('__sessions', array('SESSION_ID'=>$sessionId));
                if($sresult && $sresult->num_rows()){
                    foreach($sresult->result() as $srec){
                        $data['SESSION_START_YEAR'] = (int) $srec->SESSION_START_YEAR;
                        $data['SESSION_END_YEAR'] = (int) $srec->SESSION_END_YEAR;
                    }
                }
                $recAchieve = $this->getAchievement($sessionId-1);
                //showArrayValues($recAchieve);
                $recEstimation = $this->getEstimation($this->PROJECT_ID);
                $recEstimationStatus = $this->getEstimationStatus($this->PROJECT_ID);
                $recTargetDates = $this->getTargetDates($this->PROJECT_ID);
                $data['estimationData'] = $recEstimation;
                $data['estimationStatus'] = $recEstimationStatus;
                $data['TARGET_DATES_VALUES'] = $recTargetDates;
                //showArrayValues($recTargetDates);
                $recAchieve1 = $this->getSetupStatus();
                $data['LA_CASES_STATUS'] = $this->getWorkStatus(
                    $recAchieve1['LA_CASES_STATUS'],
                    $recEstimationStatus['LA_NA']
                );
                $data['SPILLWAY_STATUS'] = $this->getWorkStatus($recAchieve1['SPILLWAY_STATUS'], 0);
                $data['FLANK_STATUS'] = $this->getWorkStatus($recAchieve1['FLANK_STATUS'], 0);
                $data['SLUICES_STATUS'] = $this->getWorkStatus($recAchieve1['SLUICES_STATUS'], 0);
                $data['NALLA_CLOSURE_STATUS'] = $this->getWorkStatus(
                    $recAchieve1['NALLA_CLOSURE_STATUS'],
                    (($projectSetupValues['PROJECT_SUB_TYPE_ID']==6)?1:0)
                );
                $data['CANAL_EARTH_WORK_STATUS'] = $this->getWorkStatus(
                    $recAchieve1['CANAL_EARTH_WORK_STATUS'],
                    $recEstimationStatus['CANAL_EARTHWORK_NA']
                );
                $data['CANAL_STRUCTURE_STATUS'] = $this->getWorkStatus(
                    $recAchieve1['CANAL_STRUCTURE_STATUS'],
                    $recEstimationStatus['CANAL_STRUCTURES_NA']
                );
                $data['CANAL_LINING_STATUS'] = $this->getWorkStatus(
                    $recAchieve1['CANAL_LINING_STATUS'],
                    $recEstimationStatus['CANAL_LINING_NA']
                );
                $data['achievementValues'] = $recAchieve;
                $data['STATUS_VALUES'] = $recAchieve1;
            }
			//showArrayValues($data);
            if($this->session->userdata('HOLDING_PERSON')==4){
                $data['EE_ID'] = $EE_ID = $this->session->userData('CURRENT_OFFICE_ID');
                $SDO_IDs = array();
                if($editMode){
                    $recsSdoEE = $this->db->get_where('__projects_office', $arrWhere);
                    if($recsSdoEE && $recsSdoEE->num_rows()){
                        foreach($recsSdoEE->result() as $rec){
                            array_push($SDO_IDs, $rec->OFFICE_ID);
                        }
                    }
                }
                $data['sdo_options'] = $this->SDOofficeOptions($EE_ID, $SDO_IDs);
                $data['EE_NAME'] = $this->getOfficeEEname($EE_ID);
            }else if($this->session->userdata('HOLDING_PERSON')==3){
                //$data['EE_ID'] = $EE_ID = $this->session->userData('CURRENT_OFFICE_ID');
                $data['ee_options'] = $this->eeOfficeOptions($this->session->userData('CURRENT_OFFICE_ID'));
                $data['sdo_options'] = '';
            }
            if ($editMode){
                //GET BENEFITED DISTRICTS
                $data['DISTRICT_BENEFITED'] = $this->getDistrictBenefited($this->PROJECT_ID);
                $did = $this->getDistrictBenefitedIDs($this->PROJECT_ID);
                $data['BLOCKS_BENEFITED'] = $this->getBlocksBenefited($did, $this->PROJECT_ID);
                $data['ASSEMBLY_BENEFITED'] = $this->getBenefitedAssembly($this->PROJECT_ID);
                $data['VILLAGES_BENEFITED'] = $this->getBenefitedVillages($this->PROJECT_ID, $did);
                //blockwise iP
                $arrBlockIds = $this->getBlockIds($this->PROJECT_ID);

                $arrBlockIps = $this->getEstimationBlockIP($this->PROJECT_ID, $recEstimation['ESTIMATED_QTY_ID']);
                $arrBlockAIps = $this->getAchievementBlockIP($this->PROJECT_ID, $sessionId-1);
                //showArrayValues($arrBlockIps);
                //showArrayValues($arrBlockAIps);
                foreach($arrBlockIds as $arrBlockId){
                    if(array_key_exists($arrBlockId, $arrBlockAIps))
                    $arrBlockIps[$arrBlockId]['ACHIEVEMENT_IP'] = $arrBlockAIps[$arrBlockId]['ACHIEVEMENT_IP'];
                    else
                    $arrBlockIps[$arrBlockId]['ACHIEVEMENT_IP'] = 0;
                }
                $data['BLOCK_IP_DATA'] = $arrBlockIps;
            }
            $data['buttons'] = $this->createButtonSet();
            $data['PROJECT_TYPE_ID'] = $this->session->userData('SETUP_PROJECT_TYPE_ID');
            $myview = $this->load->view('pmon_deposit/project_data_view', $data, true);
            array_push($this->message, getMyArray(null, $myview));
            echo createJSONResponse( $this->message );
		}
	}
	public function getSDOOffices(){
		$eeid = $this->input->post('eeid');
		echo $this->SDOofficeOptions($eeid);
	}
	private function isMonthlyExists(){
		$this->db->select('MONTHLY_DATA_ID');
		$recs = $this->db->get_where('dep_pmon__t_monthlydata', array('PROJECT_ID'=>$this->PROJECT_ID));
		return ($recs && $recs->num_rows());
	}
	protected function getSetupStatus(){
		$mFields = $this->getFields('dep_pmon__m_setup_status');
		$recs =  $this->db->get_where('dep_pmon__m_setup_status', array('PROJECT_ID'=>$this->PROJECT_ID));
		$data = array();
		$isExists = false;
		if($recs && $recs->num_rows()){
			$isExists = true;
			$rec = $recs->row();
			for($i=0; $i<count($mFields); $i++){
				$data[$mFields[$i]] = $rec->{$mFields[$i]};
			}
		}//for
		if(!$isExists){
			for($i=0; $i<count($mFields); $i++){
				$data[$mFields[$i]] = 0;
			}
		}
		return $data;
	}
	protected function getAchievement($SESSION_ID){
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
			' FROM dep_pmon__t_achievements 
				WHERE PROJECT_ID = '.$this->PROJECT_ID.'
				AND SESSION_ID='.$SESSION_ID;
		//echo $strSQL;
		return $this->getKeyValues($mFields, $strSQL);
	}
	private function eeOfficeOptions($seId, $sel=array()){
		$opt = array();
		array_push($opt, '<option value="">Select Division</option>');
		$this->db->select('OFFICE_ID, OFFICE_NAME, OFFICE_NAME_HINDI');
		$this->db->where('HOLDING_PERSON', 4)->where('PARENT_OFFICE_ID', $seId);
		$this->db->order_by('OFFICE_NAME', 'ASC');
		$recs = $this->db->get('__offices');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push(
					$opt, 
					'<option value="'.$rec->OFFICE_ID.'" '.
					( (in_array($rec->OFFICE_ID, $sel)) ? 'selected="selected"':'').' >'.
					$rec->OFFICE_NAME.'-'.$rec->OFFICE_NAME_HINDI.
					'</option>'
				);
			}
		}
		//$this->db->last_query();
		return implode('', $opt);
	}
	private function SDOofficeOptions($eeId, $sel=array()){
		$opt = array();
		$this->db->select('OFFICE_ID, OFFICE_NAME, OFFICE_NAME_HINDI');
		$this->db->where('HOLDING_PERSON', 5)->where('PARENT_OFFICE_ID', $eeId);
		$this->db->order_by('OFFICE_NAME', 'ASC');
		$recs = $this->db->get('__offices');
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push(
					$opt, 
					'<option value="'.$rec->OFFICE_ID.'" '.
					( (in_array($rec->OFFICE_ID, $sel)) ? 'selected="selected"':'').' >'.
					$rec->OFFICE_NAME.'-'.$rec->OFFICE_NAME_HINDI.
					'</option>'
				);
			}
		}
		return implode('', $opt);
	}
	public function getBlockBenefitedList(){
		$dist_id = $this->input->post('dist_id');
        $block_id = $this->input->post('block_id');
		if( !is_array($dist_id)) 
			$dist_id = array($dist_id); 
		//list with select existing block otherwise only new selection will be saved
		$arecs = $this->db->get_where(
			'__projects_block_served', 
			array('PROJECT_ID'=>$this->input->post('project_id'))
		);
		$bid = array();
		if($arecs && $arecs->num_rows()){
			foreach($arecs->result() as $rec){
				array_push($bid, $rec->BLOCK_ID);
			}
		}
		//echo $this->getBlockOptions($dist_id, $bid);
		echo $this->getBlockOptions($dist_id, $block_id);
	}
	public function getVillagesByDistrict(){
		$DISTRICT_ID = $this->input->post('DISTRICT_ID');
		if( !is_array($DISTRICT_ID) ) $DISTRICT_ID = array($DISTRICT_ID);
		echo $this->getVillagesByDistrictList($DISTRICT_ID);
	}
	/**-----------------------------------------------*/
	//SAVING UPDATING
	public function saveProjectSetup(){
		$this->PROJECT_ID = (int) trim($this->input->post('PROJECT_ID'));
		$editMode = (($this->PROJECT_ID) ? true:false);
		if($editMode){
			//0-setup lock
			if($this->getLockStatus(0)){
				array_push(
					$this->message, 
					getMyArray(
						false, 
						'<button class="btn-large btn-danger" onclick="closeDialog();">
						Project Locked...</button>'
					)
				);
				echo createJSONResponse( $this->message );
				return;
			}
		}
		//saveMode	0-save & edit	1-save & close	2-modify(save)
		$saveMode = $this->input->post('saveMode');
		/**Transaction starts here*/
		$this->db->trans_begin();
		/** __projects */
		// if Sub Category belong to 5(Tubewell) or 25(Micro Irrigation) Call All function and controllers of MI (stored in same location not from MI module)		
		$arr['PROJECT_SETUP_ID']= 0;
		$arr['PARENT_PROJECT_ID']=0;	
		$PROJECT_SUB_TYPE_ID=0;   
		$mi_pmon_type=$this->input->post('mi_pmon_type');
		if($mi_pmon_type==1){
			require('mi/Project_mi_c.php');
			$Project_mi_c = new Project_mi_c();
			$arr1=$Project_mi_c->saveProjectSetup($this->PROJECT_ID);
			$arr['PROJECT_SETUP_ID']= $arr1['PROJECT_SETUP_ID'];
			$arr['PARENT_PROJECT_ID']=$arr1['PARENT_PROJECT_ID'];
		}else {
            $this->saveProjectMasterData();
            /**Saving Project Setup Data dep_pmon__m_project_setup */
            $this->saveProjectSetupData();
            /**    Saving Project Office Data*/
            $recs = $this->processDetailRecord('OFFICE');
            $this->processData('OFFICE', $recs, $finalShow);
            /**    Saving RAA Project Setup */
            $this->RAA_ID = 0;
            if (($this->input->post('isRAA') == 1) && ($this->input->post('RAA_NO') != '')) {
                $this->saveRAAData();
            } else {
                //1. if RAA Exists then remove it
                //2. reset RAA ID to zero
                $arrSearch = array('PROJECT_ID' => $this->PROJECT_ID, 'ADDED_BY' => 0);
                //if raa record
                $recs = $this->db->get_where('dep_pmon__t_raa_project', $arrSearch);
                if ($recs && $recs->num_rows()) {
                    $rec = $recs->row();
                    $raaID = $rec->RAA_PROJECT_ID;
                    //delete raa record
                    $this->db->delete('dep_pmon__t_raa_project', $arrSearch);
                    if ($this->db->affected_rows()) {
                        //remove raa id from estimation
                        $arrSearch = array('PROJECT_ID' => $this->PROJECT_ID, 'RAA_ID' => $raaID);
                        $data = array('RAA_ID' => 0);
                        @$this->db->update('dep_pmon__t_estimated_qty', $data, $arrSearch);
                    }
                }
                $this->RAA_ID = 0;
            }
            if ($editMode) {
                //SAVE NA Data
                $this->saveEstimationStatus();
                /////////////////////////
                $ipNA = $this->input->post('IRRIGATION_POTENTIAL_NA');
                $blockBenefited = $this->input->post('BLOCKS_BENEFITED');
                if (!is_array($blockBenefited)) $blockBenefited = array($blockBenefited);
                $arrB = array('BLOCK_ID' => 0, 'KHARIF' => 0, 'RABI' => 0, 'TOTAL' => 0);
                $arrBlockEstiDatas = array();
                $arrBlockAchiDatas = array();
                if (!$ipNA) {
                    $arrEstiKharif = $this->input->post('BLOCK_EIP_K');
                    $arrEstiRabi = $this->input->post('BLOCK_EIP_R');

                    $arrAchiKharif = $this->input->post('BLOCK_AIP_K');
                    $arrAchiRabi = $this->input->post('BLOCK_AIP_R');
                    $saveAchive = $arrAchiKharif;
                    foreach ($blockBenefited as $blockid) {
                        $arrBlockEData = $arrB;
                        $arrBlockEData['BLOCK_ID'] = $blockid;
                        $arrBlockEData['KHARIF'] = $arrEstiKharif[$blockid];
                        $arrBlockEData['RABI'] = $arrEstiRabi[$blockid];
                        $arrBlockEData['TOTAL'] = $arrBlockEData['KHARIF'] + $arrBlockEData['RABI'];
                        array_push($arrBlockEstiDatas, $arrBlockEData);
                        if ($saveAchive) {
                            $arrBlockAData = $arrB;
                            $arrBlockAData['BLOCK_ID'] = $blockid;
                            if (array_key_exists($blockid, $arrAchiKharif))
                                $arrBlockAData['KHARIF'] = $arrAchiKharif[$blockid];
                            if (array_key_exists($blockid, $arrAchiRabi))
                                $arrBlockAData['RABI'] = $arrAchiRabi[$blockid];
                            $arrBlockAData['TOTAL'] = $arrBlockAData['KHARIF'] + $arrBlockAData['RABI'];
                            array_push($arrBlockAchiDatas, $arrBlockAData);
                        }
                    }
                }

                $this->load->library('MyIrrigationLedger');
                if ($this->RAA_ID > 0) {
                    $arrDesignedLedger = array(
                        'BLOCK_IDS' => $blockBenefited,
                        'KHARIF_DATA' => $arrEstiKharif,
                        //'RABI_DATA' => $arrEstiBlockData['BLOCK_EIP_R'],
                        'RABI_DATA' => $arrEstiRabi,
                        //'PROJECT_SETUP_ID' => $this->PROJECT_ID,
                        'PROJECT_ID' => $this->PROJECT_ID,
                        'MONTH_DATE' => myDateFormat($this->input->post('RAA_DATE')),
                        'PROJECT_MODE' => 'DEP_PMON',
                        'ENTRY_MODE' => 1,
                        'RAA_ID' => $this->RAA_ID
                    );
                    $this->myirrigationledger->setDesigned($arrDesignedLedger);
                } else {
                    $arrDesignedLedger = array(
                        'BLOCK_IDS' => $blockBenefited,
                        'KHARIF_DATA' => $arrEstiKharif,
                        'RABI_DATA' => $arrEstiRabi,
                        //'PROJECT_SETUP_ID' => $this->PROJECT_ID,
                        'PROJECT_ID' => $this->PROJECT_ID,
                        'MONTH_DATE' => myDateFormat($this->input->post('AA_DATE')),
                        'PROJECT_MODE' => 'DEP_PMON',
                        'ENTRY_MODE' => 1,
                        'RAA_ID' => 0
                    );
                    $this->myirrigationledger->setDesigned($arrDesignedLedger);
                }

                //////////////////////////
                //SAVE Project Estimeted Quantity Data
                $this->saveEstimation($arrBlockEstiDatas, $ipNA);
                //get project start date session
                $sDate = myDateFormat($this->input->post('PROJECT_START_DATE'));
                $projectStartSessionId = $this->getSessionIdByDate($sDate);
                $sessionId = $this->input->post('SESSION_ID');

                if ($projectStartSessionId == $sessionId) {
                    //if both equals then no need to save it
                    $this->deleteAchiementOfSession($sessionId);
                } else {
                    $this->saveAchivUptoLastFinYr($arrBlockAchiDatas);
                }
                $this->saveSetupAchivStatus();
                $this->saveAchievementTargetDates();
                $finalShow = 1;
                $this->saveBenifitedRecords($finalShow);
                $this->saveIrrigationPotential();
                $this->cleanupTargetAndMonthlyData($sessionId);
                if ($projectStartSessionId < $sessionId) {
                    $this->setInitialProgress($this->PROJECT_ID);
                }
            }
        }
		if ($this->db->trans_status()===FALSE){
	    	// generate an error... or use the log_message() function to log your error
			array_push($this->message, getMyArray(false, $this->db->log_message()));
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
		}
		//save & show edit mode data
		if($saveMode==0){
		    if($mi_pmon_type==1){
                  $Project_mi_c->showProjectSetupData($arr['PROJECT_SETUP_ID']);
            }else
                $this->showProjectSetupData();
		}else
			echo createJSONResponse( $this->message );
	}
	private function cleanupTargetAndMonthlyData($sessionId){
		$where = ' WHERE PROJECT_ID='.$this->PROJECT_ID .
			' AND SESSION_ID<'. $sessionId;
		$this->db->query('DELETE FROM dep_pmon__t_yearlytargets '.$where);
		$this->db->query('DELETE FROM dep_pmon__t_monthlydata '.$where);
		$this->db->query('DELETE FROM dep_pmon__t_progress '.$where);
	}
	public function checkProjectCode(){
		$this->load->library('Check_latlong');
		$myDataForCode = array();
		//$projCode = array('', 'MI', 'ME', 'MJ');
	//	$myDataForCode['PROJECT_TYPE'] = $projCode[ $this->session->userData('SETUP_PROJECT_TYPE_ID')];
		$myDataForCode['DISTRICT_ID'] = $this->input->post('DISTRICT_ID');
		$myDataForCode['LATITUDE_D'] = $this->input->post('LATITUDE_D');
		$myDataForCode['LATITUDE_M'] = $this->input->post('LATITUDE_M');
		$myDataForCode['LATITUDE_S'] = $this->input->post('LATITUDE_S');
		$myDataForCode['LONGITUDE_D'] = $this->input->post('LONGITUDE_D');
		$myDataForCode['LONGITUDE_M'] = $this->input->post('LONGITUDE_M');
		$myDataForCode['LONGITUDE_S'] = $this->input->post('LONGITUDE_S');
		$PROJECT_SETUP_ID = $this->input->post('PROJECT_SETUP_ID');//PROJECT_ID
		$PROJECT_ID=$this->input->post('PROJECT_ID');//PROJECT_ID
			$arrResponse = array("success"=>1, "message"=>"");
		if($PROJECT_ID==null && $PROJECT_SETUP_ID==null) 
		{
			$iCount=$this->check_latlong->check($myDataForCode);
			if( ($iCount==1)){
					$arrResponse["message"] =  'LATITUDE/LONGITUDE already in use ';
$arrResponse["success"]=0;
				}
		}
 
		echo json_encode($arrResponse);
	}
	private function saveProjectMasterData(){
		//get project master fields
		$fieldNames = array(
			'PROJECT_CODE','PROJECT_TYPE_ID','PROJECT_SUB_TYPE_ID','LONGITUDE_D', 'LONGITUDE_M', 'LONGITUDE_S','LATITUDE_D', 'LATITUDE_M', 'LATITUDE_S','DISTRICT_ID',
			'PROJECT_NAME', 'PROJECT_NAME_HINDI', 'NO_VILLAGES_COVERED',			
			'PROJECT_START_YEAR', 'PROJECT_START_MONTH',
			'DESIGNED_IRRIGATION', 'PROJECT_STATUS',
			'CE_ID', 'LIVE_STORAGE', 'TEHSIL_ID', 'BLOCK_ID', 'ASSEMBLY_CONST_ID'
		);
		$data = array();
		//save project master data
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		$editMode = ( ($this->PROJECT_ID!=0) ? true:false);
		$oneTimeFields = array('CE_ID','PROJECT_TYPE_ID','PROJECT_SUB_TYPE_ID','LONGITUDE_D', 'LONGITUDE_M', 'LONGITUDE_S','LATITUDE_D', 'LATITUDE_M', 'LATITUDE_S','DISTRICT_ID','DEPOSIT_SCHEME_ID');
		$startDate = myDateFormat( $this->input->post('AA_DATE') );
		for($i=1; $i<count($fieldNames); $i++){
			if($editMode && in_array($fieldNames[$i], $oneTimeFields)){
				continue;//skip fields
			}else if( (!$editMode) && $fieldNames[$i]=='CE_ID' ){
				$data['CE_ID'] = $this->session->userData('CE_ID');
			}else if($fieldNames[$i]=="PROJECT_START_YEAR"){
				$data[ $fieldNames[$i]] = date("Y", strtotime($startDate));
			}else if($fieldNames[$i]=="PROJECT_START_MONTH"){
				$data[ $fieldNames[$i] ] = date("m", strtotime($startDate));
			}else{
				if($this->input->post($fieldNames[$i])){
					$data[ $fieldNames[$i] ] = trim( $this->input->post($fieldNames[$i]) );
				}else{
					$data[ $fieldNames[$i] ] = '';
				}
			}
		}
		$data['DISTRICT_ID'] =  $this->input->post('HEAD_WORK_DISTRICT_ID');
		if($this->input->post('HEAD_WORK_TEHSIL_ID'))
			$data['TEHSIL_ID'] = $this->input->post('HEAD_WORK_TEHSIL_ID');
		else
			$data['TEHSIL_ID'] = 0;
		if($this->input->post('HEAD_WORK_BLOCK_ID'))
			$data['BLOCK_ID'] = $this->input->post('HEAD_WORK_BLOCK_ID');
		else
			$data['BLOCK_ID'] = 0;
		$myDataForCode = array();
		$projCode = array('', 'PD');//array('', 'PD', 'ME', 'MJ');
		if(!$editMode){
				$myDataForCode['PROJECT_TYPE'] = $projCode[ $this->session->userData('SETUP_PROJECT_TYPE_ID')];
		$myDataForCode['DISTRICT_ID'] = $this->input->post('HEAD_WORK_DISTRICT_ID');
		$myDataForCode['LATITUDE_D'] = $this->input->post('LATITUDE_D');
		$myDataForCode['LATITUDE_M'] = $this->input->post('LATITUDE_M');
		$myDataForCode['LATITUDE_S'] = $this->input->post('LATITUDE_S');
		$myDataForCode['LONGITUDE_D'] = $this->input->post('LONGITUDE_D');
		$myDataForCode['LONGITUDE_M'] = $this->input->post('LONGITUDE_M');
		$myDataForCode['LONGITUDE_S'] = $this->input->post('LONGITUDE_S');
		$data['PROJECT_CODE'] = getProjectCode($myDataForCode);
		}
	//showArrayValues($data);exit;
		if($editMode){
			@$this->db->update('__projects', $data, $arrWhich);
		}else{
			$data['ENTRY_FROM'] = 6;
			@$this->db->insert('__projects', $data);
			$this->PROJECT_ID = $this->db->insert_id();
		}
		if ($this->db->affected_rows()){
			$this->RESULT= true;
			if($editMode){
				array_push($this->message, getMyArray(true, 'Project Master Data Updated...'));
			}else{
				array_push($this->message, getMyArray(true, 'Project Master Data Created...'));
				//create lock record for project
				$strSQL = "INSERT INTO projects__t_locks (PROJECT_ID, SETUP_LOCK) 
						VALUES(".$this->PROJECT_ID.',0)';
				$this->db->query($strSQL);
			}
		}else{
			array_push($this->message, getMyArray(false, 'No Updatable or Unable to update Project Master Data...'));
		}
	}
	private function saveProjectSetupData(){
		if ($this->PROJECT_ID==0) return;
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		$tableName = 'dep_pmon__m_project_setup';
		$recs = $this->db->get_where($tableName, $arrWhich);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$this->PROJECT_SETUP_ID = $rec->PROJECT_SETUP_ID;
		}
		$fieldNames = $this->getFields($tableName);
		$data = array();
		$arrExclude = array('PROJECT_SETUP_ID', 'OFFICE_EE_ID', 'OFFICE_SDO_ID');
		for($i=0; $i<count($fieldNames); $i++){
			if( in_array($fieldNames[$i], $arrExclude))
				continue;
			else if($fieldNames[$i]=='PROJECT_ID')
				$data[ $fieldNames[$i] ] = $this->PROJECT_ID;
			else if($fieldNames[$i]=='AA_DATE')
				$data[ $fieldNames[$i] ] = myDateFormat($this->input->post('AA_DATE'));
			else if($fieldNames[$i]=='PROJECT_START_DATE')
				$data[ $fieldNames[$i] ] = myDateFormat($this->input->post('PROJECT_START_DATE'));
			else if($fieldNames[$i]=='PROJECT_COMPLETION_DATE')
				$data[ $fieldNames[$i] ] = myDateFormat($this->input->post('DATE_COMPLETION'));
			else if($fieldNames[$i]=='PROJECT_SAVE_DATE')
				$data[ $fieldNames[$i] ] = date("Y-m-d");// myDateFormat($this->input->post('PROJECT_SAVE_DATE'));
			else if($fieldNames[$i]=='NO_VILLAGES_BENEFITED')
				$data[ $fieldNames[$i] ] = $this->input->post('NO_VILLAGES_COVERED');
			else{
				##CHECKING FOR PROJECT STATUS SAVE IF ( Save & Lock will press it will be [1] otherwise [0] )
				if($fieldNames[$i]=='OFFICE_CE_ID')
					$data[ $fieldNames[$i] ] = $this->session->userData('CE_ID');
				else{
					//$data[ $fieldNames[$i] ] = $this->input->post($fieldNames[$i]);
					if($this->input->post($fieldNames[$i]) )
						$data[ $fieldNames[$i] ] = $this->input->post($fieldNames[$i]);
					else
						$data[ $fieldNames[$i] ] = '';
				}
			}
		}##end loop
		//major project
		/*if($this->session->userData('SETUP_PROJECT_TYPE_ID')==3){
			$aadata = $this->getAAData($this->input->post('MAJOR_PROJECT_ID'));
			$data['AA_AUTHORITY_ID'] = $aadata['AA_AUTHORITY_ID'];
			//$data['RAA_AUTHORITY_ID'] = $aadata['RAA_AUTHORITY_ID'];
		}*/
		$editMode = false;
		if($this->PROJECT_SETUP_ID){
			@$this->db->update($tableName, $data, $arrWhich);
			$editMode = true;
		}else
			@$this->db->insert($tableName, $data);

		if ($this->db->affected_rows()){
			$this->RESULT = true;
			if($editMode){
				array_push($this->message, getMyArray(true, 'Project Setup Updated...'));
			}else{
				//inserted project setup id
				$this->PROJECT_SETUP_ID = $this->db->insert_id();
				array_push($this->message, getMyArray(true, 'Project Created...'));
				//create lock record for promon project
				$strSQL = "INSERT INTO dep_pmon__t_locks (PROJECT_ID, SETUP_LOCK, TARGET_LOCK) 
					VALUES(".$this->PROJECT_ID.',0,0)';
				$this->db->query($strSQL);
			}
		}else{
			array_push($this->message, getMyArray(false, 'No updatable Data for Project Setup...'));
		}
		//if major
	/*	if($this->session->userData('SETUP_PROJECT_TYPE_ID')==3){
			if($this->PROJECT_ID){
				$arrWhich = array(
					'PROJECT_ID'=>$this->PROJECT_ID,
					'MAJOR_PROJECT_ID'=> $this->input->post('MAJOR_PROJECT_ID')
				);
				$recs = $this->db->get_where('dep_pmon__m_major_children', $arrWhich);
				$isExists = (($recs && $recs->num_rows()) ? true : false);
				if(!$isExists)
					@$this->db->insert('dep_pmon__m_major_children', $arrWhich);
			}
		}*/
	}
	private function saveRAAData(){				 
		//$this->PROJECT_ID = $this->input->post('PROJECT_ID');
		$this->RAA_ID = $this->input->post('RAA_PROJECT_ID');
		//get sessionid from RAA Date
		$raaDate = myDateFormat($this->input->post('RAA_DATE'));
		$sessionId = $this->getSessionIdByDate($raaDate);
		$raaFieldNames = $this->getRAAFields();
		$arrRAA = array();
		$goAhead = false;
		for($i=0; $i<count($raaFieldNames); $i++){
			//skip first field RAA_ID
			if($raaFieldNames[$i] == 'RAA_PROJECT_ID'){
				continue;
			}else if($raaFieldNames[$i] == 'PROJECT_ID'){
				$arrRAA[$raaFieldNames[$i]] = $this->PROJECT_ID;
			}else if($raaFieldNames[$i] == 'RAA_DATE')
				$arrRAA['RAA_DATE'] = $raaDate;
			else{
				// RAA Save status will be change according to Save and Lock status 0 for Save 1 for Lock
				if($raaFieldNames[$i] == 'RAA_SAVE_STATUS')
					$arrRAA['RAA_SAVE_STATUS'] = 0;
				else if ($raaFieldNames[$i] == 'RAA_SAVE_DATE')
					$arrRAA['RAA_SAVE_DATE'] = date('Y-m-d');
				else if ($raaFieldNames[$i] == 'ADDED_BY')
					$arrRAA['ADDED_BY'] = 0;//Added through project setup module
				else{
					$arrRAA[$raaFieldNames[$i]] = $this->input->post($raaFieldNames[$i]);
				}
			}
		}// End For loop
		$raaTableName = 'dep_pmon__t_raa_project';
		//check RAA record exists for that Project Setup
		$isExists = false;
		if( ($this->PROJECT_ID>0) && ($this->RAA_ID>0)){
			//again check
			$arrSearch = array('PROJECT_ID'=>$this->PROJECT_ID, 'ADDED_BY'=>0);
			//if exists then update it
			if($this->isRecordExists($raaTableName, $arrSearch)){
				//get the real RAA ID
				$recs = $this->db->get_where($raaTableName, $arrSearch);
				if($recs && $recs->num_rows()){
					$isExists = true;
					$rec = $recs->row();
					if($this->RAA_ID!=$rec->RAA_PROJECT_ID)
						$this->RAA_ID = $rec->RAA_PROJECT_ID;
				}
			}
		}
		$goAhead = false;
		if($isExists){
			//update data
			@$this->db->update($raaTableName, $arrRAA, $arrSearch);
			if($this->db->affected_rows()){
				$goAhead = true;
				array_push($this->message, getMyArray(true, 'RAA Record Updated...'));
			}else{
				$RAA_ID = 0;
				$goAhead = true;
			} // End if RAA
		}else{
			//insert record
			$arrRAA['IS_RAA'] = 1;
			@$this->db->insert($raaTableName, $arrRAA);
			if($this->db->affected_rows()){
				$this->RAA_ID = $this->db->insert_id();
				$goAhead = true;
				array_push($this->message, getMyArray(true, 'RAA Record Created...'));
			}else{
				array_push($this->message, getMyArray(true, 'Unable to Create RAA Record...'));
			}	
		}
	}
	private function saveEstimation($arrEstiIPs, $ipNA){
		//// [ SAVE Project Estimeted Quantity Data ] ////
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID, 'ADDED_BY'=>0);
		$isExists = $this->isRecordExists('dep_pmon__t_estimated_qty', $arrWhich);
		$eFieldNames = $this->getEstimationFields();
		$data = array();
		/* `ADDED_BY 0-through setup, 1-RAA setup */
		for($i=1; $i<count($eFieldNames); $i++){
			if($eFieldNames[$i] == 'PROJECT_ID'){
				if(!$isExists)
					$data['PROJECT_ID'] = $this->PROJECT_ID;
			}else if($eFieldNames[$i] == 'RAA_ID'){
				if($this->RAA_ID) $data['RAA_ID'] = $this->RAA_ID;
			}else
				$data[$eFieldNames[$i]] = trim( $this->input->post($eFieldNames[$i]) );
		}//for
		//set NA field data
		foreach($this->arrSetupData as $k=>$v){
			if($v==1){
				if($k=='LA_NA'){
					$data['LA_NO'] = 0;
					$data['LA_HA'] = 0;
					$data['LA_COMPLETED_NO'] = 0;
					$data['LA_COMPLETED_HA'] = 0;
				}else if($k=='FA_NA'){
					$data['FA_HA'] = 0;
					$data['FA_COMPLETED_HA'] = 0;
				}else if($k=='CANAL_STRUCTURES_NA'){
					$data['CANAL_MASONRY'] = 0;
					$data['CANAL_STRUCTURES'] = 0;
				}else if($k=='IRRIGATION_POTENTIAL_NA'){
					$data['IRRIGATION_POTENTIAL_KHARIF'] = 0;
					$data['IRRIGATION_POTENTIAL_RABI'] = 0;
					$data['IRRIGATION_POTENTIAL'] = 0;
				}else{
					$data[ str_replace('_NA', '', $k) ] = 0;
				}
			}
		}
		if($isExists){
			$this->saveMyRecords('esti', $data, $arrWhich);
		}else{
			$this->saveMyRecords('esti', $data);
		}
		//get esti record id
		$estimationId = 0;
		$this->db->select('ESTIMATED_QTY_ID');
		$recs = $this->db->get_where('dep_pmon__t_estimated_qty', $arrWhich);
		$isExists = false;
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$estimationId = $rec->ESTIMATED_QTY_ID;
		}
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
				@$this->db->delete($tableName, $arrWhere);
			}
		}else{
			//showArrayValues($arrEstiIPs);
			foreach($arrEstiIPs as $arrEstiIP){
				$arrWhich = array_merge($arrWhere, array('BLOCK_ID'=>$arrEstiIP['BLOCK_ID']));
				$data = array('KHARIF'=>$arrEstiIP['KHARIF'], 'RABI'=>$arrEstiIP['RABI'], 'IP_TOTAL'=>$arrEstiIP['TOTAL']);

				if(in_array($arrEstiIP['BLOCK_ID'], $arrBlockIds)){
					@$this->db->update($tableName, $data, $arrWhich);
				}else{
					//$data['ESTIMATION_ID'] = $estimationId;
					$data = array_merge($data, $arrWhich);
					@$this->db->insert($tableName, $data);
					//echo $this->db->last_query();
				}
			}
		}
	}
	private function saveEstimationStatus(){
		//NA Status of Components
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		$isExists = $this->isRecordExists('dep_pmon__t_estimated_status', $arrWhich);
		$mFieldNames = $this->getEstimationStatusFields();
		$data = array();
		/* `ADDED_BY 0-through setup, 1-RAA setup */
		for($i=0; $i<count($mFieldNames); $i++){
			if($mFieldNames[$i] == 'PROJECT_ID'){
				//skip
			}else if($mFieldNames[$i] == 'LA_NA')
				$data['LA_NA'] = trim( $this->input->post('LA_NA') );
			else if($mFieldNames[$i] == 'FA_NA')
				$data['FA_NA'] = trim( $this->input->post('FA_NA') );
			else
				$data[$mFieldNames[$i]] = trim( $this->input->post($mFieldNames[$i]) );
		}//for
		$this->arrSetupData = $data;
		if($isExists){
			$this->saveMyRecords('estina', $data, $arrWhich);
		}else{
			$data['PROJECT_ID'] = $this->PROJECT_ID;
			$this->saveMyRecords('estina', $data);
		}
	}
	private function deleteAchiementOfSession($sessionId){
		$strSQL = "DELETE FROM dep_pmon__t_achievements 
			WHERE SESSION_ID<".$sessionId.' AND PROJECT_ID='.$this->PROJECT_ID;
		$this->db->query($strSQL);
	}	
	private function saveAchivUptoLastFinYr($arrAchiDatas){
		$sessionId =  ( ((int)$this->input->post('SESSION_ID')) - 1);
		$achievementFieldNames = $this->getAchivementFields();
		$mStatusFields = $this->getFields('dep_pmon__m_setup_status');
		$data = array();
		for($i=1; $i<count($achievementFieldNames); $i++){
			if( !in_array($achievementFieldNames[$i], $mStatusFields)){
				if($this->input->post($achievementFieldNames[$i].'_ACHIEVE')){
					$data[$achievementFieldNames[$i]] = $this->input->post($achievementFieldNames[$i].'_ACHIEVE');
				}else{
					$data[$achievementFieldNames[$i]] = 0;//set status to NA
				}
			}
			if($achievementFieldNames[$i]=='SESSION_ID')
				$data['SESSION_ID'] = $sessionId;
			else if($achievementFieldNames[$i]=='PROJECT_ID'){
				//	
			}else if($achievementFieldNames[$i]=='SUBMISSION_DATE'){
				//skip
			}
		}//for
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID, 'SESSION_ID'=>$sessionId);
		$isExists = $this->isRecordExists('dep_pmon__t_achievements', $arrWhich);
		//set NA field data
		$ipNa = FALSE;
		foreach($this->arrSetupData as $k=>$v){
			if($v==1){
				if($k=='LA_NA'){
					$data['LA_NO'] = 0;
					$data['LA_HA'] = 0;
					$data['LA_COMPLETED_NO'] = 0;
					$data['LA_COMPLETED_HA'] = 0;
				}else if($k=='FA_NA'){
					$data['FA_HA'] = 0;
					$data['FA_COMPLETED_HA'] = 0;
				}else if($k=='CANAL_STRUCTURES_NA'){
					$data['CANAL_MASONRY'] = 0;
					$data['CANAL_STRUCTURES'] = 0;
				}else if($k=='IRRIGATION_POTENTIAL_NA'){
					$ipNa = TRUE;
					$data['IRRIGATION_POTENTIAL_KHARIF'] = 0;
					$data['IRRIGATION_POTENTIAL_RABI'] = 0;
					$data['IRRIGATION_POTENTIAL'] = 0;
				}else{
					$data[ str_replace('_NA', '', $k) ] = 0;
				}
			}
		}
		if($isExists)
			$this->saveMyRecords('achieve', $data, $arrWhich);
		else{
			$data['SUBMISSION_DATE'] = date('Y-m-d');
			$data['PROJECT_ID'] = $this->PROJECT_ID;
			$data = array_merge($data, $arrWhich);
			$this->saveMyRecords('achieve', $data);
		}
		///
		//echo $this->db->last_query().'<br />';
		//Achieved IP blockwise
		$arrBlockIds = array();
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID, 'SESSION_ID'=>$sessionId);
		$recs = $this->db->get_where('dep_pmon__t_block_achievement_ip', $arrWhere);
		$isExists = false;
		if($recs && $recs->num_rows()){
			$isExists = true;
			foreach($recs->result() as $rec){
				array_push($arrBlockIds, $rec->BLOCK_ID);
			}
		}
		if($ipNa){
			//delete existing records
			if($isExists){
				@$this->db->delete('dep_pmon__t_block_achievement_ip', $arrWhere);
			}
		}else{
			foreach($arrAchiDatas as $arrAchiData){
				$arrWhich = array_merge($arrWhere, array('BLOCK_ID'=>$arrAchiData['BLOCK_ID']));
				$data = array('KHARIF'=>$arrAchiData['KHARIF'], 'RABI'=>$arrAchiData['RABI'], 'IP_TOTAL'=>$arrAchiData['TOTAL']);
				if(in_array($arrAchiData['BLOCK_ID'], $arrBlockIds)){
					@$this->db->update('dep_pmon__t_block_achievement_ip', $data, $arrWhich);
					//echo $this->db->last_query().'<br />';
				}else{
					//echo '5s';
					$data = array_merge($data, $arrWhich);
					@$this->db->insert('dep_pmon__t_block_achievement_ip', $data);					
				}
				//echo $this->db->last_query().'<br />';
			}
		}
	}
	private function saveSetupAchivStatus(){
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		$isExists = $this->isRecordExists('dep_pmon__m_setup_status', $arrWhich);
		
		$mFields = $this->getFields('dep_pmon__m_setup_status');
		$data = array();
		for($i=0; $i<count($mFields); $i++){
			if($mFields[$i]=='PROJECT_ID'){
				if(!$isExists)
					$data['PROJECT_ID'] = $this->PROJECT_ID;
			}else{
				if($this->input->post($mFields[$i].'_ACHIEVE')){
					$data[$mFields[$i]] = $this->input->post($mFields[$i].'_ACHIEVE');
				}else{
					$data[$mFields[$i]] = 1;//set status to NA
				}
			}
		}//for
		//showArrayValues($data);
		if($isExists)
			$this->saveMyRecords('setupstatus', $data, $arrWhich);
		else
			$this->saveMyRecords('setupstatus', $data);
	}
	private function saveAchievementTargetDates(){		
		$myAFields = array(
			'LA_CASES_STATUS_ACHIEVE', 'SPILLWAY_STATUS_ACHIEVE',
			'FLANK_STATUS_ACHIEVE', 'SLUICES_STATUS_ACHIEVE',
			'NALLA_CLOSURE_STATUS_ACHIEVE', 'CANAL_EARTH_WORK_STATUS_ACHIEVE', 
			'CANAL_STRUCTURE_STATUS_ACHIEVE', 'CANAL_LINING_STATUS_ACHIEVE'
		);
		$myADateFields = array(
			'LA_TARGET_DATE', 'SPILLWAY_TARGET_DATE', 
			'FLANKS_TARGET_DATE', 'SLUICES_TARGET_DATE', 
			'NALLA_CLOSURE_TARGET_DATE', 'CANAL_EARTHWORK_TARGET_DATE', 
			'CANAL_STRUCTURES_TARGET_DATE', 'CANAL_LINING_TARGET_DATE'
		);
		$mAC_Status = array();
		for($i=0; $i<count($myAFields); $i++){
			$mAC_Status[$myAFields[$i]] = $this->input->post($myAFields[$i]);
		}
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		$isExists = $this->isRecordExists('dep_pmon__t_target_date_completion', $arrWhich);
		$arrTDate = array(
			'SESSION_ID' => $this->input->post('SESSION_ID'),
			'TARGET_SUBMISSION_DATE' => date('Y-m-d')
		);
		for($j=0; $j<count($myAFields); $j++){
			$arrTDate[$myADateFields[$j]] = ($mAC_Status[$myAFields[$j]]==0 || $mAC_Status[$myAFields[$j]]==1) ?
				'0000-00-00' : myDateFormat($this->input->post($myADateFields[$j]));
		}//for
		if($isExists){
			$this->saveMyRecords('targetdate', $arrTDate, $arrWhich);
		}else{
			$arrTDate['PROJECT_ID']= $this->PROJECT_ID;
			$this->saveMyRecords('targetdate', $arrTDate);
		}
	}
	private function saveBenifitedRecords(&$finalShow=0){
		$recs = $this->processDetailRecord('DISTRICT');
		$this->processData('DISTRICT', $recs, $finalShow);
		$recs = $this->processDetailRecord('BLOCKS');
		$this->processData('BLOCKS', $recs, $finalShow);
		$recs = $this->processDetailRecord('ASSEMBLY');
		$this->processData('ASSEMBLY', $recs, $finalShow);
		$recs = $this->processDetailRecord('VILLAGE');
		$this->processData('VILLAGE', $recs, $finalShow);
	}
	private function saveIrrigationPotential(){
		//1.Designed Irrigation Potential
		//2.Irrigation Potential Created
		$data = array(
			'PROJECT_ID'=>$this->PROJECT_ID,
			'BENEFITS_DESIGNED_IRRIGATION'=> 0, 
			'BENEFITS_IRRIGATION_POTENTIAL_CREATED'=>0
		);
		//1.Designed Irrigation Potential -
		//get all estimated IR
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		//$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		$this->db->order_by('ESTIMATED_QTY_ID', 'DESC');
		$this->db->limit(1, 0);
		$recs = $this->db->get_where('dep_pmon__t_estimated_qty', $arrWhich);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$data['BENEFITS_DESIGNED_IRRIGATION'] = $rec->IRRIGATION_POTENTIAL;
			$data['BENEFITS_DESIGNED_IRRIGATION_KHARIF'] = $rec->IRRIGATION_POTENTIAL_KHARIF;
			$data['BENEFITS_DESIGNED_IRRIGATION_RABI'] = $rec->IRRIGATION_POTENTIAL_RABI;
			$data['DESIGNED_IRRIGATION_POTENTIAL'] = $rec->IRRIGATION_POTENTIAL;
			$data['DESIGNED_IRRIGATION_POTENTIAL_KHARIF'] = $rec->IRRIGATION_POTENTIAL_KHARIF;
			$data['DESIGNED_IRRIGATION_POTENTIAL_RABI'] = $rec->IRRIGATION_POTENTIAL_RABI;
		}
		//achievement i.e., created data
		$dataa = array('DI_KARIF_CROP'=>0,'DI_RABI_CROP'=>0,'DI_TOTAL'=>0);
		//get achievement 
		//if monthly exists then 
		$recs = $this->db->get_where('dep_pmon__t_achievements', $arrWhich);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$dataa['DI_KARIF_CROP'] = $rec->IRRIGATION_POTENTIAL_KHARIF;
			$dataa['DI_RABI_CROP'] = $rec->IRRIGATION_POTENTIAL_RABI;
			$dataa['DI_TOTAL'] = $rec->IRRIGATION_POTENTIAL;
		}
      	$recs = $this->db->get_where('projects__agriculture', $arrWhich);
		if($recs && $recs->num_rows()){
			@$this->db->update('projects__agriculture', $dataa, $arrWhich);	
		}else{
          	$data['PROJECT_ID'] = $this->PROJECT_ID;
          	@$this->db->insert('projects__agriculture', $dataa);	
        }
		//save general data
		$data['CREATED_IRRIGATION_POTENTIAL'] = $dataa['DI_TOTAL'];
		$data['CREATED_IRRIGATION_POTENTIAL_KHARIF'] = $dataa['DI_KARIF_CROP'];
		$data['CREATED_IRRIGATION_POTENTIAL_RABI'] = $dataa['DI_RABI_CROP'];
		$recs = $this->db->get_where('projects__general_data', $arrWhich);
		if($recs && $recs->num_rows()){
			@$this->db->update('projects__general_data', $data, $arrWhich);
		}else{
          	$data['PROJECT_ID'] = $this->PROJECT_ID;
          	@$this->db->insert('projects__general_data', $data);
        }
	}
	private function saveMyRecords($mode, $data, $arrWhich=NULL){
		$tableNames = array(
			'achieve'=>'dep_pmon__t_achievements',
			'targetdate'=>'dep_pmon__t_target_date_completion',
			'esti'=>'dep_pmon__t_estimated_qty',
			'raalock'=>'dep_pmon__t_locks',
			'setupstatus'=>'dep_pmon__m_setup_status',
			'estina'=>'dep_pmon__t_estimated_status'
		);
		$modeMessage = array(
			'achieve'=>'Achievement',
			'targetdate'=>'Target Completion Date',
			'esti'=>'Estimation',
			'raalock'=>' RAA Lock ',
			'setupstatus'=>' Setup Status  ',
			'estina'=>' E Status '
		);
		$recExists = FALSE;
		if($arrWhich!=NULL){
			$recExists = $this->isRecordExists($tableNames[$mode], $arrWhich);
			if($recExists){
				@$this->db->update($tableNames[$mode], $data, $arrWhich);
			}
		}else{
			@$this->db->insert($tableNames[$mode], $data);
		}
		//result time
		if($this->db->affected_rows()){
			$this->RESULT = true;
			if($recExists)
				array_push(
					$this->message, 
					getMyArray(true, $modeMessage[$mode].' Record Updated...')
				);
			else
				array_push(
					$this->message, 
					getMyArray(true, 'Project '.$modeMessage[$mode].' Record Created...')
				);
		}else{
			$this->RESULT = FALSE;
			array_push(
				$this->message, 
				getMyArray(false, 'Unable to Create/Update Project '.$modeMessage[$mode].' Record...')
			);
		}
	}
	private function isRecordExists($tableName, $arrWhich){
		$recs = $this->db->get_where($tableName, $arrWhich);
		return (($recs && $recs->num_rows()) ? true:false);
	}
	//DELETING DATA
	public function deleteProject(){
		$this->PROJECT_ID = (int) $this->input->post('PROJECT_ID');
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		$recs = $this->db->get_where('dep_pmon__t_locks', $arrWhich);
		$goAhead = false;
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			if($rec->SETUP_LOCK==0)
				$goAhead = true;
		}
		if(!$goAhead){
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
			'dep_pmon__t_estimated_status',
			'dep_pmon__t_monthlydata_remarks',
			'dep_pmon__t_extensions',
			'dep_pmon__t_monthlydata',
			'dep_pmon__t_progress',
			'dep_pmon__t_yearlytargets',
			'dep_pmon__t_achievements',
			'dep_pmon__t_estimated_qty',
			'dep_pmon__t_target_date_completion',
			'dep_pmon__t_raa_project',
			'dep_pmon__m_status_date',
			'dep_pmon__m_setup_status',
			'dep_pmon__m_project_setup',
			'dep_pmon__t_locks',
			'dep_pmon__t_lock_logs'
		);
		$this->db->where('PROJECT_ID', $this->PROJECT_ID);
		$this->db->delete($projectTables);
		$countDeleted1 = $this->db->affected_rows();

		$projectTables = array(
			'__projects_office', 
			'__projects_assembly_const_served', 
			'__projects_block_served', 
			'__projects_district_served', 
			'__projects_villages_served', 
			'projects__agriculture', 
			'projects__canals', 
			'projects__canal_data', 
			'projects__dam_data', 
			'projects__facilities', 
			'projects__hydrological_data', 
			'projects__pickup_weir', 
			'projects__raingauge', 
			'projects__reservoir_data', 
			'projects__general_data', 
			'projects__spillchannel', 
			'__projects'
		);
		$this->db->where('PROJECT_ID', $this->PROJECT_ID);
		$this->db->delete($projectTables);
		$countDeleted3 = $this->db->affected_rows();
		if($this->db->trans_status()===FALSE){
			echo 'Unable to Delete Project Data...Roll Back';
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
			echo 'Project Record Deleted...';
		}
	}
	//
	private function checkProjectLockStatus(){
		$recs = $this->db->get_where('dep_pmon__t_locks', array('PROJECT_ID'=>$this->PROJECT_ID));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SETUP_LOCK;
		}
		return 0;
	}
	private function lockRAA($mode){
		$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID, 'ADDED_BY'=>$mode);
		$recs = $this->db->get_where('dep_pmon__t_raa_project', $arrWhere);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$data = array('RAA_EXISTS' => $rec->RAA_DATE);
			$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
			$this->saveMyRecords('raalock', $data, $arrWhere);
			if($this->RESULT){
				array_push($this->message, getMyArray(true, 'Project RAA Setup Locked...<span class="cus-lock"></span>'));
			}else{				
				array_push($this->message, getMyArray(false, 'Project RAA Setup Not Locked...'));
			}
		}
	}	
	//Locks
	public function lockProject(){
		/*
		if(!IS_LOCAL_SERVER){
			$this->load->library('mycurl');
			$serverStatus = $this->mycurl->getServerStatus();
			if($serverStatus==0){
				echo 'Unable to lock. E-work Server Not responding. Try after sometime...';
				return;
			}
		}
		*/
		$this->PROJECT_ID = $this->input->post('project_id');
		$projectId = $this->PROJECT_ID ;
		$status = $this->setLock();
		if($status) {
			$pData = $this->getEWorksDetails($projectId);
			$params = array(
				'mode'=>'setup',
				"projectCode"=>$projectId,
				"Ddocode"=>$pData['EWORK_ID'],
				"PromonID" =>$pData['PROJECT_CODE'],
				"lDate" =>'',
				"PLock" =>5,
				"WorkName"=>$pData['PROJECT_NAME'],
				"WorkNameHindi"=>$pData['PROJECT_NAME_HINDI'],
				"progressPerc" =>$this->getMonthlyProgress($projectId),
				"AANo"=>$pData['AA_NO'],
				"AADate"=>$pData['AA_DATE'],
				"AAAmount"=>$pData['AA_AMOUNT'],
				"RAANo"=>$pData['RAA_NO'],
				"RAADate"=>$pData['RAA_DATE'],
				"RAAAmount"=>$pData['RAA_AMOUNT'],
				"RAASerialNo"=>$pData['RAA_SNO']
			);
			//showArrayValues($params);
		/*	if(!IS_LOCAL_SERVER){
				$result = $this->mycurl->savePromonData($params);
				//echo $result;
				$obj = json_decode($result);
				if($obj->{'success'}){
					echo "Setup Data Sent to E-Works Server.";
				}
			}*/  
			$this->updateLockedStatus($params);
			$this->lockRAA(0);//RAA Lock
		}
		//if($status) $this->lockRAA(0);//RAA Lock
		echo $status;
	}
    public function lockMIProject(){
        /*require('mi/Project_mi_c.php');
        $projectMi= new Project_mi_c();
        $projectMi->lockProject();*/
        //echo 'sss'; exit;
        //$this->PROJECT_ID = $this->input->post('project_setup_id');
        $projectId  = $this->input->post('project_setup_id');
        //$projectId = $this->PROJECT_ID ;
        $status = $this->setLockMi($projectId);
        //echo 'status = '. $status;exit;
        if($status) {
            $pData = $this->getEWorksDetailsMi($projectId);
            //showArrayValues($pData); exit;
            $params = array(
                'mode'=>'setup',
                "projectCode"=>$projectId,
                "Ddocode"=>$pData['EWORK_ID'],
                "PromonID" =>$pData['PROJECT_CODE'],
                "lDate" =>'',
                "PLock" =>5,
                "WorkName"=>$pData['PROJECT_NAME'],
                "WorkNameHindi"=>$pData['PROJECT_NAME_HINDI'],
                "progressPerc" =>$this->getMonthlyProgress($projectId),
                "AANo"=>$pData['AA_NO'],
                "AADate"=>$pData['AA_DATE'],
                "AAAmount"=>$pData['AA_AMOUNT'],
                "RAANo"=>$pData['RAA_NO'],
                "RAADate"=>$pData['RAA_DATE'],
                "RAAAmount"=>$pData['RAA_AMOUNT'],
                "RAASerialNo"=>$pData['RAA_SNO']
            );
            //showArrayValues($params);
            /*	if(!IS_LOCAL_SERVER){
                    $result = $this->mycurl->savePromonData($params);
                    //echo $result;
                    $obj = json_decode($result);
                    if($obj->{'success'}){
                        echo "Setup Data Sent to E-Works Server.";
                    }
                }*/
            $this->updateLockedStatus($params);
            $this->lockRAA(0);//RAA Lock
        }
        //if($status) $this->lockRAA(0);//RAA Lock
        echo $status;
    }

    protected function setLockMi($projectId){
        //echo "in setLOCK";
        $lockTable = 'dep_mi__t_locks';
        $arrWhere = array('PROJECT_SETUP_ID' => $projectId );
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
                'PROJECT_SETUP_ID'=>$projectId,
                'LOCK_DATE_TIME'=>date("Y-m-d H:i:s"),
                'LOCK_MODE'=>1,
                'LOCK_TYPE'=>1,
                'USER_ID'=>getSessionDataByKey('USER_ID'),
                'DESCRIPTION'=>'Project Setup Locked'
            );
            @$this->db->insert('dep_mi__t_lock_logs', $data);
            return TRUE;
        }
        return FALSE;
    }

	private function getMonthlyProgress($projectId){
		$records = array();
		$rec = $this->getProjectStartDate($projectId);
		if($rec){
			if($rec->SESSION_ID==0) return 0;
			$startSessionId = $this->getSessionIdByDate($rec->PROJECT_START_DATE);
			//ready for initial progress
			if($startSessionId<$rec->SESSION_ID){
				$sessionStartDate = strtotime($this->getSessionDate($rec->SESSION_ID));
				$lastMonthDate = date("Y-m", strtotime("-1 month", $sessionStartDate)).'-01';
				$arrWhere = array(
					'PROJECT_ID'=>$rec->PROJECT_ID, 
					'PROGRESS_DATE'=>$lastMonthDate
				);
				$precs = $this->db->get_where('dep_pmon__t_progress', $arrWhere);
				if($precs && $precs->num_rows()){
					$prec = $precs->row();
					return $prec->PROGRESS;
				}
			}
		}
		return 0;
	}
	private function getEWorksDetails($projectId){
		$this->db->select('PROJECT_ID, PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, EWORK_ID, AA_NO, AA_DATE, AA_AMOUNT');
		$this->db->where('PROJECT_ID', $projectId);
		$recs = $this->db->get('dep_pmon__v_projectlist_with_lock');
		$data = array();
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$data = array(
				'EWORK_ID'=>$rec->EWORK_ID,
				'PROJECT_NAME'=>$rec->PROJECT_NAME,
				'PROJECT_NAME_HINDI'=>$rec->PROJECT_NAME_HINDI,
				'PROJECT_CODE'=>$rec->PROJECT_CODE,
				'AA_NO'=>$rec->AA_NO,
				'AA_DATE'=>$rec->AA_DATE,
				'AA_AMOUNT'=>$rec->AA_AMOUNT,
				'RAA_SNO' =>0,
				'RAA_NO' => 0,
				'RAA_DATE' =>'',
				'RAA_AMOUNT' =>0
			);
		}
		$this->db->select('RAA_NO, RAA_DATE, RAA_AMOUNT');
		$this->db->order_by('RAA_DATE', 'ASC');
		$this->db->where('PROJECT_ID', $projectId);
		$this->db->where('IS_RAA', 1);
		$recs = $this->db->get('dep_pmon__t_raa_project');
		//$data = array();
		if($recs && $recs->num_rows()){
			$i=1;
			foreach($recs->result() as $rec){
				if($i==$recs->num_rows()){
					$data['RAA_SNO'] = $i;
					$data['RAA_NO'] = $rec->RAA_NO;
					$data['RAA_DATE'] = $rec->RAA_DATE;
					$data['RAA_AMOUNT'] = $rec->RAA_AMOUNT;
				}
				$i++;
			}
		}
		return $data;
	}
    public function getEWorksDetailsMi($projectId){
	    $this->load->model('pmon_dep/dep_mi__m_project_setup');
        $recs = $this->dep_mi__m_project_setup->getMIProjectDataAll($projectId, array('office_ee.EWORK_ID', 'ps.PROJECT_SETUP_ID', 'ps.PROJECT_NAME', 'ps.PROJECT_NAME_HINDI', 'ps.PROJECT_CODE', 'ps.AA_NO', 'ps.AA_DATE', 'ps.AA_AMOUNT'));
        $data = array();
        if ($recs && $recs->num_rows()) {
            $rec = $recs->row();
            $recs->free_result();
            $data = array(
                'PROJECT_NAME' => $rec->PROJECT_NAME,
                'PROJECT_NAME_HINDI' => $rec->PROJECT_NAME_HINDI,
                'PROJECT_CODE' => $rec->PROJECT_CODE,
                'EWORK_ID' => $rec->EWORK_ID,
                'AA_NO' => $rec->AA_NO,
                'AA_DATE' => $rec->AA_DATE,
                'AA_AMOUNT' => $rec->AA_AMOUNT,
                'RAA_SNO' => 0,
                'RAA_NO' => 0,
                'RAA_DATE' => '',
                'RAA_AMOUNT' => 0
            );
        }
        $recs = $this->db->select('RAA_NO, RAA_DATE, RAA_AMOUNT')
            ->order_by('RAA_DATE', 'ASC')
            ->where('PROJECT_SETUP_ID', $projectId)
            ->where('IS_RAA', 1)
            ->get('dep_mi__t_raa_project');
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
    }

	private function isValidForLock(){
		$data = $this->getValidationDataForLock();
		$arrCheckFields = array(
			'DISTRICT_ID', 'TEHSIL_ID', 'BLOCK_ID', 'ASSEMBLY_CONST_ID', 
			'LONGITUDE_D', 'LATITUDE_D', 
			'block_served', 'district_served', 
			'assembly_const_served'
		);//villages_served','NO_VILLAGES_COVERED',
		//showArrayValues($data);
		$arrDataNotFoundFields = array();
		$arrZeroDataFields = array();
		for($i=0;$i<count($arrCheckFields);$i++){
			if(array_key_exists($arrCheckFields[$i], $data)){
				if($data[$arrCheckFields[$i]]==0){
					array_push($arrZeroDataFields, $arrCheckFields[$i]);
				}
			}else{
				array_push($arrDataNotFoundFields, $arrCheckFields[$i]);
			}
		}
		return array('zeroValued'=>$arrZeroDataFields, 'NotFound'=>$arrDataNotFoundFields);
	}
	private function getValidationDataForLock(){
		$arrWhich = array('PROJECT_ID'=>$this->PROJECT_ID);
		//get general data validation
		//hw - district, tehsil, block, assembly
		$mFields = array(
			'DISTRICT_ID', 'TEHSIL_ID', 'BLOCK_ID', 'ASSEMBLY_CONST_ID', 
			'LONGITUDE_D', 'LATITUDE_D'
		);//, 'NO_VILLAGES_COVERED'
		$arrGenData = array();
		/*for($i=0;$i<count($mFields);$i++){
			$arrGenData[ $mFields[$i] ] = 0;
		}*/
		$this->db->select( implode(',', $mFields) );
		$records = $this->db->get_where('__projects', $arrWhich);
		if($records && $records->num_rows()){
			$rec = $records->row();
			for($i=0;$i<count($mFields);$i++){
				$arrGenData[ $mFields[$i] ] = $rec->{$mFields[$i]};
			}
		}
		//benefited  - district, tehsil, block, assembly
		$arrTables = array(
			'__projects_block_served', '__projects_district_served', 
			'__projects_assembly_const_served'
		);//'__projects_villages_served', 
		$arrBenefitData = array(
			'block_served', 'district_served', 
			 'assembly_const_served'
		);//'villages_served',
		/*for($i;$i<count($arrBenefitData); $i++){
			$arrBenefitData[$i] = 0;
		}*/
		$arrSQLs = array();
		for($i=0;$i<count($arrTables);$i++){
			array_push(
				$arrSQLs,
				' SELECT COUNT(PROJECT_ID) AS COUNT_PROJECT, ('.
				$i.') AS MYMODE FROM '.
				$arrTables[$i].
				' WHERE PROJECT_ID='.$this->PROJECT_ID
			);
		}
		$strSQL = implode(' UNION ALL ', $arrSQLs);
		$recs = $this->db->query($strSQL);
		//$this->db->last_query();
		$arrBenefitDatas = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$arrBenefitDatas[ $arrBenefitData[$rec->MYMODE] ] = $rec->COUNT_PROJECT;
			}
		}
		//showArrayValues($arrBenefitDatas);
		//Merge array and encode it to json
		return array_merge($arrGenData, $arrBenefitDatas);
	}
	public function getValidationForLock(){
		$this->PROJECT_ID = $this->input->post('project_id');
		echo $this->createButtonSet();
		//echo json_encode( $this->getValidationDataForLock() );
	}
	//
	public function getPermissions(){
		$arrProjectKey = array('', 'DEP_PMON_PROJECT_SETUP', 'PMON_MEDIUM_PROJECT_SETUP', 'PMON_MAJOR_PROJECT_SETUP');
		$key = $arrProjectKey[ $this->session->userData('SETUP_PROJECT_TYPE_ID') ];
		return getAccessPermissions($key, $this->session->userData('USER_ID'));
	}
	//
	private function createButtonSet(){
		$arrButtons = array();
		if($this->PROJECT_ID==0){
			if($this->session->userdata('HOLDING_PERSON')==4){
				array_push(
					$arrButtons,
					getButton('Save and Edit More Data', "saveProject(0)", 4, 'cus-disk')
				);
			}
			array_push(
				$arrButtons,
				getButton('Save', "saveProject(1)", 4, 'cus-disk')
			);
		}else{
			array_push(
				$arrButtons,
				getButton('Save', "saveProject(2)", 4, 'cus-disk')
			);
			//check permission
			$permissions = $this->getPermissions();
			if($permissions['SAVE_LOCK']==1){
				//echo "Yes";
				$lockPass = false;
				//check lock buttons
				$lockData = $this->isValidForLock();
				//showArrayValues($lockData);
				$zeroData = count($lockData['zeroValued']);
				$noData = count($lockData['NotFound']);
				if( ($zeroData==0)&&($noData==0) ){
					$lockPass = true;	
				}
				if($lockPass){
					array_push(
						$arrButtons,
						getButton('Lock', 'lockProject();', 4, 'cus-lock')
					);
				}
			}
		}
		array_push(
			$arrButtons,
			getButton('Close', 'closeDialog();', 4, 'cus-cross')
		);
		return implode('&nbsp;', $arrButtons);
	}
	private function getSetupData($projectId){
		$recs = $this->db->get_where(
			'dep_pmon__t_estimated_status', 
			array('PROJECT_ID'=>$projectId)
		);
		$mFields = array(
			'LA_NA', 'FA_NA', 'STEEL_WORKS_NA',
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA', 'ROAD_WORKS_NA',
			'CANAL_LINING_NA', 'IRRIGATION_POTENTIAL_NA'
		);
		$setupData = array();
		$isExists = false;
		if($recs && $recs->num_rows()){
			$isExists = true;
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
	private function getProjectStartDate($projectId){
		$this->db->select('PROJECT_ID, SESSION_ID, PROJECT_START_DATE, PROJECT_SUB_TYPE, OFFICE_EE_ID');
		$this->db->where_in('PROJECT_ID', $projectId);
		$recs = $this->db->get('dep_pmon__v_projectlist_with_lock');
		return (($recs && $recs->num_rows()) ? $recs->row():false);
	}
	public function setInitialProgress($projectId){
		$records = array();
		$rec = $this->getProjectStartDate($projectId);
		if($rec){
			//session 0 means skip it
			if($rec->SESSION_ID==0) continue;
			$startSessionId = $this->getSessionIdByDate($rec->PROJECT_START_DATE);
			//ready for initial progress
			if($startSessionId<$rec->SESSION_ID){
				$sessionStartDate = strtotime($this->getSessionDate($rec->SESSION_ID));
				$lastMonthDate = date("Y-m", strtotime("-1 month", $sessionStartDate)).'-01';
				$arrEntryDate = array(
					'projectId'=>$rec->PROJECT_ID,
					'sessionId'=>($rec->SESSION_ID-1),
					'moduleType'=>'promon_deposit',
					'progressType'=>'initial',
					'date'=>$lastMonthDate,
					'month'=>((int) date("m", strtotime($lastMonthDate))),
					'year'=>((int) date("Y", strtotime($lastMonthDate)))
				);
				$this->load->library('myoverallprogress');
				$messages = $this->myoverallprogress->prepareForProgress($arrEntryDate);
				$this->message = array_merge($this->message, $messages);
			}
		}
	}
	private function getSessionDate($sessionId){
		$this->db->select('START_DATE');
		$recs = $this->db->get_where('__sessions', array('SESSION_ID'=>$sessionId));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->START_DATE;
		}
		return 0;
	}
	private function getSession($sessionId){
		$this->db->select('SESSION');
		$recs = $this->db->get_where('__sessions', array('SESSION_ID'=>$sessionId));
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION;
		}
		return 0;
	}
	protected function updateLockedStatus($params){
		$arrWhere = array('projectCode'=>$params['projectCode']);
		$data = array(
			"lDate" => $params['lDate'],
			"PLock" => $params['PLock'],
			"progressPerc" => $params['progressPerc']
		);
		$recs = $this->db->get_where('dep_pmon__t_locked_status', $arrWhere);
		if($recs && $recs->num_rows()){
			@$this->db->update('dep_pmon__t_locked_status', $data, $arrWhere);
		}else{
			$data['projectCode'] = $params['projectCode'];
			@$this->db->insert('dep_pmon__t_locked_status', $data);
		}
	}

	public function delproj(){
		$arrTables = array(
			'dep_pmon__t_estimated_status','dep_pmon__t_monthlydata_remarks','dep_pmon__t_extensions','dep_pmon__t_monthlydata',
			'dep_pmon__t_block_achievement_ip','dep_pmon__t_block_estimated_ip','dep_pmon__t_block_monthly_ip','dep_pmon__t_progress',
			'dep_pmon__t_yearlytargets','dep_pmon__t_achievements','dep_pmon__t_estimated_qty','dep_pmon__t_target_date_completion','dep_pmon__t_raa_project',
			'dep_pmon__m_status_date','dep_pmon__m_setup_status','dep_pmon__m_project_setup','dep_pmon__t_locks','dep_pmon__t_lock_logs','projects__ip_blockwise',
			'__projects_office','__projects_assembly_const_served','__projects_block_served','__projects_district_served','__projects_villages_served',
			'projects__agriculture','projects__canals','projects__canal_data','projects__dam_data','projects__facilities','projects__hydrological_data',
			'projects__pickup_weir','projects__raingauge','projects__reservoir_data','projects__spillchannel','projects__general_data',
			'projects__t_utility_entry','projects__t_cca_utility_entry','projects__actual_irrigation','__projects'
		);
		$this->db->select('PROJECT_ID');
		$this->db->where('DEL_DATE', date("Y-m-d"));
		$this->db->where('DEL_TIME', NULL);
		$recs = $this->db->get('dep_pmon__t_delete');
		if($recs && $recs->num_rows()){
			$arrIds = array();
			foreach($recs->result() as $rec){
				array_push($arrIds, $rec->PROJECT_ID);
			}
			if($arrIds){
				$this->db->where_in('PROJECT_ID', $arrIds);
				$this->db->delete($arrTables);				
			}

			$arrData = array('DEL_TIME'=>date("H:i:s"));
			$this->db->where_in('PROJECT_ID', $arrIds);
			@$this->db->update('dep_pmon__t_delete', $arrData);
			echo ' Deleted:'.implode(',', $arrIds);
		}
	}

	////////////////////////////***********************************////////////////////////////////
	////////////////////////////New Codes for MI Deposit//////////////////////////////// chec
	////////////////////////////***********************************////////////////////////////////

	//OK
     public function checkAaRaafileExists(){
        $PROJECT_SETUP_ID = $this->input->post('PROJECT_SETUP_ID');
        $mode = $this->input->post('mode');
        $userFileName = $this->input->post('filename');
        //1-delete file from directory
        //2-update table
        if($mode == 1) {
            $recs = $this->db->select('PROJECT_SETUP_ID, AA_USER_FILE_NAME, AA_FILE_URL')
            		 ->from('dep_mi__m_project_setup')
            		 ->where('AA_USER_FILE_NAME',$userFileName)
            		 ->get();
            if($recs && $recs->num_rows()) {
                $rec = $recs->row();
                $aaFileName = $rec->AA_FILE_URL;
                $filePath = FCPATH . 'dep_pmon' . DIRECTORY_SEPARATOR . $aaFileName;
                if(file_exists($filePath)) {
            		array_push($this->message, getMyArray(false, '<span style="color:#ff0000;">Sorry, you can not upload the file. File with same name is alreay uploaded on server.</span>'));
                    echo createJSONResponse($this->message);
                    return;                    
                }
            }else{
            	echo "";
            }
        }elseif($mode == 2) {
        	$aaFiles=0;
        	$raaFiles=0;
        	$recs = $this->db->select('PROJECT_SETUP_ID, AA_USER_FILE_NAME, AA_FILE_URL')
            		 ->from('dep_mi__m_project_setup')
            		 ->where('AA_USER_FILE_NAME',$userFileName)
            		 ->get();
			$aaFiles= $recs->num_rows();
            $recsRaa =$this->db->select('RAA_USER_FILE_NAME, RAA_FILE_URL')
        			->from('dep_mi__t_raa_project')
        			->where('RAA_USER_FILE_NAME', $userFileName)
        			->get();
        	$raaFiles= $recsRaa->num_rows(); 	
           if($aaFiles>0 || $raaFiles>0){
                		array_push($this->message, getMyArray(false, '<span style="color:#ff0000;">Sorry, you can not upload the file. File with same name is alreay uploaded on server.</span>'));
                    echo createJSONResponse($this->message);
                    return;                    
            }else{
            	echo "";
            }
        }
    }

	////////////////////////////***********************************////////////////////////////////
	////////////////////////////New Codes for MI Deposit////////////////////////////////
	////////////////////////////***********************************////////////////////////////////
    ///
    public function getAASessionId(){
        $date = myDateFormat($this->input->post('date'));
        $sessionId = getSessionIdByDate($date);
        echo $sessionId;
    }

    public function getDepositSchemeHead()
    {
        $this->db->select('ID , HEAD')
            ->from(AGREEMENT_DB.".dep__m_scheme")
            ->where('IS_ACTIVE','0');
        $this->db->order_by('ID', 'ASC');
        $recs = $this->db->get();
        return $recs->result_array();
    }

    public function getDepositScheme($DepositId=0){
        $this->db->select('ID, HEAD, SCHEME_NAME_HINDI , SCHEME_NAME_ENGLISH , SCHEME_ABBREVIATION')
            ->from(AGREEMENT_DB.".dep__m_scheme")
            ->where('IS_ACTIVE','0');
        $this->db->order_by('ID', 'ASC');
        $recs = $this->db->get();
        if(!is_array($DepositId)) $DepositId = array($DepositId);
        $vlist = array();
        array_push($vlist, '<option value="">Select Deposit Scheme</option>');
        if($recs && $recs->num_rows()){
            foreach($recs->result() as $rec){
                array_push($vlist,
                    '<option value="'.$rec->ID.'" '.
                    ((in_array($rec->ID, $DepositId)) ? 'selected="selected"':'').'>'.$rec->SCHEME_NAME_ENGLISH.'('.$rec->SCHEME_NAME_HINDI.')</option>'
                );
            }
            $recs->free_result();
        }
        return implode('', $vlist);
    }
}
