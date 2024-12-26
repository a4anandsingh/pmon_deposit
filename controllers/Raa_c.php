<?php 
class Raa_c extends MX_Controller{
	var $PROJECT_ID, $previousEstimation, $currentEstimation;
	function __construct(){
		parent::__construct();
		$this->RESULT= false;
		$this->message = array();
		$this->PROJECT_ID = 0;
	}
	function index(){
		$data = array();
		$arrProjectType = array('', 'Minor', 'Medium', 'Major');
		$this->session->set_userdata(array('RAA_PROJECT_TYPE_ID' => 1));
		$data['message'] = '';
		$data['page_heading'] = pageHeading('DEPOSIT PROMON - '.$arrProjectType[1].' Project -  RAA/TS/Extra Quantity Setup');
		$this->load->library('office_filter');
		$data['office_list'] = $this->office_filter->office_list();
		$data['project_grid'] = $this->createGrid();
		$data['raa_grid'] = $this->createRAAGrid();
		$this->load->view('pmon_deposit/raa_index_view', $data);
	}
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
		$buttons = array();
		$mfunctions = array();
		array_push(
			$mfunctions , 
			"onSelectRow: function(ids){refreshRAASearch();}"
		);
		$aData = array(
			'set_columns' => array(
				array(
					'label' => 'Project',
					'name' => 'PROJECT_NAME',
					'width' => 60,
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
					'label' => 'No.',
					'name' => 'AA_NO',
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
					'label' => 'Date',
					'name' => 'AA_DATE',
					'width' => 25,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> 'date',
					'newformat'=> 'd-m-Y',
					'srcformat'=> 'Y-m-d',
					'searchoptions'=>''
				),
				array(
				 	'label' => 'Amount',
					'name' => 'AA_AMOUNT',
					'width' => 20,
					'align' => "right",
					'resizable'=>false,
					'sortable'=>false,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				),
				array(
				 	'label' => 'Locked',
					'name' => 'LOCKED',
					'width' => 10,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>false,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				)/*,
				array(
				 	'label' => '',
					'name' => 'operation',
					'width' => 70,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>false,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				)*/
			),
			'custom' => array("button"=>$buttons, "function"=>$mfunctions),
			'div_name' => 'projectGrid',
			'source' => 'getProjectGrid',
			'postData' => "{'SEARCH_CE_ID':$('#search_officeCE_ID').val(), 
			'SEARCH_SE_ID':$('#search_officeSE_ID').val(), 
			'SEARCH_EE_ID':$('#search_officeEE_ID').val(), 
			'SEARCH_SDO_ID':$('#search_officeSDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()}",
			'rowNum'=>10,
			'width'=>(DEFAULT_GRID_WIDTH/1.7),
			'height' => '',
			'altRows'=> true,
			'rownumbers'=>true,	
			'sort_name' => 'PROJECT_NAME',
			'sort_order' => 'asc',
			'primary_key' => 'PROJECT_NAME',
			'caption' => 'Projects',
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
	private function createRAAGrid(){
		$permissions = $this->getPermissions();
		$buttons = array();
		if ($permissions['MODIFY']){
			array_push(
				$buttons, 
				"{ caption:'', title:'Edit Record',position :'first', 
				buttonicon : 'ui-icon-pencil', 
				onClickButton:function(){showRAAEntryForm(BUTTON_MODIFY);}, cursor: 'pointer'}"
			);
		}
		if ($permissions['ADD']){
			array_push(
				$buttons, 
				"{ caption:'', title:'Add New Record',position :'first', 
				buttonicon : 'ui-icon-plus', 
				onClickButton:function(){showRAAEntryForm(BUTTON_ADD_NEW);}, cursor: 'pointer'}"
			);
		}
		$mfunctions = array();
		array_push(
			$mfunctions , 
			"ondblClickRow: function(ids){showRAAEntryForm(BUTTON_MODIFY);}"
		);
		$aData = array(
			'set_columns' => array(
				array(
				 	'label' => 'Entry Type',
					'name' => 'ENTRY_TYPE',
					'width' => 25,
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
				 	'label' => 'No.',
					'name' => 'RAA_NO',
					'width' => 50,
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
				 	'label' => 'Date',
					'name' => 'RAA_DATE',
					'width' => 25,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> 'date',
					'newformat'=> 'd-m-Y',
					'srcformat'=> 'Y-m-d',
					'searchoptions'=>''
				),
				array(
				 	'label' => 'Amount',
					'name' => 'RAA_AMOUNT',
					'width' => 30,
					'align' => "right",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				),
				array(
				 	'label' => 'Authority',
					'name' => 'AUTHORITY_NAME',
					'width' => 50,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>true,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				)/*,
				array(
				 	'label' => 'Operation',
					'name' => 'ADD',
					'width' => 70,
					'align' => "center",
					'resizable'=>false,
					'sortable'=>false,
					'hidden'=>false,
					'view'=>true,
					'search'=>true,
					'formatter'=> '',
					'searchoptions'=>''
				)*/
			),
			'custom' => array("button"=>$buttons, "function"=>$mfunctions),
			'div_name' => 'raaGrid',
			'source' => 'getRAAGrid',
			'postData' => "{'PROJECT_ID':0}",
			'rowNum'=>10,
			'width'=>(DEFAULT_GRID_WIDTH/2),
			'height' => '',
			'altRows'=> true,
			'rownumbers'=>true,	
			'sort_name' => 'RAA_DATE',
			'sort_order' => 'asc',
			'primary_key' => 'RAA_PROJECT_ID',
			'caption' => 'Project RAA ',
			'pager' => true,
			'showTotalRecords' => true,
			'toppager' =>false,
			'bottompager' =>true,
			'multiselect'=>false,
			'toolbar'=> true,
			'toolbarposition'=>'top',/*bottom*/
			'hiddengrid'=>false,
			'editable'=>false,
			'forceFit'=>true,
			'gridview'=>true,
			'footerrow'=>false, 
			'userDataOnFooter'=>true, 
			'treeGrid'=>false, 
			'custom_button_position'=>'bottom' /*top*/
		);
		return buildGrid($aData);
	}
	//Show Entry
	public function getProjectGrid(){
		$permissions = $this->getPermissions();
		//showArrayValues($permissions);
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);
		//if ( $this->input->post('project_id') )
		//	array_push( $objFilter->SQL_PARAMETERS, array('PROJECT_ID' => $this->input->post('project_id')) );
		$SDO_ID = $this->input->post('SEARCH_SDO_ID');
		$EE_ID = $this->input->post('SEARCH_EE_ID');
		$CE_ID = $this->input->post('SEARCH_CE_ID');
		$SE_ID = $this->input->post('SEARCH_SE_ID');

