<?php

declare(strict_types=1);
namespace Yuma\Database;
use PDO;
use PDOException;
use Yuma\Logger;

class MySQL_Driver extends PDODriver//EscapeWith implements IDriver {
{
	public const
		ERROR_ACCESS_DENIED = 1045,
		ERROR_DUPLICATE_ENTRY = 1062,
		ERROR_DATA_TRUNCATED = 1265;
	public $id = 'MySQL';
	//private $pdo = null;
	protected $options = array(
		PDO::ATTR_PERSISTENT => true,
//		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		//PDO::MYSQL_ATTR_SSL_KEY => '/path/to/client-key.pem',
        //PDO::MYSQL_ATTR_SSL_CERT => '/path/to/client-cert.pem',
        //PDO::MYSQL_ATTR_SSL_CA => '/path/to/ca-cert.pem'
	);
	protected $attribs = Array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_EMULATE_PREPARES => false,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	);

	public function __construct() {
		GLOBAL $DB_OPTS;
		$DB_OPTS[PDO::ATTR_PERSISTENT] = true;
/*
		if (!defined('DB_OPTS') || empty(DB_OPTS)) {
		//if (!isset($GLOBALS['data']['DB_OPTS']) || empty($GLOBALS['data']['DB_OPTS'])) {
			define('DB_OPTS', array(
			//$GLOBALS['data']['DB_OPTS'] = array(
			));
		}
		if (empty(DB_OPTS)) {
			DB_OPTS[PDO::ATTR_PERSISTENT] = true;
		}*/
	}

	public function connect($parameter=null) {
        $pre = __METHOD__ . '() ';
		try {
			if (!defined('DB_HOST') || !defined('DB_NAME')) {
			//if (!isset($GLOBALS['data']['DB_HOST']) || !isset($GLOBALS['data']['DB_NAME'])) {
				return 'Must define DB_HOST /& DB_NAME'. PHP_EOL;
				//echo 'Must define DB_HOST /& DB_NAME';
				//return true;
			}
			$dsn = strtolower($this->id). ":host=". DB_HOST. "; dbname=". DB_NAME. "";
			//$dsn = strtolower($this->id). ":host=". $GLOBALS['data']['DB_HOST']. "; dbname=". $GLOBALS['data']['DB_NAME']. "";
			if (defined('DB_CHAR')) {
			//if (isset($GLOBALS['data']['DB_CHAR'])) {
				$dsn .= ';charset='. DB_CHAR;
				//$dsn .= ';charset='. $GLOBALS['data']['DB_CHAR'];
			}
			$mysql_dbh = new PDO($dsn, DB_USER, DB_PASS, (array)DB_OPTS);
			//$mysql_dbh = new PDO($dsn, $GLOBALS['data']['DB_USER'], $GLOBALS['data']['DB_PASS'], (array)$GLOBALS['data']['DB_OPTS']);
			if (defined('DB_TIME')) {
			//if (isset($GLOBALS['data']['DB_TIME'])) {
				$mysql_dbh->setAttribute( PDO::ATTR_TIMEOUT, DB_TIME );
				//$mysql_dbh->setAttribute( PDO::ATTR_TIMEOUT, $GLOBALS['data']['DB_TIME'] );
			}
            $mysql_dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            //$mysql_dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );//may cause General error: 2014 Cannot execute queries while other unbuffered queries are active

			if (isset($parameter) && $parameter == 'test') {
				return false;
			}
//			return $mysql_dbh;
			$this->pdo = $mysql_dbh;
        } catch( PDOException $e ) {
			if (isset($parameter) && $parameter == 'test') {
				return 'Database connection test failed: '. $e->getMessage();
			}
            $log = 'Unable to connect to database('. $dsn. '): '. $e->getMessage(). PHP_EOL;
            try {
                //$logger = new Logger ('(L)'. );
                $logger = new Logger();
                $logger->log ($pre. $log);
            } catch (Exception $e) {
                error_log ($pre. $log);
            }
			//return $log;//'Unable to connect to database: '. $e->getMessage(). PHP_EOL;
            throw new PDOException($log);
        }
	}
	/*	specific:*/
	public function startTransaction() {
		if (!$this->pdo) {
			$this->connect();
		}
		$this->transactionStarted = true;
		$this->pdo->beginTransaction();
	}
