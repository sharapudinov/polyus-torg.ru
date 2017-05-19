<?if(!defined("ELASTOSTART_MODULE_ID")) define("ELASTOSTART_MODULE_ID", "altop.elastostart");
 
IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\Type\Collection;

//initialize module parametrs list and default values
include_once __DIR__."/../../parametrs.php";

class CElastoStart{
	const MODULE_ID = ELASTOSTART_MODULE_ID;
	const PARTNER_NAME = "altop"; 
	const SOLUTION_NAME	= "elastostart";
	
	static $arParametrsList = array();
	private static $arMetaParams = array();
	
	public function checkModuleRight($reqRight = "R", $bShowError = false) {
		global $APPLICATION;
		
		if($APPLICATION->GetGroupRight(self::MODULE_ID) < $reqRight) {
			if($bShowError){
				$APPLICATION->AuthForm(GetMessage("ELASTOSTART_ACCESS_DENIED"));
			}
			return false;
		}
		
		return true;
	}
	
	function GetParametrsValues($SITE_ID, $bStatic = true) {
		if($bStatic){
			static $arValues;
		}
		if($bStatic && $arValues == NULL || !$bStatic) {
			$arDefaultValues = $arValues = array();
			if(self::$arParametrsList && is_array(self::$arParametrsList)) {
				foreach(self::$arParametrsList as $blockCode => $arBlock) {
					if($arBlock["OPTIONS"] && is_array($arBlock["OPTIONS"])) {
						foreach($arBlock["OPTIONS"] as $optionCode => $arOption) {
							$arDefaultValues[$optionCode] = $arOption["DEFAULT"];
						}
					}
				}
			}
			$arValues = unserialize(COption::GetOptionString(self::MODULE_ID, "OPTIONS", serialize(array()), $SITE_ID));		
			if($arValues && is_array($arValues)) {
				foreach($arValues as $optionCode => $arOption) {
					if(!isset($arDefaultValues[$optionCode])) {
						unset($arValues[$optionCode]);
					}
				}
			}
			if($arDefaultValues && is_array($arDefaultValues)) {
				foreach($arDefaultValues as $optionCode => $arOption) {
					if(!isset($arValues[$optionCode])) {
						$arValues[$optionCode] = $arOption;
					}
				}
			}
		}
		return $arValues;
	}

	function CheckColor($strColor) {
		if(strlen($strColor) > 0) {
			$strColor = str_replace("#", "", $strColor);
			if(strlen($strColor) < 6) {
				if(strlen($strColor) <> 3) {
					for($i = 0, $l = 6 - strlen($strColor); $i < $l; ++$i) {
						$strColor = $strColor."0";
					}					
				}
			} elseif(strlen($strColor) > 6) {
				$strColor = substr($strColor, 0, -(strlen($strColor) - 6));							
			}
			$strColor = "#".$strColor;
		} else {
			$strColor = self::$arParametrsList["MAIN"]["OPTIONS"]["COLOR_SCHEME"]["LIST"][self::$arParametrsList["MAIN"]["OPTIONS"]["COLOR_SCHEME"]["DEFAULT"]]["COLOR"];			
		}		
		return $strColor;
	}

	function UpdateParametrsValues() {		
		if(self::$arParametrsList && is_array(self::$arParametrsList)) {
			foreach(self::$arParametrsList as $blockCode => $arBlock) {
				if($arBlock["OPTIONS"] && is_array($arBlock["OPTIONS"])) {
					foreach($arBlock["OPTIONS"] as $optionCode => $arOption) {
						if($arOption["IN_SETTINGS_PANEL"] == "Y" && $_POST["THEME"] == "default") {
							$newVal = $arOption["DEFAULT"];
						} else {
							if(isset($_POST[$optionCode])) {											
								$newVal = $_POST[$optionCode];
								if($arOption["TYPE"] == "multiselectbox") {
									if(!is_array($newVal))
										$newVal = array();
								}
							}
						}
						$arTab["OPTIONS"][$optionCode] = $newVal;
					}
				}
			}
		}		
		COption::SetOptionString(self::MODULE_ID, "OPTIONS", serialize((array)$arTab["OPTIONS"]), "", SITE_ID);				

		if(self::IsCompositeEnabled()) {
			$obCache = new CPHPCache();
			$obCache->CleanDir("", "html_pages");
			self::EnableComposite();
		}		
	}	

