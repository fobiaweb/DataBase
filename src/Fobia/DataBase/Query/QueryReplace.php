<?php
/**
 * QueryReplace class  - QueryReplace.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */

namespace Fobia\DataBase\Query;

use ezcQueryInsert;

/**
 * QueryReplace class
 *
 * @package Fobia.DataBase.Query
 */
class QueryReplace extends ezcQueryInsert
{
    /**
     * Returns the query string for this query object.
     *
     * @throws ezcQueryInvalidException if no table or no values have been set.
     * @return string
     */
    public function getQuery()
    {
        $query = parent::getQuery();
        $query = "REPLACE" . substr($query, 6);
        return $query;
    }
}