--TEST--
SUCCESS: mysql_num_fields
--FILE--
<?php
/*
mysql_num_fields

(PHP 3, PHP 4, PHP 5)
mysql_num_fields -- Get number of fields in result
Description
int mysql_num_fields ( resource result )

Retrieves the number of fields from a query.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().

Return Values

Returns the number of fields in the result set resource on success, or FALSE on failure. 
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

$num = mysql_num_fields($res);
if (!is_int($num)) {
    printf("FAILURE: expecting integer value, got %s value, [%d] %s\n", gettype($num), mysql_errno($con), mysql_error($con));
}

if ($num != 2) {
    printf("FAILURE: expecting 2 fields, got %d, [%d] %s\n", $num, mysql_errno($con), mysql_error($con));
}

$num = mysql_num_fields($illegal_result_identifier);
if (!is_bool($num)) {
    printf("FAILURE: expecting boolean value, got %s value, [%d] %s\n", gettype($num), mysql_errno($con), mysql_error($con));
}

if ($num) {
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
53, E_NOTICE, Undefined variable: illegal_result_identifier
53, E_WARNING, mysqli_num_fields() expects parameter 1 to be mysqli_result, null given
--EXPECT-CONVERTER-ERRORS--
24, 26, 26, 31,
--ENDOFTEST--