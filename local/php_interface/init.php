<?php
require_once __DIR__ . '/vendor/autoload.php';
use Kint\Kint;
// E05idiip\Helpers\Debug::test();

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ['E05idiip\\Socials\\SocialNetworks', 'updateEventNetwork']);
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", ['E05idiip\\Socials\\SocialNetworks', 'addEventNetwork']);


?>