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
	 * Find multiple cave archives by their IDs
	 * 
	 * @param array $arrIds     An array of archive IDs
	 * @param array $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no news archives
	 */
	public static function findMultipleByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("$t.id", $arrIds);
		}

		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, $arrOptions);
	}
}
