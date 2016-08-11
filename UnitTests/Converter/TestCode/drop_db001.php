--TEST--
SUCCESS: mysql_drop_db()
--FILE--
<?php
/*
mysql_drop_db

(PHP 3, PHP 4, PHP 5)
mysql_drop_db -- Drop (delete) a MySQL database
Description
bool mysql_drop_db ( string database_name [, resource link_identifier] )

mysql_drop_db() attempts to drop (remove) an entire database from the server associated with the specified link identifier. This function is deprecated, it is preferable to use mysql_query() to issue a sql DROP DATABASE statement instead.
Parameters

database_name

    The name of the database that will be deleted. 
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

if (function_exists('mysql_drop_db')) {
    $test_db_name = '__converter_test_create_db';

    $ret = mysql_create_db($test_db_name, $con);
    if (!$ret) {
        printf("FAILURE: failed to create test database [1], check your setup! FAILURE: [%d] %s\n",
            mysql_errno($con), mysql_error($con));
    }

    $ret = mysql_drop_db($test_db_name, $con);
    if (!is_bool($ret)) {
        printf("FAILURE: mysql_drop_db(name, con) did not return boolean value, got %s, [%d] %s\n",
            gettype($ret), mysql_errno($con), mysql_error($con));
    }

    if (!$ret) {
        printf("FAILURE: mysql_drop_db(name, con) failed, [%d] %s\n",
            mysql_errno($con), mysql_error($con));
    }

    $ret = mysql_create_db($test_db_name);
    if (!$ret) {
        printf("FAILURE: failed to create test database [2], check your setup! FAILURE: [%d] %s\n",
            mysql_errno($con), mysql_error($con));
    }

    $ret = mysql_drop_db($test_db_name);
    if (!is_bool($ret)) {
        printf("FAILURE: mysql_drop_db(name) did not return boolean value, got %s, [%d] %s\n",
            gettype($ret), mysql_errno($con), mysql_error($con));
    }

    if (!$ret) {
        printf("FAILURE: mysql_drop_db(name) failed, [%d] %s\n",
            mysql_errno($con), mysql_error($con));
    }

    $ret = mysql_drop_db($test_db_name, $illegal_link_identifier);
    if (!is_bool($ret)) {
        printf("FAILURE: mysql_drop_db(name, illegal link identifier) did not return boolean value, got %s, [%d] %s\n",
            gettype($ret), mysql_errno($con), mysql_error($con));
    }

    if ($ret) {
        printf("FAILURE: mysql_drop_db(name, illegal link identifier) failed, [%d] %s\n",
            mysql_errno($con), mysql_error($con));
    }
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
66, E_NOTICE, Undefined variable: illegal_link_identifier
66, E_WARNING, mysqli_query() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
27, 29, 29, 38, 43, 52, 57, 66,
--ENDOFTEST--