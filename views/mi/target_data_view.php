<?php //showArrayValues($arrTargetData);//exit;
//showArrayValues($arrTargetBlockData);?>
<div class="panel panel-primary">   
    <!-- Default panel contents -->
    <div class="panel-heading">
        <strong><big><big>Financial and Physical Target Setup </big>( For the FY : <?php echo $session_year; ?> )</big></strong>
    </div>
    <div class="panel-body">
        <form id="frmYearlyTarget" name="frmYearlyTarget" method="post" onsubmit="return false;" autocomplete="off">
            <input type="hidden" name="SESSION" id="SESSION" value="<?php echo $session_id; ?>"/>
            <div style="width:100%;float:left;padding:5px" id="yearlyformdata">
                <table width="100%" border="0" cellpadding="2" cellspacing="1" class="ui-widget-content" style="margin-bottom:5px">
                <tr>
                    <td width="100" class="ui-state-default"><strong>Name of Project</strong></td>
                    <td class="ui-widget-content">
                        <big><strong><?php echo $PROJECT_NAME; ?></strong></big>
                        <input type="hidden" name="PROJECT_SETUP_ID" id="PROJECT_SETUP_ID" value="<?php echo $PROJECT_SETUP_ID ?>"/>
                    </td>
                    <td width="120" class="ui-state-default"><strong>Project Code</strong></td>
                    <td width="120" class="ui-widget-content"><strong><?php echo $PROJECT_CODE; ?></strong></td>
                </tr>
                <tr>
                    <td nowrap="nowrap" class="ui-state-default"><strong>Budget Amount</strong></td>
                    <td class="ui-widget-content">
                        <input type="hidden" name="BUDGET_AMOUNT" value="<?php echo $BUDGET_AMOUNT; ?>" id="BUDGET_AMOUNT"/>
                        <div id="divBudgetAmount"
                             style="float:left;font-size:13px;font-weight:bold;padding-right:3px">
                            <?php echo $BUDGET_AMOUNT; ?>
                        </div>
                        <input type="hidden" name="AA_AMOUNT" id="AA_AMOUNT" value="<?php echo $AA_AMOUNT; ?>"/>
                        <input type="hidden" name="AA_RAA" id="AA_RAA" value="<?php echo $AA_RAA; ?>"/>(Rs.in Lakh)
                    </td>
                    <td class="ui-state-default"><strong><?php echo $AA_RAA; ?> Amount (Rs.) </strong></td>
                    <td class="ui-widget-content" align="right"><strong><?php echo $AA_AMOUNT; ?></strong> (in Lakh)</td>
                </tr>
                </table>
                <!--<input type="hidden" id="startRealStartMonth" name="startRealStartMonth" value="<?php echo $startRealStartMonth; ?>"/>
                <input type="hidden" id="startMonth" name="startMonth" value="<?php echo $startMonth; ?>"/>
                <input type="hidden" id="endMonth" name="endMonth" value="<?php echo $endMonth; ?>"/>
                <input type="hidden" id="startSession" name="startSession" value="<?php echo $startSession; ?>"/>
                <input type="hidden" id="endSession" name="endSession" value="<?php echo $endSession; ?>"/>-->
                <table width="100%" border="1" cellpadding="4" cellspacing="1" class="ui-widget-content" id="tbl_physical_target">
                <thead>
                <tr>
                    <th rowspan="3" class="ui-state-default">Month</th>
                    <th rowspan="3" class="ui-state-default">Financial</th>
                    <th colspan="2" rowspan="3" class="ui-state-default">Land <br/>Acquisition <br/> (cases to be<br/> submitted)</th>
                    <th rowspan="3" class="ui-state-default">Forest<br/>cases<br/>to be<br/>submitted</th>
                    <th rowspan="3" class="ui-state-default">Earthwork <br/> (As per <br/> "L" Earthwork<br/> section of <br/>DPR)</th>
                    <th colspan="4" class="ui-state-default">Masonry/Concrete <br/>(As per "C" Masonry section of DPR)</th>
                    <th rowspan="3" class="ui-state-default">Building Works<br/> ( As per <br/>"K" <br/>Building section<br/> of DPR) <br/>Control Rooms</th>
                    <th colspan="4" class="ui-state-default">Irrigation Potential<br/> to be created</th>
                </tr>
                <tr>
                    <th rowspan="2" class="ui-state-default">Masonry/<br/>concrete</th>
                    <th colspan="2" class="ui-state-default">Pipe Works</th>
                    <th rowspan="2" class="ui-state-default">Water Pumps</th>
                    <th rowspan="2" class="ui-state-default">Block</th>
                    <th rowspan="2" class="ui-state-default">Kharif</th>
                    <th rowspan="2" class="ui-state-default">Rabi</th>
                    <th rowspan="2" class="ui-state-default">Total</th>
                </tr>
                <tr>
                  <th class="ui-state-default"><strong>i. DE/PE/PVC<br />(Main &amp; Submain)</strong></th>
                  <th class="ui-state-default"><strong>ii. Lateral for <br />Drip/sprinkler</strong></th>
                </tr>
                <tr>
                    <th class="ui-state-default">&nbsp;</th>
                    <th class="ui-state-default">Rs. Lacs</th>
                    <th class="ui-state-default">Number</th>
                    <th class="ui-state-default">Hectares</th>
                    <th class="ui-state-default">Hectares</th>
                    <th class="ui-state-default">Th Cum</th>
                    <th class="ui-state-default">Th Cum</th>
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
                    <?php
					$arrIntNames = array('LA_NO', 'C_PIPEWORK', 'C_DRIP_PIPE', 'C_WATERPUMP', 'K_CONTROL_ROOMS');
                    $monthsOfFinyear = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
                    $i = 4;
                    $arrRows = array();
                    $totalTargetThisSession = array(
                        'EXPENDITURE'=>0, 'LA_NO' => 0, 'LA_HA' => 0,
                        'FA_HA' => 0, 'L_EARTHWORK' => 0,
                        'C_MASONRY' => 0, 'C_PIPEWORK' => 0, 'C_DRIP_PIPE' => 0,
                        'C_WATERPUMP' => 0, 'K_CONTROL_ROOMS' => 0
                    ); //'IP_TOTAL' => 0, ,'KHARIF' => 0, 'RABI' => 0
                    $colWidth = 6;
					$aMonthVal=0;
                    //for ($a = 1; $a <= 12; $a++) {
                    //echo 'xxx :';
                    //showArrayValues($arrTargetData);
					//startDate endDate
                    foreach($arrTargetData as $key=>$targetDatum) {
						$dateValue = strtotime($key);
						if($dateValue<$startDate) continue;
						if($dateValue>$endDate) continue;
                        //$startMonth
                        //sum of monthly
                        $time = '';
                        $time = strtotime($arrTargetData[$key]->TARGET_DATE);
                        $a = date("m", strtotime($key));
                        //$aTargetDate = $arrTargetData[$key]->TARGET_DATE;
                        //echo '<br />$aTargetDate= '.$aTargetDate;
                        //echo '<br />$a= '.$a;

                        //$tMonth = (($a>=10)? ($a-9) : ($a+3));
                        //$showHidden = (($aMonthVal < $startMonth) || ($aMonthVal > $endMonth)) ? 'hidden' : 'text';
						
                        $showHidden = 'text';//(($aMonthVal < $startMonth) || ($aMonthVal > $endMonth)) ? 'hidden' : 'text';

                        foreach ($totalTargetThisSession as $k=>$v){
							if(array_key_exists($k, $arrTargetData[$key]))
	                            $totalTargetThisSession[$k] += ((float)$arrTargetData[$key]->{$k});
						}
						//echo '::'.$key.'::';
						//showArrayValues($arrTargetData[$key]);
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
                                'VALUE' => $arrTargetData[$key]->EXPENDITURE,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => 1
                            ),
                            array(
                                'NAME' => 'LA_NO',
                                'VALUE' => $arrTargetData[$key]->LA_NO,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => 1,
                                'SHOW' => (($arrSetupData['LA_NA'] == 0) ? 1 : 0)
                            ),
                            array(
                                'NAME' => 'LA_HA',
                                'VALUE' => $arrTargetData[$key]->LA_HA,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['LA_NA'] == 0) ? 1 : 0)
                            ),
                            array(
                                'NAME' => 'FA_HA',
                                'VALUE' => $arrTargetData[$key]->FA_HA,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['FA_NA'] == 0) ? 1 : 0)
                            ),
                            array(
                                'NAME' => 'L_EARTHWORK',
                                'VALUE' => $arrTargetData[$key]->L_EARTHWORK,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['L_EARTHWORK_NA'] == 0) ? 1 : 0)
                            ),
                           /* array(
                                'NAME' => 'C_EARTHWORK',
                                'VALUE' => $arrTargetData[$key]->C_EARTHWORK,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['C_EARTHWORK_NA'] == 0) ? 1 : 0)
                            ),*/
                            array(
                                'NAME' => 'C_MASONRY',
                                'VALUE' => $arrTargetData[$key]->C_MASONRY,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['C_MASONRY_NA'] == 0) ? 1 : 0)
                            ),
                            array(
                                'NAME' => 'C_PIPEWORK',
                                'VALUE' => $arrTargetData[$key]->C_PIPEWORK,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['C_PIPEWORK_NA'] == 0) ? 1 : 0)
                            ),
                            array(
                                'NAME' => 'C_DRIP_PIPE',
                                'VALUE' => $arrTargetData[$key]->C_DRIP_PIPE,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['C_DRIP_PIPE_NA'] == 0) ? 1 : 0)
                            ),
                            array(
                                'NAME' => 'C_WATERPUMP',
                                'VALUE' => $arrTargetData[$key]->C_WATERPUMP,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => 1,
                                'SHOW' => (($arrSetupData['C_WATERPUMP_NA'] == 0) ? 1 : 0)
                            ),
                            array(
                                'NAME' => 'K_CONTROL_ROOMS',
                                'VALUE' => $arrTargetData[$key]->K_CONTROL_ROOMS,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => 1,
                                'SHOW' => (($arrSetupData['K_CONTROL_ROOMS_NA'] == 0) ? 1 : 0)
                            )/*,
                            array(
                                'NAME' => 'IP_TOTAL',
                                'VALUE' => $arrTargetData[$key]->IP_TOTAL,
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['IP_TOTAL_NA'] == 0) ? 1 : 0),
                                'KHARIF' => $arrTargetData[$key]->KHARIF,
                                'RABI' => $arrTargetData[$key]->RABI
                            )*/
                            ,
                            array(
                                'NAME' => 'IP_TOTAL',
                                'VALUE' => '',
                                'TYPE' => $showHidden,
                                'MONTH' => $a,
                                'COL_WIDTH' => $colWidth,
                                'SHOW' => (($arrSetupData['IP_TOTAL_NA'] == 0) ? 1 : 0),
                                'KHARIF' => '',
                                'RABI' => '',
                                'TARGET_DATE'=>$key
                            )
                        );
                        array_push($arrRows, $arrMonthTarget);
                        if ($i == 12) {
                            $i = 1;
                        } else {
                            $i++;
                        }
                    }//for
					//showArrayValues($arrTargetBlockData);
                    /*echo  'arrRows : ';
                    showArrayValues($arrRows);
                    exit;*/
                    $arrForValidation = array();
                    /*echo "ArrBlocksIps :";
                    showArrayValues($arrBlockIps);
                    exit;*/
                    //showArrayValues($arrTargetBlockData);

                    //echo 'hiiiii === '. $arrTargetBlockData["2017-11-01"][71]->KHARIF;
                    //exit;
                    $subTotalKharif=0;
                    $subTotalRabi=0;
                    $subTotalIp=0;

                    $grandTotalKharif=0;
                    $grandTotalRabi=0;
                    $grandTotalIp=0;
		//showArrayValues($arrRows);
                    foreach($arrRows as $arrRow){
                        echo '<tr>';
                        foreach($arrRow as $arrColumn){
							
                            echo '<td class="ui-widget-content" align="center" rowspan="' . (($arrColumn['NAME'] == 'IP_TOTAL') ? '' : (count($arrBlockIps) + 1)) . '">';
                            if($arrColumn['NAME'] == 'MON'){
                                echo '<strong>' . $arrColumn['VALUE'] . '</strong>';
                                echo '<input name="TARGET_DATE[' . $arrColumn['MONTH'] . ']" value="' . $arrColumn['TARGET_DATE'] . '" type="hidden" /> ';
                            }else{
                                if($arrColumn['SHOW']){ 
                                    //required : true, min:0, number:true},
                                    //array_push($arrForValidation, '"'.$arrColumn['NAME'].'_'.$arrColumn['MONTH'].'":{required : true, number:true, min:0}');
                                    //array_push($arrForValidation, '"'.$arrColumn['NAME']."[]:{required : true, number:true, min:0}');
                                    if (strstr($buttons, 'Project Locked')) {
                                        if ($arrColumn['NAME'] == 'IP_TOTAL') {
                                            echo $arrColumn['KHARIF'] .
                                                '</td>
                                                <td class="ui-widget-content" align="center">qqqq0000000000' .
                                                $arrColumn['RABI'] .
                                                '</td>							
								                    <td class="ui-widget-content" align="center">wwwwww' .
                                                $arrColumn['VALUE'];
                                        } else {
                                            echo 'eeeeeeeeee' . $arrColumn['VALUE'];
                                        }//else
                                    } else {
                                        $subTotalKharif=0;
                                        $subTotalRabi=0;
                                        $subTotalIp=0;
                                        if ($arrColumn['TYPE'] == 'text') {
                                            $arrMonthB = array();
                                            if ($arrColumn['NAME'] == 'IP_TOTAL') {
                                                $cnt = 0;
                                                foreach($arrBlockIps as $k=>$v) {
                                                    array_push($arrMonthB, $k);
                                                    $cnt++;
                                                    if($cnt > 1) {
                                                        echo '<tr><td align="center">'.$v['BLOCK_NAME'].'</td>';
                                                    }else{
                                                        echo $v['BLOCK_NAME'] . '</td>';
                                                    }
													if(!array_key_exists($arrColumn['TARGET_DATE'], $arrTargetBlockData)){
														 echo '<td class="ui-widget-content" align="center">'.$arrColumn['TARGET_DATE'].'</td>
														 	<td class="ui-widget-content" align="center"></td>';
														continue;
													}
                                                    //showArrayValues($arrTargetBlockData);
                                                    echo '<td class="ui-widget-content" align="center">';
                                                    echo '<input name="KHARIF[' . $arrColumn['MONTH'] . ']['.$k.']" 
                                                            id="KHARIF_' . $arrColumn['MONTH'] .$k.'" 
                                                            value="' . $arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]['KHARIF'] . '" type="text" maxlength="10" 
                                                            size="' . $arrColumn['COL_WIDTH'] . '" class="centertext xtext myinteger myytclass KHARIF" required
                                                            autocomplete="off" 
                                                            onkeyup="calculateIrri(\'' . $arrColumn['MONTH'].$k . '\');" />
                                                        </td>
                                                        
                                                        <td class="ui-widget-content" align="center">
                                                            <input name="RABI[' . $arrColumn['MONTH'] . ']['.$k.']"
                                                            id="RABI_' . $arrColumn['MONTH'] .$k. '" 
                                                            value="' . $arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]['RABI'] . '" type="text" maxlength="10" 
                                                            size="' . $arrColumn['COL_WIDTH'] . '" class="centertext xtext myinteger myytclass RABI" required
                                                            autocomplete="off"
                                                            onkeyup="calculateIrri(\'' . $arrColumn['MONTH'] .$k. '\');" />
                                                        </td>                                                       
                                                        
                                                        <td class="ui-state-default" align="center">
                                                            <input name="IP' . '[' . $arrColumn['MONTH'] . ']['.$k.']"
                                                            id="' . $arrColumn['NAME'] . '_' . $arrColumn['MONTH'] .$k. '" 
                                                            value="' . ($arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]['KHARIF'] + $arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]['RABI']) . '" type="text" maxlength="10"  
                                                            size="' . $arrColumn['COL_WIDTH'] . '" class="centertext xtext myytclass in_ip"
                                                            autocomplete="off" 
                                                            required   readonly="readonly" style="background-color:#ECF9FF"/>';

                                                    $subTotalKharif += $arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]['KHARIF'];
                                                    $subTotalRabi += $arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]['RABI'];
                                                    //$subTotalIp += $arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]->IP_TOTAL ;
                                                    $subTotalIp += ($arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]['KHARIF']+ $arrTargetBlockData[$arrColumn['TARGET_DATE']][$k]['RABI']);

                                                    if ($cnt == 1) {
                                                        echo '</td></tr>';
                                                    }
                                                }//foreach
                                                echo '<tr><td class="ui-state-default" align="center">Total</td>';
                                                echo '<td class="ui-state-default" align="center">
                                                          <input type="hidden" id="m_'.$arrColumn['MONTH'].'" value="'.implode(',',$arrMonthB).'" />
                                                          <input name="IP_KHARIF[' . $arrColumn['MONTH'] . ']" 
                                                            id="IP_KHARIF_' . $arrColumn['MONTH'] . '" 
                                                            value="' . $subTotalKharif . '" type="text" maxlength="10" 
                                                            size="' . $arrColumn['COL_WIDTH'] . '" class="centertext xtext myytclass" readonly="readonly" style="background-color:#ECF9FF"/>
                                                        </td>
                                                        <td class="ui-state-default" align="center">
                                                            <input name="IP_RABI[' . $arrColumn['MONTH'] . ']" 
                                                            id="IP_RABI_' . $arrColumn['MONTH'] . '" 
                                                            value="' . $subTotalRabi. '" type="text" maxlength="10" 
                                                            size="' . $arrColumn['COL_WIDTH'] . '" class="centertext xtext myytclass" readonly="readonly"  style="background-color:#ECF9FF"/>
                                                        </td>
                                                        <td class="ui-state-default" align="center">
                                                            <input name="' . $arrColumn['NAME'] . '[' . $arrColumn['MONTH'] . ']" 
                                                            id="' . $arrColumn['NAME'] . '_' . $arrColumn['MONTH'] . '" 
                                                            value="' . $subTotalIp . '" type="text" maxlength="10"  
                                                            size="' . $arrColumn['COL_WIDTH'] . '" class="centertext xtext myytclass" 
                                                            required  readonly="readonly" style="background-color:#ECF9FF"/>
		                                                </td>                                                        
                                                       </tr>';
                                            }else{
                                                echo '<input name="'.$arrColumn['NAME'].'['.$arrColumn['MONTH'].']" 
                                                    id="' . $arrColumn['NAME'] . '_' . $arrColumn['MONTH'] . '" 
                                                    value="' . $arrColumn['VALUE'] . '" type="text" maxlength="10" 
                                                    size="' . $arrColumn['COL_WIDTH'] . '" class="centertext xtext '.$arrColumn['NAME'].'" ' .
                                                           ((in_array($arrColumn['NAME'], $arrIntNames)) ? 'myinteger':'mydecimal').'" 
                                                    onkeyup="calculateSubTotal(\'' . $arrColumn['NAME'] . '\');" />';
											}//else
                                        } else {
                                            if ($arrColumn['NAME'] == 'IP_TOTAL') {
                                                $cnt = 0;
                                                foreach ($arrBlockIps as $k => $v) {
                                                    $cnt++;
                                                    if ($cnt > 1) {
                                                        echo '<tr><td align="center">' . $v['BLOCK_NAME'] . '</td>';
                                                    } else {
                                                        echo $v['BLOCK_NAME'] . '</td>';
                                                    }
                                                    echo '<td class=" class="ui-widget-content" align="center">';
                                                    echo '<input name="KHARIF[' . $arrColumn['MONTH'] . ']['.$k.']" 
                                                        id="KHARIF' . $arrColumn['MONTH'] . '" 
                                                        value="' . $arrColumn['KHARIF'] . '" type="hidden" />
                                                        ' . $arrColumn['KHARIF'] . '
                                                        </td>
                                                        <td class="ui-widget-content" align="center">
                                                        <input name="RABI[' . $arrColumn['MONTH'] . ']['.$k.']" 
                                                        id="RABI' . $arrColumn['MONTH'] . '" 
                                                        value="' . $arrColumn['RABI'] . '" type="hidden" />
                                                        ' . $arrColumn['RABI'] . '
                                                        </td>
                                                        <td class="ui-widget-content" align="center"> 
                                                            <input name="IP'. '[' . $arrColumn['MONTH'] . ']['.$k.']" 
                                                            id="' . $arrColumn['NAME'] . '_' . $arrColumn['MONTH'] . '" 
                                                            value="' . $arrColumn['VALUE'] . '" type="hidden" />
                                                            ' . $arrColumn['VALUE'] . '
                                                        ';
                                                    if ($cnt == 1) {
                                                        echo '</td></tr>';
                                                    }
                                                }//foreach
                                                echo '<tr>
                                                    <td class="ui-state-default" align="center">Total</td>                                                    
                                                    <td class="ui-state-default" align="center"><input name="IP_KHARIF[' . $arrColumn['MONTH'] . ']" 
                                                        id="IP_KHARIF' . $arrColumn['MONTH'] . '" 
                                                        value="' . $arrColumn['KHARIF'] . '" type="hidden" />
                                                        ' . $arrColumn['KHARIF'] . '
                                                     </td>
                                                     <td class="ui-state-default" align="center">
                                                        <input name="IP_RABI[' . $arrColumn['MONTH'] . ']" 
                                                        id="RABI' . $arrColumn['MONTH'] . '" 
                                                        value="' . $arrColumn['RABI'] . '" type="hidden" />
                                                        ' . $arrColumn['RABI'] . '
                                                     </td>
                                                     <td class="ui-state-default" align="center"> 
                                                            <input name="' . $arrColumn['NAME'] . '[' . $arrColumn['MONTH'] . ']" 
                                                            id="' . $arrColumn['NAME'] . '_' . $arrColumn['MONTH'] . '" 
                                                            value="' . $arrColumn['VALUE'] . '" type="hidden" />
                                                            ' . $arrColumn['VALUE'] . '
                                                     </td>
                                                   </tr>';
                                            } else {
                                                echo ' <input name="' . $arrColumn['NAME'] . '[' . $arrColumn['MONTH'] . ']" 
                                                id="' . $arrColumn['NAME'] . '_' . $arrColumn['MONTH'] . '" 
                                                value="' . $arrColumn['VALUE'] . '" type="hidden" />' . $arrColumn['VALUE'];
                                            }//else
                                        }//else
                                    }//else
                                }//if
                            }//if
                            echo '</td>';
                        }//foreach
                        echo '</tr>';
                        $grandTotalKharif += $subTotalKharif;
                        $grandTotalRabi += $subTotalRabi;
                        $grandTotalIp += $subTotalIp;
                    }//foreach ?>
                    </tr>
                    <tr>
                  <td align="center" class="ui-state-default">Total</td>
                  <td align="center" class="ui-state-default" id="EXPENDITURE_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="LA_NO_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="LA_HA_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="FA_HA_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="L_EARTHWORK_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="C_MASONRY_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="C_PIPEWORK_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="C_DRIP_PIPE_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="C_WATERPUMP_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="K_CONTROL_ROOMS_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" >&nbsp;</td>
                  <td align="center" class="ui-state-default" id="KHARIF_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="RABI_T">&nbsp;</td>
                  <td align="center" class="ui-state-default" id="TOTAL_T">&nbsp;</td>
                </tr>
                    <tr>
                      <td align="center" class="ui-widget-content">Estimated</td>
                      <td align="center" class="ui-widget-content">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="LA_NO_ESTI"><?php echo ($arrSetupData['LA_NA'])?'':$arrEstimation['LA_NO'];?></td>
                      <td align="center" class="ui-widget-content" id="LA_HA_ESTI"><?php echo ($arrSetupData['LA_NA'])?'':$arrEstimation['LA_HA'];?></td>
                      <td align="center" class="ui-widget-content" id="FA_HA_ESTI"><?php echo ($arrSetupData['FA_NA'])?'':$arrEstimation['FA_HA'];?></td>
                      <td align="center" class="ui-widget-content" id="L_EARTHWORK_ESTI"><?php echo ($arrSetupData['L_EARTHWORK_NA'])?'':$arrEstimation['L_EARTHWORK'];?></td>
                      <td align="center" class="ui-widget-content" id="C_MASONRY_ESTI"><?php echo ($arrSetupData['C_MASONRY_NA'])?'':$arrEstimation['C_MASONRY'];?></td>
                      <td align="center" class="ui-widget-content" id="C_PIPEWORK_ESTI"><?php echo ($arrSetupData['C_PIPEWORK_NA'])?'':$arrEstimation['C_PIPEWORK'];?></td>
                      <td align="center" class="ui-widget-content" id="C_DRIP_PIPE_ESTI"><?php echo ($arrSetupData['C_DRIP_PIPE_NA'])?'':$arrEstimation['C_DRIP_PIPE'];?></td>
                      <td align="center" class="ui-widget-content" id="C_WATERPUMP_ESTI"><?php echo ($arrSetupData['C_WATERPUMP_NA'])?'':$arrEstimation['C_WATERPUMP'];?></td>
                      <td align="center" class="ui-widget-content" id="K_CONTROL_ROOMS_ESTI"><?php echo ($arrSetupData['K_CONTROL_ROOMS_NA'])?'':$arrEstimation['K_CONTROL_ROOMS'];?></td>
                      <td align="center" class="ui-widget-content" >&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="KHARIF_ESTI"><?php echo $arrEstimation['KHARIF'];?></td>
                      <td align="center" class="ui-widget-content" id="RABI_ESTI"><?php echo $arrEstimation['RABI'];?></td>
                      <td align="center" class="ui-widget-content" id="TOTAL_ESTI"><?php echo (intval($arrEstimation['KHARIF'])+intval($arrEstimation['RABI']));?></td>
                  <tr>
	                  <td align="center" class="ui-state-default">Achievement<br />Till Last Year</td>  
                      <td align="center" class="ui-widget-content">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="LA_NO_ACHIEVE"><?php echo ($arrSetupData['LA_NA'])?'':$arrAchievement['LA_NO'];?></td>
                      <td align="center" class="ui-widget-content" id="LA_HA_ACHIEVE"><?php echo ($arrSetupData['LA_NA'])?'':$arrAchievement['LA_HA'];?></td>
                      <td align="center" class="ui-widget-content" id="FA_HA_ACHIEVE"><?php echo ($arrSetupData['FA_NA'])?'':$arrAchievement['FA_HA'];?></td>
                      <td align="center" class="ui-widget-content" id="L_EARTHWORK_ACHIEVE"><?php echo ($arrSetupData['L_EARTHWORK_NA'])?'':$arrAchievement['L_EARTHWORK'];?></td>
                      <td align="center" class="ui-widget-content" id="C_MASONRY_ACHIEVE"><?php echo ($arrSetupData['C_MASONRY_NA'])?'':$arrAchievement['C_MASONRY'];?></td>
                      <td align="center" class="ui-widget-content" id="C_PIPEWORK_ACHIEVE"><?php echo ($arrSetupData['C_PIPEWORK_NA'])?'':$arrAchievement['C_PIPEWORK'];?></td>
                      <td align="center" class="ui-widget-content" id="C_DRIP_PIPE_ACHIEVE"><?php echo ($arrSetupData['C_DRIP_PIPE_NA'])?'':$arrAchievement['C_DRIP_PIPE'];?></td>
                      <td align="center" class="ui-widget-content" id="C_WATERPUMP_ACHIEVE"><?php echo ($arrSetupData['C_WATERPUMP_NA'])?'':$arrAchievement['C_WATERPUMP'];?></td>
                      <td align="center" class="ui-widget-content" id="K_CONTROL_ROOMS_ACHIEVE"><?php echo ($arrSetupData['K_CONTROL_ROOMS_NA'])?'':$arrAchievement['K_CONTROL_ROOMS'];?></td>
                      <td align="center" class="ui-widget-content" >&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="KHARIF_ACHIEVE"><?php echo $arrAchievement['KHARIF'];?></td>
                      <td align="center" class="ui-widget-content" id="RABI_ACHIEVE"><?php echo $arrAchievement['RABI'];?></td>
                      <td align="center" class="ui-widget-content" id="TOTAL_ACHIEVE"><?php echo (intval($arrAchievement['KHARIF'])+intval($arrAchievement['RABI']));?></td>
                    </tr>
                    <tr>
                      <td align="center" class="ui-state-default">Diff</td>
                      <td align="center" class="ui-widget-content">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="LA_NO_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="LA_HA_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="FA_HA_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="L_EARTHWORK_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="C_MASONRY_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="C_PIPEWORK_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="C_DRIP_PIPE_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="C_WATERPUMP_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="K_CONTROL_ROOMS_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" >&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="KHARIF_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="RABI_DIFF">&nbsp;</td>
                      <td align="center" class="ui-widget-content" id="TOTAL_DIFF">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="15" align="left" class="ui-widget-content">
                            <?php if (!strstr($buttons, 'Project Locked'))
                                //echo getButton('Fill Empty Box with Zero', 'fillZero()', 4, 'icon-repeat');
                                echo getButton(array('caption'=>'Fill Empty Box with Zero', 'event'=>'fillZero()', 'icon'=>'icon-repeat', 'title'=>'Fill Empty Box with Zero'))
                            ?>
                        </td>
						<?php /*?><td colspan="9" align="center" class="ui-widget-content">
							<big><strong>Date of submission of above Data : 
							<?php if(!strstr($buttons, 'Project Locked')){?>
							<input type="text" name="SUBMISSION_DATE" id="SUBMISSION_DATE"
								readonly="readonly" value="<?php echo myDateFormat($SUBMISSION_DATE);?>" size="14"
								 class="dp1" style="text-align:center" /> 
							<?php }else{
								echo '<span class="badge badge-info">'.
									myDateFormat($SUBMISSION_DATE).
									'</span>';
								}?>
							</strong></big>
                			</td><?php */ ?>
                    </tr>
                </table>
            </div>
            <div id="mySaveDiv" align="right" class="mysavebar">
                <?php echo $buttons;
                echo getButton('Clear Error', 'clearTargetFormError();', 4, 'cus-bin');
                //echo getButton(array('caption'=>'Clear Error', 'event'=>'clearTargetFormError()', 'icon'=>'cus-bin', 'title'=>'Clear Error'))
                ?>
            </div>
        </form>
    </div>
