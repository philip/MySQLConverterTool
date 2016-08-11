--TEST--
SUCCESS: mysql_errno()
--FILE--
<?php
/*
mysql_errno

(PHP 3, PHP 4, PHP 5)
mysql_errno -- Returns the numerical value of the error message from previous MySQL operation
Description
int mysql_errno ( [resource link_identifier] )

Returns the error number from the last MySQL function.

Errors coming back from the MySQL database backend no longer issue warnings. Instead, use mysql_errno() to retrieve the error code. Note that this function only returns the error code from the most recently executed MySQL function (not including mysql_error() and mysql_errno()), so if you want to use it, make sure you check the value before calling another MySQL function.
Parameters

link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns the error number from the last MySQL function, or 0 (zero) if no error occurred. 
*/
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "SUCCESS: connect\n";
}

$errno = mysql_errno($con);
if (!is_int($errno)) {
    printf("FAILURE: expecting integer value, using regular connection, got %s\n", gettype($errno));
}

// should throw a warning    
$errno = mysql_errno();
if (!is_int($errno)) {
    printf("FAILURE: expecting integer value, using default connection, got %s\n", gettype($errno));
}

$errno = mysql_error($illegal_link_identifier);
if (!is_bool($errno)) {
    printf("FAILURE: expecting boolean value, using illegal link identifier, got %s\n", gettype($errno));
}

if ($errno) {
    printf("FAILURE: expecting false, using illegal link identifier, got %s\n", $errno);
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
42, E_NOTICE, Undefined variable: illegal_link_identifier
--EXPECT-CONVERTER-ERRORS--
26, 28, 28, 38,
--ENDOFTEST--