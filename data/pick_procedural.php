<?php

include_once ('forms.php');

if (isset($_POST['addnew'])) {
	//Redirect( BASE. 'addnew' );
	include ('business/addnew.php');
}

// attach options from types database table to the contact_types in the form
getTypes($forms);

// when the form is submitted, process the fields
	//echo '<pre>form = '. print_r($form, true). '</pre>';
if (isset($_POST['submit'])) {
	foreach ($forms as $form => $vals) {
//		echo '<pre>$arr : '. $arr. '</pre>';
//		echo '<pre>$sect : '. print_r($sect, true). '</pre>';
		if (isset($vals['inner']) && is_array($vals['inner'])) {
			//		echo '<pre>$arr[\'fields\'] : '. print_r($sect/*arr['fields']*/, true). '</pre>';
//			echo 'root fields';
			validateFields($vals['inner']);
		}
		if (isset($vals['fieldsets']) && is_array($vals['fieldsets'])) {
//			echo '<pre>arr[fieldsets] : '. print_r($sect/*arr['fieldsets']*/, true). '</pre>';
			foreach ($vals['fieldsets'] as $fieldset => $val) {
				if (isset($val['inner']) && is_array($val['inner'])) {
//					echo 'in fieldsets fields';
					validateFields($val['inner']);
				}
			}
		}
	}
	//$array = array('business_name', 'email', 'street_address', 'address_line2', 'city', 'province', 'post_code', 'website', /*'date_first',*/ /*'date_updated',*/ 'note', 'active');
	$array = array(
		'business_name' => filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT),
		'email' => filter_input(INPUT_POST, 'email', FILTER_DEFAULT),
		'street_address' => filter_input(INPUT_POST, 'street_address', FILTER_DEFAULT),
		'address_line2' => filter_input(INPUT_POST, 'address_line2', FILTER_DEFAULT),
		'city' => filter_input(INPUT_POST, 'city', FILTER_DEFAULT),
		'province' => filter_input(INPUT_POST, 'province', FILTER_DEFAULT),
		'post_code' => filter_input(INPUT_POST, 'post_code', FILTER_DEFAULT),
		'website' => filter_input(INPUT_POST, 'website', FILTER_DEFAULT),
		/*'date_updated' => filter_input(INPUT_POST, 'date_updated', FILTER_DEFAULT),*/
		'note' => filter_input(INPUT_POST, 'note', FILTER_DEFAULT),
		'active' => filter_input(INPUT_POST, 'active', FILTER_DEFAULT)
	);
	if (!empty(filter_input(INPUT_POST, 'date_first', FILTER_DEFAULT))) {
		$array['date_first'] = filter_input(INPUT_POST, 'date_first', FILTER_DEFAULT);
	}
	$sql = makeSQL('business', 'business_id', $array, filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT));
//	echo 'doing '. $sql. "<br>\n";
	$id = filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT);
	//$msg = strpos($sql, 'INSERT') == 0 ? 'inserted new' : 'updated';
	$msg = is_integer($id) ? 'Inserted new' : 'Updated';
//	echo $msg. ' business: <pre>'. print_r($array, true). '</pre>'. "<br>\n";
	$doneNote = new Note(Array(
		'type' => Note::notice,
		'message' => sprintf( t("_{$msg} business %s"), filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT)),
		'details' => "<br>\n". sprintf( t('_Query %s'), $sql ),
	));
