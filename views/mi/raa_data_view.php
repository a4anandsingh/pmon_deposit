<style>
	.natd{background: #b9aeae;}
	input:read-only{background-color: #eeeeee;}
	input:-moz-read-only {background-color: #eeeeee;}
</style>
<?php
$editMode = ($raaData['RAA_PROJECT_ID'])?TRUE:FALSE;
	//showArrayValues($estimationStatus);
	//showArrayValues($previousEstimation);
	//showArrayValues($currentEstimation);
	//showArrayValues($arrSetupStatus);
	//showArrayValues($BLOCK_IP_DATA);
	$naTd ='<td align="center" class="ui-widget-content natd" colspan="3"><strong>NA</strong></td>';
?>
<form name="frmProject" id="frmProject" onsubmit="return false;" autocomplete="off">
	<input type="hidden" name="PROJECT_SETUP_ID" id="PROJECT_SETUP_ID" value="<?php echo $PROJECT_SETUP_ID;?>" />
	<input type="hidden" name="RAA_PROJECT_ID" id="RAA_PROJECT_ID" value="<?php echo $raaData['RAA_PROJECT_ID'];?>" />
	<input type="hidden" name="ESTIMATED_QTY_ID" id="ESTIMATED_QTY_ID" value="<?php echo $currentEstimation['ESTIMATED_QTY_ID'];?>" />
	<input type="hidden" name="AA_DATE" id="AA_DATE" value="<?php echo myDateFormat($projectData['AA_DATE']);?>" />
	<input type="hidden" name="MY_DATE" id="MY_DATE" value="<?php echo date("d-m-Y");?>" />
	<input type="hidden" name="ADDED_BY" id="ADDED_BY" value="<?php echo $raaData['ADDED_BY'];?>" />
<!-- <table width="100%" border="0" cellpadding="3" class="ui-widget-content">
<tr>
    <td align="center" class="ui-widget-header">
        <strong>
        <?php echo $projectData['WORK_NAME'].' - '.$projectData['WORK_NAME_HINDI'];?>
        </strong>
    </td>
</tr>
<tr>
	<td class="ui-widget-content" align="center">
        <div id="radioset"><strong>Entry Type : </strong>
            <!-- <input type="radio" id="radio1" name="IS_RAA" value="1" 
            	onchange="changeCheckBoxOption(1, this.checked)"
			<?php //echo ($raaData['IS_RAA']==1)? 'checked="checked"':'';?> />
            <label for="radio1">RAA</label> -->

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
</table> -->

<table width="100%" border="0" cellpadding="3" class="ui-widget-content" id="RAA_DETAIL">
<tr>
    <td class="ui-state-default">
      <strong>Select Entry Type</strong>
    </td>
    <td class="ui-widget-content" colspan="3">      
      <select name="IS_RAA" id="IS_RAA" onchange="changeCheckBoxOption(this.value)" style="width: 400px;">
        <option value="0">Select</option>
        <option value="1" <?php echo ($raaData['IS_RAA']==1)? 'selected="selected"':'';?> >RAA</option>
        <option value="2" <?php echo ($raaData['IS_RAA']==2)? 'selected="selected"':'';?> >Extra Quantity</option>
        <option value="3" <?php echo ($raaData['IS_RAA']==3)? 'selected="selected"':'';?> >TS</option>
      </select>
    </td>
</tr>  
<tr>
    <td class="ui-state-default">
    	<strong id="raa_no"><?php echo $entryMode;?> No : </br><span style="color:red;">(Only numeric.)</span></strong>
    </td>
    <td class="ui-widget-content">
		<input name="RAA_NO"  id="RAA_NO" type="text" 
        	size="6" maxlength="5"
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
    <select name="RAA_AUTHORITY_ID" id="RAA_AUTHORITY_ID"  onchange="showHideOtherAuth('RAA_AUTHORITY_ID')"
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
<tr id="TR_RAA_AUTHORITY_ID">
    <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right');?><strong>Other Authority :</strong></td>
    <td class="ui-widget-content">
        <input type="text" name="OTHER_RAA_AUTHORITY" ID="OTHER_RAA_AUTHORITY" class="required" value="<?php echo $raaData['OTHER_RAA_AUTHORITY'];?>">
    </td>
</tr>

    <tr class="raa">
        <td nowrap="nowrap" class="ui-state-default">
            <?php echo getRequiredSign('right');?><strong>Scanned Copy  :</strong>
        </td>
        <td class="ui-widget-content" colspan="3">
            <div id="msg_raa_file"></div>
            <?php
            $filePath= FCPATH.'aa_raa_uploads'.DIRECTORY_SEPARATOR.$raaData['RAA_FILE_URL'];
            if($raaData['RAA_USER_FILE_NAME']!='') {
                if (file_exists($filePath)) { ?>
                    <div id="raa_button_div">
                        <a class="fm-button ui-state-default ui-corner-all"  target="_blank"
                           href="<?php echo base_url() . 'aa_raa_uploads/'.$raaData['RAA_FILE_URL']?>">
                            <span class=cus-eye></span> View </a>
                        <?php
                        if($raaData['ADDED_BY']){
                            if($isMonthlyExists){

                            }else{
                                echo getButton('Delete', 'removeFile('.$raaData['RAA_PROJECT_ID'].')', 4, 'cus-cross'). ' &nbsp; ';
                            }
                        }else{
                            if($raaData['RAA_PROJECT_ID']==0){
                                echo getButton('Delete', 'removeFile('.$raaData['RAA_PROJECT_ID'].')', 4, 'cus-cross'). ' &nbsp; ';
                            }
                        }
                        ?>
                    </div>
                    <div id="raa_upload_div" style="display: none;">
                        <input type="file"  onchange="showSize('RAA_SCAN_COPY')" id="RAA_SCAN_COPY" name="RAA_SCAN_COPY"/>
                        (only PDF File)
                    </div>
                    <?php
                }
            }else{ ?>
                <input type="file"  onchange="showSize('RAA_SCAN_COPY')" id="RAA_SCAN_COPY" name="RAA_SCAN_COPY"/>
                <span style="color:#f00">(only PDF File)</span>
                <?php
            }
            ?>
        </td>
    </tr>
</table>

<div class="wrdlinebreak"></div>
<div class="ui-state-error" style="padding:5px">
<span class="cus-lightbulb"></span> 
<strong>यदि नवीनतम मात्रा में कमी हो तभी कम मात्रा भरें। <br />
यदि नवीनतम मात्रा(Latest) में कोई भी परिवर्तन न हो तो नवीनतम मात्रा(Latest) कॉलम में पुरानी मात्रा(Old) को ही डालना है।</strong>
</div>

<div class="wrdlinebreak"></div>
    <!-- New Fixed Format -->
<table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">
<tr>
  <th rowspan="2" class="ui-widget-header">#</th>
	<th colspan="3" rowspan="2" class="ui-widget-header">Contents</th>	
	<th width="64" rowspan="2" class="ui-widget-header">Unit</th>
    <th width="64" rowspan="2" class="ui-widget-header">No Change 
    	<?php /*
        <br />
    	<input type="checkbox" id="IS_NO_CHANGE" onclick='checkAll()' class="css-checkbox" />
        <label for="IS_NO_CHANGE" class="css-label lite-green-check"></label>
		*/?>        
    </th>
    <th colspan="2" class="ui-widget-header">Estimation</th>
    </tr>
<tr>
  <th width="64" class="ui-widget-header">Old</th>
  <th width="64" class="ui-widget-header">Latest</th>
</tr>    

    <?php
    /*if($setupData['LA_NA']==0){
        array_push($arrDataFields, 'LA_NO');
        array_push($arrDataFields, 'LA_HA');
        array_push($arrDataFields, 'LA_COMPLETED_NO');
        array_push($arrDataFields, 'LA_COMPLETED_HA');
    }*/
    ?>
   
    <tr>
      <td rowspan="2" align="center" class="ui-widget-content"><strong>1</strong></td>
      <td colspan="3" rowspan="2" align="" class="ui-widget-content"><strong>Land aq cases To be done 
      (as per "B" Land section of DPR)</strong></td>
      <td align="center" class="ui-widget-content"><strong>Numbers</strong></td>
      
   	<?php if($arrSetupStatus['LA_NA']!=1){?>   
      <td align="center" class="ui-widget-content">      	
        <input type="checkbox" id="IS_LA_NO" onclick='copyValues("LA_NO")' 
		<?php echo (($currentEstimation['IS_LA_NO']==1)? " checked='checked'":"");?>  name="IS_LA_NO" class="css-checkbox" value="1"/>
        <label for="IS_LA_NO" class="css-label lite-green-check"></label>        
      </td>
      <td align="center" class="ui-widget-content"><?php echo $previousEstimation['LA_NO'];?>
      <input type="hidden" id="PREV_LA_NO" value="<?php echo $previousEstimation['LA_NO'];?>" /></td>
      <td align="" class="ui-widget-content"><input type="text" name="LA_NO" id="LA_NO" size="10" maxlength="15" autocomplete="off" <?php echo (($currentEstimation['IS_LA_NO']==1)? " readonly='readonly'":"");?>  class="centertext number required" aria-required="true" value="<?php echo $currentEstimation['LA_NO'];?>"></td>
      <?php } else{ 
	  	echo $naTd;
	}?>
      
    </tr>
    <tr>
      <td align="center" class="ui-widget-content"><strong>Hectares</strong></td>
      
     <?php if($arrSetupStatus['LA_NA']!=1){?>   
      <td align="center" class="ui-widget-content">
      	<input <?php echo (($currentEstimation['IS_LA_HA']==1)? " checked='checked'":"");?>  type="checkbox" id="IS_LA_HA" onclick='copyValues("LA_HA")' name="IS_LA_HA" class="css-checkbox"  value="1"/>
        <label for="IS_LA_HA" class="css-label lite-green-check"></label>
      </td>
      <td align="center" class="ui-widget-content"><?php echo $previousEstimation['LA_HA'];?>
      <input type="hidden" value="<?php echo $previousEstimation['LA_HA'];?>" id="PREV_LA_HA" /></td>
      <td align="" class="ui-widget-content"><input type="text" name="LA_HA" id="LA_HA" size="10" maxlength="15" autocomplete="off" class="centertext number required" <?php echo (($currentEstimation['IS_LA_HA']==1)? "  readonly='readonly'":"");?>   value="<?php echo $currentEstimation['LA_HA'];?>"aria-required="true"></td>
    </tr>
    <?php } else { echo $naTd;}?>
    <tr>
      <td align="center" class="ui-widget-content"><strong>2</strong></td>
      <td colspan="3" align="" class="ui-widget-content"><strong>Forest Acquisition to be Done</strong></td>
      <td align="center" class="ui-widget-content"><strong>Hectares</strong></td>
      
      <?php if($arrSetupStatus['FA_NA']!=1){?>
      <td align="center" class="ui-widget-content">
		<input <?php echo (($currentEstimation['IS_FA_HA']==1)? " checked='checked'":"");?>  type="checkbox" id="IS_FA_HA" onclick='copyValues("FA_HA")' name="IS_FA_HA" class="css-checkbox"  value="1"/>
		<label for="IS_FA_HA" class="css-label lite-green-check"></label>      
      </td>
      <td align="center" class="ui-widget-content"><?php echo $previousEstimation['FA_HA'];?>
      <input type="hidden" id="PREV_FA_HA" value="<?php echo $previousEstimation['FA_HA'];?>" /></td>
      <td align="" class="ui-widget-content"><input type="text" name="FA_HA" id="FA_HA" size="10" maxlength="15" autocomplete="off" class="centertext number required" <?php echo (($currentEstimation['IS_FA_HA']==1)? "  readonly='readonly'":"");?>  value="<?php echo $currentEstimation['FA_HA'];?>" aria-required="true"></td>    
     <?php }else{ 
			echo $naTd;
		}?>    
    </tr>    

    <tr>
      <td align="center" class="ui-widget-content"><strong>3</strong></td>
      <td colspan="3" align="" class="ui-widget-content"><strong>Earthwork (As per &quot;L&quot; Earthwork section of DPR)</strong></td>
      <td align="center" class="ui-widget-content"><strong>Th Cum</strong></td>

       <?php if($arrSetupStatus['L_EARTHWORK_NA']!=1){?>
        <td align="center" class="ui-widget-content">
        <input <?php echo (($currentEstimation['IS_L_EARTHWORK']==1)? " checked='checked'":"");?>  type="checkbox" id="IS_L_EARTHWORK" name="IS_L_EARTHWORK" onclick='copyValues("L_EARTHWORK")' class="css-checkbox"  value="1"/>
        <label for="IS_L_EARTHWORK" class="css-label lite-green-check"></label>
        </td>
        <td align="center" class="ui-widget-content"><?php echo $previousEstimation['L_EARTHWORK'];?>
        <input type="hidden" id="PREV_L_EARTHWORK" value="<?php echo $previousEstimation['L_EARTHWORK'];?>" /></td>
        <td align="" class="ui-widget-content"><input type="text" name="L_EARTHWORK" id="L_EARTHWORK" size="10" maxlength="15" autocomplete="off" class="centertext number required" <?php echo (($currentEstimation['IS_L_EARTHWORK']==1)? " readonly='readonly'":"");?>  value="<?php echo $currentEstimation['L_EARTHWORK'];?>" aria-required="true"></td>        
    </tr>
    <?php } else { echo $naTd;}?>
    
    
    <tr>
      <td rowspan="4" align="center" class="ui-widget-content" ><strong>4</strong></td>
      <td rowspan="4" align="" class="ui-widget-content" ><label for="select_all"  class="css-label lite-green-check"></label>        <strong>Masonry/Concrete&nbsp;<br />(As per &quot;C&quot; Masonry section of DPR)</strong></td>
        <td colspan="2" align="" class="ui-widget-content" ><strong>(a) Masonry/Concrete</strong></td>
        <td class="ui-widget-content" align="center"><strong>Th Cum</strong></td>
        
        <?php if($arrSetupStatus['C_MASONRY_NA']!=1){?>
        <td class="ui-widget-content" align="center">
        <input <?php echo (($currentEstimation['IS_C_MASONRY']==1)? " checked='checked'":"");?>  type="checkbox" id="IS_C_MASONRY" onclick='copyValues("C_MASONRY")' name="IS_C_MASONRY" class="css-checkbox"  value="1"/>
        <label for="IS_C_MASONRY" class="css-label lite-green-check"></label>
        </td>
        <td class="ui-widget-content" align="center"><?php echo $previousEstimation['C_MASONRY'];?>
        <input type="hidden" id="PREV_C_MASONRY" value="<?php echo $previousEstimation['C_MASONRY'];?>" /></td>
        <td class="ui-widget-content" ><input type="text" name="C_MASONRY" id="C_MASONRY" size="10" maxlength="15" autocomplete="off" class="centertext  number required" <?php echo (($currentEstimation['IS_C_MASONRY']==1)? " readonly='readonly'":"");?> value="<?php echo $currentEstimation['C_MASONRY'];?>" aria-required="true"></td>
        <?php }else { echo $naTd;} ?>        
    </tr>
    <tr>
      <td rowspan="2" align="" class="ui-widget-content" ><strong>(b) Pipe Works</strong></td>
        <td width="130" class="ui-widget-content" ><strong>i. DE/PE/PVC<br />(Main &amp; Submain)</strong></td>
        <td class="ui-widget-content" align="center"><strong>Th Cum</strong></td>
		
		<?php if($arrSetupStatus['C_PIPEWORK_NA']!=1){?>
        <td class="ui-widget-content" align="center">
        <input <?php echo (($currentEstimation['IS_C_PIPEWORK']==1)? " checked='checked'":"");?>  type="checkbox" id="IS_C_PIPEWORK" onclick='copyValues("C_PIPEWORK")' name="IS_C_PIPEWORK" class="css-checkbox"  value="1"/>
        <label for="IS_C_PIPEWORK" class="css-label lite-green-check"></label>
        </td>
        <td class="ui-widget-content" align="center" ><?php echo $previousEstimation['C_PIPEWORK'];?>
        <input type="hidden" id="PREV_C_PIPEWORK" value="<?php echo $previousEstimation['C_PIPEWORK'];?>" /></td>
        <td class="ui-widget-content" ><input type="text" name="C_PIPEWORK" id="C_PIPEWORK" size="10" maxlength="15" autocomplete="off" class="centertext number required" <?php echo (($currentEstimation['IS_C_PIPEWORK']==1)? " readonly='readonly'":"");?>  value="<?php echo $currentEstimation['C_PIPEWORK'];?>" aria-required="true"></td>
        <?php } else { echo $naTd;} ?>        
    </tr>
    <?php //if($setupData['C_DRIP_PIPE_NA']==0){ array_push($arrDataFields, 'C_DRIP_PIPE');}?>
    <tr>
      <td class="ui-widget-content" ><strong>ii. Lateral for <br />Drip/sprinkler</strong></td>
        <td class="ui-widget-content" align="center"><strong>Mtrs</strong></td>

		<?php if($arrSetupStatus['C_DRIP_PIPE_NA']!=1){?>
        <td class="ui-widget-content" align="center">
        	<input <?php echo (($currentEstimation['IS_C_DRIP_PIPE']==1)? " checked='checked'":"");?>  type="checkbox" id="IS_C_DRIP_PIPE"  onclick='copyValues("C_DRIP_PIPE")' name="IS_C_DRIP_PIPE" class="css-checkbox"  value="1"/>
	        <label for="IS_C_DRIP_PIPE" class="css-label lite-green-check"></label>
        </td>
        <td class="ui-widget-content" align="center"><?php echo $previousEstimation['C_DRIP_PIPE'];?>
        <input type="hidden" id="PREV_C_DRIP_PIPE" value="<?php echo $previousEstimation['C_DRIP_PIPE'];?>"/></td>
        <td class="ui-widget-content" ><input type="text" name="C_DRIP_PIPE" id="C_DRIP_PIPE" size="10" maxlength="15" autocomplete="off" class="centertext number required" <?php echo (($currentEstimation['IS_C_DRIP_PIPE']==1)? " readonly='readonly'":"");?>  value="<?php echo $currentEstimation['C_DRIP_PIPE'];?>" aria-required="true"></td>        
    
    <?php }else { echo $naTd;} ?>
    </tr>
    
    <tr>
      <td colspan="2" align="" class="ui-widget-content" ><strong>(c) Water Pumps</strong></td>
      <td class="ui-widget-content" align="center"><strong>Numbers</strong></td>
    
    <?php if($arrSetupStatus['C_WATERPUMP_NA']!=1){?>   
      <td class="ui-widget-content" align="center">
      	<input <?php echo (($currentEstimation['IS_C_WATERPUMP']==1)? " checked='checked'":"");?>  type="checkbox" id="IS_C_WATERPUMP" onclick='copyValues("C_WATERPUMP")' name="IS_C_WATERPUMP" class="css-checkbox"  value="1"/>
	        <label for="IS_C_WATERPUMP" class="css-label lite-green-check"></label>
      </td>
      <td class="ui-widget-content" align="center"><?php echo $previousEstimation['C_WATERPUMP'];?>
      <input type="hidden" id="PREV_C_WATERPUMP" value="<?php echo $previousEstimation['C_WATERPUMP'];?>"/></td>
      <td class="ui-widget-content" ><input type="text" name="C_WATERPUMP" id="C_WATERPUMP" size="10" maxlength="15" autocomplete="off" class="centertext number required" <?php echo (($currentEstimation['IS_C_WATERPUMP']==1)? " readonly='readonly'":"");?>  value="<?php echo $currentEstimation['C_WATERPUMP'];?>" aria-required="true" /></td>
    <?php }else { echo $naTd;} ?>

    </tr>
    <tr>
      <td align="center" class="ui-widget-content" ><strong>5</strong></td>
      <td colspan="3" align="" class="ui-widget-content" ><strong>Building Works 
        (As per &quot;K&quot; Building sectin of DPR)
      Control Rooms</strong></td>
      <td class="ui-widget-content" align="center"><strong>Numbers</strong></td>
      <?php if($arrSetupStatus['K_CONTROL_ROOMS_NA']!=1){?>
      <td class="ui-widget-content" align="center">
      	<input type="checkbox" <?php echo (($currentEstimation['IS_K_CONTROL_ROOMS']==1)? " checked='checked'":"");?>  id="IS_K_CONTROL_ROOMS" onclick='copyValues("K_CONTROL_ROOMS")'  name="IS_K_CONTROL_ROOMS" class="css-checkbox"  value="1"/>
      	<label for="IS_K_CONTROL_ROOMS" class="css-label lite-green-check"></label>
      </td>
      <td class="ui-widget-content" align="center"><?php echo $previousEstimation['K_CONTROL_ROOMS'];?>
      <input type="hidden" id="PREV_K_CONTROL_ROOMS" value="<?php echo $previousEstimation['K_CONTROL_ROOMS'];?>" /></td>
      <td class="ui-widget-content" ><input type="text" name="K_CONTROL_ROOMS" id="K_CONTROL_ROOMS" size="10" maxlength="15" autocomplete="off" class="centertext  number required" <?php echo (($currentEstimation['IS_K_CONTROL_ROOMS']==1)? " readonly='readonly'":"");?> value="<?php echo $currentEstimation['K_CONTROL_ROOMS'];?>" aria-required="true"></td>
      <?php } else {echo $naTd;}?>
    </tr>
    <tr>
      <td align="center" class="ui-widget-content"><strong>6</strong></td>
      <td colspan="3" align="" class="ui-widget-content"><strong>Irrigation Potential To be Created</strong></td>
      <td class="ui-widget-content" align="center"><strong>Hectares</strong></td>
      <td class="ui-widget-content" align="center">
		
      </td>
      <td class="ui-widget-content" >&nbsp;</td>
      <td class="ui-widget-content" >&nbsp;</td>
    </tr>
    <?php
	$arrTotalCur = array('KHARIF'=>0, 'RABI'=>0);
	$arrTotalPrev = array('KHARIF'=>0, 'RABI'=>0);

	$arrIPBlockIds = array();
    if($BLOCK_IP_DATA){
			$ib = 0;
			foreach($BLOCK_IP_DATA as $k=>$bb){
				array_push($arrIPBlockIds, $k);
				$arrTotalCur['KHARIF'] += $bb['CUR_ESTI']['KHARIF'];
				$arrTotalCur['RABI'] += $bb['CUR_ESTI']['RABI'];
				
				$arrTotalPrev['KHARIF'] += $bb['PREV_ESTI']['KHARIF'];
				$arrTotalPrev['RABI'] += $bb['PREV_ESTI']['RABI'];
	?>
    <tr>
      <td rowspan="3" align="" class="ui-widget-content" >
        <strong><?php echo '('.chr($ib+97) .')';  $ib++;?></strong>
      </td>
      <td colspan="3" rowspan="3" align="" class="ui-widget-content" ><strong><?php echo $bb['BLOCK_NAME']. '<br />'.$bb['BLOCK_NAME_HINDI'] ;?></strong>
      <input type="hidden" name="HID_BLOCK_ID[<?php echo $k;?>]" value="<?php echo $k;?>" /></td>
      <td class="ui-widget-content" align="center"><strong>Kharif</strong></td>
      <td class="ui-widget-content" align="center">
      	<input <?php echo ($bb['IS_SAME']['KHARIF']==1)? " checked='checked'" :"";?> type="checkbox" id="IS_BLOCK_K_<?php echo $k;?>" 
        onclick='copyValues("BLOCK_K_<?php echo $k;?>"); getIrriSubTotal(0, "<?php echo $k;?>")'  name="IS_BLOCK_K[<?php echo $k?>]" class="css-checkbox"  value="1"/>
      	<label for="IS_BLOCK_K_<?php echo $k;?>" class="css-label lite-green-check"></label>
      </td>
      <td class="ui-widget-content" align="center">
			<?php echo $bb['PREV_ESTI']['KHARIF'];?>
			<input type="hidden" id="PREV_BLOCK_K_<?php echo $k;?>" value="<?php echo $bb['PREV_ESTI']['KHARIF'];?>" />
      </td>
      <td class="ui-widget-content" >
      <input name="BLOCK_K[<?php echo $k?>]" id="BLOCK_K_<?php echo $k;?>" type="text" 
		size="10" maxlength="12" class="centertext required number" onkeyup="getIrriSubTotal(0, '<?php echo $k;?>')" 

        
        <?php echo ($bb['IS_SAME']['KHARIF']==1)? " readonly='readonly'" :"";?> 
        value="<?php echo $bb['CUR_ESTI']['KHARIF'];?>"/></td>
    </tr>
    <tr>
      <td class="ui-widget-content"  align="center"><strong>Rabi</strong></td>
      <td class="ui-widget-content" align="center">
      	<input <?php echo ($bb['IS_SAME']['RABI']==1)? " checked='checked'" :"";?> type="checkbox" id="IS_BLOCK_R_<?php echo $k;?>"
        onclick='copyValues("BLOCK_R_<?php echo $k;?>");getIrriSubTotal(0,"<?php echo $k;?>")'  name="IS_BLOCK_R[<?php echo $k?>]" class="css-checkbox"  value="1"/>
      	<label for="IS_BLOCK_R_<?php echo $k;?>" class="css-label lite-green-check"></label>
      </td>
      <td class="ui-widget-content"  align="center">
	    <?php echo $bb['PREV_ESTI']['RABI'];?>
      	<input type="hidden" id="PREV_BLOCK_R_<?php echo $k;?>" value="<?php echo $bb['PREV_ESTI']['RABI'];?>" />
      </td>
      <td class="ui-widget-content" >
      <input name="BLOCK_R[<?php echo $k;?>]" id="BLOCK_R_<?php echo $k;?>" type="text" 
       <?php echo ($bb['IS_SAME']['RABI']==1)? " readonly='readonly'" :"";?> 
        size="10" maxlength="12" class="centertext required number" onkeyup="getIrriSubTotal(0,'<?php echo $k;?>')"  value="<?php echo $bb['CUR_ESTI']['RABI'];?>"/>
        </td>
    </tr>
    <tr>
      <td class="ui-widget-content"  align="center"><strong>Total</strong></td>
      <td class="ui-widget-content" align="center">
      	
      </td>
      <td class="ui-widget-content"  align="center">
	  	<?php echo $bb['PREV_ESTI']['IP'];?>
      	<input type="hidden" id="PREV_BLOCK_IP_<?php echo $k;?>" value="<?php echo $bb['PREV_ESTI']['IP'];?>" />
       </td>
      <td class="ui-widget-content" >
      	<input name="BLOCK_T[<?php echo $k;?>]" id="BLOCK_T_<?php echo $k;?>" type="text" 
		size="10" maxlength="12" class="centertext" readonly="readonly" value="<?php echo $bb['CUR_ESTI']['IP'];?>"/>
      </td>
    </tr>
    <?php }
	}
	?>   
    <tr>
      <td rowspan="3" align="" class="ui-widget-content" >&nbsp;</td>
      <td colspan="3" rowspan="3" align="" class="ui-widget-content" ><strong>Total Irrigation Potential To be Created</strong></td>
      <td class="ui-widget-content"  align="center"><strong>Kharif</strong></td>
      <td class="ui-widget-content" >&nbsp;</td>
      <td class="ui-widget-content" align="center"><input type="hidden" id="PREV_IP_KHARIF" /><?php echo $arrTotalPrev['KHARIF'];?></td>
      <td class="ui-widget-content" ><input type="text" name="IP_KHARIF" id="IP_KHARIF" size="10" maxlength="15" autocomplete="off" class="centertext" readonly="readonly" value="<?php echo $arrTotalCur['KHARIF'];?>" aria-required="true" /></td>
    </tr>
    <tr>
      <td class="ui-widget-content"  align="center"><strong>Rabi</strong></td>
      <td class="ui-widget-content" >&nbsp;</td>
      <td class="ui-widget-content" align="center"><?php echo $arrTotalPrev['RABI'];?><input type="hidden" id="PREV_IP_RABI" value="<?php echo $arrTotalPrev['KHARIF'];?>" /></td>
      <td class="ui-widget-content" ><input type="text" name="IP_RABI" id="IP_RABI" size="10" maxlength="15" autocomplete="off" class="centertext" readonly="readonly" value="<?php echo $arrTotalCur['RABI'];?>" aria-required="true" /></td>
    </tr>
    <tr>
      <td class="ui-widget-content"  align="center"><strong>Total</strong></td>
      <td class="ui-widget-content" >&nbsp;</td>
      <td class="ui-widget-content" align="center"><?php echo ($arrTotalPrev['KHARIF']+$arrTotalPrev['KHARIF'])?>
      <input type="hidden" id="PREV_IP_TOTAL"  value="<?php echo ($arrTotalPrev['KHARIF']+$arrTotalPrev['KHARIF'])?>"/></td>
      <td class="ui-widget-content" ><input type="text" name="IP_TOTAL" id="IP_TOTAL" size="10" maxlength="15" autocomplete="off" class="centertext" readonly="readonly" value="<?php echo ($arrTotalCur['KHARIF']+$arrTotalCur['KHARIF'])?>" aria-required="true" /></td>
    </tr>
    <?php //if($setupData['C_WATERPUMP_NA']==0){array_push($arrDataFields, 'C_WATERPUMP');}?>
    
        
</table>
<div id="mySaveDiv" align="right" class="mysavebar" style="display: <?php echo ($raaData['IS_RAA'])? ' block':' none';?>;">
	<?php 
	   echo getButton('Save', 'saveRAASetup()', 4, 'cus-disk'). ' &nbsp; ';

if($raaData['ADDED_BY']){
  if(($editMode && getSessionDataByKey('USER_ID')=='23')|| (!$editMode)){
	   echo getButton('Save', 'saveRAASetup()', 4, 'cus-disk'). ' &nbsp; ';
   }
}else{
  //Entered by setup
	/*if($raaData['RAA_PROJECT_ID']==0){
		echo getButton('Save', 'saveRAASetup()', 4, 'cus-disk'). ' &nbsp; ';
	}*/
}
echo getButton('Cancel', 'closeDialog()', 4, 'cus-cancel');?>
</div>
</form>
<script language="javascript" type="text/javascript">
//
var validator = '';
$().ready(function(){
	$.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, "File must less than 2MB");
	
	$('#RAA_DATE').attr("placeholder", "dd-mm-yyyy").datepicker({
		dateFormat:'dd-mm-yy', changeMonth:true, changeYear:true, showOtherMonths: true,
		beforeShow: function(input, inst) {	return setMinMaxDate('#AA_DATE', 'today'); }
	});

    var aa_auth_id = $('#RAA_AUTHORITY_ID').val();
    //alert(aa_auth_id);
    if(aa_auth_id=='5'){
        $('#TR_RAA_AUTHORITY_ID').show();
    }else{
        $('#TR_RAA_AUTHORITY_ID').hide();
    }

	//SESSION_ID = $('#SESSION_ID').val();
	$(".raa-select").select2();
  $("#IS_RAA").select2();
	getToolTips();
	setSelect2();
	window.validator = 
	$("#frmProject").validate({
		rules: {
			"IS_RAA" : {required : true, min:1, max:3},      
			"RAA_NO" : {required : true, digits:true,maxlength:5, number:true},
			"RAA_DATE" :{required : true, indianDate:true, dpDate: true, dpCompareDate: {before:'#MY_DATE'}},
			"RAA_AMOUNT":{required : true, min:0, number:true},
            "RAA_SCAN_COPY":{required:true,extension: "pdf",filesize:2000000},
			"LA_NO" : {required : true, number:true},
			"LA_HA" : {required : true, number:true},
			"FA_HA" : {required : true, number:true},
			"L_EARTHWORK" : {required : true, number:true},
			"C_MASONRY" : {required : true, number:true},
			"C_PIPEWORK" : {required : true, number:true},			
			"C_DRIP_PIPE" : {required : true, number:true},
			"K_CONTROL_ROOMS" : {required : true, number:true}
		},
		messages: {
			"IS_RAA" : {required : "Select RAA / TS / Extra Quantity"},
			"RAA_NO" : {required : "Required - RAA / TS / Extra Quantity No ", number: "Required numeric value only."},
			"RAA_DATE" : {required : "Required - RAA / TS / Extra Quantity Date"},
			"RAA_AMOUNT" : {required : "Required - RAA / TS / Extra Quantity Amount", min:"Required Positive Amount"},
            "RAA_SCAN_COPY":{required:"Please upload scan copy of RAA",extension:"Please upload only .pdf file"}
		}
	});
	$("#radioset").buttonset();
	<?php if($raaData['IS_RAA']){?>
	changeCheckBoxOption(<?php echo $raaData['IS_RAA'];?>, true);
	<?php }?>
});

function copyValues(id){
	if($('#IS_'+id).is(":checked")){
		var prevVal = $('#PREV_'+id).val();
		$('#'+id).val(prevVal);
		$('#'+id).prop('readonly', true);	
	}else{		
		$('#'+id).prop('readonly', false);
		$('#'+id).val('');
	}
}
function checkAll(){
	var mystatus = $("#IS_NO_CHANGE").is(':checked');
	$('.css-checkbox').each(function(){
		$(this).prop('checked', mystatus);		
	});
}

//
function setEstimationFields(sno, mName, status){
	var requiredField1 = mName.substr(0, (mName.length-3));
	$('#'+requiredField1).prop('disabled', status);
	if(status) $('#'+requiredField1).val('');
}
function changeCheckBoxOption(mode){
  //console.log(mode);
  mode = parseInt(mode);
  if(mode==0){
    $('#mySaveDiv').hide();
    return;
  }else{
    $('#mySaveDiv').show();
  }
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
			<?php /*foreach($arrValidComponent as $comp){?>
				$('#<?php echo $comp;?>').rules("remove");
				$('#<?php echo $comp;?>').rules( "add", {
					required: true,
					min: 0,
					messages: {
						required: "Required.",
						min: "Minimum value 0"
					}
				});
				//$('#esti<?php echo $comp;?>').val()
			<?php }*/?>
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
			<?php /*foreach($arrValidComponent as $comp){?>
				$('#<?php echo $comp;?>').rules("remove");
				$('#<?php echo $comp;?>').rules( "add", {
					required: true,
					min: 0,
					messages: {
						required: "Required.",
						min: "Minimum value 0"
					}
				});
			<?php }*/?>
			break;
	}
}
function calculateIrri(){
	var kh = checkNo($('#KHARIF').val());
	var rab = checkNo($('#RABI').val());
	var tot = kh + rab;
	$('#IP_TOTAL').val(roundNumber(tot, 3));
}

function removeFile(raaId) {
    var ans = confirm("Are you sure to delete this file ?");
    if(!ans)
        return;
    var params = {
        'divid':'',
        'url':'removeRAAFile',
        'data':{'RAA_PROJECT_ID':raaId},
        'donefname': 'doneRemoveFile',
        'failfname' :'none',
        'alwaysfname':'none'
    };
    callMyAjax(params);
}
function doneRemoveFile(data){
    var mydata = parseAndShowMyResponse(data);
    $('#msg_raa_file').html(mydata);
    $('#raa_button_div').hide();
    $('#raa_upload_div').show();
}
<?php 
	echo 'window.arrBlockIds = ['.implode(',', $arrIPBlockIds).'];'
?>
function getIrriSubTotal(kharifOrRabi,blockId){
	//console.log('getIrriSubTotal called');
	var mode = (kharifOrRabi==0) ? "K":"R";
	var ke = checkNo($('#BLOCK_K_' + blockId).val());
	var re = checkNo($('#BLOCK_R_' + blockId).val());
	var te = ke + re;
	$('#BLOCK_T_' + blockId).val(te);

	var kt = 0;
	var rt = 0;
	for(i=0;i<window.arrBlockIds.length;i++){
		blockid = window.arrBlockIds[i];
		kt += checkNo($('#BLOCK_K_' + blockid).val());
		rt += checkNo($('#BLOCK_R_' + blockid).val());
	}
	var tt = kt + rt;
	//alert(window.arrBlockIds.join(','));	
	$('#IP_KHARIF').val(kt);
	$('#IP_RABI').val(rt);
	$('#IP_TOTAL').val(tt);	
}
function showHideOtherAuth(id){
    var authority = $('#'+id).val();
    if(authority==5){
        $('#TR_'+id).show('slow');
    }else{
        $('#TR_'+id).hide('slow');
    }
}
</script>
