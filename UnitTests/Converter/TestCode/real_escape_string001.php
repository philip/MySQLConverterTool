--TEST--
SUCCESS: mysql_real_escape_string
--FILE--
<?php
/*
mysql_real_escape_string

(PHP 4 >= 4.3.0, PHP 5)
mysql_real_escape_string -- Escapes special characters in a string for use in a SQL statement
Description
string mysql_real_escape_string ( string unescaped_string [, resource link_identifier] )

Escapes special characters in the unescaped_string, taking into account the current character set of the connection so that it is safe to place it in a mysql_query(). If binary data is to be inserted, this function must be used.

mysql_real_escape_string() calls MySQL's library function mysql_real_escape_string, which prepends backslashes to the following characters: \x00, \n, \r, \, ', " and \x1a.

This function must always (with few exceptions) be used to make data safe before sending a query to MySQL.
Parameters

unescaped_string

    The string that is to be escaped. 
link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns the escaped string, or FALSE on error.

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

$escaped = mysql_real_escape_string("Let's escape this");
if ($escaped !== "Let\'s escape this") {
    printf("FAILURE: escape with default connection did not work, [%d] %s\n", mysql_errno(), mysql_error());
}

$escaped = mysql_real_escape_string("Let's escape this", $con);
if ($escaped !== "Let\'s escape this") {
    printf("FAILURE: escape did not work, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
--EXPECT-CONVERTER-ERRORS--
32, 34, 34, 39, 43, 45, 45,
--ENDOFTEST--