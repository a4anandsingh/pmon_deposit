<?php //$arrStatusOption = array("", 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 'Dropped');
$arrStatusOption = array("", 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 7=>'Current Year AA');
echo getPrintButton("prjsetup_report", 'Print', 'xprjsetup_report');?>
<style>
	.ui-widget-content{/*color:#F00*/}
	.ui-widget-header{}
</style>
<div id="prjsetup_report">
  <table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1" style="margin-bottom:9px">
    <tr>
      <td colspan="4" align="center" class="ui-widget-header"><strong><big>Deposit Promon : Project Setup Data</big></strong></td>
    </tr>
    <tr>
        <td align="left" class="ui-state-default"><strong> Project Name </strong></td>
        <td align="left" class="ui-widget-content"><big><strong><?php echo $projectSetupValues['PROJECT_NAME'];?></strong></big></td>
        <td align="left" class="ui-state-default">Project Code</td>
        <td align="left" class="ui-widget-content"><strong><?php echo $projectSetupValues['PROJECT_CODE'];?></strong></td>
    </tr>
    <tr>
        <td align="left" class="ui-state-default"><strong> परियोजना का नाम</strong></td>
        <td align="left" class="ui-widget-content"><big><strong><?php echo $projectSetupValues['PROJECT_NAME_HINDI'];?></strong></big></td>
        <td align="left" class="ui-state-default"><strong> Project Type</strong></td>
        <td align="left" class="ui-widget-content"><strong><?php echo $projectSetupValues['PROJECT_TYPE'].' - '. $projectSetupValues['PROJECT_SUB_TYPE'];?></strong></td>

    </tr>
  </table>
<table width="100%" border="0" cellpadding="0"  cellspacing="2">
<tr>
	<td width="50%" valign="top">
		<table width="100%" cellpadding="3" cellspacing="1" class="ui-widget-content">
        <tr>
        	<td colspan="2" class="ui-state-default" align="center" style="line-height:20px;"><strong>Administrative Approval</strong></td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="ui-widget-content"><strong>AA No :</strong></td>
            <td class="ui-widget-content"><?php echo $projectSetupValues['AA_NO'];?></td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Date : </strong></td>
            <td class="ui-widget-content"><?php echo myDateFormat($projectSetupValues['AA_DATE']);?></td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Authority :</strong></td>
            <td class="ui-widget-content"><?php echo $projectSetupValues['AUTHORITY_NAME'];?></td>
        </tr>
		<tr>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Amount :</strong></td>
            <td class="ui-widget-content"><?php echo $projectSetupValues['AA_AMOUNT'];?> (Rs. In Lacs)</td>
		</tr>
        </table>
    </td>
    <td width="50%" valign="top">
		<table width="100%" cellpadding="3" cellspacing="1" class="ui-widget-content">
        <tr>
        	<td colspan="2" class="ui-state-default" align="center" style="line-height:20px;"><strong>Latest RAA</strong></td>
		</tr>
        <?php if($RAA_VALUES['RAA_NO']!=''){?>
        <tr>
        	<td nowrap="nowrap" class="ui-widget-content"><strong>RAA No : </strong></td>
            <td class="ui-widget-content"><?php echo $RAA_VALUES['RAA_NO'];?></td>
        </tr>
        <tr>
        	<td nowrap="nowrap" class="ui-widget-content"><strong>Date : </strong></td>
            <td class="ui-widget-content"><?php echo myDateFormat($RAA_VALUES['RAA_DATE']);?></td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Authority : </strong></td>
            <td class="ui-widget-content"><?php echo $RAA_AUTHORITY_ID;?></td>
        </tr>
        <tr>
			<td nowrap="nowrap" class="ui-widget-content"><strong>Amount :</strong></td>
			<td class="ui-widget-content"><?php echo $RAA_VALUES['RAA_AMOUNT'];?> (Rs. In Lacs)</td>
        </tr>
        <?php }?>
        </table>
    </td>
</tr>
</table>

