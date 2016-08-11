--TEST--
FAILURE: mysqli_fetch_assoc()
--FILE--
<?php
/*
mysql_fetch_assoc

(PHP 4 >= 4.0.3, PHP 5)
mysql_fetch_assoc -- Fetch a result row as an associative array
Description
array mysql_fetch_assoc ( resource result )

Returns an associative array that corresponds to the fetched row and moves the internal data pointer ahead. mysql_fetch_assoc() is equivalent to calling mysql_fetch_array() with MYSQL_ASSOC for the optional second parameter. It only returns an associative array.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().

Return Values

Returns an associative array that corresponds to the fetched row, or FALSE if there are no more rows.

If two or more columns of the result have the same field names, the last column will take precedence. To access the other column(s) of the same name, you either need to access the result with numeric indices by using mysql_fetch_row() or add alias names. See the example at the mysql_fetch_array() description about aliases. 

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

$ret = mysql_fetch_assoc($res);
if (!is_array($ret)) {
    printf("FAILURE: expecting array value, got %s value, [%d] %s\n",
        gettype($ret), mysql_errno($con), mysql_error($con));
}

if (!array_key_exists('id', $ret)) {
    printf("FAILURE: did not get a hash, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if ($ret['id'] != 1) {
    printf("FAILURE: values don't match, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

while ($ret = mysql_fetch_assoc($res))
    ;

if (!is_bool($ret)) {
    printf("FAILURE: expecting false because no more records can be fetched, got %s value, [%d] %s\n",
        gettype($ret),
        mysql_errno($con), mysql_error($con));
}

$ret = mysql_fetch_assoc($invalid_link_identifier);
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
FAILURE: expecting false because no more records can be fetched, got NULL value, [0] 
FAILURE: expecting false because of invalid link identifier, got NULL value, [0] 

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
73, E_NOTICE, Undefined variable: invalid_link_identifier
73, E_WARNING, mysqli_fetch_assoc() expects parameter 1 to be mysqli_result, null given
--EXPECT-CONVERTER-ERRORS--
30, 32, 32, 37,
--ENDOFTEST--