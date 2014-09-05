<?php
/**
 * DBStatement class  - DBStatement.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */

namespace Fobia\DataBase;

use PDOStatement;
use ezcDbHandler;

/**
 * DBStatement class
 *
 * @author    Dmitriy Tyurin <fobia3d@gmail.com>
 * @package   Fobia.DataBase
 */
class DbStatement extends PDOStatement
{

    const CLASS_NAME = __CLASS__;

    /** @var \ezcDbHandler */
    protected $connection;

    /** @var float время выполнения последнего запроса */
    public $time = null;

    /**
     * @internal
     */
    protected function __construct(ezcDbHandler $connection)
    {
        $this->connection = $connection;
    }

    public function execute(/* array */ $input_parameters = null)
    {
        $_time  = microtime(true);
        if ($input_parameters === null) {
            $query = parent::execute();
        } else {
            $query = parent::execute($input_parameters);
        }
        $this->time = microtime(true) - $_time;

        if (method_exists($this->connection, 'log')) {
            $logger = $this->connection->log($this, $_time);
            if ($input_parameters) {
               // $logger = $this->connection->getLogger();
               $logger->debug('[SQL]:: ==> execute parameters: ', array_values($input_parameters));
            }
        }
        return $query;
    }

    /**
     * ?# - поля таблицы
     * ?d - целочисленые параметры
     * ?f - дробные параметры
     * ?s - строка
     * ?a - масив
     *
     * @param type $query
     */
    protected function parsePlaceholder($query, array $params = array() )
    {
        if ( substr_count ($query, '?') != count($params) ) {
            trigger_error("Не верное количество параметров", E_USER_ERROR);
        }
        return preg_replace_callback('/\?([#avsnd])/', function($m) use (&$params) {
            $db = $this->connection->quote($string);
            $val = array_shift($params);

            // Столбцы
            if ($m[1] == '#') {
                $val = (array) $val;
                array_walk($val, function(&$v) use ($db) {
                    $v = $db->quoteIdentifier($v);
                });
                return implode(", ", $val);
            }
            // масив значений
            if ($m[1] == 'a') {
                array_walk($val, function(&$v) use($db) {
                    $v = $db->connection->quote($v);
                });
                return implode(", ", $val);
            }
            // масив типа "ключ = значение"
            if ($m[1] == 'v') {
                $str = "";
                foreach ($val as $k => $v) {
                    $str .= "`{$k}` = '{$v}', ";
                }
                return substr($str, 0, -2);
            }
            // Прямая вставка строки
            if ($m[1] == 's') {
                return $val;
            }
            // Целое число
            if ($m[1] == 'n') {
                return ($val) ? $db->connection->quote($val) : 'NULL';
            }
            // Целое число
            if ($m[1] == 'd') {
                return (int) $val;
            }
        }, $query);
    }

    /**
     * Выводит асс. масив, где ключи это $key, а значение в зависимости от $columns
     * - [null] первое поле строки, исключая при этом поле $key
     * - [string] поле $columns
     * - [array] масив содержащий поля $columns
     *
     * @param string $key
     * @param string|array $columns
     * @return array
     */
    public function selectCol($key, $columns = null)
    {
        $result = array();
        $rows = $this->fetchAll();


        if ( ! $columns ) {
            foreach ( $rows as $row ) {
                $kv = $row[$key];
                unset($row[$key]);
                $result[$kv] = array_shift($row);
            }
            return $result;
        }

        if ( is_string( $columns ) ) {
            foreach ($rows as $row ) {
                $result[$row[$key]] = $row[$columns];
            }
            return $result;
        }

        $columns = array_flip((array) $columns);
        foreach ($rows as $row ) {
            $result[$row[$key]] = array_intersect_key($row, $columns);
        }
        return $result;
    }

    /**
     * @internal
     */
    public function __destruct()
    {
        $this->closeCursor();
    }
}
