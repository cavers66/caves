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
 * Class ModuleCaveDetails 
 *
 * @copyright  Ralf Rötzer 2013 
 * @author     Ralf Rötzer 
 * @package    caves
 */
class ModuleCaveDetails extends ModuleCave 
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'cave_cavedetails';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### CAVES DETAILS ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
    
        // Set the item from the auto_item parameter
		if ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		return parent::generate();
    
    }

        
    	/**
	 * Generate the module
	 */   
    protected function compile()
	{                            
        $alias = \Input::get('items');
                                 
        $objCave = \CaveModel::findPublishedByIdOrAlias($alias);
        $arrMap = $objCave->getRelated('mapsheet')->row(); // Daten des Feldes  "mapsheet' aus der Tabelle tl_cave_mapsheets
        $arrCat = $objCave->getRelated('category')->row(); // Daten des Feldes  "category' aus der Tabelle tl_cave_category
        $arrCave = array();
        
        if($objCave !== null)
        {
            foreach($objCave->row() as $strKey => $strValue)
            {
                if (!empty($strValue))
                {                                 
                    $GLOBALS['TL_DEBUG']['meineVariable1'][] = $strKey . " = " . $strValue;
                    switch ($strKey)
                    {
                        // Katasterdaten
                        case ($strKey=='cadastrenumber' || $strKey=='mapsheet'):               
                            $arrCave['cadastre_legend'] = TRUE;
                            $arrCave[$strKey] = $strValue;
                            if ($strKey=='mapsheet')
                            {
                                // Werte aus Tabelle tl_map_sheets eintragen
                                $arrCave['mapsheet'] = $arrMap['mapnumber'] . ' ' . $arrMap['city'];   
                            }
                            break;
                        // Adresse & Koordinaten
                        case ($strKey=='postal' || $strKey=='city' || $strKey=='street' || $strKey=='altitude' || $strKey=='directions'):
                            $arrCave['address_legend'] = True;
                            $arrCave[$strKey] = $strValue;
                            break;
                        case ($strKey=='latitude' || $strKey=='longitude' || $strKey=='isSecure' ):
                            $arrCave[$strKey] = $strValue;
                            break;
                        // Höhlendaten
                        case ($strKey=='category' || $strKey=='mainlength' || $strKey=='totallength'):
                            $arrCave['data_legend'] = TRUE;
                            $arrCave[$strKey] = $strValue;
                            // Werte aus Tabelle tl_map_category eintragen
                            if ($strKey=='category')
                            {
                                $arrCave['category'] = $arrCat['title'];
                                $arrCave['c_description'] = $arrCat['description'];    
                            }
                            break;
                        // Fledermausschutz & Zugang
                        case ($strKey=='islock' || $strKey=='lockofday' || $strKey=='lockofmonth' || $strKey=='locktoday' || $strKey=='locktomonth' || $strKey=='notpublicopen' || $strKey=='contact'):   
                            $arrCave['access_legend'] = TRUE;
                            $arrCave[$strKey] = $strValue;
                            break;
                        // Statistische Daten
                        case ($strKey=='evaluation' || $strKey=='difficulty' || $strKey=='wheelinglife'):
                            $arrCave['statistic_legend'] = TRUE;
                            $arrCave[$strKey] = $strValue;
                            if ($strKey=='evaluation' || $strKey=='difficulty')
                            {
                                $arrCave[$strKey .'_point'] = $this->getRatingPoint($strValue);    
                            }
                            
                            break;
                        // Ausrüstung
                        case 'equipment':
                            $arrCave['equipment_legend'] = TRUE;
                            // Daten aus Tabelle tl_cave_equipment holen
                            $objEqu = \CaveEquipmentModel::findMultipleByTitle(deserialize($strValue));
                            if($objEqu !== null)
                            {
                                while ($objEqu->next())
                                {
                                    $arrCave['equipment'][]=$objEqu->row();
                                }    
                            }
                            break;
                        // Links
                        case 'links':
                            $arrCave['links_legend'] = TRUE;
                            // Links Deserealisieren
                            $arrCave[$strKey] = deserialize($strValue) ; 
                            break;
                    }
                }                    
            }                      
            // Prüfen ob Koordinaten geschützt sind
            if (isset($arrCave['latitude']) && isset($arrCave['longitude']))
            {
                // Wenn gesetzt Koordinaten verbergen
                if(isset($arrCave['isSecure']))
                {
                    unset($arrCave['latitude']);
                    unset($arrCave['longitude']);     
                }
                else
                {
                    $arrCave['address_legend'] = TRUE;
                    $arrCave['coordinates'] = $this->dgToGms($arrCave['latitude'],$arrCave['longitude']);
                    unset($arrCave['latitude']);
                    unset($arrCave['longitude']);    
                }    
            }
            // Prüfen ob Höhle zum Fledermausschutz verschlossen wird
            if (isset($arrCave['islock']))
            {
                $arrCave['lockdate'] = $GLOBALS['TL_LANG']['CAVE']['from'] . " " . $arrCave['lockofday']  . ". " . $GLOBALS['TL_LANG']['MONTHS'][$arrCave['lockofmonth']] . "  " . " ". $GLOBALS['TL_LANG']['CAVE']['to'] . " ". $arrCave['locktoday'] . ". " . $GLOBALS['TL_LANG']['MONTHS'][$arrCave['locktomonth']];
                unset($arrCave['lockofday']);
                unset($arrCave['lockofmonth']);
                unset($arrCave['locktoday']);
                unset($arrCave['locktomonth']);
            }  
        }                
    
        $this->Template->headline = $objCave->name;
        $this->Template->image = $this->cave_getImage($objCave->singleSRC, $objCave->pid);
        $this->Template->info = $arrCave;
        $this->Template->description = $objCave->description;   
        $this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
   
    } 
  
}
