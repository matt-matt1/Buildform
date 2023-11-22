<?php

declare(strict_types=1);
namespace Yuma;
use Yuma\Database;
$pre = 'setdb.php: ';
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
if (defined('DB_TYPE')) {
//if (isset($GLOBALS['data']['DB_TYPE'])) {
	switch (strtolower(DB_TYPE)) {
	//switch (strtolower($GLOBALS['data']['DB_TYPE'])) {
	case 'pgsql':
/*		if (is_callable(array('Error', 'log'))) {
			Error::log( t('_Loaded PgSQL database driver') );
		} else {
			error_log( t('_Loaded PgSQL database driver') );
		}*/
					try {
						$logger = new Logger;
						$logger->log($pre. sprintf( t('_loaded %s'), 'PgSQL'));
					} catch (Exception $e) {
						error_log ($pre. sprintf( t('_loaded %s'), 'PgSQL'));
					}
/*		(is_callable(array('Error', 'log'))) ? 
			Error::log( $pre. sprintf( t('_loaded %s'), 'PgSQL' ) ) : 
			error_log( $pre. sprintf( t('_loaded %si'), 'PgSQL' ) );*/
					$db = new Database\Db(new Database\PgSQL_Driver);
		break;
	case 'mysql':
					try {
						$logger = new Logger;
						$logger->log($pre. sprintf( t('_loaded %s'), 'MySQL'));
					} catch (Exception $e) {
						error_log ($pre. sprintf( t('_loaded %s'), 'MySQL'));
					}
/*		(is_callable(array('Error', 'log'))) &&
			Error::log( $pre. sprintf( t('_loaded %s'), 'MySQL' ) ) ||
			error_log( $pre. sprintf( t('_loaded %s'), 'MySQL' ) );*/
					$db = new Database\Db(new Database\MySQL_Driver);
		//			$db = new Db(new MySQL_Driver);
		break;
	default:
/*		if (is_callable(array('Error', 'log'))) {
			Error::log( sprintf( t('_DB_TYPE was inccorectly set as %s. Loaded MySQL driver anyway'), DB_TYPE) );
		} else {
			error_log( sprintf( t('_DB_TYPE was inccorectly set as %s. Loaded MySQL driver anyway'), DB_TYPE) );
		}*/
					try {
						$logger = new Logger;
						$logger->log($pre. sprintf( t('_DB_TYPE was inccorectly set as %s.'), DB_TYPE). sprintf( t('_loaded %s'), 'MySQL'));
					} catch (Exception $e) {
						error_log ($pre. sprintf( t('_DB_TYPE was inccorectly set as %s.'), DB_TYPE). sprintf( t('_loaded %s'), 'MySQL'));
					}
/*		(is_callable(array('Error', 'log'))) &&
			Error::log( $pre. sprintf( t('_DB_TYPE was inccorectly set as %s.'), DB_TYPE). sprintf( t('_loaded %s'), 'MySQL') ) ||
			//Error::log( sprintf( t('_DB_TYPE was inccorectly set as %s. Loaded MySQL driver anyway'), $GLOBALS['data']['DB_TYPE']) ) ||
			error_log( $pre. sprintf( t('_DB_TYPE was inccorectly set as %s.'), DB_TYPE). sprintf( t('_loaded %s'), 'MySQL') );
			//error_log( sprintf( t('_DB_TYPE was inccorectly set as %s. Loaded MySQL driver anyway'), $GLOBALS['data']['DB_TYPE']) );
 *//*		(is_callable(array('Error', 'log'))) ?
			Error::log( sprintf( t('_DB_TYPE was inccorectly set as %s. Loaded MySQL driver anyway'), DB_TYPE) ) :
			error_log( sprintf( t('_DB_TYPE was inccorectly set as %s. Loaded MySQL driver anyway'), DB_TYPE) );*/
		$db = new Database\Db(new MySQLDriver);
		break;
	}
} else {
/*	if (is_callable(array('Error', 'log'))) {
		Error::log( t('_Loaded MySQL database driver') );
	} else {
		error_log( t('_Loaded MySQL database driver') );
	}*/
					try {
						$logger = new Logger;
						$logger->log($pre. sprintf( t('_loaded %s'), 'MySQL'));
					} catch (Exception $e) {
						error_log ($pre. sprintf( t('_loaded %s'), 'MySQL'));
					}
/*	(is_callable(array('Error', 'log'))) &&
		Error::log( $pre. sprintf( t('_loaded %s'), 'MySQL' ) ) ||
		error_log( $pre. sprintf( t('_loaded %s'), 'MySQL' ) );*/
					$db = new Database\Db(new MySQLDriver);
}
$failure = $db->connect('test');
