<?php
/**
 * File containing the ezcDbUtilitiesMysql class.
 *
 * @package ezc.Database.Query
 * @version 1.4.9
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Various database methods.
 *
 * This implementation is MySQL specific.
 *
 * This class inherits most of its database handling functionality from
 * PDO ({@link http://php.net/PDO}) -- an object-oriented database abstraction
 * layer that is going to become a new standard for PHP applications.
 *
 * @package ezc.Database.Query
 * @todo this class must be renamed
 * @version 1.4.9
 * @access private
 */
class ezcDbUtilitiesMysql extends ezcDbUtilities
{
    /**
     * Constructs a new db util using the db handler $db.
     *
     * @param ezcDbHandler $db
     */
    public function __construct( $db )
    {
        parent::__construct( $db );
    }

    /**
     * Remove all tables from the database.
     */
    public function cleanup()
    {
        $this->dbHandler->beginTransaction();
        $rslt = $this->dbHandler->query( 'SHOW TABLES' );
        $rslt->setFetchMode( PDO::FETCH_NUM );
        $rows = $rslt->fetchAll();
        foreach ( $rows as $row )
        {
            $table = $row[0];
            $this->dbHandler->exec( "DROP TABLE `$table`" );
        }
        $this->dbHandler->commit();
    }
}
