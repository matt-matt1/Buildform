<?php

namespace Yuma\Database;

class Store /*extends Db*/ {
	private $db_name = 'buildForms';
	private $table_name = 'settings';

	/**
	 * Creates the database settings table if abscent
	 */
	public function __construct($name=null)
	{
		//parent::__construct();
		global $db;
		$sql = "use {$this->db_name}";
		try
		{
			$db->run($sql);
//			echo "Store done : {$sql}.\n";
			$name = (isset($name) && !empty($name)) ? $name : 'settings';
			/* CREATE TABLE `tj_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
			) ENGINE=InnoDB AUTO_INCREMENT=15541 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci */
			$this->table_name = $name;
			$sql = "CREATE TABLE IF NOT EXISTS `{$name}` ("
				. "`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
				. "`key` VARCHAR(191) NOT NULL,"
				. "`value` LONGTEXT NOT NULL,"
				. "PRIMARY KEY (`id`),"
				. "UNIQUE KEY `key` (`key`)"
				. ")";
			try
			{
				//return $this->pdo->query($sql)/*->fetch()*/;
				//return $this->driver->run($sql)/*->fetch()*/;
				return $db->run($sql)/*->fetch()*/;
			} catch (PDOException $ex)
			{
				echo "Store got the following PDOException while doing '{$sql}' : ";
				return $ex. " ({$sql})";
			}
		} catch (PDOException $ex)
		{
			echo "Store failed while doing '{$sql}' got PDOException : ";
		}
	}

	/**
	 * Returns a single value from the database
	 * that is associated to the supplied key
	 */
	public function get($name)
	{
		global $db;
		$sql = "SELECT `key`, `value` FROM `{$this->table_name}` WHERE `key`='{$name}' LIMIT 1";
		//$sql = "SELECT `value` FROM `{$this->table_name}` WHERE `key`='{$name}'";
		try {
			$logger = new Logger();
			$logger->log ('before dbquery');
		} catch (Exception $e) {
			error_log ('error_log: before dbquery');
		}
		try {
			/*
			$stmt = $pdo->prepare("SELECT `key`, `value` FROM `{$this->table_name}` WHERE `key` LIKE ?");
			$stmt->execute(array("%$name%"));
			// iterating over a statement
			foreach($stmt as $row) {
				echo $row['name'];
			}
//			- or -
			while($row = $stmt->fetch()) {
				echo $row['name'];
			}
//			- or -
			$data = $stmt->fetchAll();
			*/
			//return $this->run($sql)->fetch();
			//return $db->run($sql)->fetch();
			$v = $db->run($sql)->fetch(\PDO::FETCH_ASSOC);
//			echo 'got: '. $v. ' using '. $sql. '<'. "\n";
//			return $v['value'];
		try {
			$logger = new Logger();
			$logger->log ('dbquery ==> '. json_encode($v));
		} catch (Exception $e) {
			error_log ('error_log: dbquery ==> '. json_encode($v));
		}
			try {
				$val = unserialize($v['value']);
			} catch (Exception|TypeError $ex) {
				$val = $v['value'];
			}
			return $val;
		} catch (PDOException|TypeError $ex) {
//			return $ex;
			return null;
		}
	}