//	echo $note->display();
	try {
		$db->run($sql);//->fetchAll();//PDO::FETCH_ASSOC);
		if (empty(filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT))) {
			$id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'yumatech_organise' AND TABLE_NAME = 'business'")->fetchAll(PDO::FETCH_ASSOC);
			$id = $id[0]['id'];
//			echo 'business_id: '. $id. "<br>\n";
		}
		//1999-12-31 23:59:59
	 	$contacts = filter_input(INPUT_POST, 'contact_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$contact_first = filter_input(INPUT_POST, 'contact_first', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$contact_last = filter_input(INPUT_POST, 'contact_last', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$contact_type = filter_input(INPUT_POST, 'contact_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$contact_date = filter_input(INPUT_POST, 'contact_date', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$row = 0;
		foreach ($contacts as $k => $v) {
			$contact = new BusinessContact;
			$contact->getFromPOST();
			$contact->insert();
//			echo 'contacts '. $k. ': <pre>'. print_r($v, true). "</pre><br>\n";
			$array = array(
				'business_id' => $id,//filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT),
				'first' => $contact_first[$k],
				'last' => $contact_last[$k],
				'type_id' => $contact_type[$k],
				'number' => $contact_number[$k],
			);
			if (!empty($contact_date[$k])) {
				$array['date_created'] = $contact_date[$k];
			}
			$sql = makeSQL('contact', 'contact_id', $array, $contacts[$row]/ *filter_input(INPUT_POST, 'contact_id', FILTER_DEFAULT)* /);
			try {
				$db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
				$doneNote->set(array(
					'message' => $doneNote->getParams()['message']. ', '. sprintf( t("_with contact %s"), $row )
				));
				$doneNote->set(array(
					'details' => $doneNote->getParams()['details']. "<br>\n". sprintf( t('_Query %s'), $sql)
				));
			} catch (PDOException $e) {
				$note = new Note(Array(
					'type' => Note::error,//'error',
					'message' => sprintf( t("_Cannot %s business contact"), (strpos($sql, 'INSERT') === 0) ? 'insert' : 'update'),
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
				));
	//	echo $doneNote->display();
				echo $note->display();
//				return;
			}
			$row++;
		}
		echo $doneNote->display();
		return;
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::error,//'error',
			'message' => t("_Cannot insert/update business"),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
		));
		echo $doneNote->display();
		echo $note->display();
		return;
	}
}

function getTypes(&$forms)
{
	global $db;
	$qry = 'SELECT * FROM `yumatech_organise`.`types`;';
	try {
		$types = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
		$value = array();
		foreach ($types as $k => $v) {
			foreach ($v as $key => $val) {
				$id = $v['type_id'];
				$value[$id] = $v['name'];
			}
		}
		insertInArray($forms, 'contact_type', $value, 'options');
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::error,//'error',
			'message' => t("_Cannot get list of business contact types"),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $qry ),
		));
		echo $note->display();
	}
}

$errors = array();

/**
 * Perform basic validation for fields
 */
function validateFields($fields)
{
	//	echo '<pre>processField($fields) : '. print_r($fields, true). '</pre>';
	foreach ($fields as $k => $v) {
	//	echo '<pre>processField($fields-$k) : '. print_r($k, true). '</pre>';
	//	echo '<pre>processField($fields-$v) : '. print_r($v, true). '</pre>';
		if (isset($v['name'])) {
	//		echo 'validating : '. $v['name']. ' :'. "<br>\n";
			if (isset($v['required']) && $v['required']) {
	//			echo 'validating : '. $v['name']. ' :'. "\n";
				if (isset($v['description'])) {
					$miss = $v['description'];
				}
				if (!isset($miss) && isset($v['label'])) {
					$miss = $v['label'];
				}
				if (!isset($miss) && isset($v['name'])) {
					$miss = $v['name'];
				}
				if (isset($miss)) {
					$errors[] = "Missing '{$miss}'";
				}
			}
		}
	}
}

/**
 *
 */
