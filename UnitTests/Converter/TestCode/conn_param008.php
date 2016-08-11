--TEST--
SUCCESS: mysql_info()
--FILE--
<?php
/*
mysql_info

(PHP 4 >= 4.3.0, PHP 5)
mysql_info -- Get information about the most recent query
Description
string mysql_info ( [resource link_identifier] )

Returns detailed information about the last query.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns information about the statement on success, or FALSE on failure. See the example below for which statements provide information, and what the returned value may look like. Statements that are not listed will return FALSE. 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

if (!mysql_select_db($db, $con)) {
    printf("FAILURE: could not select database %s, [%d] %s\n", $db, mysql_errno($con), mysql_error($con));
}

if (!mysql_query('DELETE FROM nobody', $con)) {
    printf("FAILURE: could not clear table nobody, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$res = mysql_query('INSERT INTO nobody(id, msg) VALUES (1, "mysql_info()"), (2, "mysqli_info()")', $con);
if (!$res) {
    printf("FAILURE: insert failed, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$info_default = mysql_info();
$info_con = mysql_info($con);
if ($info_con != $info_default) {
    printf("FAILURE: info of default connection and specified connection differ\n");
}

if (!is_string($info_con)) {
    printf("FAILURE: function should have returned a string, got %s value\n", gettype($info_con));
}

var_dump($info_con);

$info_con = mysql_info($illegal_link_identifier);
if (!is_null($info_con)) {
    printf("FAILURE: function should have returned a NULL value, got %s value\n", gettype($info_con));
}

if ($info_con) {
    printf("FAILURE: function should have failed with illegal link identifier\n");
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect
string(38) "Records: 2  Duplicates: 0  Warnings: 0"

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect
string(38) "Records: 2  Duplicates: 0  Warnings: 0"

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
52, E_NOTICE, Undefined variable: illegal_link_identifier
52, E_WARNING, mysqli_info() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
24, 26, 26, 31,
--ENDOFTEST--