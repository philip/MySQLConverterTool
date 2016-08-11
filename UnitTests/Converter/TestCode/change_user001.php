--TEST--
SUCCESS: mysql_change_user()
--FILE--
<?php
/*
int mysql_change_user ( string user, string password [, string database [, resource link_identifier]] )

mysql_change_user() changes the logged in user of the current active connection, or the connection given by the optional link_identifier parameter. If a database is specified, this will be the current database after the user has been changed. If the new user and password authorization fails, the current connected user stays active.

This function is deprecated and no longer exists in PHP.
Parameters

user

    The new MySQL username. 
password

    The new MySQL password. 
database

    The MySQL database. If not specified, the current selected database is used. 
link_identifier

    The MySQL connection. If the link identifier is not specified, the last link opened by mysql_connect() is assumed. If no such link is found, it will try to create one as if mysql_connect() was called with no arguments. If by chance no connection is found or established, an E_WARNING level warning is generated.

Return Values

Returns TRUE on success or FALSE on failure. */

require 'MySQLConverterTool/UnitTests/Converter/TestCode/config.php';

if (!function_exists('_conv_checkUserAndDB')) {
    function _conv_checkUserAndDB($con, $user, $db, $row)
    {
        $error = '';
        if ($row['db'] != $db) {
            $error .= sprintf("FAILURE: current database is '%s' should be '%s'\n", $row['db'], $db);
        }

        $user_found = $row['user'];
        if (($pos = strpos($user_found, '@')) !== false) {
            $user_found = substr($user_found, 0, $pos);
        }

        if ($user_found != $user) {
            $error .= sprintf("FAILURE: current user is '%s' should be '%s'\n", $user_found, $user);
        }

        return $error;
    }
}

$con = mysql_connect($host, $user, $pass);
if (!$con) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
}
if (!mysql_select_db($db, $con)) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
}

$res = mysql_query('SELECT DATABASE() AS db, CURRENT_USER AS user', $con);
if (!$res) {
    printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
}
if ($error = _conv_checkUserAndDB($con, $user, $db, mysql_fetch_assoc($res))) {
    print $error;
} else {
    print "SUCCESS: user and db are correct\n";
}

mysql_free_result($res);

if (function_exists('mysql_change_user')) {
    $ret = mysql_change_user($user_nobody, $pass_nobody, $db, $con);
    if ($ret) {
        echo "SUCCESS - mysql_change_user(user, pass, db, con)\n";
    } else {
        echo "FAILURE - mysql_change_user(user, pass, db, con)\n";
    }

    $res = mysql_query('SELECT DATABASE() AS db, CURRENT_USER AS user', $con);
    if (!$res) {
        printf("FAILURE: [%d] %s\n", mysql_errno(), mysql_error());
    }

    if ($error = _conv_checkUserAndDB($con, $user_nobody, $db, mysql_fetch_assoc($res))) {
        print $error;
    }
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: user and db are correct

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: user and db are correct
SUCCESS - mysql_change_user(user, pass, db, con)

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
--EXPECT-CONVERTER-ERRORS--
50, 52, 52, 54, 55, 55, 60, 60, 80, 80,
--ENDOFTEST--