<?php error_reporting(0); 
$myheight = ($isBlank) ? 'height="30px"':'';?>
<?php echo getPrintButton('prjmonthly_report', 'Print', 'xprjmonthly_report');?>
<div id="prjmonthly_report">
<?php $statusOptions = array(' ', 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 'Dropped');
$mon = array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug","Sep", "Oct", "Nov", "Dec");
function getStatusOptions($prevStatus){
	$status = '';
	$arrData = array();
	switch($prevStatus){
		case 0: 
		case 1: $status = 'NA'; break;
		case 2: //not started
			$arrData = array('Ongoing', 'Not Started');break;
		case 3: //ongoing
			$arrData = array('Ongoing', 'Stopped', 'Completed');break;
		case 4: //stopped
		 	$arrData = array('Ongoing', 'Stopped', 'Completed');break;
		case 5: //completed
			$status = 'Completed';break;
	}
	if($arrData){
		for($i=0;$i<count($arrData);$i++){
			$status .= '<big>&#x25a2;</big><strong> '.$arrData[$i].'</strong> <br />';
		}
	}
	return $status;
}
function getWorkStatusOptions($Sel=0, $prevMonthStatus=0){
    $myOptions ='';
	//type 0-Work Status
	/*if($type==0){
		$statusOptionValues = array('NA'=>1, 'Not Started'=>2,'Ongoing'=>3, 'Stopped'=>4, 'Completed'=>5);
	}else{
		$statusOptionValues = array('NA'=>1, 'Not Started'=>2, 'Ongoing'=>3, 'Stopped'=>4, 'Completed'=>5);
	}*/
	switch($prevMonthStatus){
		case 2: //Not started
			$filteredStatus = array('Not Started', 'Ongoing', 'Stopped');
			break;
		case 3: //Ongoing
			$filteredStatus = array('Ongoing', 'Stopped', 'Completed');
			break;
		case 4: //Stopped
			$filteredStatus = array('Ongoing', 'Stopped');
			break;
		case 7: //Current Year AA
			$filteredStatus = array('Not Started', 'Ongoing', 'Stopped');
			break;
		case 5: //completed
			$filteredStatus = array('Completed');
			break;
		default : 
			$filteredStatus = array('Ongoing', 'Stopped');
			break;
	}
	$s = array();;
	//showArrayValues($filteredStatus);
	foreach($filteredStatus as $f){
		array_push($s, '<big>&#x25a2;</big> '.$f);
	}
	return implode('<br />', $s);
}
?>
<table width="100%" border="0" cellpadding="3" cellspacing="2"  class="ui-widget-content" id="xprjmonthly_report">
<tr>
    <td class="ui-widget-content" align="center" colspan="5"><strong><big><big><?php echo $PROJECT_NAME;?></big></big></strong></td>
</tr>
<tr>
    <td nowrap="nowrap" class="ui-state-default"><strong>Code</strong></td>
    <td class="ui-widget-content" colspan="2"><strong><?php echo $PROJECT_CODE;?></strong></td>
    <td class="ui-state-default"><strong>Month</strong></td>
    <td align="center" class="ui-widget-content"><strong><?php echo date('F, Y', $MONTH_DATE);?></strong></td>
</tr>
<tr>
    <td nowrap="nowrap" class="ui-state-default"><strong>Status of Scheme</strong></td>
    <td class="ui-widget-content" colspan="2" width="150" nowrap="nowrap">
        <?php if($isBlank){?>
        <big>&#x25a2;</big><strong> Ongoing</strong> &nbsp;
        <big>&#x25a2;</big><strong> Stopped</strong> &nbsp;
        <big><br />
        &#x25a2;</big><strong> Completed</strong>
        <big>&#x25a2;</big><strong> Dropped</strong>
    <?php }else{ 
        echo $statusOptions[$arrCurrentMonthData['WORK_STATUS']];
      }?>
    </td>
    <td class="ui-state-default" width="180">Proposed Completion Date : <br />(As Per Target)</td>
    <td align="center" class="ui-widget-content"><strong><?php echo myDateFormat($ACTUAL_COMPLETION_DATE);?></strong></td>
</tr>

