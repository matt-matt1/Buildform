<?php

namespace Yuma;

class Journal extends CRUD
{
	const equal = "=";
	const startsWith = "like";
	const error = "error";
	const TABLE = 'yumatech_organise.journal';
	const ID = 'journal_id';
	const WHO = 'user_id';
	const BUS = 'business_id';
	const DOC = 'document_id';
	const ACT = 'action_id';
	private $defaults = Array(
		'journal_id' => 'INT',
		'user_id' => 'INT',
		'business_id' => 'INT',
		'document_id' => 'INT',
		'contact_id' => 'INT',
		'date' => 'DATETIME',
		'action_id' => 'INT',
	);
	protected $params = Array();
	protected static $query = null;
/*
	public function __construct( array $params=null ) {
		$this->params = array_merge($this->defaults, (array)$params);
	}
 */

	//SELECT j.journal_id,u.username,b.business_name,d.name,a.verb,j.date FROM `journal` j JOIN `user` u ON u.user_id=j.user_id JOIN `business` b ON (j.business_id IS NOT null AND b.business_id=j.business_id) JOIN `document` d ON (j.document_id IS NOT null AND d.document_id=j.document_id) JOIN `action` a ON a.action_id=j.action_id

	public function getAllWithLangData(array $params=null)
	{
		if (!isset($params)) {
			$params = array();
		}
	//		echo 'params<pre>'. print_r($params, true). '</pre>';
		global $db;
		$tbls = explode('.', static::TABLE);
		//$sql = 'SELECT j.journal_id, u.user_first, u.user_last, u.username, b.business_name, d.name AS document_name, c.contact_first, c.contact_last, j.date, a.verb FROM `journal` j
		$sql = 'SELECT j.journal_id, u.user_first, u.user_last, u.username, a.verb AS action, b.business_name, d.name AS document, c.contact_first, c.contact_last, j.date FROM '. static::TABLE. ' j
INNER JOIN '. Users::TABLE. ' u ON u.user_id=j.user_id
LEFT JOIN '. Business::TABLE. ' b ON b.business_id=j.business_id
LEFT JOIN '. Documents::TABLE. ' d ON d.document_id=j.document_id
LEFT JOIN '. Contact::TABLE. ' c ON c.contact_id=j.contact_id
INNER JOIN '. Action::TABLE. ' a ON a.action_id=j.action_id
WHERE 1';
/*		if (isset($params['onlyActive']) && $params['onlyActive']) {
			$sql .= ' WHERE active!=0 AND active!=NULL';
		} else {
			$sql .= ' WHERE 1';
		}*/
		if (isset($params['filter']) && !empty($params['filter'])) {
			foreach ($params['filter'] as $f) {
//			echo 'filter<pre>'. print_r($f, true). '</pre>';
				if (!isset($f['operation']) || empty($f['operation'])) {
					$f['operation'] = static::equal;
				}
				if (isset($f['name']) && isset($f['value'])) {
					$sql .= " AND {$f['name']} {$f['operation']} {$f['value']}";
				}
			}
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
		if (isset($params['onlySQL']) && $params['onlySQL']) {
			return $sql;
		}
		try {
			$acts = $db->run($sql)->fetchAll(\PDO::FETCH_ASSOC);
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

	public function setAction($state)
	{
		if (!is_numeric($state)) {
			$array = $this->findID($state);
//			echo 'state<pre>'. print_r($array, true). '</pre>';
		}
		//$this->params['action_id'] = isset($state['action_id']) ? $state['action_id'] : implode('-', $state);
		$this->params['action_id'] = 
			$array['action_id'];
//			echo 'setAction params<pre>'. print_r($this->params, true). '</pre>';
	}

	public function findID($state)
	{
		return $this->getAllActions(array('findID' => true, 'verb' => $state))[0];//['action_id'];
/*		global $db;
		$tbls = explode('.', static::TABLE);
		$tbls[count($tbls)-1] = 'action';
		$sql = 'SELECT * FROM '. implode('.', $tbls);
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
		}*/
	}

	public function add(array $fields)
	{
//		if (!isset($fields['journal_id'])) {
//			$msg[] = 'missing journal_id';
//		}
		if (!isset($fields['user_id'])) {
			$msg[] = 'missing user_id';
		}
		if (!isset($fields['business_id']) && !isset($fields['document_id'])) {
			$msg[] = 'must supply either a business_id or a document_id';
		}
//		if (!isset($fields['document_id'])) {
//			$msg[] = 'missing document_id';
//		}
		if (!isset($fields['action_id'])) {
			$msg[] = 'missing action_id';
		}
//		if (!isset($fields['date'])) {
//			$msg[] = 'missing date';
//		}
		$this->params = $fields;
		$tbls = explode('.', static::TABLE);
		$sql = makeSQL(end($tbls), static::ID, $this->params);//, $id);
		if (!empty($msg)) {
			$note = new Note(Array(
				'type' => Note::error,
				'message' => implode("\n", $msg),
				//'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
				'details' => sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
			return;
		} else {
			$this->insert();
		}
	}

	public function fillValues(array $values)
	{
		$this->params = $values;
		return $values;
	}

	public function getFromPOST()
	{
		$array = Array(
//			'journal_id' =>	filter_input(INPUT_POST, 'journal_id', FILTER_DEFAULT),
			'business_id' =>	filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT),
			'user_id' =>		filter_input(INPUT_POST, 'user_id', FILTER_DEFAULT),
			'document_id' =>	filter_input(INPUT_POST, 'document_id', FILTER_DEFAULT),
			'contact_id' =>		filter_input(INPUT_POST, 'contact_id', FILTER_DEFAULT),
		//	'date' =>			filter_input(INPUT_POST, 'date', FILTER_DEFAULT),
			'action_id' =>		filter_input(INPUT_POST, 'action_id', FILTER_DEFAULT),
		);
		if (!empty(filter_input(INPUT_POST, 'date', FILTER_DEFAULT))) {
			$array['date'] = filter_input(INPUT_POST, 'date', FILTER_DEFAULT);
		}
		$this->params = $array;
		return $array;
	}

	/**
	 * Inserts a new journal entry
	 *
	 * Calls the makeSQL method and perform the database operation
	 */
/*	public function insert($action_id=null, $user_id=null, $business_id=null, $document_id=null)
	{
		global $db;
		$tbls = explode('.', static::$tbl);
		//$tbls = explode('.', $this->tbl);
		$values = $this->getFromPOST();
		if (!isset($user_id) || !is_numeric($user_id)) {
			$user_id = $values['user_id'];//filter_input(INPUT_POST, $this->id, FILTER_DEFAULT);
		}
		if (!isset($document_id) || !is_numeric($document_id)) {
			$document_id = $values['document_id'];//filter_input(INPUT_POST, $this->id, FILTER_DEFAULT);
		}
		if (!isset($action_id) || !is_numeric($action_id)) {
			$action_id = $values['action_id'];//filter_input(INPUT_POST, $this->id, FILTER_DEFAULT);
		}
		if (!isset($business_id) || !is_numeric($business_id)) {
			$business_id = $values['business_id'];//filter_input(INPUT_POST, $this->id, FILTER_DEFAULT);
		}
		//$sql = makeSQL(end($tbls), $this->id, $this->params, $business_id);
		$sql = 'INSERT INTO '. end($tbls). ' (`user_id`,`business_id`,`document_id`,`action_id`,`date`) VALUES (';
		$sql .= "{$user_id}, {$business_id}, {$document_id}, {$action_id}, 'NOW()')";
		try {
			$db->run($sql);//->fetchAll(PDO::FETCH_ASSOC);
			static::$query = $sql;
			if (!is_numeric($this->id)) {
				//$id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'yumatech_organise' AND TABLE_NAME = 'business'")->fetchAll(PDO::FETCH_ASSOC);
				//$id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '". reset(explode('.', $this->tbl). "' AND TABLE_NAME = '". end(explode('.', $this->tbl)). "'")->fetchAll(PDO::FETCH_ASSOC);
				$id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '". reset($tbls). "' AND TABLE_NAME = '". end($tbls). "'")->fetchAll(PDO::FETCH_ASSOC);
//			echo 'business::insert - <pre>'. print_r($id, true). '<\pre>'. "<br>\n";
				return $id[0]['id'];
			} else {
				return $this->id;
			}
		} catch (PDOException $e) {
			$msg = !is_numeric($this->id) ? 'Inserted new '. end($tbls) : 'Updated '. end($tbls);
//			$name = filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT);
			$note = new Note(Array(
				'type' => Note::error,
		//		'message' => sprintf( t("_{$msg} %s"), $name),
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
	public static function getAllActions(array $options)
	{
		global $db;
		$tbls = explode('.', static::TABLE);
		$tbls[count($tbls)-1] = 'action';
		$sql = 'SELECT * FROM '. implode('.', $tbls);
		if (isset($options) && isset($options['findID']) && $options['findID']) {
			$sql .= " WHERE verb='{$options['verb']}'";
		}
//		echo 'getAllActions sql="'. $sql. '"<br>'. "\n";
		try {
			$acts = $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
			static::$query = $sql;
			return $acts;
		} catch (PDOException $e) {
//			$msg = !is_numeric($this->id) ? 'Inserted new '. end($tbls) : 'Updated '. end($tbls);
//			$name = filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT);
			if (isset($options) && isset($options['noShowFailure']) && $options['noShowFailure']) {
				return true;
			}
			$note = new Note(Array(
				'type' => Note::error,
				'message' => t("_{$e->getMessage()}"),
				//'message' => sprintf( t("_{$msg} %s"), $name),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}

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
