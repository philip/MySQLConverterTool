--TEST--
SUCCESS: mysql_list_dbs()
--FILE--
<?php
/*
mysql_list_dbs

(PHP 3, PHP 4, PHP 5)
mysql_list_dbs -- List databases available on a MySQL server
Description
resource mysql_list_dbs ( [resource link_identifier] )

Returns a result pointer containing the databases available from the current mysql daemon.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns a result pointer resource on success, or FALSE on failure. Use the mysql_tablename() function to traverse this result pointer, or any function for result tables, such as mysql_fetch_array(). 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

if (!($res = mysql_list_dbs($con))) {
    printf("FAILURE: mysql_list_dbs(con) failed, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);

if (!($res = mysql_list_dbs())) {
    printf("FAILURE: mysql_list_dbs() failed, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$found = false;
while ($row = mysql_fetch_assoc($res)) {
    if (!array_key_exists('Database', $row)) {
        printf("FAILURE: hash does not have a 'Database' field, [%d] %s\n", mysql_errno($con), mysql_error($con));
    }

    if ($row['Database'] == $db) {
        $found = true;
        break;
    }
}
if (!$found) {
    printf("FAILURE: Database '%s' was not found\n", $db);
}

$res = mysql_list_dbs($illegal_link_identifier);
if (!is_bool($res)) {
    printf("FAILURE: expecting boolean value, got %s value, [%d] %s\n", gettype($res), mysql_errno($con), mysql_error($con));
}

if ($res) {
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
53, E_NOTICE, Undefined variable: illegal_link_identifier
53, E_WARNING, mysqli_query() expects parameter 1 to be mysqli, null given
60, E_WARNING, mysqli_free_result() expects parameter 1 to be mysqli_result, boolean given
--EXPECT-CONVERTER-ERRORS--
24, 26, 26,
--ENDOFTEST--