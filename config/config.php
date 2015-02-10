<?php 

/**
 * Extension for Contao Open Source CMS, Copyright (C) 2005-2014 Leo Feyer
 * 
 * Modul Visitors Config File
 *
 * @copyright  Glen Langer 2009..2014 <http://www.contao.glen-langer.de>
 * @author     Glen Langer (BugBuster)
 * @licence    LGPL
 * @filesource
 * @package    GLVisitors
 * @see	       https://github.com/BugBuster1701/visitors
 */

define('VISITORS_VERSION', '3.4');
define('VISITORS_BUILD'  , '1');

/**
 * -------------------------------------------------------------------------
 * BACK END MODULES
 * -------------------------------------------------------------------------
 */
$GLOBALS['BE_MOD']['content']['visitors'] = array
(
	'tables'     => array('tl_visitors_category', 'tl_visitors'),
	'icon'       => 'system/modules/visitors/assets/iconVisitor.png',
	'stylesheet' => 'system/modules/visitors/assets/mod_visitors_be.css'
);

$GLOBALS['BE_MOD']['system']['visitorstat'] = array
(
	'callback'   => 'Visitors\ModuleVisitorStat',
	'icon'       => 'system/modules/visitors/assets/iconVisitor.png',
	'stylesheet' => 'system/modules/visitors/assets/mod_visitors_be.css'
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
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('Visitors\ModuleVisitorsTag', 'ReplaceInsertTagsVisitors');
