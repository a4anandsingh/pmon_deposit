<title>Promon - Project Entry Reports - WRD MIS</title>
<style type="text/css">
	#tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
</style>
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
        <span class='cus-eye'></span> On-going &nbsp; &nbsp; &nbsp; 
         <span class='cus-thumb-up'></span> Completed &nbsp; &nbsp; &nbsp; 
         <span class='cus-cancel'></span> Dropped &nbsp; &nbsp; &nbsp; 
        <table id="projectList"></table> 
        <div id="projectListPager"></div>
	</div>
	<form name="all_reports" id="all_reports" onsubmit="return false">
	<input type="hidden" value="0" id="PROJECT_ID" name="PROJECT_ID" >
	<input type="hidden" value="0" id="PROJECT_SETUP_ID" name="PROJECT_SETUP_ID" >
	<input type="hidden" value="0" id="IS_MI" name="IS_MI" >
	<div style="width:100%;float:left" class="ui-widget-content">
  	    <div class="ui-widget-content" style="padding:5px;overflow:hidden">
        	<?php
			$arrChoices = array(
				array('id'=>'setup', 'title'=>'Project Setup', 'url'=>'printSetup'),
				array('id'=>'target', 'title'=>'Physical & Financial Target', 'url'=>'printTarget'),
				array('id'=>'raa', 'title'=>'RAA/TS/Exra Qty', 'url'=>'printRAA'),
				array('id'=>'monthly', 'title'=>'Monthly Data', 'url'=>'printMonthly'),
				//array('id'=>'blank_monthly', 'title'=>'Blank Monthly Data', 'url'=>'printMonthly'),
				array('id'=>'mp', 'title'=>'Monthly Progress', 'url'=>'printMP')
			);?>
    	<!--<div id="report_td" class="ui-widget-content">-->
            <table width="100%" border="0" cellpadding="8" cellspacing="2" class="ui-widget-content">
            <tr>
                <td colspan="3" class="ui-state-default">
                    <input type="checkbox" id="select_all" name="select_all" class="caseall css-checkbox">
                    <label for="select_all"  class="css-label lite-green-check"><strong> Select all reports</strong></label>
                </td>
            </tr>
            <tr>
				<td class="ui-widget-content" valign="middle" width="33%">
					<input type="checkbox" id="setup" name="setup" class="css-checkbox case">
					<label for="setup" class="css-label lite-red-check">
                    	<strong>Project Setup</strong>
					</label>
				</td>
                <td class="ui-widget-content" valign="middle" style="font-size:15px" colspan="2">
					<input type="checkbox" id="target" name="target" class="css-checkbox case">
					<label for="target" class="css-label lite-red-check">
                    	<strong>Physical & Financial Target :</strong>
					</label>
                     <select name="session" id="session" class="select2" style="width:160px">
					</select>
				  </td>
    	          <!-- <td class="ui-widget-content" valign="middle" style="font-size:15px">
					<input type="checkbox" id="raa" name="raa" class="css-checkbox case">
					<label for="raa" class="css-label lite-red-check">
                    	<strong>RAA/TS/Exra Qty :</strong>
					</label>
                     <select name="raaid" id="raaid" class="select2" style="width:260px">
					</select>
				  </td> -->
              </tr>
               <tr>
              <td valign="middle" nowrap="nowrap" class="ui-widget-content" style="font-size:15px">
					<input type="checkbox" id="monthly" name="monthly" class="css-checkbox case" value="1">
					<label for="monthly" class="css-label lite-red-check">
                    	<strong>Monthly Data : </strong>
					</label>
                     <select name="month" id="month" class="select2" style="width:159px" onchange="setMyValue()">
					</select>
			  </td>
               <!--<td class="ui-widget-content" valign="middle" style="font-size:15px">
					<input type="checkbox" id="blank_monthly" name="blank_monthly" class="css-checkbox case" value="1">
					<label for="blank_monthly" class="css-label lite-red-check">
                    	<strong>Monthly Blank Data Format : </strong>
					</label>
                    <select name="blank_month" id="blank_month" class="select2" style="width:159px">
					</select>
			  </td>-->
              <td class="ui-widget-content" valign="middle" style="font-size:15px" colspan="2">
					<input type="checkbox" id="mp" name="mp" class="css-checkbox case">
					<label for="mp" class="css-label lite-red-check">
                    	<strong>Progress: </strong>
					</label>
					<input type="hidden" id="dt" name="dt" value="0" />
			  </td>
              </tr>
           	<tr>
               <td colspan="5" class="mysavebar" align="right">
                	<div id="modalBox2">
                	<button onmouseover="$(this).addClass('ui-state-hover');" id="add_tab"  style="height:35px"
                    	onmouseout="$(this).removeClass('ui-state-hover');" class="fm-button ui-state-default ui-corner-all">
                        <i class="cus-report-magnify"></i>
                        Generate Report
                    </button>
                	<button  onmouseover="$(this).addClass('ui-state-hover');" id="remove_all_tab"  style="height:35px"
                    	onmouseout="$(this).removeClass('ui-state-hover');" class="fm-button ui-state-default ui-corner-all">
                        <i class="cus-delete"></i> Close All Tabs
                    </button>
                    </div>
				</td>
            </tr>
            </table>
		</div>
	</div>
    </form>
