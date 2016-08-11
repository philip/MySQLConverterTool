--TEST--
FAILURE: mysqli_fetch_row
--FILE--
<?php
/*
mysql_fetch_row

(PHP 3, PHP 4, PHP 5)
mysql_fetch_row -- Get a result row as an enumerated array
Description
array mysql_fetch_row ( resource result )

Returns a numerical array that corresponds to the fetched row and moves the internal data pointer ahead.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().

Return Values

Returns an numerical array that corresponds to the fetched row, or FALSE if there are no more rows.

mysql_fetch_row() fetches one row of data from the result associated with the specified result identifier. The row is returned as an array. Each result column is stored in an array offset, starting at offset 0. 

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

$row = mysql_fetch_row($res);
if (!is_array($row)) {
    printf("FAILURE: expecting array, got %s value  [%d] %s\n", gettype($row), mysql_errno($con), mysql_error($con));
}

if ($row[0] != 1) {
    printf("FAILURE: expecting row[0] = 1, but row[0] is %s  [%d] %s\n", $row[0], mysql_errno($con), mysql_error($con));
}

while ($row = mysql_fetch_row($res))
    ;

if (!is_bool($row)) {
    printf("FAILURE: expecting boolean value because of empty result set, got %s value  [%d] %s\n", gettype($row), mysql_errno($con), mysql_error($con));
}

$row = mysql_fetch_row($illegal_result_identifier);
if (!is_bool($row)) {
    printf("FAILURE: expecting boolean value because of invalid result identifier, got %s value  [%d] %s\n", gettype($row), mysql_errno($con), mysql_error($con));
}

if ($row) {
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
FAILURE: expecting boolean value because of empty result set, got NULL value  [0] 
FAILURE: expecting boolean value because of invalid result identifier, got NULL value  [0] 

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
64, E_NOTICE, Undefined variable: illegal_result_identifier
64, E_WARNING, mysqli_fetch_row() expects parameter 1 to be mysqli_result, null given
--EXPECT-CONVERTER-ERRORS--
30, 32, 32, 37,
--ENDOFTEST--