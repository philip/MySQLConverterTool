--TEST--
SUCCESS: mysql_num_rows
--FILE--
<?php
/*
mysql_num_rows

(PHP 3, PHP 4, PHP 5)
mysql_num_rows -- Get number of rows in result
Description
int mysql_num_rows ( resource result )

Retrieves the number of rows from a result set. This command is only valid for SELECT statements. To retrieve the number of rows affected by a INSERT, UPDATE, or DELETE query, use mysql_affected_rows().
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().

Return Values

The number of rows in a result set on success, or FALSE on failure. 


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

$num = mysql_num_rows($res);
if (!is_int($num)) {
    printf("FAILURE: expecting integer value, got %s value, [%d] %s\n", gettype($num), mysql_errno($con), mysql_error($con));
}

if ($num != 4) {
    printf("FAILURE: expecting 4 fields, got %d, [%d] %s\n", $num, mysql_errno($con), mysql_error($con));
}

$num = mysql_num_rows($illegal_result_identifier);
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
FAILURE: expecting boolean value, got NULL value, [0] 

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
59, E_NOTICE, Undefined variable: illegal_result_identifier
59, E_WARNING, mysqli_num_rows() expects parameter 1 to be mysqli_result, null given
--EXPECT-CONVERTER-ERRORS--
30, 32, 32, 37,
--ENDOFTEST--