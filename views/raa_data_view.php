<div class="ui-state-error" style="padding:5px">
<span class="cus-lightbulb"></span> 
<strong>Deposit Module में पुनरीक्षित प्रशासकीय स्वीकृति (RAA) विवरण entry करने के बाद, <br />
	E-payment (E-works) में  Send Works/Agreement To E-kosh Module से पुनरीक्षित प्रशासकीय स्वीकृति (RAA) विवरण को E-kosh में भेजे।
</strong>
</div>
<div class="wrdlinebreak"></div>
<form name="frmProject" id="frmProject" onsubmit="return false;">
	<input type="hidden" name="PROJECT_ID" id="PROJECT_ID" value="<?php echo $PROJECT_ID;?>" />
	<input type="hidden" name="RAA_PROJECT_ID" id="RAA_PROJECT_ID" value="<?php echo $raaData['RAA_PROJECT_ID'];?>" />
	<input type="hidden" name="ESTIMATED_QTY_ID" id="ESTIMATED_QTY_ID" value="<?php echo $currentEstimation['ESTIMATED_QTY_ID'];?>" />
	<input type="hidden" name="AA_DATE" id="AA_DATE" value="<?php echo myDateFormat($projectData['AA_DATE']);?>" />
	<input type="hidden" name="MY_DATE" id="MY_DATE" value="<?php echo date("d-m-Y");?>" />
	<input type="hidden" name="ADDED_BY" id="ADDED_BY" value="<?php echo $raaData['ADDED_BY'];?>" />
<table width="100%" border="0" cellpadding="3" class="ui-widget-content">
<tr>
    <td align="center" class="ui-widget-header">
        <strong>
        <?php echo $projectData['PROJECT_NAME'].' <br /> '.$projectData['PROJECT_NAME_HINDI'];?>
        </strong>
    </td>
</tr>
<tr>
	<td class="ui-widget-content" align="center">
        <div id="radioset"><strong>Entry Type : </strong>
			
			<input type="radio" id="radio1" name="IS_RAA" value="1" 
            	onchange="changeCheckBoxOption(1, this.checked)"
			<?php echo ($raaData['IS_RAA']==1)? 'checked="checked"':'';?> />
            <label for="radio1">RAA</label>
			

            <input type="radio" id="radio2" name="IS_RAA" value="2" 
	            onchange="changeCheckBoxOption(2, this.checked)"
			<?php echo ($raaData['IS_RAA']==2)? 'checked="checked"':'';?> />
            <label for="radio2">Extra Quantity</label>
            
			<input type="radio" id="radio3" name="IS_RAA" value="3" 
            	onchange="changeCheckBoxOption(3, this.checked)"
			<?php echo ($raaData['IS_RAA']==3)? 'checked="checked"':'';?> />
            <label for="radio3">TS</label>
            
			<?php $arrMode = array('', 'RAA', 'Sanction', 'TS');
			$entryMode = $arrMode[ $raaData['IS_RAA'] ];?>
        </div>
	</td>
</tr>
</table>
<div class="wrdlinebreak"></div>
<div class="ui-state-error" style="padding:5px">
<span class="cus-lightbulb"></span> 
<strong>अगर नवीनतम मात्रा में कमी हो तभी कम मात्रा भरें। <br />
अगर नवीनतम मात्रा(Latest) में कोई भी परिवर्तन न हो तो नवीनतम मात्रा(Latest) कॉलम में पुरानी मात्रा(Old) को ही डालना है।</strong>
</div>
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" id="RAA_DETAIL">
<tr>
    <td class="ui-state-default">
    	<strong id="raa_no"><?php echo $entryMode;?> No : </br><span style="color:red;">(Only numeric.)</span></strong>
    </td>
    <td class="ui-widget-content">
		<input name="RAA_NO"  id="RAA_NO" type="text" 
        	size="35" maxlength="50" 
        	value="<?php echo $raaData['RAA_NO'];?>"
            class="" />
    </td>
    <td class="ui-state-default"><strong id="raa_date"><?php echo $entryMode;?> Date :</strong></td>
    <td class="ui-widget-content">
        <input name="RAA_DATE" type="text" id="RAA_DATE" 
            size="18" maxlength="50" 
            value="<?php echo myDateFormat($raaData['RAA_DATE']);?>" 
            class="centertext"  />
	</td>
