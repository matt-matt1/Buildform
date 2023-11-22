<?php

namespace Yuma;

	defined('ABS_PATH') || (header((!empty(getServerValue('SERVER_PROTOCOL')) ? getServerValue('SERVER_PROTOCOL') : 'HTTP/1.1'). " 403 Forbidden") & die('403.14 - Directory listing denied.'));

// https://stackoverflow.com/questions/22221807/session-cookies-http-secure-flag-how-do-you-set-these
// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);
// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
ini_set('session.use_only_cookies', 1);
// Uses a secure connection (HTTPS) if possible
ini_set('session.cookie_secure', 1);

// https://stackoverflow.com/questions/5081025/php-session-fixation-hijacking#5081453
ini_set('session.use_trans_sid', 0);
// prevent Session Hijacking
/*	$version = explode('.', PHP_VERSION);
	//$vers = $version[0] * 10000 + $version[1] * 100 + $version2;
if (($version[0] <= 5 && $version[1] <= 3) || PHP_VERSION < 5.3) {*/
if (version_compare(phpversion(), '5.3.0', '<')) {
	ini_set('session.hash_function', 1);
} else {
	ini_set('session.hash_function', 'sha256');
}
//ini_set('session.hash_function', (PHPVERSION < 5.3) ? 1 : 'sha256');
ini_set('session.hash_bits_per_character', 5);
ini_set('session.entropy_file', '/dev/urandom');
ini_set('session.entropy_length', 256);

