<?php

namespace Fobia\DataBase\Query;

use Fobia\DataBase\QueryTestCase;

class QueryUpdateTest extends QueryTestCase
{
    /**
     * @var \Fobia\DataBase\Query\QueryUpdate
     */
    protected $q;

    protected function setUp()
    {
        $this->q = $this->getConnection()->createUpdateQuery();
    }

    /**
     * @covers Fobia\DataBase\Query\QueryUpdate::orderBy
     */
    public function testOrderBy()
    {
        $q     = $this->q;
        $q->update('test')
                ->set('duration', $this->getConnection()->quote(2) )
                ->where($q->expr->eq('id', $this->getConnection()->quote(1)))
                ->orderBy("timestamp", 'DESC')
                ;
        $this->assertRegExp("/ORDER BY timestamp DESC$/", $q->getQuery());

        $q->orderBy('column', 'DESC');
        $this->assertRegExp("/ORDER BY timestamp DESC, column DESC$/", $q->getQuery());
    }

    /**
     * @covers Fobia\DataBase\Query\QueryUpdate::limit
     */
    public function testLimit()
    {
        $q     = $this->q;
        $q->update('test')
                ->set('duration', $this->getConnection()->quote(2) )
                ->where($q->expr->eq('id', $this->getConnection()->quote(1)))
                ->orderBy("timestamp", 'DESC')
                ->limit(1)
                ;
        $this->assertRegExp("/LIMIT 1$/", $q->getQuery());
    }

    /**
     * @covers Fobia\DataBase\Query\QueryUpdate::getQuery
     */
    public function testGetQuery()
    {
        $q     = $this->q;
        $q->update('test')
                ->orderBy('test')
                ->limit(1)
                ->set('id', $this->getConnection()->quote(2) )
                ->where($q->expr->eq('id', $this->getConnection()->quote(1)))
                ;
        $this->assertEquals("UPDATE test SET id = '2' WHERE id = '1' ORDER BY test LIMIT 1", $q->getQuery());
    }
}
