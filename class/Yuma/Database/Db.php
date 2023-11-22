<?php

declare(strict_types=1);

namespace Yuma\Database;
use Yuma\Logger;
use PDO;
use PDOException;
use function Yuma\t;

/**
 * eg.
 * $dbh = $pdo->prepare('select name from users where id = ? or name = ? limit ?');
 * $dbh->bindValue(1, 100);
 * $dbh->bindValue(2, 'Bill');
 * $dbh->bindValue(3, 5);
 * $dbh->execute();
 * $result = $dbh->fetchAll(PDO::FETCH_OBJ);
 * foreach($result as $row) {
 * print($row->name);
 * }
 */
class Db /*extends Singleton*/ {
//	protected static $instance;
	//private $host = DB_HOST;        # database server
	//private $user = DB_USER;        # database login name
	//private $pass = DB_PASS;        # database login password
	//private $dbname = DB_NAME;      # database name
	private $driver;                # @object, Database driver (IDriver)
	private $pdo;                   # @object,  The PDO object
	private $stmt;                  # @object, PDO statement object
	private $isConnected = FALSE;   # @bool, Connected to the database
	private $params;                # @array, The parameters of the SQL query
	private $affected_rows = 0;     # @int, number of rows affected by SQL query
	private $results;               # @array, results of query
	private $options;
/*	private $transactionStarted = false;
	
	public function startTransaction() {
		if (!$this->driver->pdo)
			$this->connect();
		$this->transactionStarted = true;
		$this->driver->pdo->beginTransaction()
	}
	
	public function query($query) {
		if (!$this->driver->pdo)
			$this->driver->connect();
		try {
			$this->driver->pdo->execute($query);
		} catch (PDOException e) {
			if ($this->transactionStarted) {
				$this->driver->pdo->rollBack();
            }
        }
   }
 */
    public function __construct($driver) {
        $this->driver = $driver;
        $this->params = array();
	}

/*	public function getObject() {
		return $this->pdo;
}*/

