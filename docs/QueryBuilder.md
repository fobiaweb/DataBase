QueryBuilder
============

### Подготовка конструктора запросов

- ``ezcDbUtilities()``
- ``ezcInsertQuery()``
- ``ezcQueryDelete()``
- ``ezcQueryExpression()``
- ``ezcQuerySelect()``
- ``ezcUpdateQuery()``


### INSERT

- ``hasAliases()``: 
- ``insertInto($table)``: 
- ``set($column, $expression)``: 
- ``setAliases($aliases)``: 
- ``subSelect()``: 
- ``where()``: 

### DELETE

- ``deleteFrom($table)``: 
- ``hasAliases()``: 
- ``set($column, $expression)``: 
- ``setAliases($aliases)``: 
- ``subSelect()``: 
- ``where()``: 

### SELECT

Запросы на получение данных соответствуют SQL-запросам `SELECT`. В конструкторе есть ряд методов для сборки отдельных частей SELECT запроса. Так как все эти методы возвращают экземпляр ``ezcQuery``, мы можем использовать их цепочкой.

- ``from()``: часть запроса после FROM.
- ``groupBy()``: часть запроса после GROUP BY.
- ``having()``: часть запроса после HAVING.
- ``innerJoin()``: добавляет к запросу CROSS JOIN.
- ``join()``: добавляет к запросу INNER JOIN.
- ``leftJoin()``: добавляет к запросу LEFT OUTER JOIN.
- ``limit()``: часть запроса после LIMIT.
- ``orderBy()``: часть запроса после ORDER BY.
- ``rightJoin()``: добавляет к запросу RIGHT OUTER JOIN.
- ``select()``: часть запроса после SELECT.
- ``selectDistinct()``: часть запроса после SELECT. Добавляет DISTINCT.
- ``where()``: часть запроса после WHERE.


### UPDATE

- ``hasAliases()``: 
- ``set($column, $expression)``: 
- ``setAliases($aliases)``: 
- ``subSelect()``: 
- ``update($table)``: 
- ``where()``: 


