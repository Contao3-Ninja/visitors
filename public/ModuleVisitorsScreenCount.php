<?php 

/**
 * Contao Open Source CMS, Copyright (C) 2005-2014 Leo Feyer
 * 
 * Modul Visitors Screen Count - Frontend for Screen Counting
 *
 * @copyright  Glen Langer 2012..2014 <http://www.contao.glen-langer.de>
 * @author     Glen Langer (BugBuster)
 * @licence    LGPL
 * @filesource
 * @package    GLVisitors
 * @see	       https://github.com/BugBuster1701/visitors 
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace BugBuster\Visitors;
use BugBuster\Visitors\ModuleVisitorLog;

/**
 * Initialize the system
 */
define('TL_MODE', 'FE');

$dir = __DIR__;

while ($dir != '.' && $dir != '/' && !is_file($dir . '/system/initialize.php'))
{
    $dir = dirname($dir);
}

if (!is_file($dir . '/system/initialize.php'))
{
    echo 'Could not find initialize.php!';
    exit(1);
}
require($dir . '/system/initialize.php');


/**
 * Class ModuleVisitorsScreenCount 
 *
 * @copyright  Glen Langer 2012..2014
 * @author     Glen Langer 
 * @package    GLVisitors
 * @license    LGPL 
 */
class ModuleVisitorsScreenCount extends \Frontend  
{
	private $_PF  = false; // Prefetch found
	
	private $_SCREEN = false; // Screen Resolution
	
	/**
	 * Initialize object 
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function run()
	{
		//Parameter holen
		if ((int)\Input::get('vcid')>0) 
		{
			$visitors_category_id = (int)\Input::get('vcid');
			$this->VisitorScreenSetDebugSettings($visitors_category_id);
			//echo "scrw=".\Input::get('scrw')." scrh=".\Input::get('scrh')." scriw=".\Input::get('scriw')." scrih=".\Input::get('scrih');
            $this->_SCREEN = array( "scrw"  => \Input::get('scrw'),
                                    "scrh"  => \Input::get('scrh'),
                                    "scriw" => \Input::get('scriw'),
                                    "scrih" => \Input::get('scrih')
                                    );            
			/* __________  __  ___   _____________   ________
			  / ____/ __ \/ / / / | / /_  __/  _/ | / / ____/
			 / /   / / / / / / /  |/ / / /  / //  |/ / / __  
			/ /___/ /_/ / /_/ / /|  / / / _/ // /|  / /_/ /  
			\____/\____/\____/_/ |_/ /_/ /___/_/ |_/\____/ only
			*/
			$objVisitors = \Database::getInstance()
	                ->prepare("SELECT 
                                    tl_visitors.id AS id, visitors_block_time
                                FROM
                                    tl_visitors
                                LEFT JOIN
                                    tl_visitors_category ON (tl_visitors_category.id = tl_visitors.pid)
                                WHERE
                                    pid = ? AND published = ?
                                ORDER BY id , visitors_name")
                      ->limit(1)
				      ->executeUncached($visitors_category_id,1);
			if ($objVisitors->numRows < 1) 
			{
			    $this->log($GLOBALS['TL_LANG']['tl_visitors']['wrong_katid'], 'ModuleVisitorsScreenCount '. VISITORS_VERSION .'.'. VISITORS_BUILD, TL_ERROR);
			} 
			else 
			{
				while ($objVisitors->next()) 
				{
				    $this->VisitorScreenCountUpdate($objVisitors->id, $objVisitors->visitors_block_time, $visitors_category_id);
				    
				}
			}
		} 
		else 
		{
			$this->log($GLOBALS['TL_LANG']['tl_visitors']['wrong_count_katid'], 'ModuleVisitorsScreenCount '. VISITORS_VERSION .'.'. VISITORS_BUILD, TL_ERROR);
		}
		//log_message('run BOT SE : '.(int)$this->_BOT . '-' . (int)$this->_SE,'debug.log');
		//Pixel und raus hier
		header('Cache-Control: no-cache');
		header('Content-type: image/gif');
		header('Content-length: 43');

		echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
	} //function
	
	/**
	 * Insert/Update Counter
	 */
	protected function VisitorScreenCountUpdate($vid, $BlockTime, $visitors_category_id)
	{
	    ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ': '.print_r($this->_SCREEN, true) );
	    
		$ModuleVisitorChecks = new \Visitors\ModuleVisitorChecks();
		if ($ModuleVisitorChecks->CheckBot() == true) 
		{
			//log_message("VisitorCountUpdate BOT=true","debug.log");
	    	return; //Bot / IP gefunden, wird nicht gezaehlt
	    }
	    if ($ModuleVisitorChecks->CheckUserAgent($visitors_category_id) == true) 
	    {
	    	//log_message("VisitorCountUpdate UserAgent=true","debug.log");
	    	return ; //User Agent Filterung
	    }
	    if ($ModuleVisitorChecks->CheckBE() == true)
	    {
	        return; // Backend eingeloggt, nicht zaehlen (Feature: #197)
	    }
	    //log_message("VisitorCountUpdate count: ".$this->Environment->httpUserAgent,"useragents-noblock.log");
	    $ClientIP = bin2hex(sha1($visitors_category_id . \Environment::get('ip'),true)); // sha1 20 Zeichen, bin2hex 40 zeichen
	    $BlockTime = ($BlockTime == '') ? 1800 : $BlockTime; //Sekunden
	    $CURDATE = date('Y-m-d');

	    //Visitor Screen Blocker
	    \Database::getInstance()
	            ->prepare("DELETE FROM
                                tl_visitors_blocker
                            WHERE
                                CURRENT_TIMESTAMP - INTERVAL ? SECOND > visitors_tstamp
                            AND 
                                vid = ?
                            AND 
                                visitors_type = ?")
                ->executeUncached($BlockTime, $vid, 's');
	    
	    //Blocker IP lesen, sofern vorhanden
	    $objVisitBlockerIP = \Database::getInstance()
	            ->prepare("SELECT 
                                id, visitors_ip
                            FROM
                                tl_visitors_blocker
                            WHERE
                                visitors_ip = ? AND vid = ? AND visitors_type = ?")
                ->executeUncached($ClientIP, $vid, 's');
	    //ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ':\n'.$objVisitBlockerIP->query );
	    //Daten lesen, nur Screen Angaben, die Inner Angaben werden jedesmal überschrieben 
	    $objScreenCounter = \Database::getInstance() 
                    	    ->prepare("SELECT
                                            id, v_screen_counter
                                        FROM
                                            tl_visitors_screen_counter
                                        WHERE
                                            v_date = ? 
                                        AND vid = ? 
                                        AND v_s_w = ? 
                                        AND v_s_h = ?")
                            ->executeUncached($CURDATE, $vid, $this->_SCREEN['scrw'], $this->_SCREEN['scrh']);
	    
	    if ($objScreenCounter->numRows < 1)
	    {
    	    if ($objVisitBlockerIP->numRows < 1) 
    	    {
    	        // Insert IP + Update Visits
    	        \Database::getInstance()
            	        ->prepare("INSERT INTO
                                        tl_visitors_blocker
                                    SET
                                        vid=?,
                                        visitors_tstamp=CURRENT_TIMESTAMP,
                                        visitors_ip=?,
                                        visitors_type=?")
                        ->executeUncached($vid, $ClientIP, 's');
    	        // Insert
    	        $arrSet = array
    	        (
    	            'vid'              => $vid,
    	            'v_date'           => $CURDATE,
    	            'v_s_w'            => $this->_SCREEN['scrw'],
    	            'v_s_h'            => $this->_SCREEN['scrh'],
    	            'v_s_iw'           => $this->_SCREEN['scriw'],
    	            'v_s_ih'           => $this->_SCREEN['scrih'],
    	            'v_screen_counter' => 1
    	        );
    	        \Database::getInstance()
                	        ->prepare("INSERT IGNORE INTO tl_visitors_screen_counter %s")
                	        ->set($arrSet)
                	        ->executeUncached();
    	        ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ': insert into tl_visitors_screen_counter' );
    	        return ;
    	    } 
    	    else 
    	    {
    	        //ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ':'.'Update tstamp' );
    	    	// Update tstamp
    	    	\Database::getInstance()
    	    	        ->prepare("UPDATE
                                        tl_visitors_blocker
                                    SET
                                        visitors_tstamp=CURRENT_TIMESTAMP
                                    WHERE
                                        visitors_ip=? AND vid=? AND visitors_type=?")
                        ->executeUncached($ClientIP, $vid, 's');
    	    	ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ': update tl_visitors_blocker' );
    	    	return ;
    	    }
	    }
	    else 
	    {
	        //ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ':'.$objScreenCounter->numRows );
	        if ($objVisitBlockerIP->numRows < 1)
	        {
	            //ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ':'.$objVisitBlockerIP->numRows );
	            // Insert IP
	            \Database::getInstance()
                            ->prepare("INSERT INTO
                                             tl_visitors_blocker
                                        SET
                                            vid=?,
                                            visitors_tstamp=CURRENT_TIMESTAMP,
                                            visitors_ip=?,
                                            visitors_type=?")
                            ->executeUncached($vid, $ClientIP, 's');
	         
    	        $objScreenCounter->next();
    	        //Update der Screen Counter, Inner Daten dabei aktualisieren
                \Database::getInstance()
                	        ->prepare("UPDATE
                            	            tl_visitors_screen_counter
                        	            SET
                            	            v_s_iw = ?,
                            	            v_s_ih = ?,
                                            v_screen_counter = ?
                        	            WHERE
                            	            v_date = ? 
                                        AND vid = ? 
                                        AND v_s_w = ? 
                                        AND v_s_h = ?")
                	        ->executeUncached($this->_SCREEN['scriw'],
                                              $this->_SCREEN['scrih'],
                                              $objScreenCounter->v_screen_counter +1,
                                              $CURDATE, 
                                              $vid,
                                              $this->_SCREEN['scrw'],
                                              $this->_SCREEN['scrh']
                                              );
                ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ': update tl_visitors_screen_counter' );
	        }
	        else 
	        {
	            //ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ':'.'Update tstamp' );
	            // Update tstamp
	            \Database::getInstance()
                            ->prepare("UPDATE
                                            tl_visitors_blocker
                                        SET
                                            visitors_tstamp=CURRENT_TIMESTAMP
                                        WHERE
                                            visitors_ip=? AND vid=? AND visitors_type=?")
                            ->executeUncached($ClientIP, $vid, 's');
	            ModuleVisitorLog::Writer(__METHOD__ , __LINE__ , ': update tl_visitors_blocker' );
	        }
	    }
	    return ;
	} //VisitorScreenCountUpdate
	
	protected function VisitorScreenSetDebugSettings($visitors_category_id)
	{
	    $GLOBALS['visitors']['debug']['screenresolutioncount'] = false;
	     
	    $objVisitors = \Database::getInstance()
               ->prepare("SELECT
                                visitors_expert_debug_screenresolutioncount
                            FROM
                                tl_visitors
                            LEFT JOIN
                                tl_visitors_category ON (tl_visitors_category.id=tl_visitors.pid)
                            WHERE
                                pid=? AND published=?
                            ORDER BY tl_visitors.id, visitors_name")
	           ->limit(1)
	           ->executeUncached($visitors_category_id,1);
	    while ($objVisitors->next())
	    {
	        $GLOBALS['visitors']['debug']['screenresolutioncount'] = (boolean)$objVisitors->visitors_expert_debug_screenresolutioncount;
	        ModuleVisitorLog::Writer('## START ##', '## SCREEN DEBUG ##', '#S'.(int)$GLOBALS['visitors']['debug']['screenresolutioncount']);
	    }
	}
	
} // class

/**
 * Instantiate controller
 */
$objVisitorsScreenCount = new ModuleVisitorsScreenCount();
$objVisitorsScreenCount->run();