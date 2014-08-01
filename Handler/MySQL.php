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

/**
 * MySQL class, extends PDO
 *
 * @package     Fobia.DataBase.Handler
 */
class MySQL extends ezcDbHandlerMysql
{
    protected $profiles = false;
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

        if (@$dbParams['params']['logger'] instanceof \Psr\Log\LoggerInterface) {
            $this->logger = $dbParams['params']['logger'];
        } else {
            if (class_exists('\Fobia\Debug\Log')) {
                $this->logger = \Fobia\Debug\Log::getLogger();
            } else {
                $this->logger = new \Psr\Log\NullLogger();
            }
        }

        // if (@$dbParams['charset']) {
        //     parent::query("SET NAMES '{$dbParams['charset']}'");
        // }

        $this->getLogger()->info('[SQL]:: Connect database', array($dbParams['database']));

        if (@$dbParams['params']['debug']) {
            parent::query('SET profiling = 1');
            $this->profiles = true;
            $this->logger->debug('==> Set profiling');
        }
    }

    public function query($statement)
    {
        $time  = microtime(true);
        $query =  parent::query($statement);
        $this->log($statement, $time);
        return $query;
    }

    /**
     * Все выполненные запросы за сессию с временем выполнения.
     * @return array
     */
    public function getProfiles()
    {
        if ($this->profiles) {
            $stmt = parent::query('SHOW profiles');
            return $stmt->fetchAll();
        }
        return array();
    }

    /**
     *
     * @param \Fobia\DataBase\DbStatement|string $stmt
     * @param mixed $time
     * @return \Psr\Log\LoggerInterface 
     */
    public function log($stmt, $time)
    {
        if ( $stmt instanceof PDOStatement ) {
            $query = $stmt->queryString;
        } else {
            $query = $stmt;
            $stmt = $this;
        }

        $dTime = round( microtime(true) - $time , 6);
        $this->logger->info('[SQL]:: ' . $query, array( $dTime ) );

        if ( (int) $stmt->errorCode() ) {
            $error = $stmt->errorInfo();
            $this->logger->error('==> [SQL]:: '. $error[1].': '.$error[2]);

            if (defined('LOGS_DIR')) {
                $str = date("[Y-m-d H:i:s]") . " [SQL]:: $query\n"
                    . "  # Info [time: $dTime]\n"
                    . "  # Error " . $error[1] . ': ' . $error[2] . "\n";
                file_put_contents(LOGS_DIR . "/sql.log", $str, FILE_APPEND);
            }
        }
        return $this->logger;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
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
}
