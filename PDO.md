# PDO
====================

destruct

```php
/**
 * @internal
 */
/*
public function __destruct()
{
    if ($this->profiles && ($this->logger instanceof LoggerInterface)) {
        $profiles = $this->getProfiles();
        foreach ($profiles as $row) {
            $this->logger->debug($row[2], array($row[1]));
        }
    }
}
```



    bool PDO::setAttribute ( int $attribute , mixed $value )

Присваивает атрибут PDO объекту. Некоторые основные атрибуты приведены ниже; отдельные драйверы могут имет свои дополнительные атрибуты.

    PDO::ATTR_CASE: Приводить имена столбцов к заданному регистру.

    PDO::CASE_LOWER: Приводить имена столбцов к нижнему регистру.
    PDO::CASE_NATURAL: Оставлять имена столбцов в том виде, в котором они выданы драйвером.
    PDO::CASE_UPPER: Приводить имена столбцов к верхнему регистру.
    PDO::ATTR_ERRMODE: Режим сообщений об ошибках.

    PDO::ERRMODE_SILENT: Только установка кодов ошибок.
    PDO::ERRMODE_WARNING: Вызывать E_WARNING.
    PDO::ERRMODE_EXCEPTION: Выбрасывать исключения.
    PDO::ATTR_ORACLE_NULLS (доступен для всех драйверов, не только для Oracle): Преобразование NULL в пустые строки.

    PDO::NULL_NATURAL: Без преобразования.
    PDO::NULL_EMPTY_STRING: Пустые строки преобразовывать в NULL.
    PDO::NULL_TO_STRING: NULL преобразовывать в пустые строки.
    PDO::ATTR_STRINGIFY_FETCHES: Преобразовывать числовые значения в строки во время выборки. Значение типа bool.
    PDO::ATTR_STATEMENT_CLASS: Задает пользовательский класс производный от PDOStatement. Атрибут нельзя использовать с PDO, использующими постоянные соединения. Принимает массив array(string classname, array(mixed constructor_args)).
    PDO::ATTR_TIMEOUT: Задает таймаут в секундах. Не все драйверы поддерживают эту опцию. Также назначение этого таймаута может отличаться в разных драйверах. Например, sqlite будет ждать это количество времени получения блокировки на запись. А другие драйверы могут использовать его, как таймаут подключения или чтения. Атрибут принимает значение типа int.
    PDO::ATTR_AUTOCOMMIT (доступен в OCI, Firebird и MySQL): Требуется ли автоматическая фиксация каждого отдельного выражения в запросе.
    PDO::ATTR_EMULATE_PREPARES Включение или выключение эмуляции подготавливаемых запросов. Некоторые драйверы не поддерживают подготавливаемые запросы, либо их поддержка ограничена. Эта настройка указывает PDO всегда эмулировать подготавливаемые запросы (если TRUE) или пытаться использовать родные средства драйвера (если FALSE). Если драйвер не сможет подготовить запрос, эта настройка сбросится в режим эмуляции. Атрибут принимает значение типа bool.
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY (доступен в MySQL): Использовать буферизованные запросы.
    PDO::ATTR_DEFAULT_FETCH_MODE: Задает режим выборки данных по умолчанию. Описание возможных режимов приведено в документации к методу PDOStatement::fetch().
 


Выполнение запроса с привязкой PHP переменных

```php
$calories = 150;
$colour = 'red';
$sth = $dbh->prepare('SELECT name, colour, calories
    FROM fruit
    WHERE calories < :calories AND colour = :colour');
$sth->bindParam(':calories', $calories, PDO::PARAM_INT);
$sth->bindValue(':colour', $colour, PDO::PARAM_STR, 12);
$sth->execute();

$sth->debugDumpParams();
```

```php
set profiling=1;
select count(*) from comment;
select count(*) from message;
show profiles;
```
