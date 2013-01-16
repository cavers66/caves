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
 * Namespace
 */
namespace caves;


/**
 * Class ModuleCaveList 
 *
 * @copyright  Ralf Rötzer 2013 
 * @author     Ralf Rötzer 
 * @package    Devtools
 */
class ModuleCaveList extends \Module 
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_cavelist';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### CAVE LIST ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		//$this->news_archives = $this->sortOutProtected(deserialize($this->news_archives));

		// Return if there are no archives
		//if (!is_array($this->news_archives) || empty($this->news_archives))
		//{
		//	return '';
		//}

		return parent::generate();
	}
	
	/**
	 * Generate the module
	 */
	protected function compile()
	{
      $time = time();
      
      $objCaves = $this->Database
        ->prepare ("SELECT s.*, a.title AS 'archive'
                    FROM tl_cave s
                    INNER JOIN tl_cave_archive a
                    ON a.id=s.pid
                    WHERE a.published=?
                    AND (a.start=? OR a.start<?)
                    AND (a.stop=? OR a.stop>?)
                    AND s.published=?
                    AND (s.start=? OR s.start<?)
                    AND (s.stop=? OR s.stop>?)")
        ->execute(1, ' ', $time, ' ', $time, 1, ' ', $time, ' ', $time);
        

        $this->Template->caves = $objCaves->fetchAllAssoc();
                  
	}
}
