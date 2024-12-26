<?php 
//display_error(1);
error_reporting(E_ALL);
class Raa_c extends MX_Controller{
	var $PROJECT_SETUP_ID, $previousEstimation, $currentEstimation, $previousBlockEstimation, $currentBlockEstimation;
	function __construct(){
		parent::__construct();
		$this->RESULT= false;
		$this->message = array();
		$this->PROJECT_SETUP_ID = 0;
        $this->load->model('mi/mi__t_raa_project');
	}
    public function index(){
		$data = array();
		$data['message'] = '';
		$data['page_heading'] = pageHeading('PROMON - Micro Project -  RAA/TS/Extra Quantity Setup');
		$this->load->library('office_filter');
		$data['office_list'] = $this->office_filter->office_list();
		$data['project_grid'] = $this->createGrid();
		$data['raa_grid'] = $this->createRAAGrid();
		$this->load->view('mi/raa_index_view', $data);
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
			'sort_name' => 'WORK_NAME',
			'sort_order' => 'asc',
			'primary_key' => 'WORK_NAME',
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
			'postData' => "{'PROJECT_SETUP_ID':0}",
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
		$objFilter->SQL = 'SELECT PROJECT_SETUP_ID, AA_DATE as  PROJECT_START_DATE,
			WORK_NAME, WORK_NAME_HINDI, PROJECT_CODE, AA_NO, AA_DATE, AA_AMOUNT, 
			IF(SETUP_LOCK=1, "<span class=\'cus-lock\'></span>",
					 "<span class=\'cus-bullet-green\'></span>") as LOCKED
			FROM mi__v_projectlist_with_lock
				WHERE 1'.
				' AND SETUP_LOCK=1 AND WORK_STATUS<5 ';
		/* =============== */
		$objFilter->executeMyQuery();
        //echo $objFilter->PREPARED_SQL;
        //exit;
		$rows = array();
		if($objFilter->TOTAL_RECORDS){
			foreach($objFilter->RESULT as $row){
				$fieldValues = array();
				array_push($fieldValues, '"'.addslashes($row->WORK_NAME ).'"');
				array_push($fieldValues, '"'.addslashes($row->PROJECT_CODE).'"');
				array_push($fieldValues, '"'.addslashes($row->AA_NO).'"');
				array_push($fieldValues, '"'.addslashes($row->AA_DATE).'"');
				array_push($fieldValues, '"'.addslashes($row->AA_AMOUNT).'"');
				array_push($fieldValues, '"'.$row->LOCKED.'"');
				/*if ($permissions['ADD']){
					$xx = 'showRAAEntryForm(0, '.$row->PROJECT_ID.')';
					array_push($fieldValues, '"'.addslashes( getButton('Add New', $xx, 4, 'cus-add')).'"');
				}else{
					array_push($fieldValues, '""');
				}*/
				//array_push($fieldValues, '"'.$but.'"');
				array_push($objFilter->ROWS, '{"id":"'.$row->PROJECT_SETUP_ID.'", "cell":['. implode(',', $fieldValues).']}');
			}
		}
		echo $objFilter->getJSONCodeByRow();
	}
	public function getRAAGrid(){
		$permissions = $this->getPermissions();
		$projectID = $this->input->post('PROJECT_SETUP_ID');
		if(!$projectID) return;
		$objFilter = new clsFilterData();
		$objFilter->assignCommonPara($_POST);
		$objFilter->SQL = 'SELECT m.PROJECT_SETUP_ID, m.RAA_PROJECT_ID, 
			IF(m.IS_RAA=1, "RAA", IF(m.IS_RAA=2, "Extra Qty", "TS"))as ENTRY_TYPE,
			IFNULL(m.RAA_NO, "")AS RAA_NO, 
			IFNULL(m.RAA_DATE, "")AS RAA_DATE, 
			IFNULL(m.RAA_AMOUNT, "")AS RAA_AMOUNT, 
			IFNULL(a.AUTHORITY_NAME,"")AS AUTHORITY_NAME
			FROM mi__t_raa_project as m
				INNER JOIN pmon__m_authority as a ON(m.RAA_AUTHORITY_ID=a.AUTHORITY_ID)
				WHERE m.PROJECT_SETUP_ID='.$projectID;
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
					FROM mi__t_raa_project WHERE PROJECT_SETUP_ID='.$this->PROJECT_SETUP_ID.
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
					FROM mi__t_raa_project WHERE RAA_PROJECT_ID<'.$raaProjectId.
						' AND PROJECT_SETUP_ID='.$this->PROJECT_SETUP_ID.
					 ' ORDER BY RAA_PROJECT_ID DESC LIMIT 0, 1';
			$recs = $this->db->query($strSQL);
			if($recs && $recs->num_rows()){
				foreach($recs->result() as $rec){
					$prevRAAId = $rec->RAA_PROJECT_ID;
				}
			}
		}
		//$arrEFields = $this->getEstimationFields();
        $arrEFields = $this->mi__t_raa_project->getEstimationFields();
        $arrEValues = array();
		for($i=0;$i<count($arrEFields);$i++){
			$arrEValues[ $arrEFields[$i]] = 0;
		}
		//if not found then show estimation aa record
		if($prevRAAId){
			$strSQL = 'SELECT * FROM mi__t_estimated_qty 
				WHERE RAA_ID='.$prevRAAId.
					' AND PROJECT_SETUP_ID='.$this->PROJECT_SETUP_ID;
			//echo $strSQL;
			$result = $this->db->query($strSQL);
			if($result && ($result->num_rows()==0)){
				$prevRAAId = 0;
			}
		}
		$strSQL = 'SELECT * FROM mi__t_estimated_qty 
			WHERE RAA_ID='.$prevRAAId.
				' AND PROJECT_SETUP_ID='.$this->PROJECT_SETUP_ID;
		//echo $strSQL;
		$result = $this->db->query($strSQL);
		if($result && $result->num_rows()){
			foreach($result->result() as $rec){
				for($i=0;$i<count($arrEFields);$i++){
					//@todo error in this code check it
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
			$strSQL = 'SELECT * FROM mi__t_estimated_qty 
				WHERE RAA_ID='.$raaProjectId .
					' AND PROJECT_SETUP_ID='.$this->PROJECT_SETUP_ID;
			$result = $this->db->query($strSQL);
			//echo $strSQL;exit;
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
		//exit;
	}
  	private function isMonthlyExists($mDate){
		$recs = $this->db->get_where('mi__t_monthly', array('PROJECT_SETUP_ID'=>$this->PROJECT_SETUP_ID, 'MONTH_DATE >'=>$mDate) );
		return ($recs && $recs->num_rows());
	}
	public function showRAASetupEntryBox(){
		$this->PROJECT_SETUP_ID = $this->input->post('PROJECT_SETUP_ID');
		$raaProjectId = $this->input->post('RAA_PROJECT_ID');
		if($this->PROJECT_SETUP_ID){
			$recs = $this->db->get_where(
				'mi__v_projectlist_with_lock',
				array('PROJECT_SETUP_ID'=>$this->PROJECT_SETUP_ID)
			);

			if($recs && $recs->num_rows()){
				$rec = $recs->row();
				$arrProjectData = array(
					'PROJECT_CODE' => $rec->PROJECT_CODE,
					'WORK_NAME' => $rec->WORK_NAME,
					'WORK_NAME_HINDI' => $rec->WORK_NAME_HINDI,
					'AA_NO' => $rec->AA_NO,
					'AA_AUTHORITY_ID' => $rec->AA_AUTHORITY_ID,
					//'OTHER_RAA_AUTHORITY' => $rec->OTHER_RAA_AUTHORITY,
					'AA_DATE' => $rec->AA_DATE,
					'AA_AMOUNT' => $rec->AA_AMOUNT					
				);
			}
			$raaFields = array(
				'RAA_PROJECT_ID', 'RAA_NO', 'RAA_AUTHORITY_ID', 'OTHER_RAA_AUTHORITY',
				'RAA_DATE', 'RAA_AMOUNT', 'IS_RAA', 'ADDED_BY','RAA_FILE_URL','RAA_USER_FILE_NAME'
			);
			$raaData = array(
				'RAA_PROJECT_ID'=>0,
				'RAA_NO'=>'',
				'RAA_AUTHORITY_ID'=>0,
				'OTHER_RAA_AUTHORITY'=>'',				
				'RAA_DATE'=>'',
				'RAA_AMOUNT'=>'',
				'RAA_SAVE_DATE'=>'',
				'IS_RAA'=>0,
				'ADDED_BY'=>1,
                'RAA_FILE_URL'=>'',
                'RAA_USER_FILE_NAME'=>''
			);
			//$isValid = true;
			if($raaProjectId){
				$recs = $this->db->get_where(
					'mi__t_raa_project',
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
            $arrBlockIps = array();
            $arrBlockIps = $this->getEstimatedBlockIP($this->PROJECT_SETUP_ID, $raaProjectId);
            $prevBlockEstimation = $this->previousBlockEstimation;
            $curBlockEstimation = $this->currentBlockEstimation;
            $arrBlockIds = $this->mi__t_raa_project->getBlockIds($this->PROJECT_SETUP_ID);
            $blockIP = array();
            foreach ($arrBlockIds as $id) {
                $blockIP[$id]= array(
                    'BLOCK_NAME'=> $prevBlockEstimation[$id]['BLOCK_NAME'],
                    'BLOCK_NAME_HINDI'=> $prevBlockEstimation[$id]['BLOCK_NAME_HINDI'],
                    'PREV_ESTI'=> $prevBlockEstimation[$id]['ESTIMATION_IP'],
                    'CUR_ESTI'=> $curBlockEstimation[$id]['ESTIMATION_IP'],
                    'IS_SAME'=>$curBlockEstimation[$id]['IS_SAME']
                );
            }
            $data['arrSetupStatus'] = $this->mi__t_raa_project->getEstimationStatus($this->PROJECT_SETUP_ID);
            $data['BLOCK_IP_DATA'] = $blockIP;
            $data['setupData'] = $this->mi__t_raa_project->getSetupData($this->PROJECT_SETUP_ID);
            $data['raaData'] = $raaData;
            $data['PROJECT_SETUP_ID'] = $this->PROJECT_SETUP_ID;
            $data['projectData'] = $arrProjectData;
            $data['estimationStatus'] = $this->mi__t_raa_project->getEstimationStatus($this->PROJECT_SETUP_ID);
            $data['previousEstimation'] = $this->previousEstimation;
            $data['currentEstimation'] = $this->currentEstimation ;
            $data['AUTH_VALUES'] = $this->mi__t_raa_project->getAuthorityOptions($arrProjectData['AA_AUTHORITY_ID']);
            $data['RAA_AUTHORITY_ID'] = $this->mi__t_raa_project->getAuthorityOptions($raaData['RAA_AUTHORITY_ID']);
            $this->load->view('mi/raa_data_view', $data);
			/*}else{
				echo "<div class='ui-state-error' style='padding:5px'>
					<span class='cus-error'></span><strong>Sorry! You can't Modify RAA Entered through Setup...</strong></div>";
			}*/
		}
	}

    protected function getEstimatedBlockIP($projectId, $raaProjectId){
        $prevRAAId = 0;
        if($raaProjectId==0){
            //search last record in RAA for project id
            $strSQL = 'SELECT RAA_PROJECT_ID 
					FROM mi__t_raa_project WHERE PROJECT_SETUP_ID='.$projectId.
                ' ORDER BY RAA_PROJECT_ID DESC LIMIT 0, 1';
            $recs = $this->db->query($strSQL);
            //echo $this->db->last_query();
            if($recs && $recs->num_rows()){
                foreach($recs->result() as $rec){
                    $prevRAAId = $rec->RAA_PROJECT_ID;
                }
            }
        }else{
            //search previous RAA record for 1 less than raa record
            $strSQL = 'SELECT RAA_PROJECT_ID 
					FROM mi__t_raa_project WHERE RAA_PROJECT_ID<'.$raaProjectId.
                ' AND PROJECT_SETUP_ID='.$projectId.
                ' ORDER BY PROJECT_SETUP_ID DESC LIMIT 0, 1';
            $recs = $this->db->query($strSQL);
            if($recs && $recs->num_rows()){
                foreach($recs->result() as $rec){
                    $prevRAAId = $rec->RAA_PROJECT_ID;
                }
            }
        }
        $arrEstiBlockValues = $this->setEstimationBlockArray($projectId);
        $arrEValues = array();
        $arrEValues = $arrEstiBlockValues;

        //if not found then show estimation aa record
        if($prevRAAId){
            $strSQL =
                'SELECT mi__ip_design_block.* , b.BLOCK_NAME, b.BLOCK_NAME_HINDI
				FROM 
					mi__ip_design_block 
				JOIN
					__blocks as b ON (mi__ip_design_block.BLOCK_ID=b.BLOCK_ID)
				WHERE RAA_PROJECT_ID='.$prevRAAId.
                ' AND PROJECT_SETUP_ID='.$projectId;
            //echo $strSQL;

            $result = $this->db->query($strSQL);
            if($result && ($result->num_rows()==0)){
                $prevRAAId = 0;
            }
        }
        $strSQL =
            'SELECT mi__ip_design_block.* , b.BLOCK_NAME, b.BLOCK_NAME_HINDI 
			 FROM
			 	 mi__ip_design_block 
			 JOIN
				__blocks as b ON (mi__ip_design_block.BLOCK_ID=b.BLOCK_ID)
			WHERE RAA_PROJECT_ID='.$prevRAAId.
            ' AND PROJECT_SETUP_ID='.$projectId;
        //echo $strSQL; exit;
        $result = $this->db->query($strSQL);
        if($result && $result->num_rows()){
            foreach($result->result() as $rec){
                /*foreach ($arrFields as $value) {
                    $arrEValues[ $value] = $rec->{$value};
                }*/
                $arrEValues[$rec->BLOCK_ID] = array(
                    'BLOCK_NAME'=>$rec->BLOCK_NAME,
                    'BLOCK_NAME_HINDI'=>$rec->BLOCK_NAME_HINDI,
                    'ESTIMATION_IP'=>array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>($rec->KHARIF+ $rec->RABI)),
                    'IS_SAME'=>array('KHARIF'=>$rec->IS_KHARIF, 'RABI'=>$rec->IS_RABI)
                );
            }
        }
        //echo 'estimation:'.$prevRAAId. '::';
        //showArrayValues($arrEValues);
        $this->previousBlockEstimation = $arrEValues;

        //now capture current values
        $arrEValues = array();
        $arrEValues = $arrEstiBlockValues;

        if($raaProjectId==0){
            //do nothing
        }else{
            $strSQL = 'SELECT mi__ip_design_block.* , b.BLOCK_NAME, b.BLOCK_NAME_HINDI 
				FROM mi__ip_design_block 
				JOIN
					__blocks as b ON (mi__ip_design_block.BLOCK_ID=b.BLOCK_ID)
				WHERE RAA_PROJECT_ID='.$raaProjectId .
                ' AND PROJECT_SETUP_ID='.$projectId;
            $result = $this->db->query($strSQL);
            //echo $this->db->last_query();exit;
            if($result && $result->num_rows()){
                foreach($result->result() as $rec){
                    /*foreach ($arrFields as $value) {
                        $arrEValues[ $value] = $rec->{$value};
                    }*/
                    $arrEValues[$rec->BLOCK_ID] = array(
                        'BLOCK_NAME'=>$rec->BLOCK_NAME,
                        'BLOCK_NAME_HINDI'=>$rec->BLOCK_NAME_HINDI,
                        'ESTIMATION_IP'=>array('KHARIF'=>$rec->KHARIF, 'RABI'=>$rec->RABI, 'IP'=>($rec->KHARIF+$rec->RABI)),
                        'IS_SAME'=>array('KHARIF'=>$rec->IS_KHARIF, 'RABI'=>$rec->IS_RABI)
                    );
                }
            }
        }
        //, $currentEstimation;
        $this->currentBlockEstimation = $arrEValues;
        //showArrayValues($arrEValues);
    }

    protected function setEstimationBlockArray($projectId){
        $arrEValues = array();
        $recs = $this->db->select('b.BLOCK_ID, b.BLOCK_NAME, b.BLOCK_NAME_HINDI')
            ->from('mi__m_block_served p')
            ->join('__blocks b','p.BLOCK_ID=b.BLOCK_ID')
            ->where(array('PROJECT_SETUP_ID'=>$projectId))
            ->get();
        //echo $this->db->last_query(); exit;
        if($recs && $recs->num_rows()){
            foreach($recs->result() as $rec){
                //array_push($blocks, $rec->BLOCK_ID);
                $arrEValues[$rec->BLOCK_ID] = array(
                    'BLOCK_NAME'=>$rec->BLOCK_NAME,
                    'BLOCK_NAME_HINDI'=>$rec->BLOCK_NAME_HINDI,
                    'ESTIMATION_IP'=>array('KHARIF'=>0, 'RABI'=>0, 'IP'=>0),
                    'IS_SAME'=>array('KHARIF'=>0, 'RABI'=>0)
                );
            }
        }
        return $arrEValues;
    }

	//
	/** getAuthority */
	/*protected function getAuthority($AuthID=0){
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
	}*/
	protected function getEstimationFields(){
		/*return array(
			'ESTIMATED_QTY_ID', 'PROJECT_SETUP_ID', 'RAA_ID', 'SESSION_ID',
			'LA_NO', 'LA_HA','LA_COMPLETED_NO', 'LA_COMPLETED_HA',
			'FA_HA', 'FA_COMPLETED_HA', 
			'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 'STEEL_WORKS', 
			'CANAL_EARTHWORK', 'CANAL_STRUCTURES', 'CANAL_LINING', 
			'IRRIGATION_POTENTIAL_KHARIF',
			'IRRIGATION_POTENTIAL_RABI',
			'IRRIGATION_POTENTIAL', 'ADDED_BY',
			'EXPENDITURE_TOTAL', 'EXPENDITURE_WORK');*/
        return array(
            'ESTIMATED_QTY_ID', 'PROJECT_SETUP_ID', 'RAA_ID', 'SESSION_ID',
            'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
            'FA_HA', 'FA_COMPLETED_HA', 'L_EARTHWORK',  'C_MASONRY',
            'C_PIPEWORK', 'C_DRIP_PIPE', 'C_WATERPUMP', 'K_CONTROL_ROOMS');
        //, 'KHARIF', 'RABI', 'IP_TOTAL' 'C_EARTHWORK',
	}
	/*already in mi__base
	 * protected function getSessionIdByDate($mdate){
		$strSQL = "SELECT SESSION_ID FROM __sessions
			WHERE START_DATE<='".$mdate."'
				AND END_DATE>='".$mdate."'";
		$recs = $this->db->query($strSQL);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_ID;
		}
		return 0;
	}*/
	/*already in mi__base
	 * protected function getRAAFields(){
		return array(
			'RAA_PROJECT_ID', 'PROJECT_SETUP_ID', 'SESSION_ID',
			'RAA_NO', 'RAA_DATE', 'RAA_AUTHORITY_ID', 'RAA_AMOUNT', 
			'EXPENDITURE_TOTAL', 'IS_RAA',
			'EXPENDITURE_WORK', 'PROJECT_COMPLETION_DATE', 
			'ADDED_BY', 'RAA_SAVE_DATE'
		);
	}*/
	public function saveRAAData(){
		$this->PROJECT_SETUP_ID = $this->input->post('PROJECT_SETUP_ID');
		$raaProjectId = $this->input->post('RAA_PROJECT_ID');
		//get sessionid from RAA Date
		$raaDate = myDateFormat($this->input->post('RAA_DATE'));
		$sessionId = getSessionIdByDate($raaDate);
		$editMode = (($raaProjectId) ? true:false);

        $BLOCK_K= $this->input->post('BLOCK_K');
        $BLOCK_R= $this->input->post('BLOCK_R');
        $BLOCK_T= $this->input->post('BLOCK_T');
        $HID_BLOCK_ID= $this->input->post('HID_BLOCK_ID');
        $IS_BLOCK_K= $this->input->post('IS_BLOCK_K');
        $IS_BLOCK_R= $this->input->post('IS_BLOCK_R');

        $OTHER_RAA_AUTHORITY = $this->input->post('OTHER_RAA_AUTHORITY');

		//$raaFieldNames =  $this->mi__t_raa->getRAAFields();
		$arrRAA = array();
		$goAhead = false;
		$entryType = $this->input->post('IS_RAA');
		$raaAmount = 0;
		if($entryType==1)
			$raaAmount = $this->input->post('RAA_AMOUNT');
		    $data = array(
                'SESSION_ID'=>$sessionId,
                'RAA_NO'=>$this->input->post('RAA_NO'),
                'RAA_DATE'=>myDateFormat($this->input->post('RAA_DATE')),
                'RAA_AUTHORITY_ID'=>$this->input->post('RAA_AUTHORITY_ID'),
                'OTHER_RAA_AUTHORITY'=>$this->input->post('OTHER_RAA_AUTHORITY'),
                'RAA_AMOUNT'=>$raaAmount,
                'ADDED_BY'=>$this->input->post('ADDED_BY'),
                'IS_RAA'=>$entryType
            );// myDateFormat($this->input->post('RAA_SAVE_DATE'))
		if($editMode){
            //echo "in if edit mode";exit;
			$arrWhere = array(
				'RAA_PROJECT_ID'=>$raaProjectId, 
				'PROJECT_SETUP_ID'=>$this->PROJECT_SETUP_ID
			);
			$recs = $this->db->get_where('mi__t_raa_project', $arrWhere);
			$isExists = (($recs && $recs->num_rows())? true:false);
			if($isExists){
				@$this->db->update('mi__t_raa_project', $data, $arrWhere);
				$goAhead = true;
				if($this->db->affected_rows()){
					array_push($this->message, getMyArray(true, 'RAA Record Updated...'));
				}else{
					array_push($this->message, getMyArray(false, 'Unable to Update RAA Record...'));
				}
			}
		}else{
            //echo "in else edit mode";exit;
			//Add
			$data['PROJECT_SETUP_ID'] = $this->PROJECT_SETUP_ID;
			$data['RAA_SAVE_DATE'] = date("Y-m-d");
			$data['ADDED_BY'] = 1;
			@$this->db->insert('mi__t_raa_project', $data);
			if($this->db->affected_rows()){
				$raaProjectId = $this->db->insert_id();
				$goAhead = true;
				array_push($this->message, getMyArray(true, 'RAA Record Created...'));
			}else{
				$goAhead = false;
				array_push($this->message, getMyArray(true, 'Unable to Create RAA Record...'));
			}
		}
		//upload RAA Scan Copy
        if($goAhead){
            $dirPath ='MR-'.$this->PROJECT_SETUP_ID;
            $filePath = FCPATH.'aa_raa_uploads'.DIRECTORY_SEPARATOR.$dirPath;
            if(is_dir($filePath)){
            }else{
                if (!mkdir($filePath, 0777, true)){
                    //die('1-Failed to create folders...');
                }
            }
            chmod($filePath, 0777);
            $config['upload_path']          = $filePath.DIRECTORY_SEPARATOR;
            $config['allowed_types']        = 'jpg|pdf';
            $config['encrypt_name']         = 'TRUE';
            $config['max_filename']         = '15';
            $config['remove_spaces']        = 'TRUE';
            $this->load->library('upload', $config);
            $field_name = "RAA_SCAN_COPY";
            if (!$this->upload->do_upload($field_name)) {
                array_push($this->message, getMyArray(false, "RAA scan copy-" . $this->upload->display_errors()));
                $error = 1;
            } else {
                array_push($this->message, getMyArray(true, "RAA scan copy uploaded"));
                $RAA_FILE_ARRAY = $this->upload->data();
                $RAA_FILE_URL = $RAA_FILE_ARRAY['file_name'];
                $RAA_USER_FILE_NAME = $RAA_FILE_ARRAY['client_name'];
                $arrRAAFileData= array();
                $arrRAAFileData['RAA_FILE_URL'] = $dirPath.'/'.$RAA_FILE_URL;
                $arrRAAFileData['RAA_USER_FILE_NAME'] = $RAA_USER_FILE_NAME;
                $raaTableName = 'mi__t_raa_project';
                $isExists = false;
                //again check
                $arrSearch = array('RAA_PROJECT_ID'=>$raaProjectId);
                //if($this->isRecordExists($raaTableName, $arrSearch)){
                    $recs = $this->db->get_where($raaTableName, $arrSearch);
                    if($recs && $recs->num_rows()){
                        $isExists = true;
                    }
               // }
                if($isExists){
                    @$this->db->update($raaTableName, $arrRAAFileData, $arrSearch);
                }
            }
        }

		if($goAhead){
			//update to eworks server
			if($data['IS_RAA']==1) $this->sendRAAToEWorks();
			$arrSetupData = $this->mi__t_raa_project->getEstimationStatus($this->PROJECT_SETUP_ID);
			$estimatedQtyId =  $this->input->post('ESTIMATED_QTY_ID');
			$mExcludeFields = array('ESTIMATED_QTY_ID', 'PROJECT_SETUP_ID', 'RAA_ID','ENTRY_MODE');
			//$eFieldNames = $this->getEstimationFields();
            $eFieldNames = $this->mi__t_raa_project->getEstimationFields();
            //echo "sssssssssssssss";
            //showArrayValues($eFieldNames);
            //exit;
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
						if($eFieldNames[$i] == 'PROJECT_SETUP_ID'){
							$eData[$eFieldNames[$i]] = $this->PROJECT_SETUP_ID;
						}else if($eFieldNames[$i] == 'RAA_ID'){
							$eData[$eFieldNames[$i]] = $raaProjectId;
						}else if($eFieldNames[$i] == 'SESSION_ID'){
							$eData[$eFieldNames[$i]] = $sessionId;
						}
					}
					if($eFieldNames[$i]=='ENTRY_MODE'){
                        $eData[$eFieldNames[$i]] = ($entryType+1);
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
			    if($k=='IP_TOTAL_NA') continue;
				if($v==1){
					if($k=='LA_NA'){
						$eData['LA_NO'] = 0;
						$eData['LA_HA'] = 0;
						$eData['LA_COMPLETED_NO'] = 0;
						$eData['LA_COMPLETED_HA'] = 0;
					}else if($k=='FA_NA'){
						$eData['FA_HA'] = 0;
						$eData['FA_COMPLETED_HA'] = 0;
					}/*else if($k=='IP_TOTAL_NA'){
						$eData['KHARIF'] = 0;
						$eData['RABI'] = 0;
						$eData['IP_TOTAL'] = 0;
					}*/elseif($k=='PROJECT_SETUP_ID') {
                        $eData['PROJECT_SETUP_ID']= $this->PROJECT_SETUP_ID;
                    }else{
                        /*if($k=='ENTRY_MODE')
                         $eData['ENTRY_MODE']= ($entryType);
                        else*/
						$eData[ str_replace('_NA', '', $k) ] = 0;
					}
				}
			}
            $recs = $this->db->get_where('mi__t_estimated_qty', $arrWhere);
			if($recs && $recs->num_rows()){
				@$this->db->update('mi__t_estimated_qty', $eData, $arrWhere);
                //echo $this->db->last_query();
				if($this->db->affected_rows()){
					array_push($this->message, getMyArray(true, 'Estimation Record Updated...'));
				}
			}else{
				@$this->db->insert('mi__t_estimated_qty', $eData);
                //echo $this->db->last_query();
				if($this->db->affected_rows()){
					array_push($this->message, getMyArray(true, 'New Estimation Record Created...'));
				}else{
					array_push($this->message, getMyArray(false, 'Unable To Create Record...'));
				}
			}
			//echo $this->db->last_query();
            $arrWhereEstiIp = array();
            $blockEstiData = array();
            foreach ($HID_BLOCK_ID as $key => $value) {
                $blockEstiData['PROJECT_SETUP_ID'] = $this->PROJECT_SETUP_ID;
                $blockEstiData['BLOCK_ID'] = $value;
                $blockEstiData['KHARIF'] = $BLOCK_K[$key];
                $blockEstiData['RABI'] = $BLOCK_R[$key];
                $blockEstiData['RAA_PROJECT_ID'] = $raaProjectId;

                if(isset($IS_BLOCK_K[$key])){
                    $blockEstiData['IS_KHARIF'] = $IS_BLOCK_K[$key];
                }else{
                    $blockEstiData['IS_KHARIF'] = 0;
                }
                if(isset($IS_BLOCK_R[$key])){
                    $blockEstiData['IS_RABI'] = $IS_BLOCK_R[$key];
                }else{
                    $blockEstiData['IS_RABI'] = 0;
                }

                $arrWhereEstiIp = array('RAA_PROJECT_ID'=>$raaProjectId, 'PROJECT_SETUP_ID'=>$this->PROJECT_SETUP_ID, 'BLOCK_ID' => $value );
                $recs = $this->db->get_where('mi__ip_design_block', $arrWhereEstiIp);
                //echo '<br> 1'. $this->db->last_query();
                if($recs && $recs->num_rows()){
                    @$this->db->update('mi__ip_design_block', $blockEstiData, $arrWhereEstiIp);
                }else{
                    @$this->db->insert('mi__ip_design_block', $blockEstiData);
                }
                //echo '<br> 2'. $this->db->last_query();
            }
            //$PROJECT_ID = $this->mi__t_raa_project->getParentProjectId($this->PROJECT_SETUP_ID);
            $this->load->library('irrigation_ledger');
            $arrDesignedLedger = array('BLOCK_IDS'=>$HID_BLOCK_ID,
                'KHARIF_DATA'=>$BLOCK_K,
                'RABI_DATA'=>$BLOCK_R,
                'PROJECT_SETUP_ID'=>$this->PROJECT_SETUP_ID,
                'MONTH_DATE'=>$raaDate,
                'PROJECT_MODE'=>'MI',
                'ENTRY_MODE'=>$entryType+1,
                'RAA_ID' => $raaProjectId
            );
            $this->irrigation_ledger->setDesigned($arrDesignedLedger);
		}
		if($goAhead){
			//SAVE LATEST ENTRY IN LOCK TABLE
			$arrWhere = array('PROJECT_SETUP_ID'=>$this->PROJECT_SETUP_ID);
			$recs = $this->db->get_where('mi__t_locks', $arrWhere);
			$isExists = false;
			$data = array('RAA_EXISTS'=>$raaDate);
			/*if($recs && $recs->num_rows()){
				$isExists = true;
				$rec = $recs->row();
				if($raaDate>$rec->RAA_EXISTS){
					@$this->db->update('mi__t_locks', $data, $arrWhere);
				}
			}*/
			/*if(!$isExists){
				$data = array('RAA_EXISTS'=>$raaDate);
				@$this->db->insert('mi__t_locks', $data);
			}*/
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
		$eworkId= 0;
		$this->db->select('PROJECT_SETUP_ID, OFFICE_EE_ID');
		$this->db->where('PROJECT_SETUP_ID', $this->PROJECT_SETUP_ID);
		$recs = $this->db->get('mi__v_projectlist_with_lock');
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$eworkId = getEWorkId($rec->OFFICE_EE_ID);
		}
		/* end 03-06-2024*/
		$this->db->select('RAA_NO, RAA_DATE, RAA_AMOUNT');
		$this->db->order_by('RAA_DATE', 'ASC');
		$this->db->where('PROJECT_SETUP_ID', $this->PROJECT_SETUP_ID);
		$this->db->where('IS_RAA', 1);
		$recs = $this->db->get('mi__t_raa_project');
		$data = array(
			'RAA_SNO'=>0,
			'RAA_NO'=>0,
			'RAA_DATE'=>0,
			'RAA_AMOUNT'=>0
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
			"projectCode"=>$this->PROJECT_SETUP_ID,
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
			}
		}
	}
	//
	public function deleteRAA(){
		$projectId = $this->input->post('PROJECT_SETUP_ID');
		$raaProjectId = $this->input->post('RAA_PROJECT_ID');
		if($projectId!=0 && $raaProjectId!=0){
			$this->db->trans_begin();
			$strSQL = 'DELETE FROM mi__t_raa_project 
				WHERE PROJECT_SETUP_ID='.$projectId . ' AND RAA_PROJECT_ID='.$raaProjectId;
			$result = $this->db->query($strSQL);
			$strSQL = 'DELETE FROM mi__t_estimated_qty 
				WHERE PROJECT_SETUP_ID='.$projectId . ' AND RAA_ID='.$raaProjectId;
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
				'mi__t_raa_project',
				array(
					'RAA_PROJECT_ID'=>$raaProjectId,
					'PROJECT_SETUP_ID'=>$projectId
				)
			);
			$isExists = (($recs && $recs->num_rows())?true:false);
			if($isExists){
				$rec = $recs->row();
				$data = array('RAA_DATE'=>$rec->RAA_DATE);
				$arrWhere = array('PROJECT_SETUP_ID'=>$projectId);
				@$this->db->update('mi__t_locks', $data, $arrWhere);
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
		return getAccessPermissions('PMON_MICRO_RAA', $this->session->userData('USER_ID'));
	}

	//not using this method
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

    public function removeRAAFile(){
        $RAA_PROJECT_ID = $this->input->post('RAA_PROJECT_ID');
        $mode = $this->input->post('mode');
        //1-delete file from directory
        //2-update table
        $this->db->select('RAA_FILE_URL');
        $this->db->from('mi__t_raa_project');
        $this->db->where(array('RAA_PROJECT_ID'=>$RAA_PROJECT_ID));
        $recs = $this->db->get();

        if($recs && $recs->num_rows()){
            $rec = $recs->row();
            $raaFileName = $rec->RAA_FILE_URL;
            $filePath= FCPATH.'aa_raa_uploads'.DIRECTORY_SEPARATOR.$raaFileName;
            if(file_exists($filePath) ) {
                if(@unlink($filePath)){
                    $data =array('RAA_FILE_URL'=>'','RAA_USER_FILE_NAME'=>'');
                    $arrWhere = array('RAA_PROJECT_ID'=>$RAA_PROJECT_ID);
                    @$this->db->update('mi__t_raa_project', $data, $arrWhere);
                    array_push($this->message, getMyArray(true, 'File Deleted'));
                    echo createJSONResponse( $this->message );
                    return;
                }
            }
        }
    }
}