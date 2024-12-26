<title>Financial and Physical Target Setup - (Promon <?php echo ($this->session->userData('PROJECT_TYPE_ID')==1)? 'Minor':'Medium';?> )
 - WRD MIS</title>
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
	<div style="width:100%;float:left;margin-bottom:5px" align="center">
    	<a target="_blank" href="<?php echo base_url();?>documents/help/promon__deposit_target.pdf" 
         	class="btn btn-primary">
        	<span class="cus-page-white-acrobat"></span>
            Download Financial and Physical Target Setup Form (PDF File)
        </a>
    </div>
    <div style="width:100%;float:left;margin-bottom:15px" align="center">
        <table id="projectListGrid"></table>
        <div id="projectListGridPager"></div>
    </div>
    <div style="width:100%;float:left" align="center">
        <div id="divTargetForm" style="width:100%;float:left;"></div>
    </div>
</div>
<!--End of Content -->
<script type="text/javascript">
//var objOffice = new clsOffice();
$().ready(function(){
	showOfficeFilterBox();
});
search_office = new clsOffice();
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
	jqGrid_Projects();
}
//
function jqGrid_Projects(){
	<?php echo $project_target_grid; ?>
	jQuery("#projectListGrid").jqGrid('setGroupHeaders', {
		useColSpanStyle:true, 
		groupHeaders:[
			{startColumnName:'TARGET_LOCK_SESSION_ID', numberOfColumns: 2, titleText: 'Locked'}
		]	
	});
}
//
function refreshSearch(){
	$("#projectListGrid").setGridParam({
		postData :{
			'CE_ID':$('#search_officeCE_ID').val(), 
			'SE_ID':$('#search_officeSE_ID').val(), 
			'EE_ID':$('#search_officeEE_ID').val(),
			'SDO_ID':$('#search_officeSDO_ID').val(),
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val(),
		},
		page:1
	}).trigger('reloadGrid');
}
//
function gridReload(){
	$("#projectListGrid").setGridParam({
		postData :{ 
			'CE_ID':$('#search_officeCE_ID').val(), 
			'SE_ID':$('#search_officeSE_ID').val(), 
			'EE_ID':$('#search_officeEE_ID').val(),
			'SDO_ID':$('#search_officeSDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		}
	}).trigger('reloadGrid');
}
//
function showTargetForm(project_id, session_id){
	var params = {
		'divid':'divTargetForm', 
		'url':'showTargetForm', 
		'data': {'PROJECT_ID': project_id, 'session_id':session_id}, 
		'donefname': 'doneTarget', 
		'failfname' :'failTarget', 
		'alwaysfname':'none'
	};
	callMyAjax(params);
}

function showTargetFormMi(project_id, session_id){
	var params = {
		'divid':'divTargetForm',
		'url':'showTargetFormMi',
		'data': {'PROJECT_ID': project_id, 'session_id':session_id},
		'donefname': 'doneTarget',
		'failfname' :'failTarget',
		'alwaysfname':'none'
	};
	callMyAjax(params);
}


//
function doneTarget(response){
	$('#divTargetForm').html(response);
	$('#BUDGET_AMOUNT').focus();
}
function failTarget(response){
	$('#divTargetForm').html(response);
}
function closeTargetForm(){
	$('#divTargetForm').html('');
}
function afterReload(){
	$('#divTargetForm').html('');
}
//
function lockProject(project_id, session_id){
	var res = confirm("अनलॉक के उपरान्त अगर टारगेट लॉक करना चाहते है \nतो कृपया कम से कम एक बार टारगेट को सेव करना सुनिश्चित करें \n\n" + 
		" अगर आपने टारगेट सेव नहीं किया है तो Cancel बटन दबाएं \n टारगेट लॉक करने के लिए OK बटन दबाएं");
	if(res){
		var params = {
			'divid':'mySaveDiv',
			'url':'lockProject', 
			'data':{'project_id':project_id, 'session_id':session_id}, 
			'donefname': 'doneLocking', 
			'failfname' :'', 
			'alwaysfname':'none'
		};
		callMyAjax(params);
	}
}

function lockProjectMi(project_id, session_id){
	var res = confirm("अनलॉक के उपरान्त अगर टारगेट लॉक करना चाहते है \nतो कृपया कम से कम एक बार टारगेट को सेव करना सुनिश्चित करें \n\n" +
		" अगर आपने टारगेट सेव नहीं किया है तो Cancel बटन दबाएं \n टारगेट लॉक करने के लिए OK बटन दबाएं");
	if(res){
		var params = {
			'divid':'mySaveDiv',
			'url':'lockProjectMi',
			'data':{'project_id':project_id, 'session_id':session_id},
			'donefname': 'doneLocking',
			'failfname' :'',
			'alwaysfname':'none'
		};
		callMyAjax(params);
	}
}

function doneLocking(response){
	$('#message').html(response);
	gridReload();
}
</script>
