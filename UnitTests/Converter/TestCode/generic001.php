--TEST--
SUCCESS: mysql_data_seek()
--FILE--
<?php
/*
mysql_data_seek

(PHP 3, PHP 4, PHP 5)
mysql_data_seek -- Move internal result pointer
Description
bool mysql_data_seek ( resource result, int row_number )

mysql_data_seek() moves the internal row pointer of the MySQL result associated with the specified result identifier to point to the specified row number. The next call to a MySQL fetch function, such as mysql_fetch_assoc(), would return that row.

row_number starts at 0. The row_number should be a value in the range from 0 to mysql_num_rows() - 1. However if the result set is empty (mysql_num_rows() == 0), a seek to 0 will fail with a E_WARNING and mysql_data_seek() will return FALSE.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().
row_number

    The desired row number of the new result pointer. 

Return Values

Returns TRUE on success or FALSE on failure. 
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

if (!mysql_query("INSERT INTO nobody(id, msg) VALUES (1, 'one'), (2, 'two'), (3, 'three')", $con)) {
    printf("FAILURE: insert records into table nobody, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!($res = mysql_query('SELECT id, msg FROM nobody ORDER BY id ASC', $con))) {
    printf("FAILURE: cannot fetch records, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$jumps = array(2 => 'three', 0 => 'one', 1 => 'two');
foreach ($jumps as $offset => $sql_value) {
    $ret = mysql_data_seek($res, $offset);

    if (!is_bool($ret)) {
        printf("FAILURE: seek() did not return boolean value, got %s value\n", gettype($ret));
    }

    $row = mysql_fetch_assoc($res);
    if ($row['msg'] != $sql_value) {
        printf("FAILURE: expecting '%s', got '%s'\n", $sql_value, $row['msg']);
    }
}

$row = mysql_fetch_assoc($res);
if ($row['msg'] != 'three') {
    printf("FAILURE: expecting 'three', got '%s'\n", $row['msg']);
}

$ret = mysql_data_seek($res, 99);
if ($ret) {
    printf("FAILURE: expecting false\n");
}

mysql_free_result($res);

if (!($res = mysql_query('SELECT id, msg FROM nobody WHERE 1 = 2', $con))) {
    printf("FAILURE: cannot fetch records, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$ret = mysql_data_seek($res, 0);
if ($ret) {
    printf("FAILURE: expecting false and E_WARNING\n");
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
--EXPECT-CONVERTER-ERRORS--
29, 31, 31, 36,
--ENDOFTEST--