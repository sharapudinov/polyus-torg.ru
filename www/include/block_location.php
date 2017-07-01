<div id="map" class="map-wrapper">
	<div class="container">				
		<div class="row">
			<div class="col-md-4">
				<div class="map-caption">
					<div class="h1">Схема проезда</div>
					<div class="address">
						<!--ADDRESS-->
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/address.php"), false);?>								
					</div>
					<div class="contacts">
						<!--CONTACTS-->
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/contacts.php"), false);?>								

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="map-direction">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
			array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/map.php"
			),
			false,
			array("HIDE_ICONS" => "Y")
		);?>		
	</div>
</div>