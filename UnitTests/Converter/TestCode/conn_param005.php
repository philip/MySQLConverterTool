--TEST--
SUCCESS: mysql_thread_id()
--FILE--
<?php
/*
mysql_thread_id

(PHP 4 >= 4.3.0, PHP 5)
mysql_thread_id -- Return the current thread ID
Description
int mysql_thread_id ( [resource link_identifier] )

Retrieves the current thread ID. If the connection is lost, and a reconnect with mysql_ping() is executed, the thread ID will change. This means only retrieve the thread ID when needed.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

The thread ID on success, or FALSE on failure.


NOTE: DOCUMENTATION IS WRONG - NULL on failure!
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

if (!mysql_select_db($db, $con)) {
    printf("FAILURE: [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$id_default = mysql_thread_id();
$id_con = mysql_thread_id($con);

if ($id_default != $id_con) {
    printf("FAILURE: Different values for default and specified connection\n");
}

if (!is_int($id_con)) {
    printf("FAILURE: Function should have returned an integer value, got %s value\n", gettype($id_con));
}

$id_con = mysql_thread_id($illegal_link_identifier);
if (!is_null($id_con)) {
    printf("FAILURE: Function should have returned a NULL value, got %s value\n", gettype($id_con));
}

if ($id_con !== null) {
    printf("FAILURE: Should return NULL because of illegal link identifier\n");
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
47, E_NOTICE, Undefined variable: illegal_link_identifier
47, E_WARNING, mysqli_thread_id() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
27, 29, 29, 34,
--ENDOFTEST--