<?php
/**
 * DbConnectionMysql class  - DbConnectionMysql.php file
 *
 * @author   Dmitriy Tyurin <fobia3d@gmail.com>
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
 * MySQL class, extends PDO
 *
 * @author   Dmitriy Tyurin <fobia3d@gmail.com>
 * @package  Fobia.DataBase.Handler
 */
class MySQL extends ezcDbHandlerMysql
{
    protected $profiles = false;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger = null;

    protected $log_error = null;

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
        $this->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_SILENT);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Fobia\DataBase\DbStatement', array($this)));

        if (@$dbParams['params']['logger'] instanceof \Psr\Log\LoggerInterface) {
            $this->logger = $dbParams['params']['logger'];
        } else {
            $this->logger = (class_exists('\Fobia\Debug\Log'))
                    ? \Fobia\Debug\Log::getLogger()
                    :  new \Psr\Log\NullLogger();
        }

        if (isset($dbParams['params']['log_error'])) {
            $this->log_error = $dbParams['params']['log_error'];
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

    /**
     * Все выполненные запросы за сессию с временем выполнения.
     *
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

            if ($this->log_error) {
                // LOGS_DIR . "/sql.log"
                $str = date("[Y-m-d H:i:s]") . " [SQL]:: Error " . $error[1] . ': '
                        . $error[2] . "\n"
                        . preg_replace(array("/\n/", "/\s*\n/"), array("\n    # ", "\n"), "    # $query\n");
                file_put_contents($this->log_error, $str, FILE_APPEND);
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


    /*********************************************************
     * OVERRIDE
     *********************************************************/

    /**
     * Выполняет SQL запрос, возвращая результат запроса в виде объекта PDOStatement
     *
     * @param string $statement  Запросе SQL для подготовки и выполнения.
     *                           Данные внутри запроса должны быть надлежащим образом экранированы.
     * @return PDOStatement Возвращает объект PDOStatement или false в случае возникновения ошибки.
     */
    public function query($statement)
    {
        $time  = microtime(true);
        $query = parent::query($statement);
        $this->log($statement, $time);
        return $query;
    }

    /**
     * Начинает транзакцию.
     *
     * @return bool
     */
    public function beginTransaction()
    {
        $this->logger->info("[SQL]:: ==> Begin transaction");
        return parent::beginTransaction();
    }

    /**
     * Выполняет транзакцию.
     *
     * @return bool
     */
    public function commit()
    {
        $r = parent::commit();

        $log = ($r)
                ? "Commit transaction"
                : "Error commit transaction";
        $this->logger->error("[SQL]:: ==> $log");

        return $r;
    }

    /**
     * Откат транзакции.
     *
     * @return bool
     */
    public function rollback()
    {
        $this->logger->info("[SQL]:: ==> Rollback transaction");
        return parent::rollback();
    }

    /**
     * Возвращает новый QueryInsert производный объект для правильного типа базы данных.
     *
     * @return \Fobia\DataBase\Query\QueryInsert
     */
    public function createInsertQuery()
    {
        return new QueryInsert( $this );
    }

    /**
     * Возвращает новый QueryInsert производный объект для правильного типа базы данных.
     *
     * @return \Fobia\DataBase\Query\QueryReplace
     */
    public function createReplaceQuery()
    {
        return new QueryReplace( $this );
    }

    /**
     * Возвращает новый QuerySelect производный объект для правильного типа базы данных.
     *
     * @return \Fobia\DataBase\Query\QuerySelect
     */
    public function createSelectQuery()
    {
        return new QuerySelect( $this );
    }

    /**
     * Возвращает новый QueryUpdate производный объект для правильного типа базы данных.
     *
     * @return \Fobia\DataBase\Query\QueryUpdate
     */
    public function createUpdateQuery()
    {
        return new QueryUpdate( $this );
    }

}
