--TEST--
FAILURE: Simple mysql_connect - host, user, pass given, new connection requested, relying on same connection for same params
--FILE--
<?php
require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

$con1 = mysql_pconnect($host, $user, $pass);
$con2 = mysql_connect($host, $user, $pass, true);
if ($con1 === $con2) {
    print "FAILURE: new connection requested but got existing one!\n";
} else {
    print "SUCCESS: new connection created\n";
}

// should close $con2
mysql_close($con2);

// should throw a warning - possible request to get the same connection as $con1
$con3 = mysql_pconnect($host, $user, $pass);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: new connection created

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: new connection created

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
--EXPECT-CONVERTER-ERRORS--
5, 6, 16
--ENDOFTEST--