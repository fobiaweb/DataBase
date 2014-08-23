<?php
/**
 * ezcTestCaseBaseBase class  - ezcTestCaseBaseBase.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * ezcTestCaseBaseBase class
 *
 * @package
 */
class ezcTestCaseBase  extends PHPUnit_Framework_TestCase
{
     protected function assertType($expected, $actual)
     {
        self::assertInternalType($expected, $actual);
     }

     protected function createTempDir($dir)
     {
         return "/tmp/ezcTest/$dir";
     }

     protected function removeTempDir()
     {
         shell_exec("rm -rf /tmp/ezcTest");
     }
}
