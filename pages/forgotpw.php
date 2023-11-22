<?php
namespace Yuma;
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
	echo makeHead(array( 'page_title' => t('_login_desc'), 'body_id' => '', 'body_class' => "login", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
	//<div class="container noflex" style="width:320px">
?>
<div id="login">
<h1><?=isset($heading1) ? $heading1 : t('_login')?></h1>
<?php
	$error = 'Invalid login details.';
	if (isset($error) && !empty($error)) {
?>		<div id="login_error"><?=$error?></div>
<?php	//		Invalid login details.
	}
?>
<!--main-->
	<!--div class="panel"-->
	<form method="POST">
<?php
	echo Field::out(array(
			'id' => 'login',
			'type' => 'hidden',
			'name' => 'login',
			'value' => 'login',
			'inputWrapExtra' => 'style="display:none"',
	));
	echo Field::out(array(
			'id' => 'username',
			'type' => 'text',
			//'noLabel' => true,
			//'label' => '<i class="fas fa-user"></i>',
			'label' => t('_Username'),
			//'optional' => true,
			//'showOptional' => false,
			'showOptional' => true,
			//'canWipe' => true,
			'placeholder' => t('_username'),
			'default_' => 'Username',
		//	'default_' => t('_Username'),
			'defaultExtra' => 'style="margin:0 1em;font-size:16px""',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'extra' => 'autofocus',
	));
	echo Field::out(array(
			'id' => 'passwd',
			'type' => 'text',
			//'noLabel' => true,
			//'label' => '<i class="fas fa-lock"></i>',
			'label' => 'Password',
			'optional' => true,
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => t('_password'),
			'default_' => 'Password',
			'defaultExtra' => 'style="margin:0 1em;font-size:16px""',
	//		'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
	));
	echo Field::out(array(
			'id' => 'remember',
			'type' => 'select',
			//'noLabel' => true,
			'label' => 'Remember',
			'optional' => true,
			'showOptional' => false,
			'options' => array(
				0 => 'never',
				1 => '1 hour',
				2 => '2 hours',
				3 => '3 hours',
				4 => '4 hours',
				5 => '5 hours',
				10 => '10 hours',
			),
			'placeholder' => t('_remember'),
			'default_' => 'Remember',
			'defaultExtra' => 'style="margin:0 1em;font-size:16px"',
//			'inputWrapClass' => 'input',
			'rowClass' => 'ml-2 col-md-7 xcol-sm-2 xcol-xs-1 g-3 form-group',
	));
	echo Field::out(array(
			'id' => 'submit',
			'value' => 'Login',
			'type' => 'submit',
			'inputWrapClass' => 'input col-md-12',
			'inputWrapExtra' => 'style="display: flex;justify-content: center;"',
			'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
	));
?>
	</form>
<!--/div-->
	<p id="nav">
	<a href="<?=BASE?>"><?=t('_home_page')?></a>
	</p>
</div>
<div class="clear"></div>
<?php
/*
		$register_form = '';
		$register_form .= '<div class="panel">'. "\n";
		//$register_form .= '<div class="pane">'. "\n";
		$register_form .= '<form id="my-register_form" method="POST">'. "\n";
		$register_form .= Field::out(array(
			'id' => 'login',
			'type' => 'hidden',
			'name' => 'login',
			'value' => 'register',
		));
		$register_form .= Field::out(array(
			'id' => 'passwd',
			'type' => 'text',
			'noLabel' => true,
			//'optional' => true,
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => 'Password',
	//		'inputWrapClass' => 'has-feedback',
			'default_' => 'Password',
			//'default_' => 'Search for a Business name - start typing',
			'defaultExtra' => 'style="margin:0 1em"',
			//'inputWrapClass' => 'input has-feedback form-group autocomplete',
			//'inputWrapClass' => 'input autocomplete',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'rowClass' => 'ml-2 col-md-6 col-sm-9 col-xs-12 g-3 has-feedback form-group',
//			'rowExtra' => 'style="float:left;margin:0 1.25em"',
//			'extra' => 'data-search-in="business" data-search-id="business_name"',
		));
		$register_form .= Field::out(array(
			'id' => 'username',
			'type' => 'text',
			'noLabel' => true,
			//'optional' => true,
			'showOptional' => false,
			//'canWipe' => true,
			'placeholder' => 'Username',
	//		'inputWrapClass' => 'has-feedback',
			'default_' => 'Username',
			//'default_' => 'Search for a Business name - start typing',
			'defaultExtra' => 'style="margin:0 1em"',
			//'inputWrapClass' => 'input has-feedback form-group autocomplete',
			//'inputWrapClass' => 'input autocomplete',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'rowClass' => 'ml-2 col-md-6 col-sm-9 col-xs-12 g-3 has-feedback form-group',
//			'rowExtra' => 'style="float:left;margin:0 1.25em"',
//			'extra' => 'autofocus data-search-in="business" data-search-id="business_name"',
		));
		$register_form .= Field::out(array(
			'id' => 'submit',
//			'name' => 'submit',
			'value' => 'Submit',
			'type' => 'submit',
//			'optional' => true,
//			'showOptional' => false,
			'inputWrapClass' => 'input col-md-12',
			'rowClass' => 'ml-2 col-md-5 g-2',
			'rowExtra' => 'style="clear:both;float:left;margin:0 1.25em"',
		));
		$register_form .= '</form>';
		//$register_form .= '</div>';
		$register_form .= '</div>';
*/
