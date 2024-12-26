<?php
echo getPrintButton("prjtarget_report", 'Print', 'xprjtarget_report');?>
<div id="prjtarget_report">
    <table width="100%" border="0" cellpadding="4" cellspacing="1" class="ui-widget-content">
    <tr>
        <td colspan="11" align="center" class="ui-widget-header"><big><strong>Financial and Physical Target Setup</strong></big></td>
    </tr>
    <tr>
        <td colspan="11" align="center" class="ui-state-default"><big><strong>For the Financial Year - <?php echo $session_year;?></strong></big></td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="2" cellspacing="1" class="ui-widget-content">
            <tr>
                <td width="100" class="ui-state-default"><strong>Name of Project</strong></td>
                <td class="ui-widget-content"><big><strong><?php echo $PROJECT_NAME;?></strong></big></td>
                <td width="100" class="ui-state-default"><strong>Project Code</strong></td>
                <td width="150" class="ui-widget-content"><strong><?php echo $PROJECT_CODE;?></strong></td>
            </tr>
            <tr>
                <td class="ui-state-default"><strong>Budget Amount</strong></td>
                <td class="ui-widget-content"><?php echo $BUDGET_AMOUNT;?>(Rs. In Lakhs)</td>
                <td class="ui-state-default"><strong><?php echo $AA_RAA; ?> Amount (Rs.) </strong></td>
                <td class="ui-widget-content" align="right"><strong><?php echo $AA_AMOUNT; ?></strong> (in Lakh)</td>
            </tr>
            </table>
        </td>
    </tr>
    </table>
    <br />
    <table width="100%" border="1" cellpadding="4" cellspacing="1" class="ui-widget-content" id="xprjtarget_report">
        <thead>
        <tr>
            <th rowspan="3" class="ui-state-default">Month</th>
            <th rowspan="3" class="ui-state-default">Financial</th>
            <th colspan="2" rowspan="3" class="ui-state-default">Land <br/>Acquisition <br/> (cases to be<br/> submitted)</th>
            <th rowspan="3" class="ui-state-default">Forest<br/>cases<br/>to be<br/>submitted</th>
            <th colspan="2" rowspan="2" class="ui-state-default">Earthwork <br/> (As per <br/>                      Earthwork<br/>
                given in<br/>DPR)</th>
            <th colspan="5" class="ui-state-default">Masonry/Concrete <br/>(As per "C" Masonry section of DPR)</th>
            <th rowspan="3" class="ui-state-default">Building Works<br/> ( As per <br/>"K" <br/>Building section<br/> of DPR) <br/>Control Rooms</th>
            <th colspan="4" class="ui-state-default">Irrigation Potential<br/> to be created</th>
        </tr>
        <tr>
            <th rowspan="2" class="ui-state-default">Masonry/<br/>concrete</th>
            <th colspan="3" class="ui-state-default">Pipe Works</th>
            <th rowspan="2" class="ui-state-default">Pumps/<br />Submersible Pumps/<br />Hand Pumps</th>
            <th rowspan="2" class="ui-state-default">Block</th>
            <th rowspan="2" class="ui-state-default">Kharif</th>
            <th rowspan="2" class="ui-state-default">Rabi</th>
            <th rowspan="2" class="ui-state-default">Total</th>
        </tr>
        <tr>
            <th class="ui-state-default">Earthwork</th>
            <th class="ui-state-default">Drilling</th>
            <th class="ui-state-default"><strong>i. Housing Pipe</strong></th>
            <th class="ui-state-default"><strong>ii. Blind Pipe/Casing Pipe</strong></th>
            <th class="ui-state-default">iii. Slotted Pipe/Screen</th>
        </tr>
        <tr>
            <th class="ui-state-default">&nbsp;</th>
            <th class="ui-state-default">Rs. Lacs</th>
            <th class="ui-state-default">Number</th>
            <th class="ui-state-default">Hectares</th>
            <th class="ui-state-default">Hectares</th>
            <th class="ui-state-default">Th Cum</th>
            <th class="ui-state-default">Mtrs</th>
            <th class="ui-state-default">Th Cum</th>
            <th class="ui-state-default">Mtrs</th>
            <th class="ui-state-default">Mtrs</th>
            <th class="ui-state-default">Mtrs</th>
            <th class="ui-state-default">Numbers</th>
            <th class="ui-state-default">Numbers</th>
            <th class="ui-state-default">Name</th>
            <th class="ui-state-default">Ha</th>
            <th class="ui-state-default">Ha</th>
            <th class="ui-state-default">Ha</th>
        </tr>
        </thead>
    <tbody>
        <?php
        $monthsOfFinyear = array( 1=>'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $i=4;
        $arrRows = array();
        //'EXPENDITURE',
        $totalFields = array(
            'LA_NO', 'LA_HA', 'FA_HA', 'EXPENDITURE',
            'L_EARTHWORK', 
            'C_MASONRY', 'C_PIPEWORK', 'C_DRIP_PIPE',
            'C_WATERPUMP', 'K_CONTROL_ROOMS'
        );
        //, 'KHARIF', 'RABI','IP_TOTAL',
        $arrTotal = array();
        foreach($totalFields as $f) $arrTotal[$f] = 0;
        //showArrayValues($records);        exit;
        //for($a=1 ; $a<=12;$a++){
        foreach($records as $key => $targetDatum) {
            $time = '';
            $time = strtotime($records[$key]->TARGET_DATE);
            $a = date("m", $time);

            foreach($totalFields as $f){
                //echo ':::'.$a->{$totalFields[$iC]}.':::';
                $arrTotal[$f] += (float) $records[$key]->{$f};
            }
            //echo '<br />';
            //print_r($records);
            $arrMonthTarget = array(
                array(
                    'NAME' => 'MON',
                    //'VALUE' => $monthsOfFinyear[$i],
                    //'VALUE' => (date('M',$a)),
                    'VALUE' => date('M', strtotime($key)) ,
                    'TYPE' => 'caption',
                    'MONTH' => $a,
                    'COL_WIDTH' => 0,
                    'SHOW' => 1,
                    'TARGET_DATE'=>$key
                ),
                array(
                    'NAME' => 'EXPENDITURE',
                    'VALUE' => $records[$key]->EXPENDITURE,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => 1
                ),
                array(
                    'NAME' => 'LA_NO',
                    'VALUE' => $records[$key]->LA_NO,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 1,
                    'SHOW' => (($setupData['LA_NA'] == 0) ? 1 : 0)
                ),
                array(
                    'NAME' => 'LA_HA',
                    'VALUE' => $records[$key]->LA_HA,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['LA_NA'] == 0) ? 1 : 0)
                ),
                array(
                    'NAME' => 'FA_HA',
                    'VALUE' => $records[$key]->FA_HA,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['FA_NA'] == 0) ? 1 : 0)
                ),
                array(
                    'NAME' => 'L_EARTHWORK',
                    'VALUE' => $records[$key]->L_EARTHWORK,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['L_EARTHWORK_NA'] == 0) ? 1 : 0)
                ),
                array(
                    'NAME' => 'DRILLINGWORK',
                    'VALUE' => $records[$key]->DRILLINGWORK,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['DRILLINGWORK_NA'] == 0) ? 1 : 0)
                ),

                array(
                    'NAME' => 'C_MASONRY',
                    'VALUE' => $records[$key]->C_MASONRY,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['C_MASONRY_NA'] == 0) ? 1 : 0)
                ),


                array(
                    'NAME' => 'HOUSING_PIPE',
                    'VALUE' => $records[$key]->HOUSING_PIPE,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['HOUSING_PIPE_NA'] == 0) ? 1 : 0)
                ),
                array(
                    'NAME' => 'BLIND_PIPE',
                    'VALUE' => $records[$key]->BLIND_PIPE,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['HOUSING_PIPE_NA'] == 0) ? 1 : 0)
                ),
                array(
                    'NAME' => 'SLOTTED_PIPE',
                    'VALUE' => $records[$key]->SLOTTED_PIPE,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['SLOTTED_PIPE_NA'] == 0) ? 1 : 0)
                ),
                array(
                    'NAME' => 'SUBMERSIBLE',
                    'VALUE' => $records[$key]->SUBMERSIBLE,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['SUBMERSIBLE_NA'] == 0) ? 1 : 0)
                ),
                array(
                    'NAME' => 'K_CONTROL_ROOMS',
                    'VALUE' => $records[$key]->K_CONTROL_ROOMS,
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 1,
                    'SHOW' => (($setupData['K_CONTROL_ROOMS_NA'] == 0) ? 1 : 0)
                )/*,
                            array(
                                'NAME' => 'IP_TOTAL',
                                'VALUE' => $arrTargetData[$key]->IP_TOTAL,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($records['IP_TOTAL_NA'] == 0) ? 1 : 0),
                                'KHARIF' => $arrTargetData[$key]->KHARIF,
                                'RABI' => $arrTargetData[$key]->RABI
                            )*/
            ,
                array(
                    'NAME' => 'IP_TOTAL',
                    'VALUE' => '',
                    'TYPE'=>'text',
                    'MONTH' => $a,
                    'COL_WIDTH' => 6,
                    'SHOW' => (($setupData['IP_TOTAL_NA'] == 0) ? 1 : 0),
                    'KHARIF' => '',
                    'RABI' => '',
                    'TARGET_DATE'=>$key
                )
            );
            array_push($arrRows, $arrMonthTarget);
            if ($i==12){$i=1;} else{$i++;}
        }//for

        $subTotalKharif=0;
        $subTotalRabi=0;
        $subTotalIp=0;

        $grandTotalKharif=0;
        $grandTotalRabi=0;
        $grandTotalIp=0;


        foreach($arrRows as $arrRow){
            echo '<tr>';
			$rowspan = '';
			if(count($arrBlockIps)>1){
				$rowspan =  'rowspan="'.(count($arrBlockIps) + 1).'"';
			}
			//$rowspan = (($arrColumn['NAME'] == 'IP_TOTAL') ? '' : (count($arrBlockIps) + 1))
            foreach($arrRow as $arrColumn){
                $subTotalKharif = $subTotalRabi = $subTotalIp = 0;
                $arrMonthB = array();
                echo '<td class="ui-widget-content" ' . (($arrColumn['NAME'] == 'IP_TOTAL') ? '' : $rowspan) . ' align="center">';
                if( $arrColumn['NAME']=='MON' ){
                    echo $arrColumn['VALUE'];
                }else{
                    if( $arrColumn['SHOW'] ){
                        if( $arrColumn['NAME']=='IP_TOTAL' ){
                            /*echo $arrColumn['KHARIF'].
                                '</td><td class="ui-widget-content" align="center">'.
                                $arrColumn['RABI'].
                                '</td><td class="ui-widget-content" align="center">'.
                                $arrColumn['VALUE'];*/
                            $cnt = 0;
                            foreach ($arrBlockIps as $k => $v) {
                                array_push($arrMonthB, $k);
                                $cnt++;
                                if ($cnt > 1) {
                                    echo '<tr><td align="center">' . $v['BLOCK_NAME'] . '</td>';
                                } else {
                                    echo $v['BLOCK_NAME'] . '</td>';
                                }
                                echo '<td class="ui-widget-content" align="center">';
                                echo $targetBlockData[$arrColumn['TARGET_DATE']][$k]->KHARIF . '</td>
                                    <td class="ui-widget-content" align="center">' . $targetBlockData[$arrColumn['TARGET_DATE']][$k]->RABI . '</td>                                    
                                    <td class="ui-widget-content" align="center">' . ($targetBlockData[$arrColumn['TARGET_DATE']][$k]->KHARIF+$targetBlockData[$arrColumn['TARGET_DATE']][$k]->RABI);
                                $subTotalKharif += $targetBlockData[$arrColumn['TARGET_DATE']][$k]->KHARIF;
                                $subTotalRabi += $targetBlockData[$arrColumn['TARGET_DATE']][$k]->RABI;
                                //$subTotalIp += $targetBlockData[$arrColumn['TARGET_DATE']][$k]->IP_TOTAL;
                                $subTotalIp += ($targetBlockData[$arrColumn['TARGET_DATE']][$k]->KHARIF+$targetBlockData[$arrColumn['TARGET_DATE']][$k]->RABI);
                                if($cnt == 1) {
                                    echo '</td></tr>';
                                }
                            }
							if($blockCount>1){
								echo '<tr><td class="ui-widget-content" align="center">Total</td>';
								echo '<td class="ui-widget-content" align="center">'. $subTotalKharif . '</td>
									 <td class="ui-widget-content" align="center">' . $subTotalRabi . '</td>
									 <td class="ui-widget-content" align="center">' . $subTotalIp  . '</td>
								   </tr>';
							}
                        }else{
							if($arrColumn['COL_WIDTH']==6)
	                            echo giveComma($arrColumn['VALUE'], 2);
							else
	                            echo giveComma($arrColumn['VALUE'], 0);
						}
                    }else{
                        echo 'NA';
                    }
                }
                echo '</td>';
            }
            echo '</tr>';

            $grandTotalKharif += $subTotalKharif;
            $grandTotalRabi += $subTotalRabi;
            $grandTotalIp += $subTotalIp;
        }
        ?>
        </tr>
        <tr>
            <td class="ui-state-default" align="center"><strong>Total</strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo giveComma($arrTotal['EXPENDITURE'], 2);?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['LA_NA']==0) ? $arrTotal['LA_NO']:'NA';?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['LA_NA']==0) ? giveComma($arrTotal['LA_HA'], 2):'NA';?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['FA_NA']==0) ? giveComma($arrTotal['FA_HA'], 2):'NA';?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['L_EARTHWORK_NA']==0) ? giveComma($arrTotal['L_EARTHWORK'], 2):'NA';?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['C_MASONRY_NA']==0) ? giveComma($arrTotal['C_MASONRY'], 2):'NA';?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['C_PIPEWORK_NA']==0) ? $arrTotal['C_PIPEWORK']:'NA';?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['C_DRIP_PIPE_NA']==0) ? $arrTotal['C_DRIP_PIPE']:'NA';?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['C_WATERPUMP_NA']==0) ? $arrTotal['C_WATERPUMP']:'NA';?></strong></td>
            <td class="ui-state-default" align="center"><strong><?php echo ($setupData['K_CONTROL_ROOMS_NA']==0) ? $arrTotal['K_CONTROL_ROOMS']:'NA';?></strong></td>
            <td class="ui-state-default" align="center"> </td>
            <td align="center" class="ui-state-default"><strong><?php echo $grandTotalKharif;?></strong></td>
            <td align="center" class="ui-state-default"><strong><?php echo $grandTotalRabi;?></strong></td>
            <td align="center" class="ui-state-default"><strong><?php echo $grandTotalIp;?></strong></td>
        </tr>
		</tbody>
    </table>
<p><small>Printed on <?php echo date("d-m-Y h:i:s a");?></small></p>
</div>
