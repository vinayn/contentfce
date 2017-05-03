<?php

namespace Dustbin\Contentfce\Utility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Dustbin\Contentfce\Contenttype\Accordion;
use Dustbin\Contentfce\Contenttype\NavigationList;
use Dustbin\Contentfce\Contenttype\Row;
use Dustbin\Contentfce\Contenttype\Table;
use Dustbin\Contentfce\Contenttype\Tabs;


class CheckContenttype {

	
	
	
	function rendercontentytype($PageDataholderObj)
	{
	


				
				
				//var_dump($PageDataholderObj);


				//die;
				if($PageDataholderObj->cRow['CType'] == 'fluidcontent_content')
				{
					
					
					//var_dump($PageDataholderObj->cRow['tx_fed_fcefile']);	
		
					

					switch($PageDataholderObj->cRow['tx_fed_fcefile'])
					{
					
						case 'Dustbin.Contentfce:Row.html' :
						
						
							
							if(!empty($PageDataholderObj->cRow['pi_flexform']))
							{
							
							
								
								
								 $RowObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Contenttype\Row');
								$content = $RowObj->renderRow($PageDataholderObj);
								
								
							
							}
				
								

							break;
							
							
							case 'Dustbin.Contentfce:Table.html' :
							
								
							
								
								 $TableObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Contenttype\Table');
								
								$content = $TableObj->renderTable($PageDataholderObj);


								//var_dump($content);

							break;
							
							case 'Dustbin.Contentfce:Tabs.html' :

								
								 
								 $TabsObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Contenttype\Tabs');
								
								$content = $TabsObj->renderTabs($PageDataholderObj);
								
							
							break;
							
							case 'Dustbin.Contentfce:Accordion.html' :
								
								
								 $TabsObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Contenttype\Accordion');
								
								$content = $TabsObj->renderAccordion($PageDataholderObj);
								
							
							break;
							
							case 'Dustbin.Contentfce:NavigationList.html' :
								
								
								 $NavigationListObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Contenttype\NavigationList');
								
								$content = $NavigationListObj->renderNavigationList($PageDataholderObj);
								
							
							break;
							case 'Dustbin.Contentfce:Paragraph.html' :
								
								
								 $ParagraphObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Contenttype\Paragraph');
								
								$content = $ParagraphObj->renderParagraph($PageDataholderObj);
								
							
							break;
							case 'Dustbin.Contentfce:Listitems.html' :
								
								
								 $ListitemsObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Contenttype\Listitems');
								
								$content = $ListitemsObj->renderParagraph($PageDataholderObj);
								
							
							break;
							
							
						
						

							

						
					}
					
				}
				else {	
					


 
					switch($PageDataholderObj->cRow['CType'])
					{
					
					
						case 'text':
			
							
							$tt_contentRecord['bodytext'] = strip_tags($PageDataholderObj->cRow['bodytext'],'<p><b><i><sup><sub><ul><ol><li>');
							
							$content = "\t\t\t".$this->parsehtml($tt_contentRecord['bodytext']);

						
						break;
					
					
					
					}
				}
					
				return $content;
	
	}
	
	
	
	
						
	function parsehtml($content)
	{
	
		$RteHtmlParser = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Html\RteHtmlParser::class);
		
		$RteHtmlParser->procOptions['proc.'] = array(
			'dontConvBRtoParagraph' => '0',
			'preserveDIVSections' => '1',
			'allowTagsOutside' => 'hr, address',
			'disableUnifyLineBreaks' => '0',
			'overruleMode' => 'ts_css',

			
		);
		
		
		$html = $RteHtmlParser->RTE_transform($content,array(),'rte',$RteHtmlParser->procOptions);
		return $html;


		
	}



}
?>
