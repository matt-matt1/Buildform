<?php
namespace Yuma;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	if ( !isset($_GET['database']) ) {
		Redirect( BASE. 'databases' );		// show all databases
	}
	if ( /*isset($_GET['database']) &&*/ !isset($_GET['table']) /*|| !isset($_GET['form'])*/ ) {
		Redirect( BASE. 'database/'. $_GET['database'] );		// show table and forms in this database
	}
	$dbname = $_GET['database'];
	//$table = ((isset($_POST['fname'])) ? $_POST['fname'] : (isset($_GET['form']))) ? $_GET['form'] : '';
	$table = isset($_GET['table']) ? $_GET['table'] : /*isset($_GET['form'])) ? $_GET['form'] :*/ '';
	// *****
	// Pressed OK (after form is created) - user acknowledged notice
	// *****
	if (isset($_POST['pressed']) && $_POST['pressed'] === 'ok') {	// list all databases, if select ok from notice
		Redirect( BASE. 'database/'. $dbname );
	}
	if (isset($_POST['pressed']) && $_POST['pressed'] === 'ignore') {
/*		if (isset($_POST['fname']))
			$form = $_POST['fname'];
		if (empty($form) && isset($_GET['form']))
			$form = $_GET['form'];*/
		//Redirect( BASE. 'database/'. $dbname. (isset($_GET['table']) ? '/table/' : '/form/'). $table );
		Redirect( BASE. 'database/'. $dbname. '/table/'. $table );
	}
	echo makeHead(array( 'page_title' => t('_row_desc'), 'body_id' => 'record', 'body_class' => "", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
?>
	<!-- begin page contents -->
	<div class="container">
		<div class="panel">
<?php
global $db;
/*****************************************************************************
 * logic
 */
	display_top();
	$qry = 'SELECT * FROM '. $dbname. '.'. $table;
	try {
		$rows = $db->run($qry)->fetchAll();
		//$_GET['form'] = $_POST['fname'];
/*		$note = new Note(Array(
//			'details' => "<br>\nQuery: {$qry}",
			'type' => Note::notice,
			'message' => sprintf( t("_Created %s"), $_POST['fname'] ),
			'ok' => t('_List all'),
			'canIgnore' => true,
			'post' => Array(
				'dbname' => $_GET['database'],
				'fname' => $_POST['fname'],
			),
			'ignoreSlug' => '?edit',
			'ignore' => sprintf( t('_Goto %s'), $_POST['fname']),
			'ignore_class' => "ok",
//			'details' => "<br>\nQuery: {$qry}",
		));
		echo $note->display();*/
		if (!empty($rows)) {
			display_rows($rows);
		}
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::warning,
			'canIgnore' => true,
			'message' => sprintf( t("_Cannot get information for %s"), $table ),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". t('_Query'). ": {$qry}",
		));
		echo $note->display();
	}
	if ($table == ADD_ROW) {
	}
	$qry = 'SHOW FULL COLUMNS FROM '. $dbname. '.'. $table;
	try {
		$columns = $db->run($qry)->fetchAll();
		//$_GET['form'] = $_POST['fname'];
/*		$note = new Note(Array(
//			'details' => "<br>\nQuery: {$qry}",
			'type' => Note::notice,
			'message' => sprintf( t("_Created %s"), $_POST['fname'] ),
			'ok' => t('_List all'),
			'canIgnore' => true,
			'post' => Array(
				'dbname' => $_GET['database'],
				'fname' => $_POST['fname'],
			),
			'ignoreSlug' => '?edit',
			'ignore' => sprintf( t('_Goto %s'), $_POST['fname']),
			'ignore_class' => "ok",
//			'details' => "<br>\nQuery: {$qry}",
		));
		echo $note->display();*/
		if (empty($columns)) {
			print t('_no columns');
		} else {
			display_form($columns);
		}
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::warning,
			'canIgnore' => true,
			'message' => sprintf( t("_Cannot get information for %s"), $table ),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". t('_Query'). ": {$qry}",
		));
		echo $note->display();
	}
	/**
	 */
	//Scripts::getInstance()->enqueue(Array( 'name' => 'form', 'src' => BASE. 'js/form.js', 'version' => filemtime('js/form.js') ));
			display_bottom();


/*****************************************************************************
 * functions
 */
	function display_top()
	{
?>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<h1>
					<span class="xthis-page"><?=t('_Add a new record')?></span>
				</h1>
				<form method="post" id="new_table" name="new_table">
					<input type="hidden" name="dbname" value="<?=$_GET['database']?>">
					<input type="hidden" name="table" value="<?=$_GET['table']?>">
					<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
<?php
/*					<div class="row">
						<div class="input_field">
							<div class="label col-25">
								<label for="tbname"><?=t('_Table name')?>:</label>
							</div>
							<div class="input col-75">
							<input type="text" id="tbname" name="tbname" value="<?=$table?>" autofocus>
							</div>
						</div>
					</div>*/
	}

	function display_rows($rows)
	{
		print t('Records'). ':'. print_r($rows, true). ':'. t('Records');
	}

	/**
	 * Display form to gether information for each column in the table
	 */
	function display_form($columns)
	{
		foreach ($columns as $k => $v) {
			//Field | Type    | Collation | Null | Key | Default | Extra | Privileges                      | Comment
			$values['row_'. $k] = array(
				'id' => 'row_'. $k,
				'name' => 'row['. $k. ']['. $v['Field']. ']',
				'label' => $v['Field'],
				'label_title' => $v['Comment'],
//				'value' => $collation,
				'input_title' => !empty($collation) ? sprintf( t('_was %s'), $collation ) : '',
				'row_class' => 'row g-2 mb-4',
				'label_class' => 'right',
				'input_wrap_class' => 'input col-md-3',
				'after' => 'has after',
//				'type' => 'select',
//				'roptions' => Select::drawOptions(SQL::COLLATION, '', $collation),
				'optional' => true,
				'show_optional' => false
			);
			print Field::out($values['row_'. $k]);
		}/*
?>
					<div class="row">
						<div class="input_field">
							<div class="label col-25">
			<!--					<label for="submit"><?php l('_Submit'); ?>:</label>-->
		&nbsp;
							</div>
							<div class="submit">
<!--								<input type="submit" id="submit" name="submit" value="<?php ($_GET['table'] === ADD_ROW) ? l('_Add record') : l('_Add record'); ?>">-->
								<input type="submit" id="submit" name="submit" value="<?=t('_Add table')?>">
							</div>
						</div>
					</div>
<?php	*/
	}

	function display_bottom()
	{
		$val_submit = array(
			'make_button' => true,
			'id' => 'submit',
			'input_wrap_class' => 'input col-md-3 mx-auto',
			'input_class' => 'submit',
			'value' => t('_Add record'),//Save changes'),
			'type' => 'submit',
			'label_wrap_class' => 'hide xright',
			'row_class' => 'row g-2 mb-3',
			//'inner' => '<i class="fa-regular fa-circle-plus fa-2x"></i> '. "&nbsp;"
			'inner' => '<i class="fa-solid fa-circle-plus fa-2x"></i> '. "&nbsp;"
		);
		print Field::out($val_submit);
?>
				</form>
			</div>
<?php
	}
