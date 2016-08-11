<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_GenericBooleanTest::main');
}

require_once 'MySQLConverterTool/Function/GenericBoolean.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/GenericBoolean.
 *
 * @category   Artificial UnitTests
 *
 * @author     Andrey Hristov <andrey@php.net>, Ulf Wendel <ulf.wendel@phpdoc.de>
 * @copyright  1997-2006 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 *
 * @version    CVS: $Id:$, Release: @package_version@
 *
 * @link       http://www.mysql.com
 * @since      Class available since Release 1.0
 */
class MySQLConverterTool_UnitTests_Function_GenericBooleanTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    protected $default_conn = null;
    protected $gen = null;

    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_Function_GenericBooleanTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_GenericBoolean('mysqli_fetch_length');
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {
        list($warning, $code) = $this->gen->handle($this->buildParams(array('A', 'B')));
        $this->assertNull($warning);
        $this->assertEquals('(($___mysqli_tmp = mysqli_fetch_length(A, B)) ? $___mysqli_tmp : false)', $code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_GenericBooleanTest::main') {
    MySQLConverterTool_Function_GenericBooleanTest::main();
}
