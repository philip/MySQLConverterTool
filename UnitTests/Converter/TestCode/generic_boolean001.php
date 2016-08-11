--TEST--
SUCCESS: mysqli_fetch_length
--FILE--
<?php
/*
mysql_fetch_lengths

(PHP 3, PHP 4, PHP 5)
mysql_fetch_lengths -- Get the length of each output in a result
Description
array mysql_fetch_lengths ( resource result )

Returns an array that corresponds to the lengths of each field in the last row fetched by MySQL.

mysql_fetch_lengths() stores the lengths of each result column in the last row returned by mysql_fetch_row(), mysql_fetch_assoc(), mysql_fetch_array(), and mysql_fetch_object() in an array, starting at offset 0.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().

Return Values

An array of lengths on success, or FALSE on failure. 
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

if (!$row = mysql_fetch_assoc($res)) {
    printf("FAILURE: cannot fetch first row, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$lengths = mysql_fetch_lengths($res);
if (!is_array($lengths)) {
    printf("FAILURE: expecting array, got %s value, [%d] %s\n", gettype($lengths), mysql_errno($con), mysql_error($con));
}

if ($lengths[0] != 1) {
    printf("FAILURE: expecting length 1 for field 'id', got length %d [%d] %s\n", $lengths[0], mysql_errno($con), mysql_error($con));
}

$lengths = mysql_fetch_lengths($illegal_result_identifier);
if (!is_bool($lengths)) {
    printf("FAILURE: expecting boolean, got %s value, [%d] %s\n", gettype($lengths), mysql_errno($con), mysql_error($con));
}

if ($lengths) {
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
57, E_NOTICE, Undefined variable: illegal_result_identifier
57, E_WARNING, mysqli_fetch_lengths() expects parameter 1 to be mysqli_result, null given
--EXPECT-CONVERTER-ERRORS--
26, 28, 28, 33,
--ENDOFTEST--