		$EEE ='';
		if ($EE_ID==0 && $SE_ID==0 && $CE_ID==0 && $SDO_ID==0){
			//DO NOTHING .....
		}else{
			$EEE = ($SDO_ID==0)? '' : ' OFFICE_SDO_ID='.$SDO_ID;
			$EEE .= ($EE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_EE_ID='.$EE_ID;
			$EEE .= ($SE_ID==0)? '' : ( ($EEE=='') ? '':' AND ').'OFFICE_SE_ID='.$SE_ID;
			$EEE .= ($CE_ID==0)? '' : ( ($EEE=='') ? '':' AND '). 'OFFICE_CE_ID='.$CE_ID;
			array_push(	$objFilter->WHERE, $EEE);
		}
		//$objFilter->GROUP_BY = ' PROJECT_ID'; , AUTHORITY_NAME
		$objFilter->SQL = 'SELECT PROJECT_SETUP_ID, PROJECT_ID, PROJECT_START_DATE,
			PROJECT_NAME, PROJECT_NAME_HINDI, PROJECT_CODE, AA_NO, AA_DATE, AA_AMOUNT, 
			IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					 "<span class=\'cus-bullet-green\'></span>") as LOCKED
			FROM dep_pmon__v_projectlist_with_lock
				WHERE PROJECT_TYPE_ID = '. $this->session->userData('RAA_PROJECT_TYPE_ID').
				' AND SETUP_LOCK=1 AND PROJECT_STATUS<5 ';
		/* =============== */
		$objFilter->executeMyQuery();
		// echo $objFilter->PREPARED_SQL;
		$rows = array();
		if($objFilter->TOTAL_RECORDS){
			foreach($objFilter->RESULT as $row){
				$fieldValues = array();
				array_push($fieldValues, '"'.addslashes($row->PROJECT_NAME ).'"');
				array_push($fieldValues, '"'.addslashes($row->PROJECT_CODE).'"');
				array_push($fieldValues, '"'.addslashes($row->AA_NO).'"');
				array_push($fieldValues, '"'.addslashes($row->AA_DATE).'"');
				array_push($fieldValues, '"'.addslashes($row->AA_AMOUNT).'"');
				array_push($fieldValues, '"'.$row->LOCKED.'"');
				if ($permissions['ADD']){
					$xx = 'showRAAEntryForm(0, '.$row->PROJECT_ID.')';
					array_push($fieldValues, '"'.addslashes( getButton('Add New', $xx, 4, 'cus-add')).'"');
				}else{
					array_push($fieldValues, '""');
				}
				//array_push($fieldValues, '"'.$but.'"');
				array_push($objFilter->ROWS, '{"id":"'.$row->PROJECT_ID.'", "cell":['. implode(',', $fieldValues).']}');
			}
		}
		echo $objFilter->getJSONCodeByRow();
	}
	public function getRAAGrid(){
		$permissions = $this->getPermissions();
		$projectID = $this->input->post('PROJECT_ID');
		if(!$projectID) return;
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);
		$objFilter->SQL = 'SELECT PROJECT_ID, RAA_PROJECT_ID, 
			IF(IS_RAA=1, "RAA", IF(IS_RAA=2, "Extra Qty", "TS"))as ENTRY_TYPE,
			IFNULL(RAA_NO, "")AS RAA_NO, 
			IFNULL(RAA_DATE, "")AS RAA_DATE, 
			IFNULL(RAA_AMOUNT, "")AS RAA_AMOUNT, 
			IFNULL(AUTHORITY_NAME,"")AS AUTHORITY_NAME
			FROM dep_pmon__v_raa_locks
				WHERE PROJECT_ID='.$projectID;
		$objFilter->executeMyQuery();
		$rows = array();
		//echo '$objFilter->TOTAL_RECORDS:'.$objFilter->TOTAL_RECORDS;
		if($objFilter->TOTAL_RECORDS){
			foreach($objFilter->RESULT as $row){
				$fieldValues = array();
				array_push($fieldValues, '"'.addslashes($row->ENTRY_TYPE ).'"');
				array_push($fieldValues, '"'.addslashes($row->RAA_NO ).'"');
				array_push($fieldValues, '"'.addslashes($row->RAA_DATE).'"');
				array_push($fieldValues, '"'.addslashes($row->RAA_AMOUNT).'"');
				array_push($fieldValues, '"'.addslashes($row->AUTHORITY_NAME).'"');
				/*
				if ($row->LOCK_STATUS==1){
					array_push($fieldValues, '""');
				}else{
					if ($permissions['MODIFY'] && ($row->LOCK_STATUS!=1)){
						$xx = 'showRAAEntryForm('.$row->RAA_PROJECT_ID.', '.$row->PROJECT_ID.')';
						array_push($fieldValues, '"'.addslashes( getButton('EDIT', $xx, 4, ' cus-cog-edit')).'"');
					}else{
						array_push($fieldValues, '""');
					}
				}
				if ($row->LOCK_STATUS==1){
					array_push($fieldValues, '""');
				}else{
					if ($permissions['DELETE']){
						$xx = 'deleteRAA('.$row->RAA_PROJECT_ID.', '.$row->PROJECT_ID.')';
						array_push($fieldValues, '"'.addslashes( getButton('DELETE', $xx, 4, 'cus-cross')).'"');
					}else{
						array_push($fieldValues, '""');
					}
				}
				if ($row->LOCK_STATUS==1){
					array_push($fieldValues, '""');
				}else{
					if ($permissions['SAVE_LOCK']){
						$xx = 'lockProject('.$row->RAA_PROJECT_ID.', '.$row->PROJECT_ID.')';
						array_push($fieldValues, '"'.addslashes( getButton('Lock', $xx, 4, 'cus-lock')).'"');
					}else{
						array_push($fieldValues, '""');
					}
				}*/
				//array_push($fieldValues, '"'.$but.'"');
				array_push($objFilter->ROWS, '{"id":"'.$row->RAA_PROJECT_ID.'", "cell":['. implode(',', $fieldValues).']}');
			}
		}
		echo $objFilter->getJSONCodeByRow();
		//echo $objFilter->PREPARED_SQL;
	}
	/*------------ Show Entry --------------*/
	private function getPreviousEstimated($raaProjectId){
		$prevRAAId = 0;
		if($raaProjectId==0){
			//search last record in RAA for project id
			$strSQL = 'SELECT RAA_PROJECT_ID 
					FROM dep_pmon__t_raa_project WHERE PROJECT_ID='.$this->PROJECT_ID.
					 ' ORDER BY RAA_PROJECT_ID DESC LIMIT 0, 1';
			$recs = $this->db->query($strSQL);
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					$prevRAAId = $rec->RAA_PROJECT_ID;
				}
			}
		}else{
			//search previous RAA record for 1 less than raa record
			$strSQL = 'SELECT RAA_PROJECT_ID 
					FROM dep_pmon__t_raa_project WHERE RAA_PROJECT_ID<'.$raaProjectId.
						' AND PROJECT_ID='.$this->PROJECT_ID.
					 ' ORDER BY RAA_PROJECT_ID DESC LIMIT 0, 1';
			$recs = $this->db->query($strSQL);
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					$prevRAAId = $rec->RAA_PROJECT_ID;
				}
			}
		}
		$arrEFields = $this->getEstimationFields();
		$arrEValues = array();
		for($i=0;$i<count($arrEFields);$i++){
			$arrEValues[ $arrEFields[$i]] = 0;
		}
		//if not found then show estimation aa record
		if($prevRAAId){
			$strSQL = 'SELECT * FROM dep_pmon__t_estimated_qty 
				WHERE RAA_ID='.$prevRAAId.
					' AND PROJECT_ID='.$this->PROJECT_ID;
			//echo $strSQL;
			$result = $this->db->query($strSQL);
			if($result && ($result->num_rows()==0)){
				$prevRAAId = 0;
			}
		}
		$strSQL = 'SELECT * FROM dep_pmon__t_estimated_qty 
			WHERE RAA_ID='.$prevRAAId.
				' AND PROJECT_ID='.$this->PROJECT_ID;
		//echo $strSQL;
		$result = $this->db->query($strSQL);
		if($result && $result->num_rows()){
			foreach($result->result() as $rec){
				for($i=0;$i<count($arrEFields);$i++){
					$arrEValues[ $arrEFields[$i]] = $rec->{$arrEFields[$i]};
				}
			}
		}
		//echo 'estimation:'.$prevRAAId. '::';
		//showArrayValues($arrEValues);
		$this->previousEstimation = $arrEValues;
		
		//now capture current values
		$arrEValues = array();
		for($i=0;$i<count($arrEFields);$i++){
			$arrEValues[ $arrEFields[$i]] = 0;
		}
		if($raaProjectId==0){
			//do nothing
		}else{
			$strSQL = 'SELECT * FROM dep_pmon__t_estimated_qty 
				WHERE RAA_ID='.$raaProjectId .
					' AND PROJECT_ID='.$this->PROJECT_ID;
			$result = $this->db->query($strSQL);
			if($result && $result->num_rows()){
				foreach($result->result() as $rec){
					for($i=0;$i<count($arrEFields);$i++){
						$arrEValues[ $arrEFields[$i]] = $rec->{$arrEFields[$i]};
					}
				}
			}
		}
		//, $currentEstimation;
		$this->currentEstimation = $arrEValues;
		//showArrayValues($arrEValues);
	}
  	private function isMonthlyExists($mDate){
		$recs = $this->db->get_where('dep_pmon__t_monthlydata', array('PROJECT_ID'=>$this->PROJECT_ID, 'MONTH_DATE >'=>$mDate) );
		return ($recs && $recs->num_rows());
	}
	public function showRAASetupEntryBox(){
		$this->PROJECT_ID = $this->input->post('PROJECT_ID');
		$raaProjectId = $this->input->post('RAA_PROJECT_ID');
		if($this->PROJECT_ID){
			$recs = $this->db->get_where(
				'dep_pmon__v_projectlist_with_lock',
				array('PROJECT_ID'=>$this->PROJECT_ID)
			);
			if($recs && $recs->num_rows()){
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
			$raaFields = array(
				'RAA_PROJECT_ID', 'RAA_NO', 'RAA_AUTHORITY_ID', 
				'RAA_DATE', 'RAA_AMOUNT', 'IS_RAA', 'ADDED_BY'
			);
			$raaData = array(
				'RAA_PROJECT_ID'=>0,
				'RAA_NO'=>'',
				'RAA_AUTHORITY_ID'=>0,
				'RAA_DATE'=>'',
				'RAA_AMOUNT'=>'',
				'RAA_SAVE_DATE'=>'',
				'IS_RAA'=>0,
				'ADDED_BY'=>1
			);
			//$isValid = true;
			if($raaProjectId){
				$recs = $this->db->get_where(
					'dep_pmon__t_raa_project',	
					array('RAA_PROJECT_ID'=>$raaProjectId)
				);
				if($recs && $recs->num_rows()){
					$rec = $recs->row();
					//if($rec->ADDED_BY==0){
					//	$isValid = false;
					//}else{
						for($i=0; $i<count($raaFields); $i++){
							$raaData[$raaFields[$i]] = $rec->{$raaFields[$i]};
						}
					//}
				}
			}
          	$data['isMonthlyExists'] = FALSE;
          //echo $this->session->userdata('USER_ID');
			if($raaProjectId){
              	if(in_array($this->session->userdata('USER_ID'), array(23, 25))){
                  $data['isMonthlyExists'] = FALSE;
                }else{
					$data['isMonthlyExists'] = $this->isMonthlyExists($raaData['RAA_DATE']);
                }
			}
				$this->getPreviousEstimated($raaProjectId);
          
				$data['raaData'] = $raaData;
				$data['PROJECT_ID'] = $this->PROJECT_ID;
				$data['projectData'] = $arrProjectData;
				$data['estimationStatus'] = $this->getEstimationStatus();
				$data['previousEstimation'] = $this->previousEstimation;
				$data['currentEstimation'] = $this->currentEstimation ;
				$data['AUTH_VALUES'] = $this->getAuthority($arrProjectData['AA_AUTHORITY_ID']);
				$data['RAA_AUTHORITY_ID'] = $this->getAuthority($raaData['RAA_AUTHORITY_ID']);
				$this->load->view('pmon_deposit/raa_data_view', $data);
			/*}else{
				echo "<div class='ui-state-error' style='padding:5px'>
					<span class='cus-error'></span><strong>Sorry! You can't Modify RAA Entered through Setup...</strong></div>";
			}*/
		}
	}
	//
	/** getAuthority */
	protected function getAuthority($AuthID=0){
		$this->db->order_by('AUTHORITY_ID', 'ASC');
		$recs = $this->db->get('pmon__m_authority');
		$dd_auth = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				array_push(
					$dd_auth, 
					'<option value="'.$rec->AUTHORITY_ID.'" '.
					(($rec->AUTHORITY_ID==$AuthID) ? 'selected="selected"':'').'>'.
					$rec->AUTHORITY_NAME.'</option>'
				);
			}
		}
		return $dd_auth;
	}
	protected function getEstimationFields(){
		return array(
			'ESTIMATED_QTY_ID', 'PROJECT_ID', 'RAA_ID', 'SESSION_ID',
			'LA_NO', 'LA_HA','LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 
			'IRRIGATION_POTENTIAL_KHARIF',
			'IRRIGATION_POTENTIAL_RABI',
			'IRRIGATION_POTENTIAL', 'ADDED_BY',
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK','ROAD_WORKS');
	}
	protected function getEstimationStatus(){
		$mFields = array(
			'PROJECT_ID', 'LA_NA', 'FA_NA',
			'HEAD_WORKS_EARTHWORK_NA', 'HEAD_WORKS_MASONRY_NA', 'STEEL_WORKS_NA',
			'CANAL_EARTHWORK_NA', 'CANAL_STRUCTURES_NA', 
			'CANAL_LINING_NA', 'IRRIGATION_POTENTIAL_NA' ,'ROAD_WORKS_NA'
		);
		$recs = $this->db->get_where(
			'dep_pmon__t_estimated_status', 
			array('PROJECT_ID'=>$this->PROJECT_ID)
		);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			for($i=0; $i<count($mFields);$i++){
				$data[$mFields[$i]] = $rec->{$mFields[$i]};
			}
		}else{
			for($i=0; $i<count($mFields);$i++){
				$data[$mFields[$i]] = 0;
			}
		}
		return $data;
	}
	protected function getSessionIdByDate($mdate){
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
	protected function getRAAFields(){
		return array(
			'RAA_PROJECT_ID', 'PROJECT_ID', 'SESSION_ID', 
			'RAA_NO', 'RAA_DATE', 'RAA_AUTHORITY_ID', 'RAA_AMOUNT', 
			'EXPENDITURE_TOTAL', 'IS_RAA',
			'EXPENDITURE_WORK', 'PROJECT_COMPLETION_DATE', 
			'ADDED_BY', 'RAA_SAVE_DATE'
		);
	}
	/*START AMIT 12-08-2024*/
	public function checkStatusByNoDate(){
		$this->PROJECT_ID = $this->input->post('PROJECT_ID');
		$raaProjectId = $this->input->post('RAA_PROJECT_ID');
		$entryType = $this->input->post('IS_RAA');
		$param =  array(
			'PROJECT_ID' => $this->PROJECT_ID,
			'RAA_PROJECT_ID' => $raaProjectId,
			'IS_RAA' => $entryType,
			'RAA_NO'=>$this->input->post('RAA_NO'), 
			'RAA_DATE'=> myDateFormat($this->input->post('RAA_DATE'))
		);
		$arrType = array('1' => 'RAA', '2' => 'Sanction', '3'=> 'TS ');
		$response = array('status' => "success", 'message' => "OK");
		$this->db->select('*');
        $this->db->from('dep_pmon__t_raa_project');
        $this->db->where(array('PROJECT_ID '=> $param['PROJECT_ID'], 'RAA_NO' => $param['RAA_NO'], 'RAA_DATE' => $param['RAA_DATE']));
		if($param['RAA_PROJECT_ID']){
            $this->db->where(array('RAA_PROJECT_ID <>' => $param['RAA_PROJECT_ID']));
        }
		$recs = $this->db->get();
		//echo $this->db->last_query();
        if($recs && $recs->num_rows()) {
            $rec = $recs->row();
            $response = array('status' => "failed", 'message' => "This ".$arrType[$param['IS_RAA']]." number & ".$arrType[$param['IS_RAA']]." Date already exists.");
        }
		echo json_encode($response);  
	}
	/*END AMIT 12-08-2024*/
	public function saveRAAData(){
		$this->PROJECT_ID = $this->input->post('PROJECT_ID');
		$raaProjectId = $this->input->post('RAA_PROJECT_ID');
		//get sessionid from RAA Date
		$raaDate = myDateFormat($this->input->post('RAA_DATE'));
		$sessionId = $this->getSessionIdByDate($raaDate);
		$editMode = (($raaProjectId) ? true:false);

		$raaFieldNames =  $this->getRAAFields();
		$arrRAA = array();
		$goAhead = false;
		$entryType = $this->input->post('IS_RAA');
		$raaAmount = 0;
		if(intval($entryType) == 1)
			$raaAmount = $this->input->post('RAA_AMOUNT');
			$data = array(
			'SESSION_ID'=>$sessionId,  
			'RAA_NO'=>$this->input->post('RAA_NO'), 
			'RAA_DATE'=>myDateFormat($this->input->post('RAA_DATE')), 
			'RAA_AUTHORITY_ID'=>$this->input->post('RAA_AUTHORITY_ID'), 
			'RAA_AMOUNT'=>$raaAmount, 
			'EXPENDITURE_TOTAL'=>$this->input->post('EXPENDITURE_TOTAL'), 
			'EXPENDITURE_WORK'=>$this->input->post('EXPENDITURE_WORK'),
			'ADDED_BY'=>$this->input->post('ADDED_BY'),
			'IS_RAA'=>$entryType
		);// myDateFormat($this->input->post('RAA_SAVE_DATE'))
    /* START 04-06-2024*/
    if(intval($data['IS_RAA']) == 1){
    	$eworkId= 0;
		$this->db->select('PROJECT_ID, EWORK_ID');
		$this->db->where('PROJECT_ID', $this->PROJECT_ID);
		$recs = $this->db->get('dep_pmon__v_projectlist_with_lock');
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$eworkId =$rec->EWORK_ID;
		}
    	$this->db->select('RAA_NO, RAA_DATE, RAA_AMOUNT');
		$this->db->order_by('RAA_DATE', 'ASC');
		$this->db->where('PROJECT_ID', $this->PROJECT_ID);
		$this->db->where('IS_RAA', 1);
		$recs = $this->db->get('dep_pmon__t_raa_project');		
    	$sn=$recs->num_rows();
    	if(!$editMode) $sn++;
    
    	$RAAIssuedBy ='';
    	$strSQL = ' SELECT * FROM pmon__m_authority WHERE AUTHORITY_ID = '.$data['RAA_AUTHORITY_ID'];
    	$recs = $this->db->query($strSQL);	
    	if($recs && $recs->num_rows()){
        	$rec = $recs->row();
        	$RAAIssuedBy =$rec->AUTHORITY_NAME;
        	if(intval($rec->AUTHORITY_ID) == 10){
            	$RAAIssuedBy = 'Govt. of chhattisgarh';
            }
        }
		$arrRAAIssueAuthority =array(
            5=>"Others",
            6=>"Commissioner",
            8=>"Collector",
            9=>"Govt. of Madhya Pradesh",
            10=>"Govt. of chhattisgarh",
            11=>"Chief Engineer",
            12=>"Superintending Engineer",
            13=>"Executive Engineer",
            14=>"???? ???? ??????? ?????",
            15=>"National Thermal Power Corporation (NTPC)",
            16=>"CEO Zilla Panchayat"
        );
    	$params = array(
                'mode'=>'raasetup',
                "projectCode"=>$this->PROJECT_ID,
                "RAANo"=>$data['RAA_NO'],
                "RAADate"=>$data['RAA_DATE'],
                "RAAAmount"=>$data['RAA_AMOUNT'],
                "RAASerialNo"=>$sn,
                "DIVISION_ID"=>$eworkId,
        		//"RAAIssuedBy" => $RAAIssuedBy,
        		"RAAIssuedBy" => $arrRAAIssueAuthority[$data['RAA_AUTHORITY_ID']],
            );
            //showArrayValues($params); exit;
            if(!IS_LOCAL_SERVER){
                $this->load->library('mycurl');
                $result = $this->mycurl->savePromonData($params);
            //echo $result;
                $obj = json_decode($result);
                if(intval($obj->{'success'}) != 1){
                    array_push($this->message, getMyArray(false, 'RAA Details is failed to sending epayment server.'.$obj->{'message'}));
                    echo createJSONResponse( $this->message );
                    exit;
                }
            }
    }
    /* END 04-06-2024*/
		if($editMode){
			$arrWhere = array(
				'RAA_PROJECT_ID'=>$raaProjectId, 
				'PROJECT_ID'=>$this->PROJECT_ID
			);
			$recs = $this->db->get_where('dep_pmon__t_raa_project', $arrWhere);
			$isExists = (($recs && $recs->num_rows())? true:false);
			if($isExists){
				@$this->db->update('dep_pmon__t_raa_project', $data, $arrWhere);
				$goAhead = true;
				if($this->db->affected_rows()){
					array_push($this->message, getMyArray(true, 'RAA Record Updated...'));
				}else{
					array_push($this->message, getMyArray(false, 'Unable to Update RAA Record...'));
				}
			}
		}else{
			//Add
			$data['PROJECT_ID'] = $this->PROJECT_ID;
			$data['RAA_SAVE_DATE'] = date("Y-m-d");
			$data['ADDED_BY'] = 1;
			@$this->db->insert('dep_pmon__t_raa_project', $data);
			if($this->db->affected_rows()){
				$raaProjectId = $this->db->insert_id();
				$goAhead = true;
				array_push($this->message, getMyArray(true, 'RAA Record Created...'));
			}else{
				$goAhead = false;
				array_push($this->message, getMyArray(true, 'Unable to Create RAA Record...'));
			}
		}
		if($goAhead){
			//update to eworks server
			if($data['IS_RAA']==1) $this->sendRAAToEWorks();
			$arrSetupData = $this->getEstimationStatus();
			$estimatedQtyId =  $this->input->post('ESTIMATED_QTY_ID');
			$mExcludeFields = array('ESTIMATED_QTY_ID', 'PROJECT_ID', 'RAA_ID');
			$eFieldNames = $this->getEstimationFields();
			$arrWhere = array(
				'RAA_ID'=>$raaProjectId, 
				'ESTIMATED_QTY_ID'=>$estimatedQtyId
			);
			$eData = array();
			for($i=1; $i<count($eFieldNames); $i++){
				if( in_array($eFieldNames[$i], $mExcludeFields)){
					if($estimatedQtyId){
						//do nothing
					}else{
						if($eFieldNames[$i] == 'PROJECT_ID'){
							$eData[$eFieldNames[$i]] = $this->PROJECT_ID;
						}else if($eFieldNames[$i] == 'RAA_ID'){
							$eData[$eFieldNames[$i]] = $raaProjectId;
						}else if($eFieldNames[$i] == 'SESSION_ID'){
							$eData[$eFieldNames[$i]] = $sessionId;
						}
					}
				}else{
					if($eFieldNames[$i] == 'SESSION_ID'){
						$eData['SESSION_ID'] = $sessionId;
					}else{
						$eData[$eFieldNames[$i]] = $this->input->post($eFieldNames[$i]);
					}
				}//if
			}//for
			//set NA field data
			foreach($arrSetupData as $k=>$v){
				if($v==1){
					if($k=='LA_NA'){
						$eData['LA_NO'] = 0;
						$eData['LA_HA'] = 0;
						$eData['LA_COMPLETED_NO'] = 0;
						$eData['LA_COMPLETED_HA'] = 0;
					}else if($k=='FA_NA'){
						$eData['FA_HA'] = 0;
						$eData['FA_COMPLETED_HA'] = 0;
					}else if($k=='IRRIGATION_POTENTIAL_NA'){
						$eData['IRRIGATION_POTENTIAL_KHARIF'] = 0;
						$eData['IRRIGATION_POTENTIAL_RABI'] = 0;
						$eData['IRRIGATION_POTENTIAL'] = 0;
					}else{
						$eData[ str_replace('_NA', '', $k) ] = 0;
					}
				}
			}
			$eData['IRRIGATION_POTENTIAL'] = $eData['IRRIGATION_POTENTIAL_KHARIF']+ $eData['IRRIGATION_POTENTIAL_RABI'];
			$recs = $this->db->get_where('dep_pmon__t_estimated_qty', $arrWhere);
			if($recs && $recs->num_rows()){
				@$this->db->update('dep_pmon__t_estimated_qty', $eData, $arrWhere);
				if($this->db->affected_rows()){
					array_push($this->message, getMyArray(true, 'Estimation Record Updated...'));
				}
			}else{
				@$this->db->insert('dep_pmon__t_estimated_qty', $eData);
				if($this->db->affected_rows()){
					array_push($this->message, getMyArray(true, 'New Estimattion Record Created...'));
				}else{
					array_push($this->message, getMyArray(false, 'Unable To Create Record...'));
				}
			}
		}
		if($goAhead){
			//SAVE LATEST ENTRY IN LOCK TABLE
			$arrWhere = array('PROJECT_ID'=>$this->PROJECT_ID);
			$recs = $this->db->get_where('dep_pmon__t_locks', $arrWhere);
			$isExists = false;
			$data = array('RAA_EXISTS'=>$raaDate);
			if($recs && $recs->num_rows()){
				$isExists = true;
				$rec = $recs->row();
				if($raaDate>$rec->RAA_EXISTS){
					@$this->db->update('dep_pmon__t_locks', $data, $arrWhere);
				}
			}
			if(!$isExists){
				$data = array('RAA_EXISTS'=>$raaDate);
				@$this->db->insert('dep_pmon__t_locks', $data);
			}
		}
		if ((!$goAhead) || ($this->db->trans_status() === FALSE)){
			// generate an error... or use the log_message() function to log your error
			array_push($this->message, getMyArray(false, $this->db->log_message()));
			$this->db->trans_rollback();
		}else{
			$this->db->trans_complete();
		}
		echo createJSONResponse( $this->message );
	}
	private function sendRAAToEWorks(){
		if(!IS_LOCAL_SERVER){
			$this->load->library('mycurl');
			$serverStatus = $this->mycurl->getServerStatus();
			if($serverStatus==0){
				echo 'Unable to lock. E-work Server Not responding. Try after sometime...';
				return;
			}
		}
    	/* start 03-06-2024*/
		$eworkId = 0;
		$this->db->select('PROJECT_ID, EWORK_ID');
		$this->db->where('PROJECT_ID', $this->PROJECT_ID);
		$recs = $this->db->get('dep_pmon__v_projectlist_with_lock');
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$eworkId =$rec->EWORK_ID;
		}
		/* end 03-06-2024*/
    
		$this->db->select('RAA_NO, RAA_DATE, RAA_AMOUNT');
		$this->db->order_by('RAA_DATE', 'ASC');
		$this->db->where('PROJECT_ID', $this->PROJECT_ID);
		$this->db->where('IS_RAA', 1);
		$recs = $this->db->get('dep_pmon__t_raa_project');
		$data = array(
			'RAA_SNO'=>0,
			'RAA_NO'=>0,
			'RAA_DATE'=>0,
			'RAA_AMOUNT'=>$rec->RAA_AMOUNT
		);
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
		$params = array(
			'mode'=>'raasetup',
			"projectCode"=>$this->PROJECT_ID,
			"RAANo"=>$data['RAA_NO'],
			"RAADate"=>$data['RAA_DATE'],
			"RAAAmount"=>$data['RAA_AMOUNT'],
			"RAASerialNo"=>$data['RAA_SNO'],
			"DIVISION_ID"=>$eworkId
		);
		//showArrayValues($params);
		if(!IS_LOCAL_SERVER){
			$result = $this->mycurl->savePromonData($params);
			//echo $result;
			$obj = json_decode($result);
			if($obj->{'success'}){
				array_push($this->message, getMyArray(true, 'RAA Data Sent to E-Works Server...'));
			}else{
                array_push($this->message, getMyArray(true, 'RAA Data Not Sent to E-Works Server... '.$obj->{'message'}));
            }
		}
	}
	//
	public function deleteRAA(){
		$projectId = $this->input->post('PROJECT_ID');
		$raaProjectId = $this->input->post('RAA_PROJECT_ID');
		if($projectId!=0 && $raaProjectId!=0){
			$this->db->trans_begin();
			$strSQL = 'DELETE FROM dep_pmon__t_raa_project 
				WHERE PROJECT_ID='.$projectId . ' AND RAA_PROJECT_ID='.$raaProjectId;
			$result = $this->db->query($strSQL);
			$strSQL = 'DELETE FROM dep_pmon__t_estimated_qty 
				WHERE PROJECT_ID='.$projectId . ' AND RAA_ID='.$raaProjectId;
			$result = $this->db->query($strSQL);
			if ($this->db->trans_status() === FALSE){
				echo 'Unable to Delete Data...Roll Back';
				$this->db->trans_rollback();
			}else{
				$this->db->trans_commit();
				echo 'Record Deleted...';
			}
		}else{
			echo 'Unable to Delete Record...';
		}
	}
	//
	public function lockRAA(){
		$projectId = $this->input->post('project_id');
		$raaProjectId = $this->input->post('raaid');
		$permissions = $this->getPermissions();
		if($permissions['SAVE_LOCK']){
			$recs = $this->db->get_where(
				'dep_pmon__t_raa_project', 
				array(
					'RAA_PROJECT_ID'=>$raaProjectId,
					'PROJECT_ID'=>$projectId
				)
			);
			$isExists = (($recs && $recs->num_rows())?true:false);
			if($isExists){
				$rec = $recs->row();
				$data = array('RAA_DATE'=>$rec->RAA_DATE);
				$arrWhere = array('PROJECT_ID'=>$projectId);
				@$this->db->update('dep_pmon__t_locks', $data, $arrWhere);
				if($this->db->affected_rows()){
					array_push($this->message, getMyArray(true, 'Project RAA/Extra Quantity Record Locked....'));
				}
			}
		}else{
			array_push($this->message, getMyArray(false, 'Permission Access Denied...'));
		}
	}
	//
	public function getPermissions(){
		$arrProjectKey = array('', 'PMON_MINOR_RAA', 'PMON_MEDIUM_RAA', 'PMON_MAJOR_RAA');
		$key = $arrProjectKey[ $this->session->userData('RAA_PROJECT_TYPE_ID') ];
		return getAccessPermissions($key, $this->session->userData('USER_ID'));
	}
	private function createButtonSet(){
		$arrButtons = array();
		array_push(
			$arrButtons,
			getButton('Save RAA', "saveRAASetup()", 4, 'cus-disk')
		);
		array_push(
			$arrButtons,
			getButton('Close', 'closeDialog();', 4, 'cus-cancel')
		);
		return implode('&nbsp;', $arrButtons);
	}
}