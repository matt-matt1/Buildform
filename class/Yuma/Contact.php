<?php

namespace Yuma;

class Contact extends CRUD
{
//	const equal = "=";//-1;
//	const startsWith = "like";//-2;
//	const error = "error";//-3;
	const TABLE = 'yumatech_organise.contact';
	const ID = 'contact_id';
	const NAME = 'contact_first';
	private $defaults = Array(
		'contact_id' => 'INT',
		'business_id' => 'INT',
		'user_id' => 'INT',
		'contact_first' => 'VARCHAR',
		'contact_last' => 'VARCHAR',
		'contact_type_id' => 'INT',
		//'contact_type' => 'INT',
		'contact_number' => 'VARCHAR',
	);
	//private $params = Array();
	protected $params = Array();
	protected static $query = null;
	//protected static $tbl = 'yumatech_organise.contact';
	//protected static $id = 'business_id';
	//protected static $name = 'contact_id';
/*
	public function __construct( array $params=null ) {
//		$this->defaults['type'] = static::notice;
//		$this->defaults['ok'] = t("_OK");
//		$this->defaults['ignore'] = t('_Ignore');
		$this->params = array_merge($this->defaults, (array)$params);
	}
 */
	public function getFromPOST($business_id=0, $row=0)
	{
		$contacts = filter_input(INPUT_POST, 'contact_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		//$business_id = filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$business_id = filter_input(INPUT_POST, 'business_id', FILTER_DEFAULT);
		//$user_id = filter_input(INPUT_POST, 'user_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$user_id = filter_input(INPUT_POST, 'user_id', FILTER_DEFAULT);
		$contact_first = filter_input(INPUT_POST, 'contact_first', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$contact_last = filter_input(INPUT_POST, 'contact_last', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$contact_type = filter_input(INPUT_POST, 'contact_type_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		//$contact_type = filter_input(INPUT_POST, 'contact_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		$array = array(
			//'contact_id' => $contacts[$row]
			'business_id' => $business_id,
			'user_id' => $user_id,
			'contact_first' => $contact_first[$row],
			'contact_last' => $contact_last[$row],
			'contact_type_id' => $contact_type[$row],
			//'contact_type' => $contact_type[$row],
			'contact_number' => $contact_number[$row],
		);
//		if (!empty($contact_date[$row])) {
//			$array['date_created'] = $contact_date[$row];
//		}
		if (is_numeric($contacts[$row])) {
			$array['contact_id'] = $contacts[$row];
		}
		$this->params = $array;
		return $array;
	}
/*
		$row = 0;
		foreach ($contacts as $k => $v) {
//			echo 'contacts '. $k. ': <pre>'. print_r($v, true). "</pre><br>\n";
*/
	/**
	 * Inserts a new business or if a valid business_id is supplied updates that business
	 *
	 * Calls the makeSQL method and perform the database operation
	 */
/*	public function insert($business_id=null, $row=0)
	{
		global $db;
//		echo 'contact::insert('. $contact_id. ', '. $row. ')';
		$contacts = filter_input(INPUT_POST, 'contact_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
//function makeSQL(string $tbl, string $idName, array $els, $id=null, $row=null)
		$sql = makeSQL('contact', 'contact_id', $this->params, $contacts[$row]/ *filter_input(INPUT_POST, 'contact_id', FILTER_DEFAULT)* /);
		//$sql = makeSQL('contact', 'contact_id', $this->params, $business_id, $row);
		static::$query = $sql;
		try {
			return $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,//'error',
				'message' => t("_Cannot get list of business contact types"),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}
	public function update($contact_id=null, $row=0)
	{
		return $this->insert($contact_id, $row);
	}
 */
	/**
	 * Returns an array of business contacts for a given business_id
	 *
	 */
	public static function getForBusinessId(int $business_id)
	{
		global $db;
		$sql= 'SELECT * FROM `yumatech_organise`.`contact` WHERE business_id='. $business_id;
		static::$query = $sql;
		try {
			return $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,//'error',
				'message' => t("_Cannot get list of business contacts"),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}

	/**
	 * Returns an array of all business contact types
	 *
	 */
	//public function getAll(string $filterName=null, $filterValue=null, $filterOperation=null)
	// * only returns the records where the $filterName is $filterOperation to $filterValue
/*	public static function getAll()
	{
		global $db;
		$sql = 'SELECT * FROM `yumatech_organise`.`contact`';
		static::$query = $sql;
		try {
			return $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,//'error',
				'message' => t("_Cannot get list of business contact types"),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $sql ),
			));
			echo $note->display();
		}
	}
 */
	/**
	 * Returns an array of all business contact types
	 */
/*	public static function getAllLang()
	{
		global $db;
		$sql = 'SELECT c.business_id,c.first,c.last,t.name,c.number,c.date_created FROM `yumatech_organise`.`contact` c INNER JOIN `yumatech_organise`.`types` t ON c.type_id=t.type_id';
		try {
			return $db->run($sql)->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,//'error',
				'message' => t("_Cannot get list of business contact types"),
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
  */
}
