--TEST--
SUCCESS: mysqli_fetch_field
--FILE--
<?php
/*
mysql_fetch_field

(PHP 3, PHP 4, PHP 5)
mysql_fetch_field -- Get column information from a result and return as an object
Description
object mysql_fetch_field ( resource result [, int field_offset] )

Returns an object containing field information. This function can be used to obtain information about fields in the provided query result.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().
field_offset

    The numerical field offset. If the field offset is not specified, the next field that was not yet retrieved by this function is retrieved. The field_offset starts at 0. 

Return Values

Returns an object containing field information. The properties of the object are:

    *

      name - column name
    *

      table - name of the table the column belongs to
    *

      def - default value of the column
    *

      max_length - maximum length of the column
    *

      not_null - 1 if the column cannot be NULL
    *

      primary_key - 1 if the column is a primary key
    *

      unique_key - 1 if the column is a unique key
    *

      multiple_key - 1 if the column is a non-unique key
    *

      numeric - 1 if the column is numeric
    *

      blob - 1 if the column is a BLOB
    *

      type - the type of the column
    *

      unsigned - 1 if the column is unsigned
    *

      zerofill - 1 if the column is zero-filled 
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

$obj = mysql_fetch_field($res);
if (!is_object($obj)) {
    printf("FAILURE: expecting object, got %s value, [%d] %s\n", gettype($obj), mysql_errno($con), mysql_error($con));
}

if ($obj->name != 'id') {
    printf("FAILURE: problems with name property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->table != 'nobody') {
    printf("FAILURE: problems with table property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->def != '') {
    printf("FAILURE: problems with def property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->not_null != 0) {
    printf("FAILURE: problems with not_null property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->max_length != 1) {
    printf("FAILURE: problems with max_length property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->primary_key != 0) {
    printf("FAILURE: problems with primary_key property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->multiple_key != 0) {
    printf("FAILURE: problems with multiple_key property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->numeric != 1) {
    printf("FAILURE: problems with numeric property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->blob != 0) {
    printf("FAILURE: problems with blob property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->type != 'int') {
    printf("FAILURE: problems with type property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->unsigned != 0) {
    printf("FAILURE: problems with unsigned property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

if ($obj->zerofill != 0) {
    printf("FAILURE: problems with zerofill property, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

$obj = mysql_fetch_field($res, 1);
if (!is_object($obj)) {
    printf("FAILURE: expecting object, got %s value, [%d] %s\n", gettype($obj), mysql_errno($con), mysql_error($con));
}

if ($obj->name != 'msg') {
    printf("FAILURE: problems with the offset, [%d] %s\n",
        mysql_errno($con), mysql_error($con));
}

$obj = mysql_fetch_field($res, 99);
if (!is_bool($obj)) {
    printf("FAILURE: expecting boolean because of invalid offset, got %s value, [%d] %s\n", gettype($obj), mysql_errno($con), mysql_error($con));
}

if ($obj) {
    printf("FAILURE: expecting false because of invalid offset, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$obj = mysql_fetch_field($illegal_link_identifier);
if (!is_bool($obj)) {
    printf("FAILURE: expecting boolean value because of invalid handle, got %s value, [%d] %s\n", gettype($obj), mysql_errno($con), mysql_error($con));
}

    if ($obj) {
        printf("FAILURE: expecting false because of invalid handle, [%d] %s\n", mysql_errno($con), mysql_error($con));
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
148, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
155, E_NOTICE, Undefined variable: illegal_link_identifier
155, E_NOTICE, Undefined variable: illegal_link_identifier
155, E_WARNING, mysqli_field_tell() expects parameter 1 to be mysqli_result, null given
155, E_WARNING, mysqli_fetch_field_direct() expects parameter 1 to be mysqli_result, null given
--EXPECT-CONVERTER-ERRORS--
67, 69, 69, 74, 88, 140, 148, 155,
--ENDOFTEST--