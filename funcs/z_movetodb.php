<?php
namespace Yuma;

//use Yuma\HTML;

function ajax_movetodb($data = null)
{
	//return json_encode($data);
	//return $data;
	//return $_POST['table'];
	$qry = 'RENAME '. $_POST['was']. '.'. $_POST['table']. ' TO '. $_POST['db']. '.'. $_POST['table'];
	//return $qry;
	global $db;
	try {
		$dbs = $db->run($qry);//->fetchAll(PDO::FETCH_NUM);
/*		if (count($dbs) > 4095)
		{*/
			$note = new Note(Array(
				'type' => Note::notice,//'notice',
				'ok' => t('_List all'),
				'canIgnore' => true,
/*					'post' => Array(
						'dbname' => $_POST['dbname'],
						'tbname' => $_POST['tbname'],
					),*/
				'ignore' => sprintf( t('_Goto %s'), $_POST['table']),
				'message' => sprintf( t("_Moved %s to within %s"), $_POST['table'], $_POST['db']),
				//'details' => implode( ' - ', (array)$e->errorInfo ). "<br>\n". sprintf( t('_Query %s'), $qry),
			));
			echo $note->display();
/*		} else
		{*/
/*		$results = array();
//		$i = 0;
		foreach ($dbs as $num => $row) {
//			$name = $row['Field'];
//			$extra = $row['Extra'];
			if (hasPrefix($row[0]))
				//$results[$i++] = delPrefix($row[0]);
				$results[] = delPrefix($row[0]);
//			$results[$name] = $row[0];//extra;
		}*/
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
//		return json_encode($results);
//		return json_encode($dbs);
		//echo print_r($dbs, true);
		exit();
/*	}*/
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::error,
			//'message' => sprintf( t("_Cannot retrieve list"), $table ),
			'message' => sprintf( t("_Cannot move table %s from database %s to %s"), $_POST['table'], $_POST['db'], $_POST['table']),
			'details' => implode( ' - ', (array)$e->errorInfo ). "<br>\n". sprintf( t('_Query %s'), $qry),
		));
		//echo $note->display();
		return json_encode($note->display());
	}
}
add_hook('ajax_movetodb', 'ajax_movetodb');
