<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$APPLICATION->SetTitle('Изменить ссылку');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
// Получаем ID элемента из параметра запроса
$elementId = intval($_GET['ID']);

$result = $DB->Query("SELECT * FROM b_seo_linktune_links WHERE ID = $elementId");

$arElement = $result->Fetch();

$name           = $arElement['NAME'];
$title          = $arElement['TITLE'];
$descripotion   = $arElement['DESCRIPTION'];
$canonical      = $arElement['CANONICAL'];

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_bitrix_sessid()) {
    if (isset($_POST['NAME'])) {
        $fields['NAME'] = '"' . $_POST['NAME'] . '"';
    }

    if (isset($_POST['TITLE'])) {
        $fields['TITLE'] = '"' . $_POST['TITLE'] . '"';
    }

    if (isset($_POST['DESCRIPTION'])) {
        $fields['DESCRIPTION'] = '"' . $_POST['DESCRIPTION'] . '"';
    }
    
    if (isset($_POST['CANONICAL'])) {
        $fields['CANONICAL'] = '"' . $_POST['CANONICAL'] . '"';
    }

    $result = $DB->Update('b_seo_linktune_links', $fields, 'WHERE ID = ' . $elementId);
    
    LocalRedirect('/bitrix/admin/seo_linktune_list.php');
}

$tabControl = new CAdminTabControl(
    'seo_linktune_edit_tab_control',
    [
        [
            'DIV' => 'edit1',
            'TAB' => 'Редактирование элемента',
            'ICON' => 'seo_linktune_edit',
            'TITLE' => 'Редактирование элемента',
        ],
    ]
);

$tabControl->Begin();
?>

<form method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="ID" value="<?=$elementId?>">
    <? $tabControl->BeginNextTab(); ?>
    <tr>
        <td width="20%" class="adm-detail-content-cell-l">Ссылка:</td>
        <td width="80%" class="adm-detail-content-cell-r"><input style="width: 90%" type="text" name="NAME" value="<?=$arElement['NAME']?>"></td>
    </tr>
    <tr>
        <td width="20%" class="adm-detail-content-cell-l">Title:</td>
        <td width="80%" class="adm-detail-content-cell-r"><input style="width: 90%" type="text" name="TITLE" value="<?=$arElement['TITLE']?>"></td>
    </tr>
    <tr>
        <td width="20%" class="adm-detail-content-cell-l">Description:</td>
        <td width="80%" class="adm-detail-content-cell-r"><input style="width: 90%" type="text" name="DESCRIPTION" value="<?=$arElement['DESCRIPTION']?>"></td>
    </tr>
    <tr>
        <td width="20%" class="adm-detail-content-cell-l">Canonical:</td>
        <td width="80%" class="adm-detail-content-cell-r"><input style="width: 90%" type="text" name="CANONICAL" value="<?=$arElement['CANONICAL']?>"></td>
    </tr>
    <? $tabControl->EndTab(); ?>

    <?
    $tabControl->Buttons([
		'back_url' => '/bitrix/admin/seo_linktune_list.php'
    ]);
    ?>
</form>

<? $tabControl->End(); ?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>