<?php if($isBlank){?>
<tr>
    <td class="ui-state-default">Completion Date :</td>
    <td class="ui-widget-content" colspan="2" <?php echo $myheight;?>></td>
    <td class="ui-state-default">Completion Certificate No.:</td>
    <td class="ui-widget-content" width="140"></td>
</tr>
<tr>
    <td nowrap="nowrap" class="ui-state-default"><strong>Completion Type</strong></td>
    <td class="ui-widget-content" colspan="4"  nowrap="nowrap">
        <big>&#x25a2;</big><strong> Physically & Financially Completed</strong> &nbsp; &nbsp;
        <big>&#x25a2;</big><strong> Physically Completed but Financially not Completed</strong> 
    </td>
</tr>
<tr>
    <td nowrap="nowrap" class="ui-state-default">Financially not completed due to</td>
    <td class="ui-widget-content" colspan="4"><big>&#x25a2; </big> LA Payment &nbsp; &nbsp;  <big>&#x25a2; </big> FA Payment  &nbsp; &nbsp; <big>&#x25a2; </big> Liabilities of Contractor</td>
</tr>
<tr>
    <td class="ui-state-default">Remarks</td>
    <td class="ui-widget-content" colspan="4"><big><big>&nbsp;</big></big></td>
</tr>

<?php 
}else{
	//completionStatusData
	$projectStatus = $arrCurrentMonthData['WORK_STATUS'];
	if($projectStatus==5){
		$arrCompletionType = array('', 'Physically & Financially Completed', 'Physically Completed but Financially not Completed');
		$strPayType ='';
		if($completionStatusData['COMPLETION_TYPE']==2){
			$payType = $completionStatusData['LA_PAYMENT'].$completionStatusData['FA_PAYMENT'].$completionStatusData['CL_PAYMENT'];
			if($payType=='100'){
				$strPayType = 'LA Payment';
			}else if($payType=='010'){
				$strPayType = 'FA Payment';
			}else if($payType=='001'){
				$strPayType = 'Liabilities of Contractor';
			}else if($payType=='110'){
				$strPayType = 'LA Payment and FA Payment';
			}else if($payType=='101'){
				$strPayType = 'LA Payment and Liabilities of Contractor';
			}else if($payType=='011'){
				$strPayType = 'FA Payment and Liabilities of Contractor';
			} 
			$strPayType = ' Due to <strong>'.$strPayType.'</strong>' ;
		}
	}//if
	//echo '::'.$completionStatusData['COMPLETION_TYPE'].$strPayType.'::';
	$remarkDiv = false;
	switch($projectStatus){
		case 4:case 2: case 5:case 6:
			$remarkDiv = true; break;
	}//switch
	if($projectStatus==5 || $projectStatus==6 || $remarkDiv){?>
<tr>
    <td nowrap="nowrap" class="ui-widget-content" colspan="5">
		<?php 
		if($projectStatus==5 || $projectStatus==6){
			if($projectStatus==5){?>
            <table width="100%" border="0" cellpadding="3" cellspacing="2">
            <tr>
                <td align="left" class="ui-state-default" width="140px">Completion Type</td>
                <td align="left" class="ui-widget-content"><?php echo $arrCompletionType[$completionStatusData['COMPLETION_TYPE']]. $strPayType;?></td>
            </tr>
            </table>
			<?php }//if?>
        <table width="100%" border="0" cellpadding="3" cellspacing="2">
        <tr>
        	<td align="left" class="ui-state-default" width="140px">
            <?php if ($projectStatus==5) echo 'Completion Date';
				if ($projectStatus==6) echo 'Drop Date';?>
            </td>
          	<td align="center" class="ui-widget-content"><?php echo myDateFormat($arrCurrentMonthData['COMPLETION_DATE']);?></td>
            <td align="left" class="ui-state-default">
				<?php if ($projectStatus==5) echo 'Completion Certificate No';
				if($projectStatus==6) echo 'Memo No';?>
            </td>
			<td align="center" class="ui-widget-content"><?php echo $monthlyStatusData['PROJECT_STATUS_DISPATCH_NO'];?></td>
		</tr>
		</table>
        <?php }//if($projectStatus==5)
		if($remarkDiv){?>
        <table width="100%" border="0" cellpadding="3" cellspacing="2">
        <tr>
            <td align="left" class="ui-state-default" width="140px">Remark </td>
            <td align="left" class="ui-widget-content"><?php echo $monthly_remarks['PROJECT_STATUS_REMARK'];?></td>
        </tr>
        </table>
        <?php }?>
	</td>
</tr>
<?php }//if($projectStatus==5 || $projectStatus==6)
}?>
</table>

<div class="wrdlinebreak"></div>

