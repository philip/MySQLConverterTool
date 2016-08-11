<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_FieldNameTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/FieldName.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/FieldName.
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
class MySQLConverterTool_UnitTests_Function_FieldNameTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_FieldNameTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_FieldName();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // string mysql_field_name ( resource result, int field_offset )
        // mysqli_fetch_field_direct ( mysqli_result result, int fieldnr )

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // string mysql_field_name ( <result>, <field_offset> )
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>', '1')));
        $this->assertNull($warning);
        $this->assertEquals(
            '((($___mysqli_tmp = mysqli_fetch_field_direct(<result>, 1)->name) && (!is_null($___mysqli_tmp))) ? $___mysqli_tmp : false)',
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>', '<field_offset>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_FieldNameTest::main') {
    MySQLConverterTool_Function_FieldNameTest::main();
}
