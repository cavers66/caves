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
      
        
    /**
	* Berechnet margin-left für Bewertung u. Schwierigkeitsgrad
	* @param string
    * @return string
	*/ 
    public function getRatingPoint($level)
    {
        $range = 180;
        $step = ($range / 4);
        $point = (($level-1) * $step)-10;
        
        return $point;
    } 


    
}
