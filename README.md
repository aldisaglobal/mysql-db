# Private Library Package

Vendor: aldisa
Package: mysql-db
Author: Abid
Version: 0.1

## Connection Parameters

Make available the following Connection parameters:

- `MYSQL_HOST` _the URI for the MySQL server_
- `MYSQL_USER` _the username_
- `MYSQL_PASS` _the password_
- `MYSQL_DB` _the database_

### Option 1: As Environment Variables

You can use `putenv` in PHP or the \vlucas\dotenv package to create environment variables

### Option 2: As Constants

You can `define` constants in a settings file

### Option 3: As an Array

You can create an array and pass it to the Factory method
`$params = array('MYSQL_HOST'=>"hostname", ...)`

## Instantiate a Database Object

Use the static Factory class to get an object:

```
use Aldisa\MySQL\Factory;

$db = Factory:create([$params]);
```

## Execute queries on the Database Object

`$db->query("...mysql statement...");`

## Iterate SELECT Queries

```
foreach ($db as $row) {
	echo $row->field;
}
```

## The full Object Interface

`$db->escape($str)` - returns escaped string
`$db->getResponse()` - returns last server response
`$db->getError()` - returns error string from last query
`$db->getErrno()` - returns errno from last query
`$db->hasError()` - true if last query had an error
`$db->query($sql)` - executes query
`$db->hasResult()` - whether query returned a result
`$db->getInsertID()` - returns InsertID of last insert query
`$db->getRow([$mode = "object"])` - returns next row as object {pass "array" to get back an assoc array}
`$db->getRowNum($num, $mode = "object")` - returns row at \$num index
`$db->getNumFields()` - returns number of columns in result row
`$db->getFields()` - returns array of column names
`$db->getNumRows()` - returns total number of rows in result
