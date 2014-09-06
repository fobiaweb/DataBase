<?php

namespace Fobia\DataBase\Query;

class QueryInsertTest extends QueryTestCase
{

    /**
     * @var \Fobia\DataBase\Query\QueryInsert
     */
    protected $q;

    protected function setUp()
    {
        $this->q = $this->getConnection()->createInsertQuery();
    }

    public function testInsertQuery()
    {
        $q = $this->q;
        $this->assertInstanceOf('\Fobia\DataBase\Query\QueryInsert', $q);
    }

    /**
     * @covers Fobia\DataBase\Query\QueryInsert::insertIntoIgnore
     */
    public function testInsertIntoIgnore()
    {
        $q = $this->q;
        $q->insertIntoIgnore('user');
        $q->set("Host", "'localhost'");

        $stmt = $q->prepare();
        $this->assertStringStartsWith("INSERT IGNORE INTO user", $stmt->queryString);
    }

    /**
     * @covers Fobia\DataBase\Query\QueryInsert::getQuery
     */
    public function testGetQuery()
    {
        $q = $this->q;

        $str = "INSERT INTO user ( Host, User, Password ) VALUES ( 'localhost', 'test', '' )";
        $q
                ->set('Host', $this->getConnection()->quote('localhost'))
                ->set('User', $this->getConnection()->quote('test'))
                ->set('Password', $this->getConnection()->quote(''));
        $qi = clone $q;

        $q->insertInto('user');
        $qi->insertIntoIgnore('user');

        $this->assertEquals($str, $q->getQuery());
        $this->assertRegExp('/^INSERT IGNORE /', $qi->getQuery());
    }
}