<?php
namespace E05idiip\Descr;

use Bitrix\Main\Entity;

class DescrTable extends Entity\DataManager{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
      return 'description';
    }

    public static function getUfId()
    {
        return 'DESCRIPTION';
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
            new Entity\StringField('DESCRIPTION', array(
                'required' => true,
                'validation' => function() {
                    return array(
                        new Entity\Validator\Length(2, 255)
                    );
                }
            ))
        );
    }
}