<?php 
//showArrayValues($arrSetupStatus);
$arrData = array(
	'LA_NO'=>array(
		'ESTIMATION'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrEstimationData['LA_NO']),
		'MONTHLY'=>(($arrSetupStatus['LA_NA']) ? 'NA': (($isBlank)?'':$arrCurrentMonthData['LA_NO'])),
		'PREV_MONTH'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrPreviousMonthData['LA_NO']),
		'CFY'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':$arrCFY['LA_NO'])),
		'TLY'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrTLY['LA_NO']),
		'TOTAL'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':($arrCFY['LA_NO']+$arrTLY['LA_NO'])))
	),
	'LA_HA'=>array(
		'ESTIMATION'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrEstimationData['LA_HA'], 2)),
		'MONTHLY'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCurrentMonthData['LA_HA'], 2))),
        'PREV_MONTH'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrPreviousMonthData['LA_HA'], 2)),
		'CFY'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['LA_HA'], 2))),
		'TLY'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrTLY['LA_HA'], 2)),
		'TOTAL'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['LA_HA']+$arrTLY['LA_HA'], 2)))
	),
	'LA_COMPLETED_NO'=>array(
		'ESTIMATION'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrEstimationData['LA_COMPLETED_NO']),
		'MONTHLY'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':$arrCurrentMonthData['LA_COMPLETED_NO'])),
		'PREV_MONTH'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrPreviousMonthData['LA_COMPLETED_NO']),
		'CFY'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':$arrCFY['LA_COMPLETED_NO'])),
		'TLY'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrTLY['LA_COMPLETED_NO']),
		'TOTAL'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':($arrCFY['LA_COMPLETED_NO']+$arrTLY['LA_COMPLETED_NO'])))
	),
	'LA_COMPLETED_HA'=>array(
		'ESTIMATION'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrEstimationData['LA_COMPLETED_HA'], 2)),
		'MONTHLY'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCurrentMonthData['LA_COMPLETED_HA'], 2))),
        'PREV_MONTH'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrPreviousMonthData['LA_COMPLETED_HA'], 2)),
		'CFY'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['LA_COMPLETED_HA'], 2))),
		'TLY'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrTLY['LA_COMPLETED_HA'], 2)),
		'TOTAL'=>(($arrSetupStatus['LA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['LA_COMPLETED_HA']+$arrTLY['LA_COMPLETED_HA'], 2)))
	),
	'FA_HA'=>array(
		'ESTIMATION'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrEstimationData['FA_HA'], 2)),
		'MONTHLY'=>(($arrSetupStatus['FA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCurrentMonthData['FA_HA'], 2))),
		'PREV_MONTH'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrPreviousMonthData['FA_HA'], 2)),
		'CFY'=>(($arrSetupStatus['FA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['FA_HA'], 2))),
		'TLY'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrTLY['FA_HA'], 2)),
		'TOTAL'=>(($arrSetupStatus['FA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['FA_HA']+$arrTLY['FA_HA'], 2)))
	),
	'FA_COMPLETED_HA'=>array(
		'ESTIMATION'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrEstimationData['FA_COMPLETED_HA'], 2)),
		'MONTHLY'=>(($arrSetupStatus['FA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCurrentMonthData['FA_COMPLETED_HA'], 2))),
		'PREV_MONTH'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrPreviousMonthData['FA_COMPLETED_HA'], 2)),
		'CFY'=>(($arrSetupStatus['FA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['FA_COMPLETED_HA'], 2))),
		'TLY'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrTLY['FA_COMPLETED_HA'], 2)),
		'TOTAL'=>(($arrSetupStatus['FA_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['FA_COMPLETED_HA']+$arrTLY['FA_COMPLETED_HA'], 2)))
	),
	'L_EARTHWORK'=>array(
		'ESTIMATION'=>(($arrSetupStatus['L_EARTHWORK_NA']) ? 'NA':giveComma($arrEstimationData['L_EARTHWORK'], 2)),
		'MONTHLY'=>(($arrSetupStatus['L_EARTHWORK_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCurrentMonthData['L_EARTHWORK'], 2))),
		'PREV_MONTH'=>(($arrSetupStatus['L_EARTHWORK_NA']) ? 'NA':giveComma($arrPreviousMonthData['L_EARTHWORK'], 2)),
		'CFY'=>(($arrSetupStatus['L_EARTHWORK_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['L_EARTHWORK'], 2))),
		'TLY'=>(($arrSetupStatus['L_EARTHWORK_NA']) ? 'NA':giveComma($arrTLY['L_EARTHWORK'], 2)),
		'TOTAL'=>(($arrSetupStatus['L_EARTHWORK_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['L_EARTHWORK']+$arrTLY['L_EARTHWORK'], 2)))
	),
	'C_MASONRY'=>array(
		'ESTIMATION'=>(($arrSetupStatus['C_MASONRY_NA']) ? 'NA':giveComma($arrEstimationData['C_MASONRY'], 2)),
		'MONTHLY'=>(($arrSetupStatus['C_MASONRY_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCurrentMonthData['C_MASONRY'], 2))),
		'PREV_MONTH'=>(($arrSetupStatus['C_MASONRY_NA']) ? 'NA':giveComma($arrPreviousMonthData['C_MASONRY'], 2)),
		'CFY'=>(($arrSetupStatus['C_MASONRY_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['C_MASONRY'], 2))),
		'TLY'=>(($arrSetupStatus['C_MASONRY_NA']) ? 'NA':giveComma($arrTLY['C_MASONRY'], 2)),
		'TOTAL'=>(($arrSetupStatus['C_MASONRY_NA']) ? 'NA':(($isBlank)?'':giveComma($arrCFY['C_MASONRY']+$arrTLY['C_MASONRY'], 2)))
	),
	'C_PIPEWORK'=>array(
		'ESTIMATION'=>(($arrSetupStatus['C_PIPEWORK_NA']) ? 'NA':$arrEstimationData['C_PIPEWORK']),
		'MONTHLY'=>(($arrSetupStatus['C_PIPEWORK_NA']) ? 'NA':(($isBlank)?'':$arrCurrentMonthData['C_PIPEWORK'])),
		'PREV_MONTH'=>(($arrSetupStatus['C_PIPEWORK_NA']) ? 'NA':$arrPreviousMonthData['C_PIPEWORK']),
		'CFY'=>(($arrSetupStatus['C_PIPEWORK_NA']) ? 'NA':(($isBlank)?'':$arrCFY['C_PIPEWORK'])),
		'TLY'=>(($arrSetupStatus['C_PIPEWORK_NA']) ? 'NA':$arrTLY['C_PIPEWORK']),
		'TOTAL'=>(($arrSetupStatus['C_PIPEWORK_NA']) ? 'NA':(($isBlank)?'':($arrCFY['C_PIPEWORK']+$arrTLY['C_PIPEWORK'])))
	),
	'C_DRIP_PIPE'=>array(
		'ESTIMATION'=>(($arrSetupStatus['C_DRIP_PIPE_NA']) ? 'NA':$arrEstimationData['C_DRIP_PIPE']),
		'MONTHLY'=>(($arrSetupStatus['C_DRIP_PIPE_NA']) ? 'NA':(($isBlank)?'':$arrCurrentMonthData['C_DRIP_PIPE'])),
		'PREV_MONTH'=>(($arrSetupStatus['C_DRIP_PIPE_NA']) ? 'NA':$arrPreviousMonthData['C_DRIP_PIPE']),
		'CFY'=>(($arrSetupStatus['C_DRIP_PIPE_NA']) ? 'NA':(($isBlank)?'':$arrCFY['C_DRIP_PIPE'])),
		'TLY'=>(($arrSetupStatus['C_DRIP_PIPE_NA']) ? 'NA':$arrTLY['C_DRIP_PIPE']),
		'TOTAL'=>(($arrSetupStatus['C_DRIP_PIPE_NA']) ? 'NA':(($isBlank)?'':($arrCFY['C_DRIP_PIPE']+$arrTLY['C_DRIP_PIPE'])))
	),
	'C_WATERPUMP'=>array(
		'ESTIMATION'=>(($arrSetupStatus['C_WATERPUMP_NA']) ? 'NA':$arrEstimationData['C_WATERPUMP']),
		'MONTHLY'=>(($arrSetupStatus['C_WATERPUMP_NA']) ? 'NA':(($isBlank)?'':$arrCurrentMonthData['C_WATERPUMP'])),
		'PREV_MONTH'=>(($arrSetupStatus['C_WATERPUMP_NA']) ? 'NA':$arrPreviousMonthData['C_WATERPUMP']),
		'CFY'=>(($arrSetupStatus['C_WATERPUMP_NA']) ? 'NA':(($isBlank)?'':$arrCFY['C_WATERPUMP'])),
		'TLY'=>(($arrSetupStatus['C_WATERPUMP_NA']) ? 'NA':$arrTLY['C_WATERPUMP']),
		'TOTAL'=>(($arrSetupStatus['C_WATERPUMP_NA']) ? 'NA':(($isBlank)?'':($arrCFY['C_WATERPUMP']+$arrTLY['C_WATERPUMP'])))
	),
	'K_CONTROL_ROOMS'=>array(
		'ESTIMATION'=>(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'NA':$arrEstimationData['K_CONTROL_ROOMS']),
		'MONTHLY'=>(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'NA':(($isBlank)?'':$arrCurrentMonthData['K_CONTROL_ROOMS'])),
		'PREV_MONTH'=>(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'NA':$arrPreviousMonthData['K_CONTROL_ROOMS']),
		'CFY'=>(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'NA':(($isBlank)?'':$arrCFY['K_CONTROL_ROOMS'])),
		'TLY'=>(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'NA':$arrTLY['K_CONTROL_ROOMS']),
		'TOTAL'=>(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'NA':(($isBlank)?'':($arrCFY['K_CONTROL_ROOMS']+$arrTLY['K_CONTROL_ROOMS'])))
	)
);
$cellSpacing = ($isBlank)?0:2;
?>
<!-- FIXED FORMAT START -->
<table width="100%" border="0" cellpadding="3" cellspacing="<?php echo $cellSpacing;?>" class="ui-widget-content">
<tr><th width="30" class="ui-widget-header">#</th>
    <th colspan="3" class="ui-widget-header">&nbsp;</th>
    <th align="center" class="ui-widget-header">Unit</th>
    <th align="center" class="ui-widget-header">Estimated</th>
    <th width="40" align="center" class="ui-widget-header">Current Month</th>
    <th align="center" class="ui-widget-header">Previous Month</th>
    <th width="10%" align="center" class="ui-widget-header">Total in<br />Current<br />Financial Year</th>
    <th width="9%" align="center" class="ui-widget-header">Till Last Year</th>
    <th width="11%" align="center" class="ui-widget-header">Cumulative<br />Till Date<br />(f+g)</th>
</tr>
<tr>
    <th class="ui-state-default" width="30">&nbsp;</th>
    <th colspan="3" align="center" class="ui-state-default">a</th>
    <th align="center" class="ui-state-default">b</th>
    <th align="center" class="ui-state-default">c</th>
    <th align="center" class="ui-state-default">d</th>
    <th align="center" class="ui-state-default">e</th>
    <th align="center" class="ui-state-default">f</th>
    <th align="center" class="ui-state-default">g</th>
    <th align="center" class="ui-state-default">h</th>
</tr>
<tr>
  <th colspan="11" align="left" class="ui-state-default">1. Physical</th>
  </tr>
<tr>
    <td rowspan="4" align="center" class="ui-widget-content"><strong>1</strong></td>
    <td rowspan="4" class="ui-widget-content"><strong>Land aq cases </strong></td>
    <td colspan="2" rowspan="2" class="ui-widget-content"><strong>Submitted</strong></td>
    <td class="ui-widget-content">Numbers</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_NO']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_NO']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_NO']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_NO']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_NO']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_NO']['TOTAL'];?></td>
</tr>
<tr>
  <td class="ui-widget-content">Hectares</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_HA']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_HA']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_HA']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_HA']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_HA']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_HA']['TOTAL'];?></td>
</tr>
<tr>
  <td colspan="2" rowspan="2" class="ui-widget-content"><strong>Completed</strong></td>
	<td class="ui-widget-content" >Numbers</td>
	<td class="ui-widget-content diagonalRising " align="center">&nbsp;</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_NO']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_NO']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_NO']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_NO']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_NO']['TOTAL'];?></td>
</tr>
<tr>
  <td class="ui-widget-content">Hectares</td>
    <td class="ui-widget-content diagonalRising" align="center">&nbsp;</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_HA']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_HA']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_HA']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_HA']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['LA_COMPLETED_HA']['TOTAL'];?></td>
</tr>
    <tr>
      <td rowspan="2" align="center" class="ui-widget-content"><strong>2</strong><strong></strong></td>
    <td rowspan="2" class="ui-widget-content"><strong>Forest cases </strong></td>
    <td colspan="2" class="ui-widget-content"><strong>Submitted</strong></td>
    <td class="ui-widget-content" >Hectares</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_HA']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_HA']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_HA']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_HA']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_HA']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_HA']['TOTAL'];?></td>
</tr>
<tr>
  <td colspan="2" class="ui-widget-content"><strong>Completed</strong></td>
    <td class="ui-widget-content" >Hectares</td>
    <td class="ui-widget-content diagonalRising" align="center">&nbsp;</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_COMPLETED_HA']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_COMPLETED_HA']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_COMPLETED_HA']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_COMPLETED_HA']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['FA_COMPLETED_HA']['TOTAL'];?></td>
</tr>
<tr>
    <td class="ui-widget-content" align="center"><strong>3</strong></td>
    <td class="ui-widget-content" colspan="3"><strong>Earthwork <br />(As per "L" Earthwork section of DPR)</strong></td>
    <td class="ui-widget-content">Th Cum</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['L_EARTHWORK']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['L_EARTHWORK']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['L_EARTHWORK']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['L_EARTHWORK']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['L_EARTHWORK']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['L_EARTHWORK']['TOTAL'];?></td>
</tr>
<tr>
    <td class="ui-widget-content" rowspan="4" align="center" ><strong>4</strong></td>
    <td class="ui-widget-content" rowspan="4" ><strong>Masonry/Concrete&nbsp;<br />(As per &quot;C&quot; Masonry section of DPR)</strong></td>
    <td colspan="2" class="ui-widget-content" ><strong>(a) Masonry/Concrete</strong></td>
    <td class="ui-widget-content" >Th Cum</td>
    <td class="ui-widget-content" align="center" <?php echo $myheight;?>><?php echo $arrData['C_MASONRY']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_MASONRY']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_MASONRY']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_MASONRY']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_MASONRY']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_MASONRY']['TOTAL'];?></td>
</tr>            
<tr>
    <td rowspan="2" class="ui-widget-content" ><strong>(b) Pipe Works</strong></td>
    <td class="ui-widget-content" ><strong>i. DE/PE/PVC<br />(Main &amp; Submain)</strong></td>
    <td class="ui-widget-content" >Mtrs</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_PIPEWORK']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_PIPEWORK']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_PIPEWORK']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_PIPEWORK']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_PIPEWORK']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_PIPEWORK']['TOTAL'];?></td>
</tr>
<tr>
    <td class="ui-widget-content" ><strong>ii. Lateral for <br />Drip/sprinkler</strong></td>
    <td class="ui-widget-content" >Mtrs</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_DRIP_PIPE']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_DRIP_PIPE']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_DRIP_PIPE']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_DRIP_PIPE']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_DRIP_PIPE']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_DRIP_PIPE']['TOTAL'];?></td>