<script language="javascript" type="text/javascript">
var validator;
$().ready(function () {
	$('.mydecimal').numeric({
		allowPlus           : false, // Allow the + sign
		allowMinus          : false,  // Allow the - sign
		allowThouSep        : false,  // Allow the thousands separator, default is the comma eg 12,000
		allowDecSep         : true,  // Allow the decimal separator, default is the fullstop eg 3.141
		allowLeadingSpaces  : false,
		maxDigits           : 15,   // The max number of digits
		maxDecimalPlaces    : 2,   // The max number of decimal places
		maxPreDecimalPlaces : 12,   // The max number digits before the decimal point
		max                 : NaN,   // The max numeric value allowed
		min                 : 0 // The min numeric value allowed
	});
	$('.myinteger').numeric({
		allowPlus           : false, // Allow the + sign
		allowMinus          : false,  // Allow the - sign
		allowThouSep        : false,  // Allow the thousands separator, default is the comma eg 12,000
		allowDecSep         : false,  // Allow the decimal separator, default is the fullstop eg 3.141
		allowLeadingSpaces  : false,
		maxDigits           : 5,   // The max number of digits
		maxDecimalPlaces    : 2,   // The max number of decimal places
		maxPreDecimalPlaces : 11,   // The max number digits before the decimal point
		max                 : 99999,   // The max numeric value allowed
		min                 : 0 // The min numeric value allowed
	});
	$('.dp1').css("text-align", "center");
	window.validator = $("#frmYearlyTarget").validate({
		rules: {
			/*"BUDGET_AMOUNT" : {required : true, min:0, number:true},*/
			"EXPENDITURE[]": {required: true, min: 0, number: true}/*,
			"SUBMISSION_DATE":{required : true}*/
			<?php if (count($arrForValidation) > 0) {
				echo ',' . implode(',', $arrForValidation);
			}?>
		},
		messages: {
			/*"BUDGET_AMOUNT" : {required : "Required - Budget Amount", min:"Required Positive Amount"}*/
		}
	});
	// the following method must come AFTER .validate()
	//calculateSubTotal('EXPENDITURE');
	calculateTotals();
});
//
var v = 0;
function calculateTotals(){
	var arrVFields = new Array('EXPENDITURE', '<?php echo implode("','", $arrValidFields);?>');
	for(i=0;i<arrVFields.length;i++){
		console.log(arrVFields[i]);
		calculateSubTotal(arrVFields[i], 0);
	}
}
function calculateSubTotal(ids, no) {
	var arrIntFields = new Array('<?php echo implode("','", $arrIntNames);?>','KHARIF', 'RABI');
	var arrIp = new Array('KHARIF', 'RABI');
	var sum = totalKharif = totalRabi = 0;
	$('.'+ids).each(function (index, value){
		sum += checkNo($(this).val());
		console.log('ctrl:' + this.id + ' s:'+ sum);
		if(ids=='KHARIF'){
			ctrl = this.id.replace('KHARIF', 'RABI');//KHARIF_0865
			totalctrl = this.id.replace('KHARIF', 'IP_TOTAL');//IP_TOTAL_0465
			kval = checkNo(this.value);
			rval = checkNo($('#'+ctrl).val());
			totalKharif += kval;
			totalRabi += rval;
			//ctrlVal = kval + rval ;
			$('#'+totalctrl).val(kval + rval);
		}else if(ids=='RABI'){
			ctrl = this.id.replace('RABI', 'KHARIF');//KHARIF_0865
			totalctrl = this.id.replace('RABI', 'IP_TOTAL');//IP_TOTAL_0465
			rval = checkNo(this.value);
			kval = checkNo($('#'+ctrl).val());
			totalKharif += kval;
			totalRabi += rval;
			//ctrlVal = kval + rval ;
			$('#'+totalctrl).val(kval + rval);
		}
	});
	if($.inArray(ids, arrIntFields)) {
		console.log('T ctrl:' + '#' + ids + '_T' + ' s:'+ sum);
		//$('#' + ids + '_T').val(sum);
		$('#' + ids + '_T').html(sum);
		if(ids=='KHARIF' || ids=='RABI'){
			$('#TOTAL_T').html(totalKharif+totalRabi);
		}
	}else{
		console.log('T ctrl:' + '#' + ids + '_T' + ' s:'+ sum);
		//$('#' + ids + '_T').val(sum.toFixed(3));
		$('#' + ids + '_T').html(sum.toFixed(2));
	}
}

