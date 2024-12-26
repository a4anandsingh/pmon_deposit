<style>
.ui-state-dim{background:#eee; color:#000; border:1px solid #CCC}
</style>
<?php $status_options = array('', '', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 'Dropped', 'Current Year AA');
$isBlank = FALSE;
$arrMonthlyData = $arrDataFields = array();
function getWorkStatusOptions($type, $Sel=0, $prevMonthStatus=0){
    $myOptions ='';
	//type 0-Work Status
	if($type==0){
		$statusOptionValues = array('Please Select'=>0, 'NA'=>1, 'Not Started'=>2,'Ongoing'=>3, 'Stopped'=>4, 'Completed'=>5,'Current Year AA'=>7);
	}else{
		$statusOptionValues = array('Please Select'=>0, 'NA'=>1, 'Not Started'=>2, 'Ongoing'=>3, 'Stopped'=>4, 'Completed'=>5, 'Current Year AA'=>7);
	}
	switch($prevMonthStatus){
    case '': //
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
		case 7: //Current Year AA
			$filteredStatus = array('Please Select', 'Not Started', 'Ongoing', 'Stopped');
			break;
		default : 
			$filteredStatus = array('Please Select', 'Ongoing', 'Stopped');
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
	//for($i=0;$i<count($filteredStatus);$i++){
	foreach($filteredStatus as $fs){
		$selText = '';
		$statusValueFromKey = $statusOptionValues[ $fs ];
		//if current month doesn't have status
		//if($Sel==0){
			/*if($statusValueFromKey==$prevMonthStatus)
				$selText = 'selected="selected"';*/
		//}else{
			if($Sel==$statusValueFromKey)
				$selText = 'selected="selected"';
		//}
		$myOptions .= '<option value="'.$statusValueFromKey.'" '.$selText.'>'.$fs.'</option>';
	}
	return $myOptions;
}?>
<form id="frmMonthly" name="frmMonthly" method="post" action="">
<?php $mon = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug','Sep', 'Oct', 'Nov', 'Dec');
$arrForValidationControls = array();
$arrForValidation = array();
$arrForValidationMessage = array();
  //if($this->PROJECT_ID==4546){   showArrayValues($arrCurrentMonthData);exit;}
?>
<input type="hidden" id="PROJECT_NAME" name="PROJECT_NAME" value="<?php echo $arrProjectData['PROJECT_NAME'];?>" />
<input type="hidden" name="PROJECT_SETUP_ID" id="PROJECT_SETUP_ID" value="<?php echo $PROJECT_SETUP_ID;?>" />
<input type="hidden" name="MONTHLY_DATA_ID" id="MONTHLY_DATA_ID" value="<?php echo $arrCurrentMonthData['MONTHLY_DATA_ID'];?>" />
<input type="hidden" name="MONTH_DATE" id="MONTH_DATE" value="<?php echo $MONTH_DATE;?>" />
<input type="hidden" name="START_MONTH_DATE" id="START_MONTH_DATE" value="<?php echo date("d-m-Y", $MONTH_DATE);?>" />
<input type="hidden" name="END_MONTH_DATE" id="END_MONTH_DATE" value="<?php echo date("t-m-Y", $MONTH_DATE);?>" />
<input type="hidden" name="SESSION_ID" id="SESSION_ID" value="<?php echo $SESSION_ID;?>" />
<div class="panel panel-primary">
<!-- Default panel contents -->
<div class="panel-heading">
    <?php //echo 'monthdate ='. $MONTH_DATE;  ?>
    <strong><big><big>Monthly Entry ( <?php echo date('F Y', $MONTH_DATE);?> )</big>
    <br />
<?php echo $arrProjectData['PROJECT_NAME']. '</big><br />Code : '.$arrProjectData['PROJECT_CODE'];?></strong>
</div>
<div class="panel-body">
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tr>
    <td nowrap="nowrap" class="ui-state-default"><strong>Status of Scheme</strong></td>
    <td class="ui-widget-content" style="font-weight:900">

        <?php
            //echo '=> '.$arrCurrentMonthData['WORK_STATUS'].' ### '.$arrPreviousMonthData['WORK_STATUS'];
        ?>

     <?php //echo 'cur month status = '. $arrCurrentMonthData['WORK_STATUS'].', prev month status ='.$arrPreviousMonthData['WORK_STATUS'];?>
        <select name="WORK_STATUS" id="WORK_STATUS" class="mysel2"
            style="width:150px" onchange="setProjectStatus(this.value)">
        <?php echo getWorkStatusOptions(0, $arrCurrentMonthData['WORK_STATUS'], $arrPreviousMonthData['WORK_STATUS']);?>
        </select>
        <?php
            //echo "STATUS =". $arrCurrentMonthData['WORK_STATUS'] ." < > ". $arrPreviousMonthData['WORK_STATUS'];
        ?>
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
		switch($arrCurrentMonthData['WORK_STATUS']){
			case 4:
			case 2:
			case 5:
			case 6:	$remarkDiv = 'block'; break;
			case 3: if( strtotime($ACTUAL_COMPLETION_DATE)<=$MONTH_DATE) $remarkDiv = 'block'; break;
		}
		//echo $ACTUAL_COMPLETION_DATE.'<='.$MONTH_DATE;
		switch($arrCurrentMonthData['WORK_STATUS']){
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
                    value="<?php echo myDateFormat($arrCurrentMonthData['COMPLETION_DATE']);?>" size="16" 
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
                <div id="divCompletionType" style="display:<?php echo ($arrCurrentMonthData['WORK_STATUS']==5)? '':'none';?>">
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
                    <textarea name="WORK_STATUS_REMARK" id="WORK_STATUS_REMARK"
                            rows="2"  style="width:97%"
                       ><?php echo $monthly_remarks['WORK_STATUS_REMARK'];?></textarea>
                </div>
            </td>
        </tr>
        </table>
    </td>
</tr>
<!--<tr>
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
</tr>-->
</table>
<div class="wrdlinebreak"></div>
<!-- FIXED FORMAT START -->
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tbody>
<tr><th class="ui-widget-header">SNo.</th>
    <th colspan="3" class="ui-widget-header">&nbsp;</th>
    <th align="center" class="ui-widget-header">Unit</th>
    <th align="center" class="ui-widget-header">Estimated</th>
    <th align="center" class="ui-widget-header">Current Month</th>
    <th align="center" class="ui-widget-header">Previous Month</th>
    <th width="10%" align="center" class="ui-widget-header">Total in<br />Current<br />Financial Year</th>
    <th width="9%" align="center" class="ui-widget-header">Till Last Year</th>
    <th width="11%" align="center" class="ui-widget-header">Cumulative<br />Till Date<br />(f+g)</th>
</tr>
<tr>
    <th class="ui-state-default" width="20">&nbsp;</th>
    <th colspan="3" align="center" class="ui-state-default">a</th>
    <th align="center" class="ui-state-default">b</th>
    <th align="center" class="ui-state-default">c</th>
    <th align="center" class="ui-state-default">d</th>
    <th width="10%" align="center" class="ui-state-default">e</th>
    <th align="center" class="ui-state-default">f</th>
    <th align="center" class="ui-state-default">g</th>
    <th align="center" class="ui-state-default">h</th>
</tr>
<?php 
if($setupData['LA_NA']==0){
	array_push($arrDataFields, 'LA_NO');
	array_push($arrDataFields, 'LA_HA');
	array_push($arrDataFields, 'LA_COMPLETED_NO');
	array_push($arrDataFields, 'LA_COMPLETED_HA');
}
?>
<tr>
    <td rowspan="4" align="center" class="ui-widget-content"><strong>1</strong></td>
    <td rowspan="4" class="ui-widget-content"><strong>Land aq cases </strong></td>
    <td colspan="2" rowspan="2" class="ui-widget-content"><strong>Submitted</strong></td>
    <td class="ui-widget-content"><strong>Numbers</strong></td>
    <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrEstimationData['LA_NO'];?></td>
    <td class="ui-widget-content" align="center">
    <?php 
	if($setupData['LA_NA']){
		echo 'NA';
	}else{?>
      <input type="text" name="LA_NO" id="LA_NO" size="10" maxlength="15" autocomplete="off" 
            onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['LA_NO'];?>" />
    <?php 
		array_push($arrMonthlyData, "'LA_NO':{'ESTI':".$arrEstimationData['LA_NO'].", 'CFY':".$arrCFY['LA_NO'].", 'TLY':".$arrTLY['LA_NO']."}");
		array_push($arrForValidation, "'LA_NO':{required : true, digits:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'LA_NO':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', digits:'Numeric', min:'Min Value 0'}");
	}
    ?>
    </td>
    <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrPreviousMonthData['LA_NO'];?></td>
    <td class="ui-widget-content" align="center" id="divCFY_LA_NO"><?php echo ($setupData['LA_NA'])? 'NA':(intval($arrCFY['LA_NO']) + intval($arrCurrentMonthData['LA_NO']));?></td>
    <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrTLY['LA_NO'];?></td>
    <td class="ui-widget-content" align="center" id="divTOTAL_LA_NO"><?php echo ($setupData['LA_NA'])? 'NA':(intval($arrCFY['LA_NO']) + intval($arrCurrentMonthData['LA_NO'])+intval($arrTLY['LA_NO']));?></td>
</tr>
    <tr>
      <td class="ui-widget-content"><strong>Hectares</strong></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrEstimationData['LA_HA'];?></td>
      <td class="ui-widget-content" align="center">
       <?php 
		if($setupData['LA_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="LA_HA" id="LA_HA" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['LA_HA'];?>" />
		<?php 
		array_push($arrMonthlyData, "'LA_HA':{'ESTI':".$arrEstimationData['LA_HA'].", 'CFY':".$arrCFY['LA_HA'].", 'TLY':".$arrTLY['LA_HA']."}");
		array_push($arrForValidation, "'LA_HA':{required : true, number:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'LA_HA':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', number:'number', min:'Min Value 0'}"	);
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrPreviousMonthData['LA_HA'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_LA_HA"><?php echo ($setupData['LA_NA'])? 'NA':(floatval($arrCFY['LA_HA']) + floatval($arrCurrentMonthData['LA_HA']));?></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrTLY['LA_HA'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_LA_HA"><?php echo ($setupData['LA_NA'])? 'NA':(floatval($arrCFY['LA_HA']) + floatval($arrCurrentMonthData['LA_HA'])+floatval($arrTLY['LA_HA']));?></td>
    </tr>
    <tr>
      <td colspan="2" rowspan="2" class="ui-widget-content"><strong>Completed</strong></td>
      <td class="ui-widget-content" ><strong>Numbers</strong></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrEstimationData['LA_COMPLETED_NO'];?></td>
      <td class="ui-widget-content" align="center">
      	 <?php 
		if($setupData['LA_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="LA_COMPLETED_NO" id="LA_COMPLETED_NO" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['LA_COMPLETED_NO'];?>" />
		<?php 
		array_push($arrMonthlyData, "'LA_COMPLETED_NO':{'ESTI':".(($arrEstimationData['LA_COMPLETED_NO'])?$arrEstimationData['LA_COMPLETED_NO']:0).", 'CFY':".$arrCFY['LA_COMPLETED_NO'].", 'TLY':".$arrTLY['LA_COMPLETED_NO']."}");
		array_push($arrForValidation, "'LA_COMPLETED_NO':{required : true, digits:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'LA_COMPLETED_NO':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', digits:'Numeric', min:'Min Value 0'}");
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrPreviousMonthData['LA_COMPLETED_NO'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_LA_COMPLETED_NO"><?php echo ($setupData['LA_NA'])? 'NA':(intval($arrCFY['LA_COMPLETED_NO']) + intval($arrCurrentMonthData['LA_COMPLETED_NO']));?></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrTLY['LA_COMPLETED_NO'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_LA_COMPLETED_NO"><?php echo ($setupData['LA_NA'])? 'NA':(intval($arrCFY['LA_COMPLETED_NO']) + intval($arrCurrentMonthData['LA_COMPLETED_NO'])+intval($arrTLY['LA_COMPLETED_NO']));?></td>
    </tr>
    <tr>
      <td class="ui-widget-content" ><strong>Hectares</strong></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrEstimationData['LA_COMPLETED_HA'];?></td>
      <td class="ui-widget-content" align="center">
       <?php 
		if($setupData['LA_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="LA_COMPLETED_HA" id="LA_COMPLETED_HA" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['LA_COMPLETED_HA'];?>" />
		<?php
		array_push($arrMonthlyData, "'LA_COMPLETED_HA':{'ESTI':".(($arrEstimationData['LA_COMPLETED_HA'])?$arrEstimationData['LA_COMPLETED_HA']:0).", 'CFY':".$arrCFY['LA_COMPLETED_HA'].", 'TLY':".$arrTLY['LA_COMPLETED_HA']."}");
		array_push($arrForValidation, "'LA_COMPLETED_HA':{required : true, number:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'LA_COMPLETED_HA':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', number:'number', min:'Min Value 0'}"	);
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrPreviousMonthData['LA_COMPLETED_HA'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_LA_COMPLETED_HA"><?php echo ($setupData['LA_NA'])? 'NA':(floatval($arrCFY['LA_COMPLETED_HA']) + floatval($arrCurrentMonthData['LA_COMPLETED_HA']));?></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['LA_NA'])? 'NA':$arrTLY['LA_COMPLETED_HA'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_LA_COMPLETED_HA"><?php echo ($setupData['LA_NA'])? 'NA':(floatval($arrCFY['LA_COMPLETED_HA']) + floatval($arrCurrentMonthData['LA_COMPLETED_HA'])+floatval($arrTLY['LA_COMPLETED_HA']));?></td>
    </tr>
     <?php 
	if($setupData['FA_NA']==0){
		array_push($arrDataFields, 'FA_HA');
		array_push($arrDataFields, 'FA_COMPLETED_HA');
	}?>
    <tr>
      <td rowspan="2" align="center" class="ui-widget-content"><strong>2</strong><strong></strong></td>
      <td rowspan="2" class="ui-widget-content"><strong>Forest cases </strong></td>
      <td colspan="2" class="ui-widget-content"><strong>Submitted</strong></td>
      <td class="ui-widget-content" ><strong>Hectares</strong></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['FA_NA'])? 'NA':$arrEstimationData['FA_HA'];?></td>
      <td class="ui-widget-content" align="center">
      <?php 
		if($setupData['FA_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="FA_HA" id="FA_HA" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['FA_HA'];?>" />
		<?php 
		array_push($arrMonthlyData, "'FA_HA':{'ESTI':".$arrEstimationData['FA_HA'].", 'CFY':".$arrCFY['FA_HA'].", 'TLY':".$arrTLY['FA_HA']."}");
		array_push($arrForValidation, "'FA_HA':{required : true, number:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'FA_HA':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', number:'number', min:'Min Value 0'}"	);
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['FA_NA'])? 'NA':$arrPreviousMonthData['FA_HA'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_FA_HA"><?php echo ($setupData['FA_NA'])? 'NA':(floatval($arrCFY['FA_HA']) + floatval($arrCurrentMonthData['FA_HA']));?></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['FA_NA'])? 'NA':$arrTLY['FA_HA'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_FA_HA"><?php echo ($setupData['FA_NA'])? 'NA':(floatval($arrCFY['FA_HA']) + floatval($arrCurrentMonthData['FA_HA'])+floatval($arrTLY['FA_HA']));?></td>
    </tr>
    <tr>
      <td colspan="2" class="ui-widget-content"><strong>Completed</strong></td>
      <td class="ui-widget-content" ><strong>Hectares</strong></td>
      <td class="ui-widget-content" align="center"><?php echo  ($setupData['FA_NA'])? 'NA':$arrEstimationData['FA_COMPLETED_HA'];?></td>
      <td class="ui-widget-content" align="center">
      <?php 
		if($setupData['FA_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="FA_COMPLETED_HA" id="FA_COMPLETED_HA" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['FA_COMPLETED_HA'];?>" />
        <?php 
		array_push($arrMonthlyData, "'FA_COMPLETED_HA':{'ESTI':".$arrEstimationData['FA_COMPLETED_HA'].", 'CFY':".$arrCFY['FA_COMPLETED_HA'].", 'TLY':".$arrTLY['FA_COMPLETED_HA']."}");
		array_push($arrForValidation, "'FA_COMPLETED_HA':{required : true, number:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'FA_COMPLETED_HA':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', number:'number', min:'Min Value 0'}"	);
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo  ($setupData['FA_NA'])? 'NA':$arrPreviousMonthData['FA_COMPLETED_HA'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_FA_COMPLETED_HA"><?php echo  ($setupData['FA_NA'])? 'NA':(floatval($arrCFY['FA_COMPLETED_HA']) + floatval($arrCurrentMonthData['FA_COMPLETED_HA']));?></td>
      <td class="ui-widget-content" align="center"><?php echo  ($setupData['FA_NA'])? 'NA':$arrTLY['FA_COMPLETED_HA'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_FA_COMPLETED_HA"><?php echo  ($setupData['FA_NA'])? 'NA':(floatval($arrCFY['FA_COMPLETED_HA']) + floatval($arrCurrentMonthData['FA_COMPLETED_HA'])+floatval($arrTLY['FA_COMPLETED_HA']));?></td>
    </tr>
    <?php 
	if($setupData['L_EARTHWORK_NA']==0){
		array_push($arrDataFields, 'L_EARTHWORK');
	}?>
    <tr>
      <td class="ui-widget-content" align="center"><strong>3</strong></td>
      <td class="ui-widget-content" colspan="3"><strong>Earthwork (As per "L" Earthwork section of DPR)</strong></td>
      <td class="ui-widget-content"><strong>Th Cum</strong></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['L_EARTHWORK_NA'])? 'NA':$arrEstimationData['L_EARTHWORK'];?></td>
      <td class="ui-widget-content" align="center">
      <?php 
		if($setupData['L_EARTHWORK_NA']){
			echo 'NA';
		}else{?>
			<input type="text" name="L_EARTHWORK" id="L_EARTHWORK" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['L_EARTHWORK'];?>" />
		<?php 
			array_push($arrMonthlyData, "'L_EARTHWORK':{'ESTI':".$arrEstimationData['L_EARTHWORK'].", 'CFY':".$arrCFY['L_EARTHWORK'].", 'TLY':".$arrTLY['L_EARTHWORK']."}");
			array_push($arrForValidation, "'L_EARTHWORK':{required : true, number:true, min:0, checkMyDigit:''}");
			array_push($arrForValidationMessage, "'L_EARTHWORK':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', number:'number', min:'Min Value 0'}"	);
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['L_EARTHWORK_NA'])? 'NA':$arrPreviousMonthData['L_EARTHWORK'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_L_EARTHWORK"><?php echo ($setupData['L_EARTHWORK_NA'])? 'NA':(floatval($arrCFY['L_EARTHWORK']) + floatval($arrCurrentMonthData['L_EARTHWORK']));?></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['L_EARTHWORK_NA'])? 'NA':$arrTLY['L_EARTHWORK'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_L_EARTHWORK"><?php echo ($setupData['L_EARTHWORK_NA'])? 'NA':(floatval($arrCFY['L_EARTHWORK']) + floatval($arrCurrentMonthData['L_EARTHWORK'])+floatval($arrTLY['L_EARTHWORK']));?></td>
    </tr>
	<?php if($setupData['C_MASONRY_NA']==0){  array_push($arrDataFields, 'C_MASONRY'); } ?>


    <tr>
        <td class="ui-widget-content" rowspan="4" align="center" ><strong>4</strong></td>
        <td class="ui-widget-content" rowspan="4" ><strong>Masonry/Concrete&nbsp;<br />(As per &quot;C&quot; Masonry section of DPR)</strong></td>
        <td colspan="2" class="ui-widget-content" ><strong>(a) Masonry/Concrete</strong></td>
        <td class="ui-widget-content" ><strong>Th Cum</strong></td>
        <td class="ui-widget-content" align="center"><?php echo ($setupData['C_MASONRY_NA'])? 'NA':$arrEstimationData['C_MASONRY'];?></td>
        <td class="ui-widget-content" align="center">
        <?php 
		if($setupData['C_MASONRY_NA']){
			echo 'NA';
		}else{?>
              <input type="text" name="C_MASONRY" id="C_MASONRY" size="10" maxlength="15" autocomplete="off" 
                    onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['C_MASONRY'];?>" />
            <?php 
			array_push($arrMonthlyData, "'C_MASONRY':{'ESTI':".$arrEstimationData['C_MASONRY'].", 'CFY':".$arrCFY['C_MASONRY'].", 'TLY':".$arrTLY['C_MASONRY']."}");
            array_push($arrForValidation, "'C_MASONRY':{required : true, number:true, min:0, checkMyDigit:''}");
            array_push($arrForValidationMessage, "'C_MASONRY':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', number:'number', min:'Min Value 0'}"	);
		}?>
        </td>
        <td class="ui-widget-content" align="center"><?php echo ($setupData['C_MASONRY_NA'])? 'NA':$arrPreviousMonthData['C_MASONRY'];?></td>
        <td class="ui-widget-content" align="center" id="divCFY_C_MASONRY"><?php echo ($setupData['C_MASONRY_NA'])? 'NA':(floatval($arrCFY['C_MASONRY']) + floatval($arrCurrentMonthData['C_MASONRY']));?></td>
        <td class="ui-widget-content" align="center"><?php echo ($setupData['C_MASONRY_NA'])? 'NA':$arrTLY['C_MASONRY'];?></td>
        <td class="ui-widget-content" align="center" id="divTOTAL_C_MASONRY"><?php echo ($setupData['C_MASONRY_NA'])? 'NA':(floatval($arrCFY['C_MASONRY']) + floatval($arrCurrentMonthData['C_MASONRY'])+floatval($arrTLY['C_MASONRY']));?></td>
    </tr>            
	<?php if($setupData['C_PIPEWORK_NA']==0){array_push($arrDataFields, 'C_PIPEWORK');}?>
    <tr>
      <td rowspan="2" class="ui-widget-content" ><strong>(b) Pipe Works</strong></td>
      <td class="ui-widget-content" ><strong>i. DE/PE/PVC<br />(Main &amp; Submain)</strong></td>
      <td class="ui-widget-content" ><strong>Th Cum</strong></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['C_PIPEWORK_NA'])? 'NA':$arrEstimationData['C_PIPEWORK'];?></td>
      <td class="ui-widget-content" align="center">
      <?php 
		if($setupData['C_PIPEWORK_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="C_PIPEWORK" id="C_PIPEWORK" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['C_PIPEWORK'];?>" />
		<?php 
		array_push($arrMonthlyData, "'C_PIPEWORK':{'ESTI':".$arrEstimationData['C_PIPEWORK'].", 'CFY':".$arrCFY['C_PIPEWORK'].", 'TLY':".$arrTLY['C_PIPEWORK']."}");
		array_push($arrForValidation, "'C_PIPEWORK':{required : true, digits:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'C_PIPEWORK':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', digits:'Numeric', min:'Min Value 0'}");
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['C_PIPEWORK_NA'])? 'NA':$arrPreviousMonthData['C_PIPEWORK'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_C_PIPEWORK"><?php echo ($setupData['C_PIPEWORK_NA'])? 'NA':(intval($arrCFY['C_PIPEWORK']) + intval($arrCurrentMonthData['C_PIPEWORK']));?></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['C_PIPEWORK_NA'])? 'NA':$arrTLY['C_PIPEWORK'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_C_PIPEWORK"><?php echo ($setupData['C_PIPEWORK_NA'])? 'NA':(intval($arrCFY['C_PIPEWORK']) + intval($arrCurrentMonthData['C_PIPEWORK'])+intval($arrTLY['C_PIPEWORK']));?></td>
    </tr>
	<?php if($setupData['C_DRIP_PIPE_NA']==0){ array_push($arrDataFields, 'C_DRIP_PIPE');}?>
    <tr>
      <td class="ui-widget-content" ><strong>ii. Lateral for <br />Drip/sprinkler</strong></td>
      <td class="ui-widget-content" ><strong>Mtrs</strong></td>
      <td class="ui-widget-content" align="center" id="C_DRIP_PIPE_esti"><?php echo ($setupData['C_DRIP_PIPE_NA'])? 'NA':$arrEstimationData['C_DRIP_PIPE'];?></td>
      <td class="ui-widget-content" align="center">
      <?php 
		if($setupData['C_DRIP_PIPE_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="C_DRIP_PIPE" id="C_DRIP_PIPE" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['C_DRIP_PIPE'];?>" />
		<?php 
		array_push($arrMonthlyData, "'C_DRIP_PIPE':{'ESTI':".$arrEstimationData['C_DRIP_PIPE'].", 'CFY':".$arrCFY['C_DRIP_PIPE'].", 'TLY':".$arrTLY['C_DRIP_PIPE']."}");
		array_push($arrForValidation, "'C_DRIP_PIPE':{required : true, digits:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'C_DRIP_PIPE':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', digits:'Numeric', min:'Min Value 0'}");
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['C_DRIP_PIPE_NA'])? 'NA':$arrPreviousMonthData['C_DRIP_PIPE'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_C_DRIP_PIPE"><?php echo ($setupData['C_DRIP_PIPE_NA'])? 'NA':(intval($arrCFY['C_DRIP_PIPE']) + intval($arrCurrentMonthData['C_DRIP_PIPE']));?></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['C_DRIP_PIPE_NA'])? 'NA':$arrTLY['C_DRIP_PIPE'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_C_DRIP_PIPE">
	  	<?php echo ($setupData['C_DRIP_PIPE_NA'])? 'NA':(intval($arrCFY['C_DRIP_PIPE']) + intval($arrCurrentMonthData['C_DRIP_PIPE'])+(intval($arrCFY['C_DRIP_PIPE']) + intval($arrCurrentMonthData['C_DRIP_PIPE'])+intval($arrTLY['C_DRIP_PIPE'])));?>
      </td>
    </tr>
	<?php if($setupData['C_WATERPUMP_NA']==0){array_push($arrDataFields, 'C_WATERPUMP');}?>
      <tr>
        <td colspan="2" class="ui-widget-content" ><strong>(c) Water Pumps</strong></td>
        <td class="ui-widget-content" ><strong>Numbers</strong></td>
        <td class="ui-widget-content" align="center"><?php echo ($setupData['C_WATERPUMP_NA'])? 'NA':$arrEstimationData['C_WATERPUMP'];?></td>
      <td class="ui-widget-content" align="center">
      <?php 
		if($setupData['C_WATERPUMP_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="C_WATERPUMP" id="C_WATERPUMP" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['C_WATERPUMP'];?>" />
		<?php 
		array_push($arrMonthlyData, "'C_WATERPUMP':{'ESTI':".$arrEstimationData['C_WATERPUMP'].", 'CFY':".$arrCFY['C_WATERPUMP'].", 'TLY':".$arrTLY['C_WATERPUMP']."}");
		array_push($arrForValidation, "'C_WATERPUMP':{required : true, digits:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'C_WATERPUMP':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', digits:'Numeric', min:'Min Value 0'}");
		}?>
      </td>
        <td class="ui-widget-content" align="center"><?php echo ($setupData['C_WATERPUMP_NA'])? 'NA':$arrPreviousMonthData['C_WATERPUMP'];?></td>
        <td class="ui-widget-content" align="center" id="divCFY_C_WATERPUMP"><?php echo ($setupData['C_WATERPUMP_NA'])? 'NA':(intval($arrCFY['C_WATERPUMP']) + intval($arrCurrentMonthData['C_WATERPUMP']));?></td>
        <td class="ui-widget-content" align="center"><?php echo ($setupData['C_WATERPUMP_NA'])? 'NA':$arrTLY['C_WATERPUMP'];?></td>
        <td class="ui-widget-content" align="center" id="divTOTAL_C_WATERPUMP"><?php echo ($setupData['C_WATERPUMP_NA'])? 'NA':(intval($arrCFY['C_WATERPUMP']) + intval($arrCurrentMonthData['C_WATERPUMP'])+intval($arrTLY['C_WATERPUMP']));?></td>                
    </tr>

       <?php if($setupData['K_CONTROL_ROOMS_NA']==0){ array_push($arrDataFields, 'K_CONTROL_ROOMS');}?>
      <tr>
      <td class="ui-widget-content" align="center"><strong>5</strong></td>
      <td class="ui-widget-content" colspan="3"><strong>Building Works<br>(As per "K" Building sectin of DPR) Control Rooms</strong></td>
      <td class="ui-widget-content" ><strong>Numbers</strong></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['K_CONTROL_ROOMS_NA'])? 'NA':$arrEstimationData['K_CONTROL_ROOMS'];?></td>
      <td class="ui-widget-content" align="center">
      <?php 
		if($setupData['K_CONTROL_ROOMS_NA']){
			echo 'NA';
		}else{?>
   		  <input type="text" name="K_CONTROL_ROOMS" id="K_CONTROL_ROOMS" size="10" maxlength="15" autocomplete="off" 
            	onkeyup="calculate(this.name)" class="centertext" value="<?php echo $arrCurrentMonthData['K_CONTROL_ROOMS'];?>" />
		<?php 
		array_push($arrMonthlyData, "'K_CONTROL_ROOMS':{'ESTI':".$arrEstimationData['K_CONTROL_ROOMS'].", 'CFY':".$arrCFY['K_CONTROL_ROOMS'].", 'TLY':".$arrTLY['K_CONTROL_ROOMS']."}");
		array_push($arrForValidation, "'K_CONTROL_ROOMS':{required : true, digits:true, min:0, checkMyDigit:''}");
		array_push($arrForValidationMessage, "'K_CONTROL_ROOMS':{required : 'आंकड़े प्रविष्ट करना अनिवार्य है...', digits:'Numeric', min:'Min Value 0'}");
		}?>
      </td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['K_CONTROL_ROOMS_NA'])? 'NA':$arrPreviousMonthData['K_CONTROL_ROOMS'];?></td>
      <td class="ui-widget-content" align="center" id="divCFY_K_CONTROL_ROOMS"><?php echo ($setupData['K_CONTROL_ROOMS_NA'])? 'NA':(intval($arrCFY['K_CONTROL_ROOMS']) + intval($arrCurrentMonthData['K_CONTROL_ROOMS']));?></td>
      <td class="ui-widget-content" align="center"><?php echo ($setupData['K_CONTROL_ROOMS_NA'])? 'NA':$arrTLY['K_CONTROL_ROOMS'];?></td>
      <td class="ui-widget-content" align="center" id="divTOTAL_K_CONTROL_ROOMS"><?php echo ($setupData['K_CONTROL_ROOMS_NA'])? 'NA':(intval($arrCFY['K_CONTROL_ROOMS']) + intval($arrCurrentMonthData['K_CONTROL_ROOMS'])+intval($arrTLY['K_CONTROL_ROOMS']));?></td>
    </tr>
      <tr>
        <td class="ui-widget-content" align="center"><strong>6</strong></td>
        <td class="ui-widget-content" colspan="3"><strong>Irrigation Potential Created</strong></td>
        <td class="ui-widget-content"><strong>Hectares</strong></td>
        <td colspan="6" align="center" class="ui-widget-content"></td>
      </tr>
    <?php 
    //showArrayValues($arrBlockData);
    //foreach($arrMonthly as $arrM){
	$arrMonthlyBlockData = array();
	$iBCount = 97;
	$arrTotalTemp = array('ESTIMATION'=>0, 'CURRENT_MONTH'=>0, 'PREV_MONTH'=>0, 'CFY'=>0, 'TLY'=>0, 'TOTAL'=>0);
	$arrTotal = array('KHARIF' => $arrTotalTemp, 'RABI' => $arrTotalTemp, 'TOTAL' => $arrTotalTemp);
	$arrBenefitedBlocks = array();
	foreach($arrBlockData as $k=>$v){
		array_push($arrBenefitedBlocks, $k);
		
$s = $k.": {
			'KHARIF':{'ESTI':".$v['ESTIMATION_IP']['KHARIF'].", 'CFY':".$v['ACHIEVEMENT_IP_CFY']['KHARIF'].", 'TLY':".$v['ACHIEVEMENT_IP_TLY']['KHARIF']."}, 
			'RABI':{'ESTI':".$v['ESTIMATION_IP']['RABI'].", 'CFY':".$v['ACHIEVEMENT_IP_CFY']['RABI'].", 'TLY':".$v['ACHIEVEMENT_IP_TLY']['RABI']."}, 
			'TOTAL':{'ESTI':".$v['ESTIMATION_IP']['IP'].", 'CFY':".$v['ACHIEVEMENT_IP_CFY']['IP'].", 'TLY':".$v['ACHIEVEMENT_IP_TLY']['IP']."} 
			}";


		array_push($arrMonthlyBlockData, $s);
		
		$keyup = ' onkeyup="calculateSubIrri('.$k.')" ';
		$arrTotal['KHARIF']['ESTIMATION']+= $v['ESTIMATION_IP']['KHARIF'];
		$arrTotal['KHARIF']['CURRENT_MONTH']+= $v['CUR_MONTH_IP']['KHARIF'];
		$arrTotal['KHARIF']['PREV_MONTH']+= $v['PREV_MONTH_IP']['KHARIF'];
		$arrTotal['KHARIF']['CFY']+= $v['ACHIEVEMENT_IP_CFY']['KHARIF'];
		$arrTotal['KHARIF']['TLY']+= $v['ACHIEVEMENT_IP_TLY']['KHARIF'];
		$arrTotal['KHARIF']['TOTAL']+= (intval($v['ACHIEVEMENT_IP_CFY']['KHARIF'])+intval($v['ACHIEVEMENT_IP_TLY']['KHARIF']));

          $arrTotal['RABI']['ESTIMATION']+= $v['ESTIMATION_IP']['RABI'];
          $arrTotal['RABI']['CURRENT_MONTH']+= $v['CUR_MONTH_IP']['RABI'];
          $arrTotal['RABI']['PREV_MONTH']+= $v['PREV_MONTH_IP']['RABI'];
          $arrTotal['RABI']['CFY']+= $v['ACHIEVEMENT_IP_CFY']['RABI'];
          $arrTotal['RABI']['TLY']+= $v['ACHIEVEMENT_IP_TLY']['RABI'];
          $arrTotal['RABI']['TOTAL']+= (intval($v['ACHIEVEMENT_IP_CFY']['RABI'])+intval($v['ACHIEVEMENT_IP_TLY']['RABI']));

          $arrTotal['TOTAL']['ESTIMATION']+= $v['ESTIMATION_IP']['IP'];
          $arrTotal['TOTAL']['CURRENT_MONTH']+= $v['CUR_MONTH_IP']['IP'];
          $arrTotal['TOTAL']['PREV_MONTH']+= $v['PREV_MONTH_IP']['IP'];
          $arrTotal['TOTAL']['CFY']+= $v['ACHIEVEMENT_IP_CFY']['IP'];
          $arrTotal['TOTAL']['TLY']+= $v['ACHIEVEMENT_IP_TLY']['IP'];
          $arrTotal['TOTAL']['TOTAL']+= (intval($v['ACHIEVEMENT_IP_CFY']['IP'])+intval($v['ACHIEVEMENT_IP_TLY']['IP']));
      ?>
      <tr>
        <td class="ui-widget-content" align="center" rowspan="3"><?php echo chr($iBCount++); ?></td>
        <td class="ui-widget-content" rowspan="3" colspan="3"><?php echo $v['BLOCK_NAME']; ?></td>
        <td><strong>Kharif</strong></td>
        <td class="ui-widget-content" align="center"><?php echo $v['ESTIMATION_IP']['KHARIF'];?></td>
        <td class="ui-widget-content" align="center">
        	<input type="text" name="BLOCK_IP_K[<?php echo $k;?>]" id="BLOCK_IP_K_<?php echo $k;?>" autocomplete="off"
				size="10" maxlength="15" class="centertext" <?php echo $keyup;?> value="<?php echo $v['CUR_MONTH_IP']['KHARIF'];?>" />
		</td>
        <td class="ui-widget-content" align="center"><?php echo $v['PREV_MONTH_IP']['KHARIF'];?></td>
        <td class="ui-widget-content" align="center" id="BLOCK_IP_K_CFY_<?php echo $k;?>"><?php echo (($isBlank)?'':$v['ACHIEVEMENT_IP_CFY']['KHARIF']);?></td>
        <td class="ui-widget-content" align="center"><?php echo $v['ACHIEVEMENT_IP_TLY']['KHARIF'];?></td>
        <td class="ui-widget-content" align="center" id="BLOCK_IP_K_TLY_<?php echo $k;?>"><?php echo (($isBlank)?'':(intval($v['ACHIEVEMENT_IP_CFY']['KHARIF'])+intval($v['ACHIEVEMENT_IP_TLY']['KHARIF'])));?></td>
      </tr>
      <tr>
        <td ><strong>Rabi</strong></td>
        <td class="ui-widget-content" align="center"><?php echo $v['ESTIMATION_IP']['RABI'];?></td>
         <td class="ui-widget-content" align="center">
        	<input type="text" name="BLOCK_IP_R[<?php echo $k;?>]" id="BLOCK_IP_R_<?php echo $k;?>" autocomplete="off"
				size="10" maxlength="15" class="centertext" <?php echo $keyup;?> value="<?php echo $v['CUR_MONTH_IP']['RABI'];?>" />
		</td>
        <td class="ui-widget-content" align="center"><?php echo $v['PREV_MONTH_IP']['RABI'];?></td>
        <td class="ui-widget-content" align="center" id="BLOCK_IP_R_CFY_<?php echo $k;?>"><?php echo (($isBlank)?'':$v['ACHIEVEMENT_IP_CFY']['RABI']);?></td>
        <td class="ui-widget-content" align="center"><?php echo $v['ACHIEVEMENT_IP_TLY']['RABI'];?></td>
        <td class="ui-widget-content" align="center" id="BLOCK_IP_R_TLY_<?php echo $k;?>"><?php echo (($isBlank)?'':(intval($v['ACHIEVEMENT_IP_CFY']['RABI'])+intval($v['ACHIEVEMENT_IP_TLY']['RABI'])));?></td>
      </tr>
      <tr>
        <td class="ui-state-default" ><strong>Total</strong></td>
        <td class="ui-state-default" align="center"><?php echo $v['ESTIMATION_IP']['IP'];?></td>
        <td class="ui-state-default" align="center" id="BLOCK_IP_T_<?php echo $k;?>"><?php echo (($isBlank)?'':$v['CUR_MONTH_IP']['IP']);?></td>
        <td class="ui-state-default" align="center"><?php echo $v['PREV_MONTH_IP']['IP'];?></td>
        <td class="ui-state-default" align="center" id="BLOCK_IP_T_CFY_<?php echo $k;?>"><?php echo (($isBlank)?'':$v['ACHIEVEMENT_IP_CFY']['IP']);?></td>
        <td class="ui-state-default" align="center"><?php echo $v['ACHIEVEMENT_IP_TLY']['IP'];?></td>
        <td class="ui-state-default" align="center" id="BLOCK_IP_T_TLY_<?php echo $k;?>"><?php echo (($isBlank)?'':(intval($v['ACHIEVEMENT_IP_TLY']['IP'])+intval($v['ACHIEVEMENT_IP_CFY']['IP'])));?></td>
      </tr>
  <?php } ?>
    <tr>
      <td class="ui-state-default" colspan="4" rowspan="3">Total Irrigation Potential Created</td>
      <td><strong>Kharif</strong></td>
      <td class="ui-widget-content" align="center" id="IP_ESTI_K"><?php echo $arrTotal['KHARIF']['ESTIMATION'];?></td>
      <td class="ui-widget-content" align="center" id="IP_CUR_MONTH_K"><?php echo $arrTotal['KHARIF']['CURRENT_MONTH'];?></td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotal['KHARIF']['PREV_MONTH'];?></td>
      <td class="ui-widget-content" align="center" id="IP_CFY_K"><?php echo $arrTotal['KHARIF']['CFY'];?></td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotal['KHARIF']['TLY'];?></td>
      <td class="ui-widget-content" align="center" id="IP_TOTAL_K"><?php echo $arrTotal['KHARIF']['TOTAL'];?></td>
    </tr>
    <tr>
      <td ><strong>Rabi</strong></td>
      <td class="ui-widget-content" align="center" id="IP_ESTI_R"><?php echo $arrTotal['RABI']['ESTIMATION'];?></td>
      <td class="ui-widget-content" align="center" id="IP_CUR_MONTH_R"><?php echo $arrTotal['RABI']['CURRENT_MONTH'];?></td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotal['RABI']['PREV_MONTH'];?></td>
      <td class="ui-widget-content" align="center" id="IP_CFY_R"><?php echo $arrTotal['RABI']['CFY'];?></td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotal['RABI']['TLY'];?></td>
      <td class="ui-widget-content" align="center" id="IP_TOTAL_R"><?php echo $arrTotal['RABI']['TOTAL'];?></td>
    </tr>
    <tr>
      <td class="ui-state-default" ><strong>Total</strong></td>
      <td class="ui-state-default" align="center"><?php echo $arrTotal['TOTAL']['ESTIMATION'];?></td>
      <td class="ui-state-default" align="center" id="IP_CUR_MONTH_T"><?php echo $arrTotal['TOTAL']['CURRENT_MONTH'];?></td>
      <td class="ui-state-default" align="center"><?php echo $arrTotal['TOTAL']['PREV_MONTH'];?></td>
      <td class="ui-state-default" align="center" id="IP_CFY_T"><?php echo $arrTotal['TOTAL']['CFY'];?></td>
      <td class="ui-state-default" align="center"><?php echo $arrTotal['TOTAL']['TLY'];?></td>
      <td class="ui-state-default" align="center" id="IP_TOTAL_T"><?php echo $arrTotal['TOTAL']['TOTAL'];?></td>
    </tr>
  <?php 
  //} 
?>
  </tbody>
</table>

<!-- FIXED FORMAT END -->
<?php
$arrF = array(
	'LA_CASES_STATUS',  'FA_CASES_STATUS', 'INTAKE_WELL_STATUS', 'PUMPING_UNIT_STATUS',
	'PVC_LIFT_SYSTEM_STATUS','PIPE_DISTRI_STATUS', 'DRIP_SYSTEM_STATUS', 'WATER_STORAGE_TANK_STATUS',
	'FERTI_PESTI_CARRIER_SYSTEM_STATUS', 'CONTROL_ROOMS_STATUS'
);
$arrComponentStatus = $arrComponentsIncluded = array();
$arrShow = array();
foreach($arrF as $f){
	if($isCurrentMonthExists){
		if($arrCurrentMonthData[$f]==0){
			 $arrComponentStatus[$f] = 0;//$prevMonthStatus[$f];
		}else if($prevMonthStatus[$f]==1 || $prevMonthStatus[$f]==0){
			$arrComponentStatus[$f] = 1;//'NA';
		}else{
			if($arrCurrentMonthData[$f]==1){
				if($prevMonthStatus[$f]>1)
					$arrComponentStatus[$f] = $prevMonthStatus[$f];
				else
					$arrComponentStatus[$f] = 1;
			}else{
				$arrComponentStatus[$f] = $arrCurrentMonthData[$f];
			}
		}
	}else{
		//if($prevMonthStatus[$f]==5 || $prevMonthStatus[$f]==1  || $prevMonthStatus[$f]==0){
		if($prevMonthStatus[$f]==1  || $prevMonthStatus[$f]==0){
			$arrComponentStatus[$f] = 1;//'NA';
		}else{
			$arrComponentStatus[$f] = 0;// $prevMonthStatus[$f];
		}
	}
	if($arrComponentStatus[$f]==1){
		$arrShow[$f] = FALSE;
	}else{
		if($prevMonthStatus[$f]==5 || $prevMonthStatus[$f]==1){
			$arrShow[$f] = FALSE;
		}else{
			$arrShow[$f] = TRUE;
		}
	}
	if($arrShow[$f]==FALSE){
		if(!$isCurrentMonthExists){
			$arrComponentStatus[$f] = 0;
		}
	}
	if($arrShow[$f])
		array_push($arrComponentsIncluded, $f);
}
//showArrayValues($arrComponentStatus);
$arrRemarkShow = array();
//$status_options = 0 1'NA', 2'Not Started', 3'Ongoing', 4'Stopped', 5'Completed', 6'Dropped'
foreach($arrF as $f){
	$arrRemarkShow[$f] = 0;
	if($isCurrentMonthExists){
		if(	$arrComponentStatus[$f]==2 || 
			$arrComponentStatus[$f]==4 ){
				$arrRemarkShow[$f] = 1;
		}else{
			$arrRemarkShow[$f] = 0;
		}
		//2-not started 4-stopped
		/*if( ($arrCurrentMonthData[$arrF[$i]]==2) || ($arrCurrentMonthData[$arrF[$i]]==4) ){
			if($prevMonthStatus[$arrF[$i]]==1){
				$arrRemarkShow[$arrF[$i]] = 0;
			}else{
				$arrRemarkShow[$arrF[$i]] = 1;
			}
		}*/
	}else{
		//if($prevMonthStatus){
		//if( ($prevMonthStatus[$arrF[$i]]==2) || ($prevMonthStatus[$arrF[$i]]==4) )
			$arrRemarkShow[$f] = 0;
	}
}

/////////////////////// [ see below to delete ]
if($PROJECT_SETUP_ID==0){
	showArrayValues($arrRemarkShow);
	showArrayValues($arrComponentStatus);
}
$i=0;
//showArrayValues($status_options);
$arrHideRemarks = array(2,4);
?>
<div class="wrdlinebreak"></div>
<table width="100%" border="0" cellpadding="6" cellspacing="1" class="ui-widget-content">
<tr>
    <td rowspan="2" width="33%" class="ui-state-default">2] Status of Milestone</td>
    <td colspan="2" width="33%" align="center" class="ui-state-default"><strong>Status</strong></td>
    <td rowspan="2" width="33%" align="center" class="ui-state-default"><strong>Remarks</strong></td>
</tr>
<tr>
  <td align="center" class="ui-state-default">Previous Month</td>
  <td align="center" class="ui-state-default">Current Month</td>
</tr>
<?php if($arrShow['LA_CASES_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>a. Submission of LA Cases</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['LA_CASES_STATUS']]?></td>
    <td class="ui-widget-content" align="center">
	<select name="LA_CASES_STATUS" id="LA_CASES_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['LA_CASES_STATUS'], $prevMonthStatus['LA_CASES_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
        <td class="ui-widget-content" align="center">
        <div id="LA_CASES_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['LA_CASES_STATUS'], $arrHideRemarks))?'block':'none';?>">
        <textarea id="LA_CASES_STATUS_REMARK" name="LA_CASES_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['LA_CASES_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>a. Submission of LA Cases</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['FA_CASES_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>b. Forest Cases </strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['FA_CASES_STATUS']]?></td>
    <td class="ui-widget-content" align="center">
	<select name="FA_CASES_STATUS" id="FA_CASES_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['FA_CASES_STATUS'], $prevMonthStatus['FA_CASES_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
    <td class="ui-widget-content" align="center">
        <div id="FA_CASES_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['FA_CASES_STATUS'], $arrHideRemarks))? 'block':'none';?>">
        <textarea id="FA_CASES_STATUS_REMARK" name="FA_CASES_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['FA_CASES_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>b. Forest Cases </strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['INTAKE_WELL_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>c. Intake Well</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['INTAKE_WELL_STATUS']];?></td>
    <td class="ui-widget-content" align="center">
	<select name="INTAKE_WELL_STATUS" id="INTAKE_WELL_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['INTAKE_WELL_STATUS'], $prevMonthStatus['INTAKE_WELL_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
    <td class="ui-widget-content" align="center">
        <div id="INTAKE_WELL_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['INTAKE_WELL_STATUS'], $arrHideRemarks))? 'block':'none';?>">
        <textarea id="INTAKE_WELL_STATUS_REMARK" name="INTAKE_WELL_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['INTAKE_WELL_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>c. Intake Well</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['PUMPING_UNIT_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>d. Pumping Unit</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['PUMPING_UNIT_STATUS']];?></td>
    <td class="ui-widget-content" align="center">
	<select name="PUMPING_UNIT_STATUS" id="PUMPING_UNIT_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['PUMPING_UNIT_STATUS'], $prevMonthStatus['PUMPING_UNIT_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
    <td class="ui-widget-content" align="center">
        <div id="PUMPING_UNIT_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['PUMPING_UNIT_STATUS'], $arrHideRemarks))?'block':'none';?>">
        <textarea id="PUMPING_UNIT_STATUS_REMARK" name="PUMPING_UNIT_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['PUMPING_UNIT_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>d. Pumping Unit</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['PVC_LIFT_SYSTEM_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>e. PVC Lift System</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['PVC_LIFT_SYSTEM_STATUS']];?></td>
    <td class="ui-widget-content" align="center">
	<select name="PVC_LIFT_SYSTEM_STATUS" id="PVC_LIFT_SYSTEM_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['PVC_LIFT_SYSTEM_STATUS'], $prevMonthStatus['PVC_LIFT_SYSTEM_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
    <td class="ui-widget-content" align="center">
        <div id="PVC_LIFT_SYSTEM_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['PVC_LIFT_SYSTEM_STATUS'], $arrHideRemarks))? 'block':'none';?>">
        <textarea id="PVC_LIFT_SYSTEM_STATUS_REMARK" name="PVC_LIFT_SYSTEM_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['PVC_LIFT_SYSTEM_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>e. PVC Lift System</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['PIPE_DISTRI_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>f. Pipe Distribution Network</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['PIPE_DISTRI_STATUS']];?></td>
    <td class="ui-widget-content" align="center">
	<select name="PIPE_DISTRI_STATUS" id="PIPE_DISTRI_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['PIPE_DISTRI_STATUS'], $prevMonthStatus['PIPE_DISTRI_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
    <td class="ui-widget-content" align="center">
        <div id="PIPE_DISTRI_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['PIPE_DISTRI_STATUS'], $arrHideRemarks))?'block':'none';?>">
        <textarea id="PIPE_DISTRI_STATUS_REMARK" name="PIPE_DISTRI_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['PIPE_DISTRI_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>f. Pipe Distribution Network</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['DRIP_SYSTEM_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>g. Drip System</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['DRIP_SYSTEM_STATUS']];?></td>
    <td class="ui-widget-content" align="center">
	<select name="DRIP_SYSTEM_STATUS" id="DRIP_SYSTEM_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['DRIP_SYSTEM_STATUS'], $prevMonthStatus['DRIP_SYSTEM_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
    <td class="ui-widget-content" align="center">
        <div id="DRIP_SYSTEM_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['DRIP_SYSTEM_STATUS'], $arrHideRemarks))?'block':'none';?>">
        <textarea id="DRIP_SYSTEM_STATUS_REMARK" name="DRIP_SYSTEM_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['DRIP_SYSTEM_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>g. Drip System</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['WATER_STORAGE_TANK_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>h. Water Storage Tank</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['WATER_STORAGE_TANK_STATUS']];?></td>
    <td class="ui-widget-content" align="center">
	<select name="WATER_STORAGE_TANK_STATUS" id="WATER_STORAGE_TANK_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['WATER_STORAGE_TANK_STATUS'], $prevMonthStatus['WATER_STORAGE_TANK_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
    <td class="ui-widget-content" align="center">
        <div id="WATER_STORAGE_TANK_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['WATER_STORAGE_TANK_STATUS'], $arrHideRemarks))? 'block':'none';?>">
        <textarea id="WATER_STORAGE_TANK_STATUS_REMARK" name="WATER_STORAGE_TANK_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['WATER_STORAGE_TANK_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>h. Water Storage Tank</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['FERTI_PESTI_CARRIER_SYSTEM_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>i. Fertilizer and Pesticide Carrier System</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['FERTI_PESTI_CARRIER_SYSTEM_STATUS']];?></td>
    <td class="ui-widget-content" align="center">
	<select name="FERTI_PESTI_CARRIER_SYSTEM_STATUS" id="FERTI_PESTI_CARRIER_SYSTEM_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['FERTI_PESTI_CARRIER_SYSTEM_STATUS'], $prevMonthStatus['FERTI_PESTI_CARRIER_SYSTEM_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
    <td class="ui-widget-content" align="center">
        <div id="FERTI_PESTI_CARRIER_SYSTEM_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['FERTI_PESTI_CARRIER_SYSTEM_STATUS'], $arrHideRemarks))?'block':'none';?>">
        <textarea id="FERTI_PESTI_CARRIER_SYSTEM_STATUS_REMARK" name="FERTI_PESTI_CARRIER_SYSTEM_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['FERTI_PESTI_CARRIER_SYSTEM_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>i. Fertilizer and Pesticide Carrier System</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>
<?php if($arrShow['CONTROL_ROOMS_STATUS']){?>
<tr>
    <td class="ui-widget-content"><strong>j. Control Rooms</strong></td>
    <td class="ui-widget-content" align="center"><?php echo $status_options[$prevMonthStatus['CONTROL_ROOMS_STATUS']];?></td>
    <td class="ui-widget-content" align="center">
	<select name="CONTROL_ROOMS_STATUS" id="CONTROL_ROOMS_STATUS" style="width:150px" class="mysel2" 
    	onchange="setComponentStatus(this.name)" >
        <?php echo getWorkStatusOptions(1, $arrComponentStatus['CONTROL_ROOMS_STATUS'], $prevMonthStatus['CONTROL_ROOMS_STATUS']);?>
	</select>
	<?php //echo $arrComponentStatus['LA_CASES_STATUS'];?>
    </td>
	<td class="ui-widget-content" align="center">
        <div id="CONTROL_ROOMS_STATUSremarkDiv" style="display:<?php echo (in_array($arrComponentStatus['CONTROL_ROOMS_STATUS'], $arrHideRemarks))? 'block':'none';?>">
        <textarea id="CONTROL_ROOMS_STATUS_REMARK" name="CONTROL_ROOMS_STATUS_REMARK" cols="35" 
            rows="2"><?php echo $monthly_remarks['CONTROL_ROOMS_STATUS_REMARK'];?></textarea>
        </div>
	</td>
</tr>
<?php }else{?>
<tr>
    <td class="ui-widget-content"><strong>j. Control Rooms</strong></td>
    <td class="ui-state-dim" align="center" colspan="3">NA</td>
</tr>
<?php }?>

</table>
</form>
<div id="mySaveMonthlyDiv" align="right" class="mysavebar">
<?php echo $buttons;?>
</div>
</div>
</div>
<script language="javascript">
//
var arrMonthlyData = {<?php echo implode(',', $arrMonthlyData);?>};
var arrMonthlyBlockData = {<?php echo implode(',', $arrMonthlyBlockData);?>};

function getMyValue(id, mode){
	var num1 = parseFloat( $('#' + id).val() );
	return ((isNaN(num1))?0:num1);
}

//
var validator;
var arrBenefitedBlocks = new Array();
$().ready(function(){
	$(".mysel2").select2();
	$('#COMPLETION_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({ 
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true,
		beforeShow: function(input, inst) {	return setMinMaxDate('#START_MONTH_DATE', '#END_MONTH_DATE'); }
	});
	
	//setSelect2();//always use this for validation Engine
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
	if($arrCurrentMonthData['WORK_STATUS']==5){
		echo '$("#COMPLETION_DATE").rules("add", "required");';
		echo '$("#WORK_STATUS_REMARK").rules("add", "required");';
	}
	foreach($arrRemarkShow as $k=>$v){
		if($v==1){
			echo '$("#'.$k.'_REMARK").rules("add", "required");';
		}
	}?>
	setProjectStatus(<?php echo $arrCurrentMonthData['WORK_STATUS'];?>);
	var $demoabs = $('#monthly_table');
	$demoabs.floatThead({
		scrollContainer: function($table){
			return $table.closest('.wrapper');
		}
	});
	
	//$( "#radioset" ).buttonset();
});
//

function setProjectStatus(pstatus){
<?php echo "var noOfComponents = ".count($arrComponentsIncluded).";\n";
echo "var chkCompoFields = new Array('". implode("', '", $arrComponentsIncluded)."');";?>
	//alert();
	var ps = parseInt($('#WORK_STATUS').val());
	$('#completionDiv').hide();
	$('#completionDiv1').hide();
	$("#COMPLETION_DATE").rules("remove", "required");
	//$('#remarkDiv').hide();
	$("#WORK_STATUS_REMARK").rules("remove", "required");
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
				$("#WORK_STATUS_REMARK").rules("remove", "required");
			}else{
				//alert(2);
				$('#divRemarks').show();
				$('#remarkDiv').show();
				$("#WORK_STATUS_REMARK").rules("add", "required");
			}
			mEnableIt = true;
			break;
		case 4://stopped 
			$('#divRemarks').show();
			mEnableIt = true;
		case 2://not started
			$('#remarkDiv').show();
			$('#divRemarks').show();
			$("#WORK_STATUS_REMARK").rules("add", "required");
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
			$("#WORK_STATUS_REMARK").rules("add", "required");
			$('#divCompletionType').show();
			
			mEnableIt = true;
			checkCompletionOnSubmit();
			$('html, body').animate({scrollTop: $("#completionDiv1").offset().top}, 2000);
			//$("#WORK_STATUS_REMARK").rules("remove", "required");
			break;
		case 6://dropped
			mEnableIt = false;
			$('#divRemarks').show();
			$('#remarkDiv').show();
			$('#divStatus').show();
			$('#completedDateCaption').html('Dropped Date');
			$('#completedNoCaption').html('Dropped Memo No');
			$("#WORK_STATUS_REMARK").rules("add", "required");
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
			//alert('cjk:' + chkCompoFields[i]);
			//console.log('chk' + i + ':' + chkCompoFields[i]);
			$('#'+chkCompoFields[i]).select2("enable", mEnableIt);
			//console.log('chk' + i + 'OK');
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
	//console.log('ps:'+ps + ' compo:' + compo);
	//alert('cccc:' + compo + ' ps:' + ps);
	$('#'+ compo +'remarkDiv').hide();
	$("#" + compo + '_REMARK').rules("remove", "required");
	switch(ps){
		case 1://NA 
			break;
		case 2://not started
			$('#'+ compo +'remarkDiv').show();
			$("#" + compo + '_REMARK').rules("add", "required");
			break;
		case 3://ongoing
			break;
		case 4://stopped
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
			$("#WORK_STATUS_REMARK").rules("add", "required");
			return false;
		}
	}
	return false;
}

//
function saveMonthly(){
    //console.log('asdasdfasdf');
    
    // 27-04-2020  // If ework_project_status is 0(zero) then projects can not be saved as completed
    //[code starts]
    var ework_project_status ="<?php echo $EWORK_PROJECT_STATUS;?>";
    var ps = $('#WORK_STATUS').val();
    var ctype = $('input[name="COMPLETION_TYPE"]:checked').val();
    if(ps==5 && ework_project_status ==1 && ctype==1){
        showAlert('Error...', 'आपके द्वारा उपरोक्त कार्य में epay सिस्टम में कुछ राशि बकाया है अतः इस कार्य को financially complete नहीं किया जा सकता  है।', 'warn');
        return false;
    }

	var selectList = new Array();
	//selectList.push( Array('WORK_STATUS', 'Select Project Status', true) );
	var ps = parseInt($('#WORK_STATUS').val());
	if(ps<6){
        <?php
        $arrComponents = array(
            'LA_CASES_STATUS'=>'Select LA Case Status',
            'FA_CASES_STATUS'=>'Select FA Case Status',
            'INTAKE_WELL_STATUS'=>'Select Intake Well Status',
            'PUMPING_UNIT_STATUS'=>'Select Pumping Unit Status',
            'PVC_LIFT_SYSTEM_STATUS'=>'Select PVC Lift System Status',
            'PIPE_DISTRI_STATUS'=>'Select Pipe Distribution Network Status',
            'DRIP_SYSTEM_STATUS'=>'Select Drip System Status',
            'WATER_STORAGE_TANK_STATUS'=>'SelectWater Storage Tank Status',
            'FERTI_PESTI_CARRIER_SYSTEM_STATUS'=>'Select  Fertilizer and Pesticide Carrier System Status',
            'CONTROL_ROOMS_STATUS'=>'Select  Control Rooms Status'
        );
        $contCompo = '';
        $i=1;
        //print_r($arrComponentsIncluded);
        foreach($arrComponentsIncluded as $compo){
            $contCompo .= "selectList.push( Array('".$compo."', '".$arrComponents[$compo]."', true));\n";
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

	selectList.push( Array('WORK_STATUS', 'Select Work Status',true));
	//console.log(selectList);
	var mSelect = validateMyCombo(selectList);
	var myValidation = $("#frmMonthly").valid();
	if( !(mSelect==0 && myValidation)){
		showAlert('Oops...', 'You have : ' + ( window.validator.numberOfInvalids() + mSelect ) + ' errors in this form.', 'error');
		return ;
	}
	if(myValidation){
		var ps = parseInt($('#WORK_STATUS').val());
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
				showAlert('Oops...', 'Please Check Component Status...', 'error');
				return false;
			}
			if(!checkCompletionOnSubmit()){
				if ($('#remarkDiv').css('display') == 'none') {
					showAlert('Oops...', 'Achievement Data is not equals to Estimation Data...' +
					 "\n" + 'Please Enter Remarks given below the Completion Date', 'error');
					 return;
				}else{
					if ( $('#WORK_STATUS_REMARK').val()==""){
						showAlert('Oops...', 'Project Status Remark is blank', 'error');
						return;
					}
				}
			}
			var SelectedType = 0;
			SelectedType = (($('#radio1').is(':checked')) ? 1:0) ;
			if(SelectedType==0)
				SelectedType = (($('#radio2').is(':checked')) ? 2:0) ;
			if(SelectedType==0){
				showAlert('Oops...', 'Select Completion Type...', 'error');
				return;
			}else{
				var message = new Array();
				var iptK = checkNo($('#IP_TOTAL_K').val());
				var iptR = checkNo($('#IP_TOTAL_R').val());
				var ipeK = checkNo($('#IP_ESTI_K').val());
				var ipeR = checkNo($('#IP_ESTI_R').val());
				if((iptK!=ipeK) || (iptR!=ipeR)){
					message.push("Irrigation Potential Achieved is not equal to Estimation\n");
				}
				//alert('iptK:' + iptK + ' ipeK:' +ipeK + 'iptR:' + iptR + ' ipeR:' +ipeR);
				if(iptK!=ipeK)
					message.push("Kharif => Estimation : " + ipeK + " Total Achieved : " + iptK);
				if(iptR!=ipeR)
					message.push("Rabi => Estimation : " + ipeR + " Total Achieved : " + iptR);

				if(message.length!=0) {
					showAlert('Oops...', message.join("\n"), 'error');
					return;
				}
			}
			if(SelectedType==2){
				var lapayment = (($('#LA_PAYMENT').is(':checked')) ? 1:0) ;
				var fapayment = (($('#FA_PAYMENT').is(':checked')) ? 1:0) ;
				var capayment = (($('#CL_PAYMENT').is(':checked')) ? 1:0) ;
				if( (lapayment==0) &&  (fapayment==0) &&  (capayment==0) ){
					showAlert('Oops...', 'Select Reason...', 'error');
					return;
				}
			}
		}
		var params = {
			'divid':'mySaveMonthlyDiv', 
			'url':'saveMonthlyDataMi', 
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
	<?php  echo 'var arrB = ['.implode(',', $arrBenefitedBlocks).'];';?>
	var curMonthK = cfyK = tlyK = curMonthR = cfyR = tlyR = curMonthT = cfyT = tlyT = 0;
	for(i=0;i<arrB.length;i++){
		var blockId = arrB[i];
		//alert(blockId);
		var ss = window.arrMonthlyBlockData[blockId]['KHARIF']['ESTI'];
		//kharif
		var curMonthBk = checkNo($('#BLOCK_IP_K_'+blockId).val());
		var cfyBk = checkNo(window.arrMonthlyBlockData[blockId]['KHARIF']['CFY']);
		var tlyBk = checkNo(window.arrMonthlyBlockData[blockId]['KHARIF']['TLY']);
		var totalcfyBk = cfyBk + curMonthBk;
		var totaltlyBk = totalcfyBk + tlyBk;
		$('#BLOCK_IP_K_CFY_'+blockId).html(totalcfyBk);
		$('#BLOCK_IP_K_TLY_'+blockId).html(totaltlyBk);
		curMonthK += curMonthBk;
		cfyK += totalcfyBk;
		tlyK += totaltlyBk; 
		//rabi
		var curMonthBr = checkNo($('#BLOCK_IP_R_'+blockId).val());
		var cfyBr = checkNo(window.arrMonthlyBlockData[blockId]['RABI']['CFY']);
		var tlyBr = checkNo(window.arrMonthlyBlockData[blockId]['RABI']['TLY']);
		var totalcfyBr = cfyBr + curMonthBr;
		var totaltlyBr = totalcfyBr + tlyBr;
		$('#BLOCK_IP_R_CFY_'+blockId).html(totalcfyBr);
		$('#BLOCK_IP_R_TLY_'+blockId).html(totaltlyBr);
		curMonthR += curMonthBr;
		cfyR += totalcfyBr;
		tlyR += totaltlyBr;
		//total
		$('#BLOCK_IP_T_'+blockId).html(curMonthBk+curMonthBr);
		$('#BLOCK_IP_T_CFY_'+blockId).html(totalcfyBk+totalcfyBr);
		$('#BLOCK_IP_T_TLY_'+blockId).html(totaltlyBk+totaltlyBr);
		curMonthT += (curMonthBk+curMonthBr);
		cfyT += (totalcfyBk+totalcfyBr);
		tlyT += (totaltlyBk+totaltlyBr);
	}
	$('#IP_CUR_MONTH_K').html(curMonthK);
	$('#IP_CFY_K').html(cfyK);
	$('#IP_TOTAL_K').html(tlyK);
	
	$('#IP_CUR_MONTH_R').html(curMonthR);
	$('#IP_CFY_R').html(cfyR);
	$('#IP_TOTAL_R').html(tlyR);
	
	$('#IP_CUR_MONTH_T').html(curMonthT);
	$('#IP_CFY_T').html(cfyT);
	$('#IP_TOTAL_T').html(tlyT);
	
	
	return;
	<?php 
	if(!$isEstimationExists){?>
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
	$('#KHARIF').val(sumIPK);
	$('#divKHARIF').html(sumIPK);
	$('#RABI').val(sumIPR);
	$('#divRABI').html(sumIPR);
	$('#IP_TOTAL').val(sumIPK+sumIPR);
	$('#divIP_TOTAL').html(sumIPK+sumIPR);
	<?php if(!$isEstimationExists){?>
		//$('#eIRRIGATION_POTENTIAL_KHARIF').val(sumEIPK);
		$('#diveKHARIF').html(sumEIPK);
		//$('#eIRRIGATION_POTENTIAL_RABI').val(sumEIPR);
		$('#diveRABI').html(sumEIPR);
		//$('#eIRRIGATION_POTENTIAL').val(sumEIP);
		$('#diveIP_TOTAL').html(sumEIPK+sumEIPR);
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
	
	$('#KHARIF_T').val(sumCIPK + sumIPK + sumTIPK);
	$('#RABI_T').val(sumCIPR + sumIPR + sumTIPR);
	$('#IP_TOTAL_T').html(sumCIPK + sumIPK + sumTIPK + sumCIPR + sumIPR + sumTIPR);
}
//
function calculate(ids){
	var arrInt = new Array('LA_NO', 'LA_COMPLETED_NO', 'C_PIPEWORK', 'K_CONTROL_ROOMS', 'C_WATERPUMP', 'C_DRIP_PIPE');
//	var obj1 = window.arrMonthlyData.ids;
	var currentMonth = getMyValue(ids);
	var cfy = checkNo(window.arrMonthlyData[ids]['CFY']);
	var tly = checkNo(window.arrMonthlyData[ids]['TLY']);
	var totalCFY = currentMonth + cfy;
	var total = totalCFY + tly;
	var decimals = ($.inArray(ids, arrInt)>=0) ? 0:2;
	$('#divCFY_' + ids).html(roundNumber(totalCFY, decimals));
	$('#divTOTAL_' + ids).html(roundNumber(total, decimals));
	return;
	var currentMonth = getMyValue(ids);
	//var previousMonth = getMyValue( (ids + '_P'));
	var currentFY = getMyValue( (ids + '_CFY_H') );
	var totalCurrentFY = roundNumber((currentFY + currentMonth), decimals);
	
	$('#divCFY_' + ids).html(roundNumber(totalCurrentFY, decimals));
	$('#divTOTAL_' + ids).html(roundNumber(totalCurrentFY, decimals));
	
	$('#' + ids + '_CFY').val(roundNumber(totalCurrentFY, decimals));
	$('#div_' + ids + '_CFY').html(roundNumber(totalCurrentFY, decimals));
	//alert(roundNumber(totalCurrentFY, 2));
	var cummulativeTotal = (totalCurrentFY + getMyValue((ids+'_TLY')));
	$('#' + ids + '_T').val( roundNumber(cummulativeTotal, decimals));
	$('#div_' + ids + '_T').html( roundNumber(cummulativeTotal, decimals));
}
jQuery.validator.addMethod("checkMyDigit", function(value, element, params) {
	return true;
	var nodecimals = new String(value | 0); //truncate the decimal part (no rounding)
	var lengthOfNo = nodecimals.length;
	var nodecimalsEsti = new String($('#'+element.id+'_esti').html() | 0);
	var lengthOfEsti = nodecimalsEsti.length;
	//alert(':' + lengthOfNo  + ':' + lengthOfEsti);
	//esti-3  no-12
	return ((lengthOfNo <= (lengthOfEsti+1))? true:false);
}, jQuery.validator.format("आपकी प्रविष्टि की गई मात्रा बहुत अधिक हो गयी है."));
//
</script>
