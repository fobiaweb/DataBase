<?php
/**
 * DbConnectionMysql class  - DbConnectionMysql.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */

namespace Fobia\DataBase\Handler;

use PDO;
use ezcDbHandlerMysql;
use PDOStatement;
use Fobia\DataBase\Query\QueryInsert;
use Fobia\DataBase\Query\QueryReplace;
use Fobia\DataBase\Query\QuerySelect;
use Fobia\DataBase\Query\QueryUpdate;

/**
 * Обертка PDO драйвера MySQL
 *
 * @author    Dmitriy Tyurin <fobia3d@gmail.com>
 * @package   Fobia.DataBase.Handler
 */
class MySQL extends ezcDbHandlerMysql
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger = null;


    /**
     * Создает объект из параметров $dbParams.
     *
     * Supported database parameters are:
     * - dbname|database: Database name
     * - user|username:   Database user name
     * - pass|password:   Database user password
     * - host|hostspec:   Name of the host database is running on
     * - port:            TCP port
     * - charset:         Client character set
     * - socket:          UNIX socket path
     *
     * @param array $dbParams Database connection parameters (key=>value pairs).
     */
    public function __construct(array $dbParams)
    {
        parent::__construct($dbParams);

        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Fobia\DataBase\DbStatement', array($this)));

        // if (@$dbParams['charset']) {
        //     parent::query("SET NAMES '{$dbParams['charset']}'");
        // }

        $this->log("Connect database '{$dbParams['database']}'");
    }

    /**
     * Все выполненные запросы за сессию с временем выполнения.
     *
     * @return array
     */
    public function getProfiles()
    {
        parent::query('SET profiling = 1');
        if ($this->profiles) {
            $stmt = parent::query('SHOW profiles');
            return $stmt->fetchAll();
        }
        return array();
    }

    /**
     *
     * @param \Fobia\DataBase\DbStatement|string $logQuery
     * @param string|array $args
     */
    public function log($logQuery, $args = null)
    {
        if (is_array($logQuery)) {
            $query = array_shift($logQuery);
            $logQuery = $query . "\n"
                    . "-- ===> Params:: " . json_encode($logQuery);
        }

        $message = date("[Y-m-d H:i:s]") . " [SQL]:: " . $logQuery . "\n" ;
        if ($args) {
            if (is_array($args)) {
                $args = json_encode($args) ;
            }
            $message .= "-- ===> " . $args  . "\n";
        }

        // echo $message;

        /*
        if ( (int) $logQuery->errorCode() ) {
            $error = $logQuery->errorInfo();
            $this->logger->error('==> [SQL]:: '. $error[1].': '.$error[2]);

            if ($this->log_error) {
                // LOGS_DIR . "/sql.log"
                $str = date("[Y-m-d H:i:s]") . " [SQL]:: Error " . $error[1] . ': '
                        . $error[2] . "\n"
                        . preg_replace(array("/\n/", "/\s*\n/"), array("\n    # ", "\n"), "    # $logQuery\n");
                file_put_contents($this->log_error, $str, FILE_APPEND);
            }
        }
         *
         */
        
        // return $this->logger;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /* ***********************************************
     * OVERRIDE
     * ********************************************** */

    public function query($statement)
    {
        $time  = microtime(true);
        if ($query =  $this->pdo->query($statement)) {
            $query->time = microtime(true) - $time;
        }
        $this->log($statement, round(microtime(true) - $time, 6));
        return $query;
    }

    public function exec($statement)
    {
        $s_time  = microtime(true);
        $result = $this->pdo->exec($statement);
        
        $this->log($statement, round(microtime(true) - $s_time, 6));

        return $result;
    }

    /**
     * Начинает транзакцию
     *
     * @see \ezcDbHandler::beginTransaction()
     * @return bool
     */
    public function beginTransaction()
    {
        $this->log("Begin transaction");
        return parent::beginTransaction();
    }

    /**
     * Выполняет транзакцию.
     *
     * @see \ezcDbHandler::commit()
     * @return bool
     */
    public function commit()
    {
        $r = parent::commit();
        $this->log(($r) ? "Commit transaction" : "Error commit transaction");
        return $r;
    }

    /**
     * Откат транзакции.
     *
     * @see \ezcDbHandler::rollback()
     * @return bool
     */
    public function rollback()
    {
        $this->log("Rollback transaction");
        return parent::rollback();
    }


    /**
     * Returns a new QueryInsert derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QueryInsert
     */
    public function createInsertQuery()
    {
        return new QueryInsert( $this );
    }

    /**
     * Returns a new QueryInsert derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QueryReplace
     */
    public function createReplaceQuery()
    {
        return new QueryReplace( $this );
    }

    /**
     * Returns a new QuerySelect derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QuerySelect
     */
    public function createSelectQuery()
    {
        return new QuerySelect( $this );
    }

    /**
     * Returns a new QueryUpdate derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QueryUpdate
     */
    public function createUpdateQuery()
    {
        return new QueryUpdate( $this );
    }

}
