PHP PDO Wrapper
====================

**Конструктор запросов**

Конструктор запросов  (на основании библиотеке [eZ Components][]). предоставляет объектно-ориентированный способ написания SQL-запросов. 
Он позволяет разработчику использовать методы и свойства класса для того, чтобы указать отдельные части SQL-запроса. 
Затем конструктор собирает отдельные части в единый SQL-запрос, который может быть выполнен вызовом методов ``query`` или ``prepare``

See [eZ Components](http://ezcomponents.org/), [tutorials Database](http://ezcomponents.org/docs/tutorials/Database/)

## Table of contents

 * [Usage](#usage)
 * [QueryExpression](docs/QueryExpression.md)
 * [Tests](tests/README)
    * [phpunit](tests/README)

## Usage

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




------------------

[eZ Components]: http://ezcomponents.org/
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md