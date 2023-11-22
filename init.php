<?php

namespace Yuma;
//use const Yuma\WRITE_LOG_FILE;
use const \WRITE_LOG_FILE;
use function \str_ends_with;

//	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	//defined('ABS_PATH') || (header((defined('PROTOCOL') ? PROTOCOL : 'HTTP/1.1'). " 403 Forbidden") & die('403.14 - Directory listing denied.'));
	defined('ABS_PATH') || (header((!empty(getServerValue('SERVER_PROTOCOL')) ? getServerValue('SERVER_PROTOCOL') : 'HTTP/1.1'). " 403 Forbidden") & die('403.14 - Directory listing denied.'));
//use Yuma\{Lang};

// dan at roteloftet dot com : https://www.php.net/manual/en/function.ob-start.php
while (ob_get_level())
	ob_end_clean();
//header("Content-Encoding: None", true);
header('Content-Encoding: identity', true);
// php.ini problem: "output_handler = ob_gzhandler" ^ cancel out the ob_gzhandler
//ob_start('ob_gzhandler');//gzip compression
//ob_start(array($this,'compressor'));
//ob_start(array(get_called_class(),'compressor'));//static
/*
date_default_timezone_set('Asia/Tokyo');
 */
//$ini_errr = ini_get('error_reporting');
//ini_set( 'error_reporting', E_ALL & ~ E_NOTICE );
function error_level_tostring($intval, $separator = ', ')
{
	$errorlevels = array(
		E_ALL => 'E_ALL',	//32767 - All errors and warnings (doesn't include E_STRICT)
		E_USER_DEPRECATED => 'E_USER_DEPRECATED',	// 16384
		E_DEPRECATED => 'E_DEPRECATED',	// 8192
		E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',	// 4096 - almost fatal run-time errors
		E_STRICT => 'E_STRICT',	// 2048 - run-time notices, enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code
		E_USER_NOTICE => 'E_USER_NOTICE',	// 1024 - user-generated notice message
		E_USER_WARNING => 'E_USER_WARNING',	// 512 - user-generated warning message
		E_USER_ERROR => 'E_USER_ERROR',	// 256 - user-generated error message
		E_COMPILE_WARNING => 'E_COMPILE_WARNING',	// 128 - compile-time warnings (non-fatal errors)
		E_COMPILE_ERROR => 'E_COMPILE_ERROR',	// 64 - fatal compile-time errors
		E_CORE_WARNING => 'E_CORE_WARNING',	// 32 - warnings (non-fatal errors) that occur during PHP's initial startup
		E_CORE_ERROR => 'E_CORE_ERROR',	// 16 - fatal errors that occur during PHP's initial startup
		E_NOTICE => 'E_NOTICE',	// 8 - run-time notices (these are warnings which often result from a bug in your code, but it's possible that it was intentional (e.g., using an uninitialized variable and relying on the fact it's automatically initialized to an empty string)
		E_PARSE => 'E_PARSE',	// 4 - compile-time parse errors
		E_WARNING => 'E_WARNING',	// 2 - run-time warnings (non-fatal errors)
		E_ERROR => 'E_ERROR'	// 1 - fatal run-time errors
	);
	$result = '';
	foreach($errorlevels as $number => $name)
	{
		/*$result .= ($result != '' ? $separator : ''). (($intval & $number) == $number ? '' : '-'). $name;*/
		/**/if (($intval & $number) == $number) {
			$result .= ($result != '' ? $separator : ''). $name;
		} else {
			$result .= ($result != '' ? $separator : ''). '-'. $name;
		}/**/
	}
	return $result;
}
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set( 'html_errors', defined('SHOW_HTML_ERRORS') ? \SHOW_HTML_ERRORS : true );
ini_set( 'display_errors', defined('SHOW_ERRORS') ? \SHOW_ERRORS : 'on' );
ini_set( 'display_startup_errors', defined('SHOW_STARTUP_ERRORS') ? \SHOW_STARTUP_ERRORS : 'on' );
ini_set("mysql.trace_mode", "Off");
//$report = E_ALL | E_PARSE;	// 32767 | 4
//$report .= (defined('LOG_ERRORLEVEL_ERROR') && LOG_ERRORLEVEL_ERROR) ? E_ALL : ~E_ALL;
//$report &= (defined('LOG_ERRORLEVEL_PARSE') && LOG_ERRORLEVEL_PARSE) ? E_PARSE : ~E_PARSE;
/*$report &= (defined('LOG_ERRORLEVEL_WARNING') && LOG_ERRORLEVEL_WARNING) ? E_WARNING : ~E_WARNING;	// 2
$report &= (defined('LOG_ERRORLEVEL_NOTICE') && LOG_ERRORLEVEL_NOTICE) ? E_NOTICE : ~E_NOTICE;  // 8
$report &= (defined('LOG_ERRORLEVEL_STRICT') && LOG_ERRORLEVEL_STRICT) ? E_STRICT : ~E_STRICT;  // 2048
$report &= (defined('LOG_ERRORLEVEL_DEPRECATED') && LOG_ERRORLEVEL_DEPRECATED) ? E_DEPRECATED : ~E_DEPRECATED;	// 8192
*/
//ini_set( 'error_reporting', (defined('LOG_ERRORS') && LOG_ERRORS) ? $report : E_ALL & ~ E_NOTICE );
//ini_set( 'error_reporting', (defined('LOG_ERRORS') && LOG_ERRORS) ? E_ALL & ~ E_NOTICE );
// E_ALL & ~E_NOTICE  integer value is 6135
ini_set( 'log_errors', defined('WRITE_LOG') ? WRITE_LOG : TRUE );
$pre = 'init ';
fixServerValues();
if (defined('WRITE_LOG') && WRITE_LOG ) {
	ini_set( 'error_log', __DIR__. DIRECTORY_SEPARATOR. (defined('WRITE_LOG_FILE') && is_readable(WRITE_LOG_FILE) ? WRITE_LOG_FILE : 'log/error.log') );
	require 'class/Yuma/Logger.php';
	$log = '-----';
	try {
//		$logger = new Logger();		// works
		$logger = new Logger;
		$logger->log($pre. $log. ' using [Logger]');
//		$logger = new Logger('-----');
//		new Logger('-----');
	//	$logger->log ('-----');
	} catch (Exception $e) {
		error_log ($pre. $log. ' error_log '. json_encode($e));//. "\n");
	}
	/*$logger = new Logger ();
	if (is_callable (array($logger, 'log')) ) {
		$logger->log ('-----');
	} else {
		error_log ('-----');
	}*/
/*	(is_callable(array('Logger', 'log'))) &&
		$logger = new Logger() &&
		$logger->log ('-----') ||
		error_log ('-----');*/
	if (defined('LOG_ERRORLEVEL')) {
		try {
			$report = 0;
			foreach (LOG_ERRORLEVEL as $level) {
				$report |= $level;
//				error_log( 'level ='. $level. '=');
//				$report &= $level;
			}
			//ini_set( 'error_reporting', (defined('LOG_ERRORS') && LOG_ERRORS) ? $report : E_ALL & ~ E_NOTICE );
			ini_set( 'error_reporting', $report );
			$log = sprintf( /*t(*/'_errorlevel: %s'/*)*/, error_level_tostring($report) );
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}
		} catch(Exception $e) {
			$log = sprintf( /*t(*/'_errorlevel was: %s'/*)*/, error_level_tostring(error_reporting()) );
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}
		}
	} else {
		$log = sprintf( /*t(*/'_errorlevel was: %s'/*)*/, error_level_tostring(error_reporting()) );
		try {
			$logger = new Logger;
			$logger->log($pre. $log);
		} catch (Exception $e) {
			error_log ('error_log: '. $pre. $log);
		}
	}
}
include_once 'protect_session.php';

