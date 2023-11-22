<?php
namespace Yuma;
/**
 * 301 - Moved Permanently
 * 302 - Found
 * 303 - See Other
 * 307 - Temporary Redirect (HTTP/1.1)
 */
function Redirect($url, $code = 302) {
	if (strncmp('cli', PHP_SAPI, 3) !== 0) {
		if (headers_sent() !== true) {
			$url = strtok($url, '?');
			//$url = $_SERVER['SCRIPT_NAME'] ."?".http_build_query($_GET)
			ob_start();
			if (strlen(session_id()) > 0) {// if using sessions
				// session_regenerate_id(true); // avoids session fixation attacks
				// session_write_close(); // avoids having sessions lock other requests
				session_regenerate_id(false);
				// Grab current session ID and close both sessions
				$newSession = session_id();
				session_write_close();
				// Set session ID to the new one, and start it back up again
				session_id($newSession);
				session_start();
			}
			//header( "HTTP/1.0 302 Found" );
		//	header (getServerValue('HTTP_SCHEME'). ' '.
			header( sprintf( '%s %03u', getServerValue('SERVER_PROTOCOL'), $code ) );
			if (strncmp('cgi', PHP_SAPI, 3) === 0) {
				header( sprintf( 'Status: %03u', $code ), true, $code );
			}
			
			header( 'Location: '. $url, true, (preg_match( '~^30[1237]$~', $code ) > 0) ? $code : 302 );
			//ob_end_clean();
			ob_end_flush();
		}
		//exit();
	}
}
