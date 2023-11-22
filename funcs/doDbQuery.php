<?php
namespace Yuma;
/**
 * Perform a database query (inside a try-catch statment), then perform a success or failure function
 *
 * example:
$success = function($db) {
					$note = new Note(Array(		// draw notice after creation
						'type' => Note::notice,
						'message' => sprintf( t("_Created %s"), $_POST['dbname'] ),
						'ok' => t('_List databases'),
						'canIgnore' => true,
						'post' => Array(
							'dbname' => $_POST['dbname'],
						),
						'ignore' => sprintf( t('_Goto %s'), $_POST['dbname']),
					));
					echo $note->display();
};
$failure = function($exception, $from) {
					$note = new Note(Array(
						'type' => 'error',
						'message' => sprintf( t("_Cannot create database %s"), $dbname ),
						'details' => t('_Ensure the name is unique and not a reserved word.'). "<br>\n". implode(' - ', (array)$exception->errorInfo). "<br>\nQuery: {$query}<br>\nFrom: {$from}",
					));
					echo $note->display();
};
doDbQuery($db, 'CREATE DATABASE '. addPrefix($_POST['dbname']), $success, $failure);
 */
function doDbQuery(object$dbh, string$query, object$success, object$failure) {
				try {
					$result = $dbh->run($query)->fetch(PDO::FETCH_ASSOC);
					//$success();
					if (!is_callable($success))
						return false;
					//call_user_func($success, $dbh, $result);
					call_user_func_array($success, array($dbh, $result));
				} catch (PDOException $e) {	// cannot create db
					$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
					$from = isset($backtrace['function']) && isset($backtrace['file']) && isset($backtrace['line']) ? "{$backtrace['function']} in {$backtrace['file']}:{$backtrace['line']}" : 'unknown caller';
					//$failure($e, $from);
					if (!is_callable($failure))
						return true;
					call_user_func_array($failure, array($e, $from, $query));
					//call_user_func($failure, $e, $from);
				}
}
