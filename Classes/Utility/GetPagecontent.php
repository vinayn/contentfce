<?php


namespace Dustbin\Contentfce\Utility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Dustbin\Contentfce\Utility\CheckContenttype;

use TYPO3\CMS\Backend\View\BackendLayoutView;


class GetPagecontent
{

		public $id;
		public $sys_language;
		public $selectedpageid;		
		function constuctor($pageid,$sys_language)
		{

			$this->id = $pageid;
			$this->sys_language = $sys_language;
			


		}

		 
		function GenerateParentAndChildrenDitafiles($pageid,$sys_language,$filenamewithpath)
		{
			
				$this->selectedpageid = $pageid;
				$pageRecord = BackendUtility::getRecord('pages', $pageid);
				$pagetitle = strip_tags(BackendUtility::getRecordTitle('pages', $pageRecord));	
				$pagetitle = str_replace(' ','_',$pagetitle);
				//directory 254 , PATH_site.typo3temp	

				$parentfolderCreated = 'no';
				switch($pageRecord['doktype'])
				{

					case  254:
								GeneralUtility::mkdir($filenamewithpath.'/'.$pagetitle.'_'.$pageid);
								$parentfolderCreated = 'yes';
								$parentfilenamewithpath = $filenamewithpath.'/'.$pagetitle.'_'.$pageid;
							
						break;

					default:
							
								$pageconent = $this->getpagecontentbyid($pageid,$sys_language);


								if($myfile = fopen($filenamewithpath.'/'.$pagetitle.'_'.$pageid.'.xml', "w"))
								{ 
									fwrite($myfile, $pageconent);									
									fclose($myfile);
								}
								else {

										 throw new \Exception(' file  ' . $filenamewithpath.'/'.$pagetitle.'_'.$pageid.'.xml'. ' could not be create');
								}

						break;
				
				}
		


				// child pages are handled here

				$resChildren = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,doktype', 'pages', ' hidden = 0 and pid=' . $pageid .BackendUtility::deleteClause('pages') . BackendUtility::versioningPlaceholderClause('pages') , '', 'sorting');

				

				while ($cChildRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resChildren)) {


				




			
					$ChildpageRecord = BackendUtility::getRecord('pages', $cChildRow['uid']);
					$Childpagetitle = strip_tags(BackendUtility::getRecordTitle('pages', $ChildpageRecord));	
					$Childpagetitle = str_replace(' ','_',$Childpagetitle);

						
					switch($cChildRow['doktype'])
					{

						case  254:
									$Childfilenamewithpath = $parentfilenamewithpath.'/'.$Childpagetitle.'_'.$cChildRow['uid'];
									GeneralUtility::mkdir($Childfilenamewithpath);									
									$parentfolderCreated = 'yes';
				
							break;

						default:

									if($parentfolderCreated == 'no')
									{
												GeneralUtility::mkdir($filenamewithpath.'/'.$pagetitle.'_'.$pageid);
												$parentfolderCreated = 'yes';
												//$Childfilenamewithpath = $filenamewithpath.'/'.$pagetitle.'_'.$pageid;
												$parentfilenamewithpath = $filenamewithpath.'/'.$pagetitle.'_'.$pageid;
						
									}

									$pageconent = $this->getpagecontentbyid($cChildRow['uid'],$sys_language);


									if($myfile = fopen($parentfilenamewithpath.'/'.$Childpagetitle.'_'.$cChildRow['uid'].'.xml', "w"))
									{ 
										fwrite($myfile, $pageconent);									
										fclose($myfile);
									}
									else {

											 throw new \Exception(' file  '. $parentfilenamewithpath.'/'.$Childpagetitle.'_'.$cChildRow['uid'].'.xml'. ' could not be create');
									}

							break;
	
					}



				}



		}
		

