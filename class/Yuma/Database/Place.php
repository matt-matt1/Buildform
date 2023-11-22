<?php

namespace Yuma\Database;
class Place //extends Singleton
{
//	static public $instance = null;
	private $vars = Array(
		'dbname' => '',
		'table' => '',
		'colname' => '',
		'rownum' => -1,
	);
	//static private $dbname;
	//static private $table;
	//static private $colname;
	//static private $rownum;

	public function __construct(array $params=null)
	{
		global $metaHTML;
		if (!isset($metaHTML) || empty($metaHTML)) {
			$metaHTML = array();
		}
		if (isset($params) && !empty($params)) {
			$this->add($params);
		}
	}

	/**V
	 * Default set method
	 */
	public function __set($var, $value) {
		//if (isset($this->vars[$var])) {
			$this->vars[$var] = $value;
		//}
		/*$m = 'set'. $var;
		if (method_exists($this, $m)) {
			return $this->$m($value);
		}*/
	}

	/**
	 * Default get method
	 */
	public function __get($var) {
		if (isset($this->vars[$var])) {
			return $this->vars[$var];
		}
		/*$m = 'get'. $var;
		if (method_exists($this, $m)) {
			return $this->$m();
		}*/
	}

	/**
	 * Set the name of the current database
	 */
/*	static public function setDB(string $name) {
		self::$dbname = $name;
	}
 */
	/**
	 * Returns the name of the current database
	 */
/*	static public function getDB() {
		return self::$dbname;
	}
 */
	/**
	 * Set the name of the current database
	 */
/*	static public function setTable(string $name) {
		self::$table = $name;
	}
 */
	/**
	 * Returns the name of the current database
	 */
/*	static public function getTable() {
		return self::$table;
	}
*/
	/**
	 * Set the name of the current database
	 */
/*	static public function setCol(string $name) {
		self::$colname = $name;
	}
*/
	/**
	 * Returns the name of the current database
	 */
/*	static public function getCol() {
		return self::$colname;
	}
*/
	/**
	 * Set the name of the current database
	 */
/*	static public function setRow(string $num) {
		self::$rownum = $num;
	}
*/
	/**
	 * Returns the name of the current database
	 */
/*	static public function getRow() {
		return self::$rownum;
	}
*/
}
