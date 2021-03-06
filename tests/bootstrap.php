<?php
/**
 * bootstrap.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

$logger = require  dirname(__DIR__) . '/vendor/autoload.php';

/* @var $logger \Composer\Autoload\ClassLoader */
$loader->add('', __DIR__ . '/tests');
// $logger->add("Fobia\\DataBase\\", __DIR__);
//$logger->setPsr4("Fobia\\DataBase\\", __DIR__);


// var_dump($logger);
if (class_exists('\Fobia\Debug\Log')) {
    \Fobia\Debug\Log::setLogger(new \Psr\Log\NullLogger());
}
