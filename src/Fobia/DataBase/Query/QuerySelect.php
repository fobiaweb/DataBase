<?php
/**
 * QuerySelect class  - QuerySelect.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\DataBase\Query;

use ezcQuerySelect;

/**
 * QuerySelect class
 *
 * @author    Dmitriy Tyurin <fobia3d@gmail.com>
 * @package   Fobia.DataBase.Query
 */
class QuerySelect extends ezcQuerySelect
{
    /**
     * Количество всех найденых строк и список строк по limit
     *
     * @return array [count, items]
     */
    public function fetchItemsCount()
    {
        if (!$this->limitString) {
            trigger_error("В запросе 'SELECT'  не установлен 'limit'", E_USER_WARNING);
            $this->limit(1000, 0);
        }
        $s = $this->prepare();
        $s->execute();
        return array(
            'count' => $this->findAll(),
            // 'items' => $s->fetchAll()
            'items' => call_user_func_array(array($s, 'fetchAll'), func_get_args())
        );
    }

    /**
     * Количество всех найденых строк
     *
     * @return int
     */
    public function findAll()
    {
        $q = clone $this;

        $q->selectString = null;
        $q->limitString  = null;
        $q->orderString = null;
        $q->groupString = null;

        $q->select('COUNT(*) AS count');
        $stmt   = $q->prepare();
        $stmt->execute();
        $result = $stmt->fetch();
        return (int) $result['count'];
    }


    /* ***********************************************
     * OVERRIDE
     * ********************************************** */

    /**
     * Возвращает SQL для объединения или готовит $fromString для объединения.
     *
     * See {@see \ezcQuerySelect::doJoin()} for an example.
     *
     * @see \ezcQuerySelect::doJoin()
     * @param string $type       The join type: inner, right or left.
     * @param string $table2,... The table to join with, followed by either the
     *                           two join columns, or a join condition.
     * @return ezcQuery
     */
    protected function doJoin($type)
    {
        $this->lastInvokedMethod = 'from';
        return call_user_func_array(array('parent', 'doJoin'), func_get_args());
    }

    /**
     * Смещение строк результата запроса
     *
     * @param int $offset  integer expression
     * @return \Fobia\DataBase\Query\QuerySelect
     */
    public function offset($offset)
    {
        $limit = 1000;
        if ( $this->limitString )
        {
            if(preg_match('/LIMIT ([^\s]+)/', $this->limitString, $ml)) {
                $limit = $ml[1];
            }
        }
        $this->limit($limit, $offset);
        return $this;
    }

    /**
     * Сбрасывает объект запроса для повторного использования.
     *
     * @param string $name
     * @return void
     * @throws \ezcDbMissingParameterException
     */
    public function reset($name = null)
    {
        if ($name === null) {
            return parent::reset();
        }
        switch ($name) {
            case "select":
            case "from":
            case "where":
            case "group":
            case "having":
            case "order":
            case "limit":
                $name .= "String";
                $this->$name = null;
                break;
            default:
                throw new \ezcDbMissingParameterException("reset", $name);
                break;
        }
    }


    /**
     * Возвращает полный 'select' строку запроса.
     *
     * Этот метод использует методы сборки построения
     * Различных частей запроса на выборку
     *
     * @throws ezcQueryInvalidException если не удалось построить правильный запрос.
     * @return string
     */
    public function getQuery()
    {
        if (!$this->selectString) {
            $this->select('*');
        }

        return parent::getQuery();
    }
}