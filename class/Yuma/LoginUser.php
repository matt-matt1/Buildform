<?php

namespace Yuma;
use \Chirp\Cryptor;

class LoginUser extends Login
{
	const MAX_BAD_TRIES_BEFORE_BAN = array(
		2 => 10,	// default ban user after two tries for 10 min
		3 => 30,
		4 => 60,
	);
	const MAX_BAD_TRIES_BEFORE_CAPTCHA = array(
		1 => 'invisible',
		2 => 'checkbox',
	);

	/**
	 * if a login cookie exists the computered array is returned
	 * otherwise, the login form is displayed
	 */
	public function __construct()
	{
		$user = new User;
//		return $this->getUserORloginForm();
		/*$array = $this->getArray();
		if (!$array)
			$this->form();
		return $array;*/
	}

	/**
	 * Processes any user activity (login, logout)
	 * by processing any login variable from a HTML form
	 * Determines a user and check the password,
	 * writes the login cookie if a valid remember number is also POSTED
	 */
	public function processLogin()
	{
		$pre = 'LoginUser::processLogin() ';
		if (/*isset($_POST['login_name']) ||*/ isset($_POST['logout']) || isset($_GET['logout'])) {
			$this->logout(/*$_POST['login_name']*/);
		}
		if (isset($_GET['profile']) || isset($_POST['profile'])) {
			include ABS_PATH. '/profile.php';
			exit;
		}
		if (isset($_POST['login']) && $_POST['login'] == 'login') {
			if (!isset($_POST['username']) || empty(trim($_POST['username']))) {
				return t('_invalid_username');
			}
			if (!isset($_POST['passwd']) || empty(trim($_POST['passwd']))) {
				return t('_invalid_password');
			} elseif (isset($_POST['username']) && !empty(trim($_POST['username']))) {
//			if (isset($_POST['username']) && !empty($_POST['username']) /*&& isset($_POST['passwd']) && !empty($_POST['passwd'])*/) {
				$u = new Users;
				//$usr = $u->getWithFName($_POST['username']);
				$usr = $u->getWithUName(trim($_POST['username']));
			$c = $this->setUser($usr);
				if (!isset($usr->username)) {
					return;
				}
				$stamp = strtotime ($usr->active);
				if ($stamp == 0 || $stamp > time()) {
//				if ($usr->active < time()) {
					// inactive account
					$msg = sprintf (t("_inactive account (%s)"), $usr->username);
					$note = new Note(Array(
						'type' => Note::warning,
						'canIgnore' => true,
						'message' => $msg,
						'details' => $stamp,
					));
					echo $note->display();
					unset ($usr);
						try {
							$logger = new Logger;
							$logger->log($pre. $msg);
						} catch (Exception $e) {
							error_log ('error_log: '. $pre. $msg);
						}
					return $msg;//false;
				}
				if (isset($usr->password) && $this->checkPasswd(trim($_POST['passwd']))) {
//					if (isset($usr->username)) {
						//$_COOKIE['login'] = SSL::encrypt(serialize($usr), SSL::$key1);
/*						$key = null == SSL::$key1 ? SSL::gen32() : SSL::$key1;
						$_COOKIE['login'] = SSL::encrypt(serialize($usr), $key);
						$this->array = $usr;*/
						$array = array(
							'id' => $usr->user_id,
							'remote' => getServerValue('REMOTE_ADDR'),
							'host' => getServerValue('REMOTE_HOST'),
							'client' => getServerValue('HTTP_CLIENT_IP'),
							'proxy' => getServerValue('HTTP_X_FORWARDED_FOR'),
							'ua' => getServerValue('HTTP_USER_AGENT'),
							'ssl_sn' => getServerValue('SSL_SERVER_M_SERIAL'),
						);
						$log = 'user authenticated '. json_encode($array);
						try {
							$logger = new Logger;
							$logger->log($pre. $log);
						} catch (Exception $e) {
							error_log ('error_log: '. $pre. $log);
						}
						$this->makeArray($array);
						unset($_SESSION['LoginToken2']);
//						if (isset($_POST['remember']) && is_numeric(intval($_POST['remember']))) {
//							$this->writeCookie(intval($_POST['remember']));
//						}
				//if (isset($_POST['remember']) && is_numeric($_POST['remember'])) {	// save cookie for specific time, if remember is set
				//	$c = $l->setUser($usr, intval($_POST['remember']));
				//}
				if (isset($_POST['redirect'])) {
					$redirect = ABS_URL. htmlspecialchars($_POST['redirect']);
				} elseif (isset($_GET['redirect'])) {
					$redirect = ABS_URL. htmlspecialchars($_GET['redirect']);
				}
				//		$log = t('_redirect'). $_GET['redirect'];
				$log = 'redirecting to '. $redirect;
						try {
							$logger = new Logger;
							$logger->log($pre. $log);
						} catch (Exception $e) {
							error_log ('error_log: '. $pre. $log);
						}
					Redirect ($redirect);
				//		Redirect ($_GET['redirect']);
//					}
				} else {
					// BAD PASSWORD
//					if (isset($usr->username)) {
						$username = $usr->username;
						$encryption_key = 'CKXH2U9RPY3EFD70TLS1ZG4N8WQBOVI6AMB0';
						$cryptor = new Cryptor($encryption_key);
						if (isset($_SESSION['LoginToken2'])) {
							$attempt = json_decode($cryptor->decrypt(urldecode($_SESSION['LoginToken2'])));
						}
						$attempt[$username] = isset($attempt[$username]) ? $attempt[$username] : 0;
						if (in_array($attempt[$usr->username], static::MAX_BAD_TRIES_BEFORE_BAN)) {
							$ban = static::MAX_BAD_TRIES_BEFORE_BAN[$attempt[$usr->username]];
							// user has been banned for $ban minuets
							$msg = sprintf( t('_%s has been banned for %s mins'), $usr->username, $ban );
							$note = new Note(Array(
								'type' => Note::warning,
								'canIgnore' => true,
								'message' => $msg,
	//							'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". t('Query'). ": {$qry}",
							));
							echo $note->display();
						try {
							$logger = new Logger;
							$logger->log($pre. $msg);
						} catch (Exception $e) {
							error_log ('error_log: '. $pre. $msg);
						}
							$crypted_token = $cryptor->encrypt(json_encode($attempt));
							$_SESSION['LoginToken2'] = urlencode($crypted_token);
							$usr->setActive (time() + $ban);
						}
						if (in_array($attempt[$usr->username], static::MAX_BAD_TRIES_BEFORE_CAPTCHA)) {
							// present a captcha for the user
							$note = new Note(Array(
								'type' => Note::warning,
								'canIgnore' => true,
								'message' => sprintf( t("_Complete the captcha for %s"), $usr->username ),//_POST['tbname'] ),
	//							'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". t('Query'). ": {$qry}",
							));
							echo $note->display();
							Captcha::render(static::MAX_BAD_TRIES_BEFORE_CAPTCHA[$attempt[$usr->username]]);
						}
						unset ($usr);
						$this->setUser(null);
						$msg = t('_incorrect_login');
						try {
							$logger = new Logger;
							$logger->log($pre. $msg);
						} catch (Exception $e) {
							error_log ('error_log: '. $pre. $msg);
						}
						return $msg;//t('_incorrect_login');
						//return;
//					}
				}
			}
		} elseif (getServerValue('REQUEST_METHOD') == 'POST') {
			$_POST['passwd'] = 'xx';
			unset ($_POST['passwd']);
			$_REQUEST['passwd'] = 'xx';
			unset ($_REQUEST['passwd']);
			$user = get_object_vars($this->getUser());
			if (isset($user['username'])) {
				$this->displayUser($user);
			}
		}
	}

