<?php
/**
 * bootstrap.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

$loader = require  dirname(__DIR__) . '/vendor/autoload.php';
/* @var $logger \Composer\Autoload\ClassLoader */
$loader->add("Fobia\\DataBase\\", __DIR__ );

require_once __DIR__ . '/ezc/ezcDbTestCase.php';
require_once __DIR__ . '/ezc/ezcTestCaseBase.php';

date_default_timezone_set('Europe/Moscow');
error_reporting(error_reporting() & ~E_NOTICE);

// var_dump($logger);
if (class_exists('\Fobia\Debug\Log')) {
    \Fobia\Debug\Log::setLogger(new \Psr\Log\NullLogger());
}

if (isset($_SERVER['OS']) && $_SERVER['OS'] == 'Windows_NT') {
    $_ENV['SERVER'] = 'WINDOWS';
} else if (isset($_SERVER['USER']) && $_SERVER['USER'] == 'vagrant') {
    $_ENV['SERVER'] = 'VAGRANT';
}


$_ENV['database'] = array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'ezc-test',
    'port'     => ''
);
if ($_ENV['SERVER'] == 'WINDOWS') {
    $_ENV['database']['host'] = '127.0.0.1';// '192.168.33.10';
    $_ENV['database']['port'] = '4040';
    $_ENV['database']['username'] = $_ENV['database']['password'] = 'admin';
}

$_ENV['dsn'] = 'mysql://'
        . $_ENV['database']['username']
        . (($_ENV['database']['password']) ? ':' . $_ENV['database']['password'] : '')
        . '@' .$_ENV['database']['host']
        . (($_ENV['database']['port']) ? ':' . $_ENV['database']['port'] : '')
        . '/' . $_ENV['database']['database'] ;

