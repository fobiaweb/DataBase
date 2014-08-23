<?php
/**
 * ezcTestCaseDatabaseDb class  - ezcTestCaseDatabaseDb.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * ezcTestCaseDatabaseDb class
 *
 * @package
 */
class ezcTestCaseDatabase  extends PHPUnit_Framework_TestCase
{
    protected $db;

    public function __construct($name = null, array $data = array(),
                                $dataName = '')
    {
        try {
            $db = ezcDbInstance::get();
        } catch (Exception $exc) {
            $db = null;
        }

        if ( $db ) {
            $db = ezcDbFactory::create('mysql://root@localhost/test');
        }

        $this->db = $db;

        parent::__construct($name, $data, $dataName);
    }


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