ini_set( 'request_order', "PG" );
//$ini_zlib = ini_get('zlib.output_compression');
ini_set( 'zlib.output_compression', 'Off' );
define( 'HOST', getServerValue("HTTP_HOST") );
$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : getServerValue('SERVER_PROTOCOL');
if (!in_array($protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0', 'HTTP/3' ), true)) {
	//$protocol = 'HTTP/1.0';
	$protocol = 'HTTP/1.1';
}
define( 'PROTOCOL', $protocol );
!defined('CHARSET') && define( 'CHARSET', 'UTF-8' );
ini_set( 'default_charset', CHARSET );
//ini_set( 'default_charset', $GLOBALS['data']['CHARSET'] );
if (PHP_VERSION < 5.6)
{
	//ini_set( 'mbstring.http_output', $GLOBALS['data']['CHARSET'] );
	ini_set( 'mbstring.http_output', CHARSET );
	//ini_set( 'mbstring.internal_encoding', $GLOBALS['data']['CHARSET'] );
	ini_set( 'mbstring.internal_encoding', CHARSET );
	//ini_set("mbstring.func_overload", 2);//https://stackoverflow.com/questions/13120732/difference-between-mb-substr-and-substr#13120902
}
function my_exception_error_handler( $errno, $errstr, $errfile, $errline ) {
/*	$logger = new Logger();*/
	if (error_reporting() !== 0) {
		try {
			$logger = new Logger ($errno. $errstr. $errfile. $errline);
		} catch (Exception $e) {
			error_log ($errno. $errstr. $errfile. $errline);
		}
		throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
	}
}
set_error_handler('Yuma\\my_exception_error_handler');	// NOT E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR and E_COMPILE_WARNING
//set_error_handler('my_exception_error_handler');	// NOT E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR and E_COMPILE_WARNING
//$_GET		= filter_input_array(INPUT_GET);//, FILTER_SANITIZE_STRING);
$_GET		= filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);//, FILTER_SANITIZE_STRING);
$_POST		= filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);//, FILTER_SANITIZE_STRING);
//$_REQUEST	= (array)$_POST + (array)$_GET;
$_REQUEST	= array_merge((array)$_POST, (array)$_GET);

