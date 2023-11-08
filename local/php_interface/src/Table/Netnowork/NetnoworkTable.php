<?php
namespace E05idiip\Table\Netnowork;

use Bitrix\Main\Entity;

class NetnoworkTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
      return 'social_networks';
    }

    public static function getUfId()
    {
        return 'SOCIAL_NETWORKS';
    }

    public static function getConnectionName()
    {
        return 'default';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\IntegerField('ELEMENT_ID'),
            new Entity\StringField('LINK'),
            new Entity\StringField('COLOR')
        );
    }
}