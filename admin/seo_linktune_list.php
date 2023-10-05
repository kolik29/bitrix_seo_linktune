<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$APPLICATION->SetTitle('Список страниц');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
$oFilter = new CAdminFilter('seo_linktune',);

$oFilter->Begin();
?>
    <tr>
        <td>Ссылка:</td>
        <td><input type="text" name="filter_name" value="<?=htmlspecialcharsbx($filter_name)?>" size="40"><?=ShowFilterLogicHelp()?></td>
    </tr>
<?
$oFilter->Buttons(
    array(
        'table_id' => $sTableID,
        'url' => $APPLICATION->GetCurPage(),
        'form' => 'find_form'
    )
);
$oFilter->End();
?>
</form>

<?
$adminList = new CAdminList('seo_linktune_list');

$adminList->AddHeaders([
    array('id' => 'ID', 'content' => 'ID', 'sort' => 'id', 'default' => true, 'width' => '50px'),
    array('id' => 'NAME', 'content' => 'Ссылка', 'sort' => 'name', 'default' => true),
]);

$data = [
    ['ID' => 0, 'NAME' => 'test1']
];

$adminList->AddAdminContextMenu([
    [
        'ICON' => 'btn_new',
        'TEXT' => 'Добавить ссылку',
        'LINK' => '/bitrix/admin/seo_linktune_add.php',
    ]
], false, false);

global $DB;

$result = $DB->Query('SELECT ID, NAME FROM b_seo_linktune_links'); 

while ($arElement = $result->Fetch()) {
    $row =& $adminList->AddRow($item['ID'], $arElement);

    $arActions = [
        [
            'ICON' => 'edit',
            'DEFAULT' => true,
            'TEXT' => 'Изменить',
            'ACTION' => $adminList->ActionRedirect('/bitrix/admin/seo_linktune_edit.php?ID=' . $arElement['ID'])
        ],
        [
            'ICON' => 'delete',
            'DEFAULT' => true,
            'TEXT' => 'Удалить',
            'ACTION' => 'if (confirm("Удалить ссылку?")) {' . $adminList->ActionDoGroup($arElement['ID'], 'delete') . '; setTimeout(() => location.reload(), 100); }'
        ]
    ];

    $row->AddActions($arActions);
}

if (($arID = $adminList->GroupAction())) {
    if ($_REQUEST['action'] == 'delete') {
        $DB->Query("DELETE FROM b_seo_linktune_links WHERE ID = $arID[0]");
    }
}
?>

<?
$adminList->DisplayList();
?>

<style>
    .adm-list-table-header .adm-list-table-cell:nth-child(2),
    .adm-list-table-row .adm-list-table-cell:nth-child(2) {
        width: 50px;
    }
</style>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>