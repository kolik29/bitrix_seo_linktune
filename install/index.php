<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use seo_linktune\SeoLinkTune;

class seo_linktune extends CModule
{
    var $MODULE_ID;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    function __construct()
    {
        $this->MODULE_ID = 'seo_linktune';
        $this->MODULE_VERSION = '0.0.1';
        $this->MODULE_VERSION_DATE = '2023-10-05';
        $this->MODULE_NAME = 'SEO LinkTune';
        $this->MODULE_DESCRIPTION = 'Тонкая настрока SEO для отдельных страниц';
    }

    function DoInstall()
    {
        $this->InstallEvents();
        $this->InstallDB();
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
        $this->UnInstallEvents();
        UnRegisterModule($this->MODULE_ID);
    }

    public function InstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->registerEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            '\SeoLinkTune\CSeoLinkTune',
            'onEndBufferContentHandler'
        );

        RegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, 'CSeoLinkTune', 'OnBeforePrologHandler');
    }

    public function UnInstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unregisterEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            '\SeoLinkTune\CSeoLinkTune',
            'onEndBufferContentHandler'
        );

        UnRegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, 'CSeoLinkTune', 'OnBeforePrologHandler');
    }

    public function InstallDB()
    {
        global $DB;
        
        $DB->Query('CREATE TABLE IF NOT EXISTS `b_seo_linktune_links` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `NAME` text NOT NULL,
            `TITLE` text NOT NULL,
            `DESCRIPTION` text NOT NULL,
            `CANONICAL` text NOT NULL,
            PRIMARY KEY (`ID`)
        )');
    }

    public function InstallFiles()
    {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/seo_linktune/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
    }
}