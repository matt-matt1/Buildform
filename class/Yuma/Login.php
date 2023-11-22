<?php

namespace Yuma;

class Login
{
	protected $options = array();		// password algorithm constants - https://www.php.net/manual/en/password.constants.php
//	private static $from;
	private $array = array();
	private $cookieEnabled = null;
	private $key;


	/**
	 * if a login cookie exists the computered array is returned
	 * otherwise, the login form is displayed
	 */
	public function __construct(/*$onPage=false*/)
	{
//		global $LoginToken;
		$this->key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');
		if (isset($_COOKIE['LoginToken'])) {
			$_SESSION['LoginToken'] = filter_input(INPUT_COOKIE, 'LoginToken');//$_COOKIE['LoginToken'];
		}
		if (count($_COOKIE) > 0) {
			$this->cookieEnabled = true;
		}
/*		if ($onPage) {
			$array = $this->getArray();
			if (!$array)
				$this->form();
			return $array;
		}*/
	}

	public function form(string $url=BASE. 'login')
	{
		$pre = 'Login::form('. $url. ') ';
//		echo '<pre>'. print_r($_SERVER, true). '</pre>';
		//$url = (null !== getServerValue('REQUEST_SCHEME') ? getServerValue('REQUEST_SCHEME') : 'http'. (null !== getServerValue('HTTPS') && getServerValue('HTTPS') ? 's' : '')). '://'. getServerValue('HTTP_HOST'). $url;
		$url = ABS_URL. $url;
		if (!empty(getServerValue('REDIRECT_URL'))) {
			$url .= '?redirect='. urlencode(getServerValue('REDIRECT_URL'));
			//$_COOKIE['afterLogin'] = getServerValue('REDIRECT_URL');
//			$GLOBALS['afterLogin'] = getServerValue('REDIRECT_URL');
//			echo 'comming from: '. $this->from. '<br>'. "\n";
			//echo 'comming from: '. $this->getReferrer(). '<br>'. "\n";
			//echo 'comming from: '. static::$from. '<br>'. "\n";
//			echo 'comming from: '. $_COOKIE['afterLogin']. '<br>'. "\n";
		}
//		$log = 'Login::form('. $url. ')';
		try {
			$logger = new Logger;
			$logger->log($pre. $url);
		} catch (Exception $e) {
			error_log ('error_log: '. $pre. $url);
		}
/*		unset ($_GET['pasaswd']);
		if (is_array($_POST)) {
			foreach (array_keys($_POST) as $p) {
				$_POST[$p] = null;
			}
		}
		$_POST = array();*/
		Redirect($url);
//		echo $url;
//		echo file_get_contents($url);
		exit();
	}

