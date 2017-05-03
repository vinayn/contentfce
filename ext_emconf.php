<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "Dustbin.contentfce".
 *
 * Auto generated 17-01-2016 17:20
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Dustbin ',
  'description' => 'The extension provides fluidcontent based fluid content elements. The fluid content element (regular Text Element and Dita content Element) added in a page can be exported in the form of DITA xml files. The Table fluid template helps in creating dynamic table up-to 12 columns. The aim of the project is to add content structurally and to make the content intelligent. Structured content is is development. In future there might be intelligent and channel specific content ',
  'category' => 'misc',
  'version' => '2.0.0',
  'state' => 'Stable',
  'uploadfolder' => false,
  'createDirs' => '',
  'clearcacheonload' => true,
  'author' => 'Vinay',
  'author_email' => 'nvinayvinay@gmail.com',
  'author_company' => '',
  'createDirs' => 'typo3temp/tx_contentfce',
  'autoload'=> 
		array(		
		'psr-4'=>
		array('Dustbin\\Contentfce\\'=>
		'Classes')
	),
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '7.6.0-7.6.99',
      'flux' => '7.3.0',
      'fluidpages' => '3.4.0',
      'fluidcontent' => '4.4.0',
      'vhs' => '2.4.0',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:0:{}',
);

