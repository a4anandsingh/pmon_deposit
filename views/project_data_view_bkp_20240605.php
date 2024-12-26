<?php $projectId = $projectSetupValues['PROJECT_ID'];
$editMode = (($projectId) ? true:false);?>
<div id="projectcodehelp" style="display:none;position:absolute;left:120px;top:80px;z-index:10000;border:1px solid #03F; box-shadow:#069">
	<img src="<?php echo base_url().'assets/images/projectcode.jpg';?>" />
</div>
<form name="frmProject" id="frmProject" onsubmit="return false;">
<input type="hidden" name="PROJECT_ID" id="PROJECT_ID" value="<?php echo $projectId;?>">
<input type="hidden" name="saveMode" id="saveMode" value="<?php echo ($projectId)? 2:0;?>">
<input type="hidden" name="mi_pmon_type" id="mi_pmon_type" value="0">
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1" style="margin-bottom:9px">
<tr>
    <td colspan="3" align="center" class="ui-widget-content">
		<?php echo getRequiredSign('');?> = Mandatory Field(जानकारी जरूरी है)
	<br />
	<span class="cus-tag-pink"></span> Site = Starting Point of the Work</td>
</tr>
<?php if($PROJECT_TYPE_ID==3){?>
<tr>
  <td align="center" class="ui-widget-content" colspan="3" style="font-size:15px; font-weight:bold">
  <strong>Major Project (वृहद् परियोजना) : </strong>
	<?php if($editMode) {
		echo $MAJOR_PROJECT_NAME;?>
		<input type="hidden" name="MAJOR_PROJECT_ID" id="MAJOR_PROJECT_ID" 
        value="<?php echo $MAJOR_PROJECT_ID;?>" />
  <?php }else{ ?>
  <select name="MAJOR_PROJECT_ID" id="MAJOR_PROJECT_ID" onchange="showAA(this.value);"
  	class="chosen-select" style="width:450px" >
    <option value="0">Select</option>
  <?php echo $MAJOR_PROJECT_LIST;?>
  </select>
  <?php }?>
  </td>
</tr>
<?php }?>
<tr>
	<td width="120" align="left" nowrap="nowrap" class="ui-state-default">
  		<strong>Project Code 
        <a href="javascript:void(0)" onmouseover="$('#projectcodehelp').show();" onmouseout="$('#projectcodehelp').hide();" >
        <span><i class="cus-help"></i></span></a></strong>
	</td>
    <td colspan="2" align="left" class="ui-widget-content"><?php 
	echo ($projectSetupValues['PROJECT_CODE']=='') ? 
		'Automatically Generated': $projectSetupValues['PROJECT_CODE'];
	?></td>
    </tr>
<tr>
  <td align="left" class="ui-widget-content"><strong>Project Name</strong></td>
  <td colspan="2" align="left" class="ui-widget-content"><?php echo getRequiredSign('');?>
    <input name="PROJECT_NAME" id="PROJECT_NAME" type="text" maxlength="255" style="width:95%"
        	 value="<?php echo $projectSetupValues['PROJECT_NAME'];?>" class="" /></td>
  </tr>
<tr>
  <td align="left" class="ui-widget-content"><strong>परियोजना का नाम</strong></td>
  <td colspan="2" align="left" class="ui-widget-content"><?php echo getRequiredSign('');?>
    <input name="PROJECT_NAME_HINDI" id="PROJECT_NAME_HINDI" type="text"  style="width:95%" maxlength="255"
        	value="<?php echo $projectSetupValues['PROJECT_NAME_HINDI'];?>" class="" /></td>
  </tr>
<tr>
  <td align="left" class="ui-widget-content"><strong>Sub Category</strong></td>
  <td colspan="2" align="left" class="ui-widget-content"><?php echo getRequiredSign('');?>
	  <input type="hidden" id="PROJECT_TYPE_ID" name="PROJECT_TYPE_ID" 
       		value="<?php echo $PROJECT_TYPE_ID;?>" />
  	<?php if(!$editMode){?>
    <select data-placeholder="Choose a Project Sub Type..." style="width:95%"
       	id="PROJECT_SUB_TYPE_ID" name="PROJECT_SUB_TYPE_ID" class="chosen-select">
      <option value="" >Select Sub Type</option>
      <?php echo $PROJECT_SUB_TYPE_LIST;?>
      </select>
  	<?php }else{?>
    <input id="PROJECT_SUB_TYPE_ID" name="PROJECT_SUB_TYPE_ID" 
    	type="hidden" value="<?php echo $projectSetupValues['PROJECT_SUB_TYPE_ID'];?>" />
  	<?php echo $projectSubType;
	}?>
	</td>
</tr>
</table>
<?php if(!$editMode){?>
<table border="0" cellpadding="2" cellspacing="1" class="ui-widget-content" width="100%">
  <tr>
    <td width="20" rowspan="2" align="center" class="ui-state-default"><strong><?php echo getRequiredSign('');?></strong>District (Site)</td>
    <td colspan="3" align="center" class="ui-state-default"><strong><?php echo getRequiredSign('');?>Longitude (Site)</strong></td>
    <td colspan="3" align="center" class="ui-state-default"><strong><?php echo getRequiredSign('');?>Latitude (Site)</strong></td>
  </tr>
  <tr>
    <td width="20" align="center" class="ui-state-default">D</td>
    <td width="20" align="center" class="ui-state-default">M</td>
    <td width="20" align="center" class="ui-state-default">S</td>
    <td width="20" align="center" class="ui-state-default">D</td>
    <td width="20" align="center" class="ui-state-default">M</td>
    <td width="20" align="center" class="ui-state-default">S</td>
  </tr>
  <tr>
    <td align="center" valign="top" class="ui-widget-content">
    <select class="chosen-select" style="width:280px" name="HEAD_WORK_DISTRICT_ID" id="HEAD_WORK_DISTRICT_ID">
      <?php echo $DIST_HEAD;?>
    </select></td>
    <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LONGITUDE_D" id="LONGITUDE_D" 
            size="3" maxlength="2" class="positive-integerLong centertext" 
            value="<?php echo $projectSetupValues['LONGITUDE_D'];?>"
        /></td>
    <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LONGITUDE_M" id="LONGITUDE_M" 
            size="3" maxlength="2"  class="positive-integerM centertext" 
            value="<?php echo $projectSetupValues['LONGITUDE_M'];?>"
            /></td>
    <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LONGITUDE_S" id="LONGITUDE_S" 
            size="3" maxlength="2" class="positive-integerM centertext" 
            value="<?php echo $projectSetupValues['LONGITUDE_S'];?>" 
             /></td>
    <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LATITUDE_D" id="LATITUDE_D" 
            size="3" maxlength="2" class="positive-integerLat centertext" 
            value="<?php echo $projectSetupValues['LATITUDE_D'];?>" 
             /></td>
    <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LATITUDE_M" id="LATITUDE_M"
             size="3" maxlength="2" class="positive-integerM centertext" 
             value="<?php echo $projectSetupValues['LATITUDE_M'];?>" 
             /></td>
    <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LATITUDE_S" id="LATITUDE_S" 
            size="3" maxlength="2" class="positive-integerM centertext" 
            value="<?php echo $projectSetupValues['LATITUDE_S'];?>"
             /></td>
  </tr>
</table>
<div id="divCheckCode" class="ui-widget-header" style="text-align:center;font-size:18px;display:none"></div>
<?php } 
$readOnly = (($PROJECT_TYPE_ID==3)? 'readonly="readonly"': '');?>
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
<tr>
    <td width="100%" valign="top" colspan="2">
        <table>
             <tr>
                 <td  class="ui-state-default"><?php echo getRequiredSign('right');?><strong>Select Deposit Scheme:</strong></td>
                    <td nowrap="nowrap"  class="ui-widget-content">
                        <select name="DEPOSIT_SCHEME_ID" id="DEPOSIT_SCHEME_ID"  class=" chosen-select required" onchange="showHead('DEPOSIT_SCHEME_ID')">
                            <?php echo $DEPOSIT_SCHEME;?>
                        </select>
                        <label style="color: red;" id="DEPOSIT_SCHEME_HEAD"> </label>
                    </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
	<td width="50%" valign="top"  >
		<table width="100%" cellpadding="3" cellspacing="1" class="ui-widget-content">
        <tr>
        	<td colspan="2" class="ui-state-default" align="center" style="line-height:20px;">
            	<strong>Administrative Approval</strong>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="ui-widget-content"><?php echo getRequiredSign('right');?><strong>AA No :</strong></td>
            <td class="ui-widget-content">
            	<input name="AA_NO" id="AA_NO" type="text" size="40" maxlength="50"  
                	value="<?php echo $projectSetupValues['AA_NO'];?>" class="" <?php echo $readOnly;?> />
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="ui-widget-content">
				<?php echo getRequiredSign('right');?><strong>Date :  </strong>
            </td>
            <td class="ui-widget-content">
            	<input  name="AA_DATE" id="AA_DATE" type="text" size="14" maxlength="10" 
                	onchange="checkForStatus();"  style="text-align:center"
                	value="<?php echo myDateFormat($projectSetupValues['AA_DATE']);?>"  <?php echo $readOnly;?>/>
                     <br />
					(dd-mm-yyyy) e.g. 02-12-2013 for 2<sup>nd</sup> Dec 2013
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="ui-widget-content">
				<?php echo getRequiredSign('right');?><strong>Authority :</strong></td>
            <td class="ui-widget-content">
            	<select name="AA_AUTHORITY_ID" id="AA_AUTHORITY_ID" style="width:213px;"  <?php echo $readOnly;?>
                	 class=" chosen-select">
                	<option value="" >Select Authority</option>
                    <?php echo implode('', $AUTH_VALUES);?>
                </select>
			</td>
        </tr>
		<tr>
            <td nowrap="nowrap" class="ui-widget-content">
				<?php echo getRequiredSign('right');?><strong>Amount :</strong>
            </td>
            <td class="ui-widget-content">
            	<input name="AA_AMOUNT" id="AA_AMOUNT" type="text" size="14"  <?php echo $readOnly;?>
                	maxlength="20" value="<?php echo $projectSetupValues['AA_AMOUNT'];?>" 
                	class="righttext" /> 
            	(Rs. In Lacs)
			</td>
		</tr>
        </table>
	</td>
    <input type="hidden" id="BLOCK_FORM" value="<?php //echo $BLOCK_FORM3;?>" />
	<td width="50%" valign="top">
		<table width="100%" cellpadding="3" cellspacing="1" class="ui-widget-content">
        <tr>
        	<td colspan="2" class="ui-state-default" align="center" style="line-height:20px;">
            	<input type="checkbox" id="isRAA" name="isRAA" 
                	value="1" class="css-checkbox"
                onclick="showHideRAA(this.checked)" 
                <?php if ($raaValues['RAA_NO']!='') 
					echo 'checked="checked"';?>
                />
            	<label for="isRAA" class="css-label lite-green-check">
            	<strong>Latest RAA</strong>
				(upto 31 March, 2015)</label>
			</td>
		</tr>
		<tr class="raa" <?php if ($raaValues['RAA_NO']=='') echo 'style="display:none"';?>>
        	<td nowrap="nowrap" class="ui-widget-content"><strong>RAA No : </strong></td>
            <td class="ui-widget-content">
            	<input type="hidden" name="RAA_PROJECT_ID" id="RAA_PROJECT_ID" 
                	value="<?php echo $raaValues['RAA_PROJECT_ID'];?>" />
            	<input name="RAA_NO"  id="RAA_NO" type="text" size="40" maxlength="50" 
                	value="<?php echo $raaValues['RAA_NO'];?>" <?php echo $readOnly;?> />
            </td>
        </tr>
        <tr class="raa" <?php if ($raaValues['RAA_NO']=='') echo 'style="display:none"';?>>
        	<td nowrap="nowrap" class="ui-widget-content"><strong>Date : </strong></td>
            <td class="ui-widget-content">
            	<input name="RAA_DATE" type="text" id="RAA_DATE" size="14" maxlength="10"
                	value="<?php echo myDateFormat($raaValues['RAA_DATE']);?>" 
                    style="text-align:center"  <?php echo $readOnly;?>/>
	               <br />
				(dd-mm-yyyy) e.g. 02-12-2013 for 2<sup>nd</sup> Dec 2013
			</td>
        </tr>
        <tr class="raa" <?php if ($raaValues['RAA_NO']=='') echo 'style="display:none"';?>>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Authority : </strong></td>
            <td class="ui-widget-content">
            	<select name="RAA_AUTHORITY_ID" id="RAA_AUTHORITY_ID" style="width:313px;" class="chosen-select"  <?php echo $readOnly;?>>
                <option value="" >Select Authority</option>
                <?php echo implode('', $RAA_AUTHORITY_ID);?>
                </select>
            </td>
        </tr>
        <tr class="raa" <?php if ($raaValues['RAA_NO']=='') echo 'style="display:none"';?>>
			<td nowrap="nowrap" class="ui-widget-content"><strong>Amount :</strong></td>
			<td class="ui-widget-content">
            	<input name="RAA_AMOUNT" id="RAA_AMOUNT" type="text" size="14"  <?php echo $readOnly;?>
                	 maxlength="20" value="<?php echo $raaValues['RAA_AMOUNT'];?>" 
                     class="validate[custom[number]] righttext" />
                (Rs. In Lacs)
			</td>
        </tr>
        </table>
    </td>
</tr>
<tr>
   <td colspan="2" class="ui-state-default" align="left" style="line-height:20px;">
		<strong>Office</strong>
   </td>