/*
		function getpagecontentbyid() 
		{

			
				
			$content = $this->getContent();
			
			
			$pageRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $this->id);
			$topictitle = 	strip_tags(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('pages', $pageRecord));
			$fileName = str_replace(' ','_',$topictitle);
			if(empty($fileName))
			{
				$fileName = 'dita.xml';
			
			}
			else {
			
				$fileName = $fileName.$this->id.'.xml';
			}
			
			
			$content = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE task PUBLIC "-//OASIS//DTD DITA General Task//EN" "task.dtd">'.$content;
								
								//$fileName = 'dita.xml';
								header('Content-Type: text/xml');
								header('Content-Disposition: attachment; filename="' . $fileName . '"');
								header('Pragma: no-cache');
								echo $content;
								exit();




		}

*/
	private function getpagecontentbyid($pageid,$sys_language)
	{
	  
	  
	  $content = '';
	 

	  
	  
	  $pageRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $pageid);
	  
	  if(!$GLOBALS['BE_USER']->doesUserHaveAccess($pageRecord, 1))
	  {	  

			$this->addFlashMessage(
				 "You don't have permission to the page id ".$this->id,
				 'Permission denied', 
				 \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING, 
				 TRUE 
			);
		  
	  
			return "";
	  
	  }
	  
	  				$PageDataholderObj = GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\PageDataholder');
					$PageDataholderObj->pageid = $pageid;
					$PageDataholderObj->sys_language = $sys_language;
					$PageDataholderObj->selectedpageid = $this->selectedpageid;



			$objBackendLayoutView  = GeneralUtility::makeInstance('TYPO3\CMS\Backend\View\BackendLayoutView');
//echo 'how are you';
//var_dump($objBackendLayoutView->getColPosListItemsParsed($pageid));
	$colPosArray = $objBackendLayoutView->getColPosListItemsParsed($pageid);

	//die;

$colPos = array();
foreach($colPosArray as $eachcolPosArray)
{

	if(isset($eachcolPosArray[1]))
	{
		$colPos[] = $eachcolPosArray[1];
	}
	
}

$colPosCondition = implode(',',$colPos);



	  $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pi_flexform,uid,bodytext,CType,tx_fed_fcefile,header', 'tt_content', 'pid=' . (int)$pageid . ' AND sys_language_uid=' . (int)$sys_language .'   and colpos in  ('.$colPosCondition.')        '.BackendUtility::deleteClause('tt_content') . BackendUtility::versioningPlaceholderClause('tt_content'), '', 'colPos,sorting');
	  
	 
	  	while ($cRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			BackendUtility::workspaceOL('tt_content', $cRow);
	
			if (is_array($cRow) ) {
			

							
				$CheckContenttypeObj = GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\CheckContenttype');
				

				

				$PageDataholderObj->cRow = $cRow;


				

				//$content .= $CheckContenttypeObj->rendercontentytype($cRow,(int)$pageid,(int)sys_language,$PageDataholderObj);
				$content .= $CheckContenttypeObj->rendercontentytype($PageDataholderObj);
				
				
			}
		}
		
		
		
	

			$content = $this->checktypeofdocument('topic',$content,$pageid,$sys_language);
		
		
			return $content;

	  


	}




			function checktypeofdocument($type,$maicontent,$pageid,$sys_language)
				{
	
	

	
	
							$pageRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $pageid);
							$pagetitle = 	strip_tags(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('pages', $pageRecord),'<b><i><sup><sub>');	
				
							Switch($type)
							{
				
								case 'topic':
								default:
					
					
						
						
									$topictitle = 	strip_tags(\TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle('pages', $pageRecord));	
									$topictitle = str_replace(' ','_',$topictitle);
									$content .= '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE task PUBLIC "-//OASIS//DTD DITA General Task//EN" "task.dtd">';
									$content .= PHP_EOL.'<topic id="'.$topictitle.$this->id.'" >'.PHP_EOL;
									$content  .=  "\t".'<title>'.$pagetitle.'<title>'.PHP_EOL;
									$content .= "\t".'<body>'.PHP_EOL;
									$content .=  "\t".$maicontent.PHP_EOL;
						
									$content .=  "\t".' </body>'.PHP_EOL;
									$content .= '</topic>'.PHP_EOL;
						
							}
							return $content;
	
	
				}
	







}
