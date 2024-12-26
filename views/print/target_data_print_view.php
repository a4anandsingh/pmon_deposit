<?php echo getPrintButton('prjtarget_report', 'Print');?>
<div id="prjtarget_report">
    <table width="100%" border="0" cellpadding="4" cellspacing="1" class="ui-widget-content">
    <tr>
        <td colspan="11" align="center" class="ui-widget-header">
        	<big>
            	<strong>Financial and Physical Target Setup</strong>
            </big>
        </td>
    </tr>
    <tr>
      <td colspan="11" align="center" class="ui-state-default">
   	  <big><strong>For the Financial Year - <?php echo $session_year;?></strong></big></td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="2" cellspacing="1" class="ui-widget-content">
            <tr>
                <td width="100" class="ui-state-default"><strong>Name of Project</strong></td>
                <td class="ui-widget-content">
                    <big><strong><?php echo $PROJECT_NAME;?></strong></big>
                </td>
                <td width="100" class="ui-state-default"><strong>Project Code</strong></td>
                <td width="150" class="ui-widget-content"><strong><?php echo $PROJECT_CODE;?></strong></td>
            </tr>
            <tr>
                <td class="ui-state-default"><strong>Budget Amount</strong></td>
                <td colspan="3" class="ui-widget-content">
                    <?php echo $BUDGET_AMOUNT;?>
                (Rs. In Lakhs)</td>
            </tr>
            </table>
        </td>
    </tr>
	</table>
    <br />
    <table width="100%" border="0" cellpadding="4" cellspacing="1" class="ui-widget-content">          
    <tr>
        <th rowspan="3" class="ui-state-default">&nbsp;</th>
        <th rowspan="3" class="ui-state-default">Financial</th>
        <th colspan="2" rowspan="3" class="ui-state-default">Land Acquisition <br /> (cases to be submitted)</th>
        <th rowspan="3" class="ui-state-default">Forest cases<br />to be<br />submitted </th>
        <th colspan="6" class="ui-state-default">Physical</th>
        <th colspan="3" rowspan="2" class="ui-state-default">Irrigation <br /> Potential<br />to be created</th>
    </tr>
    <tr>
        <th colspan="3" class="ui-state-default">H/w</th>
        <th colspan="3" class="ui-state-default">Canals</th>
      </tr>
    <tr>
        <th class="ui-state-default">E/work</th>
        <th class="ui-state-default">Masonry/<br />concrete</th>
        <th class="ui-state-default">Steel <br />
        Works</th>
        <th class="ui-state-default">E/w</th>
        <th class="ui-state-default">Lining</th>
        <th class="ui-state-default">Structures</th>
        <th class="ui-state-default">Kharif</th>
        <th class="ui-state-default">Rabi</th>
        <th class="ui-state-default">Total</th>
    </tr>
    <tr>
        <th class="ui-state-default">&nbsp;</th>
        <th class="ui-state-default">Rs. Lacs</th>
        <th class="ui-state-default">Number</th>
        <th class="ui-state-default">Hectares</th>
        <th class="ui-state-default">Hectares</th>
        <th class="ui-state-default">Th Cum</th>
        <th class="ui-state-default">Th Cum</th>
        <th class="ui-state-default">mt</th>
        <th class="ui-state-default">Th Cum</th>
        <th class="ui-state-default">Km.</th>
        <th class="ui-state-default">Number</th>
        <th class="ui-state-default">Ha</th>
        <th class="ui-state-default">Ha</th>
        <th class="ui-state-default">Ha</th>
    </tr>