</tr>
<tr>
  <td colspan="2" class="ui-widget-content" align="left" style="line-height:20px;">
        <table width="100%" border="0" cellpadding="2" cellspacing="1">
        <tr>
            <td class="ui-widget-content">
               <strong>Executive Engineer : </strong>
            </td>
            <td class="ui-widget-content">
            	<?php if($this->session->userdata('HOLDING_PERSON')==3){?>
            	<select id="OFFICE_EE_ID" name="OFFICE_EE_ID" 
                    data-placeholder="Select Division - संभाग चुने"
                    style="min-width:25%;width:600px" class="chosen-select"
                    onchange="getSDOOffices(this.value)" >
                    <?php echo $ee_options;?>
                </select>
                <?php }else if($this->session->userdata('HOLDING_PERSON')==4){?>
                <input type="hidden" name="OFFICE_EE_ID" value="<?php echo $EE_ID;?>" /> 
                <strong><?php echo $EE_NAME;?></strong>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td valign="top" class="ui-widget-content"><strong>Sub-Division : </strong></td>
            <td class="ui-widget-content">
                <select id="OFFICE_SDO_ID" name="OFFICE_SDO_ID[]" multiple="multiple" 
                    data-placeholder="Select Sub-Division - उप-संभाग चुने"
                    style="min-width:25%;width:600px" class="chosen-select" >
                    <?php echo $sdo_options;?>
                </select>
            </td>
        </tr>
		</table>
    </td>
</tr>
</table>
<?php if($editMode){?>
<br />
<!----------------------------[ Form - I ]------------------------------->
<fieldset>
<legend>
    <a role="button" class="ui-button ui-state-default ui-corner-all ui-button-text-only " onclick="$('#frm1').slideToggle('slow');" aria-disabled="false"> 
    <span class="ui-button-text"><i class="cus-table"></i>
        &nbsp; Add Form - 1 &nbsp;
	</span>
    </a>
</legend>
<!--frm1-->
<div id="frm1" style="display:none;">
<table width="100%" border="0" cellpadding="3" cellspacing="1">
<tr>
    <td valign="top">
        <table width="100%" cellpadding="3" cellspacing="2"  class="ui-widget-content">
        <tr>
            <td valign="top" nowrap="nowrap" class="ui-widget-content">
                <?php echo getRequiredSign('right');?>
                <strong>Longitude of Site :</strong>
            </td>
            <td valign="middle" class="ui-widget-content">
            	<input name="LONGITUDE_D" id="LONGITUDE_D" type="hidden" 
                    value="<?php echo $projectSetupValues['LONGITUDE_D'];?>" />
                <input name="LONGITUDE_M" id="LONGITUDE_M" type="hidden" 
                    value="<?php echo $projectSetupValues['LONGITUDE_M'];?>" />
                <input name="LONGITUDE_S" id="LONGITUDE_S" type="hidden" 
                    value="<?php echo $projectSetupValues['LONGITUDE_S'];?>" />
                <strong><?php echo $projectSetupValues['LONGITUDE_D'];?>&deg; &nbsp;
                <?php echo $projectSetupValues['LONGITUDE_M'];?>' &nbsp;
				<?php echo $projectSetupValues['LONGITUDE_S'];?>"</strong>
            </td>
            <td valign="top" class="ui-widget-content">
                <?php echo getRequiredSign('right');?> <strong>Latitude of Site :</strong></td>
            <td valign="middle" class="ui-widget-content">
	            <input name="LATITUDE_D" id="LATITUDE_D" type="hidden" 
                    value="<?php echo $projectSetupValues['LATITUDE_D'];?>" />
                <input name="LATITUDE_M" id="LATITUDE_M" type="hidden" 
                    value="<?php echo $projectSetupValues['LATITUDE_M'];?>" />
                <input name="LATITUDE_S" id="LATITUDE_S" type="hidden" 
                    value="<?php echo $projectSetupValues['LATITUDE_S'];?>" />
                <strong><?php echo $projectSetupValues['LATITUDE_D'];?>&deg; &nbsp;
                <?php echo $projectSetupValues['LATITUDE_M'];?>' &nbsp;
				<?php echo $projectSetupValues['LATITUDE_S'];?>"</strong>
            </td>
        </tr>
        <tr>
            <td rowspan="2" valign="top" nowrap="nowrap" class="ui-widget-content">
            <?php echo getRequiredSign('right');?>
            <strong>District (Site) :</strong></td>
            <td rowspan="2" valign="top" class="ui-widget-content">
            	<input type="hidden" name="HEAD_WORK_DISTRICT_ID" id="HEAD_WORK_DISTRICT_ID" 
                	value="<?php echo $projectSetupValues['HEAD_WORK_DISTRICT_ID'];?>" />
                <strong><?php echo $projectSetupValues['DISTRICT_NAME'];?></strong>
            </td>
            <td valign="top" class="ui-widget-content">
                <?php echo getRequiredSign('right');?> <strong>Block (Site) :</strong>
            </td>
            <td valign="top" class="ui-widget-content">
                <select name="HEAD_WORK_BLOCK_ID" id="HEAD_WORK_BLOCK_ID" 
                    class="chosen-select " style="width:200px;">
                <?php echo $BLOCK_HEAD;?>
                </select>
            </td>
        </tr>
        <tr>
          <td valign="top" class="ui-widget-content">
            <?php echo getRequiredSign('right');?> 
            <strong>Tehsil (Site) :</strong></td>
          <td valign="top" class="ui-widget-content">
            <select name="HEAD_WORK_TEHSIL_ID" id="HEAD_WORK_TEHSIL_ID" 
                class="chosen-select " style="width:200px;">
              <?php echo $TEHSIL_HEAD;?>
            </select>
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" class="ui-widget-content">
                <strong>District Benefited :</strong><?php echo getRequiredSign('right');?> 
            </td>
            <td class="ui-widget-content" valign="top">
                <select name="DISTRICT_BENEFITED[]" id="DISTRICT_BENEFITED" style="width:200px;"
                    multiple="multiple" class="chosen-select" data-placeholder="Select Benefited Districts">
                <?php echo $DISTRICT_BENEFITED;?>
                </select>
            </td>
            <td class="ui-widget-content" valign="top"><strong>Benefited Blocks :</strong><?php echo getRequiredSign('right');?> </td>
            <td class="ui-widget-content" valign="top">
            <select name="BLOCKS_BENEFITED[]" id="BLOCKS_BENEFITED" 
                style="width:250px;" multiple="multiple" 
                class="chosen-select" 
                data-placeholder="Select Benefited Blocks">
              <?php echo $BLOCKS_BENEFITED;?>
            </select></td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" class="ui-widget-content">
            <?php echo getRequiredSign('right');?> 
            <strong>Assembly Constituency<br />
            (Site) :</strong></td>
            <td valign="top" class="ui-widget-content">
                <select name="ASSEMBLY_CONST_ID" id="ASSEMBLY_CONST_ID" 
                    class="chosen-select " 
                    style="width:200px;">
              <option value="">Assembly Constituency</option>
              <?php echo $ASSEMBLY_CONST;?>
            </select></td>
            <td valign="top" class="ui-widget-content">
                <strong>Benefited Assembly <br />
                Constituency :</strong>
            </td>
            <td valign="top" class="ui-widget-content">
            <select name="ASSEMBLY_BENEFITED[]" id="ASSEMBLY_BENEFITED" style="width:250px;"
                    multiple="multiple" class="chosen-select" 
                    data-placeholder="Select Benefited Assembly Constituency">
              <?php echo $ASSEMBLY_BENEFITED;?>
            </select></td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" class="ui-widget-content">
                <?php echo getRequiredSign('right');?> <strong>Nalla / River :</strong>
            </td>
            <td class="ui-widget-content" valign="top">
                <textarea name="NALLA_RIVER" class="" id="NALLA_RIVER" 
                    cols="27" ><?php echo $projectSetupValues['NALLA_RIVER'];?></textarea>
            </td>
            <td class="ui-widget-content" valign="top"><strong>Live Storage :</strong></td>
            <td class="ui-widget-content" valign="top">
            <input name="LIVE_STORAGE" id="LIVE_STORAGE" type="text" size="20" maxlength="50"
                    value="<?php echo $projectSetupValues['LIVE_STORAGE'];?>"
                     class="righttext" /> MCM 
            </td>
        </tr>
        <tr>
          <td colspan="4" valign="top" nowrap="nowrap" class="ui-widget-content">
          <table width="100%" border="0" cellspacing="1" cellpadding="3" class="ui-widget-content">
            <tr>
              <td class="ui-state-default">
                <div style="float:left">
                <strong>No of villages covered : </strong><?php echo getRequiredSign('');?> 
                <input type="text" name="NO_VILLAGES_COVERED" id="NO_VILLAGES_COVERED"
                    size="4" maxlength="3" class="centertext"
                    value="<?php echo $projectSetupValues['NO_VILLAGES_BENEFITED'];?>" 
                    />
               </div>
               <div style="float:right">Village, Tehsil, District</div>	
               </td>
              <td class="ui-state-default">
                <div id="villageCount" style="float:right" ></div>
                <div style="float:right">Total Selected : </div> 
                
              </td>
            </tr>
            <tr>
              <td colspan="2" class="ui-widget-content">
                <select name="VILLAGES_BENEFITED[]" id="VILLAGES_BENEFITED" 
                    multiple="multiple" data-placeholder="Select Village" 
                    style="width:99%;" class="chosen-select">
                <?php echo $VILLAGES_BENEFITED;?>
              </select>
                </td>
            </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
</tr>
</table>
<!--//frm1-->
</div>
</fieldset>
<br />
<!----------------------------[ Form - II ]------------------------------->
<fieldset> 
<legend>
    <a id="btnfrm2" role="button" class="ui-button  ui-state-default ui-corner-all ui-button-text-only"> 
    <span class="ui-button-text"><i class="cus-table"></i>
        &nbsp; Add Form - 2 &nbsp;
	</span>
    </a>
</legend>
<!--frm2-->
<div id="frm2" style="display:none">
<table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
<tr>
    <td valign="middle" align="left" class="ui-state-default">
        <?php echo getRequiredSign('right');?>
        Financial Year in which this setup(data) is entered(in Software) : 
    </td>
    <td valign="middle" class="ui-widget-content" align="center">
    	<?php if($monthlyRecordExists){?>
                <input type="hidden" name="SESSION_ID" id="SESSION_ID" 
                    value="<?php echo $projectSetupValues['SESSION_ID'];?>" />
               <strong><?php echo $SESSION_OPTIONS;?></strong>
		<?php }else if($projectSetupValues['SESSION_ID']==0){?>
			<input type="hidden" name="SESSION_ID" id="SESSION_ID" 
                    value="<?php echo $projectSetupValues['SESSION_ID'];?>" />
               <strong><?php echo $SESSION_OPTIONS;?></strong>
		<?php }else{?>
                <select name="SESSION_ID" id="SESSION_ID"
                class="chosen-select" style="width:150px" onchange="doSessionChange();checkAchievement();">
                 <option value="">Financial Year</option>
                     <?php echo implode('', $SESSION_OPTIONS);?>
                </select>
		<?php }?>
	<input type="hidden" id="sessionRealMinDate" name="sessionRealMinDate" value=""  />
	<input type="hidden" id="sessionMinDate" name="sessionMinDate" value=""  />
	<input type="hidden" id="sessionMaxDate" name="sessionMaxDate" value=""  />
	<input type="hidden" id="startInSession" value="0" />
	<input type="hidden" id="mytoday" value="<?php echo date("d-m-Y");?>" />
    </td>
    <td valign="middle" nowrap="nowrap" class="ui-state-default">
        <?php echo getRequiredSign('right');?>Actual Work Start Date:
	</td>
    <td align="center" valign="middle" nowrap="nowrap" class="ui-widget-content">
        <input type="hidden" name="PROJECT_START_DATE" id="PROJECT_START_DATE" 
            value="<?php echo myDateFormat($projectSetupValues['AA_DATE']);?>" />
		<strong><?php echo myDateFormat($projectSetupValues['AA_DATE']);?></strong>
    </td>
</tr>
<tr>
    <td valign="top" colspan="4">
    	<?php //showArrayValues($achievementValues);?>
        <table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">
        <tr>
            <th class="ui-widget-header" >SNo</th>
            <th class="ui-widget-header">Contents</th>
            <th class="ui-widget-header">Unit </th>
            <th class="ui-widget-header">NA</th>
            <th class="ui-widget-header">Latest Estimated</th>
            <th class="ui-widget-header">Achievement <br />upto last financial year </th>
        </tr>    
        <tr>
            <td class="ui-state-default" colspan="6"><strong>Financial</strong></td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="ui-widget-content" align="center"><strong>a)</strong> </td>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Total</strong> </td>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Rs. Lacs</strong> </td>
            <td align="center" class="ui-widget-content">&nbsp;</td>
            <td align="right" class="ui-widget-content">
              <?php echo getRequiredSign('left');?>
              <input name="EXPENDITURE_TOTAL" id="EXPENDITURE_TOTAL"
                 type="text" size="12" maxlength="50"
                     value="<?php echo $projectSetupValues['EXPENDITURE_TOTAL'];?>" 
                    class="righttext"/>
            </td>
            <td align="right" class="ui-widget-content"><?php echo getRequiredSign('left');?>
                <input name="EXPENDITURE_TOTAL_ACHIEVE" id="EXPENDITURE_TOTAL_ACHIEVE" 
                    type="text" size="12" maxlength="50" 
                    value="<?php echo $achievementValues['EXPENDITURE_TOTAL'];?>" 
                    class="righttext" />
            </td>  
        </tr>	
        <tr>
            <td nowrap="nowrap" class="ui-widget-content" align="center"><strong>b) </strong> </td>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Works </strong> </td>
            <td nowrap="nowrap" class="ui-widget-content"><strong>Rs. Lacs</strong> </td>
             <td align="center" class="ui-widget-content">&nbsp;</td>
            <td align="right" class="ui-widget-content">
                <?php echo getRequiredSign('left');?> 
                <input name="EXPENDITURE_WORK" id="EXPENDITURE_WORK"
                    type="text" size="12" maxlength="50"
                    value="<?php echo $projectSetupValues['EXPENDITURE_WORK'];?>"
                     class="righttext" />
            </td>
            <td align="right" class="ui-widget-content">
                <?php echo getRequiredSign('left');?>
                <input name="EXPENDITURE_WORKS_ACHIEVE" id="EXPENDITURE_WORKS_ACHIEVE"
                    type="text" size="12" maxlength="50"
                    value="<?php echo $achievementValues['EXPENDITURE_WORKS'];?>"
                    class="righttext" />
            </td>  
        </tr>	
        <tr>
            <td colspan="5" nowrap="nowrap" class="ui-state-default">
                <strong>Physical</strong>
                 <input name="ESTIMATED_QTY_ID" id="ESTIMATED_QTY_ID"
                    type="hidden" 
                    value="<?php echo $estimationData['ESTIMATED_QTY_ID'];?>" />
            </td>
        </tr>
<?php 
$arrEstimationAchievements = array(
	array(
		'SNO'=>1,
		'TITLE'=>'Land Acquisition Submited', 
		'UNIT'=>'No. of Cases',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['LA_NA'],
		'EA_NAME'=>'LA_NO', 
		'EA_VALUE'=>$estimationData['LA_NO'], 
		'AC_NAME'=>'LA_NO_ACHIEVE',
		'AC_VALUE'=>$achievementValues['LA_NO'],
		'SHOW'=>1
	),
	array(
		'SNO'=>2,
		'TITLE'=>'Land Acquisition Submited', 
		'UNIT'=>'Hectares',
		'NA_BOX'=>FALSE,
		'NA_VALUE'=>$estimationStatus['LA_NA'],
		'EA_NAME'=>'LA_HA', 
		'EA_VALUE'=>$estimationData['LA_HA'], 
		'AC_NAME'=>'LA_HA_ACHIEVE',
		'AC_VALUE'=>$achievementValues['LA_HA'],
		'SHOW'=>1
	),
	array(
		'SNO'=>3,
		'TITLE'=>'Land Acquisition Completed', 
		'UNIT'=>'No. of Cases',
		'NA_BOX'=>FALSE,
		'NA_VALUE'=>$estimationStatus['LA_NA'],
		'EA_NAME'=>'LA_COMPLETED_NO', 
		'EA_VALUE'=>$estimationData['LA_COMPLETED_NO'], 
		'AC_NAME'=>'LA_COMPLETED_NO_ACHIEVE',
		'AC_VALUE'=>$achievementValues['LA_COMPLETED_NO'],
		'SHOW'=>0
	),
	array(
		'SNO'=>4,
		'TITLE'=>'Land Acquisition Completed', 
		'UNIT'=>'Hectares',
		'NA_BOX'=>FALSE,
		'NA_VALUE'=>$estimationStatus['LA_NA'],
		'EA_NAME'=>'LA_COMPLETED_HA', 
		'EA_VALUE'=>$estimationData['LA_COMPLETED_HA'], 
		'AC_NAME'=>'LA_COMPLETED_HA_ACHIEVE',
		'AC_VALUE'=>$achievementValues['LA_COMPLETED_HA'],
		'SHOW'=>0
	),
	array(
		'SNO'=>5,
		'TITLE'=>'Forest Acquisition', 
		'UNIT'=>'Hectares',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['FA_NA'],
		'EA_NAME'=>'FA_HA', 
		'EA_VALUE'=>$estimationData['FA_HA'], 
		'AC_NAME'=>'FA_HA_ACHIEVE',
		'AC_VALUE'=>$achievementValues['FA_HA'],
		'SHOW'=>1
	),
	array(
		'SNO'=>6,
		'TITLE'=>'Forest Acquisition Completed', 
		'UNIT'=>'Hectares',
		'NA_BOX'=>FALSE,
		'NA_VALUE'=>$estimationStatus['FA_NA'],
		'EA_NAME'=>'FA_COMPLETED_HA', 
		'EA_VALUE'=>$estimationData['FA_COMPLETED_HA'], 
		'AC_NAME'=>'FA_COMPLETED_HA_ACHIEVE',
		'AC_VALUE'=>$achievementValues['FA_COMPLETED_HA'],
		'SHOW'=>0
	),
	array(
		'SNO'=>7,
		'TITLE'=>'Headworks Earthwork', 
		'UNIT'=>'Th Cum',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['HEAD_WORKS_EARTHWORK_NA'],
		'EA_NAME'=>'HEAD_WORKS_EARTHWORK', 
		'EA_VALUE'=>$estimationData['HEAD_WORKS_EARTHWORK'], 
		'AC_NAME'=>'HEAD_WORKS_EARTHWORK_ACHIEVE',
		'AC_VALUE'=>$achievementValues['HEAD_WORKS_EARTHWORK'],
		'SHOW'=>1
	),
	array(
		'SNO'=>8,
		'TITLE'=>'Headworks Masonry/Concrete', 
		'UNIT'=>'Th Cum',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['HEAD_WORKS_MASONRY_NA'],
		'EA_NAME'=>'HEAD_WORKS_MASONRY', 
		'EA_VALUE'=>$estimationData['HEAD_WORKS_MASONRY'], 
		'AC_NAME'=>'HEAD_WORKS_MASONRY_ACHIEVE',
		'AC_VALUE'=>$achievementValues['HEAD_WORKS_MASONRY'],
		'SHOW'=>1
	),
	array(
		'SNO'=>9,
		'TITLE'=>'Steel Works(Only Gate Portion) If gates are to be made.', 
		'UNIT'=>'Metric Tonn',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['STEEL_WORKS_NA'],
		'EA_NAME'=>'STEEL_WORKS', 
		'EA_VALUE'=>$estimationData['STEEL_WORKS'], 
		'AC_NAME'=>'STEEL_WORKS_ACHIEVE',
		'AC_VALUE'=>$achievementValues['STEEL_WORKS'],
		'SHOW'=>1
	),
	array(
		'SNO'=>10,
		'TITLE'=>'Canal Earthwork ', 
		'UNIT'=>'Th Cum',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['CANAL_EARTHWORK_NA'],
		'EA_NAME'=>'CANAL_EARTHWORK', 
		'EA_VALUE'=>$estimationData['CANAL_EARTHWORK'], 
		'AC_NAME'=>'CANAL_EARTHWORK_ACHIEVE',
		'AC_VALUE'=>$achievementValues['CANAL_EARTHWORK'],
		'SHOW'=>1
	),
	array(
		'SNO'=>11,
		'TITLE'=>'Canal Structures', 
		'UNIT'=>'Numbers',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['CANAL_STRUCTURES_NA'],
		'EA_NAME'=>'CANAL_STRUCTURES', 
		'EA_VALUE'=>$estimationData['CANAL_STRUCTURES'], 
		'AC_NAME'=>'CANAL_STRUCTURES_ACHIEVE',
		'AC_VALUE'=>$achievementValues['CANAL_STRUCTURES'],
		'SHOW'=>1
	),
	array(
		'SNO'=>12,
		'TITLE'=>'Canal Structure Masonry/Concrete <span style="color:#f00">(Applicable only if no. of stru. not mentioned above)</span>', 
		'UNIT'=>'Th.Cum',
		'NA_BOX'=>FALSE,
		'NA_VALUE'=>$estimationStatus['CANAL_STRUCTURES_NA'],
		'EA_NAME'=>'CANAL_MASONRY', 
		'EA_VALUE'=>$estimationData['CANAL_MASONRY'], 
		'AC_NAME'=>'CANAL_MASONRY_ACHIEVE',
		'AC_VALUE'=>$achievementValues['CANAL_MASONRY'],
		'SHOW'=>1
	),
	array(
		'SNO'=>13,
		'TITLE'=>'Canal Lining', 
		'UNIT'=>'Km.',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['CANAL_LINING_NA'],
		'EA_NAME'=>'CANAL_LINING', 
		'EA_VALUE'=>$estimationData['CANAL_LINING'], 
		'AC_NAME'=>'CANAL_LINING_ACHIEVE',
		'AC_VALUE'=>$achievementValues['CANAL_LINING'],
		'SHOW'=>1
	),
	array(
		'SNO'=>14,
		'TITLE'=>'Road Works', 
		'UNIT'=>'Km.',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['ROAD_WORKS_NA'],
		'EA_NAME'=>'ROAD_WORKS', 
		'EA_VALUE'=>$estimationData['ROAD_WORKS'], 
		'AC_NAME'=>'ROAD_WORKS_ACHIEVE',
		'AC_VALUE'=>$achievementValues['ROAD_WORKS'],
		'SHOW'=>1
	),
	array(
		'SNO'=>15,
		'TITLE'=>'Designed Irrigation Potential', 
		'UNIT'=>'Hectares',
		'NA_BOX'=>TRUE,
		'NA_VALUE'=>$estimationStatus['IRRIGATION_POTENTIAL_NA'],
		'EA_NAME'=>'IRRIGATION_POTENTIAL', 
		'EA_VALUE'=>$estimationData['IRRIGATION_POTENTIAL'], 
		'AC_NAME'=>'IRRIGATION_POTENTIAL_ACHIEVE',
		'AC_VALUE'=>$achievementValues['IRRIGATION_POTENTIAL'],
		'SHOW'=>1, 
		'KHARIF'=>array(
			'EA_NAME'=>'IRRIGATION_POTENTIAL_KHARIF', 
			'EA_VALUE'=>$estimationData['IRRIGATION_POTENTIAL_KHARIF'], 
			'AC_NAME'=>'IRRIGATION_POTENTIAL_KHARIF_ACHIEVE',
			'AC_VALUE'=>$achievementValues['IRRIGATION_POTENTIAL_KHARIF']
		),
		'RABI' => array(
			'EA_NAME'=>'IRRIGATION_POTENTIAL_RABI', 
			'EA_VALUE'=>$estimationData['IRRIGATION_POTENTIAL_RABI'], 
			'AC_NAME'=>'IRRIGATION_POTENTIAL_RABI_ACHIEVE',
			'AC_VALUE'=>$achievementValues['IRRIGATION_POTENTIAL_RABI']
		)
	)
);
/////////////////////////////////////
//$content = '';
$arrV = array();
$arrStatusCombo = array();//'la'=>false, 'ew'=>false, 'es'=>false, 'el'=>false);
$arrAchievementCompo = array('EXPENDITURE_TOTAL_ACHIEVE', 'EXPENDITURE_WORKS_ACHIEVE');
$iIPStartAt = 15;
$arrLAFACompo = array('LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA', 'FA_COMPLETED_HA');
$arrRulesExcept = array('LA_COMPLETED_NO_ACHIEVE', 'LA_COMPLETED_HA_ACHIEVE', 'FA_COMPLETED_HA_ACHIEVE');
$arrCompleted = array('LA_COMPLETED_NO_ACHIEVE', 'LA_COMPLETED_HA_ACHIEVE', 'FA_COMPLETED_HA_ACHIEVE');

