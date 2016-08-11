--TEST--
FAILURE: mysql_unbuffered_query
--FILE--
<?php
/*
mysql_unbuffered_query

(PHP 4 >= 4.0.6, PHP 5)
mysql_unbuffered_query -- Send an SQL query to MySQL, without fetching and buffering the result rows
Description
resource mysql_unbuffered_query ( string query [, resource link_identifier] )

mysql_unbuffered_query() sends a SQL query query to MySQL, without fetching and buffering the result rows automatically, as mysql_unbuffered_query() does. On the one hand, this saves a considerable amount of memory with SQL queries that produce large result sets. On the other hand, you can start working on the result set immediately after the first row has been retrieved: you don't have to wait until the complete SQL query has been performed. When using multiple DB-connects, you have to specify the optional parameter link_identifier.
Parameters

query

    A SQL query 
link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

For SELECT, SHOW, DESCRIBE or EXPLAIN statements, mysql_unbuffered_query() returns a resource on success, or FALSE on error.

For other type of SQL statements, UPDATE, DELETE, DROP, etc, mysql_unbuffered_query() returns TRUE on success or FALSE on error. 

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

$res = mysql_unbuffered_query('DELETE FROM nobody', $con);
if (!is_bool($res)) {
    printf("FAILURE: expecting boolean value as a reply to DELETE, got %s value, [%d] %s\n", gettype($res),
        mysql_errno($con), mysql_error($con));
}

if (!$res) {
    printf("FAILURE: expecting true as a reply to DELETE, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$res = mysql_unbuffered_query("INSERT INTO nobody(id, msg) VALUES (1, 'one')", $con);
if (!is_bool($res)) {
    printf("FAILURE: expecting boolean value as a reply to INSERT, got %s value, [%d] %s\n", gettype($res),
        mysql_errno($con), mysql_error($con));
}

if (!$res) {
    printf("FAILURE: expecting true as a reply to INSERT, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$res = mysql_unbuffered_query('SELECT id, msg FROM nobody', $con);
if (!is_resource($res)) {
    printf("FAILURE: known change, mysql_unbuffered_query() returns resource, mysqli_query returns object\n");
}

if (!$res) {
    printf("FAILURE: expecting true as a reply to SELECT, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$row = mysql_fetch_assoc($res);
if (!is_array($row) || !$row) {
    printf("FAILURE: could not fetch record, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if ($row['id'] != 1) {
    printf("FAILURE: strange result, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

$res = mysql_unbuffered_query('SELECT id, msg FROM nobody');
if (!is_resource($res)) {
    printf("FAILURE: known change, mysql_unbuffered_query() returns resource (default connection), mysqli_query returns object\n");
}

if (!$res) {
    printf("FAILURE: expecting true as a reply to SELECT (default connection), [%d] %s\n", mysql_errno(), mysql_error());
}

mysql_free_result($res);

$res = mysql_unbuffered_query('SELECT id, msg FROM table_which_does_not_exist', $con);
if (!is_bool($res)) {
    printf("FAILURE: known change, mysql_unbuffered_query() returns false on error (unknown table), mysqli_query returns NULL\n");
}

if ($res) {
    printf("FAILURE: expecting false as a reply to SELECT (unknown table), [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$res = mysql_unbuffered_query('SELECT id, msg FROM nobody', $illegal_link_identifier);
if (!is_bool($res)) {
    printf("FAILURE: known change, mysql_unbuffered_query() returns false on error (illegal link identifier), mysqli_query returns NULL\n");
}

if ($res) {
    printf("FAILURE: expecting false as a reply to SELECT (illegal link identifier), [%d] %s\n", mysql_errno($illegal_link_identifier), mysql_error($illegal_link_identifier));
}

$res = mysql_unbuffered_query('SHOW TABLES', $con);
if (!is_resource($res)) {
    printf("FAILURE: known change, mysql_unbuffered_query() returns resource (SHOW TABLES), mysqli_query returns object\n");
}

if (!$res) {
    printf("FAILURE: expecting true as a reply to SHOW TABLES, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

$res = mysql_unbuffered_query('DESCRIBE nobody', $con);
if (!is_resource($res)) {
    printf("FAILURE: known change, mysql_unbuffered_query() returns resource (DESCRIBE), mysqli_query returns object\n");
}

if (!$res) {
    printf("FAILURE: expecting true as a reply to DESCRIBE, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

$res = mysql_unbuffered_query('EXPLAIN SELECT id FROM nobody', $con);
if (!is_resource($res)) {
    printf("FAILURE: known change, mysql_unbuffered_query() returns resource (EXPLAIN), mysqli_query returns object\n");
}

if (!$res) {
    printf("FAILURE: expecting true as a reply to EXPLAIN, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect
FAILURE: known change, mysql_unbuffered_query() returns resource, mysqli_query returns object
FAILURE: known change, mysql_unbuffered_query() returns resource (default connection), mysqli_query returns object
FAILURE: known change, mysql_unbuffered_query() returns false on error (illegal link identifier), mysqli_query returns NULL
FAILURE: known change, mysql_unbuffered_query() returns resource (SHOW TABLES), mysqli_query returns object
FAILURE: known change, mysql_unbuffered_query() returns resource (DESCRIBE), mysqli_query returns object
FAILURE: known change, mysql_unbuffered_query() returns resource (EXPLAIN), mysqli_query returns object

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
89, E_NOTICE, Undefined variable: illegal_link_identifier
89, E_WARNING, mysqli_query() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
30, 32, 32, 37, 58, 74, 78, 78, 97, 106, 115,
--ENDOFTEST--