<? 
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$aMenu = array(
    array(
        'parent_menu' => 'global_menu_content',
        'sort' => 400,
        'text' => "Социальные сети",
        'title' => "",
        'url' => 'networks_table.php?lang=ru&table_name=social_networks',
        'items_id' => 'menu_references'
    )
);
return $aMenu;

?>