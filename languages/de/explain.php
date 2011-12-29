<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 * 
 * Modul Visitors Sprachdateien
 * 
 * Language file for explains (de).
 *
 * PHP version 5
 * @copyright  Glen Langer 2009..2010
 * @author     Glen Langer
 * @package    VisitorsLanguage
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_LANG']['XPL']['visitors_help'] = array
(
	array
	(
		'Name',
		'Der Name wird im Frontend angezeigt als &Uuml;berschrift.'
	),
	array
	(
		'Startdatum',
		'Das Startdatum dient der reinen Information.<br />'.
		'Es wird im Frontend angezeigt.<br />'.
		'Es beeinflusst nicht den Beginn der Z&auml;hlung!<br />'.
		'Wird die Angabe leer gelassen, wird im Template die entsprechende Zeile ausgeblendet.'
	)
);
$GLOBALS['TL_LANG']['XPL']['visitors_help_module'] = array
(
	array
	(
		'HTTP_USER_AGENT Teilkennung',
		'Mit &Auml;nderung der Browser-Kennung durch ein eindeutigen String '.
		'und Eintragung in dieses Feld kann verhindert werden, '.
		'dass die eigenen Zugriffe mitgez&auml;hlt werden.<br />'.
		'Genaue Anleitung dazu sind im Wiki / Forum zu finden.'
	)
);

?>