<?php
/**
 * holder.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__ . '/../bootstrap.php';


$db = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/ezc-test');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/* @var $db \Fobia\DataBase\Handler\MySQL */
// $db = new \Fobia\DataBase\Handler\MySQL('mysql://root@localhost/ezc-test');


$query = 'SELECT ?# FROM ?# WHERE id IN (?a) AND ?# LIKE ?';
$params = array(
    array('id', 'firstname'),
    'authors',
    array('1', 2, 3, 4),
    'firstname',
    'name%'
);

$p = $db->parsePlaceholder($query, $params);
var_dump($p);

echo PHP_EOL . json_encode($p[1]);
echo "\n--------------\n";

$stmt = $db->queryExec($p[0], $p[1]);
var_dump($stmt->fetchAll());
