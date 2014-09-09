<?php
/**
 * DbConnectionMysql class  - DbConnectionMysql.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Fobia\DataBase\Handler;

use PDO;
use ezcDbHandlerMysql;
use Fobia\DataBase\Query\QueryInsert;
use Fobia\DataBase\Query\QueryReplace;
use Fobia\DataBase\Query\QuerySelect;
use Fobia\DataBase\Query\QueryUpdate;

/*

    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';
$db->setLogger(function($t, $q, $args) {
    $message = date("[Y-m-d H:i:s]") . " [SQL]:: " . $q . "\n";
    if (isset($args['params'])) {
        $message .= "           --   ===> Params: " . json_encode($args['params']) . "\n";
    }
    if (isset($args['time'])) {
        $message .= "           --   ===> Time: " . $args['time'];
        if (isset($args['rows'])) {
            $message .= ", Rows: " . $args['rows'];
        }
        $message .= "\n";
    }
    if (isset($args['error'])) {
        list($sql, $code, $msg) =$args['error'];
        $message .= "           --   ===> Error $code($sql): $msg\n" ;
    }
    echo $message;
});
/* */

/**
 * Обертка PDO драйвера MySQL.
 *
 * Лог вызывают
 *  - DB::query [запрос, время, кол. строк]
 *  - DB::exec  [запрос, время, кол. строк]
 *  - DbStatement::execute [запрос, параметры, время, кол. строк]
 *  - DB::beginTransaction/commit/rollback [сообщение]
 *  - И простые сообщения от DB  [сообщение]
 * Будем также передавать и тум лога
 *
 * log($type, $query, $time, $row, $params)
 *
 * <code>
 * $db->setLogger(function($t, $q, $args) {
 *     $message = date("[Y-m-d H:i:s]") . " [SQL]:: " . $q . "\n";
 *     if (isset($args['params'])) {
 *         $message .= "           --   ===> Params: " . json_encode($args['params']) . "\n";
 *     }
 *     if (isset($args['time'])) {
 *         $message .= "           --   ===> Time: " . $args['time'];
 *         if (isset($args['rows'])) {
 *             $message .= ", Rows: " . $args['rows'];
 *         }
 *         $message .= "\n";
 *     }
 *     if (isset($args['error'])) {
 *         list($sql, $code, $msg) =$args['error'];
 *         $message .= "           --   ===> Error $code($sql): $msg\n" ;
 *     }
 *     echo $message;
 * });
 * </code>
 *
 *
 * @author    Dmitriy Tyurin <fobia3d@gmail.com>
 * @package   Fobia.DataBase.Handler
 */
