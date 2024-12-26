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
?>
<table width="100%" border="0" cellpadding="3" cellspacing="2"  class="ui-widget-content" id="xprjmonthly_report">
<tr>
    <td class="ui-widget-content" align="center" colspan="5"><strong><big><big><?php echo $PROJECT_NAME;?></big></big></strong></td>
</tr>
<tr>
  <td nowrap="nowrap" class="ui-state-default"><strong>Code</strong></td>
  <td class="ui-widget-content" colspan="2"><strong><?php echo $PROJECT_CODE;?></strong></td>
  <td class="ui-state-default"><strong>Month</strong></td>
  <td class="ui-widget-content"><strong><?php echo date('F, Y', $MONTH_DATE);?></strong></td>
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
				echo $statusOptions[$currentMonthRecord['PROJECT_STATUS']];
			}?>
    </td>
    <td class="ui-state-default" width="180px">Proposed Completion Date : <br />(As Per Target)</td>
    <td class="ui-widget-content"><strong><?php echo myDateFormat($ACTUAL_COMPLETION_DATE);?></strong></td>
</tr>

<?php if($isBlank){?>
<tr>
    <td class="ui-state-default">Completion Date :</td>
    <td class="ui-widget-content" colspan="2"></td>
    <td class="ui-state-default">Completion Certificate No.:</td>
    <td class="ui-widget-content" width="140px"></td>
</tr>
<tr>
    <td nowrap="nowrap" class="ui-state-default"><strong>Completion Type</strong></td>
    <td class="ui-widget-content" colspan="4"  nowrap="nowrap">
        <big>&#x25a2;</big><strong> Physically & Financially Completed</strong> &nbsp; &nbsp;
        <big>&#x25a2;</big><strong> Physically Completed but Financially not Completed</strong> 
    </td>
</tr>
<tr>
    <td class="ui-state-default">Financially not completed due to</td>
    <td class="ui-widget-content" colspan="4"><big>&#x25a2; </big> LA Payment <big>&#x25a2; </big> FA Payment  <big>&#x25a2; </big> Liabilities of Contractor</td>
</tr>
<tr>
    <td class="ui-state-default">Remarks</td>
    <td class="ui-widget-content" colspan="4"><big><big>&nbsp;</big></big></td>
</tr>

<?php 
}else{
	//completionStatusData
	$projectStatus = $currentMonthRecord['PROJECT_STATUS'];
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
			}else{
            	$strPayType = 'LA Payment, FA Payment and Liabilities of Contractor';
            } 
			$strPayType = ' Due to <strong>'.$strPayType.'</strong>' ;
		}
	}
	//echo '::'.$completionStatusData['COMPLETION_TYPE'].$strPayType.'::';
	$remarkDiv = false;
	switch($projectStatus){
		case 4:case 2: case 5:case 6:
			$remarkDiv = true; break;
	}

if($projectStatus==5 || $projectStatus==6 || $remarkDiv){?>
<tr>
    <td nowrap="nowrap" class="ui-widget-content" colspan="5">
        <?php if($projectStatus==5 || $projectStatus==6){
			if($projectStatus==5){?>
            <table width="100%" border="0" cellpadding="3" cellspacing="2">
            <tr>
                <td align="left" class="ui-state-default" width="140px">Completion Type</td>
                <td align="left" class="ui-widget-content"><?php echo $arrCompletionType[$completionStatusData['COMPLETION_TYPE']]. $strPayType;?></td>
            </tr>
            </table>
            <?php }?>
        <table width="100%" border="0" cellpadding="3" cellspacing="2">
        <tr>
	        <td align="left" class="ui-state-default" width="140px">
            	<?php if ($projectStatus==5) echo 'Completion Date';
				if ($projectStatus==6) echo 'Drop Date';?>
            </td>
	        <td align="center" class="ui-widget-content"><?php echo myDateFormat($currentMonthRecord['COMPLETION_DATE']);?></td>
            <td align="left" class="ui-state-default">
                <?php if ($projectStatus==5) echo 'Completion Certificate No';
				if ($projectStatus==6) echo 'Memo No';?>
            </td>
	        <td align="center" class="ui-widget-content"><?php echo $monthlyStatusData['PROJECT_STATUS_DISPATCH_NO'];?></td>
        </tr>
		</table>
        <?php }
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
<?php }
}?>
<tr>
    <td nowrap="nowrap" class="ui-state-default"><strong>Alloted Budget</strong></td>
    <td class="ui-widget-content" colspan="4">
		<?php echo $BUDGET_AMOUNT;?> for this Financial Year
	</td>
