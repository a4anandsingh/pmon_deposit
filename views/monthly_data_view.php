<?php
//showArrayValues($arrBlockData);exit;
 $status_options = array('', '', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 'Dropped');
function getMyStatus($type, $Sel=0, $prevMonthStatus=0){
	$myOptions ='';
	if($type==0){
		$statusOptionValues = array('Please Select'=>0, 'NA'=>1, 'Ongoing'=>3, 'Stopped'=>4, 'Completed'=>5);
	}else{
		$statusOptionValues = array('Please Select'=>0, 'NA'=>1, 'Not Started'=>2, 'Ongoing'=>3, 'Stopped'=>4, 'Completed'=>5);
	}
	switch($prevMonthStatus){
    	case '': //prev month not exists
			$filteredStatus = array('Please Select', 'Ongoing', 'Stopped');
			break;
		case 2: //Not started
			$filteredStatus = array('Please Select', 'Not Started', 'Ongoing', 'Stopped');
			break;
		case 3: //Ongoing
			$filteredStatus = array('Please Select', 'Ongoing', 'Stopped', 'Completed');
			break;
		case 4: //Stopped
			$filteredStatus = array('Please Select', 'Ongoing', 'Stopped');
			break;
		default : 
			$filteredStatus = array('Please Select', 'Not Started', 'Ongoing', 'Stopped', 'Completed');
			break;
		/*case 5: //completed
			$filteredStatus = array('Please Select', 'NA', 'Stopped');
			break;*/
	}
	//if project status
	if($type==0){
		$statusOptionValues['Dropped']=6;
		array_push($filteredStatus, 'Dropped');
	}
	for($i=0;$i<count($filteredStatus);$i++){
		$selText = '';
		$statusValueFromKey = $statusOptionValues[ $filteredStatus[$i] ];
		//if current month doesn't have status
		//if($Sel==0){
			/*if($statusValueFromKey==$prevMonthStatus)
				$selText = 'selected="selected"';*/
		//}else{
			if($Sel==$statusValueFromKey)
				$selText = 'selected="selected"';
		//}
		$myOptions .= '<option value="'.$statusValueFromKey.'" '.$selText.'>'.
			$filteredStatus[$i].
			'</option>';
	}
	return $myOptions;
}?>
<form id="frmMonthly" name="frmMonthly" method="post" action="">
<?php $mon = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug','Sep', 'Oct', 'Nov', 'Dec');
$arrForValidationControls = array();
$arrForValidation = array();
$arrForValidationMessage = array();
  //if($this->PROJECT_ID==4546){   showArrayValues($currentMonthRecord);exit;}
  ?>
<input type="hidden" id="PROJECT_NAME" name="PROJECT_NAME" value="<?php echo $PROJECT_NAME;?>" />
<input type="hidden" name="PROJECT_ID" id="PROJECT_ID" value="<?php echo $PROJECT_ID;?>" />
<input type="hidden" name="MONTHLY_DATA_ID" id="MONTHLY_DATA_ID" value="<?php echo $currentMonthRecord['MONTHLY_DATA_ID'];?>" />
<input type="hidden" name="MONTH_DATE" id="MONTH_DATE" value="<?php echo $MONTH_DATE;?>" />
<input type="hidden" name="START_MONTH_DATE" id="START_MONTH_DATE" value="<?php echo date("d-m-Y", $MONTH_DATE);?>" />
<input type="hidden" name="END_MONTH_DATE" id="END_MONTH_DATE" value="<?php echo date("t-m-Y", $MONTH_DATE);?>" />
<input type="hidden" name="SESSION_ID" id="SESSION_ID" value="<?php echo $SESSION_ID;?>" />
<div class="panel panel-primary">
<!-- Default panel contents -->
<div class="panel-heading">
    <strong><big><big>Monthly Entry ( <?php echo date('F Y', $MONTH_DATE);?> )</big>
    <br />
    <?php echo $PROJECT_NAME. '</big><br />Code : '.$PROJECT_CODE;?></strong>
</div>
<div class="panel-body">
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tr>
    <td nowrap="nowrap" class="ui-state-default"><strong>Status of Scheme</strong></td>
    <td class="ui-widget-content" style="font-weight:900">
     <?php //echo $currentMonthRecord['PROJECT_STATUS'].', '.$previousMonthRecord['PROJECT_STATUS'];?>
        <select name="PROJECT_STATUS" id="PROJECT_STATUS" class="chosen-select"
            style="width:150px" onchange="setProjectStatus(this.value)">
        <?php echo getMyStatus(0, $currentMonthRecord['PROJECT_STATUS'], $previousMonthRecord['PROJECT_STATUS']);?>
        </select>
    </td>
    <td class="ui-state-default">Proposed Completion Date</td>
    <td align="center" class="ui-widget-content"> As Per Target
      <span class="label label-warning" style="float:center">
        <strong><?php echo myDateFormat($ACTUAL_COMPLETION_DATE);?></strong>
        <input type="hidden" id="ACTUAL_COMPLETION_DATE" value="<?php echo $ACTUAL_COMPLETION_DATE;?>" />
        </span>
	</td>
</tr>
<tr>
    <td colspan="5" nowrap="nowrap" class="ui-widget-content">
		<?php 
		$remarkDiv = 'none';
		$staDiv = 'none';
		switch($currentMonthRecord['PROJECT_STATUS']){
			case 4:
			case 2:
			case 5:
			case 6:	$remarkDiv = 'block'; break;
			case 3: if( strtotime($ACTUAL_COMPLETION_DATE)<=$MONTH_DATE) $remarkDiv = 'block'; break;
		}
		//echo $ACTUAL_COMPLETION_DATE.'<='.$MONTH_DATE;
		switch($currentMonthRecord['PROJECT_STATUS']){
			case 5:
			case 6:	$staDiv = 'block'; break;
		}?>
		<table width="100%" border="0" cellpadding="10" cellspacing="2" id="divStatus" style="display:<?php echo $staDiv;?>">
        <tr>
	        <td width="140px" align="left" nowrap="nowrap" class="ui-state-default">
                <div id="completedDateCaption" style="font-weight:bold">
                	Completion Date
                </div>
            </td>
	        <td align="center" class="ui-widget-content">
                <div id="completionDiv" style="display:<?php echo $staDiv;?>">
                    <input name="COMPLETION_DATE" type="text" class="centertext" id="COMPLETION_DATE" 
                    value="<?php echo myDateFormat($currentMonthRecord['COMPLETION_DATE']);?>" size="16" 
                    maxlength="10" style="font-weight:900" />
                </div>
            </td>
            <td align="left" class="ui-state-default">
                <div id="completedNoCaption" style="font-weight:bold">
                	Completion Certificate No
                </div>
            </td>
	        <td align="center" class="ui-widget-content">
				<div id="completionDiv1" style="display:<?php echo $staDiv;?>">
                    <input id="PROJECT_STATUS_DISPATCH_NO" name="PROJECT_STATUS_DISPATCH_NO" 
                        type="text" value="<?php echo $monthlyStatusData['PROJECT_STATUS_DISPATCH_NO'];?>"
                          style="width:97%;" maxlength="100" />
				</div>
            </td>
        </tr>
        <tr>
	        <td align="center" nowrap="nowrap" class="ui-widget-content" colspan="4">
                <div id="divCompletionType" style="display:<?php echo ($currentMonthRecord['PROJECT_STATUS']==5)? '':'none';?>">
                    <div id="radioset">
                    <?php /*$completionData = array('COMPLETION_TYPE'=>1, 
						'LA_PAYMENT'=>0, 'FA_PAYMENT'=>0, 'CONTRACTOR_LIABILITY'=>0);*/
						$chkContLiability = 0;
						if($EWORK_PAYMENT_STATUS ==1){
							$chkContLiability = 1;
							$monthlyStatusData['CL_PAYMENT']=1;
						}
						?>					
                        <input type="radio" id="radio1" name="COMPLETION_TYPE" value="1" 
	                        onclick="checkCompletionType(this.value)"
                        <?php echo ($monthlyStatusData['COMPLETION_TYPE']==1)? 'checked="checked"':'';?> />
                        <label for="radio1">Physically & Financially Completed</label>

                        <input type="radio" id="radio2" name="COMPLETION_TYPE" value="2" 
                        	onclick="checkCompletionType(this.value)"
                        <?php echo ($monthlyStatusData['COMPLETION_TYPE']==2)? 'checked="checked"':'';?> />
                        <label for="radio2">Physically Completed but Financially not Completed</label>
                    </div>
                    <div id="divReasonsOfIncompletion" style="display:<?php 
						echo ($monthlyStatusData['COMPLETION_TYPE']==2)? '':'none';?> "> 
                        <strong>Reasons for Incompletion :</strong>
                    <input type="checkbox" id="LA_PAYMENT" name="LA_PAYMENT" 
                        value="1" class="css-checkbox"
                    <?php echo ($monthlyStatusData['LA_PAYMENT']==1)?'checked="checked"':'';?> />
                    <label for="LA_PAYMENT" class="css-label lite-red-check"><strong>LA Payment</strong></label>
                    
                    <input type="checkbox" id="FA_PAYMENT" name="FA_PAYMENT" 
                        value="1" class="css-checkbox"
                    <?php echo ($monthlyStatusData['FA_PAYMENT']==1)?'checked="checked"':'';?> />
                    <label for="FA_PAYMENT" class="css-label lite-red-check"><strong>FA Payment</strong></label>
                    
                    <input type="checkbox" id="CL_PAYMENT" name="CL_PAYMENT" 
                        value="1" class="css-checkbox"
					<?php echo ($chkContLiability == 1) ? ' onclick="return false;"' : ''; ?>
                    <?php echo ($monthlyStatusData['CL_PAYMENT']==1)?'checked="checked"':'';?> />
                    <label for="CL_PAYMENT" class="css-label lite-red-check">
                    	<strong>Liabilities of Contractor</strong></label>
                    </div>
               </div>
            </td>
            </tr>
            </table>
            
            <table width="100%" border="0" cellpadding="3" cellspacing="2" 
            	id="divRemarks" style="display:<?php echo $remarkDiv;?>">
            <tr>
            <td align="left" class="ui-state-default" width="140px">
                <div id="completedCaption" style="font-weight:bold">
					Project Status Remark 
                </div>
            </td>
	        <td align="left" class="ui-widget-content">
                <div id="remarkDiv" style="display:<?php echo $remarkDiv;?>">
                    <textarea name="PROJECT_STATUS_REMARK" id="PROJECT_STATUS_REMARK" 
                            rows="2"  style="width:97%"
                       ><?php echo $monthly_remarks['PROJECT_STATUS_REMARK'];?></textarea>
                </div>
            </td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td nowrap="nowrap" class="ui-state-default"><strong>Alloted Budget  </strong></td>
    <td class="ui-widget-content">
		<input type="text" value="<?php echo $BUDGET_AMOUNT;?>" 
            maxlength="10" size="16" readonly="readonly" class="righttext" 
            id="ALLOCATED_BUDGET" name="ALLOCATED_BUDGET" style="font-weight:900" />
        for this Financial Year
	</td>
    <td colspan="2" class="ui-widget-content" align="center">
    	<strong>(Please change the yearly target in case the Budget amount changes)</strong><br />
		<?php //echo getButton('Modify Yearly Target', 'showTarget()', 4, 'cus-target');?>
	</td>
