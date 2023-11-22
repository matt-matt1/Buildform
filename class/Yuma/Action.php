<?php

namespace Yuma;

class Action extends CRUD
{
	const equal = "=";
	const startsWith = "like";
	const error = "error";
	const TABLE = 'yumatech_organise.action';
	const ID = 'action_id';
	const NAME = 'verb';
	private $defaults = Array(
//		'action_id' => 'INT',
//		'verb' => 'VARCHAR',
	);
	protected $params = Array();
	protected static $query = null;
	//protected static $tbl = 'yumatech_organise.action';
	protected static $verb ;//= 'verb';
	//protected static $id = 'action_id';
	//protected static $name = 'verb';

	public function __construct( array $params=null ) {
		$this->params = array_merge($this->defaults, (array)$params);
	}

	public function getVerb(string $verb)
	{
		$id = static::getIdFromVerb($verb);
//		$id = static::getAll(array('filterName' => 'verb', 'filterValue' => "'{$verb}'", 'filterOperation' => self::equal));
		if (!empty($id)) {
			return $id;
		}
		$this->setVerb($verb);
		$id = $this->insert();
		return $id;
	}

	public function setVerb(string $verb)
	{
		$this->params['verb'] = $verb;
		static::$verb = $verb;
	}

	public function getFromPOST()
	{
		$array = Array(
		//	'verb' =>			filter_input(INPUT_POST, 'verb', FILTER_DEFAULT),
		//	'action_id' =>		filter_input(INPUT_POST, 'action_id', FILTER_DEFAULT),
		);
		if (!empty(filter_input(INPUT_POST, 'action_id', FILTER_DEFAULT))) {
			$array['action_id'] = filter_input(INPUT_POST, 'action_id', FILTER_DEFAULT);
		}
		if (!empty(filter_input(INPUT_POST, 'action', FILTER_DEFAULT))) {
			$array['verb'] = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);
		}
		if (!empty(filter_input(INPUT_POST, 'verb', FILTER_DEFAULT))) {
			$array['verb'] = filter_input(INPUT_POST, 'verb', FILTER_DEFAULT);
		}
		$this->params = $array;
		return $array;
	}

	private static function findVerb($var)
	{
		return $var['verb'] == self::$verb;
	}
	public function getIdFromVerb(string $verb)
	{
		self::$verb = $verb;
		$pair = array_filter(self::getAll(), array('Action', 'findVerb'));
		//$pair = array_filter(self::getAll(), function($e) { return $e == self::$verb; });
//	echo '<pre>'. print_r($pair, true). '</pre>'. "\n";
		return reset($pair)['action_id'];
	}

	/**
	 * Inserts a new journal entry
	 *
	 * Calls the makeSQL method and perform the database operation
	 */
/*	public function insert(string $verb)//, $action_id=null)
	{
		global $db;
		$tbls = explode('.', $this->tbl);
*/		//$values = $this->getFromPOST();
/*		if (!isset($action_id) || !is_numeric($action_id)) {
			$sql = "SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '". reset($tbls). "' AND          +++TABLE_NAME = '". end($tbls). "'";
		try {
			$id = $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
			static::$query = $sql;
			$this->id = $id[0]['id'];
		} catch (PDOException $e) {
			$msg = !is_numeric($this->id) ? 'Inserted new '. end($tbls) : 'Updated '. end($tbls);
//			$name = filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT);
			$note = new Note(Array(
				'type' => Note::error,
		//		'message' => sprintf( t("_{$msg} %s"), $name),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}*//*
		//$sql = makeSQL(end($tbls), $this->id, $this->params, $business_id);
		$sql = 'INSERT INTO '. end($tbls). ' (`verb`) VALUES ('. $verb. ')';
		try {
			$db->run($sql);
			static::$query = $sql;
			if (!is_numeric($this->id)) {
				$id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '". reset($tbls). "' AND TABLE_NAME = '". end($tbls). "'")->fetchAll(PDO::FETCH_ASSOC);
				return $id[0]['id'];
			} else {
				return $this->id;
			}
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,
				'message' => sprintf( t('_Cannot insert %s'), $verb ),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}
	public function update($business_id=null, $getResultId=null)
	{
		return $this->insert($business_id, $getResultId);
	}
*/
	/**
	 * Returns an array of a business that matches the given id
	 */
/*	public static function getById(int $id, int $offset=0, int $limit=0)
	{
		global $db;
		$sql = 'SELECT * FROM '. $this->tbl. ' WHERE '. $this->id. '='. $id;
		static::$query = $sql;
		try {
			return $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,
				'message' => t('_Cannot retrieve '. end($tbls)),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}
 */
	/**
	 * Returns an array of all actions
	 */
/*	public static function getAll()
	{
		global $db;
		//$tbls = explode('.', static::$tbl);
		//$sql = 'SELECT * FROM '. implode('.', $tbls);
		$sql = 'SELECT * FROM '. static::$tbl;
		try {
			$acts = $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
			static::$query = $sql;
			return $acts;
		} catch (PDOException $e) {
//			$msg = !is_numeric($this->id) ? 'Inserted new '. end($tbls) : 'Updated '. end($tbls);
//			$name = filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT);
			$note = new Note(Array(
				'type' => Note::error,
		//		'message' => sprintf( t("_{$msg} %s"), $name),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}
 */
	//public static function getAll(bool $onlyActive=false, string $filterName=null, $filterValue=null, $filterOperation=null, int $offset=null, int $limit=null)
/*	public static function getAll(array $params)
		//bool $onlyActive=false, string $filterName=null, $filterValue=null, $filterOperation=null, int $offset=null, int $limit=null)
	{
		global $db;
		$sql = 'SELECT * FROM '. $this->tbl;
		if (isset($params['onlyActive']) && $params['onlyActive']) {
			$sql .= ' WHERE active!=0 AND active!=NULL';
			if (!isset($params['filterOperation']) || empty($params['filterOperation'])) {
				$params['filterOperation'] = static::equal;
			}
			if (isset($params['filterName']) && isset($params['filterValue'])) {
				$sql .= " AND {$params['filterName']} {$params['filterOperation']} {$params['filterValue']}";
			}
		} elseif (isset($params['filterName']) && isset($params['filterValue'])) {
			if (!isset($params['filterOperation']) || empty($params['filterOperation'])) {
				$filterOperation = static::equal;
			}
			$sql .= " WHERE {$params['filterName']} {$params['filterOperation']} {$params['filterValue']}";
		}
		if (isset($params['offset']) || isset($params['limit'])) {
			$sql .= ' LIMIT ';
			if ($params['offset'] > 0) {
				$sql .= $params['offset']. ',';
			}
			//if ($params['limit'] > 0) {
				$sql .= $params['limit'];
			//}
		}
		static::$query = $sql;
		try {
			return $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,
				'message' => sprintf( t("_Cannot %s ". end($tbls)), (strpos($sql, 'INSERT') === 0) ? 'insert' : 'update'),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}
 *//*
	public function getLastSQL()
	{
		return static::$query;
	}
  *//*
	public function deleteRows($rows)
	{
		global $db;
		$sql = 'DELETE FROM '. $this->tbl. ' WHERE '. $this->id. ' IN ('. implode(', ', $rows). ')';
		static::$query = $sql;
		try {
			$done = $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
			$note = new Note(Array(
				'type' => Note::error,
				'message' => sprintf( t("_Deleted %s rows")), $done),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,
				'message' => sprintf( t("_Cannot delete rows %s"), implode(', ', $rows),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}
   */
}
