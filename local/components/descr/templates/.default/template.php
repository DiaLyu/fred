<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 
// require_once __DIR__ . 'vendor/autoload.php';
require($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/vendor/autoload.php");
$this->addExternalCss("/local/css/socials/styles.css");
?>

<div class="table-container">
    <table>
            <?if (!empty($arResult)){
                foreach ($arResult as $arItem){?>
                    <tr>
                        <td><?echo $arItem["ID"];?></td>
                        <td><?echo $arItem["DESCRIPTION"];?></td>
                    <tr>
            <?
            }
            // E05idiip\Helpers\Debug::dump($arParams);
            }?>
    </table>
</div>