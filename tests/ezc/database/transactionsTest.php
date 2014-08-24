<?php

class MyDB
{
    static private $instance = null;
    static private $dbParams = null;

    static public function setParams( $dbParams )
    {
        self::$dbParams = $dbParams;
    }

    static public function create()
    {
        // create instance
        if ( self::$dbParams === null ) {
            throw new Exception( "Missing database " .
                                 "connection parameteters." );
        }

        return ezcDbFactory::create( self::$dbParams );
    }
}

class ezcDatabaseTransactionsTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        try
        {
            //$dbparams = ezcTestSettings::getInstance()->db->dsn;
            $dbparams = 'sqlite://:memory:';// ezcTestSettings::getInstance()->db->dsn;
            MyDB::setParams( $dbparams );
            $this->db = MyDB::create();
        }
        catch ( Exception $e )
        {
            // print_r($e);
            echo $e->getMessage() . PHP_EOL;
            $this->markTestSkipped();
        }
    }

    // normal: test nested transactions
    public function test1()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->commit();
        }
        catch ( ezcDbTransactionException $e )
        {
            $this->fail( "Exception (" . get_class( $e ) . ") caught: " . $e->getMessage() );
        }
    }

    public function test2()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->commit();
        }
        catch ( Exception $e )
        {
            $this->fail( "Should not throw exception here since the action doesn't have to be user initiated" );
        }

    }

    // error: more COMMITs than BEGINs
    public function test3()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->commit();
            $this->db->commit();
            $this->db->commit();
            $this->db->commit();
        }
        catch ( ezcDbTransactionException $e )
        {
            return;
        }

        $this->fail( "The case when there were more COMMITs than BEGINs did not fail.\n" );
    }

    // normal: BEGIN, BEGIN, COMMIT, then ROLLBACK
    public function test4()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->rollback();
        }
        catch ( ezcDbException $e )
        {
            $this->fail( "Exception (" . get_class( $e ) . ") caught: " . $e->getMessage() );
        }
    }

    // normal: BEGIN, BEGIN, ROLLBACK, then COMMIT
    public function test5()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->rollback();
            $this->db->commit();
        }
        catch ( ezcDbException $e )
        {
            $this->fail( "Exception (" . get_class( $e ) . ") caught: " . $e->getMessage() );
        }
    }

    // error: BEGIN, ROLLBACK, COMMIT
    public function test6()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->rollback();
            $this->db->commit();
        }
        catch ( ezcDbTransactionException $e )
        {
            return;
        }

        $this->fail( "The case with consequent BEGIN, ROLLBACK, COMMIT did not fail.\n" );
    }

    // error: BEGIN, COMMIT, ROLLBACK
    public function test7()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->rollback();
        }
        catch ( ezcDbTransactionException $e )
        {
            return;
        }

        $this->fail( "The case with consequent BEGIN, COMMIT, ROLLBACK did not fail.\n" );
    }

}
