<?php
/**
 * DbFactory class  - DbFactory.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\DataBase;

if ( ! class_exists("\\ezcBase")) {
    @require_once 'ezc/Base/base.php';
    spl_autoload_register(function($className) {
        \ezcBase::autoload($className);
    });
}
\ezcDbFactory::addImplementation('mysql', '\\Fobia\\DataBase\\DbConnectionMysql');
\ezcDbFactory::addImplementation('mssql', '\\Fobia\\DataBase\\DbConnectionMssql');


require_once __DIR__ . '/DbStatement.php';

/**
 * DbFactory class
 *
 * @package   Fobia.DataBase
 */
class DbFactory extends \ezcDbFactory
{
    /**
     * @param array|string $dbParams
     * @return \ezcDbHandler
     */
    public static function create($dbParams)
    {
        if (isset($dbParams['dbname'])) {
            $dbParams['database'] = $dbParams['dbname'];
            unset($dbParams['dbname']);
        }
        if (isset($dbParams['driver'])) {
            $dbParams['phptype'] = $dbParams['driver'];
            unset($dbParams['driver']);
        }
        if (isset($dbParams['pass'])) {
            $dbParams['password'] = $dbParams['pass'];
            unset($dbParams['pass']);
        }
        
        if ( is_array( $dbParams ) && array_key_exists('dns', $dbParams)) {
            $params = \Fobia\DataBase\DbFactory::parseDSN($dbParams['dns']);
            $dbParams = array_merge($params, $dbParams);
            unset($dbParams['dns']);
        }

        return parent::create($dbParams);
    }
}