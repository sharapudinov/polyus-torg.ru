<?
$moduleClass = "CElastoStart";
$moduleID = "altop.elastostart";

CModule::AddAutoloadClasses(
	$moduleID,
	array(
		"elastostart" => "install/index.php",
		$moduleClass => "classes/general/".$moduleClass.".php"		
	)
);

//event handlers for component altop:forms.elastostart
AddEventHandler("iblock", "OnAfterIBlockPropertyUpdate", array($moduleClass, "UpdateMailEvent"));
AddEventHandler("iblock", "OnAfterIBlockPropertyAdd", array($moduleClass, "UpdateMailEvent"));