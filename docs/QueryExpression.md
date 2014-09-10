## QueryExpression

Класс `ezcQueryExpression` используется для создания базы данных независимой SQL выражение. 

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
- [eq](#eq)
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


##### eq

```php
$q->expr->eq( 'id', $q->bindValue( 1 ) ) );
// SELECT * FROM table WHERE id = :ezcValue1
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