</div>
<!--End of Content -->
<script type="text/javascript">
var objOffice ;
<?php
	$arrTitles = array();
	$arrUrl = array();
	$arrIds = array();
	foreach($arrChoices as $choice){
		array_push($arrTitles, "'".$choice['title']."'");
		array_push($arrUrl, "'".$choice['url']."'");
		array_push($arrIds, "'".$choice['id']."'");
	}
	echo 'var reportTitles = new Array('.implode(',', $arrTitles).');';
	echo 'var mURL = new Array('.implode(',', $arrUrl).');';
	echo 'var reportID = new Array('.implode(',', $arrIds).');';
?>
$().ready(function(){
	$('.select2').select2();
	var tabTitle = $( "#tab_title" );
	var tabContent = $( "#tab_content" );
	var tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>";
	var tabCounter = 1;
	var tabs = $( "#tabs" ).tabs();
	
	function addTab() {
		var label = tabTitle.val() || "Tab " + tabCounter,
			id = "tabs-" + tabCounter,
			li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
			tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";

		tabs.find( ".ui-tabs-nav" ).append( li );
		tabs.append( "<div id='" + id + "'><p>" + tabContentHtml + "</p></div>" );
		tabs.tabs( "refresh" );
		tabCounter++;
	}
	// close icon: removing the tab on click
	tabs.delegate( "span.ui-icon-close", "click", function() {
		var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
		$( "#" + panelId ).remove();
		tabs.tabs( "refresh" );
	});
	tabs.bind( "keyup", function( event ) {
		if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
			var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
			$( "#" + panelId ).remove();
			tabs.tabs( "refresh" );
		}
	});
	$('#remove_all_tab').on("click", null, function() {
		removeAllTabs('tabs');
		tabCounter = 1;
	});
	/** Report Generation */
	$( "#add_tab" ).on("click", null, function() {		
		/*console.log($('#PROJECT_ID').val());
		console.log($('#PROJECT_SETUP_ID').val());
		console.log($('#IS_MI').val());*/

		var projectId= $('#PROJECT_ID').val() ;
		var projectSetupId = $('#PROJECT_SETUP_ID').val();
		var isMi= $('#IS_MI').val();

		// if condition to check whether selected project is micro irrigation or Tubewell;
		if(isMi==1){
			if($('#PROJECT_SETUP_ID').val()==0){
				alert("Please Select a Project to continue");
				return;
			}
		}else{
			if($('#PROJECT_ID').val()==0){
				alert("Please Select a Project to continue");
				return;
			}
		}
		//session
		if($('#'+reportID[1] ).prop('checked')){
			if($('#session').val()==""){
				alert('Select Session...');
				return;
			}
		}
			
		if($( '#'+reportID[3] ).prop('checked')){
			if($('#month').val()==""){
				alert('Select Month...');
				return;
			}
		}
		if($( '#'+reportID[4] ).prop('checked')){
			if($('#blank_month').val()==""){
				alert('Select Month...');
				return;
			}
		}
		tab_counter = 1;
		getLoadingMessage('modalBox2', true);
		removeAllTabs('tabs');
		tabCounter = 1;
		//$tabs.tabs( "remove", index);
		//var selected = $( "#tabs" ).tabs( "option", "selected" );
		//var n = $("input:checked").length;
		var n = $("input:checked").length;
		if (n==0){
			alert("Please Select A Report");
		}else{
			//var mURL = new Array("printSetup", "printTarget","printMonthly");
			//var reportID = new Array("setup", "target", "monthly");
			var data = $('#all_reports').serialize();
			for(iRCount=0; iRCount<mURL.length; iRCount++){
				if ($('#'+reportID[iRCount] ).prop('checked')){
					$.ajax({
						async:false,
						type:"POST",
						url: mURL[iRCount],
						data : $('#all_reports').serialize() + '&xno='+iRCount,
						success:function(msg){
							var arrData = msg.split('####');
							//alert('reportTitles[' + arrData[0] + ']=' + reportTitles[arrData[0]]);
							$( "#tab_title").val( reportTitles[arrData[0]] );
							$( "#tab_content").val(parseAndShowMyResponse(arrData[1]));
							addTab();
						}
					});
				}
			}
		}
		getLoadingMessage('modalBox2', false);
		tabs.tabs({active:0});
	});
	/** */
	showOfficeFilterBox();
	// ## IF OFFICE SEARCH BOX NEED 
	//var para = 'FILE=' + 'all_report_search_view';
	//report_view	
	$('#modalBox').dialog({ modal:true, autoOpen:false, position:'center' });
		// add multiple select / deselect functionality
	$("#select_all").click(function () {
		var mystatus = $("#select_all").prop('checked');
		//var ss = mystatus + ':';
		$('.case').each(function(){
		//	ss += $(this).attr('id') +  ' - ';
			$(this).prop('checked', mystatus);
		});
	});
	$("#select_all_2").click(function () {
		  $('.case').attr('checked', this.checked);
	});
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".case").click(function(){
		if($(".case").length == $(".case:checked").length) {
			$("#selectall").attr("checked", "checked");
			$("#select_all_2").attr("checked", "checked");
		}else{
			$("#selectall").removeAttr("checked");
			$("#select_all_2").removeAttr("checked");
		}
    });
});
search_office = new clsOffice();
sdo_search_office = new clsOffice();
/** OK */
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
var blankDate = '';
function setMyValue(){
	//console.log('aaaaaaa:'+$('#monthId option:selected').attr('label'));
	if($('#month option:selected').attr('label')==1){
		$('#dt').val(window.blankDate);
	}else{
		$('#dt').val('');
	}
	//console.log('mydt :' + $('#dt').val() );
}

