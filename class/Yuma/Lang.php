<?php

namespace Yuma;
//use Yuma\Lang;

use Exception;

class Lang
{
//	protected static $instance;
	//private $translations = Array();
	protected static $translations = Array();
//	public $translations = Array();
/*	private $lang_code = 'en';
	private $country_code = 'CA';
	private $lang_dir = 'lang';
	private $loaded = '';*/
	private $lang_code = 'en';
	private $country_code = '';
	private $lang_dir = '';
	//private mixed $loaded = '';
	private $loaded = '';
	//private string $rel_file = '';
	private $rel_file = '';

	//public function setCode( string $code ) {
	public function __construct($params=null)
	{
//			if (isset($_SESSION['lang']) && is_string($_SESSION['lang'])) {
//				return $_SESSION['lang'];
//			}
//			global $LANG;
		$pre = 'Lang:: ';
		if (empty($this->lang_dir)) {
			$this->lang_dir = 'lang';	// set default directory, if not set
		}
		if (!empty($_COOKIE['lang'])) {			// test if loaded language is stored in a cookie
			$this->loaded = filter_var($_COOKIE['lang']);
		} elseif (!empty($_SESSION['lang'])) {	// test if loaded language is stored in a session
			$this->loaded = filter_var($_SESSION['lang']);
		}
//			$this->loaded = isset($_SESSION['lang']) && !empty($_SESSION['lang']) ? filter_var($_SESSION['lang']) : $this->loaded;
//			if (empty($lang)) {
//				$lang = strtolower($this->lang_code). (/*isset($this->country_code) &&*/ !empty($this->country_code) ? '-'. strtoupper($this->country_code) : '');
				/*$log = 'init loaded ("'. $this->lang_dir. '"/"'. htmlentities($this->loaded). '")'. ob_get_level();
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}*/
		if (empty($this->loaded)) {
//		if (empty($_SESSION['lang'])) {
			$this->tryLng();			// try to load a language, if not loaded
		}
	}

