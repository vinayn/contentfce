<?php

namespace Dustbin\Contentfce\Contenttype;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\StringUtility;

class Listitems {

		public function renderParagraph($PageDataholderObj){
		
			//var_dump($PageDataholderObj);

			$contentrow = '';
			
			$piFlexForm = GeneralUtility::xml2array($PageDataholderObj->cRow['pi_flexform']);	


			$listdata = '';
			
							
			foreach ( $piFlexForm['data'] as $sheet => $data ) {
			
				if(isset($data['lDEF']['xmlTitle']) && is_array($data['lDEF']['xmlTitle']))
				{
						continue;
				
				}
				
				if($sheet == 'items')
				{
			
					foreach ( $data["lDEF"] as $fieldscontainer => $fieldscontainervalue ) {
																
						
						if(isset($fieldscontainervalue['el']) && is_array($fieldscontainervalue['el']))
						{
							
							
							$contentCol = '';
							$contentColdata = ''; 
							$previousfield = '';
							foreach ( $fieldscontainervalue['el'] as $field  => $fieldData ) {
								//var_dump($fieldData);



								$fieldnameArray = array('addtext','userinput','uicontrol','codeblock','codeph','systemoutput','image',
														'screen','addlist','ditacontent');
								foreach ($fieldData as $eachfieldname => $eachfieldvalue)
								{

										 
									if($eachfieldname != 'addtext' && !is_array($eachfieldvalue['el'][$eachfieldname]['vDEF']) && in_array($eachfieldname,$fieldnameArray))
									{
										

										$eachfieldvalue['el'][$eachfieldname]['vDEF'] =	 htmlspecialchars($eachfieldvalue['el'][$eachfieldname]['vDEF']);


									}
									
									
									switch($eachfieldname)
									{

											case 'addtext' :
													$listdata .=  strip_tags($eachfieldvalue['el']['addtext']['vDEF'],'<b><i><sup><sub><ul><ol><li>');
													

											break;
											case 'userinput':
													$listdata .=  '<userinput>'.$eachfieldvalue['el']['userinput']['vDEF'].'</userinput>';

	
											break;
											case 'uicontrol':
													$listdata .=  '<uicontrol>'.$eachfieldvalue['el']['uicontrol']['vDEF'].'</uicontrol>';

	
											break;
											case 'codeblock':
													$listdata .=  '<codeblock>'.$eachfieldvalue['el']['codeblock']['vDEF'].'</codeblock>';

	
											break;
											case 'codeph':
													$listdata .=  '<codeph>'.$eachfieldvalue['el']['codeph']['vDEF'].'</codeph>';

	
											break;
											case 'systemoutput':
													$listdata .=  '<systemoutput>'.$eachfieldvalue['el']['systemoutput']['vDEF'].'</systemoutput>';

	
											break;
											case 'image':
												$imageresource = $eachfieldvalue['el']['image']['vDEF'];

												list($fieldResourceType, $fieldResourceNumber) = explode(':', $imageresource, 2);
									
									
												if ($fieldResourceType === 'file' && !StringUtility::beginsWith($imageresource, 'file://')) {
													$fileOrFolderObject = $this->getResourceFactory()->retrieveFileOrFolderObject($imageresource);
													if(!empty($fileOrFolderObject))
													{
													 $fullFilewithPath = GeneralUtility::getIndpEnv('TYPO3_SITE_URL').$fileOrFolderObject->getPublicUrl();
														$imagealtdata .= '<alt>'. $eachfieldvalue['el']['imagealt']['vDEF'] .'</alt> ';
														$listdata .=  '<image href="'.$fullFilewithPath.'">'.$imagealtdata.'</image>';	
													}
		

												}

												
													$previousfield = 'image';

													//$listdata .=  '<image href="'.$eachfieldvalue['el']['image']['vDEF'].'"></image>';

			
											break;
											case 'imagealt':

													echo $eachfieldvalue['el']['imagealt']['vDEF'];

													if($previousfield == 'image')
													{

															$listdata .= '<alt>'. $eachfieldvalue['el']['imagealt']['vDEF'] .'</alt> </image';
															$previousfield = '';

													}
											break;
											case 'addlist' :
													
												$listdata .= '</li>'.PHP_EOL."\t\t\t".'<li>';			
												$listdata .=  strip_tags($eachfieldvalue['el']['addlist']['vDEF'],'<b><i><sup><sub><ul><ol><li>');

		
											break;
												
											case 'ditacontent' :
													
															//var_dump($eachfieldvalue['el']['ditacontenttitle']['vDEF']);
												$listdata .= '<title>'. strip_tags($eachfieldvalue['el']['ditacontenttitle']['vDEF'],'<b><i><sup><sub><ul><ol><li>').'</title>';


													$resChildren = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tt_content', 'pid=' . $PageDataholderObj->pageid . ' AND sys_language_uid=' . $PageDataholderObj->sys_language .BackendUtility::deleteClause('tt_content') . BackendUtility::versioningPlaceholderClause('tt_content').' and tx_flux_parent = '.$PageDataholderObj->cRow['uid']."  and tx_flux_column = 'ditacontenttitle_".$eachfieldvalue['el']['id']['vDEF']."'" , '', 'sorting');



												//var_dump($PageDataholderObj);

												/*echo 'pid=' . $PageDataholderObj->pageid . ' AND sys_language_uid=' . $PageDataholderObj->sys_language .BackendUtility::deleteClause('tt_content') . BackendUtility::versioningPlaceholderClause('tt_content').' and tx_flux_parent = '.$PageDataholderObj->cRow['uid']."  and tx_flux_column = 'ditacontenttitle_".$eachfieldvalue['el']['id']['vDEF']."'";  */
											$eachcontentCol = '';
											while ($cChildRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resChildren)) {
													
												$CheckContenttypeObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\CheckContenttype');

												//var_dump($cChildRow);


								  				$childPageDataholderObj = GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\PageDataholder');
												$childPageDataholderObj->pageid = $PageDataholderObj->pageid;
												$childPageDataholderObj->sys_language = $PageDataholderObj->sys_language;
												$childPageDataholderObj->cRow = $cChildRow;
												$childPageDataholderObj->selectedpageid = $PageDataholderObj->selectedpageid;

	


												$eachcontentCol = $CheckContenttypeObj->rendercontentytype($childPageDataholderObj);

											}



											$listdata .= $eachcontentCol;


		
											break;
	

						

									}
										

								}							
								
	 
							}
													
						
						}
						
						

					}
				}
			}
			
			if(!empty($listdata))
			{
			
				$content = "\t\t".'<ul>'.PHP_EOL."\t\t\t".'<li>'.$listdata.'</li>'.PHP_EOL."\t\t".'</ul>'.PHP_EOL;
			}
		


							
			return  $content;
		
		}

	protected function getResourceFactory()
    {
        return ResourceFactory::getInstance();
    }
		
		




}









?>
