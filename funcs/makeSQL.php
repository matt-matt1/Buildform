<?php
namespace Yuma;

/**
 *
 */
function makeSQL(string $tbl, string $idName, array $els, $id=null, $row=null)
{
	if (!isset($id)) {
//		echo 'no id';
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
						);
		$dateTime = array(
							"DATE",
							"DATETIME",
							//"title": "A timestamp, range is 1970-01-01 00:00:01 UTC to 2038-01-09 03:14:07 UTC, stored as the number of seconds since the epoch (1970-01-01 00:00:00 UTC)",
							"TIMESTAMP",
							//"title": "A time, range is -838:59:59 to 838:59:59",
							"TIME",
							//"title": "A year in four-digit (4, default) or two-digit (2) format, the allowable values are 70 (1970) to 69 (2069) or 1901 to 2155 and 0000",
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
						);
		$json = array(	"JSON"
		);
		if (is_array($descs)) {
//			echo '<pre>fields description : '. print_r ($descs, true). '</pre'. "<br>\n";
		}
		if ($id || $id === 0 || is_array($id)) {
			$sql = 'UPDATE `yumatech_organise`.'. $tbl. ' SET ';
			foreach ($els as $k => $el) {
				if (!in_array($k, array_column($descs, 'Field'))) {		//// QUICK FIX for keys that have no description
					continue;
				}
				if (in_array(strtoupper($el), array_merge($numeric, $dateTime, $strings, $spatial, $json))) {
					continue;		// skip where value is default
				}
				$sql .= "`". trim($k). "`";
				foreach ($descs as $f) {
					if ($f['Field'] === $k/*$el*/) {
						$type = strtoupper( strtok( trim($f['Type']), '(' ) );
//							echo ')'. $k. ':'. $type. '('. "<br>\n";
						if (in_array($type, $numeric)) {
							if (isset($row) && isset($el[$row])) {
								$value = $el[$row];//dbValue($el, $row);
								if (empty($value))
									$value = 0;
							} else {
								$value = $el;//dbValue($el);
								if (empty($value))
									$value = 0;
							}
							if (!$value && strtoupper($f['Null']) != 'NO') {
								$value = 'null';
							}
	//					} elseif ($type == 'DATETIME' && $f['Default'] == 'current_timestamp()') {
						} elseif (isset($row) && isset($el[$row])) {
							$value = "'$el[$row]'";
						} else {
							$value = "'$el'";//dbValue($el);
						}
						$sql .= "={$value}, ";
					}
				}
			}
			//$sql = substr($sql, 0, -2). ' WHERE '. $idName. '='. $id. ';';
			$sql = trim($sql, ' ,'). ' WHERE '. $idName. '='. $id. ';';
		} else {
			$sql = 'INSERT INTO `yumatech_organise`.'. $tbl. ' (';
			foreach (array_keys($els) as $el) {
				$sql .= "`". trim($el). "`, ";
			}
			$sql = trim($sql, ' ,'). ') VALUES (';
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
						//$sql .= implode('-', (array)$value). ', ';
						break;
					}
				}
			}
			$sql = trim($sql, ' ,'). ')';
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
