<?php
/**
 * QueryTestCase class  - QueryTestCase.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\DataBase\Query;

/**
 * QueryTestCase class
 *
 * @package   Fobia.DataBase.Query
 */
class QueryTestCase extends  \PHPUnit_Framework_TestCase
{
    static private $pdo = null;
    private $conn = null;

    /**
     * @return \Fobia\DataBase\Handler\MySQL
     */
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/ezc-test');
            }
            $this->conn = self::$pdo;//$this->createDefaultDBConnection(self::$pdo);
        }

        return $this->conn;
    }
}