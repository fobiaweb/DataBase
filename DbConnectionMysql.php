<?php
/**
 * DbConnectionMysql class  - DbConnectionMysql.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */

namespace Fobia\DataBase;

require_once 'DbFactory.php';

use \PDO;
use \ezcDbHandlerMysql;

/**
 * DBConnection class
 *
 * @package     Fobia.DataBase
 */
class DbConnectionMysql extends ezcDbHandlerMysql
{
    protected $profiles = false;

    public function __construct(array $dbParams)
    {
        parent::__construct($dbParams);

        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Fobia\DataBase\DbStatement', array($this)));

        \Fobia\Log::info('SQL:: Connect database', array($dbParams['database']));

        if (@$dbParams['params']['debug']) {
            parent::query('SET profiling = 1');
            $this->profiles = true;
            \Fobia\Log::debug('==> Set profiling');
        }
    }

    public function query($statement)
    {
        $time  = microtime(true);
        $query =  parent::query($statement);
        $this->log($query, $time);
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
     * @param \Fobia\DataBase\DbStatement $stmt
     * @param type $time
     */
    public function log($stmt, $time)
    {
        /* @var $stmt DbStatement */
        \Fobia\Log::info('SQL:: ' . $stmt->queryString, array( round( microtime(true) - $time , 6)) );
        if (!$stmt) {
            $error = $this->errorInfo();
            \Fobia\Log::error('==> SQL:: '. $error[1].': '.$error[2]);
        }
    }
}
