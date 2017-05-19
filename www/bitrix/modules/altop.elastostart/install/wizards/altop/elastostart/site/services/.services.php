<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arServices = array(
	"main" => array(
		"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => array(
			"files.php",
			"template.php",
			"menu.php",
			"settings.php"
		)
	),
	"iblock" => array(
		"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
		"STAGES" => array(
			"types.php",			
			"block_slider.php",
			"block_advantages.php",
			"block_services.php",
			"block_gallery.php",
			"block_social.php",
			"news.php",
			"services.php",
			"gallery.php",
			"callback.php",
			"feedback.php"
		)
	)	
);?>