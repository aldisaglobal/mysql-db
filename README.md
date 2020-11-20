# Library Package

Vendor: aldisaglobal  
Package: mysql-db  
Author: Abid  
Version: 0.2

## Connection Parameters

Make available the following Connection parameters:

- `MYSQL_HOST` _the URI for the MySQL server_
- `MYSQL_USER` _the username_
- `MYSQL_PASS` _the password_
- `MYSQL_DB` _the database_

### Option 1: As Environment Variables

Place connection params in $\_ENV superglobal
Optional: You can use the \vlucas\dotenv package with a .env file to populate $\_ENV variables

### Option 2: As Constants

You can `define` these constants in a settings file

### Option 3: As an Array

You can create and pass a parameters array to the create method
`$params = array('MYSQL_HOST'=>"hostname", ...)`

## Instantiate a Database Object

Use the static `create` method to get the DB object:

```
use AldisaGlobal\MySQL\DB;

$db = DB:create([$params]);
```

## Execute queries on the Database Object

`$db->query("...mysql statement...");`

## Iterate SELECT Queries

```
foreach ($db as $row) {
	echo $row->field;
}
```

## The full Object API

`\AldisaGlobal\MySQL\DB::create(]$params])` - returns object with connection

`$db->init()` - closes any open result

`$db->escape($str)` - returns escaped string

`$db->getResponse()`- returns last server response `$db->getError()` - returns error string from last query

`$db->getErrno()`- returns errno from last query `$db->hasError()` - true if last query had an error

`$db->query($sql, [$buffered=true])`- executes query, pass false as second arg for unbuffered query

`$db->hasResult()` - whether query returned a result

`$db->getInsertID()`- returns InsertID of last insert query `$db->nextRow([$mode = "object"])`- returns next row of result as object [pass "array" to get back an assoc array]

`$db->firstRow([$mode = "object"])` - returns first row of result as object [pass "array" to get back an assoc array]

`$db->getRowNum($num, $mode = "object")` - returns row at \$num index [pass "array" to get back an assoc array]

`$db->getNumFields()`- returns number of columns in result row `$db->getFields()` - returns array of column names

`$db->getNumRows()` - returns total number of rows in result [not accurate for unbuffered queries]
