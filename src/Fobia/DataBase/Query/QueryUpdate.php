<?php
/**
 * QueryUpdate class  - QuerySelect.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\DataBase\Query;

use ezcQueryUpdate;

/**
 * QueryUpdate class
 *
 * @package Fobia.DataBase.Query
 */
class QueryUpdate extends ezcQueryUpdate
{

    /**
     * Stores the ORDER BY part of the SQL.
     *
     * @var string
     */
    protected $orderString = null;

    /**
     * Stores the LIMIT part of the SQL.
     *
     * @var string
     */
    protected $limitString = null;

    /**
     * Returns SQL that orders the result set by a given column.
     *
     * You can call orderBy multiple times. Each call will add a
     * column to order by.
     *
     *
     * @param string $column a column name in the result set
     * @param string $type if the column should be sorted ascending or descending.
     *        you can specify this using ezcQuerySelect::ASC or ezcQuerySelect::DESC
     * @return \Fobia\DataBase\Query\QueryUpdate a pointer to $this
     */
    public function orderBy($column, $type = 'ASC')
    {
        $string = $this->getIdentifier($column);
        if ($type == 'DESC') {
            $string .= ' DESC';
        }
        if ($this->orderString == '') {
            $this->orderString = "ORDER BY {$string}";
        } else {
            $this->orderString .= ", {$string}";
        }
        $this->lastInvokedMethod = 'order';

        return $this;
    }

    /**
     * Returns SQL that limits the result set.
     *
     * $limit controls the maximum number of rows that will be returned.
     * $offset controls which row that will be the first in the result
     * set from the total amount of matching rows.
     *
     *
     * LIMIT is not part of SQL92. It is implemented here anyway since all
     * databases support it one way or the other and because it is
     * essential.
     *
     * @param string $limit integer expression
     * @param string $offset integer expression
     * @return \Fobia\DataBase\Query\QueryUpdate
     */
    public function limit($limit)
    {
        $this->limitString = "LIMIT {$limit}";
        $this->lastInvokedMethod = 'limit';

        return $this;
    }

    /**
     * Returns the query string for this query object.
     *
     * @todo wrong exception
     * @throws ezcQueryInvalidException if no table or no values have been set.
     * @return string
     */
    public function getQuery()
    {
        $query = parent::getQuery();

        if ($this->orderString != null) {
            $query = "{$query} {$this->orderString}";
        }
        if ($this->limitString != null) {
            $query = "{$query} {$this->limitString}";
        }

        return $query;
    }
}