<table width="100%" border="0" cellpadding="2" cellspacing="1" class="ui-widget-content">
    <td rowspan="3" class="ui-state-default" width="30" nowrap="nowrap"><strong>Head Detail</strong></td>
    <tr>
        <td class="ui-widget-content" nowrap="nowrap"><strong>Deposit Head</strong></td>
        <td class="ui-widget-content" colspan="2"  nowrap="nowrap" width="130"><strong><?php echo $projectSetupValues['HEAD'];?></strong></td>
    </tr>
    <tr>
        <td class="ui-widget-content" nowrap="nowrap"><strong>Deposit Scheme</strong></td>
        <td class="ui-widget-content" colspan="2"  nowrap="nowrap" width="130"><strong><?php echo $projectSetupValues['SCHEME_NAME_ENGLISH'].' ( '.$projectSetupValues['SCHEME_NAME_HINDI'].' )';?></strong></td>
    </tr>
<tr>
  <td rowspan="2" class="ui-state-default" width="30" nowrap="nowrap"><strong>Office</strong></td>
    <td class="ui-widget-content" nowrap="nowrap" width="130"><strong>Executive Engineer : </strong></td>
    <td class="ui-widget-content"><strong><?php echo $projectSetupValues['OFFICE_EE_NAME'];?></strong></td>
</tr>
<tr>
  <td valign="top" class="ui-widget-content"><strong>Sub-Division : </strong></td>
    <td class="ui-widget-content"><?php echo $projectSetupValues['SDO_OFFICE_NAME'];?></td>
</tr>
</table>
<br />
<table width="100%" cellpadding="3" cellspacing="2"  class="ui-widget-content">
<tr>
	<td colspan="4" valign="top" nowrap="nowrap" class="ui-state-default"><strong>Form - 1 Benefitted Details Form</strong></td>
</tr>
<tr>
    <td valign="top" nowrap="nowrap" class="ui-widget-content"><strong>Longitude of Site :</strong></td>
    <td valign="top" class="ui-widget-content">
		<?php echo $projectSetupValues['LONGITUDE_D'];?>&deg;
        <?php echo $projectSetupValues['LONGITUDE_M'];?>'
        <?php echo $projectSetupValues['LONGITUDE_S'];?>"
    </td>
    <td valign="top" class="ui-widget-content"><strong>Lattitude of Site :</strong></td>
    <td valign="top" class="ui-widget-content">
		<?php echo $projectSetupValues['LATITUDE_D'];?>&deg; 
        <?php echo $projectSetupValues['LATITUDE_M'];?>'
        <?php echo $projectSetupValues['LATITUDE_S'];?>"
    </td>
</tr>
<tr>
    <td rowspan="2" valign="top" nowrap="nowrap" class="ui-widget-content"><strong>District (Site) :</strong></td>
    <td rowspan="2" valign="top" class="ui-widget-content"><?php echo $projectSetupValues['DISTRICT_NAME'];?></td>
    <td valign="top" class="ui-widget-content"><strong>Block (Site) :</strong></td>
    <td valign="top" class="ui-widget-content"><?php echo $projectSetupValues['BLOCK_NAME'];?></td>
</tr>
<tr>
    <td valign="top" class="ui-widget-content"><strong>Tehsil (Site) :</strong></td>
    <td valign="top" class="ui-widget-content"><?php echo $projectSetupValues['TEHSIL_NAME'];?></td>
</tr>
<tr>
    <td valign="top" nowrap="nowrap" class="ui-widget-content"><strong>District Benefited :</strong></td>
    <td class="ui-widget-content" valign="top"><?php echo $DISTRICT_BENEFITED;?></td>
    <td class="ui-widget-content" valign="top"><strong>Benefited Blocks :</strong></td>
    <td class="ui-widget-content" valign="top"><?php echo $BLOCKS_BENEFITED;?></td>
