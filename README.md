# DataBase Component
====================

A lightweight database layer on top of PHP's PDO.


# QueryExpression

- ``setAliases``
- ``hasAliases``
- ``setValuesQuoting``
- ``lOr``
- ``lAnd``
- ``not``
- ``add``
- ``sub``
- ``mul``
- ``div``
- ``eq``
- ``neq``
- ``gt``
- ``gte``
- ``lt``
- ``lte``
- ``in``
- ``isNull``
- ``between``
- ``like``
- ``avg``
- ``count``
- ``max``
- ``min``
- ``sum``
- ``md5``
- ``length``
- ``round``
- ``mod``
- ``now``
- ``subString``
- ``concat``
- ``position``
- ``lower``
- ``upper``
- ``floor``
- ``ceil``
- ``bitAnd``
- ``bitOr``
- ``bitXor``
- ``unixTimestamp``
- ``dateSub``
- ``dateAdd``
- ``dateExtract``
- ``searchedCase``

```php
$q = ezcDbInstance::get()->createSelectQuery(); 
$e = $q->expr;
$q->select( '*' )->from( 'table' ) ->where( $expr )
```

```php
$q->expr->gte( 'id', $q->bindValue( 1 ) ) );
// SELECT * FROM table WHERE id >= :ezcValue1

$q->where( $e->lOr( $e->eq( 'id', $q->bindValue( 1 ) ),
                    $e->eq( 'id', $q->bindValue( 2 ) ) ) 
         );
// SELECT * FROM table WHERE ( id = :ezcValue1 OR id = :ezcValue2 )

$q->where( $e->lAnd( $e->eq( 'id', $q->bindValue( 1 ) ),
                     $e->eq( 'id', $q->bindValue( 2 ) ) ) 
          );
// SELECT * FROM table WHERE ( id = :ezcValue1 AND id = :ezcValue2 )

$q->where( $q->expr->neq( 'id', $q->bindValue( 1 ) ) );
// SELECT * FROM table WHERE id <> :ezcValue1

$q->where( $q->expr->gte( 'id', $q->bindValue( 1 ) ) );
// SELECT * FROM table WHERE id >= :ezcValue1

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
