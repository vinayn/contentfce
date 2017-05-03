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

class NavigationList {

		public function renderNavigationList($PageDataholderObj){
		
			$contentrow = '';
			
			$piFlexForm = GeneralUtility::xml2array($PageDataholderObj->cRow['pi_flexform']);	

			//$this->uriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');	
			
											/*	$httpRequest = \TYPO3\Flow\Http\Request::createFromEnvironment();
									$request = new \TYPO3\Flow\Mvc\ActionRequest($httpRequest);
									$uriBuilder = new \TYPO3\Flow\Mvc\Routing\UriBuilder();
									$uriBuilder->setRequest($request);*/
									$this->uriBuilder = $uriBuilder;
			
							
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
							foreach ( $fieldscontainervalue['el'] as $eachcolumn => $eachcolumnData ) {
								
								if(isset($eachcolumnData['item']) && is_array($eachcolumnData))
								{
											




									
									$urlTarget = $eachcolumnData['item']['el']['target']['vDEF'];
									
									if(empty($urlTarget))
									{
									
										continue;
									}
									$urltext = $eachcolumnData['item']['el']['text']['vDEF'];
									
									if(!empty($urltext))
									{
										$urltext = PHP_EOL."\t\t\t".'<linktext>'.$urltext.'</linktext>';
									}
									
									
									//echo $urlTarget;
									
									//echo GeneralUtility::getIndpEnv('TYPO3_SITE_URL').'index.php?id='.$urlTarget;
									
									$resourceFactory = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
									

									
									list($linkHandlerKeyword, $linkHandlerValue) = explode(':', $urlTarget, 2);
									
									
									if ($linkHandlerKeyword === 'file' && !StringUtility::beginsWith($urlTarget, 'file://')) {
									
											$fileOrFolderObject = $this->getResourceFactory()->retrieveFileOrFolderObject($urlTarget);
											// Link to a folder or file
											if ($fileOrFolderObject instanceof File || $fileOrFolderObject instanceof Folder) {
											    $linkurl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL').$fileOrFolderObject->getPublicUrl();
												
												
												$pos = strrpos($linkurl,'.');
												
												$format = '';
												if ($pos !== false) { // note: three equal signs
													
														$format = substr($linkurl,$pos+1,strlen($linkurl)-1);
													// not found...
												}
												
												//$strrstr('.',$linkParameter)
												$contentCol .= PHP_EOL."\t\t\t".'<link format="'.$format.'" href="'.$linkurl.'" >'. $urltext.PHP_EOL."\t\t\t".'</link>';
											} else {
												$linkurl = null;
										}
											
											
									
									
									}
									else {
									
																				

											$parentids = BackendUtility::BEgetRootLine($urlTarget);
											$parentpath = array();
											$parentPathisthereinrootline = 'no';


											
											foreach($parentids as $eachparentid)
											{
													

													
													$parentpath[] = str_replace(' ','_',$eachparentid['title']).'_'.$eachparentid['uid'].'.xml';
													
													if($eachparentid['uid'] == $PageDataholderObj->selectedpageid)
													{

																$parentPathisthereinrootline = 'yes';
																break;
																


													}
											}

											
											$format = '';
											if($parentPathisthereinrootline == 'yes')
											{
													
												
												 krsort($parentpath); 

												$linkurl = implode('/',$parentpath);


											}
											else {
											
												$linkurl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL').'index.php?id='.$urlTarget;
												$format = 'html'; 

											}
											$contentCol .= PHP_EOL."\t\t\t".'<link format="'.$format.'" href="'.$linkurl.'" >'. $urltext.PHP_EOL."\t\t\t".'</link>';
									}
									

								}	
									 
							}
							
							if(!empty($contentCol))
							{
												$contentColdata .= PHP_EOL."\t\t\t".'<related-links>'."\t\t\t".PHP_EOL."\t\t\t". '<linkpool>'.$contentCol.PHP_EOL."\t\t\t".'</linkpool>'.PHP_EOL."\t\t\t".'</related-links>'.PHP_EOL;
																						
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
		
		
	protected function getResourceFactory()
    {
        return ResourceFactory::getInstance();
    }



}









?>
