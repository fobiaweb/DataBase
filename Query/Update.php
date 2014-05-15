<?php
/**
 * Update class  - Update.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\DataBase\Query;

use \Fobia\DataBase\Query\Implementation\Where;
use \PDO;

/**
 * Update class
 *
 * @package     Fobia.DataBase.Query
 */
class Update extends Where
{

    protected $_command = 'UPDATE';
    private $_filter    = null;

    /**
     * @param string $table таблицы обновления
     */
    public function __construct(PDO $db, $table)
    {
        parent::__construct($db);
        $this->_query['command'] = $table;
    }

    /**
     * В выражении SET указывается, какие именно столбцы следует модифицировать
     * и какие величины должны быть в них установлены.
     *
     * @param array|string $sets масив типа ключ, значение или сформулированая строка строка
     * @param boolean $escape - экранировать ли значение (только при передачи масива)
     * @return self
     */
    public function set($sets, $escape = true)
    {
        if (is_array($sets)) {
            if ($escape) {
                array_walk($sets, array($this, 'quoteValeu'));
            }
            array_walk($sets,
                       function(&$value, $key) {
                $value = $key . "=" . $value;
            });

            $sets = implode(", ", $sets);
        }

        $this->_query['set'] .= $sets;

        return $this;
    }

    /**
     * Добовляет установку в текущую команду
     * В выражении SET указывается, какие именно столбцы следует модифицировать
     * и какие величины должны быть в них установлены.
     *
     * @param string $column
     * @param string $value
     * @param boolean $escape
     * @return self
     */
    public function addSet($column, $value, $escape_quote = true)
    {
        if ($this->_filter) {
            if (!in_array($column, $this->_filter)) {
                return $this;
            }
        }

        if (isset($this->_query['set'])) {
            $this->_query['set'] .= ", ";
        } else {
            $this->_query['set'] = "";
        }

        if ($escape_quote) {
            $value = $this->quoteEscape($value);
        }

        $this->_query['set'] .= $column . "=" . $value;

        return $this;
    }

    /**
     * Ограничить вставляемые поля. Применяет к запросу только те поля,
     * что были здесь установлены.
     *
     * @param array $columns
     * @return self
     */
    public function filterColumn(array $columns)
    {
        $this->_filter = $columns;
    }

    /**
     * Если указывается ключевое слово IGNORE, то команда обновления не будет
     * прервана, даже если при обновлении возникнет ошибка дублирования ключей.
     * Строки, из-за которых возникают конфликтные ситуации, обновлены не будут.
     *
     * @return self
     */
    public function ignore()
    {
        $this->_query['ignore'] = ' IGNORE ';
        return $this;
    }
}