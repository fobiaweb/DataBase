<?php
/**
 * QueryInsert class  - QueryInsert.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 * @version    $Id: Fri Sep 12 15:10:29 2014 +0400$
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Fobia\DataBase\Query;

use ezcQueryInsert;

/**
 * QueryInsert class
 *
 * @author    Dmitriy Tyurin <fobia3d@gmail.com>
 * @package   Fobia.DataBase.Query
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
     * @return \ezcQueryInsert
     */
    public function insertIntoIgnore( $table )
    {
        $this->ignore = 'IGNORE' ;
        return $this->insertInto( $table );
    }

    /**
     * Игнорированая вставка
     *
     * @param bool $check
     * @return \ezcQueryInsert
     */
    public function ignore( $check = true )
    {
        $this->ignore = ($check) ? 'IGNORE' : false;
        return $this;
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