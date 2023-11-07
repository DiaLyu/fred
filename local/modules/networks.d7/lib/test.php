<?php
 
namespace Networks\D7;
 
use Networks\D7\NetworksTable;
 
class Test{
 
    public static function get($cond) {
        $result = NetworksTable::getList(
            array('select' => array('*')
        ));
        $row = $result->fetch();
        print "<pre>"; print_r($row); print "</pre>";
        return $row;
    }
 
}