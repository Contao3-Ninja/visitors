<?php 

/**
 * Extension for Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 * 
 * Modul Visitors SearchEngine - Frontend
 *
 * @copyright  Glen Langer 2012..2013 <http://www.contao.glen-langer.de>
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

/**
 * Class ModuleVisitorSearchEngine
 * 
 * Check for searchengines in referrer
 *
 * @copyright  Glen Langer 2012..2013 <http://www.contao.glen-langer.de>
 * @author     Glen Langer (BugBuster)
 * @package    GLVisitors
 * @license    LGPL 
 */
class ModuleVisitorSearchEngine// extends Frontend
{
    private $_http_referer  = '';
    private $_search_engine = '';
    private $_keywords      = '';
    private $_parse_result  = '';
    
    const REFERER_UNKNOWN         = 'unknown';
    const SEARCH_ENGINE_UNKNOWN   = 'unknown';
    const KEYWORDS_UNKNOWN        = 'unknown';
    
    const SEARCH_ENGINE_GOOGLE    = 'Google';
    const SEARCH_ENGINE_BING      = 'Bing';
    const SEARCH_ENGINE_YAHOO     = 'Yahoo';
    const SEARCH_ENGINE_TONLINE   = 'T-Online';
    const SEARCH_ENGINE_BAIDU     = 'Baidu.com';
    const SEARCH_ENGINE_ASKCOM    = 'Ask.com';
    const SEARCH_ENGINE_WEBDE     = 'Web.de';
    const SEARCH_ENGINE_GMX       = 'GMX';
    const SEARCH_ENGINE_LYCOS     = 'Lycos';
    const SEARCH_ENGINE_FREENET   = 'Freenet';
    const SEARCH_ENGINE_CONDUIT   = 'Conduit';
    const SEARCH_ENGINE_FORESTLE  = 'Forestle';
    const SEARCH_ENGINE_AOL       = 'AOL';
    const SEARCH_ENGINE_YANDEX    = 'Yandex';
    const SEARCH_ENGINE_BIGFINDER = 'Bigfinder';
    const SEARCH_ENGINE_BABYLON   = 'Babylon';
    const SEARCH_ENGINE_FOXTAB    = 'Foxtab';
    const SEARCH_ENGINE_SEEXIE    = 'Seexie';
    const SEARCH_ENGINE_EXTRABOT  = 'Extrabot';
    const SEARCH_ENGINE_WHORUSH   = 'Whorush';
    const SEARCH_ENGINE_NEWTABKING = 'Newtabking';
    const SEARCH_ENGINE_ECOSIA    = 'Ecosia';
    const SEARCH_ENGINE_SUCHALLES = 'Such Alles';
    const SEARCH_ENGINE_SEARCHICQ = 'Search ICQ';
    const SEARCH_ENGINE_INCREDIMAIL = 'Incredimail';
    const SEARCH_ENGINE_GENERIC     = 'Generic'; // unknown search engine
    const SEARCH_ENGINE_SEARCH_RESULT = 'Search-Result';
    const SEARCH_ENGINE_METACRAWLER   = 'Metacrawler';
    const SEARCH_ENGINE_KENNENSIEMICH = 'Kennensiemich.ch';
    const SEARCH_ENGINE_WERBUNGPUBLICRELATIONS1824 = 'WerbungPublicRelations1824';
    const SEARCH_ENGINE_DUCKDUCKGO = 'DuckDuckGo';
    const SEARCH_ENGINE_SUMAJA     = 'Sumaja';
    const SEARCH_ENGINE_DELICIOUS  = 'Delicious';

    
    /**
	 * Reset all properties
	 */
	protected function reset() 
	{
	    //NEVER TRUST USER INPUT
	    if (function_exists('filter_var'))	// Adjustment for hoster without the filter extension
	    {
	    	$this->_http_referer  = isset($_SERVER['HTTP_REFERER']) ? filter_var($_SERVER['HTTP_REFERER'],  FILTER_SANITIZE_URL) : self::REFERER_UNKNOWN ;
	    } 
	    else 
	    {
	    	$this->_http_referer  = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : self::REFERER_UNKNOWN ;
	    }
	    
	    $this->_search_engine = self::SEARCH_ENGINE_UNKNOWN ;
	    $this->_keywords      = self::KEYWORDS_UNKNOWN ;
	}
	
