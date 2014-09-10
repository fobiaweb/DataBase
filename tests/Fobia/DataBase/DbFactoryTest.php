<?php

namespace Fobia\DataBase;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-07-24 at 06:31:43.
 */
class DbFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Fobia\DataBase\DbFactory::create
     */
    public function testCreate()
    {
        $params = $_ENV['dsn'];
        $db = \Fobia\DataBase\DbFactory::create($params);
        $this->assertInstanceOf('PDO', $db->getPdo());
    }

    public function testCreate1()
    {
        $params = array(
            'host'   => 'localhost',
            'database' => 'mysql',
            'username'   => 'root',
            'password'   => ''
        );
        $db     = \Fobia\DataBase\DbFactory::create($params);
        $this->assertInstanceOf('PDO', $db->getPdo());
        $this->assertInstanceOf('\\Fobia\\DataBase\\Handler\\MySQL', $db);
    }

    public function testCreate2()
    {
        $params = array(
            'host'   => 'localhost',
            'database' => 'mysql',
            'driver' => 'mysql',
            'username'   => 'root',
            'password'   => ''
        );
        $db     = \Fobia\DataBase\DbFactory::create($params);
        $this->assertInstanceOf('\\Fobia\\DataBase\\Handler\\MySQL', $db);
    }
}