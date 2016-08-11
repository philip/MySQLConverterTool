--TEST--
SUCCESS: mysql_field_type()
--FILE--
<?php
/*
mysql_field_type

(PHP 3, PHP 4, PHP 5)
mysql_field_type -- Get the type of the specified field in a result
Description
string mysql_field_type ( resource result, int field_offset )

mysql_field_type() is similar to the mysql_field_name() function. The arguments are identical, but the field type is returned instead.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().
field_offset

    The numerical field offset. The field_offset starts at 0. If field_offset does not exist, an error of level E_WARNING is also issued.

Return Values

The returned field type will be one of "int", "real", "string", "blob", and others as detailed in the MySQL documentation.         
NOTE: The test skips "geometry" and some other rather exotic data types.
NOTE: which values are reported depends on the version of the client library. The test is successfull 
if ext/mysql and ext/mysqli return the same values even if they look a bit strange (see below!). You might
have to adapt the test depending on the version of your client library.
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

$typelist = array(
    'string' => array(
                        'VARCHAR(1)' => '"s"',
                        'VARBINARY(1)' => '"s"',
                        'CHAR(1)' => '"s"',
                        'BINARY(1)' => '"s"',
                    ),

    'int' => array(
                        'TINYINT' => 1,
                        'SMALLINT' => 1,
                        'INTEGER' => 1,
                        'BIGINT' => 1,
                        'MEDIUMINT' => 1,
                    ),

    'real' => array(
                        'FLOAT' => 1,
                        'DOUBLE' => 1,
                        'REAL' => 1,
                        'DECIMAL(1,0)' => 1,

                    ),
    'timestamp' => array('TIMESTAMP' => '"2006-07-31 17:18:43"'),
    'year' => array('YEAR' => '"2006"'),
    'date' => array('DATE' => '"2006-07-31"'),
    'time' => array('TIME' => '"17:21:12"'),
    'set' => array('SET("one", "two")' => '"one"'),
    'enum' => array('ENUM("true", "false")' => '"true"'),
    'datetime' => array('DATETIME' => '"2006-08-01 10:12:38"'),
    'blob' => array(
                        'BLOB' => '"s"',
                        'TINYBLOB' => '"s"',
                        'MEDIUMBLOB' => '"s"',
                        'LONGBLOB' => '"s"',
                        'TEXT' => '"s"',
                        'TINYTEXT' => '"s"',
                        'MEDIUMTEXT' => '"s"',
                        'LONGTEXT' => '"s"',
                    ),
    'unknown' => array('BIT(8)' => "b'1'"),
);

foreach ($typelist as $expected_type => $columns) {
    foreach ($columns as $sql_type => $sql_value) {
        @mysql_query('DROP TABLE field_types', $con);

        if (!mysql_query(sprintf('CREATE TABLE field_types(col1 %s)', $sql_type), $con)) {
            printf("FAILURE: skipping test for type %s/%s, cannot create table, [%d] %s\n",
                $expected_type, $sql_type,
                mysql_errno($con), mysql_error($con));
            continue;
        }

        if (!mysql_query(sprintf('INSERT INTO field_types(col1) values (%s)', $sql_value), $con)) {
            printf("FAILURE: skipping test for type %s/%s, cannot insert sql value '%s', [%d] %s\n",
                $expected_type, $sql_type, $sql_value,
                mysql_errno($con), mysql_error($con));
            continue;
        }

        if (!($res = mysql_query('SELECT col1 FROM field_types', $con))) {
            printf("FAILURE: skipping test for type %s/%s, cannot fetch any records, [%d] %s\n",
                $expected_type, $sql_type,
                mysql_errno($con), mysql_error($con));
            continue;
        }

        $type = mysql_field_type($res, 0);
        if (!is_string($type)) {
            printf("FAILURE: expecting string value, got %s value\n", gettype($type));
        }

        if ($type != $expected_type) {
            printf("FAILURE: expected type %s for SQL type %s, reported type %s\n", $expected_type, $sql_type, $type);
        }

        $type = mysql_field_type($res, 1);
        if (!is_bool($type)) {
            printf("FAILURE: expecting boolean value, got %s value\n", gettype($type));
        }

        if ($type) {
            printf("FAILURE: expecting false\n");
        }
    }
}

mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect
FAILURE: expected type real for SQL type DECIMAL(1,0), reported type unknown
FAILURE: expected type set for SQL type SET("one", "two"), reported type string
FAILURE: expected type enum for SQL type ENUM("true", "false"), reported type string

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect
FAILURE: expected type real for SQL type DECIMAL(1,0), reported type unknown
FAILURE: expected type set for SQL type SET("one", "two"), reported type string
FAILURE: expected type enum for SQL type ENUM("true", "false"), reported type string

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
120, E_WARNING, mysqli_fetch_field_direct(): Field offset is invalid for resultset
--EXPECT-CONVERTER-ERRORS--
31, 33, 33, 38,
--ENDOFTEST--