	public function checkEngines($referer='') 
	{
		$this->reset();
		if( $referer != "" ) 
		{
			//NEVER TRUST USER INPUT
			if (function_exists('filter_var'))	// Adjustment for hoster without the filter extension
	    	{
				$this->_http_referer = filter_var($referer,  FILTER_SANITIZE_URL);
	    	} 
	    	else 
	    	{
	    		$this->_http_referer = $referer;
	    	}
		}
		if ($this->_http_referer !== self::REFERER_UNKNOWN ) 
		{
			$this->detect();
		}
		
	}
	
	protected function detect()
	{
	    parse_str( parse_url( $this->_http_referer, PHP_URL_QUERY ), $this->_parse_result);
	    return (
	    	$this->checkEngineGoogleUserContent() ||
			$this->checkEngineGoogle()    ||
			$this->checkEngineBing()      ||
			$this->checkEngineYahoo()     ||
			$this->checkEngineYandex()    ||
			$this->checkEngineTOnline()   ||
			$this->checkEngineConduit()   ||
			$this->checkEngineBaidu()     ||
			$this->checkEngineAOL()       ||
			$this->checkEngineAsk()       ||
			$this->checkEngineWebde()     ||
			$this->checkEngineGMX()       ||
			$this->checkEngineLycos()     ||
			$this->checkEngineFreenet()   ||
			$this->checkEngineForestle()  ||
			$this->checkEngineBigfinder() ||
			$this->checkEngineBabylon()   ||
			$this->checkEngineSearchResult() ||
			$this->checkEngineMetaCrawler()  ||
			$this->checkEngineWerbungPublicRelations1824() ||
			$this->checkEngineKennenSieMich() ||
			$this->checkEngineFoxtab() ||
			$this->checkEngineSeexie() ||
			$this->checkEngineExtrabot()   ||
			$this->checkEngineWhorush()    ||
			$this->checkEngineNewtabking() ||
			$this->checkEngineEcosia()     ||
			$this->checkEngineSuchAlles()  ||
			$this->checkEngineSearchICQ()  ||
			$this->checkEngineIncredimail() ||
			$this->checkEngineGoogleBased() ||
	        $this->checkEngineDuckduckgo()  ||
	        $this->checkEngineSumaja()      ||
	        $this->checkEngineDelicious()   ||
			$this->checkEngineGeneric()     ||
			false
	    );
	}
    
