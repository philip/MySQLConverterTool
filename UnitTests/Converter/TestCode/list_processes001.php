--TEST--
SUCCESS: mysql_list_processes
--FILE--
<?php
/*
mysql_list_processes

(PHP 4 >= 4.3.0, PHP 5)
mysql_list_processes -- List MySQL processes
Description
resource mysql_list_processes ( [resource link_identifier] )

Retrieves the current MySQL server threads.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

A result pointer resource on success, or FALSE on failure.
Examples
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

if (!($res = mysql_list_processes())) {
    printf("FAILURE: cannot run mysql_list_fields() on default connection, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$row = mysql_fetch_array($res);

if (!is_array($row)) {
    printf("FAILURE: expecting array, got %s value, [%d] %s\n", gettype($row), mysql_errno($con), mysql_error($con));
}
if (!array_key_exists('Id', $row) || !array_key_exists('User', $row) || !array_key_exists('Host', $row) ||
    !array_key_exists('db', $row) || !array_key_exists('Command', $row) || !array_key_exists('Time', $row) ||
    !array_key_exists('State', $row) || !array_key_exists('Info', $row) ||
    !array_key_exists(0, $row) || !array_key_exists(1, $row) || !array_key_exists(2, $row) ||
    !array_key_exists(3, $row) || !array_key_exists(4, $row) || !array_key_exists(5, $row) ||
    !array_key_exists(6, $row) || !array_key_exists(7, $row) ||
    $row[0] != $row['Id']) {
    printf("FAILURE: result hash does not have the expected entries, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

if (!($res = mysql_list_processes($con))) {
    printf("FAILURE: cannot run mysql_list_fields(), [%d] %s\n", mysql_errno(), mysql_error());
}

mysql_free_result($res);

$res = mysql_list_processes($illegal_link_identifier);
if (!is_null($res)) {
    printf("FAILURE: expecting null value, got %s value, [%d] %s\n", gettype($res), mysql_errno($con), mysql_error($con));
}

if ($res) {
    printf("FAILURE: expecting false, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
60, E_NOTICE, Undefined variable: illegal_link_identifier
60, E_WARNING, mysqli_query() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
25, 27, 27, 32, 56, 56,
--ENDOFTEST--