<?php
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 * 
 * Modul Visitors Stat - Backend Referrer Details
 *
 * 
 * PHP version 5
 * @copyright  Glen Langer 2007..2012
 * @author     Glen Langer
 * @package    GLVisitors
 * @license    LGPL
 * @filesource
 */

/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require('../../initialize.php');

/**
 * Class ModuleVisitorReferrerDetails
 *
 * @copyright  Glen Langer 2007..2012
 * @author     Glen Langer
 * @package    GLVisitors
 */
class ModuleVisitorReferrerDetails extends Backend // Backend bringt DB mit
{
   
    /**
	 * Set the current file
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct(); 
		$this->User->authenticate(); 
	    $this->loadLanguageFile('default');
		$this->loadLanguageFile('modules');
		$this->loadLanguageFile('tl_visitors_referrer'); 
	}
	
    public function run()
	{
   	    if ( is_null( $this->Input->get('tl_referrer',true) ) || 
   	         is_null( $this->Input->get('tl_vid',true) ) )
   	    {
   	        echo "<html><body>".$GLOBALS['TL_LANG']['tl_visitors_referrer']['no_referrer']."</body></html>";
            return ;
	    }
	    
	    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<!--

	This website is powered by Contao Open Source CMS :: Licensed under GNU/LGPL
	Copyright ©2005-2012 by Leo Feyer :: Extensions are copyright of their respective owners
	Visit the project website at http://www.contao.org for more information

//-->
<base href="'.$this->Environment->base.'"></base>
<title>Contao Open Source CMS '.VERSION.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="system/themes/'.$this->getTheme().'/basic.css" media="screen" />
<link rel="stylesheet" type="text/css" href="system/themes/'.$this->getTheme().'/main.css" media="screen" />
';
	    if (version_compare(VERSION, '2.11', '<'))
	    {
	        echo '<link rel="stylesheet" type="text/css" href="system/themes/'.$this->getTheme().'/be27.css" media="screen" />
';
	    }
echo '<!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="system/themes/'.$this->getTheme().'/iefixes.css" media="screen" /><![endif]-->
';
		if (version_compare(VERSION, '2.10', '<'))
		{
			echo '<!--[if IE 8]><link type="text/css" rel="stylesheet" href="system/themes/'.$this->getTheme().'/ie8fixes.css" media="screen" /><![endif]-->';
		}
echo '
<link rel="stylesheet" type="text/css" href="system/modules/visitors/mod_visitors_be.css" media="all" />
</head>
<body id="top">
<div id="main">
	<br />
	<h1 class="main_headline">'.$GLOBALS['TL_LANG']['tl_visitors_referrer']['details_for'].': '.str_rot13($this->Input->get('tl_referrer',true)).'</h1>
	<br /><br />
	<div class="tl_formbody_edit">
		<table cellpadding="0" cellspacing="0" summary="Table lists records" class="mod_visitors_be_table_max">
		<tbody>
			<tr>
				<td style="text-align: center;" class="tl_folder_tlist">'.$GLOBALS['TL_LANG']['tl_visitors_referrer']['visitor_referrer'].'</td>
				<td style="width: 120px; padding-left: 2px;" class="tl_folder_tlist">'.$GLOBALS['TL_LANG']['tl_visitors_referrer']['visitor_referrer_last_seen'].'</td>
				<td style="width: 80px; padding-left: 2px; text-align: center;" class="tl_folder_tlist">'.$GLOBALS['TL_LANG']['tl_visitors_referrer']['number'].'</td>
			</tr>';
		/*$objDetails = $this->Database->prepare("SELECT `visitors_referrer_full`, count(id) as ANZ"
						                     . " FROM `tl_visitors_referrer`"
						                     . " WHERE `visitors_referrer_dns` = ?"
						                     . " AND `vid` = ?"
						                     . " GROUP BY 1 ORDER BY 2 DESC")*/
		$objDetails = $this->Database->prepare("SELECT `visitors_referrer_full`, count(id) as ANZ, max(`tstamp`) as maxtstamp"
						                     . " FROM `tl_visitors_referrer`"
						                     . " WHERE `visitors_referrer_dns` = ?"
						                     . " AND `vid` = ?"
						                     . " GROUP BY 1 ORDER BY 2 DESC")
        			       ->execute(str_rot13($this->Input->get('tl_referrer',true)),$this->Input->get('tl_vid',true));
		$intRows = $objDetails->numRows;
		if ($intRows > 0) {
	        while ($objDetails->next())
	        {
				echo '
			<tr>
				<td class="tl_file_list" style="padding-left: 2px; text-align: left;">'.rawurldecode(htmlspecialchars($objDetails->visitors_referrer_full)).'</td>
				<td class="tl_file_list" style="padding-left: 2px; text-align: left;">'.date($GLOBALS['TL_CONFIG']['datimFormat'],$objDetails->maxtstamp).'</td>
				<td class="tl_file_list" style="text-align: center;">'.$objDetails->ANZ.'</td>
			</tr>';
	        }
        } else {
        	echo '
        	<tr>
				<td colspan="3">'.$GLOBALS['TL_LANG']['tl_visitors_referrer']['no_data'].'</td>
			</tr>';
        }
	    echo '
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		</tbody>
		</table>
	</div>
</div>
<div class="clear"></div>
</body>
</html>';

	} // run
}

/**
 * Instantiate
 */
$objModuleVisitorReferrerDetails = new ModuleVisitorReferrerDetails();
$objModuleVisitorReferrerDetails->run();

?>