<fieldset>
    <legend>
        <a id="btnfrm2" role="button" class="ui-button  ui-state-default ui-corner-all ui-button-text-only">
    <span class="ui-button-text"><i class="cus-table"></i>
        &nbsp; Estimation Form  &nbsp;
        <span id="sp_arrow_frm2_down" style=""><i class="cus-arrow-down"></i></span>
        <span id="sp_arrow_frm2_up" style="display:none;"><i class="cus-arrow-up"></i></span>
	</span>
        </a>
    </legend>
    <!--frm2-->
    <div id="frm2" style="display:block">
        <input type="hidden" name="SESSION_ID" id="SESSION_ID"
               value="<?php echo $projectSetupValues['SESSION_ID']; ?>"/>
        <input type="hidden" id="sessionRealMinDate" name="sessionRealMinDate" value=""/>
        <input type="hidden" id="sessionMinDate" name="sessionMinDate" value=""/>
        <input type="hidden" id="sessionMaxDate" name="sessionMaxDate" value=""/>
        <input type="hidden" id="startInSession" value="0"/>
        <input type="hidden" id="mytoday" value="<?php echo date("d-m-Y"); ?>"/>

        <?php		
		$IP_NA = 0;
		$IP_NA = $arrSetupStatus['IP_TOTAL_NA'];
        $arrFields = array(
            'LA_NO', 'LA_HA', 'LA_COMPLETED_NO', 'LA_COMPLETED_HA',
            'FA_HA', 'FA_COMPLETED_HA', 'L_EARTHWORK', 'C_MASONRY', 'C_PIPEWORK', 'C_DRIP_PIPE',
            'C_WATERPUMP', 'K_CONTROL_ROOMS', 'DRILLINGWORK',
			'HOUSING_PIPE', 'BLIND_PIPE', 'SLOTTED_PIPE', 'SUBMERSIBLE'
        );
        $arrAchievementCompo = array();
        foreach ($arrFields as $f) {
            array_push($arrAchievementCompo, $f . '_ACHIEVE');
        } ?>
        <table width="100%" border="0" cellpadding="3" cellspacing="2" align="right" class="ui-widget-content">
            <tr>
                <td width="50%" class="ui-state-default">Financial Year in which this setup(data) is entered (in
                    Software):
                </td>
                <td class="ui-widget-content" align="center"><strong><?php echo $SESSION_OPTIONS; ?></strong>
                </td>
            </tr>
        </table>
        <div class="wrdlinebreak"></div>
        <table width="100%" border="0" cellpadding="3" cellspacing="2" class="ui-widget-content">
            <tbody>
            <tr>
                <th class="ui-widget-header">SNo</th>
                <th colspan="4" class="ui-widget-header">Contents</th>
                <th align="center" class="ui-widget-header">NA</th>
                <th width="130" align="center" class="ui-widget-header">Latest<br/>Estimated</th>
                <th align="center" class="ui-widget-header">Unit</th>
                <th width="130" align="center" class="ui-widget-header">Achievement <br/> upto last financial
                    year
                </th>
            </tr>
            <tr>
                <th class="ui-state-default" width="20">a</th>
                <th colspan="4" align="center" class="ui-state-default">b</th>
                <th align="center" class="ui-state-default">c</th>
                <th align="center" class="ui-state-default">d</th>
                <th align="center" class="ui-state-default">e</th>
                <th align="center" class="ui-state-default">f</th>
            </tr>
            <tr>
                <td rowspan="4" align="center" class="ui-widget-content"><strong>1</strong></td>
                <td colspan="4" rowspan="4" class="ui-widget-content"><strong>Land aq cases </strong><strong>To
                        be done <br/>(as per &quot;B&quot; Land section of DPR)</strong></td>
                <td rowspan="4" align="center" class="ui-widget-content">
                    <?php $disable = (($arrSetupStatus['LA_NA']) ? ' disabled="disabled" ' : '') ?>
                    <input type="checkbox" name="LA_NA" id="LA_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['LA_NA']) echo 'checked="checked"'; ?>
                    />
                    <label for="LA_NA" class="css-label lite-blue-check">NA</label>
                </td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('LA_NO', $arrSetupStatus['LA_NA']); ?>
                    <input type="text" name="LA_NO" id="LA_NO" size="10" maxlength="15" autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['LA_NA']) ? '' : $arrEstimationData['LA_NO']; ?>"
                        <?php echo $disable; ?>
                    />
                </td>
                <td align="center" class="ui-widget-content"><strong>Numbers</strong></td>
                <td align="center" class="ui-widget-content">
                    <?php echo getRequiredDiv('LA_NO_ACHIEVE', $arrSetupStatus['LA_NA'], $isCurrentSession); ?>
                    <input type="text" name="LA_NO_ACHIEVE" id="LA_NO_ACHIEVE" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['LA_NO']; ?>"
                    />
                </td>
            </tr>
            <tr>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('LA_HA', $arrSetupStatus['LA_NA']); ?>
                    <input type="text" name="LA_HA" id="LA_HA" size="10" maxlength="15" autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['LA_NA']) ? '' : $arrEstimationData['LA_HA']; ?>"
                        <?php echo $disable; ?>
                    />
                </td>
                <td align="center" class="ui-widget-content"><strong>Hectares</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('LA_HA_ACHIEVE', $arrSetupStatus['LA_NA'], $isCurrentSession); ?>
                    <input type="text" name="LA_HA_ACHIEVE" id="LA_HA_ACHIEVE" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['LA_HA']; ?>"
                    />
                </td>
            </tr>
            <tr>
                <td rowspan="2" align="center" class="ui-widget-content"><strong>submitted</strong></td>
                <td align="center" class="ui-widget-content"><strong>Numbers</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('LA_COMPLETED_NO_ACHIEVE', $arrSetupStatus['LA_NA'], $isCurrentSession); ?>
                    <input type="text" name="LA_COMPLETED_NO_ACHIEVE" id="LA_COMPLETED_NO_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['LA_COMPLETED_NO']; ?>"
                    />
                </td>
            </tr>
            <tr>
                <td align="center" class="ui-widget-content"><strong>Hectares</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('LA_COMPLETED_HA_ACHIEVE', $arrSetupStatus['LA_NA'], $isCurrentSession); ?>
                    <input type="text" name="LA_COMPLETED_HA_ACHIEVE" id="LA_COMPLETED_HA_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['LA_COMPLETED_HA']; ?>"
                    />
                </td>
            </tr>
            <tr>
                <td rowspan="2" align="center" class="ui-widget-content"><strong>2</strong><strong></strong>
                </td>
                <td colspan="4" rowspan="2" class="ui-widget-content"><strong>Forest Acquisition to be
                        Done</strong></td>
                <td rowspan="2" align="center" class="ui-widget-content">
                    <?php $disable = (($arrSetupStatus['FA_NA']) ? ' disabled="disabled" ' : '') ?>
                    <input type="checkbox" name="FA_NA" id="FA_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['FA_NA']) echo 'checked="checked"'; ?>
                    />
                    <label for="FA_NA" class="css-label lite-blue-check">NA</label>
                </td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('FA_HA', $arrSetupStatus['FA_NA']); ?>
                    <input type="text" name="FA_HA" id="FA_HA" size="10" maxlength="15" autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['FA_NA']) ? '' : $arrEstimationData['FA_HA']; ?>"
                        <?php echo $disable; ?>
                    />
                </td>
                <td align="center" class="ui-widget-content"><strong>Hectares</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('FA_HA_ACHIEVE', $arrSetupStatus['FA_NA'], $isCurrentSession); ?>
                    <input type="text" name="FA_HA_ACHIEVE" id="FA_HA_ACHIEVE" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['FA_HA']; ?>"
                    />
                </td>
            </tr>
            <tr>
                <td class="ui-widget-content" align="center"><strong>Submitted</strong></td>
                <td align="center" class="ui-widget-content"><strong>Hectares</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('FA_COMPLETED_HA_ACHIEVE', $arrSetupStatus['FA_NA'], $isCurrentSession); ?>
                    <input type="text" name="FA_COMPLETED_HA_ACHIEVE" id="FA_COMPLETED_HA_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['FA_COMPLETED_HA']; ?>"
                    />
                </td>
            </tr>
            <tr>
                <td rowspan="2" align="center" class="ui-widget-content"><strong>3</strong></td>
                <td colspan="3" rowspan="2" class="ui-widget-content"><strong>Earthwork<br/>(As per Earthwork given in DPR)</strong>
                </td>
                <td class="ui-widget-content"><strong>a. Earthwork</strong></td>
                <td align="center" class="ui-widget-content">
                    <?php $disable = (($arrSetupStatus['L_EARTHWORK_NA']) ? ' disabled="disabled" ' : '') ?>
                    <input type="checkbox" name="L_EARTHWORK_NA" id="L_EARTHWORK_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['L_EARTHWORK_NA']) echo 'checked="checked"'; ?>
                    />
                    <label for="L_EARTHWORK_NA" class="css-label lite-blue-check">NA</label>
                </td>
                <td align="center" class="ui-widget-content">
                    <?php echo getRequiredDiv('L_EARTHWORK', $arrSetupStatus['L_EARTHWORK_NA']); ?>
                    <input type="text" name="L_EARTHWORK" id="L_EARTHWORK" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['L_EARTHWORK_NA']) ? '' : $arrEstimationData['L_EARTHWORK']; ?>"
                        <?php echo $disable; ?>
                    />
                </td>
                <td align="center" class="ui-widget-content"><strong>Th Cum</strong></td>
                <td align="center" class="ui-widget-content">
                    <?php echo getRequiredDiv('L_EARTHWORK_ACHIEVE', $arrSetupStatus['L_EARTHWORK_NA'], $isCurrentSession); ?>
                    <input type="text" name="L_EARTHWORK_ACHIEVE" id="L_EARTHWORK_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['L_EARTHWORK']; ?>"/>
                </td>
            </tr>
            <tr>
              <td class="ui-widget-content"><strong>b. Drilling</strong></td>
              <td align="center" class="ui-widget-content"><?php $disable = (($arrSetupStatus['DRILLINGWORK_NA']) ? ' disabled="disabled" ' : '') ?>
                <input type="checkbox" name="DRILLINGWORK_NA" id="DRILLINGWORK_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['DRILLINGWORK_NA']) echo 'checked="checked"'; ?>
                    />
              <label for="DRILLINGWORK_NA" class="css-label lite-blue-check">NA</label></td>
              <td align="center" class="ui-widget-content"><?php echo getRequiredDiv('DRILLINGWORK', $arrSetupStatus['DRILLINGWORK_NA']); ?>
              <input type="text" name="DRILLINGWORK" id="DRILLINGWORK" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['DRILLINGWORK_NA']) ? '' : $arrEstimationData['DRILLINGWORK']; ?>"
                        <?php echo $disable; ?>
                    /></td>
              <td align="center" class="ui-widget-content"><strong>Mtrs</strong></td>
              <td align="center" class="ui-widget-content">
			  <?php echo getRequiredDiv('DRILLINGWORK_ACHIEVE', $arrSetupStatus['DRILLINGWORK_NA'], $isCurrentSession); ?>
              <input type="text" name="DRILLINGWORK_ACHIEVE" id="DRILLINGWORK_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['DRILLINGWORK']; ?>"/></td>
            </tr>
            <tr>
                <td rowspan="5" align="center" class="ui-widget-content"><strong>4</strong></td>
                <td rowspan="5" class="ui-widget-content"><strong>Masonry/Concrete <br/>
                        (As per "C" Masonry Section <br/>
                        of DPR)</strong></td>
                <td colspan="3" class="ui-widget-content"><strong>a. Masonry/Concrete</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php $disable = (($arrSetupStatus['C_MASONRY_NA']) ? ' disabled="disabled" ' : '') ?>
                    <input type="checkbox" name="C_MASONRY_NA" id="C_MASONRY_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['C_MASONRY_NA']) echo 'checked="checked"'; ?>
                    />
                    <label for="C_MASONRY_NA" class="css-label lite-blue-check">NA</label>
                </td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('C_MASONRY', $arrSetupStatus['C_MASONRY_NA']); ?>
                    <input type="text" name="C_MASONRY" id="C_MASONRY" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['C_MASONRY_NA']) ? '' : $arrEstimationData['C_MASONRY']; ?>"
                        <?php echo $disable; ?>
                    />
                </td>
                <td align="center" class="ui-widget-content"><strong>Th Cum</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('C_MASONRY_ACHIEVE', $arrSetupStatus['C_MASONRY_NA'], $isCurrentSession); ?>
                    <input type="text" name="C_MASONRY_ACHIEVE" id="C_MASONRY_ACHIEVE" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['C_MASONRY']; ?>"/>
                </td>
            </tr>
            <tr>
                <td rowspan="3" class="ui-widget-content"><strong>b. Pipe Works</strong></td>
                <td colspan="2" class="ui-widget-content"><strong>i. Housing Pipe</strong>
                </td>
                <td class="ui-widget-content" align="center">
                    <?php $disable = (($arrSetupStatus['HOUSING_PIPE_NA']) ? ' disabled="disabled" ' : '') ?>
                    <input type="checkbox" name="HOUSING_PIPE_NA" id="HOUSING_PIPE_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['HOUSING_PIPE_NA']) echo 'checked="checked"'; ?>
                    />
                    <label for="HOUSING_PIPE_NA" class="css-label lite-blue-check">NA</label>
                </td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('HOUSING_PIPE', $arrSetupStatus['HOUSING_PIPE_NA']); ?>
                    <input type="text" name="HOUSING_PIPE" id="HOUSING_PIPE" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['HOUSING_PIPE_NA']) ? '' : $arrEstimationData['HOUSING_PIPE']; ?>"
                        <?php echo $disable; ?>
                    />
                </td>
                <td align="center" class="ui-widget-content"><strong>Mtrs</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('HOUSING_PIPE_ACHIEVE', $arrSetupStatus['HOUSING_PIPE_NA'], $isCurrentSession); ?>
                    <input type="text" name="HOUSING_PIPE_ACHIEVE" id="HOUSING_PIPE_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['HOUSING_PIPE']; ?>"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="ui-widget-content"><strong>ii. Blind Pipe/Casing Pipe</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php $disable = (($arrSetupStatus['BLIND_PIPE_NA']) ? ' disabled="disabled" ' : '') ?>
                    <input type="checkbox" name="BLIND_PIPE_NA" id="BLIND_PIPE_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['BLIND_PIPE_NA']) echo 'checked="checked"'; ?>
                    />
                    <label for="BLIND_PIPE_NA" class="css-label lite-blue-check">NA</label>
                </td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('BLIND_PIPE', $arrSetupStatus['BLIND_PIPE_NA']); ?>
                    <input type="text" name="BLIND_PIPE" id="BLIND_PIPE" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['BLIND_PIPE_NA']) ? '' : $arrEstimationData['BLIND_PIPE']; ?>"
                        <?php echo $disable; ?>
                    />
                </td>
                <td align="center" class="ui-widget-content"><strong>Mtrs</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('BLIND_PIPE_ACHIEVE', $arrSetupStatus['BLIND_PIPE_NA'], $isCurrentSession); ?>
                    <input type="text" name="BLIND_PIPE_ACHIEVE" id="BLIND_PIPE_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['BLIND_PIPE']; ?>"/>
                </td>
            </tr>
            <tr>
              <td colspan="2" class="ui-widget-content"><strong>iii. Slotted Pipe/Screen</strong></td>
              <td class="ui-widget-content" align="center"><?php $disable = (($arrSetupStatus['SLOTTED_PIPE_NA']) ? ' disabled="disabled" ' : '') ?>
                <input type="checkbox" name="SLOTTED_PIPE_NA" id="SLOTTED_PIPE_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['SLOTTED_PIPE_NA']) echo 'checked="checked"'; ?>
                    />
              <label for="SLOTTED_PIPE_NA" class="css-label lite-blue-check">NA</label></td>
              <td class="ui-widget-content" align="center"><?php echo getRequiredDiv('SLOTTED_PIPE', $arrSetupStatus['SLOTTED_PIPE_NA']); ?>
              <input type="text" name="SLOTTED_PIPE" id="SLOTTED_PIPE" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['SLOTTED_PIPE_NA']) ? '' : $arrEstimationData['SLOTTED_PIPE']; ?>"
                        <?php echo $disable; ?>
                    /></td>
              <td align="center" class="ui-widget-content"><strong>Mtrs</strong></td>
              <td class="ui-widget-content" align="center"><?php echo getRequiredDiv('SLOTTED_PIPE_ACHIEVE', $arrSetupStatus['SLOTTED_PIPE_NA'], $isCurrentSession); ?>
              <input type="text" name="SLOTTED_PIPE_ACHIEVE" id="SLOTTED_PIPE_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['SLOTTED_PIPE']; ?>"/></td>
            </tr>
            <tr>
                <td colspan="3" class="ui-widget-content"><strong>c. Pumps/Submersible Pumps/Hand Pumps</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php $disable = (($arrSetupStatus['SUBMERSIBLE_NA']) ? ' disabled="disabled" ' : '') ?>
                    <input type="checkbox" name="SUBMERSIBLE_NA" id="SUBMERSIBLE_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['SUBMERSIBLE_NA']) echo 'checked="checked"'; ?>
                    />
                    <label for="SUBMERSIBLE_NA" class="css-label lite-blue-check">NA</label>
                </td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('SUBMERSIBLE', $arrSetupStatus['SUBMERSIBLE_NA']); ?>
                    <input type="text" name="SUBMERSIBLE" id="SUBMERSIBLE" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['SUBMERSIBLE_NA']) ? '' : $arrEstimationData['SUBMERSIBLE']; ?>"
                        <?php echo $disable; ?>
                    />
                </td>
                <td align="center" class="ui-widget-content"><strong>Numbers</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('SUBMERSIBLE_ACHIEVE', $arrSetupStatus['SUBMERSIBLE_NA'], $isCurrentSession); ?>
                    <input type="text" name="SUBMERSIBLE_ACHIEVE" id="SUBMERSIBLE_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['SUBMERSIBLE']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="ui-widget-content" align="center"><strong>5</strong></td>
                <td class="ui-widget-content" colspan="4"><strong>Building Works<br>(As per "K" Building sectin
                        of DPR)<br/> Control Rooms</strong></td>
                <td class="ui-widget-content" align="center">
                    <?php $disable = (($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? ' disabled="disabled" ' : '') ?>
                    <input type="checkbox" name="K_CONTROL_ROOMS_NA" id="K_CONTROL_ROOMS_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['K_CONTROL_ROOMS_NA']) echo 'checked="checked"'; ?>
                    />
                    <label for="K_CONTROL_ROOMS_NA" class="css-label lite-blue-check">NA</label>
                </td>
                <td class="ui-widget-content" align="center">
                    <?php echo getRequiredDiv('K_CONTROL_ROOMS', $arrSetupStatus['K_CONTROL_ROOMS_NA']); ?>
                    <input type="text" name="K_CONTROL_ROOMS" id="K_CONTROL_ROOMS" size="10" maxlength="15"
                           autocomplete="off"
                           class="centertext"
                           value="<?php echo ($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? '' : $arrEstimationData['K_CONTROL_ROOMS']; ?>"
                        <?php echo $disable; ?> />
                </td>
                <td align="center" class="ui-widget-content"><strong>Numbers</strong></td>
                <td align="center" class="ui-widget-content">
                    <?php echo getRequiredDiv('K_CONTROL_ROOMS_ACHIEVE', $arrSetupStatus['K_CONTROL_ROOMS_NA'], $isCurrentSession); ?>
                    <input type="text" name="K_CONTROL_ROOMS_ACHIEVE" id="K_CONTROL_ROOMS_ACHIEVE" size="10"
                           maxlength="15" autocomplete="off"
                           class="centertext" value="<?php echo $arrAchievementData['K_CONTROL_ROOMS']; ?>"/>
                </td>
            </tr>
            <tr id="ip_tr">
                <td class="ui-widget-content" align="center"><strong>6</strong></td>
                <td colspan="4" class="ui-widget-content"><strong>Irrigation Potential To be Created</strong>
                </td>
              <td class="ui-widget-content">
			  <?php 
			  //print_r($arrSetupStatus);
			  			  
			  $disable = (($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? ' disabled="disabled" ' : '') ?>
                <input type="checkbox" name="IP_TOTAL_NA" id="IP_TOTAL_NA"
                           class="css-checkbox"
                           onclick="setEstimationFields(this.name, this.checked)" value="1"
                        <?php if ($arrSetupStatus['IP_TOTAL_NA']) echo 'checked="checked"'; ?>
                    />
                <label for="IP_TOTAL_NA" class="css-label lite-blue-check">NA</label></td>
                <td class="ui-widget-content">&nbsp;</td>
                <td class="ui-widget-content" align="center"><strong>Hectares</strong></td>
                <td class="ui-widget-content" align="center"></td>
            </tr>
            <?php $i = 0;
            //showArrayValues($arrBlockData);
			$disabled = (($IP_NA) ? 'disabled="disabled"':'');
			$arrIPBlockIds = array();
            $arrBlocks = array();
            $arrTotals = array('KHARIF' => 0, 'RABI' => 0, 'TOTAL' => 0);
            $arrATotals = array('KHARIF' => 0, 'RABI' => 0, 'TOTAL' => 0);
            foreach ($arrBlockData as $k => $rec) {
				//$arrIPBlockIds = array();
				array_push($arrIPBlockIds, $k);
                $blockId = $k;
                $arrTotals['KHARIF'] += $rec['ESTIMATION_IP']['KHARIF'];
                $arrTotals['RABI'] += $rec['ESTIMATION_IP']['RABI'];
                $arrTotals['TOTAL'] += ($rec['ESTIMATION_IP']['KHARIF'] + $rec['ESTIMATION_IP']['RABI']);
                $arrATotals['KHARIF'] += $rec['ACHIEVEMENT_IP']['KHARIF'];
                $arrATotals['RABI'] += $rec['ACHIEVEMENT_IP']['RABI'];
                $arrATotals['TOTAL'] += ($rec['ACHIEVEMENT_IP']['KHARIF'] + $rec['ACHIEVEMENT_IP']['RABI']);
                array_push($arrBlocks, $k);
                ?>
                <tr id="tr-bk-<?php echo $blockId; ?>" class="trb-<?php echo $blockId; ?> trbip">
                    <td rowspan="3" align="center" class="ui-widget-content">
                        <strong><?php echo chr($i + 97); ?></strong></td>
                    <td colspan="5" rowspan="3" class="ui-widget-content">
                        <strong><?php echo $rec['BLOCK_NAME']; ?></strong></td>
                    <td class="ui-widget-content" align="center">
                        <?php echo getRequiredDiv('BLOCK_EIP_K_' . $blockId, 0); ?>
                        <input type="text" name="BLOCK_EIP_K[<?php echo $blockId; ?>]"
                               id="BLOCK_EIP_K_<?php echo $blockId; ?>"
                               size="10" maxlength="15" autocomplete="off"
                               <?php echo $disabled;?>
                               onkeyup="getIrriSubTotal(0, 0, <?php echo $blockId; ?>)"
                               class="centertext" value="<?php echo $rec['ESTIMATION_IP']['KHARIF']; ?>"/>
                    </td>
                    <td class="ui-state-default" align="center"><strong>Kharif</strong></td>
                    <td class="ui-widget-content" align="center">
                        <?php echo getRequiredDiv('BLOCK_AIP_K_' . $blockId, 0, $isCurrentSession); ?>
                        <input type="text" name="BLOCK_AIP_K[<?php echo $blockId; ?>]"
                               id="BLOCK_AIP_K_<?php echo $blockId; ?>"
                               <?php echo $disabled;?>
                               size="10" maxlength="15" autocomplete="off"
                               
                               onkeyup="getIrriSubTotal(0, 1, <?php echo $blockId; ?>)"
                               class="centertext" value="<?php echo $rec['ACHIEVEMENT_IP']['KHARIF']; ?>"/>
                    </td>
                </tr>
                <tr id="tr-br-<?php echo $blockId; ?>" class="trb-<?php echo $blockId; ?>">
                    <td class="ui-widget-content" align="center">
                        <?php echo getRequiredDiv('BLOCK_EIP_R_' . $blockId, 0); ?>
                        <input type="text" name="BLOCK_EIP_R[<?php echo $blockId; ?>]"
                               id="BLOCK_EIP_R_<?php echo $blockId; ?>"
                               size="10" maxlength="15" autocomplete="off"
                               <?php echo $disabled;?>
                               onkeyup="getIrriSubTotal(1, 0, <?php echo $blockId; ?>)"
                               class="centertext" value="<?php echo $rec['ESTIMATION_IP']['RABI']; ?>"/>
                    </td>
                    <td class="ui-state-default" align="center"><strong>Rabi</strong></td>
                    <td class="ui-widget-content" align="center">
                        <?php echo getRequiredDiv('BLOCK_AIP_R_' . $blockId, 0, $isCurrentSession); ?>
                        <input type="text" name="BLOCK_AIP_R[<?php echo $blockId; ?>]"
                               id="BLOCK_AIP_R_<?php echo $blockId; ?>"

                               <?php echo $disabled;?>
                               size="10" maxlength="15" autocomplete="off"
                               
                               onkeyup="getIrriSubTotal(1, 1, <?php echo $blockId; ?>)"
                               class="centertext" value="<?php echo $rec['ACHIEVEMENT_IP']['RABI']; ?>"/>
                    </td>
                </tr>
                <tr id="tr-bt-<?php echo $blockId; ?>" class="trb-<?php echo $blockId; ?>">
                    <td class="ui-state-default" align="center" id="BLOCK_EIP_T_<?php echo $blockId; ?>">
                        <?php echo $rec['ESTIMATION_IP']['KHARIF'] + $rec['ESTIMATION_IP']['RABI']; ?>
                    </td>
                    <td class="ui-state-default" align="center"><strong>Total</strong></td>
                    <td class="ui-state-default" align="center" id="BLOCK_AIP_T_<?php echo $blockId; ?>">
                        <?php echo $rec['ACHIEVEMENT_IP']['KHARIF'] + $rec['ACHIEVEMENT_IP']['RABI']; ?>
                    </td>
                </tr>

                <?php $i++;
            } ?>
            <tr id="tr-bk-total">
                <td rowspan="3" align="center" class="ui-state-default">
                    <strong></strong><strong></strong><strong></strong></td>
                <td colspan="5" rowspan="3" class="ui-state-default"><strong>Total Irrigation Potential To be
                        Created</strong></td>
                <td class="ui-widget-content" align="center"
                    id="IP_KHARIF_T"><?php echo $arrTotals['KHARIF']; ?></td>
                <td class="ui-state-default" align="center"><strong>Kharif</strong></td>
                <td class="ui-widget-content" align="center"
                    id="IP_KHARIF_T_ACHIEVE"><?php echo $arrATotals['KHARIF']; ?></td>
            </tr>
            <tr>
                <td class="ui-widget-content" align="center"
                    id="IP_RABI_T"><?php echo $arrTotals['RABI']; ?></td>
                <td class="ui-state-default" align="center"><strong>Rabi</strong></td>
                <td class="ui-widget-content" align="center"
                    id="IP_RABI_T_ACHIEVE"><?php echo $arrATotals['RABI']; ?></td>
            </tr>
            <tr>
                <td class="ui-state-default" align="center"
                    id="IP_TOTAL_T"><?php echo $arrTotals['TOTAL']; ?></td>
                <td class="ui-state-default" align="center"><strong>Total</strong></td>
                <td class="ui-state-default" align="center"
                    id="IP_TOTAL_T_ACHIEVE"><?php echo $arrATotals['TOTAL']; ?></td>
            </tr>
            <tr>
              <td align="center" class="ui-widget-content">7</td>
              <td colspan="5" class="ui-widget-content"><strong>Drinking Purpose</strong></td>
              <td colspan="3" align="center" class="ui-widget-content"
                    id="">	                    
                        <label><input type="radio" class="required" <?php if ($arrSetupStatus['DRINKING_PURPOSE']==1) echo 'checked="checked"'; ?> name="DRINKING_PURPOSE"  value="1" /> Yes </label>
                        
    	                <label><input type="radio"  class="required" <?php if ($arrSetupStatus['DRINKING_PURPOSE']==0) echo 'checked="checked"'; ?>  name="DRINKING_PURPOSE"  value="0" /> No </label>
                    </td>
              </tr>
        </table>

    </div>
    <!--//frm2-->
</fieldset>
<div class="wrdlinebreak"></div>
<!----------------------------[ Form - III ]------------------------------->
<?php //showArrayValues($arrSetupStatus);
$arrOptions = array(); ?>
<fieldset>
    <legend>
        <a role="button" class="ui-button  ui-state-default ui-corner-all ui-button-text-only"
           onclick="$('#frm4').slideToggle('slow');$('#sp_arrow_frm4_down').toggle();$('#sp_arrow_frm4_up').toggle(); ">
    <span class="ui-button-text"><i class="cus-table"></i>
        &nbsp; Milestone Form &nbsp;
        <span id="sp_arrow_frm4_down" style=""><i class="cus-arrow-down"></i></span>
        <span id="sp_arrow_frm4_up" style="display:none;"><i class="cus-arrow-up"></i></span>
	</span>
        </a>
    </legend>
    <div id="frm4" class="class2" style="display:block">
        <?php //showArrayValues($arrStatusOptions);?>
        <table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1">
            <tr>
                <td class="ui-state-default" valign="middle" align="right" colspan="2">Completion Date of Scheme
                    :
                </td>
                <td class="ui-state-default" valign="middle" colspan="2">
                    <input name="PROJECT_COMPLETION_DATE" id="DATE_COMPLETION"
                           size="30" maxlength="50" type="text" class="centertext"
                           value="<?php echo myDateFormat($projectSetupValues['PROJECT_COMPLETION_DATE']); ?>"/>
                </td>
            </tr>
            <tr>
                <th class="ui-widget-header"></th>
                <th class="ui-widget-header">Contents</th>
                <th class="ui-widget-header">Status upto Last Financial Year</th>
                <th class="ui-widget-header">Target Dates of Completion</th>
            </tr>
            <tr>
                <td class="ui-state-default" align="center">a)</td>
                <td class="ui-state-default">Submission of LA Cases</td>
                <td class="ui-widget-content">
                    <?php echo getRequiredSign('left');
                    $arrHideCondition = array(0, 1, 5);
                    $displayCSS = 'block';
                    $isRequired = 'class=""';
                    if (in_array($arrStatusData['LA_CASES_STATUS'], $arrHideCondition) || $arrSetupStatus['LA_NA']) {
                        $displayCSS = 'none';
                        $isRequired = '';
                    } ?>
                    <select name="LA_CASES_STATUS" id="LA_CASES_STATUS" class="sel2" style="width:180px"
                        <?php echo(($arrSetupStatus['LA_NA']) ? 'disabled="disabled"' : ''); ?>
                            onchange="enableDisableDate(this.name, this.value)">
                        <?php echo $arrStatusOptions['LA_CASES_STATUS']; ?>
                    </select>
                    <?php $arrOptions['LA_CASES_STATUS'] = $arrStatusOptions['LA_CASES_STATUS']; ?>
                </td>
                <td class="ui-widget-content" align="center">
                    <div id="reqLA_CASES_STATUS"
                         style="float:left;display:<?php echo $displayCSS; ?>"><?php echo getRequiredSign('left'); ?></div>
                    <input name="LA_DATE" id="LA_DATE" readonly="readonly" type="text" size="48" maxlength="10"
                           style="width:50%;text-align:center;display:<?php echo $displayCSS; ?>"
                           value="<?php echo myDateFormat($arrTargetDates['LA_DATE']); ?>"/>
                </td>
            </tr>
            <tr>
                <td class="ui-state-default" align="center">b)</td>
                <td class="ui-state-default">Submission of FA Cases</td>
                <td class="ui-widget-content">
                    <?php
                    echo getRequiredSign('left');
                    $displayCSS = 'block';
                    $isRequired = 'class=""';
                    if (in_array($arrStatusData['FA_CASES_STATUS'], $arrHideCondition) || $arrSetupStatus['FA_NA']) {
                        $displayCSS = 'none';
                        $isRequired = '';
                    } ?>
                    <select name="FA_CASES_STATUS" id="FA_CASES_STATUS" class="sel2" style="width:180px"
                        <?php echo(($arrSetupStatus['FA_NA']) ? 'disabled="disabled"' : ''); ?>
                            onchange="enableDisableDate(this.name, this.value)">
                        <?php echo $arrStatusOptions['FA_CASES_STATUS']; ?>
                    </select>
                    <?php $arrOptions['FA_CASES_STATUS'] = $arrStatusOptions['FA_CASES_STATUS']; ?>
                </td>
                <td class="ui-widget-content" align="center">
                    <div id="reqFA_CASES_STATUS"
                         style="float:left;display:<?php echo $displayCSS; ?>"><?php echo getRequiredSign('left'); ?></div>
                    <input name="FA_DATE" id="FA_DATE" readonly="readonly" type="text" size="48" maxlength="10"
                           style="width:50%;text-align:center;display:<?php echo $displayCSS; ?>"
                           value="<?php echo myDateFormat($arrTargetDates['FA_DATE']); ?>"/>
                </td>
            </tr>

            <tr>
                <td class="ui-state-default" align="center">c)</td>
                <td class="ui-state-default">Drilling Work</td>
                <td class="ui-widget-content">
                    <?php echo getRequiredSign('left');
                    $displayCSS = 'block';
                    $isRequired = 'class=""';
                    if (in_array($arrStatusData['DRILLINGWORK_STATUS'], $arrHideCondition) || $arrSetupStatus['DRILLINGWORK_NA']) {
                        $displayCSS = 'none';
                        $isRequired = '';
                    } ?>
                    <select name="DRILLINGWORK_STATUS" id="DRILLINGWORK_STATUS" class="sel2" style="width:180px"
                        <?php echo(($arrSetupStatus['DRILLINGWORK_NA']) ? 'disabled="disabled"' : ''); ?>
                            onchange="enableDisableDate(this.name, this.value)">
                        <?php echo $arrStatusOptions['DRILLINGWORK_STATUS']; ?>
                    </select>
                    <?php $arrOptions['DRILLINGWORK_STATUS'] =
					 $arrStatusOptions['DRILLINGWORK_STATUS']; ?>
                </td>
                <td class="ui-widget-content" align="center">
                    <div id="reqDRILLINGWORK_STATUS"
                         style="float:left;display:<?php echo $displayCSS; ?>"><?php echo getRequiredSign('left'); ?></div>
                    <input name="DRILLINGWORK_DATE" id="DRILLINGWORK_DATE" readonly="readonly" type="text"
                           size="48" maxlength="10"
                           style="width:50%;text-align:center;display:<?php echo $displayCSS; ?>"
                           value="<?php echo myDateFormat($arrTargetDates['DRILLINGWORK_DATE']); ?>"/>
                </td>
            </tr>

            <tr>
                <td class="ui-state-default" align="center">d)</td>
                <td class="ui-state-default">Housing Pipe</td>
                <td class="ui-widget-content">
                    <?php echo getRequiredSign('left');
                    $displayCSS = 'block';
                    $isRequired = 'class=""';
                    if (in_array($arrStatusData['HOUSING_PIPE_STATUS'], $arrHideCondition) || 
					$arrSetupStatus['HOUSING_PIPE_NA']) {
                        $displayCSS = 'none';
                        $isRequired = '';
                    } ?>
                    <select name="HOUSING_PIPE_STATUS" id="HOUSING_PIPE_STATUS" class="sel2" style="width:180px"
                        <?php echo(($arrSetupStatus['HOUSING_PIPE_NA']) ? 'disabled="disabled"' : ''); ?>
                            onchange="enableDisableDate(this.name, this.value)">
                        <?php echo $arrStatusOptions['HOUSING_PIPE_STATUS']; ?>
                    </select>
                    <?php $arrOptions['HOUSING_PIPE_STATUS'] = $arrStatusOptions['HOUSING_PIPE_STATUS']; ?>
                </td>
                <td class="ui-widget-content" align="center">
                    <div id="reqHOUSING_PIPE_STATUS"
                         style="float:left;display:<?php echo $displayCSS; ?>"><?php echo getRequiredSign('left'); ?></div>
                    <input name="HOUSING_PIPE_DATE" id="HOUSING_PIPE_DATE" readonly="readonly" type="text"
                           size="48" maxlength="10"
                           style="width:50%;text-align:center;display:<?php echo $displayCSS; ?>"
                           value="<?php echo myDateFormat($arrTargetDates['HOUSING_PIPE_DATE']); ?>"/>
                </td>
            </tr>
            <tr>
                <td class="ui-state-default" align="center">e)</td>
                <td class="ui-state-default">Blind Pipe/Casing Pipe</td>
                <td class="ui-widget-content">
                    <?php echo getRequiredSign('left');
                    $displayCSS = 'block';
                    $isRequired = 'class=""';
                    if (in_array($arrStatusData['BLIND_PIPE_STATUS'], $arrHideCondition) || $arrSetupStatus['BLIND_PIPE_NA']) {
                        $displayCSS = 'none';
                        $isRequired = '';
                    } ?>
                    <select name="BLIND_PIPE_STATUS" id="BLIND_PIPE_STATUS" class="sel2"
                            style="width:180px"
                        <?php echo(($arrSetupStatus['BLIND_PIPE_NA']) ? 'disabled="disabled"' : ''); ?>
                            onchange="enableDisableDate(this.name, this.value)">
                        <?php echo $arrStatusOptions['BLIND_PIPE_STATUS']; ?>
                    </select>
                    <?php $arrOptions['BLIND_PIPE_STATUS'] = $arrStatusOptions['BLIND_PIPE_STATUS']; ?>
                </td>
                <td class="ui-widget-content" align="center">
                    <div id="reqBLIND_PIPE_STATUS"
                         style="float:left;display:<?php echo $displayCSS; ?>"><?php echo getRequiredSign('left'); ?></div>
                    <input name="BLIND_PIPE_DATE" id="BLIND_PIPE_DATE" readonly="readonly" type="text"
                           size="48" maxlength="10"
                           style="width:50%;text-align:center;display:<?php echo $displayCSS; ?>"
                           value="<?php echo myDateFormat($arrTargetDates['BLIND_PIPE_DATE']); ?>"/>
                </td>
            </tr>
            <tr>
                <td class="ui-state-default" align="center">f)</td>
                <td class="ui-state-default">Slotted Pipe/Screen</td>
                <td class="ui-widget-content">
                    <?php echo getRequiredSign('left');
                    $displayCSS = 'block';
                    $isRequired = 'class=""';
					//echo '<br>a='. $arrStatusData['SLOTTED_PIPE_STATUS'];
					//echo '<br>b='. $arrSetupStatus['SLOTTED_PIPE_NA'];
                    if (in_array($arrStatusData['SLOTTED_PIPE_STATUS'], $arrHideCondition) || $arrSetupStatus['SLOTTED_PIPE_NA']) {
                        $displayCSS = 'none';
                        $isRequired = '';
                    } ?>
                    <select name="SLOTTED_PIPE_STATUS" id="SLOTTED_PIPE_STATUS" class="sel2" style="width:180px"
                        <?php echo(($arrSetupStatus['SLOTTED_PIPE_NA']) ? 'disabled="disabled"' : ''); ?>
                            onchange="enableDisableDate(this.name, this.value)">
                        <?php echo $arrStatusOptions['SLOTTED_PIPE_STATUS']; ?>
                    </select>
                    <?php $arrOptions['SLOTTED_PIPE_STATUS'] = $arrStatusOptions['SLOTTED_PIPE_STATUS']; ?>
                </td>
                <td class="ui-widget-content" align="center">
                    <div id="reqSLOTTED_PIPE_STATUS"
                         style="float:left;display:<?php echo $displayCSS; ?>"><?php echo getRequiredSign('left'); ?></div>
                    <input name="SLOTTED_PIPE_DATE" id="SLOTTED_PIPE_DATE" readonly="readonly" type="text"
                           size="48" maxlength="10"
                           style="width:50%;text-align:center;display:<?php echo $displayCSS; ?>"
                           value="<?php echo myDateFormat($arrTargetDates['SLOTTED_PIPE_DATE']); ?>"/>
                </td>
            </tr>
            <tr>
                <td class="ui-state-default" align="center">g)</td>
                <td class="ui-state-default">Pumps/Submersible pumps/Hand pumps</td>
                <td class="ui-widget-content">
                    <?php echo getRequiredSign('left');
                    $displayCSS = 'block';
                    $isRequired = 'class=""';
    								//echo '<br>a='. $arrStatusData['SUBMERSIBLE_STATUS'];
    								//echo '<br>b='. $arrSetupStatus['SUBMERSIBLE_NA'];
                    if (in_array($arrStatusData['SUBMERSIBLE_STATUS'], $arrHideCondition) || $arrSetupStatus['SUBMERSIBLE_NA']) {
                        $displayCSS = 'none';
                        $isRequired = '';
                    } ?>
                    <select name="SUBMERSIBLE_STATUS" id="SUBMERSIBLE_STATUS" class="sel2" style="width:180px"
                        <?php echo(($arrSetupStatus['SUBMERSIBLE_NA']) ? 'disabled="disabled"' : ''); ?>
                            onchange="enableDisableDate(this.name, this.value)">
                        <?php echo $arrStatusOptions['SUBMERSIBLE_STATUS']; ?>
                    </select>
                    <?php $arrOptions['SUBMERSIBLE_STATUS'] = $arrStatusOptions['SUBMERSIBLE_STATUS']; ?>
                </td>
                <td class="ui-widget-content" align="center">
                    <div id="reqSUBMERSIBLE_STATUS"
                         style="float:left;display:<?php echo $displayCSS; ?>"><?php echo getRequiredSign('left'); ?></div>
                    <input name="SUBMERSIBLE_DATE" id="SUBMERSIBLE_DATE" readonly="readonly" type="text"
                           size="48" maxlength="10"
                           style="width:50%;text-align:center;display:<?php echo $displayCSS; ?>"
                           value="<?php echo myDateFormat($arrTargetDates['SUBMERSIBLE_DATE']); ?>"/>
                </td>
            </tr>
            <tr>
                <td class="ui-state-default" align="center">h)</td>
                <td class="ui-state-default">Control Rooms</td>
                <td class="ui-widget-content">
                    <?php echo getRequiredSign('left');
                    $displayCSS = 'block';
                    $isRequired = 'class=""';
                    if (in_array($arrStatusData['CONTROL_ROOMS_STATUS'], $arrHideCondition) || $arrSetupStatus['K_CONTROL_ROOMS_NA']) {
                        $displayCSS = 'none';
                        $isRequired = '';
                    } ?>
                    <select name="CONTROL_ROOMS_STATUS" id="CONTROL_ROOMS_STATUS" class="sel2"
                            style="width:180px"
                        <?php echo(($arrSetupStatus['K_CONTROL_ROOMS_NA']) ? 'disabled="disabled"' : ''); ?>
                            onchange="enableDisableDate(this.name, this.value)">
                        <?php echo $arrStatusOptions['CONTROL_ROOMS_STATUS']; ?>
                    </select>
                    <?php $arrOptions['CONTROL_ROOMS_STATUS'] = $arrStatusOptions['CONTROL_ROOMS_STATUS']; ?>
                </td>
                <td class="ui-widget-content" align="center">
                    <div id="reqCONTROL_ROOMS_STATUS"
                         style="float:left;display:<?php echo $displayCSS; ?>"><?php echo getRequiredSign('left'); ?></div>
                    <input name="CONTROL_ROOMS_DATE" id="CONTROL_ROOMS_DATE" readonly="readonly" type="text"
                           size="48" maxlength="10"
                           style="width:50%;text-align:center;display:<?php echo $displayCSS; ?>"
                           value="<?php echo myDateFormat($arrTargetDates['CONTROL_ROOMS_DATE']); ?>"/>
                </td>
            </tr>
        </table>
    </div>
</fieldset>