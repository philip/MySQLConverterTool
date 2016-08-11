--TEST--
SUCCESS: mysql_insert_id()
--FILE--
<?php
/*
mysql_insert_id

(PHP 3, PHP 4, PHP 5)
mysql_insert_id -- Get the ID generated from the previous INSERT operation
Description
int mysql_insert_id ( [resource link_identifier] )

Retrieves the ID generated for an AUTO_INCREMENT column by the previous INSERT query.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

The ID generated for an AUTO_INCREMENT column by the previous INSERT query on success, 0 if the previous query does not generate an AUTO_INCREMENT value, or FALSE if no MySQL connection was established.
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

if (!mysql_query('DELETE FROM nobody', $con)) {
    printf("FAILURE: Cannot delete records, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!mysql_query('INSERT INTO nobody(id, msg) VALUES (1, "mysql_insert_id()")', $con)) {
    printf("FAILURE: Cannot insert, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$id_default = mysql_insert_id();
$id_con = mysql_insert_id($con);

if ($id_default != $id_con) {
    printf("FAILURE: Different values for default and specified connection\n");
}

if (!is_int($id_con)) {
    printf("FAILURE: Function should have returned an integer value, got %s value\n", gettype($id_con));
}

if ($id_con !== 0) {
    printf("FAILURE: Expecting 0, because table has no auto_increment column, got %d\n", $id_con);
}

$id_con = mysql_insert_id($illegal_link_identifier);
if (!is_bool($id_con)) {
    printf("FAILURE: mysql_insert_id(<illegal_link_identifier>) should have returned a boolean value, got %s value\n", gettype($id_con));
}

if ($id_con !== false) {
    printf("FAILURE: Function should have returned false\n");
}

if (!mysql_query('DELETE FROM root', $con)) {
    printf("FAILURE: Cannot delete records from root, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!mysql_query('INSERT INTO root(msg) VALUES ("mysql_insert_id()")', $con)) {
    printf("FAILURE: Cannot insert, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$id_con = mysql_insert_id($con);
if (!is_int($id_con)) {
    printf("FAILURE: Function should have returned an integer value for auto_increment column, got %s value\n", gettype($id_con));
}

if ($id_con < 1) {
    printf("FAILURE: Function returned bogus value for auto_increment column\n");
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
52, E_NOTICE, Undefined variable: illegal_link_identifier
52, E_WARNING, mysqli_insert_id() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
24, 26, 26, 31,
--ENDOFTEST--