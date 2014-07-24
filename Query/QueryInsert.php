<?php
/**
 * QueryInsert class  - QueryInsert.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */

namespace Fobia\DataBase\Query;

use ezcQueryInsert;

/**
 * QueryInsert class
 *
 * @package Fobia.DataBase.Query
 */
class QueryInsert extends ezcQueryInsert
{
    protected $ignore = false;


    /**
     * Opens the query and sets the target table to $table.
     *
     * insertInto() returns a pointer to $this.
     *
     * @param string $table
     * @return ezcQueryInsert
     */
    public function insertIntoIgnore( $table )
    {
        $this->ignore = 'IGNORE' ;
        return $this->insertInto( $table );
    }

    /**
     * Returns the query string for this query object.
     *
     * @throws ezcQueryInvalidException if no table or no values have been set.
     * @return string
     */
    public function getQuery()
    {
        $query = "INSERT";
        if ($this->ignore) {
            $query .= " " . $this->ignore;
        }
        $query .= substr(parent::getQuery(), 6);
        return $query;
    }
}