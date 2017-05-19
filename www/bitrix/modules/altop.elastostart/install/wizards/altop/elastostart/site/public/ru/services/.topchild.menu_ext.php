<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent("altop:menu.links.elastostart", "",
	array(		
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "#ELASTO_SERVICES_IBLOCK_ID#",
		"DEPTH_LEVEL" => "2",
		"SHOW_ELEMENTS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000" 
    ),
	false,
	Array("HIDE_ICONS" => "Y")
);
$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);?>