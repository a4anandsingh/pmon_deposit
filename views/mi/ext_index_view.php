<title>Project Extention - WRD MIS</title>
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
 		<div style="padding-left:8px;width:400px;float:left;/*display:none*/" >
            <table id="extGrid"></table>
        	<div id="extGridPager"></div>
		</div>
	</div>
</div>
<!--End of Content -->
<script type="text/javascript">
var objOffice = new clsOffice();
$().ready(function(){
	showOfficeFilterBox();
});
search_office = new clsOffice();
sdo_search_office = new clsOffice();
//
function showOfficeFilterBox(){
	var params = {
		'divid':'office_filter', 
		'url':'showOfficeFilterBox', 
		'data':{'prefix':'search_office'}, 
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
	jqGrid_ext();
	//jqGrid_raa();
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
function jqGrid_ext(){
	<?php echo $ext_grid; ?>
	centerCaption("extGrid");
	jQuery("#extGrid").jqGrid('setGroupHeaders', {
		useColSpanStyle:true, 
		groupHeaders:[
			{startColumnName:'ORDER_DATE', numberOfColumns: 2, titleText: 'Date'}
		]	
	});
}
//
function showFilter(PNAME){
	$('#SEARCH_CE_ID').val(0);
	$('#SEARCH_SE_ID').val(0);
	$('#SEARCH_EE_ID').val(0);
	$('#SEARCH_EE_ID').val(0);
	$('#SEARCH_PROJECT_NAME').val(PNAME);	
	gridReload();
}
//
function gridReload(){
	$("#projectGrid").jqGrid('setGridParam',{
		postData : {
			'SEARCH_CE_ID':$('#SEARCH_CE_ID').val(), 
			'SEARCH_SE_ID':$('#SEARCH_SE_ID').val(), 
			'SEARCH_EE_ID':$('#SEARCH_EE_ID').val(), 
			'SEARCH_SDO_ID':$('#SEARCH_SDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		}, 
		page:1
	}).trigger("reloadGrid"); 
} 
function refreshSearch(){
	//jqGrid_Projects();
	$("#projectGrid").jqGrid('setGridParam',{
		postData:{
			'SEARCH_CE_ID':$('#search_officeCE_ID').val(), 
			'SEARCH_SE_ID':$('#search_officeSE_ID').val(), 
			'SEARCH_EE_ID':$('#search_officeEE_ID').val(), 
			'SEARCH_SDO_ID':$('#search_officeSDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		},
	}).trigger("reloadGrid");
	refreshExtSearch();
}
function refreshExtSearch(){
	var projectID = 0;
	var projectID = $("#projectGrid").getGridParam("selrow");
	if(projectID){
		$("#extGrid").jqGrid('setGridParam', {
			postData:{'PROJECT_SETUP_ID':projectID},
		}).trigger("reloadGrid");
	}
}
//
function showEntryForm(mode){
	var projectID = 0;
	var projectID = $("#projectGrid").getGridParam("selrow");
	if(projectID){
		rec = $("#projectGrid").jqGrid('getRowData', projectID);
		if(rec){
			$('#extGrid').jqGrid('setCaption', rec.PROJECT_NAME);
		}
	}else{
		alert('Select Project...');
		return;
	}
	recId = 0;
	if(mode>0){
		recId = $("#extGrid").getGridParam("selrow");
		if(recId==0){
			alert('Select Extension Record...');
			return;
		}
	}
	var data = {'ID':recId, 'PROJECT_SETUP_ID':projectID};
	var title = ((mode==0) ? 'Add New ' : 'Modify '  )+ ' Extension For Project';
	showModalBox('modalBox', 'showEntryBox', data, title, 'doneShowProject', true);
}
//
function doneShowProject(response){
	$('#modalBox').html(response);
	$('#modalBox').dialog('open');
	centerDialog('modalBox');
}
//
function failProject(response){
	$('#modalBox').html('Fail to Open');
	centerDialog('modalBox');
}
var currentSelectedRow = 0;
//
function saveExt(mode){
	var selectList = new Array();
	selectList[0] = Array('ORDER_OFFICE_ID', 'Select Office', true);

	var mSelect = validateMyCombo(selectList);
	var myValidation = $("#frmProject").valid();

	if( !(mSelect==0 && myValidation)){
			alert('You have : ' + ( validator.numberOfInvalids() + mSelect ) + ' errors in this form.');
			//alert('Please Check Errors');
		return ;
	}
	//var myValidation = $("#frmProject").validationEngine('validate');
	if(myValidation){
		var params = {
			'divid':'mySaveDiv', 
			'url':'saveData', 
			'data':$('#frmProject').serialize(), 
			'donefname': 'doneSave', 
			'failfname' :'failProject', 
			'alwaysfname':''
		};
		callMyAjax(params);
	}else{
		showMyAlert('Error...', 'There is/are some Required Data... <br />Please Check & Complete it.', 'warn');
	}
}
//
function doneSave(response){
	//$('#projectGrid').expandSubGridRow(rowid);
	refreshExtSearch();
	$('#message').html(parseAndShowMyResponse(response));
	closeDialog();
}
function deleteExt(id, pid){
	$.ajax({
		type:"POST",
		mtype:"POST",
		url:'deleteData',
		data:{'ID':id, 'PROJECT_ID':pid},
		success:function(msg){
			$('#message').html(msg);
			gridReload();
		}
	});	
}
//
function showProjectData(msg){
	$('#modalBox').html(parseAndShowMyResponse(msg));
	//$('#modalBox').html(parseMyResponse(msg));
	centerDialog('modalBox');
}
</script>