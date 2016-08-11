--TEST--
SUCCESS: Simple mysql_connect - host, user, pass given
--FILE--
<?php
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

// Converter should recognize dynamic host specification which cannot be parsed for a port/socket
// Should give: Cannot analyze server parameter to extract host, socket and port!
$con = mysql_connect($host, $user, $pass);
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
7, 9, 9
--ENDOFTEST--