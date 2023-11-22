<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Yuma\Database;
//namespace Nette\Database\Drivers;

//use Nette;


/**
 * Supplemental MS SQL database driver.
 */
class MsSql_Driver extends PdoDriver
{
	public $id = '';

	public function delimite(string $name)/*: string*/
	{
		// @see https://msdn.microsoft.com/en-us/library/ms176027.aspx
		return '[' . str_replace(['[', ']'], ['[[', ']]'], $name) . ']';
	}


	public function formatDateTime(\DateTimeInterface $value)/*: string*/
	{
		return $value->format("'Y-m-d H:i:s'");
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


	//public function applyLimit(string &$sql, ?int $limit, ?int $offset)/*: void*/

    /**
     * @param string $sql
     * @param int|null $limit
     * @param int|null $offset
     * @return void
     */
    public function applyLimit(string &$sql, /*?*/int $limit=0, /*?*/int $offset=0)/*: void*/
    //public function applyLimit(string &$sql, int|null $limit, ?int $offset)/*: void*/
	{
		if ($offset) {
			//throw new Nette\NotSupportedException('Offset is not supported by this database.');
			throw new Exception('Offset is not supported by this database.');

		} elseif ($limit < 0) {
			//throw new Nette\InvalidArgumentException('Negative offset or limit.');
			throw new Exception('Invalid Argument - Negative offset or limit.');

		} elseif ($limit !== null) {
			$sql = preg_replace('#^\s*(SELECT(\s+DISTINCT|\s+ALL)?|UPDATE|DELETE)#i', '$0 TOP ' . (int) $limit, $sql, 1, $count);
			if (!$count) {
				//throw new Nette\InvalidArgumentException('SQL query must begin with SELECT, UPDATE or DELETE command.');
				throw new Exception('Invalid Argument - SQL query must begin with SELECT, UPDATE or DELETE command.');
			}
		}
	}


	/********************* reflection ****************d*g**/


	public function getTables()/*: array*/
	{
		return $this->pdo->query('SELECT TABLE_SCHEMA, TABLE_NAME, TABLE_TYPE FROM INFORMATION_SCHEMA.TABLES')->fetchAll(
			\PDO::FETCH_FUNC,
			fn($schema, $name, $type) => new Nette\Database\Reflection\Table($schema . '.' . $name, $type === 'VIEW'),
		);
	}


	public function getColumns(string $table)/*: array*/
	{
		[$table_schema, $table_name] = explode('.', $table);
		$columns = [];

		$query = <<<X
			SELECT
				COLUMN_NAME,
				DATA_TYPE,
				CHARACTER_MAXIMUM_LENGTH,
				NUMERIC_PRECISION,
				IS_NULLABLE,
				COLUMN_DEFAULT,
				DOMAIN_NAME
			FROM
				INFORMATION_SCHEMA.COLUMNS
			WHERE
				TABLE_SCHEMA = {$this->pdo->quote($table_schema)}
				AND TABLE_NAME = {$this->pdo->quote($table_name)}
			X;

		foreach ($this->pdo->query($query, \PDO::FETCH_ASSOC) as $row) {
/*			$columns[] = new Nette\Database\Reflection\Column(
				name: $row['COLUMN_NAME'],
				table: $table,
				nativeType: $row['DATA_TYPE'],
				size: $row['CHARACTER_MAXIMUM_LENGTH'] ?? $row['NUMERIC_PRECISION'] ?? null,
				nullable: $row['IS_NULLABLE'] === 'YES',
				default: $row['COLUMN_DEFAULT'],
				autoIncrement: $row['DOMAIN_NAME'] === 'COUNTER',
				primary: $row['COLUMN_NAME'] === 'ID',
				vendor: $row,
			);*/
		}

		return $columns;
	}


	public function getIndexes(string $table)/*: array*/
	{
		[, $table_name] = explode('.', $table);
		$indexes = [];

		$query = <<<X
			SELECT
				 name_index = ind.name,
				 id_column = ic.index_column_id,
				 name_column = col.name,
				 ind.is_unique,
				 ind.is_primary_key
			FROM
				sys.indexes ind
				INNER JOIN sys.index_columns ic ON  ind.object_id = ic.object_id and ind.index_id = ic.index_id
				INNER JOIN sys.columns col ON ic.object_id = col.object_id and ic.column_id = col.column_id
				INNER JOIN sys.tables t ON ind.object_id = t.object_id
			WHERE
				 t.name = {$this->pdo->quote($table_name)}
			ORDER BY
				 t.name, ind.name, ind.index_id, ic.index_column_id
			X;

		foreach ($this->pdo->query($query) as $row) {
			$id = $row['name_index'];
			$indexes[$id]['name'] = $id;
			$indexes[$id]['unique'] = $row['is_unique'] !== 'False';
			$indexes[$id]['primary'] = $row['is_primary_key'] !== 'False';
			$indexes[$id]['columns'][$row['id_column'] - 1] = $row['name_column'];
		}

		return array_map(fn($data) => new Nette\Database\Reflection\Index(...$data), array_values($indexes));
	}


	public function getForeignKeys(string $table)/*: array*/
	{
		[$table_schema, $table_name] = explode('.', $table);
		$keys = [];

		$query = <<<X
			SELECT
				obj.name AS [fk_name],
				col1.name AS [column],
				tab2.name AS [referenced_table],
				col2.name AS [referenced_column]
			FROM
				sys.foreign_key_columns fkc
				INNER JOIN sys.objects obj
					ON obj.object_id = fkc.constraint_object_id
				INNER JOIN sys.tables tab1
					ON tab1.object_id = fkc.parent_object_id
				INNER JOIN sys.schemas sch
					ON tab1.schema_id = sch.schema_id
				INNER JOIN sys.columns col1
					ON col1.column_id = parent_column_id AND col1.object_id = tab1.object_id
				INNER JOIN sys.tables tab2
					ON tab2.object_id = fkc.referenced_object_id
				INNER JOIN sys.columns col2
				ON col2.column_id = referenced_column_id AND col2.object_id = tab2.object_id
			WHERE
				tab1.name = {$this->pdo->quote($table_name)}
			X;

		foreach ($this->pdo->query($query) as $row) {
			$id = $row['fk_name'];
			$keys[$id]['name'] = $id;
			$keys[$id]['columns'][] = $row['column'];
			$keys[$id]['targetTable'] = $table_schema . '.' . $row['referenced_table'];
			$keys[$id]['targetColumns'][] = $row['referenced_column'];
		}

		return array_map(fn($data) => new Nette\Database\Reflection\ForeignKey(...$data), array_values($keys));
	}


	public function getColumnTypes(\PDOStatement $statement)/*: array*/
	{
		return Nette\Database\Helpers::detectTypes($statement);
	}


	public function isSupported(string $item)/*: bool*/
	{
		return $item === self::SupportSubselect;
	}

	public function connect($parameter = null) {
		return parent::connect($parameter);
	}

	public function escapeField($field) {
		return parent::escapeField($field);
	}

	public function escapeValue($value) {
		return parent::escapeValue($value);
	}

}
