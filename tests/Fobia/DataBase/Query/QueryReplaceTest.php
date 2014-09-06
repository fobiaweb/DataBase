<?php

namespace Fobia\DataBase\Query;

class QueryReplaceTest extends QueryTestCase
{

    /**
     * @var \Fobia\DataBase\Query\QueryReplace
     */
    protected $q;

    protected function setUp()
    {
        $this->q = $this->getConnection()->createReplaceQuery();
    }

    /**
     * @covers Fobia\DataBase\Query\QueryReplace::getQuery
     */
    public function testGetQuery()
    {
        $q = $this->q;

        $str = "REPLACE INTO user ( Host, User, Password ) VALUES ( 'localhost', 'test', '' )";
        $q->insertInto('user')
                ->set('Host', $this->getConnection()->quote('localhost'))
                ->set('User', $this->getConnection()->quote('test'))
                ->set('Password', $this->getConnection()->quote(''));
        $this->assertEquals($str, $q->getQuery());
    }
}