	/**
	 * Attempts to load a language from a range of inputs
	 */
	public function tryLng()
	{
		$pre = 'Lang::tryLng() ';
		if (isset($_GET['lang']) && is_string($_GET['lang'])) {					// test if language is given within the GET variable
			$lang = htmlentities(filter_input(INPUT_GET, 'lang'));
			$log = 'trying _GET: '. $lang;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
			if (!$this->attempt($this->componentize($lang))) {					// attempt to load from GET
				$this->import();
			}
            //				$_SESSION['lang'] = $this->lang_dir. DIRECTORY_SEPARATOR. htmlentities($_GET['lang']). '.php';
		} elseif (isset($_SESSION['lang']) /*&& is_string($_SESSION['lang'])*/) {	// test if language is set in the session variable
			$lang = htmlentities(filter_var($_SESSION['lang'], FILTER_DEFAULT));
			$log = 'trying _SESSION: '. $lang;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
			//if (!$this->tryLoad($this->loadCodes(/*htmlentities(*/$_SESSION['lang']/*)*/))) {
			if (!$this->attempt($lang)) {										// attempt to load from session
				$this->import();
			}
        } elseif (isset($_COOKIE['lang']) /*&& is_string($_COOKIE['lang'])*/) {		// test if language is set in a cookie
			$lang = htmlentities(filter_var($_COOKIE['lang']));
			$log = 'trying _COOKIE: '. $lang;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
			//if (!$this->tryLoad($this->loadCodes(/*htmlentities(*/$_SESSION['lang']/*)*/))) {
			if (!$this->attempt($lang)) {										// attempt to load from a cookie
				$this->import();
			}
            //		} elseif (isset($_GET['lang']) && is_string($_GET['lang']) && empty($this->loaded)) {
//			$_SESSION['lang'] = $this->lang_dir. $_GET['lang'];
//			$this->tryLoad($_SESSION['lang']);
		} elseif (isset($params)) {
			if (is_array($params) && isset($params['lang'])) {
				$lang = '';
				if (isset($params['lang_dir'])) {
					$lang .= $params['lang_dir']. DIRECTORY_SEPARATOR;		// start with directory, if set
				}
				$lang .= $params['lang'];									// add language code
				if (isset($params['country_code'])) {
					$lang .= '-'. $params['country_code'];					// add country_code, if set
				}
			} else {
				$lang = $params;
			}
			$log = 'trying params: '. $lang;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
			if (!$this->attempt($this->componentize($lang))) {			// try to load from the given parameters
				$log = 'failed to load: '. $lang;
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}
				$this->import();
			}
        } else {
				$log = 'trying HTTP client lang';
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}
			$lang = $this->getClientLanguage();
			if (empty($lang) || !$this->attempt($lang)) {		// try to load using the client browser value
				$this->import();
			}
/*				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}*/
        }
        return $lang;
    }

    /**
     * Returns the client language code (may include the country_code) specified in the header from the client browser
     *
     * @return bool|string|null Returns the ISO-639 Language Code followed by '-' then ISO-3166 Country Code, like 'en-US'.
     * Empty string if PHP couldn't detect it.
     */
	//protected function getClientLanguage()
	public function getClientLanguage()//: bool|string|null
    {
		$pre = 'Lang::getClientLanguage() ';
		$lng = getServerValue('HTTP_ACCEPT_LANGUAGE');
			$log = 'HTTP_ACCEPT_LANGUAGE = '. $lng;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
            if (!empty($lng)) {
                $parts = explode(';', $lng);            // ignore semi-colon and after
                $lang = $parts[0];
            }
		if (!empty($lang)) {
			return $this->componentize($lang);		// separate into class variables
		}
		return '';//null;
	}

	/** bool $setFile=true, bool $doImp
	 * //Sets the (2-letter) language code, (2-letter) country code and the language directory (if defined)
	 * Sets the language code, country code and the language directory (if defined)
	 */
	//public function set( string $lang_code, string $country_code="", string $dir='lang' /*, array $params*/ )
	public function set (string $lang_code, string $country_code='', string $dir='lang', bool $setFile=true, bool $doImport=true): void
    {
		//$this->lang_code = substr(strtolower($lang_code), 0, 2);
		$this->lang_code = strtolower($lang_code);
		$this->country_code = (!empty($country_code)) ? strtoupper($country_code) : '';
		if (isset($dir)) {
			$this->lang_dir = $dir;
		}
		if (isset($setFile) && $setFile) {
			$this->setWhole();
		}
		$this->rel_file = (!empty($this->lang_dir) ? $this->lang_dir. DIRECTORY_SEPARATOR : ''). $this->lang_code. (!empty($this->country_code) ? '-'. $this->country_code : '');
		//if (empty($this->loaded)) {
		if (isset($doImport) && $doImport) {
			$this->import();
		}
	}

	/**
	 * Sets the language code
	 */
	public function setLang(string $lang_code, bool $setFile=true, bool $doImport=true): void
    {
		$this->lang_code = strtolower($lang_code);
		if (isset($setFile) && $setFile) {
			$this->setWhole();
		}
		//if (isset($this->lang_dir) && empty($this->loaded)) {
		if (isset($doImport) && $doImport) {
			$this->import();
		}
	}

	/**
	 * Sets the country code
	 */
	public function setCountry(string $country_code, bool $setFile=true, bool $doImport=true): void
    {
		$this->country_code = (!empty($country_code)) ? strtoupper($country_code) : null;
		if (isset($setFile) && $setFile) {
			$this->setWhole();
		}
		//if (isset($this->lang_dir) && empty($this->loaded)) {
		if (isset($doImport) && $doImport) {
			$this->import();
		}
	}

	/**
	 * Sets the relative filename
	 */
	public function setWhole (string $raw=''): void
    {
		if (!empty($raw)) {
			$this->rel_file = $raw;
		} else {
			$this->rel_file = (!empty($this->lang_dir) ? $this->lang_dir. DIRECTORY_SEPARATOR : ''). $this->lang_code. (!empty($this->country_code) ? '-'. $this->country_code : ''). '.php';
//			$this->rel_file = (isset($this->lang_dir) ? $this->lang_dir. DIRECTORY_SEPARATOR : ''). $this->lang_code. (isset($this->country_code) ? '-'. $this->country_code : ''). '.php';					// make file location string
		}
	}

	/**
	 * Sets the language directory
	 */
	public function setDir(string $dir, bool $setFile=true, bool $doImport=true): bool
    {
		$this->lang_dir = $dir;
		if (isset($setFile) && $setFile) {
			$this->setWhole();
		}
		//if (empty($this->loaded)) {
		if (isset($doImport) && $doImport) {
			$this->import();
		}
		return is_dir($dir);
	}

	/**
	 * Dissects the 5-letter code into language and country codes
	 */
	public function set5Code( string $code ): void
    {
		if (strlen($code) == 5) {
			$this->lang_code = substr(strtolower($code), 0, 2);
//			$this->country_code = substr(strtoupper($country_code), 2, 2);
		}
		$this->setWhole();
		//if (empty($this->loaded)) {
			$this->import();
		//}
	}

    /**
     * Divides a raw string into components:
     * language directory,
     * language 2letter code,
     * country 2letter code.  Together a relative filename string can be created also.
     * These components are stored in class variables.
     *
     * @param string $raw2 raw string input (maybe from unknown sources)
     * @return bool|string 5-letter code (for storage or transmission)
     */
	public function componentize (string $raw2)//: bool|string
    {
		$pre = 'Lang::componentize('. $raw2. ') ';
/*				$log = 'deciphering';
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}*/
		if ($raw2 === $this->loaded)
			return $this->loaded;
		$langs = array_map('trim', explode(',', $raw2));			// TODO: process each , value
		//$raw1 = $langs[0];							// only processes first language string
		$raw1 = array_shift($langs);							// only processes first language string
		$pos = strrpos ($raw1, DIRECTORY_SEPARATOR);
		if ($pos !== false) {						// extract directory, if exists
			$this->setDir(substr ($raw1, 0, $pos), false, false);
//				$this->lang_dir = substr ($raw1, 0, $pos);
			$raw = substr ($raw1, $pos+1);			// reset raw string without directory
		} else {
			$raw = $raw1;
		}
//			if (strpos ($raw, '_') !== false) {
//				$raw = str_replace ('_', '-', $raw);	// convert any underlines to dashes
//			}
		if (strpos ($raw, '-') !== false) {
			$parts = explode ('-', $raw, 2);		// split the raw string into parts (2 at most)
		}
//			if (count ($parts) != 2) {
		if (!isset($parts[0])) {
			$log = 'no valid language components in "'. $raw. '"';
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
			return false;							// no language_code could be extracted
		}
//			$tryLng2 = strtolower ($parts[0]);
	/*			$log = 'language code: "'. $tryLng2. '"';
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}*/
/*			if (strlen ($tryLng2) != 2) {
//				return false;							// not a valid 2-letter code
				$this->lang_code ='en';					// fall-back language
			}*/
		$this->setLang($parts[0], false, false);
//			$this->lang_code = $tryLng2;				// store language_code in lowercase
//			$raw = $tryLng2;

		if (!isset($parts[1])) {					// process country code
			$log = 'no valid country_code in "'. $raw. '", but got lang: "'. $this->lang_code. '"';
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
//				return false;							// no valid country_code could be extracted
		} else {
			$tryCnt2 = strtoupper ($parts[1]);
			if (strlen ($tryCnt2) != 2) {
				$temp = $tryCnt2;
				$tryCnt2 = substr ($temp, 0, 2);
				//$log = 'country_code not a valid 2-letter code: "'. $tryCnt2. '"';
				$log = 'country_code truncated: "'. $temp. '" --> "'. $tryCnt2. '"';
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}
//				return false;							// not a valid 2-letter code
			}
			$this->setCountry($tryCnt2, false, false);
//				$this->country_code = $tryCnt2;		// store country_code in uppercase
			/*	$log = 'country code: "'. $tryCnt2. '"';
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}*/
//			$raw .= '-'. $tryCnt2;						// add country_code to raw string
		}
		$this->setWhole();//$raw1);
