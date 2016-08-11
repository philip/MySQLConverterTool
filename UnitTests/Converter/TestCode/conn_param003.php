--TEST--
SUCCESS: mysql_client_encoding()
--FILE--
<?php
/*
mysql_client_encoding

(PHP 4 >= 4.3.0, PHP 5)
mysql_client_encoding -- Returns the name of the character set
Description
string mysql_client_encoding ( [resource link_identifier] )

Retrieves the character_set variable from MySQL.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns the default character set name for the current connection. 
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

$encoding_default = mysql_client_encoding();
$encoding_con = mysql_client_encoding($con);

if ($encoding_con != $encoding_default) {
    printf("FAILURE: different client encodings reported, [%d] %s\n", mysql_errno($con), mysql_error($con));
} elseif (!is_string($encoding_con)) {
    printf("FAILURE: no string returned, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
--EXPECT-CONVERTER-ERRORS--
24, 26, 26, 31,
--ENDOFTEST--