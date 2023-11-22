<?php
namespace Yuma;
use Yuma\HTML\Link;
use Yuma\HTML\Field;
// sumbit one ==>
// Array (
// [requestToken] => e5bf7fa52dfd74abb1b05060b0dd95e51f59318c6ca73d1af662db1c129230a1
// [login] => register1 // [uname] => Username // [email] => me@qws.com
// [first] => FirstN [last] => LastN [login_as] => first [submit] => Register > )
// SQL: INSERT INTO `yumatech_organise`.user (`username`, `user_first`, `user_last`,
// `user_email`, `password`, `active`) VALUES ('FirstN', 'FirstN', 'LastN',
// 'me@qws.com', '', 0)
//
// submit two ==>
// Array (
// [requestToken] => e5bf7fa52dfd74abb1b05060b0dd95e51f59318c6ca73d1af662db1c129230a1
// [login] => register2 [user_id] => 17
// [password] => asdf [password2] => asdf [submit] => Register )
// Fatal error: Uncaught ErrorException: Undefined index: id in /var/www/html/BuildForm/pages/register.php:94
//
// me@qws.com
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
//	if ( !isset($_GET['database']) ) {
//		Redirect( BASE. 'databases' );
//	}
////	if ( isset($_GET['database']) && (!isset($_GET['table']) || empty($_GET['table'])) ) {
////		Redirect( BASE. 'database/'. $_GET['database'] );
////	}
//	$dbname = $_GET['database'];
////	$table = isset($_POST['fname']) ? $_POST['fname'] : $_GET['form'];
//	// *****
//	// Pressed OK (after table is created) - user acknowledged notice
//	// *****
//	if (isset($_POST['pressed'])) {
//		if ($_POST['pressed'] === 'ok' /*|| (!isset($_POST['tbname']) || !isset($_GET['table']))*/) {	// list all databases, if select ok from notice
//			Redirect( BASE. 'database/'. $dbname );
//		} elseif ($_POST['pressed'] === 'ignore') {
//			//$table = isset($_POST['tbname']) ? $_POST['tbname'] : (isset($_GET['table']) ? $_GET['table'] : '');
////			if (isset($_POST['tbname']))
////				$table = $_POST['tbname'];
//				//$table = isset($_GET['table']) ? $_GET['table'] : $_POST['tbname'];
//		//		$table = isset($_POST['tbname']) ? $_POST['tbname'] : $_GET['table'];
////			if (empty($table) && isset($_GET['table']))
////				$table = $_GET['table'];
///*			if ($_GET['table'] === ADD_TBL || $_GET['table'] === ADD_FRM) {
//				Redirect( BASE. 'database/'. $dbname );
//			}*/
//			Redirect( BASE. 'database/'. $dbname. '/form/'. $table );
//			//Redirect( BASE. 'database/'. $dbname. (isset($_GET['table']) ? '/table/' : '/form/'). $table );
//		}
//	}
//	$default_new_table_data = 'id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY';
	$top = array( 'page_title' => t('_reg_desc'), 'body_id' => 'register', 'body_class' => "no-js login", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " );
	$submit = !isset($id) ? t('_register_next') : t('_register');
	if (isset($_GET['user'])) {
		$top['page_title'] = t('_rec_pw');
		$heading1 = t('_rec_pw');
		$submit = t('_rec_pw_btn');
	}
	echo makeHead($top);
	// [login] => register [username] => random [email] => email [first] => first [last] => last [login_as] => username [submit] => Register
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	if (isset($_POST['login']) /*&& $_POST['login'] == 'register1' || isset($_POST['submit']) && $_POST['submit'] == 'Login'*/) {
		if (!isset($_POST['requestToken']) || $_POST['requestToken'] !== $_SESSION['requestToken']) {
			$error = t('_session_expired');
		}
		$u = new Users;
		if ($_POST['login'] == 'register1') {
			if (!isset($_POST['uname']) || empty($_POST['uname'])) {
				$error = t('_invalid_username');
			}
			if (!isset($_POST['email']) || empty($_POST['email'])) {
				$error = t('_invalid_email');
			//} else/*if (!isset($_POST['uname']) || empty($_POST['uname']))*/ {
			} elseif (!isset($_POST['password'])) {
				if (isset($_POST['login_as'])) {
					$login_as = $_POST['login_as'];
				} else {
					if (isset($_POST['uname'])) {
						$login_as = $_POST['uname'];
					} elseif ($pos = do_mbstr('strpos', $_POST['email'], '@')) {
	//					$pos = strpos($_POST['email'], '@');
						$login_as = do_mbstr('substr', $_POST['email'], 0, $pos);
					}
					//$_POST['login_as'] = isset($_POST['login']) ? $_POST['login'] : substr($_POST['email'], 0, strpos($_POST['email'], '@'));
				}
				//echo 'userPOST:<pre>'. print_r($u->getFromPOST(), true). '</pre>'. "\n";
				$u->getFromPOST();
				switch ($login_as) {
				case 'first':
					$username = $_POST['first'];
					break;
				case 'last':
					$username = $_POST['last'];
					break;
				case 'email':
					$pos = do_mbstr('strpos', $_POST['email'], '@');
					$username = do_mbstr('substr', $_POST['email'], 0, $pos);
					break;
	//			case 'username':
				default:
					$username = $_POST['uname'];
					break;
				}
				if (!empty($usr = $u->getWithUname($username))) {	// is it unique?
					if (isset($_GET['user']) && $usr['user_id'] == $_GET['user']) {		// check if supplied as parameter
						$id = $_GET['user'];	// use this ID for the next step
					} else {
						$error = t('_uname_taken');		// otherwise show error message
					}
				} elseif (!empty($u->getWithEmail($_POST['email']))) {	// is it unique?
					$error = t('_email_taken');
				} else {
					$u->setUsername($username);
		//			include BASE. 'pages/newpw.php';
					//echo 'sql: '. $u->insert(array('onlySQL' => true)). '<br>'. "\n";
					$id = $u->insert(null, array('getResultId' => true, 'noShowSuccess' => true));
					//echo 'id: '. $id. '<br>'. "\n";
					echo 'SQL: '. $u->getLastSQL(). '<br>'. "\n";
		//			$l = Login::getInstance();
		//			if (isset($usr['password']) && $l->checkPasswd($usr['password'], $_POST['passwd'])) {
		//				// valid login
		//			} else {
		//				$error = 'Incorrect user password.';
		//			}
				}
			}
		} elseif ($_POST['login'] == 'register2') {
			if (!isset($_POST['password']) || empty($_POST['password'])) {
				$error = t('_invalid_password');
			}
			if (!isset($_POST['password2']) || empty($_POST['password2'])) {
				$error = t('_invalid_password2');
			}
			if ($_POST['password'] !== $_POST['password2']) {
				$error = t('_passwords_mismatch');
			} else {
				// user needs to set password
				$u = new Users;
				$u->setPassword($_POST['password']);
				//echo 'sql: '. $u->update($_POST['user_id'], array('onlySQL' => true)). '<br>'. "\n";
				$u->update($_POST['user_id'], array('noShowSuccess' => true));
			}
		}
	}
	//<div class="container noflex" style="width:320px">
?>
<div id="login">
<h1><?=isset($heading1) ? $heading1 : t('_register_new_user')?></h1>
<?php
//	$error = 'Invalid login details.';
	if (isset($error) && !empty($error)) {
?>		<div id="login_error"><?=$error?></div>
<?php	//		Invalid login details.
	}
?>
<!--main-->
	<!--div class="panel"-->
	<!--form method="POST" action="<?=htmlspecialchars(getServerValue('PHP_SELF'))?>"<?=defined('CHARSET') ? ' accept-charset="UTF-8"' : ''?>-->
	<form method="POST" action="<?=htmlspecialchars(BASE. 'register')?>"<?=!empty($error) ? ' class="shake"' : ''?><?=defined('CHARSET') ? ' accept-charset="UTF-8"' : ''?>>
<?php
//	echo Field::out(array(
//		'id' => 'login',
//		'type' => 'hidden',
//		'name' => 'login',
//		'value' => 'register1',
//		'inputWrapExtra' => 'style="display:none"',
//	));
	echo Field::out(array(
		'id' => 'user_form_nonce',
		'type' => 'hidden',
		'name' => 'requestToken',
		'value' => isset($_SESSION['requestToken']) ? htmlspecialchars($_SESSION['requestToken']) : '',
	//	'inputWrapExtra' => 'style="display:none"',
	));
	//if (isset($_POST['login']) && $_POST['login'] == 'register' && !isset($_POST['password'])) {
	if (isset($id) && $id > 0 /*&& !isset($_POST['password'])*/) {
		echo Field::out(array(
			'id' => 'login',
			'type' => 'hidden',
			'name' => 'login',
			'value' => 'register2',
			//'inputWrapExtra' => 'style="display:none"',
		));
		echo Field::out(array(
			'id' => 'user_id',
			'type' => 'hidden',
			'name' => 'user_id',
			'value' => isset($id) ? htmlspecialchars($id) : '',
			//'value' => isset($_POST['id']) ? $_POST['id'] : $id,
			'inputWrapExtra' => 'style="display:none"',
		));
		echo Field::out(array(
			'id' => 'password',
			'type' => 'text',
			//'type' => 'password',
			//'noLabel' => true,
			'label' => t('_password'),
			'labelWrapClass' => 'label col',
			'showOptional' => false,
			'canShow' => true,
			//'canWipe' => true,
			'placeholder' => t('_password'),
			//'default_' => t('_choose_password'),
			//'defaultExtra' => 'style="margin:0 1em"',
			'inputWrapClass' => 'input input-group mb-4',
			'inputClass' => 'password-input',
			'inputWrapExtra' => 'style="width:100%"',
			'extra' => 'autofocus data-password-input="true" autocapitalize="off" spellcheck="false" autocorrect="off"',
		));
		echo Field::out(array(
			'id' => 'password2',
			'type' => 'text',
			//'type' => 'password',
			//'noLabel' => true,
			'label' => t('_confirm_password'),
			'labelWrapClass' => 'label col',
			//'default_' => t('_confirm_password'),
			'showOptional' => false,
			'canShow' => true,
			//'canWipe' => true,
			'placeholder' => t('_password'),
			//'default_' => t('_confirm_password'),
			//'defaultExtra' => 'style="margin:0 1em"',
			'inputWrapClass' => 'input input-group mb-4',
			'inputClass' => 'password-input',
			'inputWrapExtra' => 'style="width:100%"',
			'extra' => 'data-password-input="true" autocapitalize="off" spellcheck="false" autocorrect="off"',
		));
	} else {
		echo Field::out(array(
			'id' => 'login',
			'type' => 'hidden',
			'name' => 'login',
			'value' => 'register1',
			//'inputWrapExtra' => 'style="display:none"',
		));
		if (isset($id)) {
			echo Field::out(array(
				'id' => 'user_id',
				'type' => 'hidden',
				'name' => 'user_id',
				'value' => isset($id) ? htmlspecialchars($id) : '',
			//	'inputWrapExtra' => 'style="display:none"',
			));
		}
		echo Field::out(array(
			'id' => 'uname',
			'type' => 'text',
			//'noLabel' => true,
			'label' => t('_username'),
			'labelWrapClass' => 'label col',
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => t('_username'),
			//'default_' => t('_username'),
			//'defaultExtra' => 'style="margin:0 1em"',
			'value' => isset($_POST['uname']) ? htmlspecialchars($_POST['uname']) : '',
			//'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'extra' => 'autofocus autocapitalize="off" spellcheck="false" autocorrect="off"',
		));
		echo Field::out(array(
			'id' => 'email',
			//'type' => 'text',
			'type' => 'email',
			//'noLabel' => true,
			'label' => t('_email'),
			'labelWrapClass' => 'label col',
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => t('_email'),
			//'default_' => t('_email'),
			//'defaultExtra' => 'style="margin:0 1em"',
			'value' => isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '',
			//'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'extra' => 'autocapitalize="off" spellcheck="false" autocorrect="off"',
		));
		echo Field::out(array(
			'id' => 'first',
			'type' => 'text',
			//'noLabel' => true,
			'label' => t('_firstname'),
			'labelWrapClass' => 'label col',
			'optional' => true,
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => t('_firstname'),
			//'default_' => t('_firstname'),
			//'defaultExtra' => 'style="margin:0 1em"',
			'inputWrapClass' => 'input',
			'value' => isset($_POST['first']) ? htmlspecialchars($_POST['first']) : '',
			'extra' => 'spellcheck="false" autocorrect="off"',
	//		'inputWrapExtra' => 'style="width:100%"',
		));
		echo Field::out(array(
			'id' => 'last',
			'type' => 'text',
			//'noLabel' => true,
			'label' => t('_lastname'),
			'labelWrapClass' => 'label col',
			'optional' => true,
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => t('_lastnamePH'),
			//'default_' => t('_lastname'),
			//'defaultExtra' => 'style="margin:0 1em"',
			'inputWrapClass' => 'input',
			'value' => isset($_POST['last']) ? htmlspecialchars($_POST['last']) : '',
	//		'inputWrapExtra' => 'style="width:100%"',
			'extra' => 'spellcheck="false" autocorrect="off"',
		));
		if (!isset($_GET['user'])) {
			echo Field::out(array(
				'id' => 'login_as',
				'type' => 'select',
				//'noLabel' => true,
				'label' => t('_login_as'),
				'labelWrapClass' => 'label col',
		//		'optional' => true,
				'showOptional' => false,
				//'canWipe' => true,
				'options' => array(
					'username' => t('_username'),
					'first' => t('_firstname'),
					'last' => t('_lastname'),
					'email' => t('_first_part_email'),
				),
				'value' => isset($_POST['login_as']) ? htmlspecialchars($_POST['login_as']) : '',
			//	'placeholder' => t('_login_as'),
		//		'placeholder' => t('_Select'),
			//	'default_' => t('_username'),
				//'default_' => t('_login_as'),
				//'defaultExtra' => 'style="margin:0 1em"',
				'inputWrapClass' => 'input',
		//		'inputWrapExtra' => 'style="width:100%"',
			));
		}
	}
	echo Field::out(array(
			'id' => 'submit',
			'type' => 'submit',
			'makeButton' => true,
//			'inputClass' => 'butt no-button',
			'inputClass' => 'br2 butt yellow no-button g-recaptcha',
//			'inputClass' => 'br2 butt green no-button g-recaptcha',
			'inputWrapClass' => 'input mt-2 col-md-12',
			//'inputWrapClass' => 'input col-md-12',
			'inputWrapExtra' => 'style="display: flex;justify-content: center;"',
			'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
			'extra' => 'value="login" style="margin: 1em 0" data-sitekey="'. Captcha::v3_sitekey. '" data-callback="onSubmit" data-action="submit"',
			'inner' =>'<span>'. $submit. '</span>',
//			'inputClass' => 'g-recaptcha',
	));
?>
	</form>
<!--/div-->
	<p id="nav">
		<!--a href="<?=BASE?>"><?=t('_home_page')?></a-->
		<br>
		<a href="<?=BASE. 'login'?>"><?=t('_login')?></a>
		<br>
		<br>
	</p>
</div>
<div class="clear"></div>
