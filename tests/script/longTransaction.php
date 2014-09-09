<?php
/**
 * longTransaction.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__ . '/../bootstrap.php';

$db = \Fobia\DataBase\DbFactory::create('mysql://root@localhost/ezc-test');

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




$db->beginTransaction();
$q = $db->createUpdateQuery();
$q->update('authors')->set('lastname', "'update'")->where('id > 5')->prepare()->execute();


$q = $db->createSelectQuery();
$stmt = $q->select('*')->from('authors')->prepare();
$stmt->execute();
print_r($stmt->fetchAll());

//sleep(60);
$db->commit();