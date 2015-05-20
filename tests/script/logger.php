<?php
/**
 * logger.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */


require_once __DIR__ . '/../bootstrap.php';

$logger = function($level, $message, array $context = array()) {
    echo $level . ": " . $message . " -- " .json_encode($context) . PHP_EOL;
};


$db = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/ezc-test');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* @var $db Fobia\DataBase\Handler\MySQL */
$db->setLogger($logger);
$db->beginTransaction();
$db->query("SET NAMES 'UTF8'");
$r = $db->query("select * from authors");
$db->commit();




