<?php
namespace Yuma;
use Yuma\HTML;
use Yuma\HTML\Scripts;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
//$_SESSION['LoginToken'] = null;
//$_COOKIE['LoginToken'] = null;
/*
	if (isset($_GET['business']) && $_GET['business'] == 'testform') {
		echo makeHead(array( 'page_title' => t('_Business Admin'), 'body_id' => 'admin', 'body_class' => "", 'page_description' => t('_bus_desc'), 'page_posttitle' => "", 'page_pretitle' => "Business Admin | " ));
		//echo 'table1:<pre>'. print_r($tbl1, true). '</pre>'. "\n";
		include('../business/testform.php');
		return;
	}
 */
if ((isset($_POST['pressed']) && $_POST['pressed'] == 'ok') || (isset($_POST['action']) && $_POST['action'] == 'admin')) {
//	echo 'pressed ok';
	$_GET['checked'] = null;
	unset($_GET['checked']);
	$_GET['table-action'] = null;
	unset($_GET['table-action']);
	header('location: '. BASE. 'business/admin');
//	Redirect( BASE. 'business/admin' );
}
$spt = new Scripts();
$spt->enqueue(Array( 'name' => "MyAjax", 'src' => BASE. "js/MyAjax.js", 'version' => filemtime('js/MyAjax.js') ));
$spt->enqueue(Array( 'name' => "tabSelect", 'src' => BASE. "js/tabSelect.js", 'version' => filemtime('js/tabSelect.js') ));
//Scripts::getInstance()->enqueue(Array( 'name' => "tabbar7", 'src' => BASE. "js/tabbar7.js", 'version' => filemtime('js/tabbar7.js') ));
// ^ needed ???
	//Scripts::getInstance()->enqueue(Array( 'name' => "addClass", 'src' => BASE. "js/addClass.js", 'version' => filemtime('js/addClass.js') ));
///	Scripts::getInstance()->enqueue(Array( 'name' => "adds", 'src' => BASE. "js/adds.js", 'version' => filemtime('js/adds.js'), 'requires' => Array('bootstrap@5.0.1', "addEvent") ));
//	Scripts::getInstance()->enqueue(Array( 'name' => "dels", 'src' => BASE. "js/dels.js", 'version' => filemtime('js/dels.js'), 'requires' => Array('bootstrap@5.0.1', "addEvent") ));
///	Scripts::getInstance()->enqueue(Array( 'name' => "trigmodal", 'src' => BASE. "js/trigmodal.js", 'version' => filemtime('js/trigmodal.js'), 'requires' => Array('bootstrap@5.0.1') ));
/*					<a class="nav-link active" aria-current="page" href="#business">Business</a>*/
	if (isset($_GET['business']) && $_GET['business'] == 'admin') {
		//echo makeHead(array( 'page_title' => t('_Business Admin'), 'body_id' => 'admin', 'body_class' => "", 'page_description' => t('_bus_desc'), 'page_posttitle' => "", 'page_pretitle' => "Business Administration | " ));
		//echo 'table1:<pre>'. print_r($tbl1, true). '</pre>'. "\n";
		include('../business/admin.php');
		return;
	} else {
		//echo makeHead(array( 'page_title' => t('_Business'), 'body_id' => 'edit-view', 'body_class' => "", 'page_description' => t('_bus_desc'), 'page_posttitle' => "", 'page_pretitle' => "Business Edit/View | " ));
		include('../business/forms.php');
		return;
	}
?>
	<!-- begin page contents -->
	<form id="admin_form" method="post" action="business" data-showReq=false data-showDesc=true autocomplete="off">
<?php /*if (isset($_GET['business']) && $_GET['business'] == 'admin') {	?>
		<button type="submit" name="edit" value="business" style="position:absolute" class="btn btn-primary adminButton" id="admin-button">View / Edit<i class="fa-solid fa-file-pen fa-2xl" style="padding-left: 0.5em;"></i></button>
<?php } else {*/	?>
		<button type="submit" name="action" value="admin" class="btn btn-primary adminButton" id="admin-button">Management<i class="fa-solid fa-bars-progress fa-2xl" style="padding-left: 0.5em;"></i></button>
<?php /*}*/		?>
	</form>
	<div class="container">
