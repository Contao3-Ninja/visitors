<?php 

/**
 * Extension for Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 * 
 * Visitors Banner - Backend DCA tl_visitors_category
 *
 * This is the data container array for table tl_visitors_category.
 *
 * @copyright  Glen Langer 2009..2013 <http://www.contao.glen-langer.de>
 * @author     Glen Langer (BugBuster)
 * @licence    LGPL
 * @filesource
 * @package    GLVisitors
 * @see	       https://github.com/BugBuster1701/visitors
 */

/**
 * Table tl_visitors_category 
 */
$GLOBALS['TL_DCA']['tl_visitors_category'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_visitors'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id'  => 'primary'
            )
        )
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'search,limit'
		),
		'label' => array
		(
			'fields'                  => array('tag'),
			'format'                  => '%s',
			'label_callback'		  => array('BugBuster\Visitors\DCA_visitors_category', 'labelCallback'),
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_visitors_category']['edit'],
				'href'                => 'table=tl_visitors',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
		        'label'               => &$GLOBALS['TL_LANG']['tl_visitors_category']['editheader'],
		        'href'                => 'act=edit',
		        'icon'                => 'header.gif',
		        'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_visitors_category']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_visitors_category']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['tl_visitors_category']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_visitors_category']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'stat' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_visitors_category']['stat'],
				'href'                => 'do=visitorstat',
				'icon'                => 'system/modules/visitors/assets/iconVisitor.png'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		//'__selector__'                => array(''),
		'default'                     => '{title_legend},title;{cache_legend:hide},visitors_cache_mode'
	),

	// Subpalettes
	/**'subpalettes' => array
	(
		''                            => ''
	),**/

	// Fields
	'fields' => array
	(
    	'id' => array
    	(
    	        'sql'       => "int(10) unsigned NOT NULL auto_increment"
    	),
    	'tstamp' => array
    	(
    	        'sql'       => "int(10) unsigned NOT NULL default '0'"
    	),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_visitors_category']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'sql'                     => "varchar(60) NOT NULL default ''",
			'eval'                    => array('mandatory'=>true, 'maxlength'=>60, 'tl_class'=>'w50')
		),
		'visitors_cache_mode' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_visitors_category']['visitors_cache_mode'],
			'exclude'                 => true,
			'default'                 => '1',
			'inputType'               => 'radio',
			'options'                 => array('1', '2'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_visitors_category'],
			'sql'                     => "tinyint(3) unsigned NOT NULL default '1'",
			'eval'                    => array('mandatory'=>true)
		)
	)
);

