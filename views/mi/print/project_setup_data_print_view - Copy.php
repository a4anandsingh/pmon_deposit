<?php 
    //$arrStatusOption = array("", 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 'Dropped');
$arrStatusOption = array("", 'NA', 'Not Started', 'Ongoing', 'Stopped', 'Completed', 7=>'Current Year AA');
echo getPrintButton("prjsetup_report", 'Print', 'xprjsetup_report');?>
<div id="prjsetup_report">
  <table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1" style="margin-bottom:9px">
  <tr>
      <td colspan="4" align="center" class="ui-widget-content"><?php echo getReportTitle( array("Project Setup Data") );?></td>
    </tr>
      <tr>
          <td align="left" class="ui-state-default"><strong> Parent Project Name </strong></td>
          <td align="left" class="ui-widget-content" colspan="3">
              <big><strong><?php echo $projectSetupValues['PARENT_PROJECT_NAME'] .'('.$projectSetupValues['PARENT_PROJECT_NAME'].')';?> </strong></big>
          </td>

      </tr>
    <tr>
      <td align="left" class="ui-state-default"><strong> Project Name </strong></td>
      <td align="left" class="ui-widget-content">
        <big><strong><?php echo $projectSetupValues['WORK_NAME'];?></strong></big>
        </td>
      <td align="left" class="ui-state-default">Project Code</td>
      <td align="left" class="ui-widget-content"><strong><?php echo $projectSetupValues['PROJECT_CODE'];?></strong></td>
    </tr>
    <tr>
      <td align="left" class="ui-state-default"><strong> परियोजना का नाम</strong></td>
      <td align="left" class="ui-widget-content" colspan="3">
        <big><strong><?php echo $projectSetupValues['WORK_NAME_HINDI'];?></strong></big>
        </td>
      <!--<td align="left" class="ui-state-default">Project Type</td>
      <td align="left" class="ui-widget-content">
       <strong> <?php /*echo ($projectSetupValues['PROJECT_TYPE_ID']==1) ? 'Minor' : 'Medium';*/?>
       -
        <?php /*echo $projectSetupValues['PROJECT_SUB_TYPE'];*/?> </strong>
      </td>-->
    </tr>
</table>
<table width="100%" border="0" cellpadding="0"  cellspacing="2">
<tr>
	<td width="50%" valign="top">
		<table width="100%" cellpadding="3" cellspacing="1" class="ui-widget-content">
        <tr>
        	<td colspan="2" class="ui-state-default" align="center" style="line-height:20px;">
            	<strong>Administrative Approval</strong>
            </td>
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
            <td nowrap="nowrap" class="ui-widget-content">
				<strong>Authority :</strong></td>
            <td class="ui-widget-content">
            	<?php echo $projectSetupValues['AUTHORITY_NAME'];?>
			</td>
        </tr>
		<tr>
            <td nowrap="nowrap" class="ui-widget-content">
				<strong>Amount :</strong>
            </td>
            <td class="ui-widget-content">
            	<?php echo $projectSetupValues['AA_AMOUNT'];?> (Rs. In Lacs)
			</td>
		</tr>
        </table>
	</td>
	<td width="50%" valign="top">
		<table width="100%" cellpadding="3" cellspacing="1" class="ui-widget-content">
        <tr>
        	<td colspan="2" class="ui-state-default" align="center" style="line-height:20px;">
            	<strong>Latest RAA</strong>
			</td>
		</tr>
		<tr class="raa" <?php if ($RAA_VALUES['RAA_NO']=='') echo 'style="display:none"';?>>
        	<td nowrap="nowrap" class="ui-widget-content"><strong>RAA No : </strong></td>
            <td class="ui-widget-content">
            	<?php echo $RAA_VALUES['RAA_NO'];?>
            </td>
        </tr>
        <tr class="raa" <?php if ($RAA_VALUES['RAA_NO']=='') echo 'style="display:none"';?>>
        	<td nowrap="nowrap" class="ui-widget-content"><strong>Date : </strong></td>
            <td class="ui-widget-content">
            	<?php echo myDateFormat($RAA_VALUES['RAA_DATE']);?>
			</td>
        </tr>
        <tr class="raa" <?php if ($RAA_VALUES['RAA_NO']=='') echo 'style="display:none"';?>>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Authority : </strong></td>
            <td class="ui-widget-content">
                <?php echo $RAA_AUTHORITY_ID;?>
            </td>
        </tr>
        <tr class="raa" <?php if ($RAA_VALUES['RAA_NO']=='') echo 'style="display:none"';?>>
			<td nowrap="nowrap" class="ui-widget-content"><strong>Amount :</strong></td>
			<td class="ui-widget-content">
            	<?php echo $RAA_VALUES['RAA_AMOUNT'];?> (Rs. In Lacs)
			</td>
        </tr>
        </table>
    </td>