</tr>
<tr>
  <td class="ui-state-default"><strong id="raa_aid"><?php echo $entryMode;?> Authority :</strong></td>
  <td class="ui-widget-content">
    <select name="RAA_AUTHORITY_ID" id="RAA_AUTHORITY_ID" 
    	style="width:200px;" class=" raa-select" >
      <option value="" >Select Authority</option>
      <?php echo implode('', $RAA_AUTHORITY_ID);?>
      </select>
    </td>
  <td class="ui-state-default"><strong id="raa_amt"><?php echo $entryMode;?> Amount :</strong></td>
  <td class="ui-widget-content"><input name="RAA_AMOUNT" id="RAA_AMOUNT" type="text" 
        	size="12" maxlength="20" 
            value="<?php echo $raaData['RAA_AMOUNT'];?>" 
            class=" righttext" /> Rs. In Lacs
  </td>
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
    <td align="right" class="ui-widget-content">
        <strong><?php echo $previousEstimation['EXPENDITURE_TOTAL'];?></strong>
    </td>
    <td align="center" class="ui-widget-content"><?php echo getRequiredSign('left');?>
        <input name="EXPENDITURE_TOTAL" id="EXPENDITURE_TOTAL" 
            type="text" size="12" maxlength="50" 
            value="<?php echo $currentEstimation['EXPENDITURE_TOTAL'];?>" 
            class="righttext" />
    </td>  
</tr>	
<tr>
    <td nowrap="nowrap" class="ui-widget-content"><strong>b. Works</strong></td>
    <td nowrap="nowrap" class="ui-widget-content"><strong>Rs. Lacs</strong></td>
    <td nowrap="nowrap" class="ui-widget-content"></td>
    <td align="right" class="ui-widget-content">
        <strong><?php echo $previousEstimation['EXPENDITURE_WORK'];?></strong>
    </td>
    <td align="center" class="ui-widget-content">
        <?php echo getRequiredSign('left');?>
        <input name="EXPENDITURE_WORK" id="EXPENDITURE_WORK"
            type="text" size="12" maxlength="50"
            value="<?php echo $currentEstimation['EXPENDITURE_WORK'];?>"
            class="righttext" />
    </td>  