/*
	public function query($query) {
		if (!$this->pdo) {
			$this->connect();
		}
		try {
             $this->pdo->execute($query);
        } catch (PDOException $e) {
			if ($this->transactionStarted) {
				$this->pdo->rollBack();
            }
        }
	}
*/
/* */
    public function escapeField($field) {
		return new EscapeWith( $field, '`' );
		//trim( $field, '`' );
        //return '`'. $field. '`';
    }

	public function escapeValue($value) {
		return new EscapeWith( $value, "'" );
		//trim( $value, "'" );
        //return "'". $value. "'";
	}

	public function run($sql, $args = NULL)
    {
        $pre = __METHOD__ . ' ';//'DB::run(): ';
		if (!$this->pdo) {
			$this->connect();
		}
        //try {
            $stmt = $this->pdo->prepare($sql);
            if (!empty($args)) {
                foreach ($args as $key => &$val) {
                    $stmt->bindParam($key, $val);
                }
            }
            //try {
        //$stmt->closeCursor();
                $stmt->execute($args);
                //$results = $stmt->fetchAll();
                //$stmt->closeCursor();
/*            } catch (PDOException $e) {
                //$stmt->fetchAll();
                $log = 'Cannot execute stamement for '. $sql. (isset($args) ? '('. implode(', ', $args). ')' : '');
                $log .= ' '. $e->getMessage();
                try {
                    $logger = new Logger();
                    $logger->log ($pre. $log);
                } catch (\Exception $e) {
                    error_log ($pre. $log);
                }
                return $stmt;
            }*/
/*        } catch (PDOException $e) {

            $log = 'Cannot prepare stamement for '. $sql. (isset($args) ? '('. implode(', ', $args). ')' : '');
            $log .= ' '. $e->getMessage();
            try {
                $logger = new Logger();
                $logger->log ($pre. $log);
            } catch (\Exception $e) {
                error_log ($pre. $log);
            }
            return false;
        }*/
        return $stmt;
	}


	public function detectExceptionClass(PDOException $e)//: ?string
	{
		$code = $e->errorInfo[1] ?? null;
		if (in_array($code, [1216, 1217, 1451, 1452, 1701], true)) {
			return Nette\Database\ForeignKeyConstraintViolationException::class;

		} elseif (in_array($code, [1062, 1557, 1569, 1586], true)) {
			return Nette\Database\UniqueConstraintViolationException::class;

		} elseif ($code >= 2001 && $code <= 2028) {
			return Nette\Database\ConnectionException::class;

		} elseif (in_array($code, [1048, 1121, 1138, 1171, 1252, 1263, 1566], true)) {
			return Nette\Database\NotNullConstraintViolationException::class;

		} else {
			return null;
		}
	}

	/* IDriver compulsary */
//	function beginTransaction()/*: void*/{}

//	function commit()/*: void*/{}

//	function rollBack()/*: void*/{}

	/**
	 * Returns the ID of the last inserted row or sequence value.
	 */
//	function getInsertId(?string $sequence = null)/*: string*/{}

	/**
	 * Delimits string for use in SQL statement.
     * @param string $string
     * @param int $type
     */
	//function quote($value, $pdotype = PDO::PARAM_STR)/*: string*/
	function quote(string $string, int $pdotype = PDO::PARAM_STR)
	{
		if (!$this->pdo) {
			$this->connect();
		}
		if ($pdotype == PDO::PARAM_INT)
			return (int)$string;
		return $this->pdo->quote($string, $pdotype);
		return $this->pdo->quote($string);
	}

	/**
	 * Delimits identifier for use in SQL statement.
	 */
	//function delimite(string $name)/*: string*/{}
	public function delimite(string $name)//: string
	{
		// @see http://dev.mysql.com/doc/refman/5.0/en/identifiers.html
		return '`' . str_replace('`', '``', $name) . '`';
	}
	/**
	 * Formats date-time for use in a SQL statement.
	 */
//	function formatDateTime(\DateTimeInterface $value)/*: string*/{}
	public function formatDateTime(\DateTimeInterface $value)//: string
	{
		return $value->format("'Y-m-d H:i:s'");
	}

	/**
	 * Formats date-time interval for use in a SQL statement.
	 */
	//function formatDateInterval(\DateInterval $value)/*: string*/{}
	public function formatDateInterval(\DateInterval $value)//: string
	{
		return $value->format("'%r%h:%I:%S'");
	}

	/**
	 * Encodes string for use in a LIKE statement.
	 */
	//function formatLike(string $value, int $pos)/*: string*/{}
	public function formatLike(string $value, int $pos)//: string
	{
		$value = str_replace('\\', '\\\\', $value);
		$value = addcslashes(do_mbstr('substr', $this->pdo->quote($value), 1, -1), '%_');
		return ($pos <= 0 ? "'%" : "'") . $value . ($pos >= 0 ? "%'" : "'");
	}

	/**
	 * Injects LIMIT/OFFSET to the SQL query.
	 */
	//function applyLimit(string &$sql, ?int $limit, ?int $offset)/*: void*/{}
	public function applyLimit(string &$sql, /*?*/int $limit=0, /*?*/int $offset=0)//: void
	{
		if ($limit < 0 || $offset < 0) {
			//throw new Nette\InvalidArgumentException('Negative offset or limit.');
			throw new Exception('Negative offset or limit.');

		} elseif ($limit !== null || $offset) {
			// see http://dev.mysql.com/doc/refman/5.0/en/select.html
			$sql .= ' LIMIT ' . ($limit ?? '18446744073709551615')
				. ($offset ? ' OFFSET ' . $offset : '');
		}
	}

	/********************* reflection ****************d*g**/

	/**
	 * @return Reflection\Table[]
	 */
	//function getTables()/*: array*/{}
