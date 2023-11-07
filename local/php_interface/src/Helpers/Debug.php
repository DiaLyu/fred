<?php

namespace E05idiip\Helpers;

class Debug{

    /*
    *
    * @param string
    * @param mixed
    *
    * @return int|false

    */
    
    public static function l2f($data, $filePath = '/tlog.txt'){
         return file_put_contents(
            $_SERVER['DOCUMENT_ROOT'] . $filePath,
            print_r($data, true) . PHP_EOL, FILE_APPEND |LOCK_EX
         );
    }

    public static function dump($var, $die = false, $all = false){
        global $USER;
        if(($USER->GetID() == 1) || ($all == true))
        {
            echo "<pre>";
            print_r($var);
            echo "<pre>";
        }

        if ($die){
            die;
        }
    }

    public static function test(){
        echo "test";
    }

}