<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2013 Ralf Rötzer
 * 
 * @package cave
 * @link    http://cavers.de
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Caves;


/**
 * Class ModuleNews
 *
 * Parent class for news modules.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
abstract class ModuleCave extends \Module
{

	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();


	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrArchives)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrArchives) || empty($arrArchives))
		{
			return $arrArchives;
		}

		$this->import('FrontendUser', 'User');
		$objArchive = \CaveArchiveModel::findMultipleByIds($arrArchives);
		$arrArchives = array();

		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				if ($objArchive->protected)
				{
					if (!FE_USER_LOGGED_IN)
					{
						continue;
					}

					$groups = deserialize($objArchive->groups);

					if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $this->User->groups)))
					{
						continue;
					}
				}

				$arrArchives[] = $objArchive->id;
			}
		}

		return $arrArchives;
	}


    /**
	 * Setzt ja nach Suchergebnis die Bildparameter 'id', 'name', 'singleSRC', 'alt', 'imageurl', 'caption
	 * @param integer
     * @param integer
	 * @return array
	 */
    protected function cave_getImage($singleSRC, $pid)
    {
        global $objPage;
        $arrImage = array
        (
            'id'        => 0,
            'name'      => '',
            'singleSRC' => 'system/modules/caves/assets/img/noimage.png',
            'alt'       => 'Leider kein Bild vorhanden!',
            'imageurl'  => '',
            'caption'   => 'Leider kein Bild vorhanden!',

        );

        if ($singleSRC == "")
        {
            $objResult = $this->Database
                                ->prepare("SELECT addDefImage, singleSRC
                                            FROM tl_cave_archive
                                            WHERE id=?")
                                ->execute($pid);
            if ($objResult->addDefImage !="")
            {
                $objImage = \FilesModel::findByPk($objResult->singleSRC);
            }           
        }
        else
        {
            $objImage = \FilesModel::findByPk($singleSRC);           
        }
        if (isset($objImage))
        {
            if ($objImage->countAll() != 0)
            {
                $arrMeta = $this->getMetaData($objImage->meta, $objPage->language);
                if ($arrMeta['title'] == '')
                {
                    $arrMeta['title'] = specialchars(str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objImage->name)));
                }
                $arrImage['id']        = $objImage->id;
                $arrImage['name']      = $arrMeta['title'];
                $arrImage['singleSRC'] = $objImage->path;
                $arrImage['alt']       = $arrMeta['title'];
                $arrImage['imageurl']  = $arrMeta['link'];
                $arrImage['caption']   = $arrMeta['caption'];   
            }       
        }
        
        
        return $arrImage;    
    }

    /**
	 * Holt sich die entsprechende Ausrüstungs-Beschreibung aus der Tabelle
	 * @param array
	 * @return array
	 */ 
    protected function cave_getEquipment($arrValue)
    {
        if (is_array($arrValue))
        {
            $strValue = implode("', '", $arrValue);
            $objResult = $this->Database
                                ->prepare("SELECT title, description
                                            FROM tl_cave_equipment
                                            WHERE title IN ('$strValue')")
                                ->execute(); 
        return $objResult->fetchAllAssoc();
        }
    }
   
    /**
	 * Rechnet die gespeicherten Koordinaten in das DMS-Format um
	 * @param decimal
     * @param decimal
	 * @return string
	 */
    protected function dgToGms($x,$y)
{
    if(($x == 0) || ($y == 0))
    {
        return;
    }
    
    if ($x > 0)
    {
        $ns = "";
    }
    else
    {
        $ns = "-";
        $x = $x * -1;
    }
        if ($y > 0)
    {
        $ew = "";
    }
    else
    {
        $ew = "-";
        $y = $y * -1;
    }
    $xr = round($x, 5);
    $yr = round($y, 5);
    $xr = $ns.$xr;
    $yr = $ew.$yr;
    $exploded = explode(".",$x);
    $xg = $exploded[0];
    $exploded = explode(".",$y);
    $yg = $exploded[0];
    $xm = ($x - $xg)*60;
    $ym = ($y - $yg)*60;
    $xmr = round($xm, 3);
    $ymr = round($ym, 3);
    $xgm = $ns.$xg." ".abs($xmr);
    $ygm = $ew.$yg." ".abs($ymr);
    $exploded = explode(".",$xm);
    $xmg = $exploded[0];
    $exploded = explode(".",$ym);
    $ymg = $exploded[0]; 
    $xs = ($xm - $xmg)*60;
    $ys = ($ym - $ymg)*60;
    $xsr = round($xs, 1);
    $ysr = round($ys, 1);
    if ($ns == "")
    {
        $ns = "N";
    }
    else
    {
        $ns = "S";
    }
    if ($ew == "")
    {
        $ew = "E";
    }
    else
    {
        $ew = "W";
    }  
    $strHtml = $ns . " " . $xg ."° ". abs($xmg) . "\" ". abs($xsr) . "' ";
    $strHtml .= $ew . " " . $yg ."° ".abs($ymg) . "\" ". abs($ysr) . "'";
    return $strHtml; 
}
   
    protected function isFieldEmpty($strField)
    {
        return ($strField != NULL);
    }

    protected function getArray($arr)
    {
        $filter = array_filter($arr, array($this,'isFieldEmpty'));
        return $filter;
    }

     /**
	 * Erstellt das Arcordeon-Element
	 * @param string
     * @param string
	 * @return string
	 */ 
    protected function getArcordeon($title, $content)
    {
        $strHtml = "<div class='ce_accordion block'> \n <div class='toggler'> \n <span class='toggler_first'>";
        $strHtml .= $title;
        $strHtml .= "</span> \n </div> \n <div class='accordion'> \n <div> \n";
        $strHtml .= $content;
        $strHtml .= " \n </div> \n </div> \n </div>";
        return $strHtml;
    }

     /**
	 * Liefert eine Tabellenzeile
	 * @param string
     * @param string
     * @param string
     * @param bolean
	 * @return string
	 */
    protected function getTableRow($title, $content, $help = "", $colspan=false)
    {
        $strHtml = "<tr> \n";
        if ($colspan)
        {
            $strHtml .= "<td colspan='2'><b>" . $title . "</b></td> \n";
            $strHtml .= "<tr> \n <td colspan='2'>" . $content . "</td> \n </tr> \n";
        }
        else
        {
            $strHtml .= "<td class='info_left'><b>" . $title . "<b></td><td class='info_right'>" . $content;
            if ($help != "")
            {
                $strHtml .= " \n <a class='tooltip' title='" . $content . "!' rel='" . $help . "'> \n";
                $strHtml .= "<img src='system/modules/caves/assets/img/help16.png' alt='Info' width='16' height='16' /> \n";
                $strHtml .= "</a> \n";
            }
            $strHtml .= "</td> \n";    
        }
        
        $strHtml .="</tr> \n";
        return $strHtml;
    }
    
     /**
	 * Erstellt eine Tabelle
	 * @param array
	 * @return string
	 */
    protected function getTable($arrContent)
    {
        $strHtml = "<table> \n";
        foreach ($arrContent as $key => $value)
        {   
            // Prüfen ob Spalten verbunden werden
            $colspan = (strpos($key,"#c#")) ? true : false;
            list($content,$help) = explode(",", $value);
            $strHtml .= $this->getTableRow($GLOBALS['TL_LANG']['CAVE'][$key],str_replace("#",",",$content),str_replace("#",",",$help),$colspan);
        }
        $strHtml .= "</table> \n";
        return $strHtml;
    }

    protected function getList($arrContent)
    {
        $strHtml = "<ul> \n";
        foreach ($arrContent as $key =>$value)
        {   
            $strHtml .= "<li>" . $value['title'];
            if ($value['description'] != "")
            {
                $strHtml .= " \n <a class='tooltip' title='" . $value['title'] . "!' rel='" . $value['description'] . "'> \n";
                $strHtml .= "<img src='system/modules/caves/assets/img/help16.png' alt='Info' width='16' height='16' /> \n";
                $strHtml .= "</a> \n";
            }
            $strHtml .= "</li> \n";
        }
        $strHtml .= "</ul> \n";
        return $strHtml;
    }
  
    protected function cave_getLink($arrLink)
    {
        $newArray = array();
        foreach($arrLink as $key => $value)
        {
            array_push($newArray, array('title' => "<a href='" . $value['url'] ."' target='_blank'>" . $value['titleText'] . "</a>",'description'=>""));    
        }
        return $newArray;
    }
    
    


    protected function getValScala($level)
    {
        $range = 180;
        $step = ($range / 6);
        $pointer = ($level * $step - ($step / 2))-2;
        $strHtml = "<div class='valuation'>\n";
        $strHtml .= "1 <span class='scale' title='Schulnote: " . $level . "'><span class='pointer' style='margin-left:" .  $pointer . "px;' title='Schulnote: " . $level . "'></span></span> 6\n";
        $strHtml .= "</div>\n";
        
        return $strHtml;
    } 
}
