<?php
/**
 * QueryReplace class  - QueryReplace.php file
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
 * QueryReplace class
 *
 * @author    Dmitriy Tyurin <fobia3d@gmail.com>
 * @package   Fobia.DataBase.Query
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