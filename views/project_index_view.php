<script language="javascript" type="text/javascript" src="<?php  echo base_url().'js/clsIP.js';?>"></script>
<title>Promon <?php  echo ($this->session->userData('PROJECT_TYPE_ID')==1)? 'Minor':'Medium';?>  Project Setup - WRD MIS</title>
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
    	<?php 
				$message = '

<h1 style="color:#d03">नलकूप(Tubewell) एवं सूक्ष्म सिंचाई (Micro Irrigation) योजना से संबंधित Deposit के Promon बनाने संबंधी प्रक्रिया अभी प्रक्रियाधीन है
</h1>


<h1 style="color:#f03">
			<img src="/images/deposit.jpg" align="left" style="padding:4px;border:1px solid #f03">
				विभाग के स्वयं की संपत्ति अर्थात (बांध, नहर,जलाशय, स्टोरेज, फीडर, व्यपवर्तन, बाढ़ सुरक्षा योजना, नलकूप, एनीकट, उद्वहन सिंचाई योजना, बैराज, औद्योगिक बैराज, सूक्ष्म सिंचाई, स्टॉप डैम, औद्योगिक एनीकट, औद्योगिक स्टॉप डैम, नहर तंत्र, शीर्ष कार्य) पर किसी भी तरह के किये जाने वाले कार्य, जिसमें बजट उपलब्धता अन्य विभाग (कलेक्टर निधी, सांसद निधी, विधायक निधी आदि) के माध्यम (जमा मद) से की जा रही हो, उन कार्यो को डिपॉजिट प्रोमोन के अंतर्गत रिमॉडलिंग मे ही बनाना सूनिश्चित करें।</h1>';

				echo getMessageBox('message', $message);?>


    </div>
	<div style="width:100%;float:left" align="center">
	    <a href="<?php echo base_url();?>documents/help/promon__deposit_setup.pdf" 
			class="btn btn-primary" target="_blank">
			<span class="cus-page-white-acrobat"></span>
			Download Deposit Promon Setup Form (PDF File)
		</a>
    </div>
    <div style="width:100%;float:left;padding-bottom:5px;padding-top:5px" align="center">
        <table id="projectList"></table> 
        <div id="projectListPager"></div>
	</div>

	    <div style="width:100%;float:left;padding-bottom:5px;padding-top:5px" align="center">
        <table id="projectList1"></table> 
        <div id="projectList1Pager"></div>
	</div>

    <div style="width:100%;float:left;padding-top:10px" align="center">
        <table id="cprojectList"></table> 
        <div id="cprojectListPager"></div>
	</div>
    <div style="width:100%;float:left;padding-top:10px" align="center">
        <table id="dprojectList"></table> 
        <div id="dprojectListPager"></div>
      <?php  
		if ($this->session->userData('USER_ID')==23){
          echo  getButton('Lock Project', 'lockProject1()', 4)
          ;}
      ?>
	</div>