<?php 
	$tabsParams = array(
		'wrapClass' => "panel novisual",
		'ulClass' => 'xnav-justified nav-fill justify-content-center',
		'liExtra' => 'style="margin-right:3em"',
		'tabPaneClass' => 'fade',
		'tabs-name' => 'edit',
		'ulExtra' => 'style="padding-left:3em"',
		'data' => array(
			array(
				'heading' => 'Business',
				'content' => '<div class="panel">
					<h2>business</h2>
					</div>',
			),
			array(
				'heading' => 'User',
				'content' => '<div class="panel">
					<h2>user</h2>
   					</div>',
			),
			array(
				'heading' => 'Document',
				'content' => '<div class="panel">
					<h2>document</h2>
					</div>',
			),
			array(
				'heading' => 'Journal',
				'content' => '<div class="panel">
					<h2>journal</h2>
					</div>',
			),
		),
	);

	if (isset($_GET['business']) && $_GET['business'] == 'admin') {
	ob_start();
		echo $tbl1->render();
	$out = ob_get_contents();
	ob_end_clean();
	} else {
	ob_start();
		include('../business/business.php');
	$out = ob_get_contents();
	ob_end_clean();
	}
	//appendToArrayInner(array &$array, string $findKey, string $findVal, $value, $newKey=null)
	appendToArray($tabsParams, 'heading', 'Business', $out, 'content');

	if (isset($_GET['business']) && $_GET['business'] == 'admin') {
	ob_start();
		$tbl2->render();
	$out = ob_get_contents();
	ob_end_clean();
	} else {
	ob_start();
		include('../business/user.php');
	$out = ob_get_contents();
	ob_end_clean();
	}
	appendToArray($tabsParams, 'heading', 'User', $out, 'content');

	if (isset($_GET['business']) && $_GET['business'] == 'admin') {
	ob_start();
		$tbl3->render();
	$out = ob_get_contents();
	ob_end_clean();
	} else {
	ob_start();
		include('../business/document.php');
	$out = ob_get_contents();
	ob_end_clean();
	}
	appendToArray($tabsParams, 'heading', 'Document', $out, 'content');

	if (isset($_GET['business']) && $_GET['business'] == 'admin') {
	ob_start();
		$tbl4->render();
	$out = ob_get_contents();
	ob_end_clean();
	} else {
	ob_start();
		include('../business/sent.php');
	$out = ob_get_contents();
	ob_end_clean();
	}
	appendToArray($tabsParams, 'heading', 'Sent', $out, 'content');

	$tabs_edit = new TabBar($tabsParams);
	echo $tabs_edit->render7();
/*		<div class="panel novisual"	role="navigation">
			<ul id="tabs-list_edit" class="nav nav-tabs xnav-justified nav-fill justify-content-center" role="tablist">
				<li class="nav-item" style="margin-right:3em" role="presentation">
					<button class="nav-link active" id="business-tab" data-bs-toggle="tab" data-bs-target="#business" type="button" role="tab" aria-controls="business" aria-selected="true">Business</button>
				</li>
				<li class="nav-item" style="margin-right:3em" role="presentation">
					<button class="nav-link" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="false">User</button>
				</li>
				<li class="nav-item" style="margin-right:3em" role="presentation">
					<button class="nav-link" id="document-tab" data-bs-toggle="tab" data-bs-target="#document" type="button" role="tab" aria-controls="document" aria-selected="false">Document</button>
				</li>
				<li class="nav-item" style="margin-right:3em" role="presentation">
					<button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab" aria-controls="sent" aria-selected="false">Sent</button>
				</li>
			</ul>
			<div id="tabs-content_edit" class="tab-content">
				<div id="business" class="tab-pane show fade active" role="tabpanel" aria-labelledby="business-tab">
					<div class="panel">
					<h2>business</h2>
<?php	/ *include('../business/business.php');* /	?>
					</div>
				</div>
				<div id="user" class="tab-pane fade" role="tabpanel" aria-labelledby="user-tab">
					<div class="panel">
					<h2>user</h2>
<?php	/ *include('../business/user.php');* /	?>
					</div>
				</div>
				<div id="document" class="tab-pane fade" role="tabpanel" aria-labelledby="document-tab">
					<div class="panel">
					<h2>document</h2>
<?php	/ *include('../business/document.php');* /	?>
					</div>
				</div>
				<div id="sent" class="tab-pane fade" role="tabpanel" aria-labelledby="sent-tab">
					<div class="panel">
					<h2>sent</h2>
<?php	/ *include('../business/sent.php');* /	?>
					</div>
				</div>
			</div>
*/ ?>
<?php /*
	$tabs = new TabBar(array(
		'data' => array(
			array(
				'heading' => 'Businesses',
//				'content' => $tbl1->render(),
			),
			array(
				'heading' => 'Users',
//				'content' => $tbl2->render(),
			),
			array(
				'heading' => 'documents',
//				'content' => $tbl3->render(),
			),
			array(
				'heading' => 'sent',
//				'content' => $tbl4->render(),
			),
		),
	));
//	echo 'business/table.php: ';
	$tabs->render6();
			<div id="pane1" class="pane">
 */ ?>
<?php 
	if ((isset($_POST['business']) && $_POST['business'] == 'admin') || (isset($_GET['business']) && $_GET['business'] == 'admin')) {
		include('../business/admin.php');
	} elseif ((isset($_POST['business']) && $_POST['business'] == 'user') || (isset($_GET['business']) && $_GET['business'] == 'user')) {
		include('../business/user.php');
	} elseif ((isset($_POST['business']) && $_POST['business'] == 'document') || (isset($_GET['business']) && $_GET['business'] == 'document')) {
		include('../business/document.php');
	} elseif ((isset($_POST['business']) && $_POST['business'] == 'sent') || (isset($_GET['business']) && $_GET['business'] == 'sent')) {
		include('../business/sent.php');
	} else {
		/*include('../business/business.php');*/
	}	?>
<?php /*
			</div>
		</div>*/ ?>
	</div>