function makeSQL(string $tbl, string $idName, array $els, $id=null, $row=null)
{
	if (!isset($id)) {
		echo 'no id';
		$id = filter_input(INPUT_POST, $idName);//'business_id');
	}
	global $db;
	$qry = 'DESCRIBE `yumatech_organise`.'. $tbl;
	try {
		$descs = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
		$numeric = array(	"TINYINT",
							"SMALLINT",
							"MEDIUMINT",
							"INT",
							"BIGINT",
							"DECIMAL",
							"FLOAT",
							"DOUBLE",
							"REAL",
							"BIT",
							"BOOLEAN",
							"SERIAL",
						);/*
		$dateTime = array(
							"DATE",
							"DATETIME",
							"title": "A timestamp, range is 1970-01-01 00:00:01 UTC to 2038-01-09 03:14:07 UTC, stored as the number of seconds since the epoch (1970-01-01 00:00:00 UTC)",
							"TIMESTAMP",
							"title": "A time, range is -838:59:59 to 838:59:59",
							"TIME",
							"title": "A year in four-digit (4, default) or two-digit (2) format, the allowable values are 70 (1970) to 69 (2069) or 1901 to 2155 and 0000",
							"YEAR",
						);
		$strings = array(
							"CHAR",
							"VARCHAR",
							"TINYTEXT",
							"TEXT",
							"MEDIUMTEXT",
							"LONGTEXT",
							"BINARY",
							"VARBINARY",
							"TINYBLOB",
							"BLOB",
							"MEDIUMBLOB",
							"LONGBLOB",
							"ENUM",
							"SET",
						);
		$spatial = array(
							"GEOMETRY",
							"POINT",
							"LINESTRING",
							"POLYGON",
							"MULTIPOINT",
							"MULTILINESTRING",
							"MULTIPOLYGON",
							"GEOMETRYCOLLECTION",
						);,
		$json = array(	"JSON"
		);*/
		if (is_array($descs)) {
//			echo '<pre>fields description : '. print_r ($descs, true). '</pre'. "<br>\n";
		}
		if ($id || $id === 0 || is_array($id)) {
			$sql = 'UPDATE `yumatech_organise`.'. $tbl. ' SET ';
			foreach ($els as $k => $el) {
				$sql .= "`". trim($k). "`";
				foreach ($descs as $f) {
					if ($f['Field'] === $k/*$el*/) {
						$type = strtoupper( strtok( trim($f['Type']), '(' ) );
//							echo ')'. $k. ':'. $type. '('. "<br>\n";
						if (in_array($type, $numeric)) {
							if (!isset($row)) {
								$value = $el;//dbValue($el);
								if (empty($value))
									$value = 0;
							} else {
								$value = $el[$row];//dbValue($el, $row);
								if (empty($value))
									$value = 0;
							}
							if (!$value && strtoupper($f['Null']) != 'NO') {
								$value = 'null';
							}
	//					} elseif ($type == 'DATETIME' && $f['Default'] == 'current_timestamp()') {
						} elseif (isset($row) && isset($el[$row])) {
							$value = "'$el[row]'";
						} else {
							$value = "'$el'";//dbValue($el);
						}
						$sql .= "={$value}, ";
					}
				}
			}
			$sql = substr($sql, 0, -2). ' WHERE '. $idName. '='. $id. ';';
		} else {
			$sql = 'INSERT INTO `yumatech_organise`.'. $tbl. ' (';
			foreach (array_keys($els) as $el) {
				$sql .= "`". trim($el). "`, ";
			}
			$sql = substr($sql, 0, -2). ') VALUES (';
			foreach ($els as $el => $v) {
//				echo 'processing "'. $el. "\"<br>\n";
				foreach ($descs as $f) {
					//echo 'trying '. print_r($f, true). "<br>\n";
//					echo 'trying "'. $f['Field']. "\"<br>\n";
					//if (isset(key($el)) && !empty(key($el))) {
					if ($el == $f['Field']) {
//						echo 'processing '. $el. "<br>\n";
						$type = strtoupper( strtok( trim($f['Type']), '(' ) );
//						echo ')'. $el. ':'. $type. '('. "<br>\n";
						if (in_array($type, $numeric)) {
//							echo $el. ' is numeric'. "<br>\n";
							if (isset($row) && isset($v[$row])) {
								$value = $v[$row];//dbValue($el, $row);
//								echo $el. ' using row: '. $row. ', value is '. $value. "<br>\n";
								if (empty($value))
									$value = 0;
							} else {
								$value = $v;//dbValue($el);
//								echo $el. ' not using row value is '. $value. "<br>\n";
								if (empty($value))
									$value = 0;
							}
/*						} elseif ($type == 'DATETIME') {// && $f['Default'] == 'current_timestamp()') {
							if ($f['Default'] == 'current_timestamp()') {
//								$value = '\'NOW()\'';
								unset($els[$el]);
								continue;
							} else {
								$value = "'$v'";//dbValue($el);
								if (empty($value))
									$value = "'0000-00-00 00:00:00'";
							}*/
						} elseif (isset($row) && isset($v[$row])) {
							$value = "'$v[$row]'";//dbValue($el);
						} else {
							$value = "'$v'";//dbValue($el);
//							echo $el. ' value is '. $value. "<br>\n";
							if (empty($value))
								$value = '\'\'';//0;
						}
						if (!$value && strtoupper($f['Null']) != 'NO') {
							$value = 'null';
						}
						$sql .= "{$value}, ";
						break;
					}
				}
			}
			$sql = substr($sql, 0, -2). ')';
		}
		return $sql;
//		echo '<pre>field description : '. print_r ($f, true). '</pre'. "<br>\n";
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::error,//'error',
			'message' => sprintf( t("_Cannot get description of %s"), $tbl ),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $qry ),
		));
		echo $note->display();
	}
}


