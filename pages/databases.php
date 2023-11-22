<?php
namespace Yuma;
use Yuma\HTML\Scripts;
use Yuma\HTML\Breadcrumbs;
use \PDO;

defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
$scr = new Scripts;
	//Scripts::getInstance()->enqueue(Array( 'name' => "addEvent", 'src' => BASE. "js/addEvent.js", 'version' => filemtime('js/addEvent.js') ));
	//Scripts::getInstance()->enqueue(Array( 'name' => "addClass", 'src' => BASE. "js/addClass.js", 'version' => filemtime('js/addClass.js') ));
	$scr->enqueue(Array( 'name' => "adds", 'src' => BASE. "js/adds.js", 'version' => filemtime('js/adds.js'), 'requires' => Array('bootstrap@5.0.1', "addEvent") ));
//	Scripts::getInstance()->enqueue(Array( 'name' => "dels", 'src' => BASE. "js/dels.js", 'version' => filemtime('js/dels.js'), 'requires' => Array('bootstrap@5.0.1', "addEvent") ));
	$scr->enqueue(Array( 'name' => "trigmodal", 'src' => BASE. "js/trigmodal.js", 'version' => filemtime('js/trigmodal.js'), 'requires' => Array('bootstrap@5.0.1') ));
	echo makeHead(array( 'page_title' => t('_Databases'), 'body_id' => 'databases', 'body_class' => "", 'page_description' => t('_dbs_desc'), 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
?>
	<!-- begin page contents -->
	<div class="container">
		<div class="panel">
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
<?php
/*****************************************************************************
 * logic
 */
	if (isset($_POST['pressed']) /*&& isset($_POST['dbname']) && $_POST['pressed'] === 'ignore'*/) {
		//unset($_GET['add']);
		if ($_POST['pressed'] == 'ignore' && isset($_POST['dbname']))
			Redirect( BASE. 'database/'. addPrefix($_POST['dbname']) );
		elseif (isset($_GET['database']))
			Redirect( BASE. 'database/'. addPrefix($_GET['database']) );
		else
			Redirect( BASE. 'databases' );
	}
/**/
	//if ($_GET['database'] === $GLOBALS['data']['ADD_DB'] || isset($_GET['add'])) {		// selected to add a new database
	//if ($_GET['database'] === ADD_DB || isset($_GET['add'])) {		// selected to add a new database
	//if ((isset($_GET['database']) && $_GET['database'] === ADD_DB) || isset($_GET[ADD_DB])) {		// selected to add a new database
	if ((isset($_POST['requestToken']) && isset($_POST['dbname']) && !empty($_POST['dbname'])) || (isset($_GET['database']) && isset($_GET[ADD_DB]))) {
	// *****
	// Add a new database
	// *****
/*			Redirect( BASE. 'databases' );
	}*/
		unset($_GET['databases']);
		//$dbname = (isset($_POST['dbname']) ? cleanString($_POST['dbname']) : isset($_GET['database'])) ? cleanString($_GET['database']) : '';
		if (isset($_POST['dbname']))
			$dbname = cleanString($_POST['dbname']);
		elseif (isset($_GET['database']))
			$dbname = cleanString($_GET['database']);
		else
			Redirect( BASE. 'databases' );
		$log = 'adding database: '. $dbname;
		(is_callable(array('Logger', 'log'))) &&
			Logger::getInstance()->log( $pre. $log ) ||
			error_log( $pre. $log );
		if (isset($_POST['pressed']) && isset($_POST['dbname']))		// User responded to notice
		{
			if ($_POST['pressed'] === 'ignore')
				Redirect( BASE. 'database/'. addPrefix($dbname) );
			else
				Redirect( BASE. 'databases' );
		} else {							// actually add the database, if a name was posted
//			enScript(Array( 'name' => "modal-add", 'src' => BASE. "js/modal-add.js", 'version' => fileatime('js/modal-add.js'), 'requires' => Array('bootstrap@5.0.1') ));
			if (isset($_POST['dbname']) && !empty($_POST['dbname'])) {
				//add_db();
				//$qry = 'CREATE DATABASE '. addPrefix(trim(strip_tags(htmlentities($_POST['dbname']))));
				$qry = 'CREATE DATABASE '. addPrefix($dbname);
				//$qry = 'CREATE DATABASE '. addPrefix(hyphenize($_POST['dbname']));
				try {
					unset($_GET['database']);
					unset($_GET['databases']);
					$db->run($qry);
					if (defined('NOTIFY_CREATE_DB') && NOTIFY_CREATE_DB) {
						$note = new Note(Array(		// draw notice after creation
							'type' => Note::notice,
							'message' => sprintf( t("_Created %s"), $_POST['dbname'] ),
							'ok' => t('_List databases'),
	//						'canIgnore' => true,
							'post' => Array(
								'dbname' => $dbname,
								'database' => $dbname,
							),
							'ignore' => sprintf( t('_Goto %s'), $dbname ),
							'ignore_class' => 'ok',
							//'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					} else Redirect( BASE. 'databases' );
				} catch (PDOException $e) {	// cannot create db
					$note = new Note(Array(
						'type' => 'error',
						'message' => sprintf( t("_Cannot create database %s"), $dbname ),
						'details' => t('_Ensure the name is unique and not a reserved word'). "<br>\n". implode(' - ', (array)$e->errorInfo). "<br>\n". t('_Query'). ": {$qry}",
					));
					echo $note->display();
				}
			} else {			// if no db name posted, draw the form to ask it
	$scr->enqueue(Array( 'name' => 'common', 'src' => BASE. 'js/common.js', 'version' => filemtime('js/common.js') ));
				display_add_form();
			}
		}
//	} elseif ((!isset($_POST['dbname']) || empty($_POST['dbname'])) && isset($_GET['databases']) && $_GET['databases'] == ADD_DB) {
//		display_add_form();
	} else {	// not adding a database
/**/
/*		$inl = <<<EOS
function modalAction_deleted(btn)
{
	if (modal.instance)
		modal.instance.hide();
	if (btn.href)
		window.location.href = btn.href;
}
EOS;
		print '<script>'. $inl. '</script>';*/
		$qry = 'SHOW DATABASES';
		try {
			$raw = $db->run($qry)->fetchAll(PDO::FETCH_COLUMN);
//				echo '<!-- raw:'. print_r($raw, true). ' -->';
			if (!empty($raw))
				print '<script>const reserved = ["'. implode('","', $raw). '"];</script>';		// form a javascript variable of resered db names
//			foreach ($raw as $r) {
//				print 
			if ((!isset($_POST['dbname']) || empty($_POST['dbname'])) && isset($_GET['databases']) && $_GET['databases'] == ADD_DB) {
				display_add_form();
			} else {
				$dbs = array();
				//if (is_array($raw) && !empty($raw) && (!isset($GLOBALS['data']['SHOW_OTHER_DBS']) || !$GLOBALS['data']['SHOW_OTHER_DBS']))
				if (is_array($raw) && !empty($raw) && (!defined('SHOW_OTHER_DBS') || !SHOW_OTHER_DBS))
				{
					foreach($raw as $name)
					{
						if (hasPrefix($name))
						{
							$dbs[] = $name;
						}
					}
				} else {
					$dbs = $raw;
				}
	//			echo '<!-- dbs:'. print_r($dbs, true). ' -->';
				display_list( $dbs );
			}
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,
				'message' => sprintf( t("_Database error: %s"), implode(' - ', (array)$e->errorInfo) ),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
			));
			echo $note->display();
		}
	}
?>
	<!-- ending page contents -->
		</div>
	</div>
	<!-- end page contents -->
<?php
/*****************************************************************************
 * functions
 */
	/**
	 * Form to ask database to add
	 */
	function display_add_form(/*$results*/) {
		$dbname = array(
			'id' => 'dbname',
//			'name' => 'dbname',
			'row_class' => 'row g-2 mb-4 mt-4',
			'label_class' => 'right',
			'placeholder' => t('_alpha-numeric characters'),
//			'title' => t('_move this table in another database'),
			'label' => t('_Database name'),
			'label_title' => t('_alpha-numeric characters'),//t('_Database name'),
			'value' => (isset($_POST['dbname']) && !empty($_POST['dbname'])) ? $_POST['dbname'] : '',
//			'input_title' => !empty($collation) ? sprintf( t('_was %s'), $collation ) : '',
//			'label_class' => 'list_dbs',
//			'input_class' => 'list_dbs',
			'input_wrap_class' => 'input col',// -auto md-3',
			'extra' => 'pattern="[A-Za-z!@#$^&\(\)-=_\+\[\]\{\};`~, 0-9]+" maxlength="64" autofocus',//^[^\\/?%*:|\"<>.]{1,64}$
			'optional' => false,
//			'show_optional' => false,
 		);
/*		$valid = array(
//			'make_button' => true,
			'id' => 'submit',
			'nolabel' => true,
			'input_wrap_class' => 'invalid-note col-md-3 mx-auto',
//			'input_class' => 'submit',
//			'value' => t('_Add database'),
//			'type' => 'submit',
//			'label_wrap_class' => 'hide xright',
			'row_class' => 'row g-2 mb-3',
			'noinput' => true,
			'inner' => t('_Name is invalid'),//'<i class="fa-solid fa-circle-plus fa-2x"></i> '. "&nbsp;"
			//'optional' => true,
			//'show_optional' => false,
		);*/
		$submit = array(
			'make_button' => true,
			'id' => 'submit',
			'input_wrap_class' => 'input col-md-3 mx-auto',
			'input_class' => 'submit',
			'value' => t('_Add database'),
			'type' => 'submit',
			'label_wrap_class' => 'hide xright',
			'row_class' => 'row g-2 mb-3',
			'inner' => '<i class="fa-solid fa-circle-plus fa-2x"></i> '. "&nbsp;"
			//'optional' => true,
			//'show_optional' => false,
		);
?>
		<h1>
			<span class="xthis-page"><?php l('_Add a new database'); ?></span>
		</h1>
		<form method="post">
			<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
				<?=Field::out($dbname)?>
				<?/*=Field::out($valid)*/?>
			<div class="row g-2 mb-4">
				<div class="col-md-3">&nbsp;</div>
				<div class="xnote-container hide col-auto mx-auto"><span class="invalid-note"><?=t('_Name is invalid')?></span></div>
			</div>
<!--			<div class="note-container"><span class="invalid-note"><?=t('_Name is invalid')?></span></div>-->
				<?=Field::out($submit)?>
		</form>
	</div>
<?php
	}

	/**
	 * List available databases
	 */
	function display_list($dbs) {
		$href = BASE. 'database/';
		$title = t('_Do you really want to do this?');
?>
<!--			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>-->
				<h1><?php l('_Select a database'); ?></h1>
				<table class="zebra">
					<tbody>
<?php
		if (!isset($dbs) || empty($dbs)) {	// no databases
?>
						<tr id="add-db-last" data-token="<?=$_SESSION['requestToken']?>" data-placeholder="<?=t('_alpha-numeric characters')?>"><td><span class="empty"><?php l("_empty"); ?></span></td></tr>
<?php
		} else {
			$last = count($dbs)-1;
			$k = 0;
		//	echo '<!-- dbs:'. print_r($dbs, true). ' -->';
			foreach( (array)$dbs as $name ) {	// each database
		//	echo '<!-- name:'. print_r($name, true). ' -->';
				//if (!empty($name) && !in_array( $name, $GLOBALS['data']['RESERVED'] )) {
				if (!empty($name) && !in_array( $name, RESERVED )) {
?>
						<tr<?php if ($k == $last)
				{
					echo ' id="add-db-last"';
					if (isset($_SESSION['requestToken']))
					{
						echo ' data-token="'. $_SESSION['requestToken']. '"';
					}
					echo ' data-placeholder="'. t('_alpha-numeric characters'). '"';
				}?>>
							<td class="symbol"><span class="symbol db"></span></td>
<?php
					if (hasPrefix($name)) {
						$dbname = htmlspecialchars(delPrefix($name));
						//$h = sprintf( '<span class="highlight">%s</span>', $dbname );
						$h = sprintf( "<span class='highlight'>%s</span>", $dbname );
						$body = sprintf( t('_Really remove database %s'), $dbname);
						//$body = sprintf( t('_Really remove database %s'), $h );
						//$confirm = sprintf( t('_Really remove database %s'), $dbname);
//xhref="use?dbname=< ?=addPrefix($dbname)? >"
//xhref="remove?dbname=< ?php echo $row[0]; ? >"
//href2="note?dbname=< ?php echo $row[0]; ? >&message=< ?php urlencode( sprintf( l('_Really remove database %s'), $dbname ) ); ? >&type=< ?php echo Note::warning; ? >&ok=Continue&canIgnore=true&ignore=Cancel&redirect=remove"
?>
							<td><a class="symbol" href="<?=$href. addPrefix($dbname)
?>" title="<?=sprintf( t("_Database %s!"), /*squote(*/$dbname/*)*/ ); ?>"><?=$dbname
?></a></td>
							<!--<td class="symbol"><a onclick="return confirm('<?//=$confirm?>');"-->
							<td class="symbol"><a data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?=$dbname
?>" data-title="<?=$title?>" data-body="<?=$body
?>" data-modal-button-del="value:proceed" data-modal-button-add="node:button,type:submit,name:modal-button,value:deletedb,class:btn btn-danger no-button,aria-label:<?=t('Delete')?>,data-url:<?=$href. $dbname?>?remove,innerText:<?=t('Delete')/*,data-url:<?=$href?>*/
?>" class="symbol modal-trigger" href="<?=$href. $dbname
?>?remove" title="<?=sprintf( t("_Remove database %s"), /*squote(*/$dbname/*)*/ );
?>"><span class="symbol delete"></span></a></td>
<?php
//					} elseif (defined('SHOW_OTHER_DBS') && SHOW_OTHER_DBS) {
					} else {
						$dbname = htmlspecialchars($name);
?>
							<td class="symbol"><a class="symbol" href="<?=$href. $dbname
?>" title="<?php echo sprintf( t("_Database %s"), squote($dbname) ); ?>"><?=$dbname
?></a></td>
<?php
					}
					++$k;
?>
						</tr>
<!--						<option value="<?=$dbname?>"><?=$dbname?></option>-->
<?php
				} else {
					--$last;
				}
			}
		//	$menu->add( new Button( $row[0], "", 2 ) );
		}
		//$menu = new ButtonMenu([], 1);
/*	$menu = new ButtonMenu(Array(
		'content' => Array(
			new Button(Array(
				'text' => t('_New'),
				'slug' => 'begin',
				'desc' => t('_Create a new database with forms'),
				'title' => t('_Create a new database with forms'),
				'wrap' => 'option',
				'indents' => 3)),
		),
		'newlines' => true,
		'indents' => 2,
		'canAdd' => false,
		'wrap' => 'select id="dbname" name="dbname"',
		'class' => ''));
		echo $menu->HTML();*/
		$href = BASE. 'databases/';
?>
					</tbody>
					<tfoot>
						<tr>
<!--						<td class="symbol"><a data-toggle="add-db-prompt" data-target="#add-db-last" href="< ?$href. $GLOBALS['data']['ADD_DB']-->
							<td class="symbol">
								<a data-toggle="add-db-prompt" data-target="#add-db-last" href="<?=$href. ADD_DB
?>" class="dbadd add-db symbol" title="<?php l('_Add a new database');
?>"><span class="symbol add"></span></a>
							</td>
							<td>
								<div class="note-container">
									<div class="invalid-note hide"><?=t('_Database name is taken')?></div>
								</div>
							</td>
						</tr>
						<tr>
						</tr>
					</tfoot>
				</table>
			</div>
<?php
	}
