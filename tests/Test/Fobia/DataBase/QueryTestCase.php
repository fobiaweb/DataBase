<?php
/**
 * QueryTestCase class  - QueryTestCase.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\DataBase;

/**
 * QueryTestCase class
 *
 * @package   Fobia.DataBase.Query
 */
class QueryTestCase extends  \PHPUnit_Framework_TestCase
{
    static private $pdo = null;

    /**
     * @return \Fobia\DataBase\Handler\MySQL
     */
    final public function getConnection()
    {
        if (self::$pdo == null) {
            try {
                $db = \ezcDbInstance::get();
            } catch (\Exception $e) {
                $db = null;
            }
            if ($db instanceof \Fobia\DataBase\Handler\MySQL) {
                self::$pdo = $db;
            } else {
                \ezcDbFactory::addImplementation('mysql', '\\Fobia\\DataBase\\Handler\\MySQL');
                self::$pdo = \Fobia\DataBase\DbFactory::create($_ENV['dsn']);
            }
        }

        return self::$pdo;
    }
}