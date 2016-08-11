--TEST--
SUCCESS: mysql_escape_string()
--FILE--
<?php
/*
mysql_escape_string

(PHP 4 >= 4.0.3, PHP 5)
mysql_escape_string -- Escapes a string for use in a mysql_query
Description
string mysql_escape_string ( string unescaped_string )

This function will escape the unescaped_string, so that it is safe to place it in a mysql_query(). This function is deprecated.

This function is identical to mysql_real_escape_string() except that mysql_real_escape_string() takes a connection handler and escapes the string according to the current character set. mysql_escape_string() does not take a connection argument and does not respect the current charset setting.
Parameters

unescaped_string

    The string that is to be escaped. 

Return Values

Returns the escaped string. 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

// I hate ext/mysql - this one does not fail!
$escaped = mysql_escape_string("ext/mysql doesn't fail...");
if (!is_string($escaped)) {
    printf("FAILURE: 1 did not return string value but %s value\n", gettype($escaped));
}

var_dump($escaped);

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

$escaped = mysql_escape_string("ext/mysql and ext/mysqli won't fail...");
if (!is_string($escaped)) {
    printf("FAILURE: 2 did not return string value but %s value\n", gettype($escaped));
}

var_dump($escaped);

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
string(26) "ext/mysql doesn\'t fail..."
SUCCESS: connect
string(39) "ext/mysql and ext/mysqli won\'t fail..."

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
string(0) ""
SUCCESS: connect
string(39) "ext/mysql and ext/mysqli won\'t fail..."

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
27, E_USER_ERROR, [MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.
--EXPECT-CONVERTER-ERRORS--
27, 33, 35, 35, 40,
--ENDOFTEST--