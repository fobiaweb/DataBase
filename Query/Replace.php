<?php
/**
 * Replace class  - Replace.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\DataBase\Query;

use \PDO;

/**
 * Replace class
 *
 * @package     Fobia.DataBase.Query
 */
class Replace extends Insert
{

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->_command = "REPLACE";
    }
}