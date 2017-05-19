<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require(dirname(__FILE__)."/lang/".LANGUAGE_ID."/script.php");

if(!CModule::IncludeModule("iblock"))
	return;

/***CAPTCHA***/
if($_POST["USE_CAPTCHA"] == "Y") {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
	CCaptcha::Delete($_POST["captcha_sid"]);
	$newCaptchaCode = $APPLICATION->CaptchaGetCode();
}

/***IBLOCK***/
$_POST["IBLOCK_NAME"] = iconv("UTF-8", LANG_CHARSET, $_POST["IBLOCK_NAME"]);

/***IBLOCK_PROPS***/
$arPropFields = array();

$fields = array();
if(isset($_POST["FIELDS_STRING"])) {
	$fields = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_POST["FIELDS_STRING"], '-_,', '+/=')))));
}

if(!empty($fields)) {
	foreach($fields as $key => $arField) {		
		if(isset($_POST[$arField["CODE"]]) && !empty($_POST[$arField["CODE"]])) {
			if($arField["USER_TYPE"] == "HTML") {
				$arPropFields[$arField["CODE"]] = array(
					"VALUE" => array(
						"TEXT" => iconv("UTF-8", LANG_CHARSET, strip_tags(trim($_POST[$arField["CODE"]]))),
						"TYPE" => $arField["DEFAULT_VALUE"]["TYPE"]
					)
				);
			} else {
				$arPropFields[$arField["CODE"]] = iconv("UTF-8", LANG_CHARSET, strip_tags(trim($_POST[$arField["CODE"]])));
			}
		}
	}
}

/***IBLOCK_ELEMENT***/
$el = new CIBlockElement;

$arFields = array(
	"IBLOCK_ID" => $_POST["IBLOCK_ID"],
	"ACTIVE" => "Y",
	"NAME" => GetMessage("IBLOCK_ELEMENT_NAME").ConvertTimeStamp(time(), "FULL"),
	"PROPERTY_VALUES" => $arPropFields,
);

if($el->Add($arFields)) {
	/***MAIL_EVENT***/	
	$eventName = "ALTOP_FORM_".$_POST["IBLOCK_CODE"];

	$eventDesc = $messBody = "";
	if(!empty($fields)) {
		foreach($fields as $key => $arField) {
			$eventDesc .= "#".$arField["CODE"]."# - ".$arField["NAME"]."\n";
			$messBody .= $arField["NAME"].": "."#".$arField["CODE"]."#\n";		
		}
	}
	$eventDesc .= GetMessage("MAIL_EVENT_DESCRIPTION");	
	
	/***MAIL_EVENT_TYPE***/
	$arEvent = CEventType::GetByID($eventName, LANGUAGE_ID)->Fetch();
	if(!is_array($arEvent)) {
		$et = new CEventType;
		$arEventFields = array(
			"LID" => LANGUAGE_ID,
			"EVENT_NAME" => $eventName,
			"NAME" => GetMessage("MAIL_EVENT_TYPE_NAME")." \"".$_POST["IBLOCK_NAME"]."\"",
			"DESCRIPTION" => $eventDesc
		);
		$et->Add($arEventFields);		
	}
	
	/***MAIL_EVENT_MESSAGE***/
	$arMess = CEventMessage::GetList($by = "site_id", $order = "desc", array("TYPE_ID" => $eventName))->Fetch();
	if(!is_array($arMess)) {
		$em = new CEventMessage;
		$arMess = array();
		$arMess["ID"] = $em->Add(
			array(
				"ACTIVE" => "Y",
				"EVENT_NAME" => $eventName,
				"LID" => SITE_ID,
				"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
				"EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
				"BCC" => "",
				"SUBJECT" => GetMessage("MAIL_EVENT_MESSAGE_SUBJECT"),
				"BODY_TYPE" => "text",
				"MESSAGE" => GetMessage("MAIL_EVENT_MESSAGE_MESSAGE_HEADER").$messBody.GetMessage("MAIL_EVENT_MESSAGE_MESSAGE_FOOTER")
			)
		);		
	}

	/***SEND_MAIL***/	
	$arEventFields = array(		
		"FORM_NAME" => $_POST["IBLOCK_NAME"]
	);

	foreach($arPropFields as $key => $arPropField) {
		$arEventFields[$key] = $arPropField;
	}

	CEvent::SendImmediate($eventName, SITE_ID, $arEventFields, "Y", $arMess["ID"]);

	if($_POST["USE_CAPTCHA"] == "Y") {
		echo json_encode(
			array(
				"result" => "Y",				
				"captcha_code" => $newCaptchaCode
			)
		);
	} else {
		echo json_encode(
			array(
				"result" => "Y"				
			)
		);
	}
} else {	
	if($_POST["USE_CAPTCHA"] == "Y") {
		echo json_encode(
			array(
				"result" => "N",				
				"captcha_code" => $newCaptchaCode
			)
		);
	} else {
		echo json_encode(
			array(
				"result" => "N"				
			)
		);
	}
	return;
}?>