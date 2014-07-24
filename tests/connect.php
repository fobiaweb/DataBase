<?php
/**
 * connect.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once 'bootstrap.php';

$db = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/test');

/* @var $db \Fobia\DataBase\Handler\DbConnectionMysql */

$q = $db->createSelectQuery();
$q->select('*')->from('users')->limit(10);
$stmt = $q->prepare();
$stmt->execute();
dump($stmt->fetchAll());