/***********************************************/
/**
 * Output the from(s) to the screen,
 * using the value in the global array
 */
/***********************************************/
	Scripts::getInstance()->enqueue(Array( 'name' => 'autocomplete2', 'src' => BASE. 'js/autocomplete2.js', 'version' => filemtime('js/autocomplete2.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'MyAjax', 'src' => BASE. 'js/MyAjax.js', 'version' => filemtime('js/MyAjax.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'MakeCopy', 'src' => BASE. 'js/MakeCopy.js', 'version' => filemtime('js/MakeCopy.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'resetForm', 'src' => BASE. 'js/resetForm.js', 'version' => filemtime('js/resetForm.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'business', 'src' => BASE. 'js/business.js', 'version' => filemtime('js/business.js') ));
	//Link::style(Array( 'name' => 'autocomplete', 'href' => BASE. '../business/autocomplete.css', 'version' => filemtime('../business/autocomplete.css') ));
	Link::style(Array( 'name' => 'business', 'href' => BASE. 'css/business.css', 'version' => filemtime('css/business.css') ));
//	Link::style(Array( 'name' => 'autocomplete', 'href' => BASE. 'css/autocomplete.css', 'version' => filemtime('css/autocomplete.css') ));
	//Styles::getInstance()->enqueue(Array( 'name' => 'autocomplete', 'href' => BASE. 'css/autocomplete.css', 'version' => filemtime('css/autocomplete.css') ));
	/*echo Breadcrumbs::here();*/	?>
<!--<head>
	<link rel="stylesheet" href="basic.css" type="text/css">
</head>-->
<?php
/**
 * Draw form
 */
	if (isset($_POST['submit'])) {
		echo '<div class="errors">'. "\n";
		foreach ($errors as $err) {
			echo "\t<p class=\"error\">{$err}</p>\n";
		} 
		echo "</div>\n";
	}
	foreach ($forms as $num => $form) {
//			echo '<pre>form : '. print_r($form/*arr['fieldsets']*/, true). '</pre>';
?>	<form id="<?php echo isset($form['id']) ? $form['id'] : 'form_'. ($num+1);?>"<?php
//		if (isset($form['id'])) {
//			echo ' id="'. $form['id']. '"';
//		}
		if (isset($form['method'])) {
			echo ' method="'. $form['method']. '"';
		}
		if (isset($form['class'])) {
			echo ' class="'. $form['class']. '"';
		}
		if (isset($form['action'])) {
			echo ' action="'. $form['action']. '"';
		}
		echo ">\n";
		renderFields($form['inner']);
?>	</form>
<?php
	}//end foreach form

	function renderInner(array $node)
	{
//		echo 'renderInner :';
//		echo '  : <pre>'. print_r($node, true). '</pre>';
		if (isset($node['id']) && !isset($node['inner'])) {
			echo Field::out($node);
		} elseif (isset($node['tag'])) {
			renderTag($node);
		} else {
//		echo ' -not a field : <pre>'. key($node). ':'. print_r($node, true). '</pre>';
			foreach ($node as $k => $v) {
//		echo ' -not a field : <pre>'. print_r($v, true). '</pre>';
				if (is_array($v)) {
//					echo ' v is an array';
//					renderInner($v);
					if (isset($v['tag'])) {
//						echo ' next level is a tag';
						renderTag($v);
					} elseif (isset($v['id'])) {
//						echo ' next level is a field';
						echo Field::out($v);
					}
				} else {
//					echo ' -not an array : <pre>'. print_r($v, true). '</pre>';
				}
			}
		}
	}

	function renderTag(array $node)
	{
		if (!isset($node['tag'])) {
			echo 'called renderTag without "tag" in <pre>'. print_r($node, true). '</pre>';
			return;
		}
//		echo 'renderTag :';
		if ($node['tag'] == 'fieldset') {
//			echo ' fieldset';
			if (isset($node['inner'])) {
//				echo ' with inner';
				echo Fieldset::open($node);
				//foreach ($node['inner'] as $k => $v) {
				foreach ($node as $k => $v) {
					if (is_array($v)) {
						//if ((!isset($v[0]) || !is_array($v[0]))) {
						if (isset($v['tag']) && (!isset($v[0]) || !is_array($v[0]))) {
//							echo 'calling renderTag:<pre>'. $k. ':'. print_r($v, true). '</pre>'. "<br>\n";
							renderTag($v);
						} else {
							renderInner($v);
						}
					}
				}
				if (!isset($node['selfClose'])) {
					echo Fieldset::close();
				}
			}
		} else {
//		echo ' -not a fieldset : <pre>'. $node['tag']. ' : '. print_r($node, true). '</pre>';
			echo '<'. $node['tag'];
			$skipKeys = array('tag', 'selfClose', 'innerText', 'inner');
			foreach ($node as $k => $v) {
				if (!in_array($k, $skipKeys)) {
					echo " {$k}";
					if (is_string($v) || is_integer($v)) {
						echo '="'. $v. '"';
					}
				}
			}
/*			if (isset($node['id'])) {
				echo ' id="'. $node['id']. '"';
			}
			if (isset($node['class'])) {
				echo ' class="'. $node['class']. '"';
			}
			if (isset($node['value'])) {
				echo ' value="'. $node['value']. '"';// ['inner']
			}*/
			echo '>';
			if (isset($node['innerText'])) {
				echo $node['innerText'];
			}
			if (isset($node['inner']) /*&& !isset($node['selfClose'])*/) {
//				echo ' tag has inner';
				foreach ($node['inner'] as $k => $v) {
					renderInner($v);
				}
			}
			if (isset($node['selfClose']) && $node['selfClose']) {
				echo '</'. $node['tag']. '>';
			}
		}
	}

	function recurseAll($node) {
	    if (is_array($node)) {
			if (isset($node['tag'])) {
				renderTag($node);
			} elseif (isset($node['id'])) {
				echo Field::out($node);
			} else
			foreach ($node as &$childNode) {
	            recurseAll($childNode);
	        }
	    }
	}

	function renderFields($arr)
	{
		$toClose = array();
		$i = 0;
		recurseAll($arr);
	}