</tr>
<tr>
   <td colspan="2" class="ui-state-default" align="left" style="line-height:20px;">
		<strong>Office</strong>
   </td>
</tr>
<tr>
  <td colspan="2" class="ui-widget-content" align="left" style="line-height:20px;">
        <table width="100%" border="0" cellpadding="2" cellspacing="1">
        <tr>
        <td class="ui-widget-content">
           <strong>Executive Engineer : </strong>
        </td>
        <td class="ui-widget-content">
           <strong><?php echo $projectSetupValues['OFFICE_EE_NAME'];?></strong></td>
        </tr>
        <tr>
        <td valign="top" class="ui-widget-content"><strong>Sub-Division : </strong></td>
        <td class="ui-widget-content">
            <?php echo $projectSetupValues['SDO_OFFICE_NAME'];?>
        </td>
        </tr>
        </table>
  </td>
</tr>
</table>
<br />
<table width="100%" border="0" cellpadding="3" cellspacing="1">
<tr class="ui-widget-content">
    <td valign="top">
        <table width="100%" cellpadding="3" cellspacing="2"  class="ui-widget-content">
        <tr>
          <td colspan="4" valign="top" nowrap="nowrap" class="ui-state-default"><strong>Form - 1</strong></td>
          </tr>
        <tr>
            <td valign="top" nowrap="nowrap" class="ui-widget-content">                
              <strong>Longitude of Site :</strong>
            </td>
            <td valign="top" class="ui-widget-content">
                    <?php echo $projectSetupValues['LONGITUDE_D'];?>&deg;
                    <?php echo $projectSetupValues['LONGITUDE_M'];?>'
                    <?php echo $projectSetupValues['LONGITUDE_S'];?>"
            </td>
            <td valign="top" class="ui-widget-content">
            <strong>Lattitude of Site :</strong></td>
            <td valign="top" class="ui-widget-content">
                <?php echo $projectSetupValues['LATITUDE_D'];?>&deg; 
                <?php echo $projectSetupValues['LATITUDE_M'];?>'
                <?php echo $projectSetupValues['LATITUDE_S'];?>"
            </td>
        </tr>
        <tr>
            <td rowspan="2" valign="top" nowrap="nowrap" class="ui-widget-content">
            
            <strong>District (Site) :</strong></td>
            <td rowspan="2" valign="top" class="ui-widget-content">
                <?php echo $projectSetupValues['DISTRICT_NAME'];?>
            </td>
            <td valign="top" class="ui-widget-content">
              <strong>Block (Site) :</strong>
            </td>
            <td valign="top" class="ui-widget-content">
                <?php echo $projectSetupValues['BLOCK_NAME'];?>
            </td>
        </tr>
        <tr>
          <td valign="top" class="ui-widget-content">
             
            <strong>Tehsil (Site) :</strong></td>
          <td valign="top" class="ui-widget-content">
            <?php echo $projectSetupValues['TEHSIL_NAME'];?>
          </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" class="ui-widget-content">
                <strong>District Benefited :</strong>
            </td>
            <td class="ui-widget-content" valign="top">
                <?php echo $DISTRICT_BENEFITED;?>
            </td>
            <td class="ui-widget-content" valign="top"><strong>Benefited Blocks :</strong></td>
            <td class="ui-widget-content" valign="top">
                <?php echo $BLOCKS_BENEFITED;?>
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" class="ui-widget-content">
            <strong>Assembly constituency (Site) :</strong></td>
            <td valign="top" class="ui-widget-content">
                <?php echo $projectSetupValues['ASSEMBLY_NAME'];?>
            </td>
            <td valign="top" class="ui-widget-content">
                <strong>Benefited Assembly Constituency :</strong>
            </td>
            <td valign="top" class="ui-widget-content">
                <?php echo $ASSEMBLY_BENEFITED;?>
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" class="ui-widget-content">
              <strong>Nalla / River :</strong>
            </td>
            <td class="ui-widget-content" valign="top">
                <?php echo $projectSetupValues['NALLA_RIVER'];?>
            </td>
            <td class="ui-widget-content" valign="top"></td>
            <td class="ui-widget-content" valign="top">

            </td>
        </tr>
        <tr>
          <td colspan="4" valign="top" class="ui-state-default">
               <strong>No of villages covered</strong> : <?php echo $projectSetupValues['NO_VILLAGES_BENEFITED'];?>
               &nbsp; &nbsp; &nbsp; &nbsp; Village (Tehsil[District])
          </td>
        </tr>
        <tr>
			<td colspan="4" class="ui-widget-content">
				<?php echo $VILLAGES_BENEFITED;?>
            </td>
      </tr>
      </table>
    </td>
