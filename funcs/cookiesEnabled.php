<?php
namespace Yuma;

// https://stackoverflow.com/questions/6663859/check-if-cookies-are-enabled
/*
 * if (isClientCookiesEnabled()) {
 * echo 'Cookies are enabled!';
 * -better add- ini_set( 'session.use_cookies', 1 );
 * -better add- @start_session();
 * } else {
 * echo 'Cookies are NOT enabled!';
 * }
 */
function ieClientCookiesEnabled() // Test if browser has cookies enabled
{
	if (defined( 'CLIENT_COOKIES_ENABLED' )) {
		return CLIENT_COOKIES_ENABLED;
	}

	$bIni = ini_get( 'session.use_cookies' ); 
	ini_set( 'session.use_cookies', 1 ); 

	$a = session_id();
	$bWasStarted = ( is_string( $a ) && strlen( $a ));
	if (!$bWasStarted) {
		@session_start();
		$a = session_id();
	}

	// Make a copy of current session data
	$aSesDat = (isset( $_SESSION )) ? $_SESSION : array();
	// Now we destroy the session and we lost the data but not the session id 
	// when cookies are enabled. We restore the data later. 
	@session_destroy(); 
	// Restart it
	@session_start();

	// Restore copy
	$_SESSION = $aSesDat;

	// If no cookies are enabled, the session differs from first session start
	$b = session_id();
	if (!$bWasStarted) { // If not was started, write data to the session container to avoid data loss
		@session_write_close(); 
	}

	// When no cookies are enabled, $a and $b are not the same
	$b = ($a === $b);
	define( 'CLIENT_COOKIES_ENABLED', $b );

	if (!$bIni) {
		@ini_set( 'session.use_cookies', 0 ); }

	//echo $b?'1':'0';
	return $b;
}