</tr>
<tr>
    <td valign="top" nowrap="nowrap" class="ui-widget-content"><strong>Assembly constituency (Site) :</strong></td>
    <td valign="top" class="ui-widget-content"><?php echo $projectSetupValues['ASSEMBLY_NAME'];?></td>
    <td valign="top" class="ui-widget-content"><strong>Benefited Assembly Constituency :</strong></td>
    <td valign="top" class="ui-widget-content"><?php echo $ASSEMBLY_BENEFITED;?></td>
</tr>
<tr>
	<td valign="top" nowrap="nowrap" class="ui-widget-content"><strong>Nalla / River :</strong></td>
	<td class="ui-widget-content" valign="top"><?php echo $projectSetupValues['NALLA_RIVER'];?></td>
    <td class="ui-widget-content" valign="top"></td>
    <td class="ui-widget-content" valign="top"></td>
</tr>
<tr>
	<td colspan="4" valign="top" class="ui-state-default">
    	<strong>No of villages covered</strong> : <?php echo $projectSetupValues['NO_VILLAGES_BENEFITED'];?>&nbsp; &nbsp; &nbsp; &nbsp; Village (Tehsil[District])
	</td>
</tr>
<tr>
    <td colspan="4" class="ui-widget-content"><?php echo $VILLAGES_BENEFITED;?></td>
</tr>
</table>
<br />
<?php //showArrayValues($BLOCK_IP_DATA);?>
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
<tr>
	<td colspan="4" align="left" valign="middle" class="ui-state-default"><strong>Form - 2 Estimation Form</strong></td>
</tr>
<tr>
    <td valign="middle" align="left" colspan="2" class="ui-widget-content" >Financial Year in which this setup(data) is entered(in Software) : <?php echo $projectSetupValues['SETUP_SESSION'];?></td>
</tr>
</table>
<?php 
$arrData = array(
	'LA_NO'=>array(
		'ESTIMATION'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrEstimationData['LA_NO']),
		'ACHIEVEMENT'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrAchievementData['LA_NO'])
	),
	'LA_HA'=>array(
		'ESTIMATION'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrEstimationData['LA_HA'], 2)),
		'ACHIEVEMENT'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrAchievementData['LA_HA'], 2))
	),
	'LA_COMPLETED_NO'=>array(
		'ESTIMATION'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrEstimationData['LA_COMPLETED_NO']),
		'ACHIEVEMENT'=>(($arrSetupStatus['LA_NA']) ? 'NA':$arrAchievementData['LA_COMPLETED_NO'])
	),
	'LA_COMPLETED_HA'=>array(
		'ESTIMATION'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrEstimationData['LA_COMPLETED_HA'], 2)),
		'ACHIEVEMENT'=>(($arrSetupStatus['LA_NA']) ? 'NA':giveComma($arrAchievementData['LA_COMPLETED_HA'], 2))
	),
	'FA_HA'=>array(
		'ESTIMATION'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrEstimationData['FA_HA'], 2)),
		'ACHIEVEMENT'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrAchievementData['FA_HA'], 2))
	),
	'FA_COMPLETED_HA'=>array(
		'ESTIMATION'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrEstimationData['FA_COMPLETED_HA'], 2)),
		'ACHIEVEMENT'=>(($arrSetupStatus['FA_NA']) ? 'NA':giveComma($arrAchievementData['FA_COMPLETED_HA'], 2))
	),
	'L_EARTHWORK'=>array(
		'ESTIMATION'=>(($arrSetupStatus['L_EARTHWORK_NA']) ? 'NA':giveComma($arrEstimationData['L_EARTHWORK'], 2)),
		'ACHIEVEMENT'=>(($arrSetupStatus['L_EARTHWORK_NA']) ? 'NA':giveComma($arrAchievementData['L_EARTHWORK'], 2))
	),
	'C_MASONRY'=>array(
		'ESTIMATION'=>(($arrSetupStatus['C_MASONRY_NA']) ? 'NA':giveComma($arrEstimationData['C_MASONRY'], 2)),
		'ACHIEVEMENT'=>(($arrSetupStatus['C_MASONRY_NA']) ? 'NA':giveComma($arrAchievementData['C_MASONRY'], 2))
	),
	'C_PIPEWORK'=>array(
		'ESTIMATION'=>(($arrSetupStatus['C_PIPEWORK_NA']) ? 'NA':$arrEstimationData['C_PIPEWORK']),
		'ACHIEVEMENT'=>(($arrSetupStatus['C_PIPEWORK_NA']) ? 'NA':$arrAchievementData['C_PIPEWORK'])
	),
	'C_DRIP_PIPE'=>array(
		'ESTIMATION'=>(($arrSetupStatus['C_DRIP_PIPE_NA']) ? 'NA':$arrEstimationData['C_DRIP_PIPE']),
		'ACHIEVEMENT'=>(($arrSetupStatus['C_DRIP_PIPE_NA']) ? 'NA':$arrAchievementData['C_DRIP_PIPE'])
	),
	'C_WATERPUMP'=>array(
		'ESTIMATION'=>(($arrSetupStatus['C_WATERPUMP_NA']) ? 'NA':$arrEstimationData['C_WATERPUMP']),
		'ACHIEVEMENT'=>(($arrSetupStatus['C_WATERPUMP_NA']) ? 'NA':$arrAchievementData['C_WATERPUMP'])
	),
	'K_CONTROL_ROOMS'=>array(
		'ESTIMATION'=>(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'NA':$arrEstimationData['K_CONTROL_ROOMS']),
		'ACHIEVEMENT'=>(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'NA':$arrAchievementData['K_CONTROL_ROOMS'])
	)
);
?>
<table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tr>
    <th width="30" class="ui-widget-header"><strong>#</strong></th>
    <th class="ui-widget-header" colspan="3"><strong>Contents </strong></th>
    <th class="ui-widget-header"><strong>Latest Estimated</strong></th>
    <th class="ui-widget-header"><strong>Unit</strong></th>
    <th class="ui-widget-header"><strong>Achievement <br />up to last financial Year</strong></th>
