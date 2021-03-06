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
namespace Caves;


/**
 * Class ModuleCaveList 
 *
 * @copyright  Ralf Rötzer 2013 
 * @author     Ralf Rötzer 
 * @package    Devtools
 */
class ModuleCaveList extends \ModuleCave 
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
	
	protected function getArchivList($dbObject)
    {
        if ($dbObject->count() != 0 )
        {
            $strHtml = "";
        
            $class_tr = '';   // Variable für Zusammengesetzte Klassenangabe
            
            for ($row=0;$i< $dbObject->count();$i++)
            {
                $objRow = $dbObject->row();
                $class_tr = 'row_'.$row;
                if ($row == 0) $class_tr . ' row_first';
                if ($row == $dbObject->count()-1) $class_tr . ' row_last';
                $class_tr . (($row % 2) == 0) ? ' even' : ' odd'; 
                $image = $this->cave_getImage($objRow['singleSRC'], $objRow['pid']);     
                $objPage = \PageModel::findByPk($dbObject->jumpTo);
            
                $url = $this->generateFrontendUrl($objPage->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objRow['alias'] != '') ? $objRow['alias'] : $objRow['id']));

                $strHtml .= "<tr class='" . $class_tr . "'> \n";
                $strHtml .= "<td class='col_0 col_first'><a href='" . $url . "'>" . trim($objRow['name']) . "</a></td> \n";
                $strHtml .= "<td class='col_1'>" . trim($objRow['postal']) . "</td> \n";
                $strHtml .= "<td class='col_2'>" . trim($objRow['city']) . "</td> \n";
                $strHtml .= "<td class='col_3 col_last'>";
                $strHtml .= "<a href='" . $image['singleSRC'] . "' rel='lightbox[lb7]' title='" . $image['caption'] . "'>";
                $strHtml .= "<img src='" . $this->getImage($image['singleSRC'], '80', '60') . "' alt='" . $image['alt'] . "' width='80' height='60' /></a> \n";
                $strHtml .= "</td>\n </tr> \n";
                
                $dbObject->next();
            }
        
        }
        else
        {
            $strHtml = "<p>Leider keine Datensätze vorhanden.</p>";
        }
                 
        return $strHtml;
    }
    
    
    
    
    /**
	 * Generate the module
	 */
	protected function compile()
	{
      $time = time();
    
      $objCaves = $this->Database
        ->prepare ("SELECT s.*, a.title AS 'archive', a.jumpTo
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
     
    
  
        $this->Template->caves = $this->getArchivList($objCaves);
        //$this->Template->images = $this->cave_getImage($objCaves);
                  
	}

   
}
