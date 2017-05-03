<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

\FluidTYPO3\Flux\Core::registerProviderExtensionKey('Dustbin.Contentfce', 'Page');
\FluidTYPO3\Flux\Core::registerProviderExtensionKey('Dustbin.Contentfce', 'Content');

// ext_localconf.php
/*
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', '
    plugin.tx_contentfce.view {
	
        templateRootPath = EXT:contentfce/Resources/Private/Templates/
        partialRootPath = EXT:contentfce/Resources/Private/Partials/
        layoutRootPath = EXT:contentfce/Resources/Private/Layouts/
    }
');
*/
