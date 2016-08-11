--TEST--
SUCCESS: mysql_connect with flags
--FILE--
<?php
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

mysql_connect($host, $user, $pass, MYSQL_CLIENT_COMPRESS);
mysql_close();

mysql_connect($host, $user, $pass, MYSQL_CLIENT_IGNORE_SPACE);
mysql_close();

mysql_connect($host, $user, $pass, MYSQL_CLIENT_INTERACTIVE);
mysql_close();
?>
--EXPECT-EXT/MYSQL-OUTPUT--
--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
--EXPECT-EXT/MYSQLI-PHP-ERRORS--
--EXPECT-CONVERTER-ERRORS--
5, 8, 11
--ENDOFTEST--