function getExpenditure(fin_tot) {
	$('#divBudgetAmount').html(fin_tot.toFixed(2));
	$('#BUDGET_AMOUNT').val(fin_tot.toFixed(2));
}

//
function getFloatValue(vv) {
	var n = parseFloat(vv);
	return ((isNaN(n)) ? 0 : n);
}

//
function calculate(ids) {
	var curMonthVal = $('#' + ids).val();
	var prevMonth = $('#' + ids + "_P_H").val();

	if (prevMonth == '') prevMonth = 0;
	if (curMonthVal == '') curMonthVal = 0;
	var sum;
	//if(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(curMonthVal)){
	//sum = parseFloat(curMonthVal) + parseFloat(sum);
	//sum = parseFloat(prevMonth) + parseFloat(curMonthVal);
	//$("#"+ids+"_P").val(sum);
	//}
	sum = 0;
	var curFinancialYear = $('#' + ids + '_CFY_H').val();
	if (curFinancialYear == "") curFinancialYear = 0;
	sum = parseFloat(curFinancialYear) + parseFloat(curMonthVal);
	$('#' + ids + '_CFY').val(sum);
}

//
function saveTarget() {
	var myValidation = $("#frmYearlyTarget").valid();
	if (!myValidation) {
		//alert('You have : ' + window.validator.numberOfInvalids() + ' errors in this form.');
		showAlert('Oops...','You have : ' + ( window.validator.numberOfInvalids()) + ' errors in this form.', 'error');
		return;
	}
	if (myValidation) {
		var params = {
			'divid': 'mySaveDiv',
			'url': 'saveTargetMi',
			'data': $('#frmYearlyTarget').serialize(),
			'donefname': 'doneSaving',
			'failfname': 'failSaving',
			'alwaysfname': 'none'
		};
		callMyAjax(params);
	} else {
		showMyAlert('Error...', 'There is/are some Required Data... <br />Please Check & Complete it.', 'warn');
	}
}
//
function doneSaving(response) {
	$('#message').html(parseAndShowMyResponse(response));
	<?php 
	if ($entrymode == 'monthly') {
		echo 'closeDialog("modalBox");';
	}else{
		echo "$('#divTargetForm').html('');\ngridReload();";
	}?>
}
//
function failSaving(response) {
	$('#message').html(parseAndShowMyResponse(response));
}
//
function checkAAAmount() {
	if ($('#BUDGET_AMOUNT').val() > 0) {
		var BUDGET_AMOUNT = parseFloat($('#BUDGET_AMOUNT').val());
		var AA_AMOUNT = parseFloat($('#AA_AMOUNT').val());
		if ((BUDGET_AMOUNT > AA_AMOUNT)) {
			alert('Budget Amount is more than ' + $('#AA_RAA').val() + ' Amount :' + $('#AA_AMOUNT').val());
			$('#BUDGET_AMOUNT').val('');
		}
	}
}
//
function fillZero() {
	$('.xtext').each(function () {
		if (this.value == '') {
			this.value = 0;
		}
	});
}