class MySQL extends ezcDbHandlerMysql
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger = null;

    private $_debug = false;


    /**
     * Создает объект из параметров $dbParams.
     *
     * Supported database parameters are:
     * - dbname|database: Database name
     * - user|username:   Database user name
     * - pass|password:   Database user password
     * - host|hostspec:   Name of the host database is running on
     * - port:            TCP port
     * - charset:         Client character set
     * - socket:          UNIX socket path
     *
     * @param array $dbParams Database connection parameters (key=>value pairs).
     */
    public function __construct($dbParams)
    {
        parent::__construct($dbParams);

        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Fobia\DataBase\DbStatement', array($this)));

        // if (@$dbParams['charset']) {
        //     parent::query("SET NAMES '{$dbParams['charset']}'");
        // }

        // $this->addLogRecord('DEBUG', "Connect database '{$dbParams['database']}'");
    }

    /**
     * Залогировать че-нить
     *
     * @param string $message   строка сообщения / запроса
     * @param float $time       время начало запроса
     * @param int   $rows       кол. затронутых строк
     * @param array $params     параметры execute
     */
    public function addLogRecord($message, $time = null, $rows = null, $params = null)
    {
        if (!$this->logger) {
            return;
        }

        $context = array();
        
        // Error
        if ($this->pdo->errorCode() != \PDO::ERR_NONE) {
            $context['error'] = $this->pdo->errorInfo();
        }

        // Time
        if ($time) {
            $context['time'] = round(microtime(true) - $time, 7);
        }
        
        // Rows
        $context['rows']   = $rows;
        $context['params'] = $params;

        // array_keys($args, null)
        // Debug
        if ($this->_debug) {
            $context['debug'] = $this->debug_backtrace_smart();
        }

        call_user_func_array($this->logger, array('INFO', $message, $context));
    }

    protected function _log($query, array $context = array(), $level = 'DEBUG')
    {
        if (!$this->logger) {
            return;
        }
        call_user_func_array($this->logger, array($level, $query, $context));
    }

    /**
     * Все выполненные запросы за сессию с временем выполнения.
     *
     * @return array
     */
    public function getProfiles()
    {
        /*
        parent::query('SET profiling = 1');
        if ($this->profiles) {
            $stmt = parent::query('SHOW profiles');
            return $stmt->fetchAll();
        }
        return array();
         *
         */
        // $this->logQuery('DEBUG', "========TEST============");
    }

    /**
     * @return \Closure
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Установливает логгер.
     *
     * В функцию передаються [type, msq, array(time, rows, params)]
     * , где
     *     type   - уровень лога
     *     msg    -  сообщение, как правело запрос
     *     time   - время
     *     rows   - кол. затронувших строк
     *     params - переданые параметры в подготовленый запрос
     *     debug  - стек вызова
     *
     * @param callback $logger
     */
    public function setLogger($logger)
    {
        /*
        if ( (int) $logQuery->errorCode() ) {
            $error = $logQuery->errorInfo();
            $this->logger->error('==> [SQL]:: '. $error[1].': '.$error[2]);

            if ($this->log_error) {
                // LOGS_DIR . "/sql.log"
                $str = date("[Y-m-d H:i:s]") . " [SQL]:: Error " . $error[1] . ': '
                        . $error[2] . "\n"
                        . preg_replace(array("/\n/", "/\s*\n/"), array("\n    # ", "\n"), "    # $logQuery\n");
                file_put_contents($this->log_error, $str, FILE_APPEND);
            }
        }
         *
         */

        $this->logger = $logger;
    }

    /* ***********************************************
     * OVERRIDE
     * ********************************************** */

    /**
     * Выполняет SQL заявление, возвращая результат запроса в виде объекта \PDOStatement
     *
     * @param string $statement
     * @return \PDOStatement
     */
    public function query($statement)
    {
        $time  = microtime(true);
        $stmt =  $this->pdo->query($statement);

        $this->addLogRecord($statement, $time, ($stmt) ? $stmt->rowCount() : null);
        return $stmt;
    }

    /**
     * Выполняет оператор SQL и возвращает количество затронутых строк
     *
     * @param string $statement
     * @return int
     */
    public function exec($statement)
    {
        $time  = microtime(true);
        $result = $this->pdo->exec($statement);

        $this->addLogRecord($statement, $time, $result);
        return $result;
    }

    /**
     * Начинает транзакцию
     *
     * @see \ezcDbHandler::beginTransaction()
     * @return bool
     */
    public function beginTransaction()
    {
        $this->addLogRecord("Begin transaction");
        return parent::beginTransaction();
    }

    /**
     * Выполняет транзакцию.
     *
     * @see \ezcDbHandler::commit()
     * @return bool
     */
    public function commit()
    {
        $r = parent::commit();
        $this->addLogRecord("Commit transaction.");
        if (!$r) {
            $this->_log("Error commit transaction", array(), 'ERROR');
        }
        return $r;
    }

    /**
     * Откат транзакции.
     *
     * @see \ezcDbHandler::rollback()
     * @return bool
     */
    public function rollback()
    {
        $this->addLogRecord("Rollback transaction");
        return parent::rollback();
    }


    /**
     * Returns a new QueryInsert derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QueryInsert
     */
    public function createInsertQuery()
    {
        return new QueryInsert( $this );
    }

    /**
     * Returns a new QueryInsert derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QueryReplace
     */
    public function createReplaceQuery()
    {
        return new QueryReplace( $this );
    }

    /**
     * Returns a new QuerySelect derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QuerySelect
     */
    public function createSelectQuery()
    {
        return new QuerySelect( $this );
    }

    /**
     * Returns a new QueryUpdate derived object for the correct database type.
     *
     * @return \Fobia\DataBase\Query\QueryUpdate
     */
    public function createUpdateQuery()
    {
        return new QueryUpdate( $this );
    }


    /**
     * Стек вызова логера. (Взято из DbSimple)
     *
     * {@see http://en.dklab.ru}
     *
     * Return stacktrace. Correctly work with call_user_func*
     * (totally skip them correcting caller references).
     * If $returnCaller is true, return only first matched caller,
     * not all stacktrace.
     *
     * @param bool $ignoresRe
     * @param bool $returnCaller вернуть последнего колера
     * @return array
     *
     * @version 2.03
     */
    protected function debug_backtrace_smart($ignoresRe = null, $returnCaller = false)
    {
        if (!is_callable($tracer = 'debug_backtrace')) {
            return array();
        }
        $trace = $tracer();

        if ($ignoresRe !== null) {
            $ignoresRe = "/^(?>{$ignoresRe})$/six";
        }
        $smart = array();
        $framesSeen = 0;
        for ($i=0, $n = count($trace); $i < $n; $i++) {
            $t = $trace[$i];
            if (!$t) {
                continue;
            }

            // Next frame.
            $next = isset($trace[$i+1])? $trace[$i+1] : null;

            // Dummy frame before call_user_func* frames.
            if (!isset($t['file'])) {
                $t['over_function'] = $trace[$i+1]['function'];
                $t = $t + $trace[$i+1];
                $trace[$i+1] = null; // skip call_user_func on next iteration
            }

            // Skip myself frame.
            if (++$framesSeen < 3) {
                continue;
            }

            // 'class' and 'function' field of next frame define where
            // this frame function situated. Skip frames for functions
            // situated in ignored places.
            if ($ignoresRe && $next) {
                // Name of function "inside which" frame was generated.
                $frameCaller = (isset($next['class'])? $next['class'].'::' : '') . (isset($next['function'])? $next['function'] : '');
                if (preg_match($ignoresRe, $frameCaller)) {
                    continue;
                }
            }

            // On each iteration we consider ability to add PREVIOUS frame
            // to $smart stack.
            if ($returnCaller) {
                return $t;
            }
            $smart[] = $t;
        }
        return $smart;
    }

}