</tr>
</table>
<div class="wrdlinebreak"></div>
<?php 
$arrMonthly = array(
	array(
		'SNO'=>1,
		'LIST_VALUE'=>'a',
		'TITLE'=>'Expenditure Total', 
		'UNIT'=>'Rs. Lacs',
		'E_VALUE'=> '',//$currentMonthRecord['EXPENDITURE_TOTAL'],
		'CM_VALUE'=> '',//$currentMonthRecord['EXPENDITURE_TOTAL'],
		'PM_VALUE'=>'',// $previousMonthRecord['EXPENDITURE_TOTAL'], 
		'CFY_VALUE'=> '',//($totalInCurrentFY['EXPENDITURE_TOTAL'] + $currentMonthRecord['EXPENDITURE_TOTAL']),
		'TLY_VALUE'=> '',//$achievementTillLastFY['EXPENDITURE_TOTAL'],
		'SHOW'=>0
		),
	array(
		'SNO'=>2,
		'LIST_VALUE'=>'b',
		'TITLE'=>'Expenditure Works', 
		'UNIT'=>'Rs. Lacs',
		'E_VALUE'=> '',//$currentMonthRecord['EXPENDITURE_WORKS'],
		'CM_VALUE'=> '',//$currentMonthRecord['EXPENDITURE_WORKS'],
		'PM_VALUE'=> '',//$previousMonthRecord['EXPENDITURE_WORKS'], 
		'CFY_VALUE'=> '',//($totalInCurrentFY['EXPENDITURE_WORKS'] + $currentMonthRecord['EXPENDITURE_WORKS']),
		'HCFY_VALUE'=>'',// $totalInCurrentFY['EXPENDITURE_WORKS'],
		'TLY_VALUE'=> '',//$achievementTillLastFY['EXPENDITURE_WORKS'],
		'SHOW'=>0
	), 
	array(
		'SNO'=>3,
		'LIST_VALUE'=>'a',
		'TITLE'=>'Land aq cases submitted', 
		'UNIT'=>'Numbers',
		'E_VALUE'=> $estimationData['LA_NO'] ,
		'CM_VALUE'=> $currentMonthRecord['LA_NO'] ,
		'PM_VALUE'=> $previousMonthRecord['LA_NO'], 
		'PCFY_VALUE'=> $totalInCurrentFY['LA_NO'],
		'CFY_VALUE'=> $totalInCurrentFY['LA_NO'] ,//+ $currentMonthRecord['LA_NO']),
		'HCFY_VALUE'=> $totalInCurrentFY['LA_NO'],
		'TLY_VALUE'=> $achievementTillLastFY['LA_NO'],
		'SHOW'=> $setupData['LA_NA']
	),
	array(
		'SNO'=>4,
		'LIST_VALUE'=>'b',
		'TITLE'=>'Land aq cases submitted', 
		'UNIT'=>'Hectares',
		'E_VALUE'=> $estimationData['LA_HA'] ,
		'CM_VALUE'=> $currentMonthRecord['LA_HA'],
		'PM_VALUE'=> $previousMonthRecord['LA_HA'],
		'PCFY_VALUE'=> $totalInCurrentFY['LA_HA'], 
		'CFY_VALUE'=> $totalInCurrentFY['LA_HA'],// + $currentMonthRecord['LA_HA']),
		'HCFY_VALUE'=> $totalInCurrentFY['LA_HA'],
		'TLY_VALUE'=> $achievementTillLastFY['LA_HA'],
		'SHOW'=> $setupData['LA_NA']
	),
	array(
		'SNO'=>5,
		'LIST_VALUE'=>'c',
		'TITLE'=>'Land aq cases completed', 
		'UNIT'=>'Numbers',
		'E_VALUE'=> $estimationData['LA_COMPLETED_NO'] ,
		'CM_VALUE'=> $currentMonthRecord['LA_COMPLETED_NO'],
		'PM_VALUE'=> $previousMonthRecord['LA_COMPLETED_NO'],
		'PCFY_VALUE'=> $totalInCurrentFY['LA_COMPLETED_NO'],
		'CFY_VALUE'=> $totalInCurrentFY['LA_COMPLETED_NO'],// + $currentMonthRecord['LA_COMPLETED_NO']),
		'HCFY_VALUE'=> $totalInCurrentFY['LA_COMPLETED_NO'],
		'TLY_VALUE'=> $achievementTillLastFY['LA_COMPLETED_NO'],
		'SHOW'=> $setupData['LA_NA']
	),
	array(
		'SNO'=>6,
		'LIST_VALUE'=>'d',
		'TITLE'=>'Land aq cases completed', 
		'UNIT'=>'Hectares',
		'E_VALUE'=> $estimationData['LA_COMPLETED_HA'] ,
		'CM_VALUE'=> $currentMonthRecord['LA_COMPLETED_HA'],
		'PM_VALUE'=> $previousMonthRecord['LA_COMPLETED_HA'], 
		'PCFY_VALUE'=> $totalInCurrentFY['LA_COMPLETED_HA'],
		'CFY_VALUE'=> $totalInCurrentFY['LA_COMPLETED_HA'],//+$currentMonthRecord['LA_COMPLETED_HA']),
		'HCFY_VALUE'=> $totalInCurrentFY['LA_COMPLETED_HA'],
		'TLY_VALUE'=> $achievementTillLastFY['LA_COMPLETED_HA'],
		'SHOW'=> $setupData['LA_NA']
	),
	array(
		'SNO'=>7,
		'LIST_VALUE'=>'e',
		'TITLE'=>'Forest cases submitted', 
		'UNIT'=>'Hectares',
		'E_VALUE'=> $estimationData['FA_HA'] ,
		'CM_VALUE'=> $currentMonthRecord['FA_HA'],
		'PM_VALUE'=> $previousMonthRecord['FA_HA'], 
		'PCFY_VALUE'=> $totalInCurrentFY['FA_HA'],
		'CFY_VALUE'=> $totalInCurrentFY['FA_HA'],//+$currentMonthRecord['FA_HA']),
		'HCFY_VALUE'=> $totalInCurrentFY['FA_HA'],
		'TLY_VALUE'=> $achievementTillLastFY['FA_HA'],
		'SHOW'=> $setupData['FA_NA']
	),
	array(
		'SNO'=>8,
		'LIST_VALUE'=>'f',
		'TITLE'=>'Forest cases completed', 
		'UNIT'=>'Hectares',
		'E_VALUE'=> $estimationData['FA_COMPLETED_HA'] ,
		'CM_VALUE'=> $currentMonthRecord['FA_COMPLETED_HA'],
		'PM_VALUE'=> $previousMonthRecord['FA_COMPLETED_HA'], 
		'PCFY_VALUE'=> $totalInCurrentFY['FA_COMPLETED_HA'],
		'CFY_VALUE'=> $totalInCurrentFY['FA_COMPLETED_HA'],//+$currentMonthRecord['FA_COMPLETED_HA']),
		'HCFY_VALUE'=> $totalInCurrentFY['FA_COMPLETED_HA'],
		'TLY_VALUE'=> $achievementTillLastFY['FA_COMPLETED_HA'],
		'SHOW'=> $setupData['FA_NA']
	),                            
	array(
		'SNO'=>9,
		'LIST_VALUE'=>'g',
		'TITLE'=>'Headworks Earthwork', 
		'UNIT'=>'Th Cum',
		'E_VALUE'=> $estimationData['HEAD_WORKS_EARTHWORK'] ,
		'CM_VALUE'=> $currentMonthRecord['HEAD_WORKS_EARTHWORK'],
		'PM_VALUE'=> $previousMonthRecord['HEAD_WORKS_EARTHWORK'], 
		'PCFY_VALUE'=> $totalInCurrentFY['HEAD_WORKS_EARTHWORK'],
		'CFY_VALUE'=> $totalInCurrentFY['HEAD_WORKS_EARTHWORK'],//+$currentMonthRecord['HEAD_WORKS_EARTHWORK']),
		'HCFY_VALUE'=> $totalInCurrentFY['HEAD_WORKS_EARTHWORK'],
		'TLY_VALUE'=> $achievementTillLastFY['HEAD_WORKS_EARTHWORK'],
		'SHOW'=> $setupData['HEAD_WORKS_EARTHWORK_NA']
	),                            
	array(
		'SNO'=>10,
		'LIST_VALUE'=>'h',
		'TITLE'=>'Headworks Masonry/Concrete', 
		'UNIT'=>'Th Cum',
		'E_VALUE'=> $estimationData['HEAD_WORKS_MASONRY'] ,
		'CM_VALUE'=> $currentMonthRecord['HEAD_WORKS_MASONRY'],
		'PM_VALUE'=> $previousMonthRecord['HEAD_WORKS_MASONRY'], 
		'PCFY_VALUE'=> $totalInCurrentFY['HEAD_WORKS_MASONRY'],
		'CFY_VALUE'=> $totalInCurrentFY['HEAD_WORKS_MASONRY'],//+$currentMonthRecord['HEAD_WORKS_MASONRY']),
		'HCFY_VALUE'=> $totalInCurrentFY['HEAD_WORKS_MASONRY'],
		'TLY_VALUE'=> $achievementTillLastFY['HEAD_WORKS_MASONRY'],
		'SHOW'=> $setupData['HEAD_WORKS_MASONRY_NA']
	),
	array(
		'SNO'=>11,
		'LIST_VALUE'=>'h',
		'TITLE'=>'Steel Works', 
		'UNIT'=>'mt',
		'E_VALUE'=> $estimationData['STEEL_WORKS'] ,
		'CM_VALUE'=> $currentMonthRecord['STEEL_WORKS'],
		'PM_VALUE'=> $previousMonthRecord['STEEL_WORKS'], 
		'PCFY_VALUE'=> $totalInCurrentFY['STEEL_WORKS'],
		'CFY_VALUE'=> $totalInCurrentFY['STEEL_WORKS'],//+$currentMonthRecord['STEEL_WORKS']),
		'HCFY_VALUE'=> $totalInCurrentFY['STEEL_WORKS'],
		'TLY_VALUE'=> $achievementTillLastFY['STEEL_WORKS'],
		'SHOW'=> $setupData['STEEL_WORKS_NA']
	),
	array(
		'SNO'=>12,
		'LIST_VALUE'=>'i',
		'TITLE'=>'Canal Earthwork', 
		'UNIT'=>'Th Cum',
		'E_VALUE'=> $estimationData['CANAL_EARTHWORK'] ,
		'CM_VALUE'=> $currentMonthRecord['CANAL_EARTHWORK'],
		'PM_VALUE'=> $previousMonthRecord['CANAL_EARTHWORK'], 
		'PCFY_VALUE'=> $totalInCurrentFY['CANAL_EARTHWORK'],
		'CFY_VALUE'=> $totalInCurrentFY['CANAL_EARTHWORK'],//+$currentMonthRecord['CANAL_EARTHWORK']),
		'HCFY_VALUE'=> $totalInCurrentFY['CANAL_EARTHWORK'],
		'TLY_VALUE'=> $achievementTillLastFY['CANAL_EARTHWORK'],
		'SHOW'=> $setupData['CANAL_EARTHWORK_NA']
	),
	array(
		'SNO'=>13,
		'LIST_VALUE'=>'k',
		'TITLE'=>'Canal Structures', 
		'UNIT'=>'Numbers',
		'E_VALUE'=> $estimationData['CANAL_STRUCTURES'] ,
		'CM_VALUE'=> $currentMonthRecord['CANAL_STRUCTURES'],
		'PM_VALUE'=> $previousMonthRecord['CANAL_STRUCTURES'], 
		'PCFY_VALUE'=> $totalInCurrentFY['CANAL_STRUCTURES'],
		'CFY_VALUE'=> $totalInCurrentFY['CANAL_STRUCTURES'],//+$currentMonthRecord['CANAL_STRUCTURES']),
		'HCFY_VALUE'=> $totalInCurrentFY['CANAL_STRUCTURES'],
		'TLY_VALUE'=> $achievementTillLastFY['CANAL_STRUCTURES'],
		'SHOW'=> $setupData['CANAL_STRUCTURES_NA']
	),
	array(
		'SNO'=>14,
		'LIST_VALUE'=>'k',
		'TITLE'=>'Canal Structure Masonry/Concrete', /* <span style="color:#f00">(Applicable only if no. of stru. not mentioned above)</span>', */
		'UNIT'=>'Th Cum',
		'E_VALUE'=> $estimationData['CANAL_MASONRY'] ,
		'CM_VALUE'=> $currentMonthRecord['CANAL_MASONRY'],
		'PM_VALUE'=> $previousMonthRecord['CANAL_MASONRY'], 
		'PCFY_VALUE'=> $totalInCurrentFY['CANAL_MASONRY'],
		'CFY_VALUE'=> $totalInCurrentFY['CANAL_MASONRY'],//+$currentMonthRecord['CANAL_MASONRY']),
		'HCFY_VALUE'=> $totalInCurrentFY['CANAL_MASONRY'],
		'TLY_VALUE'=> $achievementTillLastFY['CANAL_MASONRY'],
		'SHOW'=> $setupData['CANAL_STRUCTURES_NA']
	),
	array(
		'SNO'=>15,
		'LIST_VALUE'=>'j',
		'TITLE'=>'Canal Lining', 
		'UNIT'=>'Km',
		'E_VALUE'=> $estimationData['CANAL_LINING'] ,
		'CM_VALUE'=> $currentMonthRecord['CANAL_LINING'],
		'PM_VALUE'=> $previousMonthRecord['CANAL_LINING'], 
		'PCFY_VALUE'=> $totalInCurrentFY['CANAL_LINING'],
		'CFY_VALUE'=> $totalInCurrentFY['CANAL_LINING'],//+$currentMonthRecord['CANAL_LINING']),
		'HCFY_VALUE'=> $totalInCurrentFY['CANAL_LINING'],
		'TLY_VALUE'=> $achievementTillLastFY['CANAL_LINING'],
		'SHOW'=> $setupData['CANAL_LINING_NA']
	),
	array(
		'SNO'=>16,
		'LIST_VALUE'=>'k',
		'TITLE'=>'Road Works', 
		'UNIT'=>'Km',
		'E_VALUE'=> $estimationData['ROAD_WORKS'] ,
		'CM_VALUE'=> $currentMonthRecord['ROAD_WORKS'],
		'PM_VALUE'=> $previousMonthRecord['ROAD_WORKS'], 
		'PCFY_VALUE'=> $totalInCurrentFY['ROAD_WORKS'],
		'CFY_VALUE'=> $totalInCurrentFY['ROAD_WORKS'],//+$currentMonthRecord['ROAD_WORKS']),
		'HCFY_VALUE'=> $totalInCurrentFY['ROAD_WORKS'],
		'TLY_VALUE'=> $achievementTillLastFY['ROAD_WORKS'],
		'SHOW'=> $setupData['ROAD_WORKS_NA']
	),
	array(
		'SNO'=>17,
		'LIST_VALUE'=>'l',
		'TITLE'=>'Irrigation Potential Created', 
		'UNIT'=>'Hectares',
		'E_VALUE'=> $estimationData['IRRIGATION_POTENTIAL'] ,
		'CM_NAME'=> 'IRRIGATION_POTENTIAL',
		'CM_VALUE'=> $currentMonthRecord['IRRIGATION_POTENTIAL'],
		'PM_VALUE'=> $previousMonthRecord['IRRIGATION_POTENTIAL'], 
		'PCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL'],
		'CFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL'],// + $currentMonthRecord['IRRIGATION_POTENTIAL']),
		'HCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL'],
		'TLY_VALUE'=> $achievementTillLastFY['IRRIGATION_POTENTIAL'],
		'SHOW'=> $setupData['IRRIGATION_POTENTIAL_NA'],
		'KHARIF' => array(
			'E_VALUE'=> $estimationData['IRRIGATION_POTENTIAL_KHARIF'] ,
			'CM_VALUE'=> $currentMonthRecord['IRRIGATION_POTENTIAL_KHARIF'],
			'PM_VALUE'=> $previousMonthRecord['IRRIGATION_POTENTIAL_KHARIF'],
			'PCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL_KHARIF'], 
			'CFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL_KHARIF'] ,//+ $currentMonthRecord['IRRIGATION_POTENTIAL_KHARIF']),
			'HCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL_KHARIF'],
			'TLY_VALUE'=> $achievementTillLastFY['IRRIGATION_POTENTIAL_KHARIF'],
		),
		'RABI' => array(
			'E_VALUE'=> $estimationData['IRRIGATION_POTENTIAL_RABI'] ,
			'CM_VALUE'=> $currentMonthRecord['IRRIGATION_POTENTIAL_RABI'],
			'PM_VALUE'=> $previousMonthRecord['IRRIGATION_POTENTIAL_RABI'], 
			'PCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL_RABI'],
			'CFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL_RABI'],// + $currentMonthRecord['IRRIGATION_POTENTIAL_RABI']),
			'HCFY_VALUE'=> $totalInCurrentFY['IRRIGATION_POTENTIAL_RABI'],
			'TLY_VALUE'=> $achievementTillLastFY['IRRIGATION_POTENTIAL_RABI'],
		)
	)
);
$arrMyFields = array();
$contentOne = '';
$arrDataFields = array();
$decimalPlace = 3;
$iSNOofIPRow = 17;
$isEstimationExists = true;
//showArrayValues($ESTIMATION_DATA);
//showArrayValues($arrMonthly);
$contentIPTotal = '';
//$a = array();
foreach($arrMonthly as $arrM){
	//array_push($a, $arrM);
	if($arrM['SNO']==1 || $arrM['SNO']==2) continue;
	$sno = (($arrM['SNO']>=3)? ($arrM['SNO']-2):$arrM['SNO']);
	if($sno==11)		$sno = '11a';
	else if($sno==12)	$sno = '11b';
	else if($sno>12)	$sno = $sno-1;

	if($arrM['SNO']==$iSNOofIPRow) {
		$contentOne .= '<tr>
			<td class="ui-widget-content" align="center"><strong>'.$sno.'</strong></td>
			<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
			';
		if($arrM['SHOW']==1){
			$contentOne .= str_repeat('<td align="center" class="ui-widget-content">NA</td>', (($isBlank)? 7:6)).'</tr>';
		}else{
			//$contentOne .=str_repeat('<td align="center" class="ui-widget-content"></td>', 6).'</tr>';
			$contentOne .= '<td align="center" class="ui-widget-content" colspan="'.(($isBlank)? 7:6).'"></td></tr>';
			$blockData = '';
			$iBCount = 97;
			foreach($arrBlockData as $k=>$v){
				//echo 'hhhh';showArrayValues($v);
				//array_push($arrBenefitedBlocks, $k);
				$keyup = ' onkeyup="calculateSubIrri('.$k.')" ';
				//showArrayValues($v);
				$blockData .= '<tr>
					<td class="ui-widget-content" align="center" rowspan="3">'.chr($iBCount++).'</td>
					<td class="ui-widget-content" rowspan="3">'.$v['BLOCK_NAME'].'</td>
					<td class="ui-widget-content">Kharif</td>
					<td align="center" class="ui-widget-content">'.$v['ESTIMATION_IP']['KHARIF'].'</td>
					<td align="center" class="ui-widget-content">'.(($isBlank)?'<big><big><big><big>&nbsp; </big></big></big></big>':$v['CUR_MONTH_IP']['KHARIF']).'</td>
					<td align="center" class="ui-widget-content">'.$v['PREV_MONTH_IP']['KHARIF'].'</td>'.
					(($isBlank) ? '<td align="center" class="ui-widget-content">'.$v['ACHIEVEMENT_IP_CFY']['KHARIF'].'</td>
						<td align="center" class="ui-widget-content"></td>':
						'<td align="center" class="ui-widget-content">'.($v['ACHIEVEMENT_IP_CFY']['KHARIF'] ).'</td>').
					'<td align="center" class="ui-widget-content">'.$v['ACHIEVEMENT_IP_TLY']['KHARIF'].'</td>
					<td align="center" class="ui-widget-content">'.(($isBlank)?'':($v['ACHIEVEMENT_IP_CFY']['KHARIF']+$v['ACHIEVEMENT_IP_TLY']['KHARIF'])).'</td>
					</tr>
					<tr>
					<td class="ui-widget-content"><strong>Rabi</strong></td>
					<td align="center" class="ui-widget-content">'.$v['ESTIMATION_IP']['RABI'].'</td>
					<td align="center" class="ui-widget-content">'.(($isBlank)?'<big><big><big><big>&nbsp; </big></big></big></big>':$v['CUR_MONTH_IP']['RABI']).'</td>
					<td align="center" class="ui-widget-content">'.$v['PREV_MONTH_IP']['RABI'].'</td>'.
					(($isBlank) ? '<td align="center" class="ui-widget-content">'.$v['ACHIEVEMENT_IP_CFY']['RABI'].'</td>
						<td align="center" class="ui-widget-content"></td>':
						'<td align="center" class="ui-widget-content">'.($v['ACHIEVEMENT_IP_CFY']['RABI']).'</td>').
					'<td align="center" class="ui-widget-content">'.$v['ACHIEVEMENT_IP_TLY']['RABI'].'</td>
					<td align="center" class="ui-widget-content">'.(($isBlank)?'':($v['ACHIEVEMENT_IP_CFY']['RABI']+$v['ACHIEVEMENT_IP_TLY']['RABI'])).'</td>
					</tr>
					<tr>
					<td class="ui-state-default" ><strong>Total</strong></td>
					<td align="center" class="ui-state-default">'.$v['ESTIMATION_IP']['IP'].'</td>
					<td align="center" class="ui-state-default">'.(($isBlank)?'':$v['CUR_MONTH_IP']['IP']).'</td>
					<td align="center" class="ui-state-default">'.$v['PREV_MONTH_IP']['IP'].'</td>'.
					(($isBlank) ? '<td align="center" class="ui-state-default">'.$v['ACHIEVEMENT_IP_CFY']['IP'].'</td>
						<td align="center" class="ui-state-default"></td>':
						'<td align="center" class="ui-state-default">'.($v['ACHIEVEMENT_IP_CFY']['IP']).'</td>').
					'<td align="center" class="ui-state-default">'.$v['ACHIEVEMENT_IP_TLY']['IP'].'</td>
					<td align="center" class="ui-state-default">'.(($isBlank)?'':$v['ACHIEVEMENT_IP_CFY']['IP']+$v['ACHIEVEMENT_IP_TLY']['IP']).'</td>
					</tr>
					';
			}

			///////////////////////////////////////////////////////////
			/*$contentIPTotal ='<tr>
			<td class="ui-widget-content" align="center"><strong>'.$sno.'</strong></td>
			<td class="ui-widget-content"><strong>'.$arrM['TITLE'].'</strong></td>
			<td class="ui-widget-content">'.$arrM['UNIT'].'</td>
			';*/
			//array_push($arrMyFields, $arrM['KHARIF']['CM_NAME']);
			//array_push($arrMyFields, $arrM['RABI']['CM_NAME']);
			//$keyup = ' onkeyup="calculateIrri(\''.$arrM['CM_NAME'].'\')" ';
			/*$estimatedQuantity = '<input type="hidden" name="e'.$arrM['CM_NAME'].
				'" id="e'.$arrM['CM_NAME'].'" value="'.$arrM['ESTI_VALUE'].'"/>
				<div id="dive'.$arrM['CM_NAME'].'" class="font14">'.$arrM['E_VALUE'].'</div>';
			$estimatedQuantity1 = '<input type="hidden" name="e'.$arrM['KHARIF']['CM_NAME'].
				'" id="e'.$arrM['KHARIF']['CM_NAME'].'" value="'.$arrM['KHARIF']['E_VALUE'].'"/>
				<div id="dive'.$arrM['KHARIF']['CM_NAME'].'" class="font14">'.$arrM['KHARIF']['ESTI_VALUE'].'</div>';
			$estimatedQuantity2 = '<input type="hidden" name="e'.$arrM['RABI']['CM_NAME'].
				'" id="e'.$arrM['RABI']['CM_NAME'].'" value="'.$arrM['RABI']['ESTI_VALUE'].'"/>
				<div id="dive'.$arrM['RABI']['CM_NAME'].'" class="font14">'.$arrM['RABI']['ESTI_VALUE'].'</div>';*/

			$contentIPTotal .= '<tr>
				<td class="ui-state-default" colspan="2" rowspan="3">Total Irrigation Potential Created</td>
				<td class="ui-widget-content">Kharif</td>
				<td align="center" class="ui-widget-content">'.$arrM['KHARIF']['E_VALUE'].'</td>
				<td align="center" class="ui-widget-content">'.(($isBlank)?'':$arrM['KHARIF']['CM_VALUE']).'</td>
				<td align="center" class="ui-widget-content">'.$arrM['KHARIF']['PM_VALUE'].'</td>'.
				(($isBlank)?'<td align="center" class="ui-widget-content">'.$arrM['KHARIF']['HCFY_VALUE'].'</td>
					<td align="center" class="ui-widget-content"></td>' :'<td align="center" class="ui-widget-content">'.$arrM['KHARIF']['CFY_VALUE'].'</td>' ).
				'<td align="center" class="ui-widget-content">'.$arrM['KHARIF']['TLY_VALUE'].'</td>
				<td align="center" class="ui-widget-content">'.(($isBlank)?'':($arrM['KHARIF']['TLY_VALUE'] + $arrM['KHARIF']['CFY_VALUE'])).'</td>
			</tr>
			<tr>
				<td class="ui-widget-content">Rabi</td>
				<td align="center" class="ui-widget-content">'.$arrM['RABI']['E_VALUE'].'</td>
				<td align="center" class="ui-widget-content">'.(($isBlank)?'':$arrM['RABI']['CM_VALUE']).'</td>
				<td align="center" class="ui-widget-content">'.$arrM['RABI']['PM_VALUE'].'</td>'.
				(($isBlank)?'<td align="center" class="ui-widget-content">'.$arrM['RABI']['HCFY_VALUE'].'</td>
					<td align="center" class="ui-widget-content"></td>' :'<td align="center" class="ui-widget-content">'.$arrM['RABI']['CFY_VALUE'].'</td>' ).
				'<td align="center" class="ui-widget-content">'.$arrM['RABI']['TLY_VALUE'].'</td>
				<td align="center" class="ui-widget-content">'.(($isBlank)?'':($arrM['RABI']['TLY_VALUE'] + $arrM['RABI']['CFY_VALUE'])).'</td>
			</tr>
				<tr>
				<td class="ui-state-default" >Total</td>
				<td align="center" class="ui-state-default">'.$arrM['E_VALUE'].'</td>
				<td align="center" class="ui-state-default">'.(($isBlank)?'':$arrM['CM_VALUE']).'</td>
				<td align="center" class="ui-state-default">'.$arrM['PM_VALUE'].'</td>'.
				(($isBlank)?'<td align="center" class="ui-state-default">'.$arrM['HCFY_VALUE'].'</td>
					<td align="center" class="ui-state-default"></td>' :'<td align="center" class="ui-state-default">'.$arrM['CFY_VALUE'].'</td>' ).
				'<td align="center" class="ui-state-default">'.$arrM['TLY_VALUE'].'</td>
				<td align="center" class="ui-state-default">'.(($isBlank)?'':($arrM['TLY_VALUE'] + $arrM['CFY_VALUE'])).'</td>
			</tr>';
			$contentOne .= $blockData.$contentIPTotal;
		}
		continue;
	}
	if($arrM['SNO']==3)
		$contentOne .= '<tr><td class="ui-state-default" colspan="'.(($isBlank)? 10:9).'"><strong>1] Physical </strong></td></tr>';
	// if NA ==1 then don't show entry box
	$rowSpan = '';
	if($arrM['SNO']==$iSNOofIPRow) $rowSpan = 'rowspan="3"';
	$contentOne .= '<tr>
		<td class="ui-widget-content" '.$rowSpan.' align="center"><strong>'.$sno.'</strong></td>
		<td class="ui-widget-content" '.$rowSpan.'><strong>'.$arrM['TITLE'].'</strong></td>
		<td class="ui-widget-content" '.$rowSpan.'>'.$arrM['UNIT'].'</td>';
		
	if(($arrM['SHOW']==1) || (substr($arrM['TITLE'], 0, 5)=='Expen')){//
		$contentOne .= str_repeat('<td align="center" class="ui-widget-content">NA</td>', (($isBlank)? 7:6)).'</tr>';
		continue;
	}

	$decimalPlace = ((in_array($arrM['SNO'], array(3,5,13))) ? 0:3);
	if($arrM['SNO']==$iSNOofIPRow){
		$contentOne .= '
			<td align="center" class="ui-widget-content">Kharif</td>
			<td align="center" class="ui-widget-content">'.$arrM['KHARIF']['E_VALUE'].'</td>
			<td align="center" class="ui-widget-content">'.(($isBlank) ? '<big><big><big><big>&nbsp; </big></big></big></big>' : $arrM['KHARIF']['CM_VALUE']).'</td>
			<td align="center" class="ui-widget-content">'.$arrM['KHARIF']['PM_VALUE'].'</td>
			<td align="center" class="ui-widget-content">'.(($isBlank) ? '' : $arrM['KHARIF']['CFY_VALUE']).'</td>
			<td align="center" class="ui-widget-content">'.$arrM['KHARIF']['TLY_VALUE'].'</td>
			<td align="center" class="ui-widget-content">'.(($isBlank) ? '' : ($arrM['KHARIF']['CFY_VALUE']+$arrM['KHARIF']['TLY_VALUE'])).'</td>
			</tr>
			<tr>
			<td align="center" class="ui-widget-content">Rabi</td>
			<td align="center" class="ui-widget-content">'.$arrM['RABI']['E_VALUE'].'</td>
			<td align="center" class="ui-widget-content">'.(($isBlank) ? '<big><big><big><big>&nbsp; </big></big></big></big>' : $arrM['RABI']['CM_VALUE']).'</td>
			<td align="center" class="ui-widget-content">'.$arrM['RABI']['PM_VALUE'].'</td>
			<td align="center" class="ui-widget-content">'.(($isBlank) ? '' : $arrM['RABI']['CFY_VALUE']).'</td>
			<td align="center" class="ui-widget-content">'.$arrM['RABI']['TLY_VALUE'].'</td>
			<td align="center" class="ui-widget-content">'.(($isBlank)?'':($arrM['RABI']['CFY_VALUE']+$arrM['RABI']['TLY_VALUE'])).'</td>
			</tr>
			<tr>
			<td align="center" class="ui-state-default">Total</td>
			<td align="center" class="ui-state-default">'.$arrM['E_VALUE'].'</td>
			<td align="center" class="ui-state-default">'.(($isBlank) ? '' : $arrM['CM_VALUE']).'</td>
			<td align="center" class="ui-state-default">'.$arrM['PM_VALUE'].'</td>
			<td align="center" class="ui-state-default">'.(($isBlank) ? '' : $arrM['CFY_VALUE']).'</td>
			<td align="center" class="ui-state-default">'.$arrM['TLY_VALUE'].'</td>
			<td align="center" class="ui-state-default">'.(($isBlank) ? '': ($arrM['CFY_VALUE']+$arrM['TLY_VALUE'])).'</td></tr>';
	}else{
		if(($sno=='11b') && ($arrM['E_VALUE']==0)){
			$contentOne .= str_repeat('<td align="center" class="ui-widget-content">NA</td>', (($isBlank)? 7:6)).'</tr>';
		}else{
			$contentOne .= '
				<td align="center" class="ui-widget-content">'.$arrM['E_VALUE'].'</td>
				<td align="center" class="ui-widget-content">'.(($isBlank) ? '<big><big><big><big>&nbsp; </big></big></big></big>' : $arrM['CM_VALUE']).'</td>
				<td align="center" class="ui-widget-content">'.giveComma($arrM['PM_VALUE'], $decimalPlace).'</td>'.
				(($isBlank) ? '<td align="center" class="ui-widget-content">'.giveComma($arrM['HCFY_VALUE'], $decimalPlace).'</td>
					<td align="center" class="ui-widget-content">&nbsp;</td>': 
					'<td align="center" class="ui-widget-content">'.giveComma($arrM['CFY_VALUE'], $decimalPlace).'</td>').
				'<td align="center" class="ui-widget-content">'.giveComma($arrM['TLY_VALUE'], $decimalPlace).'</td>
				<td align="center" class="ui-widget-content">'.(($isBlank) ? '' : giveComma($arrM['CFY_VALUE']+$arrM['TLY_VALUE'], $decimalPlace)).'</td>
			</tr>';
		}
	}
}

//showArrayValues($a);
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
		}else if($prevMonthStatus[$arrF[$i]]==5 || $prevMonthStatus[$arrF[$i]]==1  || $prevMonthStatus[$arrF[$i]]==0){
			$arrComponentStatus[$arrF[$i]] = 1;//'NA';
		}else{
			if($currentMonthRecord[$arrF[$i]]==1){
				if($prevMonthStatus[$arrF[$i]]>1)
					$arrComponentStatus[$arrF[$i]] = $prevMonthStatus[$arrF[$i]];
				else
					$arrComponentStatus[$arrF[$i]] = 1;
			}else{
				$arrComponentStatus[$arrF[$i]] =  $currentMonthRecord[$arrF[$i]];
			}
		}
	}else{
		if($prevMonthStatus[$arrF[$i]]==5 || $prevMonthStatus[$arrF[$i]]==1  || $prevMonthStatus[$arrF[$i]]==0){
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

$arrRemarkShow = array();
//$statusOptions = 0 1'NA', 2'Not Started', 3'Ongoing', 4'Stopped', 5'Completed', 6'Dropped'
for($i=0;$i<count($arrF);$i++){
	$arrRemarkShow[$arrF[$i]] = 0;
	if($currentMonthRecordExists){
		if(	$arrComponentStatus[$arrF[$i]]==2 || 
			$arrComponentStatus[$arrF[$i]]==4 ||
			$arrComponentStatus[$arrF[$i]]==5 ){
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
//showArrayValues($arrRemarkShow);
//showArrayValues($currentMonthRecord);
//showArrayValues($prevMonthStatus);

//echo '::'.$statusData['FLANK_STATUS'].'::'.$currentMonthRecordExists.'::';
$arrStatus = array(
	array(
		'SNO'=>1,
		'LIST_VALUE'=>'a',
		'TITLE'=>'Submission of LA Cases', 
		'STATUS_BOX_NAME'=>'LA_CASES_STATUS',
		'STATUS_VALUE'=>$currentMonthRecord['LA_CASES_STATUS'],
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
		'STATUS_VALUE'=>$currentMonthRecord['SPILLWAY_STATUS'],
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
		'STATUS_VALUE'=>$currentMonthRecord['FLANK_STATUS'],
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
		'STATUS_VALUE'=>$currentMonthRecord['SLUICES_STATUS'],
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
		'STATUS_VALUE'=>$currentMonthRecord['NALLA_CLOSURE_STATUS'],
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
		'STATUS_VALUE'=>$currentMonthRecord['CANAL_STRUCTURE_STATUS'],
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
		'STATUS_VALUE'=>$currentMonthRecord['CANAL_EARTH_WORK_STATUS'],
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
		'STATUS_VALUE'=>$currentMonthRecord['CANAL_LINING_STATUS'],
		'PRE_STATUS_VALUE'=>$prevMonthStatus['CANAL_LINING_STATUS'],
		'SHOW'=>$arrShow['CANAL_LINING_STATUS'], 
		'REMARK'=>$monthly_remarks['CANAL_LINING_STATUS_REMARK'],
		'SHOW_REMARK'=>$arrRemarkShow['CANAL_LINING_STATUS']
	)
);
$i=0;
//showArrayValues($prevMonthStatus);
$contentStatus = '';
foreach($arrStatus as $arrSt){
	$myOptions = '';
	if($arrSt['SHOW']){
		$myOptions1 = (($isBlank)? '': (($arrSt['SHOW_REMARK']==1) ?  $arrSt['REMARK']:''));
	}else{
		$myOptions1 =  'NA';
	}
	if($isBlank){
		$st = getStatusOptions($arrSt['PRE_STATUS_VALUE']);
	}else{
		$st = $statusOptions[ $arrSt['STATUS_VALUE'] ];
	}
	$contentStatus .= '<tr>	
		<td class="ui-widget-content" nowrap="nowrap" width="150px"><strong>'.$arrSt['LIST_VALUE'].') '.$arrSt['TITLE'].'</strong></td>
		<td class="ui-widget-content" align="center" width="100px"><strong>'.$statusOptions[ $arrSt['PRE_STATUS_VALUE'] ].'</strong></td>
		<td class="ui-widget-content" align="'.(($isBlank) ? 'left':'center').'" nowrap="nowrap"width="100px">'.$st.'</td>
		<td class="ui-widget-content" align="center">'.(($isBlank) ? '<big><big><big><big>&nbsp; </big></big></big></big>' : $myOptions1).'</td>
	</tr>';
}?>

<table width="100%" border="0" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tr>
    <th class="ui-widget-header">SNo.</th>
    <th class="ui-widget-header">&nbsp;</th>
    <th align="center" class="ui-widget-header">Unit</th>
    <th align="center" class="ui-widget-header">Estimated</th>
    <th align="center" class="ui-widget-header">Current Month</th>
    <th align="center" class="ui-widget-header">Previous Month</th>
   <?php if($isBlank){?> 
    <th width="10%" align="center" class="ui-widget-header">Total in<br />Current<br />Financial Year</th>
<?php }?> 
    <th width="10%" align="center" class="ui-widget-header">Total in<br />Current<br />Financial Year</th>
    <th width="9%" align="center" class="ui-widget-header">Till Last Year</th>
    <th width="11%" align="center" class="ui-widget-header">Cumulative<br />Till Date<br />
     <?php echo ($isBlank)? '(g+h)':'(f+g)';?></th>
</tr>
<tr>
  <th class="ui-state-default" width="20px">&nbsp;</th>
<?php for($i=0;$i<(($isBlank)? 9:8);$i++){?> 
  <th align="center" class="ui-state-default"><?php echo chr(97+$i); ?> </th>
<?php }?> 
</tr>
<?php /*if($isBlank){?>
<tr>
    <td colspan="10" class="ui-state-default"><strong>1] Financial</strong></td>
</tr>
<?php */echo $contentOne;?>
</table>
<div class="wrdlinebreak"></div>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tr>
    <td class="ui-widget-header" colspan="4"><strong>2] Status</strong></td>
</tr>
<tr>
    <td rowspan="2" class="ui-state-default">&nbsp;</td>
    <td colspan="2" align="center" class="ui-state-default"><strong>Status</strong></td>
    <td rowspan="2"  align="center" class="ui-state-default"><strong>Remarks</strong></td>
</tr>
<tr>
  <td align="center" class="ui-state-default">Previous Month</td>
  <td align="center" class="ui-state-default">Current Month</td>
</tr>            
<?php echo $contentStatus;?>
</table>
<div class="wrdlinebreak"></div>
	<?php if(!$isBlank){?> 
	<div style="text-align:center;width:100%">
        Physical Progress : 
        <?php echo $PROGRESS;?>%
     </div>
   	<?php }?> 
</div>
