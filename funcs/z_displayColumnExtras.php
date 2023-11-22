<?php
namespace Yuma;

//use Yuma\HTML;

function ajax_displayColumnExtras()
{
	//$dbname = isset($_GET['database']) ? $_GET['database'] : 'bf_aaaa';
	//$dbname = isset($_REQUEST['database']) ? $_REQUEST['database'] : 'bf_aaaa';
	$dbname = isset($_REQUEST['db']) ? $_REQUEST['db'] : 'bf_aaaa';
	//$table = ( isset($_GET['table']) ? $_GET['table'] : isset($_GET['form']) ) ? $_GET['form'] : 'Bbbb';
	//$table = ( isset($_REQUEST['table']) ? $_REQUEST['table'] : isset($_REQUEST['form']) ) ? $_REQUEST['form'] : 'Bbbb';
	$table = ( isset($_REQUEST['tbl']) ? $_REQUEST['tbl'] /*: isset($_REQUEST['form']) ) ? $_REQUEST['form']*/ : 'Bbbb' );
	$a = isset($_REQUEST['col']) ? $_REQUEST['col'] : 0;
	//$qry = 'DESC '. $dbname. '.'. $table;
	//return $qry;
	$qry = 'SHOW FULL COLUMNS FROM '. $dbname. '.'. $table;
/*	SHOW FULL COLUMNS FROM bf_aaaa.Bbbb;
+-------+-----------------+-----------+------+-----+---------+----------------+---------------------------------+------------+
| Field | Type            | Collation | Null | Key | Default | Extra          | Privileges                      | Comment    |
+-------+-----------------+-----------+------+-----+---------+----------------+---------------------------------+------------+
| id    | int(6) unsigned | NULL      | NO   | PRI | NULL    | auto_increment | select,insert,update,references | hello test |
| Gyhuo | int(11)         | NULL      | NO   |     | NULL    |                | select,insert,update,references |            |
+-------+-----------------+-----------+------+-----+---------+----------------+---------------------------------+------------+*/
	global $db;
	try {
		$columns = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
		if (count($columns) > 4095)
		{
			$note = new Note(Array(
				'type' => 'error',
				'message' => t("_Cannot have more than 4095 columns"),
				//'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
			));
			echo $note->display();
		} else
		{
	/*		$results = array();
			foreach ($columns as $num => $row) {
				$name = $row['Field'];
				$extra = $row['Extra'];
				$results[$name] = $extra;
			}*/
			//$prefix = (!empty($columns[$a]['Field']) ? $columns[$a]['Field'] : ''). ' '. $columns[$a]['Field']. ' ';
			$prefix = '`'. $columns[$a]['Field']. '` ';
			$prefix .= strtoupper($columns[$a]['Type']);
			$prefix .= strtoupper($columns[$a]['Null']) == 'YES' ? ' NULL' : '';
			//$columns[$a]['Key']. ' ';
			$prefix .= !empty($columns[$a]['Default']) ? ' '. $columns[$a]['Default'] : '';
			$results = $columns[$a];//['Extra'];
			//echo print_r($results, true);
			//return display_column_extras( $a, $prefix, $results );
			echo display_column_extras( $a, $prefix, $results, $dbname, $table );
			exit();
		}
	/*	$qry = 'INSERT INTO buildForms.recent (name) VALUES ('. squote($dbname). ')';
		try {
			$db->run($qry);
		} catch (PDOException $e) {
		}*/
	/*	$qry = 'SHOW TABLES';
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
}

function getRegExStr($name, $str)
{									
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
LOD;
	$pattern .= strip_tags($name);	## main pattern
	$pattern .= <<<'LOD2'
(?| " (?<param1> \g<dqc> ) " | ' (?<param1> \g<sqc> ) ' )
# note that once you define a named group in the first branch in a branch reset
# group, you don't have to include the name in other branches:
# (?| " (?<param1> \g<dgc>) " | ' ( \g<sqc> ) ' ) does the same. Even if the
# second branch succeeds, the capture group will be named as in the first branch.
# Only the order of groups is taken in account.
(?:, \s* (?<param2> \g<var> ) )?
.*$
~xsi
LOD2;
	preg_match_all($pattern, $str, $matches);
	return $matches;
}

//function ajax_displayColumnExtras($a, $prefix, $val) {
function display_column_extras($a, $prefix, $val, $dbname, $table) {
//	require __DIR__. "/../config.php";
	//$action = $_SERV['PHP_SELF'];
	$action = BASE. "database/{$dbname}/table/{$table}/columns";
	$value = (isset($_POST['Extra']) && !empty($_POST['Extra'])) ? $_POST['Extra'] : $val;
/*	?><!-- $value => <?=print_r($value, true)?> --><?php	*/
	/*<!-- $value => Array
(
    [Field] => id
    [Type] => int(6) unsigned
    [Collation] => 
    [Null] => NO
    [Key] => PRI
    [Default] => 
    [Extra] => auto_increment
    [Privileges] => select,insert,update,references
    [Comment] => hello test
)
-->*/
//	if (!is_array($value) || (isset($_POST['Extra']) && !empty($_POST['Extra']) && !is_array($_POST['Extra'])))
//	{
//		$value = strtoupper((isset($_POST['Extra']) && !empty($_POST['Extra'])) ? $_POST['Extra'] : $val);
		$ai = isset($value['Extra']) && stripos($value['Extra'], 'auto_increment') !== false;
/*	SHOW FULL COLUMNS FROM bf_aaaa.Bbbb;
+-------+-----------------+-----------+------+-----+---------+----------------+---------------------------------+------------+
| Field | Type            | Collation | Null | Key | Default | Extra          | Privileges                      | Comment    |
+-------+-----------------+-----------+------+-----+---------+----------------+---------------------------------+------------+
| id    | int(6) unsigned | NULL      | NO   | PRI | NULL    | auto_increment | select,insert,update,references | hello test |
| Gyhuo | int(11)         | NULL      | NO   |     | NULL    |                | select,insert,update,references |            |
+-------+-----------------+-----------+------+-----+---------+----------------+---------------------------------+------------+*/
		$privileges = (isset($value['Privileges']) && !empty($value['Privileges'])) ? implode(', ', explode(',', $value['Privileges'])) : '';

		$comment = '';
		if (is_array($value['Extra']) && isset($value['Extra']['comm']))
			$comment = $value['Extra']['comm'];
		elseif (isset($value['Comment']) && !empty($value['Comment']))
			$comment = $value['Comment'];
/*		elseif ($value && stripos($value, 'comment') !== false)
		{
//			$start = stripos($value, 'comment');
			//$str = substr($value, $start+6, strrpos($value, '"'
	//		$pattern = '#comment\s["\']([^"\']*)["\'].*$#i';
			//$pattern = '~__\((["\'])(?<param1>(?>[^"\'\\\]+|\\\.|(?!\1)["\'])*)\1(?:,\s*(?<param2>\$[a-z0-9_-]+))?\);~si';
			$comment = getRegExStr('comment', $value);
			echo '<h4 id="comm"> comment '. print_r($comment, true). ' </h4>';
			//preg_match_all('/".*?"|\'.*?\'/', $value, $matches);
		}*/
//		if (isset($_POST['comm']))
//			$comment = $_POST['comm'];
/*		$charset = '';
		if (is_array($value) && isset($value['coll']) && stripos($value['coll'], '_') !== false) {
			$c = explode('_', $value['coll']);
			if (is_array($c) && isset($c[0]))
				$charset = $c[0];
		} elseif ($value && stripos($value, 'CHARACTER SET') !== false)
		{
//			$start = mb_str('substr', 0, mb_str('stripos', $value)+1);
			//$collation = mb_str('substr', $start, mb_str('strlen', $value));
			$charset = getRegExStr('CHARACTER SET', $value);
		}*/
		$collation = 'NULL';//'';
		//if (is_array($value) && isset($value['coll']))
		if (is_array($value['Extra']) && isset($value['Extra']['coll']))
			$collation = $value['Extra']['coll'];
		elseif (isset($value['Collation']) && !empty($value['Collation']))
			$collation = $value['Collation'];
/*		elseif ($value && stripos($value, 'collate') !== false)
		{
		//	$start = mb_str('substr', 0, mb_str('stripos', $value)+1);
			//$collation = mb_str('substr', $start, mb_str('strlen', $value));
			$collation = getRegExStr('collate', $value);
		}*/
		$virt = '';	$virte = '';
	//ALTER TABLE `Bbbb` CHANGE `id` `id` INT(6) UNSIGNED AS (dert) VIRTUAL COMMENT 'hello test';
	//ALTER TABLE `Bbbb` CHANGE `id` `id` INT(6) UNSIGNED AS (dert) PERSISTENT COMMENT 'hello test';
	//ALTER TABLE `Bbbb` CHANGE `id` `id` INT(6) UNSIGNED AS (dert) STORED COMMENT 'hello test';
		if (is_array($value['Extra']) && isset($value['Extra']['virte']))
			$virte = $value['Extra']['virte'];
		if (is_array($value['Extra']) && isset($value['Extra']['virt']))
			$virt = $value['Extra']['virt'];
		elseif ($value['Extra'] && stripos($value['Extra'], 'AS') !== false)
		{
			$virt = preg_match('/AS (.*) (VIRUALITY|PERSISTENT|STORED) /', $value['Extra'], $matches);
			if (is_array($matches) && isset($matches[2]))
				$virt = $matches[2];
			if (!empty($virt) /*&& isset($matches[1])*/)
				$virte = $matches[1];
		}
		$MIME = '';
		if (is_array($value['Extra']) && isset($value['Extra']['MIME']))
			$MIME = $value['Extra']['MIME'];
		elseif ($value['Extra'] && stripos($value['Extra'], 'MIME') !== false)
		{
			$MIME = getRegExStr('MIME', $value['Extra']);
		}
/*		$browserDisTrans = '';
		if (is_array($value) && isset($value['BROWSERDISTRANS']))
			$browserDisTrans = $value['BROWSERDISTRANS'];
		elseif ($value && stripos($value, 'BROWSERDISTRANS') !== false)
		{
			$browserDisTrans = getRegExStr('BROWSERDISTRANS', $value);
		}*/
/*		if (strpos($value, ' ') !== false)
		{
			//$value = explode(' COMMENT', $value);
			$value = explode(' ', $value);
		}*/
/*		if (strpos($value, ',') !== false)
		{
			$value = explode(',', $value);
			if (!isset($value[1]))
				$value = $value[0];*/
//	} else {
//		$value = $val;
//	}
	$values['ai'] = array(
		'id' => "auto-inc". $a,
		'name' => 'col['. $a. '][Extra][ai]',
		'label' => SQL::COLEXTRAS[0]['text'],//t('_Auto-Increment'),
		'after' => SQL::COLEXTRAS[0]['title'],//t('_Auto-Increament'),
		'label_title' => SQL::COLEXTRAS[0]['title'],//t('_Auto-Increament'),
		'value' => ($ai),
		'input_title' => sprintf( t('_was %s'), ($ai ? t('_YES') : t('_NO')) ),
		'row_class' => 'row g-2 mb-4 form-switch',
		'label_class' => 'right',
		'input_class' => 'big-check',
		'type' => 'checkbox',
		'extra' => 'form="columns"',
//		'optional' => true,
//		'show_optional' => false
	);
	$values['ai2'] = array(
		'type' => 'hidden',
		'extra' => 'form="columns"',
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
		'input_wrap_class' => 'input col',// xcol-md-6 xcol-md-9',
		'type' => 'textarea',
		'extra' => 'form="columns" max="1024"'
//		'optional' => true,
//		'show_optional' => false
	);
	$values['comm2'] = array(
		'type' => 'hidden',
		'extra' => 'form="columns"',
		'name' => 'col['. $a. '][WAS_Extra][comm]',
		'value' => $comment
	);
	$values['coll'] = array(
		'id' => "collation". $a,
		'name' => 'col['. $a. '][Extra][coll]',
		'label' => isset(SQL::COLEXTRAS[2]['text']) && SQL::COLEXTRAS[2]['text'] ? SQL::COLEXTRAS[2]['text'] : t('_Collation'),
		'label_title' => SQL::COLEXTRAS[2]['title'],
		'value' => $collation,
		'input_title' => !empty($collation) ? sprintf( t('_was %s'), $collation ) : '',
		'row_class' => 'row g-2 mb-4',
		'label_class' => 'right',
		'input_class' => 'collation-Select',
		//'input_wrap_class' => 'input col',//-md-3',
		'input_wrap_class' => 'input col-md-4',
		'type' => 'select',
		'roptions' => '<option value="" title="'. t('_no specific value'). '">'. t('_inherit'). '</option>'. (!empty($collation) ? Select::drawOptions(SQL::COLLATION, '', $collation) : Select::drawOptions(SQL::COLLATION, t('_inherit'))),
		'extra' => 'form="columns"',
		'optional' => true,
		'show_optional' => false
	);
	$values['coll2'] = array(
		'type' => 'hidden',
		'extra' => 'form="columns"',
		'name' => 'col['. $a. '][WAS_Extra][coll]',
		'value' => $collation
	);
	$values['virt'] = array(	// Virtuality
		'id' => "virt". $a,
		'name' => 'col['. $a. '][Extra][virt]',
		'label' => SQL::COLEXTRAS[3]['text'],
		//'label' => t('_Viruality'),
		'label_title' => SQL::COLEXTRAS[3]['title'],
		//'label_title' => t('_Viruality'),
		'value' => $virt,
		'input_title' => !empty($virt) ? sprintf( t('_was %s'), $virt ) : '',
		//'row_class' => 'row g-2',
		'row_class' => 'row g-2 mb-4',
		'label_class' => 'right',
//		'input_class' => 'data-trig="#virte"',
		//'input_wrap_class' => 'input col-md-3',
		'input_wrap_class' => 'input col-md-2',
		'type' => 'select',
		'roptions' => Select::drawOptions(SQL::VIRUALITY, '', $virt),
		'optional' => true,
		'show_optional' => false,
		'extra' => 'form="columns" data-endisTrig="#virte"',
		'input2' => array(
//			$id = isset($details['input2']['id']) ? $details['input2']['id'] : $details['id']. '2';
//                 $name = isset($details['input2']['name']) ? $details['input2']['name'] : $details['id']. '2';
//                 $type = isset($details['input2']['type']) ? $details['input2']['type'] : 'text';
//                 $extra = isset($details['input2']['extra']) ? $details['input2']['extra'] : null;
//                 $inputClass
			'id' => "virte". $a,
			'name' => 'col['. $a. '][Extra][virte]',
		//'label' => SQL::COLEXTRAS[2]['text'],
//		'label' => t('_Expression'),
//		'label_title' => SQL::COLEXTRAS[3]['placeholder'],
		//'label_title' => t('_Expression'),
//		'label_class' => 'right',
			'value' => $virte,
		'input_title' => !empty($virte) ? sprintf( t('_was %s'), $virte ) : '',
//		'row_class' => 'row g-2 mb-4',
			'input_class' => 'disable',
		//'input_wrap_class' => 'input col',//-md-3',
			'input_wrap_class' => 'input col-md-4',
			'placeholder' => t('_Expression'). ' '. SQL::COLEXTRAS[3]['placeholder'],//t("Expression"),
			'extra' => 'form="columns"',
//		'extra' => 'columns',
//		'type' => 'select',
//		'roptions' => Select::drawOptions(SQL::VIRUALITY, '', $val),
		'optional' => true,
		'show_optional' => false
		)
	);
	$values['virt2'] = array(
		'type' => 'hidden',
		'extra' => 'form="columns"',
		'name' => 'col['. $a. '][WAS_Extra][virt]',
		'value' => $virt
	);
	//<input type="text" id="Default-input0" data-name="Default0[]" name="col[0][Default][]" class="form-control disable" placeholder="Default value" disabled="">
/*	$values['virte'] = array(
		'id' => "virte". $a,
		'name' => 'col['. $a. '][Extra][virte]',
		//'label' => SQL::COLEXTRAS[2]['text'],
		'label' => t('_Expression'),
		'label_title' => SQL::COLEXTRAS[3]['placeholder'],
		//'label_title' => t('_Expression'),
		'label_class' => 'right',
		'value' => $virte,
		'input_title' => !empty($virte) ? sprintf( t('_was %s'), $virte ) : '',
		'row_class' => 'row g-2 mb-4',
		'input_class' => 'disable',
		'input_wrap_class' => 'input col',//-md-3',
		'placeholder' => SQL::COLEXTRAS[3]['placeholder'],//t("Expression"),
		'extra' => 'form="columns"',
//		'type' => 'select',
//		'roptions' => Select::drawOptions(SQL::VIRUALITY, '', $val),
		'optional' => true,
		'show_optional' => false
	);
	$values['virte2'] = array(
		'type' => 'hidden',
		'extra' => 'form="columns"',
		'name' => 'col['. $a. '][WAS_Extra][virte]',
		'value' => $virte
	);*/
	//move
/*	$values['MIME'] = array(	// media (MIME) type MIME
		'id' => "MIME". $a,
		'name' => 'col['. $a. '][Extra][MIME]',
		//'label' => SQL::COLEXTRAS[2]['text'],
		'label' => t('_Media type'),
		//'label_title' => SQL::COLEXTRAS[2]['title'],
		'label_title' => t('_Media type'),
		'label_class' => 'right',
		'value' => $MIME,
		'input_title' => !empty($MIME) ? sprintf( t('_was %s'), $MIME ) : '',
		'row_class' => 'row g-2 mb-4',
		'input_class' => 'disable',
		'input_wrap_class' => 'input col-md-3',
		'type' => 'select',
		'roptions' => Select::drawOptions(SQL::MIME, '', $MIME),
		'optional' => true,
		'show_optional' => false
	);
	$values['MIME2'] = array(
		'type' => 'hidden',
		'name' => 'col['. $a. '][WAS_Extra][MIME]',
		'value' => $MIME
	);*/
/*	$values['BROWSERDISTRANS'] = array(	// Browser display transformation
		'id' => "BROWSERDISTRANS". $a,
		'name' => 'col['. $a. '][Extra][BROWSERDISTRANS]',
		//'label' => SQL::COLEXTRAS[2]['text'],
		'label' => t('_Browser display'),
		//'label_title' => SQL::COLEXTRAS[2]['title'],
		'label_title' => t('_Browser display transformation'),
		'label_class' => 'right',
		'value' => $browserDisTrans,
		'input_title' => !empty($browserDisTrans) ? sprintf( t('_was %s'), $browserDisTrans ) : '',
		'row_class' => 'row g-2 mb-4',
		'input_class' => 'disable',
		'input_wrap_class' => 'input col-md-3',
		'type' => 'select',
		'roptions' => Select::drawOptions(SQL::BROWSERDISTRANS, '', $browserDisTrans),
		'optional' => true,
		'show_optional' => false
	);
	$values['BROWSERDISTRANS2'] = array(
		'type' => 'hidden',
		'name' => 'col['. $a. '][WAS_Extra][BROWSERDISTRANS]',
		'value' => $browserDisTrans
	);*/
/*	//Input transformation
	$values['MIMEuu'] = array(	// media (MIME) type MIME
		'id' => "MIMEuu". $a,
		'name' => 'col['. $a. '][Extra][MIMEuu]',
		//'label' => SQL::COLEXTRAS[2]['text'],
		'label' => t('_Media type'),
		//'label_title' => SQL::COLEXTRAS[2]['title'],
		'label_title' => t('_Media type'),
		'label_class' => 'right',
		'value' => $val,//$MIME,
		'input_title' => !empty($val) ? sprintf( t('_was %s'), $val ) : '',
		'row_class' => 'row g-2 mb-4',
		'input_class' => 'disable',
		'input_wrap_class' => 'input col-md-3',
		'type' => 'select',
		'roptions' => Select::drawOptions(SQL::MIME, '', $val),
		'optional' => true,
		'show_optional' => false
	);
	$values['MIME2uu'] = array(
		'type' => 'hidden',
		'name' => 'col['. $a. '][WAS_Extra][MIME2uu]',
		'value' => $value
	);
	//Input transformation options
	$values['MIMEuuu'] = array(	// media (MIME) type MIME
		'id' => "MIMEuuu". $a,
		'name' => 'col['. $a. '][Extra][MIMEuuu]',
		//'label' => SQL::COLEXTRAS[2]['text'],
		'label' => t('_Media type'),
		//'label_title' => SQL::COLEXTRAS[2]['title'],
		'label_title' => t('_Media type'),
		'label_class' => 'right',
		'value' => $val,//$MIME,
		'input_title' => !empty($val) ? sprintf( t('_was %s'), $val ) : '',
		'row_class' => 'row g-2 mb-4',
		'input_class' => 'disable',
		'input_wrap_class' => 'input col-md-3',
		'type' => 'select',
		'roptions' => Select::drawOptions(SQL::MIME, '', $val),
		'optional' => true,
		'show_optional' => false
	);
	$values['MIME2uuu'] = array(
		'type' => 'hidden',
		'name' => 'col['. $a. '][WAS_Extra][MIME2uuu]',
		'value' => $value
	);*/
	$values['submitAJAX'] = array(	// submit button
		'id' => "submitAJAX",
		'name' => 'save',//'col['. $a. '][Extra][MIMEuuu]',
		//'label' => SQL::COLEXTRAS[2]['text'],
		'no_label' => true,//t('_Media type'),
		//'label_title' => SQL::COLEXTRAS[2]['title'],
//		'label_title' => t('_Media type'),
//		'label_class' => 'right',
		'value' => t('Save'),//$val,//$MIME,
//		'input_title' => !empty($val) ? sprintf( t('_was %s'), $val ) : '',
		'row_class' => 'row g-2 mb-4',
//		'input_class' => 'disable',
//		'input_wrap_class' => 'input col-md-3',
		'type' => 'submit',
//		'roptions' => Select::drawOptions(SQL::MIME, '', $val),
//		'optional' => true,
//		'show_optional' => false
	);
	ob_start();
/*	?><form id="extra_<?=$a?>" class="extra" method="POST" action="<?=$action?>" data-col="<?=$a?>" data-prefix="<?=$prefix?>"><?php	*/
	/*	?><form id="extra_<?=$a?>" class="extra" method="POST" data-col="<?=$a?>" data-prefix="<?=$prefix?>"><?php	*/
	?><div class="container-fluid"><?php
	print '<fieldset>'. Field::out($values['ai']). '</fieldset>'. Field::out($values['ai2']);
	print '<fieldset>'. Field::out($values['comm']). '</fieldset>'. Field::out($values['comm2']);
	print '<fieldset>'. Field::out($values['coll']). '</fieldset>'. Field::out($values['coll2']);
	print '<fieldset>'. Field::out($values['virt']). '</fieldset>'. Field::out($values['virt2']);
	if ($privileges)
		print '<div class="row g-2 mb-4"> <div class="label col-md-3"> <label>'. t('_Privileges'). '</label> </div> <div class="label col"> <span data-bs-toggle="tooltip" title="'. t('_readonly'). '">'. $privileges. '</span> </div> </div>';
	//print '<fieldset>'. Field::out($values['virt']). Field::out($values['virte']). '</fieldset>'. Field::out($values['virt2']). Field::out($values['virte2']);
//	print Field::out($values['submitAJAX']);
	/*	print '<fieldset>'. Field::out($values['MIME']). '</fieldset>'. Field::out($values['MIME2']);*/
	/*	print '<fieldset>'. Field::out($values['BROWSERDISTRANS']). '</fieldset>'. Field::out($values['BROWSERDISTRANS2']);*/
	/*?></form><?php	*/
	?></div><?php
	$out = ob_get_contents();
	ob_clean();
	return $out;
}
		
add_hook('ajax_displayColumnExtras', 'ajax_displayColumnExtras');
