<?
$moduleClass = "CElastoStart";
$moduleID = "altop.elastostart";
global $APPLICATION;
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$RIGHT = $APPLICATION->GetGroupRight($moduleID);

if($RIGHT >= "R") {
	$by = "id";
	$sort = "asc";
	
	$arSites = array();
	$db_res = CSite::GetList($by , $sort ,array("ACTIVE"=>"Y"));
	while($res = $db_res->Fetch()){
		$arSites[] = $res;
	}

	$arTabs = array();
	foreach($arSites as $key => $arSite){
		$arBackParametrs = $moduleClass::GetParametrsValues($arSite["ID"], false);
		$arTabs[] = array(
			"DIV" => "edit".($key+1), 
			"TAB" => GetMessage("MAIN_OPTIONS_SITE_TITLE", array("#SITE_NAME#" => $arSite["NAME"], "#SITE_ID#" => $arSite["ID"])), 
			"ICON" => "settings", 
			"TITLE" => GetMessage("MAIN_OPTIONS_TITLE"),
			"PAGE_TYPE" => "site_settings",
			"SITE_ID" => $arSite["ID"],
			"OPTIONS" => $arBackParametrs,
		);	
	}
	
	$tabControl = new CAdminTabControl("tabControl", $arTabs);

	if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) && $RIGHT >= "W" && check_bitrix_sessid()) {
		global $APPLICATION;
		if(strlen($RestoreDefaults)) {
			COption::RemoveOption($moduleID, "OPTION");			
			$APPLICATION->DelGroupRight($moduleID);
		} else {
			COption::RemoveOption($moduleID, "sid");	
			foreach($arTabs as $key => $arTab){
				$optionsSiteID = $arTab["SITE_ID"];
				$arBackParametrs = $moduleClass::GetParametrsValues($optionsSiteID, false);
				foreach($moduleClass::$arParametrsList as $blockCode => $arBlock) {
					foreach($arBlock["OPTIONS"] as $optionCode => $arOption) {						
						if($optionCode == "COLOR_SCHEME_CUSTOM") {							
							$_REQUEST[$optionCode."_".$optionsSiteID] = $moduleClass::CheckColor($_REQUEST[$optionCode."_".$optionsSiteID]);
						}
						if($arOption["TYPE"] == "file") {							
							$arPICTURE = $_FILES[$optionCode."_".$optionsSiteID];							
							$arPICTURE["del"] = ${$optionCode."_".$optionsSiteID."_del"};
							$arPICTURE["MODULE_ID"] = "elastostart";							
							if($arPICTURE["size"] > 0) {
								$_REQUEST[$optionCode."_".$optionsSiteID] = CFile::SaveFile($arPICTURE, "elastostart");
							} elseif($arPICTURE["del"] == "Y") {
								if($arBackParametrs[$optionCode] != COption::GetOptionString("elastostart", $optionCode)) {
									CFile::Delete($arBackParametrs[$optionCode]);
								}
								$_REQUEST[$optionCode."_".$optionsSiteID] = "";							
							} else {
								$_REQUEST[$optionCode."_".$optionsSiteID] = $arBackParametrs[$optionCode];
							}
						}
						$newVal = $_REQUEST[$optionCode."_".$optionsSiteID];
						if($arOption["TYPE"] == "checkbox") {
							if(!strlen($newVal) || $newVal != "Y") {
								$newVal = "N";
							}
						} elseif($arOption["TYPE"] == "multiselectbox") {
							if(!is_array($newVal))
								$newVal = array();
						}
						$arTab["OPTIONS"][$optionCode] = $newVal;
					}
				}
				COption::SetOptionString($moduleID, "OPTIONS", serialize((array)$arTab["OPTIONS"]), "", $arTab["SITE_ID"]);
				$arTabs[$key] = $arTab;
			}
		}
		
		if($moduleClass::IsCompositeEnabled()){
			$obCache = new CPHPCache();
			$obCache->CleanDir("", "html_pages");
			$moduleClass::EnableComposite();
		}
		
		$APPLICATION->RestartBuffer();
	}	
	
	CModule::IncludeModule("fileman");
	CJSCore::Init(array("jquery"));
	CAjax::Init();	
	$tabControl->Begin();?>
	<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>" enctype="multipart/form-data">
		<?=bitrix_sessid_post();
		foreach($arTabs as $key => $arTab) {
			$tabControl->BeginNextTab();
			if($arTab["SITE_ID"]){
				$optionsSiteID = $arTab["SITE_ID"];
				foreach($moduleClass::$arParametrsList as $blockCode => $arBlock) {?>
					<tr class="heading">
						<td colspan="2"><?=$arBlock["TITLE"]?></td>
					</tr>
					<?foreach($arBlock["OPTIONS"] as $optionCode => $arOption) {
						if(isset($arTab["OPTIONS"][$optionCode])) {
							$arControllerOption = CControllerClient::GetInstalledOptions($module_id);
							if(isset($arOption["NOTE"])) {?>
								<tr>
									<td colspan="2" align="center">
										<?=BeginNote('align="center"');?>
										<?=$arOption["NOTE"]?>
										<?=EndNote();?>
									</td>
								</tr>
							<?} else {
								$optionName = $arOption["TITLE"];
								$optionType = $arOption["TYPE"];
								$optionList = $arOption["LIST"];
								$optionDefault = $arOption["DEFAULT"];
								$optionVal = $arTab["OPTIONS"][$optionCode];
								$optionSize = $arOption["SIZE"];
								$optionCols = $arOption["COLS"];
								$optionRows = $arOption["ROWS"];
								$optionChecked = $optionVal == "Y" ? "checked" : "";
								$optionDisabled = isset($arControllerOption[$optionCode]) || array_key_exists("DISABLED", $arOption) && $arOption["DISABLED"] == "Y" ? "disabled" : "";
								$optionSup_text = array_key_exists("SUP", $arOption) ? $arOption["SUP"] : "";
								$optionController = isset($arControllerOption[$optionCode]) ? "title='".GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT")."'" : "";?>
								<tr>
									<td class="<?=(in_array($optionType, array("multiselectbox", "textarea", "statictext", "statichtml")) ? "adm-detail-valign-top" : "")?>" width="50%">
										<?if($optionType == "checkbox"):?>
											<label for="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=$optionName?></label>
										<?else:?>
											<?=$optionName?>
										<?endif;?>
										<?if(strlen($optionSup_text)):?>
											<span class="required"><sup><?=$optionSup_text?></sup></span>
										<?endif;?>
									</td>
									<td width="50%">										
										<?if($optionType == "checkbox"):?>
											<input type="checkbox" <?=$optionController?> id="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" value="Y" <?=$optionChecked?> <?=$optionDisabled?> <?=(strlen($optionDefault) ? $optionDefault : "")?>>
										<?elseif($optionType == "text" || $optionType == "password"):?>
											<input type="<?=$optionType?>" <?=$optionController?> size="<?=$optionSize?>" maxlength="255" value="<?=htmlspecialcharsbx($optionVal)?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" <?=$optionDisabled?> <?=($optionCode == "password" ? "autocomplete='off'" : "")?>>
										<?elseif($optionType == "selectbox"):											
											if(!is_array($optionList)) $optionList = (array)$optionList;
											$arr_keys = array_keys($optionList);?>
											<select name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" <?=$optionController?> <?=$optionDisabled?>>
												<?for($j = 0, $c = count($arr_keys); $j < $c; ++$j):?>
													<option value="<?=$arr_keys[$j]?>" <?if($optionVal == $arr_keys[$j]) echo "selected"?>><?=htmlspecialcharsbx((is_array($optionList[$arr_keys[$j]]) ? $optionList[$arr_keys[$j]]["TITLE"] : $optionList[$arr_keys[$j]]))?></option>
												<?endfor;?>
											</select>
										<?elseif($optionType == "multiselectbox"):											
											if(!is_array($optionList)) $optionList = (array)$optionList;
											$arr_keys = array_keys($optionList);
											if(!is_array($optionVal)) $optionVal = (array)$optionVal;?>
											<select size="<?=$optionSize?>" <?=$optionController?> <?=$optionDisabled?> multiple name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>[]" >
												<?for($j = 0, $c = count($arr_keys); $j < $c; ++$j):?>
													<option value="<?=$arr_keys[$j]?>" <?if(in_array($arr_keys[$j], $optionVal)) echo "selected"?>><?=htmlspecialcharsbx((is_array($optionList[$arr_keys[$j]]) ? $optionList[$arr_keys[$j]]["TITLE"] : $optionList[$arr_keys[$j]]))?></option>
												<?endfor;?>
											</select>
										<?elseif($optionType == "textarea"):?>
											<textarea <?=$optionController?> <?=$optionDisabled?> rows="<?=$optionRows?>" cols="<?=$optionCols?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=htmlspecialcharsbx($optionVal)?></textarea>
										<?elseif($optionType == "file"):
											echo CFileInput::Show(htmlspecialcharsbx($optionCode)."_".$optionsSiteID, htmlspecialcharsbx($optionVal),
												array(
													"IMAGE" => "Y",
													"PATH" => "Y",
													"FILE_SIZE" => "Y",
													"DIMENSIONS" => "Y",
													"IMAGE_POPUP" => "Y",
													"MAX_SIZE" => array(
														"W" => COption::GetOptionString("iblock", "detail_image_size"),
														"H" => COption::GetOptionString("iblock", "detail_image_size"),
													),
												), array(
													'upload' => true,
													'medialib' => true,
													'file_dialog' => true,
													'cloud' => true,
													'del' => true,
													'description' => false,
												)
											);
										elseif($optionType == "date"):											
											echo CAdminCalendar::CalendarDate(htmlspecialcharsbx($optionCode)."_".$optionsSiteID, htmlspecialcharsbx($optionVal), 19, true);
										elseif($optionType == "statictext"):
											echo htmlspecialcharsbx($optionVal);
										elseif($optionType == "statichtml"):
											echo $optionVal;
										endif;?>
									</td>
								</tr>
							<?}
						}
					}
				}
			}
		}
		
		if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) && check_bitrix_sessid()) {
			if(strlen($Update) && strlen($_REQUEST["back_url_settings"]))
				LocalRedirect($_REQUEST["back_url_settings"]);
			else
				LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());	
		}
		$tabControl->Buttons();?>
		
		<input <?if($RIGHT < "W") echo "disabled"?> type="submit" name="Apply" class="submit-btn" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
		<?if(strlen($_REQUEST["back_url_settings"])):?>
			<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?=htmlspecialchars(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
			<input type="hidden" name="back_url_settings" value="<?=htmlspecialchars($_REQUEST["back_url_settings"])?>">
		<?endif;
		
		if($moduleClass::IsCompositeEnabled()):?>
			<div class="adm-info-message"><?=GetMessage("WILL_CLEAR_HTML_CACHE_NOTE")?></div>
			<div style="clear:both;"></div>
			<script type="text/javascript">
				$(document).ready(function() {
					$("input[name^='SHOW_SETTINGS_PANEL']").change(function() {
						var ischecked = $(this).attr("checked");
						if(typeof(ischecked) != "undefined") {
							if(!confirm("<?=GetMessage('NO_COMPOSITE_NOTE')?>")) {
								$(this).removeAttr("checked");
							}
						}
					});
				});
			</script>
		<?endif;?>
	</form>
	<?$tabControl->End();
} else {
	CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));
}?>