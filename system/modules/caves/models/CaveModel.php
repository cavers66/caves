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
class CaveModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_cave';

    
    /**
	 * Find published caves items by their ID or alias
	 * 
	 * @param array   $varId        The numeric ID or alias name
	 * @param array   $arrOptions  An optional options array
	 * 
	 * @return \Model\Collection|null The CaveModel or null if there are no news
	 */
    public static function findPublishedByIdOrAlias($varId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=? OR $t.alias=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}


	
	
}
