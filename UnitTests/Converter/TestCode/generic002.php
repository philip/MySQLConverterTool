--TEST--
FAILURE: mysqli_fetch_array()
--FILE--
<?php
/*
mysql_fetch_array

(PHP 3, PHP 4, PHP 5)
mysql_fetch_array -- Fetch a result row as an associative array, a numeric array, or both
Description
array mysql_fetch_array ( resource result [, int result_type] )

Returns an array that corresponds to the fetched row and moves the internal data pointer ahead.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().
result_type

    The type of array that is to be fetched. It's a constant and can take the following values: MYSQL_ASSOC, MYSQL_NUM, and the default value of MYSQL_BOTH. 

Return Values

Returns an array that corresponds to the fetched row, or FALSE if there are no more rows. The type of returned array depends on how result_type is defined. By using MYSQL_BOTH (default), you'll get an array with both associative and number indices. Using MYSQL_ASSOC, you only get associative indices (as mysql_fetch_assoc() works), using MYSQL_NUM, you only get number indices (as mysql_fetch_row() works).

If two or more columns of the result have the same field names, the last column will take precedence. To access the other column(s) of the same name, you must use the numeric index of the column or make an alias for the column. For aliased columns, you cannot access the contents with the original column name. 

NOTE: This function gets called too frequently, we did not take care of the different 
return types between ext/mysql (false) and ext/mysqli (NULL) in case of an error. It would
cost too much performance to convert the call to ((is_null($__f = func())) ? false : $__f).
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

$ret = mysql_fetch_array($res);
if (!is_array($ret)) {
    printf("FAILURE: expecting array value, got %s value, [%d] %s\n",
        gettype($ret), mysql_errno($con), mysql_error($con));
}

$ret = mysql_fetch_array($res, MYSQL_NUM);
if (!is_array($ret)) {
    printf("FAILURE: expecting array value, got %s value, [%d] %s\n",
        gettype($ret), mysql_errno($con), mysql_error($con));
}

if (array_key_exists('id', $ret)) {
    printf("FAILURE: got hash, asked for array by specifying MYSQL_NUM, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!array_key_exists(0, $ret)) {
    printf("FAILURE: did not get an array, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if ($ret[0] != 2) {
    printf("FAILURE: expecting 2, '%s' returned [%d] %s\n", $ret[0], mysql_errno($con), mysql_error($con));
}

$ret = mysql_fetch_array($res, MYSQL_ASSOC);
if (array_key_exists(0, $ret)) {
    printf("FAILURE: got array, asked for hash by specifying MYSQL_ASSOC, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!array_key_exists('id', $ret)) {
    printf("FAILURE: did not get a hash, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if ($ret['id'] != 3) {
    printf("FAILURE: expecting 3, '%s' returned [%d] %s\n", $ret[0], mysql_errno($con), mysql_error($con));
}

$ret = mysql_fetch_array($res, MYSQL_BOTH);
if (!array_key_exists('id', $ret)) {
    printf("FAILURE: asked for MYSQL_BOTH, but seems not to contain the MYSQL_ASSOC values, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!array_key_exists(0, $ret)) {
    printf("FAILURE: asked for MYSQL_BOTH, but seems not to contain the MYSQL_ARRAY values, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if ($ret['id'] != $ret[0]) {
    printf("FAILURE: asked for MYSQL_BOTH, but got something strange, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

while ($ret = mysql_fetch_array($res))
    ;

if (!is_bool($ret)) {
    printf("FAILURE: expecting false because of no more records, got %s value, [%d] %s\n",
        gettype($ret),
        mysql_errno($con), mysql_error($con));
}

$ret = mysql_fetch_array($invalid_link_identifier);
if (!is_bool($ret)) {
    printf("FAILURE: expecting false because of invalid link identifier, got %s value, [%d] %s\n",
        gettype($ret),
        mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);
mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect
FAILURE: expecting false because of no more records, got NULL value, [0] 
FAILURE: expecting false because of invalid link identifier, got NULL value, [0] 

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
105, E_NOTICE, Undefined variable: invalid_link_identifier
105, E_WARNING, mysqli_fetch_array() expects parameter 1 to be mysqli_result, null given
--EXPECT-CONVERTER-ERRORS--
33, 35, 35, 40,
--ENDOFTEST--