</tr>
<tr>
    <td colspan="2" class="ui-widget-content" ><strong>(c) Water Pumps</strong></td>
    <td class="ui-widget-content" >Numbers</td>
    <td class="ui-widget-content" align="center" <?php echo $myheight;?>><?php echo $arrData['C_WATERPUMP']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_WATERPUMP']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_WATERPUMP']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_WATERPUMP']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_WATERPUMP']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['C_WATERPUMP']['TOTAL'];?></td>
</tr>
<tr>
    <td class="ui-widget-content" align="center"><strong>5</strong></td>
    <td class="ui-widget-content" colspan="3"><strong>Building Works (Control Rooms)<br>(As per "K" Building sectin of DPR)<br />
    </strong></td>
    <td class="ui-widget-content" >Numbers</td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['K_CONTROL_ROOMS']['ESTIMATION'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['K_CONTROL_ROOMS']['MONTHLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['K_CONTROL_ROOMS']['PREV_MONTH'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['K_CONTROL_ROOMS']['CFY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['K_CONTROL_ROOMS']['TLY'];?></td>
    <td class="ui-widget-content" align="center"><?php echo $arrData['K_CONTROL_ROOMS']['TOTAL'];?></td>
</tr>
<tr>
    <td class="ui-widget-content" align="center"><strong>6</strong></td>
    <td class="ui-widget-content" colspan="3"><strong>Irrigation Potential Created</strong></td>
    <td class="ui-widget-content">Hectares</td>
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
		$s = "{'".$k."': {'KHARIF':{'ESTI':".$v['ESTIMATION_IP']['KHARIF'].", 'CFY':".$v['ACHIEVEMENT_IP_CFY']['KHARIF'].", 'TLY':".$v['ACHIEVEMENT_IP_TLY']['KHARIF']."}, 
		'RABI':{'ESTI':".$v['ESTIMATION_IP']['RABI'].", 'CFY':".$v['ACHIEVEMENT_IP_CFY']['RABI'].", 'TLY':".$v['ACHIEVEMENT_IP_TLY']['RABI']."}, 
		'TOTAL':{'ESTI':".$v['ESTIMATION_IP']['IP'].", 'CFY':".$v['ACHIEVEMENT_IP_CFY']['IP'].", 'TLY':".$v['ACHIEVEMENT_IP_TLY']['IP']."} }}";
		array_push($arrMonthlyBlockData, $s);
		
		$keyup = ' onkeyup="calculateSubIrri('.$k.')" ';
		$arrTotal['KHARIF']['ESTIMATION']+= $v['ESTIMATION_IP']['KHARIF'];
		$arrTotal['KHARIF']['CURRENT_MONTH']+= $v['CUR_MONTH_IP']['KHARIF'];
		$arrTotal['KHARIF']['PREV_MONTH']+= $v['PREV_MONTH_IP']['KHARIF'];
		$arrTotal['KHARIF']['CFY']+= $v['ACHIEVEMENT_IP_CFY']['KHARIF'];
		$arrTotal['KHARIF']['TLY']+= $v['ACHIEVEMENT_IP_TLY']['KHARIF'];
		$arrTotal['KHARIF']['TOTAL']+= ($v['ACHIEVEMENT_IP_CFY']['KHARIF']+$v['ACHIEVEMENT_IP_TLY']['KHARIF']);

          $arrTotal['RABI']['ESTIMATION']+= $v['ESTIMATION_IP']['RABI'];
          $arrTotal['RABI']['CURRENT_MONTH']+= $v['CUR_MONTH_IP']['RABI'];
          $arrTotal['RABI']['PREV_MONTH']+= $v['PREV_MONTH_IP']['RABI'];
          $arrTotal['RABI']['CFY']+= $v['ACHIEVEMENT_IP_CFY']['RABI'];
          $arrTotal['RABI']['TLY']+= $v['ACHIEVEMENT_IP_TLY']['RABI'];
          $arrTotal['RABI']['TOTAL']+= ($v['ACHIEVEMENT_IP_CFY']['RABI']+$v['ACHIEVEMENT_IP_TLY']['RABI']);

          $arrTotal['TOTAL']['ESTIMATION']+= $v['ESTIMATION_IP']['IP'];
          $arrTotal['TOTAL']['CURRENT_MONTH']+= $v['CUR_MONTH_IP']['IP'];
          $arrTotal['TOTAL']['PREV_MONTH']+= $v['PREV_MONTH_IP']['IP'];
          $arrTotal['TOTAL']['CFY']+= $v['ACHIEVEMENT_IP_CFY']['IP'];
          $arrTotal['TOTAL']['TLY']+= $v['ACHIEVEMENT_IP_TLY']['IP'];
          $arrTotal['TOTAL']['TOTAL']+= ($v['ACHIEVEMENT_IP_CFY']['IP']+$v['ACHIEVEMENT_IP_TLY']['IP']);
      ?>
      <tr>
        <td class="ui-widget-content" align="center" rowspan="3"><?php echo chr($iBCount++); ?></td>
        <td class="ui-widget-content" rowspan="3" colspan="3"><?php echo $v['BLOCK_NAME']; ?></td>
        <td class="ui-widget-content"><strong>Kharif</strong></td>
        <td class="ui-widget-content" align="center" <?php echo $myheight;?>><?php echo $v['ESTIMATION_IP']['KHARIF'];?></td>
        <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$v['CUR_MONTH_IP']['KHARIF']);?></td>
        <td class="ui-widget-content" align="center"><?php echo $v['PREV_MONTH_IP']['KHARIF'];?></td>
        <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$v['ACHIEVEMENT_IP_CFY']['KHARIF']);?></td>
        <td class="ui-widget-content" align="center"><?php echo $v['ACHIEVEMENT_IP_TLY']['KHARIF'];?></td>
        <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':($v['ACHIEVEMENT_IP_CFY']['KHARIF']+$v['ACHIEVEMENT_IP_TLY']['KHARIF']));?></td>
      </tr>
      <tr>
        <td class="ui-widget-content"><strong>Rabi</strong></td>
        <td class="ui-widget-content" align="center" <?php echo $myheight;?>><?php echo $v['ESTIMATION_IP']['RABI'];?></td>
         <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$v['CUR_MONTH_IP']['RABI']);?></td>
        <td class="ui-widget-content" align="center"><?php echo $v['PREV_MONTH_IP']['RABI'];?></td>
        <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$v['ACHIEVEMENT_IP_CFY']['RABI']);?></td>
        <td class="ui-widget-content" align="center"><?php echo $v['ACHIEVEMENT_IP_TLY']['RABI'];?></td>
        <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':($v['ACHIEVEMENT_IP_CFY']['RABI']+$v['ACHIEVEMENT_IP_TLY']['RABI']));?></td>
      </tr>
      <tr>
        <td class="ui-state-default" ><strong>Total</strong></td>
        <td class="ui-state-default" align="center" <?php echo $myheight;?>><?php echo $v['ESTIMATION_IP']['IP'];?></td>
        <td class="ui-state-default" align="center"><?php echo (($isBlank)?'':$v['CUR_MONTH_IP']['IP']);?></td>
        <td class="ui-state-default" align="center"><?php echo $v['PREV_MONTH_IP']['IP'];?></td>
        <td class="ui-state-default" align="center"><?php echo (($isBlank)?'':$v['ACHIEVEMENT_IP_CFY']['IP']);?></td>
        <td class="ui-state-default" align="center"><?php echo $v['ACHIEVEMENT_IP_TLY']['IP'];?></td>
        <td class="ui-state-default" align="center"><?php echo (($isBlank)?'':($v['ACHIEVEMENT_IP_TLY']['IP']+$v['ACHIEVEMENT_IP_CFY']['IP']));?></td>
      </tr>
  <?php } ?>
  <tr>
      <td class="ui-state-default" rowspan="3">&nbsp;</td>
      <td colspan="3" rowspan="3" class="ui-state-default"><strong>Total Irrigation Potential Created</strong></td>
      <td class="ui-widget-content"><strong>Kharif</strong></td>
      <td class="ui-widget-content" align="center" <?php echo $myheight;?>><?php echo $arrTotal['KHARIF']['ESTIMATION'];?></td>
      <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$arrTotal['KHARIF']['CURRENT_MONTH']);?></td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotal['KHARIF']['PREV_MONTH'];?></td>
      <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$arrTotal['KHARIF']['CFY']);?></td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotal['KHARIF']['TLY'];?></td>
      <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$arrTotal['KHARIF']['TOTAL']);?></td>
    </tr>
    <tr>
      <td class="ui-widget-content"><strong>Rabi</strong></td>
      <td class="ui-widget-content" align="center" <?php echo $myheight;?>><?php echo $arrTotal['RABI']['ESTIMATION'];?></td>
      <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$arrTotal['RABI']['CURRENT_MONTH']);?></td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotal['RABI']['PREV_MONTH'];?></td>
      <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$arrTotal['RABI']['CFY']);?></td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotal['RABI']['TLY'];?></td>
      <td class="ui-widget-content" align="center"><?php echo (($isBlank)?'':$arrTotal['RABI']['TOTAL']);?></td>
    </tr>
    <tr>
      <td class="ui-state-default" ><strong>Total</strong></td>
      <td class="ui-state-default" align="center" <?php echo $myheight;?>><?php echo $arrTotal['TOTAL']['ESTIMATION'];?></td>
      <td class="ui-state-default" align="center"><?php echo (($isBlank)?'':$arrTotal['TOTAL']['CURRENT_MONTH']);?></td>
      <td class="ui-state-default" align="center"><?php echo $arrTotal['TOTAL']['PREV_MONTH'];?></td>
      <td class="ui-state-default" align="center"><?php echo (($isBlank)?'':$arrTotal['TOTAL']['CFY']);?></td>
      <td class="ui-state-default" align="center"><?php echo $arrTotal['TOTAL']['TLY'];?></td>
      <td class="ui-state-default" align="center"><?php echo (($isBlank)?'':$arrTotal['TOTAL']['TOTAL']);?></td>
    </tr>
