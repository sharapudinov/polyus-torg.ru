<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<div class="row gallery">			
	<?foreach($arResult["ITEMS"] as $arItem):?>				
		<div class="col-xs-6 col-sm-6 col-md-3">				
			<a class="gallery-item fancyimage" title="<?=$arItem['NAME'].(!empty($arItem['PREVIEW_TEXT']) ? '<br />'.$arItem['PREVIEW_TEXT'] : '');?>" href="<?=$arItem['DETAIL_PICTURE']['SRC']?>" data-fancybox-group="gallery">
				<span class="item-image"<?=(!empty($arItem["PREVIEW_PICTURE"]) ? " style='background-image:url(".$arItem["PREVIEW_PICTURE"]["SRC"].");'" : "");?>></span>
				<span class="item-caption-wrap">
					<span class="item-caption">
						<span class="item-title"><?=$arItem["NAME"]?></span>
						<?=(!empty($arItem["PREVIEW_TEXT"]) ? "<span class='item-text'>".$arItem["PREVIEW_TEXT"]."</span>" : "");?>
					</span>
				</span>
			</a>
		</div>
	<?endforeach;	
	if($arParams["DISPLAY_BOTTOM_PAGER"]):
		if(!empty($arResult["NAV_STRING"])):?>
			<div class="col-md-12">
				<?=$arResult["NAV_STRING"];?>
			</div>
		<?endif;
	endif;?>
</div>