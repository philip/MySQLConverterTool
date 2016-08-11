--TEST--
SUCCESS: mysql_close() - behaves differently for illegal link identifier, extra cast
--FILE--
<?php
/*
mysql_close

(PHP 3, PHP 4, PHP 5)
mysql_close -- Close MySQL connection
Description
bool mysql_close ( [resource link_identifier] )

mysql_close() closes the non-persistent connection to the MySQL server that's associated with the specified link identifier. If link_identifier isn't specified, the last opened link is used.

Using mysql_close() isn't usually necessary, as non-persistent open links are automatically closed at the end of the script's execution. See also freeing resources.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

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

$ret = mysql_close($con);
if (!is_bool($ret)) {
    print "FAILURE: mysql_close(con) is supposed to return a boolean value\n";
}

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

$ret = mysql_close();
if (!is_bool($ret)) {
    printf("FAILURE: mysql_close() is supposed to return a boolean value, got %s\n", gettype($ret));
}

$ret = mysql_close($invalid_link_identifier);
if (!is_bool($ret)) {
    printf("FAILURE: mysql_close() is supposed to return a boolean value, got %s\n", gettype($ret));
}

if ($ret) {
    print "FAILURE: mysql_cose(invalid_link_identifier) should bail\n";
}

?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
48, E_NOTICE, Undefined variable: invalid_link_identifier
48, E_WARNING, mysqli_close() expects parameter 1 to be mysqli, null given
--EXPECT-CONVERTER-ERRORS--
26, 28, 28, 37, 39, 39,
--ENDOFTEST--