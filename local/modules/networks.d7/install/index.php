<?
 
IncludeModuleLangFile(__FILE__);
 
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Config as Conf;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Entity\Base;
use Networks\D7\NetworksTable;
 
Loc::loadMessages(__FILE__);
Class networks_d7 extends CModule
{
 
    var $MODULE_ID = "networks.d7";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $errors;
    var $exclusionAdminFiles;
 
    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__.'version.php');

        $this->exclusionAdminFiles=array(
            '..',
            '.',
            'menu.php'
        );

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = "Социальные сети D7";
        $this->MODULE_DESCRIPTION = "Социальные сети компании";
    }
 
    function InstallFiles($arParams = array())
    {
        CopyDirFiles($this->GetPath()."/install/components", $_SERVER["DOCUMENT_ROOT"]."/locsl/components", true, true);

        if(\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath()."/admin"))
        {
            CopyDirFiles($this->GetPath().'/install/admin', $_SERVER["DOCUMENT_ROOT"].'/bitrix/admin');
            if($dir = opendir($path))
            {
                while(false !== $item = readdir($dir)){
                    if(in_array($item, $this->exclusionAdminFiles))
                        continue;
                    file_put_contents($_SERVER["DOCUMENT_ROOT"].'/bitrix/admin/'.$this->MODULE_ID.'_'.$item,
                        '<'.'? require($_SERVER["DOCUMENT_ROOT"]."'.$this->GetPath(true).'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }
        return true;
    }

    function UnInstallFiles(){
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"]."/local/components/networks");

        if(\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath()."/admin")){
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"].$this->GetPath().'/install/admin/', $_SERVER["DOCUMENT_ROOT"].'/bitrix/admin');
            if($dir = opendir($path))
            {
                while(false !== $item = readdir($dir)){
                    if(in_array($item, $this->exclusionAdminFiles))
                        continue;
                    \Bitrix\Main\IO\File::deleteFile($_SERVER["DOCUMENT_ROOT"].'/bitrix/admin'.$this->MODULE_ID.'_'.$this);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        if($this->isVersionD7()){
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();

            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
        }
        else{
            $APPLICATION->ThrowException(Loc::getMessage('NETWORK_D7_INSTALL_ERROR_VERSION'));
        }
        $APPLICATION->IncludeAdminFile(Loc::getMessage("NETWORK_D7_INSTALL_TITLE"), $this->GetPath()."/install/step.php");
    }
 
    function DoUninstall()
    {
        global $APPLICATION;
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if($request["step"]<2){
            $APPLICATION->IncludeAdminFile(Loc::getMessage("NETWORK_D7_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep1.php");
        } 
        elseif ($request["step"] == 2){
            $this->UnInstallEvents();
            $this->UnInstallFiles();

            if($request["savedata"] != 'Y'){
                $this->UnInstallDB();
            }

            ModuleManager::unRegisterModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(Loc::getMessage("NETWORK_D7_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep2.php");
        }
    }

    public function GetPath($notDocumentRoot=false)
    {
        if ($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(),'', dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }
 
    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        if(!Application::getConnection(NetworksTable::getConnectionName())->isTableExists(
            Base::getInstance('\Networks\D7\NetworksTable')->getDBTableName()
        ))
        {
            Base::getInstance('\Networks\D7\NetworksTable')->createDbTable();
        }
    }
 
    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        Application::getConnection(\Networks\D7\NetworksTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('\Networks\D7\NetworksTable')->getDBTableName());
        Option::delete($this->MODULE_ID);
    }
 
    function InstallEvents()
    {
        return true;
    }
 
    function UnInstallEvents()
    {
        return true;
    }
}