//$cli_mode = (strtolower(PHP_SAPI) == 'cli'? true : false);
//if( null === getServerValue("HTTP_HOST") && isset($argv[1]) ) {	// uses CLI args as GET parameters
if (null === HOST && isset($argv[1]) ) {	// uses CLI args as GET parameters
	parse_str( $argv[1], $_GET );		// eg. php -f somefile.php "asdf=zxqwfv&re=rere"
	define( 'PRINT_HEADERS', true );		// echo header value rather than do header
}

function fixServerValues()
{
	$default_server_values = array(
		'SERVER_SOFTWARE' => '',
		'REQUEST_URI'     => '',
	);
	$_SERVER = array_merge($default_server_values, $_SERVER);

	// Fix for IIS when running with PHP ISAPI.
	if (empty($_SERVER['REQUEST_URI'])
		|| ('cgi-fcgi' !== PHP_SAPI && preg_match('/^Microsoft-IIS\//', $_SERVER['SERVER_SOFTWARE']))
	) {
		if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
			// IIS Mod-Rewrite.
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
		} elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			// IIS Isapi_Rewrite.
			$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
		} else {
			// Use ORIG_PATH_INFO if there is no PATH_INFO.
			if (!isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO'])) {
				$_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
			}
			// Some IIS + PHP configurations put the script-name in the path-info (no need to append it twice).
			if (isset($_SERVER['PATH_INFO'])) {
				if ($_SERVER['PATH_INFO'] === $_SERVER['SCRIPT_NAME']) {
					$_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
				} else {
					$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
				}
			}
			// Append the query string if it exists and isn't null.
			if (!empty($_SERVER['QUERY_STRING'])) {
				$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
	}
	// Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests.
	//if (isset($_SERVER['SCRIPT_FILENAME']) && str_ends_with($_SERVER['SCRIPT_FILENAME'], 'php.cgi')) {
	if (isset($_SERVER['SCRIPT_FILENAME']) && substr($_SERVER['SCRIPT_FILENAME'], strlen('php.cgi')) == 'php.cgi') {
		$_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];
	}
	// Fix for Dreamhost and other PHP as CGI hosts.
	//if (isset($_SERVER['SCRIPT_NAME']) && str_contains($_SERVER['SCRIPT_NAME'], 'php.cgi')) {
	if (isset($_SERVER['SCRIPT_NAME']) && strpos($_SERVER['SCRIPT_NAME'], 'php.cgi') !== false) {
		unset($_SERVER['PATH_INFO']);
	}
	// Fix empty PHP_SELF.
	if (empty($_SERVER['PHP_SELF'])) {
		$_SERVER['PHP_SELF'] = preg_replace('/(\?.*)?$/', '', $_SERVER['REQUEST_URI']);
	}
}

