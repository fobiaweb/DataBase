<?php
/**
 * @package Base
 * @subpackage Tests
 * @version 1.8
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * @package Base
 * @subpackage Tests
 *
 * @
 */
class ezcBaseFeaturesUnixTest extends ezcTestCaseBase
{
    protected function setUp()
    {
        $uname = php_uname( 's' );
        if ( substr( $uname, 0, 7 ) == 'Windows' )
        {
            $this->markTestSkipped( 'Unix tests' );
        }
    }
    /**
     * @requires OS Linux
     */
    public function testSupportsLink()
    {
        $this->assertEquals( true, ezcBaseFeatures::supportsLink() );
    }
    /**
     * @requires OS Linux
     */
    public function testSupportsSymLink()
    {
        $this->assertEquals( true, ezcBaseFeatures::supportsSymLink() );
    }
    /**
     * @requires OS Linux
     */
    public function testSupportsUserId()
    {
        $this->assertEquals( true, ezcBaseFeatures::supportsUserId() );
    }

/*  // Need to find a way to make this test work, as setting global enviroment variables
    // is not working (putenv( "PATH=" ) doesn't unset $_ENV["PATH"])
    // One solution would be to use in the ezcBaseFeatures::getPath():
    // getenv( 'PATH' ) instead of $_ENV['PATH'] (but that won't work under IIS).
    public function testHasImageIdentifyNoPath()
    {
        $envPath = getenv( 'PATH' );
        putenv( "PATH=" );
        $this->assertEquals( false, ezcBaseFeatures::hasImageIdentify() );
        putenv( "PATH={$envPath}" );
    }
*/

    /**
     * @requires OS Linux
     */
    /*
    public function testHasImageConvert()
    {
        $this->assertEquals( true, ezcBaseFeatures::hasImageConvert() );
    }
    /**/
    /**
     * @requires OS Linux
     */
    /*
    public function testGetImageConvertExecutable()
    {
        $this->assertEquals( '/usr/bin/convert', ezcBaseFeatures::getImageConvertExecutable() );
    }
    /**/
    /**
     * @requires OS Linux
     */
    /*
    public function testGetImageIdentifyExecutable()
    {
        $this->assertEquals( '/usr/bin/identify', ezcBaseFeatures::getImageIdentifyExecutable() );
    }
    /**/
    /**
     * @requires OS Linux
     */
    /*
    public function testHasImageIdentify()
    {
        $this->assertEquals( true, ezcBaseFeatures::hasImageIdentify() );
    }
    /**/
    /**
     * @requires OS Linux
     */
    public function testHasExtensionSupport1()
    {
        $this->assertEquals( true, ezcBaseFeatures::hasExtensionSupport( 'standard' ) );
    }
    /**/
    /**
     * @requires OS Linux
     */
    public function testHasExtensionSupportNotFound1()
    {
        $this->assertEquals( false, ezcBaseFeatures::hasExtensionSupport( 'non_existent_extension' ) );
        try
        {
            throw new ezcBaseExtensionNotFoundException( 'non_existent_extension', null, 'This is just a test.' );
        }
        catch ( ezcBaseExtensionNotFoundException $e )
        {
            $this->assertEquals( "The extension 'non_existent_extension' could not be found. This is just a test.",
                                 $e->getMessage() );
        }
    }
    /**
     * @requires OS Linux
     */
    public function testHasExtensionSupportNotFound2()
    {
        $this->assertEquals( false, ezcBaseFeatures::hasExtensionSupport( 'non_existent_extension' ) );
        try
        {
            throw new ezcBaseExtensionNotFoundException( 'non_existent_extension', '1.2', 'This is just a test.' );
        }
        catch ( ezcBaseExtensionNotFoundException $e )
        {
            $this->assertEquals( "The extension 'non_existent_extension' with version '1.2' could not be found. This is just a test.",
                                 $e->getMessage() );
        }
    }
    /**/
    /**
     * @requires OS Linux
     */
    public function testHasFunction1()
    {
        $this->assertEquals( true, ezcBaseFeatures::hasFunction( 'function_exists' ) );
    }
    /**
     * @requires OS Linux
     */
    public function testHasFunction2()
    {
        $this->assertEquals( false, ezcBaseFeatures::hasFunction( 'non_existent_function_in_php' ) );
    }
    /**
     * @requires OS Linux
     */
    public function testHasExtensionSupport2()
    {
        $this->assertEquals( true, ezcBaseFeatures::hasExtensionSupport( 'date', '5.1.0' ) );
    }
    /**
     * @requires OS Linux
     */
    public function testClassExists()
    {
        $this->assertEquals( true, ezcBaseFeatures::classExists( 'Exception', false ) );
    }
    /**
     * @requires OS Linux
     */
    public function testClassExistsAutoload()
    {
        $this->assertEquals( true, ezcBaseFeatures::classExists( 'ezcBaseFeatures' ) );
    }
    /**
     * @requires OS Linux
     */
    public function testClassExistsNotFound()
    {
        $this->assertEquals( false, ezcBaseFeatures::classExists( 'ezcBaseNonExistingClass', false ) );
    }
    /**
     * @requires OS Linux
     */
    public function testClassExistsNotFoundAutoload()
    {
        $this->assertEquals( false, ezcBaseFeatures::classExists( 'ezcBaseNonExistingClass' ) );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite(__CLASS__);
    }
}
