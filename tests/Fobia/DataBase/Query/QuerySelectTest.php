<?php

namespace Fobia\DataBase\Query;

class QuerySelectTest extends QueryTestCase
{

    /**
     * @var \Fobia\DataBase\Query\QuerySelect
     */
    protected $q;

    protected function setUp()
    {
        $this->q = $this->getConnection()->createSelectQuery();
    }

    /**
     * @covers Fobia\DataBase\Query\QuerySelect::fetchItemsCount
     */
    public function testFetchItemsCount()
    {
        $q      = $this->q;
        $q->select('*')->from('user')->limit(100);
        $result = $q->fetchItemsCount();

        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('items', $result);
    }

    /**
     * @covers Fobia\DataBase\Query\QuerySelect::findAll
     */
    public function testFindAll()
    {
        $q     = $this->q;
        $q->select('*')->from('user');
        $count = $q->findAll();

        $this->assertInternalType('int', $count);
    }

    public function testDoJoin()
    {
        $q = $this->q;
        $q->select('*')->from('t1')
                ->limit(50, 10)
                ->orderBy('host')
                ->where("user = 'root'")
                ->groupBy('select')
                ->having($q->expr->eq('id', 1));
        $q->leftJoin('t2', 't1.id', 't2.id');

        $this->assertRegExp('/LEFT JOIN t2 ON t1.id = t2.id/', $q->getQuery());
    }

    /**
     * @covers Fobia\DataBase\Query\QuerySelect::offset
     */
    public function testOffset()
    {
        $q = $this->q;
        $q->select('*')->from('user')
                ->limit(50, 10);
        $this->assertRegExp('/OFFSET 10/', $q->getQuery());
        $q->offset(0);
        $this->assertRegExp('/OFFSET 0/', $q->getQuery());
    }

    /**
     * @covers Fobia\DataBase\Query\QuerySelect::reset
     */
    public function testReset()
    {
        $q     = $this->q;
        $q->select('*')->from('user')
                ->limit(50, 10)
                ->orderBy('host')
                ->where("user = 'root'")
                ->groupBy('select')
                ->having($q->expr->eq('id', 1));
        // echo $q->getQuery();
        $query = "SELECT * FROM user WHERE user = 'root' GROUP BY select HAVING id = 1 ORDER BY host LIMIT 50 OFFSET 10";
        $this->assertEquals($query, $q->getQuery());

        $qFull = clone $q;

        return $q;
    }

    /**
     * @depends testReset
     */
    public function testResetFull($qFull)
    {
        $q = clone $qFull;

        $q->reset();
        $q->select('*')->from('user');
        $this->assertEquals("SELECT * FROM user", $q->getQuery());

        // selct
        $q = clone $qFull;
        $q->reset('select');
        $q->select('Host');
        $this->assertRegExp('/^SELECT Host FROM user/', $q->getQuery());

        // from
        $q = clone $qFull;
        $q->reset('from');
        $q->from("Host");
        $this->assertRegExp('/^SELECT \* FROM Host/', $q->getQuery());

        $q = clone $qFull;
        $q->reset('limit');
        $this->assertRegExp('/ORDER BY host$/', $q->getQuery());

        $q = clone $qFull;
        $q->reset('where');
        $q->where("user = 'test'");
        $this->assertRegExp("/WHERE user = 'test'/", $q->getQuery());

        $q = clone $qFull;
        $q->reset('group');
        $q->groupBy('test');
        $this->assertRegExp("/GROUP BY test/", $q->getQuery());

        $q = clone $qFull;
        $q->reset('having');
        $q->having("user = 'test'");
        $this->assertRegExp("/HAVING user = 'test'/", $q->getQuery());

        $q = clone $qFull;
        $q->reset('order');
        $q->orderBy('test');
        $this->assertRegExp('/ORDER BY test/', $q->getQuery());
    }

    /**
     * @expectedException \ezcDbMissingParameterException
     */
    public function testResetException()
    {
        $this->q->reset('no name');
    }
}