function getServerValue( $variable_name, $filter=FILTER_DEFAULT, $options=0, $defaultValue=null ) {
//	$try = filter_input( INPUT_SERVER, $variable_name );
//	if( !isset($try) ) {
	if (isset( $_SERVER[$variable_name] ) ) {
		$val = filter_var( $_SERVER[$variable_name], $filter, $options );
		return ($val) ? $val : $defaultValue;
	}
//	return $try;
}
/**
 * Search recusively for files in a base directory matching a glob pattern.
 * The `GLOB_NOCHECK` flag has no effect.
 * https://gist.github.com/UziTech/3b65b2543cee57cd6d2ecfcccf846f20
 *
 * @param  string $base Directory to search
 * @param  string $pattern Glob pattern to match files
 * @param  int $flags Glob flags from https://www.php.net/manual/function.glob.php
 * @return string[] Array of files matching the pattern
 */
function glob_recursive($base, $pattern, $flags = 0) {
	$glob_nocheck = $flags & GLOB_NOCHECK;
	$flags = $flags & ~GLOB_NOCHECK;

	if (!function_exists("Yuma\\check_folder")) {
		function check_folder($base, $pattern, $flags) {
			if (substr($base, -1) !== DIRECTORY_SEPARATOR) {
			//if (do_mbstr('substr', $base, -1) !== DIRECTORY_SEPARATOR) {
				$base .= DIRECTORY_SEPARATOR;
			}
	
			$files = glob($base.$pattern, $flags);
			if (!is_array($files)) {
				$files = [];
			}
	
			$dirs = glob($base.'*', GLOB_ONLYDIR|GLOB_NOSORT|GLOB_MARK);
			if (!is_array($dirs)) {
				return $files;
			}
	
			foreach ($dirs as $dir) {
				$dirFiles = check_folder($dir, $pattern, $flags);
				$files = array_merge($files, $dirFiles);
			}
			
			return $files;
		}
	}
	$files = check_folder($base, $pattern, $flags);

	if ($glob_nocheck && count($files) === 0) {
		return [$pattern];
	}
	
	return $files;
}
foreach ( glob_recursive( __DIR__. "/funcs/", "*.php") as $filename ) {
	include_once($filename);
}
//echo 'get_include_path = '. get_include_path(). "\n";
//require "Autoloader.php";
require_once("class/Autoload.php");
//Autoloader::init();
//$lang = Lang::getInstance()->getClientLanguage();
//if (!isset($lang) || empty($lang)) {
//	$lng = new Lang();
	//$lng = new \Yuma\Lang('en-CA');
	$lng = new Lang('en-CA');
//}
//if (isset($_SESSION['lang'])) {
//	$lng->set($_SESSION['lang']);
//} elseif (isset($_GET['lang'])) {
//	$lng->set($_GET['lang']);
//} else {
//	$lng->set('en', 'CA');
//}
//$_SESSION['lang'] = $lng->getCode();
/*
Error_log("<html><h2>stuff</h2></html>",1,"eat@joe.com","subject  :lunch\nContent-Type: text/html; charset=ISO-8859-1");
 */
