<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   caves 
 * @author    Ralf Rötzer 
 * @license   LGPL 
 * @copyright Ralf Rötzer 2013 
 */


/**
 * Table tl_cave 
 */
$GLOBALS['TL_DCA']['tl_cave'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_cave_archive',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'alias' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('name','city'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,filter;search'
		),
		'label' => array
		(
			'fields'                  => array('name','city'),
			'showColumns'             => true,
			'format'                  => '%s, %s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cave']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cave']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cave']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_cave']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('notpublicopen','islock','addImage','addLinks'),
		'default'                     => '{name_legend},name,alias,author;
                                      {date_legend:hide},date,time;
                                      {cadaster_legend},cadastrenumber,mapsheet;
                                      {gps_legend:hide},latitude,longitude,isSecure,altitude;
                                      {address_legend:hide},street,postal,country,city;
                                      {directions_legend:hide},directions;
                                      {data_legend},category,mainlength,totallength,notpublicopen,islock;
                                      {statistics_legend:hide},evaluation,difficulty,wheelinglife;
                                      {description_legend},description;
                                      {equipment_legend:hide},equipment;
                                      {pictures_legend:hide},addImage,addLinks;
                                      {publish_legend},published,start,stop'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'notpublicopen'               => 'contact',
		'islock'                      => 'lockofday,lockofmonth,locktoday,locktomonth',
        'addImage'                    => 'singleSRC',
        'addLinks'                    => 'links'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_cave_archive.title',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['name'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_cave', 'generateAlias')
			),
			'sql'                     => "varbinary(128) NOT NULL default ''"
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['author'],
			'default'                 => $this->User->id,
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, 'mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'eager')
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['date'],
			'default'                 => time(),
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'time' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['time'],
			'default'                 => time(),
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'cadastrenumber' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['cadastrenumber'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'mapsheet' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['mapsheet'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'foreignKey'              => "tl_cave_mapsheets.CONCAT(mapnumber, ' ', city)",
            'eval'                    => array('doNotCopy'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'hasOne', 'load'=>'eager')
		),
		'latitude' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['latitude'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alnum', 'maxlength'=>9, 'doNotCopy'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'longitude' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['longitude'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alnum', 'maxlength'=>9, 'doNotCopy'=>true, 'tl_class'=>'w50 wizard'),
            'wizard'                  => array(array('tl_cave', 'getGpsConverter')),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'isSecure' => array
		(
	        'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['isSecure'],
	        'exclude'                 => true,
	        'filter'                  => true,
	        'inputType'               => 'checkbox',
	        'eval'                    => array('isBoolean' => true,'doNotCopy'=>true,'tl_class'=>'clr m12'),
	        'sql'                     => "char(1) NOT NULL default ''"
		),
		'altitude' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['altitude'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'tl_class'=>'clr'),
            'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'street' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['street'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'postal' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['postal'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'country' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['country'],
			'default'                 => 'de',
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getCountries(),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
        'city' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['city'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'directions' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['directions'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('doNotCopy'=>true,'style'=>'height: 80px;'),
			'sql'                     => "text NULL"
		),
		'category' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['category'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_cave_category.title',
			'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true, 'maxlength'=>255, 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'hasOne', 'load'=>'eager')
		),
		'mainlength' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['mainlength'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>7,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'totallength' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['totallength'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>7,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'notpublicopen' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['notpublicopen'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true,'doNotCopy'=>true, 'tl_class'=>'clr m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'contact' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['contact'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('doNotCopy'=>true,'style'=>'height:80px'),
			'sql'                     => "text NULL"
		),
		'islock' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['islock'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true,'doNotCopy'=>true,'tl_class'=>'clr m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'lockofday' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['lockofday'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => range(1,31),
			'eval'                    => array('includeBlankOption'=>true,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
		'lockofmonth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['lockofmonth'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => range(0,11),
			'reference'               => &$GLOBALS['TL_LANG'][MONTHS],
			'eval'                    => array('includeBlankOption'=>true,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
		'locktoday' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['locktoday'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => range(1,31),
			'eval'                    => array('includeBlankOption'=>true,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
		'locktomonth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['locktomonth'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => range(0,11),
			'reference'               => &$GLOBALS['TL_LANG'][MONTHS],
			'eval'                    => array('includeBlankOption'=>true,'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
		'evaluation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['evaluation'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => range(1,5),
			'eval'                    => array('mandatory'=>false,'includeBlankOption'=>true,'doNotCopy'=>true, 'maxlength'=>2, 'tl_class'=>'w50'),
			'sql'                     => "varchar(1) NOT NULL default ''"
		),
		'difficulty' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['difficulty'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => range(1,5),
			'eval'                    => array('mandatory'=>false,'includeBlankOption'=>true,'doNotCopy'=>true, 'maxlength'=>2, 'tl_class'=>'w50'),
			'sql'                     => "varchar(1) NOT NULL default ''"
		),
		'wheelinglife' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['wheelinglife'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>8,'doNotCopy'=>true, 'tl_class'=>'clr'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['description'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE'),
			'sql'                     => "text NULL"
		),
		'equipment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['equipment'],
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_cave', 'getEquipment'),
            'load_callback'           => array(array('tl_cave', 'setDefaultEquipment')), // Laden der Defaultwerte
			'save_callback'           => array(array('tl_cave', 'setDefaultEquipment')), // Speichert auch die Defaultwerte obwohl "disabled"
			'eval'                    => array('multiple'=>true, 'alwaysSave' =>true,'doNotCopy'=>true),    // 'alwaysSave'=>true, damit werte auf jeden fall gespeichert werden
            'wizard'                  => array(array('tl_cave', 'disableDefault')), // Deaktivieren der Checkboxen mit Defaultwerten
			'sql'                     => "blob NULL",
            'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
		),
        'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['addImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true,'doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'singleSRC' => array 
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('alwaysSave' =>true, 'files'=>true, 'fieldType'=>'radio','filesOnly'=>true, 'extensions'=>'jpg,jpeg,png.gif','doNotCopy'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'addLinks' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['addLinks'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true,'tl_class'=>'clr m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
       'links' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['links'],
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array
                (      
                    'columnFields'    => array
                        (
                            'url'       => array
                                (
                                    'label'     => &$GLOBALS['TL_LANG']['tl_cave']['url'],
                                    'inputType' => 'text',
                                    'eval'      => array('mandatory'=>true,'rgxp'=>'url', 'decodeEntities'=>true, 'style'=>'width:270px;')                                   
                                ),
                            'titleText' => array
                                (
                                    'label'     => &$GLOBALS['TL_LANG']['tl_cave']['titleText'],
                                    'inputType' => 'text',                                   
                                    'eval'      => array('mandatory'=>true, 'style'=>'width:310px;')
                                )
                        )
                    ),
			'sql'                     => "blob NULL"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_cave']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)			
	),
    
);
/**
 * Class tl_cave
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Ralf Rötzer 2013
 * @author     Ralf Rötzer
 * @package    caves
 */
class tl_cave extends Backend
{
	/**
	 * Auto-generate the news alias if it has not been set yet
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize(String::restoreBasicEntities($dc->activeRecord->name));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_cave WHERE alias=?")
								   ->execute($varValue);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}

    /**
	 * Return the link picker wizard
	 * @param \DataContainer
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		return ' <a href="contao/page.php?do='.Input::get('do').'&amp;table='.$dc->table.'&amp;field='.$dc->field.'&amp;value='.str_replace(array('{{link_url::', '}}'), '', $dc->value).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\'ctrl_'.$dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '').'\',\'self\':this});return false">' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}
	
  /**
	 * Generiert die Checkboxen anhand der Tabelle: tl_cave_equipment
	 * @return array()
	 * @throws 
	 */
  public function getEquipment()
  {
    $objOptions = $this->Database->prepare("SELECT * FROM tl_cave_equipment")->execute();
    $arrOptions = array();
    While ($objOptions->next())
    {
      $arrOptions[]=$objOptions->title;
    }
    return $arrOptions;
  }
  
  /**
	 * Setzt die Defaultwerte in den Checkboxen wenn diese Pflicht (mandatory) sind.
	 * @param string
	 * @return string
	 * @throws 
	 */  
  public function setDefaultEquipment($varValue)
  {                    
      $varArray = array();
      $varArray = deserialize($varValue);
      $objDefaults = $this->Database->prepare("SELECT * FROM tl_cave_equipment")->execute();
      $arrDefaults = array();
      while ($objDefaults->next())
      {
        if ($objDefaults->ismandatory == '1')
        {
          $arrDefaults[]=$objDefaults->title;
        }
      }
      if (strlen($varValue)==0)
      {
      $resArray = $arrDefaults;
      }
      else
      {
      $resArray = array_merge ($varArray, $arrDefaults);
      }
      $resArray = array_unique($resArray);
      $varValue = serialize($resArray);
    return $varValue;
  }
  
  public function getGpsConverter()
  {
    $phpForm = '
      <img id="cave-converter" width="20" height="20" style="vertical-align:-6px;cursor:pointer" title="Koordinatenkonverter" alt="Koordinatenkonverter" src="system/modules/caves/assets/img/convert-icon.png">';
      if (TL_MODE == 'BE')
      {
      $GLOBALS['TL_CSS'][]='system/modules/caves/assets/css/converter.css';
      $GLOBALS['TL_JAVASCRIPT'][]='system/modules/caves/assets/js/converter.js';
      }
    return $phpForm;
  }
  
    /**
	 * Disabled die Checkboxen mit Defaultwerten per JawaScript 
	 * @return string
	 * @throws 
	 */  
  public function disableDefault()
  {
  $objDefaults = $this->Database->prepare("SELECT * FROM tl_cave_equipment")->execute();
      $arrDefaults = array();
      while ($objDefaults->next())
      {
        if ($objDefaults->ismandatory == '1')
        {
          $arrDefaults[]=$objDefaults->title;
        }
      }
      $temp = "'" . implode("','", $arrDefaults) . "'";   //Array für javascript
      
      $sJavaScript = '
        <script type="text/javascript">
        <!--//--><![CDATA[//><!--   
        disableDefault = function(){
          phparray = [' . $temp . '];
          for (var i = 0; i < tl_cave.elements.length; i++)
          {
            res = phparray.indexOf(tl_cave.elements[i].value);
            if (res >= 0 )
            {
              tl_cave.elements[i].disabled = true;
            }
          }
        };
        window.addEvent("domready", function(){
          disableDefault();
        });
        //--><!]]></script>';
      return $sJavaScript;
  }
  
}