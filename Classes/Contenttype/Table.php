<?php

namespace Dustbin\Contentfce\Contenttype;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class Table {

		public function renderTable($PageDataholderObj){
		
			$contentrow = '';
			$columnheaderdata = '';
			$columncount  = '';

			
			//var_dump($PageDataholderObj->cRow['pi_flexform']);		
			$piFlexForm = GeneralUtility::xml2array($PageDataholderObj->cRow['pi_flexform']);

						
							
			foreach ( $piFlexForm['data'] as $sheet => $data ) {
			
				if(isset($data['lDEF']['xmlTitle']) && is_array($data['lDEF']['xmlTitle']))
				{
						continue;
				
				}
				
				
				if($sheet == 'TableSheet')
				{
					if(isset($data["lDEF"]['ColCount']['vDEF']) && $data["lDEF"]['ColCount']['vDEF'] > 0)
					{
					
							$columncount =  $data["lDEF"]['ColCount']['vDEF'];
									
						for($colcount = 1;$colcount <= $data["lDEF"]['ColCount']['vDEF'];$colcount++)
						{
						
							//$columnheaderdata[$colcount]['tableheader'] = $columncount =  $data["lDEF"]['tableheader'.$colcount]['vDEF'];
							//$columnheaderdata[$colcount]['colwidth'] = $data["lDEF"]['colwidth'.$colcount]['vDEF'];
							//tableheader1
							 $columnheaderdata .= PHP_EOL."\t\t\t".'<entry colname="COLSPEC'.($colcount-1).'">'.$data["lDEF"]['tableheader'.$colcount]['vDEF'].PHP_EOL."\t\t\t".'</entry>';
							$columnspec .= "\t\t\t".'<colspec colname="COLSPEC'.($colcount-1).'" colwidth="'.$data["lDEF"]['colwidth'.$colcount]['vDEF'].'" />'.PHP_EOL;
											
										
						}
						
									 $columnheaderdata = '<tgroup cols="'.$columncount.'">'.PHP_EOL.$columnspec."\t\t\t".' <thead>'.PHP_EOL."\t\t\t".'<row>'.$columnheaderdata.PHP_EOL."\t\t\t".'</row>'.PHP_EOL."\t\t\t".' </thead>'.PHP_EOL;
									 
					}
				
				}
				
				
				
				
				
				
				if($sheet == 'items')
				{
						$rowcount = 0;
				
					foreach ( $data["lDEF"] as $fieldscontainer => $fieldscontainervalue ) {
																	
							
						if(isset($fieldscontainervalue['el']) && is_array($fieldscontainervalue['el']))
						{
							
							
							$contentCol = '';
							$contentColdata = ''; 
							foreach ($fieldscontainervalue['el'] as $eachcolumn => $eachcolumnData ) {								


								for($icolcount = 1; $icolcount <=$columncount; $icolcount++)
								{
								
										
										//var_dump($eachcolumnData);
										//var_dump($fieldscontainervalue['el']);
										
										
										if($rowcount == 0 && $icolcount <= 9  )
										{
											$eachcolumn = sprintf("%02d", $icolcount);
											
											
										}
										else {
												$eachcolumn =  sprintf("%d%d",$rowcount,$icolcount);
										}	
										 
										
										$resChildren = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tt_content', 'pid=' . $PageDataholderObj->pageid . ' AND sys_language_uid=' . $PageDataholderObj->sys_language .BackendUtility::deleteClause('tt_content') . BackendUtility::versioningPlaceholderClause('tt_content').' and tx_flux_parent = '.$PageDataholderObj->cRow['uid']."  and tx_flux_column = 'column".$eachcolumn.'_'.$eachcolumnData['item']['el']['id']['vDEF']."'", '', 'sorting');

						
											$eachcontentCol = '';
											while ($cChildRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resChildren)) {
													
												$CheckContenttypeObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\CheckContenttype');

								  				$childPageDataholderObj = GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\PageDataholder');
												$childPageDataholderObj->pageid = $PageDataholderObj->pageid;
												$childPageDataholderObj->sys_language = $PageDataholderObj->sys_language;
												$childPageDataholderObj->cRow = $cChildRow;
												$childPageDataholderObj->selectedpageid = $PageDataholderObj->selectedpageid;


												$eachcontentCol .= $CheckContenttypeObj->rendercontentytype($childPageDataholderObj);
												//echo $eachcontentCol;
											}
											
											
											$contentColdata .=  "\t\t\t".'<entry> '.$eachcontentCol .PHP_EOL."\t\t\t".'</entry>'.PHP_EOL;
																							
											
									
								}

									$rowcount++;

								$contentrow .= PHP_EOL."\t\t\t".'<row>'."\n".$contentColdata."\t\t\t".'</row>'.PHP_EOL;
								$contentColdata	 = '';							 
									 
							}
							/*
							if(!empty($contentColdata))
							{
								$contentrow .= '<row>'."\n".$contentColdata.'</row>'."\n";	
							}
							*/
						
						}
						
						

					}
					
				}	
				
	
			}
			
		 	if(!empty($contentrow))
			{
			
				$content = "\t\t".'<table>'.PHP_EOL."\t\t".$columnheaderdata."\t\t".'<tbody>'. $contentrow ."\t\t" .'</tbody>'.PHP_EOL."\t\t".'</tgroup>'.PHP_EOL."\t\t" .'</table>'.PHP_EOL;
			}

//var_dump($content);
//die;
							
			return  $content;
		
		}



}









?>
