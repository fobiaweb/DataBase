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
\ezcDbFactory::addImplementation('mysql', '\\Fobia\\DataBase\\Handler\\DbConnectionMysql');
\ezcDbFactory::addImplementation('mssql', '\\Fobia\\DataBase\\Handler\\DbConnectionMssql');


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
        if ( ! is_array( $dbParams )) {
            $dbParam['dns'] = $dbParam;
        }

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
        if (isset($dbParams['user'])) {
            $dbParams['username'] = $dbParams['user'];
            unset($dbParams['user']);
        }

        if ( @array_key_exists('dns', $dbParams)) {
            $params = self::parseDSN($dbParams['dns']);
            $dbParams = array_merge($params, $dbParams);
            unset($dbParams['dns']);
        }

        if (!isset($dbParams['phptype'])) {
            @$dbParams['phptype'] = 'mysql';
        }

        return parent::create($dbParams);
    }
}