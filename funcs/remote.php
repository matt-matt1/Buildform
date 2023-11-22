<?php
namespace Yuma;
/*
require_once "config.php";
require_once "init.php";
session_start();
if (empty($_SESSION['requestToken']))
	$_SESSION['requestToken'] = bin2hex(random_bytes(32));
*/
require_once "begin.php";
//require_once "class/Lang.php";
//require_once "setdb.php";
//require "dohead.php";
//	$store = new Store();
/*	$head = Head::getInstance();
	$head->noModel = true;
	$head->noNoScript = false;
	$head->noRequest = true;
//echo makeHead(array( 'page_title' => t('_col_desc'), 'body_id' => 'column', 'body_class' => "", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | ", 'noModel' => true, 'noNoScript' => false, 'noRequest' => true ));
	$head->pagePreTitle = "BuildForm | ";
	$head->pageTitle = '';
	$head->pagePostTitle = '';
	$head->pageDescription = '';
	$head->bodyId = '';
	$head->bodyClass = '';
	$head->articleTag = '';
	echo $head->out();*/
function doSQL($db)
{
	global $db;
	if (!isset($_REQUEST['query']))
		$qry = "SHOW DATABASES";
	else
		$qry = urldecode(htmlentities($_REQUEST['query']));
	if (isset($_REQUEST['show']) && $_REQUEST['show'])
		print $qry. "\n";
	try {
		$result = $db->run($qry)->fetchAll(PDO::FETCH_ASSOC);
		$out = isset($_REQUEST['out']) ? $_REQUEST['out'] : '';
		ob_start();
		switch($out) {
		case 'php':
			if (is_array($result)) {
				foreach($result as $k => $v) {
					if (is_array($v) /*&& count($v) > 1*/)
						print $k. ': '. print_r($v, true);
					else
						print $k. ': '. $v. "\n";
				}
			} else print $result. "\n";
			break;
		case 'xml':
			print 'xml';
			break;
		case 'www':
			print 'www';
			break;
		case 'note':
			$note = new Note(Array(
				'type' => Note::notice,
				'message' => sprintf( t('_Result %s'), json_encode($result)),
				//'details' => "Query: {$qry}",
			));
			print $note->display();
			break;
		default:
			print json_encode($result). "\n\n\n";
		}
		$out = ob_get_contents();
		ob_clean();
		if (isset($_REQUEST['show']) && $_REQUEST['show'])
			print $out. "\n";
		$_POST['result'] = $out;
	} catch (PDOException $e) {
		$note = new Note(Array(
			'type' => Note::error,
			//'message' => sprintf( t('_Cannot delete column %s from table %s'), $_GET['column'], $table),
			'message' => t('_Failed to do query'),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
		));
		echo $note->display();
	}
}
/*if (isset($db) && $db) {
	doSQL($db);
}*/
//Field::out($values['ccol']);
//echo PHP_EOL;