</tr>	
<tr>
    <td colspan="5" nowrap="nowrap" class="ui-state-default">
        <strong>Physical</strong></td>
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
		'TITLE'=>'Canals Earth work ', 
		'UNIT'=>'Th Cum',
		'NA_VALUE'=>$estimationStatus['CANAL_EARTHWORK_NA'],
		'EA_NAME'=>'CANAL_EARTHWORK', 
		'EA_VALUE'=>$previousEstimation['CANAL_EARTHWORK'], 
		'AC_NAME'=>'CANAL_EARTHWORK',
		'AC_VALUE'=>$currentEstimation['CANAL_EARTHWORK'],
		'SHOW'=>1
		),
	array('SNO'=>8,
		'TITLE'=>'Canals Structures', 
		'UNIT'=>'Numbers',
		'NA_VALUE'=>$estimationStatus['CANAL_STRUCTURES_NA'],
		'EA_NAME'=>'CANAL_STRUCTURES', 
		'EA_VALUE'=>$previousEstimation['CANAL_STRUCTURES'], 
		'AC_NAME'=>'CANAL_STRUCTURES',
		'AC_VALUE'=>$currentEstimation['CANAL_STRUCTURES'],
		'SHOW'=>1
		),
	array('SNO'=>9,
		'TITLE'=>'Canals Lining', 
		'UNIT'=>'Km.',
		'NA_VALUE'=>$estimationStatus['CANAL_LINING_NA'],
		'EA_NAME'=>'CANAL_LINING', 
		'EA_VALUE'=>$previousEstimation['CANAL_LINING'], 
		'AC_NAME'=>'CANAL_LINING',
		'AC_VALUE'=>$currentEstimation['CANAL_LINING'],
		'SHOW'=>1
		),

	array(
		'SNO'=>10,
		'TITLE'=>'Road Works', 
		'UNIT'=>'Km.',
		'NA_VALUE'=>$estimationStatus['ROAD_WORKS_NA'],
		'EA_NAME'=>'ROAD_WORKS', 
		'EA_VALUE'=>$previousEstimation['ROAD_WORKS'], 
		'AC_NAME'=>'ROAD_WORKS',
		'AC_VALUE'=>$currentEstimation['ROAD_WORKS'],
		'SHOW'=>1

		/*'SNO'=>10,
		'TITLE'=>'Road Works', 
		'UNIT'=>'Km.',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['ROAD_WORKS_NA'],
		'EA_NAME'=>'ROAD_WORKS', 
		'EA_VALUE'=>$estimationData['ROAD_WORKS'], 
		'AC_NAME'=>'ROAD_WORKS_ACHIEVE',
		'AC_VALUE'=>$achievementValues['ROAD_WORKS'],

		'LEA_NAME'=>'ROAD_WORKS_LEA',								//Latest Estimated Amount
		'LEA_VALUE'=>$estimationAmountData['ROAD_WORKS_LEA'],
		'SHOW'=>1*/
	),

	array('SNO'=>11,
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
	)
);
/////////////////////////////////////
//$content = '';
$arrV = array();
$arrValidComponent = array();
foreach($arrEstimationAchievements as $x){
	if($x['NA_VALUE']) continue;
	$myClass = ($x['NA_VALUE'])? '' : 'required';
	$rowSpan = '';
	if($x['SNO']==11){
		$rowSpan = 'rowspan="3"';
	}
	echo '<tr>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'><strong>'.$x['TITLE'].'</strong></td>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'>'.$x['UNIT'].'</td>';

	if($x['SNO']==11){
		array_push($arrValidComponent, $x['AC_NAME'].'_KHARIF');
		array_push($arrValidComponent, $x['AC_NAME'].'_RABI');
		echo '<td align="center" class="ui-widget-content">Kharif</td>
			<td align="right" class="ui-widget-content">
			<input type="hidden" id="esti'.$x['AC_NAME'].'_KHARIF" value="'.$x['KHARIF']['EA_VALUE'].'" />
			<strong>'.$x['KHARIF']['EA_VALUE'].'</strong></td>
			<td align="center" class="ui-widget-content">'.getRequiredSign('left').'
			  <input name="'.$x['AC_NAME'].'_KHARIF" type="text" id="'.$x['AC_NAME'].'_KHARIF"
			  onkeyup="calculateIrri()"			  	
				 size="12" maxlength="50"  class="righttext" value="'.$x['KHARIF']['AC_VALUE'].'"/>
			</td>
			</tr><tr>
			<td align="center" class="ui-widget-content">Rabi</td>
			<td align="right" class="ui-widget-content">
			<input type="hidden" id="esti'.$x['AC_NAME'].'_RABI" value="'.$x['RABI']['EA_VALUE'].'" />
			<strong>'.$x['RABI']['EA_VALUE'].'</strong></td>
			<td align="center" class="ui-widget-content">'.getRequiredSign('left').'
			  <input name="'.$x['AC_NAME'].'_RABI" type="text" id="'.$x['AC_NAME'].'_RABI"
				 size="12" maxlength="50"  class="righttext" 
				onkeyup="calculateIrri()" value="'.$x['RABI']['AC_VALUE'].'"/>
			</td>
			</tr><tr>
			<td align="center" class="ui-state-default">Total</td>
			<td align="right" class="ui-state-default"><strong>'.$x['EA_VALUE'].'</strong></td>
			<td align="center" class="ui-state-default">
			  <input name="'.$x['AC_NAME'].'" type="text" id="'.$x['AC_NAME'].'"
				 size="12" maxlength="50" class="righttext" readonly="readonly" 
					value="'.$x['AC_VALUE'].'"/>
			</td>';
	}else{
		array_push($arrV, '"'.$x['AC_NAME'].'":{required : true, number:true, min:'.$x['EA_VALUE'].'}');
		array_push($arrValidComponent, $x['AC_NAME']);
		echo '<td align="center" class="ui-widget-content"></td>
			<td align="right" class="ui-widget-content">
			<input type="hidden" id="esti'.$x['AC_NAME'].'" value="'.$x['EA_VALUE'].'" />
			<strong>'.$x['EA_VALUE'].'</strong>
			</td>
			<td align="center" class="ui-widget-content">'.getRequiredSign('left').'
			  <input name="'.$x['AC_NAME'].'" type="text" id="'.$x['AC_NAME'].'"
				 size="12" maxlength="50"  class="righttext" 
					value="'.$x['AC_VALUE'].'"/></td>';
	}
	echo '</tr>';
}//foreach?>
</table>
<div id="mySaveDiv" align="right" class="mysavebar">
<?php 
$userId = getSessionDataByKey("USER_ID");
if($raaData['RAA_PROJECT_ID']==0){
	echo getButton('Save', 'saveRAASetup()', 4, 'cus-disk'). ' &nbsp; ';
}else if($userId==23){
	echo getButton('Save', 'saveRAASetup()', 4, 'cus-disk'). ' &nbsp; ';
}else if($raaData['ADDED_BY']){
	if($isMonthlyExists){
		
	}else
		echo getButton('Save', 'saveRAASetup()', 4, 'cus-disk'). ' &nbsp; ';
}
echo getButton('Cancel', 'closeDialog()', 4, 'cus-cancel');
echo '##'.$isMonthlyExists.'###';
?>

