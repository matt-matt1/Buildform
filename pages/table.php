<?php
namespace Yuma;
use \PDO;
use Yuma\HTML\Breadcrumbs;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	if ( !isset($_GET['database']) ) {
		Redirect( BASE. 'databases' );
	}
	if ( isset($_GET['database']) && (!isset($_GET['table']) || empty($_GET['table'])) ) {
		Redirect( BASE. 'database/'. $_GET['database'] );
	}
	$dbname = $_GET['database'];
	$table = isset($_POST['tbname']) ? $_POST['tbname'] : $_GET['table'];
	if ( isset($_GET[ADD_ROW]) ) {
		Redirect( BASE. 'database/'. $dbname. '/table/'. $table. '/rows/'. ADD_ROW );
	}
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
	<!-- begin page contents -->
	<div class="container">
		<div class="panel">
<?php
	
/*****************************************************************************
 * logic
 */
	//if ($_GET['table'] === $GLOBALS['data']['ADD_TBL'] || $_GET['table'] === $GLOBALS['data']['ADD_FRM'])	// selected to add either a table or a form
	if ($_GET['table'] === ADD_TBL || $_GET['table'] === ADD_FRM)	// selected to add either a table or a form
	{
		Redirect( BASE. 'databases' );
	} else {
		$pos = strpos( $_GET['table'], 'remove' );
		if ($pos !== false || isset($_GET['remove']))			// db is to be removed
		{
			$table = ($pos !== false) ? do_mbstr('substr', $_GET['table'], 0, $pos-1) : $_GET['table'];
			$qry = 'SELECT * FROM '. $_GET['database']. '.'. $table;
			try {
				if (!isset($_GET['bypass_table_has_rows_check']) && PREVENT_DELETE_TABLE_IF_HAS_ROWS && !empty( $db->run($qry)->fetchAll() ))	// check if db has existing tables
				//if (!isset($_GET['bypass_table_has_rows_check']) && $GLOBALS['data']['PREVENT_DELETE_TABLE_IF_HAS_ROWS'] && !empty( $db->run($qry)->fetchAll() ))	// check if db has existing tables
				{
					$note = new Note(Array(
						'type' => 'error',
						'message' => sprintf( t("_Cannot remove non-empty table %s"), $table ),
						'canIgnore' => true,
						'ignore' => t('Bypass check'),
						'ignoreSlug' => 'bypass_table_has_rows_check',//&bypass_form_has_columns_check',
						'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				} else {									// actually drop database, if no inners
//					$qry = 'DESCRIBE '. $_GET['database']. '.'. $table;
					//$qry = 'SELECT * FROM '. $_GET['database']. '.'. $table;
					try {
						//if (!isset($_GET['bypass_table_has_columns_check']) && $GLOBALS['data']['PREVENT_DELETE_TABLE_IF_HAS_COLUMNS'] && !empty( $db->run($qry)->fetchAll() ))	// check if db has existing tables
						if (!isset($_GET['bypass_table_has_columns_check']) && PREVENT_DELETE_TABLE_IF_HAS_COLUMNS && !empty( $db->run($qry)->fetchAll() ))	// check if db has existing tables
						{
							$note = new Note(Array(
								'type' => 'error',
								'message' => sprintf( t("_Cannot remove non-empty table %s"), $table ),
								'canIgnore' => true,
								'ignore' => t('Bypass check'),
								'ignoreSlug' => 'bypass_table_has_columns_check',
//								'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
							));
							echo $note->display();
						} elseif (/*strpos( $_GET['table'], 'add' ) !== false ||*/ isset($_GET['add'])) {		// a record is to be added
							//******
							// TODO
							//******
						} else {
							//$qry = 'DROP DATABASE '. addPrefix($dbname);
							$qry = 'DROP TABLE '. $_GET['database']. '.'. $table;
							try {
								$db->run($qry);
								unset($_GET['table']);// = '';
								unset($_GET['remove']);// = '';
								if (defined('NOTIFY_CREATE_TABLE') && NOTIFY_CREATE_TABLE) {
									$note = new Note(Array(		// draw notice after drop
										'type' => Note::notice,
										'message' => sprintf( t("_Deleted %s"), $table ),
										'ok' => t('_List all'),
										/*POST:
										GET: Array ( [page] => table [database] => bf_aaaa [table] => tttt [remove] => )
										==>
										POST: Array ( [pressed] => ok )
										GET: Array ( [page] => table [database] => bf_aaaa [table] => tttt [remove] => )*/
	/*									'post' => array(
											'table' => false,
											'remove' => false,
										),*/
										//'ignore' => sprintf( t('Goto %s'), $table ),
										//'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
									));
									echo $note->display();
								} else Redirect( BASE. 'database/'. $_GET['database'] );
							} catch (PDOException $e) {	// cannot drop db
								$note = new Note(Array(
									'type' => 'error',
									'message' => sprintf( t("_Failed to drop table %s"), $table ),
									'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
								));
								echo $note->display();
							}
						}
					} catch (PDOException $e) {	// cannot show tables
						$note = new Note(Array(
							'type' => 'error',
							'message' => sprintf( t("_Cannot get information for table %s"), $table ),
							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					}
				}
			} catch (PDOException $e) {	// cannot show tables
				$note = new Note(Array(
					'type' => 'error',
					'message' => sprintf( t("_Cannot get information for table %s"), $table ),
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
				));
				echo $note->display();
			}
		} else {
			if (!hasPrefix($_GET['database']) && !isset($_POST['pressed'])) {
				$note = new Note(Array(
					'type' => Note::error,
					'ok' => 'return',
					'canIgnore' => true,
					'message' => sprintf( t("_Not a forms database %s"), $_GET['database'] ),
				));
				echo $note->display();
			} else {
				$dbname = delPrefix($_GET['database']);
				$qry = 'SELECT * FROM '. addPrefix($dbname). '.'. $_GET['table'];	// get records from table/form
				try {
					display_rows( $db->run($qry)->fetchAll(PDO::FETCH_ASSOC) );
	/*				$qry = 'INSERT INTO buildForms.recent (name) VALUES ('. squote($dbname). ')';
					try {
						$db->run($qry);
					} catch (PDOException $e) {
					}*/
	/*				$qry = 'SHOW TABLES';
					try {
						display_rows( $db->run($qry)->fetchAll(PDO::FETCH_COLUMN) );
					} catch (PDOException $e) {
					//if (isset($results->errorInfo)) {
						$note = new Note(Array(
							'type' => 'error',
							'message' => sprintf( t("_Cannot list tables in database %s"), PREFIX. $dbname ),//$_GET['databases'])
							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					}*/
				} catch (PDOException $e) {
					$note = new Note(Array(
						'type' => 'error',
						'message' => sprintf( t("_Cannot get information for table %s"), $_GET['table'] ),
						'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				}
			}
	/*
?>
);</script>
	<form>
		<div class="input_field">
			<div class="label">
				<label for="dbname"><?php l('_Database name'); ?>:</label>
			</div>
			<div class="input">
				<input type="text" id="dbname" name="dbname">
			</div>
		</div>
	</form>
<?php
	/ *
	$menu = new ButtonMenu([
		new Button('first', 'slug1'),
		new Button('second', 'slug2'),
		new Button('thrid', 'slug3')]);
	echo $menu->HTML();
	 */
		}
	}
?>
	<!-- ending page contents -->
		</div>
	</div>
	<!-- end page contents -->
	<!--<script src="js/rusure.js<?php echo '?'. fileatime('js/rusure.js'); ?>"></script>-->

<?php
		
/*****************************************************************************
 * functions
 */
	/**
	 * Display form to gether name of new table to create
	 */
	function display_form()
	{
?>
			<script>const takenDBNames = [
<?php
		if (is_array($results)) {
			foreach( $results as $row ) {
				if (!in_array( $row[0], RESERVED )) {
					echo "\t'{$row[0]}',\n";
				}
			}
		}
?>
];</script>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<h1>
					<?php //echo sprintf( t('_Database %s'), delPrefix($dbname)); ?>
<!--					<span class="xthis-page"><?php ($_GET['table'] === $GLOBALS['data']['ADD_FRM']) ? l('_Add a new form') : l('_Add a new table'); ?></span>-->
					<span class="xthis-page"><?=t('_Add a new table')?></span>
<!--					<span class="xthis-page"><?php printf( t('_Add table to database %s'), delPrefix($_GET['database']) ); ?></span>-->
				</h1>
				<form method="post" id="new_table" name="new_table">
					<input type="hidden" name="dbname" value="<?=$_GET['database']?>">
					<input type="hidden" name="action" value="<?=$_GET['table']?>">
						<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
					<div class="row">
						<div class="input_field">
							<div class="label col-25">
<!--								<label for="tbname"><?php ($_GET['table'] === $GLOBALS['data']['ADD_FRM']) ? l('_Form name') : l('_Table name'); ?>:</label>-->
								<label for="tbname"><?=t('_Table name')?>:</label>
							</div>
							<div class="input col-75">
								<input type="text" id="tbname" name="tbname" autofocus>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="input_field">
							<div class="label col-25">
			<!--					<label for="submit"><?php l('_Submit'); ?>:</label>-->
		&nbsp;
							</div>
							<div class="submit">
<!--								<input type="submit" id="submit" name="submit" value="<?php ($_GET['table'] === $GLOBALS['data']['ADD_FRM']) ? l('_Add form') : l('_Add table'); ?>">-->
								<input type="submit" id="submit" name="submit" value="<?=t('_Add table')?>">
							</div>
						</div>
					</div>
				</form>
			</div>
<!--			<script src="js/addClass.js<?php echo '?'. fileatime('js/addClass.js'); ?>"></script>
			<script src="js/begin.js<?php echo '?'. fileatime('js/begin.js'); ?>"></script>-->
<?php
//		Link::style( $name, BASE. $file, filemtime($filename) );
//function enqueueScript( string $name, string $filename, string $version='', bool $put_in_header=false, bool $is_async=false, string                $cross_domain='', bool $showComment=false ) {
		//enqueueScript( 'addEvent', BASE. 'js/addEvent.js', fileatime('js/addEvent.js'), false, false, '', true );
		enScript( Array(
			'name' => 'addEvent',
			'filename' => BASE. 'js/addEvent.js',
			'version' => fileatime('js/addEvent.js'),
			'putInHeader' => false,
			'isAsync' => false,
			'crossDomain' => '',
			'showComment' => true
		));
	}

	/**
	 * **********************************
	 * List rows in the given table
	 */
	//function display_rows($results) {
	function display_rows($tables) {
		$dbname = $_GET['database'];
		//$table = isset($_GET['table']) ? $_GET['table'] : $_GET['form'];
		$table = $_GET['table'];
?>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<h1>
					<form>
						<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
						<span class="xthis-page"><?=sprintf( t('_Records in table %s'),
'<input class="xhide highlight" type="text" name="rename" size="'. strlen($table). '" value="'. $table. '" />')?></span>
					</form>
					<!--<span class="xthis-page"><?php printf( t('_Records in table %s'), $table); ?></span>-->
				</h1>
				<table id="<?php echo $table; ?>_rows" class="tables zebra">
<?php
/*		$tables = array();
		$forms = array();
		if (!empty($results)) {
			foreach($results as $k => $v) {
				if ((is_callable(array('Form', 'hasPrefix')) && Form::hasPrefix($v)) || strpos($v, 'form_') === 0) {
					$forms[$k] = $v;
//				} elseif (strpos($v, 'form_') === 0) {
//					$forms[$k] = $v;
				} else {
					$tables[$k] = $v;
				}
			}
		}*/
		if (empty($tables)) {	// no tables
?>
					<tbody>
						<!--<tr><td><span class="empty"><?php echo sprintf( t("_No tables in %s"), $dbname ); ?></span></td></tr>-->
						<!--<tr><td><span class="empty"><?php l("_No rows"); ?></span></td></tr>-->
						<tr id="add-record-last" data-token="<?=$_SESSION['requestToken']?>" data-placeholder="<?=t('_alpha-numeric characters')?>"><td><span class="empty"><?php l("_empty"); ?></span></td></tr>
					</tbody>
<?php
		} else {
			enScript(Array( 'name' => "addEvent", 'src' => BASE. "js/addEvent.js", 'put_in_header' => false, 'version' => fileatime('js/addEvent.js') ));
			enScript(Array( 'name' => "addClass", 'src' => BASE. "js/addClass.js", 'put_in_header' => false, 'version' => fileatime('js/addClass.js') ));
			enScript(Array( 'name' => "trigmodal", 'src' => BASE. "js/trigmodal.js", 'put_in_header' => false, 'version' => fileatime('js/trigmodal.js'), 'requires' => Array('bootstrap@5.0.1') ));
?>
					<thead>
						<tr>
							<th class="rownum"><!--<a href="<?=BASE. 'database/'. $_GET['database']. '/table/'. $_GET['table']. '/columns'?>">--><span class="symbol cols">#</span><!--</a>--></th>
<?php
			//$href = "note?redirect=remove&amp;dbname={addPrefix($dbname)}&amp;table={$table}&amp;column={$fk}&amp;type={Note::warning}&amp;ok=Continue&amp;canIgnore=true&amp;ignore=Cancel";
			$h=0;
			foreach( $tables[0] as $fk => $fv ) {		// column names - record headings
				$href = BASE. 'database/'. addPrefix($dbname). '/table/'. $table. '/column/'. $fk. '?remove';
?>
							<th id="head<?php
						echo $h++; ?>" class="head"><span><?php
						echo $fk; ?></span><a data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?=htmlspecialchars($fk)?>" class="symbol modal-trigger head" href="<?=$href?>" title="<?php
						echo sprintf( t('_Delete column %s'), $fk); ?>"><span class="symbol delete"></span></a></th>
<?php
/*						echo $fk; ?></span><a data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?=htmlspecialchars($row)?>" class="symbol modal-trigger" onclick="return confirm('<?php
						l('Do you really want to do this?'); ?>;" class="symbol head" href="<?=$href?>" title="<?php*/
			}
?>
						</tr>
					</thead>
					<tbody>
<?php
			$last = count($tables)-1;
			$remove_base = BASE. 'database/'. $dbname. '/table/'. $table. '/row/';
//			print '<h5>last:'. $last. '</h5>';
//			$k = 0;
			foreach( $tables as $a => $row ) {
				$id = htmlspecialchars($row['id']);
				//$record = htmlspecialchars($row['Field']);
//							<!--<td><a class="symbol" href="remove?table=< ?php echo $row[0]; ? >" title="< ?php echo sprintf(t('_Delete table %s'), $row[0]); ? >"><span class="symbol delete">&#x2718;</span></a></td>-->
//				$remove = $remove_base. $id. '?remove';
//href="note?redirect=remove&table=< ?php echo htmlspecialchars($row); ? >&type=< ?php echo Note::warning; ? >&ok=Continue&canIgnore=true&ignore=Cancel"
?>
						<tr id="record_<?=$id?>" data-row="<?=$a?>" class="datarow<?php if ($a == $last) { print ' last-row" data-token="'. $_SESSION['requestToken']. '" data-placeholder="'. t('_alpha-numeric characters');} ?>" draggable="true">
							<td class="rownum"><span class="symbol dbcol row<?=$a?>"></span></td>
<?php
				foreach($row as $col => $val) {
?>
							<td class="colvalue" data-col="<?php echo $col; ?>"><?php echo htmlspecialchars($val);?></td>
<?php
				}
?>
							<td class="symbol"><a data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?=$id?>" class="symbol modal-trigger"
href="<?=$remove_base. $id. '?remove'?>"
title="<?=sprintf( t('_Delete record %s'), $id)?>"><span class="symbol delete"></span></a></td>
						</tr>
<?php
			}
?>
					</tbody>
<?php
		}
/*		$results = $db->run('SHOW DATABASES')->fetchAll();
		//$menu = new ButtonMenu([]);
		foreach( $results as $row ) {
			//echo print_r( $row, true ). "\n";
		//	echo $row[0]. "\n";
			//$menu->add( new Button($row[0], "") );
			if ($row == $posted['databases']) {
				echo 'already exitis';
				exit();
			}
		}
		//echo $menu->HTML();

 */
/*		if ($db->run('CREATE DATABASE '. $_GET['databases'])) {
			$note = Notice(Array(
				'message' => t('_Cannot create database.')
			));
			echo $note->display();
		} else {
			Redirect('_use/'. $_GET['databases']);
		}
	}*/
		//$dbn = addPrefix($dbname);
		//$href = BASE. 'databases/'. $dbn. '/tables/add';
		$href = BASE. 'database/'. $dbname. '/table/'. $table. '?'. ADD_ROW;
?>
					<tfoot>
						<tr>
							<td class="symbol"><a data-toggle="add-table-prompt" data-target="#add-table-last" href="<?=$href
?>" title="<?=l('_Add a new record'); ?>" class="dbadd add-row symbol"><span class="symbol add"></span></a></td>
					</tfoot>
				</table>
<!--				<h2 class="xrownum"><a href="<?=BASE. 'database/'. $_GET['database']. '/table/'. $_GET['table']. '/columns'?>" title="<?=t('_Columns')?>"><span class="symbol ret"><?=printf( t('_Goto %s'), t('_Columns'))?></span></a></h2>-->
				<h2 class="xrownum"><a href="<?=BASE. 'database/'. $_GET['database']. '/table/'. $_GET['table']. '/columns'?>" title="<?=t('_Columns')?>"><span class="symbol ret"><?=t('_Columns')?></span></a></h2>
			</div>
<?php
	}
