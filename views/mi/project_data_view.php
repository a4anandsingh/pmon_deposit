<?php
$projectSetupId = $projectSetupValues['PROJECT_SETUP_ID'];
$sessionId = $projectSetupValues['SESSION_ID'];
$curSessionId = getSessionDataByKey('CURRENT_SESSION_ID');
$isCurrentSession = ($sessionId == $curSessionId) ? 1 : 0;
function getRequiredDiv($ctrl, $status, $isCurrentSession = 0)
{
    $ipos = strpos($ctrl, '_ACHIEVE');
    $st = '';
    if ($ipos) {
        if ($isCurrentSession) {
            $st = true;//($status) ?
        } else {
            $st = ($status) ? true : false;
        }
    } else {
        $strSearch = substr($ctrl, 0, 10);
        if ($strSearch == 'BLOCK_EIP_') {
            $st = false;
        } else if ($strSearch == 'BLOCK_AIP_') {
            $st = ($isCurrentSession) ? true : false;
        } else {
            $st = ($status) ? true : false;
        }
    }
    return '<div id="req_' . $ctrl . '" style="float:left;display:' . (($st) ? 'none' : '') . '">' . getRequiredSign('left') . '</div>';
}

?>
<form name="frmProject" id="frmProject" onsubmit="return false;" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="PROJECT_SETUP_ID" id="PROJECT_SETUP_ID" value="<?php echo $projectSetupId; ?>">
    <input type="hidden" name="PROJECT_ID" id="PARENT_PROJECT_ID" value="<?php echo $arrProjectData['PROJECT_ID']; ?>">
    <input type="hidden" id="PROJECT_TYPE_ID" name="PROJECT_TYPE_ID"
           value="<?php echo $arrProjectData['PROJECT_TYPE_ID']; ?>"/>
    <input type="hidden" id="PROJECT_SUB_TYPE_ID" name="PROJECT_SUB_TYPE_ID"
           value="<?php echo $arrProjectData['PROJECT_SUB_TYPE_ID']; ?>"/>
    <input type="hidden" name="saveMode" id="saveMode" value="<?php echo ($projectSetupId) ? 2 : 0; ?>">
    <input type="hidden" name="mi_pmon_type" id="mi_pmon_type" value="1">
    <table width="100%" border="0" cellpadding="3" class="ui-widget-content" cellspacing="1" style="margin-bottom:9px">
        <tr>
            <td colspan="4" align="center" class="ui-widget-content">
                <?php echo getRequiredSign(''); ?> = Mandatory Field(जानकारी जरूरी है)<br/>
                <span class="cus-tag-pink"></span> Site = Starting Point of the Work
            </td>
        </tr>
        <tr>
            <td align="center" class="ui-widget-header" colspan="4">
                <strong><?php echo $arrProjectData['PROJECT_NAME'] . ' <br /> ' . $arrProjectData['PROJECT_NAME_HINDI']; ?></strong>
            </td>
        </tr>
        <tr>
            <td width="100" align="left" nowrap="nowrap" class="ui-state-default"><strong>Project Code</strong></td>
            <td align="left" nowrap="nowrap" class="ui-widget-content">
                <strong><?php echo $arrProjectData['PROJECT_CODE']; ?></strong></td>
            <td class="ui-state-default"><strong>Project Category</strong></td>
            <td class="ui-widget-content"><strong> Minor - <?php echo $arrProjectData['PROJECT_SUB_TYPE_NAME']?></strong></td>
        </tr>
        <tr>
            <td width="120" align="left" nowrap="nowrap" class="ui-state-default"><strong>Micro Irrigation Project
                    Code</strong></td>
            <td colspan="3" align="left" class="ui-widget-content">
                <?php echo ($projectSetupValues['PROJECT_CODE'] == '') ? 'Automatically Generated' : $projectSetupValues['PROJECT_CODE'];
                ?></td>
        </tr>
        <tr>
            <td align="left" class="ui-state-default"><strong>Work Name</strong></td>
            <td colspan="3" align="left" class="ui-widget-content"><?php echo getRequiredSign(''); ?>
                <input name="PROJECT_NAME" placeholder="Work Name" id="WORK_NAME" type="text" maxlength="255"
                       style="width:95%"
                       value="<?php echo $projectSetupValues['PROJECT_NAME']; ?>" class="" onpaste="return false"/></td>
        </tr>
        <tr>
            <td align="left" class="ui-state-default"><strong>कार्य का नाम</strong></td>
            <td colspan="3" align="left" class="ui-widget-content"><?php echo getRequiredSign(''); ?>
                <input name="PROJECT_NAME_HINDI" id="WORK_NAME_HINDI" placeholder="कार्य का नाम" type="text"
                       style="width:95%" maxlength="255"
                       value="<?php echo $projectSetupValues['PROJECT_NAME_HINDI']; ?>" class=""
                       onpaste="allowHindi(event, this)"/></td>
        </tr>
    </table>
    <?php if (!$editMode) { ?>
        <table border="0" cellpadding="2" cellspacing="1" class="ui-widget-content" width="100%">
            <tr>
                <td width="20" rowspan="2" align="center" class="ui-state-default">
                    <strong><?php echo getRequiredSign(''); ?></strong>District (Site)
                </td>
                <td colspan="3" align="center" class="ui-state-default"><strong><?php echo getRequiredSign(''); ?>
                        Longitude (Site)</strong></td>
                <td colspan="3" align="center" class="ui-state-default"><strong><?php echo getRequiredSign(''); ?>
                        Latitude (Site)</strong></td>
            </tr>
            <tr>
                <td width="20" align="center" class="ui-state-default">D</td>
                <td width="20" align="center" class="ui-state-default">M</td>
                <td width="20" align="center" class="ui-state-default">S</td>
                <td width="20" align="center" class="ui-state-default">D</td>
                <td width="20" align="center" class="ui-state-default">M</td>
                <td width="20" align="center" class="ui-state-default">S

                </td>
            </tr>
            <tr>
                <td align="center" class="ui-widget-content" style="width:280px">
                    <select class="chosen-select" style="width:280px" name="DISTRICT_ID" id="DISTRICT_ID">
                        <?php echo $DISTRICT_START; ?>
                    </select>
                </td>
                <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LONGITUDE_D"
                                                                                 id="LONGITUDE_D"
                                                                                 size="3" maxlength="2"
                                                                                 class="positive-integerLong centertext onlynumber"
                                                                                 readonly="readonly"
                                                                                 style="background-color:#ECF9FF"
                                                                                 value="<?php echo $projectSetupValues['LONGITUDE_D']; ?>"
                    /></td>
                <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LONGITUDE_M"
                                                                                 id="LONGITUDE_M"
                                                                                 size="3" maxlength="2"
                                                                                 class="positive-integerM centertext onlynumber"
                                                                                 value="<?php echo $projectSetupValues['LONGITUDE_M']; ?>"
                    /></td>
                <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LONGITUDE_S"
                                                                                 id="LONGITUDE_S"
                                                                                 size="3" maxlength="2"
                                                                                 class="positive-integerM centertext onlynumber"
                                                                                 value="<?php echo $projectSetupValues['LONGITUDE_S']; ?>"
                    /></td>
                <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LATITUDE_D"
                                                                                 id="LATITUDE_D"
                                                                                 size="3" maxlength="2"
                                                                                 class="positive-integerLat centertext onlynumber"
                                                                                 readonly="readonly"
                                                                                 value="<?php echo $projectSetupValues['LATITUDE_D']; ?>"
                                                                                 style="background-color:#ECF9FF"
                    /></td>
                <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LATITUDE_M"
                                                                                 id="LATITUDE_M"
                                                                                 size="3" maxlength="2"
                                                                                 class="positive-integerM centertext onlynumber"
                                                                                 value="<?php echo $projectSetupValues['LATITUDE_M']; ?>"
                    /></td>
                <td align="center" valign="top" class="ui-widget-content"><input type="text" name="LATITUDE_S"
                                                                                 id="LATITUDE_S"
                                                                                 size="3" maxlength="2"
                                                                                 class="positive-integerM centertext onlynumber"
                                                                                 value="<?php echo $projectSetupValues['LATITUDE_S']; ?>"
                    /></td>
            </tr>
            <tr>
                <td colspan="7" align="right"><?php echo getButton('View on Map', 'viewOnMap()', 4, 'cus-eye'); ?></td>
            </tr>
        </table>
        <div id="divCheckCode" class="ui-state-error" style="text-align:center;font-size:18px;display:none;"></div>
    <?php } ?>
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
            <td width="50%" valign="top">
                <table width="100%" cellpadding="3" cellspacing="1" class="ui-widget-content">
                    <tr>
                        <td colspan="2" class="ui-state-default" align="center"><strong>Administrative Approval</strong>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>AA
                                No : </br><span style="color:red;">(Only numeric.)</span></strong></td>
                        <td class="ui-widget-content">
                            <input name="AA_NO" placeholder="AA No" id="AA_NO" type="text" maxlength="5"
                                   class="centertext" style="width:180px;"
                                   value="<?php echo $projectSetupValues['AA_NO']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>Date
                                : </strong></td>
                        <td class="ui-widget-content">
                            <input type="hidden" id="AA_SESSION_ID"
                                   value="<?php echo $projectSetupValues['AA_SESSION_ID']; ?>">
                            <input name="AA_DATE" id="AA_DATE" type="text" maxlength="10"
                                   onchange="checkForStatus();" style="text-align:center;width:180px;"
                                   value="<?php echo myDateFormat($projectSetupValues['AA_DATE']); ?>"/>
                            <br/>
                            (dd-mm-yyyy) e.g. 02-12-2013 for 2<sup>nd</sup> Dec 2013
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>Authority
                                :</strong></td>
                        <td class="ui-widget-content">
                            <select name="AA_AUTHORITY_ID" id="AA_AUTHORITY_ID" style="width:180px;"
                                    class=" chosen-select required" onchange="showHideOtherAuth('AA_AUTHORITY_ID')">
                                <option value="">Select Authority</option>
                                <?php echo implode('', $AUTH_VALUES); ?>
                            </select>
                        </td>
                    </tr>
                    <tr id="TR_AA_AUTHORITY_ID">
                        <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>Other
                                Authority :</strong></td>
                        <td class="ui-widget-content">
                            <input type="text" name="OTHER_AA_AUTHORITY" ID="OTHER_AA_AUTHORITY"
                                   value="<?php echo $projectSetupValues['OTHER_AA_AUTHORITY']; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>Amount
                                :</strong></td>
                        <td class="ui-widget-content">
                            <input name="AA_AMOUNT" id="AA_AMOUNT" type="text" style="width:90px;"
                                   maxlength="20" value="<?php echo $projectSetupValues['AA_AMOUNT']; ?>"
                                   class="righttext"/>
                            (Rs. In Lacs)
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>Scanned
                                Copy :</strong></td>
                        <td class="ui-widget-content">
                            <div id="msg_aa_file"></div>
                            <?php
                            $filePath = FCPATH . 'dep_pmon' . DIRECTORY_SEPARATOR . $projectSetupValues['AA_FILE_URL'];
                            if ($projectSetupValues['AA_USER_FILE_NAME'] != '') {
                                if (file_exists($filePath)) { ?>
                                    <div id="aa_button_div">
                                        <a href="javascript:void(0)" onclick="viewPDF(1,this)"
                                           id="<?php echo base_url() . 'dep_pmon/' . $projectSetupValues['AA_FILE_URL'] ?>"
                                           class="fm-button ui-state-default ui-corner-all"
                                        ><span class=cus-eye></span> View </a>
                                        <a class=" fm-button ui-state-default ui-corner-all" href="javascript:void(0)"
                                           onclick="removeFile('1','<?php echo $projectSetupId; ?>')"><i
                                                    class="cus-cross"></i>Delete</a>

                                        <?php echo $projectSetupValues['AA_USER_FILE_NAME']; ?>
                                    </div>
                                    <div id="aa_upload_div" style="display: none;">
                                        <input type="file"
                                               onchange="showSize('AA_SCAN_COPY');checkAaRaafileExists('1','<?php echo $projectSetupId; ?>');"
                                               id="AA_SCAN_COPY" name="AA_SCAN_COPY"/>
                                        <span style="color:#f00">(.pdf)</span>
                                    </div>
                                    <?php
                                }
                            } else { ?>
                                <input type="file"
                                       onchange="showSize('AA_SCAN_COPY');checkAaRaafileExists('1','<?php echo $projectSetupId; ?>');"
                                       id="AA_SCAN_COPY" name="AA_SCAN_COPY"/>
                                <span style="color:#f00">(.pdf)</span>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
            <input type="hidden" id="BLOCK_FORM" value="<?php //echo $BLOCK_FORM3;?>"/>
            <td width="50%" valign="top">
                <?php //showArrayValues($arrRAAData);?>
                <table width="100%" cellpadding="3" cellspacing="1" class="ui-widget-content" <?php if ($arrRAAData['RAA_NO'] == '') echo 'style="display:none"'; ?>>
                    <tr>
                        <td colspan="2" class="ui-state-default" align="center" style="line-height:20px;">
                            <input style="display:none" type="checkbox" id="isRAA" name="isRAA" value="1" class="css-checkbox"
                                   onclick="showHideRAA(this.checked)" <?php if ($arrRAAData['RAA_NO'] != '') echo 'checked="checked"'; ?> />
                            <label for="isRAA" class="css-label lite-green-check"><strong>Latest RAA</strong></label>
                        </td>
                    </tr>
                    <tr class="raa" <?php if ($arrRAAData['RAA_NO'] == '') echo 'style="display:none"'; ?>>
                        <td nowrap="nowrap" class="ui-state-default"><strong>RAA No : </br><span style="color:red;">(Only numeric.)</span></strong></td>
                        <td class="ui-widget-content">
                            <input type="hidden" name="RAA_PROJECT_ID" id="RAA_PROJECT_ID"
                                   value="<?php echo $arrRAAData['RAA_PROJECT_ID']; ?>"/>
                            <input placeholder="RAA No" name="RAA_NO" id="RAA_NO" type="text"
                                   style="width:180px;text-align:center" maxlength="5"
                                   value="<?php echo $arrRAAData['RAA_NO']; ?>"/>
                        </td>
                    </tr>
                    <tr class="raa" <?php if ($arrRAAData['RAA_NO'] == '') echo 'style="display:none"'; ?>>
                        <td nowrap="nowrap" class="ui-state-default"><strong>Date : </strong></td>
                        <td class="ui-widget-content">
                            <input name="RAA_DATE" type="text" id="RAA_DATE" style="width:180px;text-align:center"
                                   maxlength="10"
                                   value="<?php echo myDateFormat($arrRAAData['RAA_DATE']); ?>"/>
                            <br/>
                            (dd-mm-yyyy) e.g. 02-12-2013 for 2<sup>nd</sup> Dec 2013
                        </td>
                    </tr>
                    <tr class="raa" <?php if ($arrRAAData['RAA_NO'] == '') echo 'style="display:none"'; ?>>
                        <td nowrap="nowrap" class="ui-state-default"><strong>Authority : </strong></td>
                        <td class="ui-widget-content">
                            <select name="RAA_AUTHORITY_ID" id="RAA_AUTHORITY_ID" style="width:180px;"
                                    class="chosen-select" onchange="showHideOtherAuth('RAA_AUTHORITY_ID')">
                                <option value="">Select Authority</option>
                                <?php echo implode('', $arrRAAData['RAA_AUTHORITY_OPTIONS']); ?>
                            </select>
                        </td>
                    </tr>
                    <tr id="TR_RAA_AUTHORITY_ID">
                        <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>Other
                                Authority :Authority :</strong></td>
                        <td class="ui-widget-content">
                            <input type="text" name="OTHER_RAA_AUTHORITY" ID="OTHER_RAA_AUTHORITY"
                                   value="<?php echo $arrRAAData['OTHER_RAA_AUTHORITY']; ?>">
                        </td>
                    </tr>
                    <tr class="raa" <?php if ($arrRAAData['RAA_NO'] == '') echo 'style="display:none"'; ?>>
                        <td nowrap="nowrap" class="ui-state-default"><strong>Amount :</strong></td>
                        <td class="ui-widget-content">
                            <input name="RAA_AMOUNT" id="RAA_AMOUNT" type="text" style="width:90px;"
                                   maxlength="20" value="<?php echo $arrRAAData['RAA_AMOUNT']; ?>"
                                   class="validate[custom[number]] righttext"/>
                            (Rs. In Lacs)
                        </td>
                    </tr>
                    <tr class="raa" <?php if ($arrRAAData['RAA_NO'] == '') echo 'style="display:none"'; ?>>
                        <td nowrap="nowrap" class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>Scanned
                                Copy :</strong></td>
                        <td class="ui-widget-content">
                            <div id="msg_raa_file"></div>
                            <?php
                            $filePath = FCPATH . 'dep_pmon' . DIRECTORY_SEPARATOR . $arrRAAData['RAA_FILE_URL'];
                            if ($arrRAAData['RAA_USER_FILE_NAME'] != '') {
                                if (file_exists($filePath)) { ?>
                                    <div id="raa_button_div">
                                        <a class="fm-button ui-state-default ui-corner-all" onclick="viewPDF(2,this)"
                                           id="<?php echo base_url() . 'dep_pmon/' . $arrRAAData['RAA_FILE_URL'] ?>"
                                        > <span
                                                    class=cus-eye></span> View </a>
                                        <a class=" fm-button ui-state-default ui-corner-all" href="javascript:void(0);"
                                           onclick="removeFile('2','<?php echo $projectSetupId; ?>')"><i
                                                    class="cus-cross"></i>Delete</a>
                                    </div>
                                    <div id="raa_upload_div" style="display: none;">
                                        <input type="file"
                                               onchange="showSize('RAA_SCAN_COPY');checkAaRaafileExists('2','<?php echo $projectSetupId; ?>');"
                                               id="RAA_SCAN_COPY" name="RAA_SCAN_COPY"/>
                                        <span style="color:#f00">(.pdf)</span>
                                    </div>
                                    <?php echo $arrRAAData['RAA_USER_FILE_NAME']; ?>
                                    <?php
                                }
                            } else { ?>
                                <input type="file"
                                       onchange="showSize('RAA_SCAN_COPY');checkAaRaafileExists('2','<?php echo $projectSetupId; ?>');"
                                       id="RAA_SCAN_COPY" name="RAA_SCAN_COPY"/>
                                <span style="color:#f00">(.pdf)</span>
                                <?php
                            } ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="div_pdf_button_close" style="display: none;text-align: right">
                    <?php echo getButton('Close', 'closePDF()', 4, 'cus-cross'); ?>
                </div>
                <div id="div_pdf" style="display: none" class="ui-widget-content"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="ui-state-default" align="left" style="line-height:20px;"><strong>Office</strong></td>
        </tr>
        <tr>
            <td colspan="2" class="ui-widget-content" align="left" style="line-height:20px;">
                <table width="100%" border="0" cellpadding="2" cellspacing="1">
                    <tr>
                        <td class="ui-state-default"><strong>Executive Engineer : </strong></td>
                        <td class="ui-widget-content">
                            <?php if ($holdingPerson == 3) { ?>
                                <select id="OFFICE_EE_ID" name="OFFICE_EE_ID"
                                        data-placeholder="Select Division - संभाग चुने"
                                        style="min-width:25%;width:600px" class="chosen-select"
                                        onchange="getSDOOffices(this.value)">
                                    <?php echo $ee_options; ?>
                                </select>
                            <?php } else if ($holdingPerson == 4) { ?>
                                <input type="hidden" name="OFFICE_EE_ID" value="<?php echo $eeId; ?>"/>
                                <strong><?php echo $EE_NAME; ?></strong>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="ui-state-default"><strong>Sub-Division : </strong></td>
                        <td class="ui-widget-content">
                            <select id="OFFICE_SDO_ID" name="OFFICE_SDO_ID[]" multiple="multiple"
                                    data-placeholder="Select Sub-Division - उप-संभाग चुने"
                                    style="min-width:25%;width:600px" class="chosen-select required">
                                <?php echo $sdo_options; ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br/>
    <div id="div_button_close" style="display: none;text-align: right">
        <?php echo getButton('Close Map', 'closeMap()', 4, 'cus-cross'); ?>
    </div>
    <div id="div_map" style="display: none" class="ui-widget-content"></div>
    <?php if ($editMode) { ?>
        <br/>
        <!-- [ Form - I ] -->
        <fieldset>
            <legend>
                <a role="button" class="ui-button ui-state-default ui-corner-all ui-button-text-only " onclick="$('#frm1').slideToggle('slow'); $('#sp_arrow_frm1_down').toggle();
    $('#sp_arrow_frm1_up').toggle(); " aria-disabled="false">

    <span class="ui-button-text"><i class="cus-table"></i>
        &nbsp; Site Details Form &nbsp;
        <span id="sp_arrow_frm1_down" style=""><i class="cus-arrow-down"></i></span>
        <span id="sp_arrow_frm1_up" style="display:none;"><i class="cus-arrow-up"></i></span>
    </span>
                </a>
            </legend>
            <!--frm1-->
            <div id="frm1" style="display:none;">
                <table width="100%" cellpadding="3" cellspacing="2" class="ui-widget-content">
                    <tr>
                        <td valign="top" nowrap="nowrap"
                            class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>Longitude of Site
                                :</strong></td>
                        <td valign="middle" class="ui-widget-content">
                            <strong><?php echo $projectSetupValues['LONGITUDE_D']; ?>&deg; &nbsp;
                                <?php echo $projectSetupValues['LONGITUDE_M']; ?>' &nbsp;
                                <?php echo $projectSetupValues['LONGITUDE_S']; ?>"</strong>
                        </td>
                        <td valign="top" class="ui-state-default"><?php echo getRequiredSign('right'); ?> <strong>Latitude
                                of Site :</strong></td>
                        <td valign="middle" class="ui-widget-content">
                            <strong><?php echo $projectSetupValues['LATITUDE_D']; ?>&deg; &nbsp;
                                <?php echo $projectSetupValues['LATITUDE_M']; ?>' &nbsp;
                                <?php echo $projectSetupValues['LATITUDE_S']; ?>"</strong>
                            <div style="float: right">
                                <?php echo getButton('View On Map', 'viewOnMap()', 4, 'cus-eye'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap="nowrap"
                            class="ui-state-default"><?php echo getRequiredSign('right'); ?><strong>District (Site)
                                :</strong></td>
                        <td valign="top" class="ui-widget-content">
                            <input type="hidden" name="HEAD_WORK_DISTRICT_ID" id="DISTRICT_ID"
                                   value="<?php echo $projectSetupValues['DISTRICT_ID']; ?>"/>
                            <strong><?php echo $projectSetupValues['DISTRICT_NAME']; ?></strong>
                        </td>
                        <td valign="top" class="ui-state-default"><?php echo getRequiredSign('right'); ?> <strong>Block
                                (Site) :</strong></td>
                        <td valign="top" class="ui-widget-content">

                            <select name="HEAD_WORK_BLOCK_ID" id="BLOCK_ID" class="chosen-select required" style="width:200px;">
                                <?php echo $BLOCK_START; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap="nowrap"
                            class="ui-state-default"><?php echo getRequiredSign('right'); ?> <strong>Assembly
                                Constituency<br/>
                                (Site) :</strong></td>
                        <td valign="top" class="ui-widget-content">
                            <select name="ASSEMBLY_ID" id="ASSEMBLY_ID"
                                    class="chosen-select required"
                                    style="width:200px;">
                                <option value="">Assembly Constituency</option>
                                <?php echo $ASSEMBLY_CONST; ?>
                            </select>
                        </td>
                        <td valign="top" class="ui-state-default"><?php echo getRequiredSign('right'); ?> <strong>Tehsil
                                (Site) :</strong></td>
                        <td valign="top" class="ui-widget-content">

                            <select name="HEAD_WORK_TEHSIL_ID" id="TEHSIL_ID" class="chosen-select required"
                                    style="width:200px;">
                                <?php echo $TEHSIL_START; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap="nowrap"
                            class="ui-state-default"><?php echo getRequiredSign('right'); ?> <strong>Nalla / River
                                :</strong></td>
                        <td class="ui-widget-content" valign="top" colspan="3">
                            <input type="text" class="required" name="NALLA_RIVER" id="NALLA_RIVER" style="width:98%"
                                   value="<?php echo $projectSetupValues['NALLA_RIVER']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="ui-widget-header"><strong>Benefitted:</strong></td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap="nowrap" class="ui-state-default"><strong>District Benefitted
                                :</strong><?php echo getRequiredSign('right'); ?> </td>
                        <td class="ui-widget-content" valign="top">
                            <select name="DISTRICT_BENEFITED[]" id="DISTRICT_BENEFITED" style="width:98%;"
                                    multiple="multiple" class="chosen-select"
                                    data-placeholder="Select Benefitted Districts">
                                <?php echo $DISTRICT_BENEFITED; ?>
                            </select>
                        </td>
                        <td class="ui-state-default" valign="top"><strong>Benefitted Blocks
                                :</strong><?php echo getRequiredSign('right'); ?> </td>
                        <td class="ui-widget-content" valign="top">
                            <select name="BLOCKS_BENEFITED[]" id="BLOCKS_BENEFITED"
                                    style="width:98%;" multiple="multiple"
                                    class="chosen-select"
                                    data-placeholder="Select Benefitted Blocks">
                                <?php echo $BLOCKS_BENEFITED; ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap="nowrap" class="ui-state-default"><strong>Benefitted Assembly <br/>Constituency
                                :</strong></td>
                        <td valign="top" class="ui-widget-content" colspan="3">
                            <select name="ASSEMBLY_BENEFITED[]" id="ASSEMBLY_BENEFITED" style="width:98%;"
                                    multiple="multiple" class="chosen-select"
                                    data-placeholder="Select Benefitted Assembly Constituency">
                                <?php echo $ASSEMBLY_BENEFITED; ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td class="ui-state-default" colspan="2">
                            <div style="float:left">
                                <strong>No of villages covered : </strong><?php echo getRequiredSign(''); ?>
                                <input type="text" name="NO_VILLAGES_BENEFITED" id="NO_VILLAGES_BENEFITED"
                                       size="4" maxlength="3" class="centertext"
                                       value="<?php echo $projectSetupValues['NO_VILLAGES_BENEFITED']; ?>"
                                />
                            </div>
                            <div style="float:right">Village, Tehsil, District</div>
                        </td>
                        <td class="ui-state-default" colspan="2">
                            <div id="villageCount" style="float:right"></div>
                            <div style="float:right">Total Selected :</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="ui-widget-content">
                            <select name="VILLAGES_BENEFITED[]" id="VILLAGES_BENEFITED"
                                    multiple="multiple" data-placeholder="Select Village"
                                    style="width:99%;" class="chosen-select">
                                <?php echo $VILLAGES_BENEFITED; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <!--//frm1-->
            </div>
        </fieldset>
        <br/>
        <!-- [ Form - II ] -->
        <?php
            //showArrayValues($arrProjectData);
            //Conditional Load data view for MI/Tubewell
            if($arrProjectData['PROJECT_SUB_TYPE_ID']==5){
                include_once('projects_tubewell_data_view.php');
            }elseif($arrProjectData['PROJECT_SUB_TYPE_ID']==25){
                include_once('projects_mi_data_view.php');
            }
            //echo $myviewestimation;
        ?>
        <div class="wrdlinebreak"></div>
            <!--<div align="center" class="ui-state-default" style="padding:2px;margin:2px">
                <strong>Data Submission Date :</strong>
                <input type="text" id="PROJECT_SAVE_DATE" name="PROJECT_SAVE_DATE"
                    value="<?php echo myDateFormat($projectSetupValues['PROJECT_SAVE_DATE']); ?>"
                    class="centertext" />
                </div>
            -->
    <?php }//editMode ?>
    <div id="divCheckCode1" class="ui-widget-header" style="text-align:center;font-size:18px;"></div>
    <div id="divtest" class="ui-widget-header" style="text-align:center;font-size:18px;"></div>
    <div id="mySaveDiv" align="right" class="mysavebar"><?php echo $buttons; ?></div>
</form>
<?php
if($arrProjectData['PROJECT_SUB_TYPE_ID']==5){
    include_once('projects_tubewell_js.php');
}elseif($arrProjectData['PROJECT_SUB_TYPE_ID']==25){
    include_once('projects_mi_js.php');
}
?>
<script>
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
