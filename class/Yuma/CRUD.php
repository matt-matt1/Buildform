<?php

namespace Yuma;

abstract class CRUD
{
/*	const equal = "=";
	const startsWith = "like";
	const error = "error";
	private $defaults = Array(
		'business_name' => 'VARCHAR',
		'email' => 'VARCHAR',
		'street_address' => 'VARCHAR',
		'address_line2' => 'VARCHAR',
		'city' => 'VARCHAR',
		'province' => 'VARCHAR',
		'post_code' => 'VARCHAR',
		'website' => 'VARCHAR',
		/ *'date_updated' => filter_input(INPUT_POST, 'date_updated', FILTER_DEFAULT),* /
		'note' => 'TEXT',
		'active' => 'INT'
	);
	private $params = Array();
	private static $query = null;
	//private $tbl = '`yumatech_organise`.business';
	private $tbl = 'yumatech_organise.business';
	private $id = 'business_id';
	private $name = 'business';
	
	public function __construct( array $params=null ) {
		$this->params = array_merge($this->defaults, (array)$params);
	}
 */
	public $isNew = false;
/*
	public function getFromPOST()
	{
		$array = Array(
//			'business_id' =>	filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT),
			'business_name' =>	filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT),
			'email' =>			filter_input(INPUT_POST, 'email', FILTER_DEFAULT),
			'street_address' => filter_input(INPUT_POST, 'street_address', FILTER_DEFAULT),
			'address_line2' =>	filter_input(INPUT_POST, 'address_line2', FILTER_DEFAULT),
			'city' =>			filter_input(INPUT_POST, 'city', FILTER_DEFAULT),
			'province' =>		filter_input(INPUT_POST, 'province', FILTER_DEFAULT),
			'post_code' =>		filter_input(INPUT_POST, 'post_code', FILTER_DEFAULT),
			'website' =>		filter_input(INPUT_POST, 'website', FILTER_DEFAULT),
			/ *'date_updated' => filter_input(INPUT_POST, 'date_updated', FILTER_DEFAULT),* /
			'note' =>			filter_input(INPUT_POST, 'note', FILTER_DEFAULT),
			'active' =>			filter_input(INPUT_POST, 'active', FILTER_DEFAULT)
		);
		if (!empty(filter_input(INPUT_POST, 'date_first', FILTER_DEFAULT))) {
			$array['date_first'] = filter_input(INPUT_POST, 'date_first', FILTER_DEFAULT);
		}
		$this->params = $array;
		return $array;
	}
 */
	/**
	 * Inserts a new business or if a valid business_id is supplied updates that business
	 *
	 * Calls the makeSQL method and perform the database operation
	 */
	//public function insert($id=null, $getResultId=null)
	public function insert($id=null, array $options=null)
	{
		global $db;
		//$c = get_called_class();
		//$tbls = explode('.', $c::TABLE);
		$tbls = explode('.', static::TABLE);
		if (!isset($id) || !is_numeric($id)) {		// try to obtain POSTED value for ID
			//$id = filter_input(INPUT_POST, $c::ID, FILTER_DEFAULT);
			$id = filter_input(INPUT_POST, static::ID, FILTER_DEFAULT);
		}
		//echo 'table='. end($tbls). ' , id='. $c::ID. ' , params:<pre>'. print_r($this->params, true). '</pre> , id='. $id. "\n";
//		echo 'table='. end($tbls). ' , id='. static::ID. ' , params:<pre>'. print_r($this->params, true). '</pre> , id='. $id. "\n";
		//$sql = makeSQL(end($tbls), $c::ID, $this->params, $id);
		$sql = makeSQL(end($tbls), static::ID, $this->params, $id);
		static::$query = $sql;
		if (isset($options['onlySQL']) && $options['onlySQL']) {
			return $sql;
		}
		try {
			$db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
			if (isset($options['getResultId']) && $options['getResultId']) {
				if (!is_numeric($id)) {
					$res_id = $db->run("SELECT AUTO_INCREMENT - 1 as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '". reset($tbls). "' AND TABLE_NAME = '". end($tbls). "'")->fetchAll(PDO::FETCH_ASSOC);
					if (!isset($options['noShowSuccess']) || !$options['noShowSuccess']) {
						$note = new Note(Array(
							'type' => Note::notice,
							'message' => sprintf( t('_Updated %s (ID %s)'), end($tbls), $res_id[0]['id']),
							'details' => sprintf( t('_Query %s'), $sql ),
						));
						echo $note->display();
					}
					return $res_id[0]['id'];
				} else {
					$this->isNew = true;
					if (!isset($options['noShowSuccess']) || !$options['noShowSuccess']) {
						$note = new Note(Array(
							'type' => Note::notice,
							'message' => sprintf( t('_Inserted new %s'), end($tbls)),
							'details' => sprintf( t('_Query %s'), $sql ),
						));
						echo $note->display();
					}
					return $id;
				}
			} else {
				if (!isset($options['noShowSuccess']) || !$options['noShowSuccess']) {
					$msg = !is_numeric($id) ? ('Inserted new '. end($tbls)) : ('Updated '. end($tbls). ' (ID '. $id. ')');
					$note = new Note(Array(
						'type' => Note::notice,
						'message' => t("_{$msg}"),
						//'message' => (defined(static::NAME)) ? sprintf( t("_{$msg} %s"), static::NAME) : 'error',
						'details' => sprintf( t('_Query %s'), $sql ),
					));
					echo $note->display();
				}
				return false;
			}
		} catch (PDOException $e) {
			if (isset($options['noShowFailure']) && $options['noShowFailure']) {
				$msg = !is_numeric($id) ? 'Inserted new '. end($tbls) : 'Updated '. end($tbls);
	//			$name = filter_input(INPUT_POST, 'business_name', FILTER_DEFAULT);
				$note = new Note(Array(
					'type' => Note::error,
					'message' => sprintf( t("_Failed to %s"), $msg),
					//'message' => (defined($c::NAME)) ? sprintf( t("_Failed to %s"), $msg),
					//'message' => (defined(static::NAME)) ? sprintf( t("_{$msg} %s"), static::NAME) : 'error',
					'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
				));
				echo $note->display();
			}
		}
	}
	public function update($id=null, $getResultId=null)
	{
		return $this->insert($id, $getResultId);
	}

