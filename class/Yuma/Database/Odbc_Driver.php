<?php

declare(strict_types=1);

namespace Yuma\Database;
//namespace Nette\Database\Drivers;

//use Nette;


/**
 * Supplemental ODBC database driver.
 */
class Odbc_Driver extends PdoDriver
{
	public $id = '';

	public function delimite(string $name)/*: string*/
	{
		return '[' . str_replace(['[', ']'], ['[[', ']]'], $name) . ']';
	}


	public function formatDateTime(\DateTimeInterface $value)/*: string*/
	{
		return $value->format('#m/d/Y H:i:s#');
	}


	public function formatDateInterval(\DateInterval $value)/*: string*/
	{
		//throw new Nette\NotSupportedException;
		throw new Exception('Not Supported');
	}


	public function formatLike(string $value, int $pos)/*: string*/
	{
		$value = strtr($value, ["'" => "''", '%' => '[%]', '_' => '[_]', '[' => '[[]']);
		return ($pos <= 0 ? "'%" : "'") . $value . ($pos >= 0 ? "%'" : "'");
	}


	public function applyLimit(string &$sql, ?int $limit, ?int $offset)/*: void*/
	{
		if ($offset) {
			//throw new Nette\NotSupportedException('Offset is not supported by this database.');
			throw new Exception('NotSupported - Offset is not supported by this database.');

		} elseif ($limit < 0) {
			//throw new Nette\InvalidArgumentException('Negative offset or limit.');
			throw new Exception('InvalidArgument - Negative offset or limit.');

		} elseif ($limit !== null) {
			$sql = preg_replace('#^\s*(SELECT(\s+DISTINCT|\s+ALL)?|UPDATE|DELETE)#i', '$0 TOP ' . $limit, $sql, 1, $count);
			if (!$count) {
				//throw new Nette\InvalidArgumentException('SQL query must begin with SELECT, UPDATE or DELETE command.');
				throw Exception('InvalidArgument - SQL query must begin with SELECT, UPDATE or DELETE command.');
			}
		}
	}


	/********************* reflection ****************d*g**/


	public function getTables()/*: array*/
	{
		//throw new Nette\NotImplementedException;
		throw new Exception('Not Implemented');
	}


	public function getColumns(string $table)/*: array*/
	{
		//throw new Nette\NotImplementedException;
		throw new Exception('Not Implemented');
	}


	public function getIndexes(string $table)/*: array*/
	{
		//throw new Nette\NotImplementedException;
		throw new Exception('Not Implemented');
	}


	public function getForeignKeys(string $table)/*: array*/
	{
		//throw new Nette\NotImplementedException;
		throw new Exception('Not Implemented');
	}


	public function getColumnTypes(\PDOStatement $statement)/*: array*/
	{
		return [];
	}


	public function isSupported(string $item)/*: bool*/
	{
		return $item === self::SupportSubselect;
	}
	
	public function escapeField($field) {
		return parent::escapeField($field);
	}

	public function escapeValue($value) {
		return parent::escapeValue($value);
	}

}
