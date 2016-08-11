--TEST--
SUCCESS: mysql_free_result
--FILE--
<?php
/*
mysql_free_result

(PHP 3, PHP 4, PHP 5)
mysql_free_result -- Free result memory
Description
bool mysql_free_result ( resource result )

mysql_free_result() will free all memory associated with the result identifier result.

mysql_free_result() only needs to be called if you are concerned about how much memory is being used for queries that return large result sets. All associated result memory is automatically freed at the end of the script's execution.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().

Return Values

Returns TRUE on success or FALSE on failure.

If a non-resource is used for the result, an error of level E_WARNING will be emitted. It's worth noting that mysql_query() only returns a resource for SELECT, SHOW, EXPLAIN, and DESCRIBE queries. 
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

$ret = mysql_free_result($res);
if (!is_bool($ret)) {
    printf("FAILURE: expecting boolean value, got %s value,  [%d] %s\n", gettype($ret), mysql_errno($con), mysql_error($con));
}

if (!$ret) {
    printf("FAILURE: expecting true, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$ret = mysql_free_result($illegal_result_identifier);
if (!is_bool($ret)) {
    printf("FAILURE: expecting boolean value, illegal result identifier, got %s value,  [%d] %s\n", gettype($ret), mysql_errno($con), mysql_error($con));
}

if ($ret) {
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
56, E_NOTICE, Undefined variable: illegal_result_identifier
56, E_WARNING, mysqli_free_result() expects parameter 1 to be mysqli_result, null given
56, E_NOTICE, Undefined variable: illegal_result_identifier
--EXPECT-CONVERTER-ERRORS--
28, 30, 30, 35,
--ENDOFTEST--