	/**
	 * Collects the login cookie
	 *
	 * Returns the array on success, false if not found
	 *
	 * @return false if no cookie has been set
	 * otherwise an array and sets the class variable and stores the session
	 */
	protected function getArray()
	{
		$pre = 'Login::getArray() ';
		//global $LoginToken;
//			echo 'Login::getArray()_COOKIE:<pre>'. print_r($_COOKIE, true). '</pre>'. "\n";
		if (isset($_SESSION['LoginToken']) && (!isset($_COOKIE['LoginToken']) || empty($_COOKIE['LoginToken']))) {
			$_COOKIE['LoginToken'] = filter_var($_SESSION['LoginToken']);
		}
		if (!isset($_SESSION['LoginToken']) || empty($_SESSION['LoginToken'])) {
			$_SESSION['LoginToken'] = filter_input(INPUT_COOKIE, 'LoginToken');
		}
		if (isset($_COOKIE['LoginToken'])) {
			/*$log = 'found login cookie';
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
			//$this->cookie = $_COOKIE['login'];
			$cookie = urldecode($_COOKIE['LoginToken']);
//			echo 'cookie: '. $cookie. '<br>'. "\n";
			//$this->array = SSL::secure_decrypt($this->cookie);
			if (!$cookie)
				return false;
			/*$log = 'login cookie: '. $_COOKIE['LoginToken'];
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
			//$key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');// 64btyes
//		$key = null == SSL::$key1 ? SSL::gen32() : SSL::$key1;
			$code = \SaferCrypto::decrypt($cookie, $this->key);
			$json = base64_decode ($code);
			/*$log = 'base64 code: '. $code;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
/*			$key = null == SSL::$key1 ? SSL::gen32() : SSL::$key1;
//			echo 'key: '. $key. '<br>'. "\n";
			$code = SSL::decrypt($cookie, $key);
//			echo 'array: '. $this->array. '<br>'. "\n";
 */
			//$array = unserialize($code);
			$array = json_decode ($json);
			/*$log = 'json: '. $json;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
	//		$array = json_decode($this->array);
//			echo 'Login::getArray() array<pre>'. print_r($array, true). '</pre>'. "\n";
			$this->array = $array;
			$log = 'array: '. print_r($array, true);
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}
//		$u = new User;
//		$usr = getWithFName();
//			if (!isset($_SESSION['LoginToken']) || empty($_SESSION['LoginToken'])) {
//				$_SESSION['LoginToken'] = $array;
//			}
//			if (!isset($_COOKIE['LoginToken']) || empty($_COOKIE['LoginToken'])) {
//				$_COOKIE['LoginToken'] = $array;
//			}
			return $this->array;
		//	return true;
		}
		return false;
	}

	/**
	 * Encodes an array and sets a cookie-like variable
	 *
	 * @param $array		data to be saved
	 * @param int $length	if greater-than 0, the login cookie is written to store for number of hours
	 */
	//private function makeArray()
	public function makeArray(/*array*/ $array, int $length=0)
	{
		$pre = 'Login::makeArray() ';
		//global $LoginToken;
//		echo 'Login::makeArray()<pre>'. print_r($array, true). '</pre>'. "\n";
		$this->array = $array;
		if (!$array) {
			$_COOKIE['LoginToken'] = null;// unset ();
			$_SESSION['LoginToken'] = null;
			return false;
		}
		session_regenerate_id(false);
		$newSession = session_id();
		session_write_close();
		session_id($newSession);
		session_start();
		//$data = serialize ($array);
/*			$log = 'array: '. print_r($array, true);
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
		$json = json_encode ($array);
			/*$log = 'json: '. $json;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
		$code = base64_encode ($json);
			/*$log = 'base64 code: '. $code;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
//		echo 'Login::makeArray()$data<pre>'. print_r($data, true). '</pre>'. "\n";
//		$key = null == SSL::$key1 ? SSL::gen32() : SSL::$key1;
		$raw = \SaferCrypto::encrypt($code, $this->key);
/*		$raw = SSL::encrypt($data, $key);*/
		$_COOKIE['LoginToken'] = urlencode($raw);
		$_SESSION['LoginToken'] = urlencode($raw);
			/*$log = 'set login cookie: '. $_COOKIE['LoginToken'];
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
//		error_log('Login::makeArray() set login cookie: '. $_COOKIE['LoginToken']);
//			echo 'Login::makeArray()_COOKIE:<pre>'. print_r($_COOKIE, true). '</pre>'. "\n";
		if (isset($length) /*&& is_numeric($length)*/ && $length > 0) {
			//setrawcookie('login', rawurlencode($raw), time() + 3600 * $length, '/');	// htmlspecialchars
			setrawcookie('LoginToken', encode_cookie_value($raw), time() + 3600 * $length, '/');	// htmlspecialchars
			/*$log = 'wrote cookie: '. encode_cookie_value($raw);
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}*/
		}
//		echo 'Login::makeArray()_COOKIE<pre>'. print_r($_COOKIE, true). '</pre>'. "\n";
//			echo '<pre>'. print_r($_COOKIE, true). '</pre>'. "\n";
/*	//echo 'available:<pre>'. print_r(SSL::available(), true). '</pre>'. "\n";
	SSL::setCipher('aes-256-cbc');
	$enc = SSL::secure_encrypt('Hello world');
	echo 'encrypted: '. $enc. "<br>\n";
	$dec = SSL::secure_decrypt($enc);
	echo 'decrypted: '. $dec. "<br>\n";

	$cip = SSL::encrypt('Hello world', SSL::$key1);
	echo 'encrypted: '. $cip. "<br>\n";
	$dec = SSL::decrypt($cip, SSL::$key1);
	echo 'decrypted: '. $dec. "<br>\n";
 */
	}

	private function encode_cookie_value($value)
	{
		if (function_exists('mb_str_split') /*&& function_exists('mb_strtr')*/)
			return strtr ($value,
				array_combine (mb_str_split ($tmp = ",; \t\r\n\013\014"),
					array_map ('rawurlencode', mb_str_split($tmp))
				)
			);
		else
			return strtr ($value,
				array_combine (str_split ($tmp = ",; \t\r\n\013\014"),
					array_map ('rawurlencode', str_split($tmp))
				)
			);
	}

	/**
	 * @depreciated
	 * Writes the login cookie for a spefic length of time (hours)
	 * @param	int		$length		time before expire, in hours
	 */
	public function writeCookie(int $length=1)
	{
		if ($this->cookieEnabled)
			return false;		// TODO
	//	if ($this->user instanceof Users) {
			//setcookie('login', urlencode(SSL::encrypt($this->array, SSL::$key1)), time() + 3600 * $length, '/');	// htmlspecialchars
			//setcookie('LoginToken', $_COOKIE['LoginToken'], time() + 3600 * $length, '/');	// htmlspecialchars
			setcookie('LoginToken', $_SESSION['LoginToken'], time() + 3600 * $length, '/');	// htmlspecialchars
		return true;
	//	}
	}

	/**
	 * Returns the page HTTP_REFERER
	 */
/*	public function getReferrer()
	{
	//	return $this->from;
	//	return $GLOBALS['afterLogin'];
		//return static::$from;
		$headers = headers_list();
		if (in_array('X-Referrer', $headers)) {
			return trim( substr ($headers, 10) );
		}
		//return $_COOKIE['previous'];
		return implode('; ', $headers);
	}
 */
	/**
	 * Sets the page HTTP_REFERER
	 */
/*	public function setReferrer($url)
	{
	//	$GLOBALS['afterLogin'] = $url;
		//static::$from = $url;
		header ('X-Referrer: '. $url);
		$_COOKIE['previous'] = $url;
	//	$this->from = $url;
	}
*/
}

