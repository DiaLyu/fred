<?
if($INCLUDE_FROM_CACHE!='Y')return false;
$datecreate = '001699420961';
$dateexpire = '001699424561';
$ser_content = 'a:2:{s:7:"CONTENT";s:0:"";s:4:"VARS";a:1:{s:6:"query1";s:204:"use Bitrix\\Iblock\\ElementPropertyTable;
use Bitrix\\Main\\Loader;
use E05idiip\\Table\\Netnowork\\DescrTable;

Loader::IncludeModule(\'iblock\');

print_r(DescrTable::getEntity()->compileDbTableStructureDump());";}}';
return true;
?>