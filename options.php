<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight("seo_linktune") < "R")
{
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

Loader::includeModule("seo_linktune");

$tabControl = new CAdminTabControl("tabControl", [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("MAIN_TAB_SET"),
        "ICON" => "ib_settings",
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"),
    ],
]);

if ($_SERVER["REQUEST_METHOD"] == "POST" && check_bitrix_sessid())
{
    // Обработка формы и сохранение данных

    if ($_POST["apply"])
    {
        // Обработка сохранения данных
    }

    if ($_POST["delete"])
    {
        // Обработка удаления данных
    }
}

$tabControl->Begin();
?>

<form method="post" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&amp;lang=<?= LANGUAGE_ID ?>">
    <?= bitrix_sessid_post() ?>
    <? $tabControl->BeginNextTab(); ?>

        <tr>
            <td width="40%" align="right" valign="top">
                <label for="element_name"><?= Loc::getMessage("ELEMENT_NAME_LABEL") ?>:</label>
            </td>
            <td width="60%" align="left" valign="top">
                <input type="text" name="element_name" id="element_name" size="50" value="<?= htmlspecialcharsbx($element["NAME"]) ?>">
            </td>
        </tr>
        <!-- Добавьте другие поля, если необходимо -->

    <? $tabControl->Buttons(["disabled" => false, "back_url" => "seo_linktune_list.php?lang=" . LANGUAGE_ID]); ?>
    <? $tabControl->End(); ?>
</form>