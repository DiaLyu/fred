<?php

namespace E05idiip\Socials;

use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Kint\Kint;
use E05idiip\Table\Netnowork\NetnoworkTable;
use E05idiip\Table\Netnowork\DescrTable;
use \Bitrix\Main\Application;

class SocialNetworks{

    public static function addEventNetwork(&$arFields){
       
        $connection = Application::getConnection();
        $network_link = SocialNetworks::choiceLinkName($arFields['NAME']); // получение ссылки по названию соцсети
        
        $new_elem = [
            'ELEMENT_ID' => $arFields['ID'],
            'LINK' =>  $network_link[0],
            'COLOR' =>  $network_link[1]
        ];
        $new_descr = [
            'ELEMENT_ID' => $arFields['ID'],
            'DESCRIPTION' => $network_link[2]
        ];

        $connection->startTransaction();
        try{
            $res = NetnoworkTable::add($new_elem);
            if($network_link[2] == ""){
                throw new \Exception('В descr ничего нет');
            }
            $res = DescrTable::add($new_descr);
            $connection->commitTransaction();
        }
        catch(\Throwable $e){
            $connection->rollbackTransaction();
        }
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
                "color" => "#DC143C",
                "description" => "«Твиттер» (Twitter) — американский сервис микроблогов и социальная сеть, в которой пользователи публикуют сообщения, известные как «твиты», и взаимодействуют с ними"
            ],
            1 => [
                "link" => "https://vk.com/",
                "names" => ["вконтакте", "вк", "vkontakte", "vk"],
                "color" => "#0000FF",
                "description" => "«ВКонта́кте» — российская социальная сеть со штаб-квартирой в Санкт-Петербурге. Сайт доступен на 82 языках; особенно популярен среди русскоязычных пользователей."
            ],
            2 => [
                "link" => "https://en.wikipedia.org/wiki/Instagram",
                "names" => ["инстаграм", "instagram"],
                "color" => "#C71585",
                "description" => "Instagram[a] is an American photo and video sharing social networking service owned by Meta Platforms. It allows users to upload media that can be edited with filters, be organized by hashtags, and be associated with a location via geographical tagging. "
            ],
            3 => [
                "link" => "https://t.me/",
                "names" => ["телеграм", "telegram"],
                "color" => "#87CEEB",
                "description" => "Telegram (от др.-греч. τῆλε «далеко» + др.-греч. γράμμα «запись») — кроссплатформенная система мгновенного обмена сообщениями (мессенджер) с функциями обмена текстовыми, голосовыми и видеосообщениями, а также стикерами, фотографиями и файлами многих форматов[5]. "
            ],
            4 => [
                "link" => "https://en.wikipedia.org/wiki/Facebook",
                "names" => ["фейсбук", "facebook"],
                "color" => "#4169E1",
                "description" => "Facebook, Inc. IPO. Facebook - это онлайн-социальная сеть, принадлежащая американскому технологическому гиганту Meta Platforms."
            ]
        ];

        $property_link = "";
        $property_color = "";
        $property_descr = "";
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
                    $property_descr = $network["description"];
                }
            }
        }

        return [$property_link, $property_color, $property_descr];
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
    }

}

?>