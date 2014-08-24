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

require_once __DIR__ . '/ezc/ezcTestUtils.php';


// var_dump($logger);
if (class_exists('\Fobia\Debug\Log')) {
    \Fobia\Debug\Log::setLogger(new \Psr\Log\NullLogger());
}