//			$this->rel_file = (isset($this->lang_dir) ? $this->lang_dir. DIRECTORY_SEPARATOR : ''). $this->lang_code. (isset($this->country_code) ? '-'. $this->country_code : ''). '.php';					// make file location string
		//$log = 'saving code: '. $raw. ', file: '. $this->rel_file;
		$log = 'saving code: '. $this->lang_code. (!empty($this->country_code) ? '-'. $this->country_code : ''). ', file: '. $this->rel_file;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
		return $this->lang_code. (!empty($this->country_code) ? '-'. $this->country_code : '');//$raw;
	}
		
	/**
	 * Loads (parses PHP code of) the set language(&/ country) translations file - if file can be found,
	 * otherwise loads the base language (or similar language) file - if found,
	 * otherwise sets the loaded variable as empty
	 */
	public function import(): void
    {
		//global $LANG;
/*			if (isset($LANG) && !empty($LANG)) {
				$this->loaded = $LANG;
				return;
			}*/
		if (!empty($this->loaded)) {
//				$_SESSION['lang'] = $this->loaded;
			return; //$this->loaded;
		}
		$pre = 'Lang::import() ';
		//$lang = $this->lang_dir. DIRECTORY_SEPARATOR. strtolower($this->lang_code). '-'. strtoupper($this->country_code). '.php';
		$lang = isset($this->rel_file) ? $this->rel_file : '';
		if (empty($lang))
			$lang = $this->lang_dir. DIRECTORY_SEPARATOR. $this->lang_code. (!empty($this->country_code) ? '-'. strtoupper($this->country_code) : ''). '.php';
//			if (isset($this->loaded) && $this->loaded == $lang) {
//				$_SESSION['lang'] = $this->loaded;
//				return;
//			}
//			while (!isset($this->loaded) || empty($this->loaded)) {
		if (!$this->attempt($lang)) {
/*			if (is_readable(ABS_PATH. DIRECTORY_SEPARATOR. $lang)) {
				require (ABS_PATH. DIRECTORY_SEPARATOR. $lang);
				$this->loaded = $lang;
				$LANG = $this->loaded;
				//$log = sprintf( t('_loaded: %s'), $lang);
				$log = 'Loaded: '. $lang;
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}
				//echo 'require '. $lang. "\n";
			} else {*/
			$files = glob_recursive($this->lang_dir, $this->lang_code. '*.php');
			//$log = 'No exact match: "'. $lang. '". Similar languages ('. $this->lang_code. '): '. print_r($files, true);
			//$log = 'No exact match: "'. $lang. '" - Similar languages ('. $this->lang_code. '): '. print_r($files, true);
			$log = 'No match for "'. $lang. '" - Similar ('. $this->lang_code. '~): '. json_encode($files);
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
			if (is_array($files)) {
				foreach ($files as $f) {
					$log = 'trying: '. $f;
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*						if (!empty($this->loaded))
							return;*/
					if ($this->attempt($f)) {
						return;
					}
/*						if (is_readable(ABS_PATH. DIRECTORY_SEPARATOR. $f)) {
							$this->loaded = str_replace($this->lang_dir. DIRECTORY_SEPARATOR, '', $f);
							$log = 'loaded '. $f;
							try {
								$logger = new Logger;
								$logger->log($pre. $log);
							} catch (Exception $e) {
								error_log ($pre. $log);
							}
							return;
						}*/
				}
			}
			$this->loaded = '';
			setcookie('lang', $this->loaded, time() - 60, '/');
			$_SESSION['lang'] = $this->loaded;
			unset($_SESSION['lang']);
			$_COOKIE['lang'] = $this->loaded;
			unset($_COOKIE['lang']);
			$log = 'language not found';
			try {
				$logger = new Logger;
				$logger->log($log);
			} catch (Exception $e) {
				error_log ($log);
			}
/*				if (isset($files[0]) && is_readable(ABS_PATH. DIRECTORY_SEPARATOR. $files[0])) {
					require( ABS_PATH. DIRECTORY_SEPARATOR. $files[0] );
					$this->loaded = $lang;
					//$log = sprintf( t('_loaded: %s'), $lang);
					//$log = 'trying: '. $lang;
					$log = 'trying: '. $files[0];
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
					//echo 'require '. $files[0]. "\n";
				} else {
					//$this->loaded = 'language not found';
					$this->loaded = '';
					$log = 'language not found';
					try {
						$logger = new Logger;
						$logger->log($log);
					} catch (Exception $e) {
						error_log ($log);
					}
				}*/
		}
/*		if (isset($langArray)) {
			$this->translations = $langArray;	// Store the translations into class array
			unset($langArray);	// reclaim memory
		}*/
	}

	/**
	 * Attempts to load (include) the given string
	 */
	public function attempt(string $lang='')
	{
		//			global $LANG;
		$pre = 'Lang::attempt('. $lang. ') ';
//			$test = $this->rel_file;
//			if (isset($lang) && !empty($lang)) {
			$test = $lang;
			if (!strpos($lang, DIRECTORY_SEPARATOR)) {
				$test = $this->lang_dir. DIRECTORY_SEPARATOR. $lang;
			} else {
				$pos = stripos ($lang, DIRECTORY_SEPARATOR);
				$lang = substr ($lang, $pos+1);
			}
			if (!strpos($lang, '.php')) {
				$test .= '.php';
			} else {
				$lang = substr ($lang, 0, strlen ($lang)-4);
			}
//			} else {
//				$test = $this->rel_file;
//			}
	/*	$log = 'trying to load: '. $test;
		try {
			$logger = new Logger;
			$logger->log($pre. $log);
			//$logger->logWithTrace($pre. $log);
		} catch (Exception $e) {
			error_log ($pre. $log);
		}*/
		if (is_readable(ABS_PATH. DIRECTORY_SEPARATOR. $test)) {
			require (ABS_PATH. DIRECTORY_SEPARATOR. $test);
			if (isset($langArray)) {
				//$this->translations = $langArray;	// Store the translations into class array
				static::$translations = $langArray;	// Store the translations into class array
				unset($langArray);	// reclaim memory
			}
//				$this->rel_file = $test;
//				$this->loadCodes($lang);
			$this->loaded = $lang;
			$_SESSION['lang'] = $this->loaded;
			$_COOKIE['lang'] = $this->loaded;
			if (!headers_sent()) {
				setcookie('lang', $this->loaded, time() + 60 * 60 * 24 * 100, '/');
			}
			//$log = sprintf( t('_loaded: %s'), $lang);
//				$log = 'loaded: '. $lang. $this->loaded. $_SESSION['lang'];
			$log = 'required/loaded: '. $this->loaded;
//				$log = 'loaded: '. $_SESSION['lang'];
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
			//echo 'require '. $lang. "\n";
			return $this->loaded;//$_SESSION['lang'];
		} else {
			$log = 'failed '. ABS_PATH. DIRECTORY_SEPARATOR. $test;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
		}
		return false;
	}

	/**
	 * Returns the loaded language, if any
	 */
	public function getLoaded()
	{
//			global $LANG;
//			$LANG = $this->loaded;
		return $this->loaded;
	}

	/**
	 * Returns the 5-letter code containing the language and country code - if country code was set,
	 * otherwise returns the 2-letter language code
	 */
	public function getLanguage()
	{
		$pre = 'Lang::getLanguage() ';
		//return "{static::$lang_code}-{static::$country_code}";
		//if (!isset($this->lang_code))
//				$log = 'lang: '. $this->lang_code. (!empty($this->country_code) ? '-'. strtoupper($this->country_code) : '');
/*				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}*/
//		$lang = $this->loaded;
//		if (empty($this->loaded)) {
//		if (empty($_SESSION['lang'])) {
			$lang = $this->tryLng();			// try to load a language, if not loaded
//		}
/*		if (/ *null == $this->lang_code ||* / empty($this->lang_code))
			$this->getClientLanguage();
		$lang = isset($_SESSION['lang']) ? filter_var($_SESSION['lang']) : '';
		if (empty($lang)) {
			$lang = isset($_COOKIE['lang']) ? filter_var($_COOKIE['lang']) : '';
		}
		if (empty($lang)) {
			$lang = strtolower($this->lang_code). (/ *isset($this->country_code) &&* / !empty($this->country_code) ? '-'. strtoupper($this->country_code) : '');
		}*/
			$log = 'lang: '. $lang;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
			return $lang;
		//return isset($this->country_code) ? strtolower($this->lang_code). '-'. strtoupper($this->country_code) : strtolower($this->lang_code);
		//return $this->country_code;
	}

	//public function setDir( string $dir ) {
	//	$this->lang_dir = $dir;
	//}

	/**
	 * Returns the language directory
	 */
	public function getLangDir(): string
    {
		return $this->lang_dir;
	}

	/**
	 * Returns the value for the translation of the supplied string, if found,
	 * otherwise returns the supplied string
	 */
	public function translate( string $str )
	{
		//if (isset($this->translations[$str]) && !empty($this->translations[$str])) {
		if (isset(static::$translations[$str]) && !empty(static::$translations[$str])) {
			//return $this->translations[$str];
			return static::$translations[$str];
		} else {
			//return ':'. $str. ':';	// no translation found
			return $str;	// no translation found
		}
	}

	public function getTr()//: bool|string
    {
		//return print_r($this->translations, true);
		//return json_encode($this->translations);
		return json_encode(static::$translations);
	}

}
/* functions - wrappers for class methods (without using class structures): */
function t(string $str)
{
	//return Lang::getInstance()->translate($str);
	//$lng = new \Yuma\Lang;
	$lng = new Lang;
	return $lng->translate($str);
}
function l(string $str): void
{
	//echo Lang::getInstance()->translate($str);
	//$lng = new \Yuma\Lang;
	$lng = new Lang;
	echo $lng->translate($str);
}
/*
 * function translate($q, $sl, $tl){
    $res= file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$sl."&tl=".$tl."&hl=hl&q=".urlencode($q), $_SERVER['DOCUMENT_ROOT']."/transes.html");
    $res=json_decode($res);
    return $res[0][0][0];
}

//example--
echo translate("اسمي منتصر الصاوي", "ar", "en");
 */