</tr>
</table>
<br />
<?php //showArrayValues($BLOCK_IP_DATA);?>
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
<tr>
	<td colspan="4" align="left" valign="middle" class="ui-state-default"><strong>Form - 2</strong></td>
</tr>
<tr>
    <td valign="middle" align="left" colspan="2"> 
        Financial Year in which this setup(data) is entered(in Software) : &nbsp; &nbsp; &nbsp; 
        <?php echo $projectSetupValues['SETUP_SESSION'];?> 
    </td>
    <td valign="middle" nowrap="nowrap" colspan="2">Actual Work Start Date : &nbsp; &nbsp; &nbsp; <?php echo myDateFormat($projectSetupValues['AA_DATE']);?></td>
</tr>
<tr>
    <td valign="top" colspan="4">
        <table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">
        <tr>
          <th width="30" class="ui-widget-header">&nbsp;</th>
            <th class="ui-widget-header" colspan="2">Contents</th>
            <th class="ui-widget-header">Unit </th>
            <th class="ui-widget-header">Latest Estimated</th>
            <th class="ui-widget-header">Achievement upto last financial year </th>
        </tr>
        <tr>
            <td colspan="6" nowrap="nowrap" class="ui-state-default"><strong>Physical</strong></td>
        </tr>
<?php
$arrEstimationAchievements = array(
    array(
        'SNO'=>1,
        'TITLE'=>'Land Acquisition Submited',
        'UNIT'=>'No. of Cases',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['LA_NA'],
        'EA_NAME'=>'LA_NO',
        'EA_VALUE'=>$estimationData['LA_NO'],
        'AC_NAME'=>'LA_NO_ACHIEVE',
        'AC_VALUE'=>$achievementData['LA_NO'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>2,
        'TITLE'=>'Land Acquisition Submited',
        'UNIT'=>'Hectares',
        'NA_BOX'=>FALSE,
        'NA_VALUE'=>$estimationStatus['LA_NA'],
        'EA_NAME'=>'LA_HA',
        'EA_VALUE'=>$estimationData['LA_HA'],
        'AC_NAME'=>'LA_HA_ACHIEVE',
        'AC_VALUE'=>$achievementData['LA_HA'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>3,
        'TITLE'=>'Land Acquisition Completed',
        'UNIT'=>'No. of Cases',
        'NA_BOX'=>FALSE,
        'NA_VALUE'=>$estimationStatus['LA_NA'],
        'EA_NAME'=>'LA_COMPLETED_NO',
        'EA_VALUE'=>$estimationData['LA_COMPLETED_NO'],
        'AC_NAME'=>'LA_COMPLETED_NO_ACHIEVE',
        'AC_VALUE'=>$achievementData['LA_COMPLETED_NO'],
        'SHOW'=>0
    ),
    array(
        'SNO'=>4,
        'TITLE'=>'Land Acquisition Completed',
        'UNIT'=>'Hectares',
        'NA_BOX'=>FALSE,
        'NA_VALUE'=>$estimationStatus['LA_NA'],
        'EA_NAME'=>'LA_COMPLETED_HA',
        'EA_VALUE'=>$estimationData['LA_COMPLETED_HA'],
        'AC_NAME'=>'LA_COMPLETED_HA_ACHIEVE',
        'AC_VALUE'=>$achievementData['LA_COMPLETED_HA'],
        'SHOW'=>0
    ),
    array(
        'SNO'=>5,
        'TITLE'=>'Forest Acquisition',
        'UNIT'=>'Hectares',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['FA_NA'],
        'EA_NAME'=>'FA_HA',
        'EA_VALUE'=>$estimationData['FA_HA'],
        'AC_NAME'=>'FA_HA_ACHIEVE',
        'AC_VALUE'=>$achievementData['FA_HA'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>6,
        'TITLE'=>'Forest Acquisition Completed',
        'UNIT'=>'Hectares',
        'NA_BOX'=>FALSE,
        'NA_VALUE'=>$estimationStatus['FA_NA'],
        'EA_NAME'=>'FA_COMPLETED_HA',
        'EA_VALUE'=>$estimationData['FA_COMPLETED_HA'],
        'AC_NAME'=>'FA_COMPLETED_HA_ACHIEVE',
        'AC_VALUE'=>$achievementData['FA_COMPLETED_HA'],
        'SHOW'=>0
    ),
    array(
        'SNO'=>7,
        'TITLE'=>'Earthwork <br />(As per "L" Earthwork section of AA)',
        'UNIT'=>'Th Cum',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['L_EARTHWORK_NA'],
        'EA_NAME'=>'L_EARTHWORK',
        'EA_VALUE'=>$estimationData['L_EARTHWORK'],
        'AC_NAME'=>'L_EARTHWORK_ACHIEVE',
        'AC_VALUE'=>$achievementData['L_EARTHWORK'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>9,
        'TITLE'=>' Masonry/Concrete',
        'UNIT'=>'Th Cum',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['C_MASONRY_NA'],
        'EA_NAME'=>'C_MASONRY',
        'EA_VALUE'=>$estimationData['C_MASONRY'],
        'AC_NAME'=>'C_MASONRY_ACHIEVE',
        'AC_VALUE'=>$achievementData['C_MASONRY'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>10,
        'TITLE'=>' Pipe Works',
        'UNIT'=>'Mtrs',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['C_PIPEWORK_NA'],
        'EA_NAME'=>'C_PIPEWORK',
        'EA_VALUE'=>$estimationData['C_PIPEWORK'],
        'AC_NAME'=>'C_PIPEWORK_ACHIEVE',
        'AC_VALUE'=>$achievementData['C_PIPEWORK'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>11,
        'TITLE'=>' Drip Pipe',
        'UNIT'=>'Mtrs',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['C_DRIP_PIPE_NA'],
        'EA_NAME'=>'C_DRIP_PIPE',
        'EA_VALUE'=>$estimationData['C_DRIP_PIPE'],
        'AC_NAME'=>'C_DRIP_PIPE_ACHIEVE',
        'AC_VALUE'=>$achievementData['C_DRIP_PIPE'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>12,
        'TITLE'=>' Water Pumps',
        'UNIT'=>'Numbers',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['C_WATERPUMP_NA'],
        'EA_NAME'=>'C_WATERPUMP',
        'EA_VALUE'=>$estimationData['C_WATERPUMP'],
        'AC_NAME'=>'C_WATERPUMP_ACHIEVE',
        'AC_VALUE'=>$achievementData['C_WATERPUMP'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>13,
        'TITLE'=>'Building Works <br />( As per "K" Building section of AA) Control Rooms',
        'UNIT'=>'Numbers',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['K_CONTROL_ROOMS_NA'],
        'EA_NAME'=>'K_CONTROL_ROOMS',
        'EA_VALUE'=>$estimationData['K_CONTROL_ROOMS'],
        'AC_NAME'=>'K_CONTROL_ROOMS_ACHIEVE',
        'AC_VALUE'=>$achievementData['K_CONTROL_ROOMS'],
        'SHOW'=>1
    ),
    array(
        'SNO'=>14 ,
        'TITLE'=>'Designed Irrigation Potential',
        'UNIT'=>'Hectares',
        'NA_BOX'=>TRUE,
        'NA_VALUE'=>$estimationStatus['IP_TOTAL_NA'],
        'EA_NAME'=>'IP_TOTAL',
        'EA_VALUE'=>$estimationData['IP_TOTAL'],
        'AC_NAME'=>'IP_TOTAL_ACHIEVE',
        //'AC_VALUE'=>$achievementData['IP_TOTAL'],
        'AC_VALUE'=>($achievementData['KHARIF']+$achievementData['RABI']),
        'SHOW'=>1,
        'KHARIF'=>array(
            'EA_NAME'=>'KHARIF',
            'EA_VALUE'=>$estimationData['KHARIF'],
            'AC_NAME'=>'KHARIF_ACHIEVE',
            'AC_VALUE'=>$achievementData['KHARIF']
        ),
        'RABI' => array(
            'EA_NAME'=>'RABI',
            'EA_VALUE'=>$estimationData['RABI'],
            'AC_NAME'=>'RABI_ACHIEVE',
            'AC_VALUE'=>$achievementData['RABI']
        )
    )
);

/////////////////////////////////////
$arrIPBlockIds = array();
//showArrayValues($BLOCK_IP_DATA);
$blockContent = '';
if($BLOCK_IP_DATA){
	$i=0;
	foreach($BLOCK_IP_DATA as $k=>$bb){
		//showArrayValues($bb);
		array_push($arrIPBlockIds, $k);
	//for($i=0; $i<count($BLOCK_IP_DATA);$i++){
		$blockContent .= '<tr>
			<td class="ui-widget-content" rowspan="3" width="20" align="center">'.(chr(97+$i++)).'</td>
			<td class="ui-widget-content" rowspan="3" colspan="2" ><strong>'.$bb['BLOCK_NAME'].'</strong></td>
			<td class="ui-widget-content" ><strong>Kharif</strong></td>
			<td class="ui-widget-content" align="right">'.$bb['ESTIMATION_IP']['KHARIF'].'</td>
			<td class="ui-widget-content" align="right">'.$bb['ACHIEVEMENT_IP']['KHARIF'].'</td>
		</tr>
		<tr><td class="ui-widget-content"><strong>Rabi</strong></td>
			<td class="ui-widget-content" align="right">'.$bb['ESTIMATION_IP']['RABI'].'</td>
			<td class="ui-widget-content"  align="right">'.$bb['ACHIEVEMENT_IP']['RABI'].'</td>
		</tr>
		<tr><td class="ui-state-default"><strong>Total</strong></td>
			<td class="ui-state-default" align="right">'.$bb['ESTIMATION_IP']['IP'].'</td>
			<td class="ui-state-default" align="right">'.$bb['ACHIEVEMENT_IP']['IP'].'</td>
		</tr>';
	}
}
//showArrayValues($arrAchievementCompo);
//($estimationStatus['IRRIGATION_POTENTIAL_NA']==0){
//$content = '';
$ipRow = 14;
$finalPrint = '';
foreach($arrEstimationAchievements as $x){
	/*$sno = $x['SNO'];//(($x['SNO']>=3)? ($x['SNO']-2):$x['SNO']);
	if($sno==11) 		$sno = '11a';
	else if($sno==12) 	$sno = '11b';
	else if($sno>12)	$sno = $sno-1;*/
    if($x['SNO']>1){
        $sno = $x['SNO']-1;        
        if($x['SNO']>4){
            $sno = ($x['SNO']-2);
            if($x['SNO']>12){
                //$sno = $x['SNO']-4;
                $sno = $x['SNO']-6;
            }
        }
    }else {
        $sno = $x['SNO'];
    }
    if($x['SNO']==$ipRow){
        $IP_NA = $x['NA_VALUE'];
        $arrIP = $x;
    }

	$myClass = ($x['NA_VALUE'])? '' : 'required,';
	$rowSpan = '';
	//if($x['SNO']==$ipRow) $rowSpan = 'rowspan="3"';
	/*$finalPrint .= '<tr>
		<td nowrap="nowrap" class="ui-widget-content"  align="center"'.$rowSpan.'>'.$sno.'</td>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'>'.$x['TITLE'].'</td>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'>'.$x['UNIT'].'</td>';*/

    if($x['SNO']==8){
        $finalPrint .= '<tr>
		<td nowrap="nowrap" class="ui-widget-content" align="center" rowspan="5"><strong>'.$sno.'</strong></td>
		<td nowrap="nowrap" class="ui-widget-content"  rowspan="5"><strong>Masonry/Concrete <br />(As per "C" Masonry section of AA)</strong></td>
		<td nowrap="nowrap" class="ui-widget-content" ><strong>'.$x['TITLE'].'</strong></td>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'><strong>'.$x['UNIT'].'</strong></td>';

    }else if ($x['SNO']==1){
        echo '<tr>
        <td nowrap="nowrap" class="ui-widget-content" align="center" rowspan="2"><strong>'.$sno.'</strong></td>
        <td nowrap="nowrap" class="ui-widget-content"  rowspan="2" colspan="2"><strong>Land Acquisition to be done  <br />(As per "B" Land section of DPR)</strong></td>
        <td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'><strong>'.$x['UNIT'].'</strong></td>';
        //<td nowrap="nowrap" class="ui-widget-content" ><strong>'.$x['TITLE'].'</strong></td>
    } else {
        $finalPrint .='<tr>';
        $colspan = '';
        if($x['SNO']>8 && $x['SNO']<=12){

        }elseif ($x['SNO']==2) {
            
        }else{
            $colspan = ' colspan="2"';
            $finalPrint .='<td nowrap="nowrap" class="ui-widget-content" align="center" ' . $rowSpan . '><strong>' . $sno . '</strong></td>';
        }
        if($x['SNO']==2) {
            
        }else{
            $finalPrint .= '
            <td nowrap="nowrap" class="ui-widget-content" ' . $rowSpan . ' '.$colspan.'><strong>' . $x['TITLE'] . '</strong></td>
            <td nowrap="nowrap" class="ui-widget-content" ' . $rowSpan . '><strong>' . $x['UNIT'] . '</strong></td>';
            //echo '<td nowrap="nowrap" class="ui-widget-content" ' . $rowSpan . ' ' . $colspan . '><strong>' . $x['TITLE'] . '</strong></td>';
        }
    }
	//if($x['SNO']!=$ipRow) 
	//	$finalPrint .= '<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'></td>';
	if($x['NA_VALUE']){
		$finalPrint .= str_repeat('<td nowrap="nowrap" class="ui-widget-content" align="right" '.$rowSpan.'>NA</td>',2).'</tr>';
		continue;
	}
	/*if($x['NA_BOX']){
		if($x['EA_NAME']=='LA_NO' || $x['EA_NAME']=='FA_HA'){
			echo '<td align="center" class="ui-widget-content" rowspan="'.
			(($x['EA_NAME']=='LA_NO')? 4:2).'" valign="middle">';
		}else{
			echo '<td align="center" class="ui-widget-content" '.$rowSpan.'>';
		}
		echo ( ($x['NA_VALUE']) ? 'NA' : '' ) .'</td>';
	}*/
	if($x['SHOW']==0){
		$finalPrint .= '<td align="center" class="ui-widget-content"></td>
		<td align="right" class="ui-widget-content">'.$x['AC_VALUE'].'</td>';
	}else{
		if($x['NA_VALUE']){
			$finalPrint .= str_repeat('<td align="right" class="ui-widget-content">NA</td>', 2);
		}else{
			//$blockContent.
			if($x['SNO']==$ipRow){
				$finalPrint .= '<td align="left" class="ui-widget-content" colspan="2"></td></tr>'.
				$blockContent.
				'<tr>
				<td align="left" class="ui-widget-content" rowspan="3"></td>
				<td align="left" class="ui-widget-content" rowspan="3" colspan="2">Total Irrigation Potential</td>
				<td align="left" class="ui-widget-content">Kharif</td>
				<td align="right" class="ui-widget-content">'.$x['KHARIF']['EA_VALUE'].'</td>
				<td align="right" class="ui-widget-content">'.$x['KHARIF']['AC_VALUE'].'</td>
				</tr>
				<tr>
				<td align="left" class="ui-widget-content">Rabi</td>
				<td align="right" class="ui-widget-content">'.$x['RABI']['EA_VALUE'].'</td>
				<td align="right" class="ui-widget-content">'.$x['RABI']['AC_VALUE'].'</td>
				</tr>
				<tr>
				<td align="left" class="ui-state-default">Total</td>
				<td align="right" class="ui-state-default">'.$x['EA_VALUE'].'</td>
				<td align="right" class="ui-state-default">'.$x['AC_VALUE'].'</td>';
			}else
				if(($sno=='11b') && ($x['EA_VALUE']==0))
					$finalPrint .= '<td align="right" class="ui-widget-content">NA</td>
					<td align="right" class="ui-widget-content">NA</td>';
				else
					$finalPrint .= '<td align="right" class="ui-widget-content">'.$x['EA_VALUE'].'</td>
					<td align="right" class="ui-widget-content">'.$x['AC_VALUE'].'</td>';
		}
	}
	$finalPrint .= '</tr>';
}//foreach
echo $finalPrint;?>
        </table>
    </td>
</tr>
</table>

<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
<tr>
    <td valign="middle" align="center" colspan="5">Completion Date of Scheme :<?php echo myDateFormat($projectSetupValues['PROJECT_COMPLETION_DATE']);?></td>
</tr>   
<tr>
    <td valign="top" colspan="4"> 
        <table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">                  
        <tr>
            <th class="ui-widget-header">Contents</th>
            <th class="ui-widget-header">Status upto Last Financial Year</th>
            <th class="ui-widget-header">Target Dates of Completion</th>
        </tr>
<?php
$arrStatus = array(
	array('SNO'=>1,
		'TITLE'=>'a) Submission of LA Cases', 
		'STATUS_BOX_NAME'=>'LA_CASES_STATUS',
		'DATE_BOX_NAME'=>'LA_DATE',
		'OPTION_LIST' => (($statusData) ? $statusData->LA_CASES_STATUS:0)
	),
	array('SNO'=>2,
		'TITLE'=>'b) Submission of FA Cases',
		'STATUS_BOX_NAME'=>'FA_CASES_STATUS',
		'DATE_BOX_NAME'=>'FA_DATE',
		'OPTION_LIST' =>  (($statusData) ? $statusData->FA_CASES_STATUS:0)
	),
	array('SNO'=>3,
		'TITLE'=>'c) Intake Well',
		'STATUS_BOX_NAME'=>'INTAKE_WELL_STATUS',
		'DATE_BOX_NAME'=>'INTAKE_WELL_DATE',
		'OPTION_LIST' =>  (($statusData) ? $statusData->INTAKE_WELL_STATUS:0)
	),
	array('SNO'=>4,
		'TITLE'=>'d) Pumping Unit',
		'STATUS_BOX_NAME'=>'PUMPING_UNIT_STATUS',
		'DATE_BOX_NAME'=>'PUMPING_UNIT_DATE',
		'OPTION_LIST' =>  (($statusData) ? $statusData->PUMPING_UNIT_STATUS:0)
	),
	array('SNO'=>5,
		'TITLE'=>'e) PVC Lift System',
		'STATUS_BOX_NAME'=>'PVC_LIFT_SYSTEM_STATUS',
		'DATE_BOX_NAME'=>'PVC_LIFT_SYSTEM_DATE',
		'OPTION_LIST' =>  (($statusData) ? $statusData->PVC_LIFT_SYSTEM_STATUS:0)
	),
	array('SNO'=>6,
		'TITLE'=>'f) Pipe Distribution Network',
		'STATUS_BOX_NAME'=>'PIPE_DISTRI_STATUS',
		'DATE_BOX_NAME'=>'PIPE_DISTRI_DATE',
		'OPTION_LIST' =>  (($statusData) ? $statusData->PIPE_DISTRI_STATUS:0)
	),
	array('SNO'=>7,
		'TITLE'=>'g) Drip System',
		'STATUS_BOX_NAME'=>'DRIP_SYSTEM_STATUS',
		'DATE_BOX_NAME'=>'DRIP_SYSTEM_DATE',
		'OPTION_LIST' =>  (($statusData) ? $statusData->DRIP_SYSTEM_STATUS:0)
	),
	array('SNO'=>8,
		'TITLE'=>'h)  Water Storage Tank',
		'STATUS_BOX_NAME'=>'WATER_STORAGE_TANK_STATUS',
		'DATE_BOX_NAME'=>'WATER_STORAGE_TANK_DATE',
		'OPTION_LIST' =>  (($statusData) ? $statusData->WATER_STORAGE_TANK_STATUS:0)
	),
    array('SNO'=>9,
        'TITLE'=>'i) Fertilizer and Pesticide Carrier System',
        'STATUS_BOX_NAME'=>'FERTI_PESTI_CARRIER_SYSTEM_STATUS',
        'DATE_BOX_NAME'=>'FERTI_PESTI_CARRIER_SYSTEM_DATE',
        'OPTION_LIST' =>  (($statusData) ? $statusData->FERTI_PESTI_CARRIER_SYSTEM_STATUS:0)
    ),
    array('SNO'=>10,
        'TITLE'=>'j) Control Rooms',
        'STATUS_BOX_NAME'=>'CONTROL_ROOMS_STATUS',
        'DATE_BOX_NAME'=>'CONTROL_ROOMS_DATE',
        'OPTION_LIST' =>  (($statusData) ? $statusData->CONTROL_ROOMS_STATUS:0)
    )
);
foreach($arrStatus as $arrSt){
	$displayCSS =  '';
	if($setupStatusData[$arrSt['STATUS_BOX_NAME']]==1 ||
        $setupStatusData[$arrSt['STATUS_BOX_NAME']]==0){
		$displayCSS = 'none';
		$isRequired = '';
	}
	echo '<tr>
			<td class="ui-widget-content">'.$arrSt['TITLE'].'</td>
			<td class="ui-widget-content" align="center">'.
				$arrStatusOption[ $arrSt['OPTION_LIST'] ].'
			</td>
			<td class="ui-widget-content" align="center">
				<div id="req'.$arrSt['DATE_BOX_NAME'].'" style="float:left;display:'.$displayCSS.'">'.
				'</div>
				'.myDateFormat($TARGET_DATES_VALUES[$arrSt['DATE_BOX_NAME']]).' 
			</td>
		  </tr>';
}?>
        </table>
    </td>
</tr>
</table>
</div>