	/**
	 * Stores the value under the key in the database
	 * @param $name database table key, existing or new
	 * @param $value value to be stored for the key
	 */
	public function set($name, $value)
	{
/*		$sql = ($this->get($name) == null) ?
			"INSERT INTO `{$this->table_name}` (`key`,`value`) VALUES ('{$name}','{$value}')" :
			"UPDATE `{$this->table_name}` SET `value`='{$value}' WHERE `key`='{$name}'";*/
		global $db;
		//$sql = "INSERT INTO `{$this->table_name}` (`key`,`value`) VALUES ('{$name}','{$value}')";
		if (is_array($value))
		{
			$val = serialize($value);
		} else
			$val = $value;
		//$sql = "INSERT IGNORE INTO `{$this->table_name}` (`key`,`value`) VALUES ('{$name}','{$value}')";
		$sql = "INSERT INTO `{$this->table_name}` (`key`,`value`) VALUES ('{$name}', '{$val}')";
//		echo 'trying(1) '. $sql;
//		$sql = "INSERT INTO `{$this->table_name}` (`key`,`value`) VALUES ({$name},{$value}) ON DUPLICATE KEY UPDATE `value`=VALUES({$value})";
		try
		{
			//return $this->run($sql);
//			return $db->run($sql);
			$r = $db->run($sql);
//			echo 'set result:'. print_r($r, true). '<';
//			echo 'set value:'. $value. '<';
			return $value;
		} catch (PDOException $ex)	// PDOException: SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'flavour' for key 'key'
		{
			try
			{
				$sql = "UPDATE `{$this->table_name}` SET `value`='{$val}' WHERE `key`='{$name}'";
//		echo ' failed. trying(2) '. $sql;
				//return $db->run($sql);
				$r = $db->run($sql);
		//		echo 'set '. $ex .' : (2) result:'. print_r($r, true). '<';
				return $value;
			} catch (PDOException $ex)
			{
				return $ex;
			}
			//echo "Store cannot set {$value} on {$name} : {$ex} using {$sql}\n";
/*			return $ex;*/
		}
	}

	/**
	 * Returns a single value from the database
	 * that is associated to the supplied key
	 */
	public function load($name, $value)
	{
			//echo 'load::name='. print_r($name, true). ', '. print_r($value, true). '!'. "\n";
		global $db;
		$sql = "SELECT `key`, `value` FROM `{$this->table_name}` WHERE `key`='{$name}'";
		try
		{
			//return $this->run($sql)->fetch();
			//return $db->run($sql)->fetch();
			$v = $db->run($sql)->fetch(\PDO::FETCH_ASSOC);
			//echo 'got: '. print_r($v, true). ' using '. $sql. '<'. "\n";
			//return isset($v['value']) ? $v['value'] : null;
			if (isset($v['value']) && !is_integer($v['value']))
			{
				$orig = error_reporting();
				error_reporting(0);
				$val = html_entity_decode($v['value'], ENT_QUOTES);
				//if (@unserialize( $val ) !== false) {
				if (is_serialized( $val )) {
					$val = unserialize( $val );
				}
				error_reporting($orig);
/*				if (false === $val) {
					throw new Exception('Cannot unserialize '. $val);
				}*/
/*				}*/
				/*				try
				{
					//$val = unserialize($v['value']);
					//ob_start();
					$val = html_entity_decode($v['value'], ENT_QUOTES);
					$val = @unserialize( $val );
					//ob_end_clean();
				//} catch (Exception|TypeError $ex)
				} catch (ErrorException $ex)
				{
					$val = $v['value'];
				}
				//return $v['value'];
*/				return $val;
			} else {
				$this->set($name, $value);
/*				if (is_array($value))
				{
					$value = serialize($value);
				}
				$sql = "INSERT IGNORE INTO `{$this->table_name}` (`key`,`value`) VALUES ('{$name}','{$value}')";
				try
				{
					$r = $db->run($sql);
					//return false;
					//return $value. $sql;
					return $value;
				} catch (PDOException $ex)
				{
					return $ex;
				}*/
			}
		} catch (PDOException $ex)
		{
			$this->set($name, $value);
/*			if (is_array($value))
			{
				$value = serialize($value);
			}
			$sql = "INSERT IGNORE INTO `{$this->table_name}` (`key`,`value`) VALUES ('{$name}','{$value}')";
			try
			{
				$r = $db->run($sql);
				//return false;
				//return $value. $sql;
				return $value;
			} catch (PDOException $ex)
			{
				return $ex;
			}*/
		}
	}

}
