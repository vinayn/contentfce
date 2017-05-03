<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Provider extension for DITA content');
\FluidTYPO3\Flux\Core::registerProviderExtensionKey('Dustbin.Contentfce', 'Page');
\FluidTYPO3\Flux\Core::registerProviderExtensionKey('Dustbin.Contentfce', 'Content');



/**
 * Include Backend Module
 */
if (
	TYPO3_MODE === 'BE' &&	
	!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)
) {
			\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
				'Dustbin.Contentfce',
				'web',
				'tx_contentfce_m1',
				'',
				array(
					'Ditaexport' => 'index',
				),
				array(
					'access' => 'user,group',
					'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/ditalogo.png',
					'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xlf',
				)
			);

  }