</div>
<!--<div id="modalBox2" style="z-index:10000"></div> -->
<!--End of Content -->
<script type="text/javascript">
var objOffice ;
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
	jQuery("#projectList").setGridParam({
		postData :{
			'CE_ID':$('#search_officeCE_ID').val(), 
			'SE_ID':$('#search_officeSE_ID').val(), 
			'EE_ID':$('#search_officeEE_ID').val(),
			'SDO_ID':$('#search_officeSDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		}, 
		page:1
	}).trigger('reloadGrid');
	jQuery("#cprojectList").setGridParam({
		postData :{
			'CE_ID':$('#search_officeCE_ID').val(), 
			'SE_ID':$('#search_officeSE_ID').val(), 
			'EE_ID':$('#search_officeEE_ID').val(),
			'SDO_ID':$('#search_officeSDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		}, 
		page:1
	}).trigger('reloadGrid');
	jQuery("#dprojectList").setGridParam({
		postData :{
			'CE_ID':$('#search_officeCE_ID').val(), 
			'SE_ID':$('#search_officeSE_ID').val(), 
			'EE_ID':$('#search_officeEE_ID').val(),
			'SDO_ID':$('#search_officeSDO_ID').val(), 
			'SEARCH_PROJECT_NAME':$('#SEARCH_PROJECT_NAME').val()
		}, 
		page:1
	}).trigger('reloadGrid');
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
			var gridName = '#' + ((ptype==0) ? "":"c") + "projectList";
			selectedRecordID = $(gridName).getGridParam("selrow");			
		 
			if(selectedRecordID>0 ){
				//var data = 'PROJECT_TYPE_ID=' + $("#projectTypeGrid").getGridParam("selrow");
				var id = $(gridName).getGridParam("selrow");
			    var ret = $(gridName).jqGrid('getRowData', id);		 
				
				var data = {'PROJECT_SETUP_ID':id,'PROJECT_ID':ret.PROJECT_ID};
				var showTitle = 'Edit Projects';
				//show_project_modalBox(id, ret.PROJECT_ID);
				showModalBox('modalBox', 'showProjectSetupEntryBox', data, showTitle, 'showProjectData', true, false);
				//alert("Edit"+data['PROJECT_ID']);
			} else{
				alert("Select row for edit");
			}
		}
	}
}
//
function projectOperation1(mode, ptype){
	if(mode==BUTTON_DELETE){
		//delete
		var id = $("#projectList1").getGridParam("selrow");
		if(id){
			var ret = $("#projectList1").jqGrid('getRowData', id); 									
			var values = {'PROJECT_SETUP_ID':id, 'oper':'del'};
			$.ajax({
				type:"POST",
				mtype:"POST",
				url:'deleteProject',
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
			var data = {'PROJECT_SETUP_ID':0};
			var title = 'Add New Project ';
			//modelBox('showEntryBox', data, title, 'auto', 'auto');	
			showModalBox('modalBox', 'showProjectSetupEntryBox', data, title, 'showProjectData', true);
			//show_project_modalBox(0, 0);
		}else{
			var gridName = '#' + ((ptype==0) ? "":"c") + "projectList1";
			selectedRecordID = $(gridName).getGridParam("selrow");
		
		 
			if(selectedRecordID>0){
				//var data = 'PROJECT_TYPE_ID=' + $("#projectTypeGrid").getGridParam("selrow");
					var id = $(gridName).getGridParam("selrow");		 
				var data = {'PROJECT_SETUP_ID':id,'PROJECT_ID':'0'};
				var showTitle = 'Edit Projects';
				//show_project_modalBox(id, ret.PROJECT_ID);
				showModalBox('modalBox', 'showProjectSetupEntryBox', data, showTitle, 'showProjectData', true, false);
				//alert("Edit"+data['PROJECT_ID']);
			} else{
				alert("Select row for edit");
			}
		}
	}
}
function showProjectData(msg){
	$('#modalBox').html(parseAndShowMyResponse(msg));
	//$('#modalBox').html(parseMyResponse(msg));
	centerDialog('modalBox');
}
//
function showFilter(PNAME){
	$('#SEARCH_CE_ID').val(0);
	$('#SEARCH_SE_ID').val(0);
	$('#SEARCH_EE_ID').val(0);
	
	$('#SEARCH_PROJECT_TYPE_ID').val(0);
	$('#SEARCH_PROJECT_NAME').val(PNAME);	
	gridReload();
}
//
function gridReload(){
	$("#projectList").jqGrid('setGridParam',{postData : {
		"SEARCH_CE_ID" : $('#SEARCH_CE_ID').val(), 
		"SEARCH_SE_ID" : $('#SEARCH_SE_ID').val(),
		"SEARCH_EE_ID" : $('#SEARCH_EE_ID').val(), 
		"SEARCH_SDO_ID" : $('#SEARCH_SDO_ID').val(),
		"SEARCH_PROJECT_NAME" : $('#SEARCH_PROJECT_NAME').val()
	}}).trigger("reloadGrid"); 

	$("#projectList1").jqGrid('setGridParam',{postData : {
		"SEARCH_CE_ID" : $('#SEARCH_CE_ID').val(), 
		"SEARCH_SE_ID" : $('#SEARCH_SE_ID').val(),
		"SEARCH_EE_ID" : $('#SEARCH_EE_ID').val(), 
		"SEARCH_SDO_ID" : $('#SEARCH_SDO_ID').val(),
		"SEARCH_PROJECT_NAME" : $('#SEARCH_PROJECT_NAME').val()
	}}).trigger("reloadGrid"); 
	
	$("#cprojectList").jqGrid('setGridParam',{postData : {
		"SEARCH_CE_ID" : $('#SEARCH_CE_ID').val(), 
		"SEARCH_SE_ID" : $('#SEARCH_SE_ID').val(),
		"SEARCH_EE_ID" : $('#SEARCH_EE_ID').val(), 
		"SEARCH_SDO_ID" : $('#SEARCH_SDO_ID').val(),
		"SEARCH_PROJECT_NAME" : $('#SEARCH_PROJECT_NAME').val()
	}, page:1}).trigger("reloadGrid"); 
}
</script>
