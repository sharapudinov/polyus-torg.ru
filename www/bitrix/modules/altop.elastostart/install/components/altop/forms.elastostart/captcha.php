<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$resp = CElastoStart::CheckCaptchaCode($_POST["captcha_word"], $_POST["captcha_sid"]);

echo json_encode(
	array(
		"valid" => $resp
	)
);?>