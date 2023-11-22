<?php

require "begin.php";
require "setdb.php";

//if ( !isset($_GET['database']) ) {
/*if ( !isset($_REQUEST['database']) ) {
//	Redirect( BASE. 'databases' );
//	return;
	die("missing database name");
	}*/
//if ( /*isset($_GET['database']) &&*/ (!isset($_GET['table']) || isset($_GET['form']))) {
/*if ( (!isset($_REQUEST['table']) || isset($_REQUEST['form']))) {
//	Redirect( BASE. 'database/'. $_GET['database'] );
//	return;
	die("missing table name");
	}*/
echo 'GET:'. print_r($_GET, true);
echo 'REQUEST:'. print_r($_REQUEST, true);
//$dbname = isset($_GET['database']) ? $_GET['database'] : 'bf_aaaa';
$dbname = isset($_REQUEST['database']) ? $_REQUEST['database'] : 'bf_aaaa';
//$table = ( isset($_GET['table']) ? $_GET['table'] : isset($_GET['form']) ) ? $_GET['form'] : 'Bbbb';
$table = ( isset($_REQUEST['table']) ? $_REQUEST['table'] : isset($_REQUEST['form']) ) ? $_REQUEST['form'] : 'Bbbb';
//$qry = 'DESC '. addPrefix($dbname). '.'. $table;
$a = 0;
global $db;
$qry = 'DESC '. $dbname. '.'. $table;
try {
	$columns = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
	if (count($columns) > 4095)
	{
		$note = new Note(Array(
			'type' => 'error',
			'message' => t("_Cannot have more than 4095 columns"),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
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
		$results = $columns[$a]['Extra'];
		//echo print_r($results, true);
		display_column_extras( $a, $prefix, $results );
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
