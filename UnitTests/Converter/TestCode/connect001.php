--TEST--
SUCCESS: Simple mysql_connect(), static host, dynamic user, dynamic password
--FILE--
<?php
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con = mysql_connect('127.0.0.1', $user, $pass);
if (!$con) {
    printf("[connect_1] Failure: [%d] %s\n", mysql_errno(), mysql_error());
} else {
    echo "[connect_1] Success\n";
}
mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
[connect_1] Success

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
[connect_1] Success

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
--EXPECT-CONVERTER-ERRORS--
7, 7,
--ENDOFTEST--