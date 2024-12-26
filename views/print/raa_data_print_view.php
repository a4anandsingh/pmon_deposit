<?php $arrEntryType = array('','RAA', 'Extra Quantity', 'TS');
		$entryMode = $arrEntryType[$raaData['IS_RAA']];
echo getPrintButton("prjsetup_report", 'Print', 'xprjsetup_report');?>
<div id="prjsetup_report">
<table width="100%" border="0" cellpadding="3" class="ui-widget-content">
<tr>
	<td class="ui-widget-content" align="center"><h2><?php echo $entryMode;?> Setup</h2></td>
</tr>
<tr>
    <td align="center" class="ui-widget-header"><strong><?php echo $projectData['PROJECT_NAME'].' - '.$projectData['PROJECT_NAME_HINDI'];?></strong></td>
</tr>

</table>
<div class="wrdlinebreak"></div>
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" id="RAA_DETAIL">
<tr>
    <td class="ui-state-default"><strong id="raa_no"><?php echo $entryMode;?> No :</strong></td>
    <td class="ui-widget-content"><?php echo $raaData['RAA_NO'];?></td>
    <td class="ui-state-default"><strong id="raa_date"><?php echo $entryMode;?> Date :</strong></td>
    <td class="ui-widget-content"><?php echo myDateFormat($raaData['RAA_DATE']);?></td>
</tr>
<tr>
  <td class="ui-state-default"><strong id="raa_aid"><?php echo $entryMode;?> Authority :</strong></td>
  <td class="ui-widget-content"><?php echo $AuthorityName;?></td>
  <td class="ui-state-default"><strong id="raa_amt"><?php echo $entryMode;?> Amount :</strong></td>
  <td class="ui-widget-content"><?php echo $raaData['RAA_AMOUNT'];?> (Rs. In Lacs)</td>
</tr>
</table>
<div class="wrdlinebreak"></div>
<table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tr>
    <th rowspan="2" class="ui-widget-header">Contents</th>
    <th rowspan="2" class="ui-widget-header">Unit</th>
    <th rowspan="2" class="ui-widget-header"></th>
    <th colspan="2" class="ui-widget-header">Estimation</th>
</tr>
<tr>
  <th width="100" class="ui-widget-header" style="min-width:55px">Old</th>
  <th width="130" class="ui-widget-header">Latest</th>
</tr>    
<tr>
    <td class="ui-state-default" colspan="5"><strong>Financial</strong></td>
</tr>
<tr>
    <td nowrap="nowrap" class="ui-widget-content"><strong>a. Total</strong></td>
    <td nowrap="nowrap" class="ui-widget-content"><strong>Rs. Lacs</strong></td>
    <td nowrap="nowrap" class="ui-widget-content"></td>
    <td align="right" class="ui-widget-content"><strong><?php echo $previousEstimation['EXPENDITURE_TOTAL'];?></strong></td>
    <td align="center" class="ui-widget-content"><?php echo $currentEstimation['EXPENDITURE_TOTAL'];?></td>  
</tr>	
<tr>
    <td nowrap="nowrap" class="ui-widget-content"><strong>b. Works</strong></td>
    <td nowrap="nowrap" class="ui-widget-content"><strong>Rs. Lacs</strong></td>
    <td nowrap="nowrap" class="ui-widget-content"></td>
    <td align="right" class="ui-widget-content"><strong><?php echo $previousEstimation['EXPENDITURE_WORK'];?></strong></td>
    <td align="center" class="ui-widget-content"><?php echo $currentEstimation['EXPENDITURE_WORK'];?></td>  
</tr>	
<tr>
    <td colspan="5" nowrap="nowrap" class="ui-state-default"><strong>Physical</strong></td>
