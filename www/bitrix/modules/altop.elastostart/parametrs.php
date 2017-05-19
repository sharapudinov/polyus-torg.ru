<?IncludeModuleLangFile(__FILE__);
$moduleClass = "CElastoStart";

//initialize module parametrs list and default values
$moduleClass::$arParametrsList = array(
	"MAIN" => array(
		"TITLE" => GetMessage("MAIN_OPTIONS"),
		"OPTIONS" => array(			
			"SHOW_SETTINGS_PANEL" => array(
				"TITLE" => GetMessage("SHOW_SETTINGS_PANEL"),
				"TYPE" => "checkbox",
				"DEFAULT" => "Y",
				"IN_SETTINGS_PANEL" => "N"
			),
			"COLOR_SCHEME" => array(
				"TITLE" => GetMessage("COLOR_SCHEME"), 
				"TYPE" => "selectbox", 
				"LIST" => array(
					"BLUE" => array("COLOR" => "#33a9d9", "TITLE" => GetMessage("COLOR_SCHEME_BLUE")),
					"STRONG_BLUE" => array("COLOR" => "#286bc6", "TITLE" => GetMessage("COLOR_SCHEME_STRONG_BLUE")),
					"DARK_BLUE" => array("COLOR" => "#3847a2", "TITLE" => GetMessage("COLOR_SCHEME_DARK_BLUE")),
					"VIOLET" => array("COLOR" => "#9933d9", "TITLE" => GetMessage("COLOR_SCHEME_VIOLET")),
					"PINK" => array("COLOR" => "#d9336e", "TITLE" => GetMessage("COLOR_SCHEME_PINK")),
					"RED" => array("COLOR" => "#d93324", "TITLE" => GetMessage("COLOR_SCHEME_RED")),
					"ORANGE" => array("COLOR" => "#ff6634", "TITLE" => GetMessage("COLOR_SCHEME_ORANGE")),
					"YELLOW" => array("COLOR" => "#faa510", "TITLE" => GetMessage("COLOR_SCHEME_YELLOW")),
					"STRONG_YELLOW" => array("COLOR" => "#9dc21a", "TITLE" => GetMessage("COLOR_SCHEME_STRONG_YELLOW")),
					"GREEN" => array("COLOR" => "#349933", "TITLE" => GetMessage("COLOR_SCHEME_GREEN")),
					"CYAN" => array("COLOR" => "#1cc1a4", "TITLE" => GetMessage("COLOR_SCHEME_CYAN")),
					"GRAY" => array("COLOR" => "#55686e", "TITLE" => GetMessage("COLOR_SCHEME_GRAY")),
					"CUSTOM" => array("COLOR" => "", "TITLE" => GetMessage("COLOR_SCHEME_CUSTOM")),
				),
				"DEFAULT" => "BLUE",
				"IN_SETTINGS_PANEL" => "Y"
			),
			"COLOR_SCHEME_CUSTOM" => array(
				"TITLE" => GetMessage("COLOR_SCHEME_CUSTOM"), 
				"TYPE" => "text", 
				"DEFAULT" => "#33a9d9",
				"IN_SETTINGS_PANEL" => "Y"
			),
			"TOP_MENU" => array(
				"TITLE" => GetMessage("TOP_MENU"),
				"TYPE" => "selectbox", 
				"LIST" => array(
					"LIGHT" => GetMessage("TOP_MENU_LIGHT"),
					"DARK" => GetMessage("TOP_MENU_DARK"),
					"SCHEME" => GetMessage("TOP_MENU_SCHEME"),
				),
				"DEFAULT" => "LIGHT",
				"IN_SETTINGS_PANEL" => "Y"
			),
			"HOME_PAGE" => array(
				"TITLE" => GetMessage("HOME_PAGE"),
				"TYPE" => "multiselectbox",
				"LIST" => array(					
					"SLIDER" => GetMessage("HOME_PAGE_SLIDER"),
					"ADVANTAGES" => GetMessage("HOME_PAGE_ADVANTAGES"),
					"SERVICES" => GetMessage("HOME_PAGE_SERVICES"),
					"CONTENT" => GetMessage("HOME_PAGE_CONTENT"),
					"GALLERY" => GetMessage("HOME_PAGE_GALLERY"),
					"NEWS" => GetMessage("HOME_PAGE_NEWS"),
					"LOCATION" => GetMessage("HOME_PAGE_LOCATION")
				),
				"DEFAULT" => array("SLIDER", "ADVANTAGES", "SERVICES", "CONTENT", "GALLERY", "NEWS", "LOCATION"),
				"IN_SETTINGS_PANEL" => "Y"
			)
		)
	),
	"SLIDER" => array(
		"TITLE" => GetMessage("SLIDER_OPTIONS"),
		"OPTIONS" => array(
			"SMART_SPEED" => array(
				"TITLE" => GetMessage("SMART_SPEED"),
				"TYPE" => "text",
				"DEFAULT" => "1000",
				"IN_SETTINGS_PANEL" => "N"
			),
			"AUTOPLAY_TIMEOUT" => array(
				"TITLE" => GetMessage("AUTOPLAY_TIMEOUT"),
				"TYPE" => "text",
				"DEFAULT" => "5000",
				"IN_SETTINGS_PANEL" => "N"
			),
			"ANIMATE_OUT" => array(
				"TITLE" => GetMessage("ANIMATE_OUT"),
				"TYPE" => "selectbox", 
				"LIST" => array(
					"none" => GetMessage("ANIMATE_NONE"),
					"bounce" => GetMessage("ANIMATE_BOUNCE"),
					"flash" => GetMessage("ANIMATE_FLASH"),
					"pulse" => GetMessage("ANIMATE_PULSE"),
					"rubberBand" => GetMessage("ANIMATE_RUBBER_BAND"),
					"shake" => GetMessage("ANIMATE_SHAKE"),
					"swing" => GetMessage("ANIMATE_SWING"),
					"tada" => GetMessage("ANIMATE_TADA"),
					"wobble" => GetMessage("ANIMATE_WOBBLE"),
					"jello" => GetMessage("ANIMATE_JELLO"),
					"bounceOut" => GetMessage("ANIMATE_BOUNCE_OUT"),
					"bounceOutDown" => GetMessage("ANIMATE_BOUNCE_OUT_DOWN"),
					"bounceOutLeft" => GetMessage("ANIMATE_BOUNCE_OUT_LEFT"),
					"bounceOutRight" => GetMessage("ANIMATE_BOUNCE_OUT_RIGHT"),
					"bounceOutUp" => GetMessage("ANIMATE_BOUNCE_OUT_UP"),
					"fadeOut" => GetMessage("ANIMATE_FADE_OUT"),
					"fadeOutDown" => GetMessage("ANIMATE_FADE_OUT_DOWN"),
					"fadeOutDownBig" => GetMessage("ANIMATE_FADE_OUT_DOWN_BIG"),
					"fadeOutLeft" => GetMessage("ANIMATE_FADE_OUT_LEFT"),
					"fadeOutLeftBig" => GetMessage("ANIMATE_FADE_OUT_LEFT_BIG"),
					"fadeOutRight" => GetMessage("ANIMATE_FADE_OUT_RIGHT"),
					"fadeOutRightBig" => GetMessage("ANIMATE_FADE_OUT_RIGHT_BIG"),
					"fadeOutUp" => GetMessage("ANIMATE_FADE_OUT_UP"),
					"fadeOutUpBig" => GetMessage("ANIMATE_FADE_OUT_UP_BIG"),
					"flip" => GetMessage("ANIMATE_FLIP"),
					"flipOutX" => GetMessage("ANIMATE_FLIP_OUT_X"),
					"flipOutY" => GetMessage("ANIMATE_FLIP_OUT_Y"),
					"lightSpeedOut" => GetMessage("ANIMATE_LIGHT_SPEED_OUT"),
					"rotateOut" => GetMessage("ANIMATE_ROTATE_OUT"),
					"rotateOutDownLeft" => GetMessage("ANIMATE_ROTATE_OUT_DOWN_LEFT"),
					"rotateOutDownRight" => GetMessage("ANIMATE_ROTATE_OUT_DOWN_RIGHT"),
					"rotateOutUpLeft" => GetMessage("ANIMATE_ROTATE_OUT_UP_LEFT"),
					"rotateOutUpRight" => GetMessage("ANIMATE_ROTATE_OUT_UP_RIGHT"),
					"slideOutUp" => GetMessage("ANIMATE_SLIDE_OUT_UP"),
					"slideOutDown" => GetMessage("ANIMATE_SLIDE_OUT_DOWN"),
					"slideOutLeft" => GetMessage("ANIMATE_SLIDE_OUT_LEFT"),
					"slideOutRight" => GetMessage("ANIMATE_SLIDE_OUT_RIGHT"),
					"zoomOut" => GetMessage("ANIMATE_ZOOM_OUT"),
					"zoomOutDown" => GetMessage("ANIMATE_ZOOM_OUT_DOWN"),
					"zoomOutLeft" => GetMessage("ANIMATE_ZOOM_OUT_LEFT"),
					"zoomOutRight" => GetMessage("ANIMATE_ZOOM_OUT_RIGHT"),
					"zoomOutUp" => GetMessage("ANIMATE_ZOOM_OUT_UP"),					
					"hinge" => GetMessage("ANIMATE_HINGE"),
					"rollOut" => GetMessage("ANIMATE_ROLL_OUT")
				),
				"DEFAULT" => "fadeOut",
				"IN_SETTINGS_PANEL" => "N"
			),
			"ANIMATE_IN" => array(
				"TITLE" => GetMessage("ANIMATE_IN"),
				"TYPE" => "selectbox", 
				"LIST" => array(
					"none" => GetMessage("ANIMATE_NONE"),
					"bounce" => GetMessage("ANIMATE_BOUNCE"),
					"flash" => GetMessage("ANIMATE_FLASH"),
					"pulse" => GetMessage("ANIMATE_PULSE"),
					"rubberBand" => GetMessage("ANIMATE_RUBBER_BAND"),
					"shake" => GetMessage("ANIMATE_SHAKE"),
					"swing" => GetMessage("ANIMATE_SWING"),
					"tada" => GetMessage("ANIMATE_TADA"),
					"wobble" => GetMessage("ANIMATE_WOBBLE"),
					"jello" => GetMessage("ANIMATE_JELLO"),
					"bounceIn" => GetMessage("ANIMATE_BOUNCE_IN"),
					"bounceInDown" => GetMessage("ANIMATE_BOUNCE_IN_DOWN"),
					"bounceInLeft" => GetMessage("ANIMATE_BOUNCE_IN_LEFT"),
					"bounceInRight" => GetMessage("ANIMATE_BOUNCE_IN_RIGHT"),
					"bounceInUp" => GetMessage("ANIMATE_BOUNCE_IN_UP"),
					"fadeIn" => GetMessage("ANIMATE_FADE_IN"),
					"fadeInDown" => GetMessage("ANIMATE_FADE_IN_DOWN"),
					"fadeInDownBig" => GetMessage("ANIMATE_FADE_IN_DOWN_BIG"),
					"fadeInLeft" => GetMessage("ANIMATE_FADE_IN_LEFT"),
					"fadeInLeftBig" => GetMessage("ANIMATE_FADE_IN_LEFT_BIG"),
					"fadeInRight" => GetMessage("ANIMATE_FADE_IN_RIGHT"),
					"fadeInRightBig" => GetMessage("ANIMATE_FADE_IN_RIGHT_BIG"),
					"fadeInUp" => GetMessage("ANIMATE_FADE_IN_UP"),
					"fadeInUpBig" => GetMessage("ANIMATE_FADE_IN_UP_BIG"),
					"flip" => GetMessage("ANIMATE_FLIP"),
					"flipInX" => GetMessage("ANIMATE_FLIP_IN_X"),
					"flipInY" => GetMessage("ANIMATE_FLIP_IN_Y"),
					"lightSpeedIn" => GetMessage("ANIMATE_LIGHT_SPEED_IN"),
					"rotateIn" => GetMessage("ANIMATE_ROTATE_IN"),
					"rotateInDownLeft" => GetMessage("ANIMATE_ROTATE_IN_DOWN_LEFT"),
					"rotateInDownRight" => GetMessage("ANIMATE_ROTATE_IN_DOWN_RIGHT"),
					"rotateInUpLeft" => GetMessage("ANIMATE_ROTATE_IN_UP_LEFT"),
					"rotateInUpRight" => GetMessage("ANIMATE_ROTATE_IN_UP_RIGHT"),
					"slideInUp" => GetMessage("ANIMATE_SLIDE_IN_UP"),
					"slideInDown" => GetMessage("ANIMATE_SLIDE_IN_DOWN"),
					"slideInLeft" => GetMessage("ANIMATE_SLIDE_IN_LEFT"),
					"slideInRight" => GetMessage("ANIMATE_SLIDE_IN_RIGHT"),
					"zoomIn" => GetMessage("ANIMATE_ZOOM_IN"),
					"zoomInDown" => GetMessage("ANIMATE_ZOOM_IN_DOWN"),
					"zoomInLeft" => GetMessage("ANIMATE_ZOOM_IN_LEFT"),
					"zoomInRight" => GetMessage("ANIMATE_ZOOM_IN_RIGHT"),
					"zoomInUp" => GetMessage("ANIMATE_ZOOM_IN_UP"),					
					"hinge" => GetMessage("ANIMATE_HINGE"),
					"rollIn" => GetMessage("ANIMATE_ROLL_IN")
				),
				"DEFAULT" => "fadeIn",
				"IN_SETTINGS_PANEL" => "N"
			)
		)
	),
	"FORMS" => array(
		"TITLE" => GetMessage("FORMS_OPTIONS"),
		"OPTIONS" => array(			
			"PHONE_MASK" => array(
				"TITLE" => GetMessage("PHONE_MASK"),				
				"TYPE" => "text",
				"DEFAULT" => "+7 (999) 99-99-999",
				"IN_SETTINGS_PANEL" => "N"				
			),
			"VALIDATE_PHONE_MASK" => array(
				"TITLE" => GetMessage("VALIDATE_PHONE_MASK"),
				"TYPE" => "text",
				"DEFAULT" => "^[+][0-9]{1} [(][0-9]{3}[)] [0-9]{2}[-][0-9]{2}[-][0-9]{3}$",
				"IN_SETTINGS_PANEL" => "N"
			)			
		)
	),
	"SITE_CLOSING" => array(
		"TITLE" => GetMessage("SITE_CLOSING_OPTIONS"),
		"OPTIONS" => array(			
			"SITE_CLOSED_TITLE" => array(
				"TITLE" => GetMessage("SITE_CLOSED_TITLE"),				
				"TYPE" => "text",
				"DEFAULT" => GetMessage("SITE_CLOSED_TITLE_DEF"),
				"IN_SETTINGS_PANEL" => "N"				
			),
			"SITE_CLOSED_DESCRIPTION" => array(
				"TITLE" => GetMessage("SITE_CLOSED_DESCRIPTION"),				
				"TYPE" => "textarea",
				"ROWS" => "5",
				"COLS" => "50",
				"DEFAULT" => GetMessage("SITE_CLOSED_DESCRIPTION_DEF"),
				"IN_SETTINGS_PANEL" => "N"				
			),
			"SITE_OPENING_TITLE" => array(
				"TITLE" => GetMessage("SITE_OPENING_TITLE"),				
				"TYPE" => "text",
				"DEFAULT" => GetMessage("SITE_OPENING_TITLE_DEF"),
				"IN_SETTINGS_PANEL" => "N"				
			),
			"DATE_OPENING_SITE" => array(
				"TITLE" => GetMessage("DATE_OPENING_SITE"), 
				"TYPE" => "date", 
				"DEFAULT" => ConvertTimeStamp(time() + CTimeZone::GetOffset(), "FULL"),
				"IN_SETTINGS_PANEL" => "N"
			)
		)
	),
	"APPLE_TOUCH_ICON" => array(
		"TITLE" => GetMessage("APPLE_TOUCH_ICON"),
		"OPTIONS" => array(
			"APPLE_TOUCH_ICON_114_114" => array(
				"TITLE" => GetMessage("APPLE_TOUCH_ICON_114_114"),
				"TYPE" => "file",
				"DEFAULT" => COption::GetOptionString("elastostart", "APPLE_TOUCH_ICON_114_114"),				
				"IN_SETTINGS_PANEL" => "N"
			),
			"APPLE_TOUCH_ICON_144_144" => array(
				"TITLE" => GetMessage("APPLE_TOUCH_ICON_144_144"),
				"TYPE" => "file",
				"DEFAULT" => COption::GetOptionString("elastostart", "APPLE_TOUCH_ICON_144_144"),				
				"IN_SETTINGS_PANEL" => "N"
			)
		)
	)
);?>