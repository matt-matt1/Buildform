<?php
namespace Yuma;
//define('START',microtime());
define( 'CHARSET', 'UTF-8' );
//define("ABS_PATH", $_SERVER['DOCUMENT_ROOT']);
define("ABS_PATH", dirname(__FILE__));
//define("LOG_ERRORS", true);
define("WRITE_LOG", true);
//define('WRITE_LOG_FILE', 'log/error.log');
define("LOG_CLASS_DIRS", true);
//define("LOG_AUTOLOAD_TRY", true);
//define("LOG_ERRORLEVEL_PARSE", true);
/*define("LOG_ERRORLEVEL_NOTICE", true);
define("LOG_ERRORLEVEL_WARNING", true);
define("LOG_ERRORLEVEL_STRICE", true);
define("LOG_ERRORLEVEL_DEPRECATED", true);*/
define("LOG_ERRORLEVEL", array(
//	E_ALL,
	E_USER_DEPRECATED,
	E_DEPRECATED,
	E_RECOVERABLE_ERROR,
	E_STRICT,
	~E_USER_NOTICE,
	~E_USER_WARNING,
	~E_USER_ERROR,
	E_COMPILE_WARNING,
	E_COMPILE_ERROR,
	E_CORE_WARNING,
	E_CORE_ERROR,
	E_NOTICE,
	E_PARSE,
	E_WARNING,
	E_ERROR
	));
define("LOG_URI", true);
//define("LOG_STYLES", true);
define("LOG_SCRIPTS", true);
//define('LOG_AUTOLOAD_TRY', true);
//define('LOG_AUTOLOAD_LOAD', true);
define('LOG_AUTOLOAD_ONCE', true);

define('PREFIX', 'bf_');
define('ADD_DB', 'add');
define('ADD_TBL', 'add');
define('ADD_COL', 'add');
define('ADD_ROW', 'add');
//define('ADD_FRM', 'add_form');
define('ADD_FRM', 'add');
/*define('DEBUG', Array(
	'HEAD_PLACEHOLDERS' => true,
	'SHOW_REQUEST' => true,
));*/
define('DEBUG_SHOW_URI', true);
define('DEBUG_SHOW_REQ', true);
define('DEBUG_SHOW_SESSION', true);
define('DEBUG_SHOW_COOKIES', true);
//define('DEBUG_SHOW_HOOK_POSITIONS', true);
define('DEBUG_SHOW_HEAD_COMMENTS', false);
//define('DEBUG_SHOW_HTTP_VARS', true);		// $_SERVER
//define('DEBUG_SHOW_HTTP_VARS', false);
define('DEBUG_HIDE_HEAD_METAS', false);
//define('DEBUG_HIDE_HEAD_METAS', true);
define('DEBUG_HIDE_HEAD_STYLES', false);
//define('DEBUG_HIDE_HEAD_STYLES', true);
define('DEBUG_HIDE_HEAD_SCRIPTS', false);
//define('DEBUG_HIDE_HEAD_SCRIPTS', true);
define('NOTIFY_CREATE_DB', true);
define('NOTIFY_DELETE_DB', true);
define('NOTIFY_CREATE_TABLE', true);
define('NOTIFY_DELETE_TABLE', true);
define('NOTIFY_CREATE_RECORD', true);
define('NOTIFY_DELETE_RECORD', true);
define('NOTIFY_CREATE_COLUMN', true);
define('NOTIFY_DELETE_COLUMN', true);
define('PREVENT_DELETE_DB_IF_HAS_TABLES', true);
//define('PREVENT_DELETE_DB_IF_HAS_TABLES', true);
define('PREVENT_DELETE_TABLE_IF_HAS_ROWS', true);
define('PREVENT_DELETE_FORM_IF_HAS_ROWS', true);
define('PREVENT_DELETE_TABLE_IF_HAS_COLUMNS', true);
define('PREVENT_DELETE_FORM_IF_HAS_COLUMNS', true);
define('SHOW_OTHER_DBS', true);
define('SHOW_OG_TAGS', true);
define('SHOW_TWITTER_TAGS', true);
define('SHOW_ARTICLE_TAGS', true);
define('USEPHPMAILER', true);

define('RESERVED', Array(
//	'django',
//	'django_auth',
//	'foodanic',
	'information_schema',
	'mysql',
	'performance_schema',
	'phpmyadmin',
//	'roundcube',
//	'wordpress'
));
//database configuration
	define('DB_TYPE', 'mysql');
	//define('DB_TYPE', 'pgsql');
	//server name
	//define('DB_HOST', 'localhost');
    //define('DB_HOST', '127.0.0.1');
    define('DB_HOST', '192.168.1.55');
	//alternately, if unix_socket set pipe name with:
	//define('DB_SOCK', 'pipe / socket name');
	//db port - optional
	//define('DB_PORT', '3306');	//optional!!!
	//db name - optional any database
	define('DB_NAME', 'mysql');
	//db user name
	define('DB_USER', 'yuma');
	//db password
	define('DB_PASS', 'yuma');

	//second of timeout - optional
	define('DB_TIME', 5);
	//charset - optional
	define('DB_CHAR', 'utf8mb4');
/*	define('DB_OPTS', array(
//		PDO::ATTR_PERSISTENT => true,
));*/
//define('_SESSION_NAME', '__HOST-Mine');
define('USER_SESSION_PROTECTION', true);

