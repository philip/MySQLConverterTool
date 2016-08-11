--TEST--
SUCCESS: mysql_field_flags()
--FILE--
<?php
/*
mysql_field_flags

(PHP 3, PHP 4, PHP 5)
mysql_field_flags -- Get the flags associated with the specified field in a result
Description
string mysql_field_flags ( resource result, int field_offset )

mysql_field_flags() returns the field flags of the specified field. The flags are reported as a single word per flag separated by a single space, so that you can split the returned value using explode().
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().
field_offset

    The numerical field offset. The field_offset starts at 0. If field_offset does not exist, an error of level E_WARNING is also issued.

Return Values

Returns a string of flags associated with the result, or FALSE on failure.

The following flags are reported, if your version of MySQL is current enough to support them: "not_null", "primary_key", "unique_key", "multiple_key", "blob", "unsigned", "zerofill", "binary", "enum", "auto_increment" and "timestamp". 

// string mysql_field_flags ( resource result, int field_offset )        
        // "not_null"           NOT_NULL_FLAG       MYSQLI_NOT_NULL_FLAG
        // "primary_key"        PRI_KEY_FLAG        MYSQLI_PRI_KEY_FLAG
        // "unique_key"         UNIQUE_KEY_FLAG     MYSQLI_UNIQUE_KEY_FLAG
        // "multiple_key"       MULTIPLE_KEY_FLAG   MYSQLI_MULTIPLE_KEY_FLAG
        // "blob"               BLOB_FLAG           MYSQLI_BLOB_FLAG
        // "unsigned"           UNSIGNED_FLAG       MYSQLI_UNSIGNED_FLAG
        // "zerofill"           ZEROFILL_FLAG       MYSQLI_ZEROFILL_FLAG
        // "binary"             BINARY_FLAG         !!!
        // "enum"               ENUM_FLAG           !!!
        // "auto_increment"     AUTO_INCREMENT_FLAG MYSQLI_AUTO_INCREMENT_FLAG
        // "timestamp"          TIMESTAMP_FLAG      MYSQLI_TIMESTAMP_FLAG
        // "set"                SET_FLAG            MYSQLI_SET_FLAG

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

$columns = array(
    'COL_NOT_NULL' => array('INT NOT NULL', '1', 'not_null'),
    'COL_PRIMARY_KEY' => array('INT PRIMARY KEY NOT NULL', '1', 'primary_key not_null'),
    'COL_UNIQUE' => array('INT UNIQUE', '1', 'unique_key'),
    'COL_BLOB' => array('BLOB', '1', 'blob binary'),
    'COL_UNSIGNED' => array('INT UNSIGNED', '1', 'unsigned'),
    'COL_ZEROFILL' => array('INT ZEROFILL', '1', 'unsigned zerofill'),
    'COL_ENUM' => array('ENUM("true", "false")', '"false"', 'enum'),
    'COL_AUTO_INC' => array('INT AUTO_INCREMENT PRIMARY KEY NOT NULL', '1', 'primary_key not_null auto_increment'),
    'COL_TIMESTAMP' => array('TIMESTAMP', '20060728235021', 'timestamp binary not_null unsigned zerofill'),
    'COL_SET' => array('SET("true", "false")', '"false"', 'set'),

);

foreach ($columns as $name => $column) {
    @mysql_query('DROP TABLE field_flags', $con);

    $sql = sprintf('CREATE TABLE field_flags(%s %s)', $name, $column[0]);
    mysql_query($sql, $con);

    $sql = sprintf('INSERT INTO field_flags(%s) values (%s)', $name, $column[1]);
    if (!mysql_query($sql, $con)) {
        printf("FAILURE: insert for column '%s' failed, [%d] %s\n",
            $column[0], mysql_errno($con), mysql_error($con));
        continue;
    }

    if (!($res = mysql_query('SELECT * FROM field_flags', $con))) {
        printf("FAILURE: cannot select value of column %s failed, [%d] %s\n",
            $column[0], mysql_errno($con), mysql_error($con));
        continue;
    }

    $fields = mysql_field_flags($res, 0);
    mysql_free_result($res);

    if (!is_string($fields)) {
        printf("FAILURE: cannot fetch field flags of column '%s', got %s value instead of a string, [%d] %s\n",
            $column[0], gettype($fields), mysql_errno($con), mysql_error($con));
        continue;
    }

    $fields = explode(' ', $fields);
    $fields = array_flip($fields);
    ksort($fields);
    $expected = explode(' ', $column[2]);
    $expected = array_flip($expected);
    ksort($expected);
    $diff = array_diff($fields, $expected);
    if (!empty($diff)) {
        printf("FAILURE: the following fields are not expected: %s\n", implode(' ', $diff));
    }
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
44, 46, 46, 51,
--ENDOFTEST--