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
               $logger->debug('SQL:: ==> input_parameters: ', array_values($input_parameters));
            }
        }
        return $query;
    }

    /**
     * @internal
     */
    public function __destruct()
    {
        $this->closeCursor();
    }
}
