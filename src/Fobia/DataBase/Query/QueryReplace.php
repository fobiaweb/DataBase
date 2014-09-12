<?php
/**
 * QueryReplace class  - QueryReplace.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 * @version    $Id: 6beb96aeb935ccdd64ae58b9e75e66bf86e850bd 2014-09-12 15:51:09 +0400 $
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