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
 * @package     Fobia.DataBase
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

    public function execute(array $input_parameters = null)
    {
        $time  = microtime(true);
        if ($input_parameters === null) {
            $query = parent::execute();
        } else {
            $query = parent::execute($input_parameters);
        }

        if (method_exists($this->connection, 'log')) {
            $logger = $this->connection->log($this, $time);
            if ($input_parameters) {
               // $logger = $this->connection->getLogger();
               $logger->debug('[SQL]:: ==> execute parameters: ', array_values($input_parameters));
            }
        }
        return $query;
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
