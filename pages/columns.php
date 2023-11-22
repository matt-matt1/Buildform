<?php
namespace Yuma;
use function Yuma\HTML\enScript;
use \PDO;
use Yuma\HTML\Breadcrumbs;
use Yuma\HTML\Scripts;

	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	if ( !isset($_GET['database']) ) {
		Redirect( BASE. 'databases' );
	}
	if ( /*isset($_GET['database']) &&*/ (!isset($_GET['table']) || isset($_GET['form']))) {
		Redirect( BASE. 'database/'. $_GET['database'] );
	}
	echo makeHead(array( 'page_title' => t('_Columns'), 'body_id' => 'columns', 'body_class' => "clearfix", 'page_description' => /*t('_cols_desc')*/'', 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
	//echo makeHead(array( 'page_title' => t('_Columns'), 'body_id' => 'columns', 'body_class' => "", 'page_description' => t('_cols_desc'), 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
?>
	<!-- begin page contents -->
	<div class="container">
		<div class="panel">
<?php
/*****************************************************************************
 * logic
 */
	$dbname = /*isset($_GET['database']) ?*/ $_GET['database'] /*: null*/;
	$table = isset($_GET['table']) ? $_GET['table'] : $_GET['form'];
	if ( isset($_POST['pressed']) && !empty($_POST['pressed']) ) {
		Redirect( BASE. 'database/'. $_GET['database']. '/table/'. $_GET['table']. '/columns/'/*filter_input( INPUT_SERVER, 'PHP_SELF' )*/ );
	}
	//if ($_GET['table'] === 'add' || $_GET['form'] === 'add') {
	if ($table === ADD_TBL) {
		// *****
		// Pressed OK (after table is created) - user acknowledged notice
		// *****
		if (isset($_POST['pressed']) && $_POST['pressed'] === 'ok') {
			Redirect(BASE. 'databases');
		}
/*		$qry = "CREATE TABLE {$table} (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY)";
		try {
			//$e = $db->run('ALTER '. $_GET['dbname']. '');
			$db->run($qry);
			$note = new Note(Array(
				'type' => Note::notice,
				//'message' => sprintf( t("_Created %s. Press OK to continue."), $table ),
				'message' => sprintf( t("_Created %s"), $table ),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
			));
			echo $note->display();
		} catch (PDOException $e) {*/
			$note = new Note(Array(
				//'type' => Note::error,
				'type' => Note::warning,
				'canIgnore' => true,
				//'message' => sprintf( t("_Cannot create table %s"), $table ),
				'message' => sprintf( t("_Trying to create a table %s"), $table ),
				//'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
				'details' => (isset($_SERVER['HTTP_REFERER']) ? 'Coming from '. $_SERVER['HTTP_REFERER'] : ''). (isset($_COOKIE['previous']) ? ' previous='. $_COOKIE['previous'] : '')
			));
			echo $note->display();
/*		}*/
	} else {
		// *****
		// Pressed OK (AND Database name provided) - user acknowledged notice
		// *****
		if (isset($_POST['pressed']) && $_POST['pressed'] === 'ok') {
			Redirect(BASE. 'databases');
		}
		if (!hasPrefix($_GET['database']) && !isset($_POST['pressed'])) {
			$note = new Note(Array(
				'type' => Note::error,
				'ok' => 'return',
				'canIgnore' => true,
				'message' => sprintf( t("_Not a forms database %s"), $_GET['database'] ),
			));
			echo $note->display();
		} else {
			//enScript(Array( 'name' => "columns", 'src' => BASE. "js/columns.js", 'version' => filemtime('js/columns.js'), 'requires' => array('column') ));
			enScript(Array( 'name' => "add-column", 'src' => BASE. "js/add-column.js", 'version' => filemtime('js/add-column.js'), 'requires' => array('column') ));
			//$dbname = delPrefix($_GET['database']);
//			$dbname = $_GET['database'];
		// *****
		// Form submitted with database name:
		// *****
//		$dbname = $_GET['databases'];
			if (isset($_POST['requestToken']) /*&& $_POST['requestToken'] == $_SESSION['requestToken']*/) {
			//echo '<pre>'. print_r($_POST, true). '<pre>';
			// form was submitted with a valid request token
				$errors = array();
				foreach ($_POST['col'] as $col) {
			//	echo '<pre>'. print_r($col, true). '<pre>';
/*					if ((isset($col['Field']) && !empty($col['Field'])) || (isset($col['WAS_Field']) && !empty($col['WAS_Field'])) {
						$change = (isset($col['WAS_Field']) && !empty($col['WAS_Field']) ? 'CHANGE '. $col['WAS_Field'] : 'ADD COLUMN '. $col['Field'];//MODIFY
					} else {
					}*/
					$newstr = '';
					if (isset($_POST['tableName']) && $table != $_POST['tableName']) {
						$qry = 'ALTER '. $dbname. '.'. $table. ' RENAME '. $dbname. '.'. $_POST['tableName'];
						// also: RENAME TABLE old_table TO new_table;
						try {
							$db->run($qry);//->fetchAll(PDO::FETCH_ASSOC);
/*							$note = new Note(Array(
								'type' => 'notice',
								'message' => t("_Cannot have more than 4095 columns"),
								'details' => $qry,
							));
							echo $note->display();*/
						} catch (PDOException $e) {
							$note = new Note(Array(
								'type' => 'error',
								'message' => t("_Cannot rename database table"),
								'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",//$qry,
							));
							echo $note->display();
						}
					}
					if (isset($col['Field']) && !empty($col['Field'])) {
						if (isset($col['WAS_Field']) && !empty($col['WAS_Field'])) {
							$column = $col['WAS_Field'];
							$change = 'CHANGE '. $column. ' '. ($col['Field'] != $column ? $col['Field'] : $column);//MODIFY
						//	if (!isset($col['WAS_ctype'])) {
						//		$errors[] = 'missing datatype for column ('. ((($col['Field']) && !empty($col['Field'])) ? $col['Field'] : $col['WAS_Field']). ')';
						//	} else {
						//	}
		//					if (!isset($col['WAS_clen']) || !isset($col['WAS_cattr']) || !isset($col['WAS_Null']) || !isset($col['WAS_Key']) || !isset($col['WAS_Default'])) {
		//						continue;
		//					}
						} else {
							$column = $col['Field'];
							$change = 'ADD COLUMN '. $column;
						}
						//$newstr = isset($col['Field']) && $col['Field'] != $col['WAS_Field'] ? $col['Field'] : $col['WAS_Field'];
						if (isset($col['ctype']) && !empty($col['ctype']) && $col['ctype'] != $col['WAS_ctype']) {
							$newstr .= ' '. strtoupper($col['ctype']);
						} elseif (isset($col['WAS_ctype']) && !empty($col['WAS_ctype'])) {
							$newstr .= ' '. strtoupper($col['WAS_ctype']);
						} else {
							$errors[] = 'No datatype for column ('. $column. ')';
						}
						if ((isset($col['ctype']) && !empty($col['ctype'])) || (isset($col['WAS_ctype']) && !empty($col['WAS_ctype']))) {
							if (isset($col['clen']) && !empty($col['clen']) && $col['clen'] != $col['WAS_clen']) {
								$newstr .= '('. strtoupper($col['clen']). ')';
							} elseif (isset($col['WAS_clen']) && !empty($col['WAS_clen'])) {
								$newstr .= '('. strtoupper($col['WAS_clen']). ')';
							}
							$newstr .= ((isset($col['cattr']) && $col['cattr'] != $col['WAS_cattr']) ? ' '. strtoupper($col['cattr']) : isset($col['WAS_cattr'])) ? ' '.strtoupper($col['WAS_cattr']) : '';
							if (isset($col['Null']) && $col['Null'] != $col['WAS_Null']) {
								$newstr .= (strToUpper($col['Null']) != 'NO') ? ' NULL' : ' NOT NULL';
							} else {
								$newstr .= (strToUpper($col['WAS_Null']) != 'NO') ? ' NULL' : ' NOT NULL';
							}
							if (isset($col['Default']) && isset($col['Default'][0]) && $col['Default'][0] == 'define' && isset($col['Default'][1]))
								$newstr .= ' DEFAULT '. (isset($col['Default'][1]) && !empty($col['Default'][1]) ? $col['Default'][1] : 'NULL');
						}
					} elseif (!isset($col['WAS_Field']) || empty($col['WAS_Field'])) {
						$errors[] = 'missing name of column (Field)';
					}

					//$teststr = (isset($col['WAS_Field']) ? $col['WAS_Field']. ' ' : '');
					$teststr = '';
					if (isset($col['WAS_ctype'])) {
						$teststr .= ' '. strtoupper($col['WAS_ctype']);
//					} else {
//						$errors[] = 'No datatype for column ('. $newstr. ')';
//						continue;
						$teststr .= isset($col['WAS_clen']) ? '('. strtoupper($col['WAS_clen']). ')' : '';
						$teststr .= isset($col['WAS_cattr']) ? ' '. strtoupper($col['WAS_cattr']) : '';
						if (isset($col['WAS_Null'])) {	// $_POST['tableName']
							$teststr .= (strToUpper($col['WAS_Null']) != 'NO') ? ' NULL' : ' NOT NULL';
						}
						if (isset($col['Default']) && isset($col['Default'][0]) && $col['Default'][0] == 'define' && isset($col['Default'][1]))
							$teststr .= ' DEFAULT '. (isset($col['Default'][1]) && !empty($col['Default'][1]) ? $col['Default'][1] : 'NULL');
					}

					if (empty($errors) && $teststr !== $newstr) {
						$qry = "ALTER TABLE {$dbname}.{$table} {$change} {$newstr}";
//						echo 'sql='. $qry. '</br>'. "\n";
//						echo 'teststr='. $teststr. '</br>'. "\n";
//						echo 'newstr='. $newstr. '</br>'. "\n";
						try {
							$db->run($qry);//->fetchAll(PDO::FETCH_ASSOC);
/*							$note = new Note(Array(
								'type' => 'notice',
								'message' => t("_Cannot have more than 4095 columns"),
								'details' => $qry,
							));
							echo $note->display();*/
						} catch (PDOException $e) {
							$note = new Note(Array(
								'type' => 'error',
								'message' => t("_Cannot make change to column"),
								'details' => $qry,
							));
							echo $note->display();
						}
					} else {
						if ($teststr == $newstr) {
							$errors[] = 'no change required';
						}
						$note = new Note(Array(
							'type' => 'error',
							'message' => t("_Cannot make change to column"),
							'details' => implode(' ', $errors). '</br>'. "ALTER TABLE {$dbname}.{$table} {$change} {$newstr}",
						));
						echo $note->display();
//						echo 'wanted='. "ALTER TABLE {$dbname}.{$table} {$change} {$newstr}". '</br>'. "\n";
//						echo implode(' ', $errors). '</br>'. "\n";
//							echo 'teststr='. $teststr. '</br>'. "\n";
//							echo 'newstr='. $newstr. '</br>'. "\n";
						$errors = array();
					}
				}	// endforeach
			} else {	// requestToken
				//$qry = 'DESC '. addPrefix($dbname). '.'. $table;
				$qry = 'DESC '. $dbname. '.'. $table;
				try {
					$columns = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
					if (count($columns) > 4095) {
						$note = new Note(Array(
							'type' => 'error',
							'message' => t("_Cannot have more than 4095 columns"),
							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					} else {
							display_rows( $columns );
					}
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
						'message' => sprintf( t("_Cannot get information for table %s"), $table ),
						'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				}
			}	// endif requestToken
		}	// end if-else hasPrefix
	}	// end if-else 'add'
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
	 * List rows in the given table
	 */
	function display_rows($results) {
		$scr = new Scripts;
		$dbname = $_GET['database'];
		$table = isset($_GET['table']) ? $_GET['table'] : $_GET['form'];
?>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here(); ?>
				<form method="POST" id="columns"><!-- novalidate -->
					<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
					<h1>
						<span><?=sprintf( t('_Columns of table %s'), '<input class="highlight" type="text" name="tableName" size="'. strlen($table). '" value="'. $table. '" />')?></span>
					</h1>
<!--					<div class="container">-->
					<div class="table-responsive">
<?php	//echo 'results=<pre>'. print_r($results, true). '</pre>';	?>
				<table id="dbtable_<?=$table?>" class="table table-striped xzebra xtable-hover" data-dbtable="<?=$table?>">
<?php
		//$title = sprintf( t('_Modify table %s'), $table);
/*		$h = sprintf( '<span class="highlight">%s</span>', $table );
		//$h = sprintf( "<span class='highlight'>%s</span>", $table );
		$title = sprintf( t('_Modify table %s'), $h );*/
		if (empty($results)) {	// no columns
?>
					<tbody>
						<tr id="add-col-last" <?/*data-unused-token="< ?=$_SESSION['requestToken']? >"*/?> data-placeholder="<?=t('_alpha-numeric characters')?>"><td><span class="empty"><?=l("_empty")?></span></td></tr>
<!--						<div class="row" id="add-col-last" <?/*data-unused-token="< ?=$_SESSION['requestToken']? >"*/?> data-placeholder="<?=t('_alpha-numeric characters')?>"><div class="col"><span class="empty"><?=l("_empty")?></span></div></div>-->
					</tbody>
<?php
		} else {
//			enScript(Array( 'name' => "addEvent", 'src' => BASE. "js/addEvent.js", 'version' => filemtime('js/addEvent.js') ));
//			enScript(Array( 'name' => "addClass", 'src' => BASE. "js/addClass.js", 'version' => filemtime('js/addClass.js') ));
			enScript(Array( 'name' => "table", 'src' => BASE. "js/table.js", 'version' => filemtime('js/table.js') ));
			enScript(Array( 'name' => "trigmodal", 'src' => BASE. "js/trigmodal.js", 'version' => filemtime('js/trigmodal.js'), 'requires' => Array('bootstrap@5.0.1') ));
			enScript(Array( 'name' => "fillSelect", 'src' => BASE. "js/fillSelect.js", 'version' => filemtime('js/fillSelect.js') ));
			//enScript(Array( 'name' => "addCol", 'src' => BASE. "js/addCol.js", 'version' => filemtime('js/addCol.js') ));
			enScript(Array( 'name' => "column", 'src' => BASE. "js/column.js", 'version' => filemtime('js/column.js') ));
			enScript(Array( 'name' => "CustomSelect", 'src' => BASE. "js/CustomSelect.js", 'version' => filemtime('js/CustomSelect.js') ));
			//enScript(Array( 'name' => "multiSelect", 'src' => BASE. "js/multiSelect.js", 'version' => filemtime('js/multiSelect.js') ));
	//		enScript(Array( 'name' => "multiselect-dropdown", 'src' => BASE. "js/multiselect-dropdown.js", 'version' => filemtime('js/multiselect-dropdown.js') ));
	$scr->enqueue(Array(
		'name' => 'resize-select',
		//'src' => BASE. "js/validate.js",
		'src' => BASE. "js/resize-select.js",
		//'version' => filemtime('js/validate.js'),
		'version' => filemtime('js/resize-select.js'),
		'crossorigin' => "anonymous", 'put_in_header' => false )
	);
/*	Scripts::getInstance()->enqueue(Array(
		'name' => 'validate',
		//'src' => BASE. "js/validate.js",
		'src' => BASE. "js/validate.min.js",
		//'version' => filemtime('js/validate.js'),
		'version' => filemtime('js/validate.min.js'),
		'crossorigin' => "anonymous", 'put_in_header' => false )
);*/
	$scr->enqueue(Array(
		'name' => 'sleep',
		'src' => BASE. "js/sleep.js",
		'version' => filemtime('js/sleep.js'),
		'crossorigin' => "anonymous", 'put_in_header' => false )
	);
	$scr->enqueue(Array(
		'name' => 'MyAjax',
		'src' => BASE. "js/MyAjax.js",
		'version' => filemtime('js/MyAjax.js'),
		'crossorigin' => "anonymous", 'put_in_header' => false )
	);
/*	Scripts::getInstance()->enqueue(Array(
		'name' => 'customSelect',
		'src' => BASE. "js/custom-select.js",
		'version' => filemtime('js/custom-select.js'),
		'crossorigin' => "anonymous", 'put_in_header' => false )
);*/
	$scr->enqueue(Array(
		'name' => 'vanillaSelectBox',
		'src' => BASE. "js/vanillaSelectBox.js",
		'version' => filemtime('js/vanillaSelectBox.js'),
		'crossorigin' => "anonymous", 'put_in_header' => false )
	);
			$dtitle = sprintf( t('_Modify table %s'), $table );
			//echo '<!-- '. print_r($results, true). ' -->';?>
					<thead>
						<tr class="heads">
<!--						<div class="row row-cols-<?=(count($results[0])+1+(count($results) > 1 ? 1 : 0))?> heads xrows-<?=(count($results)+1)?>">-->
<!-- row-cols value is number of results for first row + 1 ( for a delete column ) + ( 1 if more than a single  row; otherwise 0 ) -->
<?php		if (count($results) > 1) :	?>
							<th>&nbsp;</th>
						<!--	<div class="col">&nbsp;</div>-->
<?php		endif;
			$h=0;
			foreach( $results[0] as $fk => $fv ) {	// display the headings
?>
							<th class="head label" data-head="<?php
				echo $h++; ?>"><label for="<?=$fk?>"><?php
						echo $fk; ?></label></th>
<!--							<div class="col head label" data-head="<?php
				echo $h++; ?>"><label for="<?=$fk?>"><?php
						echo $fk; ?></label></div>-->
<?php
			}
?>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
<!--							<div class="col head label">&nbsp;</div>-->
<!--						</div>--><!-- end heads row -->
<?php
			$last = count($results)-1;
			$k = 0;
			foreach( $results as $a => $row ) {	// each row
				$column = htmlspecialchars($row['Field']);
				$href = BASE. 'database/'. $_GET['database']. '/table/'. $table. '/column/'. $column;
				$remove = BASE. 'database/'. $_GET['database']. '/table/'. $table. '/column/'. $column. '?remove';
				/*$h = sprintf( '<span class="highlight">%s</span>', $column );
				$body = sprintf( t('_Really remove column %s'), $h );*/
				$body = sprintf( t('_Really remove column %s'), $column );
				$title = sprintf( t('_Modify column %s!'), $column );
//				$last = ($a == (count($results)-1)) ? true : false;
?>
					<tr class="datacol<?php if ($a == (count($results)-1)) { print ' last-row'; }?>" data-row="<?=$a?>" data-col="<?=$column?>" draggable="true"<?php if ($k == $last)
/*						<div class="row" data-column="<?=$column?>" draggable="true"<?php if ($k == $last)*/
				{
					//echo ' id="add-col-last" data-token="'. $_SESSION['requestToken']. '" data-placeholder="'. t('_alpha-numeric characters'). '"';
					echo ' id="add-col-last" data-placeholder="'. t('_alpha-numeric characters'). '"';
				}?>>
<?php			if (count($results) > 1) :	?>
						<td><span class="symbol dbcol" title="<?=t('_move handle')?>"></span></td>
<!--							<div class="col"><span class="symbol dbcol"></span></div>-->
<?php			endif;
					foreach($row as $col => $val) {	// each cell in row
						if (isset($row['Type']))
						{
							/*$asdf='asdf(zxc)';
							 * if (strpos($asdf, '(') !== false) {
							 *	$parts = explode('(', $asdf);
							 *	if (strpos($parts[1], ')') !== false) {
							 *		$more = explode(')', $parts[1]);
							 *		echo $parts[0].','.$more[0];
							 *	}
							   }*/
							if (strpos($row['Type'], '(') !== false)	// contains a data length start delimiter
							{
								$types = explode('(', strtoupper($row['Type']));	// separate data type and remainder
								$dataType = $types[0];
//								echo '<!-- dataType = '. $dataType. ' -->';
								if (isset($types[1]))	// contains a data length
								{
									$types = explode(')', $types[1]);//$row['Type']);	// separate data length end delimiter
									$dataLen = $types[0];
//									echo '<!-- dataLen = '. $dataLen. ' -->';
								}
	//						} else {
	//							$dataType = $row['Type'];
							}
//							$dataAttrs = '';
							if (strpos($row['Type'], ' ') !== false)    // container other(s)
							{
								$types = explode(' ', $row['Type']);	// separate each further attribute
								if (!isset($dataType) || !$dataType)
								{
									$dataType = array_shift($types);
								} else {
									array_shift($types);
								}
								if (isset($types[1]))
								{
									$dataAttrs = strtoupper($types);
								} else {
									$dataAttrs = strtoupper($types[0]);
								}
							}
							if (isset($types))
							{
								$types = null;
								unset($types);
							}
						}
?>							<td class="colvalue" data-row="<?=$a?>" data-col="<?=$col?>"><?php
/*						? >							<div class="col colvalue" data-col="<?php echo $col; ?>"><?php */
						switch($col)
						{
						case 'Field':
?>								<div class="input col xform-control">
									<input type="text" id="Field<?=$a?>" name="col[<?=$a?>][Field]" class="form-control"<?php if (!empty($val)) {echo /*' placeholder="'. $val. '"*/' title="'. sprintf( t('_was %s'), $val ). '" value="'. $val. '"';}?> required>
									<input type="hidden" name="col[<?=$a?>][WAS_Field]" value="<?=$val?>">
								</div><?php
							$values['Field'] = array(
								'id' => 'Field'. $a,
								//'row_class' => "input col xform-control",
								'input_wrap_class' => "input col",
								
								'noLabel' => true//,
//								'type' => 'text'
							);
							if (!empty($val)) {
								$values['Field']['placeholder'] = $val;
								$values['Field']['title'] = sprintf( t('_was %s'), $val );
								$values['Field']['value'] = $val;
							}
//							echo '<!-- try: '. Field::out($values['Field']). ' -->';
							break;
						case 'Type':
//			echo (isset($_POST['clen'])) ? $_POST['clen'] : (isset($dataLen) && !empty($dataLen)) ? $dataLen : (isset($_POST['Type']) && !empty($_POST['Type'])) ? $arr = explode( '(', $_POST['Type'] ); $str = explode( ')', $arr[1] ); $str[0];
							if (isset($_POST['clen']))
							{
								$value = $_POST['clen'];
							} elseif (isset($dataLen) && !empty($dataLen))
							{
								$value = $dataLen;
							} elseif (isset($_POST['Type']) && !empty($_POST['Type']))
							{
								$arr = explode( '(', $_POST['Type'] );
								if (isset($arr[1]))
								{
									$str = explode( ')', $arr[1] );
									$value = $str[0];
								}
							}
?>								<div class="input col" id="Type">
									<select id="Type-select<?=$a?>" name="col[<?=$a?>][ctype]" class="Type-select form-control"<?php if (!empty($dataType)){echo ' value="'. $dataType. '" title="'. sprintf(t('_was %s'), $dataType). '"';}?> required>
<?php
							if (!isset($_POST['ctype']) /*&& !isset($dataType)*/) {
//										<option selected disabled><?=t('_Select')? ></option>
?>
										<option selected disabled><?=t('_Must select')?></option>
<?php
							}
/*							if (isset($_POST['ctype'])) {
								echo '<!-- POST-ctype='. $_POST['ctype']. '!! -->';
							}
							if (isset($dataType)) {
								echo '<!-- dataType='. $dataType. '!! -->';
							}*/
							//echo '<!-- '. print_r(SQL::COLTYPES, true). '| dt='. $dataType. ' -->';
							echo Select::drawOptions(SQL::COLTYPES, '', $dataType);
?>
									</select>
									<input type="hidden" name="col[<?=$a?>][WAS_ctype]" value="<?=$dataType?>">
									<input type="text" id="Type-len<?=$a?>" name="col[<?=$a?>][clen]" class="Type-len form-control" WASmin="0"<?php if (isset($value) && !empty($value)){echo ' value="'. $value. '" title="'. sprintf(t('_was %s'), $value). '"';}?>>
									<input type="hidden" name="col[<?=$a?>][WAS_clen]" value="<?=$value?>">
									<select id="Type-attr<?=$a?>" name="col[<?=$a?>][cattr]" class="Type-attr form-control"<?php if (isset($dataAttrs) && !empty($dataAttrs)) { echo ' value="'. $dataAttrs. '" title="'. sprintf(t('_was %s'), $dataAttrs). '"'; }?>>
<?php
				if (!isset($_POST['ctype']) && !isset($dataAttrs)) {
?>
										<option selected disabled><?=t('_Select')?></option>
<?php
				} else {
?>
							<!--<option value=""><?=t('_none!')?></option>-->
										<option value=""><?=t('_none')?></option>
<?php
				}
				echo Select::drawOptions(SQL::COLATTR, '', (isset($dataAttrs) && !empty($dataAttrs) ? $dataAttrs : ''));
		//echo Select::drawOptions(SQL::COLATTR, $dataAttrs);
?>
									</select>
									<input type="hidden" name="col[<?=$a?>][WAS_cattr]"<?php if (isset($dataAttrs) && !empty($dataAttrs)) { print ' value="'. $dataAttrs. '"'; } ?>>
								</div>
<?php	/*								<input type="text" name="Type" placeholder="<?=$val?>" title="<?=t('_was:'). ' '. $val?>" value="<?=$val?>">	*/
							break;
						case 'Null':
							$value = ((isset($_POST['Null']) && $_POST['Null'] && strtoupper($_POST['Null']) != 'NO') || strtoupper($val) === 'YES') ? true : false;
							$text = sprintf(t('_was %s'), ($value ? t('_YES') : t('_NO')));
/*								<select name="Null" title="<?=t('_was:'). ' '. $val?>" value="<?=$value ? t('_YES') : t('_NO')?>">*/
?>								<div class="input col">
									<select id="Null<?=$a?>" name="col[<?=$a?>][Null]" class="Null form-control" title="<?=$text?>" value="<?=$text?>">
										<option<?php if ($value) { echo ' selected';}?> value="YES"><?=t('_YES')?></option>
										<option<?php if (!$value) { echo ' selected';}?> value="NO"><?=t('_NO')?></option>
									</select>
									<input type="hidden" name="col[<?=$a?>][WAS_Null]" value="<?=$val?>">
								</div><?php
							break;
						case 'Key':
							$value = (isset($_POST['Key']) && !empty($_POST['Key'])) ? $_POST['Key'] : $val;
							if (!is_array($value))
							{
								$value = explode(',', $value);
							}
							if (!isset($value[1]))
							{
								$value = strtoupper($value[0]);
							}
								//$opts = Select::drawOptions(SQL::COLINDEX, $value);
							$opts = Select::drawOptions(SQL::COLINDEX, '', $value);
									/*<select id="Key<?=$a?>" name="Key<?=$a?>[]" class="select xselectMultple form-control" multiple="multiple" data-multiselect-select-all="true" data-multiselect-invert="true" data-multiselect-change-title="false" data-multiselect-search="false" style="xdisplay: none;"<?php if (!empty($value)) {echo ' title="'. sprintf(t('_was %s'), $value). '" value="'. $value. '"';}?> size="3"><?=$opts */
?>								<div class="input col xselectMultpleWrap">
									<select id="Key<?=$a?>" name="col[<?=$a?>][Key][]" class="Key select form-control" multiple="multiple"<?php if (!empty($value)) {echo ' title="'. sprintf(t('_was %s'), $value). '" value="'. $value. '"';}?> size="3"><?=$opts 
?>									</select>
									<input type="hidden" name="col[<?=$a?>][WAS_Key][]" value="<?=$value?>">
								</div><?php
							break;
						case 'Default':
							$value = (isset($_POST['Default']) && !empty($_POST['Default'])) ? $_POST['Default'] : $val;
//							echo print_r($value, true);
							if (null != $value) {
								if (!is_array($value))
								{
									$value = explode(',', $value);
								}
								if (!isset($value[1]))
								{
									$value = $value[0];
								}
							}
?>								<div id="Default" class="input col">
									<select id="Default-select<?=$a?>" name="col[<?=$a?>][Default][]" class="Default form-control" data-endisTrig="#Default-input"<?php if (!empty($value)) {echo ' title="'. sprintf(t('_was %s'), $value). '" value="'. $value. '"';}?>><?php
		echo Select::drawOptions(SQL::COLDEF, $value);
?>									</select>
									<input type="hidden" name="col[<?=$a?>][WAS_Default]" value="<?=$value?>">
<!--									<input type="hidden" name="col[<?=$a?>][WAS_Default][]" value="<?=$value?>">-->
									<input type="text" id="Default-input<?=$a?>" name="col[<?=$a?>][Default][]" class="form-control disable" placeholder="<?=t('_Default value')?>"<?php if (!empty($value)) {echo ' title="'. sprintf(t('_was %s'), $value). '" value="'. $value. '"';}?>>
<!--									<input type="hidden" name="col[<?=$a?>][WAS_Default][]" value="<?=$value?>">-->
								</div><?php
							break;
						case 'Extra':
							$value = (isset($_POST['Extra']) && !empty($_POST['Extra'])) ? $_POST['Extra'] : $val;
						/*		<input type="text" name="Extra" placeholder="<?=$val?>" title="<?=t('_was:'). ' '. $val?>" value="<?=$val?>"><?php	*/
							if (!is_array($value) || (isset($_POST['Extra']) && !empty($_POST['Extra']) && !is_array($_POST['Extra'])))
							{
								$value = strtoupper((isset($_POST['Extra']) && !empty($_POST['Extra'])) ? $_POST['Extra'] : $val);
								$comment = '';
								if ($value && stripos($value, 'comment') !== false)
								{
									$start = stripos($value, 'comment');
									//$str = substr($value, $start+6, strrpos($value, '"'
		//							$pattern = '#comment\s["\']([^"\']*)["\'].*$#i';
									/*$pattern = '~__\((["\'])(?<param1>(?>[^"\'\\\]+|\\\.|(?!\1)["\'])*)\1(?:,\s*(?<param2>\$[a-z0-9_-]+))?\);~si';*/
									$pattern = <<<'LOD'
~
## definitions
(?(DEFINE)
    (?<sqc>        # content between single quotes
        (?> [^'\\]+  | \\. )* #'
        # can be written in a more efficient way, with an unrolled pattern:
        # [^'\\]*+ (?:\\. ['\\]*)*+
    )
    (?<dqc>        # content between double quotes
        (?> [^"\\]+  | \\. )* #"
    )
    (?<var>        # variable
        \$ [a-zA-Z0-9_-]+
    )
)

## main pattern
comment\s
(?| " (?<param1> \g<dqc> ) " | ' (?<param1> \g<sqc> ) ' )
# note that once you define a named group in the first branch in a branch reset
# group, you don't have to include the name in other branches:
# (?| " (?<param1> \g<dgc>) " | ' ( \g<sqc> ) ' ) does the same. Even if the
# second branch succeeds, the capture group will be named as in the first branch.
# Only the order of groups is taken in account.
(?:, \s* (?<param2> \g<var> ) )?
.*$
~xs
LOD;
									preg_match_all($pattern, $str, $matches);
									echo '<h4 id="comm"> comment '. print_r($matches, true). ' </h4>';
									$comment = $matches;
									//preg_match_all('/".*?"|\'.*?\'/', $value, $matches);
								}
								$charset = '';
								if ($value && stripos($value, 'CHARACTER SET') !== false)
								{
									$start = mb_str('substr', 0, mb_str('stripos', $value)+1);
									$collation = mb_str('substr', $start, mb_str('strlen', $value));
								}
								$collation = '';
								if ($value && stripos($value, 'collate') !== false)
								{
									$start = mb_str('substr', 0, mb_str('stripos', $value)+1);
									$collation = mb_str('substr', $start, mb_str('strlen', $value));
								}
/*								if (strpos($value, ' ') !== false)
								{
									//$value = explode(' COMMENT', $value);
									$value = explode(' ', $value);
								}*/
		/*					if (strpos($value, ',') !== false)
							{
								$value = explode(',', $value);
								if (!isset($value[1]))
									$value = $value[0];*/
							} else {
								$value = $val;
							}
						/*	<input type="text" name="Key" placeholder="<?=$val?>" title="<?=t('_was:'). ' '. $val?>" value="<?=$val?>"><?php	*/
/*								<div class="input col selectMultpleWrap">*/
?>								<div class="input col xselectMultpleWrap"><?php	/*
<!--									<select multiple id="Extra-select<?=$a?>" data-name="Extra<?=$a?>[]" name="col[<?=$a?>][Extra][]" class="xmodal-trigger selectMultple form-control"<?php if (!empty($value)) {echo ' title="'. sprintf(t('_was %s'), $value). '" value="'. $value. '"';}?> size="3">--><?php
		echo Select::drawOptions(SQL::COLEXTRAS, '', $value);
?>									</select>
									<input type="hidden" name="col[<?=$a?>][WAS_Extra][]" value="<?=$value?>">
 */
							$titlee = sprintf( t('_Column %s'), $column);
							$values['ai'] = array(
								'id' => "auto-inc". $a,
								'name' => 'col['. $a. '][Extra][ai]',
								'label' => SQL::COLEXTRAS[0]['text'],//t('_Auto-Increment'),
//								'after' => SQL::COLEXTRAS[0]['title'],//t('_Auto-Increament'),
								'label_title' => SQL::COLEXTRAS[0]['title'],//t('_Auto-Increament'),
								'value' => $value,
								'input_title' => sprintf( t('_was %s'), ($value ? t('_YES') : t('_NO')) ),
								'row_class' => 'row g-2 mb-4 form-switch',
								'label_class' => 'right',
								'input_class' => 'big-check',
								'type' => 'checkbox',
								'optional' => true,
								'show_optional' => false
							);
							$values['ai2'] = array(
								'type' => 'hidden',
								'name' => 'col['. $a. '][WAS_Extra][ai]',
								'value' => ($value) ? 'on' : ''
							);
							$values['comm'] = array(
								'id' => "comment". $a,
								'name' => 'col['. $a. '][Extra][comm]',
								'label' => SQL::COLEXTRAS[1]['text'],//t('_Comment'),
								'label_title' => SQL::COLEXTRAS[1]['title'],
								'value' => $comment,
								'input_title' => /*!empty($comment) ?*/ sprintf( t('_was %s'), $comment ) /*: ''*/,
								'row_class' => 'row g-2 mb-4 col-rows-2 textarea5',
								'label_class' => 'right',
								'input_wrap_class' => 'input col-md-9',
								'type' => 'textarea',
								'optional' => true,
								'show_optional' => false
							);
							$values['comm2'] = array(
								'type' => 'hidden',
								'name' => 'col['. $a. '][WAS_Extra][ai]',
								'value' => $value
							);
							$values['coll'] = array(
								'id' => "collation". $a,
								'name' => 'col['. $a. '][Extra][coll]',
								'label' => SQL::COLEXTRAS[2]['text'],//t('_Collation'),
								'label_title' => SQL::COLEXTRAS[2]['title'],
								'value' => $collation,
								'input_title' => !empty($collation) ? sprintf( t('_was %s'), $collation ) : '',
								'row_class' => 'row g-2 mb-4',
								'label_class' => 'right',
								'input_wrap_class' => 'input col-md-3',
								'type' => 'select',
								'roptions' => Select::drawOptions(SQL::COLLATION, '', $collation),
								'optional' => true,
								'show_optional' => false
							);
							$values['coll2'] = array(
								'type' => 'hidden',
								'name' => 'col['. $a. '][WAS_Extra][ai]',
								'value' => $value
							);
//							$body = '';
							$bodye = t('_loading...');//htmlentities(Field::out($values['ai']));
							$hrefe = '';
							$ajaxURL = BASE. 'getAjax.php?nonce='. getServerValue('UNIQUE_ID');
							//$data = urlencode('query=DESC '. $dbname. '.'. $column);
							$data = urlencode('db='. $dbname. '&tbl='. $table. '&col='. $a. '&action=displayColumnExtras');
							//add_hook('ajax_displayColumnExtras', 'ajax_displayColumnExtras');
							//$data = urlencode('db='. $dbname. '&tbl='. $table. '&col='. $a. '&action=hint&q=c');
?>
									<a class="symbol modal-trigger" data-bs-toggle="modal" data-bs-target="#myModal" data-crow="<?=$a

?>" data-modal-dialog-class="modal-xl" data-body="<?=$bodye
?>" href="<?=$hrefe
?>" data-title="<?=$titlee
?>" data-ajax-headersWAS="asdf:qwerty,zxc:vbnm,uiop:ghjkl" data-ajax-method="POST" data-ajax-url="<?=$ajaxURL
/*?>" data-ajax-column="<?=$a*/
?>" data-modal-styleWAS="width:80%,max-width:850px" data-ajax-data="<?=$data
/*?>" data-modal-button-WASadd="<?=urlencode('[{"node":"button","type":"button","name":"modal-button","value":"save","class":"btn btn-primary"."innerText":"'. t('Save'). '"}]')*/
/*?>" data-modal-button-del="value:proceed" data-modal-button-add="node:button,type:button,name:modal-button,value:save,class:btn btn-primary,form:columns,innerText:<?=t('Save')*/
?>" data-modal-button-del="value:proceed" data-modal-button-add="node:button,type:submit,name:modal-button,value:save,class:btn btn-primary,form:columns,aria-label:<?=t('Save')?>,innerText:<?=t('Save')
?>" data-name="<?=$column
?>" title="<?=sprintf( t('_Set extra for %s'), $column)
?>"><span class="symbol more"></span></a>
								</div><?php
/*							echo Field::out($values['ai']). Field::out($values['ai2']);
							echo Field::out($values['comm']). Field::out($values['comm2']);
							echo Field::out($values['coll']). Field::out($values['coll2']);*/
							break;
						}
?>							</td><?php
?><!--							</div>--><?php
					}	// control for each item in row ^
?>
							<td class="col symbol"><a data-title="<?=$title
?>" data-body="<?=$body
?>" data-bs-toggle="modal" data-bs-target="#myModal" data-crow="<?=$a?>" data-name="<?=$column
?>" class="symbol modal-trigger delrow" href="<?=$remove
?>" title="<?=sprintf( t('_Delete column %s'), $column)
?>"><span class="symbol delete"></span></a><!--</div>--></td>
						</tr>
						</div>
<?php
				++$k;
			}	// control to delete the row/ database item ^
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
/*		if (isset($_GET['table']))
		{*/
			//$href = BASE. 'database/'. $_GET['database']. '/table/'. $_GET['table']. '/column/'. ADD_COL;//BASE. 'database/'. $dbname. '/table/add';
			$href = BASE. 'database/'. $_GET['database']. '/table/'. $_GET['table']. '/columns/'. ADD_COL;//BASE. 'database/'. $dbname. '/table/add';
/*			ob_start();
			displayAddForm($column);
		$xbody = ob_get_clean();*/
?>
					<tfoot>
						<tr>
<!--						<div class="row">-->
							<td class="col symbol"><a id="add-column-btn" data-title="<?=$title
?>" data-body="<?=$body
/*?>" data-bs-toggle="modal" data-bs-target="#myModal" data-name="<?=$column*/
?>" class="ib symbol dbadd add-col modal-trigger" href="<?=$href
?>" title="<?=sprintf( t('_Add a new column to %s!'), $_GET['table'])?>"><span class="symbol add"></span></a><!--</div>--></td>
						</tr>
					</tfoot>
				</table>
						</div>
<!--					</div>--><!-- end container -->
<!--					</tfoot>
				</table>-->
				<div class="submit">
					<input id="saveColumns" type="submit" value="<?=t('_Save changes')?>">
				</div>
				<h2><a href="<?=BASE. 'database/'. $_GET['database']. '/table/'. $_GET['table']
?>" title="<?=sprintf( t('_Records in table %s!'), $_GET['table'])
?>"><span class="symbol ret"><?=t('_Records')?></span></a></h2>
<?php	/*
		} elseif (isset($_GET['form']))
		{
?>
				<h2><a href="<?=BASE. 'database/'. $_GET['database']. '/form/'. $_GET['form']
?>" title="<?=sprintf( t('_Records in form %s'), $_GET['form'])
?>"><span class="symbol ret"><?=t('_Records')?></span></a></h2>
<?php	*/
/*		}*/
?>
				</form>
			</div>
<?php
	}

	/**
	 * Display the HTML form to add/ modify a column
	 */
	function displayAddForm($columns) {
?>
		<form method="post" id="column" name="column">
			<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
			<h1>
<?php
		if (isset($page) && !empty($page))
		{
?>
				<?=$page?>
<?php
		} else {
?>
				<span><?=sprintf( t('_Add a new column to %s'), $_GET['table']); ?></span>
<?php
		}
?>
			</h1>
<?php
		if (isset($_POST['do'])) {
?>
			<input type="hidden" name="do" value="<?=$_POST['do']?>">
<?php
		}
		if (!isset($page) || empty($page)) {
?>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label for="cname"><?=t('_Column name')?>:</label>
					</div>
					<div class="input col-75">
					<input placeholder="<?=t('_alpha-numeric characters')?>" type="text" id="cname" name="cname" autofocus value="<?=isset($_POST['cname']) ? $_POST['cname'] : (isset($use['Field']) ? $use['Field'] : '') ?>">
					</div>
				</div>
			</div>
<?php	}
		if (!isset($_POST['do']) || $_POST['do'] !== 'drop') {
			$value = isset($_POST['ctype']) ? $_POST['ctype'] : (isset($use['Type']) ? strtoupper( strtok( $use['Type'], '(' ) ) : '');
?>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label for="ctype"><?=t('_Data type')?>:</label>
					</div>
					<div class="input col-40">
						<select id="ctype" name="ctype" value="<?=$value?>">
<?php
				if (!isset($_POST['ctype']) && !isset($use['Type'])) {
?>
							<option selected disabled><?=t('_Select')?></option>
<?php
				}
		Select::drawOptions(SQL::COLTYPES, '', $value);
?>
						</select>
					</div>
				</div>
				<div class="input_field">
					<div class="label col-25">
						<label for="clen"><?=t('_Data length/value')?>:</label>
					</div>
					<div class="input col-20">
						<input type="number" id="clen" name="clen" value="<?php
			if (isset($_POST['clen']))
			{
				echo $_POST['clen'];
			} elseif (isset($use['Type']) && strpos( $use['Type'], '('))
			{
				$arr = explode( '(', $use['Type'] );
				$str = explode( ')', $arr[1] );
				echo $str[0];
			}?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label id="cdef_label" for="cdef"><?=t('_Default value')?>:<small><?=t('_optional')?></small></label>
					</div>
					<div class="input col-40">
						<select id="cdef" name="cdef" value="<?=(isset($_POST['cdef']) && !empty(trim($_POST['cdef'])) ? $_POST['cdef'] : isset($use['Default']) && !empty($use['Default'])) ? $use['Default'] : ''?>" data-dest="cdef2">
							<option disabled><?=t('_Select')?> <?=t('_optional')?></option>
<?php
			if (isset($use['Default']) && $use['Default'] !== 'NULL' && $use['Default'] !== 'CURRENT_TIMESTAMP' && $use['Default'] !== 'none') {
				$def = $use['Default'];
			}
			Select::drawOptions(SQL::COLDEF, '', isset($_POST['cdef']) && !empty(trim($_POST['cdef'])) ? $_POST['cdef'] : (isset($def) ? 'define' : ''));
?>
						</select>
					</div>
<!--				<div class="input_field">
				</div>-->
	<!-- -->				<div class="label col-25 hidden disabled">
						<label for="cdef">&nbsp;</label>
					</div><!-- -->
					<div class="input col-30">
						<input type="text" id="cdef2" name="cdef2" value="<?=isset($_POST['cdef2']) && !empty($_POST['cdef2']) ? $_POST['cdef2'] : (isset($def) ? $def : '')?>" class="disable" <?php /*data-trig="ccol"*/ ?> placeholder="<?=t('_default defined value')?>">
					</div>
				</div>
			</div>
<?php
		if (isset($use['Extra']) && strpos($use['Extra'], 'COLLATE') !== false) {
			$pos = strpos($use['Extra'], 'COLLATE');
			$pattern = "#COLLATE ([^\s]+)#";
			preg_match_all($pattern, $use['Extra'], $match, PREG_SET_ORDER);
			$coll = $match[1];//substr($use['Extra'], $pos;
		}
?>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label for="ccol"><?=t('_Column Collation')?>:<small><?=t('_optional')?></small></label>
					</div>
					<div class="input col-40">
						<select id="ccol" name="ccol" value="<?=isset($_POST['ccol']) && !empty( trim($_POST['ccol']) ) ? $_POST['ccol'] : (isset($coll) ? $coll : '')?>">
							<option disabled><?=t('_Select')?> <?=t('_optional')?></option>
							<option value=""<?php if ((!isset($_POST['ccol']) || empty(trim($_POST['ccol']))) && !isset($coll)){echo ' selected';}?>><?=t('_inherit')?></option>
<?php
		Select::drawOptions(SQL::COLLATION, isset($_POST['ccol']) && !empty(trim($_POST['ccol'])) ? $_POST['ccol'] : (isset($ccoll) ? $coll : ''));
?>
						</select>
					</div>
				</div>
			</div>
<?php
		$cattr = isset($_POST['cattr']) && !empty(trim($_POST['cattr'])) ? $_POST['cattr'] : ((isset($use['Type']) && strpos( $use['Type'], ' ') !== false) ? strtoupper( explode( ' ', $use['Type'], 2 )[1] ) : '');//`(a ? b : c) ? d : e`  or  `a ? b : (c ? d : e)`
?>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label for="cattr"><?=t('_Attribute')?>:<small><?=t('_optional')?></small></label>
					</div>
					<div class="input col-40">
						<select id="cattr" name="cattr" value="<?=$cattr?>">
							<option disabled><?=t('_Select')?> <?=t('_optional')?></option>
							<option value=""<?php if (!isset($_POST['cattr']) || empty(trim($_POST['cattr'])) || (isset($use['Type']) && strpos( $use['Type'], ' '))){echo ' selected';}?>><?=t('_none')?></option>
<?php
		Select::drawOptions(SQL::COLATTR, '', $cattr);
?>
						</select>
					</div>
				</div>
				<div class="input_field">
					<div class="label col-25">
						<label for="cnul"><?=t('_Can contain null')?>:</label>
					</div>
					<div class="input col-20">
						<input type="checkbox" id="cnul" name="cnul"<?php if ((isset($_POST['cnul']) && $_POST['cnul']) || (isset($use['Null']) && strtoupper($use['Null']) !== 'NO' && $use['Null'] !== 0 && $use['Null'] !== false)){echo ' checked';}?>>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label for="cind"><?=t('_Index(es)')?>:<small><?=t('_optional')?></small></label>
					</div>
					<div class="input col-40">
						<select id="cind" name="cind" value="<?=isset($_POST['cind']) && !empty(trim($_POST['cind'])) ? $_POST['cind'] : (isset($use['Key']) ? $use['Key'] : '')?>">
							<option disabled><?=t('_Select')?> <?=t('_optional')?></option>
							<option value=""<?php if (!isset($_POST['ccol']) || empty(trim($_POST['ccol'])) || !isset($use['Key']) || empty($use['Key'])){echo ' selected';}?>><?=t('_none')?></option>
<?php
		Select::drawOptions(SQL::COLINDEX, isset($_POST['cind']) ? $_POST['cind'] : (isset($use['Key']) ? $use['Key'] : ''));
?>
						</select>
					</div>
				</div>
				<div class="input_field">
					<div class="label col-25">
						<label for="cai"><?=t('_Auto-Increment')?>:</label>
					</div>
					<div class="input col-20">
						<input type="checkbox" id="cai" name="cai"<?php if ((isset($_POST['cai']) && $_POST['cai']) || (isset($use['Extra']) && strpos($use['Extra'], 'auto_increment') !== false)){echo ' checked';}?>>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label for="ccom"><?=t('_Comments')?>:<small><?=t('_optional')?></small></label>
					</div>
					<div class="input col-75">
<?php
		if (isset($use['Extra']) && strpos($use['Extra'], 'COMMENT') !== false) {
			$pos = strpos($use['Extra'], 'COMMENT');
			$pattern = "#COMMENT ([\"'])([^\"']+)(\/\\1)#";
			preg_match_all($pattern, $use['Extra'], $match, PREG_SET_ORDER);
			echo '<pre>match: '. print_r($match, true). '<pre>'. "\n";
			$comment = $match[2];//substr($use['Extra'], $pos;
		}
?>
						<input placeholder="<?=t('_Only visible to author/ editor')?>" type="text" id="ccom" name="ccom" value="<?=isset($_POST['ccom']) ? $_POST['ccom'] : (isset($comment) ? $comment : '')?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label for="cpos"><?=t('_Position')?>:<small><?=t('_optional')?></small></label>
					</div>
					<div class="input col-40">
						<select id="cpos" name="cpos" value="<?=$_POST['cpos']?>">
							<option disabled><?=t('_Select')?> <?=t('_optional')?></option>
							<option value="FIRST"><?=t('_First')?></option>
<?php
		$last = count($columns)-1;
		foreach( $columns as $col => $v ) {
?>
						<option<?php if ($col === $last){echo ' selected';}?> value="AFTER <?php echo enquote($v['Field']); ?>">After '<?=$v['Field']?>'<?php if ($col === $last){echo ' ('. t('_default'). ')';}?></option>
<?php
		}
?>
						</select>
					</div>
				</div>
			</div>
<!-- ... -->
<?php
		}
?>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
	<!--					<label for="submit"><?=t('_Submit')?>:</label>-->
&nbsp;
					</div>
					<div class="submit">
						<input type="submit" id="submit" name="submit" value="<?php echo ($submit) ? $submit : t('_Add column'); ?>">
					</div>
				</div>
			</div>
		</form>
<?php
	}