</tr>
<tr>
	<td colspan="7" nowrap="nowrap" class="ui-state-default"><strong>Physical</strong></td>
</tr>
<tr>
    <td rowspan="2" align="center" nowrap="nowrap" class="ui-widget-content"><strong>1</strong></td>
    <td class="ui-widget-content" nowrap="nowrap" colspan="3" rowspan="2"><strong>Land Acquisition to be done (As per &ldquo;B&rdquo; Land    Section of DPR)</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['LA_NO']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>No. of Cases</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['LA_NO']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><?php echo $arrData['LA_HA']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Hectares</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['LA_HA']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td rowspan="2" align="center" nowrap="nowrap" class="ui-widget-content"><strong>2</strong></td>
    <td class="ui-widget-content" nowrap="nowrap" colspan="3" rowspan="2"><strong>Land Acquisition Submitted</strong></td>
    <td align="center" class="ui-widget-content diagonalRising">&nbsp;</td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>No. of Cases</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['LA_COMPLETED_NO']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content diagonalRising" >&nbsp;</td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Hectares</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['LA_COMPLETED_HA']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>3</strong></td>
    <td class="ui-widget-content" nowrap="nowrap" colspan="3"><strong>Forest Acquisition to be Done</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['FA_HA']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Hectares</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['FA_HA']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>4</strong></td>
    <td class="ui-widget-content" nowrap="nowrap" colspan="3"><strong>Forest Acquisition Completed</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['FA_COMPLETED_HA']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Hectares</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['FA_COMPLETED_HA']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>5</strong></td>
    <td class="ui-widget-content" nowrap="nowrap" colspan="3"><strong>Earthwork&nbsp;<br />(As per Earthwork given in DPR)</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['L_EARTHWORK']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Th Cum</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['L_EARTHWORK']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td rowspan="4" align="center" class="ui-widget-content"><strong>6</strong></td>
    <td class="ui-widget-content"width="110" rowspan="4"><strong>Masonry/Concrete&nbsp;<br />(As per &quot;C&quot; Masonry section of DPR)</strong></td>
    <td class="ui-widget-content"colspan="2"><strong>Masonry/Concrete</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['C_MASONRY']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Th Cum</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['C_MASONRY']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td class="ui-widget-content"rowspan="2"><strong>Pipe<br />Works</strong></td>
    <td class="ui-widget-content" nowrap="nowrap"><strong>i. DE/PE/PVC<br />(Main &amp; Submain)</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['C_PIPEWORK']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Mtrs</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['C_PIPEWORK']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td class="ui-widget-content" nowrap="nowrap"><strong>ii. Lateral for <br />Drip/sprinkler</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['C_DRIP_PIPE']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Mtrs</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['C_DRIP_PIPE']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td class="ui-widget-content"colspan="2"><strong>Water Pumps</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['C_WATERPUMP']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Numbers</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['C_WATERPUMP']['ACHIEVEMENT'];?></td>