    /**
     * Returns an array of a business that matches the given id
     * @param int $id
     * @param $tbls
     * @param int $offset
     * @param int $limit
     * @return void
     */
	public static function getById(int $id, $tbls, int $offset=0, int $limit=0)
	{
		global $db;
//		$c = get_called_class();
//		$sql = 'SELECT * FROM '. $c::TABLE. ' WHERE '. $c::ID. '='. $id;
		$sql = 'SELECT * FROM '. static::TABLE. ' WHERE '. static::ID. '='. $id;
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
	public static function getAll(array $params=null)
		//bool $onlyActive=false, string $filterName=null, $filterValue=null, $filterOperation=null, int $offset=null, int $limit=null)
	{
		$c = get_called_class();
/*		if (null !== static::TABLE) {
		//if (null !== get_called_class()::TABLE) {
		//if (null !== $c::TABLE) {
			throw new Exception(sprintf( t('_Called from %s'), get_called_class() ));
			//throw new Exception(sprintf( t('_Called from %s'), $c ));
		}*/
		global $db;
		//$c = get_called_class();
//			echo 'caled CRUD: <pre>'. print_r($c, true). '<\pre>'. "<br>\n";
		//$tbls = explode('.', $c::TABLE);
		$tbls = explode('.', static::TABLE);
		//$sql = 'SELECT * FROM '. $c::TABLE;
		$sql = 'SELECT * FROM '. static::TABLE;
/*		if (isset($params['onlyActive']) && $params['onlyActive']) {
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
		}*/
		if (isset($params['onlyActive']) && $params['onlyActive']) {
			$sql .= ' WHERE active!=0 AND active!=NULL';
		} else {
			$sql .= ' WHERE 1';
/*		} elseif (isset($params['filterName']) && isset($params['filterValue'])) {
			if (!isset($params['filterOperation']) || empty($params['filterOperation'])) {
				$filterOperation = static::equal;
			}
			$sql .= " WHERE {$params['filterName']} {$params['filterOperation']} {$params['filterValue']}";*/
		}
		if (isset($params['filter']) && !empty($params['filter'])) {
			foreach ($params['filter'] as $f) {
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
			return $db->run($sql)->fetchAll(\PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,
				'message' => sprintf( t("_Cannot %s ". end($tbls)), (strpos($sql, 'INSERT') === 0) ? 'insert' : 'update'),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}

	public function removeEmptyColumns(array &$array)
	{
		if (empty($array)) {
			return;
		}
		if (is_array($array[0])) {
			//$cols = array_keys($this->params['data'][0]);
			foreach ($array as $n => $rows) {
				foreach ($rows as $k => $v) {
					if (empty($v) /*&& (!isset($empties[$k]) || !in_array(true, (array)$empties[$k]))*/) {
						$empties[$k][] = true;
					} else {
						$empties[$k][] = false;
					}
				}
			}
//			echo '<pre>empties: '. print_r($empties, true). '</pre>'. "\n";
//			echo '<script>console.log(empties: '. print_r($empties, true). ');</script>'. "\n";
//			error_log ('empties: '. print_r($empties, true). '!!!');//. "\n";
			foreach ($empties as $col => $val) {
				$unc = array_unique($val);
//				echo $col. ' has <pre>'. print_r($unc, true). ':'. count($unc). '</pre>'. "\n";
				if (count($unc) === 1 && $unc[0]) {
					foreach ($array as $n => $rows) {
						unset ($array[$n][$col]);
					}
				}
			}
//			echo '<pre>new: '. print_r($array, true). '</pre>'. "\n";
		} else {
//			$cols = array_keys($this->params['data']);
			foreach ($rows as $k => $v) {
				if (empty($v)) {
					$empties[$k] = true;
				} else {
					$empties[$k] = false;
				}
			}
//			echo '<pre>'. print_r($empties, true). '</pre>'. "\n";
			foreach ($empties as $col => $val) {
				$unc = array_unique($val);
//				echo $col. ' has <pre>'. print_r($unc, true). ':'. count($unc). '</pre>'. "\n";
				if (count($unc) === 1 && $unc[0]) {
					foreach ($array as $k => $v) {
						if ($k == $col) {
							unset ($array[$col]);
						}
					}
				}
			}
		}
	}

	public function getLastSQL()
	{
		return static::$query;
	}

	public static function deleteRows($rows)
	{
		global $db;
		//$c = get_called_class();
		//$sql = 'DELETE FROM '. $c::TABLE. ' WHERE '. $c::ID. ' IN ('. implode(', ', $rows). ')';
		$sql = 'DELETE FROM '. static::TABLE. ' WHERE '. static::ID. ' IN ('. implode(', ', $rows). ')';
		static::$query = $sql;
		try {
			$db->run($sql);
			$note = new Note(Array(
				'type' => Note::notice,
				'message' => sprintf( t("_Deleted rows %s"), implode(', ', $rows) ),
				'details' => sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,
				'message' => sprintf( t("_Cannot delete rows %s"), $done),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}

}
