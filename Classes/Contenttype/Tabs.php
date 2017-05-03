<?php

namespace Dustbin\Contentfce\Contenttype;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class Tabs {

		public function renderTabs($PageDataholderObj){
		
			$contentrow = '';
			
			$piFlexForm = GeneralUtility::xml2array($PageDataholderObj->cRow['pi_flexform']);			
							
			foreach ( $piFlexForm['data'] as $sheet => $data ) {
			
				if(isset($data['lDEF']['xmlTitle']) && is_array($data['lDEF']['xmlTitle']))
				{
						continue;
				
				}
				
				if($sheet == 'tabs')
				{
			
					foreach ( $data["lDEF"] as $fieldscontainer => $fieldscontainervalue ) {
																
						
						if(isset($fieldscontainervalue['el']) && is_array($fieldscontainervalue['el']))
						{
							
							
							$contentCol = '';
							$contentColdata = ''; 
							foreach ( $fieldscontainervalue['el'] as $eachcolumn => $eachcolumnData ) {
									
									//var_dump($eachcolumnData['tab'][0]['el'][0]['title']['vDEF']);
									$tabtitle = $eachcolumnData['tab']['el']['title']['vDEF'];
								$eachcolumn = $eachcolumn -1;	
								$resChildren = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tt_content', 'pid=' . $PageDataholderObj->pageid . ' AND sys_language_uid=' . $PageDataholderObj->sys_language .BackendUtility::deleteClause('tt_content') . BackendUtility::versioningPlaceholderClause('tt_content').' and tx_flux_parent = '.$PageDataholderObj->cRow['uid']."  and tx_flux_column = 'content_".$eachcolumnData['tab']['el']['id']['vDEF']."'" , '', 'sorting');



																					
									$eachcontentCol = '';
									while ($cChildRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resChildren)) {
											

												$CheckContenttypeObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\CheckContenttype');

								  				$childPageDataholderObj = GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\PageDataholder');
												$childPageDataholderObj->pageid = $PageDataholderObj->pageid;
												$childPageDataholderObj->sys_language = $PageDataholderObj->sys_language;
												$childPageDataholderObj->cRow = $cChildRow;
												$childPageDataholderObj->selectedpageid = $PageDataholderObj->selectedpageid;

												$eachcontentCol .= $CheckContenttypeObj->rendercontentytype($childPageDataholderObj);
										

										//echo $contentColdata ;
									}
									
									if(!empty($eachcontentCol))
									{
											$contentColdata .= PHP_EOL."\t\t".'<section>'."\t\t\t".PHP_EOL."\t\t\t". '<title>'.$tabtitle.PHP_EOL."\t\t\t".'</title>'.PHP_EOL.$eachcontentCol .PHP_EOL."\t\t".'</section>'.PHP_EOL;
																					
									}
									

									
									 
							}
							
							if(!empty($contentColdata))
							{
								$contentrow = $contentColdata;
							}
							
						
						}
						
						

					}
				}
			}
			
			if(!empty($contentrow))
			{
			
				$content = $contentrow;
			}
		
		
			return  $content;
		
		}



}









?>
