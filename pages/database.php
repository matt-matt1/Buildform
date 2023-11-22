<?php
namespace Yuma;
//use Yuma\HTML\Script;
use function Yuma\HTML\enScript;
use \PDO;
use Yuma\HTML\Field;
use Yuma\HTML\Breadcrumbs;

	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	$pre = 'pages/database: ';
	if (!isset($_GET['database']) || empty($_GET['database'])) {
		Redirect( BASE. 'databases' );
	}
	// *****
	// User pressed OK - select a new database
	// *****
	if (isset($_POST['pressed']) && $_POST['pressed'] === 'ok') {
		Redirect(BASE. 'databases');	// show all databases, if user clicked ok - to select another
	}
	if (isset($_POST['pressed']) && $_POST['pressed'] === 'ignore' && isset($_POST['dbname'])) {
		Redirect(BASE. 'database/'. addPrefix($_POST['dbname']));	// show database, if user clicked ignore - to select another
	}
	//Head::load();
//	Article::getInstance()->sddArticleTag( 'james' );
//	Acticle::getInstance()->sddArticleTag( 'mary' );
	echo makeHead(array( 'page_title' => t('_db_desc'), 'body_id' => 'database', 'body_class' => "", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | ", '_article_tag' => array( 'john', 'harry', 'dick' )));
?>
	<!-- begin page contents -->
	<div class="container">
		<div class="panel">
<?php
	
/*****************************************************************************
 * logic
 */
	// *****
	// Has a parameter - extract database name and parameter
	// *****
		if (isset($_GET['remove'])) {
				$dbname = addPrefix($_GET['database']);
			$log = 'removing database: '. $dbname;
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $pre. $log ) ||
				error_log( $pre. $log );
	// *****
				$qry = 'USE '. $dbname;
				//doDbQuery(object$dbh, string$query, $success, $failure)
/*				try {
					$db->run($qry);*/
				$success = function($db) {
/*			$log = 'removing database: success';
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $log ) ||
				error_log( $log );*/
					$qry1 = 'SHOW TABLES';
//					try {
					$success1 = function($db, $result) {
/*			$log = 'removing database: success1';
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $log. (string)$result ) ||
				error_log( $log. (string)$result );*/
						if (!isset($_GET['bypass_db_has_tables_check']) && PREVENT_DELETE_DB_IF_HAS_TABLES && !empty( $result/*$db->run('SHOW TABLES')->fetchAll()*/ ))	// check if db has existing tables
						//if (!isset($_GET['bypass_db_has_tables_check']) && $GLOBALS['data']['PREVENT_DELETE_DB_IF_HAS_TABLES'] && !empty( $db->run($qry)->fetchAll() ))	// check if db has existing tables
						{
							$note = new Note(Array(
								'type' => Note::error,//'error',
								'message' => sprintf( t("_Cannot remove non-empty database %s"), /*delPrefix($dbname)*/$_GET['database'] ),
								'canIgnore' => true,
								'ignore' => t('Bypass check'),
								'ignoreSlug' => 'bypass_db_has_tables_check',
//								'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
							));
							echo $note->display();
						} else {									// actually drop database, if no inners
							//$qry = 'DROP DATABASE '. addPrefix($dbname);
							$qry2 = 'DROP DATABASE '. /*$dbname*/addPrefix($_GET['database']);
							$success2 = function($db) {
/*			$log = 'removing database: success2';
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $log ) ||
				error_log( $log );*/
								unset($_GET['remove']);
								if (defined('NOTIFY_DELETE_DB') && NOTIFY_DELETE_DB) {
									$note = new Note(Array(		// draw notice after drop
										'type' => Note::notice,
										'message' => sprintf( t("_Deleted %s"), /*delPrefix($dbname)*/$_GET['database'] ),
										'ok' => t('_List databases'),
			//							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
									));
									echo $note->display();
								} else Redirect( BASE. 'databases' );
							};//success2
//							try {
//								$db->run($qry);
//							} catch (PDOException $e) {	// cannot drop db
							$failure2 = function($e, $from) {
								$note = new Note(Array(
									'type' => Note::error,//'error',
									'message' => sprintf( t("_Failed to drop database %s"), delPrefix($dbname) ),
									'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}<br>\nFrom: {$from}",
								));
								echo $note->display();
							};//failure2
							doDbQuery($db, $qry2, $success2, $failure2);
						}
					};//success1
//					} catch (PDOException $e) {	// cannot show tables
					$failure1 = function($e, $from) {
						$note = new Note(Array(
							'type' => Note::error,//'error',
							'message' => sprintf( t("_Cannot get information for %s"), delPrefix($dbname) ),
							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}<br>\nFrom: {$from}",
						));
						echo $note->display();
					};
					doDbQuery($db, $qry1, $success1, $failure1);
				};//success
				$failure = function($e, $from, $qry) {
//				} catch (PDOException $e) {	// cannot use db
					$notes = Array(
						'type' => Note::warning,
						'message' => sprintf( t("_Cannot get information for %s"), /*delPrefix(*/$_GET['database']/*)*/ ),
						'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}". "<br>\nFrom: {$from}",
						//42000 - 1049 - Unknown database 'bf_bgbhbn'
						//'details' => print_r($e, true). "<br>\nQuery: {$qry}". "<br>\nFrom: {$from}",
					);
					if ($e->errorInfo[0] == 42000 && $e->errorInfo[1] == 1049) {
						//$note->message = $e->errorInfo[2];
//						$notes['message'] = sprintf( t('_Deleted %s'), $e->errorInfo[2] );
						$notes['message'] = sprintf( t('_Deleted %s'), '' );
						$notes['type'] = Note::notice;
						//$note->details = false;
						unset($notes['details']);
					}
					$note = new Note($notes);
					echo $note->display();
				};
				doDbQuery($db, $qry, $success, $failure);
/*				break;
	// *****
			case 'rename':*/
		} elseif (isset($_GET['rename']) || isset($_POST['rename'])) {
				$dbname = $_GET['database'];
			$log = 'renaming database '. $dbname;
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $pre. $log ) ||
				error_log( $pre. $log );
	// *****
