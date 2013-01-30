<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Caves
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Caves',
)); 


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Caves\Cave'              => 'system/modules/caves/classes/Cave.php',

	// Models
    'Caves\CaveArchiveModel'    => 'system/modules/caves/models/CaveArchiveModel.php',
    'Caves\CaveCategoryModel'   => 'system/modules/caves/models/CaveCategoryModel.php',
    'Caves\CaveEquipmentModel'  => 'system/modules/caves/models/CaveEquipmentModel.php',
    'Caves\CaveMapsheetsModel'           => 'system/modules/caves/models/CaveMapsheetsModel.php',
    'Caves\CaveModel'           => 'system/modules/caves/models/CaveModel.php',
  
    // Modules
	'Caves\ModuleCave'        => 'system/modules/caves/modules/ModuleCave.php',
    'Caves\ModuleCaveArchive' => 'system/modules/caves/modules/ModuleCaveArchive.php',
	'Caves\ModuleCaveDetails' => 'system/modules/caves/modules/ModuleCaveDetails.php',
	'Caves\ModuleCaveList'    => 'system/modules/caves/modules/ModuleCaveList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_cavearchive'       => 'system/modules/caves/templates',
    'mod_cavearchive_empty' => 'system/modules/caves/templates',
	'cave_cavedetails'       => 'system/modules/caves/templates',
	'mod_cavelist'          => 'system/modules/caves/templates',
    'cave_short'            => 'system/modules/caves/templates',
    'cave_full'            => 'system/modules/caves/templates',
));
