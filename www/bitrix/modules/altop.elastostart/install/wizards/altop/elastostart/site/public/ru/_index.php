<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ELASTO START - Готовый сайт-визитка на 1С-Битрикс");
global $arSettings;?>

<!--BLOCK_SLIDER-->
<?if(in_array("SLIDER", $arSettings["HOME_PAGE"]["VALUE"])):?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_slider.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?endif;?>

<!--BLOCK_ADVANTAGES-->
<?if(in_array("ADVANTAGES", $arSettings["HOME_PAGE"]["VALUE"])):?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_advantages.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?endif;?>

<!--BLOCK_SERVICES-->
<?if(in_array("SERVICES", $arSettings["HOME_PAGE"]["VALUE"])):?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_services.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?endif;?>

<!--BLOCK_CONTENT-->
<?if(in_array("CONTENT", $arSettings["HOME_PAGE"]["VALUE"])):?>
	<div class="content-wrapper">
		<div class="container">				
			<div class="row content">
				<div class="col-md-12">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
						array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => SITE_DIR."include/block_content.php"
						),
						false
					);?>
				</div>		
			</div>
		</div>
	</div>
<?endif;?>

<!--BLOCK_GALLERY-->
<?if(in_array("GALLERY", $arSettings["HOME_PAGE"]["VALUE"])):?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_gallery.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?endif;?>

<!--BLOCK_NEWS-->
<?if(in_array("NEWS", $arSettings["HOME_PAGE"]["VALUE"])):?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_news.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?endif;?>

<!--BLOCK_LOCATION-->
<?if(in_array("LOCATION", $arSettings["HOME_PAGE"]["VALUE"])):?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/block_location.php"
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?endif;?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>