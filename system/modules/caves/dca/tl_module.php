<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['cavelist'] = '{title_legend},name,headline,type;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['cavedetails']  = '{title_legend},name,headline,type;{config_legend},cave_archives;{template_legend:hide},cave_metaFields,cave_template,imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['cavearchive'] = '{title_legend},name,headline,type;{config_legend},cave_archives,cave_jumpToCurrent,cave_readerModule,perPage,cave_format;{template_legend:hide},cave_metaFields,cave_template,imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['cave_archives'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cave_archives'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_caves', 'getCaveArchives'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cave_jumpToCurrent'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cave_jumpToCurrent'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('hide_module', 'show_current', 'all_items'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(16) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cave_readerModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cave_readerModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_caves', 'getReaderModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['cave_metaFields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cave_metaFields'],
	'default'                 => array('date', 'author'),
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('date', 'author'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cave_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cave_template'],
	//'default'                 => 'cave_short',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_caves', 'getCaveTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cave_format'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cave_format'],
	'default'                 => 'cave_month',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('cave_day', 'cave_month', 'cave_year'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'wizard' => array
	(
		array('tl_module_caves', 'hideStartDay')
	),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cave_startDay'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cave_startDay'],
	'default'                 => 0,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(0, 1, 2, 3, 4, 5, 6),
	'reference'               => &$GLOBALS['TL_LANG']['DAYS'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cave_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cave_order'],
	'default'                 => 'descending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);



/* Class tl_module_caves
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Ralf Rötzer 2013
 * @author     Ralf Rötzer <https://www.cavers.de>
 * @package    cave
 */
class tl_module_caves extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Get all news archives and return them as array
	 * @return array
	 */
	public function getCaveArchives()
	{
		/*if (!$this->User->isAdmin && !is_array($this->User->caves))
		{
			return array();
		}*/

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT id, title FROM tl_cave_archive ORDER BY title");

		while ($objArchives->next())
		{
			if ($this->User->isAdmin || $this->User->hasAccess($objArchives->id, 'cave'))
			{
				$arrArchives[$objArchives->id] = $objArchives->title;
			}
		}

		return $arrArchives;
	}


	/**
	 * Get all cave reader modules and return them as array
	 * @return array
	 */
	public function getReaderModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='cavedetails' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}


    /**
	 * Hide the start day drop-down if not applicable
	 * @return string
	 */
	public function hideStartDay()
	{
		return '
  <script>
  var enableStartDay = function() {
    var e1 = $("ctrl_cave_startDay").getParent("div");
    var e2 = $("ctrl_cave_order").getParent("div");
    if ($("ctrl_cave_format").value == "cave_day") {
      e1.setStyle("display", "block");
      e2.setStyle("display", "none");
	} else {
      e1.setStyle("display", "none");
      e2.setStyle("display", "block");
	}
  };
  window.addEvent("domready", function() {
    if ($("ctrl_cave_startDay")) {
      enableStartDay();
      $("ctrl_cave_format").addEvent("change", enableStartDay);
    }
  });
  </script>';
	}


	/**
	 * Return all news templates as array
	 * @return array
	 */
	public function getCaveTemplates(DataContainer $dc)
	{
		return $this->getTemplateGroup('cave_', $dc->activeRecord->pid);
	}

}