/* ?>
<div class="spinner-border" role="status"></div>
<?php */
			//$dbname = substr($_GET['database'], 0, -6);
/*				if (!isset($_POST['requestToken']) || $_POST['requestToken'] != $_SESSION['requestToken'])
				{
					$note = new Note(Array(
						'type' => Note::error,//'error',
						'message' => t("_Invalid form request token"),
					));
					if (isset($_POST['requestToken'])) {
						$note['details'] = "<br>\nGot: {$_POST['requestToken']} expecting {$_SESSION['requestToken']}";
					}
					echo $note->display();
					exit();
				}*/
				//$name = (isset($_POST['rename']) ? $_POST['rename'] : isset($_GET['rename'])) ? $_GET['rename'] : '';
				$name = isset($_POST['rename']) ? $_POST['rename'] : $_GET['rename'];
				$qry = 'CREATE DATABASE '. addPrefix($name);				// step 1 - create new (renamed) db
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $pre. $qry ) ||
				error_log( $pre. $qry );
				try {
					$db->run($qry);
					$qry = 'USE '. $_GET['database'];						// step 2.a - fetch from original db
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $pre. $qry ) ||
				error_log( $pre. $qry );
					try {
						$db->run($qry);
						$qry = 'SHOW TABLES';								// step 2.b - get all inner tables
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $pre. $qry ) ||
				error_log( $pre. $qry );
						try {
							$tables = $db->run($qry)->fetchAll();
							if (!empty($tables))
							{
								$qry = 'RENAME TABLE ';						// step 3.a - begin to rename tables
								foreach ($tables as $t)						// step 3.b - set new db for each table
								{
									$qry .= $dbname. '.'. $t[0]. ' TO '. addPrefix($name). '.'. $t[0]. ',';
//									echo 'db='. print_r($dbname, true). ";<br/>\n";
//									echo 't='. print_r($t, true). ";<br/>\n";
//									echo 'name='. addPrefix($name). ";<br/>\n";
								}
//								exit();
								$qry = do_mbstr('substr', $qry, 0, -1);
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $pre. $qry ) ||
				error_log( $pre. $qry );
							}
							try {
								$db->run($qry);
								$qry = 'DROP DATABASE '. $dbname;		// step 4 (final) - remove original db
			(is_callable(array('Logger', 'log'))) &&
				Logger::getInstance()->log( $pre. $qry ) ||
				error_log( $pre. $qry );
								try {
									$db->run($qry);
									unset($_GET['remame']);
							$note = new Note(Array(		// draw notice after drop
								'type' => Note::notice,
								'message' => sprintf( t("_Renamed %s"), /*delPrefix($dbname)*/$_GET['database'] ),
								'ok' => t('_List databases'),
								'canIgnore' => true,
								'post' => Array(
									//'dbname' => delPrefix($_POST['database']),
									'dbname' => $_POST['rename'],
								),
								'ignore' => sprintf( t('_Goto %s'), $_POST['rename']),
	//							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
							));
							echo $note->display();
//									Redirect( BASE. 'database/'. addPrefix($name) );
//		Redirect( BASE. 'databases' );
								} catch (PDOException $e) {
									$note = new Note(Array(
										'type' => Note::error,//'error',
										'message' => sprintf( t("_Cannot remove database %s"), $dbname ),
										'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
									));
									echo $note->display();
								}
							} catch (PDOException $e) {
								$note = new Note(Array(
									'type' => Note::error,//'error',
									'message' => sprintf( t("_Cannot rename tables %s"), $dbname ),
									'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}<br>\nTables: ". print_r($tables),
								));
								echo $note->display();
							}
						} catch (PDOException $e) {	// cannot show tables
							$note = new Note(Array(
								'type' => Note::error,//'error',
								'message' => sprintf( t("_Cannot get information for %s"), $dbname ),
								'details' => t('_Ensure the name is unique and not a reserved word'). "<br>\n". implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
							));
							echo $note->display();
						}
					} catch (PDOException $e) {	// cannot use db
						$note = new Note(Array(
							'type' => Note::error,//'error',
							'message' => sprintf( t("_Cannot get information for %s"), $dbname ),
							'details' => t('_Ensure the name is unique and not a reserved word'). "<br>\n". implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					}
				} catch (PDOException $e) {	// cannot create db
					$note = new Note(Array(
						'type' => Note::error,//'error',
						'message' => sprintf( t("_Cannot create database %s"), $name ),
						'details' => t('_Ensure the name is unique and not a reserved word'). "<br>\n". implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				}
/*				break;
	// *****
			case 'add':*/
		//} elseif (isset($_GET['add'])) {
	// *****
				//add_db();
/*				break;
			default:
		} else {
				echo 'unknown command: '. $action;
			}*/
	// *****
	// * Move table to another database *
	// *****
		} elseif ((isset($_GET['move']) || isset($_POST['move'])) && (isset($_GET['to']) || isset($_POST['to']))) {
			$move = isset($_GET['move']) ? $_GET['move'] : $_POST['move'];
			$to = isset($_GET['to']) ? $_GET['to'] : $_POST['to'];
			$database = $_GET['database'];
//			if (isset($_POST['dbname']) && !empty($_POST['dbname'])) {	// add prefix if dbname was posted
//				$database = addPrefix($_POST['dbname']);
//			}
			if (!hasPrefix($database) && !isset($_POST['pressed'])) {
				$note = new Note(Array(
					'type' => Note::error,
					'canIgnore' => true,
					'message' => sprintf( t("_Not a forms database %s"), $database ),
				));
				echo $note->display();
			} else {
				//$dbname = delPrefix($_GET['database']);
//				$dbname = delPrefix($database);
				$top = addPrefix($to);
				$qry = "RENAME TABLE {$database}.{$move} TO {$top}.{$move}";
				try {
					$db->run($qry);		// attempts to insert record as recent db
					$note = new Note(Array(
						'type' => Note::notice,
						'message' => sprintf( t("_Moved table %s from database %s to %s"), $move, delPrefix($database), $to ),
//						'details' => "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				} catch (PDOException $e) {	// cannot use db
					$note = new Note(Array(
						'type' => Note::error,//'error',
						'message' => sprintf( t("_Cannot move table %s from database %s to %s"), $move, delPrefix($database), $to ),
						'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				}
			}
		} else {
	// *****
	// No parameter - just database name
	// *****
//			$dbname = $_GET['database'];
//			enScript(Array( 'name' => "adds", 'src' => BASE. "js/adds.js", 'version' => filemtime('js/adds.js'), 'requires' => Array('bootstrap@5.0.1') ));
			//enScript(Array( 'name' => "dels", 'src' => BASE. "js/dels.js", 'version' => filemtime('js/dels.js'), 'requires' => Array('bootstrap@5.0.1') ));
//			enScript(Array( 'name' => "MyAjax", 'src' => BASE. "js/MyAjax.js", 'version' => filemtime('js/MyAjax.js') ));
			enScript(Array( 'name' => "CustomSelect", 'src' => BASE. "js/CustomSelect.js", 'version' => filemtime('js/CustomSelect.js') ));
			enScript(Array( 'name' => "database", 'src' => BASE. "js/database.js", 'version' => filemtime('js/database.js') ));
/*			$raw = array();
			$dbs = array();
			$onRaw = function($databases) {	/ *echo '<pre>raw:'. print_r($databases, true). '</pre>';* /$raw = $databases;/ *print '<script>const reserved = ["'. implode('","', $raw). '"];</script>';* /	};
			$onSuccess = function($databases) {	/ *echo '<pre>dbs:'. print_r($databases, true). '</pre>';* /$dbs = $databases;	};
			$onFail = null;
			showDbs($db, $onRaw, $onSuccess, $onFail);
			echo '<pre>raw:'. print_r($raw, true). '</pre>';*/
			$raw = getAllDbs($db);
			$dbs = getDbs($db);
			print '<script>const reserved = ["'. implode('","', $raw). '"];</script>';
			$database = $_GET['database'];
			if (isset($_POST['dbname']) && !empty($_POST['dbname'])) {	// add prefix if dbname was posted
				$database = addPrefix($_POST['dbname']);
			}
			if (!hasPrefix($database) && !isset($_POST['pressed'])) {
				$note = new Note(Array(
					'type' => Note::error,
			//		'canIgnore' => true,
					'message' => sprintf( t("_Not a forms database %s"), $database ),
				));
				echo $note->display();
			} else {
				$dbname = delPrefix($_GET['database']);
				$qry = 'USE '. addPrefix($dbname);
				try {
					$db->run($qry);		// attempts to insert record as recent db
/*					$qry = 'INSERT INTO buildForms.recent (name) VALUES ('. squote($dbname). ')';
					try {
						$db->run($qry);
					} catch (PDOException $e) {
					}*/
					$qry = 'SHOW TABLES';
					try {
						display_tables( $db->run($qry)->fetchAll(PDO::FETCH_COLUMN), $dbs );
					} catch (PDOException $e) {	// cannot show tables
						$note = new Note(Array(
							'type' => Note::error,//'error',
							//'message' => sprintf( t("_Cannot list tables in database %s"), $GLOBALS['data']['PREFIX']. $dbname ),//$_GET['databases'])
							'message' => sprintf( t("_Cannot list tables in database %s"), addPrefix($dbname)/*$_GET['database']*/ ),
							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					}
				} catch (PDOException $e) {	// cannot use db
					$note = new Note(Array(
						'type' => Note::error,//'error',
						'message' => sprintf( t("_Cannot get information for non-Forms database %s"), /*$dbname*/$_GET['database'] ),
						'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				}
			}
		}
/*	}*/

/*****************************************************************************
 * functions that display HTML, if needed
 */
	/**
	 * List the tables in the given database
	 */
	function display_tables( $results, $dbs ) {
		$dbname = $_GET['database'];
		//enScript(Array( 'name' => "addEvent", 'src' => BASE. "js/addEvent.js", 'version' => filemtime('js/addEvent.js') ));
		//enScript(Array( 'name' => "addClass", 'src' => BASE. "js/addClass.js", 'version' => filemtime('js/addClass.js') ));
?>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<h1>
					<!--<span class="xthis-page"><?=sprintf( t('_Tables in database %s'), delPrefix($dbname))?>-->
					<form method="post">
						<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
		<!--				<input type="hidden" name="database" value="<?=delPrefix($dbname)?>">-->
						<span><?=sprintf( t('_Database %s'), '<input class="highlight" type="text" name="rename" size="'. strlen(delPrefix($dbname)). '" value="'. delPrefix($dbname). '" />')?></span>
<!--							<div class="clearfix">
							<div class="spinner-border right hide xfloat-right" role="status">
								<span class="sr-only">Loading...</span>
							</div>
							</div>-->
					</form>
				</h1>
				<h2><?=l('_Tables')?></h2>
				<table id="<?php echo $dbname; ?>_tables" class="tables zebra">
<?php
//			echo '<pre>raw:'. print_r($raw, true). '</pre>';
			//echo '<pre>dbs:'. print_r($dbs, true). '</pre>';
		$tables = array();
		$forms = array();
		if (!empty($results)) {
		//	echo '<pre>'. print_r($results, true). '</pre>';
			//echo '<pre>'. print_r($forms, true). '</pre>';
			foreach($results as $k => $v) {
				if (is_callable(array('Form', 'hasPrefix')) && Form::hasPrefix($v)) {
//					echo 'formjjjj';
					$forms[$k] = Form::delPrefix($v);
				} elseif (strpos($v, 'form_') === 0) {
					$forms[$k] = do_mbstr('substr', $v, 5);
				} else {
					$tables[$k] = $v;
				}
			}
		}
?>
					<tbody>
<?php
		$title = sprintf( t('_Remove table in %s'), delPrefix($dbname));
		if (empty($tables)) {	// no tables
?>
						<!--<tr><td><span class="empty"><?php echo sprintf( t("_No tables in %s"), $dbname ); ?></span></td></tr>-->
						<!--<tr><td><span class="empty"><?php l("_No tables"); ?></span></td></tr>-->
						<tr id="add-table-last" data-token="<?=$_SESSION['requestToken']?>" data-placeholder="<?=t('_alpha-numeric characters')?>"><td><span class="empty"><?php l("_empty"); ?></span></td></tr>
						<!--<tr><td><span class="empty"><?php l("_empty"); ?></span></td></tr>-->
<?php
		} else {
			enScript(Array( 'name' => "trigmodal", 'src' => BASE. "js/trigmodal.js", 'version' => filemtime('js/trigmodal.js'), 'requires' => Array('bootstrap@5.0.1') ));
			//echo '<pre><!-- '. print_r($results, true). ' --></pre>';
			$last = count($tables)-1;
			$k = 0;
			foreach( $tables as $a => $row ) {
				$table = htmlspecialchars($row);
				$href = BASE. 'database/'. $dbname. '/table/'. $table;
				$remove = BASE. 'database/'. $dbname. '/table/'. $table. '?remove';
				$body = sprintf( t('_Really remove table %s'), $table);
?>
						<tr<?php if ($k == $last)
				{
					echo ' id="add-table-last" data-token="'. $_SESSION['requestToken']. '" data-placeholder="'. t('_alpha-numeric characters'). '"';
				}?>>
							<td class="symbol"><span class="symbol dbtab"></span></td>
							<td><a class="symbol" href="<?=$href
?>" title="<?=sprintf( t('_Table %s'), $table). ' ('. sprintf( t('_Database %s'), delPrefix($dbname)). ')'
?>"><?=$table?></a></td>
<?php
				$move = BASE. 'database/'. $dbname. '?move='. $table. '&to=';
				$opts = '';//'<option selected disabled>'. t('_Select'). '<option>';
				foreach ($dbs as $base) {
					$opts .= '<option ';
					if ($dbname == $base)
						$opts .= 'disabled ';
					$opts .= 'value="'. delPrefix($base). '">'. delPrefix($base). '</option>';
				}
				$dblist = array(
					'type' => 'select',
					'id' => "move". $a,
					'name' => 'move'. $a,
					'noLabel' => true,
					'placeholder' => t('_move to database'),
					'title' => t('_move this table in another database'),
//					'label' => SQL::COLEXTRAS[2]['text'],//t('_Collation'),
//					'label_title' => SQL::COLEXTRAS[2]['title'],
//					'value' => $collation,
//					'input_title' => !empty($collation) ? sprintf( t('_was %s'), $collation ) : '',
//					'row_class' => 'row g-2 mb-4',
//					'label_class' => 'list_dbs',
					'input_class' => 'list_dbs',
//					'input_wrap_class' => 'input col-md-3',
					'extra' => 'style="width: 12em;" data-table="'. $table. '" data-disable="'. delPrefix($dbname). '" data-href="'. $move. '"',
//					'extra' => 'style="width: 12em;"><a class="symbol"><span class="symbol"><i class="fa-solid fa-right-long-to-line"></i></span></a',
//					'type' => 'select',
//					'roptions' => Select::drawOptions(SQL::COLLATION, '', $collation),
					'optional' => true,
					'show_optional' => false,
/*					'input2' => array(
//						'type' => 'submit',
//						'input_wrap_class' => 'xsymbol',
//						'input_class' => 'no-button xsymbol',
//						'value' => t('_go'),
						'extra' => '><a class="symbol"><span class="symbol"><i class="fa-solid fa-right-long-to-line"></i></span></a'
					),
//					'roptions' => '<option selected disabled>'. t('_move to'). '</option>',
 */
					'roptions' => $opts
 				);
?>
							<!--<td><span>< ?=Field::out($dblist)? >&nbsp;<i class="fa-solid fa-right-long-to-line"></i></span></td>-->
							<!--<td class="symbol">< ?=Field::out($dblist)? ></td>-->
							<td><?=Field::out($dblist)?></td>
							<!--<td class="symbol">< ?=Field::out($dbs)? >&nbsp;<a class="symbol"><span class="symbol"><i class="fa-solid fa-right-long-to-line"></i></span></a></td>-->
							<td class="symbol js-hide"><a class="symbol"><span class="symbol"><i class="xfa-sharp fa-solid xfa-regular xfa-duotone xfa-thin xfa-light fa-right-long"></i></span></a></td>
							<!--<td class="symbol"><a onclick="return confirm('<?php printf( t('_Really remove table %s'), $table); ?>');"-->
							<td class="symbol"><a data-title="<?=$title
?>" data-body="<?=$body
?>" data-modal-button-del="value:proceed" data-modal-button-add="node:button,type:submit,name:modal-button,value:deletet,class:btn btn-danger no-button,aria-label:<?=t('Delete')?>,data-url:<?=$remove?>,innerText:<?=t('Delete')
?>" data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?=$table
?>" class="symbol modal-trigger" href="<?=$remove
?>" title="<?=sprintf( t('_Delete table %s'), $table)
?>"><span class="symbol delete"></span></a></td>
						</tr>
<?php
						++$k;
			}
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
		//$href = BASE. 'database/'. $dbname. '/table/'. ADD_TBL;
		$href = BASE. 'database/'. $dbname. '/tables/'. ADD_TBL;
		//$href = BASE. 'database/'. $dbname. '/table/'. $GLOBALS['data']['ADD_TBL'];
?>
					</tbody>
					<tfoot>
						<tr>
							<td class="symbol"><a data-toggle="add-table-prompt" data-target="#add-table-last" href="<?=$href
?>" title="<?php l('_Add a new table'); ?>" class="dbadd add-table symbol"><span class="symbol add"></span></a></td>
						</tr>
					</tfoot>
				</table>
				<hr/>
				<h2>
					<span><?=t('_Forms')?></span>
				</h2>
				<table id="<?php echo $dbname; ?>_forms" class="forms zebra">
					<tbody>
<?php
		$title = sprintf( t('_Remove form in %s'), $dbname);
		if (empty($forms)) {
?>
						<!--<tr><td><span class="empty"><?php l("_No forms"); ?></span></td></tr>-->
						<tr id="add-form-last" data-token="<?=$_SESSION['requestToken']
?>" data-placeholder="<?=t('_alpha-numeric characters')?>"><td><span class="empty"><?php l("_empty"); ?></span></td></tr>
<?php
		} else {
			$last = count($forms)-1;
			$k = 0;
			foreach( $forms as $a => $row ) {	// each row
//							<td><a class="symbol" href="view?dbname=<?php
//					echo $dbname. '&table='. htmlspecialchars($row);
				$form = htmlspecialchars($row);
				if (is_callable(array('Form', 'hasPrefix')) && Form::hasPrefix($form))
				{
					$form = Form::delPrefix($form);
				}
				$href = BASE. 'database/'. $dbname. '/form/'. $form;
//href="note?redirect=remove&table=< ?php echo htmlspecialchars($row); ? >&type=< ?php echo Note::warning; ? >&ok=Continue&canIgnore=true&ignore=Cancel"
				$remove = BASE. 'database/'. $dbname. '/form/'. $form. '?remove';
				$body = sprintf( t('_Really remove form %s'), $form);
?>
						<tr<?php if ($k == $last)
				{
					echo ' id="add-form-last" data-token="'. $_SESSION['requestToken']. '" data-placeholder="'. t('_alpha-numeric characters'). '"';
				}?>>
							<td class="symbol"><span class="symbol dbtab"></span></td>
							<td><a class="symbol" href="<?=$href
?>" title="<?=sprintf( t('_Form %s'), $form). ' ('. sprintf( t('_Database %s'), delPrefix($dbname)). ')';
?>"><?=$form?></a></td>
<?php
/*<td><select class="form-control"><option selected disabled><?=t('_move to')?></select></td>*/
				$move = BASE. 'database/'. $dbname. '?move='. $table. '&to=';
				$dblist = array(
					'type' => 'select',
					'id' => "move". $a,
					'name' => 'move'. $a,
					'noLabel' => true,
					'placeholder' => t('_move to database'),
					'title' => t('_move this form into another database'),
					'input_class' => 'list_dbs',
					'input_wrap_class' => 'input col-md-3',
					'extra' => 'style="width: 12em;" data-table="'. $table. '" data-disable="'. delPrefix($dbname). '" data-href="'. $move. '"',
					'optional' => true,
					'show_optional' => false,
					'roptions' => $opts
 				);
?>
							<!--<td><span>< ?=Field::out($dblist)? >&nbsp;<i class="fa-solid fa-right-long-to-line"></i></span></td>-->
							<!--<td class="symbol">< ?=Field::out($dblist)? ></td>-->
							<!--<td>< ?=Field::out($dblist)? ></td>-->
							<td class="symbol"><span><?=Field::out($dblist)?><span class="symbol js-hide"><a class="symbol"><span class="symbol"><i class="fa-solid fa-right-long"></i></span></a></span></span></td>
<!--							<td class="symbol js-hide"><a class="symbol"><span class="symbol"><i class="xfa-sharp fa-solid xfa-regular xfa-duotone xfa-thin xfa-light fa-right-long"></i></span></a></td>-->
							<!--<td class="symbol"><a onclick="return confirm('<?php /*printf( l("_Really remove form %s"), $form);*/ ?>');"-->
							<td class="symbol"><a data-title="<?=$title
?>" data-body="<?=$body
?>" data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?=$form
?>" data-modal-button-del="value:proceed" data-modal-button-add="node:button,type:submit,name:modal-button,value:deletef,class:btn btn-danger no-button,aria-label:<?=t('Delete')?>,data-url:<?=$remove?>,innerText:<?=t('Delete')
?>" class="symbol modal-trigger" href="<?=$remove
?>" title="<?php printf( t('_Delete form %s'), $form); ?>"><span class="symbol delete"></span></a></td>
						</tr>
<?php
					++$k;
			}
		}
				//$href = BASE. 'database/'. $dbname. '/form/'. $GLOBALS['data']['ADD_FRM'];
				//$href = BASE. 'database/'. $dbname. '/form/'. ADD_FRM;
				$href = BASE. 'database/'. $dbname. '/forms/'. ADD_FRM;
?>
<!--						<tr class="add-form-prompt" aria-hidden="true" id="add-table-last">
							<td class="symbol"><span class="symbol dbtab"></span></td>
							<td><input type="text" name="fname" placeholder="<?//=l('_alpha-numeric characters')?>" title="<?php
//					echo t('_New form'). ' ('. sprintf( t('_Database %s'), delPrefix($dbname)). ')';
					?>" value="<?php
//					echo htmlspecialchars($_REQUEST['fname']); ?>"></td>
							<td class="symbol"><a data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?//=htmlspecialchars($_REQUEST['fname'])?>" class="symbol modal-trigger"
href="<?//=$remove?>"
title="<?//php printf( t('_Delete table %s'), htmlspecialchars($_REQUEST['fname']) ); ?>"><span class="symbol delete"></span></a></td>
						</tr>-->
					</tbody>
					<tfoot>
						<tr>
							<td class="symbol"><a class="dbadd add-form symbol" href="<?=$href?>" title="<?php l('_Add a new form'); ?>" data-bs-toggle="add-form-prompt" data-bs-target="#add-form-last"><span class="symbol add"></span></a></td>
						</tr>
					</tfoot>
				</table>
			</div>
<?php
	}

	/**
	 * Add a new database
	 */
	function add_db()
	{
		$qry = 'CREATE DATABASE '. addPrefix($_POST['dbname']);
		try {
			$db->run($qry);
			$note = new Note(Array(		// draw notice after creation
				'type' => Note::notice,
				'message' => sprintf( t("_Created %s"), $_POST['dbname'] ),
				'ok' => t('_List databases'),
				'canIgnore' => true,
				'post' => Array(
					'dbname' => $_POST['dbname'],
				),
				'ignore' => sprintf( t('_Goto %s'), $_POST['dbname']),
				//'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
			));
			echo $note->display();
		} catch (PDOException $e) {	// cannot create db
			$note = new Note(Array(
				'type' => 'error',
				'message' => sprintf( t("_Cannot create database %s"), $dbname ),
				'details' => t('_Ensure the name is unique and not a reserved word'). "<br>\n". implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
			));
			echo $note->display();
		}
	}
?>
	<!-- ending page contents -->
		</div>
	</div>