function calculateIrri(mmonth) {
	var ik = 'KHARIF_' + mmonth;
	var ir = 'RABI_' + mmonth;
	var it = 'IP_TOTAL_' + mmonth;

	var m = mmonth.substring(0, 2);
	var strid = $('#m_'+m).val();
	var ids = strid.split(',');

	var tk=0, tr=0, tt=0;
	for(i=0;i<ids.length;i++){
		//console.log('>> '+i);
		kh = checkNo($('#KHARIF_'+m+ids[i]).val());
		rab = checkNo($('#RABI_'+m+ids[i]).val());
		tot = kh + rab;

		tk += kh;
		tr += rab;
		tt += tot;
	}
	$('#IP_KHARIF_'+m).val(tk);
	$('#IP_RABI_'+m).val(tr);
	$('#IP_TOTAL_'+m).val(tt);

	//return;

	var kt = checkNo($('#' + ik).val());
	var rt = checkNo($('#' + ir).val());
	var tt = kt + rt
	$('#' + it).val(roundNumber(tt, 5));
	calculateSubTotal('KHARIF');
	calculateSubTotal('RABI');
	calculateSubTotal('IP_TOTAL');


	var block_kharif_total =0;
	var block_rabi_total =0;
	var block_total_total=0;
	$(".in_kharif").each(function() {
		block_kharif_total+= checkNo($(this).val());
	});

	$(".in_rabi").each(function() {
		block_rabi_total+= checkNo($(this).val());
	});

	$(".in_ip").each(function() {
		block_total_total+= checkNo($(this).val());
	});

	$('#KHARIF_TOTAL').val(block_kharif_total);
	$('#RABI_TOTAL').val(block_rabi_total);
	$('#IP_TOTAL_TOTAL').val(block_total_total);
}

function clearTargetFormError() {
	$("#frmYearlyTarget").validate().resetForm();
}

makeReadable($('#tbl_physical_target'));
</script>