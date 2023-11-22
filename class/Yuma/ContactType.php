<?php

namespace Yuma;

class ContactType
{
//	const equal = "=";//-1;
//	const startsWith = "like";//-2;
//	const error = "error";//-3;
	private $defaults = Array(
		'type_id' => 'INT',
		'name' => 'VARCHAR'
	);
	private $params = Array();
	private static $db=null;
	
	public function __construct( array $params=null ) {
//		global $db;
//		static::$db = $db;
//		$this->defaults['type'] = static::notice;
//		$this->defaults['ok'] = t("_OK");
//		$this->defaults['ignore'] = t('_Ignore');
		$this->params = array_merge($this->defaults, (array)$params);
		unset($this->defaults);
	}

	/**
	 * Inserts a new business or if a valid business_id is supplied updates that business
	 *
	 * Calls the makeSQL method and perform the database operation
	 */
	public function insert($type_id=null)
	{
//function makeSQL(string $tbl, string $idName, array $els, $id=null, $row=null)
		$sql = makeSQL('types', 'type_id', $this->params, $type_id);
	}
	public function update($type_id=null)
	{
		return insert($type_id);
	}

	/**
	 * Returns an array of all business contact types
	 *
	 */
	//public function getAll(string $filterName=null, $filterValue=null, $filterOperation=null)
	// * only returns the records where the $filterName is $filterOperation to $filterValue
	public static function getAll()
	{
		global $db;
		$qry = 'SELECT * FROM `yumatech_organise`.`types`;';
		try {
			return $db->run($qry)->fetchAll(\PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$note = new Note(Array(
				'type' => Note::error,//'error',
				'message' => t("_Cannot get list of business contact types"),
				'details' => implode(' - ', (array)$e->errorInfo). "<br>\n". sprintf( t('_Query %s'), $qry ),
			));
			echo $note->display();
		}
	}

}