/** OK */
function refreshSearch(){
	jQuery("#projectList").setGridParam({
		postData :{
			'CE_ID':$('#search_officeCE_ID').val(), 
			'SE_ID':$('#search_officeSE_ID').val(), 
			'EE_ID':$('#search_officeEE_ID').val(),
			'SDO_ID':$('#search_officeSDO_ID').val(), 
			'SEARCH_PROJECT_STATUS':$('#SEARCH_PROJECT_STATUS').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		}
	}).trigger('reloadGrid');
}
/**OK*/
function jqGrid_Projects(){
	<?php echo $project_list;?>
	jQuery("#projectList").jqGrid('setGroupHeaders', {
		useColSpanStyle:true, 
		groupHeaders:[
			{startColumnName:'LOCKED', numberOfColumns: 3, titleText: 'Locked'}
		]	
	});
}
/**OK*/
function projectOperation(mode){
	if(mode==BUTTON_DELETE){
		//delete
		var id = $("#projectList").getGridParam("selrow");
		if(id){
			var ret = $("#projectList").jqGrid('getRowData', id); 									
			var values = {'PROJECT_SETUP_ID':id, 'PROJECT_ID':ret.PROJECT_ID, 'oper':'del'};
			$.ajax({
				type:"POST",
				mtype:"POST",
				url:'saveProjectSetupData',
				data:values,
				success:function(msg){
					$('#message').html(msg);
					gridReload();
				}
			});
		}else{
		alert("Please Select Row To Delete");
		}
	}else{
		if(mode==BUTTON_ADD_NEW){
			var data = {'PROJECT_SETUP_ID':0, 'PROJECT_ID':0};
			var title = 'Add New Project ';
			//modelBox('showEntryBox', data, title, 'auto', 'auto');	
			showModalBox('modalBox', 'showProjectSetupEntryBox', data, title, 'showProjectData', true);
			//show_project_modalBox(0, 0);
		}else{
			if($("#projectList").getGridParam("selrow")>0){
				//var data = 'PROJECT_TYPE_ID=' + $("#projectTypeGrid").getGridParam("selrow");
				var id = $("#projectList").getGridParam("selrow");
				var ret = $("#projectList").jqGrid('getRowData', id); 									
				var data = {'PROJECT_SETUP_ID':id,'PROJECT_ID':ret.PROJECT_ID};
				var showTitle = 'Edit Projects';
				//show_project_modalBox(id, ret.PROJECT_ID);
				showModalBox('modalBox', 'showProjectSetupEntryBox', data, showTitle, 'showProjectData', true, false);
	//	showModalBox('modalBox', 'showUserClassEntryBox', {"USER_CLASS_ID":app_id}, showTitle, 'afterEntryBox', true, false);
				//alert("Edit"+data['PROJECT_ID']);
			}else{
				alert("Select row for edit");
			}
		}
	}
}
/**OK*/
function showProjectData(msg){
	$('#modalBox').html(parseAndShowMyResponse(msg));
	//$('#modalBox').html(parseMyResponse(msg));
	centerDialog('modalBox');
}
/** */
function showFilter(PNAME){
	$('#SEARCH_CE_ID').val(0);
	$('#SEARCH_SE_ID').val(0);
	$('#SEARCH_EE_ID').val(0);
	
	$('#SEARCH_PROJECT_TYPE_ID').val(0);
	$('#SEARCH_PROJECT_NAME').val(PNAME);	
	gridReload();
}
/** */
function gridReload(){
	$("#projectList").jqGrid('setGridParam',{postData : {
		"SEARCH_CE_ID" : $('#SEARCH_CE_ID').val(), 
		"SEARCH_SE_ID" : $('#SEARCH_SE_ID').val(),
		"SEARCH_EE_ID" : $('#SEARCH_EE_ID').val(), 
		"SEARCH_SDO_ID" : $('#SEARCH_SDO_ID').val(),
		'SEARCH_PROJECT_STATUS':$('#SEARCH_PROJECT_STATUS').val(), 
		"SEARCH_PROJECT_NAME" : $('#SEARCH_PROJECT_NAME').val()
		}, page:1}).trigger("reloadGrid"); 
}
function checkjs(){
	var x = {	type:"POST",	mdiv:"Faltu", url:'murl', data:'mdata'}
	alert(x.mdiv);
}
function printData(prjID){
	var data = {'PROJECT_ID':prjID};
	var title = 'Print Data';
	showModalBox('modalBox2', 'printData', data, title, 'showProjectDataPrint', true);
}
function showProjectDataPrint(msg){
	$('#modalBox2').html(parseAndShowMyResponse(msg));
	//$('#modalBox').html(parseMyResponse(msg));
	centerDialog('modalBox2');
}
function fillProjectId(){
	setLoadingStatus(true, 'target');
	var id = $("#projectList").getGridParam("selrow");
	if(id){
		var ret = $("#projectList").jqGrid('getRowData', id);
		var projectSetupId = ret.PROJECT_SETUP_ID
		var projectId =ret.PROJECT_ID;
		var isMi = ret.IS_MI;
		
		$('#PROJECT_ID').val(ret.PROJECT_ID);
		$('#PROJECT_SETUP_ID').val(ret.PROJECT_SETUP_ID);
		$('#IS_MI').val(ret.IS_MI);
		
		if(isMi==1){
			reloadOptionsMi(projectSetupId);
		}else{
			reloadOptions(projectId);
		}
	}
}

