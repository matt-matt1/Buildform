<?php
declare(strict_types=1);

namespace Yuma;
global $failure;

use Yuma\Logger;
use Yuma\HTML\Head;
use Yuma\HTML\Meta;
use Yuma\Database\Store;

require_once "begin.php";	// loads config.php, init.php-("Autoloader.php", "mail.php"?)
//	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
require_once "setdb.php";
require_once "dohead.php";
require_once 'dohead2.php';		// loads header.php

if (!$failure) {
    global $db;
    $dbs = $db->run('SHOW DATABASES')->fetchAll();
	$store = new Store();
	$head = new Head();
/*	$log = 'Framework loaded1';
	try {
		$logger = new Logger();
		$logger->log ('index '. $log);
	} catch (Exception $e) {
		error_log ('error_log: index '. $uri. ' ; Exception: '. json_encode($e));
	}*/

//	if (isset($_SERV['QUERY_STRING'])) {
	if (isset($_SERV['REQUEST_URI']) && $_SERV['REQUEST_URI']) {
//		$uri = $_SERV['QUERY_STRING'];
		$uri = str_replace(BASE, '', $_SERV['REQUEST_URI']);
	}
	if ( !isset($uri) )
		$uri = '';
	if ( null === HOST && isset($argv[1]) )	// uses CLI args as GET parameters
	{
		foreach ($argv as $arg)	// add GET arguments to uri
		{
			if ($arg !== $argv[0])
			{
				//$uri .= ' ';
				$uri .= $arg;
//			} else {
//				$uri .= 'uri=';
			}
//			$uri .= $arg;
		}
	}
	if (defined('DEBUG_SHOW_HTTP_VARS') && \DEBUG_SHOW_HTTP_VARS)
	//if (isset($GLOBALS['data']['DEBUG_SHOW_HTTP_VARS']) && $GLOBALS['data']['DEBUG_SHOW_HTTP_VARS'])
	{
		echo "uri: {$uri}<br>\n";
	} else {
		setcookie('previous', $uri, time() + 60 * 60 * 1, '/');
	}
	$meta = new Meta();
	if ( defined('DEBUG_SHOW_URI') && DEBUG_SHOW_URI ) {
		$meta->comment( 'this_uri', 'this_uri = '. $uri );//
	}
//	if (!defined('DEBUG_SHOW_HTTP_VARS') || !DEBUG_SHOW_HTTP_VARS) {
//		setcookie('previous', $uri);
//	}
	if (defined('LOG_URI') && LOG_URI ) {
		try {
			//$logger = new Logger ('(L)'. $pre. $log);
			$logger = new Logger();
			$logger->log ('uri = '. $uri);
		} catch (Exception $e) {
			error_log ('error_log: uri = '. $uri);
		}
/*
		try {
			$logger = new Logger('uri: '. $uri);
		} catch (Exception $e) {
			error_log('uri: '. $uri);
		}
*//*
		if (class_exists('Logger')) {
//			(new Logger)->log( '(L)uri: '. $uri );
//			$logger = new Logger;
//			$logger = new Logger();
//			$logger->log( '(L)uri: '. $uri );
			$logger = new Logger( '(L)uri: '. $uri );
		} else {
			error_log( '(EL)uri: ('. $uri. ')' );
		}
*//*
		(is_callable(array('Logger', 'log'))) &&
		$logger = new Logger() &&
		$logger->log( '(L)uri: '. $uri ) ||
			error_log( '(EL)uri: ('. $uri. ')' );
*/
	}
	if (!isset($uri) || empty($uri) || $uri == '/' ) {
		include_once "home.php";
	} else {
		include_once "nothome.php";
	}
} else {
	$log = 'Cannot connect to database';
		try {
			$logger = new Logger();
			$logger->log ('index '. $log);
		} catch (Exception $e) {
			error_log ('error_log: index '. $uri. ' ; Exception: '. json_encode($e));
		}
	$note = new Note(Array(
		'message' => $log,//'Cannot connect to database',
		'type' => Note::error,
		'fatal' => true
	));
	echo '<body>'. $note->display(). '</body>';
}
require "footer.php";
ini_set( 'display_errors', 'off' );
