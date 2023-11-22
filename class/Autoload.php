<?php

//namespace Yuma;

//set_include_path( implode( PATH_SEPARATOR, [__DIR__. '/class', get_include_path()] ) );
set_include_path (implode (PATH_SEPARATOR, array_unique (array_merge (array(
	ABS_PATH . DIRECTORY_SEPARATOR . 'class',
//	APP_ROOT . 'src',
//	APP_ROOT . 'test'
), explode (PATH_SEPARATOR, get_include_path()) ) ) ) );

class Autoloader
{
	static public function loader ($className) {
		$pre = 'Autoloader::loader('. $className. ') ';
		//$filename = "class/" . str_replace("\\", '/', $className) . ".php";
		$filename = __DIR__ . DIRECTORY_SEPARATOR . str_replace ("\\", DIRECTORY_SEPARATOR, $className) . ".php";
//		$filename = "class/" . str_replace (['Yuma\\', '\\'], ['', DIRECTORY_SEPARATOR], $className) . ".php";
		//$filename = 'class/' . str_replace ([ '_','\\'], '/', $className ). '.php';
				//$log = 'filename"'. $filename. '"';
				//$log = '';
		if (defined('LOG_AUTOLOAD_TRY') && LOG_AUTOLOAD_TRY) {
			$log = 'ob level: '. ob_get_level();
			try {
				$logger = new Yuma\Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ($pre. $log);
			}
		}
//		if (file_exists ($filename)) {
//			require ($filename);
//			include($filename);
		if (($classPath = stream_resolve_include_path($filename)) != false) {
			if (defined('LOG_AUTOLOAD_LOAD') && LOG_AUTOLOAD_LOAD) {
				//$log = 'loaded class"'. $classPath. '"';
				//$log = 'requiring class"'. $classPath. '"'. ob_get_level();
				$log = 'requiring. ob level: '. ob_get_level();
				try {
					$logger = new Yuma\Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}
			}
			//require $classPath;
			$once = require_once $classPath;
			if (defined('LOG_AUTOLOAD_ONCE') && LOG_AUTOLOAD_ONCE && $once !== true) {
				//$log = 'require loaded class"'. $classPath. '"'. ob_get_level();
				$log = 'loaded. ob level: '. ob_get_level();
				try {
					$logger = new Yuma\Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ($pre. $log);
				}
			}
			if (class_exists ($className)) {
				return TRUE;
			}
		}
		return FALSE;
	}
}
		require 'class/Yuma/Logger.php';
spl_autoload_extensions ('.php');
//spl_autoload_register ('Yuma\Autoloader::loader');
spl_autoload_register ('Autoloader::loader');
/*spl_autoload_register(function ($class_name) {
	require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . $class_name . '.php';
});*/
/*
spl_autoload_register( function( $class ) {
	$pos = strrpos( $class, '\\' );
	include ( $pos === false ? $class : substr( $class, $pos + 1 ) ).'.php';
});
*/
