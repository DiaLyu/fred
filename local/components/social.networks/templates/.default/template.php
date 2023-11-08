<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 
// require_once __DIR__ . 'vendor/autoload.php';
require($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/vendor/autoload.php");
$this->addExternalCss("/local/css/socials/styles.css");
?>

<div class="social-container">
    <?if (!empty($arResult)){
        foreach ($arResult as $arItem){?>
            <div class="social-container-item" style="background: <?=$arItem["COLOR"]?>">
                <a href="<?= isset($arItem["LINK"]) ? $arItem["LINK"] : '#'?>" class="social-container-link">
                    <img src="<?= $arItem["ICON_NETWORK"]['VALUE']?>" alt="<?= $arItem["NAME"]?>">               
                </a>
                <p><?= $arItem["DESCRIPTION"] ? $arItem["DESCRIPTION"] : "пока нет";?></p>
            </div>
    <?     // echo '<br>'.var_dump($arItem).'<br>';
    }
    // E05idiip\Helpers\Debug::dump($arParams);
    }?>
</div>