$IP_NA = 0;
$arrIntSNo = array(1, 3, 11);
$arrIP = array();
foreach($arrEstimationAchievements as $x){
	if($x['SNO']==11){
		$sno = '11a';
	}else if($x['SNO']==12){
		$sno = '11b';
	}else if($x['SNO']>12){
		$sno = $x['SNO']-1;
	}else 
		$sno = $x['SNO'];
	if($x['SNO']==$iIPStartAt){
		$IP_NA = $x['NA_VALUE'];
		$arrIP = $x;
	}
	//if( !in_array($x['AC_NAME'], $arrRulesExcept))
		array_push($arrAchievementCompo, $x['AC_NAME']);
	$myClass = ($x['NA_VALUE'])? '' : 'required,';
	$rowSpan = '';
	if( ($x['SNO']==$iIPStartAt) && ($x['SHOW'])){
		$rowSpan = '';//'rowspan="3"';
		array_push($arrAchievementCompo, $x['KHARIF']['AC_NAME']);
		array_push($arrAchievementCompo, $x['RABI']['AC_NAME']);
	}
	/*if($x['SNO']==11){
		$rowSpan = 2;//'rowspan="3"';
		//array_push($arrAchievementCompo, $x['KHARIF']['AC_NAME']);
		//array_push($arrAchievementCompo, $x['RABI']['AC_NAME']);
	}*/
	echo '<tr>
		<td nowrap="nowrap" class="ui-widget-content" align="center" '.$rowSpan.'><strong>'.$sno.'</strong></td>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'><strong>'.$x['TITLE'].'</strong></td>
		<td nowrap="nowrap" class="ui-widget-content" '.$rowSpan.'><strong>'.$x['UNIT'].'</strong></td>';
	//NA checkbox
	if($x['NA_BOX']){
		if($x['EA_NAME']=='LA_NO' || $x['EA_NAME']=='FA_HA'){
			echo '<td align="center" class="ui-widget-content" rowspan="'.
			(($x['EA_NAME']=='LA_NO')? 4:2).'" valign="middle">';
		}else{
			if($x['EA_NAME']=='CANAL_STRUCTURES') $rowSpan = ' rowspan="2"';
			echo '<td align="center" class="ui-widget-content" '.$rowSpan.'>';
		}
		$nf = substr($x['EA_NAME'], 0, 3);
		if($nf=='LA_' || $nf=='FA_'){
			$fname = $nf.'NA';
		}else{
			$fname = $x['EA_NAME'].'_NA';
		}
		echo '<input type="checkbox" name="'.$fname.'" id="'.$fname.'"'.
		( ($x['NA_VALUE']) ? 'checked="checked"' : '' ).' class="css-checkbox" 
		onclick="setEstimationFields('.$x['SNO'].', this.name, this.checked)" value="1" />
		<label for="'.$fname.'" class="css-label lite-blue-check">NA</label>
		</td>';
		if($x['SNO']==$iIPStartAt){
			echo '<td nowrap="nowrap" class="ui-widget-content" colspan="3"></td></tr>';
			continue;
		}
	}
	//end NA checkbox
	//kharif/rabi title column
	if($x['EA_NAME']=='LA_NO')	$rowSpan = 'rowspan="4"';
	if($x['EA_NAME']=='LA_HA')	$rowSpan = '';
	if($x['EA_NAME']=='FA_HA')	$rowSpan = 'rowspan="2"';
	//if($x['EA_NAME']=='CANAL_STRUCTURES')	$rowSpan = 'rowspan="2"';
		
	/*if($x['SNO']<$iIPStartAt){
		if( in_array($x['EA_NAME'], $arrLAFACompo)){
			//
			//echo '<td align="center" class="ui-widget-content" '.$rowSpan.'" valign="middle">2</td>';
		}else{
			if($x['SNO']!=12)
			echo '<td align="center" class="ui-widget-content" '.$rowSpan.'" valign="middle"></td>';
		}
	}*/
	//end of kharif/rabi title column
	$arrStatusCombo[$x['EA_NAME'].'_NA'] = $x['NA_VALUE'];
	$mDisabled = (($x['NA_VALUE']) ? 'disabled="disabled"':'');
	if($x['SHOW']==0){
		//estimate
		if(in_array($x['AC_NAME'], $arrCompleted)) 
			echo '<td align="center" class="ui-widget-content"></td>';
		echo '<td align="right" class="ui-widget-content">
			<div id="req_'.$x['AC_NAME'].'" style="float:left;display:'.
			( ($x['NA_VALUE']) ? 'none' : '' )
			.'">'.getRequiredSign('left').'</div>
		  <input name="'.$x['AC_NAME'].'" type="text" id="'.$x['AC_NAME'].'"
			 size="12" maxlength="50"  class="righttext"  
				value="'.(($x['NA_VALUE']) ? '' : $x['AC_VALUE']).'"/>
		</td></tr>';
		if(in_array($x['SNO'], $arrIntSNo)){
		//if( ($x['SNO']==3) || ($x['SNO']==4) || ($x['SNO']==6)){
			//
			//array_push($arrV, '"'.$x['EA_NAME'].'":{required : true, digits:true, minStrict:0}');
			/*if($x['AC_NAME']=='CANAL_STRUCTURES_ACHIEVE'){
				array_push($arrV, '"'.$x['AC_NAME'].'":{required : true, digits:true, myLess1:""}');
			}else{*/
				array_push($arrV, '"'.$x['AC_NAME'].'":{required : true, digits:true, myLess1:"", min:0}');
			//}
		}else{
			if($x['SNO']!=$iIPStartAt){
				/*if($x['AC_NAME']=='CANAL_MASONRY_ACHIEVE'){
					array_push($arrV, '"'.$x['AC_NAME'].'":{required : true, number:true, min:0, myLess1:""}');
				}else{*/
					array_push($arrV, '"'.$x['AC_NAME'].'":{required : true, number:true, min:0, myLess1:""}');
				//}
			}
		}
	}else{
		if($x['SNO']==$iIPStartAt){
			echo '<td align="right" class="ui-widget-content"><strong>Kharif</strong></td>
				<td align="right" class="ui-widget-content">
					<div id="req_'.$x['SNO'].'kharif" style="float:left;display:'.
					( ($x['NA_VALUE']) ? 'none' : '' )
					.'">'.getRequiredSign('left').'</div>
					<input name="'.$x['KHARIF']['EA_NAME'].'" type="text" id="'.$x['KHARIF']['EA_NAME'].'" 
						size="12" maxlength="50" value="'.(($x['NA_VALUE'])? '':$x['KHARIF']['EA_VALUE']).'"
					  class="righttext" '. $mDisabled .' onkeyup="getIrriTotal(0)" />
				</td>
				<td align="right" class="ui-widget-content">
				<div id="reqa_'.$x['SNO'].'kharif" style="float:left;display:'.
				( ($x['NA_VALUE']) ? 'none' : '' )
				.'">'.getRequiredSign('left').'</div>
			  <input name="'.$x['KHARIF']['AC_NAME'].'" type="text" id="'.$x['KHARIF']['AC_NAME'].'"
				 size="12" maxlength="50"  class="righttext" '. $mDisabled .'  onkeyup="getIrriTotal(1)"
					value="'.(($x['NA_VALUE']) ? '' : $x['KHARIF']['AC_VALUE']).'"/>
			</td>
			</tr>
			<tr>
			<td align="right" class="ui-widget-content"><strong>Rabi</strong></td>
				<td align="right" class="ui-widget-content">
					<div id="req_'.$x['SNO'].'rabi" style="float:left;display:'.
					( ($x['NA_VALUE']) ? 'none' : '' )
					.'">'.getRequiredSign('left').'</div>
					<input name="'.$x['RABI']['EA_NAME'].'" type="text" id="'.$x['RABI']['EA_NAME'].'" 
						size="12" maxlength="50" value="'.(($x['NA_VALUE'])? '':$x['RABI']['EA_VALUE']).'"
					  class="righttext" '. $mDisabled .'  onkeyup="getIrriTotal(0)" />
				</td>
				<td align="right" class="ui-widget-content">
				<div id="reqa_'.$x['SNO'].'rabi" style="float:left;display:'.
				( ($x['NA_VALUE']) ? 'none' : '' )
				.'">'.getRequiredSign('left').'</div>
			  <input name="'.$x['RABI']['AC_NAME'].'" type="text" id="'.$x['RABI']['AC_NAME'].'"
				 size="12" maxlength="50"  class="righttext" '. $mDisabled .'  onkeyup="getIrriTotal(1)"
					value="'.(($x['NA_VALUE']) ? '' : $x['RABI']['AC_VALUE']).'"/>
			</td>
			</tr>
			<tr>
			<td align="right" class="ui-state-default"><strong>Total</strong></td>
				<td align="right" class="ui-state-default">
					<div id="req_'.$x['SNO'].'" style="float:left;display:'.
					( ($x['NA_VALUE']) ? 'none' : '' )
					.'">'.getRequiredSign('left').'</div>
					<input name="'.$x['EA_NAME'].'" type="text" id="'.$x['EA_NAME'].'" 
						size="12" maxlength="50" value="'.(($x['NA_VALUE'])? '':$x['EA_VALUE']).'"
					  class="righttext" readonly="readonly" />
				</td>
				<td align="right" class="ui-state-default">
				<div id="reqa_'.$x['SNO'].'" style="float:left;display:'.
				( ($x['NA_VALUE']) ? 'none' : '' )
				.'">'.getRequiredSign('left').'</div>
			  <input name="'.$x['AC_NAME'].'" type="text" id="'.$x['AC_NAME'].'"
				 size="12" maxlength="50"  class="righttext"  readonly="readonly"
					value="'.(($x['NA_VALUE']) ? '' : $x['AC_VALUE']).'"/>
			</td></tr>';
			/*array_push($arrV, '"'.$x['KHARIF']['EA_NAME'].'":{required : true, digits:true, minStrict:0}');
			array_push($arrV, '"'.$x['RABI']['EA_NAME'].'":{required : true, digits:true, minStrict:0}');
			array_push($arrV, '"'.$x['KHARIF']['AC_NAME'].'":{required : true, digits:true, min:0}');//, myLess1:""}');
			array_push($arrV, '"'.$x['RABI']['AC_NAME'].'":{required : true, digits:true, min:0}');//, myLess1:""}');
			//minStrict: "Minimum value should be greater than 0"
			*/
		}else{
			if( ($x['SNO']==3) || ($x['SNO']==4) || ($x['SNO']==6)){
				//no estimation box
				echo '<td align="center" class="ui-widget-content"></td>';
			}elseif( $x['SNO']==19){
				//estimation box FOR canal structure
				//echo '<td align="center" class="ui-widget-content"></td>';
			}else{
				echo '<td align="right" class="ui-widget-content">
						<div id="req_'.$x['EA_NAME'].'" style="float:left;display:'.
						( ($x['NA_VALUE']) ? 'none' : '' )
						.'">'.getRequiredSign('left').'</div>
						<input name="'.$x['EA_NAME'].'" type="text" id="'.$x['EA_NAME'].'" 
							size="12" maxlength="50" value="'.(($x['NA_VALUE'])? '':$x['EA_VALUE']).'"
						  class="righttext" '. $mDisabled .' />
					</td>';
			}
			echo '<td align="right" class="ui-widget-content">
				<div id="req_'.$x['AC_NAME'].'" style="float:left;display:'.
				( ($x['NA_VALUE']) ? 'none' : '' )
				.'">'.getRequiredSign('left').'</div>
			  <input name="'.$x['AC_NAME'].'" type="text" id="'.$x['AC_NAME'].'"
				 size="12" maxlength="50"  class="righttext" '. $mDisabled .'
					value="'.(($x['NA_VALUE']) ? '' : $x['AC_VALUE']).'"/>
			</td>';
				
		}
		if(in_array($x['SNO'], $arrIntSNo)){
		//if( ($x['SNO']==3) || ($x['SNO']==4) || ($x['SNO']==6)){
			//
			if($x['EA_NAME']=='CANAL_STRUCTURES'){
				array_push($arrV, '"'.$x['EA_NAME'].'":{required : true, digits:true, min:0}');
			}else{
				array_push($arrV, '"'.$x['EA_NAME'].'":{required : true, digits:true, minStrict:0}');
			}
			array_push($arrV, '"'.$x['AC_NAME'].'":{required : true, digits:true, myLess1:"", min:0}');
		}else{
			if($x['SNO']!=$iIPStartAt){
				if($x['EA_NAME']=='CANAL_MASONRY'){
					array_push($arrV, '"'.$x['EA_NAME'].'":{required : true, number:true, min:0}');
				}else{
					array_push($arrV, '"'.$x['EA_NAME'].'":{required : true, number:true, minStrict:0}');
				}
				array_push($arrV, '"'.$x['AC_NAME'].'":{required : true, number:true, min:0, myLess1:""}');
			}
		}
	}
	echo '</tr/>';
	//achieve
	/*echo '<td align="right" class="ui-widget-content">
			<div id="reqa_'.$x['SNO'].'" style="float:left;display:'.
			( ($x['NA_VALUE']) ? 'none' : '' )
			.'">'.getRequiredSign('left').'</div>
		  <input name="'.$x['AC_NAME'].'" type="text" id="'.$x['AC_NAME'].'"
			 size="12" maxlength="50"  class="righttext" '. $mDisabled .'
				value="'.(($x['NA_VALUE']) ? '' : $x['AC_VALUE']).'"/>
		</td>
	</tr>';*/
	
}//foreach
$disabled = (($IP_NA) ? 'disabled="disabled"':'');
$arrIPBlockIds = array();
//showArrayValues($BLOCK_IP_DATA);
if($BLOCK_IP_DATA){
	$ib = 0;
	foreach($BLOCK_IP_DATA as $k=>$bb){
		//showArrayValues($bb);
		array_push($arrIPBlockIds, $k);
	//for($i=0; $i<count($BLOCK_IP_DATA);$i++){
		echo '<tr id="tr-bk-'.$k.'"><td colspan="7">
			<table border="0" width="100%" cellpadding="2" cellspacing="1"><tr>
			<td class="ui-widget-content" rowspan="3" width="20" align="center">'.(chr($ib+97)).'</td>
			<td class="ui-widget-content" rowspan="3"><strong>'.$bb['BLOCK_NAME'].'</strong></td>
			<td class="ui-widget-content" width="130"><strong>Kharif</strong></td>
			<td class="ui-widget-content" width="133" align="center">
			<div id="req_ipek_'.$k.'" style="float:left;display:'.( ($IP_NA) ? 'none' : '' ).'">'.getRequiredSign('left').'</div>
			<input name="BLOCK_EIP_K['.$k.']" id="BLOCK_EIP_K_'.$k.'" type="text" 
			 size="12" maxlength="12" class="righttext" onkeyup="getIrriSubTotal(0, 0, '.$k.')" '.$disabled.' value="'.$bb['ESTIMATION_IP']['KHARIF'].'"/>
			</td>
			<td class="ui-widget-content" width="133" align="center">
			<div id="req_ipak_'.$k.'" style="float:left;display:'.( ($IP_NA) ? 'none' : '' ).'">'.getRequiredSign('left').'</div>
			<input name="BLOCK_AIP_K['.$k.']" id="BLOCK_AIP_K_'.$k.'" type="text" 
			 size="12" maxlength="12" class="righttext" onkeyup="getIrriSubTotal(0, 1, '.$k.')" '.$disabled.' value="'.$bb['ACHIEVEMENT_IP']['KHARIF'].'"/>
			</td>
			</tr>
			<tr><td class="ui-widget-content"><strong>Rabi</strong></td>
			<td class="ui-widget-content" width="133" align="center">
			<div id="req_iper_'.$k.'" style="float:left;display:'.( ($IP_NA) ? 'none' : '' ).'">'.getRequiredSign('left').'</div>
			<input name="BLOCK_EIP_R['.$k.']" id="BLOCK_EIP_R_'.$k.'" type="text" 
			 size="12" maxlength="12" class="righttext" onkeyup="getIrriSubTotal(1, 0, '.$k.')" '.$disabled.' value="'.$bb['ESTIMATION_IP']['RABI'].'" />
			</td>
			<td class="ui-widget-content" width="133" align="center">
			<div id="req_ipar_'.$k.'" style="float:left;display:'.( ($IP_NA) ? 'none' : '' ).'">'.getRequiredSign('left').'</div>
			<input name="BLOCK_AIP_R['.$k.']" id="BLOCK_AIP_R_'.$k.'" type="text" 
			 size="12" maxlength="12" class="righttext" onkeyup="getIrriSubTotal(1, 1, '.$k.')" '.$disabled.' value="'.$bb['ACHIEVEMENT_IP']['RABI'].'" />
			</td>
			</tr>
			<tr><td class="ui-state-default"><strong>Total</strong></td>
			<td class="ui-state-default" width="133" align="center">
			<input name="BLOCK_EIP_T['.$k.']" id="BLOCK_EIP_T_'.$k.'" type="text" 
			 size="12" maxlength="12" class="righttext" readonly="readonly" '.$disabled.' value="'.$bb['ESTIMATION_IP']['IP'].'" />
			</td>
			<td class="ui-state-default" width="133" align="center">
			<input name="BLOCK_AIP_T['.$k.']" id="BLOCK_AIP_T_'.$k.'" type="text" 
			 size="12" maxlength="12" class="righttext" readonly="readonly" '.$disabled.' value="'.$bb['ACHIEVEMENT_IP']['IP'].'" />
			</td>
			</tr>
			</table></td></tr>';
			$ib++;
	}
}
//showArrayValues($arrAchievementCompo);
//($estimationStatus['IRRIGATION_POTENTIAL_NA']==0){