	public function displayUser($loggedin)
	{
?><div class="user-feature sticky">
	<div class="user-badge" title="'<?=$loggedin['username']?>">
		<div class="user-frame">
			<div><?=(strlen($loggedin['username']) < 4) ? strtoupper($loggedin['username']) : substr($loggedin['user_first'], 0, 1). substr($loggedin['user_last'], 0, 1)?></div>
		</div>
		<ul class="user-menu">
			<li class="user-profile"><a href="?profile" title="<?=t('_title_profile')?>"><?=t('_profile')?></a></li>
			<li class="user-logout"><a href="?logout" title="<?=t('_title_signout')?>"><?=t('_signout')?></a></li>
		</ul>
	</div>
</div>
<?php
	}

	public function setActive($stamp)
	{
		$pre = 'LoginUser::setActive('. $stamp. ')';
		$this->active = date ('Y-m-d H:i:s', $stamp);
		$this->update();
	}

	/**
	 * Return a hash for the supplied password
	 */
	public static function cryptPasswd($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}

	/**
	 * Check that the supplied password matches the class array (user),
	 * while also determining if a hash update is required
	 *
	 * Return true if the passwords match
	 *
	 * @return bool true|false
	 */
	public function checkPasswd($password)
	{
		$pre = 'LoginUser::checkPasswd('. $password. ') ';
		$usr = $this->getArray();
		//if (!isset($this->user['password']) || empty($this->user['password'])) {
//		if (!isset($usr['password']) || empty($usr['password'])) {
		if (!isset($usr->password) || empty($usr->password)) {
			$log = 'no password for '. $usr->username;//print_r ($usr, true);
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}
			return true;	// TODO TEMP UNTIL user has password
			//return false;
		}
/* */		//$log = 'testing with: '. json_encode ($usr);
		$log = 'testing with: '. $usr->password;
		//$log = 'testing with: '. print_r ($usr, true);
		try {
			$logger = new Logger;
			$logger->log($pre. $log);
		} catch (Exception $e) {
			error_log ('error_log: '. $pre. $log);
		}/* */
		//if (password_verify($password, $this->user['password']))
//		if (password_verify($password, $usr['password']))
		if (password_verify($password, $usr->password))
		{
			$log = 'passwords match for '. $usr->username;
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}
			$login = TRUE;
			//if (password_needs_rehash($this->user['password'], PASSWORD_DEFAULT, $this->options))
//			if (password_needs_rehash($usr['password'], PASSWORD_DEFAULT, $this->options))
			if (password_needs_rehash($usr->password, PASSWORD_DEFAULT, $this->options))
			{
				//$this->user['password'] = password_hash($password, PASSWORD_DEFAULT, $this->options);
//				$usr['password'] = password_hash($password, PASSWORD_DEFAULT, $this->options);
				$usr->password = password_hash($password, PASSWORD_DEFAULT, $this->options);
				// update to database
				$u = new Users($usr);
				$u->update(array('onlySQL' => true));	// TODO
				$log = 'password_needs_rehash for '. print_r ($usr, true);
				try {
					$logger = new Logger;
					$logger->log($pre. $log);
				} catch (Exception $e) {
					error_log ('error_log: '. $pre. $log);
				}
			}
			return true;
		//} elseif (md5($password) == $this->user['password']) {
//		} elseif (md5($password) == $usr['password']) {
		} elseif (md5($password) == $usr->password) {
			//$this->user['password'] = $this->cryptPasswd($password);
//			$usr['password'] = $this->cryptPasswd($password);
			$usr->password = $this->cryptPasswd($password);
			// update to database
			$u = new Users($usr);
			$u->update(array('onlySQL' => true));	// TODO
			$log = 'using MD5 for '. print_r ($usr, true);
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (Exception $e) {
				error_log ('error_log: '. $pre. $log);
			}
		}
		return false;
		//return true;
	}

	/**
	 * Creates an new (updated) hash for the supplied password
	 */
	public function needsNewPasswd($password)
	{
		$usr = $this->getArray();
		//if (password_needs_rehash($this->user['password'], PASSWORD_DEFAULT, $options))
		if (password_needs_rehash($usr['password'], PASSWORD_DEFAULT/*, $options*/))
		{
			//$this->user['password'] = static::cryptPasswd($password);
			$usr['password'] = static::cryptPasswd($password);
			// update to database
			$u = new Users($usr);
			$u->update(array('onlySQL' => true));	// TODO
		}
	}

	/**
	 * Removed the cookie and class array
	 */
	public function logout(/*$username=null*/)
	{
		$pre = 'LoginUser::logout() ';
		$log = '';
		try {
			$logger = new Logger;
			$logger->log($pre. $log);
		} catch (Exception $e) {
			error_log ('error_log: '. $pre. $log);
		}
		$this->array = null;
//		unset ($this->array);
//		$this->user = null;
//		unset ($this->user);
		$_SESSION['LoginToken'] = null;
		unset ($_SESSION['LoginToken']);
		$_COOKIE['LoginToken'] = null;
		unset ($_COOKIE['LoginToken']);
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
		session_destroy();
		session_start();
		$this->form();
//		setcookie('login', '', time() - (3600));
	}

	/**
	 * If a user is not loggedin, the login form is shown
	 */