</tr>
</table>
<div class="wrdlinebreak"></div>
<?php 
//showArrayValues($currentMonthRecord);
//showArrayValues($totalInCurrentFY);
$arrMonthly = array(
	array(
		'SNO'=>1,
		'LIST_VALUE'=>'a',
		'TITLE'=>'Expenditure Total', 
		'UNIT'=>'Rs. Lacs',
		'CM_NAME'=> 'EXPENDITURE_TOTAL',
		'CM_VALUE'=> $currentMonthRecord['EXPENDITURE_TOTAL'],
		'PM_NAME'=>'EXPENDITURE_TOTAL_P', 
		'PM_VALUE'=> $previousMonthRecord['EXPENDITURE_TOTAL'], 
		'CFY_NAME'=>'EXPENDITURE_TOTAL_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['EXPENDITURE_TOTAL']) + floatval($currentMonthRecord['EXPENDITURE_TOTAL'])),
		'HCFY_NAME'=>'EXPENDITURE_TOTAL_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['EXPENDITURE_TOTAL'],
		'TLY_NAME'=>'EXPENDITURE_TOTAL_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['EXPENDITURE_TOTAL'],
		'SHOW'=>0,
		'ESTI_VALUE'=>$estimationRecord['EXPENDITURE_TOTAL']
		),
	array(
		'SNO'=>2,
		'LIST_VALUE'=>'b',
		'TITLE'=>'Expenditure Works', 
		'UNIT'=>'Rs. Lacs',
		'CM_NAME'=> 'EXPENDITURE_WORKS',
		'CM_VALUE'=> $currentMonthRecord['EXPENDITURE_WORKS'],
		'PM_NAME'=>'EXPENDITURE_WORKS_P', 
		'PM_VALUE'=> $previousMonthRecord['EXPENDITURE_WORKS'], 
		'CFY_NAME'=>'EXPENDITURE_WORKS_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['EXPENDITURE_WORKS']) + floatval($currentMonthRecord['EXPENDITURE_WORKS'])),
		'HCFY_NAME'=>'EXPENDITURE_WORKS_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['EXPENDITURE_WORKS'],
		'TLY_NAME'=>'EXPENDITURE_WORKS_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['EXPENDITURE_WORKS'],
		'SHOW'=>0,
		'ESTI_VALUE'=>$estimationRecord['EXPENDITURE_WORK']
	), 
	array(
		'SNO'=>3,
		'LIST_VALUE'=>'a',
		'TITLE'=>'Land aq cases submitted', 
		'UNIT'=>'Numbers',
		'CM_NAME'=> 'LA_NO',
		'CM_VALUE'=> $currentMonthRecord['LA_NO'] ,
		'PM_NAME'=>'LA_NO_P', 
		'PM_VALUE'=> $previousMonthRecord['LA_NO'], 
		'CFY_NAME'=>'LA_NO_CFY',
		'CFY_VALUE'=> (intval($totalInCurrentFY['LA_NO']) + intval($currentMonthRecord['LA_NO'])),
		'HCFY_NAME'=>'LA_NO_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['LA_NO'],
		'TLY_NAME'=>'LA_NO_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['LA_NO'],
		'SHOW'=> $setupData['LA_NA'],
		'ESTI_VALUE'=>$estimationRecord['LA_NO']
	),
	array(
		'SNO'=>4,
		'LIST_VALUE'=>'b',
		'TITLE'=>'Land aq cases submitted', 
		'UNIT'=>'Hectares',
		'CM_NAME'=> 'LA_HA',
		'CM_VALUE'=> $currentMonthRecord['LA_HA'],
		'PM_NAME'=>'LA_HA_P', 
		'PM_VALUE'=> $previousMonthRecord['LA_HA'], 
		'CFY_NAME'=>'LA_HA_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['LA_HA']) + floatval($currentMonthRecord['LA_HA'])),
		'HCFY_NAME'=>'LA_HA_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['LA_HA'],
		'TLY_NAME'=>'LA_HA_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['LA_HA'],
		'SHOW'=> $setupData['LA_NA'],
		'ESTI_VALUE'=>$estimationRecord['LA_HA']
	),
	array(
		'SNO'=>5,
		'LIST_VALUE'=>'c',
		'TITLE'=>'Land aq cases completed', 
		'UNIT'=>'Numbers',
		'CM_NAME'=> 'LA_COMPLETED_NO' ,
		'CM_VALUE'=> $currentMonthRecord['LA_COMPLETED_NO'],
		'PM_NAME'=>'LA_COMPLETED_NO_P', 
		'PM_VALUE'=> $previousMonthRecord['LA_COMPLETED_NO'],
		'CFY_NAME'=>'LA_COMPLETED_NO_CFY',
		'CFY_VALUE'=> (intval($totalInCurrentFY['LA_COMPLETED_NO']) + intval($currentMonthRecord['LA_COMPLETED_NO'])),
		'HCFY_NAME'=>'LA_COMPLETED_NO_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['LA_COMPLETED_NO'],
		'TLY_NAME'=>'LA_COMPLETED_NO_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['LA_COMPLETED_NO'],
		'SHOW'=> $setupData['LA_NA'],
		'ESTI_VALUE'=>(intval($totalInCurrentFY['LA_NO']) + intval($currentMonthRecord['LA_NO']) + intval($achievementTillLastFY['LA_NO']))
	),
	array(
		'SNO'=>6,
		'LIST_VALUE'=>'d',
		'TITLE'=>'Land aq cases completed', 
		'UNIT'=>'Hectares',
		'CM_NAME'=> 'LA_COMPLETED_HA' ,
		'CM_VALUE'=> $currentMonthRecord['LA_COMPLETED_HA'],
		'PM_NAME'=>'LA_COMPLETED_HA_P', 
		'PM_VALUE'=> $previousMonthRecord['LA_COMPLETED_HA'], 
		'CFY_NAME'=>'LA_COMPLETED_HA_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['LA_COMPLETED_HA'])+floatval($currentMonthRecord['LA_COMPLETED_HA'])),
		'HCFY_NAME'=>'LA_COMPLETED_HA_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['LA_COMPLETED_HA'],
		'TLY_NAME'=>'LA_COMPLETED_HA_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['LA_COMPLETED_HA'],
		'SHOW'=> $setupData['LA_NA'],
		'ESTI_VALUE'=>(floatval($totalInCurrentFY['LA_HA']) + floatval($currentMonthRecord['LA_HA'])+floatval($achievementTillLastFY['LA_HA'])),
	),
	array(
		'SNO'=>7,
		'LIST_VALUE'=>'e',
		'TITLE'=>'Forest cases submitted', 
		'UNIT'=>'Hectares',
		'CM_NAME'=> 'FA_HA' ,
		'CM_VALUE'=> $currentMonthRecord['FA_HA'],
		'PM_NAME'=>'FA_HA_P', 
		'PM_VALUE'=> $previousMonthRecord['FA_HA'], 
		'CFY_NAME'=>'FA_HA_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['FA_HA'])+floatval($currentMonthRecord['FA_HA'])),
		'HCFY_NAME'=>'FA_HA_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['FA_HA'],
		'TLY_NAME'=>'FA_HA_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['FA_HA'],
		'SHOW'=> $setupData['FA_NA'],
		'ESTI_VALUE'=>$estimationRecord['FA_HA']
	),
	array(
		'SNO'=>8,
		'LIST_VALUE'=>'f',
		'TITLE'=>'Forest cases completed', 
		'UNIT'=>'Hectares',
		'CM_NAME'=> 'FA_COMPLETED_HA',
		'CM_VALUE'=> $currentMonthRecord['FA_COMPLETED_HA'],
		'PM_NAME'=>'FA_COMPLETED_HA_P', 
		'PM_VALUE'=> $previousMonthRecord['FA_COMPLETED_HA'], 
		'CFY_NAME'=>'FA_COMPLETED_HA_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['FA_COMPLETED_HA'])+floatval($currentMonthRecord['FA_COMPLETED_HA'])),
		'HCFY_NAME'=>'FA_COMPLETED_HA_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['FA_COMPLETED_HA'],
		'TLY_NAME'=>'FA_COMPLETED_HA_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['FA_COMPLETED_HA'],
		'SHOW'=> $setupData['FA_NA'],
		'ESTI_VALUE'=>(floatval($totalInCurrentFY['FA_HA'])+floatval($currentMonthRecord['FA_HA'])+floatval($achievementTillLastFY['FA_HA']))
	),                            
	array(
		'SNO'=>9,
		'LIST_VALUE'=>'g',
		'TITLE'=>'Headworks Earthwork', 
		'UNIT'=>'Th Cum',
		'CM_NAME'=> 'HEAD_WORKS_EARTHWORK',
		'CM_VALUE'=> $currentMonthRecord['HEAD_WORKS_EARTHWORK'],
		'PM_NAME'=>'HEAD_WORKS_EARTHWORK_P', 
		'PM_VALUE'=> $previousMonthRecord['HEAD_WORKS_EARTHWORK'], 
		'CFY_NAME'=>'HEAD_WORKS_EARTHWORK_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['HEAD_WORKS_EARTHWORK'])+floatval($currentMonthRecord['HEAD_WORKS_EARTHWORK'])),
		'HCFY_NAME'=>'HEAD_WORKS_EARTHWORK_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['HEAD_WORKS_EARTHWORK'],
		'TLY_NAME'=>'HEAD_WORKS_EARTHWORK_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['HEAD_WORKS_EARTHWORK'],
		'SHOW'=> $setupData['HEAD_WORKS_EARTHWORK_NA'],
		'ESTI_VALUE'=>$estimationRecord['HEAD_WORKS_EARTHWORK']
	),                            
	array(
		'SNO'=>10,
		'LIST_VALUE'=>'h',
		'TITLE'=>'Headworks Masonry/Concrete', 
		'UNIT'=>'Th Cum',
		'CM_NAME'=> 'HEAD_WORKS_MASONRY',
		'CM_VALUE'=> $currentMonthRecord['HEAD_WORKS_MASONRY'],
		'PM_NAME'=>'HEAD_WORKS_MASONRY_P', 
		'PM_VALUE'=> $previousMonthRecord['HEAD_WORKS_MASONRY'], 
		'CFY_NAME'=>'HEAD_WORKS_MASONRY_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['HEAD_WORKS_MASONRY'])+floatval($currentMonthRecord['HEAD_WORKS_MASONRY'])),
		'HCFY_NAME'=>'HEAD_WORKS_MASONRY_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['HEAD_WORKS_MASONRY'],
		'TLY_NAME'=>'HEAD_WORKS_MASONRY_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['HEAD_WORKS_MASONRY'],
		'SHOW'=> $setupData['HEAD_WORKS_MASONRY_NA'],
		'ESTI_VALUE'=>$estimationRecord['HEAD_WORKS_MASONRY']
	),
	array(
		'SNO'=>11,
		'LIST_VALUE'=>'h',
		'TITLE'=>'Steel Works', 
		'UNIT'=>'Metric Tonn',
		'CM_NAME'=> 'STEEL_WORKS',
		'CM_VALUE'=> $currentMonthRecord['STEEL_WORKS'],
		'PM_NAME'=>'STEEL_WORKS_P', 
		'PM_VALUE'=> $previousMonthRecord['STEEL_WORKS'], 
		'CFY_NAME'=>'STEEL_WORKS_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['STEEL_WORKS'])+floatval($currentMonthRecord['STEEL_WORKS'])),
		'HCFY_NAME'=>'STEEL_WORKS_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['STEEL_WORKS'],
		'TLY_NAME'=>'STEEL_WORKS_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['STEEL_WORKS'],
		'SHOW'=> $setupData['STEEL_WORKS_NA'],
		'ESTI_VALUE'=>$estimationRecord['STEEL_WORKS']
	),
	array(
		'SNO'=>12,
		'LIST_VALUE'=>'i',
		'TITLE'=>'Canals Earthwork', 
		'UNIT'=>'Th Cum',
		'CM_NAME'=> 'CANAL_EARTHWORK',
		'CM_VALUE'=> $currentMonthRecord['CANAL_EARTHWORK'],
		'PM_NAME'=>'CANAL_EARTHWORK_P', 
		'PM_VALUE'=> $previousMonthRecord['CANAL_EARTHWORK'], 
		'CFY_NAME'=>'CANAL_EARTHWORK_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['CANAL_EARTHWORK'])+floatval($currentMonthRecord['CANAL_EARTHWORK'])),
		'HCFY_NAME'=>'CANAL_EARTHWORK_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['CANAL_EARTHWORK'],
		'TLY_NAME'=>'CANAL_EARTHWORK_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['CANAL_EARTHWORK'],
		'SHOW'=> $setupData['CANAL_EARTHWORK_NA'],
		'ESTI_VALUE'=>$estimationRecord['CANAL_EARTHWORK']
	),
	array(
		'SNO'=>13,
		'LIST_VALUE'=>'j',
		'TITLE'=>'Canals Lining', 
		'UNIT'=>'Km',
		'CM_NAME'=> 'CANAL_LINING',
		'CM_VALUE'=> $currentMonthRecord['CANAL_LINING'],
		'PM_NAME'=>'CANAL_LINING_P', 
		'PM_VALUE'=> $previousMonthRecord['CANAL_LINING'], 
		'CFY_NAME'=>'CANAL_LINING_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['CANAL_LINING'])+floatval($currentMonthRecord['CANAL_LINING'])),
		'HCFY_NAME'=>'CANAL_LINING_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['CANAL_LINING'],
		'TLY_NAME'=>'CANAL_LINING_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['CANAL_LINING'],
		'SHOW'=> $setupData['CANAL_LINING_NA'],
		'ESTI_VALUE'=>$estimationRecord['CANAL_LINING']
	),
	array(
		'SNO'=>14,
		'LIST_VALUE'=>'k',
		'TITLE'=>'Canals Structures', 
		'UNIT'=>'Numbers',
		'CM_NAME'=> 'CANAL_STRUCTURES',
		'CM_VALUE'=> $currentMonthRecord['CANAL_STRUCTURES'],
		'PM_NAME'=>'CANAL_STRUCTURES_P', 
		'PM_VALUE'=> $previousMonthRecord['CANAL_STRUCTURES'], 
		'CFY_NAME'=>'CANAL_STRUCTURES_CFY',
		'CFY_VALUE'=> (intval($totalInCurrentFY['CANAL_STRUCTURES'])+intval($currentMonthRecord['CANAL_STRUCTURES'])),
		'HCFY_NAME'=>'CANAL_STRUCTURES_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['CANAL_STRUCTURES'],
		'TLY_NAME'=>'CANAL_STRUCTURES_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['CANAL_STRUCTURES'],
		'SHOW'=> $setupData['CANAL_STRUCTURES_NA'],
		'ESTI_VALUE'=>$estimationRecord['CANAL_STRUCTURES']
	),
	array(
		'SNO'=>15,
		'LIST_VALUE'=>'l',
		'TITLE'=>'Canals Masonry', 
		'UNIT'=>'Th.Cum',
		'CM_NAME'=> 'CANAL_MASONRY',
		'CM_VALUE'=> $currentMonthRecord['CANAL_MASONRY'],
		'PM_NAME'=>'CANAL_MASONRY_P', 
		'PM_VALUE'=> $previousMonthRecord['CANAL_MASONRY'], 
		'CFY_NAME'=>'CANAL_MASONRY_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['CANAL_MASONRY'])+floatval($currentMonthRecord['CANAL_MASONRY'])),
		'HCFY_NAME'=>'CANAL_MASONRY_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['CANAL_MASONRY'],
		'TLY_NAME'=>'CANAL_MASONRY_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['CANAL_MASONRY'],
		'SHOW'=> $setupData['CANAL_STRUCTURES_NA'],
		'ESTI_VALUE'=>$estimationRecord['CANAL_MASONRY']
	),
	array(
		'SNO'=>16,
		'LIST_VALUE'=>'m',
		'TITLE'=>'Road Works', 
		'UNIT'=>'Km',
		'CM_NAME'=> 'ROAD_WORKS',
		'CM_VALUE'=> $currentMonthRecord['ROAD_WORKS'],
		'PM_NAME'=>'ROAD_WORKS_P', 
		'PM_VALUE'=> $previousMonthRecord['ROAD_WORKS'], 
		'CFY_NAME'=>'ROAD_WORKS_CFY',
		'CFY_VALUE'=> (floatval($totalInCurrentFY['ROAD_WORKS'])+floatval($currentMonthRecord['ROAD_WORKS'])),
		'HCFY_NAME'=>'ROAD_WORKS_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['ROAD_WORKS'],
		'TLY_NAME'=>'ROAD_WORKS_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['ROAD_WORKS'],
		'SHOW'=> $setupData['ROAD_WORKS_NA'],
		'ESTI_VALUE'=>$estimationRecord['ROAD_WORKS']
	),
	array(
		'SNO'=>17,
		'LIST_VALUE'=>'m',
		'TITLE'=>'Irrigation Potential Created', 
		'UNIT'=>'Hectares',
		'CM_NAME'=> 'IRRIGATION_POTENTIAL',
		'CM_VALUE'=> $currentMonthRecord['IRRIGATION_POTENTIAL'],
		'PM_NAME'=>'IRRIGATION_POTENTIAL_P', 
		'PM_VALUE'=> $previousMonthRecord['IRRIGATION_POTENTIAL'], 
		'CFY_NAME'=>'IRRIGATION_POTENTIAL_CFY',
		'CFY_VALUE'=> (intval($totalInCurrentFY['IRRIGATION_POTENTIAL']) + intval($currentMonthRecord['IRRIGATION_POTENTIAL'])),
		'HCFY_NAME'=>'IRRIGATION_POTENTIAL_CFY_H',
		'HCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL'],
		'TLY_NAME'=>'IRRIGATION_POTENTIAL_TLY',
		'TLY_VALUE'=> $achievementTillLastFY['IRRIGATION_POTENTIAL'],
		'SHOW'=> $setupData['IRRIGATION_POTENTIAL_NA'],
		'ESTI_VALUE'=>$estimationRecord['IRRIGATION_POTENTIAL'],
		'KHARIF'=>array(
			'CM_NAME'=> 'IRRIGATION_POTENTIAL_KHARIF',
			'CM_VALUE'=> $currentMonthRecord['IRRIGATION_POTENTIAL_KHARIF'],
			'PM_NAME'=>'IRRIGATION_POTENTIAL_KHARIF_P', 
			'PM_VALUE'=> $previousMonthRecord['IRRIGATION_POTENTIAL_KHARIF'], 
			'CFY_NAME'=>'IRRIGATION_POTENTIAL_KHARIF_CFY',
			'CFY_VALUE'=> (intval($totalInCurrentFY['IRRIGATION_POTENTIAL_KHARIF']) + 
			intval($currentMonthRecord['IRRIGATION_POTENTIAL_KHARIF'])),
			'HCFY_NAME'=>'IRRIGATION_POTENTIAL_KHARIF_CFY_H',
			'HCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL_KHARIF'],
			'TLY_NAME'=>'IRRIGATION_POTENTIAL_KHARIF_TLY',
			'TLY_VALUE'=> $achievementTillLastFY['IRRIGATION_POTENTIAL_KHARIF'],
			'ESTI_VALUE'=>$estimationRecord['IRRIGATION_POTENTIAL_KHARIF']
		),
		'RABI'=>array(
			'CM_NAME'=> 'IRRIGATION_POTENTIAL_RABI',
			'CM_VALUE'=> $currentMonthRecord['IRRIGATION_POTENTIAL_RABI'],
			'PM_NAME'=>'IRRIGATION_POTENTIAL_RABI_P', 
			'PM_VALUE'=> $previousMonthRecord['IRRIGATION_POTENTIAL_RABI'], 
			'CFY_NAME'=>'IRRIGATION_POTENTIAL_RABI_CFY',
			'CFY_VALUE'=> (intval($totalInCurrentFY['IRRIGATION_POTENTIAL_RABI']) + 
			intval($currentMonthRecord['IRRIGATION_POTENTIAL_RABI'])),
			'HCFY_NAME'=>'IRRIGATION_POTENTIAL_RABI_CFY_H',
			'HCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL_RABI'],
			'TLY_NAME'=>'IRRIGATION_POTENTIAL_RABI_TLY',
			'TLY_VALUE'=> $achievementTillLastFY['IRRIGATION_POTENTIAL_RABI'],
			'ESTI_VALUE'=>$estimationRecord['IRRIGATION_POTENTIAL_RABI']
		)
	)
);
$arrMyFields = array();
$arrForValidation = array();
$arrForValidationMessage = array();
$contentOne = '';
$arrDataFields = array();
$arrCanalFields = array('CANAL_EARTHWORK', 'CANAL_LINING', 'CANAL_STRUCTURES');
$arrCanalStatusFields = array(
	'CANAL_EARTHWORK'=>'CANAL_EARTH_WORK_STATUS', 
	'CANAL_LINING'=>'CANAL_LINING_STATUS', 
	'CANAL_STRUCTURES'=>'CANAL_STRUCTURE_STATUS'
);
$arrIntFields = array('LA_NO', 'LA_COMPLETED_NO', 'CANAL_STRUCTURES');
//showArrayValues($ESTIMATION_DATA);
//showArrayValues($arrMonthly);
$arrBenefitedBlocks = array();
$contentIPTotal = '';
$iSNoOfIPRow = 17;
//$isEstimationExists = false;
//showArrayValues($arrBlockData);
foreach($arrMonthly as $arrM){
	$sno = (($arrM['SNO']>=3)? ($arrM['SNO']-2):$arrM['SNO']);
	if($arrM['SNO']==$iSNoOfIPRow) {
		$contentOne .= '<tr>
			<td class="ui-widget-content" align="center"><strong>'.$sno.'</strong></td>
			<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
			';
		if($arrM['SHOW']==1){
			$contentOne .='
				<td align="center" class="ui-widget-content"></td>
				<td align="center" class="ui-widget-content">NA</td>
				<td align="center" class="ui-widget-content">NA</td>
				<td align="center" class="ui-widget-content">NA</td>
				<td align="center" class="ui-widget-content">NA</td>
				<td align="center" class="ui-widget-content">NA</td>
				<td align="center" class="ui-widget-content">NA</td>
			</tr>';
		}else{
			$contentOne .='
				<td align="center" class="ui-widget-content"></td>
				<td align="center" class="ui-widget-content"></td>
				<td align="center" class="ui-widget-content"></td>
				<td align="center" class="ui-widget-content"></td>
				<td align="center" class="ui-widget-content"></td>
				<td align="center" class="ui-widget-content"></td>
				<td align="center" class="ui-widget-content"></td>
			</tr>';
			$blockData = '';
			$iBCount = 97;
			
			foreach($arrBlockData as $k=>$v){
				//echo 'hhhh';showArrayValues($v);
				array_push($arrBenefitedBlocks, $k);
				$keyup = ' onkeyup="calculateSubIrri('.$k.')" ';
				if($isEstimationExists){
					$estik = $v['ESTIMATION_IP']['KHARIF'].
						'<input type="hidden" name="BLOCK_EIP_K['.$k.']" id="BLOCK_EIP_K_'.$k.'"  
							 value="'.$v['ESTIMATION_IP']['KHARIF'].'" />';
					$estir = $v['ESTIMATION_IP']['RABI'].
						'<input type="hidden" name="BLOCK_EIP_R['.$k.']" id="BLOCK_EIP_R_'.$k.'"  
							 value="'.$v['ESTIMATION_IP']['RABI'].'" />';
					$esti = $v['ESTIMATION_IP']['IP'].
						'<input type="hidden" name="BLOCK_EIP_T['.$k.']" id="BLOCK_EIP_T_'.$k.'"  
							 value="'.$v['ESTIMATION_IP']['IP'].'" />';
				}else{
					$estik = '<input type="text"  name="BLOCK_EIP_K['.$k.']" id="BLOCK_EIP_K_'.$k.'"  
						size="10" maxlength="15" class="centertext" '.$keyup.
						' value="'.$v['ESTIMATION_IP']['KHARIF'].'" />';
					$estir = '<input type="text"  name="BLOCK_EIP_R['.$k.']" id="BLOCK_EIP_R_'.$k.'"  
						size="10" maxlength="15" class="centertext" '.$keyup.
						' value="'.$v['ESTIMATION_IP']['RABI'].'" />';
					$esti = '<input type="hidden" name="BLOCK_EIP_T['.$k.']" id="BLOCK_EIP_T_'.$k.'"  
						value="'.$v['ESTIMATION_IP']['IP'].'" /><div id="divBLOCK_EIP_T_'.$k.'">'.$v['ESTIMATION_IP']['IP'].'</div>';
				}
				
				//showArrayValues($v);
				$blockData .= '<tr>
					<td class="ui-widget-content" align="center" rowspan="3">'.chr($iBCount++).'</td>
					<td class="ui-widget-content" colspan="2" rowspan="3">'.$v['BLOCK_NAME'].'</td>
					<td class="ui-widget-content">
						Kharif
						<input type="hidden" id="BLOCK_PIP_K_'.$k.'" value="'.$v['PREV_MONTH_IP']['KHARIF'].'" />
						<input type="hidden" id="BLOCK_PIP_R_'.$k.'" value="'.$v['PREV_MONTH_IP']['RABI'].'" />
						
						<input type="hidden" id="BLOCK_CIP_K_'.$k.'" value="'.$v['ACHIEVEMENT_IP_CFY']['KHARIF'].'" />
						<input type="hidden" id="BLOCK_CIP_R_'.$k.'" value="'.$v['ACHIEVEMENT_IP_CFY']['RABI'].'" />

						<input type="hidden" id="BLOCK_TIP_K_'.$k.'" value="'.$v['ACHIEVEMENT_IP_TLY']['KHARIF'].'" />
						<input type="hidden" id="BLOCK_TIP_R_'.$k.'" value="'.$v['ACHIEVEMENT_IP_TLY']['RABI'].'" />
                        <input type="hidden" id="BLOCK_SIP_K_'.$k.'" value="'.($v['ACHIEVEMENT_IP_TLY']['KHARIF']+$v['ACHIEVEMENT_IP_CFY']['KHARIF']).'" />
						<input type="hidden" id="BLOCK_SIP_R_'.$k.'" value="'.($v['ACHIEVEMENT_IP_TLY']['RABI']+$v['ACHIEVEMENT_IP_CFY']['RABI']).'" />
					</td>
					<td align="center" class="ui-widget-content">'.$estik.'</td>
					<td align="center" class="ui-widget-content">
						<input type="text"  name="BLOCK_IP_K['.$k.']" id="BLOCK_IP_K_'.$k.'"  
							size="10" maxlength="15" class="centertext" '.$keyup.' value="'.$v['CUR_MONTH_IP']['KHARIF'].'" 
							ONKE/>
					</td>
					<td align="center" class="ui-widget-content">'.$v['PREV_MONTH_IP']['KHARIF'].'</td>
					<td align="center" class="ui-widget-content">
						<div id="divBLOCK_CIP_K_'.$k.'" >'.$v['ACHIEVEMENT_IP_CFY']['KHARIF'].'</div>
					</td>
					<td align="center" class="ui-widget-content">'.$v['ACHIEVEMENT_IP_TLY']['KHARIF'].'</td>
					<td align="center" class="ui-widget-content">
						<div id="divBLOCK_SIP_K_'.$k.'" >'.($v['ACHIEVEMENT_IP_CFY']['KHARIF']+$v['ACHIEVEMENT_IP_TLY']['KHARIF']).'</div>
					</td>
					</tr>
					<tr>
					<td class="ui-widget-content"><strong>Rabi</strong></td>
					<td align="center" class="ui-widget-content">'.$estir.'</td>

					<td align="center" class="ui-widget-content">
						<input type="text"  name="BLOCK_IP_R['.$k.']" id="BLOCK_IP_R_'.$k.'"  
							size="10" maxlength="15" class="centertext" '.$keyup.' value="'.$v['CUR_MONTH_IP']['RABI'].'" />
					</td>
					<td align="center" class="ui-widget-content">'.$v['PREV_MONTH_IP']['RABI'].'</td>
					<td align="center" class="ui-widget-content">
						<div id="divBLOCK_CIP_R_'.$k.'" >'.$v['ACHIEVEMENT_IP_CFY']['KHARIF'].'</div>
					</td>
					<td align="center" class="ui-widget-content">'.$v['ACHIEVEMENT_IP_TLY']['RABI'].'</td>
					<td align="center" class="ui-widget-content">
						<div id="divBLOCK_SIP_R_'.$k.'" >'.($v['ACHIEVEMENT_IP_CFY']['RABI']+$v['ACHIEVEMENT_IP_TLY']['RABI']).'</div>
					</td>
					</tr>
					<tr>
					<td class="ui-state-default" ><strong>Total</strong></td>
					<td align="center" class="ui-state-default">'.$esti.'</td>
					<td align="center" class="ui-state-default">
						<input type="hidden" name="BLOCK_IP_T['.$k.']" id="BLOCK_IP_T_'.$k.'" value="'.$v['CUR_MONTH_IP']['IP'].'" />
						<div id="divBLOCK_IP_T_'.$k.'">'.$v['CUR_MONTH_IP']['IP'].'</div>
					</td>
					<td align="center" class="ui-state-default">'.$v['PREV_MONTH_IP']['IP'].'</td>
					<td align="center" class="ui-state-default">
						<div id="divBLOCK_CIP_T_'.$k.'" >'.$v['ACHIEVEMENT_IP_CFY']['IP'].'</div>
					</td>
					<td align="center" class="ui-state-default">'.$v['ACHIEVEMENT_IP_TLY']['IP'].'</td>
					<td align="center" class="ui-state-default">
						<div id="divBLOCK_SIP_T_'.$k.'" >'.$v['ACHIEVEMENT_IP_CFY']['IP'].'</div>
					</td>
					</tr>
					';
				/*
				$blockData .= '<tr>
					<td class="ui-widget-content" align="center" rowspan="3"></td>
					<td class="ui-widget-content" rowspan="3"><strong></strong></td>
					<td class="ui-widget-content" rowspan="3">Total IP</td>
					<td class="ui-widget-content">Kharif</td>
					<td align="center" class="ui-widget-content">'.$estimatedQuantity1.'</td>
					<td align="center" class="ui-widget-content">'.
						'<input type="text" name="'.$arrM['KHARIF']['CM_NAME'].'" 
						id="'.$arrM['KHARIF']['CM_NAME'].'" size="10" maxlength="15"
							'.$keyup. ' 
							 class="righttext" value="'.$arrM['KHARIF']['CM_VALUE'].'" />'.
					'</td>
					<td align="center" class="ui-widget-content">
						<input type="hidden" name="'.$arrM['KHARIF']['PM_NAME'].'" id="'.$arrM['KHARIF']['PM_NAME'].'" size="10" 
							value="'.$arrM['KHARIF']['PM_VALUE'].'" readonly="readonly" class="righttext" />
						<div id="div_'.$arrM['KHARIF']['PM_NAME'].'">'.
						giveComma($arrM['KHARIF']['PM_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-widget-content">
						<input type="hidden" name="'.$arrM['KHARIF']['HCFY_NAME'].'" id="'.$arrM['KHARIF']['HCFY_NAME'].'" 
							value="'.$arrM['KHARIF']['HCFY_VALUE'].'" />
						<input type="hidden" name="'.$arrM['KHARIF']['CFY_NAME'].'" id="'.$arrM['KHARIF']['CFY_NAME'].'" size="10"
							value="'.$arrM['KHARIF']['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['KHARIF']['CFY_NAME'].'">'.
						giveComma($arrM['KHARIF']['CFY_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-widget-content">
						<input type="hidden" name="'.$arrM['KHARIF']['TLY_NAME'].'" id="'.$arrM['KHARIF']['TLY_NAME'].'" size="10"
							value="'.$arrM['KHARIF']['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['KHARIF']['TLY_NAME'].'">'.
						giveComma($arrM['KHARIF']['TLY_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-widget-content">
						<input type="hidden" name="'.$arrM['KHARIF']['CM_NAME'].'_T" id="'.$arrM['KHARIF']['CM_NAME'].'_T" size="10" 
							value="'.($arrM['KHARIF']['TLY_VALUE'] + $arrM['KHARIF']['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['KHARIF']['CM_NAME'].'_T">'.
						giveComma(($arrM['KHARIF']['TLY_VALUE'] + $arrM['KHARIF']['CFY_VALUE']), $decimalPlace) .'</div>
						<input type="hidden" name="'.$arrM['KHARIF']['CM_NAME'].'_E" id="'.$arrM['KHARIF']['CM_NAME'].'_E" 
							value="'.$ESTIMATION_DATA[$arrM['KHARIF']['CM_NAME']].'" />
					</td>
				</tr>
				<tr>
					<td class="ui-widget-content">Rabi</td>
					<td align="center" class="ui-widget-content">'.$estimatedQuantity2.'</td>
					<td align="center" class="ui-widget-content">'.
						'<input type="text" name="'.$arrM['RABI']['CM_NAME'].'" id="'.$arrM['RABI']['CM_NAME'].'" size="10" maxlength="15"
							'.$keyup. ' 
							 class="righttext" value="'.$arrM['RABI']['CM_VALUE'].'" />'.
					'</td>
					<td align="center" class="ui-widget-content">
						<input type="hidden" name="'.$arrM['RABI']['PM_NAME'].'" id="'.$arrM['RABI']['PM_NAME'].'" size="10" 
							value="'.$arrM['RABI']['PM_VALUE'].'" readonly="readonly" class="righttext" />
						<div id="div_'.$arrM['RABI']['PM_NAME'].'">'.giveComma($arrM['RABI']['PM_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-widget-content">
						<input type="hidden" name="'.$arrM['RABI']['HCFY_NAME'].'" id="'.$arrM['RABI']['HCFY_NAME'].'" 
							value="'.$arrM['RABI']['HCFY_VALUE'].'" />
						<input type="hidden" name="'.$arrM['RABI']['CFY_NAME'].'" id="'.$arrM['RABI']['CFY_NAME'].'" size="10"
							value="'.$arrM['RABI']['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['RABI']['CFY_NAME'].'">'.giveComma($arrM['RABI']['CFY_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-widget-content">
						<input type="hidden" name="'.$arrM['RABI']['TLY_NAME'].'" id="'.$arrM['RABI']['TLY_NAME'].'" size="10"
							value="'.$arrM['RABI']['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['RABI']['TLY_NAME'].'">'.giveComma($arrM['RABI']['TLY_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-widget-content">
						<input type="hidden" name="'.$arrM['RABI']['CM_NAME'].'_T" id="'.$arrM['RABI']['CM_NAME'].'_T" size="10" 
							value="'.($arrM['RABI']['TLY_VALUE'] + $arrM['RABI']['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['RABI']['CM_NAME'].'_T">'.giveComma(($arrM['RABI']['TLY_VALUE'] + $arrM['RABI']['CFY_VALUE']), $decimalPlace) .'</div>
						<input type="hidden" name="'.$arrM['RABI']['CM_NAME'].'_E" id="'.$arrM['RABI']['CM_NAME'].'_E" 
							value="'.$ESTIMATION_DATA[$arrM['RABI']['CM_NAME']].'" />
					</td>
				</tr>
					<tr>
					<td class="ui-state-default" >Total</td>
					<td align="center" class="ui-state-default">'.$estimatedQuantity.'</td>
					<td align="center" class="ui-state-default">'.
						'<input type="text" name="'.$arrM['CM_NAME'].'" id="'.$arrM['CM_NAME'].'" 
							size="10" maxlength="15" readonly="readonly" '.$keyup. '   
							 class="righttext" value="'.$arrM['CM_VALUE'].'" />'.
					'</td>
					<td align="center" class="ui-state-default">
						<input type="hidden" name="'.$arrM['PM_NAME'].'" id="'.$arrM['PM_NAME'].'" size="10" 
							value="'.$arrM['PM_VALUE'].'" readonly="readonly" class="righttext" />
						<div id="div_'.$arrM['PM_NAME'].'">'.giveComma($arrM['PM_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-state-default">
						<input type="hidden" name="'.$arrM['HCFY_NAME'].'" id="'.$arrM['HCFY_NAME'].'" 
							value="'.$arrM['HCFY_VALUE'].'" />
						<input type="hidden" name="'.$arrM['CFY_NAME'].'" id="'.$arrM['CFY_NAME'].'" size="10"
							value="'.$arrM['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['CFY_NAME'].'">'.giveComma($arrM['CFY_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-state-default">
						<input type="hidden" name="'.$arrM['TLY_NAME'].'" id="'.$arrM['TLY_NAME'].'" size="10"
							value="'.$arrM['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['TLY_NAME'].'">'.giveComma($arrM['TLY_VALUE'], $decimalPlace).'</div>
					</td>
					<td align="center" class="ui-state-default">
						<input type="hidden" name="'.$arrM['CM_NAME'].'_T" id="'.$arrM['CM_NAME'].'_T" size="10" 
							value="'.($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
						<div id="div_'.$arrM['CM_NAME'].'_T">'.
						giveComma(($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']), $decimalPlace) .'</div>
						<input type="hidden" name="'.$arrM['CM_NAME'].'_E" id="'.$arrM['CM_NAME'].'_E" 
							value="'.$ESTIMATION_DATA[$arrM['CM_NAME']] .'" />
					</td>
				</tr>';*/
			}

			///////////////////////////////////////////////////////////
			/*$contentIPTotal ='<tr>
			<td class="ui-widget-content" align="center"><strong>'.$sno.'</strong></td>
			<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
			';*/
			array_push($arrMyFields, $arrM['KHARIF']['CM_NAME']);
			array_push($arrMyFields, $arrM['RABI']['CM_NAME']);
			$keyup = ' onkeyup="calculateIrri(\''.$arrM['CM_NAME'].'\')" ';
			$estimatedQuantity = '<input type="hidden" name="e'.$arrM['CM_NAME'].
				'" id="e'.$arrM['CM_NAME'].'" value="'.$arrM['ESTI_VALUE'].'"/>
				<div id="dive'.$arrM['CM_NAME'].'" class="font14">'.$arrM['ESTI_VALUE'].'</div>';
			$estimatedQuantity1 = '<input type="hidden" name="e'.$arrM['KHARIF']['CM_NAME'].
				'" id="e'.$arrM['KHARIF']['CM_NAME'].'" value="'.$arrM['KHARIF']['ESTI_VALUE'].'"/>
				<div id="dive'.$arrM['KHARIF']['CM_NAME'].'" class="font14">'.$arrM['KHARIF']['ESTI_VALUE'].'</div>';
			$estimatedQuantity2 = '<input type="hidden" name="e'.$arrM['RABI']['CM_NAME'].
				'" id="e'.$arrM['RABI']['CM_NAME'].'" value="'.$arrM['RABI']['ESTI_VALUE'].'"/>
				<div id="dive'.$arrM['RABI']['CM_NAME'].'" class="font14">'.$arrM['RABI']['ESTI_VALUE'].'</div>';
			$contentIPTotal .= '<tr>
				<td class="ui-widget-content" align="center" rowspan="3"></td>
				<td class="ui-widget-content" colspan="2" rowspan="3">Total IP</td>
				<td class="ui-widget-content">Kharif</td>
				<td align="center" class="ui-widget-content">'.$estimatedQuantity1.'</td>
				<td align="center" class="ui-widget-content">
					<input type="hidden" name="'.$arrM['KHARIF']['CM_NAME'].'" id="'.$arrM['KHARIF']['CM_NAME'].'" 
						value="'.$arrM['KHARIF']['CM_VALUE'].'" />
					<div id="div'.$arrM['KHARIF']['CM_NAME'].'" class="font14">'.$arrM['KHARIF']['CM_VALUE'].'</div>
				</td>
				<td align="center" class="ui-widget-content">
					<input type="hidden" name="'.$arrM['KHARIF']['PM_NAME'].'" id="'.$arrM['KHARIF']['PM_NAME'].'" size="10" 
						value="'.$arrM['KHARIF']['PM_VALUE'].'" readonly="readonly" class="righttext" />
					<div id="divPIP_K" class="font14">'.$arrM['KHARIF']['PM_VALUE'].'</div>
				</td>
				<td align="center" class="ui-widget-content">
					<div id="divCIP_K" class="font14">'.$arrM['KHARIF']['CFY_VALUE'].'</div>
					<input type="hidden" name="'.$arrM['KHARIF']['HCFY_NAME'].'" id="'.$arrM['KHARIF']['HCFY_NAME'].'" 
						value="'.$arrM['KHARIF']['HCFY_VALUE'].'" />
					<input type="hidden" name="'.$arrM['KHARIF']['CFY_NAME'].'" id="'.$arrM['KHARIF']['CFY_NAME'].'" size="10"
						value="'.$arrM['KHARIF']['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
				</td>
				<td align="center" class="ui-widget-content">
					<div id="divTIP_K" class="font14">'.$arrM['KHARIF']['TLY_VALUE'].'</div>
					<input type="hidden" name="'.$arrM['KHARIF']['TLY_NAME'].'" id="'.$arrM['KHARIF']['TLY_NAME'].'" size="10"
						value="'.$arrM['KHARIF']['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
				</td>
				<td align="center" class="ui-widget-content">
					<div id="divSIP_K" class="font14">'.($arrM['KHARIF']['TLY_VALUE'] + $arrM['KHARIF']['CFY_VALUE']).'</div>
					<input type="hidden" name="'.$arrM['KHARIF']['CM_NAME'].'_T" id="'.$arrM['KHARIF']['CM_NAME'].'_T" size="10" 
						value="'.($arrM['KHARIF']['TLY_VALUE'] + $arrM['KHARIF']['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
					<input type="hidden" name="'.$arrM['KHARIF']['CM_NAME'].'_E" id="'.$arrM['KHARIF']['CM_NAME'].'_E" 
						value="'.$ESTIMATION_DATA[$arrM['KHARIF']['CM_NAME']].'" />
				</td>
			</tr>
			<tr>
				<td class="ui-widget-content">Rabi</td>
				<td align="center" class="ui-widget-content">'.$estimatedQuantity2.'</td>
				<td align="center" class="ui-widget-content">
					<input type="hidden" name="'.$arrM['RABI']['CM_NAME'].'" id="'.$arrM['RABI']['CM_NAME'].'" 
						value="'.$arrM['RABI']['CM_VALUE'].'" />
					<div id="div'.$arrM['RABI']['CM_NAME'].'" class="font14">'.$arrM['RABI']['CM_VALUE'].'</div>
				</td>
				<td align="center" class="ui-widget-content">
					<input type="hidden" name="'.$arrM['RABI']['PM_NAME'].'" id="'.$arrM['RABI']['PM_NAME'].'" size="10" 
						value="'.$arrM['RABI']['PM_VALUE'].'" readonly="readonly" class="righttext" />
					<div id="divPIP_R" class="font14">'.$arrM['RABI']['PM_VALUE'].'</div>
				</td>
				<td align="center" class="ui-widget-content">
					<div id="divCIP_R" class="font14">'.$arrM['RABI']['CFY_VALUE'].'</div>
					<input type="hidden" name="'.$arrM['RABI']['HCFY_NAME'].'" id="'.$arrM['RABI']['HCFY_NAME'].'" 
						value="'.$arrM['RABI']['HCFY_VALUE'].'" />
					<input type="hidden" name="'.$arrM['RABI']['CFY_NAME'].'" id="'.$arrM['RABI']['CFY_NAME'].'" size="10"
						value="'.$arrM['RABI']['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
				</td>
				<td align="center" class="ui-widget-content">
					<div id="divTIP_R" class="font14">'.$arrM['RABI']['TLY_VALUE'].'</div>
					<input type="hidden" name="'.$arrM['RABI']['TLY_NAME'].'" id="'.$arrM['RABI']['TLY_NAME'].'" size="10"
						value="'.$arrM['RABI']['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
					
				</td>
				<td align="center" class="ui-widget-content">
					<div id="divSIP_R" class="font14">'.($arrM['RABI']['TLY_VALUE'] + $arrM['RABI']['CFY_VALUE']).'</div>
					<input type="hidden" name="'.$arrM['RABI']['CM_NAME'].'_T" id="'.$arrM['RABI']['CM_NAME'].'_T" size="10" 
						value="'.($arrM['RABI']['TLY_VALUE'] + $arrM['RABI']['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
					<input type="hidden" name="'.$arrM['RABI']['CM_NAME'].'_E" id="'.$arrM['RABI']['CM_NAME'].'_E" 
						value="'.$ESTIMATION_DATA[$arrM['RABI']['CM_NAME']].'" />
				</td>
			</tr>
				<tr>
				<td class="ui-state-default" >Total</td>
				<td align="center" class="ui-state-default">'.$estimatedQuantity.'</td>
				<td align="center" class="ui-state-default">
					<input type="hidden" name="'.$arrM['CM_NAME'].'" id="'.$arrM['CM_NAME'].'" 
						value="'.$arrM['CM_VALUE'].'" />
					<div id="div'.$arrM['CM_NAME'].'" class="font14">'.$arrM['CM_VALUE'].'</div>
				</td>
				<td align="center" class="ui-state-default">
					<input type="hidden" name="'.$arrM['PM_NAME'].'" id="'.$arrM['PM_NAME'].'" size="10" 
						value="'.$arrM['PM_VALUE'].'" readonly="readonly" class="righttext" />
					<div id="divPIP" class="font14">'.$arrM['PM_VALUE'].'</div>
				</td>
				<td align="center" class="ui-state-default">
					<div id="divCIP" class="font14">'.$arrM['CFY_VALUE'].'</div>
					<input type="hidden" name="'.$arrM['HCFY_NAME'].'" id="'.$arrM['HCFY_NAME'].'" 
						value="'.$arrM['HCFY_VALUE'].'" />
					<input type="hidden" name="'.$arrM['CFY_NAME'].'" id="'.$arrM['CFY_NAME'].'" size="10"
						value="'.$arrM['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
				</td>
				<td align="center" class="ui-state-default">
					<div id="divTIP" class="font14">'.$arrM['TLY_VALUE'].'</div>
					<input type="hidden" name="'.$arrM['TLY_NAME'].'" id="'.$arrM['TLY_NAME'].'" size="10"
						value="'.$arrM['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
				</td>
				<td align="center" class="ui-state-default">
					<div id="divSIP" class="font14">'.($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']).'</div>
					<input type="hidden" name="'.$arrM['CM_NAME'].'_T" id="'.$arrM['CM_NAME'].'_T" size="10" 
						value="'.($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
					<input type="hidden" name="'.$arrM['CM_NAME'].'_E" id="'.$arrM['CM_NAME'].'_E" 
						value="'.$ESTIMATION_DATA[$arrM['CM_NAME']] .'" />
				</td>
			</tr>';
			$contentOne .= $blockData.$contentIPTotal;
			
		}
		continue;
	}
	
	if($arrM['SNO']==3)
		$contentOne .= '<tr><td class="ui-state-default" colspan="10"><strong>2] Physical </strong></td></tr>';
	// if NA ==1 then don't show entry box
	if($arrM['SNO']==1){
		$contentOne .= '<tr>
			<td class="ui-widget-content" align="center"><strong>'.$arrM['SNO'].'</strong></td>
			<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
			<td align="center" class="ui-state-error" colspan="7">
			<big><span class="cus-lightbulb"></span> No need to Enter Financial Expenses</big>
			</td>
		</tr>';
		continue;
	}
	if($arrM['SNO']==2){
		$contentOne .= '<tr>
			<td class="ui-widget-content" align="center"><strong>'.$arrM['SNO'].'</strong></td>
			<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
			<td align="center" class="ui-state-error" colspan="7">
			<big><span class="cus-lightbulb"></span> मासिक वित्तीय खर्चों की प्रवृष्टि की आवश्यकता नहीं है</big>
			</td>
		</tr>';
		continue;
	}
	$decimalPlace = ((in_array($arrM['CM_NAME'], $arrIntFields))? 0:3);
	$estimatedQuantity = '<input type="hidden" name="e'.$arrM['CM_NAME'].
		'" id="e'.$arrM['CM_NAME'].'" value="'.$arrM['ESTI_VALUE'].'"/>'.giveComma($arrM['ESTI_VALUE'], $decimalPlace);
	if($arrM['SHOW']==1){//
		$contentOne .= '<tr>
			<td class="ui-widget-content" align="center"><strong>'.$sno.'</strong></td>
			<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
			<td align="center" class="ui-widget-content"></td>
			<td align="center" class="ui-widget-content">NA</td>
			<td align="center" class="ui-widget-content">NA</td>
			<td align="center" class="ui-widget-content">NA</td>
			<td align="center" class="ui-widget-content">NA</td>
			<td align="center" class="ui-widget-content">NA</td>
			<td align="center" class="ui-widget-content">NA</td>
		</tr>';
		continue;
	}
	
	if(in_array($arrM['CM_NAME'], $arrCanalFields)){
		if($prevMonthStatus[ $arrCanalStatusFields[$arrM['CM_NAME']]]==5){
			$contentOne .= '<tr>
			<td class="ui-widget-content" align="center"><strong>'.$sno.'</strong></td>
				<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
				<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
				<td class="ui-widget-content"></td>
				<td align="center" class="ui-widget-content">'.$estimatedQuantity.'</td>
				<td align="center" class="ui-widget-content">'.
					'<input type="hidden" name="'.$arrM['CM_NAME'].'" id="'.$arrM['CM_NAME'].'" 
						value="'.$arrM['CM_VALUE'].'" />0'.
				'</td>
				<td align="center" class="ui-widget-content">
					<input type="hidden" name="'.$arrM['PM_NAME'].'" id="'.$arrM['PM_NAME'].'" 
						value="'.$arrM['PM_VALUE'].'" />
					<div id="div_'.$arrM['PM_NAME'].'">'.giveComma($arrM['PM_VALUE'], $decimalPlace).'</div>
				</td>
				<td align="center" class="ui-widget-content">
					<input type="hidden" name="'.$arrM['HCFY_NAME'].'" id="'.$arrM['HCFY_NAME'].'" 
						value="'.$arrM['HCFY_VALUE'].'" />
					<input type="hidden" name="'.$arrM['CFY_NAME'].'" id="'.$arrM['CFY_NAME'].'" 
						value="'.$arrM['CFY_VALUE'].'" />
					<div id="div_'.$arrM['CFY_NAME'].'">'.giveComma($arrM['CFY_VALUE'], $decimalPlace).'</div>
				</td>
				<td align="center" class="ui-widget-content">
					<input type="hidden" name="'.$arrM['TLY_NAME'].'" id="'.$arrM['TLY_NAME'].'" 
						value="'.$arrM['TLY_VALUE'].'" />
					<div id="div_'.$arrM['TLY_NAME'].'">'.giveComma($arrM['TLY_VALUE'], $decimalPlace).'</div>
				</td>
				<td align="center" class="ui-widget-content">
					<input type="hidden" name="'.$arrM['CM_NAME'].'_T" id="'.$arrM['CM_NAME'].'_T"  
						value="'.($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']) .'" />
					<div id="div_'.$arrM['CM_NAME'].'_T">'.giveComma(($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']), $decimalPlace) .'</div>
					<input type="hidden" name="'.$arrM['CM_NAME'].'_E" id="'.$arrM['CM_NAME'].'_E" 
						value="'.$ESTIMATION_DATA[$arrM['CM_NAME']].'" />
				</td>
			</tr>';
			continue;
		}
	}
	array_push($arrDataFields, $arrM['CM_NAME']);
	if(in_array($arrM['CM_NAME'], $arrIntFields)){
		array_push(
			$arrForValidation, 
			'"'.$arrM['CM_NAME'].'":{required : true, digits:true, min:0, checkMyDigit:"#e'.
			$arrM['CM_NAME'].'"}'
		);
		array_push(
			$arrForValidationMessage, 
			'"'.$arrM['CM_NAME'].
			'":{required : "आंकड़े प्रविष्ट करना अनिवार्य है...", digits:"Numeric", min:"Min Value 0"}'
		);
		/*	max:"Exceed the Estimated Quantity:'.
			$arrM['ESTI_VALUE'].' <br /> Quantity Remains:'.$maxValue.'"}'
		);*/
	}else{
		/*if($arrM['SNO']==16){
			array_push(
				$arrForValidation, 
				'"'.$arrM['KHARIF']['CM_NAME'].'":{required : true, number:true, min:0, checkMyDigit:"#e'.
				$arrM['KHARIF']['CM_NAME'].'"}'
			);
			array_push(
				$arrForValidation, 
				'"'.$arrM['RABI']['CM_NAME'].'":{required : true, number:true, min:0, checkMyDigit:"#e'.
				$arrM['RABI']['CM_NAME'].'"}'
			);
		}else{*/
			array_push(
				$arrForValidation, 
				'"'.$arrM['CM_NAME'].'":{required : true, number:true, min:0, checkMyDigit:"#e'.
				$arrM['CM_NAME'].'"}'
			);
			array_push(
				$arrForValidationMessage, 
				'"'.$arrM['CM_NAME'].
				'":{required : "आंकड़े प्रविष्ट करना अनिवार्य है...", digits:"Numeric", min:"Min Value 0"}'
			);
		/*}*/
		/*, max:"Exceed the Estimated Quantity:'.
			$arrM['ESTI_VALUE'].' <br />Quantity Remains:'.$maxValue.'"}'
		);*/
	}
	array_push($arrMyFields, $arrM['CM_NAME']);
	$readonly = '';
	$decimalPlace = ((in_array($arrM['CM_NAME'], $arrIntFields))? 0:3);
	if($arrM['SNO']==$iSNoOfIPRow){
		array_push($arrMyFields, $arrM['KHARIF']['CM_NAME']);
		array_push($arrMyFields, $arrM['RABI']['CM_NAME']);
		$keyup = ' onkeyup="calculateIrri(\''.$arrM['CM_NAME'].'\')" ';
		$estimatedQuantity1 = '<input type="hidden" name="e'.$arrM['KHARIF']['CM_NAME'].
			'" id="e'.$arrM['KHARIF']['CM_NAME'].'" value="'.$arrM['KHARIF']['ESTI_VALUE'].'"/>'.
			giveComma($arrM['KHARIF']['ESTI_VALUE'], $decimalPlace);
		$estimatedQuantity2 = '<input type="hidden" name="e'.$arrM['RABI']['CM_NAME'].
			'" id="e'.$arrM['RABI']['CM_NAME'].'" value="'.$arrM['RABI']['ESTI_VALUE'].'"/>'.
			giveComma($arrM['RABI']['ESTI_VALUE'], $decimalPlace);
		$contentOne .= '<tr>
			<td class="ui-widget-content" align="center" rowspan="3"><strong>'.$sno.'</strong></td>
			<td class="ui-widget-content" rowspan="3"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content" rowspan="3">'.$arrM['UNIT'].'</td>
			<td class="ui-widget-content">Kharif</td>
			<td align="center" class="ui-widget-content">'.$estimatedQuantity1.'</td>
			<td align="center" class="ui-widget-content">'.
				'<input type="text" name="'.$arrM['KHARIF']['CM_NAME'].'" 
				id="'.$arrM['KHARIF']['CM_NAME'].'" size="10" maxlength="15"
					'.$keyup. ' 
					 class="righttext" value="'.$arrM['KHARIF']['CM_VALUE'].'" />'.
			'</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['KHARIF']['PM_NAME'].'" id="'.$arrM['KHARIF']['PM_NAME'].'" size="10" 
					value="'.$arrM['KHARIF']['PM_VALUE'].'" readonly="readonly" class="righttext" />
				<div id="div_'.$arrM['KHARIF']['PM_NAME'].'">'.
				giveComma($arrM['KHARIF']['PM_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['KHARIF']['HCFY_NAME'].'" id="'.$arrM['KHARIF']['HCFY_NAME'].'" 
					value="'.$arrM['KHARIF']['HCFY_VALUE'].'" />
				<input type="hidden" name="'.$arrM['KHARIF']['CFY_NAME'].'" id="'.$arrM['KHARIF']['CFY_NAME'].'" size="10"
					value="'.$arrM['KHARIF']['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['KHARIF']['CFY_NAME'].'">'.
				giveComma($arrM['KHARIF']['CFY_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['KHARIF']['TLY_NAME'].'" id="'.$arrM['KHARIF']['TLY_NAME'].'" size="10"
					value="'.$arrM['KHARIF']['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['KHARIF']['TLY_NAME'].'">'.
				giveComma($arrM['KHARIF']['TLY_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['KHARIF']['CM_NAME'].'_T" id="'.$arrM['KHARIF']['CM_NAME'].'_T" size="10" 
					value="'.($arrM['KHARIF']['TLY_VALUE'] + $arrM['KHARIF']['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['KHARIF']['CM_NAME'].'_T">'.
				giveComma(($arrM['KHARIF']['TLY_VALUE'] + $arrM['KHARIF']['CFY_VALUE']), $decimalPlace) .'</div>
				<input type="hidden" name="'.$arrM['KHARIF']['CM_NAME'].'_E" id="'.$arrM['KHARIF']['CM_NAME'].'_E" 
					value="'.$ESTIMATION_DATA[$arrM['KHARIF']['CM_NAME']].'" />
			</td>
		</tr>
		<tr>
			<td class="ui-widget-content">Rabi</td>
			<td align="center" class="ui-widget-content">'.$estimatedQuantity2.'</td>
			<td align="center" class="ui-widget-content">'.
				'<input type="text" name="'.$arrM['RABI']['CM_NAME'].'" id="'.$arrM['RABI']['CM_NAME'].'" size="10" maxlength="15"
					'.$keyup. ' 
					 class="righttext" value="'.$arrM['RABI']['CM_VALUE'].'" />'.
			'</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['RABI']['PM_NAME'].'" id="'.$arrM['RABI']['PM_NAME'].'" size="10" 
					value="'.$arrM['RABI']['PM_VALUE'].'" readonly="readonly" class="righttext" />
				<div id="div_'.$arrM['RABI']['PM_NAME'].'">'.giveComma($arrM['RABI']['PM_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['RABI']['HCFY_NAME'].'" id="'.$arrM['RABI']['HCFY_NAME'].'" 
					value="'.$arrM['RABI']['HCFY_VALUE'].'" />
				<input type="hidden" name="'.$arrM['RABI']['CFY_NAME'].'" id="'.$arrM['RABI']['CFY_NAME'].'" size="10"
					value="'.$arrM['RABI']['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['RABI']['CFY_NAME'].'">'.giveComma($arrM['RABI']['CFY_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['RABI']['TLY_NAME'].'" id="'.$arrM['RABI']['TLY_NAME'].'" size="10"
					value="'.$arrM['RABI']['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['RABI']['TLY_NAME'].'">'.giveComma($arrM['RABI']['TLY_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['RABI']['CM_NAME'].'_T" id="'.$arrM['RABI']['CM_NAME'].'_T" size="10" 
					value="'.($arrM['RABI']['TLY_VALUE'] + $arrM['RABI']['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['RABI']['CM_NAME'].'_T">'.giveComma(($arrM['RABI']['TLY_VALUE'] + $arrM['RABI']['CFY_VALUE']), $decimalPlace) .'</div>
				<input type="hidden" name="'.$arrM['RABI']['CM_NAME'].'_E" id="'.$arrM['RABI']['CM_NAME'].'_E" 
					value="'.$ESTIMATION_DATA[$arrM['RABI']['CM_NAME']].'" />xx
			</td>
		</tr>
			<tr>
			<td class="ui-state-default" >Total</td>
			<td align="center" class="ui-state-default">'.$estimatedQuantity.'</td>
			<td align="center" class="ui-state-default">'.
				'<input type="text" name="'.$arrM['CM_NAME'].'" id="'.$arrM['CM_NAME'].'" 
					size="10" maxlength="15" readonly="readonly" '.$keyup. '   
					 class="righttext" value="'.$arrM['CM_VALUE'].'" />'.
			'</td>
			<td align="center" class="ui-state-default">
				<input type="hidden" name="'.$arrM['PM_NAME'].'" id="'.$arrM['PM_NAME'].'" size="10" 
					value="'.$arrM['PM_VALUE'].'" readonly="readonly" class="righttext" />
				<div id="div_'.$arrM['PM_NAME'].'">'.giveComma($arrM['PM_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-state-default">
				<input type="hidden" name="'.$arrM['HCFY_NAME'].'" id="'.$arrM['HCFY_NAME'].'" 
					value="'.$arrM['HCFY_VALUE'].'" />
				<input type="hidden" name="'.$arrM['CFY_NAME'].'" id="'.$arrM['CFY_NAME'].'" size="10"
					value="'.$arrM['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['CFY_NAME'].'">'.giveComma($arrM['CFY_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-state-default">
				<input type="hidden" name="'.$arrM['TLY_NAME'].'" id="'.$arrM['TLY_NAME'].'" size="10"
					value="'.$arrM['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['TLY_NAME'].'">'.giveComma($arrM['TLY_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-state-default">
				<input type="hidden" name="'.$arrM['CM_NAME'].'_T" id="'.$arrM['CM_NAME'].'_T" size="10" 
					value="'.($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['CM_NAME'].'_T">'.
				giveComma(($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']), $decimalPlace) .'</div>
				<input type="hidden" name="'.$arrM['CM_NAME'].'_E" id="'.$arrM['CM_NAME'].'_E" 
					value="'.$ESTIMATION_DATA[$arrM['CM_NAME']] .'" />
			</td>
		</tr>';
	}else{
		$contentOne .= '<tr>
			<td class="ui-widget-content" align="center"><strong>'.$sno.'</strong></td>
			<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
			<td class="ui-widget-content"></td>
			<td align="center" class="ui-widget-content">'.$estimatedQuantity.'</td>
			<td align="center" class="ui-widget-content">'.
				'<input type="text" name="'.$arrM['CM_NAME'].'" id="'.$arrM['CM_NAME'].'" size="10" maxlength="15"
					onkeyup="calculate(\''.$arrM['CM_NAME'].'\')" 
					 class="righttext" value="'.$arrM['CM_VALUE'].'" />'.
			'</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['PM_NAME'].'" id="'.$arrM['PM_NAME'].'" size="10" 
					value="'.$arrM['PM_VALUE'].'" readonly="readonly" class="righttext" />
				<div id="div_'.$arrM['PM_NAME'].'">'.giveComma($arrM['PM_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['HCFY_NAME'].'" id="'.$arrM['HCFY_NAME'].'" 
					value="'.$arrM['HCFY_VALUE'].'" />
				<input type="hidden" name="'.$arrM['CFY_NAME'].'" id="'.$arrM['CFY_NAME'].'" size="10"
					value="'.$arrM['CFY_VALUE'].'" readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['CFY_NAME'].'">'.giveComma($arrM['CFY_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['TLY_NAME'].'" id="'.$arrM['TLY_NAME'].'" size="10"
					value="'.$arrM['TLY_VALUE'].'" readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['TLY_NAME'].'">'.giveComma($arrM['TLY_VALUE'], $decimalPlace).'</div>
			</td>
			<td align="center" class="ui-widget-content">
				<input type="hidden" name="'.$arrM['CM_NAME'].'_T" id="'.$arrM['CM_NAME'].'_T" size="10" 
					value="'.($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']) .'"  readonly="readonly"  class="righttext"/>
				<div id="div_'.$arrM['CM_NAME'].'_T">'.giveComma(($arrM['TLY_VALUE'] + $arrM['CFY_VALUE']), $decimalPlace) .'</div>
				<input type="hidden" name="'.$arrM['CM_NAME'].'_E" id="'.$arrM['CM_NAME'].'_E" 
					value="'.
					($ESTIMATION_DATA[ (($arrM['CM_NAME']=='EXPENDITURE_WORKS')? 'EXPENDITURE_WORK' : $arrM['CM_NAME'])]) .'" />
			</td>
		</tr>';
	}
}
//showArrayValues($currentMonthRecord);
//showArrayValues($prevMonthStatus);
$arrF = array(
	'LA_CASES_STATUS', 'SPILLWAY_STATUS', 
	'FLANK_STATUS', 'SLUICES_STATUS', 'NALLA_CLOSURE_STATUS', 
	'CANAL_EARTH_WORK_STATUS', 'CANAL_STRUCTURE_STATUS', 'CANAL_LINING_STATUS'
);
$arrComponentStatus = array();
$arrShow = array();
for($i=0;$i<count($arrF);$i++){
	if($currentMonthRecordExists){
		if($currentMonthRecord[$arrF[$i]]==0){
			 $arrComponentStatus[$arrF[$i]] = 0;//$prevMonthStatus[$arrF[$i]];
		}else if($prevMonthStatus[$arrF[$i]]==1 || $prevMonthStatus[$arrF[$i]]==0){
			$arrComponentStatus[$arrF[$i]] = 1;//'NA';
		}else{
			if($currentMonthRecord[$arrF[$i]]==1){
				if($prevMonthStatus[$arrF[$i]]>1)
					$arrComponentStatus[$arrF[$i]] = $prevMonthStatus[$arrF[$i]];
				else
					$arrComponentStatus[$arrF[$i]] = 1;
			}else{
				$arrComponentStatus[$arrF[$i]] = $currentMonthRecord[$arrF[$i]];
			}
		}
	}else{
		//if($prevMonthStatus[$arrF[$i]]==5 || $prevMonthStatus[$arrF[$i]]==1  || $prevMonthStatus[$arrF[$i]]==0){
		if($prevMonthStatus[$arrF[$i]]==1  || $prevMonthStatus[$arrF[$i]]==0){
			$arrComponentStatus[$arrF[$i]] = 1;//'NA';
		}else{
			$arrComponentStatus[$arrF[$i]] = 0;// $prevMonthStatus[$arrF[$i]];
		}
	}
	if($arrComponentStatus[$arrF[$i]]==1){
		$arrShow[$arrF[$i]] = FALSE;
	}else{
		if($prevMonthStatus[$arrF[$i]]==5 || $prevMonthStatus[$arrF[$i]]==1){
			$arrShow[$arrF[$i]] = FALSE;
		}else{
			$arrShow[$arrF[$i]] = TRUE;
		}
	}
	if($arrShow[$arrF[$i]]==FALSE){
		if(!$currentMonthRecordExists){
			$arrComponentStatus[$arrF[$i]] = 0;
		}
	}
}
//showArrayValues($arrComponentStatus);
$arrRemarkShow = array();
//$status_options = 0 1'NA', 2'Not Started', 3'Ongoing', 4'Stopped', 5'Completed', 6'Dropped'
for($i=0;$i<count($arrF);$i++){
	$arrRemarkShow[$arrF[$i]] = 0;
	if($currentMonthRecordExists){
		if(	$arrComponentStatus[$arrF[$i]]==2 || 
			$arrComponentStatus[$arrF[$i]]==4 ){
				$arrRemarkShow[$arrF[$i]] = 1;
		}else{
			$arrRemarkShow[$arrF[$i]] = 0;
		}
		//2-not started 4-stopped
		/*if( ($currentMonthRecord[$arrF[$i]]==2) || ($currentMonthRecord[$arrF[$i]]==4) ){
			if($prevMonthStatus[$arrF[$i]]==1){
				$arrRemarkShow[$arrF[$i]] = 0;
			}else{
				$arrRemarkShow[$arrF[$i]] = 1;
			}
		}*/
	}else{
		//if($prevMonthStatus){
		//if( ($prevMonthStatus[$arrF[$i]]==2) || ($prevMonthStatus[$arrF[$i]]==4) )
			$arrRemarkShow[$arrF[$i]] = 0;
	}
}
if($PROJECT_ID==0){
	showArrayValues($arrRemarkShow);
	showArrayValues($arrComponentStatus);
}
//showArrayValues($currentMonthRecord);
//showArrayValues($prevMonthStatus);
//showArrayValues($arrShow);
//echo '::'.$statusData['FLANK_STATUS'].'::'.$currentMonthRecordExists.'::';

$arrStatus = array(
	array(
		'SNO'=>1,
		'LIST_VALUE'=>'a',
		'TITLE'=>'Submission of LA Cases', 
		'STATUS_BOX_NAME'=>'LA_CASES_STATUS',
		'STATUS_VALUE'=>$arrComponentStatus['LA_CASES_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['LA_CASES_STATUS'],
		'SHOW'=>$arrShow['LA_CASES_STATUS'], 
		'REMARK'=>$monthly_remarks['LA_CASES_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['LA_CASES_STATUS']
		),
	array(
		'SNO'=>2,
		'LIST_VALUE'=>'b',
		'TITLE'=>'Spillway / weir', 
		'STATUS_BOX_NAME'=>'SPILLWAY_STATUS',
		'STATUS_VALUE'=>$arrComponentStatus['SPILLWAY_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['SPILLWAY_STATUS'],
		'SHOW'=>$arrShow['SPILLWAY_STATUS'], 
		'REMARK'=>$monthly_remarks['SPILLWAY_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['SPILLWAY_STATUS']
	),
	array(
		'SNO'=>3,
		'LIST_VALUE'=>'c',
		'TITLE'=>'Flanks/Af.bunds', 
		'STATUS_BOX_NAME'=>'FLANK_STATUS',
		'STATUS_VALUE'=>$arrComponentStatus['FLANK_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['FLANK_STATUS'],
		'SHOW'=>$arrShow['FLANK_STATUS'],  
		'REMARK'=>$monthly_remarks['FLANK_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['FLANK_STATUS']
	),
	array(
		'SNO'=>4,
		'LIST_VALUE'=>'d',
		'TITLE'=>'Sluice/s', 
		'STATUS_BOX_NAME'=>'SLUICES_STATUS',
		'STATUS_VALUE'=>$arrComponentStatus['SLUICES_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['SLUICES_STATUS'],
		'SHOW'=>$arrShow['SLUICES_STATUS'],  
		'REMARK'=>$monthly_remarks['SLUICES_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['SLUICES_STATUS']
	),
	array(
		'SNO'=>5,
		'LIST_VALUE'=>'e',
		'TITLE'=>'Nalla closer', 
		'STATUS_BOX_NAME'=>'NALLA_CLOSURE_STATUS',
		'STATUS_VALUE'=>$arrComponentStatus['NALLA_CLOSURE_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['NALLA_CLOSURE_STATUS'],
		'SHOW'=>$arrShow['NALLA_CLOSURE_STATUS'],
		'REMARK'=>$monthly_remarks['NALLA_CLOSURE_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['NALLA_CLOSURE_STATUS']
	),
	array(
		'SNO'=>6,
		'LIST_VALUE'=>'f',
		'TITLE'=>'Canal Structures Status', 
		'STATUS_BOX_NAME'=>'CANAL_STRUCTURE_STATUS',
		'STATUS_VALUE'=>$arrComponentStatus['CANAL_STRUCTURE_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['CANAL_STRUCTURE_STATUS'],
		'SHOW'=>$arrShow['CANAL_STRUCTURE_STATUS'],
		'REMARK'=>$monthly_remarks['CANAL_STRUCTURE_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['CANAL_STRUCTURE_STATUS']
	),
	array(
		'SNO'=>7,
		'LIST_VALUE'=>'g',
		'TITLE'=>'Canal E/W Status', 
		'STATUS_BOX_NAME'=>'CANAL_EARTH_WORK_STATUS',
		'STATUS_VALUE'=>$arrComponentStatus['CANAL_EARTH_WORK_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['CANAL_EARTH_WORK_STATUS'],
		'SHOW'=>$arrShow['CANAL_EARTH_WORK_STATUS'],
		'REMARK'=>$monthly_remarks['CANAL_EARTH_WORK_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['CANAL_EARTH_WORK_STATUS']
	),
	array(
		'SNO'=>8,
		'LIST_VALUE'=>'h',
		'TITLE'=>'Canal Lining', 
		'STATUS_BOX_NAME'=>'CANAL_LINING_STATUS',
		'STATUS_VALUE'=>$arrComponentStatus['CANAL_LINING_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['CANAL_LINING_STATUS'],
		'SHOW'=>$arrShow['CANAL_LINING_STATUS'], 
		'REMARK'=>$monthly_remarks['CANAL_LINING_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['CANAL_LINING_STATUS']
	)
);
$i=0;
//showArrayValues($prevMonthStatus);
$contentStatus = '';
$arrComponentsIncluded = array();
foreach($arrStatus as $arrSt){
	//if($setupData[''] = $PROJECT_ID
	$myOptions = '';
	if($arrSt['SHOW']){
		array_push($arrComponentsIncluded, $arrSt['STATUS_BOX_NAME']);
		$myOptions = '<select name="'.$arrSt['STATUS_BOX_NAME'].'" id="'.$arrSt['STATUS_BOX_NAME'].'"
			style="width:150px" class="chosen-select" onchange="setComponentStatus(this.name)">'.
			getMyStatus(1, $arrSt['STATUS_VALUE'], $arrSt['PRE_STATUS_VALUE']).
			'</select>';
		$myOptions1 = '<div id="'.$arrSt['STATUS_BOX_NAME'].'remarkDiv" style="display:'.
		(($arrSt['SHOW_REMARK']==1)? 'block':'none')
		.'">
        <textarea id="'.$arrSt['STATUS_BOX_NAME'].'_REMARK" name="'.$arrSt['STATUS_BOX_NAME'].'_REMARK" '.
			'cols="35" rows="2">'.
			$arrSt['REMARK'].'</textarea>
        </div>';
	}else{
		if($prevMonthStatus[$arrSt['STATUS_BOX_NAME']]==5){
			$myOptions =  'Completed'.
				'<input type="hidden" name="'.$arrSt['STATUS_BOX_NAME'].'" id="'.$arrSt['STATUS_BOX_NAME'].'"
				 value="5" />';
			$myOptions1 =  '';
		}else{
			$myOptions =  'NA';
			$myOptions1 =  '';
		}
	}
	$contentStatus .= '<tr>	
		<td class="ui-widget-content"><strong>'.$arrSt['LIST_VALUE'].') '.$arrSt['TITLE'].'</strong></td>
		<td class="ui-widget-content" align="center"><strong>'.$status_options[ $prevMonthStatus[$arrSt['STATUS_BOX_NAME']] ].'</strong></td>
		<td class="ui-widget-content" align="center">'.$myOptions.'</td>
		<td class="ui-widget-content" align="center">'.$myOptions1.'</td>
	</tr>';
}
//showArrayValues($arrComponentsIncluded);
?>
<table width="100%" border="0" cellpadding="6" cellspacing="1" class="ui-widget-content" id="monthly_table">
<thead>
<tr>
  <th class="ui-widget-header">&nbsp;</th>
    <th class="ui-widget-header">&nbsp;</th>
    <th align="center" class="ui-widget-header">Unit</th>
    <th align="center" class="ui-widget-header">&nbsp;</th>
    <th align="center" class="ui-widget-header">Estimated<br />Quantity</th>
    <th align="center" class="ui-widget-header">Current Month</th>
    <th align="center" class="ui-widget-header">Previous<br />Month</th>
    <th width="10%" align="center" class="ui-widget-header">Total in<br />Current<br />Financial Year</th>
    <th width="9%" align="center" class="ui-widget-header">Till<br />Last <br />Year</th>
    <th width="11%" align="center" class="ui-widget-header">Cumulative<br />Till Date<br />(d+e)</th>
</tr>
<tr>
  <th class="ui-state-default">&nbsp;</th>
  <th class="ui-state-default">&nbsp;</th>
  <th align="center" class="ui-state-default">&nbsp;</th>
  <th align="center" class="ui-state-default">&nbsp;</th>
  <th align="center" class="ui-state-default">a</th>
  <th align="center" class="ui-state-default">b</th>
  <th align="center" class="ui-state-default">c</th>
  <th align="center" class="ui-state-default">d</th>
  <th align="center" class="ui-state-default">e</th>
  <th align="center" class="ui-state-default">f</th>
</tr>
</thead>
<tbody>
<tr>
    <td colspan="13" class="ui-state-default"><strong>1] Financial</strong></td>
</tr>
<?php echo $contentOne;?>
</table>
<div class="wrdlinebreak"></div>
<table width="100%" border="0" cellpadding="6" cellspacing="1" class="ui-widget-content">
<tr>
    <td rowspan="2" width="33%" class="ui-state-default">3] Status</td>
    <td colspan="2" width="33%" align="center" class="ui-state-default"><strong>Status</strong></td>
    <td rowspan="2" width="33%" align="center" class="ui-state-default"><strong>Remarks</strong></td>
</tr>
<tr>
  <td align="center" class="ui-state-default">Previous Month</td>
  <td align="center" class="ui-state-default">Current Month</td>
</tr>            
<?php echo $contentStatus;?>
</tbody>
</table>
</form>
<div id="mySaveMonthlyDiv" align="right" class="mysavebar">
<?php echo $buttons;?>
</div>
</div>
</div>
<script language="javascript">
//
function getMyValue(id){
	var num1 = parseFloat( $('#' + id).val() );
	return ((isNaN(num1))?0:num1);
}
//
function calculate(ids){
	var currentMonth = getMyValue(ids);
	//var previousMonth = getMyValue( (ids + '_P'));
	var currentFY = getMyValue( (ids + '_CFY_H') );
	var totalCurrentFY = roundNumber((currentFY + currentMonth), 3);
	
	$('#' + ids + '_CFY').val(roundNumber(totalCurrentFY, 3));
	$('#div_' + ids + '_CFY').html(roundNumber(totalCurrentFY, 3));
	//alert(roundNumber(totalCurrentFY, 2));
	var cummulativeTotal = (totalCurrentFY + getMyValue((ids+'_TLY')));
	$('#' + ids + '_T').val( roundNumber(cummulativeTotal, 3));
	$('#div_' + ids + '_T').html( roundNumber(cummulativeTotal, 3));
}
//
var validator;
var arrBenefitedBlocks = new Array();
$().ready(function(){
	//$('.date_picker').datepicker({dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true});
	$('#COMPLETION_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({ 
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true,
		beforeShow: function(input, inst) {	return setMinMaxDate('#START_MONTH_DATE', '#END_MONTH_DATE'); }
	});
	$(".chosen-select").select2();
	setSelect2();//always use this for validation Engine
	window.validator = 
		$("#frmMonthly").validate({
			rules: {
				<?php if(count($arrForValidation)){
					echo implode(",\n", $arrForValidation);
				}?>
			},
			messages: {
				<?php if(count($arrForValidationMessage )){
					echo implode(',', $arrForValidationMessage);
				}?>
			}
		});
	<?php
	echo 'window.arrBenefitedBlocks = ['.implode(',', $arrBenefitedBlocks).'];';
	if($currentMonthRecord['PROJECT_STATUS']==5){
		echo '$("#COMPLETION_DATE").rules("add", "required");';
		echo '$("#PROJECT_STATUS_REMARK").rules("add", "required");';
	}
	foreach($arrRemarkShow as $k=>$v){
		if($v==1){
			echo '$("#'.$k.'_REMARK").rules("add", "required");';
		}
	}?>
	setProjectStatus(<?php echo $currentMonthRecord['PROJECT_STATUS'];?>);
	var $demoabs = $('#monthly_table');
	$demoabs.floatThead({
		scrollContainer: function($table){
			return $table.closest('.wrapper');
		}
	});
	$( "#radioset" ).buttonset();
});
//
jQuery.validator.addMethod("checkMyDigit", function(value, element, params) {
	var nodecimals = new String(value | 0); //truncate the decimal part (no rounding)
	var lengthOfNo = nodecimals.length;
	var nodecimalsEsti = new String($(params).val() | 0);
	var lengthOfEsti = nodecimalsEsti.length;
	//alert(':' + lengthOfNo  + ':' + lengthOfEsti);
	//esti-3  no-12
	return ((lengthOfNo <= (lengthOfEsti+1))? true:false);
}, jQuery.validator.format("आपकी प्रविष्टि की गई मात्रा बहुत अधिक हो गयी है."));
//
function showTarget(){
	var id = $('#PROJECT_ID').val();
	if(id>0){
		data = {'PROJECT_ID':id, 'session_id':$('#SESSION_ID').val()};
		showModalBox('modalBox', 'showTargetForm', data, 'Edit Target', 'aTEntryBox', true, false);
	}
}
function aTEntryBox(data){
	$('#modalBox').html(data);
	centerDialog('modalBox');
}
//
function setProjectStatus(pstatus){
<?php echo "var noOfComponents = ".count($arrComponentsIncluded).";\n";
echo "var chkCompoFields = new Array('". implode("', '", $arrComponentsIncluded)."');";?>
	//alert();
	var ps = parseInt($('#PROJECT_STATUS').val());
	$('#completionDiv').hide();
	$('#completionDiv1').hide();
	$("#COMPLETION_DATE").rules("remove", "required");
	//$('#remarkDiv').hide();
	$("#PROJECT_STATUS_REMARK").rules("remove", "required");
	$('#divRemarks').hide();
	$('#divStatus').hide();
	$('#divCompletionType').hide();
	var mEnableIt = false;
	switch(ps){
		case 0://not clear
			 mEnableIt = true;
			 break;
		case 1://NA
			//$('#completionDiv').hide();
			//$('#remarkDiv').hide();
			break;
		case 3://ongoing
			var arrDate = $('#ACTUAL_COMPLETION_DATE').val().split("-");
			var mYear = parseInt(arrDate[0]);
			var mMonth = parseInt(arrDate[1]);
			var mYearC = <?php echo date("Y", $MONTH_DATE);?>;
			var mMonthC = <?php echo date("n", $MONTH_DATE);?>;
			var mValid = true;
			if(mYearC==mYear && mMonthC>mMonth) 
				mValid = false;
			else if(mYearC>mYear)
				mValid = false;
			
			if(mValid){
				//$('#remarkDiv').hide();
				//alert(1);
				$("#PROJECT_STATUS_REMARK").rules("remove", "required");
			}else{
				//alert(2);
				$('#divRemarks').show();
				$('#remarkDiv').show();
				$("#PROJECT_STATUS_REMARK").rules("add", "required");
			}
			mEnableIt = true;
			break;
		case 4://stopped 
			$('#divRemarks').show();
			mEnableIt = true;
		case 2://not started
			$('#remarkDiv').show();
			$('#divRemarks').show();
			$("#PROJECT_STATUS_REMARK").rules("add", "required");
			$('#completionDiv').hide();
			$('#completionDiv1').hide();
			break;
		case 5://completed
			$('#remarkDiv').show();
			$('#divRemarks').show();
			$('#divStatus').show();
			$('#completionDiv').show();
			$('#completionDiv1').show();
			$('#completedDateCaption').html('Completion Date');
			$('#completedNoCaption').html('Completion Certificate No');
			$("#COMPLETION_DATE").rules("add", "required");
			$("#PROJECT_STATUS_DISPATCH_NO").rules("add", "required");
			$("#PROJECT_STATUS_REMARK").rules("add", "required");
			$('#divCompletionType').show();
			
			mEnableIt = true;
			checkCompletionOnSubmit();
			$('html, body').animate({scrollTop: $("#completionDiv1").offset().top}, 2000);
			//$("#PROJECT_STATUS_REMARK").rules("remove", "required");
			break;
		case 6://dropped
			mEnableIt = false;
			$('#divRemarks').show();
			$('#remarkDiv').show();
			$('#divStatus').show();
			$('#completedDateCaption').html('Dropped Date');
			$('#completedNoCaption').html('Dropped Memo No');
			$("#PROJECT_STATUS_REMARK").rules("add", "required");
			$('#completionDiv').show();
			$('#completionDiv1').show();
			$("#COMPLETION_DATE").rules("add", "required");
			$("#PROJECT_STATUS_DISPATCH_NO").rules("add", "required");
			$('html, body').animate({scrollTop: $("#completionDiv1").offset().top}, 1000);
			<?php foreach($arrDataFields as $arrDataField){
				echo '$("#'.$arrDataField.'" ).rules("remove", "required");'."\n";
				//disable control
				echo '$("#'. $arrDataField.'").prop("disabled", true);'."\n";
			}?>
			break;
	}
	if(noOfComponents>0){
		//alert('Complet:' + chkCompoFields.length + " " + chkCompoFields.join("#") );
		for(i=0;i<chkCompoFields.length;i++){
			$('#'+chkCompoFields[i]).select2("enable", mEnableIt);
			/*if(ps==4){
				$('#'+chkCompoFields[i]).val(4);
				$('#'+chkCompoFields[i]).select2("val", 4);
				$('#'+chkCompoFields[i]).trigger("updatecomplete");
			}*/
		}
	}
}
function setComponentStatus(compo){
	var ps = parseInt($('#'+compo).val());
	$('#'+ compo +'remarkDiv').hide();
	$("#" + compo + '_REMARK').rules("remove", "required");
	switch(ps){
		case 1://NA
		case 3://ongoing
			break;
		case 4://stopped
		case 2://not started
			$('#'+ compo +'remarkDiv').show();
			$("#" + compo + '_REMARK').rules("add", "required");
			break;
		case 5://completed
			break;
	}
}
function checkCompletionOnSubmit(){
	var mfields = new Array('<?php echo implode("','", $arrDataFields);?>');
	var mEstimation = 0;
	var mCumulative = 0;
	for(i=0; i<mfields.length; i++){
		mEstimation = parseFloat( $('#'+ mfields[i] + '_E').val() );
		mCumulative = parseFloat( $('#'+ mfields[i] + '_T').val() );
		if(mEstimation==mCumulative) {
			return true;
		}else{
			$('#remarkDiv').show();
			$("#PROJECT_STATUS_REMARK").rules("add", "required");
			return false;
		}
	}
	return false;
}
//
function saveMonthly(){
	// 27-04-2020  // If ework_project_status is 0(zero) then projects can not be saved as completed
	//[code starts]
	var ework_project_status ="<?php echo $EWORK_PROJECT_STATUS;?>";
	var ps = $('#PROJECT_STATUS').val();
	var ctype = $('input[name="COMPLETION_TYPE"]:checked').val();
	if(ps==5 && ework_project_status ==1 && ctype==1){
		showAlert('Error...', 'आपके द्वारा उपरोक्त कार्य में epay सिस्टम में कुछ राशि बकाया है अतः इस कार्य को financially complete नहीं किया जा सकता  है।', 'warn');
		return false;
	}
	//return false;
	//[code end]

	var selectList = new Array();
	selectList.push( Array('PROJECT_STATUS', 'Select Project Status', true) );
	var ps = parseInt($('#PROJECT_STATUS').val());
	if(ps<6){
            <?php
            $arrComponents = array(
                'LA_CASES_STATUS'=>'Select LA Case Status',
                'SPILLWAY_STATUS'=>'Select Spillway Status',
                'FLANK_STATUS'=>'Select Flank Status',
                'SLUICES_STATUS'=>'Select Sluices Status',
                'NALLA_CLOSURE_STATUS'=>'Select Nalla Closure',
                'CANAL_EARTH_WORK_STATUS'=>'Select Canal Earthwork Status',
                'CANAL_STRUCTURE_STATUS'=>'Select Canal Structure Status',
                'CANAL_LINING_STATUS'=>'Select Canal Lining Status'
            );
            $contCompo = '';
            $i=1;
            foreach($arrComponentsIncluded as $compo){
                $contCompo .= "selectList.push( Array('".$compo."', '".
                    $arrComponents[$compo]."', true));\n";
                $i++;
            }
            echo $contCompo;
            ?>
	}else{
		<?php foreach($arrDataFields as $arrDataField){
			echo '$("#'.$arrDataField.'" ).rules("remove", "required");'."\n";
			//disable control
			echo '$("#'. $arrDataField.'").prop("disabled", true);'."\n";
		}?>
	}
	var mSelect = validateMyCombo(selectList);
	var myValidation = $("#frmMonthly").valid();
	if( !(mSelect==0 && myValidation)){
		alert('You have : ' + ( window.validator.numberOfInvalids() + mSelect ) + ' errors in this form.');
		return ;
	}
	if(myValidation){
		var ps = parseInt($('#PROJECT_STATUS').val());
		if(ps==5){
			//check component status
			<?php echo "var noOfComponents = ".count($arrComponentsIncluded).";\n";
			 echo "var chkCompoFields = new Array('". implode("', '", $arrComponentsIncluded)."');";?>
			var countNotCompleted = 0;
			for(i=0;i<chkCompoFields.length;i++){
				if( $('#'+chkCompoFields[i]).val()!=5){
					countNotCompleted++;
				}
			}
			if((countNotCompleted>0) && (noOfComponents>0)){
				alert('Please Check Component Status...');
				return false;
			}
			if(!checkCompletionOnSubmit()){
				if ($('#remarkDiv').css('display') == 'none') {
					alert('Achievement Data is not equals to Estimation Data...' +
					 "\n" + 'Please Enter Remarks given below the Completion Date');
					 return;
				}else{
					if ( $('#PROJECT_STATUS_REMARK').val()==""){
						alert('Project Status Remark is blank');
						return;
					}
				}
			}
			var SelectedType = 0;
			SelectedType = (($('#radio1').is(':checked')) ? 1:0) ;
			if(SelectedType==0)
				SelectedType = (($('#radio2').is(':checked')) ? 2:0) ;
			if(SelectedType==0){
				alert('Select Completion Type...');
				return;
			}else{
				var message = new Array();
				<?php if($setupData['IRRIGATION_POTENTIAL_NA']==0){?>
				var iptK = checkNo($('#IRRIGATION_POTENTIAL_KHARIF_T').val());
				var iptR = checkNo($('#IRRIGATION_POTENTIAL_RABI_T').val());
				var ipeK = checkNo($('#IRRIGATION_POTENTIAL_KHARIF_E').val());
				var ipeR = checkNo($('#IRRIGATION_POTENTIAL_RABI_E').val());
				if((iptK!=ipeK) || (iptR!=ipeR)){
					message.push("Irrigation Potential Achieved is not equal to Estimation\n");
				}
				//alert('iptK:' + iptK + ' ipeK:' +ipeK + 'iptR:' + iptR + ' ipeR:' +ipeR);
				if(iptK!=ipeK)
					message.push("Kharif => Estimation : " + ipeK + " Total Achieved : " + iptK);
				if(iptR!=ipeR)
					message.push("Rabi => Estimation : " + ipeR + " Total Achieved : " + iptR);
				<?php }?>
				if(message.length!=0) {
					alert(message.join("\n"));
					return;
				}
			}
			if(SelectedType==2){
				var lapayment = (($('#LA_PAYMENT').is(':checked')) ? 1:0) ;
				var fapayment = (($('#FA_PAYMENT').is(':checked')) ? 1:0) ;
				var capayment = (($('#CL_PAYMENT').is(':checked')) ? 1:0) ;
				if( (lapayment==0) &&  (fapayment==0) &&  (capayment==0) ){
					alert('Select Reason...');
					return;
				}
			}
		}
		var params = {
			'divid':'mySaveMonthlyDiv', 
			'url':'saveMonthlyData', 
			'data':$('#frmMonthly').serialize(), 
			'donefname': 'doneMonthlyData', 
			'failfname' :'failMonthlyData', 
			'alwaysfname':''
		};
		callMyAjax(params);
	}else{
		showMyAlert('Error...', 'There is/are some Required Data... <br />Please Check & Complete it.', 'warn');
	}
}
function calculateIrri(ids){
	var kh = checkNo($('#IRRIGATION_POTENTIAL_KHARIF').val());
	var rab = checkNo($('#IRRIGATION_POTENTIAL_RABI').val());
	var tot = kh + rab;
	$('#'+ids).val(roundNumber(tot, 3));
	calculate('IRRIGATION_POTENTIAL_KHARIF');
	calculate('IRRIGATION_POTENTIAL_RABI');
	calculate('IRRIGATION_POTENTIAL');
}
function checkCompletionType(mode){
	if(mode==2){
		$('#divReasonsOfIncompletion').show();
		$('html, body').animate({scrollTop: $("#completionDiv1").offset().top}, 1000);
		//$(document).scrollTop( 300 );
	}else{
		$('#divReasonsOfIncompletion').hide();
	}
}

function calculateSubIrri(blockId){
	<?php if(!$isEstimationExists){?>
		var estiKh = checkNo($('#BLOCK_EIP_K_'+blockId).val());
		var estiRab = checkNo($('#BLOCK_EIP_R_'+blockId).val());
		var esti = estiKh + estiRab;
		$('#BLOCK_EIP_T_'+blockId).val(esti);
		$('#divBLOCK_EIP_T_'+blockId).html(esti);
		//alert(esti);
	<?php }?>
		
	var curMonthKh = checkNo($('#BLOCK_IP_K_'+blockId).val());
	var curMonthRab = checkNo($('#BLOCK_IP_R_'+blockId).val());
	var curMonth = curMonthKh+ curMonthRab;

	var totalInCurrentKh = checkNo($('#BLOCK_CIP_K_'+blockId).val());
	var totalInCurrentRab = checkNo($('#BLOCK_CIP_R_'+blockId).val());

	var tlyKh = checkNo($('#BLOCK_TIP_K_'+blockId).val());
	var tlyRab = checkNo($('#BLOCK_TIP_R_'+blockId).val());

	var totalInSessionKh = totalInCurrentKh + curMonthKh;
	var totalInSessionRab = totalInCurrentRab + curMonthRab;
	var totalInSession = totalInSessionKh + totalInSessionRab; 
	
	var cummulativeKh = totalInSessionKh + tlyKh;
	var cummulativeRab = totalInSessionRab + tlyRab;
	var cummulative = cummulativeKh + cummulativeRab;

	//current month total
	$('#BLOCK_IP_T_'+blockId).val(curMonth);
	$('#divBLOCK_IP_T_'+blockId).html(curMonth);
	//update total in current session
	$('#divBLOCK_CIP_K_'+blockId).html(totalInSessionKh);
	$('#divBLOCK_CIP_R_'+blockId).html(totalInSessionRab);
	$('#divBLOCK_CIP_T_'+blockId).html(totalInSession);	
	
	//cummulative
	$('#divBLOCK_SIP_K_'+blockId).html(cummulativeKh);
	$('#divBLOCK_SIP_R_'+blockId).html(cummulativeRab);
	$('#divBLOCK_SIP_T_'+blockId).html(cummulative);

	var sumIPK = 0, sumIPR=0, sumIP=0;
	var sumPIPK = 0, sumPIPR=0, sumPIP=0;
	var sumCIPK = 0, sumCIPR=0, sumCIP=0;
	var sumTIPK = 0, sumTIPR=0, sumTIP=0;
	var sumSIPK = 0, sumSIPR=0, sumSIP=0;
	<?php if(!$isEstimationExists){?>
		var sumEIPK = 0, sumEIPR=0, sumEIP=0;
	<?php } ?>
	var ss = '';
	for(i=0;i<window.arrBenefitedBlocks.length;i++){
		block_id = window.arrBenefitedBlocks[i];
		ss += block_id + "-";
		<?php if(!$isEstimationExists){?>
		sumEIPK += checkNo($('#BLOCK_EIP_K_' + block_id).val());
		sumEIPR += checkNo($('#BLOCK_EIP_R_' + block_id).val());
		sumEIP += checkNo($('#BLOCK_EIP_T_' + block_id).val());
		<?php } ?>
		//current month
		sumIPK += checkNo($('#BLOCK_IP_K_' + block_id).val());
		sumIPR += checkNo($('#BLOCK_IP_R_' + block_id).val());
		//sumIP += checkNo($('#BLOCK_IP_T_' + block_id).val());
		//prev
		sumPIPK += checkNo($('#BLOCK_PIP_K_' + block_id).val());
		sumPIPR += checkNo($('#BLOCK_PIP_R_' + block_id).val());
		//sumPIP += checkNo($('#BLOCK_PIP_T_' + block_id).val());
		//total in current FY
		sumCIPK += checkNo($('#BLOCK_CIP_K_' + block_id).val());
		sumCIPR += checkNo($('#BLOCK_CIP_R_' + block_id).val());
		//sumCIP += checkNo($('#BLOCK_CIP_T_' + block_id).val());
		//till last year
		sumTIPK += checkNo($('#BLOCK_TIP_K_' + block_id).val());
		sumTIPR += checkNo($('#BLOCK_TIP_R_' + block_id).val());
		//sumTIP += checkNo($('#BLOCK_TIP_T_' + block_id).val());
		//cummulative total
		sumSIPK += checkNo($('#BLOCK_SIP_K_' + block_id).val());
		sumSIPR += checkNo($('#BLOCK_SIP_R_' + block_id).val());
		//sumSIP += checkNo($('#BLOCK_SIP_T_' + block_id).val());
	}
	//alert(window.arrBenefitedBlocks.length + "\n" +  ss + "\n" + window.arrBenefitedBlocks.join('#') + '::'+  sumIPK + ' :' + sumIPR + ':' + sumIP);
	$('#IRRIGATION_POTENTIAL_KHARIF').val(sumIPK);
	$('#divIRRIGATION_POTENTIAL_KHARIF').html(sumIPK);
	$('#IRRIGATION_POTENTIAL_RABI').val(sumIPR);
	$('#divIRRIGATION_POTENTIAL_RABI').html(sumIPR);
	$('#IRRIGATION_POTENTIAL').val(sumIPK+sumIPR);
	$('#divIRRIGATION_POTENTIAL').html(sumIPK+sumIPR);
	<?php if(!$isEstimationExists){?>
		//$('#eIRRIGATION_POTENTIAL_KHARIF').val(sumEIPK);
		$('#diveIRRIGATION_POTENTIAL_KHARIF').html(sumEIPK);
		//$('#eIRRIGATION_POTENTIAL_RABI').val(sumEIPR);
		$('#diveIRRIGATION_POTENTIAL_RABI').html(sumEIPR);
		//$('#eIRRIGATION_POTENTIAL').val(sumEIP);
		$('#diveIRRIGATION_POTENTIAL').html(sumEIPK+sumEIPR);
	<?php }?>
	$('#divCIP_K').html(sumCIPK+sumIPK);
	$('#divCIP_R').html(sumCIPR+sumIPR);
	$('#divCIP').html(sumCIPK+sumIPK+sumCIPR+sumIPR);

	$('#divPIP_K').html(sumPIPK);
	$('#divPIP_R').html(sumPIPR);
	$('#divPIP').html(sumPIPK+sumPIPR);

	/*$('#divTIP_K').html(sumTIPK);
	$('#divTIP_R').html(sumTIPR);
	$('#divTIP').html(sumTIP);*/

	$('#divSIP_K').html(sumCIPK + sumIPK + sumTIPK);
	$('#divSIP_R').html(sumCIPR + sumIPR + sumTIPR);
	$('#divSIP').html(sumCIPK + sumIPK + sumTIPK + sumCIPR + sumIPR + sumTIPR);
	
	$('#IRRIGATION_POTENTIAL_KHARIF_T').val(sumCIPK + sumIPK + sumTIPK);
	$('#IRRIGATION_POTENTIAL_RABI_T').val(sumCIPR + sumIPR + sumTIPR);
	$('#IRRIGATION_POTENTIAL_T').html(sumCIPK + sumIPK + sumTIPK + sumCIPR + sumIPR + sumTIPR);

}
</script>
