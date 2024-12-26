<div class="panel panel-primary">
<!-- Default panel contents -->
<div class="panel-heading">
   <strong><big><big>Financial and Physical Target Setup </big>
    ( For the FY : <?php echo $session_year;?> )</big></strong>
</div>
<div class="panel-body">
<form id="frmYearlyTarget" name="frmYearlyTarget" method="post" onsubmit="return false;" >
<input type="hidden" name="SESSION" id="SESSION" value="<?php echo $session_id;?>" />
<div style="width:99%;float:left;padding:5px" id="yearlyformdata">
    <table width="100%" border="0" cellpadding="2" cellspacing="1" class="ui-widget-content" style="margin-bottom:5px">
    <tr>
        <td width="100" class="ui-state-default"><strong>Name of Project</strong></td>
        <td class="ui-widget-content">
            <big><strong><?php echo $PROJECT_NAME;?></strong></big>
            <input type="hidden" name="PROJECT_ID" id="PROJECT_ID" value="<?php echo $PROJECT_ID?>" />
        </td>
        <td width="120" class="ui-state-default"><strong>Project Code</strong></td>
        <td width="120" class="ui-widget-content"><strong><?php echo $PROJECT_CODE;?></strong></td>
    </tr>
    <tr>
        <td nowrap="nowrap" class="ui-state-default"><strong>Budget Amount</strong></td>
        <td class="ui-widget-content">
            
            <input type="hidden" name="BUDGET_AMOUNT" value="<?php echo $BUDGET_AMOUNT;?>"
                id="BUDGET_AMOUNT" />
          <div id="divBudgetAmount" style="float:left;font-size:13px;font-weight:bold;padding-right:3px">
                <?php echo $BUDGET_AMOUNT;?> 
            </div>
          <input type="hidden" name="AA_AMOUNT" id="AA_AMOUNT" value="<?php echo $AA_AMOUNT;?>" />
            <input type="hidden" name="AA_RAA" id="AA_RAA" value="<?php echo $AA_RAA;?>" />
             (Rs.in Lakh)
      </td>
         <td class="ui-state-default"><strong><?php echo $AA_RAA;?> Amount (Rs.) </strong></td>
        <td class="ui-widget-content" align="right">
        <strong><?php echo $AA_AMOUNT;?></strong> (in Lakh)
        </td>
    </tr>
    </table>
    <input type="hidden" id="startRealStartMonth" name="startRealStartMonth" value="<?php echo $startRealStartMonth;?>" />
    <input type="hidden" id="startMonth" name="startMonth" value="<?php echo $startMonth;?>" />
    <input type="hidden" id="endMonth" name="endMonth" value="<?php echo $endMonth;?>" />
    <input type="hidden" id="startSession" name="startSession" value="<?php echo $startSession;?>" />
    <input type="hidden" id="endSession" name="endSession" value="<?php echo $endSession;?>" />
    
    <table width="100%" border="0" cellpadding="4" cellspacing="1" class="ui-widget-content">          
    <tr>
        <th rowspan="3" class="ui-state-default">Month</th>
        <th rowspan="3" class="ui-state-default">Financial</th>
        <th colspan="2" rowspan="3" class="ui-state-default">Land Acquisition <br /> (cases to be submitted)</th>
        <th rowspan="3" class="ui-state-default">Forest<br />cases<br />to be<br />submitted </th>
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
        <th class="ui-state-default">Steel <br />Works</th>
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

	$monthsOfFinyear = array(1=>'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
	$i = 4;
	$arrRows = array();
	$totalTargetThisSession = array(
		'EXPENDITURE'=>0, 'LA_NO'=>0, 'LA_HA'=>0, 'FA_HA'=>0, 
		'HEAD_WORKS_EARTHWORK'=>0, 'HEAD_WORKS_MASONRY'=>0, 'STEEL_WORKS'=>0, 
		'CANAL_EARTHWORK'=>0, 'CANAL_LINING'=>0, 'CANAL_STRUCTURES'=>0, 
		'IRRIGATION_POTENTIAL_KHARIF'=>0, 'IRRIGATION_POTENTIAL_RABI'=>0,
		'IRRIGATION_POTENTIAL'=>0
	);
	$colWidth = 6;
    if($session_id==34) $startMonth=10;
	for($a=1;$a<=12;$a++){
		//$startMonth
		//sum of monthly
		$showHidden = (($a<$startMonth) || ($a>$endMonth)) ? 'hidden':'text';
		foreach($totalTargetThisSession as $k=>$v)
			$totalTargetThisSession[$k] += ((float) $targetData[$a]->{$k});
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
				'VALUE'=> $targetData[$a]->EXPENDITURE,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=>1
			),
			array(
				'NAME'=>'LA_NO',
				'VALUE'=> $targetData[$a]->LA_NO,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>1,
				'SHOW'=> ( ($setupData['LA_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'LA_HA',
				'VALUE'=> $targetData[$a]->LA_HA,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=> ( ($setupData['LA_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'FA_HA',
				'VALUE'=> $targetData[$a]->FA_HA,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=> ( ($setupData['FA_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'HEAD_WORKS_EARTHWORK',
				'VALUE'=> $targetData[$a]->HEAD_WORKS_EARTHWORK,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=> ( ($setupData['HEAD_WORKS_EARTHWORK_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'HEAD_WORKS_MASONRY',
				'VALUE'=> $targetData[$a]->HEAD_WORKS_MASONRY,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=> ( ($setupData['HEAD_WORKS_MASONRY_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'STEEL_WORKS',
				'VALUE'=> $targetData[$a]->STEEL_WORKS,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=> ( ($setupData['STEEL_WORKS_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'CANAL_EARTHWORK',
				'VALUE'=> $targetData[$a]->CANAL_EARTHWORK,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=> ( ($setupData['CANAL_EARTHWORK_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'CANAL_LINING',
				'VALUE'=> $targetData[$a]->CANAL_LINING,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=> ( ($setupData['CANAL_LINING_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'CANAL_STRUCTURES',
				'VALUE'=> $targetData[$a]->CANAL_STRUCTURES,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>1,
				'SHOW'=> ( ($setupData['CANAL_STRUCTURES_NA']==0)? 1:0)
			),
			array(
				'NAME'=>'IRRIGATION_POTENTIAL',
				'VALUE'=> $targetData[$a]->IRRIGATION_POTENTIAL,
				'TYPE'=>$showHidden,
				'MONTH' =>$a,
				'COL_WIDTH' =>$colWidth,
				'SHOW'=> ( ($setupData['IRRIGATION_POTENTIAL_NA']==0)? 1:0),
				'KHARIF'=> $targetData[$a]->IRRIGATION_POTENTIAL_KHARIF,
				'RABI'=> $targetData[$a]->IRRIGATION_POTENTIAL_RABI
			)
		);
		array_push($arrRows, $arrMonthTarget);
		if ($i==12){$i=1;} else{$i++;}
	}//for
	//showArrayValues($arrRows);
	$arrForValidation = array(); 
	foreach($arrRows as $arrRow){
		echo '<tr>';
		foreach($arrRow as $arrColumn){
			echo '<td class="ui-widget-content" align="center">';
			if( $arrColumn['NAME']=='MON' ){
				echo '<strong>'.$arrColumn['VALUE'].'</strong>';
			}else{
				if( $arrColumn['SHOW'] ){
					//required : true, min:0, number:true},
					//array_push($arrForValidation, '"'.$arrColumn['NAME'].'_'.$arrColumn['MONTH'].'":{required : true, number:true, min:0}');
					//array_push($arrForValidation, '"'.$arrColumn['NAME']."[]:{required : true, number:true, min:0}');
					if(strstr($buttons, 'Project Locked')){
						if($arrColumn['NAME']=='IRRIGATION_POTENTIAL'){
							echo $arrColumn['KHARIF'].
								'</td>								
								<td class="ui-widget-content" align="center">'.
								$arrColumn['RABI'].
								'</td>							
								<td class="ui-widget-content" align="center">'.
								$arrColumn['VALUE'];
						}else{
							echo $arrColumn['VALUE'];
						}
					}else{
						if($arrColumn['TYPE']=='text'){
							if($arrColumn['NAME']=='IRRIGATION_POTENTIAL'){
								echo '<input name="IRRIGATION_POTENTIAL_KHARIF['.$arrColumn['MONTH'].']" 
								id="IRRIGATION_POTENTIAL_KHARIF_'.$arrColumn['MONTH'].'" 
								value="'.$arrColumn['KHARIF'].'" type="text" maxlength="10" 
								size="'.$arrColumn['COL_WIDTH'].'" class="righttext xtext myytclass" required
								onkeyup="calculateIrri('.$arrColumn['MONTH'].');" />
								</td>
								
								<td class="ui-widget-content" align="center">
								<input name="IRRIGATION_POTENTIAL_RABI['.$arrColumn['MONTH'].']" 
								id="IRRIGATION_POTENTIAL_RABI_'.$arrColumn['MONTH'].'" 
								value="'.$arrColumn['RABI'].'" type="text" maxlength="10" 
								size="'.$arrColumn['COL_WIDTH'].'" class="righttext xtext myytclass" required
								onkeyup="calculateIrri('.$arrColumn['MONTH'].');" />
								</td>
								
								<td class="ui-widget-content" align="center">
								<input name="'.$arrColumn['NAME'].'['.$arrColumn['MONTH'].']" 
								id="'.$arrColumn['NAME'].'_'.$arrColumn['MONTH'].'" 
								value="'.$arrColumn['VALUE'].'" type="text" maxlength="10" readonly="readonly" 
								size="'.$arrColumn['COL_WIDTH'].'" class="righttext xtext myytclass" 
								required />';
							}else{
								echo '<input name="'.$arrColumn['NAME'].'['.$arrColumn['MONTH'].']" 
								id="'.$arrColumn['NAME'].'_'.$arrColumn['MONTH'].'" 
								value="'.$arrColumn['VALUE'].'" type="text" maxlength="10" 
								size="'.$arrColumn['COL_WIDTH'].'" class="righttext xtext '.
								( ($arrColumn['NAME']=='LA_NO' || $arrColumn['NAME']=='CANAL_STRUCTURES') ? 
									'myytclassInt':'myytclass').'" required
								onkeyup="calculateSubTotal(\''.$arrColumn['NAME'].'\');" />';
							}
						}else{
							if($arrColumn['NAME']=='IRRIGATION_POTENTIAL'){
								echo '<input name="IRRIGATION_POTENTIAL_KHARIF['.$arrColumn['MONTH'].']" 
								id="IRRIGATION_POTENTIAL_KHARIF'.$arrColumn['MONTH'].'" 
								value="'.$arrColumn['KHARIF'].'" type="hidden" />
								'.$arrColumn['KHARIF'].'
								</td>
								<td class="ui-widget-content" align="center">
								<input name="IRRIGATION_POTENTIAL_RABI['.$arrColumn['MONTH'].']" 
								id="IRRIGATION_POTENTIAL_RABI'.$arrColumn['MONTH'].'" 
								value="'.$arrColumn['RABI'].'" type="hidden" />
								'.$arrColumn['RABI'].'
								</td>
								<td class="ui-widget-content" align="center">
									<input name="'.$arrColumn['NAME'].'['.$arrColumn['MONTH'].']" 
									id="'.$arrColumn['NAME'].'_'.$arrColumn['MONTH'].'" 
									value="'.$arrColumn['VALUE'].'" type="hidden" />
									'.$arrColumn['VALUE'].'
								';
							}else{
								echo '<input name="'.$arrColumn['NAME'].'['.$arrColumn['MONTH'].']" 
								id="'.$arrColumn['NAME'].'_'.$arrColumn['MONTH'].'" 
								value="'.$arrColumn['VALUE'].'" type="hidden" />'.$arrColumn['VALUE'];
							}
							/*if($arrColumn['MONTH']<$startRealStartMonth){
								//echo ' - ';
								echo $arrColumn['VALUE'];
							}else
								echo $arrColumn['VALUE'];*/
						}
					}
				}
			}
			echo '</td>';
		}
		echo '</tr>';
	}
?>
    </tr>
    <tr>
    <?php 
$arrRow = array(
	array(
		'NAME'=>'TOTAL',
		'VALUE'=>'Total',
		'COL_WIDTH' =>0,
		'SHOW'=>1
	),
	array(
		'NAME'=>'EXPENDITURE',
		'VALUE'=> giveComma($totalTargetThisSession['EXPENDITURE'], 2),
		'COL_WIDTH' =>$colWidth,
		'SHOW'=>1
	),
	array(
		'NAME'=>'LA_NO',
		'VALUE'=> $totalTargetThisSession['LA_NO'],
		'COL_WIDTH' =>1,
		'SHOW'=> ( ($setupData['LA_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'LA_HA',
		'VALUE'=> $totalTargetThisSession['LA_HA'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['LA_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'FA_HA',
		'VALUE'=> $totalTargetThisSession['FA_HA'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['FA_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'HEAD_WORKS_EARTHWORK',
		'VALUE'=> $totalTargetThisSession['HEAD_WORKS_EARTHWORK'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['HEAD_WORKS_EARTHWORK_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'HEAD_WORKS_MASONRY',
		'VALUE'=> $totalTargetThisSession['HEAD_WORKS_MASONRY'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['HEAD_WORKS_MASONRY_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'STEEL_WORKS',
		'VALUE'=> $totalTargetThisSession['STEEL_WORKS'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['STEEL_WORKS_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'CANAL_EARTHWORK',
		'VALUE'=> $totalTargetThisSession['CANAL_EARTHWORK'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['CANAL_EARTHWORK_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'CANAL_LINING',
		'VALUE'=> $totalTargetThisSession['CANAL_LINING'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['CANAL_LINING_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'CANAL_STRUCTURES',
		'VALUE'=> $totalTargetThisSession['CANAL_STRUCTURES'],
		'COL_WIDTH' =>1,
		'SHOW'=> ( ($setupData['CANAL_STRUCTURES_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'IRRIGATION_POTENTIAL_KHARIF',
		'VALUE'=> $totalTargetThisSession['IRRIGATION_POTENTIAL_KHARIF'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['IRRIGATION_POTENTIAL_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'IRRIGATION_POTENTIAL_RABI',
		'VALUE'=> $totalTargetThisSession['IRRIGATION_POTENTIAL_RABI'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['IRRIGATION_POTENTIAL_NA']==0)? 1:0)
	),
	array(
		'NAME'=>'IRRIGATION_POTENTIAL',
		'VALUE'=> $totalTargetThisSession['IRRIGATION_POTENTIAL'],
		'COL_WIDTH' =>$colWidth,
		'SHOW'=> ( ($setupData['IRRIGATION_POTENTIAL_NA']==0)? 1:0)
	)
);
foreach($arrRow as $arrColumn){
	echo ' <td class="ui-state-default" align="center">';
	if( $arrColumn['NAME']=='TOTAL' ){
		echo $arrColumn['VALUE'];
	}else{
		if( $arrColumn['SHOW'] ){
			//array_push($arrForValidation, '"'.$arrColumn['NAME'].'":{required : true, number:true, min:0}');
			if(strstr($buttons, 'Project Locked')){
				echo '<big>'.$arrColumn['VALUE'].'</big>';
			}else{
				echo '<input name="'.$arrColumn['NAME'].'_TOTAL" 
				id="'.$arrColumn['NAME'].'_TOTAL" readonly="readonly" class="righttext xtext"
				value="'.$arrColumn['VALUE'].'" type="text" maxlength="10" 
				size="'.$arrColumn['COL_WIDTH'].'" />';
			}
		}
	}
	echo '</td>';
}
?>
    </tr>
    <tr>
      <td colspan="14" align="left" class="ui-widget-content">
        <?php if(!strstr($buttons, 'Project Locked'))
				echo getButton('Fill Empty Box with Zero', 'fillZero()', 4, 'icon-repeat');
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
        </td><?php */?>
    </tr>
    </table>
</div>
<div id="mySaveDiv" align="right" class="mysavebar">
<?php echo $buttons;?>
</div>
</form>
</div>
<script language="javascript" type="text/javascript">
var validator;
$().ready(function(){
	//$('.date_picker').datepicker({dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true});
	/*$('#SUBMISSION_DATE').datepicker({
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true,
        beforeShow: function(){
		<?php
			$arrYear = explode('-', $session_year);
			//showArrayValues($arrYear );
			$dd = $arrYear[0]."-04-01";
			$arrDate1 = date("d-m-Y", strtotime($dd));
			//$arrDate2 = date("d-m-Y", strtotime($arrYear[1].".03.31"));
			$arrDate2 = date("d-m-Y");
		?>
			return setMinMaxDateByDates(
				'<?php echo date("d-m-Y", strtotime($arrDate1));?>',
				'<?php echo date("d-m-Y", strtotime($arrDate2));?>'	
			);
        }
	});*/
	//$('#MONTHLY_FORM_DATE').datepicker({dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true});
	//$('input[type="text"]').css("text-align", "right");
	//$('.date_picker').css("text-align", "center");
	$('.dp1').css("text-align", "center");
	window.validator = 
	$("#frmYearlyTarget").validate({
		rules: {
			/*"BUDGET_AMOUNT" : {required : true, min:0, number:true},*/
			"EXPENDITURE[]" : {required : true, min:0, number:true}/*,
			"SUBMISSION_DATE":{required : true}*/
			<?php if(count($arrForValidation)>0){
				echo ','.implode(',', $arrForValidation);
			}?>
		},
		messages: {
			/*"BUDGET_AMOUNT" : {required : "Required - Budget Amount", min:"Required Positive Amount"}*/
		}
	});
	// the following method must come AFTER .validate()
    $('#frmYearlyTarget').find('.myytclass').each(function() {
        $(this).rules('add', {
            required: true,
			number:true,
			min:0,
            messages: {
                required: "Required",
				number : "Invalid No."
                /*minlength: jQuery.format("At least {0} characters are necessary")*/
            }
        });
    });
    $('#frmYearlyTarget').find('.myytclassInt').each(function() {
        $(this).rules('add', {
            required: true,
			digits:true,
			min:0,
            messages: {
                required: "Required",
				number : "Invalid No."
                /*minlength: jQuery.format("At least {0} characters are necessary")*/
            }
        });
    });
	calculateSubTotal('EXPENDITURE');
});
//
var v=0;
function calculateSubTotal(ids, no){
	var sum = 0;
	var fin_tot = 0;
	var i;
	for(i=1;i<=12;i++){
		v = $('#' + ids + '_' + i).val();
		if(v==''){v=0;}
		//if(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(v)){
		if(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(v)){
			sum = getFloatValue(sum) + getFloatValue(v);
			if($('#EXPENDITURE_'+i).val()){
				fin_tot =  parseFloat(fin_tot) + parseFloat(v);
			}
		}
	}
	if(ids=='EXPENDITURE'){
		getExpenditure(fin_tot);
		/*if(fin_tot>$("#BUDGET_AMOUNT").val()){
			alert('Financial Total Should Not Exceed');		
			$('#EXPENDITURE_' + no).val('');
			// $('#EXPENDITURE_TOT').val('');
			return;
		}*/
		$('#'+ids+'_TOTAL').val( sum.toFixed(2));
	}else if(ids=='LA_NO' || ids=='CANAL_STRUCTURES'){
		$('#'+ids+'_TOTAL').val( sum );	
	}else{
		$('#'+ids+'_TOTAL').val( sum.toFixed(3));
	}
	//alert("#"+ids+"_TOT");
}
function getExpenditure(fin_tot){
	$('#divBudgetAmount').html( fin_tot.toFixed(2));
	$('#BUDGET_AMOUNT').val(fin_tot.toFixed(2));
}
//
function getFloatValue (vv){
	var n = parseFloat(vv);
	return ( (isNaN(n))? 0:n);
}
//
function calculate(ids){
	var curMonthVal = $('#'+ids).val();
	var prevMonth = $('#'+ids+"_P_H").val();
	
	if(prevMonth=='') prevMonth=0;
	if(curMonthVal=='') curMonthVal=0;
	var sum;
	//if(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(curMonthVal)){
		//sum = parseFloat(curMonthVal) + parseFloat(sum);
		//sum = parseFloat(prevMonth) + parseFloat(curMonthVal);
		//$("#"+ids+"_P").val(sum);
	//}
	sum = 0;
	var curFinancialYear = $('#'+ids+'_CFY_H').val();
	if(curFinancialYear=="") curFinancialYear=0;	
	sum = parseFloat(curFinancialYear) + parseFloat(curMonthVal);
	$('#'+ids+'_CFY').val(sum);
}
//
function saveTarget(){
	//check budget amount
	/*var totalExpenditure = getFloatValue( $('#EXPENDITURE_TOTAL').val() );
	var budgetAmount = getFloatValue( $("#BUDGET_AMOUNT").val() );
	if(totalExpenditure>budgetAmount){
		alert(	"You can allot as per budget.\n Budget Amount :" + 
				budgetAmount + "\n" + 
				"You are allotting : " + totalExpenditure
			);
		return;
	}*/
	var myValidation = $("#frmYearlyTarget").valid();
	if( !myValidation ){
		alert('You have : ' + window.validator.numberOfInvalids() + ' errors in this form.');
		return;
	}
	if(myValidation){
		var params = {
			'divid':'mySaveDiv', 
			'url':'saveTarget', 
			'data':$('#frmYearlyTarget').serialize(),
			'donefname': 'doneSaving', 
			'failfname' :'failSaving', 
			'alwaysfname':'none'
		};
		callMyAjax(params);
	}else{
		showMyAlert('Error...', 'There is/are some Required Data... <br />Please Check & Complete it.', 'warn');
	}
}
function doneSaving(response){
	$('#message').html( parseAndShowMyResponse(response) );
	<?php if($entrymode=='monthly'){
		echo 'closeDialog("modalBox");';
	}else{
		echo "$('#divTargetForm').html('');\ngridReload();";
	}?>
}
function failSaving(response){
	$('#message').html( parseAndShowMyResponse(response) );
}
//
function checkAAAmount(){
	if($('#BUDGET_AMOUNT').val()>0){
		var BUDGET_AMOUNT = parseFloat($('#BUDGET_AMOUNT').val());
		var AA_AMOUNT = parseFloat($('#AA_AMOUNT').val());
		if( (BUDGET_AMOUNT>AA_AMOUNT) ){
			alert('Budget Amount is more than ' + $('#AA_RAA').val() + ' Amount :' + $('#AA_AMOUNT').val());
			$('#BUDGET_AMOUNT').val('');
		}
	}
}
//
function fillZero(){
	$('.xtext').each(function(){
		if(this.value==''){
			this.value=0;
		}
	});
}
function calculateIrri(mmonth){
	var ik = 'IRRIGATION_POTENTIAL_KHARIF_'+mmonth;
	var ir = 'IRRIGATION_POTENTIAL_RABI_'+mmonth;
	var it = 'IRRIGATION_POTENTIAL_'+mmonth;
	
	var kt = checkNo($('#'+ ik).val());
	var rt = checkNo($('#'+ ir).val());
	var tt = kt + rt
	$('#'+ it).val( roundNumber(tt, 5));
	calculateSubTotal('IRRIGATION_POTENTIAL_KHARIF');
	calculateSubTotal('IRRIGATION_POTENTIAL_RABI');
	calculateSubTotal('IRRIGATION_POTENTIAL');
}
</script>