	protected function checkEngineGoogle()
	{
	    if (preg_match('/http:\/\/plus\.google\..*\/url/', $this->_http_referer ))
	    {
	    	//no search engine!
	    	return false;
	    }
	    if (preg_match('/http:\/\/.*\.google\..*\/(|url|search|cse|imgres)/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_GOOGLE ;
			if ( isset($this->_parse_result['q']) ) 
			{ 
				$this->_keywords = $this->_parse_result['q']; 
			} 
			else 
			{ 
				//for imgres
				if ( isset($this->_parse_result['prev']) ) 
				{
					parse_str( parse_url( $this->_parse_result['prev'], PHP_URL_QUERY ), $this->_parse_result);
					if ( isset($this->_parse_result['q']) ) 
					{ 
						$this->_keywords = $this->_parse_result['q']; 
					}
				}
			}
			//default
			if ( strlen($this->_keywords) == 0 ) { $this->_keywords = self::REFERER_UNKNOWN ; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineGoogleUserContent()
	{
		if (preg_match('/http:\/\/.*\.googleusercontent\..*\//', $this->_http_referer ))
		{
			$this->_search_engine = self::SEARCH_ENGINE_GOOGLE ;
			if ( isset($this->_parse_result['q']) ) 
			{ 
				//webcache.googleusercontent.com
				$this->_keywords = substr($this->_parse_result['q'],1+strpos($this->_parse_result['q'],' ')); 
			} 
			else 
			{
				//translate.googleusercontent
				if ( isset($this->_parse_result['prev']) ) 
				{
					parse_str( parse_url( $this->_parse_result['prev'], PHP_URL_QUERY ), $this->_parse_result);
					if ( isset($this->_parse_result['q']) ) 
					{ 
						$this->_keywords = str_replace('+','',$this->_parse_result['q']); 
					}
				}
			}
			if ( strlen($this->_keywords) == 0 ) { $this->_keywords = self::REFERER_UNKNOWN ; }
			return true;
		}
		return false;
	}
	
	protected function checkEngineGoogleBased()
	{
	    if ( preg_match('/http:\/\/search\.avg\.com/'    , $this->_http_referer ) ||
	         preg_match('/http:\/\/search\.conduit\.com/', $this->_http_referer ) ||
	         preg_match('/http:\/\/search\.toolbars\.alexa\.com/', $this->_http_referer )
	       )
	    {
			$this->_search_engine = self::SEARCH_ENGINE_GOOGLE ;
			if ( isset($this->_parse_result['q']) ) 
			{ 
				$this->_keywords = $this->_parse_result['q']; 
			}
			//default
			if ( strlen($this->_keywords) == 0 ) { $this->_keywords = self::REFERER_UNKNOWN ; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineBing()
	{
	    //if (preg_match('/http:\/\/www\.bing\..*\/search/', $this->_http_referer ))
	    if (preg_match('/http:\/\/.*\.bing\..*\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_BING ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineBaidu()
	{
	    if (preg_match('/http:\/\/www\.baidu\.com\/s/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_BAIDU ;
			if ( isset($this->_parse_result['wd']) ) { $this->_keywords = $this->_parse_result['wd']; }
			if ($this->_keywords == 'QQ')    // I don't know what QQ is, but no keyword from an user
			{
			    $this->_search_engine = self::SEARCH_ENGINE_UNKNOWN ;
	            $this->_keywords      = self::KEYWORDS_UNKNOWN ;
				return false;
			}
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineYahoo()
	{
	    if (preg_match('/(http:\/\/.*\.search\.yahoo\..*\/search|http:\/\/search\.yahoo\..*\/search|http:\/\/.*\.images\.search\.yahoo\..*\/images|http:\/\/images\.search\.yahoo\..*\/images)/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_YAHOO  ;
			if ( isset($this->_parse_result['p']) ) { $this->_keywords = $this->_parse_result['p']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineAsk()
	{
	    if (preg_match('/http:\/\/.*\.ask\.com\/web/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_ASKCOM ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}

	protected function checkEngineTOnline()
	{
	    if (preg_match('/http:\/\/suche\.t-online\.de\/fast-cgi/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_TONLINE ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineWebde()
	{
	    if (preg_match('/http:\/\/suche\.web\.de\/search|http:\/\/suche\.web\.de\/web/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_WEBDE ;
			if ( isset($this->_parse_result['su']) ) { $this->_keywords = $this->_parse_result['su']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineGMX()
	{
	    if (preg_match('/http:\/\/suche\.gmx\.net\/search/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_GMX  ;
			if ( isset($this->_parse_result['su']) ) { $this->_keywords = $this->_parse_result['su']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineLycos()
	{
	    if (preg_match('/(http:\/\/www\.lycos\.de|http:\/\/search\.lycos\..*)/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_LYCOS ;
			if ( isset($this->_parse_result['query']) ) { $this->_keywords = $this->_parse_result['query']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineFreenet()
	{
	    if (preg_match('/http:\/\/suche\.freenet\.de\/suche/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_FREENET ;
			if ( isset($this->_parse_result['query']) ) { $this->_keywords = $this->_parse_result['query']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineConduit()
	{
	    if (preg_match('/http:\/\/search\.conduit\.com\/ResultsExt.aspx/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_CONDUIT ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineForestle()
	{
	    if (preg_match('/http:\/\/.*\.forestle\.org\/search.php/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_FORESTLE ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineAOL()
	{
	    if (preg_match('/(http:\/\/.*\.aol\..*|http:\/\/.*\.aolsvc\..*)/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_AOL ;
			if ( isset($this->_parse_result['query']) ) { $this->_keywords = $this->_parse_result['query']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineYandex()
	{
	    if (preg_match('/(http:\/\/yandex\.ru\/yandsearch|http:\/\/www\.yandex\.ru\/yandsearch)/', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_YANDEX ;
			if ( isset($this->_parse_result['text']) ) { $this->_keywords = $this->_parse_result['text']; }
			$this->_keywords = trim(preg_replace(array('/\xb6/','/  /'),array('',' '),$this->_keywords)); 
			if ( strlen($this->_keywords)===0 ) { $this->_keywords = self::KEYWORDS_UNKNOWN ; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineBigfinder()
	{
	    if (preg_match('/http:\/\/www\.bigfinder\.de\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_BIGFINDER ;
			if ( isset($this->_parse_result['suchwert']) ) { $this->_keywords = $this->_parse_result['suchwert']; }
			if ( strlen($this->_keywords) == 0 ) { $this->_keywords = self::REFERER_UNKNOWN ; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineBabylon()
	{
	    if (preg_match('/http:\/\/search\.babylon\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_BABYLON ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			if ( strlen($this->_keywords) == 0 ) { $this->_keywords = self::REFERER_UNKNOWN ; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineSearchResult()
	{
	    if (preg_match('/http:\/\/.*\.search-results\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_SEARCH_RESULT ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineMetaCrawler()
	{
	    if (preg_match('/http:\/\/.*\.metacrawler\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_METACRAWLER ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineWerbungPublicRelations1824()
	{
	    if (preg_match('/http:\/\/werbung-public-relations\.18x24\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_WERBUNGPUBLICRELATIONS1824 ;
			//if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineKennenSieMich()
	{
	    if (preg_match('/http:\/\/kennensiemich\.ch\/roboto/', $this->_http_referer ))
	    {
	    	$parse_result = array();
			$this->_search_engine = self::SEARCH_ENGINE_KENNENSIEMICH ;
			parse_str( parse_url( str_replace('?=','?q=',$this->_http_referer), PHP_URL_QUERY ), $parse_result);
			if ( isset($parse_result['q']) ) { $this->_keywords = $parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineFoxtab()
	{
	    if (preg_match('/http:\/\/search\.foxtab\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_FOXTAB ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			if ( strlen($this->_keywords) == 0 ) { $this->_keywords = self::REFERER_UNKNOWN ; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineSeexie()
	{
	    if (preg_match('/http:\/\/.*\.seexie\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_SEEXIE ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineExtrabot()
	{
	    if (preg_match('/http:\/\/extrabot\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_EXTRABOT ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineWhorush()
	{
	    if (preg_match('/http:\/\/.*\.whorush\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_WHORUSH ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineNewtabking()
	{
	    if (preg_match('/http:\/\/search\.newtabking\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_NEWTABKING ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineEcosia()
	{
	    if (preg_match('/http:\/\/ecosia\.org\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_ECOSIA ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineSuchAlles()
	{
	    if (preg_match('/http:\/\/.*\.such-alles\.de\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_SUCHALLES ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineSearchICQ()
	{
	    if (preg_match('/http:\/\/search\.icq\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_SEARCHICQ ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineIncredimail()
	{
	    if (preg_match('/http:\/\/search\.incredimail\.com\//', $this->_http_referer ))
	    {
			$this->_search_engine = self::SEARCH_ENGINE_INCREDIMAIL ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}
	
	protected function checkEngineDuckduckgo()
	{
	    if (preg_match('/http:\/\/duckduckgo\.com\/post\.html/', $this->_http_referer ))
	    {
	        $this->_search_engine = self::SEARCH_ENGINE_DUCKDUCKGO ;
	        //no parameter
	        return true;
	    }
	    return false;
	}
	
	protected function checkEngineSumaja()
	{
	    if (preg_match('/http:\/\/www\.sumaja\.de\//', $this->_http_referer ))
	    {
	        $this->_search_engine = self::SEARCH_ENGINE_SUMAJA ;
	        if ( isset($this->_parse_result['such_wert']) ) 
	        {
	            $this->_keywords = $this->_parse_result['such_wert'];
	        }
	        return true;
	    }
	    return false;
	}
	
	protected function checkEngineDelicious()
	{
	    if (preg_match('/http:\/\/www\.delicious\.com\/search/', $this->_http_referer ))
	    {
	        $this->_search_engine = self::SEARCH_ENGINE_DELICIOUS ;
	        if ( isset($this->_parse_result['p']) )
	        {
	            $this->_keywords = $this->_parse_result['p'];
	        }
	        return true;
	    }
	    return false;
	}
	
	/**
	 * Last Check for unknown Search Engines
	 *
	 * @return	bool
	 */
	protected function checkEngineGeneric()
	{
	    if ( preg_match('/\?q=/', $this->_http_referer ) || preg_match('/\&q=/', $this->_http_referer ) )
	    {
			$this->_search_engine = self::SEARCH_ENGINE_GENERIC ;
			if ( isset($this->_parse_result['q']) ) { $this->_keywords = $this->_parse_result['q']; }
			return true;
	    }
	    return false;
	}


	public function getEngine() { return $this->_search_engine; }
	
	public function getKeywords() { return $this->_keywords; }
	
	public function __toString() 
	{
	    return "Engine Name : {$this->getEngine()}\n" .
			   "Keywords    : {$this->getKeywords()}\n\n";
	}
	
}
