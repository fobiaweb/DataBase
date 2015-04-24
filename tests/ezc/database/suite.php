<?php
/**
 * @package ezc.Base
 * @subpackage Tests
 * @version 1.8
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once( "factoryTest.php");
require_once( "handlerTest.php");
require_once( "pdoTest.php");
require_once( "transactionsTest.php");

require_once( "sqlabstraction/expressionTest.php");
require_once( "sqlabstraction/paramValuesTest.php");
require_once( "sqlabstraction/queryDeleteTest.php");
require_once( "sqlabstraction/queryInsertTest.php");
require_once( "sqlabstraction/querySelectImplTest.php");
require_once( "sqlabstraction/querySelectJoinTest.php");
require_once( "sqlabstraction/querySelectTest.php");
require_once( "sqlabstraction/querySubSelectImplTest.php");
require_once( "sqlabstraction/querySubSelectTest.php");
require_once( "sqlabstraction/queryTest.php");
require_once( "sqlabstraction/queryUpdateTest.php");
require_once( "sqlabstraction/rdbmsLimitsTest.php");

/**
 * @package ezc.DataBase
 * @subpackage Tests
 */
class ezcDataBaseSuite extends PHPUnit_Framework_TestSuite
{
	public function __construct()
	{
		parent::__construct();
        $this->setName("ezcDataBase");

        $this->addTest( ezcDbFactoryTest::suite() );
        $this->addTest( ezcDbHandlerTest::suite() );
        $this->addTest( ezcPdoTest::suite() );
        $this->addTest( ezcDatabaseTransactionsTest::suite() );

        $this->addTest( ezcQueryExpressionTest::suite() );
        $this->addTest( ezcParamValuesTest::suite() );
        $this->addTest( ezcQueryDeleteTest::suite() );
        $this->addTest( ezcQueryInsertTest::suite() );
        $this->addTest( ezcQuerySelectTestImpl::suite() );
        $this->addTest( ezcQuerySelectJoinTestImpl::suite() );
        $this->addTest( ezcQuerySelectTest::suite() );
        $this->addTest( ezcQuerySubSelectTestImpl::suite() );
        $this->addTest( ezcQuerySubSelectTest::suite() );
        $this->addTest( ezcQueryTest::suite() );
        $this->addTest( ezcQueryUpdateTest::suite() );

        $this->addTest( ezcRdbmsLimitTest::suite() );
    }

    public static function suite()
    {
        return new ezcDataBaseSuite();
    }
}