echo '<tr id="tr-bk-total"><td colspan="7">
			<table border="0" width="100%" cellpadding="2" cellspacing="1"><tr>
			<td class="ui-state-default" rowspan="3" width="20"></td>
			<td class="ui-state-default" rowspan="3"><strong>Total Designed Irrigation Potential</strong></td>
			<td class="ui-state-default" width="130"><strong>Kharif</strong></td>
			<td class="ui-state-default" width="133" align="center">
				<input name="IRRIGATION_POTENTIAL_KHARIF" id="IRRIGATION_POTENTIAL_KHARIF" type="text" 
				 size="12" maxlength="12" class="righttext" readonly="readonly" '.$disabled.' value="'.$arrIP['KHARIF']['EA_VALUE'].'"/>
			</td>
			<td class="ui-state-default" width="133" align="center">
				<input name="IRRIGATION_POTENTIAL_KHARIF_ACHIEVE" id="IRRIGATION_POTENTIAL_KHARIF_ACHIEVE" type="text" 
				 size="12" maxlength="12" class="righttext" readonly="readonly" '.$disabled.' value="'.$arrIP['KHARIF']['AC_VALUE'].'"/>
			</td>
			</tr>
			<tr><td class="ui-state-default"><strong>Rabi</strong></td>
			<td class="ui-state-default" width="133" align="center">
				<input name="IRRIGATION_POTENTIAL_RABI" id="IRRIGATION_POTENTIAL_RABI" type="text" 
				 size="12" maxlength="12" class="righttext" readonly="readonly" '.$disabled.' value="'.$arrIP['RABI']['EA_VALUE'].'"/>
			</td>
			<td class="ui-state-default" width="133" align="center">
				<input name="IRRIGATION_POTENTIAL_RABI_ACHIEVE" id="IRRIGATION_POTENTIAL_RABI_ACHIEVE" type="text" 
				 size="12" maxlength="12" class="righttext" readonly="readonly" '.$disabled.' value="'.$arrIP['RABI']['AC_VALUE'].'"/>
			</td>
			</tr>
			<tr><td class="ui-state-default"><strong>Total</strong></td>
			<td class="ui-state-default" width="133" align="center">
				<input name="IRRIGATION_POTENTIAL" id="IRRIGATION_POTENTIAL" type="text" 
				 size="12" maxlength="12" class="righttext" readonly="readonly" '.$disabled.' value="'.$arrIP['EA_VALUE'].'"/>
			</td>
			<td class="ui-state-default" width="133" align="center">
				<input name="IRRIGATION_POTENTIAL_ACHIEVE" id="IRRIGATION_POTENTIAL_ACHIEVE" type="text" 
				 size="12" maxlength="12" class="righttext"  readonly="readonly" '.$disabled.' value="'.$arrIP['AC_VALUE'].'"/>
			</td>
			</tr>
			</table></td></tr>';
?>
        </table>
    </td>
</tr>
</table>
</div>
<!--//frm2-->
</fieldset>
<div class="wrdlinebreak"></div>
<!----------------------------[ Form - III ]------------------------------->

<fieldset>
<legend>
    <a role="button" class="ui-button  ui-state-default ui-corner-all ui-button-text-only" onclick="$('#frm4').slideToggle('slow');"> 
    <span class="ui-button-text"><i class="cus-table"></i>
        &nbsp; Add Form - 3 &nbsp;
	</span>
    </a>
</legend>
<div id="frm4" class="class2" style="display:none">
    <table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
    <tr>
        <td class="ui-state-default" valign="middle" align="right">Completion Date of Scheme :</td>
        <td class="ui-state-default" valign="middle">
	        <input name="DATE_COMPLETION" id="DATE_COMPLETION" 
            	size="30" maxlength="50" type="text" 
                class="" style="text-align:center"
		        value="<?php echo myDateFormat($projectSetupValues['PROJECT_COMPLETION_DATE']);?>" />
        </td>
    </tr>   
    <tr>
        <td valign="top" colspan="2"> 
            <table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">                  
            <tr>
                <th class="ui-widget-header">Contents</th>
                <th class="ui-widget-header">Status upto Last Financial Year</th>
                <th class="ui-widget-header">Target Dates of Completion</th>
			</tr>
<?php

//$arrStatusCombo[]
$arrStatus = array(
	array('SNO'=>1,
		'TITLE'=>'a) Submission of LA Cases', 
		'STATUS_BOX_NAME'=>'LA_CASES_STATUS',
		'DATE_BOX_NAME'=>'LA_TARGET_DATE',
		'OPTION_LIST' => $LA_CASES_STATUS,
		'ENABLE' => (!$arrStatusCombo['LA_NO_NA'])
	),
	array('SNO'=>2,
		'TITLE'=>'b) Spillway / weir', 
		'STATUS_BOX_NAME'=>'SPILLWAY_STATUS',
		'DATE_BOX_NAME'=>'SPILLWAY_TARGET_DATE',
		'OPTION_LIST' => $SPILLWAY_STATUS,
		'ENABLE' => true
	),
	array('SNO'=>3,
		'TITLE'=>'c) Flanks/Af.bunds', 
		'STATUS_BOX_NAME'=>'FLANK_STATUS',
		'DATE_BOX_NAME'=>'FLANKS_TARGET_DATE',
		'OPTION_LIST' => $FLANK_STATUS,
		'ENABLE' =>true
	),
	array('SNO'=>4,
		'TITLE'=>'d) Sluice/s', 
		'STATUS_BOX_NAME'=>'SLUICES_STATUS',
		'DATE_BOX_NAME'=>'SLUICES_TARGET_DATE',
		'OPTION_LIST' => $SLUICES_STATUS,
		'ENABLE' =>true
	),
	array('SNO'=>5,
		'TITLE'=>'e) Nalla closer', 
		'STATUS_BOX_NAME'=>'NALLA_CLOSURE_STATUS',
		'DATE_BOX_NAME'=>'NALLA_CLOSURE_TARGET_DATE',
		'OPTION_LIST' => $NALLA_CLOSURE_STATUS,
		'ENABLE' => true
	),
	array('SNO'=>6,
		'TITLE'=>'f) Canal E/W', 
		'STATUS_BOX_NAME'=>'CANAL_EARTH_WORK_STATUS',
		'DATE_BOX_NAME'=>'CANAL_EARTHWORK_TARGET_DATE',
		'OPTION_LIST' => $CANAL_EARTH_WORK_STATUS,
		'ENABLE' => (!$arrStatusCombo['CANAL_EARTHWORK_NA'])
	),
	array('SNO'=>7,
		'TITLE'=>'g) Canal Structures', 
		'STATUS_BOX_NAME'=>'CANAL_STRUCTURE_STATUS',
		'DATE_BOX_NAME'=>'CANAL_STRUCTURES_TARGET_DATE',
		'OPTION_LIST' => $CANAL_STRUCTURE_STATUS,
		'ENABLE' => (!$arrStatusCombo['CANAL_STRUCTURES_NA'])
	),
	array('SNO'=>8,
		'TITLE'=>'h) Canal Lining', 
		'STATUS_BOX_NAME'=>'CANAL_LINING_STATUS',
		'DATE_BOX_NAME'=>'CANAL_LINING_TARGET_DATE',
		'OPTION_LIST' => $CANAL_LINING_STATUS,
		'ENABLE' => (!$arrStatusCombo['CANAL_LINING_NA'])
	)
);
//showArrayValues($STATUS_VALUES);
foreach($arrStatus as $arrSt){
	$displayCSS =  '';
	$isRequired = 'class=""';
	if(in_array($STATUS_VALUES[$arrSt['STATUS_BOX_NAME']], array(0, 1, 5))){
		$displayCSS = 'none';
		$isRequired = '';
	}
	array_push($arrV, '"'.$arrSt['DATE_BOX_NAME'].'":{required : true}');
	echo '<tr>
			<td class="ui-widget-content">'.$arrSt['TITLE'].'</td>
			<td class="ui-widget-content">'.
				getRequiredSign('left').'
				<select name="'.$arrSt['STATUS_BOX_NAME'].'_ACHIEVE" id="'.$arrSt['STATUS_BOX_NAME'].'" 
					class="chosen-select " style="width:180px" '.
					( ($arrSt['ENABLE'])? '':'disabled="disabled"' )
					.' onchange="enableDisableDate(this.name, this.value, \''.$arrSt['DATE_BOX_NAME'].'\')">
					'.$arrSt['OPTION_LIST'].'
				</select>
			</td>
			<td class="ui-widget-content">
				<div id="req'.$arrSt['DATE_BOX_NAME'].'" style="float:left;display:'.$displayCSS.'">'.
				getRequiredSign('left').'</div>
				<input name="'.$arrSt['DATE_BOX_NAME'].'" id="'.$arrSt['DATE_BOX_NAME'].'" 
					readonly="readonly" type="text" size="48" maxlength="10" 
					style="width:90%;text-align:center;display:'.$displayCSS.'"
					value="'.myDateFormat($TARGET_DATES_VALUES[$arrSt['DATE_BOX_NAME']]).'" '.
					$isRequired.' >
			</td>
		  </tr>';
}?>
            </table>
        </td>
	</tr>
	</table>
</div>
</fieldset>
<div class="wrdlinebreak"></div>
<!--<div align="center" class="ui-state-default" style="padding:2px;margin:2px">
<strong>Data Submission Date :</strong>
<input type="text" id="PROJECT_SAVE_DATE" name="PROJECT_SAVE_DATE"
	 value="<?php echo myDateFormat($projectSetupValues['PROJECT_SAVE_DATE']);?>" 
     class="centertext" />
</div>
 -->
<?php }//editMode ?>
<div id="divCheckCode" class="ui-widget-header" style="text-align:center;font-size:18px;"></div>
<div id="divtest" class="ui-widget-header" style="text-align:center;font-size:18px;"></div>
<div id="mySaveDiv" align="right" class="mysavebar">
	<?php echo $buttons;?>
</div>
<!--<input type="text" id="test" value="1" />
<input type="button" value=" test " onclick="testme()" />-->
</form>
<script language="javascript" type="text/javascript">
/** ready OK */
$.validator.addMethod('myLess1', function(value, element, param) {
	var m_id = new String(element.id);
	var estifield="";
	if(m_id == 'LA_COMPLETED_HA_ACHIEVE'){
		estifield = "LA_HA";
	}else if(m_id == 'LA_COMPLETED_NO_ACHIEVE'){
		estifield = "LA_NO";
	}else if(m_id == 'FA_COMPLETED_HA_ACHIEVE'){
		estifield = "FA_HA";
	}else if((m_id=='EXPENDITURE_TOTAL') || (m_id == 'EXPENDITURE_WORK')){
		estifield = "AA_AMOUNT";
		//check for raa
		if($('#isRAA').is(":checked")){
			estifield = "RAA_AMOUNT";	
		}
	}else if(m_id == 'EXPENDITURE_WORKS_ACHIEVE'){
		estifield = "EXPENDITURE_WORK";
	}else{
		estifield = m_id.replace("_ACHIEVE", "");
	}
	//$('#divtest').html(m_id+" : ");
	//$('#divtest').append(estifield+"::");
	var estival = $('#' + estifield).val();
	var e = checkNo(estival);
	var a = checkNo(value);
	
	//$('#divtest').append(estival+"::");
	return this.optional(element) || ((a<=e) ? true:false);
	//return this.optional(element) || value <= $(param).val();
}, function(params, element) {
	var m_id = new String(element.id);
	//$('#divtest').append(m_id+" : z : ");
	var estifield="";
	if(m_id == 'LA_COMPLETED_HA_ACHIEVE'){
		estifield = "LA_HA";
	}else if(m_id == 'LA_COMPLETED_NO_ACHIEVE'){
		estifield = "LA_NO";
	}else if(m_id == 'FA_COMPLETED_HA_ACHIEVE'){
		estifield = "FA_HA";
	}else if((m_id=='EXPENDITURE_TOTAL') || (m_id == 'EXPENDITURE_WORK')){
		estifield = "AA_AMOUNT";
		//check for raa
		if($('#isRAA').is(":checked")){
			estifield = "RAA_AMOUNT";	
		}
	}else if(m_id == 'EXPENDITURE_WORKS_ACHIEVE'){
		estifield = "EXPENDITURE_WORK";
	}else{
		estifield = m_id.replace("_ACHIEVE", "");
	}
	var estival = $('#' + estifield).val();
	//$('#divtest').html(m_id+"fg");
	
	//$('#divtest').html( $(params).val() +"gg");
	var achVal = element.value;// $(m_id).val();
	var e = checkNo(estival);
	var a = checkNo(achVal);
	//$('#divtest').append(estival+" : y : " + achVal + " : u ");
	if(a<=e)
		return "";
	else
	  return 'Max Limit : ' + estival;
});