//new functions
function reloadOptions(projectId){
		$.ajax({
			type:"POST",
			mtype:"POST",
			url:'getTargetSessionOptions',
			data:{'PROJECT_ID':projectId},
			success:function(msg){
				$('#session').html(msg);
				$("#session").trigger("updatecomplete");
				$("#session").select2("val", "");
				setLoadingStatus(false, 'target');
			}
		});
		setLoadingStatus(true, 'monthly');
		//get months
		$.ajax({
			type:"POST",
			mtype:"POST",
			url:'getMonthlyOptions',
			data:{'PROJECT_ID':projectId},
			success:function(msg){
				var res = msg.split("##");
				$('#month').html(res[0]);
				$("#month").trigger("updatecomplete");
				$("#month").select2("val", "");
				res = res.join("");
				$('#blank_month').html(res);
				//$("#blank_month").trigger("updatecomplete");
				//$("#blank_month").select2("val", "");
				setLoadingStatus(false, 'monthly');
			}
		});		
		/*setLoadingStatus(true, 'raa');
		//get months
		$.ajax({
			type:"POST",
			mtype:"POST",
			url:'getRAAList',
			data:{'PROJECT_ID':projectId},
			success:function(msg){
				$('#raaid').html(msg);
				$("#raaid").trigger("updatecomplete");
				$("#raaid").select2("val", "");
				setLoadingStatus(false, 'raa');
			}
		});	*/
}

