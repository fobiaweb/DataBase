<?php
/**
 * ezcTestCaseBase class  - ezcTestCaseBase.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * ezcTestCaseBase class
 *
 * @package   
 */
class ezcTestCaseBase extends PHPUnit_Framework_TestCase
{
     protected function assertType($expected, $actual)
     {
        self::assertInternalType($expected, $actual);
     }

     protected function createTempDir($dir)
     {
        if ( substr( php_uname( 's' ), 0, 7 ) == 'Windows' )
        {
            return dirname(dirname(dirname(__FILE__))) . "/tmp/ezcTest/$dir";
        }

        return "/tmp/ezcTest/$dir";
     }

     protected function removeTempDir($dir = null)
     {
        $dir = ($dir) ? $dir : '/tmp/ezcTest';
        if ( substr( php_uname( 's' ), 0, 7 ) == 'Windows' )
        {
            $dir = dirname(dirname(dirname(__FILE__))) . $dir ;
            shell_exec("RMDIR /S $dir");
            return;
        }
        shell_exec("rm -rf $dir");
     }
}