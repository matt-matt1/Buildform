<?php declare(strict_types=1) ?>
<?php namespace Yuma; ?>
<?php /*xdebug_break();*/ ?>
<?php /*<meta http-equiv="refresh" content="0;url=home.php"> */ ?>
<?php
/*
<body style="height:100%;margin:0;padding:0;">
	<iframe width=100% height=100% src="home.php"></iframe>
</body>
*
	header("HTTP/1.1 301 Moved Permanently");
	header('Location: home.php');
	die();
 */
//$posted = filter_input_array( INPUT_POST );
require_once "config.php";
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
//echo '<pre>GET:'. print_r($_GET, true). '.</pre>';
//echo '<pre>POST:'. print_r($_POST, true). '.</pre>';
//echo '<pre>REQUEST:'. print_r($_REQUEST, true). '.</pre>';
//include "init.php";	//	autoload Depends Scripts Styles dialog.php download.php get_cert.php htmlhead.php
require_once "init.php";	//	autoload Depends Scripts Styles dialog.php download.php get_cert.php htmlhead.php
//require "config.php";
if (defined('_SESSION_NAME') && \_SESSION_NAME)
	session_name(\_SESSION_NAME);//'__Host-MYSESSION');
session_start();
/*if (strtolower(get_server_value('REQUEST_SCHEME')) == 'https' || (get_server_value('HTTPS') && strtolower(get_server_value('HTTPS') != 'off'))) {
	$_SESSION['cert'] = get_server_value('SSL_SESSION_ID');
}*/
if (empty($_SESSION['requestToken']))
	$_SESSION['requestToken'] = htmlentities(bin2hex(random_bytes(32)));
//$ua = (function_exists('getServerValue') ? htmlentities(getServerValue('HTTP_USER_AGENT')) : isset($_SERVER['HTTP_USER_AGENT'])) ? htmlentities($_SERVER['HTTP_USER_AGENT']) : '';
$ua = '';
if (function_exists('getServerValue')) {
	$ua = htmlentities(getServerValue('HTTP_USER_AGENT'));
}
if (empty($ua) && isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
	$ua = htmlentities($_SERVER['HTTP_USER_AGENT']);
}
if (defined('USER_SESSION_PROTECTION') && USER_SESSION_PROTECTION && !empty($ua)) {
	$msg = function_exists('t') ? t('_session_hijacking') : '!! Session hijacking !!';
	if (empty($_SESSION['user_agent']))
		$_SESSION['user_agent'] = $ua;
	elseif ($_SESSION['user_agent'] != $ua)
		throw new Exception($msg);
}
//$string = "welcome to buildform";
//echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
//filter_input(INPUT_GET, 'input', FILTER_SANITIZE_SPECIAL_CHARS)
