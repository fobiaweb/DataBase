<?php

namespace Fobia\DataBase\Query;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-07-24 at 06:58:43.
 */
class QueryReplaceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Fobia\DataBase\Handler\MySQL
     */
    protected $db;

    protected function setUp()
    {
        if ( ! $this->db) {
            $this->db = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/mysql');
        }
    }

    /**
     * @covers Fobia\DataBase\Query\QueryReplace::getQuery
     * @todo   Implement testGetQuery().
     */
    public function testGetQuery()
    {
        $q = $this->db->createReplaceQuery();

        $str = "REPLACE INTO user ( Host, User, Password ) VALUES ( 'localhost', 'test', '' )";
        $q->insertInto('user')
                ->set('Host', $this->db->quote('localhost'))
                ->set('User', $this->db->quote('test'))
                ->set('Password', $this->db->quote(''));
        $this->assertEquals($str, $q->getQuery());
    }
}