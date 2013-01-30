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
 * Class ModuleCaveArchive 
 *
 * @copyright  Ralf Rötzer 2013 
 * @author     Ralf Rötzer 
 * @package    caves
 */
class ModuleCaveArchive extends ModuleCave 
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_cavearchive';

    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### CAVES ARCHIVE ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->cave_archives = $this->sortOutProtected(deserialize($this->cave_archives));
        
		// No cave archives available
		if (!is_array($this->cave_archives) || empty($this->cave_archives))
		{
			return '';
		}

		// Show the cave reader if an item has been selected
		if ($this->cave_readerModule > 0 && (isset($_GET['items']) || ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->cave_readerModule, $this->strColumn);
		}

		// Hide the module if no period has been selected
		/* if ($this->news_jumpToCurrent == 'hide_module' && !isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']))
		{
			return '';
		} */

		return parent::generate();
	}
	    
    
    /**
	 * Generate the module
	 */
	protected function compile()
	{
	    global $objPage;

		$limit = null;
		$offset = 0;
		$intBegin = 0;
		$intEnd = 0;

		// Jump to the current period
		if (!isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']) && $this->cave_jumpToCurrent != 'all_items')
		{
			switch ($this->cave_format)
			{
				case 'cave_year':
					\Input::setGet('year', date('Y'));
					break;

				default:
				case 'cave_month':
					\Input::setGet('month', date('Ym'));
					break;

				case 'cave_day':
					\Input::setGet('day', date('Ymd'));
					break;
			}
		}

		// Display year
		if (\Input::get('year'))
		{
			$strDate = \Input::get('year');
			$objDate = new \Date($strDate, 'Y');
			$intBegin = $objDate->yearBegin;
			$intEnd = $objDate->yearEnd;
			$this->headline .= ' ' . date('Y', $objDate->tstamp);
		}
		// Display month
		elseif (\Input::get('month'))
		{
			$strDate = \Input::get('month');
			$objDate = new \Date($strDate, 'Ym');
			$intBegin = $objDate->monthBegin;
			$intEnd = $objDate->monthEnd;
			$this->headline .= ' ' . $this->parseDate('F Y', $objDate->tstamp);
		}
		// Display day
		elseif (\Input::get('day'))
		{
			$strDate = \Input::get('day');
			$objDate = new \Date($strDate, 'Ymd');
			$intBegin = $objDate->dayBegin;
			$intEnd = $objDate->dayEnd;
			$this->headline .= ' ' . $this->parseDate($objPage->dateFormat, $objDate->tstamp);
		}
		// Show all items
		elseif ($this->cave_jumpToCurrent == 'all_items')
		{
			$intBegin = 0;
			$intEnd = time();
		}

		$this->Template->articles = array();

		// Split the result
		if ($this->perPage > 0)
		{
			// Get the total number of items
			$intTotal = \CaveModel::countPublishedFromToByPids($intBegin, $intEnd, $this->cave_archives);

			if ($intTotal > 0)
			{
				$total = $intTotal;

				// Get the current page
				$id = 'page_a' . $this->id;
				$page = \Input::get($id) ?: 1;

				// Do not index or cache the page if the page number is outside the range
				if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
				{
					global $objPage;
					$objPage->noSearch = 1;
					$objPage->cache = 0;

					// Send a 404 header
					header('HTTP/1.1 404 Not Found');
					return;
				}

				// Set limit and offset
				$limit = $this->perPage;
				$offset = (max($page, 1) - 1) * $this->perPage;

				// Add the pagination menu
				$objPagination = new \Pagination($total, $this->perPage, 7, $id);
				$this->Template->pagination = $objPagination->generate("\n  ");
			}
		}

		// Get the cave items
		if (isset($limit))
		{
			$objArticles = \CaveModel::findPublishedFromToByPids($intBegin, $intEnd, $this->cave_archives, $limit, $offset);
		}
		else
		{
			$objArticles = \CaveModel::findPublishedFromToByPids($intBegin, $intEnd, $this->cave_archives);
		}

		// No items found
		if ($objArticles === null)
		{
			$this->Template = new \FrontendTemplate('mod_cavearchive_empty');
		}
		else
		{
			$this->Template->articles = $this->parseArticles($objArticles);
		}

		$this->Template->headline = trim($this->headline);
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['empty'];	
	}
}
