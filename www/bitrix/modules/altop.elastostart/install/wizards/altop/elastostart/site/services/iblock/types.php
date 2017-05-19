<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;
	
$arTypes = array(	
	array(
		"ID" => "content",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 100,
		"LANG" => array()
	),
	array(
		"ID" => "forms",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 200,
		"LANG" => array()
	),
);

$arLanguages = Array();
$rsLanguage = CLanguage::GetList($by, $order, array());
while($arLanguage = $rsLanguage->Fetch())
	$arLanguages[] = $arLanguage["LID"];
	
$iblockType = new CIBlockType;	

foreach($arTypes as $arType) {
	$dbType = CIBlockType::GetList(array(), array("=ID" => $arType["ID"]));	
	if($dbType->Fetch())
		continue;

	foreach($arLanguages as $languageID) {
		WizardServices::IncludeServiceLang("types.php", $languageID);
		$code = strtoupper($arType["ID"]);		
		$arType["LANG"][$languageID]["NAME"] = GetMessage($code."_TYPE_NAME");
		$arType["LANG"][$languageID]["ELEMENT_NAME"] = GetMessage($code."_ELEMENT_NAME");
		if($arType["SECTIONS"] == "Y")
			$arType["LANG"][$languageID]["SECTION_NAME"] = GetMessage($code."_SECTION_NAME");
	}
		
	$iblockType->Add($arType);
};?>