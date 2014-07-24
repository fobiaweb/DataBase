<?php
/**
 * test.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once __DIR__ . '/bootstrap.php';

$db = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/mysql');
$s = $db->query('SELECT * FROM user');
var_dump($s);


$q = $db->createSelectQuery();
$q->select('*')->from('user');
$stmt = $q->prepare();
//$stmt->execute();
var_dump($stmt->execute());