</table>

<div class="wrdlinebreak" style="page-break-after:always"></div>

<?php 
$arrTitles = array(
	'LA_CASES_STATUS'=>'Submission of LA Cases', 
	'FA_CASES_STATUS'=>'Submission of Forest Cases', 
	'INTAKE_WELL_STATUS'=>'Intake Well', 
	'PUMPING_UNIT_STATUS'=>'Pumping Unit', 
	'PVC_LIFT_SYSTEM_STATUS'=>'PVC Lift System',
	'PIPE_DISTRI_STATUS'=>'Pipe Distribution Network', 
	'DRIP_SYSTEM_STATUS'=>'Drip System',
	'WATER_STORAGE_TANK_STATUS'=>'Water Storage Tank', 
	'FERTI_PESTI_CARRIER_SYSTEM_STATUS'=>'Fertilizer and Pesticide Carrier System', 
	'CONTROL_ROOMS_STATUS'=>'Control Rooms'
);
$arrFields = array(
	'LA_CASES_STATUS', 'FA_CASES_STATUS', 'INTAKE_WELL_STATUS', 'PUMPING_UNIT_STATUS', 'PVC_LIFT_SYSTEM_STATUS',
	'PIPE_DISTRI_STATUS', 'DRIP_SYSTEM_STATUS',
	'WATER_STORAGE_TANK_STATUS', 'FERTI_PESTI_CARRIER_SYSTEM_STATUS', 'CONTROL_ROOMS_STATUS'
);
$arrStatus = array();
//showArrayValues($monthly_remarks);
foreach($arrFields as $f){
	$arrStatus[$f] = array(
		'TITLE'=>$arrTitles[$f],
		'CURRENT_MONTH'=>	(($arrComponentStatus[$f]==1)? 'NA': (($isBlank)? getWorkStatusOptions(0, $prevMonthStatus[$f]):$statusOptions[$arrCurrentMonthData[$f]])),
		'PREV_MONTH'=>		(($arrComponentStatus[$f]==1)? 'NA':$statusOptions[$prevMonthStatus[$f]]),
		'REMARKS'=>			(($arrComponentStatus[$f]==1)? 'NA':$monthly_remarks[$f.'_REMARK'])
    );
}
?>
<!-- Status -->
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="<?php echo $cellSpacing;?>">
<tr>
  <th colspan="5" align="left" class="ui-widget-header">2. Status</th>
  </tr>
