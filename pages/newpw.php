<?php
namespace Yuma;
	header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.');	// UNUSED
	//defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
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
	echo makeHead(array( 'page_title' => t('_newpw_desc'), 'body_id' => 'newpw', 'body_class' => "login new-password", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
	//<div class="container noflex" style="width:320px">
?>
<div id="login">
<h1><?=isset($heading1) ? $heading1 : t('_newpw')?></h1>
<?php
//	$error = 'Invalid login details.';
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
		'value' => 'new-password',
		'inputWrapExtra' => 'style="display:none"',
	));
/*	echo Field::out(array(
			'id' => 'username',
			'type' => 'text',
			//'noLabel' => true,
			//'label' => '<i class="fas fa-user"></i>',
			'label' => t('_username'),
			//'optional' => true,
			//'showOptional' => false,
			'showOptional' => true,
			//'canWipe' => true,
			'placeholder' => t('_username'),
			'default_' => t('_username'),
			'defaultExtra' => 'style="margin:0 1em;font-size:16px""',
			'inputWrapClass' => 'input',
			'inputWrapExtra' => 'style="width:100%"',
			'extra' => 'autofocus',
	));*/
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
