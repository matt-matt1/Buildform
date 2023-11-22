<?php
namespace Yuma;
use Yuma\HTML\Breadcrumbs;
use Yuma\HTML\Field;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	if ( !isset($_GET['database']) ) {
		Redirect( BASE. 'databases' );
	}
//	if ( isset($_GET['database']) && (!isset($_GET['table']) || empty($_GET['table'])) ) {
//		Redirect( BASE. 'database/'. $_GET['database'] );
//	}
	$dbname = $_GET['database'];
//	$table = isset($_POST['tbname']) ? $_POST['tbname'] : $_GET['table'];
	// *****
	// Pressed OK (after table is created) - user acknowledged notice
	// *****
	if (isset($_POST['pressed'])) {
		if ($_POST['pressed'] === 'ok' /*|| (!isset($_POST['tbname']) || !isset($_GET['table']))*/) {	// list all databases, if select ok from notice
			Redirect( BASE. 'database/'. $dbname );
		} elseif ($_POST['pressed'] === 'ignore') {
			//$table = isset($_POST['tbname']) ? $_POST['tbname'] : (isset($_GET['table']) ? $_GET['table'] : '');
//			if (isset($_POST['tbname']))
//				$table = $_POST['tbname'];
				//$table = isset($_GET['table']) ? $_GET['table'] : $_POST['tbname'];
		//		$table = isset($_POST['tbname']) ? $_POST['tbname'] : $_GET['table'];
//			if (empty($table) && isset($_GET['table']))
//				$table = $_GET['table'];
/*			if ($_GET['table'] === ADD_TBL || $_GET['table'] === ADD_FRM) {
				Redirect( BASE. 'database/'. $dbname );
			}*/
			Redirect( BASE. 'database/'. $dbname. '/table/'. $table );
			//Redirect( BASE. 'database/'. $dbname. (isset($_GET['table']) ? '/table/' : '/form/'). $table );
		}
	}
	$default_new_table_data = 'id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY';
	echo makeHead(array( 'page_title' => t('_tbl_desc'), 'body_id' => 'dbtable', 'body_class' => "", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
?>
	<div class="container">
		<div class="panel">
<?php

/*****************************************************************************
 * logic
 */
	if ($_GET['tables'] === ADD_TBL || $_GET['tables'] === ADD_FRM)	// selected to add either a table or a form
	{
		if (isset($_POST['tbname']) && !empty($_POST['tbname'])) {	// if posted a non-blank name
			$table = $_POST['tbname'];
			$qry = "CREATE TABLE {$dbname}.{$table} ({$default_new_table_data})";//id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY)";
			try {
				$db->run($qry);
//				$_GET['table'] = $_POST['tbname'];
				if (defined('NOTIFY_CREATE_TABLE') && NOTIFY_CREATE_TABLE) {
					$note = new Note(Array(
						'type' => Note::notice,
						'message' => sprintf( t("_Created %s"), $table ),//_POST['tbname'] ),
						'ok' => t('_List all'),
						'canIgnore' => true,
						'post' => Array(
							'dbname' => $dbname,//_POST['dbname'],
							'tbname' => $table,//_POST['tbname'],
						),
						'ignore' => sprintf( t('_Goto %s'), $table ),//_POST['tbname']),
						'ignore_class' => "ok",
	//					'details' => "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				} else Redirect( BASE. 'database/'. $_GET['database'] );
			} catch (PDOException $e) {
				$note = new Note(Array(
					'type' => Note::warning,
					'canIgnore' => true,
					'message' => sprintf( t("_Cannot create table %s"), $table ),//_POST['tbname'] ),
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". t('Query'). ": {$qry}",
				));
				echo $note->display();
			}
		} else {
			display_form();		// no name posted, so display the form
		}
	} else {
		display_form();		// no name posted, so display the form
	}
?>
		</div>
	</div>
<?php
		
/*****************************************************************************
 * functions
 */
	/**
	 * Display form to gether name of new table to create
	 */
	function display_form()
	{
		$dbname = array(
			'id' => 'tbname',
			'row_class' => 'row g-2 mb-4 mt-4',
			'label_class' => 'right',
			'placeholder' => t('_alpha-numeric characters'),
//			'title' => t('_move this table in another database'),
			'label' => t('_Table name'),//t('_Database name'),
			'label_title' => t('_alpha-numeric characters'),//t('_Database name'),
			'value' => (isset($_POST['tbname']) && !empty($_POST['tbname'])) ? $_POST['tbname'] : '',//(isset($_POST['dbname']) && !empty($_POST['dbname'])) ? $_POST['dbname'] : '',
//			'input_title' => !empty($collation) ? sprintf( t('_was %s'), $collation ) : '',
//			'label_class' => 'list_dbs',
//			'input_class' => 'list_dbs',
			'input_wrap_class' => 'input col',// -auto md-3',
			'extra' => 'pattern="[A-Za-z!@#$^&\(\)-=_\+\[\]\{\};`~, 0-9]+" maxlength="64" autofocus',//^[^\\/?%*:|\"<>.]{1,64}$
			'optional' => false,
//			'show_optional' => false,
 		);
		$submit = array(
			'make_button' => true,
			'id' => 'submit',
			'input_wrap_class' => 'input col-md-3 mx-auto',
			'input_class' => 'submit',
			'value' => t('_Add table'),//t('_Add database'),'_Submit')
			'type' => 'submit',
			'label_wrap_class' => 'hide xright',
			'row_class' => 'row g-2 mb-3',
			'inner' => '<i class="fa-solid fa-circle-plus fa-2x"></i> '. "&nbsp;"
		);
?>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<h1>
					<?php //echo sprintf( t('_Database %s'), delPrefix($dbname)); ?>
					<span class="xthis-page"><?=t('_Add a new table')?></span>
<!--					<span class="xthis-page"><?php printf( t('_Add table to database %s'), delPrefix($_GET['database']) ); ?></span>-->
				</h1>
				<form method="post" id="new_table" name="new_table">
					<input type="hidden" name="dbname" value="<?=$_GET['database']?>">
					<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
						<?=Field::out($dbname)?>
					<div class="row g-2 mb-4">
						<div class="col-md-3">&nbsp;</div>
						<div class="xnote-container hide col-auto mx-auto"><span class="invalid-note"><?=t('_Name is invalid')?></span></div>
					</div>
						<?=Field::out($submit)?>
				</form>
			</div>
<?php
	}
