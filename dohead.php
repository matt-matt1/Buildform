<?php
namespace Yuma;
use const Yuma\DEBUG_SHOW_HTTP_VARS;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
$_SERV = filter_input_array(INPUT_SERVER);
///if (isset($_SERV['REQUEST_URI']) && strlen($_SERV['REQUEST_URI']) > 100) {
///	notFound();
///}
/*
if (defined('DEBUG_SHOW_HTTP_VARS') && DEBUG_SHOW_HTTP_VARS)
//if (isset($GLOBALS['data']['DEBUG_SHOW_HTTP_VARS']) && $GLOBALS['data']['DEBUG_SHOW_HTTP_VARS'])
{
	echo '<table><tbody>'. "\n";
	foreach($_SERV as $k => $v) {
		echo '<tr><td>'. $k. '</td><td>'. $v. '</td></tr>'. "\n";
	}
	echo '</tbody></table>';
}
 */
$parts = explode('/', $_SERV['PHP_SELF']);
if (count($parts)==0) {
//	define( 'BASE', '/');
	$base = '/';
} else {
	array_pop($parts);
	$base = implode('/', $parts);
	if ($base[(strlen($base)-1)] != '/') {
		$base .= '/';
	}
}
define ('BASE', $base);
//$uri = '';
/*if (0&&isset($_SERV['REQUEST_URI']) && $_SERV['REQUEST_URI']) {
//	$uri = $_SERV['QUERY_STRING'];
	$uri = str_replace(BASE, '', $_SERV['REQUEST_URI']);
} else {*/
/**	$sch = (null !== getServerValue('REQUEST_SCHEME') ? getServerValue('REQUEST_SCHEME') : 'http'. (null !== getServerValue('HTTPS') && getServerValue('HTTPS') ? 's' : '')). '://';**/
	//define ('ABS_BASE', $sch. getServerValue('HTTP_HOST'). BASE;
//	define ('ABS_URL', $sch. HOST);//. BASE;
//	define ('ABS_BASE', $sch. HOST. BASE);
/**	$uri = $sch. HOST;**/
/*}*/
/**define ('ABS_URL', $uri);**/
if (defined('DEBUG_SHOW_HTTP_VARS') && DEBUG_SHOW_HTTP_VARS)
//if (isset($GLOBALS['data']['DEBUG_SHOW_HTTP_VARS']) && $GLOBALS['data']['DEBUG_SHOW_HTTP_VARS'])
{
//echo '<pre>'. print_r($_GET, true). '</pre>';
	echo 'base: '. BASE. "<br>\n";
//exit();
}
/*
if (!defined('DEBUG_SHOW_HTTP_VARS') || !DEBUG_SHOW_HTTP_VARS)
//if (!isset($GLOBALS['data']['DEBUG_SHOW_HTTP_VARS']) || !$GLOBALS['data']['DEBUG_SHOW_HTTP_VARS'])
{
	require "header.php";
}
 */
//require 'dohead2.php';
