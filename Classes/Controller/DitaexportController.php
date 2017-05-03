<?php

namespace Dustbin\Contentfce\Controller;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Dustbin\Contentfce\Utility\CheckContenttype;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Dustbin\Contentfce\Utility\ZipDirectory;





//require_once(ExtensionManagementUtility::extPath('contentfce') . 'Classes/Utility/CheckContenttype.php');
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Controller for Ditaexport (BE and FE)
 *
 * @package Ditaexport
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class DitaexportController extends  \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
//class Tx_Contentfce_Controller_DitaexportController extends  \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {





	/**
	 * Object initialization
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->piVars = $this->request->getArguments();
		$this->id = (int)GeneralUtility::_GP('id');
		$this->sys_language = (int)GeneralUtility::_GP('sys_language_uid');

		$this->colPosList = 0;
		

		

		
		
	}

	/**
	 * Generate Dita file for selected page in Backend
	 *
	 * @return void
	 */
	public function indexAction() {
		
		$this->piVars = $this->request->getArguments();



				

		if(isset($this->piVars['ditaexport_submit']))
		{



					if(empty($this->id))
					{
			



					$this->addFlashMessage(
						 "Please select a page",
						 'Error', 
						 \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR, 
						 TRUE 
					);




						return;
					}

				$ObjGetPagecontent	= GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\GetPagecontent');
				$tempcontentfcepath = PATH_site.'typo3temp/tx_contentfce/';				
				$filename =	'ditazip_'.mt_rand().'_'.date("Y-m-d_H_i_s");
				$filenamewithpath = $tempcontentfcepath.$filename;
				if(!GeneralUtility::mkdir($filenamewithpath))
				{
						$this->addFlashMessage(
						 'Folder '.$filenamewithpath.' could not be created',
						 'Error', 
						 \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR, 
						 TRUE 
					);
					
						return;

				}



				
				
				$ObjGetPagecontent->GenerateParentAndChildrenDitafiles($this->id,$this->sys_language,$filenamewithpath );


				if (!extension_loaded('zip')) {
					$this->addFlashMessage(
						 'ZipArchive  is not loaded . Dita files can be found at '.$filenamewithpath,
						 'Error', 
						 \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR, 
						 TRUE 
					);
					
						return;
				}

					//GeneralUtility::mkdir($filenamewithpath.'.zip');

				$ObjZipDirectory = GeneralUtility::makeInstance('Dustbin\Contentfce\Utility\ZipDirectory');
				$ObjZipDirectory->zipDir($filenamewithpath, $filenamewithpath.'.zip');



				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=\"".$filename.'.zip'."\"");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".filesize($filenamewithpath.'.zip'));
				ob_end_flush();
				@readfile($filenamewithpath.'.zip');



				if(is_dir($filenamewithpath))
				{
	
						$this->removeDirectory($filenamewithpath);

				}

				unlink($filenamewithpath.'.zip');


				die;




		}
		

}


public function removeDirectory($path) {
	$files = glob($path . '/*');

	foreach ($files as $file) {

		is_dir($file) ? $this->removeDirectory($file) : unlink($file);
	}
	rmdir($path);
	return;
}
	

	
	

}
