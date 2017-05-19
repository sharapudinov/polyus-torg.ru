<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $arSettings;

$smartSpeed = $arSettings["SMART_SPEED"]["VALUE"] ? $arSettings["SMART_SPEED"]["VALUE"] : 1000;
$loop = count($arResult["ELEMENTS"]) > 1 ? "true" : "false";
$autoplayTimeout = $arSettings["AUTOPLAY_TIMEOUT"]["VALUE"] ? $arSettings["AUTOPLAY_TIMEOUT"]["VALUE"] : 5000;
$animateOut = $arSettings["ANIMATE_OUT"]["VALUE"] != "none" ? "'".$arSettings["ANIMATE_OUT"]["VALUE"]."'" : "false";
$animateIn = $arSettings["ANIMATE_IN"]["VALUE"] != "none" ? "'".$arSettings["ANIMATE_IN"]["VALUE"]."'" : "false";

$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.css");
$APPLICATION->AddHeadString("
	<style type='text/css'>
		.owl-carousel .animated{
			-webkit-animation-duration:".$smartSpeed."ms;
			animation-duration:".$smartSpeed."ms;
		}
	</style>
", true);
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/owlCarousel/animate.min.css");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/owlCarousel/owl.carousel.min.js");
$APPLICATION->AddHeadString("
	<script type='text/javascript'>		
		$(function() {
			$('.slider').owlCarousel({
				items: 1,
				loop: ".$loop.",
				nav: true,
				navText: ['<i class=\"fa fa-angle-left\"></i>', '<i class=\"fa fa-angle-right\"></i>'],				
				autoplay: true,
				autoplayTimeout: ".$autoplayTimeout.",			
				autoplayHoverPause: true,
				smartSpeed: ".$smartSpeed.",
				responsiveRefreshRate: 0,
				animateOut: ".$animateOut.",
				animateIn: ".$animateIn.",
				navContainer: '.slider'
			});
		});		
	</script>
", true);?>