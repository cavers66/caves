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
	'caves',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'caves\Cave'              => 'system/modules/caves/classes/Cave.php',

	// Modules
	'caves\ModuleCaveArchive' => 'system/modules/caves/modules/ModuleCaveArchive.php',
	'caves\ModuleCaveDetails' => 'system/modules/caves/modules/ModuleCaveDetails.php',
	'caves\ModuleCaveList'    => 'system/modules/caves/modules/ModuleCaveList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_cavearchive' => 'system/modules/caves/templates',
	'mod_cavedetails' => 'system/modules/caves/templates',
	'mod_cavelist'    => 'system/modules/caves/templates',
));
