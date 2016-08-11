--TEST--
SUCCESS: mysql_get_proto_info()
--FILE--
<?php
/*
mysql_get_proto_info

(PHP 4 >= 4.0.5, PHP 5)
mysql_get_proto_info -- Get MySQL protocol info
Description
int mysql_get_proto_info ( [resource link_identifier] )

Retrieves the MySQL protocol.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns the MySQL protocol on success, or FALSE on failure. 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

$proto_info_default = mysql_get_proto_info();
$proto_info_con = mysql_get_proto_info($con);
if ($proto_info_con != $proto_info_default) {
    printf("FAILURE: proto info of default connection and specified connection differ\n");
}

if (!is_int($proto_info_con)) {
    printf("FAILURE: function should have returned an integer\n");
}

$proto_info_con = mysql_get_proto_info($illegal_link_identifier);
if (!is_bool($proto_info_con)) {
    printf("FAILURE: function should have returned a boolean value, got %s value\n", gettype($proto_info_con));
}

if ($proto_info_con) {
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
40, E_WARNING, mysqli_get_proto_info() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
24, 26, 26,
--ENDOFTEST--