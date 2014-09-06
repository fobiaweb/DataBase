<?php
/**
 * DbConnectionMysql class  - DbConnectionMysql.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */

namespace Fobia\DataBase\Handler;

use PDO;
use ezcDbHandlerMysql;
use PDOStatement;
use Fobia\DataBase\Query\QueryInsert;
use Fobia\DataBase\Query\QueryReplace;
use Fobia\DataBase\Query\QuerySelect;
use Fobia\DataBase\Query\QueryUpdate;

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
    public function __construct(array $dbParams)
    {
        parent::__construct($dbParams);

        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Fobia\DataBase\DbStatement', array($this)));

        // if (@$dbParams['charset']) {
        //     parent::query("SET NAMES '{$dbParams['charset']}'");
        // }
        /*
        $this->setLogger(function($t, $q, $args) {
            $message = date("[Y-m-d H:i:s]") . " [SQL]:: " . $q . "\n" ;
            if (isset($args['params'])) {
                $message .= "           --   ===> Params: " . json_encode($args['params']) . "\n" ;
            }
            if (isset($args['time'])) {
                $message .= "           --   ===> Time: " . $args['time'];
                if (isset($args['rows'])) {
                    $message .= ", Rows: " . $args['rows'] ;
                }
                $message .= "\n" ;
            }
            echo  $message;
        });
        /* */
        $this->logQuery('DEBUG', "Connect database '{$dbParams['database']}'");
    }

    public function logQuery($type, $query, $time = null, $rows = null, $params = null)
    {
        if ($this->logger) {
            $args = array();
            if ($time) {
               $args['time'] = round(microtime(true) - $time, 6);
            }
            if ($rows) {
               $args['rows'] = $rows;
            }
            if ($params) {
               $args['params'] = $params;
            }
            $args['debug'] = $this->debug_backtrace_smart();
            call_user_func_array($this->logger, array($type, $query, $args));
       }
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
     *
     * @param \Fobia\DataBase\DbStatement|string $logQuery
     * @param string|array $args
     */
//    public function log($logQuery, $args = null)
//    {
//        if (is_array($logQuery)) {
//            $query = array_shift($logQuery);
//            $logQuery = $query . "\n"
//                    . "-- ===> Params:: " . json_encode($logQuery);
//        }
//
//        $message = date("[Y-m-d H:i:s]") . " [SQL]:: " . $logQuery . "\n" ;
//        if ($args) {
//            if (is_array($args)) {
//                $args = json_encode($args) ;
//            }
//            $message .= "-- ===> " . $args  . "\n";
//        }

        // echo $message;

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
        
        // return $this->logger;
//    }

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
        $this->logger = $logger;
    }

    /* ***********************************************
     * OVERRIDE
     * ********************************************** */

    public function query($statement)
    {
        $time  = microtime(true);
        if ($query =  $this->pdo->query($statement)) {
            $query->time = microtime(true) - $time;
            $rows = $query->rowCount();
        }

        $this->logQuery('INFO', $statement, $time, $rows );
        return $query;
    }

    public function exec($statement)
    {
        $time  = microtime(true);
        $result = $this->pdo->exec($statement);
        
        $this->logQuery('INFO', $statement, $time, $result);
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
        $this->logQuery('DEBUG', "Begin transaction");
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
        $this->logQuery('DEBUG', ($r) ? "Commit transaction" : "Error commit transaction");
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
        $this->logQuery('DEBUG', "Rollback transaction");
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
            if (++$framesSeen < 2) {
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
