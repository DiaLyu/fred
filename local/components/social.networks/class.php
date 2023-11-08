<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;

use E05idiip\Table\Netnowork\NetnoworkTable;

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
            $query = new Bitrix\Main\Entity\Query( Bitrix\Iblock\Elements\ElementNetworkapiTable::getEntity());
            $query->registerRuntimeField(
                'network_field',
                [
                    'data_type' => 'E05idiip\Table\Netnowork\NetnoworkTable',
                    'reference' => ['=this.ID' => 'ref.ELEMENT_ID'],
                    'join_type' => 'LEFT'
                ]
            );
            $query->registerRuntimeField(
                'network_desc',
                [
                    'data_type' => 'E05idiip\Table\Netnowork\DescrTable',
                    'reference' => ['=this.ID' => 'ref.ELEMENT_ID'],
                    'join_type' => 'LEFT'
                ]
            );
            $query->setSelect(['ID', 'NAME', 'ICON_NETWORK', 'network_field.ID', 'network_field.ELEMENT_ID', 'network_field.COLOR', 'network_field.LINK', 'network_desc.DESCRIPTION']);
            $result = $query->exec();
            while ($row = $result->fetch()) {
                $row['ICON_NETWORK']['VALUE'] = CFile::GetPath($row['IBLOCK_ELEMENTS_ELEMENT_NETWORKAPI_ICON_NETWORK_VALUE']);
                $row['LINK'] = $row['IBLOCK_ELEMENTS_ELEMENT_NETWORKAPI_network_field_LINK'];
                $row['COLOR'] = $row['IBLOCK_ELEMENTS_ELEMENT_NETWORKAPI_network_field_COLOR'];
                $row['DESCRIPTION'] = $row["IBLOCK_ELEMENTS_ELEMENT_NETWORKAPI_network_desc_DESCRIPTION"];
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

        /*
        if ($this->startResultCache($this->arParams['CACHE_TIME'])) {
            $res = \Bitrix\Iblock\Elements\ElementNetworkapiTable::getList([
                'select' => ["ID", "NAME", "IBLOCK_ID"],    // "LINK_NETWORK", "ICON_NETWORK"
                "filter" => ["ACTIVE" => "Y"],
                "order" => [strtoupper($this->arParams["ELEMENT_SORT_FIELD"]) => strtoupper($this->arParams["ELEMENT_SORT_ORDER"])]
            ]);
            
            while ($arItem = $res->fetch()) {
                $resProperty = \CIBlockElement::getProperty(
                    $arItem['IBLOCK_ID'],
                    $arItem['ID']
                );
                while($arProperty = $resProperty->Fetch()){
                    if ($arProperty['PROPERTY_TYPE'] == "F"){
                        $arProperty['VALUE'] = CFile::GetPath($arProperty['VALUE']);
                    }
                    $arPropertyDetails = [
                        'VALUE' => $arProperty['VALUE']
                    ];
                    $arItem['PROPERTIES'][$arProperty['CODE']] = $arPropertyDetails;
                }
                $this->arResult[] = $arItem;
            }
            
            if (isset($this->arResult)) {
                $this->SetResultCacheKeys(
                    array("ID", "NAME", "IBLOCK_ID", "PROPERTIES")
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
        */
    }
}