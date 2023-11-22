<?php

namespace Yuma;

class Users extends CRUD
{
	const equal = "=";
	const startsWith = "like";
	const error = "error";
	const TABLE = 'yumatech_organise.user';
	const ID = 'user_id';
	const NAME = 'username';
	private $defaults = Array(
		'user_id' => 'int',
		'username' => 'varchar',
		'user_first' => 'varchar',
		'user_last' => 'varchar',
		'user_email' => 'varchar',
		'password' => 'text',
		'active' => 'tinyint',
	);
	protected $params = Array();
	protected static $query = null;
	//protected static $tbl = 'yumatech_organise.user';
	//protected static $id = 'user_id';
	//protected static $name = 'username';
	protected static $use;

	public function __construct( array $params=null ) {
		$this->params = array_merge($this->defaults, (array)$params);
	}

	public function setUsername(string $name)
	{
		$this->params['username'] = $name;
	}

	public function setPassword(string $text)
	{
		//$l = Login::getInstance();
		//$pass = $l->password_hash($text, PASSWORD_DEFAULT);
		$this->params['password'] = password_hash($text, PASSWORD_DEFAULT);
		return $this->params['password'];
	}

	public function getWithID(int $num)
	{
		//$result = static::getAll(array('filterName' => 'first', 'filterValue' => "'{$name}'", 'filterOperation' => self::equal));
		$result = static::getAll(array(
			'filter' => array(
				array(
					'name' => 'user_id',
//					'operation' => self::equal,
					'value' => "'{$num}'"
				)
			)
		));
		return reset($result);
	}

	public function getWithFName(string $name)
	{
		//$result = static::getAll(array('filterName' => 'first', 'filterValue' => "'{$name}'", 'filterOperation' => self::equal));
		$result = static::getAll(array(
			'filter' => array(
				array(
					'name' => 'user_first',
//					'operation' => self::equal,
					'value' => "'{$name}'"
				)
			)
		));
		return reset($result);
	}

	public function getWithEmail(string $addr)
	{
		//$result = static::getAll(array('filterName' => 'email', 'filterValue' => "'{$addr}'", 'filterOperation' => self::equal));
		$result = static::getAll(array(
			'filter' => array(
				array(
					'name' => 'user_email',
					'value' => "'{$addr}'"
				)
			)
		));
		return reset($result);
	}

	public function getWithLName(string $name)
	{
		//$result = static::getAll(array('filterName' => 'last', 'filterValue' => "'{$name}'", 'filterOperation' => self::equal));
		$result = static::getAll(array(
			'filter' => array(
				array(
					'name' => 'user_last',
					'value' => "'{$name}'"
				)
			)
		));
		return reset($result);
	}

	public function getWithUName(string $name)
	{
		//$result = static::getAll(array('filterName' => 'last', 'filterValue' => "'{$name}'", 'filterOperation' => self::equal));
		$result = static::getAll(array(
			'filter' => array(
				array(
					'name' => 'username',
					'value' => "'{$name}'"
				)
			)
		));
		return reset($result);
	}

	private static function findName($var)
	{
		return $var['name'] == self::$use;
	}

	public function getIdFromName(string $name)
	{
		self::$use = $name;
		//$pair = array_filter(self::getAll(), 'self::findName');
		//$pair = array_filter(self::getAll(), array('Users', 'findName'));
		//$pair = array_filter(self::getAll(), array(self::, 'findName'));
		$pair = array_filter(self::getAll(), array(__CLASS__, 'findName'));
		//$pair = array_filter(self::getAll(), function($e) { return $e == self::$verb; });
//	echo '<pre>'. print_r($pair, true). '</pre>'. "\n";
		return reset($pair)[self::ID];
	}

	public function getFromPOST()
	{
		$array = Array(
			'username' =>	filter_input(INPUT_POST, 'username', FILTER_DEFAULT),
			'user_first' => filter_input(INPUT_POST, 'first', FILTER_DEFAULT),
			'user_last' =>	filter_input(INPUT_POST, 'last', FILTER_DEFAULT),
			'user_email' => filter_input(INPUT_POST, 'email', FILTER_DEFAULT),
			'password' =>	filter_input(INPUT_POST, 'password', FILTER_DEFAULT),
			'active' =>		(filter_input(INPUT_POST, 'active', FILTER_DEFAULT) == true),
		);
		if (!empty(filter_input(INPUT_POST, 'user_id', FILTER_DEFAULT))) {
			$array['user_id'] = filter_input(INPUT_POST, 'user_id', FILTER_DEFAULT);
		}
		if (!empty(filter_input(INPUT_POST, 'date_first', FILTER_DEFAULT))) {
			$array['date_first'] = filter_input(INPUT_POST, 'date_first', FILTER_DEFAULT);
		}
		if (!empty(filter_input(INPUT_POST, 'date_first', FILTER_DEFAULT))) {
			$array['date_first'] = filter_input(INPUT_POST, 'date_first', FILTER_DEFAULT);
		}
		$this->params = $array;
		return $array;
	}

	/**
	 * Inserts a new business or if a valid business_id is supplied updates that business
	 *
	 * Calls the makeSQL method and perform the database operation
	 */
/*	public function insert($business_id=null, $getResultId=null)
	{
		global $db;
		$tbls = explode('.', $this->tbl);
		if (!isset($business_id) || !is_numeric($business_id)) {
			$business_id = filter_input(INPUT_POST, $this->id, FILTER_DEFAULT);
		}
		$sql = makeSQL(end($tbls), $this->id, $this->params, $business_id);
		try {
			$db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
			static::$query = $sql;
			if (!is_numeric($business_id)) {
				//$id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'yumatech_organise' AND TABLE_NAME = 'business'")->fetchAll(PDO::FETCH_ASSOC);
				//$id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '". reset(explode('.', $this->tbl). "' AND TABLE_NAME = '". end(explode('.', $this->tbl)). "'")->fetchAll(PDO::FETCH_ASSOC);
				$id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '". reset($tbls). "' AND TABLE_NAME = '". end($tbls). "'")->fetchAll(PDO::FETCH_ASSOC);
//			echo 'business::insert - <pre>'. print_r($id, true). '<\pre>'. "<br>\n";
				return $id[0]['id'];
			} else {
				return $business_id;
			}
		} catch (PDOException $e) {
			$msg = !is_numeric($business_id) ? 'Inserted new '. end($tbls) : 'Updated '. end($tbls);
			$name = filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT);
			$note = new Note(Array(
				'type' => Note::error,
				'message' => sprintf( t("_{$msg} %s"), $name),
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
	 * Returns an array of all businesses
	 *
	 * if $onlyActive is true only returns active ones
	 * only returns the records where the $filterName is $filterOperation to $filterValue
	 *
	 * set an offset to begin the records from that number
	 * set a limit to extract only that number of records
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
