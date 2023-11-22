<?php
namespace Yuma;
use \PDO;

function showDbs($db, $onRaw, $onSuccess, $onFail)
{
	$qry = 'SHOW DATABASES';
	try {
		$raw = $db->run($qry)->fetchAll(PDO::FETCH_COLUMN);
//			echo '<!-- raw:'. print_r($raw, true). ' -->';
		if (!empty($raw))
			call_user_func_array($onRaw, array($raw, $db));
//			print '<script>const reserved = ["'. implode('","', $raw). '"];</script>';		// form a javascript variable of resered db names
//		if ((!isset($_POST['dbname']) || empty($_POST['dbname'])) && isset($_GET['databases']) && $_GET['databases'] == ADD_DB) {
//			display_add_form();
//		} else {
			$dbs = array();
			//if (is_array($raw) && !empty($raw) && (!isset($GLOBALS['data']['SHOW_OTHER_DBS']) || !$GLOBALS['data']['SHOW_OTHER_DBS']))
			if (is_array($raw) && !empty($raw) && (!defined('SHOW_OTHER_DBS') || !SHOW_OTHER_DBS))
			{
				foreach($raw as $name)
				{
					if (hasPrefix($name))
					{
						$dbs[] = $name;
					}
				}
			} else {
				$dbs = $raw;
			}
//			echo '<!-- dbs:'. print_r($dbs, true). ' -->';
			call_user_func_array($onSuccess, array($dbs, $db));
//		}
	} catch (PDOException $e) {
		call_user_func_array($onFail, array($e, $qry, $db));
/*		$note = new Note(Array(
			'type' => Note::error,
			'message' => sprintf( t("_Database error: %s"), implode(' - ', (array)$e->errorInfo) ),
			'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
		));
		echo $note->display();*/
	}
}
function getAllDbs($db)
{
	$qry = 'SHOW DATABASES';
	try {
		return $db->run($qry)->fetchAll(PDO::FETCH_COLUMN);
//			echo '<!-- raw:'. print_r($raw, true). ' -->';
//		if (!empty($raw))
//			return $raw;
	} catch (PDOException $e) {
		return $e;
	}
}
function getDbs($db)
{
	$raw = getAllDbs($db);
	$dbs = array();
//	if (is_array($raw) && !empty($raw) && (!defined('SHOW_OTHER_DBS') || !SHOW_OTHER_DBS)) {
		foreach($raw as $name)
		{
			if (hasPrefix($name))
			{
				$dbs[] = $name;
			}
		}
		return $dbs;
//	} else {
//		return $raw;
//	}
}
