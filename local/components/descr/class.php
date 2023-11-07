<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;

use E05idiip\Descr\DescrTable;

class CIBlockSocialNetworks extends CBitrixComponent
{
    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->getResult();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }
    
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }
    
    protected function checkModules()
    {
        if (!Loader::includeModule('iblock'))
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    }
    
    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        } else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }   
        return $arParams;
    }

    protected function getResult()
    {
        if ($this->startResultCache()) {
            $query = DescrTable::getList(
                array(
                    'select' => ['ID', 'DESCRIPTION']
                )
            );

            while ($row = $query->fetch()) {
                $this->arResult[] = $row;
            }

            if (isset($this->arResult)) {
                $this->SetResultCacheKeys(
                    []
                );
                $this->IncludeComponentTemplate();
            } else { 
                $this->AbortResultCache();
                \Bitrix\Iblock\Component\Tools::process404(
                    Loc::getMessage('PAGE_NOT_FOUND'),
                    true,
                    true
                );
            }
        }
    }
}