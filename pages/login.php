<?php
namespace Yuma;
use Yuma\HTML\Field;
use Yuma\HTML\Link;
use Yuma\HTML\Scripts;

//echo '<pre>'. print_r($_SERVER, true). '</pre>';
//exit();
defined('ABS_PATH') || (header(getServerValue('SERVER_PROTOCOL'). ' 403 Forbidden') & die('403.14 - Directory listing denied.'));
$pre = 'pages/login.php ';
$spt = new Scripts();
$spt->enqueue (Array ('name' => 'Cookie', 'src' => BASE. 'js/Cookie.js', 'version' => filemtime('js/Cookie.js'), 'put_in_header' => true ) );
$spt->enqueue (Array ('name' => 'login', 'src' => BASE. 'js/login.js', 'version' => filemtime('js/login.js') ) );
$spt->enqueue (Array ('name' => 'MyAjax', 'src' => BASE. 'js/MyAjax.js', 'version' => filemtime('js/MyAjax.js'), /*'put_in_header' => true*/ ) );
Link::style(Array(
	'name' => 'google.recaptcha',
	'href' => "https://www.gstatic.com",
	'rel' => "preconnect",
	'crossorigin' => "anonymous"
));
Link::style(Array(
	'name' => 'google_recaptcha',
	//'name' => 'google.recaptcha',
	'href' => "https://www.google.com",
//	'version' => getlastmod(),//date(),//filemtime('js/Element.js'),
	'rel' => "preconnect",
//	'integrity' => "sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3",
//	'crossorigin' => "anonymous"
));
//$spt->enqueue (Array ('name' => 'google-recaptcha', 'src' => 'https://www.google.com/recaptcha/api.js', 'async' => true, 'version' => getlastmod(), /*'put_in_header' => true*/ ) );
$spt->enqueue (Array ('name' => 'recaptcha2', 'src' => BASE. 'js/reCaptcha2.js', 'async' => true, 'version' => filemtime('js/reCaptcha2.js') ) );
$l = new LoginUser;//(true);
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
$error = $l->processLogin();
	echo makeHead(array( 'page_title' => t('_login_desc'), 'body_id' => '', 'body_class' => "no-js login", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
?>
<div id="login">
	<h1><?=isset($heading1) ? $heading1 : t('_login')?></h1>
<?php
	if (/*isset($error) &&*/ !empty($error)) {
		$log = 'error: '. $error;
		try {
			$logger = new logger;
			$logger->log($pre. $log);
		} catch (exception $e) {
			error_log ('error_log: '. $pre. $log);
		}
?>	<div id="login_error"><?=$error?></div>
<?php
	}
?>
	<form method="POST" action="<?=BASE. 'login'?>"<?=!empty($error) ? ' class="shake"' : ''?>"<?=defined('CHARSET') ? ' accept-charset="UTF-8"' : ''?>>
<?php
	echo Field::out(array(
		'id' => 'login',
		'type' => 'hidden',
		'name' => 'login',
		'value' => 'login',
	//	'inputWrapExtra' => 'style="display:none"',
	));
	if (isset($_POST['redirect'])) {
		$v = htmlspecialchars($_POST['redirect']);
	} elseif (isset($_GET['redirect'])) {
		$v = htmlspecialchars($_GET['redirect']);
	} else $v = '';
	echo Field::out(array(
		'id' => 'login',
		'type' => 'hidden',
		'name' => 'redirect',
		//'value' => (isset($_POST['redirect']) ? htmlspecialchars($_POST['redirect']) : isset($_GET['redirect'])) ? htmlspecialchars($_GET['redirect']) : '',
		'value' => $v,
	//	'inputWrapExtra' => 'style="display:none"',
	));
	echo Field::out(array(
		'id' => 'user_form_nonce',
		'type' => 'hidden',
		'name' => 'requestToken',
		'value' => isset($_SESSION['requestToken']) ? htmlspecialchars($_SESSION['requestToken']) : '',
	//	'inputWrapExtra' => 'style="display:none"',
	));
	echo Field::out(array(
			'id' => 'username',
			'type' => 'text',
			'noLabel' => true,
			'icon' => '<i class="fas fa-15x fa-user"></i>',
//			'label' => t('_username'),
			//'showOptional' => false,
			'showOptional' => true,
			//'canWipe' => true,
			'placeholder' => t('_username'),
//			'default_' => t('_username'),
//			'defaultExtra' => 'style="margin:0 1em;font-size:16px""',
//			'inputWrapClass' => 'input',
			'inputWrapClass' => 'input input-group mb-3',
			'inputWrapExtra' => 'style="width:100%"',
			'extra' => 'autofocus autocapitalize="off" spellcheck="false" autocorrect="off"',
			'value' => isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '',
	));
	echo Field::out(array(
			'id' => 'passwd',
			'type' => 'text',
			//'type' => 'password',
			'noLabel' => true,
			'icon' => '<i class="fas fa-15x fa-lock"></i>',
//			'label' => t('_password'),
			'optional' => true,		// TODO
			'showOptional' => false,
			'canShow' => true,
			//'canWipe' => true,
			'placeholder' => t('_password'),
//			'default_' => 'Password',
//			'defaultExtra' => 'style="margin:0 1em;font-size:16px""',
			'inputWrapClass' => 'input input-group mb-4',
			'inputClass' => 'password-input',
			'extra' => 'data-password-input="true" autocapitalize="off" spellcheck="false" autocorrect="off"',
			'inputWrapExtra' => 'style="width:100%"',
//			'value' => isset($_POST['passwd']) ? $_POST['passwd'] : '',
	));
/*	echo Field::out(array(
			'id' => 'remember',
			'type' => 'select',
			'noLabel' => true,
//			'label' => 'Remember',
			'optional' => true,
			'showOptional' => false,
			'options' => array(
				0 => t('_no_cookie'),
				1 => t('_1 hr'),
				2 => t('_2 hr'),
				3 => t('_3 hr'),
				4 => t('_4 hr'),
				5 => t('_5 hr'),
				10 => t('_10 hr'),
				24 => t('_24 hr'),
				48 => t('_48 hr'),
			),
			'placeholder' => t('_remember'),
			'default_' => t('_remember_hint'),
			'defaultExtra' => 'style="margin:0 1em;font-size:14px"',
			'inputWrapClass' => 'input col-6 xcol-md-2 xcol-sm-2 xcol-xs-1',
			'value' => isset($_POST['remember']) ? htmlspecialchars($_POST['remember']) : '',
	));*/
// Automatic render for reCAPTCHA
/*?><div class="g-recaptcha" data-sitekey="<?=Captcha::v2_invisible_sitekey?>"></div><?php*/
// ^end^ Automatic render for reCAPTCHA
/*	echo <<<EOS
<script>
	function onSubmit(token) {
		document.getElementById("demo-form").submit();
	}
</script>
	EOS;*/
	echo Field::out(array(
			'id' => 'submit',
			'type' => 'submit',
			'makeButton' => true,
//			'inputClass' => 'butt no-button',
			'inputClass' => 'br2 butt yellow no-button g-recaptcha',
//			'inputClass' => 'br2 butt green no-button g-recaptcha',
			'inputWrapClass' => 'input mt-2 col-md-12',
			'inputWrapExtra' => 'style="display: flex;justify-content: center;"',
//			'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
			'extra' => 'value="login" style="margin: 1em 0" data-sitekey="'. Captcha::v3_sitekey. '" data-callback="onSubmit" data-action="submit"',
			'inner' => '<span>'. t('_login'). '</span>',
//			'value' => t('_login'),
//			'inputClass' => 'g-recaptcha',
	));
	$user_id = 0;
	if (isset($username)) {
		$u = new Users;
		$user = $u->getUser($username);
		$user_id = $user['user_id'];
	}
?>
	</form><?php
?>	<p id="nav">
		<!--a href="<?=BASE?>"><?=t('_home_page')?></a-->
		<br>
		<a href="<?=BASE. 'register'?>"><?=t('_not_user')?></a>
		<br>
		<!--a href="<?=BASE. 'forgotpw'?>"><?=t('_forgotpw')?></a-->
		<a href="<?=BASE. 'register?user='. $user_id?>"><?=t('_forgotpw')?></a>
		<br>
		<br>
	</p>
</div>
<div class="clear"></div>
<?php
	$log = 'Awaiting login input...';
	try {
		$logger = new logger;
		$logger->log($pre. $log);
	} catch (exception $e) {
		error_log ('error_log: '. $pre. $log);
	}
