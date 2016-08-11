--TEST--
SUCCESS: mysql_get_host_info()
--FILE--
<?php
/*
mysql_get_host_info

(PHP 4 >= 4.0.5, PHP 5)
mysql_get_host_info -- Get MySQL host info
Description
string mysql_get_host_info ( [resource link_identifier] )

Describes the type of connection in use for the connection, including the server host name.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns a string describing the type of MySQL connection in use for the connection or FALSE on failure. 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

$host_info_default = mysql_get_host_info();
$host_info_con = mysql_get_host_info($con);
if ($host_info_con != $host_info_default) {
    printf("FAILURE: host info of default connection and specified connection differ\n");
}

if (!is_string($host_info_con)) {
    printf("FAILURE: function should have returned a string\n");
}

$host_info_con = mysql_get_host_info($illegal_link_identifier);
if (!is_bool($host_info_con)) {
    printf("FAILURE: function should have returned a boolean value, got %s value\n", gettype($host_info_con));
}

if ($host_info_con) {
    printf("FAILURE: function should have failed with illegal link identifier\n");
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
40, E_NOTICE, Undefined variable: illegal_link_identifier
40, E_WARNING, mysqli_get_host_info() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
24, 26, 26,
--ENDOFTEST--