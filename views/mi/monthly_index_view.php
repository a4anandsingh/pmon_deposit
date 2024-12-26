<title>Monthly Data Entry for <?php  echo ($this->session->userData('PROJECT_TYPE_ID')==1)? 'Minor':'Medium';?> Projects - WRD MIS</title>
<?php echo $office_list;?>
<!--Start of Content -->
<div id="content_wrapper">
	<div id="page_heading">
		<?php echo $page_heading;?>
    </div>
	<!--<div style="width:99%;float:left;padding:5px;font-size:15px;margin-bottom:5px;margin-top:5px" align="center">
        <div class="ui-state-error" style="width:60%;padding:5px;font-size:15px;margin-bottom:5px;margin-top:5px" >
            <span class="cus-flag-blue"></span> 
            मासिक प्रविष्टियों को लॉक करने के लिए लॉक बटन प्रत्येक माह के 9 तारीख से संभागीय कार्यालयों के लिए उपलब्ध होगा
            <br /> &nbsp; &nbsp; &nbsp;
            (उदाहरण के लिए सितंबर माह के डाटा के लिए लॉक बटन 9 अक्टूबर से उपलब्ध होगा)
        </div>
    </div>-->
    <div style="width:100%;float:left">
        <div id="office_filter" class="messagebox"></div>
    </div>
    <div style="width:100%;float:left">
	     <?php  if($isValid) $message = '<big><big style="color:#f00">अभी आपकी कुछ परियोजना (सूक्ष्म सिंचाई में) Setup या Financial and Physical Target Setup स्तर पर ही है इसलिए आप मासिक प्रविष्टी नहीं कर पा रहे हैं।</big></big>';
			echo getMessageBox('message', $message); ?>
    </div>
	<div style="width:100%;float:left;margin-bottom:5px" align="center">
    	<a target="_blank" href="<?php echo base_url();?>documents/help/promon_monthly_entry_format.pdf" class="btn btn-primary">
        	<span class="cus-page-white-acrobat"></span>
            Download Monthly Entry Form (PDF File)
        </a>
    </div>
    <div style="width:100%;float:left;margin-bottom:10px" align="center">
        <?php if(!$isValid){?>
        <table id="projectListGrid"></table> 
        <div id="projectListGridPager"></div>    
        <?php }?>    
	</div>
    <div style="width:100%;float:left" align="center" id="divMonthlyDataEntry"></div>
</div>
<!--End of Content -->
<script type="text/javascript">
$().ready(function(){
	showOfficeFilterBox();
	$(window).on("resize", function () {
		var newWidth = $("#projectListGrid").closest(".ui-jqgrid").parent().width();
		$("#projectListGrid").jqGrid("setGridWidth", newWidth, true);
	});
});
search_office = new clsOffice();
//sdo_search_office = new clsOffice();
//
function showOfficeFilterBox(){
	var params = {
		'divid':'office_filter', 
		'url':'showOfficeFilterBox', 
		'data':{'prefix':'search_office'}, 
		'donefname': 'doneOfficeSearch', 
		'failfname' :'', 'alwaysfname':'none'
	};
	callMyAjax(params);
}
function doneOfficeSearch(response){
	$('#office_filter').html(response);
	search_office.init();
	//refreshSearch();
	jqGrid_Projects();
}
//
function jqGrid_Projects(){
	<?php echo $project_monthly_grid;?>	
	//responsive_jqgrid($("#projectListGrid"));
}
//
function refreshSearch(){
	$("#projectListGrid").setGridParam({
		page:1, 
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
function getMyDValue(vv){
	var x = parseInt(vv);
	return ( (isNaN(x))?0:x);
}
//
function showMonthlyStatusForm(project_id, dtval){
	var params = {
		'divid':'divMonthlyDataEntry', 
		'url':'showMonthlyStatusForm', 
		'data':{'PROJECT_ID':project_id, 'entryMonth':dtval}, 
		'donefname': 'doneMonthly', 
		'failfname' :'failMonthly', 
		'alwaysfname':'none'
	};
	callMyAjax(params);
}
function doneMonthly(response){
	$('#divMonthlyDataEntry').html(response);
	$('#WORK_STATUS').focus();
	$('html, body').animate({scrollTop: $("#divMonthlyDataEntry").offset().top}, 1000);
}
function failMonthly(response){
	$('#divMonthlyDataEntry').html(response);
}
/**check it*/
function check_all_status(){
	if( $('#s1').val()=='Completed' && 
		$('#s2').val()=='Completed' && 
		$('#s3').val()=='Completed' && 
		$('#s4').val()=='Completed' &&
		$('#s5').val()=='Completed' && 
		$('#s6').val()=='Completed' && 
		$('#s7').val()=='Completed' && 
		$('#s8').val()=='Completed'){
			return 1;
	}else{
		return 0;
	}
}
//
function doneMonthlyData(response){
	$('#message').html( parseAndShowMyResponse(response) );
	gridReload();
	$('#divMonthlyDataEntry').html('');
}
//
function failMonthlyData(response){
	
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
function afterReload(){
	$('#divMonthlyDataEntry').html('');
}
function closeMonthly(){
	$('#divMonthlyDataEntry').html('');
}
//
var myProjectId, myDate;
function lockMonthly(project_id, dt){
	window.myProjectId = project_id; 
	window.myDate = dt;
	/*var res = confirm("अनलॉक के उपरान्त लॉक करना चाहते है \nतो कृपया मासिक प्रविष्टि को कम से कम एक बार सेव करना सुनिश्चित करें \n\n" + 
		" अगर आपने मासिक प्रविष्टि सेव नहीं किया है तो Cancel बटन दबाएं \nमासिक प्रविष्टि लॉक करने के लिए OK बटन दबाएं");
	if(res){*/
		var params = {
			'divid':'mySaveDiv',
			'url':'monthlyProgressCheck', 
			'data':{'project_id':project_id, 'lock_month':dt}, 
			'donefname': 'doneLockChecking', 
			'failfname' :'', 
			'alwaysfname':'none'
		};
		callMyAjax(params);
	//}
}
function doneLockChecking(response){
	if(response>110){
		alert('परियोजना में भौतिक प्रगति 110 प्रतिशत से ज्यादा है अतः इस माह के डाटा को लॉक नहीं किया जा सकता');
	}else{
		goForLock();
	}
}
function goForLock(project_id, month){
	var params = {
		'divid':'mySaveDiv',
		'url':'lockMonthly', 
		'data':{'project_id':window.myProjectId, 'lock_month':window.myDate}, 
		'donefname': 'doneLocking', 
		'failfname' :'', 
		'alwaysfname':'none'
	};
	callMyAjax(params);
}
function doneLocking(response){
	$('#message').html(response);
	gridReload();
}
function responsive_jqgrid(jqgrid) {
    jqgrid.find('.ui-jqgrid').addClass('clear-margin span12').css('width', '');
    jqgrid.find('.ui-jqgrid-view').addClass('clear-margin span12').css('width', '');
    jqgrid.find('.ui-jqgrid-view > div').eq(1).addClass('clear-margin span12').css('width', '').css('min-height', '0');
    jqgrid.find('.ui-jqgrid-view > div').eq(2).addClass('clear-margin span12').css('width', '').css('min-height', '0');
    jqgrid.find('.ui-jqgrid-sdiv').addClass('clear-margin span12').css('width', '');
    jqgrid.find('.ui-jqgrid-pager').addClass('clear-margin span12').css('width', '');
}
</script>
