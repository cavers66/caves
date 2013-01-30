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
	 * Aufbau der Informationsspalte. Es werden nur Daten angezeigt, für die entsprechende Einträge in der Tabelle sind
	 *@param object 
     *@return string
	 */
    protected function getInfo($dbObject)
    {
        
        $info = array
        (
            'cadaster'    => array
                (
                    'cadastrenumber'   => ($dbObject->cadastrenumber == "")                        ? NULL : $dbObject->cadastrenumber,
                    'map'              => ($dbObject->mapsheet < 1 )                               ? NULL : $dbObject->map_mapnumber . ' ' . $dbObject->map_city
                ),
            'address'     => array
                (
                    'city'             => ($dbObject->postal == "" | $dbObject->city == "")        ? NULL : $dbObject->postal . " " . $dbObject->city,
                    'street'           => ($dbObject->street == "")                                ? NULL : $dbObject->street,
                    'coordinates'      => ($dbObject->latitude == 0 || $dbObject->longitude == 0)  ? NULL : ($dbObject->isSecure == true) ? NULL : $this->dgToGms($dbObject->latitude, $dbObject->longitude),
                    'altitude'         => ($dbObject->altitude == 0)                               ? NULL : $dbObject->altitude . " ". $GLOBALS['TL_LANG']['CAVE']['meter'],
                    'directions#c#'    => ($dbObject->directions == "")                            ? NULL : str_replace(",","#",$dbObject->directions)
                ),
            'cavedata'    => array
            (
                'category'         => ($dbObject->category < 1)                                ? NULL : $dbObject->cat_title . "," . str_replace(",","#",$dbObject->cat_description),
                'mainlength'       => ($dbObject->mainlength == 0)                             ? NULL : $dbObject->mainlength . " ". $GLOBALS['TL_LANG']['CAVE']['meter'],
                'totallength'      => ($dbObject->totallength == 0)                            ? NULL : $dbObject->totallength . " ". $GLOBALS['TL_LANG']['CAVE']['meter']
            ),
            'access'      => array
            (
                'islock#c#'        => ($dbObject->islock != 1)                                 ? NULL : $GLOBALS['TL_LANG']['CAVE']['from'] . " " . $dbObject->lockofday . ". " . $GLOBALS['TL_LANG']['MONTHS'][$dbObject->lockofmonth] . "  " . " ". $GLOBALS['TL_LANG']['CAVE']['to'] . " ". $dbObject->locktoday . ". " . $GLOBALS['TL_LANG']['MONTHS'][$dbObject->locktomonth],                                        
                'notpublicopen#c#' => ($dbObject->notpublicopen != 1)                          ? NULL : str_replace(",","#",($dbObject->contact))
            ),
            'statistics'  => array
            (
                'evaluation'       => ($dbObject->evaluation == "")                            ? NULL : $this->getValScala($dbObject->evaluation),
                'difficulty'       => ($dbObject->difficulty == "")                            ? NULL : $this->getValScala($dbObject->difficulty),
                'wheelinglife'     => ($dbObject->wheelinglife == "")                          ? NULL : str_replace(",","#",($dbObject->wheelinglife) . " " . $GLOBALS['TL_LANG']['CAVE']['hours'])
            ),
            'links'                => ($dbObject->links == NULL)                               ? NULL : $this->cave_getLink(deserialize($dbObject->links)),
            'equipment'            => $this->cave_getEquipment(deserialize($dbObject->equipment))
        ); 
    
        $strHtml = "";
        // Katasterdaten
        if(count($this->getArray($info['cadaster'])) != 0)
            $strHtml .= $this->getArcordeon($GLOBALS['TL_LANG']['CAVE']['cadastre_legend'], $this->getTable($this->getArray($info['cadaster'])));

        // Adresse & Koordinaten
        if(count($this->getArray($info['address'])) != 0)
            $strHtml .= $this->getArcordeon($GLOBALS['TL_LANG']['CAVE']['address_legend'], $this->getTable($this->getArray($info['address'])));

        // Höhlendaten
        if(count($this->getArray($info['cavedata'])) != 0)
            $strHtml .= $this->getArcordeon($GLOBALS['TL_LANG']['CAVE']['data_legend'], $this->getTable($this->getArray($info['cavedata'])));

        // Fledermausschutz & Zugang
        if(count($this->getArray($info['access'])) != 0)
            $strHtml .= $this->getArcordeon($GLOBALS['TL_LANG']['CAVE']['access_legend'], $this->getTable($this->getArray($info['access'])));

        // Bewertung & Zeit
        if(count($this->getArray($info['statistics'])) != 0)
            $strHtml .= $this->getArcordeon($GLOBALS['TL_LANG']['CAVE']['statistic_legend'], $this->getTable($this->getArray($info['statistics'])));

        // Ausrüstung
        $strHtml .= $this->getArcordeon($GLOBALS['TL_LANG']['CAVE']['equipment_legend'], $this->getList($info['equipment']));

        // Links
        if(is_array($info['links']))
            $strHtml .= $this->getArcordeon($GLOBALS['TL_LANG']['CAVE']['links_legend'],$this->getList($info['links']));
        
        return $strHtml;
    }  
    
    
    
    
    
    	/**
	 * Generate the module
	 */   
    protected function compile()
	{                            
        $alias = \Input::get('items');
        $result = $this->Database
                        ->prepare("SELECT cave.*, map.mapnumber AS 'map_mapnumber', map.city AS 'map_city', cat.title AS 'cat_title', cat.description AS 'cat_description'
                                   FROM tl_cave cave
                                   LEFT JOIN (tl_cave_mapsheets map)  
                                   ON (map.id=cave.mapsheet)
                                   LEFT JOIN (tl_cave_category cat)
                                   ON (cat.id=cave.category) 
                                   WHERE cave.alias=?")
                        ->execute($alias);
                         
        $this->Template->headline = $result->name;
        $this->Template->image = $this->cave_getImage($result->singleSRC, $result->pid);
        $this->Template->info = $this->getInfo($result);
        $this->Template->description = $result->description;   
        $this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
    }

  
}
