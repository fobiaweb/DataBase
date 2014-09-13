PHP PDO Wrapper
====================

[![Latest Stable Version](https://poser.pugx.org/fobiaweb/database/v/stable.svg)](https://packagist.org/packages/fobiaweb/database) 
[![Total Downloads](https://poser.pugx.org/fobiaweb/database/downloads.svg)](https://packagist.org/packages/fobiaweb/database) 
[![License](https://poser.pugx.org/fobiaweb/database/license.svg)](https://packagist.org/packages/fobiaweb/database)
-----

[![Latest Stable Version](https://poser.pugx.org/fobiaweb/database/v/stable.svg)](https://packagist.org/packages/fobiaweb/database) 

---- 

[![Build Status](https://travis-ci.org/fobiaweb/DataBase.svg?branch=master)](https://travis-ci.org/fobiaweb/DataBase)
[![Build Status](https://travis-ci.org/fobiaweb/DataBase.svg?branch=develop)](https://travis-ci.org/fobiaweb/DataBase)


[ci skip]

**Конструктор запросов**

Конструктор запросов  (на основании библиотеке [eZ Components][]). предоставляет объектно-ориентированный способ написания SQL-запросов. 
Он позволяет разработчику использовать методы и свойства класса для того, чтобы указать отдельные части SQL-запроса. 
Затем конструктор собирает отдельные части в единый SQL-запрос, который может быть выполнен вызовом методов ``query`` или ``prepare``

> **NOTICE:** Библиотека `ezc/Database` не много модефецирована
> Смотрите официальную документацию [eZ Components](http://ezcomponents.org/), [tutorials Database](http://ezcomponents.org/docs/tutorials/Database/)

## Table of contents

 * [Usage](#usage)
 * * [Connection](#connection)
 * * [Выборга](#fetch)
 * [QueryExpression](docs/QueryExpression.md)
 * [Tests](tests/README)
    * [phpunit](tests/README)

## Usage

### Connection

Создаем подключение

```php
<?php
$db = Fobia\DataBase\DbFactory::create('mysql://root@localhost/test');
?>
```


Создаем один из генератор команды

```php
<?php
$q = $db->createDeleteQuery();
$q = $db->createInsertQuery();
$q = $db->createReplaceQuery();
$q = $db->createSelectQuery();
$q = $db->createUpdateQuery();
?>
```


Конструируем команду, вызывая поочередности характерные для нее методы. Вызываемые методы возвращают текущий объект, в связи с чем можно организовать цепочку вызовов

```php
<?php
$q->select('column')->limit(1)->orderBy('timestamp')->where('id > 10');
$q->offset(10);
?>
```


Подготавливаем запрос к выполнению. Возвращает ассоциированный с этим запросом объект

```php
<?php
$stmt = $q->prepare();
?>
```


Выполняем запрос иразбераем строки

```php
<?php
$stmt->execute();
print_r($stmt->fetchAll());
?>
```



### Fetch

Получения всех строк результата

```php
<?php
$stmt->execute();
print_r($stmt->fetchAll());

// Список объектов класса MyClass
$stmt->fetchAll(\PDO::FETCH_CLASS, 'MyClass');

// Список масивов(ассоциативных)
$stmt->fetchAll(\PDO::FETCH_ASSOC);

// Список масивов(списковых)
$stmt->fetchAll(\PDO::FETCH_NUM);

?>
```


Получения количество строк удовлетворяющем условиюменуя `LIMIT`

```php
<?php
$q = $db->createSelectQuery();
$q->select('column')->limit(1)->orderBy('timestamp')->where('id > 10');
$result = $q->findAll();
echo $result;
?>
```

Получения количество строк удовлетворяющем условиюменуя `LIMIT`, а также список строк в результирующей таблицы

```php
<?php
$q = $db->createSelectQuery();
$q->select('column')->limit(1)->orderBy('timestamp')->where('id > 10');
$result = $q->fetchItemsCount();
//$result = $q->fetchItemsCount(\PDO::FETCH_CLASS, 'MyClass');
//$result = $q->fetchItemsCount(\PDO::FETCH_ASSOC);
//$result = $q->fetchItemsCount(\PDO::FETCH_NUM);

print_r($result);
// Result:
/*
Array
(
    [count] => 10
    [items] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [firstname] => name_1
                    [lastname] => lastname_1
                )
            ...
        )
)
 */
?>
```

------------------

[eZ Components]: http://ezcomponents.org/
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md