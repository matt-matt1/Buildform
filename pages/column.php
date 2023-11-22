<?php
namespace Yuma;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	if ( !isset($_GET['database']) ) {
		Redirect( BASE. 'databases' );
	}
	$dbname = enquote($_GET['database']);
	if (isset($_GET['table']))
		$table = enquote($_GET['table']);
	elseif (isset($_GET['form']))
		$table = enquote($_GET['form']);
	else
		Redirect( BASE. 'database/'. $_GET['database'] );
	if (isset($_POST['pressed']))		// User responded to notice
	{
		if ($_POST['pressed'] === 'ok')
		{
			if (isset($_GET['table']))
				Redirect( BASE. 'database/'. $_GET['database']. '/table/'. $table. '/columns' );
			elseif (isset($_GET['form']))
				Redirect( BASE. 'database/'. $_GET['database']. '/form/'. $_GET['form']. '/columns' );
		} elseif ($_POST['pressed'] === 'ignore' && isset($_GET['column'])) {
			if (isset($_GET['table']))
				Redirect( BASE. 'database/'. $_GET['database']. 'table/'. $table. '/column'. $_GET['column'] );
			elseif (isset($_GET['form']))
				Redirect( BASE. 'database/'. $_GET['database']. 'form/'. $_GET['form']. '/column'. $_GET['column'] );
		} else {
			if (isset($_GET['table']))
				Redirect( BASE. 'database/'. $_GET['database']. '/table/'. $table. '/columns' );
			elseif (isset($_GET['form']))
				Redirect( BASE. 'database/'. $_GET['database']. '/form/'. $_GET['form']. '/columns' );
		}
	}
	echo makeHead(array( 'page_title' => t('_col_desc'), 'body_id' => 'column', 'body_class' => "", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
?>
	<!-- begin page contents -->
	<div class="container">
		<div class="panel">
<?php
global $db;
	// *****
	// Form submitted with a database name and a matching token
	// *****
	//if (isset($_POST['dbname']) && !empty($_POST['dbname'])) {
	if (isset($_POST['cname']) && !empty($_POST['cname'])) {
		if (!isset($_POST['requestToken']) || $_POST['requestToken'] != $_SESSION['requestToken'])
		{
			$note = new Note(Array(
				'type' => 'error',
				'message' => t("_Invalid form token"),
				'details' => "<br>\nGot: {$_POST['requestToken']} expecting {$_SESSION['requestToken']}",
			));
			echo $note->display();
			exit();
		} else {	// Valid token
			$qry = 'USE '. $dbname;
			try {
				$results = $db->run($qry);
				$qry = make_alter($table);
				try {
					$results = $db->run($qry);
					$note = new Note(Array(
						'type' => Note::notice,
						'message' => sprintf( t('_Modified %s'), $table),
						'ok' => t('_Columns'),
						'details' => "Query: {$qry}",
					));
					if (isset($_POST['cname']) && !empty($_POST['cname']))
					{
						$note->canignore = true;
						$note->ignore = sprintf( t('_Column %s'), $_POST['cname']);
					}
					echo $note->display();
				} catch (PDOException $e) {
					$note = new Note(Array(
						'type' => Note::error,
						//'canIgnore' => true,
						'message' => sprintf( t('_Cannot make changes to table %s'), $table),
						'details' => implode(' - ', $e->errorInfo). "<br>\nQuery: {$qry}",
					));
					echo $note->display();
				}
			} catch (PDOException $e) {
				$note = new Note(Array(
					'type' => Note::error,
					//'canIgnore' => true,
					'message' => sprintf( t('_Cannot select database %s'), $dbname),
					'details' => implode(' - ', $e->errorInfo). "<br>\nQuery: {$qry}",
				));
				echo $note->display();
			}
		}
/*		if (!empty($results)) {
		}*/
	// *****
	// No database name submitted - show add column form
	// *****
	} else {
		if ((isset($_GET['remove']) && isset($_GET['column'])) ||
		(getServerValue('REQUEST_METHOD') == "DELETE" && isset($_GET['column']))) {
			$qry = 'DESC '. $dbname. '.'. $table;
			try {
				$results = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
				$is_last = count($results);
				if (count($results) > 2) {
					$qry = "ALTER TABLE {$dbname}.{$table} DROP COLUMN ". $_GET['column'];	//FIXED, I HOPE 42000 - 1090 - You can't delete all columns with ALTER TABLE; use DROP TABLE instead
					try {
						$results = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
						$note = new Note(Array(
							'type' => Note::notice,
							'message' => sprintf( t('_Deleted %s'), $_GET['column']),
							//'details' => "Query: {$qry}",
						));
						echo $note->display();
					} catch (PDOException $e) {
						$note = new Note(Array(
							'type' => Note::error,
							//'message' => sprintf( t('_Cannot delete column %s from table %s'), $_GET['column'], $table),
							'message' => sprintf( t('_Cannot delete column %s'), $_GET['column']),
							'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
						));
						echo $note->display();
					}
				} else {
					//throw new Error('This is the last column - if you wish to remove the whole table please go back to the table page.');
					$note = new Note(Array(
						'type' => Note::notice,
						'message' => 'This is the last column - if you wish to remove the whole table please go back to the table page.',
						//'details' => "Query: {$qry}",
					));
					echo $note->display();
				}
			} catch (PDOException $e) {
				$note = new Note(Array(
					'type' => Note::error,
					//'message' => sprintf( t('_Cannot delete column %s from table %s'), $_GET['column'], $table),
					'message' => sprintf( t('_Cannot delete column %s'), $_GET['column']),
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
				));
				echo $note->display();
			}
		} else {
			$qry = "DESCRIBE {$dbname}.{$table}";
/*			if (isset($_GET['column']))
			{
				$qry .= ' '. $_GET['column'];
			}*/
	//		echo '<!-- '. $qry. ' -->'. "\n";
			try {
				$results = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
				displayForm($results);
			} catch (PDOException $e) {
				$note = new Note(Array(
					'type' => Note::error,
					'message' => sprintf( t('_Cannot get information for table %s'), $table),
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
				));
				echo $note->display();
			}
		}
	}
?>
		</div>
	</div>
<?php
/***************************************************************************/

	function make_alter(string $table)
	{
	//$field = (isset($_POST['cname']) ? $_POST['cname'] :  ;
		if (isset($_POST['cname']) && !empty($_POST['cname'])) {
			//$column = str_replace("``", "`", "`{$_POST['cname']}`");
			$column = enquote($_POST['cname']);
		}
		$qry = "ALTER TABLE {$table}";
		if (isset($_POST['do']) && $_POST['do'] == 'drop') {
			$qry .= " DROP COLUMN {$column}";
		} else {
			if (isset($_POST['do']) && ($_POST['do'] == 'mod' || $_POST['do'] == 'move')) {
				$qry .= " MODIFY {$column}";
			} elseif (isset($_POST['do']) && ($_POST['do'] == 'ren')) {
				$qry .= " RENAME COLUMN {$column}";
			} else {
				$qry .= " ADD {$column}";
			}
			if (isset($_POST['ctype']) && !empty($_POST['ctype'])) {	// eg. INT
				$qry .= " {$_POST['ctype']}";
				if (isset($_POST['clen']) && !empty($_POST['clen'])) {	// eg. (8)
					$qry .= "({$_POST['clen']})";
				}
			}
			if (isset($_POST['ccol']) && !empty($_POST['ccol'])) {	// eg. armscii8_bin
				$chars = explode('_', $_POST['ccol']);
				$qry .= ' CHARACTER SET '. $chars[0];
				$qry .= ' COLLATE '. $_POST['ccol'];
			}
			if (isset($_POST['cattr']) && !empty($_POST['cattr'])) {	// eg. binary
				$qry .= " {$_POST['cattr']}";
			}
			if (!isset($_POST['cnul']) || empty($_POST['cnul']) || !$_POST['cnul']) {	// eg. NOT NULL
				$qry .= ' NOT NULL';
			} else {
				$qry .= ' NULL';
			}
			if (isset($_POST['cai']) && !empty($_POST['cai'])) {
				$qry .= ' AUTO_INCREMENT';
			}
			if (isset($_POST['cdef']) && $_POST['cdef'] !== 'none') {
			//if (isset($_POST['cdef']) && $_REQUEST['cdef'] !== 'none' && isset($_REQUEST['cdef2']) && empty($_REQUEST['cdef2'])) {
				if (isset($_POST['do']) /*&& ($_POST['do'] == 'mod' || $_POST['do'] == 'move')*/) {
					$qry .= " SET";
				}
				$qry .= ' DEFAULT ';
				if (strtoupper($_POST['cdef']) === 'NULL') {
					$qry .= 'NULL';
				} elseif (strtoupper($_POST['cdef']) === 'CURRENT_TIMESTAMP') {	// must have datatype DATETIME (or similar)
					$qry .= 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
				} elseif (isset($_POST['cdef2']) && !empty($_POST['cdef2'])) {
					$qry .= str_replace("''", "'", "'{$_POST['cdef2']}'");
				}
			}
			if (isset($_POST['cind']) && !empty($_POST['cind'])) {	// eg. Primary
				if (isset($_POST['ctype']) || isset($_POST['ccol']) || isset($_POST['cattr']) || isset($_POST['cnul']) || isset($_POST['cai']) || isset($_POST['cdef'])) {
					$qry .= ',';
				}
				if (isset($_POST['do']) && $_POST['do'] == 'drop') {
					$qry .= ' DROP INDEX '. $_GET['column'];
				} else {
					$qry .= ' ADD';
					switch ($_POST['cind']) {
					case 'PRI':
						$qry .= ' PRIMARY KEY';
						break;
					case 'UNQ':
						$qry .= ' UNIQUE';
						break;
					case 'FUL':
						$qry .= ' FULLTEXT INDEX';
						break;
					case 'SPA':
						$qry .= ' SPACIAL INDEX';
						break;
					}
					$qry .= ' ('. $_GET['column']. ')';
				}
			}
			if (isset($_POST['ccom']) && !empty($_POST['ccom'])) {	// eg. My comments ...
				$qry .= ' COMMENT '. str_replace("''", "'", "'{$_POST['ccom']}'");//{$_REQUEST['ccom']}";
			}
			if (isset($_POST['cpos']) && !empty($_POST['cpos'])) {	// eg. FIRST
				$qry .= " {$_POST['cpos']}";
			}
		}
		return $qry;
	}

	function displayForm($columns) {
//		enScript(Array( 'name' => "addEvent", 'src' => BASE. "js/addEvent.js", 'version' => filemtime('js/addEvent.js') ));
//		enScript(Array( 'name' => "addClass", 'src' => BASE. "js/addClass.js", 'version' => filemtime('js/addClass.js') ));
		enScript(Array( 'name' => "column", 'src' => BASE. "js/column.js", 'version' => filemtime('js/column.js') ));
//	Scripts::getInstance()->enqueue(Array( 'name' => 'column', 'src' => BASE. 'js/column.js', 'version' => filemtime('js/column.js') ));
//		echo '<pre><!-- '. print_r($columns, true). ' --></pre>'. "\n";
		if ($_GET['column'] !== ADD_COL)
		//if ($_GET['column'] !== $GLOBALS['data']['ADD_COL'])
		{
/*		if (isset($_POST['do'])) {
			switch ($_POST['do']) {
				case 'drop':
					$page = sprintf( t('_Drop column %s'), $_GET['column']);
					$submit = t('_Drop column');
					break;
				case 'mod':*/
					//$page = sprintf( t('_Modify column %s'), $_GET['column']);
					$page = sprintf( t('_Modify column %s'), '<input class="highlight" name="cname" size="'. (strlen($_GET['column'])+1). '" value="'. $_GET['column']. '">');
					$submit = t('_Modify column');
					$use = Array();
					foreach ($columns as $k => $v)
					{
						//if ($k === $_GET['column'])
						if (isset($v['Field']) && $v['Field'] === $_GET['column'])
						{
							$use = $v;
							break;
						}
					}
					//echo 'use: '. print_r($use, true). "\n";
/*					break;
				case 'move':
					$page = sprintf( t('_Move column %s'), $_GET['column']);
					$submit = t('_Move column');
					break;
			}*/
//		} else {
//			$page = t('_Add a column');
//			$submit = t('_Add column');
		}
?>
	<script>const takenDBNames = [
<?php
		if (is_array($columns)) {
			foreach( $columns as $num => $row ) {
				//if (!in_array( $row['Field'], $GLOBALS['data']['RESERVED'] )) {
				if (!in_array( $row['Field'], RESERVED )) {
					echo "\t'{$row['Field']}',\n";
				}
			}
		}
?>
];</script>
<!--<div class="panel">-->
	<div class="pane">
<?php	echo Breadcrumbs::here(); ?>
		<form method="post" id="column" name="column">
<!--			<input type="hidden" name="dbname" value="<?=$_GET['database']?>">
			<input type="hidden" name="table" value="<?=$_GET['table']?>">-->
<?php	if (isset($_SESSION['requestToken'])) :	?>
			<input type="hidden" name="requestToken" value="<?=$_SESSION['requestToken']?>">
<?php	endif;	?>
			<h1>
<?php
		$values = array();
		$values['cname'] = array('id' => 'cname', 'value' => isset($_POST['cname']) ? $_POST['cname'] : (isset($use['Field']) ? $use['Field'] : null),/*'type' => 'text',*/ 'label' => t('_Column name'), /*'extra' => 'autofocus',*/ 'placeholder' => t('_alpha-numeric characters'));
		$values['ccen'] = array('id' => 'ccen', 'value' => isset($_POST['cname']) ? $_POST['cname'] : (isset($use['Field']) ? $use['Field'] : null),/*'type' => 'text',*/ 'label' => t('_Change existing name'), /*'extra' => 'autofocus',*/ 'placeholder' => t('_alpha-numeric characters'));
		if (isset($use['Type']))
		{
			$parts = explode('(', $use['Type']);
			$dt = $parts[0];
			if (isset($parts[1]))
			{
				$parts = explode(')', $parts[1]);
				$ds = $parts[0];
				if (isset($parts[1]))
				{
					$do = explode(' ', strtoupper(trim($parts[1])));
					if (!isset($do[1]) || empty($do[1]))
					{
						$do = $do[0];
					}
				}
			}
		}
		$ctype = isset($_POST['ctype']) ? $_POST['ctype'] : (isset($dt) ? $dt : null);
		$values['ctype'] = array(
			'id' => 'ctype',
			'label' => t('_Data type'),
			'input_wrap_class' => 'input col-md-3',
			'value' => $ctype,
			'type' => 'select',
			'roptions' => Select::drawOptions(SQL::COLTYPES, '', $ctype),
		);
		$values['clen'] = array(
			'id' => 'clen',
			'label' => t('_Data length/value'),
			'value' => isset($_POST['clen']) ? $_POST['clen'] : (isset($ds) ? $ds : null),
			'type' => 'number',
			'input_wrap_class' => 'input col-md-3'
		);
		$cdef = isset($_POST['cdef']) ? $_POST['cdef'] : (isset($use['Default']) ? $use['Default'] : null);
		$values['cdef'] = array(
			'id' => 'cdef',
			'label' => t('_Default value'),
			//'input_wrap_class' => 'input col-md-3',
			'input_wrap_class' => 'input col-auto',
			'value' => $cdef,
			'type' => 'select',
			'roptions' => Select::drawOptions(SQL::COLDEF, '', (isset($_POST['cdef']) && !empty(trim($_POST['cdef'])) ? $_POST['cdef'] : isset($def))),
			'extra' => 'data-dest="cdef2"',
			'optional' => true,
			'input2' => array(
//				'id' => 'cdef2',
				'id' => 'Default-input0',
				'type' => 'text',
			'input_wrap_class' => 'input col-auto',
				'input_class' => 'disable'
//				'extra' => 'disabled',
			),
			'row_class' => 'row g-2 mb-4'
		);
		$ccol = isset($_POST['ccol']) && !empty(trim($_POST['ccol'])) ? $_POST['ccol'] : (isset($dt) ? $dt : null);
		$values['ccol'] = array(
			'id' => 'ccol',
			'label' => t('_Column Collation'),
			//'input_wrap_class' => 'input col-md-3',
			'input_wrap_class' => 'input col-auto',
			'value' => $ccol/*isset($_POST['ccol']) ? $_POST['ccol'] : (isset($dt) ? $dt : null)*/,
			'type' => 'select',
			'optional' => true,
			'roptions' => '<option value="">'. t('_inherit'). '</option>'. Select::drawOptions(SQL::COLLATION, $ccol/*isset($_POST['ccol']) && !empty(trim($_POST['ccol'])) ? $_POST['ccol'] : (isset($dt) ? $dt : null)*/),
			'row_class' => 'row g-2 mb-4'
		);
		$cattr = isset($_POST['cattr']) && !empty(trim($_POST['cattr'])) ? $_POST['cattr'] : (isset($do) ? (is_array($do) ? implode(' ', $do) : $do) : null);
		$values['cattr'] = array(
			'id' => 'cattr',
			'label' => t('_Attribute'),
			'input_wrap_class' => 'input col-md-3',
			'value' => $cattr/*isset($_POST['cattr']) ? $_POST['cattr'] : $do,//(is_array($do) ? in_array($do,  : null)*/,
			'type' => 'select',
			'optional' => true,
			'roptions' => '<option value="">'. t('_none!'). '</option>'. Select::drawOptions(SQL::COLATTR, '', $cattr/*(isset($do) ? $do : null)*/),/*Select::drawOptions(SQL::COLLATION, isset($_POST['ccol']) && !empty(trim($_POST['ccol'])) ? $_POST['ccol'] : (isset($dt) ? $dt : null)),*/
//			'row_class' => 'row g-2 mb-4'
		);
		$values['cnul'] = array(
			'id' => 'cnul',
			'label' => t('_Can contain null'),
//			'input_wrap_class' => 'input col-auto',
			'input_class' => 'big-check',
			'value' => (isset($_POST['cnul']) ? $_POST['cnul'] : isset($use['Null']) && strtoupper($use['Null']) != 'NO') ? true : false,
			'type' => 'checkbox',
			'label_class' => 'right',
			//'row_class' => 'row g-2 mb-4 form-switch'
			'row_class' => 'xg-2 xmb-4 form-switch',
			'extra' => 'style="margin-left: 0.1em" checked'
		);
		$cind = isset($_POST['cind']) ? $_POST['cind'] : (isset($use['Key']) ? $use['Key'] : '');
		$values['cind'] = array(
			'id' => "cind",
			'label' => t('_Index(es)'),
			'optional' => true,
			//'input_wrap_class' => 'input col-auto',
			'input_wrap_class' => 'input col-md-3',
			'value' => $ccol/*isset($_POST['ccol']) ? $_POST['ccol'] : (isset($dt) ? $dt : null)*/,
			'type' => 'select',
			'roptions' => '<option value="">'. t('_inherit'). '</option>'. Select::drawOptions(SQL::COLINDEX, $cind/*isset($_POST['cind']) ? $_POST['cind'] : (isset($use['Key']) ? $use['Key'] : '')*/),
			//'row_class' => 'row g-2 mb-4'
		);
		$cai = ((isset($_POST['cai']) && $_POST['cai']) || (isset($use['Extra']) && strpos($use['Extra'], 'auto_increment') !== false));
		$values['cai'] = array(
			'id' => 'cai',
			'label' => t('_Auto-Increment'),
			'input_wrap_class' => 'input col-auto',
			'input_class' => 'big-check',
			'value' => $cai,
			'type' => 'checkbox',
			'label_class' => 'right',
			//'row_class' => 'row g-2 mb-4 form-switch'
			'row_class' => 'xg-2 xmb-4 form-switch',
			'extra' => 'style="margin-left: 0.1em"'
		);
		if (isset($use['Extra']) && strpos($use['Extra'], 'COMMENT') !== false) {
			$pos = strpos($use['Extra'], 'COMMENT');
			$pattern = "#COMMENT ([\"'])([^\"']+)(\/\\1)#";
			preg_match_all($pattern, $use['Extra'], $match, PREG_SET_ORDER);
			echo '<pre>match: '. print_r($match, true). '<pre>'. "\n";
			$comment = $match[2];//substr($use['Extra'], $pos;
		}
		$ccom = isset($_POST['ccom']) ? $_POST['ccom'] : (isset($comment) ? $comment : '');
		$values['ccom'] = array(
			'id' => 'ccom',
			'label' => t('_Comments'),
			'input_wrap_class' => 'input col-md-9',
			'value' => $ccom,
			'optional' => true,
			'placeholder' => t('_Only visible to author/ editor'),
			'row_class' => 'row g-2 mb-4'
		);
		$last = count($columns)-1;
		ob_start();
		if (!$last) {
?>						<option selected value="FIRST"><?=l('_unchanged')?></option><?php
		} else {
?>						<option value="FIRST"><?=l('_First')?></option><?php
			foreach( $columns as $col => $v ) {
		//echo '<pre><!-- $col:'. print_r($col, true). ' --></pre>'. "\n";
		//echo '<pre><!-- $v:'. print_r($v, true). ' --></pre>'. "\n";
//						<option value="<?=$v('Field')? >">Before '<?=$v('Field')? >' (<?=$col? >)</option>
?>						<option<?php if ($col === $last){echo ' selected';}?> value="AFTER <?php echo enquote($v['Field']); ?>">After '<?=$v['Field']?>'<?php if ($col === $last){echo ' ('. t('_default'). ')';}?></option>
<?php
			}
		}
		$options = ob_get_contents();
		ob_clean();
		$values['cpos'] = array(
			'id' => 'cpos',
			'label' => t('_Position'),
			'input_wrap_class' => 'input col-auto',
//			'input_class' => 'big-check',
			'value' => '$cai',
			'type' => 'select',
			'optional' => true,
//			'label_class' => 'right',
			'roptions' => $options,//Select::drawOptions(SQL::COLTYPES, '', $ctype/*isset($_POST['ctype']) ? $_POST['ctype'] : (isset($dt) ? $dt : null)*/),
			'row_class' => 'row g-2 mb-4'
		);
/*							<option disabled><?=l('_Select')?> <?=l('_optional')?></option>
							<!--<option selected disabled><?=l('_Select')?></option>-->
							<option value="FIRST"><?=l('_First')?></option>*/

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
			<div class="row g-2 mb-4">
<?=Field::out($values['cname'])/*
					</div>*/ ?>
			</div>
<?php	}	/*
		if (isset($_POST['do']) && $_POST['do'] !== 'drop') {
?>
			<div class="row">
				<div class="input_field">
					<div class="label col-25">
						<label for="ccen"><?php l('_Change existing name'); ?>:</label>
					</div>
					<div class="input col-20">
						<input type="checkbox" id="ccen" name="ccen"<?php if (isset($_POST['ccen']) && $_POST['ccen']){echo ' checked';}?> data-dest="crename">
					</div>
				</div>
				<div class="input_field">
					<div class="label col-25 disabled">
						<label for="crename"><?php l('_Column new name'); ?>:</label>
					</div>
					<div class="input col-45">
					<!--<input placeholder="<?=l('_alpha-numeric characters')?>" type="text" id="crename" name="crename" value"<?=$_REQUEST['crename']?>" disabled="disabled">-->
						<input placeholder="<?=l('_alpha-numeric characters')?>" type="text" id="crename" name="crename" value"<?=$_POST['crename']?>" disabled<?php / *data-trig="ccen"* / ?>>
					</div>
				</div>
			</div>
<?php
		}	*/
?>
<?php
		if (!isset($_POST['do']) || $_POST['do'] !== 'drop') {
/*			if (isset($_POST['ctype']))
			{
				$value = $_POST['ctype'];
			} elseif (isset($use['Type']))
			{
				$value = strtoupper( strtok( $use['Type'], '(' ) );
			}*/
//			$value = isset($_POST['ctype']) ? $_POST['ctype'] : (isset($use['Type']) ? strtoupper( strtok( $use['Type'], '(' ) ) : '');
?>
			<div class="row g-2 mb-4">
<?php	echo Field::out($values['ctype']);/*
					</div>*/ echo Field::out($values['clen']); /*?>
					</div>*/	?>
				</div>
			<div class="row g-2 mb-4">
<?php	echo Field::out($values['cdef']);/*?>
 */	?>		</div>
<?php	echo Field::out($values['ccol']);/*
<?php	*/	?>
		<div class="row g-2 mb-4">
<?php	echo Field::out($values['cattr']);	?>
<?php	echo Field::out($values['cnul']);	?>
		</div>	<?php	/*
			</div>*/	?>
			<div class="row g-2 mb-4">
<?php	echo Field::out($values['cind']);	?>
<?php	echo Field::out($values['cai']);	?>
			</div><?php	/*	?>
				</div>*/	?>
<?php	echo Field::out($values['ccom']);/*
				</div>*/	?>
<?php	echo Field::out($values['cpos']);/*
				</div>*/	?>
<!-- ... -->
<!--					<div class="row g-2 mb-3">
						<div class="input col-md-3 mx-auto">
							<button type="submit" data-lpignore="true" class="xform-control symbol" id="submit" name="submit"><i class='xfa-light xfa-duotone fa-regular xfa-solid xfa-line-columns fa-circle-plus xfa-square-plus xfa-download fa-2x'></i>&nbsp; <?=isset($submit) ? $submit : t('_Add column'); ?></button>
						</div>
					</div>-->
<?php
		}
		$values['submit'] = array(
			'make_button' => true,
			'id' => 'submit',
			'input_wrap_class' => 'input col-md-3 mx-auto',
			'input_class' => 'submit',
			'value' => t('_Add column'),//Save changes'),
			'type' => 'submit',
			'label_wrap_class' => 'hide xright',
			'row_class' => 'row g-2 mb-3',
			//'inner' => '<i class="fa-regular fa-circle-plus fa-2x"></i> '. "&nbsp;"
			'inner' => '<i class="fa-solid fa-circle-plus fa-2x"></i> '. "&nbsp;"
		);
		echo Field::out($values['submit']);
?>
		</form>
	</div>
<?php
	}
