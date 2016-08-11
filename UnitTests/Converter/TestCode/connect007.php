--TEST--
SUCCESS: mysql_connect() with Unix Socket
--FILE--
<?php
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect(':/path/to/socket/');
var_dump(is_resource($con));
var_dump(mysql_close($con));
?>
--EXPECT-EXT/MYSQL-OUTPUT--
bool(false)
bool(false)

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
bool(false)
bool(false)

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
5, E_WARNING, mysqli_connect()
7, E_WARNING, mysqli_close()
--EXPECT-CONVERTER-ERRORS--
6,
--ENDOFTEST--