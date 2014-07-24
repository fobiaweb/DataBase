<?php
/**
 * bootstrap.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


$logger = require_once __DIR__ . '/vendor/autoload.php';

/* @var $logger \Composer\Autoload\ClassLoader */
$logger->addPsr4("Fobia\\DataBase\\", dirname(__DIR__));

//echo "ok";