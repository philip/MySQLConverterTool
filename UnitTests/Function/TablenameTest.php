<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_TablenameTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/Tablename.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/Tablename.
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
class MySQLConverterTool_UnitTests_Function_TablenameTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_TablenameTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_Tablename();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // string mysql_tablename ( resource result, int i )          

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // mysql_select_db(<$database_name>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>', '<i>')));
        $this->assertEquals(
            '((mysqli_data_seek(<result>, <i>) && (($___mysqli_tmp = mysqli_fetch_row(<result>)) !== NULL)) ? array_shift($___mysqli_tmp) : false)',
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>', '<i>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_TablenameTest::main') {
    MySQLConverterTool_Function_TablenameTest::main();
}