    /**
     * Establish a PDO database connection, using and database driver
     * @param null $parameter Optional string Type of connection to establish
     * @return false|PDOException
     */
	public function connect($parameter=null) {
		$pre = 'DB::connect(): ';
		if (!defined('DB_HOST') || !defined('DB_NAME')) {
			return 'Must define DB_HOST /& DB_NAME'. PHP_EOL;
			//echo 'Must define DB_HOST /& DB_NAME';
			//return true;
		}
		$dsn = strtolower($this->driver->id). ': ';
		$dsn .= (defined('DB_SOCK')) ? 'host='. DB_HOST : 'unix_socket='. DB_HOST;
//		if (defined('DB_CHAR')) {
//			$dsn .= ';charset='. DB_CHAR;
//		}
		defined('DB_PORT') && $dsn .= ';port='. \DB_PORT;
		$dsn .= "; dbname=". DB_NAME. "";
		defined('DB_CHAR') && $dsn .= ';charset='. DB_CHAR;
		//dbname=
		//if (!defined('DB_OPTS')) {
		defined('DB_OPTS') ||
			define('DB_OPTS', array(
			));
		//}
		$this->options = isset($this->driver->options) ? array_merge((array)$this->driver->options, DB_OPTS) : DB_OPTS;
		try {
			//$dbh = new PDO($dsn, DB_USER, DB_PASS, (array)DB_OPTS);
			$dbh = new PDO($dsn, DB_USER, DB_PASS, (array)$this->options);

			if (defined('DB_TIME')) {
				$dbh->setAttribute( PDO::ATTR_TIMEOUT, DB_TIME );
			}
			defined('DB_ATTR') ||
				define('DB_ATTR', array(
				));
			//}
/*			$this->attribs = isset($this->driver->attribs) ? array_merge((array)$this->driver->attribs, DB_OPTS) : DB_OPTS;
			foreach($this->attribs as $k => $v) {
				$dbh->setAttribute( $k, $v );
			}*/
            //$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			//$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			//$dbh->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

			if (isset($parameter) && $parameter == 'test') {
				return false;
			}
//			return $mysql_dbh;
			$this->pdo = $dbh;
			$this->isConnected = TRUE;
/*		} catch( PDOException $e ) {
			error_log($e->getMessage());
			if (isset($parameter) && $parameter == 'test') {
				return 'Database connection test failed: '. $e;//->getMessage();
	public function connect($parameter=null) {
		if (!defined('DB_HOST') || !defined('DB_NAME')) {
		//if (!isset($GLOBALS['data']['DB_HOST']) || !isset($GLOBALS['data']['DB_NAME'])) {
			return 'Must define DB_HOST /& DB_NAME'. PHP_EOL;
			//echo 'Must define DB_HOST /& DB_NAME';
			//return true;
		}
		$dsn = strtolower($this->driver->id). ': ';
		$dsn .= (defined('DB_SOCK')) ? 'host='. DB_HOST : 'unix_socket='. DB_HOST;
		//$dsn .= (isset($GLOBALS['data']['DB_SOCK'])) ? 'host='. $GLOBALS['data']['DB_HOST'] : 'unix_socket='. $GLOBALS['data']['DB_HOST'];
//		if (defined('DB_CHAR')) {
//			$dsn .= ';charset='. DB_CHAR;
//		}
		defined('DB_PORT') && $dsn .= ';port='. DB_PORT;
		//isset($GLOBALS['data']['DB_PORT']) && $dsn .= ';port='. $GLOBALS['data']['DB_PORT'];
		$dsn .= "; dbname=". DB_NAME. "";
		//$dsn .= "; dbname=". $GLOBALS['data']['DB_NAME']. "";
		defined('DB_CHAR') && $dsn .= ';charset='. DB_CHAR;
		//isset($GLOBALS['data']['DB_CHAR']) && $dsn .= ';charset='. $GLOBALS['data']['DB_CHAR'];
		//if (!defined('DB_OPTS')) {
		defined('DB_OPTS') ||
		//isset($GLOBALS['data']['DB_OPTS']) ||
			define('DB_OPTS', array(
			//$GLOBALS['data']['DB_OPTS'] = array(
			));
		//)}
		$this->options = isset($this->driver->options) ? array_merge((array)$this->driver->options, DB_OPTS) : DB_OPTS;
		//$this->options = isset($this->driver->options) ? array_merge((array)$this->driver->options, $GLOBALS['data']['DB_OPTS']) : $GLOBALS['data']['DB_OPTS'];
		try {
			//$dbh = new PDO($dsn, DB_USER, DB_PASS, (array)DB_OPTS);
			$dbh = new PDO($dsn, DB_USER, DB_PASS, (array)$this->options);
			//$dbh = new PDO($dsn, $GLOBALS['data']['DB_USER'], $GLOBALS['data']['DB_PASS'], (array)$this->options);

			if (defined('DB_TIME')) {
			//if (isset($GLOBALS['data']['DB_TIME'])) {
				$dbh->setAttribute( PDO::ATTR_TIMEOUT, DB_TIME );
				//$dbh->setAttribute( PDO::ATTR_TIMEOUT, $GLOBALS['data']['DB_TIME'] );
			}
			defined('DB_ATTR') ||
			//isset($GLOBALS['data']['DB_ATTR']) ||
				define('DB_ATTR', array(
				//$GLOBALS['data']['DB_ATTR'] = array(
				));
			//}
			$this->attribs = isset($this->driver->attribs) ? array_merge((array)$this->driver->attribs, DB_OPTS) : DB_OPTS;//Deprecated: Creation of dynamic property
			//$this->attribs = isset($this->driver->attribs) ? array_merge((array)$this->driver->attribs, $GLOBALS['data']['DB_OPTS']) : $GLOBALS['data']['DB_OPTS'];
			foreach($this->attribs as $k => $v) {
				$dbh->setAttribute( $k, $v );
			}
            //$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			//$dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			//$dbh->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

			if (isset($parameter) && $parameter == 'test') {
				return false;
			}
//			return $mysql_dbh;
			$this->pdo = $dbh;
			$this->isConnected = TRUE;*/
		} catch( PDOException $e ) {
			$log = sprintf( t('_SQL error: %s'), $e->getMessage() );
			try {
				$logger = new Logger;
				$logger->log($pre. $log);
			} catch (\Exception $e) {
				error_log ($pre. $log);
			}
/*			(is_callable(array('Error', 'log'))) &&
				Error::log( $pre. $log ) ||
				error_log( $pre. $log );*/
			if (isset($parameter) && $parameter == 'test') {
				return 'Database connection test failed: '. $e;//->getMessage();
			}
			return 'Unable to connect to database: '. $e/*->getMessage()*/. PHP_EOL;
        }
/*        try {
            $mysql_dbh = new PDO("mysql:host=$host; dbname=$dbname", $user, $pass);
            $mysql_dbh->setAttribute( PDO::ATTR_TIMEOUT, $timeout );
            $mysql_dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            return $mysql_dbh;
        } catch( PDOException $e ) {
            echo 'Unable to connect to database: ' . $e->getMessage();
		}*/
        return false;
    }
/*
	public function connect($parameter) {
        return $this->driver->connect($parameter);
	}*/

