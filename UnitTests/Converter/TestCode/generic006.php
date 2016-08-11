--TEST--
FAILURE: mysql_field_seek
--FILE--
<?php
/*
mysql_field_seek

(PHP 3, PHP 4, PHP 5)
mysql_field_seek -- Set result pointer to a specified field offset
Description
bool mysql_field_seek ( resource result, int field_offset )

Seeks to the specified field offset. If the next call to mysql_fetch_field() doesn't include a field offset, the field offset specified in mysql_field_seek() will be returned.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().
field_offset

    The numerical field offset. The field_offset starts at 0. If field_offset does not exist, an error of level E_WARNING is also issued.

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

if (!mysql_query('DELETE FROM nobody', $con)) {
    printf("FAILURE: cannot clear table nobody, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!mysql_query("INSERT INTO nobody(id, msg) VALUES (1, 'one'), (2, 'two'), (3, 'three'), (4, 'four')", $con)) {
    printf("FAILURE: insert records into table nobody, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!($res = mysql_query('SELECT id, msg FROM nobody ORDER BY id ASC', $con))) {
    printf("FAILURE: cannot fetch records, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$row = mysql_fetch_row($res);
if (!is_array($row)) {
    printf("FAILURE: expecting array, got %s value,  [%d] %s\n", gettype($row), mysql_errno($con), mysql_error($con));
}

$ret = mysql_field_seek($res, 1);
if (!is_bool($ret)) {
    printf("FAILURE: expecting boolean value, got %s value, [%d] %s\n", gettype($ret), mysql_errno($con), mysql_error($con));
}

if (!$ret) {
    printf("FAILURE: expecting true, seek() failed, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$obj_seek = mysql_fetch_field($res);
if (!is_object($obj_seek)) {
    printf("FAILURE: expecting object (seek), got %s value, [%d] %s\n", gettype($obj_seek), mysql_errno($con), mysql_error($con));
}

if ($obj_seek->name != 'msg') {
    printf("FAILURE: expecting name to be 'msg', got value %s, [%d] %s\n", $obj_seek->name, mysql_errno($con), mysql_error($con));
}

$obj_direct = mysql_fetch_field($res, 1);
if (!is_object($obj_direct)) {
    printf("FAILURE: expecting object (direct), got %s value, [%d] %s\n", gettype($obj_direct), mysql_errno($con), mysql_error($con));
}

if ($obj_seek != $obj_direct) {
    printf("FAILURE: objects should be identical,  [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$ret = mysql_field_seek($res, 99);
if (!is_bool($ret)) {
    printf("FAILURE: expecting boolean value, got %s value, [%d] %s\n", gettype($ret), mysql_errno($con), mysql_error($con));
}

if ($ret) {
    printf("FAILURE: expecting false, [%d] %s\n", mysql_errno($con), mysql_error($con));
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
70, E_WARNING, mysqli_field_seek(): Function cannot be used with MYSQL_USE_RESULT
--EXPECT-CONVERTER-ERRORS--
24, 26, 26, 31, 56, 63,
--ENDOFTEST--