<?php
/**
 * DbConnectionMssql class  - DbConnectionMssql.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */

namespace Fobia\DataBase\Handler;

use PDO;
use ezcDbHandlerMssql;
use Fobia\DataBase\Query\QueryInsert;

/**
 * DBConnection class
 *
 * @package     Fobia.DataBase.Handler
 */
class MSSQL extends ezcDbHandlerMssql
{

    /**
     * @var LoggerInterface
     */
    public $logger;
    public $logEnabled = true;
    protected $profiles;

    public function __construct(array $dbParams)
    {
        parent::__construct($dbParams);

        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Fobia\DataBase\DbStatement', array($this)));

        \Fobia\Log::info('SQL:: Connect database', array($dbParams['dbname']));
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
        // TODO: MSSQL getProfiles
    }

    /**
     *
     * @param \Fobia\DataBase\DbStatement|string $stmt
     * @param float $time
     */
    public function log($stmt, $time)
    {
        if ( $stmt instanceof \PDOStatement ) {
            $query = $stmt->queryString;
        } else {
            $query = $stmt;
            $stmt = $this;
        }

        \Fobia\Log::info('SQL:: ' . $query, array( round( microtime(true) - $time , 6)) );

        if ((int) $stmt->errorCode()) {
            $error = $stmt->errorInfo();
            \Fobia\Log::error('==> SQL:: '. $error[1].': '.$error[2]);
        }
    }

    /**
     * Returns a new ezcQueryInsert derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QueryInsert
     */
    public function createInsertQuery()
    {
        return new QueryInsert( $this );
    }
}
