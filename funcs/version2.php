<?php
namespace Yuma;
if (!defined('PHP_VERSION_ID2')) {
	$pre = 'version2: ';
	$version = explode('.', PHP_VERSION);
	$version2 = do_mbstr('substr', $version[2], 0, do_mbstr('strspn', $version[2], "0123456789"));
	//define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
	//define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version2));
	$vers = $version[0] * 10000 + $version[1] * 100 + $version2;
	$versd = $version[0]. '.'. $version[1]. '.'. $version2;
	define('PHP_VERSION_ID2', $vers);
	define('PHP_VERSION_ID2DOT', $versd);
/*	$log = 'using PHP '. $versd;
	(is_callable(array('Error', 'log'))) &&
		Error::log( $pre. $log ) ||
		error_log( $pre. $log );	*/
}
if (PHP_VERSION_ID < 50207) {
	define('PHP_MAJOR_VERSION', 	$version[0]);
	define('PHP_MINOR_VERSION', 	$version[1]);
//	define('PHP_RELEASE_VERSION',	$version[2]);
	define('PHP_RELEASE_VERSION',	$version2);
}
//if (PHP_VERSION_ID2 < 50207) {
	define('PHP_MAJOR_VERSION2', 	$version[0]);
	define('PHP_MINOR_VERSION2', 	$version[1]);
//	define('PHP_RELEASE_VERSION',	$version[2]);
	define('PHP_RELEASE_VERSION2',	$version2);
//}
$vers = PHP_MAJOR_VERSION2. '.'. PHP_MINOR_VERSION2. '.'. PHP_RELEASE_VERSION2;
$log = 'using PHP '. $vers;
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*(is_callable(array('Error', 'log'))) &&
	Error::log( $pre. $log ) ||
	error_log( $pre. $log );*/
function phpversion2() {
	return PHP_MAJOR_VERSION2. '.'. PHP_MINOR_VERSION2. '.'. PHP_RELEASE_VERSION2;
}