$pre = 'init: ';
$log = 'lang='. $lng->getLanguage();
try {
	//$logger = new Logger ('(L)init: lang='. $lang->getCode());
	$logger = new Logger ();
	$logger->log($pre. $log);
} catch (Exception $e) {
	error_log ('error_log: '. $pre. $log);
}
/* set the breadcrumbs *//*
Breadcrumbs::register(Array(
	new Breadcrumb(Array(
		'id' => 'home',
		'text' => t('_Home'),
		'title' => t('_Home'),
		'link' => BASE. '/',
		'class' => 'symbol home')),
	new Breadcrumb(Array(
		'id' => 'dbs',
		'text' => t('_Databases'),
		'title' => t('_Databases'),
		'link' => BASE. '/databases',
		'class' => '')))
);
Breadcrumbs::register(Array(
	new Breadcrumb(Array(
		'id' => 'database_'. $_GET['database'],
		'text' => enquote(delPrefix($_GET['database'])),
		'title' => sprintf( t('_Database %s'), delPrefix($_GET['database'])),
		//'link' => BASE. '/database/'. delPrefix($_GET['database']),
		'link' => BASE. '/database/'. $_GET['database'],
		'class' => ''))),
	1//if (isset($_GET['database']) && !empty($_GET['database']) && $_GET['database'] !== ADD_DB)
);
Breadcrumbs::register(Array(
	new Breadcrumb(Array(
		'id' => 'tabs',
		'text' => t('_Tables'),
		'title' => t('_Tables'),
		'link' => BASE. '/database/'. $_GET['database']/ *. '/tables/'* /,
		'class' => 'symbol dbtabs'))),
	1//if (isset($_GET['tables']) / *&& !empty($_GET['tables'])* /
);
Breadcrumbs::register(Array(
	new Breadcrumb(Array(
		'id' => 'tabs',
		'text' => t('_Tables'),
		'title' => t('_Tables'),
		'link' => BASE. '/database/'. $_GET['database']/ *. '/tables/'* /,
		'class' => 'symbol dbtabs')),
	new Breadcrumb(Array(
		'id' => 'table_'. $_GET['table'],
		'text' => enquote($_GET['table']),
		'title' => sprintf( t('_Table %s'), $_GET['table']),
		'link' => BASE. '/database/'. $_GET['database']. '/table/'. $_GET['table'],
		'class' => ''))),
	1//if (isset($_GET['table']) && !empty($_GET['table']) && $_GET['database'] !== ADD_TBL)
);
Breadcrumbs::register(Array(
	new Breadcrumb(Array(
		'id' => 'cols',
		'text' => t('_Columns'),
		'title' => t('_Columns'),
		'link' => BASE. '/database/'. $_GET['database']. '/table/'. $_GET['table']. '/columns/',
		'class' => 'symbol dbcols'))),
	1//if (isset($_GET['columns']) || (isset($_GET['page']) && $_GET['page'] == 'columns'))
);
Breadcrumbs::register(Array(
	new Breadcrumb(Array(
		'id' => 'cols',
		'text' => t('_Columns'),
		'title' => t('_Columns'),
		'link' => BASE. '/database/'. $_GET['database']. '/table/'. $_GET['table']. '/columns/',
		'class' => 'symbol dbcols')),
	new Breadcrumb(Array(
		'id' => 'column_'. $_GET['column'],
		'text' => enquote($_GET['column']),
		'title' => sprintf( t('_Column %s'), $_GET['column']),
		'link' => BASE. '/database/'. $_GET['database']. '/table/'. $_GET['table']. '/column/'. $_GET['column'],
		'class' => ''))),
	1//if (isset($_GET['column']) && !empty($_GET['column']))
);*/
//@ini_set('error_reporting', $ini_errr);
//@ini_set('zlib.output_compression', $ini_zlib);
if (defined('USEPHPMAILER') && USEPHPMAILER) {
	require 'mail.php';
}