</tr>
<?php 
/*echo 'ESIT';
showArrayValues($previousEstimation);
echo 'CUR ESIT';
showArrayValues($currentEstimation);*/
$arrEstimationAchievements = array(
	array('SNO'=>1,
		'TITLE'=>'Land Acquisition Submited', 
		'UNIT'=>'No. of Cases',
		'NA_VALUE'=>$estimationStatus['LA_NA'],
		'EA_NAME'=>'LA_NO', 
		'EA_VALUE'=>$previousEstimation['LA_NO'], 
		'AC_NAME'=>'LA_NO',
		'AC_VALUE'=>$currentEstimation['LA_NO'],
		'SHOW'=>1
		),
	array('SNO'=>2,
		'TITLE'=>'Land Acquisition Submited', 
		'UNIT'=>'Hectares',
		'NA_VALUE'=>$estimationStatus['LA_NA'],
		'EA_NAME'=>'LA_HA', 
		'EA_VALUE'=>$previousEstimation['LA_HA'], 
		'AC_NAME'=>'LA_HA',
		'AC_VALUE'=>$currentEstimation['LA_HA'],
		'SHOW'=>1
		),
	array('SNO'=>3,
		'TITLE'=>'Forest Acquisition', 
		'UNIT'=>'Hectares',
		'NA_VALUE'=>$estimationStatus['FA_NA'],
		'EA_NAME'=>'FA_HA', 
		'EA_VALUE'=>$previousEstimation['FA_HA'], 
		'AC_NAME'=>'FA_HA',
		'AC_VALUE'=>$currentEstimation['FA_HA'],
		'SHOW'=>1
		),
	array('SNO'=>4,
		'TITLE'=>'Headworks Earthwork', 
		'UNIT'=>'Th Cum',
		'NA_VALUE'=>$estimationStatus['HEAD_WORKS_EARTHWORK_NA'],
		'EA_NAME'=>'HEAD_WORKS_EARTHWORK', 
		'EA_VALUE'=>$previousEstimation['HEAD_WORKS_EARTHWORK'], 
		'AC_NAME'=>'HEAD_WORKS_EARTHWORK',
		'AC_VALUE'=>$currentEstimation['HEAD_WORKS_EARTHWORK'],
		'SHOW'=>1
		),
	array('SNO'=>5,
		'TITLE'=>'Headworks Masonry/Concrete', 
		'UNIT'=>'Th Cum',
		'NA_VALUE'=>$estimationStatus['HEAD_WORKS_MASONRY_NA'],
		'EA_NAME'=>'HEAD_WORKS_MASONRY', 
		'EA_VALUE'=>$previousEstimation['HEAD_WORKS_MASONRY'], 
		'AC_NAME'=>'HEAD_WORKS_MASONRY',
		'AC_VALUE'=>$currentEstimation['HEAD_WORKS_MASONRY'],
		'SHOW'=>1
		),
	array('SNO'=>6,
		'TITLE'=>'Steel Works', 
		'UNIT'=>'Metric Tonn',
		'NA_VALUE'=>$estimationStatus['STEEL_WORKS_NA'],
		'EA_NAME'=>'STEEL_WORKS', 
		'EA_VALUE'=>$previousEstimation['STEEL_WORKS'], 
		'AC_NAME'=>'STEEL_WORKS',
		'AC_VALUE'=>$currentEstimation['STEEL_WORKS'],
		'SHOW'=>1
		),
	array('SNO'=>7,
		'TITLE'=>'Canal Earth work ', 
		'UNIT'=>'Th Cum',
		'NA_VALUE'=>$estimationStatus['CANAL_EARTHWORK_NA'],
		'EA_NAME'=>'CANAL_EARTHWORK', 
		'EA_VALUE'=>$previousEstimation['CANAL_EARTHWORK'], 
		'AC_NAME'=>'CANAL_EARTHWORK',
		'AC_VALUE'=>$currentEstimation['CANAL_EARTHWORK'],
		'SHOW'=>1
		),
	array('SNO'=>8,
		'TITLE'=>'Canal Structures', 
		'UNIT'=>'Numbers',
		'NA_VALUE'=>$estimationStatus['CANAL_STRUCTURES_NA'],
		'EA_NAME'=>'CANAL_STRUCTURES', 
		'EA_VALUE'=>$previousEstimation['CANAL_STRUCTURES'], 
		'AC_NAME'=>'CANAL_STRUCTURES',
		'AC_VALUE'=>$currentEstimation['CANAL_STRUCTURES'],
		'SHOW'=>1
		),
	array('SNO'=>9,
		'TITLE'=>'Canal Lining', 
		'UNIT'=>'Km.',
		'NA_VALUE'=>$estimationStatus['CANAL_LINING_NA'],
		'EA_NAME'=>'CANAL_LINING', 
		'EA_VALUE'=>$previousEstimation['CANAL_LINING'], 
		'AC_NAME'=>'CANAL_LINING',
		'AC_VALUE'=>$currentEstimation['CANAL_LINING'],
		'SHOW'=>1
		)/*,
	array('SNO'=>10,
		'TITLE'=>'Designed Irrigation Potential', 
		'UNIT'=>'Hectares',
		'NA_VALUE'=>$estimationStatus['IRRIGATION_POTENTIAL_NA'],
		'EA_NAME'=>'IRRIGATION_POTENTIAL', 
		'EA_VALUE'=>$previousEstimation['IRRIGATION_POTENTIAL'], 
		'AC_NAME'=>'IRRIGATION_POTENTIAL',
		'AC_VALUE'=>$currentEstimation['IRRIGATION_POTENTIAL'],
		'SHOW'=>1,
		'KHARIF'=>array(
			'EA_VALUE'=>$previousEstimation['IRRIGATION_POTENTIAL_KHARIF'], 
			'AC_VALUE'=>$currentEstimation['IRRIGATION_POTENTIAL_KHARIF'],
		),
		'RABI'=>array(
			'EA_VALUE'=>$previousEstimation['IRRIGATION_POTENTIAL_RABI'], 
			'AC_VALUE'=>$currentEstimation['IRRIGATION_POTENTIAL_RABI'],
		)
	)*/
);
/////////////////////////////////////
//$content = '';
$arrV = array();
$arrValidComponent = array();
foreach($arrEstimationAchievements as $x){
	if($x['NA_VALUE']) continue;
	$myClass = ($x['NA_VALUE'])? '' : 'required';
	$rowSpan = '';
	if($x['SNO']==10){
		$rowSpan = 'rowspan="3"';
	}
	echo '<tr>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'><strong>'.$x['TITLE'].'</strong></td>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'>'.$x['UNIT'].'</td>';

	if($x['SNO']==10){
		echo '<td align="center" class="ui-widget-content">Kharif</td>
			<td align="right" class="ui-widget-content"><strong>'.$x['KHARIF']['EA_VALUE'].'</strong></td>
			<td align="center" class="ui-widget-content">'.$x['KHARIF']['AC_VALUE'].'</td>
			</tr><tr>
			<td align="center" class="ui-widget-content">Rabi</td>
			<td align="right" class="ui-widget-content"><strong>'.$x['RABI']['EA_VALUE'].'</strong></td>
			<td align="center" class="ui-widget-content">'.$x['RABI']['AC_VALUE'].'</td>
			</tr><tr>
			<td align="center" class="ui-state-default">Total</td>
			<td align="right" class="ui-state-default"><strong>'.$x['EA_VALUE'].'</strong></td>
			<td align="center" class="ui-state-default">'.$x['AC_VALUE'].'</td>';
	}else{
		echo '<td align="center" class="ui-widget-content"></td>
			<td align="right" class="ui-widget-content">'.$x['EA_VALUE'].'</td>
			<td align="center" class="ui-widget-content">'.$x['AC_VALUE'].'</td>';
	}
	echo '</tr>';
}//foreach?>
</table>
</div>