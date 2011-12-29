<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 * 
 * Modul Visitors File - Frontend
 *
 * PHP version 5
 * @copyright  Glen Langer 2009..2011
 * @author     Glen Langer 
 * @package    GLVisitors 
 * @license    LGPL 
 * @filesource
 */


/**
 * Class ModuleVisitors 
 *
 * @copyright  Glen Langer 2009..2011
 * @author     Glen Langer 
 * @package    GLVisitors
 * @license    LGPL 
 */
class ModuleVisitors extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_visitors_fe_all';
	
	protected $useragent_filter = '';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### VISITORS LIST ###';
			$objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            if (version_compare(VERSION . '.' . BUILD, '2.8.9', '>'))
			{
			   // Code für Versionen ab 2.9.0
			   $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			}
			else
			{
			   // Code für Versionen < 2.9.0
			   $objTemplate->wildcard = '### VISITORS MODUL ONLY FOR CONTAO 2.9 ###';
			}
			return $objTemplate->parse();
		}
		//alte und neue Art gemeinsam zum Array bringen
		if (strpos($this->visitors_categories,':') !== false) 
		{
			$this->visitors_categories = deserialize($this->visitors_categories, true);
		} else {
			$this->visitors_categories = array($this->visitors_categories);
		}
		// Return if there are no categories
		if (!is_array($this->visitors_categories) || !is_numeric($this->visitors_categories[0]))
		{
			return '';
		}
		$this->useragent_filter = $this->visitors_useragent;
		return parent::generate();
	}
	

	/**
	 * Generate module
	 */
	protected function compile()
	{																						//visitors_template
		$objVisitors = $this->Database->prepare("SELECT tl_visitors.id AS id, visitors_name, visitors_startdate, visitors_average"
		                                      ." FROM tl_visitors LEFT JOIN tl_visitors_category ON (tl_visitors_category.id=tl_visitors.pid)"
		                                      ." WHERE pid=? AND published=?" 
		                                      ." ORDER BY id, visitors_name")
		                              ->limit(1)
								      ->execute($this->visitors_categories[0],1);
		if ($objVisitors->numRows < 1)
		{
			$this->strTemplate = 'mod_visitors_error';
			$this->Template = new FrontendTemplate($this->strTemplate); 
			$this->log("ModuleVisitors User Error: no published counter found.",'ModulVisitors compile', TL_ERROR);
			return;
		}
       
		$arrVisitors = array();

		while ($objVisitors->next())
		{
		    //if (($objVisitors->visitors_template != $this->strTemplate) && ($objVisitors->visitors_template != '')) {
		    if (($this->visitors_template != $this->strTemplate) && ($this->visitors_template != '')) {
                $this->strTemplate = $this->visitors_template;
                $this->Template = new FrontendTemplate($this->strTemplate); 
		    }
		    if ($this->strTemplate != 'mod_visitors_fe_invisible') {
		    	//VisitorsStartDate
	            if (!strlen($objVisitors->visitors_startdate)) {
			    	$VisitorsStartDate = false;
			    } else {
			        $VisitorsStartDate = true;
			    } 
			    if ($objVisitors->visitors_average) {
			    	$VisitorsAverageVisits = true;
			    } else {
	                $VisitorsAverageVisits = false;
	            } 
			    $arrVisitors[] = array
				(
	                'VisitorsName'        => trim($objVisitors->visitors_name),
	                'VisitorsKatID'       => $this->visitors_categories[0],
	                'VisitorsStartDate'   => $VisitorsStartDate, 
	                'AverageVisits'       => $VisitorsAverageVisits, 
	                'VisitorsNameLegend'        => $GLOBALS['TL_LANG']['visitors']['VisitorsNameLegend'],
	                'VisitorsOnlineCountLegend' => $GLOBALS['TL_LANG']['visitors']['VisitorsOnlineCountLegend'],
	                'VisitorsStartDateLegend'   => $GLOBALS['TL_LANG']['visitors']['VisitorsStartDateLegend'],
	                'TotalVisitCountLegend'     => $GLOBALS['TL_LANG']['visitors']['TotalVisitCountLegend'],
	                'TotalHitCountLegend'       => $GLOBALS['TL_LANG']['visitors']['TotalHitCountLegend'],
	                'TodayVisitCountLegend'     => $GLOBALS['TL_LANG']['visitors']['TodayVisitCountLegend'],
	                'TodayHitCountLegend'       => $GLOBALS['TL_LANG']['visitors']['TodayHitCountLegend'],
	                'AverageVisitsLegend'       => $GLOBALS['TL_LANG']['visitors']['AverageVisitsLegend']
				);
			} else {
				// invisible, but counting!
				$arrVisitors[] = array('VisitorsKatID' => $this->visitors_categories[0]);
			}
		}
		$this->Template->visitors = $arrVisitors;
	} // compile
} // class

?>