</tr>
<tr>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>7</strong></td>
    <td class="ui-widget-content" nowrap="nowrap" colspan="3"><strong>Building Works&nbsp;<br />( As per &quot;K&quot; Building section of DPR) Control Rooms</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['K_CONTROL_ROOMS']['ESTIMATION'];?></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Numbers</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrData['K_CONTROL_ROOMS']['ACHIEVEMENT'];?></td>
</tr>
</table>

<div class="wrdlinebreak" style="page-break-after:always"></div>

<table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tr>
    <th width="30" class="ui-widget-header"><strong>#</strong></th>
    <th class="ui-widget-header"><strong>Contents </strong></th>
    <th class="ui-widget-header"><strong>Latest Estimated</strong></th>
    <th class="ui-widget-header"><strong>Unit</strong></th>
    <th class="ui-widget-header"><strong>Achievement <br />up to last financial Year</strong></th>
</tr>
<tr>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>8</strong></td>
    <td class="ui-widget-content" nowrap="nowrap"><strong>Designed Irrigation Potential</strong></td>
    <td class="ui-widget-content"><strong>&nbsp;</strong></td>
    <td align="center" nowrap="nowrap" class="ui-widget-content"><strong>Hectares</strong></td>
    <td class="ui-widget-content"></td>
</tr>
<?php 
$arrIPBlockIds = array();
//showArrayValues($BLOCK_IP_DATA);
$blockContent = '';
$arrTotalE = array('KHARIF'=>0, 'RABI'=>0);
$arrTotalA = array('KHARIF'=>0, 'RABI'=>0);
if($BLOCK_IP_DATA){
	$i=0;
	foreach($BLOCK_IP_DATA as $k=>$bb){
		$arrTotalE['KHARIF'] += $bb['ESTIMATION_IP']['KHARIF'];
		$arrTotalE['RABI'] += $bb['ESTIMATION_IP']['RABI'];
		$arrTotalA['KHARIF'] += $bb['ACHIEVEMENT_IP']['KHARIF'];
		$arrTotalA['RABI'] += $bb['ACHIEVEMENT_IP']['RABI'];?>
<tr>
    <td rowspan="3" align="center" class="ui-widget-content"><strong><?php echo (chr(97+$i++));?></strong></td>
    <td class="ui-widget-content" rowspan="3"><strong><?php echo $bb['BLOCK_NAME'];?></strong></td>
    <td align="center" class="ui-widget-content"><?php echo $bb['ESTIMATION_IP']['KHARIF'];?></td>
    <td align="center" class="ui-widget-content"><strong>Kharif</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $bb['ACHIEVEMENT_IP']['KHARIF'];?></td>
  </tr>
  <tr>
    <td align="center" class="ui-widget-content"><?php echo $bb['ESTIMATION_IP']['RABI'];?></td>
    <td align="center" class="ui-widget-content"><strong>Rabi</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $bb['ACHIEVEMENT_IP']['RABI'];?></td>
  </tr>
  <tr>
    <td align="center" class="ui-state-default"><?php echo $bb['ESTIMATION_IP']['IP'];?></td>
    <td align="center" class="ui-state-default"><strong>Total</strong></td>
    <td align="center" class="ui-state-default"><?php echo $bb['ACHIEVEMENT_IP']['IP'];?></td>
  </tr>
<?php }//foreach
}//if?>
<tr>
    <td rowspan="3" align="center" class="ui-state-default"><strong></strong></td>
    <td class="ui-state-default" rowspan="3"><strong>Total Irrigation Potential to be Created</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrTotalE['KHARIF'];?></td>
    <td align="center" class="ui-widget-content"><strong>Kharif</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrTotalA['KHARIF'];?></td>
  </tr>
  <tr>
    <td align="center" class="ui-widget-content"><?php echo $arrTotalE['RABI'];?></td>
    <td align="center" class="ui-widget-content"><strong>Rabi</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrTotalA['RABI'];?></td>
  </tr>
  <tr>
    <td align="center" class="ui-state-default"><strong><?php echo ($arrTotalE['KHARIF']+$arrTotalE['RABI']);?></strong></td>
    <td align="center" class="ui-state-default"><strong>Total</strong></td>
    <td align="center" class="ui-state-default"><strong><?php echo ($arrTotalA['KHARIF']+$arrTotalA['RABI']);?></strong></td>
  </tr>
