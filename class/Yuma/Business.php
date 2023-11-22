<?php

namespace Yuma;

class Business extends CRUD
{
	const equal = "=";
	const startsWith = "like";
	const error = "error";
	const TABLE = 'yumatech_organise.business';
	const ID = 'business_id';
	const NAME = 'business_name';
	private $defaults = Array(
		'business_name' => 'VARCHAR',
		'business_email' => 'VARCHAR',
		'business_street_address' => 'VARCHAR',
		'business_address_line2' => 'VARCHAR',
		'business_city' => 'VARCHAR',
		'business_province' => 'VARCHAR',
		'business_post_code' => 'VARCHAR',
		'business_website' => 'VARCHAR',
		/*'date_updated' => filter_input(INPUT_POST, 'date_updated', FILTER_DEFAULT),*/
		'business_note' => 'TEXT',
		'business_active' => 'INT',
		'business_source' => 'VARCHAR'
	);
	//private $params = Array();
	public $params = Array();
	protected static $query = null;
	protected static $tbl = 'yumatech_organise.business';
	protected static $id = 'business_id';
	protected static $name = 'business_name';
/*
	public function __construct( array $params=null ) {
		$this->params = array_merge($this->defaults, (array)$params);
	}
 */
	public function getFromPOST()
	{
		$array = Array(
//			'business_id' =>				filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT),
			'business_name' =>				filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT),
			'business_email' =>				filter_input(INPUT_POST, 'business_email', FILTER_DEFAULT),
			'business_street_address' =>	filter_input(INPUT_POST, 'business_street_address', FILTER_DEFAULT),
			'business_address_line2' =>		filter_input(INPUT_POST, 'business_address_line2', FILTER_DEFAULT),
			'business_city' =>				filter_input(INPUT_POST, 'business_city', FILTER_DEFAULT),
			'business_province' =>			filter_input(INPUT_POST, 'business_province', FILTER_DEFAULT),
			'business_post_code' =>			filter_input(INPUT_POST, 'business_post_code', FILTER_DEFAULT),
			'business_website' =>			filter_input(INPUT_POST, 'business_website', FILTER_DEFAULT),
			'business_note' =>				filter_input(INPUT_POST, 'business_note', FILTER_DEFAULT),
			'business_active' =>			(filter_input(INPUT_POST, 'business_active', FILTER_DEFAULT) == true),
			'business_source' =>			filter_input(INPUT_POST, 'business_source', FILTER_DEFAULT),
		);
		if (!empty(filter_input(INPUT_POST, 'business_created', FILTER_DEFAULT))) {
			$array['business_created'] =	filter_input(INPUT_POST, 'business_created', FILTER_DEFAULT);
		}
		if (!empty(filter_input(INPUT_POST, 'business_updated', FILTER_DEFAULT))) {
			$array['business_updated'] =	filter_input(INPUT_POST, 'business_updated', FILTER_DEFAULT);
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
