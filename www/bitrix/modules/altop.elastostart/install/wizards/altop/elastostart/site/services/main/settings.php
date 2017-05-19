<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!defined("WIZARD_SITE_ID"))
	return;

/***FILEMAN***/
COption::SetOptionString("fileman", "propstypes", serialize(array("description" => GetMessage("MAIN_OPT_DESCRIPTION"), "keywords" => GetMessage("MAIN_OPT_KEYWORDS"), "title" => GetMessage("MAIN_OPT_TITLE"), "keywords_inner" => GetMessage("MAIN_OPT_KEYWORDS_INNER"))), false, WIZARD_SITE_ID);

/***SEARCH***/
COption::SetOptionInt("search", "suggest_save_days", 250);
COption::SetOptionString("search", "use_tf_cache", "Y");
COption::SetOptionString("search", "use_word_distance", "Y");
COption::SetOptionString("search", "use_social_rating", "Y");

/***IBLOCK***/
COption::SetOptionString("iblock", "use_htmledit", "Y");
COption::SetOptionString("iblock", "combined_list_mode", "Y");

/***SOCIALSERVICES***/
if(COption::GetOptionString("socialservices", "auth_services") == "") {
	$bRu = (LANGUAGE_ID == 'ru');
	$arServices = array(
		"VKontakte" => "N",  
		"MyMailRu" => "N",
		"Twitter" => "N",
		"Facebook" => "N",
		"Livejournal" => "Y",
		"YandexOpenID" => ($bRu? "Y":"N"),
		"Rambler" => ($bRu? "Y":"N"),
		"MailRuOpenID" => ($bRu? "Y":"N"),
		"Liveinternet" => ($bRu? "Y":"N"),
		"Blogger" => "Y",
		"OpenID" => "Y",
		"LiveID" => "N",
	);
	COption::SetOptionString("socialservices", "auth_services", serialize($arServices));
}

/***APPLE_TOUCH_ICON***/
$arIcons = array("APPLE_TOUCH_ICON_114_114", "APPLE_TOUCH_ICON_144_144");
foreach($arIcons as $arIcon) {	
	if($arIcon == "APPLE_TOUCH_ICON_114_114") {
		$arFile = CFile::MakeFileArray(WIZARD_TEMPLATE_ABSOLUTE_PATH."/images/apple-touch-icon-114.png");
	} elseif($arIcon == "APPLE_TOUCH_ICON_144_144") {
		$arFile = CFile::MakeFileArray(WIZARD_TEMPLATE_ABSOLUTE_PATH."/images/apple-touch-icon-144.png");
	}
	$arFile["MODULE_ID"] = "elastostart";
	$appleTouchIcon = CFile::SaveFile($arFile, "elastostart");
	if($appleTouchIcon > 0)
		COption::SetOptionString("elastostart", $arIcon, $appleTouchIcon);
}

/***CAPTCHA***/
COption::SetOptionString("main", "CAPTCHA_presets", "0");
COption::SetOptionString("main", "CAPTCHA_transparentTextPercent", "0");
COption::SetOptionString("main", "CAPTCHA_arBGColor_1", "d0e0e3");
COption::SetOptionString("main", "CAPTCHA_arBGColor_2", "d0e0e3");
COption::SetOptionString("main", "CAPTCHA_numEllipses", "0");
COption::SetOptionString("main", "CAPTCHA_arEllipseColor_1", "ffffff");
COption::SetOptionString("main", "CAPTCHA_arEllipseColor_2", "ffffff");
COption::SetOptionString("main", "CAPTCHA_bLinesOverText", "N");
COption::SetOptionString("main", "CAPTCHA_numLines", "0");
COption::SetOptionString("main", "CAPTCHA_arLineColor_1", "ffffff");
COption::SetOptionString("main", "CAPTCHA_arLineColor_2", "ffffff");
COption::SetOptionString("main", "CAPTCHA_textStartX", "30");
COption::SetOptionString("main", "CAPTCHA_textFontSize", "24");
COption::SetOptionString("main", "CAPTCHA_arTextColor_1", "000000");
COption::SetOptionString("main", "CAPTCHA_arTextColor_2", "000000");
COption::SetOptionString("main", "CAPTCHA_textAngel_1", "-15");
COption::SetOptionString("main", "CAPTCHA_textAngel_2", "-15");
COption::SetOptionString("main", "CAPTCHA_textDistance_1", "15");
COption::SetOptionString("main", "CAPTCHA_textDistance_2", "15");
COption::SetOptionString("main", "CAPTCHA_bWaveTransformation", "N");
COption::SetOptionString("main", "CAPTCHA_bEmptyText", "N");
COption::SetOptionString("main", "CAPTCHA_arBorderColor", "d0e0e3");
COption::SetOptionString("main", "CAPTCHA_arTTFFiles", "bitrix_captcha.ttf");
COption::SetOptionString("main", "CAPTCHA_letters", "123456789");?>