	/**
	 * Clears the established database connection
	 * Sets $pdo to null and makes $isConnected false
	 */
    public function Disconnect() {
		$this->pdo = NULL;
		$this->isConnected = FALSE;
	}
/*
	public function query($parameter) {
		if (!$this->isConnected) {
			throw new Exception(t('Tried to access database when not connected.'));
		}
        try {
			$this->driver->query($parameter);
			return false;
        } catch( PDOException $e ) {
			//return $e->getMessage();
			//$obj = new stdClass();
			//$obj->id = $this->driver->id;
			$id = Array('driver' => $this->driver->id);
			//return (object)array_merge((array)$obj, (array)$e);
			return (object)array_merge($id, (array)$e);
            //return $e;
        }
	}
*/
    // this method is db-specific, so we call the driver
    public function escapeField($field) {
        return $this->driver->escapeField($field);
    }

    public function escapeValue($value) {
        return $this->driver->escapeValue($value);
	}

	public function run($sql, $args = NULL) {
		$pre = 'DB::run(): ';
		$log = $sql. (isset($args) ? '('. implode(', ', $args). ')' : '');
			try {
				$logger = new Logger();
				$logger->log (/*'(L)'.*/ $pre. $log);
			} catch (\Exception $e) {
				error_log ($pre. $log);
			}
		return $this->driver->run($sql, $args);
	}
	public function xrun($sql, $args = NULL) {
        try {
			return $this->driver->run($sql, $args);
			//return false;
		} catch( PDOException $e ) {
			/*			return $this->throwExceptionWithInfo($sql, (array)$args, $e);*/

            //return $e->getMessage();
            //return $e;
			$id = Array('driver' => $this->driver->id);
			//$reflection = new ReflectionClass($e);
			//$prefix = chr(0).'*'.chr(0);
			//$property = $reflection->getProperty('message');
			//$property->setAccessible(true);
			//$a = json_encode( (array)$e );
			//$b = (array)json_decode( str_replace('\u0000*\u0000', '', $a) );
			return (object)array_merge($id, (array)$e);
			//return (object)array_merge($id, (array)$reflection);
			//return (object)array_merge($id, (array)$b);
        }
	}

	public function getId()
	{
		return $this->driver->id;
	}

	/**
	 * Throw DbException with query info
	 *
	 * @param string        $sql
	 * @param array         $params
	 * @param PDOException $e
	 *
	 * @throws DbException
	 */
/*	protected function throwExceptionWithInfo($sql, array $params, PDOException $e)
	{
		$exception = new DbException($e->getMessage(), (int) $e->getCode(), $e);
		$exception->setSql($sql);
		$exception->setParams($params);
		throw $exception;
	}
*/

