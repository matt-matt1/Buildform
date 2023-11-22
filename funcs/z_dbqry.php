<?php
namespace Yuma;

//use Yuma\HTML;

function ajax_dbqry($data = null)
{
	if (!isset($data) || !isset($data['qry'])) {
		throw new Exception(t('_Missing query parameter'));
		return;
	}
	header('Content-Type: application/json');
//	$dbname = isset($_REQUEST['db']) ? $_REQUEST['db'] : 'bf_aaaa';
//	$table = ( isset($_REQUEST['tbl']) ? $_REQUEST['tbl'] /*: isset($_REQUEST['form']) ) ? $_REQUEST['form']*/ : 'Bbbb' );
//	$a = isset($_REQUEST['col']) ? $_REQUEST['col'] : 0;
//	$qry = 'SHOW DATABASES';
	//return $qry;
	global $db;
	try {
		$dbs = $db->run($data['qry'])->fetchAll(PDO::FETCH_ASSOC);//FETCH_NUM);
//		return json_encode($dbs);
/*		if (count($dbs) > 4095)
		{
			$note = new Note(Array(
				'type' => 'error',
				'message' => t("_Cannot have more than 4095 columns"),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
			));
			echo $note->display();
		} else
		{*/
		$results = array();
//		$i = 0;
		foreach ($dbs as $num => $row) {
//			$name = $row['Field'];
//			$extra = $row['Extra'];
/*			if (hasPrefix($row[0]))
				//$results[$i++] = delPrefix($row[0]);
			$results[] = delPrefix($row[0]);*/
			$results[] = $row;
//			$results[$name] = $row[0];//extra;
		}
/*		//$prefix = (!empty($columns[$a]['Field']) ? $columns[$a]['Field'] : ''). ' '. $columns[$a]['Field']. ' ';
		$prefix = '`'. $columns[$a]['Field']. '` ';
		$prefix .= strtoupper($columns[$a]['Type']);
		$prefix .= strtoupper($columns[$a]['Null']) == 'YES' ? ' NULL' : '';
		//$columns[$a]['Key']. ' ';
		$prefix .= !empty($columns[$a]['Default']) ? ' '. $columns[$a]['Default'] : '';
		$results = $columns[$a];//['Extra'];
		//echo print_r($results, true);
		//return display_column_extras( $a, $prefix, $results );
		echo display_column_extras( $a, $prefix, $results, $dbname, $table );*/
//		echo print_r($results, true);
		//return json_encode(print_r($results, true));
		return json_encode($results);
//		return json_encode($dbs);
		//echo print_r($dbs, true);
		exit();
/*	}*/
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => 'error',
			//'message' => sprintf( t("_Cannot retrieve list"), $table ),
			'message' => t("_Cannot retrieve list"),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$data['qry']}",
		));
		//echo $note->display();
		return json_encode($note->display());
	}
}
add_hook('ajax_dbqry', 'ajax_dbqry');
