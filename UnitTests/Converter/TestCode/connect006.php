--TEST--
SUCCESS: mysql_connect() with Unix Socket - ext/mysql reports success for bogus socket
--FILE--
<?php
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect(':/path/to/socket/', $user, $pass);
var_dump(is_resource($con));
mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
bool(true)

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
bool(false)

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
--EXPECT-CONVERTER-ERRORS--
6,
--ENDOFTEST--