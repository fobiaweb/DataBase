<?php
/**
 * QueryReplace class  - QueryReplace.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 * @version    $Id: 2a08c86e415a397101ea220f8a6b0fdf1ec0bde5 2014-09-12 16:00:38 +0400 $
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