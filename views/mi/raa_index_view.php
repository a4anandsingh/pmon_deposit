<title>Project RAA/TS/Extra Quantity Setup - WRD MIS</title>
<?php echo $office_list;?>
<!--Start of Content -->
<div id="content_wrapper">
	<div id="page_heading">
	    <?php echo $page_heading;?>
    </div>
    <div style="width:100%;float:left">
        <div id="office_filter" class="messagebox"></div>
    </div>
    <div style="width:100%;float:left">
	    <?php echo getMessageBox('message', $message);?>
    </div>
    <div style="width:100%;float:left;margin-bottom:10px" align="center">
		<div style="width:584px;float:left" align="left">
            <table id="projectGrid"></table>
            <div id="projectGridPager"></div>
		</div>
 		<div style="padding-left:8px;width:400px;float:left;/*display:none*/">
            <table id="raaGrid"></table>
            <div id="raaGridPager"></div>
		</div>
	</div>
</div>
<!--End of Content -->
<script type="text/javascript">
var objOffice = new clsOffice();
$().ready(function(){
	showOfficeFilterBox();
	//jqGrid_Projects();
});
/*function getExp(sid, ids){
	$('#projectGrid').expandSubGridRow(sid);
	//alert("SID:" + sid + " ids:"+ids);
}
function getColp(sid, ids){
	alert("SSID:" + sid + " Sids:"+ids);
}*/
search_office = new clsOffice();
sdo_search_office = new clsOffice();
/** OK on 29-10-2013*/
function showOfficeFilterBox(){
	var mydata = {'prefix':'search_office'};
	var params = {
		'divid':'office_filter', 
		'url':'showOfficeFilterBox', 
		'data':mydata, 
		'donefname': 'doneOfficeSearch', 
		'failfname' :'', 
		'alwaysfname':'none'
	};
	callMyAjax(params);
}
//
function doneOfficeSearch(response){
	$('#office_filter').html(response);
	search_office.init();
	//refreshSearch();
	jqGrid_Projects();
	jqGrid_raa();
}
function jqGrid_Projects(){
<?php echo $project_grid; ?>
	jQuery("#projectGrid").jqGrid('setGroupHeaders', {
		useColSpanStyle:true, 
		groupHeaders:[
			{startColumnName:'AA_NO', numberOfColumns: 3, titleText: 'AA Details'}
		]	
	});
}
function jqGrid_raa(){
	<?php echo $raa_grid; ?>
	jQuery("#raaGrid").jqGrid('setGroupHeaders', {
		useColSpanStyle:true, 
		groupHeaders:[
			{startColumnName:'RAA_NO', numberOfColumns: 3, titleText: 'RAA Details'}
		]	
	});
	centerCaption("raaGrid");
}
/** */
function showFilter(PNAME){
	$('#SEARCH_CE_ID').val(0);
	$('#SEARCH_SE_ID').val(0);
	$('#SEARCH_EE_ID').val(0);
	$('#SEARCH_EE_ID').val(0);
	$('#SEARCH_PROJECT_NAME').val(PNAME);	
	gridReload();
}
/** OK on 02-11-2013 */
function gridReload(){
	$("#projectGrid").jqGrid('setGridParam',{
		postData : {
			'SEARCH_CE_ID':$('#SEARCH_CE_ID').val(), 
			'SEARCH_SE_ID':$('#SEARCH_SE_ID').val(), 
			'SEARCH_EE_ID':$('#SEARCH_EE_ID').val(), 
			'SEARCH_SDO_ID':$('#SEARCH_SDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		}, 
		page:1})
	.trigger("reloadGrid"); 
} 
function refreshSearch(){
	$("#projectGrid").jqGrid('setGridParam',{
		postData:{
			'SEARCH_CE_ID':$('#search_officeCE_ID').val(), 
			'SEARCH_SE_ID':$('#search_officeSE_ID').val(), 
			'SEARCH_EE_ID':$('#search_officeEE_ID').val(), 
			'SEARCH_SDO_ID':$('#search_officeSDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		}, 
		page:1
	}).trigger("reloadGrid");
	refreshRAASearch();
}
function refreshRAASearch(){
	//jqGrid_Projects();
	var projectID = 0;
	var projectID = $("#projectGrid").getGridParam("selrow");

	if(projectID){
		rec = $("#projectGrid").jqGrid('getRowData', projectID);
		if(rec){
			$('#raaGrid').jqGrid('setCaption', rec.PROJECT_NAME);
		}
	}
	$("#raaGrid").jqGrid('setGridParam', {
		postData:{'PROJECT_SETUP_ID':projectID},
	}).trigger("reloadGrid");
}
//
function showRAAEntryForm(mode){
	var projectID = 0;
	var projectID = $("#projectGrid").getGridParam("selrow");
	if(projectID){
		rec = $("#projectGrid").jqGrid('getRowData', projectID);
		if(rec){
			$('#raaGrid').jqGrid('setCaption', rec.PROJECT_NAME);
		}
	}else{
		alert('Select Project...');
		return;
	}
	recId = 0;
	if(mode>0){
		recId = checkNo($("#raaGrid").getGridParam("selrow"));
		/*if(id){
			recId = $("#raaGrid").jqGrid('getRowData', id);
		}*/
		if(recId==0){
			alert('Select RAA/TA/Extra Quantity Record...');
			return;
		}
	}
	var data = {'RAA_PROJECT_ID':recId, 'PROJECT_SETUP_ID':projectID};
	var title = ((mode==0) ? 'Add New ' : 'Modify '  )+ ' RAA/TA/Extra Quantity For Project';
	showModalBox('modalBox', 'showRAASetupEntryBox', data, title, 'doneShowRAAProject', true);
}
//
function doneShowRAAProject(response){
	$('#modalBox').html(response);
	$('#modalBox').dialog('open');
	centerDialog('modalBox');
}
//
function failProject(response){
	$('#modalBox').html('Fail to Open');
	$('#modalBox').dialog('option', 'position', 'center');
	//$('#modalBox').dialog('open');
}
var currentSelectedRow = 0;
//
function saveRAASetup(){
	var selectList = new Array();
	selectList[0] = Array('RAA_AUTHORITY_ID', 'Select Authority', false);
	selectList[1] = Array('IS_RAA', 'Select Entry Type', false);
	var mSelect = validateMyCombo(selectList);
	var myValidation = $("#frmProject").valid();
	if( !(mSelect==0 && myValidation)){
			alert('You have : ' + ( validator.numberOfInvalids() + mSelect ) + ' errors in this form.');
			//alert('Please Check Errors');
		return ;
	}
	//var myValidation = $("#frmProject").validationEngine('validate');
	if(myValidation){
		/*var mydata = $('#frmProject').serialize();
		var params = {
			'divid':'mySaveDiv', 
			'url':'saveRAAData', 
			'data':mydata, 
			'donefname': 'doneSaveRAA', 
			'failfname' :'failProject', 
			'alwaysfname':''
		};
		callMyAjax(params);*/		
		//return;
		var fileData = new FormData($('#frmProject')[0]);
        var params = {
            'divid':'mySaveDiv',
            'url':'saveRAAData',
            'data':fileData,
            'donefname': 'doneSaveRAA',
            'failfname' :'failProject',
            'alwaysfname':''
        };
        callMyAjaxUploadFile(params);
	}else{
		showMyAlert('Error...', 'There is/are some Required Data... <br />Please Check & Complete it.', 'warn');
	}
}
//
function doneSaveRAA(response){
	//$('#projectGrid').expandSubGridRow(rowid);
	refreshRAASearch();
	$('#message').html(parseAndShowMyResponse(response));
	closeDialog();
}
function deleteRAA(id, pid){
	var values = {'RAA_PROJECT_ID':id, 'PROJECT_SETUP_ID':pid};
	$.ajax({
		type:"POST",
		mtype:"POST",
		url:'deleteRAA',
		data:values,
		success:function(msg){
			$('#message').html(msg);
			gridReload();
		}
	});	
}
//
function projectOperation(mode, pid){
	if(mode==BUTTON_ADD_NEW){
		var data = {'RAA_PROJECT_ID':0, 'PROJECT_SETUP_ID':pid};
		var title = 'Add New RAA/Extra Quantity for Project';
		//modelBox('showEntryBox', data, title, 'auto', 'auto');	
		showModalBox('modalBox', 'showRAASetupEntryBox', data, title, 'showProjectData', true);
		//show_project_modalBox(0, 0);
	}
}
//
function showProjectData(msg){
	$('#modalBox').html(parseAndShowMyResponse(msg));
	//$('#modalBox').html(parseMyResponse(msg));
	centerDialog('modalBox');
}
/***/
function lockProject(rid, pid){
	var params = {
		'divid':'mySaveDiv',
		'url':'lockRAA', 
		'data':{'project_id':pid, 'raaid':rid}, 
		'donefname': 'doneLockProject', 
		'failfname' :'', 
		'alwaysfname':'none'
	};
	callMyAjax(params);
}
/***/
function doneLockProject(data){
	if(data==1){
		//locked
		$("#modalBox").dialog('close');
		gridReload();
	}else{
		//fail to lock
	}
}
</script>