$.validator.addMethod('myLess', function(value, element, param) {
	var m_id = '#' + new String(element.id);
	//$('#divtest').html(m_id+"y");
	var estifield = m_id.replace("AIP_", "EIP_");
	var estival = $(estifield).val();
//	$('#divtest').html(estival);
	var e = checkNo(estival);
	var a = checkNo(value);
	return this.optional(element) || ((a<=e) ? true:false);
	//return this.optional(element) || value <= $(param).val();
},
function(params, element) {
	var m_id = '#' + new String(element.id);
	//$('#divtest').html(m_id+"z");
	var estifield = m_id.replace("AIP_", "EIP_");
	var estival = $(estifield).val();
	//$('#divtest').append('<br/>' + m_id+"fg");
	//$('#divtest').html( $(params).val() +"gg");
	//$('#divtest').append('<br/>' + element.value+ ': ' + "fg");

	var achVal = element.value;
	var e = checkNo(estival);
	var a = checkNo(achVal);
	//$('#divtest').append('<br/>' + element.value+ ': ' + "fg");
	if(a<=e)
		return "";
	else
	  return 'Max Limit : ' + estival;
});

var validator;
var objIPData;
var arrIPBlockData = new Array();
var arrBlockIds = new Array();
var mCurrentProjectMode = '';
var ipNa = false;
$().ready(function(){

    $("#PROJECT_SUB_TYPE_ID").change(function(){
        var val=$("#PROJECT_SUB_TYPE_ID").val();
       if(val==5 || val==25)
        $("#mi_pmon_type").val("1");
        else
            $("#mi_pmon_type").val("0");
        //
    });
	$(".chosen-select").select2();
	//set date for project start date
    $('#AA_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({ 
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true, maxDate:new Date 
	});
	$('#RAA_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true, 
		beforeShow: function(input, inst) {	return setMinMaxDate('#AA_DATE', 'today'); }
	});
	<?php if($editMode){
		echo 'window.arrBlockIds = ['.implode(',', $arrIPBlockIds).'];'."\n".
		'window.ipNa = '.(($IP_NA) ? 'true':'false').';'."\n";
	?>
	doSessionChange();
	$('#sessionMinDate, #sessionMaxDate, #sessionRealMinDate').attr("placeholder", "dd-mm-yyyy").datepicker({
		dateFormat:'dd-mm-yy'
	});
	/*$('#PROJECT_START_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({ 
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true,
		beforeShow: function(input, inst) {	return setMinMaxDate('#sessionMinDate', '#sessionMaxDate'); }
	});*/
	/*$('#PROJECT_SAVE_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({ 
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true,
		beforeShow: function(input, inst) {	
			return setMinMaxDate('#PROJECT_START_DATE', '#mytoday'); 
		}
	});*/
	$('#DATE_COMPLETION').attr("placeholder", "dd-mm-yyyy").datepicker({
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true,
		beforeShow: function(input, inst) {	return setMinMaxDate('#AA_DATE', ''); }
	});
	$('#LA_TARGET_DATE, #SPILLWAY_TARGET_DATE, #FLANKS_TARGET_DATE, ' +
		'#SLUICES_TARGET_DATE, #NALLA_CLOSURE_TARGET_DATE, ' + 
		'#CANAL_EARTHWORK_TARGET_DATE, #CANAL_STRUCTURES_TARGET_DATE, '+
		'#CANAL_LINING_TARGET_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({
			dateFormat:'dd-mm-yy', 
			changeMonth:true, 
			changeYear:true, 
			showOtherMonths: true,
			beforeShow: function(input, inst) {	return setMinMaxDate('#AA_DATE', '#DATE_COMPLETION'); }
	});
	var project_id = $('#PROJECT_ID').val();
	//change event
	$('#HEAD_WORK_DISTRICT_ID').on('change', function(){
		getBlockHW(this.value);
		getTehsilHW(this.value);
	});
	$('#DISTRICT_BENEFITED').on('change', function(){
		showBenefitedBlocks($("#DISTRICT_BENEFITED").select2("val"));
		getVillages($("#DISTRICT_BENEFITED").select2("val"));
	});
	$("#BLOCKS_BENEFITED").select2({ placeholder: "Select Block",allowClear: true});
	var $blockBenefited = $("#BLOCKS_BENEFITED");
	$blockBenefited.on("select2:unselect", function (evt) { 
		if (!evt) {
			var args = "{}";
		} else {
			var id = evt.params.data.id;
			var args = evt.params.data.text
			if(window.arrBlockIds.length>1){
				var index = window.arrBlockIds.indexOf(evt.params.data.id);
				if(index != -1){
					var item = window.arrBlockIds.splice( index, 1 );
					if(item){
						//alert(111111);
						$('#tr-bk-'+id).remove();
					}
				}
			}else{
				window.arrBlockIds = new Array();
				$('#tr-bk-'+id).remove();
			}
		}
		//alert(args);
	});
	$blockBenefited.on("select2:select", function (evt) { 
		if (!evt) {
			var args = "{}";
		} else {
			var args = evt.params.data.text;
			window.arrBlockIds.push(evt.params.data.id);
			showBlockIP(evt.params.data.id, evt.params.data.text);
		}
		//alert(args);
	});
	$('#VILLAGES_BENEFITED').on("select2-opening", function() {
		var noOfVillagesCovered = checkNo( $('#NO_VILLAGES_COVERED').val() );
		var selectedVillages = $('#VILLAGES_BENEFITED').select2("val");
		if(selectedVillages!=null){
			if(noOfVillagesCovered==selectedVillages.length){
				getCountVillages();
				return false;
			}
		}
		getCountVillages();
    });
	$('#VILLAGES_BENEFITED').on('change', function(e){
		getCountVillages();
	});
	$( "#btnfrm2" ).click(function() {
		$('#frm2').slideToggle('slow');
	});
	$("#VILLAGES_BENEFITED").select2();
	getCountVillages();
	<?php }//$editMode ?>
	getToolTips();
	//setSelect2();
	window.validator = 
	$("#frmProject").validate({
		rules: {
			"PROJECT_NAME" : {required : true},
			"PROJECT_NAME_HINDI" : {required : true},
			"AA_NO" : {required : true},
			"AA_DATE" : {required : true, indianDate:true},
			"AA_AMOUNT" : {required : true, min:0, number:true},
			
			"LONGITUDE_D":{required : true, number:true, range:[80, 84]},
			"LONGITUDE_M":{required : true, number:true, range:[0, 60]},
			"LONGITUDE_S":{required : true, number:true, range:[0, 60]},

			"LATITUDE_D":{required : true, number:true, range:[17, 24]},
			"LATITUDE_M":{required : true, number:true, range:[0, 60]},
			"LATITUDE_S":{required : true, number:true, range:[0, 60]}

	<?php if($editMode){?>,
			"RAA_NO":{required : true},
			"RAA_DATE":{required : true,indianDate:true},
			"RAA_AMOUNT":{required : true},

			"PROJECT_START_DATE": { required : true, indianDate:true},
			"EXPENDITURE_TOTAL":{required : true, number:true, minStrict:0, myLess1:""},
			"EXPENDITURE_TOTAL_ACHIEVE":{required : true, number:true, min:0, myLess1:""},
			"EXPENDITURE_WORK":{required : true, number:true, min:0, myLess1:""},
			"EXPENDITURE_WORKS_ACHIEVE":{required : true, number:true, min:0, myLess1:""},
			"DATE_COMPLETION":{required : true, indianDate:true},
			"PROJECT_SAVE_DATE":{required : true, indianDate:true, }
			<?php if(count($arrV)>0){echo ','.implode(',', $arrV);}?>
	<?php }?>
		},
		messages: {
			"PROJECT_NAME" : {required : "Project Name is Must"},
			"PROJECT_NAME_HINDI" : {required : "परियोजना का नाम जरूरी है..."},
			"AA_NO" : {required : "Required - AA No "},
			"AA_DATE" : {required : "Required - AA Date"},
			"AA_AMOUNT" : {required : "Required - AA Amount", min:"Required Positive Amount"}
		}
	});

    $('#PROJECT_NAME').alphanum({
        allow              : ' -:.,;[](){}%',
        allowSpace         : true,
        allowNumeric       : true,
        allowUpper         : true,
        allowLower         : true,
        allowCaseless      : true,
        allowLatin         : true,
        allowOtherCharSets : false,
        forceUpper         : false,
        forceLower         : false,
        maxLength          : 1000
    });
    $('#PROJECT_NAME_HINDI').alphanum({
        allow              : ' -:.,;[](){}%',
        allowSpace         : true,
        allowNumeric       : true,
        allowUpper         : false,
        allowLower         : false,
        allowCaseless      : true,
        allowLatin         : true,
        allowOtherCharSets : true,
        forceUpper         : false,
        forceLower         : false,
        maxLength          : 1000
    });

    $('#NALLA_RIVER').alphanum({
        allow              : ' -:.,',
        allowSpace         : true,
        allowNumeric       : false,
        allowUpper         : true,
        allowLower         : true,
        allowCaseless      : true,
        allowLatin         : true,
        allowOtherCharSets : false,
        forceUpper         : false,
        forceLower         : false,
        maxLength          : 1000
    });

	<?php if($editMode){?>
	$("#VILLAGES_BENEFITED").select2();
	checkAchievementReady();
	setRules();
	checkTotalExp();
	<?php }//$editMode?>
});
//
function getCountVillages(){
	var selectedVillages = $('#VILLAGES_BENEFITED').select2("val");
	if(selectedVillages!=null){
		$('#villageCount').html(selectedVillages.length);
	}else{
		$('#villageCount').html(0);
	}
}
//
function checkValidation(){
	var selectList = new Array();
	selectList.push( Array('PROJECT_SUB_TYPE_ID', 'Select Project Sub Type'));
	selectList.push( Array('AA_AUTHORITY_ID', 'Select Authority'));
	selectList.push( Array('DISTRICT_ID', 'Select District'));
	var mSelect = validateMyCombo(selectList);
	if(mSelect>0){
		alert('Please Check Errors');
		return;
	}
}
//
function checkForStatus(){
	$selDate = $('#AA_DATE').val();
}
//
function check_validation(){
	$('.error').each(function(){ 
		var errId = $(this).attr('id');			  
		//$('#'+errId).css({backgroundColor:"#F00", color:"#FFF" });
		if(errId){
			$('#'+errId).parents('div:eq(0)').css({display:"block"});		
			$('#'+errId).parents('div:eq(0)').siblings().css({color:"#F00", border:"#F00 1px solid" });
		}
	});
}
//
var mCurrentProjectMode = '';
function saveProject(mode){
	//mode 0-save_edit 1-save 2-save_modification
	$('#saveMode').val(mode);
	window.mCurrentProjectMode = mode;
	if(mode==2)	checkTotalExp();
	var selectList = new Array();
	if(mode!=2){
		selectList.push( Array('PROJECT_SUB_TYPE_ID', 'Select Project Sub Type', false));
	}
	selectList.push( Array('AA_AUTHORITY_ID', 'Select AA Authority', false));
	<?php if($this->session->userdata('HOLDING_PERSON')==3){?>
		selectList.push( Array('OFFICE_EE_ID', 'Select Division', true));
	<?php }?>
	selectList.push( Array('OFFICE_SDO_ID', 'Select Sub Division', true, false));
	
	if(mode!=2){
		selectList.push( Array('HEAD_WORK_DISTRICT_ID', 'Select Head Work District', true));
	}
	if(mode==2){
		//selectList.push( Array('ALLOCATION_BUDGET_HEAD_ID', 'Allocation of Budget Head', false));
		//selectList.push( Array('FUND_ASSIS_ID', 'Select Fund Assitance', false));
		selectList.push( Array('HEAD_WORK_BLOCK_ID', 'Select Head Work Block', false));
		selectList.push( Array('HEAD_WORK_TEHSIL_ID', 'Select Head Work Tehsil', false));
		selectList.push( Array('ASSEMBLY_CONST_ID', 'Select Assembly', false));
		selectList.push( Array('LA_CASES_STATUS', 'Select LA Case Status', true));
		selectList.push( Array('SPILLWAY_STATUS', 'Select Spillway Status', true));
		selectList.push( Array('FLANK_STATUS', 'Select Flank Status', true));
		selectList.push( Array('SLUICES_STATUS', 'Select Sluices Status', true));
		selectList.push( Array('NALLA_CLOSURE_STATUS', 'Select Nalla Closure Status', true));
		selectList.push( Array('CANAL_EARTH_WORK_STATUS', 'Select Canal Earthwork Status', true));
		selectList.push( Array('CANAL_STRUCTURE_STATUS', 'Select Canal Structure Status', true));
		selectList.push( Array('CANAL_LINING_STATUS', 'Select Canal Lining Status', true));
		<?php if(!$monthlyRecordExists){?>
		selectList.push( Array('SESSION_ID', 'Select Session', true));
		<?php }?>		
		//selectList.push( Array('GRANT_NO', 'Select Grant no', true));
		selectList.push( Array('DISTRICT_BENEFITED', 'Select Benefited District', true, false));
		selectList.push( Array('BLOCKS_BENEFITED', 'Select Benefited Block', true, false));
		selectList.push( Array('ASSEMBLY_BENEFITED', 'Select Benefited Assembly Const.', true, false));
		selectList.push( Array('VILLAGES_BENEFITED', 'Select Benefited Villages', true, false));
		//selectList.push( Array('VILLAGE_ID', 'Select Village', true));
	}
	var mSelect = validateMyCombo(selectList);
	mStCount = 0;
	if(mode==2){
		var prevSettings = new Array();
		prevSettings[0] = $('#frm1').css('display');
		prevSettings[1] = $('#frm2').css('display');
		prevSettings[2] = $('#frm4').css('display');
		//show all div
		$('#frm1').css('display', '');
		$('#frm2').css('display', '');
		$('#frm4').css('display', '');
		
		if( $('#startInSession').val()==1){
			//in current session
			var arrValidValues = new Array("1","2");
			var arrFields = new Array(
				"LA_CASES_STATUS", "SPILLWAY_STATUS", "FLANK_STATUS", "SLUICES_STATUS", 
				"NALLA_CLOSURE_STATUS", "CANAL_EARTH_WORK_STATUS", "CANAL_STRUCTURE_STATUS", "CANAL_LINING_STATUS"
			);
			for(i=0; i<arrFields.length;i++){
				res = $.inArray($('#'+arrFields[i]).val(), arrValidValues);
				if(res==-1) mStCount++;
			}
		}
	}
	var myValidation = $("#frmProject").valid();

	if( !(mSelect==0 && myValidation && mStCount==0)){
		if( mSelect==0 && myValidation){
			var msession = '';
			<?php if($monthlyRecordExists){?>
				msession = '<?php echo $SESSION_OPTIONS;?>';
			<?php }else{?>
				msession = $('#SESSION_ID[selected]').text();
			<?php }?>
			alert(
				'परियोजना की प्रारंभ तिथि ' + $('#AA_DATE').val() + 
				' सॉफ्टवेयर में परियोजना की प्रविष्टी वाले सत्र ' + msession + ' में है ' + "\n" + 
				'अतः पिछले सत्र की स्थिति में फॉर्म 3 में ' + (mStCount) + 
				' कम्पोनेन्ट के स्थिति को ' + "\n" + ' Not Started रखना होगा ' +  "\n\n" + 
				'This project\'s start Date (' + $('#AA_DATE').val() + 
				')is in Selected Session (' + msession + '). ' + "\n" + 
				' You have to select Status as Not Started in : ' + 
				(mStCount) + ' component in this form.'
			);
		}else{
			alert('You have : ' + ( window.validator.numberOfInvalids() + mSelect  + mStCount) + ' errors in this form.');
		}
		return ;
	}
	if(myValidation){
		checkProjectCode();
	}else{
		showMyAlert('Error...', 'There is/are some Required Data... <br />Please Check & Complete it.', 'warn');
	}
}
function checkProjectCode(){
	$('#divCheckCode').show();
	var params = {
		'divid':'divCheckCode1', 
		'url':'checkProjectCode', 
		'data':{
			'PROJECT_ID':$('#PROJECT_ID').val(), 
			'DISTRICT_ID':$('#HEAD_WORK_DISTRICT_ID').val(),
			'LONGITUDE_D':$('#LONGITUDE_D').val(),
			'LONGITUDE_M':$('#LONGITUDE_M').val(),
			'LONGITUDE_S':$('#LONGITUDE_S').val(),
			'LATITUDE_D':$('#LATITUDE_D').val(),
			'LATITUDE_M':$('#LATITUDE_M').val(),
			'LATITUDE_S':$('#LATITUDE_S').val()
		}, 
		'donefname': 'doneCheckCodeProject', 
		'failfname' :'failProject', 
		'alwaysfname':'doThisProjectCheck'
	};
	callMyAjax(params);
}
function doneCheckCodeProject(response){
	var myData = parseMyResponse(response);
	//alert(myData.success);
	if(parseInt(myData.success)==0){
		//alert(myData.message);
		$('#divCheckCode').html("Unable to Save due to Duplicate Data for Project...<br />" + myData.message);
		$('#divCheckCode').show();
	}
	if(parseInt(myData.success)==1){
		$('#divCheckCode').hide();
		var params = {
			'divid':'mySaveDiv', 
			'url':'saveProjectSetup', 
			'data':$('#frmProject').serialize(), 
			'donefname': 'doneProject', 
			'failfname' :'failProject', 
			'alwaysfname':'none'
		};
		callMyAjax(params);
	}else{
		$('#divCheckCode').html("Duplicate Data for Project...<br />Unable to Save Project <br /> " + myData.message);
	}
}
function doThisProjectCheck(){
}
function doneProject(response){
	$('#divCheckCode').hide();
	$("#projectList").trigger('reloadGrid');
    $("#projectList1").trigger('reloadGrid');
	if (window.mCurrentProjectMode==0){
		//replace dialog box with msg
		$('#modalBox').html(parseAndShowMyResponse(response));
	}else{
		$('#message').html(parseAndShowMyResponse(response));
		$("#modalBox").dialog('close');
	}
	gridReload();
}
function failProject(){}
function doThisProject(){}
//
function enableDisableDate(sourceControl, currentValue, targetControl){
	if (currentValue==1 || currentValue==0 || currentValue==5 ){
		$('#'+targetControl).attr('disable', true).removeClass('hasDatepicker').removeClass('required');
		$('#req'+targetControl).hide();
		enableDisableDatePicker(targetControl, true);
	}else{
		$('#'+targetControl).attr('disable', false).addClass('hasDatepicker').addClass('required');
		$('#req'+targetControl).show();
		enableDisableDatePicker(targetControl, false);
	}
}
//
function enableDisableDatePicker(ctrl, status){
	//$( '#'+ctrl ).datepicker( "option", { disabled: status } );
	if(status){//disable
		$( '#'+ctrl ).hide();
	}else{
		$( '#'+ctrl ).show();
	}
}
//
function getS(status){
	$("#CANAL_EARTH_WORK_STATUS").select2("enable", status);
}
//
function showHideRAA(status){
	if(status){
		$('.raa').show();
		return;
		$('#RAA_NO').show();
		$('#RAA_DATE').show();
		$('#RAA_AMOUNT').show();
		$('#RAA_AUTHORITY_ID').show();
	}else{
		$('.raa').hide();
		return;
		$('#RAA_NO').hide();
		$('#RAA_DATE').hide();
		$('#RAA_AMOUNT').hide();
		$('#RAA_AUTHORITY_ID').hide();
	}
}
function getSDOOffices(eeid){
	setLoadingStatus(true, 'OFFICE_SDO_ID');
	$.ajax({
		type:"POST",
		url:'getSDOOffices',
		data:{'eeid': eeid},
		success:function(data){
			$('#OFFICE_SDO_ID').html(data);
			$('#OFFICE_SDO_ID').trigger("updatecomplete");
			setLoadingStatus(false, 'OFFICE_SDO_ID');
		}
	});
}
<?php if($editMode){ ?>
function setEstimationFields(sno, mName, status){
	//alert(sno, name);
	var requiredField1 = mName.substr(0, (mName.length-3));
	var arrIntFields = new Array("LA_NO", "LA_NO_ACHIEVE", "LA_COMPLETED_NO_ACHIEVE", "CANAL_STRUCTURES", "CANAL_STRUCTURES_ACHIEVE",
		"IRRIGATION_POTENTIAL", "IRRIGATION_POTENTIAL_KHARIF", "IRRIGATION_POTENTIAL_RABI");
	//$('#'+requiredField1).prop('disabled', status);
	if(status) $('#'+requiredField1).val('');
	var arrReadOnlyFields = new Array();
	//alert($('#startInSession').val());
	/*if(	$('#startInSession').val()==1){
		$('#'+requiredField1+'_ACHIEVE').prop('disabled', true);
	}else{
		$('#'+requiredField1+'_ACHIEVE').prop('disabled', status);
		$('#'+requiredField1+'_ACHIEVE').val('');
	}*/
	/*if(!status){
		$('#req_' + sno).show();
		$('#reqa_' + sno).show();
	}else{
		$('#req_' + sno).hide();
		$('#reqa_' + sno).hide();
	}*/
	switch(sno){
		case 1://LA case
			//$("#LA_HA, #LA_NO, #LA_HA_ACHIEVE, #LA_NO_ACHIEVE, #LA_COMPLETED_HA_ACHIEVE, #LA_COMPLETED_NO_ACHIEVE").prop('disabled', status);
			//alert(status);
			var arrRulesFields = new Array(
				"LA_HA", "LA_NO", "LA_HA_ACHIEVE", "LA_NO_ACHIEVE", 
				"LA_COMPLETED_HA_ACHIEVE", "LA_COMPLETED_NO_ACHIEVE"
			);
			var arrDisableFields = new Array(
				"LA_HA", "LA_NO", "LA_HA_ACHIEVE", "LA_NO_ACHIEVE", 
				"LA_COMPLETED_HA_ACHIEVE", "LA_COMPLETED_NO_ACHIEVE"
			);
			if(status){
				//disable controls
				arrRulesFields = new Array();
				//alert(' sno 2 : ' + sno);
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array("LA_HA", "LA_NO");
					arrDisableFields = new Array( "LA_HA_ACHIEVE", "LA_NO_ACHIEVE", "LA_COMPLETED_HA_ACHIEVE", "LA_COMPLETED_NO_ACHIEVE");
					/*$('#reqa_12').hide();
					$('#req_12').hide();
					arrRulesFields = new Array();
					$('#CANAL_STRUCTURES_ACHIEVE, #CANAL_MASONRY_ACHIEVE').prop('disabled', true).val('').rules("remove");*/
				}else{
					//enable both controls
					arrDisableFields = new Array();
					/*$('#req_12').show();
					$('#reqa_12').show();
					$('#CANAL_STRUCTURES, #CANAL_STRUCTURES_ACHIEVE, #CANAL_MASONRY, #CANAL_MASONRY_ACHIEVE').prop('disabled', status);*/
				}
				//enable
				//$('#LA_HA').val('');
				/*$("#LA_NO").rules( "add", {
					required: true,
					minStrict: 0,
					digits:true,
					messages: {required: "Required.",  minStrict: "Minimum value should be greater than 0"}
				});
				$("#LA_HA").rules( "add", {
					required: true,
					minStrict: 0,
					number:true,
					messages: {required: "Required.",  minStrict: "Minimum value should be greater than 0"}
				});*/
				//alert(' sno 1 : ' + sno);
				/*$('#req_2').show();
				$('#reqa_2').show();
				$('#reqa_3').show();
				$('#reqa_4').show();
				if( $('#startInSession').val()==1){
					arrRulesFields = new Array("LA_HA", "LA_NO");
					$('#LA_HA_ACHIEVE, #LA_NO_ACHIEVE, #LA_COMPLETED_HA_ACHIEVE, #LA_COMPLETED_NO_ACHIEVE').prop('disabled', true).val('').rules("remove");
					$('#req_1').hide();
					$('#reqa_1').hide();
					$('#req_2').hide();
					$('#reqa_2').hide();
					$('#reqa_3').hide();
					$('#reqa_4').hide();
				}else{
					$('#LA_HA_ACHIEVE, #LA_NO_ACHIEVE, #LA_COMPLETED_HA_ACHIEVE, #LA_COMPLETED_NO_ACHIEVE').prop('disabled', false);
				}*/
			}
			break;
		case 5://Forest Case
			//alert('Forest');
			//$('#FA_HA, #FA_HA_ACHIEVE, #FA_COMPLETED_HA_ACHIEVE').prop('disabled', status);
			var arrRulesFields = new Array("FA_HA", "FA_HA_ACHIEVE", "FA_COMPLETED_HA_ACHIEVE");
			var arrDisableFields = new Array("FA_HA", "FA_HA_ACHIEVE", "FA_COMPLETED_HA_ACHIEVE");
			if(status){
				//disable controls
				arrRulesFields = new Array();
			}else{
				if($('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array('FA_HA');
					arrDisableFields = new Array('FA_HA_ACHIEVE', 'FA_COMPLETED_HA_ACHIEVE');
				}else{
					//enable both controls
					arrDisableFields = new Array();
				}
			}
			break;
		case 7: //HW_EW
			var arrRulesFields = new Array("HEAD_WORKS_EARTHWORK", "HEAD_WORKS_EARTHWORK_ACHIEVE");
			var arrDisableFields = new Array("HEAD_WORKS_EARTHWORK", "HEAD_WORKS_EARTHWORK_ACHIEVE");
			if(status){
				//disable controls
				arrRulesFields = new Array();
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array('HEAD_WORKS_EARTHWORK');
					arrDisableFields = new Array("HEAD_WORKS_EARTHWORK_ACHIEVE");
				}else{
					//enable both controls
					arrDisableFields = new Array();
				}
			}
			break;
		case 8: //HW_MAS
			var arrRulesFields = new Array("HEAD_WORKS_MASONRY", "HEAD_WORKS_MASONRY_ACHIEVE");
			var arrDisableFields = new Array("HEAD_WORKS_MASONRY", "HEAD_WORKS_MASONRY_ACHIEVE");
			if(status){
				//disable controls
				arrRulesFields = new Array();
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array('HEAD_WORKS_MASONRY');
					arrDisableFields = new Array("HEAD_WORKS_MASONRY_ACHIEVE");
				}else{
					//enable both controls
					arrDisableFields = new Array();
				}
			}
			break;
		case 9: //HW_STEEL
			var arrRulesFields = new Array("STEEL_WORKS", "STEEL_WORKS_ACHIEVE");
			var arrDisableFields = new Array("STEEL_WORKS", "STEEL_WORKS_ACHIEVE");
			if(status){
				//disable controls
				arrRulesFields = new Array();
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array('STEEL_WORKS');
					arrDisableFields = new Array("STEEL_WORKS_ACHIEVE");
				}else{
					//enable both controls
					arrDisableFields = new Array();
				}
			}
			break;
		case 10: //CANAL_EW
			var arrRulesFields = new Array("CANAL_EARTHWORK", "CANAL_EARTHWORK_ACHIEVE");
			var arrDisableFields = new Array("CANAL_EARTHWORK", "CANAL_EARTHWORK_ACHIEVE");
			if(status){
				//disable controls
				arrRulesFields = new Array();
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array('CANAL_EARTHWORK');
					arrDisableFields = new Array("CANAL_EARTHWORK_ACHIEVE");
				}else{
					//enable both controls
					arrDisableFields = new Array();
				}
			}
			break;
		case 11: //CANAL_ST
			var arrRulesFields = new Array("CANAL_STRUCTURES", "CANAL_STRUCTURES_ACHIEVE", "CANAL_MASONRY", "CANAL_MASONRY_ACHIEVE");
			var arrDisableFields = new Array("CANAL_STRUCTURES", "CANAL_STRUCTURES_ACHIEVE", "CANAL_MASONRY", "CANAL_MASONRY_ACHIEVE");
			if(status){
				//disable controls
				arrRulesFields = new Array();
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array('CANAL_STRUCTURES', 'CANAL_MASONRY');
					arrDisableFields = new Array('CANAL_STRUCTURES_ACHIEVE', 'CANAL_MASONRY_ACHIEVE');
				}else{
					//enable both controls
					arrDisableFields = new Array();
				}
			}
			break;
		case 13: //CANAL_LINING
			var arrRulesFields = new Array("CANAL_LINING", "CANAL_LINING_ACHIEVE");
			var arrDisableFields = new Array("CANAL_LINING", "CANAL_LINING_ACHIEVE");
			if(status){
				//disable controls
				arrRulesFields = new Array();
				//$('#CANAL_LINING, CANAL_LINING_ACHIEVE').prop('disabled', status).val('').rules("remove");
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array('CANAL_LINING');
					arrDisableFields = new Array("CANAL_LINING_ACHIEVE");
					//arrRulesFields = new Array();
					//$('#CANAL_LINING_ACHIEVE').prop('disabled', true).val('').rules("remove");
				}else{
					//enable both controls
					arrDisableFields = new Array();
					//$('#CANAL_LINING, #CANAL_LINING_ACHIEVE').prop('disabled', status);
				}
			}
			break;
		case 14: //ROAD WORKS
			var arrRulesFields = new Array("ROAD_WORKS", "ROAD_WORKS_ACHIEVE");
			var arrDisableFields = new Array("ROAD_WORKS", "ROAD_WORKS_ACHIEVE");
			if(status){
				//disable controls
				arrRulesFields = new Array();
				//$('#ROAD_WORKS, #ROAD_WORKS_ACHIEVE').prop('disabled', status).val('').rules("remove");
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array('ROAD_WORKS');
					arrDisableFields = new Array("ROAD_WORKS_ACHIEVE");
					//arrRulesFields = new Array();
					//$('#ROAD_WORKS_ACHIEVE').prop('disabled', true).val('').rules("remove");
				}else{
					//enable both controls
					arrDisableFields = new Array();
					//$('#ROAD_WORKS, #ROAD_WORKS_ACHIEVE').prop('disabled', status);
				}
			}
			break;
		case 15:
			window.ipNa = status;
			var arrRulesFields = new Array();
			var arrDisableFields = new Array();
			if(status){
				//disable controls
				arrRulesFields = new Array();
				arrDisableFields = new Array("IRRIGATION_POTENTIAL_RABI", "IRRIGATION_POTENTIAL_KHARIF", "IRRIGATION_POTENTIAL", 
					"IRRIGATION_POTENTIAL_RABI_ACHIEVE", "IRRIGATION_POTENTIAL_KHARIF_ACHIEVE", "IRRIGATION_POTENTIAL_ACHIEVE"
				);
			}else{
				if( $('#startInSession').val()==1){
					//disable achieve controls
					arrRulesFields = new Array();
					arrDisableFields = new Array("IRRIGATION_POTENTIAL_RABI_ACHIEVE", "IRRIGATION_POTENTIAL_KHARIF_ACHIEVE", "IRRIGATION_POTENTIAL_ACHIEVE");
					arrReadOnlyFields = new Array("IRRIGATION_POTENTIAL_RABI", "IRRIGATION_POTENTIAL_KHARIF", "IRRIGATION_POTENTIAL");
				}else{
					//enable both controls
					arrDisableFields = new Array();
					arrReadOnlyFields = new Array("IRRIGATION_POTENTIAL_RABI", "IRRIGATION_POTENTIAL_KHARIF", "IRRIGATION_POTENTIAL", 
						"IRRIGATION_POTENTIAL_RABI_ACHIEVE", "IRRIGATION_POTENTIAL_KHARIF_ACHIEVE", "IRRIGATION_POTENTIAL_ACHIEVE"
					);
				}
			}
			//
			/*$('#IRRIGATION_POTENTIAL, #IRRIGATION_POTENTIAL_ACHIEVE').prop('readonly', true);
			if( $('#startInSession').val()==1){
				$('#IRRIGATION_POTENTIAL_RABI_ACHIEVE, #IRRIGATION_POTENTIAL_KHARIF_ACHIEVE')
					.prop('disabled', true).val('');
			}else{
				$('#IRRIGATION_POTENTIAL_RABI_ACHIEVE, #IRRIGATION_POTENTIAL_KHARIF_ACHIEVE, #IRRIGATION_POTENTIAL_ACHIEVE')
					.prop('disabled', status);
				if(status){
					$('#IRRIGATION_POTENTIAL_RABI_ACHIEVE, #IRRIGATION_POTENTIAL_KHARIF_ACHIEVE, #IRRIGATION_POTENTIAL_ACHIEVE').val('');
				}
			}
			$('#IRRIGATION_POTENTIAL_RABI, #IRRIGATION_POTENTIAL_KHARIF').prop('disabled', status).val('');
			var arrRulesFields = new Array(
				"IRRIGATION_POTENTIAL_RABI", "IRRIGATION_POTENTIAL_KHARIF", "IRRIGATION_POTENTIAL", 
				"IRRIGATION_POTENTIAL_RABI_ACHIEVE", "IRRIGATION_POTENTIAL_KHARIF_ACHIEVE", "IRRIGATION_POTENTIAL_ACHIEVE"
			);*/
			if(status){
				$('#req_' + sno+'kharif').hide();
				$('#reqa_' + sno+'kharif').hide();
				$('#req_' + sno+'rabi').hide();
				$('#reqa_' + sno+'rabi').hide();
				for(ii=0; ii<window.arrBlockIds.length;ii++){
					blockid = window.arrBlockIds[ii];
					$('#req_ipak_' + blockid).hide();
					$('#req_ipar_' + blockid).hide();
					$('#req_ipek_' + blockid).hide();
					$('#req_iper_' + blockid).hide();
				}
			}else{
				$('#req_' + sno+'kharif').show();
				$('#reqa_' + sno+'kharif').show();
				$('#req_' + sno+'rabi').show();
				$('#reqa_' + sno+'rabi').show();
				for(ii=0; ii<window.arrBlockIds.length;ii++){
					blockid = window.arrBlockIds[ii];
					$('#req_ipak_' + blockid).show();
					$('#req_ipar_' + blockid).show();
					$('#req_ipek_' + blockid).show();
					$('#req_iper_' + blockid).show();
				}
			}
			mystatus = false;
			if( $('#startInSession').val()==1) mystatus = true;
			if(status==1) mystatus = true;
			arrIPRulesFields = new Array();
			arrIPRulesRemoveFields = new Array();
			for(ii=0; ii<window.arrBlockIds.length;ii++){
				blockId = window.arrBlockIds[ii];
				$('#BLOCK_EIP_K_' + blockId).prop('disabled', status).val('');
				$('#BLOCK_EIP_R_' + blockId).prop('disabled', status).val('');
				$('#BLOCK_EIP_T_' + blockId).prop('disabled', status).val('');
				
				$('#BLOCK_AIP_K_' + blockId).prop('disabled', mystatus).val('');
				$('#BLOCK_AIP_R_' + blockId).prop('disabled', mystatus).val('');
				$('#BLOCK_AIP_T_' + blockId).prop('disabled', mystatus).val('');
				if(status){
					arrIPRulesRemoveFields.push('BLOCK_EIP_K_' + blockId);
					arrIPRulesRemoveFields.push('BLOCK_EIP_R_' + blockId);
					arrIPRulesRemoveFields.push('BLOCK_AIP_K_' + blockId);
					arrIPRulesRemoveFields.push('BLOCK_AIP_R_' + blockId);
				}else{
					if(mystatus){
						arrIPRulesRemoveFields.push('BLOCK_AIP_K_' + blockId);
						arrIPRulesRemoveFields.push('BLOCK_AIP_R_' + blockId);
					}else{
						arrIPRulesFields.push('BLOCK_AIP_K_' + blockId);
						arrIPRulesFields.push('BLOCK_AIP_R_' + blockId);
					}
					arrIPRulesFields.push('BLOCK_EIP_K_' + blockId);
					arrIPRulesFields.push('BLOCK_EIP_R_' + blockId);
				}
			}
			for(i=0; i<arrIPRulesFields.length;i++){
				$('#'+ arrIPRulesFields[i]).rules( "add", {
					required: true,
					min: 0,
					myLess1:'',
					digits:true,
					messages: {required: "Required."}
				});
			}
			for(i=0; i<arrIPRulesRemoveFields.length;i++){
				$('#'+ arrIPRulesRemoveFields[i]).rules( "remove");
			}
			break;
	}
	
	//alert('ruls:' + arrRulesFields.join(",") );
	//enable disable control
	for(i=0; i<arrRulesFields.length;i++){
		ctrlName = '#'+arrRulesFields[i];
		$(ctrlName).prop('disabled', false);
		$('#req_'+arrRulesFields[i]).show();
	}
	//alert('ruls:' + arrRulesFields.join(",") +"\n"+ ' disable : ' + arrDisableFields.join(",") +"\n"+ arrDisableFields.length +"\n"+ ' arrReadOnlyFields : ' + arrReadOnlyFields.join(","));
	for(i=0; i<arrDisableFields.length;i++){
		ctrlName = '#'+arrDisableFields[i];
		//alert('disable : ' + ctrlName );
		var rul = false;
		try{
			var rul = $(ctrlName).rules("remove");
		}catch(err){
			
		}
		if(rul){
			$(ctrlName).rules("remove");
		}
		//$(ctrlName).rules("remove");
		$(ctrlName).val('');
		$(ctrlName).prop('disabled', true);
		$('#req_'+arrDisableFields[i]).hide();
	}
	for(i=0; i<arrReadOnlyFields.length;i++){
		ctrlName = '#'+arrReadOnlyFields[i];
		$(ctrlName).prop('disabled', false);
		$(ctrlName).prop('readonly', true);
	}
	
	//enable/disable rules 
	//alert(arrRulesFields.join(","));
	for(i=0; i<arrRulesFields.length;i++){
		ctrlName = '#'+arrRulesFields[i];
		//alert(status);
		if(status) {
			//alert(i + ' : ' +arrRulesFields[i]);
			//$(ctrlName).rules("remove");
		}else{
			startPositionOfAchieve = arrRulesFields[i].length-8;
			var rightText = arrRulesFields[i].substr(startPositionOfAchieve);
			//alert(arrRulesFields[i] + " : " + rightText);
			if(rightText=='_ACHIEVE'){
				//arrExclude = new Array("LA_COMPLETED_HA_ACHIEVE", "LA_COMPLETED_NO_ACHIEVE");
				//var estiField = arrRulesFields[i].replace('_ACHIEVE', '');
				/*if(arrRulesFields[i]=="LA_COMPLETED_HA_ACHIEVE"){
					$('#'+ arrRulesFields[i]).rules( "add", {
						required: true,
						min: 0,
						number:true,
						messages: {required: "Required."}
					});
				}else if(arrRulesFields[i]=="LA_COMPLETED_NO_ACHIEVE"){
					$('#'+ arrRulesFields[i]).rules( "add", {
						required: true,
						min: 0,
						digits:true,
						messages: {required: "Required."}
					});
				}else if(arrRulesFields[i]=="FA_COMPLETED_HA_ACHIEVE"){
					$('#'+ arrRulesFields[i]).rules( "add", {
						required: true,
						min: 0,
						digits:true,
						messages: {required: "Required."}
					});
				}else */
				if(jQuery.inArray(arrRulesFields[i], arrIntFields) != -1){
					$(ctrlName).rules( "add", {
						required: true,
						min: 0,
						myLess1:'',
						digits:true,
						messages: {required: "Required."}
					});
				}else{
					//alert(arrRulesFields[i]);
					$(ctrlName).rules( "add", {
						required: true,
						min: 0,
						myLess1:'',
						number:true,
						messages: {required: "Required."}
					});
				}
			}else{
				if(jQuery.inArray(arrRulesFields[i], arrIntFields) != -1){
					if(arrRulesFields[i]=='CANAL_STRUCTURES'){
						$(ctrlName).rules( "add", {
							required: true,
							digits:true,
							messages: {required: "Required."}
						});
					}else{
						$(ctrlName).rules( "add", {
							required: true,
							minStrict: 0,
							digits:true,
							messages: {
								required: "Required.",
								minStrict: "Minimum value should be greater than 0"
							}
						});
					}
					//alert(arrRulesFields[i]);
				}else{
					try {
						if(arrRulesFields[i]=='CANAL_MASONRY'){
							$(ctrlName).rules( "add", {
								required: true,
								min: 0,
								number:true,
								messages: {required: "Required."}
							});
						}else{
							//alert(arrRulesFields[i]);
							$(ctrlName).rules( "add", {
								required: true,
								minStrict: 0,
								number:true,
								messages: {
									required: "Required.",
									minStrict: "Minimum value should be greater than 0"
								}
							});
						}
					}catch(err) {
						alert(arrRulesFields[i] + " " + err.message);
					}
				}
			}
		}
	}
	var arrFields = new Array(
		"LA_CASES_STATUS", "CANAL_EARTH_WORK_STATUS", "CANAL_STRUCTURE_STATUS", "CANAL_LINING_STATUS"
	);
	var mName ='';
	switch(sno){
		case 1: mName = arrFields[0]; break;
		case 10:
		case 11:mName = arrFields[ (sno-9)]; break;
		case 13: mName =  "CANAL_LINING_STATUS"; break;
	}
	//alert('Name:' + mName + ' sno:' + sno + ' stt:' + status);
	if(mName!=""){
		//alert(status);
		//if(status) $('#' + mName).val(1);
		//xxx
		if(status){ 
			//$('#' + mName).trigger("updatecomplete");
		}
		$('#' + mName).prop("disabled", (status));
		//$('#' + mName).select2("enable", (!status));
		targetDate = mName.replace('_STATUS', '_TARGET_DATE');
		if(status){
			$('#' + mName).select2("val", 1);
			if( $('#' + targetDate).css('display')=='none'){
			}else{
				$('#' + targetDate).css("display", 'none');
			}
		}else{
			var valOfCombo = $('#' + mName).select2("val");
			if(valOfCombo==5){
				$('#' + targetDate).css("display", 'none');
			}else{
				$('#' + targetDate).css("display", 'block');
			}
		}
	}
}
//
function getBlockHW(dist_id){
	setLoadingStatus(true, 'HEAD_WORK_BLOCK_ID');
	$.ajax({
		type: "POST",
		url: "getBlockOptionsByDistrict",
		data : {'dist_id':dist_id},
		success: function(msg){
			$("#HEAD_WORK_BLOCK_ID").html(msg);
			$("#HEAD_WORK_BLOCK_ID").trigger("updatecomplete");
			$("#HEAD_WORK_BLOCK_ID").select2("val", "");
			setLoadingStatus(false, 'HEAD_WORK_BLOCK_ID');
		}
	});
}
//
function getTehsilHW(dist_id){
	setLoadingStatus(true, 'HEAD_WORK_TEHSIL_ID');
	$.ajax({
		type: "POST",
		url: "getTehsilOptionsByDistrict",
		data : {'dist_id':dist_id},
		success: function(msg){
			$("#HEAD_WORK_TEHSIL_ID").html(msg);
			$("#HEAD_WORK_TEHSIL_ID").trigger("updatecomplete");
			$("#HEAD_WORK_TEHSIL_ID").select2("val", "");
			setLoadingStatus(false, 'HEAD_WORK_TEHSIL_ID');
		}
	});
}
//
function getVillages(dist_id){
	setLoadingStatus(true, 'VILLAGES_BENEFITED');
	if( isNull(dist_id)){
		$("#VILLAGES_BENEFITED").html('');
		$("#VILLAGES_BENEFITED").trigger("updatecomplete");
		getCountVillages();
		setLoadingStatus(false, 'VILLAGES_BENEFITED');
	}else{
		$.ajax({
			type: "POST",
			url: "getVillagesByDistrict",
			data : {'DISTRICT_ID':dist_id},
			success: function(msg){
				$("#VILLAGES_BENEFITED").html(msg);
				$("#VILLAGES_BENEFITED").trigger("updatecomplete");
				setLoadingStatus(false, 'VILLAGES_BENEFITED');
			}
		});
	}
}
function doSessionChange(){
	var arrYear = '';
	<?php if($monthlyRecordExists){?>
	arrYear = "<?php echo $SESSION_OPTIONS;?>".split(' - ');
	<?php }else{?>
	arrYear = $("#SESSION_ID :selected").text().split(' - ');
	<?php }?>
	if(arrYear[0]==2013 && arrYear[1]==2014){
		$("#sessionMinDate").val("<?php echo myDateFormat($projectSetupValues['AA_DATE']);?>");
		$("#sessionMaxDate").val(getMaxDate(arrYear[1]));
	}else{
		$("#sessionMinDate").val("<?php echo myDateFormat($projectSetupValues['AA_DATE']);?>");
		//$("#sessionMinDate").val(getMinDate(arrYear[0]));
		$("#sessionMaxDate").val(getMaxDate(arrYear[1]));
	}
	//$("#sessionRealMinDate").val(getMinDate(arrYear[0]));
	/*var dd = $("#sessionMinDate").val().split("-");
	$( "#PROJECT_START_DATE" ).datepicker( "option", "minDate",  new Date(dd[2], (dd[1]- 1), dd[0]) );
	$( "#PROJECT_START_DATE" ).datepicker( "option", "minDate",  new Date(dd[2], (dd[1]- 1), dd[0]) );
	var dd = $("#sessionMaxDate").val().split("-");
	$( "#PROJECT_START_DATE" ).datepicker( "option", "maxDate",  new Date(dd[2], (dd[1]- 1), dd[0]) );*/
}
function getMaxDate(mYear) {
    return '31-03-' + mYear;
}
function getMinDate(mYear) {
    return '01-04-' + mYear;
}
function checkAchievementReady(){
	var sdate = "<?php echo myDateFormat($projectSetupValues['AA_DATE']);?>";
	var sYear = <?php echo $SESSION_START_YEAR;?>;
	var eYear = <?php echo $SESSION_END_YEAR;?>;
	//alert('sdate :' + sdate + 'sYear :' + sYear + 'eYear :'+ eYear );
	if( (sYear==0) || (eYear==0)){
		disableAchievement(true);
	}else{
		setAchievement(sdate, sYear, eYear);
	}
}
function checkAchievement(){
	sdate = $('#PROJECT_START_DATE').val();
	var mSession = '';
	<?php if($monthlyRecordExists){?>
	mSession = new String("<?php echo $SESSION_OPTIONS;?>");
	<?php }else{?>
	mSession = new String($("#SESSION_ID :selected").text());
	<?php }?>
	var arrYear =  mSession.split(' - ');
	if ((mSession=="") || (sdate=="")){
		disableAchievement(true);
		return;
	}
	setAchievement(sdate, arrYear[0], arrYear[1]);
}
function setAchievement(sdate, sYear, eYear){
	var minDate = getMinDate(sYear);
	var maxDate = getMaxDate(eYear);
	//compare with 
	var dc1 = dateCompare(sdate, minDate);
	var dc2 = dateCompare(sdate, maxDate);
	$('#startInSession').val(0);
	//alert('dc1: ' + dc1 + ' dc2: ' + dc2  +'ss:'+ $('#startInSession').val());
	//(dc1>=0)  (dc2<=0) (dc2>0)
	if( ((dc1>=0) && (dc2<=0)) || (dc2>0)){
		$('#startInSession').val(1);
		//alert('startInSession:'+ $('#startInSession').val());
		//start in selected session
		//disable achievements (no achievement)
		disableAchievement(true);
	}else{
		disableAchievement(false);
	}
}
function disableAchievement(status){
	var arrAC = new Array('<?php echo implode("', '", $arrAchievementCompo);?>');
	var arrIntFields = new Array("LA_NO", "LA_NO_ACHIEVE", "LA_COMPLETED_NO_ACHIEVE", "CANAL_STRUCTURES", "CANAL_STRUCTURES_ACHIEVE");
	//alert(arrAC.join(","));
	var chkna='';
	//var arrX = new Array();
	
	for(i=0;i<arrAC.length;i++){
		//arrX.push(arrAC[i].substr(0, 3));
		if(arrAC[i]=='EXPENDITURE_WORKS_ACHIEVE' || arrAC[i]=='EXPENDITURE_TOTAL_ACHIEVE'){
			//do nothing
			$('#'+arrAC[i]).prop('disabled', status);
			if(status){
				$('#'+arrAC[i]).prop('disabled', true).val();//.rules("remove");
				//remove
			}else{
				$('#'+arrAC[i]).rules( "add", {
					required: true,
					min: 0,
					number:true,
					myLess1: "",
					messages: {required: "Required.",  min: "Minimum value should be greater than 0"}
				});
			}
		}else{
			if(arrAC[i].substr(0, 3)=="LA_"){
				chkna = 'LA_NA';
			}else if(arrAC[i].substr(0, 3)=="FA_"){
				chkna = 'FA_NA';
			}else if( (arrAC[i].substr(0, 8)=="CANAL_ST") || (arrAC[i].substr(0, 8)=="CANAL_MA")){
				chkna = 'CANAL_STRUCTURES_NA';
			}else if(arrAC[i].substr(0, 4)=="IRRI"){
				chkna = 'IRRIGATION_POTENTIAL_NA';
				mystatus = false;
				if ($('#'+chkna).is(':checked')){
					mystatus = true;
				}
				//alert(mystatus);
				for(ii=0; ii<window.arrBlockIds.length;ii++){
					blockId = window.arrBlockIds[ii];
					/*$('#BLOCK_EIP_K_' + blockId).prop('disabled', mystatus).val('');
					$('#BLOCK_EIP_R_' + blockId).prop('disabled', mystatus).val('');
					$('#BLOCK_EIP_T_' + blockId).prop('disabled', mystatus).val('');*/
					if(mystatus){
						$('#BLOCK_AIP_K_' + blockId).prop('disabled', true).val('');
						$('#BLOCK_AIP_R_' + blockId).prop('disabled', true).val('');
						$('#BLOCK_AIP_T_' + blockId).prop('disabled', true).val('');
					}else{
						$('#BLOCK_AIP_K_' + blockId).prop('disabled', mystatus);
						$('#BLOCK_AIP_R_' + blockId).prop('disabled', mystatus);
						$('#BLOCK_AIP_T_' + blockId).prop('disabled', mystatus);
					}
					//BLOCK_AIP_R_88
				}
				iu = 0;
				if(!mystatus){
					if($('#startInSession').val()==1) {
						iu=1;
						for(ii=0; ii<window.arrBlockIds.length;ii++){
							blockId = window.arrBlockIds[ii];
							$('#BLOCK_AIP_K_' + blockId).prop('disabled', true).val('');
							$('#BLOCK_AIP_R_' + blockId).prop('disabled', true).val('');
							$('#BLOCK_AIP_T_' + blockId).prop('disabled', true).val('');
						}
					}
				}

				if(iu==0){
					for(ii=0; ii<window.arrBlockIds.length;ii++){
						blockId = window.arrBlockIds[ii];
						$('#BLOCK_AIP_K_' + blockId).rules( "add", {
							required: true,
							min: 0,
							digits:true,
							myLess:'',
							messages: {required: "Required.",  min: "Minimum value should be greater than 0"}
						});
						$('#BLOCK_AIP_R_' + blockId).rules( "add", {
							required: true,
							min: 0,
							digits:true,
							myLess:'',
							messages: {required: "Required.",  min: "Minimum value should be greater than 0"}
						});
					}
				}
			}else{
				chkna = arrAC[i].replace("_ACHIEVE", "_NA");
			}

			//arrX.push(chkna);
			if ($('#'+chkna).is(':checked')){
				//arrX.push(arrAC[i]);
				//alert(3 + ': ' + $('#'+chkna).is(':checked') + ' : ' + arrAC[i]);
				$('#'+arrAC[i]).prop('disabled', true).val('');
			}else{
				//alert(4);
				$('#'+arrAC[i]).prop('disabled', status);
				if(status){
					$('#'+arrAC[i]).val('');
				}else{
					//alert(arrAC[i]);
					/////////////////////////
					if(jQuery.inArray(arrAC[i], arrIntFields) != -1){
						$('#'+arrAC[i]).rules( "add", {
							required: true,
							min: 0,
							digits:true,
							myLess1: "",
							messages: {required: "Required.",  min: "Minimum value should be greater than 0"}
						});
					}else{
						$('#'+arrAC[i]).rules( "add", {
							required: true,
							min: 0,
							number:true,
							myLess1: "",
							messages: {required: "Required.",  min: "Minimum value should be greater than 0"}
						});
					}
					///////////////////////////
	
					/*$('#'+arrAC[i]).rules( "add", {
						required: true,
						min: 0,
						digits:true,
						myLess1: "",
						messages: {required: "Required.",  min: "Minimum value should be greater than 0"}
					});*/
				}
			}
			if(arrAC[i]=='IRRIGATION_POTENTIAL'){
				$('#'+arrAC[i]).prop('readonly', true);//.rules("remove");
				$('#'+arrAC[i]+'_ACHIEVE').prop('readonly', true);//.rules("remove");
				
			}
		}
		if(status) $('#'+arrAC[i]).val('');
	}
	//alert(arrAC.join("\n"));
	//alert(arrX.join("\n"));
}
function setRules(){
<?php 
if( $editMode ){ 
	$arrComp = array();
	if($estimationStatus['LA_NA']==0){
		array_push($arrComp, '#LA_NO');
		array_push($arrComp, '#LA_HA');
	}
	if($estimationStatus['FA_NA']==0)				array_push($arrComp, '#FA_HA');
	if($estimationStatus['HEAD_WORKS_EARTHWORK_NA']==0)array_push($arrComp, '#HEAD_WORKS_EARTHWORK');
	if($estimationStatus['HEAD_WORKS_MASONRY_NA']==0)	array_push($arrComp, '#HEAD_WORKS_MASONRY');
	if($estimationStatus['STEEL_WORKS_NA']==0)	array_push($arrComp, '#STEEL_WORKS');
	if($estimationStatus['CANAL_EARTHWORK_NA']==0)		array_push($arrComp, '#CANAL_EARTHWORK');
	if($estimationStatus['CANAL_STRUCTURES_NA']==0)	array_push($arrComp, '#CANAL_STRUCTURES');
	if($estimationStatus['CANAL_LINING_NA']==0)		array_push($arrComp, '#CANAL_LINING');
	/*if($estimationStatus['IRRIGATION_POTENTIAL_NA']==0){
		array_push($arrComp, '#IRRIGATION_POTENTIAL_KHARIF');
		array_push($arrComp, '#IRRIGATION_POTENTIAL_RABI');
	}*/
	if( count($arrComp)){
		$x = implode(',', $arrComp);
	?>
		$("<?php echo $x;?>").rules( "add", {required: true, minStrict: 0,
			messages: {	required: "Required.", minStrict: "Minimum value should be greater than 0"}
		});
	<?php }//if count
}//if ?>
}
function checkTotalExp(){
	var aaAmount  = checkNo( $('#AA_AMOUNT').val());
	var raaAmount = checkNo( $('#RAA_AMOUNT').val());
	var amountToCheck = ((raaAmount>0)? raaAmount : aaAmount);
	var expAmount = checkNo( $('#EXPENDITURE_TOTAL').val());
	if(expAmount>amountToCheck){
		$("#EXPENDITURE_TOTAL").rules( "add", {
			required: true,
			max: amountToCheck,
			messages: {
				required: "Required.",
				max: "More than AA/RAA Amount: Rs." + amountToCheck
			}
		});
	}
}
//
function showBenefitedBlocks(ids){
	setLoadingStatus(true, 'BLOCKS_BENEFITED');
    var BLOCKS_BENEFITED=$("#BLOCKS_BENEFITED").select2('val');
	//alert(ids);
	if(isNull(ids)){
		$('#BLOCKS_BENEFITED').html('');
		$('#BLOCKS_BENEFITED').trigger("updatecomplete");
		setLoadingStatus(false, 'BLOCKS_BENEFITED');
        $("#BLOCKS_BENEFITED").select2("val", "");
	}else{
		$.ajax({
			type:"POST",
			url:'getBlockBenefitedList',
            data:{'project_id': $('#PROJECT_SETUP_ID').val(), 'dist_id':ids, 'block_id':BLOCKS_BENEFITED},
			success:function(data){
				$('#BLOCKS_BENEFITED').html(data);
				$('#BLOCKS_BENEFITED').trigger("updatecomplete");
				setLoadingStatus(false, 'BLOCKS_BENEFITED');
			}
		});
	}
}
/***/
function getIrriTotal(mode){
	var suffix = ((mode==1) ? '_ACHIEVE':'');
	var kh = checkNo($('#IRRIGATION_POTENTIAL'+'_KHARIF'+suffix).val());
	var ra = checkNo($('#IRRIGATION_POTENTIAL'+'_RABI'+suffix).val());
	var t = kh + ra;
	$('#IRRIGATION_POTENTIAL'+ suffix).val(t);
}
/***/
function lockProject(){
	var conf = confirm("Do you want to Lock the Project Setup Record?");
	if(conf){
		var params = {
			'divid':'mySaveDiv',
			'url':'lockProject', 
			'data':{'project_id':<?php echo $projectId;?>}, 
			'donefname': 'doneLockProject', 
			'failfname' :'', 
			'alwaysfname':'none'
		};
		callMyAjax(params);
	}
}
function showNewRow(blockId, blockName){
	var dis="", adis='';
	window.ipNa = $('#IRRIGATION_POTENTIAL_NA').is(':checked');
	//alert(window.ipNa);
	if(window.ipNa){
		dis='disabled="disabled"';
	}else{
		if( ($('#startInSession').val()==1) || (window.ipNa)) adis='disabled="disabled"';
	}
	var cont = '<tr id="tr-bk-'+blockId+'"><td colspan="7">' +
		'<table border="0" width="100%">' +
		'<tr>' +
		'<td class="ui-widget-content" rowspan="3" width="20"></td>' +
		'<td class="ui-widget-content" rowspan="3"><strong>' + blockName  + '</strong></td>' +
		'<td class="ui-widget-content" width="130"><strong>Kharif</strong></td>' +
		'<td class="ui-widget-content" width="133" align="center">' + 
		'<div id="req_ipek_' + blockId + '" style="float:left;display:' + ( (window.ipNa) ? 'none' : '' ) + '">' + '<?php echo getRequiredSign('left');?>' +'</div>'+
		'<input name="BLOCK_EIP_K[' + blockId + ']" id="BLOCK_EIP_K_' + blockId + '" type="text" size="12" maxlength="12" ' + 
		' class="righttext" onkeyup="getIrriSubTotal(0, 0, ' + blockId + ')" value="" '+ dis +'/>'+
		'</td>' +
		'<td class="ui-widget-content" width="133" align="center">' +
		'<div id="req_ipak_' + blockId + '" style="float:left;display:' + ( (window.ipNa) ? 'none' : '' ) + '">' + '<?php echo getRequiredSign('left');?>' +'</div>'+
		'<input name="BLOCK_AIP_K[' + blockId + ']" id="BLOCK_AIP_K_' + blockId + '" type="text"  size="12" maxlength="12" ' + 
		'class="righttext" onkeyup="getIrriSubTotal(0, 1, ' + blockId + ')" value="" '+ adis +'/>'+
		'</td>' + '</tr>'+
		'<tr><td class="ui-widget-content"><strong>Rabi</strong></td>' +
		'<td class="ui-widget-content" width="133" align="center">' + 
		'<div id="req_iper_' + blockId + '" style="float:left;display:' + ( (window.ipNa) ? 'none' : '' ) + '">' + '<?php echo getRequiredSign('left');?>' +'</div>'+
		'<input name="BLOCK_EIP_R[' + blockId + ']" id="BLOCK_EIP_R_' + blockId + '" type="text" size="12" maxlength="12"'+
		' class="righttext" onkeyup="getIrriSubTotal(1, 0, ' + blockId + ')" value="" '+ dis +'/>'+
		'</td>' +
		'<td class="ui-widget-content" width="133" align="center">' +
		'<div id="req_ipar_' + blockId + '" style="float:left;display:' + ( (window.ipNa) ? 'none' : '' ) + '">' + '<?php echo getRequiredSign('left');?>' +'</div>'+
		'<input name="BLOCK_AIP_R[' + blockId + ']" id="BLOCK_AIP_R_' + blockId + '" type="text"  size="12" maxlength="12" ' + 
		'class="righttext" onkeyup="getIrriSubTotal(1, 1, ' + blockId + ')" value="" '+ adis +'/>'+
		'</td>' + '</tr>'+
		'<tr><td class="ui-state-default"><strong>Total</strong></td>' +
		'<td class="ui-state-default" width="133" align="center">' + 
		'<input name="BLOCK_EIP_T[' + blockId + ']" id="BLOCK_EIP_T_' + blockId + '" type="text"  size="12" maxlength="12" class="righttext" value="" readonly="readonly" />'+
		'</td>' +
		'<td class="ui-state-default" width="133" align="center">' +
	'<input name="BLOCK_AIP_T[' + blockId + ']" id="BLOCK_AIP_T_' + blockId + '" type="text"  size="12" maxlength="12" class="righttext" value="" readonly="readonly" />'+
		'</td>' + '</tr>'+
		'</table>' +
		'</td><tr>';
	$('#tr-bk-total').before(cont);
	$('#BLOCK_AIP_R_' + blockId).rules( "add", {
	//$('#BLOCK_AIP_R_' + blockId + ', #BLOCK_AIP_K_' + blockId).rules( "add", {
		required: true,
		min: 0,
		digits:true,
		myLess:'',
		messages: {required: "Required.",  min: "Minimum value should be greater than 0"}
	});
	$('#BLOCK_AIP_K_' + blockId).rules( "add", {
		required: true,
		min: 0,
		digits:true,
		myLess:'',
		messages: {required: "Required.",  min: "Minimum value should be greater than 0"}
	});
	//$('#BLOCK_AIP_R_' + blockId) + ', #BLOCK_AIP_R_' + blockId + ', 
	
}
function showBlockIP(id, name){
	window.arrIPBlockData.push(new clsIP(id, name, 0, 0, 0, 0, 0, 0));
	showNewRow(id, name);
}
function getIrriSubTotal(kharifOrRabi, EstiOrAchi, blockId){
	var mode = (kharifOrRabi==0) ? "K":"R";
	var esti = (EstiOrAchi==0)? "E":"A";
	
	var ke = checkNo($('#BLOCK_'+ esti +'IP_K_' + blockId).val());
	var re = checkNo($('#BLOCK_'+ esti +'IP_R_' + blockId).val());
	var te = ke + re;
	//alert('Lenght:' + window.arrBlockIds.length);
	$('#BLOCK_'+ esti +'IP_T_' + blockId).val(te);
	/*if(esti=="E"){
		//first check achievement
		stat = $('#BLOCK_AIP_K_' + blockId).prop('disabled');
		//alert(stat);
		if(stat){
			//if achievement disabled remove rules
		}else{
			$('#BLOCK_AIP_K_' + blockId).rules("remove");
			$('#BLOCK_AIP_K_' + blockId).rules( "add", {
				required: true,
				min: 0,
				digits:true,
				max: function(e){ return getaa(e);},
				messages: {
					required: "Required.",  min: "Minimum value should be greater than 0", 
					max: {function(e){ return getbb(e);}}
				}
			});
		}
		$('#frmProject').valid();
	}*/
	//else add max rules

	var kt = 0;
	var rt = 0;
	for(i=0;i<window.arrBlockIds.length;i++){
		blockid = window.arrBlockIds[i];
		kt += checkNo($('#BLOCK_'+ esti +'IP_K_' + blockid).val());
		rt += checkNo($('#BLOCK_'+ esti +'IP_R_' + blockid).val());
	}
	var tt = kt + rt;
	//alert(window.arrBlockIds.join(','));
	if(EstiOrAchi==1){
		$('#IRRIGATION_POTENTIAL_KHARIF_ACHIEVE').val(kt);
		$('#IRRIGATION_POTENTIAL_RABI_ACHIEVE').val(rt);
		$('#IRRIGATION_POTENTIAL_ACHIEVE').val(tt);
	}else{
		$('#IRRIGATION_POTENTIAL_KHARIF').val(kt);
		$('#IRRIGATION_POTENTIAL_RABI').val(rt);
		$('#IRRIGATION_POTENTIAL').val(tt);
	}
}
<?php } ?>
function showAA(vv){
	if(vv==0) return;
	var params = {
		'divid':'divAA',
		'url':'showAAData', 
		'data':{'id':vv}, 
		'donefname': 'doneAA', 
		'failfname' :'none', 
		'alwaysfname':'none'
	};
	callMyAjax(params);
}
function doneAA(data){
	var mydata = parseMyResponse(data);
	$('#AA_NO').val(mydata.AA_NO);
	$('#AA_DATE').val(mydata.AA_DATE);
	$('#AA_AMOUNT').val(mydata.AA_AMOUNT);
	$("#AA_AUTHORITY_ID").select2("val", mydata.AA_AUTHORITY_ID);
	$("#AA_AUTHORITY_ID").trigger("updatecomplete");
	if(mydata.RAA_NO==0)
	$('#RAA_NO').val(mydata.RAA_NO);
	$('#RAA_DATE').val(mydata.RAA_DATE);
	$('#RAA_AMOUNT').val(mydata.RAA_AMOUNT);
	$("#RAA_AUTHORITY_ID").select2("val", mydata.RAA_AUTHORITY_ID);
	$("#RAA_AUTHORITY_ID").trigger("updatecomplete");
	$("#AA_AUTHORITY_ID").prop("disabled", true);
	$("#RAA_AUTHORITY_ID").prop("disabled", true);
}
function doneLockProject(data){
	if(data==1){
		//locked
		$("#modalBox").dialog('close');
		alert('Project Locked...');
		gridReload();
	}else{
		//fail to lock
	}
}
function testme(){
	var arrCondition = new Array("1","2");
	//var vv = parseInt($('#test').val());
	var vv = $('#test').val();
	alert($('#test').val() + ' : ' + $.inArray(vv, arrCondition));
}

var myHeads = <?php echo json_encode($DEPOSIT_SCHEME_DETAILS); ?>;
function showHead(id)
{
    var head_id=$("#DEPOSIT_SCHEME_ID").val();
    
 for (var i = 0; i < myHeads.length; i++) {

        if(head_id==myHeads[i].ID)
            $("#DEPOSIT_SCHEME_HEAD").html(myHeads[i].HEAD);                
 }

}
showHead('1');
</script>
