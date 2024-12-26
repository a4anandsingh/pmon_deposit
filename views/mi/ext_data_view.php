<form name="frmProject" id="frmProject" onsubmit="return false;">
	<input type="hidden" name="PROJECT_SETUP_ID" id="PROJECT_SETUP_ID" value="<?php echo $PROJECT_SETUP_ID;?>" />
	<input type="hidden" name="ID" id="ID" value="<?php echo $extData['ID'];?>" />
<table width="100%" border="0" cellpadding="3" class="ui-widget-content">
<tr>
    <td rowspan="2" class="ui-widget-header"><strong>Project Name :</strong></td>
    <td rowspan="2" class="ui-widget-content">
    	<strong><?php echo $projectData['PROJECT_NAME'] . ' <BR />' . $projectData['PROJECT_NAME_HINDI'];?>
        </strong>
    </td>
    <td class="ui-widget-header"><strong>Project Code :</strong></td>
    <td class="ui-widget-content"><strong><?php echo $projectData['PROJECT_CODE'];?></strong></td>
</tr>
<tr>
  <td class="ui-widget-header"><strong>Existing Completion Date :</strong></td>
  <td class="ui-widget-content"><strong><?php echo myDateFormat($existingCompletionDate);?></strong></td>
</tr>
</table>
<div class="wrdlinebreak"></div>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="ui-widget-content" id="RAA_DETAIL">
<tr>
    <td colspan="2" class="ui-widget-header" align="center">
   	  <strong>Project Extension</strong></td>
</tr>
<tr>
    <td class="ui-widget-content"><strong>Order No :</strong></td>
    <td class="ui-widget-content">
        <input name="ORDER_NO"  id="ORDER_NO" type="text" 
        	size="60" maxlength="50" 
        	value="<?php echo $extData['ORDER_NO'];?>"
            class="" />
    </td>
</tr>
<tr>
    <td class="ui-widget-content"><strong>Order  Date :</strong></td>
    <td class="ui-widget-content">
        <input name="ORDER_DATE" type="text" id="ORDER_DATE" 
        	size="18" maxlength="50" 
            value="<?php echo myDateFormat($extData['ORDER_DATE']);?>" 
            class=" centertext"  /> (dd-mm-yyyy)
    </td>
</tr>
<tr>
    <td class="ui-widget-content"><strong>Order by :</strong></td>
    <td class="ui-widget-content">
        <select name="ORDER_OFFICE_ID" id="ORDER_OFFICE_ID" style="width:450px;" 
        	class="raa-select" >
        <option value="" >Select Office</option>
        <?php echo $ORDER_BY_OFFICE_LIST;?>
        </select>
    </td>
</tr>
<tr>
	<td class="ui-widget-content"><strong>New Completion Date :</strong></td>
    <td class="ui-widget-content">
        <input name="NEW_COMPLETION_DATE" id="NEW_COMPLETION_DATE" type="text" 
        	size="18" maxlength="20" 
            value="<?php echo myDateFormat($extData['NEW_COMPLETION_DATE']);?>" 
            class="centertext" /> (dd-mm-yyyy)
    </td>
</tr>
</table>

<div id="mySaveDiv" align="right" class="mysavebar">
<?php
    echo getButton(array('caption'=>'Save ', 'event'=>'saveExt()', 'icon'=>'cus-disk', 'title'=>'Save ')).'&nbsp; '.
    getButton(array('caption'=>'Cancel ', 'event'=>'closeDialog()', 'icon'=>'cus-cancel', 'title'=>'Close'));    
?>
</div>
</form>
<script language="javascript" type="text/javascript">
/***/
var validator = '';
$().ready(function(){
	$('#ORDER_DATE').datepicker({
		dateFormat:'dd-mm-yy', 
		changeMonth:true, 
		changeYear:true, 
		showOtherMonths: true,
		beforeShow: function(input, inst) {	return setMinMaxDate('', 'today'); }
	});
	$('#NEW_COMPLETION_DATE').datepicker({ 
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true, 
		beforeShow: function(input, inst) {	return setMinMaxDate('#ORDER_DATE', ''); }
	});
	//SESSION_ID = $('#SESSION_ID').val();
	$(".raa-select").select2();
	getToolTips();
	setSelect2();
	validator = 
	$("#frmProject").validate({
		rules: {
			/*"ORDER_NO" : {required : true},
			"ORDER_DATE" : {required : true, indianDate:true},*/
			"NEW_COMPLETION_DATE" : {required : true, indianDate:true}
		},
		messages: {
			/*"ORDER_NO" : {required : "Required - Order No "},
			"ORDER_DATE" : {required : "Required - Order Date"},*/
			"NEW_COMPLETION_DATE" : {required : "Required New Completion Date"}
		}
	});
});
</script>