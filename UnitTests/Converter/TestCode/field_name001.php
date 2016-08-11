--TEST--
SUCCESS: mysql_field_name()
--FILE--
<?php
/*
mysql_field_name

(PHP 3, PHP 4, PHP 5)
mysql_field_name -- Get the name of the specified field in a result
Description
string mysql_field_name ( resource result, int field_offset )

mysql_field_name() returns the name of the specified field index.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().
field_offset

    The numerical field offset. The field_offset starts at 0. If field_offset does not exist, an error of level E_WARNING is also issued.

Return Values

The name of the specified field index on success, or FALSE on failure. 
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

mysql_query('DELETE FROM nobody', $con);

if (!mysql_query("INSERT INTO nobody(id, msg) VALUES (1, 'msg')", $con)) {
    printf("FAILURE: cannot insert a dummy row, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if (!($res = mysql_query('SELECT * FROM nobody', $con))) {
    printf("FAILURE: cannot fetch the dummy row, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

$field_name = mysql_field_name($res, 1);
if (!is_string($field_name)) {
    printf("FAILURE: expecting integer value, got %s value\n", gettype($field_name));
}

if ($field_name != 'msg') {
    printf("FAILURE: expecting 'msg', got '%s'\n", $field_name);
}

$field_name = mysql_field_name($res, 2);
if (!is_bool($field_name)) {
    printf("FAILURE: expecting boolean value, got %s value\n", gettype($field_name));
}

if ($field_name) {
    printf("FAILURE: expecting false, got true\n");
}

mysql_free_result($res);
mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
56, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
56, E_NOTICE, Trying to get property of non-object
--EXPECT-CONVERTER-ERRORS--
27, 29, 29, 34,
--ENDOFTEST--