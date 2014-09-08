<?php
/**
 * ezcTestUtils class  - ezcTestUtils.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * ezcTestUtils class
 *
 * @package
 */
class ezcTestUtils
{
    public static function instanceDb($params = 'mysql://root@localhost/test')
    {
        $db = null;

        try
        {
            $db = ezcDbInstance::get();
        }
        catch ( Exception $e ) { }

        if ( ! $db ) {
            ezcDbFactory::addImplementation('mysql', 'ezcDbHandlerMysql');

            $db = ezcDbFactory::create($params);
            ezcDbInstance::set($db);
        }

        return $db;
    }
}