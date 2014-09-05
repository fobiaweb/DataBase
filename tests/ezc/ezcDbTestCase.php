<?php
/**
 * ezcDataBaseTestCase class  - ezcDataBaseTestCase.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * ezcDataBaseTestCase class
 *
 * @package   
 */
class ezcDataBaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                // $pdo = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/ezc-test');
                self::$pdo = new PDO('mysql:dbname=ezc-test;host=127.0.0.1', 'root', '');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo);
        }

        return $this->conn;
    }


    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(dirname(__FILE__).'/database/_files/database.xml');
    }


    public function testGuestbook()
    {
        // $dataSet = $this->getConnection()->createDataSet();
print_r($this->getConnection()->getRowCount('authors'));
        $this->getConnection()->getConnection()->query("DELETE FROM authors");
        $res = $this->getConnection()->getConnection()->query("SELECT * FROM authors");
        print_r($res->fetchAll());
    }

    public function testFilteredGuestbook()
    {
        $res = $this->getConnection()->getConnection()->query("SELECT * FROM authors");
        print_r($res->fetchAll());
    }
}