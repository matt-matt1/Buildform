<?php
namespace Yuma\Database;
class PgSQL_Driver implements IDriver {
	public $id = 'PgSQL';
	private $pdo = null;
	protected $options = array(
		PDO::ATTR_PERSISTENT => true,
	);
	protected $attribs = Array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_EMULATE_PREPARES => false,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	);

	public function connect($parameter=null) {
        try {
			$dsn = strtolower($this->id). ":host=". DB_HOST. "; dbname=". DB_NAME. "";
			//$dsn = strtolower($this->id). ":host=". $GLOBALS['data']['DB_HOST']. "; dbname=". $GLOBALS['data']['DB_NAME']. "";
			if (defined('DB_CHAR')) {
			//if (isset($GLOBALS['data']['DB_CHAR'])) {
				$dsn .= ';charset='. DB_CHAR;
				//$dsn .= ';charset='. $GLOBALS['data']['DB_CHAR'];
			}
			$pgsql_dbh = new PDO($dsn, DB_USER, DB_PASS);
			//$pgsql_dbh = new PDO($dsn, $GLOBALS['data']['DB_USER'], $GLOBALS['data']['DB_PASS']);
			if (defined('DB_TIME')) {
			//if (isset($GLOBALS['data']['DB_TIME'])) {
				$pgsql_dbh->setAttribute(PDO::ATTR_TIMEOUT, DB_TIME);
				//$pgsql_dbh->setAttribute(PDO::ATTR_TIMEOUT, $GLOBALS['data']['DB_TIME']);
			}
			$pgsql_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			if (isset($parameter) && $parameter == 'test') {
				return false;
			}
			//return $pgsql_dbh;
			$this->pdo = $pgsql_dbh;
	    } catch (PDOException $e) {
			if (isset($parameter) && $parameter == 'test') {
				return 'Database connection failed: '. $e->getMessage();
			}
            echo 'Unable to connect to database: '. $e->getMessage();
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

	public function query($query) {
		if (!$this->pdo)
			$this->connect();
		try {
             $this->pdo->execute($query);
        } catch (PDOException $e) {
			if ($this->transactionStarted) {
				$this->pdo->rollBack();
            }
        }
	}
/**/
	public function escapeField($field) {
		return parent::escapeField($field);
	}

	public function escapeValue($value) {
		return parent::escapeValue($value);
	}

//	public function escapeField($field) {
        // Db-specific method
		//trim( $field, '`' );
        //return '`'. $field. '`';
		//return EscapeWith( $value, '`' );
//    }

//	public function escapeValue($value) {
        // Db-specific method
		//trim( $value, "'" );
        //return "'". $value. "'";
		//return EscapeWith( $value, "'" );
//	}

	public function applyLimit(string &$sql, int $limit = 0, int $offset = 0) {
//		return parent::;
	}

	public function beginTransaction() {
		
	}

	public function commit() {
		
	}

	public function delimite(string $name) {
		
	}

	public function formatDateInterval(\DateInterval $value) {
		
	}

	public function formatDateTime(\DateTimeInterface $value) {
		
	}

	public function formatLike(string $value, int $pos) {
		
	}

	public function getColumns(string $table): array {
		
	}

	public function getForeignKeys(string $table): array {
		
	}

	public function getIndexes(string $table): array {
		
	}

	public function getInsertId(?string $sequence = null) {
		
	}

	public function getTables(): array {
		
	}

	public function isSupported(string $item) {
		
	}

	public function quote(string $string) {
		
	}

	public function rollBack() {
		
	}

}