    /**
     * 	Every method which needs to execute a SQL query uses this method.
     *
     * 	1. If not connected, connect to the database.
     * 	2. Prepare Query.
     * 	3. Parameterize Query.
     * 	4. Execute Query.
     * 	5. On exception : Return Error Info.
     * 	6. Reset the Parameters.
     */
    private function go($query, $params = "") {
        # Connect to database
        if (!$this->isConnected) {
            $this->Connect();
        }

        try {
            # Prepare query
            $this->stmt = $this->pdo->prepare($query);

            # Add parameters to the parameter array
            $this->bindMore($params);

            # Bind parameters
            if (!empty($this->params)) {
                foreach ($this->params as $param => $value) {
                    $type = PDO::PARAM_STR;
                    switch ($value[1]) {
                        case is_int($value[1]):
                            $type = PDO::PARAM_INT;
                            break;
                        case is_bool($value[1]):
                            $type = PDO::PARAM_BOOL;
                            break;
                        case is_null($value[1]):
                            $type = PDO::PARAM_NULL;
                            break;
                    }
                    # Add type when binding the values to the column
                    $this->stmt->bindValue($value[0], $value[1], $type);
                }
            }

            # Execute SQL
            $this->stmt->execute();
        } catch (PDOException $e) {
            # Return Exception
            //if ($GLOBALS['data']['DEBUG']) { # if debug mode is True
            //if (defined('DEBUG') && DEBUG_DB) { # if debug mode is True
            if (defined('DEBUG') && \DEBUG) { # if debug mode is True
                $err = $e->getMessage();
            } else {
                # Send error message to the server log
                error_log($e->getMessage());
                $err = "Error Connecting To The Server";
            }
            throw new \Exception($err);
        }
        //}

        # Reset the parameters
        $this->params = array();
    }
 
    /**
     * 	@void
     *
     * 	Add the parameter to the parameter array
     * 	@param string $param
     * 	@param string $value
     */
    public function bind($param, $value) {
        $this->params[sizeof($this->params)] = [":" . $param, $value];
    }

    /**
     * 	@void
     *
     * 	Add more parameters to the parameter array
     * 	@param array $parray
     */
    public function bindMore($parray) {
        if (empty($this->params) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }

    /**
     *  If the SQL query  contains a SELECT or SHOW statement it returns an array containing all of the result set row
     * 	If the SQL statement is a DELETE, INSERT, or UPDATE statement it returns the number of affected rows
     *
     *  @param  string $query
     * 	@param  array  $params
     * 	@return mixed
     */
    public function query($query, $params = null) {
		$pre = 'DB::query(): ';

        # Query brake down
        $query = trim(str_replace("\r", " ", $query));

        $err = $this->go($query, $params);
        //$rawStatement = explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $query));
        # Which SQL statement is used and convert to lower case
        $statement = strtolower(explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $query))[0]);

        if ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            $this->affected_rows = $this->stmt->rowCount();
        }
        if ($statement === 'select' || $statement === 'show') {
            $this->results = $this->stmt->fetchAll();
        }
		//$log = sprintf( t('_SQL: %s'), $query. (isset($params) ? '('. implode(', ', $params). ')' : '') );
		$log = $query. (isset($params) ? '('. implode(', ', $params). ')' : '');
		try {
			$logger = new Logger;
			$logger->log($pre. $log);
		} catch (\Exception $e) {
			error_log ($pre. $log);
		}
        //return false;
        return $this->results;
    }

    /**
     *  Returns the last inserted id.
     *  @return int
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     *  returns the number of rows for select or affected rows for update/delete.
     *  @return int
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    /**
     * 	fetches and returns results one line at a time from last query
     *
     *  @param  int    $row is the row number to retrieve, defaults to the first row
     * 	@return (array) fetched record(s)
     */
    public function single($row = 0) {
        if (!is_null($this->results) && $row >= 0 && $row < count($this->results)) {
            $record = $this->results[$row];
        } else {
            $record = null;
        }

        return $record;
    }

    /**
     * 	returns all the results (not one row) for the last query
     * 	@return (array) assoc array of ALL fetched results
     */
    public function resultset() {
        return $this->results;
    }

    /**
     * Starts the transaction
     * @return boolean, true on success or false on failure
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    /**
     *  Execute Transaction
     *  @return boolean, true on success or false on failure
     */
    public function executeTransaction() {
        return $this->pdo->commit();
    }

    /**
     *  Rollback of Transaction
     *  @return boolean, true on success or false on failure
     */
    public function rollBack() {
        return $this->pdo->rollBack();
    }

    /**
     *  dumps the the information that was contained in the Prepared Statement.
     *  @return array
     */
    public function debugDumpParams() {
        return $this->stmt->debugDumpParams();
    }

/*    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Db();
        }
        return self::$instance;
	}
*/

    /**
     * @param $dv
     * @return bool
     */
	function dbExists($dv){
        $stmt = $this->pdo->query(
            sprintf("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '%s'", $dv)
        );
        return (bool)$stmt->fetchColumn();
	}

    public function getAffectedRows(): int
    {
        return $this->affected_rows;
    }

}
