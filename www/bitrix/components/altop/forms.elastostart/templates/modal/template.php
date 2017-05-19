<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();

$sComponentFolder = $this->__component->__path;?>

<div class="modal fade" id="<?=$arResult['IBLOCK_CODE']?>" tabindex="-1" role="dialog" aria-labelledby="<?=$arResult['IBLOCK_CODE']?>_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">						
				<div class="modal-title" id="<?=$arResult['IBLOCK_CODE']?>_label"><?=$arResult["IBLOCK_NAME"]?></div>
				<a href="javascript:void(0)" class="modal-close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></a>
			</div>
			<div class="modal-body">
				<form id="<?=$arResult['IBLOCK_CODE']?>_form" action="javascript:void(0)">					
					<div id="<?=$arResult['IBLOCK_CODE']?>_alert" class="alert" style="display:none;"></div>
					<?if(!empty($arResult["IBLOCK_DESCRIPTION"])):?>
						<span class="modal-caption"><?=$arResult["IBLOCK_DESCRIPTION"]?></span>
					<?endif;?>
					<input type="hidden" name="IBLOCK_CODE" value="<?=$arResult['IBLOCK_CODE']?>" />
					<input type="hidden" name="IBLOCK_NAME" value="<?=$arResult['IBLOCK_NAME']?>" />
					<input type="hidden" name="IBLOCK_ID" value="<?=$arParams['IBLOCK_ID']?>" />					
					<input type="hidden" name="USE_CAPTCHA" value="<?=$arParams['USE_CAPTCHA']?>" />
					<?if(is_array($arResult["FIELDS"])):?>
						<input type="hidden" name="FIELDS_STRING" value="<?=$arResult['FIELDS_STRING']?>" />
						<?foreach($arResult["FIELDS"] as $key => $arField):?>							
							<div class="form-group<?=(!empty($arField['HINT']) ? ' has-feedback' : '');?>">									
								<?if($arField["USER_TYPE"] != "HTML"):?>
									<input type="text" name="<?=$arField['CODE']?>" class="form-control" placeholder="<?=$arField['NAME']?>" />
								<?else:?>									
									<textarea name="<?=$arField['CODE']?>" class="form-control" rows="3" placeholder="<?=$arField['NAME']?>" style="height:<?=$arField['USER_TYPE_SETTINGS']['height']?>px; min-height:<?=$arField['USER_TYPE_SETTINGS']['height']?>px; max-height:<?=$arField['USER_TYPE_SETTINGS']['height']?>px;"></textarea>
								<?endif;
								if(!empty($arField["HINT"])):?>
									<i class="form-control-feedback fv-icon-no-has fa <?=$arField['HINT']?>"></i>
								<?endif;?>
							</div>							
						<?endforeach;
					endif;					
					if($arParams["USE_CAPTCHA"] == "Y"):
						$frame = $this->createFrame()->begin("");?>
							<div class="form-group captcha">
								<div class="pic">								
									<img class="captcha_img" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" width="100" height="36" alt="CAPTCHA" />
								</div>							
								<input type="text" maxlength="5" name="captcha_word" class="form-control" placeholder="<?=GetMessage('FORMS_MODAL_CAPTCHA_WORD')?>" />
								<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>" />
							</div>
						<?$frame->end();
					endif;?>
					<div class="form-group">
						<button type="submit" class="btn btn-primary"><?=GetMessage("FORMS_MODAL_SUBMIT")?></button>						
					</div>
				</form>
			</div>					
		</div>
	</div>
</div>

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		/***Mask***/		
		<?if(is_array($arResult["FIELDS"])):					
			foreach($arResult["FIELDS"] as $key => $arField):
				if($arField["CODE"] == "PHONE" && !empty($arResult["SETTINGS"]["PHONE_MASK"]["VALUE"])):?>
					$("#<?=$arResult['IBLOCK_CODE']?>_form").find("[name='PHONE']").inputmask("<?=$arResult['SETTINGS']['PHONE_MASK']['VALUE']?>");
				<?endif;
			endforeach;
		endif;?>
		
		/***Validation***/
		$("#<?=$arResult['IBLOCK_CODE']?>_form").formValidation({
			framework: "bootstrap",
			icon: {
				valid: "fa fa-check",
				invalid: "fa fa-times",
				validating: "fa fa-refresh"
			},			
			fields: {
				<?if(is_array($arResult["FIELDS"])):					
					foreach($arResult["FIELDS"] as $key => $arField):?>
						<?=$arField["CODE"]?>: {
							row: ".form-group",
							validators: {								
								<?if($arField["IS_REQUIRED"] == "Y"):?>
									notEmpty: {
										message: "<?=GetMessage('FORMS_MODAL_NOT_EMPTY_INVALID')?>"
									},
								<?endif;
								if($arField["CODE"] == "PHONE" && !empty($arResult["SETTINGS"]["VALIDATE_PHONE_MASK"]["VALUE"])):?>
									regexp: {
										message: "<?=GetMessage('FORMS_MODAL_REGEXP_INVALID')?>",										
										regexp: /<?=$arResult["SETTINGS"]["VALIDATE_PHONE_MASK"]["VALUE"]?>/
									},
								<?endif;?>
							}
						},						
					<?endforeach;
				endif;
				if($arParams["USE_CAPTCHA"] == "Y"):?>
					captcha_word: {
						row: ".form-group",
						validators: {
							notEmpty: {
								message: "<?=GetMessage('FORMS_MODAL_NOT_EMPTY_INVALID')?>"
							},
							remote: {
								type: "POST",
								url: "<?=$sComponentFolder?>/captcha.php",
								message: "<?=GetMessage('FORMS_MODAL_CAPTCHA_WRONG')?>",
								data: function() {
									return {
										captcha_sid: $("#<?=$arResult['IBLOCK_CODE']?>_form").find("[name='captcha_sid']").val()
									};
								},
								delay: 1000
							}
						}
					}
				<?endif;?>
			}
		}).on("success.form.fv", function(e) {
			/***Prevent default form submission***/
			e.preventDefault();
				
			var $form = $(e.target);			
				
			/***Send all form data to back-end***/
			$.ajax({
				url: "<?=$sComponentFolder?>/script.php",
				type: "POST",
				data: $form.serialize(),
				dataType: "json",
				success: function(response) {						
					/***Clear the form***/
					$form.formValidation("resetForm", true);
					
					/***Show the message***/
					if(!!response.result) {
						if(response.result == "Y") {							
							$("#<?=$arResult['IBLOCK_CODE']?>_alert").removeClass("alert-warning").addClass("alert-success").html("<?=GetMessage('FORMS_MODAL_ALERT_SUCCESS')?>").show();
						} else {
							$("#<?=$arResult['IBLOCK_CODE']?>_alert").removeClass("alert-success").addClass("alert-warning").html("<?=GetMessage('FORMS_MODAL_ALERT_WARNING')?>").show();
						}
					}

					/***Captcha***/
					<?if($arParams["USE_CAPTCHA"] == "Y"):?>
						if(!!response.captcha_code) {
							$("#<?=$arResult['IBLOCK_CODE']?>_form").find(".captcha_img").attr("src", "/bitrix/tools/captcha.php?captcha_sid=" + response.captcha_code);
							$("#<?=$arResult['IBLOCK_CODE']?>_form").find("[name='captcha_sid']").val(response.captcha_code);
						}
					<?endif;?>
				}
			});
		});				
	});	
	//]]>
</script>