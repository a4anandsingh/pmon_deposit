<script language="javascript" type="text/javascript" src="<?php  echo base_url().'js/clsIP.js';?>"></script>
<!-- google map js -->
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGiaahoYYiYPC0wAk7OP9qPF1stxgK4Nw">
</script>
<title>Promon - Micro Irrigation Project Setup - WRD MIS</title>
<?php echo $office_list;?>
<!--Start of Content -->
<div id="content_wrapper">
    <div id="page_heading">
        <strong><?php echo $page_heading;?></strong>
    </div>
    <div style="width:100%;float:left">
        <div id="office_filter" class="messagebox"></div>
    </div>
    <div style="width:100%;float:left">
        <?php echo getMessageBox('message', $message);?>
    </div>
    <div style="width:100%;float:left" align="center">
        <a href="<?php echo base_url();?>documents/help/promon_minor_blank_format.pdf" 
            class="btn btn-primary" target="_blank">
            <span class="cus-page-white-acrobat"></span>
            Download Promon - Micro Irrigation Setup Form (PDF File)
        </a>
    </div>
    <div>
    	<?php echo $validMsg;?>    	
    </div>
    <div style="width:100%;float:left;padding-bottom:5px;padding-top:5px" align="center">
        <table id="projectList"></table> 
        <div id="projectListPager"></div>
    </div>
    <div style="width:100%;float:left;padding-top:10px" align="center">
        <table id="miProjectList"></table>
        <div id="miProjectListPager"></div>
    </div>
    <div style="width:100%;float:left;padding-top:10px" align="center">
        <table id="miProjectCList"></table>
        <div id="miProjectCListPager"></div>
    </div>
    <div style="width:100%;float:left;padding-top:10px" align="center">
        <table id="dprojectList"></table> 
        <div id="dprojectListPager"></div>
    </div>