</table>

<div class="wrdlinebreak"></div>
<br />
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
<tr>
	<td colspan="4" align="left" valign="middle" class="ui-state-default"><strong>Form - 3 Milestone Form</strong></td>
</tr>
</table>

<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
<tr>
    <td valign="middle" align="center" colspan="5">Completion Date of Scheme : <?php echo myDateFormat($projectSetupValues['PROJECT_COMPLETION_DATE']);?></td>
</tr>   
</table>
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
<tr>
    <th class="ui-widget-header"><strong>#</strong></th>
    <th class="ui-widget-header">Contents</th>
    <th class="ui-widget-header">Status upto Last Financial Year</th>
    <th class="ui-widget-header">Target Dates of Completion</th>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>a</strong></td>
    <td class="ui-widget-content"><strong>Submission of LA Cases</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['LA_CASES_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['LA_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>b</strong></td>
    <td class="ui-widget-content"><strong>Submission of FA Cases</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['FA_CASES_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['FA_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>c</strong></td>
    <td class="ui-widget-content"><strong>Intake Well</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['INTAKE_WELL_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['INTAKE_WELL_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>d</strong></td>
    <td class="ui-widget-content"><strong>Pumping Unit</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['PUMPING_UNIT_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['PUMPING_UNIT_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>e</strong></td>
    <td class="ui-widget-content"><strong>PVC Lift System</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['PVC_LIFT_SYSTEM_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['PVC_LIFT_SYSTEM_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>f</strong></td>
    <td class="ui-widget-content"><strong>Pipe Distribution Network</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['PIPE_DISTRI_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['PIPE_DISTRI_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>g</strong></td>
    <td class="ui-widget-content"><strong>Drip System</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['DRIP_SYSTEM_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['DRIP_SYSTEM_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>h</strong></td>
    <td class="ui-widget-content"><strong>Water Storage Tank</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['WATER_STORAGE_TANK_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['WATER_STORAGE_TANK_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>i</strong></td>
    <td class="ui-widget-content"><strong>Fertilizer and Pesticide Carrier System</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['FERTI_PESTI_CARRIER_SYSTEM_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['FERTI_PESTI_CARRIER_SYSTEM_DATE']);?></td>
</tr>
<tr>
    <td align="center" class="ui-widget-content"><strong>j</strong></td>
    <td class="ui-widget-content"><strong>Control Rooms</strong></td>
    <td align="center" class="ui-widget-content"><?php echo $arrStatusOption[$arrSetupStatusData['CONTROL_ROOMS_STATUS']];?></td>
    <td align="center" class="ui-widget-content"><?php echo myDateFormat($arrTargetDates['CONTROL_ROOMS_DATE']);?></td>
</tr>
</table>
<p><small>Printed on <?php echo date("d-m-Y h:i:s a");?></small></p>
</div>