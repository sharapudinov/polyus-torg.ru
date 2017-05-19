<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");

class SelectSiteStep extends CSelectSiteWizardStep {
	function InitStep() {
		parent::InitStep();				
		
		$wizard =& $this->GetWizard();
		$wizard->solutionName = "elastostart";
	}
}

class SelectTemplateStep extends CSelectTemplateWizardStep {
	function InitStep() {		
		parent::InitStep();		
		$this->SetNextStep("site_settings");
		
		$wizard =& $this->GetWizard();
		$wizard->SetDefaultVars(Array("templateID" => "elasto_start"));
	}	
}

class SiteSettingsStep extends CSiteSettingsWizardStep {
	function InitStep() {
		parent::InitStep();
		$this->SetPrevStep("select_template");		
		
		$wizard =& $this->GetWizard();
		$wizard->solutionName = "elastostart";
		$siteID = $wizard->GetVar("siteID");
		$obSite = new CSite;
		$arSite = $obSite->GetByID($siteID)->Fetch();
		
		$wizard->SetDefaultVars(
			Array(
				"siteName" => GetMessage("WIZ_COMPANY_NAME_DEF"),
				"siteEmail" => "info@".$_SERVER["SERVER_NAME"]				
			)
		);
	}

	function ShowStep() {
		$wizard =& $this->GetWizard();		
		
		/***SITE_NAME***/
		$this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'.GetMessage("WIZ_COMPANY_NAME").'</div>';
		$this->content .= $this->ShowInputField("text", "siteName", Array("id" => "siteName", "class" => "wizard-field")).'</div>';		

		/***SITE_EMAIL***/
		$this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'.GetMessage("WIZ_COMPANY_EMAIL").'</div>';
		$this->content .= $this->ShowInputField("text", "siteEmail", Array("id" => "siteEmail", "class" => "wizard-field")).'</div>';		
		
		/***DEMO_DATA***/
		$this->content .= $this->ShowHiddenField("installDemoData", "Y");		
	}
}

class DataInstallStep extends CDataInstallWizardStep {
	function CorrectServices(&$arServices) {
		$wizard =& $this->GetWizard();
		if($wizard->GetVar("installDemoData") != "Y") {}
	}
}

class FinishStep extends CFinishWizardStep {}?>