function reloadOptionsMi(projectId){
		$.ajax({
			type:"POST",
			mtype:"POST",
			url:'getTargetSessionOptionsMi',
			data:{'PROJECT_SETUP_ID':projectId},
			success:function(msg){
				$('#session').html(msg);
				$("#session").trigger("updatecomplete");
				$("#session").select2("val", "");
				setLoadingStatus(false, 'target');
			}
		});
		setLoadingStatus(true, 'monthly');
		//get months
		$.ajax({
			type:"POST",
			mtype:"POST",
			url:'getMonthlyOptionsMi',
			data:{'PROJECT_SETUP_ID':projectId},
			success:function(msg){
				var res = msg.split("##");
				$('#month').html(res[0]);
				$("#month").trigger("updatecomplete");
				$("#month").select2("val", "");
				res = res.join("");
				//$('#blank_month').html(res);
				//$("#blank_month").trigger("updatecomplete");
				//$("#blank_month").select2("val", "");
				setLoadingStatus(false, 'monthly');
			}
		});
		/*
		commenting this function because RAA in not implemented 10-05-2019
		setLoadingStatus(true, 'raa');
		//get months
		$.ajax({
			type:"POST",
			mtype:"POST",
			url:'getRAAListMi',
			data:{'PROJECT_SETUP_ID':projectId},
			success:function(msg){
				$('#raaid').html(msg);
				$("#raaid").trigger("updatecomplete");
				$("#raaid").select2("val", "");
				setLoadingStatus(false, 'raa');
			}
		});	*/
}

function checkReportMonth(){
	var mCYear = parseInt($('#curYear').val());
	var mYear = parseInt($('#S_YEAR').val());
	var mCMonth = parseInt($('#curMon').val());
	var mSelectedMonth = parseInt($('#S_MONTH').val());
	if(mCYear==mYear){
		if(mSelectedMonth>mCMonth){
			return false;
		}
	}
	return true;
}
</script>