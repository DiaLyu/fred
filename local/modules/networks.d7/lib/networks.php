<?php
namespace Networks\D7;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type;

class NetworksTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
      return 'social_networks';
    }

    public static function getConnectionName()
    {
        return 'default';
    }

    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('DATA_ENTITY_ID_FIELD'),
            ),
            'ELEMENT_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('DATA_ENTITY_ELEMENT_ID_FIELD'),
                'validation' => function(){
                    return array(
                        new Entity\Validator\Unique,
                    );
                }
            ),
            'LINK' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DATA_ENTITY_LINK_FIELD'),
            ),
            'COLOR' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DATA_ENTITY_COLOR_FIELD'),
            ),
        );
    }
}