</div>
<!--<div id="modalBox2" style="z-index:10000"></div> -->
<!--End of Content -->
<script type="text/javascript">
var objOffice;
$().ready(function(){
	showOfficeFilterBox();
});
search_office = new clsOffice();
sdo_search_office = new clsOffice();
/***/
function lockProject1(){
	var conf = true;//confirm("Do you want to Lock the Project Setup Record?");
	if(conf){
		var params = {
			'divid':'',
			'url':'lockProject', 
			'data':{'project_id':5408}, 
			'donefname': 'doneLockProject1', 
			'failfname' :'', 
			'alwaysfname':'none'
		};
		callMyAjax(params);
	}
}
function doneLockProject1(){}
//
function showOfficeFilterBox(){
	$.ajax({
		type:"POST",
		url:'showOfficeFilterBox',
		data:{'prefix':'search_office'},
		success:function(msg){
			$('#office_filter').html(msg);
			search_office.init();
			jqGrid_Projects();
		}
	});
}
//
function refreshSearch(){
	var projectStatus = $('#SEARCH_PROJECT_STATUS').val();
	var pdata = {
		'CE_ID':$('#search_officeCE_ID').val(),
		'SE_ID':$('#search_officeSE_ID').val(),
		'EE_ID':$('#search_officeEE_ID').val(),
		/*'SDO_ID':$('#search_officeSDO_ID').val(),*/
		'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
	};
	var pdata1 = pdata;
	xdata = {
		'SEARCH_PROJECT_STATUS':projectStatus,
		'SEARCH_PROJECT_STATUS':$('#SEARCH_PROJECT_STATUS').val(),
		'SEARCH_PROJECT_TYPE':$('#SEARCH_PROJECT_TYPE').val()
	};
	$.extend(true, pdata1, xdata);
	jQuery("#projectList").setGridParam({postData:pdata1, page:1}).trigger('reloadGrid');
	jQuery("#miProjectCList").setGridParam({postData:pdata, page:1}).trigger('reloadGrid');
	jQuery("#miProjectList").setGridParam({postData:pdata, page:1}).trigger('reloadGrid');
}
//
function jqGrid_Projects(){
	<?php echo $project_grid;?>
}
//
function projectOperation(mode, ptype){
	if(mode==BUTTON_DELETE){
		//delete
		var id = $("#projectList").getGridParam("selrow");
		if(id){
			var ret = $("#projectList").jqGrid('getRowData', id); 									
			var values = {'PROJECT_SETUP_ID':id, 'PROJECT_ID':ret.PROJECT_ID, 'oper':'del'};
			$.ajax({
				type:"POST",
				mtype:"POST",
				url:'deleteProject',
				data:values,
				success:function(msg){
					$('#message').html(msg);
					gridReload(1);
				}
			});
		}else{
		    alert("Please Select Row To Delete");
		}
	}else{
		if(mode==BUTTON_ADD_NEW){
            var gridName = '#' + ((ptype==0) ? "":"c") + "projectList";
            selectedRecordID = $(gridName).getGridParam("selrow");
            if(selectedRecordID>0){
                var id = $(gridName).getGridParam("selrow");
                var ret = $(gridName).jqGrid('getRowData', id);
                var data = {'PARENT_PROJECT_ID':id,'PROJECT_SETUP_ID':0};
                //alert(id+ ' ------------- '+ ret.PROJECT_ID);return;
                var showTitle = 'Add Micro Irrigation Project';
                //show_project_modalBox(id, ret.PROJECT_ID);
                showModalBox('modalBox', 'showProjectSetupEntryBox', data, showTitle, 'showProjectData', true, false);
                //alert("Edit"+data['PROJECT_ID']);
            }else{
                alert("Select a row to add new Minor Irrigation Project.");
            }
            //var data = {'PROJECT_SETUP_ID':0, 'PROJECT_ID':0};
			//var title = 'Add New Project ';
			//modelBox('showEntryBox', data, title, 'auto', 'auto');	
			//showModalBox('modalBox', 'showProjectSetupEntryBox', data, title, 'showProjectData', true);
			//show_project_modalBox(0, 0);
		}else{
			var gridName = "#miProjectList";
			selectedRecordID = $(gridName).getGridParam("selrow");
			if(selectedRecordID>0){
				//var data = 'PROJECT_TYPE_ID=' + $("#projectTypeGrid").getGridParam("selrow");
				var id = $(gridName).getGridParam("selrow");
				var ret = $(gridName).jqGrid('getRowData', id);
				var data = {'PROJECT_SETUP_ID':id,'PARENT_PROJECT_ID':ret.PARENT_PROJECT_ID};
				var showTitle = 'Edit Micro Irrigation Projects';
				//alert(id);
				//show_project_modalBox(id, ret.PROJECT_ID);
				showModalBox('modalBox', 'showProjectSetupEntryBox', data, showTitle, 'showProjectData', true, false);
				//alert("Edit"+data['PROJECT_ID']);
			}else{
				alert("Select row for edit");
			}
		}
	}
}
function addNewMicroProject(parentProjectId){
    //var gridName = '#' + ((ptype==0) ? "":"c") + "projectList";
    //selectedRecordID = $(gridName).getGridParam("selrow");
    //if(selectedRecordID>0){
        //var id = $(gridName).getGridParam("selrow");
        //var ret = $(gridName).jqGrid('getRowData', id);
		var data = {'PARENT_PROJECT_ID':parentProjectId,'PROJECT_SETUP_ID':0};
		var showTitle = 'Add Micro Irrigation Project';
		showModalBox('modalBox', 'showProjectSetupEntryBox', data, showTitle, 'showProjectData', true, false);
    //}
}
function showProjectData(msg){
	$('#modalBox').html(parseAndShowMyResponse(msg));
	//$('#modalBox').html(parseMyResponse(msg));
	$( "#modalBox" ).dialog( "option", "width", 1000);
	centerDialog('modalBox');
	
}
function showFilter(PNAME){
	$('#SEARCH_CE_ID').val(0);
	$('#SEARCH_SE_ID').val(0);
	$('#SEARCH_EE_ID').val(0);
	
	$('#SEARCH_PROJECT_TYPE_ID').val(0);
	$('#SEARCH_PROJECT_NAME').val(PNAME);	
	gridReload();
}
function gridReload(mode){
	var pdata = {
		"SEARCH_CE_ID" : $('#SEARCH_CE_ID').val(), 
		"SEARCH_SE_ID" : $('#SEARCH_SE_ID').val(),
		"SEARCH_EE_ID" : $('#SEARCH_EE_ID').val(), 
		/*"SEARCH_SDO_ID" : $('#SEARCH_SDO_ID').val(),*/
		"SEARCH_PROJECT_NAME" : $('#SEARCH_PROJECT_NAME').val()
	};
	if(mode)
		var gparam = {postData : pdata};
	else
		var gparam = {postData : pdata, page:1};
	
	$("#projectList").jqGrid('setGridParam',gparam).trigger("reloadGrid"); 
    $("#miProjectList").jqGrid('setGridParam',gparam).trigger("reloadGrid");
    $("#miProjectCList").jqGrid('setGridParam',gparam).trigger("reloadGrid");
}

var globalFileExistsMode='';
function checkAaRaafileExists(mode, projectId){
    //mode1= AA , mode2=RAA
    var filename = '';
    if(mode==1){
    	filename="AA_SCAN_COPY";
    	$('#msg_aa_file').html('');
    }else if (mode==2){
    	$('#msg_raa_file').html('');
    	filename="RAA_SCAN_COPY";
    }
    var userFile = $('#'+filename).val().replace(/.*(\/|\\)/, '');    
    globalFileExistsMode = mode;
    var params = {
        'divid':'',
        'url':'checkAaRaafileExists',
        'data':{'PROJECT_SETUP_ID':projectId,'mode':mode,'filename':userFile},
        'donefname': 'doneCheckFile',
        'failfname' :'none',
        'alwaysfname':'none'
    };
    callMyAjax(params);
}
function doneCheckFile(data){
    var mydata = parseAndShowMyResponse(data);
    if(globalFileExistsMode ==1) {
        $('#msg_aa_file').html(mydata);
        if(data!=''){
        	$('#AA_SCAN_COPY').val('');
        }
        /*$('#aa_button_div').hide();
        $('#aa_upload_div').show();*/
    }else{
        $('#msg_raa_file').html(mydata);
        if(data!=''){
        	$('#RAA_SCAN_COPY').val('');
        }
        /*$('#raa_button_div').hide();
        $('#raa_upload_div').show();*/
    }
}
</script>