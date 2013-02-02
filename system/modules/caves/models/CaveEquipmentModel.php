<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2013 Ralf Rötzer
 * 
 * @package cave
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Caves;


/**
 * Reads and writes caves
 * 
 * @package   Models
 * @author    Ralf Rötzer
 * @copyright 2013 Ralf Rötzer
 */
class CaveEquipmentModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_cave_equipment';


	/**
	 * Find multiple cave equipment by their title
	 * 
	 * @param array $arrTitles     An array of equipment title
	 * @param array $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no cave equipment
	 */
	public static function findMultipleByTitle($arrTitles, array $arrOptions=array())
	{
		if (!is_array($arrTitles) || empty($arrTitles))
		{
			return null;
		}

		$t = static::$strTable;
        $list = "'" . implode("','", $arrTitles) . "'";
		
        $objEqu = \Database::getInstance()->query("SELECT title, description FROM tl_cave_equipment WHERE title IN(" . $list . ")");
        return $objEqu;
	}
}
