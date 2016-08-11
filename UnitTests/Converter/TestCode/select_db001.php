--TEST--
SUCCESS: mysql_select_db
--FILE--
<?php
/*
mysql_select_db

(PHP 3, PHP 4, PHP 5)
mysql_select_db -- Select a MySQL database
Description
bool mysql_select_db ( string database_name [, resource link_identifier] )

Sets the current active database on the server that's associated with the specified link identifier. Every subsequent call to mysql_query() will be made on the active database.
Parameters

database_name

    The name of the database that is to be selected. 
link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns TRUE on success or FALSE on failure. 
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

if (!($res = mysql_query('SELECT database() as db', $con))) {
    printf("FAILURE: cannot run SELECT, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!($row = mysql_fetch_assoc($res))) {
    printf("FAILURE: cannot fetch record, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if ($row['db'] != $db) {
    printf("FAILURE: select_db() did not switch the DB, should be connected to DB %s, are connected to %s,  [%d] %s\n", $db, $row['db'], mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

if (!mysql_select_db($db)) {
    printf("FAILURE: cannot select db (default connection) '%s', [%d] %s\n",
        $db, mysql_errno(), mysql_error());
}

if (!($res = mysql_query('SELECT database() as db', $con))) {
    printf("FAILURE: cannot run SELECT (default connection), [%d] %s\n", mysql_errno(), mysql_error());
}

if (!($row = mysql_fetch_assoc($res))) {
    printf("FAILURE: cannot fetch record (default connection), [%d] %s\n", mysql_errno(), mysql_error());
}

if ($row['db'] != $db) {
    printf("FAILURE: select_db() did not switch the DB (default connection), should be connected to DB %s, are connected to %s,  [%d] %s\n", $db, $row['db'], mysql_errno(), mysql_error());
}

mysql_free_result($res);

if (!defined('SELECT_DB_DATABASE')) {
    define('SELECT_DB_DATABASE', $db);
}

if (!mysql_select_db(SELECT_DB_DATABASE)) {
    printf("FAILURE [SELECT_DB_DATABASE]: cannot select db (default connection) '%s', [%d] %s\n",
        SELECT_DB_DATABASE, mysql_errno(), mysql_error());
}

if (!($res = mysql_query('SELECT database() as db', $con))) {
    printf("FAILURE [SELECT_DB_DATABASE]: cannot run SELECT (default connection), [%d] %s\n", mysql_errno(), mysql_error());
}

if (!($row = mysql_fetch_assoc($res))) {
    printf("FAILURE [SELECT_DB_DATABASE]: cannot fetch record (default connection), [%d] %s\n", mysql_errno(), mysql_error());
}

if ($row['db'] != SELECT_DB_DATABASE) {
    printf("FAILURE [SELECT_DB_DATABASE]: select_db() did not switch the DB (default connection), should be connected to DB %s, are connected to %s,  [%d] %s\n", SELECT_DB_DATABASE, $row['db'], mysql_errno(), mysql_error());
}

mysql_free_result($res);

$res = mysql_select_db($db, $illegal_link_identifier);
if (!is_bool($res)) {
    printf("FAILURE: expecting boolean value, got %s value\n", gettype($res));
}

if ($res) {
    printf("FAILURE: expecting false because of illegal link\n");
}

$res = mysql_select_db('this_database_does_never_ever_exist_27278kajha', $con);
if ($res) {
    printf("FAILURE: expecting false because of unknown database\n");
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
83, E_NOTICE, Undefined variable: illegal_link_identifier
83, E_WARNING, mysqli_query() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
27, 29, 29, 34, 49, 51, 51, 54, 54, 57, 57, 60, 60, 67, 69, 69, 72, 72, 75, 75, 78, 78, 83, 90,
--ENDOFTEST--