/*	public function loginIfRequired()
	{
		if (/ *isset($_POST['login_name']) ||* / isset($_POST['logout']) || isset($_GET['logout'])) {
			//$this->logout($_POST['login_name']);
			$this->logout(/ *$_POST['login_name']* /);
		}
		if (isset($_POST['login'])) {
	//		echo 'processing login...<br>'. "\n";
			$this->processLogin();
		}
		if (!$this->getArray()) {
			$this->form();
			exit();
		}
	}
 */

	public function getUser()
	{
		$user = $this->getArray();
						/*$log = t('_loggedin'). ': '. print_r($user, true);
						try {
							$logger = new Logger;
							$logger->log($log);
						} catch (Exception $e) {
							error_log ('error_log: '. $log);
						}*/
//		if (isset($this->array) /*&& $this->user instanceof Users*/) {
//			return $this->array;
//		}
		return $this->getArray();
	}


	public function setUser(/*Users*/ $user)
	{
//		if (!$user instanceof Users)
//		$usr = (Users)$user;
		//echo 'LoginUser::setUser()<pre>'. print_r($user, true). '</pre>'. "\n";
		return $this->makeArray($user);
/*		$key = null == SSL::$key1 ? SSL::gen32() : SSL::$key1;
		$_COOKIE['login'] = SSL::encrypt(serialize($user), $key);
		$this->array = $user;
		//$this->array = $_COOKIE['login'];
		//$this->makeArray($data)
		return $this->array;//$_COOKIE['login'];
 */
	}


	public function getUserORloginForm()
	{
//		echo 'LoginUser::getUserORloginForm()<br>'. "\n";
		//if (isset($_POST['login_name']) || isset($_POST['logout']) || isset($_GET['logout'])) {
		if (isset($_POST['logout']) || isset($_GET['logout'])) {
			//$this->logout($_POST['login_name']);
			//$this->logout($_POST['login_name']);
			$this->logout();
		}
		if (isset($_POST['login'])) {
	//		echo 'processing login...<br>'. "\n";
			$this->processLogin();
		}
/*		if (!$this->getArray()) {
			$this->form();
			exit();
		} else {
			return $this->user;//getUser();*/
		//if ($this->getArray()) {
/*		if ($this->getUser()) {
			return $this->user;//getUser();
		} else {
			$this->form();
			exit();
		}*/
		$val = $this->getArray();
//		echo 'LoginUser::getUserORloginForm() value:<pre>'. print_r($val, true). '</pre>'. "\n";
//		echo 'LoginUser::getUserORloginForm() _COOKIE:<pre>'. print_r($_COOKIE, true). '</pre>'. "\n";
		if ($val) {
			return $val;//this->array;
		} else {
			$this->form();
			exit();
		}
	}

}

