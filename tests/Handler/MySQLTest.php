<?php

namespace Fobia\DataBase\Handler;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-07-24 at 06:40:13.
 */
class MySQLTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Fobia\DataBase\Handler\MySQL
     */
    protected $db;
    
    protected function setUp()
    {
        if (!$this->db) {
            $this->db = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/mysql');
        }
    }

    /**
     * @covers Fobia\DataBase\Handler\MySQL::query
     * @todo   Implement testQuery().
     */
    public function testQuery()
    {
        $db = $this->db;
        $stmt = $db->query("SELECT VERSION()");
        $this->assertInstanceOf('\Fobia\DataBase\DbStatement', $stmt);

        $row = $stmt->fetch();
        $v = array_shift($row);
        $this->assertRegExp("/^5\..+/", $v);
    }

    /**
     * @covers Fobia\DataBase\Handler\MySQL::getProfiles
     * @todo   Implement testGetProfiles().
     */
    /*
    public function testGetProfiles()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
/* */

    /**
     * @covers Fobia\DataBase\Handler\MySQL::log
     * @todo   Implement testLog().
     */
    public function testLog()
    {
        $logger = $this->db->log("SELECT VERSION()", microtime(true));
        $this->assertInstanceOf('\Psr\Log\LoggerInterface', $logger);
    }

    public function testGetLogger()
    {
        $this->assertInstanceOf('\Psr\Log\LoggerInterface', $this->db->getLogger());
    }

    /**
     * @covers Fobia\DataBase\Handler\MySQL::createInsertQuery
     * @todo   Implement testCreateInsertQuery().
     */
    public function testCreateInsertQuery()
    {
        $q = $this->db->createInsertQuery();
        $this->assertInstanceOf('\Fobia\DataBase\Query\QueryInsert', $q);
    }

    /**
     * @covers Fobia\DataBase\Handler\MySQL::createReplaceQuery
     * @todo   Implement testCreateReplaceQuery().
     */
    public function testCreateReplaceQuery()
    {
        $q = $this->db->createReplaceQuery();
        $this->assertInstanceOf('\Fobia\DataBase\Query\QueryReplace', $q);
    }

    /**
     * @covers Fobia\DataBase\Handler\MySQL::createSelectQuery
     * @todo   Implement testCreateSelectQuery().
     */
    public function testCreateSelectQuery()
    {
        $q = $this->db->createSelectQuery();
        $this->assertInstanceOf('\Fobia\DataBase\Query\QuerySelect', $q);
    }
}