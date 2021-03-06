<?php 

/**
 * Extension for Contao Open Source CMS, Copyright (C) 2005-2015 Leo Feyer
 * 
 * Modul Visitors Config File
 *
 * @copyright  Glen Langer 2009..2021 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @licence    LGPL
 * @filesource
 * @package    GLVisitors
 * @see	       https://github.com/BugBuster1701/visitors
 */

define('VISITORS_VERSION', '3.8');
define('VISITORS_BUILD'  , '8');

/**
 * Backend css version number are added to style sheets files to make
 * the web browser reload those resources after a Visitors update.
 * 
 */
define('VISITORS_BE_CSS', '3.8.0');

/**
 * -------------------------------------------------------------------------
 * BACK END MODULES
 * -------------------------------------------------------------------------
 */
$GLOBALS['BE_MOD']['content']['visitors'] = array
(
	'tables'     => array('tl_visitors_category', 'tl_visitors'),
	'icon'       => 'system/modules/visitors/assets/iconVisitor.png',
	'stylesheet' => 'system/modules/visitors/assets/mod_visitors_be_'.VISITORS_BE_CSS.'.css'
);

$GLOBALS['BE_MOD']['system']['visitorstat'] = array
(
	'callback'   => 'Visitors\ModuleVisitorStat',
	'icon'       => 'system/modules/visitors/assets/iconVisitor.png',
	'stylesheet' => 'system/modules/visitors/assets/mod_visitors_be_'.VISITORS_BE_CSS.'.css'
);

/**
 * -------------------------------------------------------------------------
 * FRONT END MODULES
 * -------------------------------------------------------------------------
 */
array_insert($GLOBALS['FE_MOD']['miscellaneous'], 0, array
(
	'visitors' => 'Visitors\ModuleVisitors',
));

/**
 * -------------------------------------------------------------------------
 * HOOKS
 * -------------------------------------------------------------------------
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('Visitors\ModuleVisitorsTag', 'replaceInsertTagsVisitors');
