<?
if($INCLUDE_FROM_CACHE!='Y')return false;
$datecreate = '001699347464';
$dateexpire = '001699351064';
$ser_content = 'a:2:{s:7:"CONTENT";s:0:"";s:4:"VARS";a:1:{s:6:"query1";s:194:"use Bitrix\\Iblock\\ElementPropertyTable;
use Bitrix\\Main\\Loader;
use E05idiip\\Descr\\DescrTable;

Loader::IncludeModule(\'iblock\');

print_r(DescrTable::getEntity()->compileDbTableStructureDump());";}}';
return true;
?>