<?php
	$monthsOfFinyear = array(
		1=>'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
	);
	$i=4;
	$arrRows = array();
	$totalFields = array(
		'EXPENDITURE', 'LA_NO', 'LA_HA', 'FA_HA', 
		'HEAD_WORKS_EARTHWORK', 'HEAD_WORKS_MASONRY', 
		'CANAL_EARTHWORK', 'CANAL_LINING', 'STEEL_WORKS',
		'CANAL_STRUCTURES', 'IRRIGATION_POTENTIAL',
		'IRRIGATION_POTENTIAL_KHARIF', 'IRRIGATION_POTENTIAL_RABI'
	);
	$arrTotal = array();
	for($iC=0;$iC<count($totalFields);$iC++){
		$arrTotal[$totalFields[$iC]] = 0;
	}
	for($a=1 ; $a<=12;$a++){
		for($iC=0;$iC<count($totalFields);$iC++){
			//echo ':::'.$a->{$totalFields[$iC]}.':::';
			$arrTotal[$totalFields[$iC]] += (float) $records[$a]->{$totalFields[$iC]};
		}	
		$arrMonthTarget = array(
			array(
				'NAME'=>'MON',
				'VALUE'=>$monthsOfFinyear[$i],
				'TYPE'=>'caption',
				'MONTH' =>$i,
				'COL_WIDTH' =>0,
				'SHOW'=>1
			),
			array(
				'NAME'=>'EXPENDITURE',
				'VALUE'=> $records[$a]->EXPENDITURE,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=>1
			),
			array(
				'NAME'=>'LA_NO',
				'VALUE'=> $records[$a]->LA_NO,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>3,
				'SHOW'=> ( ($setupData['LA_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'LA_HA',
				'VALUE'=> $records[$a]->LA_HA,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=> ( ($setupData['LA_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'FA_HA',
				'VALUE'=> $records[$a]->FA_HA,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=> ( ($setupData['FA_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'HEAD_WORKS_EARTHWORK',
				'VALUE'=> $records[$a]->HEAD_WORKS_EARTHWORK,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=> ( ($setupData['HEAD_WORKS_EARTHWORK_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'HEAD_WORKS_MASONRY',
				'VALUE'=> $records[$a]->HEAD_WORKS_MASONRY,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=> ( ($setupData['HEAD_WORKS_MASONRY_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'STEEL_WORKS',
				'VALUE'=> $records[$a]->STEEL_WORKS,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=> ( ($setupData['STEEL_WORKS_NA']==0)? 1:0)
			),
			
			array(
				'NAME'=>'CANAL_EARTHWORK',
				'VALUE'=> $records[$a]->CANAL_EARTHWORK,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=> ( ($setupData['CANAL_EARTHWORK_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'CANAL_LINING',
				'VALUE'=> $records[$a]->CANAL_LINING,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=> ( ($setupData['CANAL_LINING_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'CANAL_STRUCTURES',
				'VALUE'=> $records[$a]->CANAL_STRUCTURES,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>3,
				'SHOW'=> ( ($setupData['CANAL_STRUCTURES_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'IRRIGATION_POTENTIAL',
				'VALUE'=> $records[$a]->IRRIGATION_POTENTIAL,
				'TYPE'=>'text',
				'MONTH' =>$a,
				'COL_WIDTH' =>6,
				'SHOW'=> ( ($setupData['IRRIGATION_POTENTIAL_NA']==0)? 1:0),
				'KHARIF' =>$records[$a]->IRRIGATION_POTENTIAL_KHARIF,
				'RABI' =>$records[$a]->IRRIGATION_POTENTIAL_RABI
			)
		);
		array_push($arrRows, $arrMonthTarget);
		if ($i==12){$i=1;} else{$i++;}
	}//for

	foreach($arrRows as $arrRow){
		echo '<tr>';
		foreach($arrRow as $arrColumn){
			echo '<td class="ui-widget-content" align="center">';
			if( $arrColumn['NAME']=='MON' ){
				echo $arrColumn['VALUE'];
			}else{
				if( $arrColumn['SHOW'] ){
					if( $arrColumn['NAME']=='IRRIGATION_POTENTIAL' ){
						echo $arrColumn['KHARIF'].
						'</td><td class="ui-widget-content" align="center">'.
						$arrColumn['RABI'].
						'</td><td class="ui-widget-content" align="center">'.
						$arrColumn['VALUE'];
					}else
						echo $arrColumn['VALUE'];
				}else{
					echo 'NA';
				}
			}
			echo '</td>';
		}
		echo '</tr>';
	}
?>
    </tr>
    <tr>
		<td class="ui-state-default" align="center"><strong>Total</strong></td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo  giveComma($arrTotal['EXPENDITURE'], 2);?></strong>
        </td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo ($setupData['LA_NA']==0) ? $arrTotal['LA_NO']:'NA';?></strong>
        </td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo ($setupData['LA_NA']==0) ? giveComma($arrTotal['LA_HA'], 3):'NA';?></strong>
        </td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo ($setupData['FA_NA']==0) ? giveComma($arrTotal['FA_HA'], 3):'NA';?></strong>
        </td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo ($setupData['HEAD_WORKS_EARTHWORK_NA']==0) ? giveComma($arrTotal['HEAD_WORKS_EARTHWORK'], 3):'NA';?></strong>
        </td>
		<td align="center" class="ui-state-default">
        	<strong><?php echo ($setupData['HEAD_WORKS_MASONRY_NA']==0) ? giveComma($arrTotal['HEAD_WORKS_MASONRY'], 3):'NA';?></strong>
        </td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo ($setupData['STEEL_WORKS_NA']==0) ? giveComma($arrTotal['STEEL_WORKS'], 3):'NA';?></strong>
        </td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo ($setupData['CANAL_EARTHWORK_NA']==0) ? giveComma($arrTotal['CANAL_EARTHWORK'], 3):'NA';?></strong>
        </td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo ($setupData['CANAL_LINING_NA']==0) ? giveComma($arrTotal['CANAL_LINING'], 3):'NA';?></strong>
        </td>
		<td class="ui-state-default" align="center">
        	<strong><?php echo ($setupData['CANAL_STRUCTURES_NA']==0) ? $arrTotal['CANAL_STRUCTURES']:'NA';?></strong>
        </td>
        <?php if($setupData['IRRIGATION_POTENTIAL_NA']==0){?>
			<td align="center" class="ui-state-default">
        	<strong><?php echo giveComma($arrTotal['IRRIGATION_POTENTIAL_KHARIF'], 3);?></strong>
            </td>
			<td align="center" class="ui-state-default">
        	<strong><?php echo giveComma($arrTotal['IRRIGATION_POTENTIAL_RABI'], 3);?></strong>
            </td>
			<td align="center" class="ui-state-default">
        	<strong><?php echo giveComma($arrTotal['IRRIGATION_POTENTIAL'], 3);?></strong>
            </td>
        <?php }else{?>
			<td align="center" class="ui-state-default">NA</td>
			<td align="center" class="ui-state-default">NA</td>
			<td align="center" class="ui-state-default">NA</td>
        <?php }?>
	</tr>
    
    </table>
</div>