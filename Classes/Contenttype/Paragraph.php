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

class Paragraph {

		public function renderParagraph($PageDataholderObj){
		
			$contentrow = '';
			
			$piFlexForm = GeneralUtility::xml2array($PageDataholderObj->cRow['pi_flexform']);	


			$paragraphdata = '';
			
							
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



								$fieldnameArray = array('addtext','userinput','uicontrol','codeblock','codeph','systemoutput');
								foreach ($fieldData as $eachfieldname => $eachfieldvalue)
								{

										 
									if($eachfieldname != 'addtext' && !is_array($eachfieldvalue['el'][$eachfieldname]['vDEF']) && in_array($eachfieldname,$fieldnameArray))
									{
										

										$eachfieldvalue['el'][$eachfieldname]['vDEF'] =	 htmlspecialchars($eachfieldvalue['el'][$eachfieldname]['vDEF']);


									}
									
									
									switch($eachfieldname)
									{

											case 'addtext' :
													$paragraphdata .=  strip_tags($eachfieldvalue['el']['addtext']['vDEF'],'<b><i><sup><sub><ul><ol><li>');
													

											break;
											case 'userinput':
													$paragraphdata .=  '<userinput>'.$eachfieldvalue['el']['userinput']['vDEF'].'</userinput>';

	
											break;
											case 'uicontrol':
													$paragraphdata .=  '<uicontrol>'.$eachfieldvalue['el']['uicontrol']['vDEF'].'</uicontrol>';

	
											break;
											case 'codeblock':
													$paragraphdata .=  '<codeblock>'.$eachfieldvalue['el']['codeblock']['vDEF'].'</codeblock>';

	
											break;
											case 'codeph':
													$paragraphdata .=  '<codeph>'.$eachfieldvalue['el']['codeph']['vDEF'].'</codeph>';

	
											break;
											case 'systemoutput':
													$paragraphdata .=  '<systemoutput>'.$eachfieldvalue['el']['systemoutput']['vDEF'].'</systemoutput>';

	
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
														$paragraphdata .=  '<image href="'.$fullFilewithPath.'">'.$imagealtdata.'</image>';	
													}
		

												}

												
													$previousfield = 'image';

													//$paragraphdata .=  '<image href="'.$eachfieldvalue['el']['image']['vDEF'].'"></image>';

			
											break;
											case 'imagealt':

													echo $eachfieldvalue['el']['imagealt']['vDEF'];

													if($previousfield == 'image')
													{

															$paragraphdata .= '<alt>'. $eachfieldvalue['el']['imagealt']['vDEF'] .'</alt> </image';
															$previousfield = '';

													}
											break;
						

									}
										

								}							
								
	 
							}
													
						
						}
						
						

					}
				}
			}
			



			if(!empty($paragraphdata))
			{



			
				if(!empty($PageDataholderObj->cRow['header']))
				{

					$content = PHP_EOL."\t\t\t".'<section>'."\t\t\t".PHP_EOL."\t\t\t". '<title>'.$PageDataholderObj->cRow['header'].PHP_EOL."\t\t\t".'</title>'.PHP_EOL."\t\t\t".'<p>'.$paragraphdata.'</p>'.PHP_EOL."\t\t\t".'</section>'.PHP_EOL;
				}
				else {

					$content = "\t\t\t".'<p>'.$paragraphdata.'</p>';


				}
			}
		
							
			return  $content;
		
		}

	protected function getResourceFactory()
    {
        return ResourceFactory::getInstance();
    }
		
		




}









?>
