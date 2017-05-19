<?IncludeModuleLangFile(__FILE__);

class altop_elastostart extends CModule {
	const solutionName	= "elastostart"; 	
	const partnerName = "altop"; 
	const moduleClass = "CElastoStart"; 
	
	var $MODULE_ID = "altop.elastostart";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "R";

	function altop_elastostart() {
		$arModuleVersion = array();
		
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");
		
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("ELASTOSTART_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("ELASTOSTART_MODULE_DESC");
		$this->PARTNER_NAME = GetMessage("ELASTOSTART_PARTNER");
		$this->PARTNER_URI = GetMessage("ELASTOSTART_PARTNER_URI");
	}
	
	function checkValid() {
		return true;
	}

	function InstallDB($install_wizard = true) {
		global $DB, $DBType, $APPLICATION;

		RegisterModule($this->MODULE_ID);
		COption::SetOptionString($this->MODULE_ID, "GROUP_DEFAULT_RIGHT", $this->MODULE_GROUP_RIGHTS);
		
		RegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, $this->moduleClass, "showPanel");
		if(preg_match("/.bitrixlabs.ru/", $_SERVER["HTTP_HOST"])){
			RegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, $this->moduleClass, "correctInstall");
		}
		
		return true;
	}

	function UnInstallDB($arParams = array()) {
		global $DB, $DBType, $APPLICATION;		
		
		UnRegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, $this->moduleClass, "showPanel");
		
		COption::RemoveOption($this->MODULE_ID, "GROUP_DEFAULT_RIGHT");
		UnRegisterModule($this->MODULE_ID);

		return true;
	}

	function InstallEvents() {
		return true;
	}

	function UnInstallEvents() {
		return true;
	}

	function InstallPublic() {
	}
	
	function InstallFiles() {		
		CopyDirFiles(__DIR__."/components/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		CopyDirFiles(__DIR__."/php_interface/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface", true, true);
		CopyDirFiles(__DIR__."/wizards/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards", true, true);
		
		if(preg_match("/.bitrixlabs.ru/", $_SERVER["HTTP_HOST"])){
			@set_time_limit(0);
			include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/include.php");
			CFileMan::DeleteEx(array("s1", "/bitrix/modules/".$this->MODULE_ID."/install/wizards"));
		}

		return true;
	}

	function UnInstallFiles() {		
		DeleteDirFilesEx("/bitrix/wizards/".self::partnerName."/".self::solutionName."/");

		return true;
	}

	function DoInstall(){
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();

		$APPLICATION->IncludeAdminFile(GetMessage("ELASTOSTART_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/step.php");
	}

	function DoUninstall(){
		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
		$APPLICATION->IncludeAdminFile(GetMessage("ELASTOSTART_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/unstep.php");
	}
}?>