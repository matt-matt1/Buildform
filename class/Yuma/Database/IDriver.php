<?php
declare(strict_types=1);
namespace Yuma\Database;

use DateInterval;
use DateTimeInterface;

interface IDriver {
	public const
		SupportSequence = 'sequence',
		SupportSelectUngroupedColumns = 'ungrouped_cols',
		SupportMultiInsertAsSelect = 'insert_as_select',
		SupportMultiColumnAsOrCond = 'multi_column_as_or',
		SupportSubselect = 'subselect',
		SupportSchema = 'schema';
	public function connect($parameter=null);
//	public function query($query);
	function query(string $queryString, array $params)/*: ResultDriver*/;
    public function escapeField($field);
	public function escapeValue($value);

	function beginTransaction()/*: void*/;

	function commit()/*: void*/;

	function rollBack()/*: void*/;

	/**
	 * Returns the ID of the last inserted row or sequence value.
	 */
	function getInsertId(?string $sequence = null)/*: string*/;

	/**
	 * Delimits string for use in SQL statement.
	 */
	function quote(string $string)/*: string*/;

	/**
	 * Delimits identifier for use in SQL statement.
	 */
	function delimite(string $name)/*: string*/;

	/**
	 * Formats date-time for use in a SQL statement.
	 */
	function formatDateTime(DateTimeInterface $value)/*: string*/;

	/**
	 * Formats date-time interval for use in a SQL statement.
	 */
	function formatDateInterval(DateInterval $value)/*: string*/;

	/**
	 * Encodes string for use in a LIKE statement.
	 */
	function formatLike(string $value, int $pos)/*: string*/;

	/**
	 * Injects LIMIT/OFFSET to the SQL query.
	 */
	function applyLimit(string &$sql, /*?*/int $limit=0, /*?*/int $offset=0)/*: void*/;

	/********************* reflection ****************d*g**/

	/**
	 * @return Reflection\Table[]
	 */
	function getTables()/*: array*/;

	/**
	 * Returns metadata for all columns in a table.
	 * @return Reflection\Column[]
	 */
	function getColumns(string $table)/*: array*/;

	/**
	 * Returns metadata for all indexes in a table.
	 * @return Reflection\Index[]
	 */
	function getIndexes(string $table)/*: array*/;

	/**
	 * Returns metadata for all foreign keys in a table.
	 * @return Reflection\ForeignKey[]
	 */
	function getForeignKeys(string $table)/*: array*/;

	/**
	 * Cheks if driver supports specific property
	 * @param  string  $item  self::SUPPORT_* property
	 */
	function isSupported(string $item)/*: bool*/;

}
