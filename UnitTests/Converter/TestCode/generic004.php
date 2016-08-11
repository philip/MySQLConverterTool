--TEST--
FAILURE: mysqli_fetch_object
--FILE--
<?php
/*
mysql_fetch_object

(PHP 3, PHP 4, PHP 5)
mysql_fetch_object -- Fetch a result row as an object
Description
object mysql_fetch_object ( resource result )

Returns an object with properties that correspond to the fetched row and moves the internal data pointer ahead.
Parameters

result

    The result resource that is being evaluated. This result comes from a call to mysql_query().

Return Values

Returns an object with properties that correspond to the fetched row, or FALSE if there are no more rows.

mysql_fetch_row() fetches one row of data from the result associated with the specified result identifier. The row is returned as an array. Each result column is stored in an array offset, starting at offset 0. 


NOTE: This function gets called too frequently, we did not take care of the different 
return types between ext/mysql (false) and ext/mysqli (NULL) in case of an error. It would
cost too much performance to convert the call to ((is_null($__f = func())) ? false : $__f).

NOTE: /* {{{ proto object mysql_fetch_object(resource result [, string class_name [, NULL|array ctor_params]]) 
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

if (!mysql_query('DELETE FROM nobody', $con)) {
    printf("FAILURE: cannot clear table nobody, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!mysql_query("INSERT INTO nobody(id, msg) VALUES (1, 'one'), (2, 'two'), (3, 'three'), (4, 'four')", $con)) {
    printf("FAILURE: insert records into table nobody, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

if (!($res = mysql_query('SELECT id, msg FROM nobody ORDER BY id ASC', $con))) {
    printf("FAILURE: cannot fetch records, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

$obj = mysql_fetch_object($res);
if (!is_object($obj)) {
    printf("FAILURE: expecting object, got %s value, [%d] %s\n", gettype($obj), mysql_errno($con), mysql_error($con));
}

if ($obj->msg != 'one') {
    printf("FAILURE: expecting property 'msg' to have the value 'one', got '%s', [%d] %s\n",
        $obj->msg,
        mysql_errno($con), mysql_error($con));
}

if (!class_exists('foo')) {
    class foo
    {
        public $classname;
        public function foo($classname)
        {
            $this->classname = $classname;
        }
    }
}

$obj = mysql_fetch_object($res, 'foo');
if (get_class($obj) != 'foo') {
    printf("FAILURE: expecting object of class 'foo', got object of class '%s', [%d] %s\n",
        get_class($obj),
        mysql_errno($con), mysql_error($con));
}

$obj = mysql_fetch_object($res, 'foo', array('bar'));
if ($obj->classname != 'bar') {
    printf("FAILURE: passing parameters to the custom class did not work,  [%d] %s\n", mysql_errno($con), mysql_error($con));
}

while ($obj = mysql_fetch_object($res))
    ;

if (!is_bool($obj)) {
    printf("FAILURE: expecting boolean value because of empty result set, got %s value  [%d] %s\n", gettype($obj), mysql_errno($con), mysql_error($con));
}

$obj = mysql_fetch_object($illegal_result_identifier);
if (!is_bool($obj)) {
    printf("FAILURE: expecting boolean value because of invalid result identifier, got %s value  [%d] %s\n", gettype($obj), mysql_errno($con), mysql_error($con));
}

if ($obj) {
    printf("FAILURE: expecting false, [%d] %s\n", mysql_errno($con), mysql_error($con));
}

mysql_free_result($res);
mysql_close($con);
?>
--EXPECT-EXT/MYSQL-OUTPUT--
SUCCESS: connect

--EXPECT-EXT/MYSQL-PHP-ERRORS--
--EXPECT-EXT/MYSQLI-OUTPUT--
SUCCESS: connect
FAILURE: expecting boolean value because of empty result set, got NULL value  [0] 
FAILURE: expecting boolean value because of invalid result identifier, got NULL value  [0] 

--EXPECT-EXT/MYSQLI-PHP-ERRORS--
66, E_WARNING, Missing argument 1 for foo::foo()
67, E_NOTICE, Undefined variable: classname
89, E_NOTICE, Undefined variable: illegal_result_identifier
89, E_WARNING, mysqli_fetch_object() expects parameter 1 to be mysqli_result, null given
--EXPECT-CONVERTER-ERRORS--
33, 35, 35, 40,
--ENDOFTEST--