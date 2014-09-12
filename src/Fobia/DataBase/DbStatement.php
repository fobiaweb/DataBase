<?php
/**
 * DBStatement class  - DBStatement.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 * @version    $Id: 2a08c86e415a397101ea220f8a6b0fdf1ec0bde5 2014-09-12 16:00:38 +0400 $
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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

    /**
     * @internal
     */
    protected function __construct(ezcDbHandler $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Выполнить подготовленый запрос.
     *
     * @param array $input_parameters
     * @return bool
     */
    public function execute($input_parameters = null)
    {
        $time  = microtime(true);
        if ($input_parameters === null) {
            $result = parent::execute();
        } else {
            $result = parent::execute($input_parameters);
        }

        if ( method_exists($this->connection, 'addLogRecord') ) {
            $this->connection->addLogRecord($this->queryString, $time, $this->rowCount(), $input_parameters);
        }

        return  $result;
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
     * @return void
     */
    public function __destruct()
    {
        $this->closeCursor();
    }
}
