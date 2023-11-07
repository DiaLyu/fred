<?php

namespace E05idiip\Socials;

use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Kint\Kint;
use E05idiip\Netnowork\NetnoworkTable;
use \Bitrix\Main\Application;

class SocialNetworks{

    public static function addEventDescr(&$arFields){
        // $connection = Application::getConnection();
        // $connection->startTransaction();
        // try {
        //     self::makeSomeAction();
        //     $connection->commitTransaction();
        // } catch (Throwable $e) {
        //     $connection->rollbackTransaction();
        //     LogHandler::writeMessage($e);
        // }

        Debug::writeToFile(
            [
              'text'   => "Свойства инфоблока",
              'fields' => $arFields
            ],
            date('d.m.Y'),
            "iblockUpdatepPropertiesClass.log"
          ); 
    }

    public static function addEventNetwork(&$arFields){
        $network_link = SocialNetworks::choiceLinkName($arFields['NAME']); // получение ссылки по названию соцсети

        $new_elem = [
            'ELEMENT_ID' => $arFields['ID'],
            'LINK' =>  $network_link[0],
            'COLOR' =>  $network_link[1]
        ];

        $res = NetnoworkTable::add($new_elem);
    }

    public static function updateEventNetwork(&$arFields){
        $network_link = SocialNetworks::choiceLinkName($arFields['NAME']); // получение ссылки по названию соцсети

        // $arFields['PROPERTY_VALUES'][31][2109]['VALUE'] = 'dasdasdasd';
        // unset($arFields['PROPERTY_VALUES'][31]);
        // die;
        //создать новое свойство для размещения в ней ссылки
        self::changePropertyField($arFields['ID'], $network_link);
        //-----
    }

    public static function choiceLinkName($field_name){
        $network_links = [
            0 => [
                "link" => "https://ru.wikipedia.org/wiki/Твиттер",
                "names" => ["твиттер", "twitter"],
                "color" => "#DC143C"
            ],
            1 => [
                "link" => "https://vk.com/",
                "names" => ["вконтакте", "вк", "vkontakte", "vk"],
                "color" => "#0000FF"
            ],
            2 => [
                "link" => "https://en.wikipedia.org/wiki/Instagram",
                "names" => ["инстаграм", "instagram"],
                "color" => "#C71585"
            ],
            3 => [
                "link" => "https://t.me/",
                "names" => ["телеграм", "telegram"],
                "color" => "#87CEEB"
            ],
            4 => [
                "link" => "https://en.wikipedia.org/wiki/Facebook",
                "names" => ["фейсбук", "facebook"],
                "color" => "#4169E1"
            ]
        ];

        $property_link = "";
        $property_color = "";
        foreach ($network_links as $network){
            foreach($network["names"] as $name){
                if(preg_match("/[А-Яа-я]/", $name)){
                    $name = mb_strtolower($name);
                } else{
                    $name = strtolower($name);
                }
                if(preg_match("/[А-Яа-я]/", $field_name)){
                    $field_name = mb_strtolower($field_name);
                } else{
                    $field_name = strtolower($field_name);
                }
                
                if (strcasecmp(trim($field_name),$name) == 0){
                    $property_link = $network["link"];
                    $property_color = $network["color"];
                }
            }
        }

        return [$property_link, $property_color];
    }

    public static function changePropertyField($element_id, $network_link){

        Loader::IncludeModule('iblock');

        $arProps = NetnoworkTable::getList([
            'select' => ['ID', 'ELEMENT_ID', 'LINK', 'COLOR'],
            'filter' => ['ELEMENT_ID' => $element_id] 
        ])->fetch();

        if($arProps['ID']){
            $res = NetnoworkTable::update($arProps['ID'], [
                'LINK' => $network_link[0],
                'COLOR' => $network_link[1]
            ]);
        }

        // $prop = null;
        // if (!$res->isSuccess()) { $prop = "Ошибка ".$res->getErrorMessages(); } else { $prop = "Сработало ".$res->getId(); }
        
        // Debug::writeToFile(
        //     [
        //       'text'   => "Свойства инфоблока",
        //       'fields' => $arProps,
        //       '$network_link' => $network_link,
        //       'результат выполнения' => $prop,
        //       "IBLOCK_ELEMENT_ID" => $element_id, 
        //       'IBLOCK_PROPERTY_ID' => $arProps['ID'],
        //       'res' => $res
        //     ],
        //     date('d.m.Y'),
        //     "iblockUpdatepPropertiesClass.log"
        //   ); 
    }

}

?>