<tr>
    <th width="30" nowrap="nowrap" class="ui-widget-header"><strong>#</strong></th>
    <th width="80" class="ui-widget-header">Contents</th>
    <th width="50" nowrap="nowrap" class="ui-widget-header">Previous Month</th>
    <th width="70" nowrap="nowrap" class="ui-widget-header">Current Month</th>
    <th class="ui-widget-header">Remarks</th>
</tr>
<?php
$i = 0;
//showArrayValues($arrStatus );
foreach($arrStatus as $f=>$v){?>
<tr>
    <td align="center" class="ui-widget-content"><strong><?php echo chr(97+$i++);?></strong></td>
    <td nowrap="nowrap" class="ui-widget-content"><strong><?php echo $v['TITLE'];?></strong></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><?php echo $v['PREV_MONTH'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><?php echo $v['CURRENT_MONTH'];?></td>
    <td align="center" class="ui-widget-content"><?php echo $v['REMARKS'];?></td>
</tr>
<?php }//foreach?>
</table>
<div class="wrdlinebreak"></div>
<?php if(!$isBlank){?> 
<div style="text-align:center;width:100%">
    Physical Progress : <?php echo $PROGRESS;?>%
</div>
<?php }?> 
<p><small>Printed on <?php echo date("d-m-Y h:i:s a");?></small></p>
<!-- FIXED FORMAT END -->
</div>