	function GenerateColorScheme() {		
		$arBackParametrs = self::GetParametrsValues(SITE_ID);		
		$colorScheme = $arBackParametrs["COLOR_SCHEME"];
		$arColorSchemes = self::$arParametrsList["MAIN"]["OPTIONS"]["COLOR_SCHEME"]["LIST"];		
		if(!class_exists("lessc")) {
			include_once "lessc.inc.php";
		}
		$less = new lessc;
		try {
			if($arColorSchemes && is_array($arColorSchemes)) {
				if($colorScheme == "CUSTOM") {
					$colorCustom = $arBackParametrs["COLOR_SCHEME_CUSTOM"] = str_replace("#", "", $arBackParametrs["COLOR_SCHEME_CUSTOM"]);
					$less->setVariables(array("bcolor" => (strlen($colorCustom) ? "#".$colorCustom : $arColorSchemes[self::$arParametrsList["MAIN"]["OPTIONS"]["COLOR_SCHEME"]["DEFAULT"]]["COLOR"])));
				} else {				
					$less->setVariables(array("bcolor" => $arColorSchemes[$colorScheme]["COLOR"]));
				}
				if(defined("SITE_TEMPLATE_PATH")) {
					$schemeDirPath = $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/schemes/".$colorScheme.($colorScheme == "CUSTOM" ? "_".SITE_ID : "")."/";
					if(!is_dir($schemeDirPath))
						mkdir($schemeDirPath, 0755, true);
					$inputFile = __DIR__."/../../css/colors.less";
					$outputFile = $schemeDirPath."colors.css";
					$inputCache = $less->cachedCompile($inputFile);
					$outputCache = file_get_contents($outputFile);
					if(md5($outputCache) != md5($inputCache["compiled"])) {
						$output = $less->compileFile($inputFile, $outputFile);
					} else {
						$output = $less->checkedCompile($inputFile, $outputFile);
					}
				}
			}
		} catch(exception $e) {
			echo "Fatal error: ".$e->getMessage();
			die();
		}
	}

