--TEST--
SUCCESS: mysql_db_query()
--FILE--
<?php
/*
mysql_db_query

(PHP 3, PHP 4, PHP 5)
mysql_db_query -- Send a MySQL query
Description
resource mysql_db_query ( string database, string query [, resource link_identifier] )

mysql_db_query() selects a database, and executes a query on it.
Parameters

database

    The name of the database that will be selected. 
query

    The MySQL query. 
link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns a positive MySQL result resource to the query result, or FALSE on error. The function also returns TRUE/FALSE for INSERT/UPDATE/DELETE queries to indicate success/failure. 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

if (function_exists('mysql_db_query')) {
    $res = mysql_db_query($db, 'SELECT DATABASE() AS db');
    if (!$res) {
        printf("FAILURE: Cannot run query on default connection, [%d] %s\n",
            mysql_errno($con), mysql_error($con));
    }

    // mysqli resource is an object 
    if (!is_resource($res) && !is_bool($res) && !is_object($res)) {
        printf("FAILURE: Function is expected to return resource or boolean value, using default connection, got %s, [%d] %s\n",
           gettype($res), mysql_errno($con), mysql_error($con));
    }

    $row = mysql_fetch_assoc($res);
    if ($row['db'] != $db) {
        printf("FAILURE: Got connected to %s, expected %s using default connection, [%d] %s\n",
            $row['db'], $db, mysql_errno($con), mysql_error($con));
    }

    mysql_free_result($res);

    $res = mysql_db_query($db, 'SELECT DATABASE() AS db', $con);
    if (!$res) {
        printf("FAILURE: Cannot run query, [%d] %s\n",
            mysql_errno($con), mysql_error($con));
    }

    if (!is_resource($res) && !is_bool($res) && !is_object($res)) {
        printf("FAILURE: Function is expected to return resource or boolean value, got %s, [%d] %s\n",
           gettype($res), mysql_errno($con), mysql_error($con));
    }

    $row = mysql_fetch_assoc($res);
    if ($row['db'] != $db) {
        printf("FAILURE: Got connected to %s, expected %s, [%d] %s\n",
            $row['db'], $db, mysql_errno($con), mysql_error($con));
    }

    mysql_free_result($res);

    $res = mysql_db_query($db, 'SELECT DATABASE() AS db', $illegal_link_identifier);

    if (!is_resource($res) && !is_bool($res) && !is_object($res)) {
        printf("FAILURE: Function is expected to return resource or boolean value, illegal link identifier, got %s, [%d] %s\n",
           gettype($res), mysql_errno($con), mysql_error($con));
    }

    if ($res) {
        printf("FAILURE: Function is expected to return false, illegal link identifier, [%d] %s\n",
            mysql_errno($con), mysql_error($con));
    }

    mysql_free_result($res);
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
73, E_NOTICE, Undefined variable: illegal_link_identifier
73, E_WARNING, mysqli_query() expects parameter 1 to be mysqli, null given
83, E_WARNING, mysqli_free_result() expects parameter 1 to be mysqli_result, boolean given
--EXPECT-CONVERTER-ERRORS--
30, 32, 32, 39, 45, 56, 62, 73, 75,
--ENDOFTEST--