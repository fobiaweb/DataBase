DataBase Component
====================

**Конструктор запросов**

Конструктор запросов  (на основании библиотеке [eZ Components][]). предоставляет объектно-ориентированный способ написания SQL-запросов. 
Он позволяет разработчику использовать методы и свойства класса для того, чтобы указать отдельные части SQL-запроса. 
Затем конструктор собирает отдельные части в единый SQL-запрос, который может быть выполнен вызовом методов ``query`` или ``prepare``

See [eZ Components](http://ezcomponents.org/), [tutorials Database](http://ezcomponents.org/docs/tutorials/Database/)


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




## QueryExpression

Класс ``ezcQueryExpression`` используется для создания базы данных независимой SQL выражение. 

- ``add``
- ``avg``
- ``between``
- ``bitAnd``
- ``bitOr``
- ``bitXor``
- ``ceil``
- ``concat``
- ``count``
- ``dateAdd``
- ``dateExtract``
- ``dateSub``
- ``div``
- ``eq``
- ``floor``
- ``gt``
- [gte](#gte)
- ``hasAliases``
- ``in``
- ``isNull``
- [lAnd](#land)
- ``length``
- ``like``
- [lOr](#lor)
- ``lower``
- ``lt``
- ``lte``
- ``max``
- ``md5``
- ``min``
- ``mod``
- ``mul``
- [neq](#neq)
- ``not``
- ``now``
- ``position``
- ``round``
- [searchedCase](#searchedcase)
- ``setAliases``
- ``setValuesQuoting``
- ``sub``
- ``subString``
- ``sum``
- ``unixTimestamp``
- ``upper``

```php
$q = ezcDbInstance::get()->createSelectQuery(); 
$e = $q->expr;
$q->select( '*' )->from( 'table' ) ->where( $expr )
```


##### gte

```php
$q->expr->gte( 'id', $q->bindValue( 1 ) ) );
// SELECT * FROM table WHERE id >= :ezcValue1
```


##### lAnd

```php
$q->where( $e->lAnd( $e->eq( 'id', $q->bindValue( 1 ) ),
                     $e->eq( 'id', $q->bindValue( 2 ) ) ) 
          );
// SELECT * FROM table WHERE ( id = :ezcValue1 AND id = :ezcValue2 )
```


##### lOr

```php
$q->where( $e->lOr( $e->eq( 'id', $q->bindValue( 1 ) ),
                    $e->eq( 'id', $q->bindValue( 2 ) ) ) 
         );
// SELECT * FROM table WHERE ( id = :ezcValue1 OR id = :ezcValue2 )
```


##### neq

```php
$q->where( $q->expr->neq( 'id', $q->bindValue( 1 ) ) );
// SELECT * FROM table WHERE id <> :ezcValue1
```


##### searchedCase

```php
$q->select(
    $q->expr->searchedCase(
        array( $q->expr->gte( 'column1', 20 ), 'column1' )
           , array( $q->expr->gte( 'column2', 50 ), 'column2' )
           , 'column3'
        )
);
// SELECT  CASE
//             WHEN column1 >= 20 THEN column1
//             WHEN column2 >= 50 THEN column2
//             ELSE column3
//           END
// FROM table
```


------------------

[eZ Components]: http://ezcomponents.org/
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md