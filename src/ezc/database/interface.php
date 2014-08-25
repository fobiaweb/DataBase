<?php
/**
 * ezcDbInterface class  - interface.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * Интерфейс для всех реализаций драйверов баз данных.
 *
 * ezcDbInterface обеспечивает некоторую функциональность, которая не присутствует в PDO.
 * - Обработка offset/limit в datbase независимым образом
 * - Правильная рекурсивная обработка транзакций
 *
 * @author  Dmitriy Tyurin <fobia3d@gmail.com>
 * @package ezc.Database
 * @version 1.4.9
 */
interface ezcDbInterface
{
    /**
     * Возвращает указатель на обработчик базы данных.
     *
     * @return PDO
     */
    public function getPdo();

    /**
     * Начинает транзакцию.
     *
     * @return bool
     */
    public function beginTransaction();

    /**
     * Выполняет транзакцию.
     *
     * @return bool
     */
    public function commit();

    /**
     * Откат транзакции.
     *
     * @return bool
     */
    public function rollback();

    /**
     * Возвращает новый ezcQuerySelect производный объект для правильного типа базы данных.
     *
     * @return ezcQuerySelect
     */
    public function createSelectQuery();

    /**
     * Возвращает новый ezcQueryUpdate производный объект для правильного типа базы данных.
     *
     * @return ezcQueryUpdate
     */
    public function createUpdateQuery();

    /**
     * Возвращает новый ezcQueryInsert производный объект для правильного типа базы данных.
     *
     * @return ezcQueryInsert
     */
    public function createInsertQuery();

    /**
     * Возвращает новый ezcQueryDelete производный объект для правильного типа базы данных.
     *
     * @return ezcQueryDelete
     */
    public function createDeleteQuery();

    /**
     * Возвращает новый ezcQueryExpression производный объект для правильного типа базы данных.
     *
     * @return ezcQueryExpression
     */
    public function createExpression();

    /**
     * Returns a new ezcUtilities derived object for the correct database type.
     *
     * @return ezcDbUtilities
     */
    public function createUtilities();

    /**
     * Возвращает цитируемый идентификатора, которые будут использоваться
     * в SQL запросе. Этот метод принимает указанный идентификатор и цитирует
     * его, так что смело можно использовать в запросах SQL.
     *
     * @param  string $identifier  идентификатор для цитирования.
     * @return string строка процитированого идентификатора.
     */
    public function quoteIdentifier( $identifier );

    // public function exec($statement);
    // public function query($statement);
    // public function quote($string, $parameter_type = PDO::PARAM_STR);
    // public function prepare($statement, array $driver_options = array());
    // public function errorCode();
    // public function errorInfo();
    // public function getAttribute(  $attribute );
    // public function getAvailableDrivers();
    // public function inTransaction();
    // public function lastInsertId( $name = NULL );
    // public function setAttribute( $attribute , $value );

}