/*	public function getTables()//: array
	{
		return $this->pdo->query('SHOW FULL TABLES')->fetchAll(
			PDO::FETCH_FUNC,
			fn($name, $type) => new Nette\Database\Reflection\Table($name, $type === 'VIEW'),
		);
	}
*/
	/**
	 * Returns metadata for all columns in a table.
	 * @return Reflection\Column[]
	 */
	//function getColumns(string $table)/*: array*/{}
	public function getColumns(string $table)//: array
	{
		$columns = [];
		foreach ($this->pdo->query('SHOW FULL COLUMNS FROM ' . $this->delimite($table), PDO::FETCH_ASSOC) as $row) {
			$type = explode('(', $row['Type']);
/*			$columns[] = new Nette\Database\Reflection\Column(
				name: $row['Field'],
				table: $table,
				nativeType: $type[0],
				size: isset($type[1]) ? (int) $type[1] : null,
				nullable: $row['Null'] === 'YES',
				default: $row['Default'],
				autoIncrement: $row['Extra'] === 'auto_increment',
				primary: $row['Key'] === 'PRI',
				vendor: $row,
);*/
		}

		return $columns;
	}

	/**
	 * Returns metadata for all indexes in a table.
	 * @return Reflection\Index[]
	 */
	//function getIndexes(string $table)/*: array*/{}
	public function getIndexes(string $table)//: array
	{
		$indexes = [];
		foreach ($this->pdo->query('SHOW INDEX FROM ' . $this->delimite($table)) as $row) {
			$id = $row['Key_name'];
			$indexes[$id]['name'] = $id;
			$indexes[$id]['unique'] = !$row['Non_unique'];
			$indexes[$id]['primary'] = $row['Key_name'] === 'PRIMARY';
			$indexes[$id]['columns'][$row['Seq_in_index'] - 1] = $row['Column_name'];
		}

		//return array_map(fn($data) => new Nette\Database\Reflection\Index(...$data), array_values($indexes));
	}

	/**
	 * Returns metadata for all foreign keys in a table.
	 * @return Reflection\ForeignKey[]
	 */
	//function getForeignKeys(string $table)/*: array*/{}
	public function getForeignKeys(string $table)//: array
	{
		$keys = [];
        $query = sprintf("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
  AND REFERENCED_TABLE_NAME IS NOT NULL
  AND TABLE_NAME = %s", $this->pdo->quote($table));
		foreach ($this->pdo->query($query) as $row) {
			$id = $row['CONSTRAINT_NAME'];
			$keys[$id]['name'] = $id;
			$keys[$id]['columns'][] = $row['COLUMN_NAME'];
			$keys[$id]['targetTable'] = $row['REFERENCED_TABLE_NAME'];
			$keys[$id]['targetColumns'][] = $row['REFERENCED_COLUMN_NAME'];
		}

		//return array_map(fn($data) => new Nette\Database\Reflection\ForeignKey(...$data), array_values($keys));
	}
	public function getColumnTypes(PDOStatement $statement)//: array
	{
		$types = [];
		$count = $statement->columnCount();
		for ($col = 0; $col < $count; $col++) {
			$meta = $statement->getColumnMeta($col);
			if (isset($meta['native_type'])) {
				$types[$meta['name']] = $type = Nette\Database\Helpers::detectType($meta['native_type']);
				if ($type === Nette\Database\IStructure::FIELD_TIME) {
					$types[$meta['name']] = Nette\Database\IStructure::FIELD_TIME_INTERVAL;
				}
			}
		}

		return $types;
	}

	/**
	 * Cheks if driver supports specific property
	 * @param  string  $item  self::SUPPORT_* property
	 */
//	function isSupported(string $item)/*: bool*/{}
	public function isSupported(string $item)//: bool
	{
		// MULTI_COLUMN_AS_OR_COND due to mysql bugs:
		// - http://bugs.mysql.com/bug.php?id=31188
		// - http://bugs.mysql.com/bug.php?id=35819
		// and more.
		return $item === self::SupportSelectUngroupedColumns || $item === self::SupportMultiColumnAsOrCond;
	}

    function getTables()
    {
        // TODO: Implement getTables() method.
    }
}
