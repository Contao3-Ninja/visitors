<?php 

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 * 
 * Modul Visitors Sprachdateien
 * 
 * Language file for table tl_visitors (de).
 *
 * PHP version 5
 * @copyright  Glen Langer 2009..2014 
 * @author     Glen Langer 
 * @package    VisitorsLanguage 
 * @license    LGPL 
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_visitors']['visitors_name']        = array('Name', 'Bitte geben Sie einen Namen an für diesen Besucherzähler.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_startdate']   = array('Startdatum', 'Hier kann ein Startdatum angegeben werden. Dieses Datum wird dann zur Information im Frontend angezeigt.<br>Es beeinflusst nicht den Beginn der Zählung!');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_visit_start'] = array('Startwert Besucher', 'Die eingegebene Zahl wird zu den Zählungen addiert.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_hit_start']   = array('Startwert Zugriffe'  , 'Die eingegebene Zahl wird zu den Zählungen addiert.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_average']     = array('Besucher pro Tag', 'Bei Aktivierung erscheint im Frontend zusätzlich eine Zeile mit der Durchschnittsanzahl der Besucher pro Tag.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_block_time']  = array('Blockzeit', 'Zeit in Sekunden. Nach Zugriffspause dieser Zeit wird ein Zugriff als weiterer Besucher gezählt.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_thousands_separator'] = array('Tausendertrennzeichen','Bei Aktivierung werden im Frontend Tausendertrennzeichen eingefügt.');
$GLOBALS['TL_LANG']['tl_visitors']['published']            = array('Veröffentlicht', 'Der Besucherzähler wird erst auf Ihrer Webseite erscheinen, wenn dieser veröffentlicht ist.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_expert_debug_tag']          = array('Debugmodus für die Klasse "Visitors Tag"'        ,'Bei Aktivierung werden Laufzeitinformationen in die Logdatei (<em>system/logs/visitors_debug.log</em>) geschrieben.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_expert_debug_checks']       = array('Debugmodus für die Klasse "Visitor Check"'       ,'Bei Aktivierung werden Laufzeitinformationen in die Logdatei (<em>system/logs/visitors_debug.log</em>) geschrieben.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_expert_debug_referrer']     = array('Debugmodus für die Klasse "Visitor Referrer"'    ,'Bei Aktivierung werden Laufzeitinformationen in die Logdatei (<em>system/logs/visitors_debug.log</em>) geschrieben.');
$GLOBALS['TL_LANG']['tl_visitors']['visitors_expert_debug_searchengine'] = array('Debugmodus für die Klasse "Visitor SearchEngine"','Bei Aktivierung werden Laufzeitinformationen in die Logdatei (<em>system/logs/visitors_debug.log</em>) geschrieben.');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_visitors']['not_defined']   = 'Nicht definiert';

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_visitors']['title_legend']   = 'Name und Startdatum';
$GLOBALS['TL_LANG']['tl_visitors']['start_legend']   = 'Startwerte für Zähler';
$GLOBALS['TL_LANG']['tl_visitors']['average_legend'] = 'Durchschnitt und Blockzeit';
$GLOBALS['TL_LANG']['tl_visitors']['publish_legend'] = 'Veröffentlichung';
$GLOBALS['TL_LANG']['tl_visitors']['design_legend']  = 'Darstellung';
$GLOBALS['TL_LANG']['tl_visitors']['visitors_expert_legend']  = 'Experteneinstellungen';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_visitors']['new']        = array('Neuer Besucherzähler', 'Neuen Besucherzähler erstellen');
$GLOBALS['TL_LANG']['tl_visitors']['edit']       = array('Besucherzähler bearbeiten', 'Besucherzähler ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_visitors']['copy']       = array('Besucherzähler duplizieren', 'Besucherzähler ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_visitors']['delete']     = array('Besucherzähler löschen', 'Besucherzähler ID %s löschen');
$GLOBALS['TL_LANG']['tl_visitors']['show']       = array('Details', 'Details der Besucherzähler ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_visitors']['editheader'] = array('Kategorie bearbeiten', 'Diese Kategorie bearbeiten');
$GLOBALS['TL_LANG']['tl_visitors']['toggle']     = array('Besucherzähler ein- oder ausschalten', 'Besucherzähler ID %s ein- oder ausschalten');

/**
 * Errorlog
 */
$GLOBALS['TL_LANG']['tl_visitors']['wrong_katid'] = 'Fehlerhafte oder falsche Kategorie ID (Parameter 2)';
$GLOBALS['TL_LANG']['tl_visitors']['wrong_key']   = 'Fehlerhafter oder falscher Tagname (Parameter 3)';
$GLOBALS['TL_LANG']['tl_visitors']['no_key']      = 'Kein Tagname angegeben (Parameter 3)';
$GLOBALS['TL_LANG']['tl_visitors']['wrong_count_katid'] = 'Aufruf ohne Parameter';
$GLOBALS['TL_LANG']['tl_visitors']['no_param4']   = 'Aufruf ohne Parameter 4';