	function LoadCountdown() {
		$arBackParametrs = self::GetParametrsValues(SITE_ID);
		$openingDate = $arBackParametrs["DATE_OPENING_SITE"];
		$showCountdown = $openingDate && time() + CTimeZone::GetOffset() < MakeTimeStamp($openingDate) ? true : false;		
		if($showCountdown) {
			$arOpeningDate = ParseDateTime($openingDate, FORMAT_DATETIME);
			$GLOBALS["APPLICATION"]->AddHeadScript(SITE_TEMPLATE_PATH."/js/countdown/jquery.plugin.min.js");
			$GLOBALS["APPLICATION"]->AddHeadScript(SITE_TEMPLATE_PATH."/js/countdown/jquery.countdown.min.js");
			$GLOBALS["APPLICATION"]->AddHeadString("
				<script type='text/javascript'>
					$(function() {
						$.countdown.regionalOptions['ru'] = {
							labels: ['".GetMessage("COUNTDOWN_REGIONAL_LABELS_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_DAY")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_HOUR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
							labels1: ['".GetMessage("COUNTDOWN_REGIONAL_LABELS1_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_DAY")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_HOUR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_MIN")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_SEC")."'],
							labels2: ['".GetMessage("COUNTDOWN_REGIONAL_LABELS2_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_DAY")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_HOUR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_MIN")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_SEC")."'],
							compactLabels: ['".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_DAY")."'],
							compactLabels1: ['".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS1_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_DAY")."'],
							whichLabels: function(amount) {
								var units = amount % 10;
								var tens = Math.floor((amount % 100) / 10);
								return (amount == 1 ? 1 : (units >= 2 && units <= 4 && tens != 1 ? 2 : (units == 1 && tens != 1 ? 1 : 0)));
							},
							digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
							timeSeparator: ':',
							isRTL: false
						};
						$.countdown.setDefaults($.countdown.regionalOptions['ru']);
					});
				</script>
			", true);		
			$GLOBALS["APPLICATION"]->AddHeadString("
				<script type='text/javascript'>
					$(function() {
						if($('.site-opening-timer').length) {
							$('.site-opening-timer').countdown({
								until: new Date(".$arOpeningDate["YYYY"].", ".$arOpeningDate["MM"]." - 1, ".$arOpeningDate["DD"].($arOpeningDate["HH"] ? ", ".$arOpeningDate["HH"] : "").($arOpeningDate["MI"] ? ", ".$arOpeningDate["MI"] : "").")								
							});
						}
					});
				</script>
			", true);
		}		
		return $showCountdown;
	}

	function CheckCaptchaCode($userCode, $sid, $bUpperCode = true) {
		global $DB;		
		if(strlen($userCode) <= 0 || strlen($sid) <= 0)
			return false;		
		if($bUpperCode)
			$userCode = strtoupper($userCode);		
		$res = $DB->Query("SELECT CODE FROM b_captcha WHERE ID = '".$DB->ForSQL($sid,32)."' ");
		if(!$ar = $res->Fetch())
			return false;		
		if($ar["CODE"] != $userCode)
			return false;		
		return true;
	}
	
	function start($siteID) {	
		return true;
	}
	
	public function showPanel() {
		global $APPLICATION, $USER;
		if($USER->IsAdmin() && COption::GetOptionString("main", "wizard_solution", "", SITE_ID) == self::SOLUTION_NAME) {
			$APPLICATION->SetAdditionalCSS("/bitrix/wizards/".self::PARTNER_NAME."/".self::SOLUTION_NAME."/css/panel.css"); 
			
			$arMenu = array(
				array(
					"ACTION" => "jsUtils.Redirect([], \"".CUtil::JSEscape("/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardSiteID=".SITE_ID."&wizardName=".self::PARTNER_NAME.":".self::SOLUTION_NAME."&".bitrix_sessid_get())."\")",
					"ICON" => "bx-popup-item-wizard-icon",
					"TITLE" => GetMessage("STOM_BUTTON_TITLE_W1"),
					"TEXT" => GetMessage("STOM_BUTTON_NAME_W1"),
				),
			);

			$APPLICATION->AddPanelButton(
				array(
					"HREF" => "/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardName=".self::PARTNER_NAME.":".self::SOLUTION_NAME."&wizardSiteID=".SITE_ID."&".bitrix_sessid_get(),
					"ID" => self::SOLUTION_NAME."_wizard",
					"ICON" => "bx-panel-site-wizard-icon",
					"MAIN_SORT" => 2500,
					"TYPE" => "BIG",
					"SORT" => 10,	
					"ALT" => GetMessage("SCOM_BUTTON_DESCRIPTION"),
					"TEXT" => GetMessage("SCOM_BUTTON_NAME"),
					"MENU" => $arMenu,
				)
			);
		}
	}	
	
	public function correctInstall(){
		if(CModule::IncludeModule("main")) {
			if(COption::GetOptionString(self::MODULE_ID, "WIZARD_DEMO_INSTALLED") == "Y") {
				require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/wizard.php");
				@set_time_limit(0);
				if(!CWizardUtil::DeleteWizard(self::PARTNER_NAME.":".self::SOLUTION_NAME)) {
					if(!DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/".self::PARTNER_NAME."/".self::SOLUTION_NAME."/")) {
						self::removeDirectory($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/".self::PARTNER_NAME."/".self::SOLUTION_NAME."/");
					}
				}
				
				UnRegisterModuleDependences("main", "OnBeforeProlog", self::MODULE_ID, __CLASS__, "correctInstall"); 
				COption::SetOptionString(self::MODULE_ID, "WIZARD_DEMO_INSTALLED", "N");
			}
		}  
	}	
	
	function IsCompositeEnabled() {
		if(class_exists("CHTMLPagesCache")) {
			if(method_exists("CHTMLPagesCache", "GetOptions")) {
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()) {
					if($arHTMLCacheOptions["COMPOSITE"] == "Y") {
						return true;
					}
				}
			}
		}
		return false;
	}
	
	function EnableComposite() {
		if(class_exists("CHTMLPagesCache")) {
			if(method_exists("CHTMLPagesCache", "GetOptions")) {
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()) {
					$arHTMLCacheOptions["COMPOSITE"] = "Y";
					$arHTMLCacheOptions["DOMAINS"] = array_merge((array)$arHTMLCacheOptions["DOMAINS"], (array)$arDomains);
					CHTMLPagesCache::SetEnabled(true);
					CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
					bx_accelerator_reset();
				}
			}
		}
	}

	function UpdateMailEvent(&$arFields) {
		if($arFields["IBLOCK_ID"]) {			
			$arIBlock = CIBlock::GetList(array("SORT" => "ASC"), array("ID" => $arFields["IBLOCK_ID"]))->Fetch();						
			$eventName = "ALTOP_FORM_".$arIBlock["CODE"];
			$arEvent = CEventType::GetByID($eventName, LANGUAGE_ID)->Fetch();
			if($arEvent) {
				if(strpos($arEvent["DESCRIPTION"], "#".$arFields["CODE"]."# - ".$arFields["NAME"]) == false) {
					$arEvent["DESCRIPTION"] = str_replace("#".$arFields["CODE"]."#", "", $arEvent["DESCRIPTION"]);
					$arEvent["DESCRIPTION"] .= "#".$arFields["CODE"]."# - ".$arFields["NAME"]."\n";
					CEventType::Update(array("ID" => $arEvent["ID"]), $arEvent);
				}
			}
		}
	}
}?>