</div>
</form>
<script language="javascript" type="text/javascript">
//
var validator = '';
$().ready(function(){
	$('#RAA_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true,
		beforeShow: function(input, inst) {	return setMinMaxDate('#AA_DATE', 'today'); }
	});
	//SESSION_ID = $('#SESSION_ID').val();
	$(".raa-select").select2();
	getToolTips();
	setSelect2();
	window.validator = 
	$("#frmProject").validate({
		rules: {
			"IS_RAA" : {required : true, min:1, max:3},
			"RAA_NO" : {required : true, number:true},
			"RAA_DATE" :{required : true, indianDate:true, 
				dpDate: true, dpCompareDate: {before:'#MY_DATE'}},
			"RAA_AMOUNT":{required : true, min:0, number:true},
			"EXPENDITURE_TOTAL":{required : true, number:true, min:<?php echo $previousEstimation['EXPENDITURE_TOTAL'];?>},
			"EXPENDITURE_WORK":{required : true, number:true, min:<?php echo $previousEstimation['EXPENDITURE_WORK'];?>}
			<?php /*if(count($arrV)>0){
				echo ','.implode(',', $arrV);
			}*/?>
		},
		messages: {
			"IS_RAA" : {required : "Select RAA / TS / Extra Quantity"},
			"RAA_NO" : {required : "Required - RAA / TS / Extra Quantity No ", number: "Required numeric value only."},
			"RAA_DATE" : {required : "Required - RAA / TS / Extra Quantity Date"},
			"RAA_AMOUNT" : {required : "Required - RAA / TS / Extra Quantity Amount", min:"Required Positive Amount"}
		}
	});
	$("#radioset").buttonset();
	<?php if($raaData['IS_RAA']){?>
	changeCheckBoxOption(<?php echo $raaData['IS_RAA'];?>, true);
	<?php }?>
});
//
function setEstimationFields(sno, mName, status){
	var requiredField1 = mName.substr(0, (mName.length-3));
	$('#'+requiredField1).prop('disabled', status);
	if(status) $('#'+requiredField1).val('');
}
function changeCheckBoxOption(mode, status){
	switch(mode){
		case 1://RAA
			//alert('raa');
			$('#raa_no').html("RAA No : ");
			$('#raa_date').html("RAA Date : ");
			$('#raa_aid').html("RAA Authority: ");
			$('#raa_amt').html("RAA Amount : ");
			//Add amount validation
			<?php $arrValidComponent;?>
			$('#RAA_AMOUNT').rules( "add", {
				required: true,
				min: 0,
				messages: {
					required: "Required.",
					min: "Minimum value 0"
				}
			});
			$('#RAA_AMOUNT').prop('disabled', false);
			<?php foreach($arrValidComponent as $comp){?>
				var estiMinVal = parseFloat($('#esti<?php echo $comp;?>').val()); // add by amit on 08-08-2024
				$('#<?php echo $comp;?>').rules("remove");
				$('#<?php echo $comp;?>').rules( "add", {
					required: true,
					//min: 0,
					min: estiMinVal,
					messages: {
						required: "Required.",
						//min: "Minimum value 0"
						min: "Minimum value is greater than or equal to "+estiMinVal
					}
				});
			<?php }?>
			break;
		case 2://XTRA QTY
			//alert('xtra qty');
			$('#raa_no').html("Sanction No : ");
			$('#raa_date').html("Sanction Date : ");
			$('#raa_aid').html("Sanction Authority: ");
			$('#raa_amt').html("Sanction Amount : ");
			//remove amount validation
			$('#RAA_AMOUNT').prop('disabled', true);
			$('#RAA_AMOUNT').rules("remove");
			$('#RAA_AMOUNT').val(0);
			<?php foreach($arrValidComponent as $comp){?>
				var estiMinVal = parseFloat($('#esti<?php echo $comp;?>').val()); // add by amit on 08-08-2024
				$('#<?php echo $comp;?>').rules("remove");
				$('#<?php echo $comp;?>').rules( "add", {
					required: true,
					//min: 0,
					min: estiMinVal,
					messages: {
						required: "Required.",
						//min: "Minimum value 0"
						min: "Minimum value is greater than or equal to "+estiMinVal
					}
				});
				//$('#esti<?php echo $comp;?>').val()
			<?php }?>
			break;
		case 3://TS
			//alert('ts');
			$('#raa_no').html("TS No : ");
			$('#raa_date').html("TS Date : ");
			$('#raa_aid').html("TS Authority: ");
			$('#raa_amt').html("TS Amount : ");
			//remove amount validation
			$('#RAA_AMOUNT').rules("remove");
			$('#RAA_AMOUNT').prop('disabled', true);
			$('#RAA_AMOUNT').val(0);
			<?php foreach($arrValidComponent as $comp){?>
				var estiMinVal = parseFloat($('#esti<?php echo $comp;?>').val()); // add by amit on 08-08-2024
				$('#<?php echo $comp;?>').rules("remove");
				$('#<?php echo $comp;?>').rules( "add", {
					required: true,
					//min: 0,
					min: estiMinVal,
					messages: {
						required: "Required.",
						//min: "Minimum value 0"
						min: "Minimum value is greater than or equal to "+estiMinVal
					}
				});
			<?php }?>
			break;
	}
}
function calculateIrri(){
	var kh = checkNo($('#IRRIGATION_POTENTIAL_KHARIF').val());
	var rab = checkNo($('#IRRIGATION_POTENTIAL_RABI').val());
	var tot = kh + rab;
	$('#IRRIGATION_POTENTIAL').val(roundNumber(tot, 3));
}
</script>
