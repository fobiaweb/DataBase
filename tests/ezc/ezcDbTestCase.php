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
class ezcDbTestCase
{
    public static function instanceDb($params = null)
    {
        $db = null;
        if ($params === null) {
            $params = $_ENV['database'];
            $params['type'] = 'mysql';
        }

        try
        {
            $db = ezcDbInstance::get();
        }
        catch ( Exception $e ) { }

        if ( ! $db ) {
            ezcDbFactory::addImplementation('mysql', 'ezcDbHandlerMysql');

            $db = ezcDbFactory::create($params);
            $db->query("SET time_zone = '+04:00'");
            ezcDbInstance::set($db);
        }

        return $db;
    }
}
