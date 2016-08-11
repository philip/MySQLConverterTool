--TEST--
SUCCESS: mysql_get_client_info
--FILE--
<?php
/*
mysql_get_client_info

(PHP 4 >= 4.0.5, PHP 5)
mysql_get_client_info -- Get MySQL client info
Description
string mysql_get_client_info ( void )

mysql_get_client_info() returns a string that represents the client library version.
Return Values

The MySQL client version. 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$client_info = mysql_get_client_info();
if (!is_string($client_info)) {
    printf("FAILURE: expected string value, got %s value\n", gettype($client_info));
} else {
    printf("SUCCESS\n");
}
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
--EXPECT-CONVERTER-ERRORS--
--ENDOFTEST--