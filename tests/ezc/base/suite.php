<?php
/**
 * @package ezc.Base
 * @subpackage Tests
 * @version 1.8
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once( "baseInitTest.php");
require_once( "baseOptionsTest.php");
require_once( "baseTest.php");
require_once( "featuresUnixTest.php");
//require_once( "featuresWindowsTest.php");
require_once( "fileCalculateRelativePathTest.php");
require_once( "fileCopyRecursiveTest.php");
require_once( "fileFindRecursiveTest.php");
require_once( "fileIsAbsolutePathTest.php");
require_once( "fileRemoveRecursiveTest.php");
require_once( "structTest.php");

/**
 * @package ezc.Base
 * @subpackage Tests
 */
class ezcBaseSuite extends PHPUnit_Framework_TestSuite
{
	public function __construct()
	{
		parent::__construct();
        $this->setName("ezcBase");

        $this->addTest( ezcBaseInitTest::suite() );
        $this->addTest( ezcBaseOptionsTest::suite() );
        $this->addTest( ezcBaseTest::suite() );
        $this->addTest( ezcBaseFeaturesUnixTest::suite() );
//        $this->addTest( ezcBaseFeaturesWindowsTest::suite() );
        $this->addTest( ezcBaseStructTest::suite() );
        $this->addTest( ezcBaseFileCalculateRelativePathTest::suite() );
        $this->addTest( ezcBaseFileCopyRecursiveTest::suite() );
        $this->addTest( ezcBaseFileFindRecursiveTest::suite() );
        $this->addTest( ezcBaseFileIsAbsoluteTest::suite() );
        $this->addTest( ezcBaseFileRemoveRecursiveTest::suite() );
    }

    public static function suite()
    {
        return new ezcBaseSuite();
    }
}
