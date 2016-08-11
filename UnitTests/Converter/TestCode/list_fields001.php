--TEST--
FAILURE: mysql_list_fields()
--FILE--
<?php
/*
mysql_list_fields

(PHP 3, PHP 4, PHP 5)
mysql_list_fields -- List MySQL table fields
Description
resource mysql_list_fields ( string database_name, string table_name [, resource link_identifier] )

Retrieves information about the given table name.

This function is deprecated. It is preferable to use mysql_query() to issue a SQL SHOW COLUMNS FROM table [LIKE 'name'] statement instead.
Parameters

database_name

    The name of the database that's being queried. 
table_name

    The name of the table that's being queried. 
link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

A result pointer resource on success, or FALSE on failure.

The returned result can be used with mysql_field_flags(), mysql_field_len(), mysql_field_name() and mysql_field_type(). 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

if (!mysql_select_db($db, $con)) {
    printf("FAILURE: cannot select db '%s', [%d] %s\n",
        $db, mysql_errno($con), mysql_error($con));
}

if (!($res = mysql_list_fields($db, 'nobody'))) {
    printf("FAILURE: cannot run mysql_list_fields() on default connection, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$row = mysql_fetch_array($res);

if (!is_array($row)) {
    printf("FAILURE: expecting array, got %s value, [%d] %s\n", gettype($row), mysql_errno($con), mysql_error($con));
}

if (!array_key_exists(0, $row) || !array_key_exists('Field', $row) || $row[0] != $row['Field']) {
    printf("FAILURE: hash looks strange, [%d] %s\n", gettype($row), mysql_errno($con), mysql_error($con));
}

if ($row['Field'] != 'id') {
    printf("FAILURE: strange field name,  [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

if (!($res = mysql_list_fields($db, 'nobody', $con))) {
    printf("FAILURE: cannot run mysql_list_fields(), [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

$res = mysql_list_fields($db, 'nobody', $illegal_link_identifier);

if (!is_bool($res)) {
    printf("FAILURE: expecting boolean value (illegal link identifier), got %s value,  [%d] %s\n", gettype($row), mysql_errno($con), mysql_error($con));
}

if ($res) {
    printf("FAILURE: expecting false (illegal link identifier), got true,   [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!defined('LIST_FIELDS_TABLE')) {
    define('LIST_FIELDS_TABLE', 'nobody');
}

if (!($res = mysql_list_fields($db, LIST_FIELDS_TABLE, $con))) {
    printf("FAILURE [LIST_FIELDS_TABLE]: cannot run mysql_list_fields(), [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$row = mysql_fetch_array($res);
if ($row['Field'] != 'id') {
    printf("FAILURE [LIST_FIELDS_TABLE]: return value looks strange, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

if (!defined('LIST_FIELDS_DATABASE')) {
    define('LIST_FIELDS_DATABASE', $db);
}

if (!($res = mysql_list_fields(LIST_FIELDS_DATABASE, LIST_FIELDS_TABLE, $con))) {
    printf("FAILURE [LIST_FIELDS_DATABASE, LIST_FIELDS_TABLE]: cannot run mysql_list_fields(), [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$row = mysql_fetch_array($res);
if ($row['Field'] != 'id') {
    printf("FAILURE [LIST_FIELDS_DATABASE, LIST_FIELDS_TABLE]: return value looks strange, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect
FAILURE: expecting array, got boolean value, [0] 
FAILURE: hash looks strange, [0] 0
FAILURE: strange field name,  [0] 
FAILURE [LIST_FIELDS_TABLE]: return value looks strange, [0] 
FAILURE [LIST_FIELDS_DATABASE, LIST_FIELDS_TABLE]: return value looks strange, [0] 

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
66, E_NOTICE, Undefined variable: illegal_link_identifier
66, E_WARNING, mysqli_query() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
34, 36, 36, 41, 45, 61, 66, 77, 89,
--ENDOFTEST--