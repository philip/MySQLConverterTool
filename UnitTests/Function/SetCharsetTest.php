<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_SetCharsetTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/SetCharset.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/SetCharset.
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
class MySQLConverterTool_UnitTests_Function_SetCharsetTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_SetCharsetTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_SetCharset();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // bool mysql_set_charset ( string charset_name [, resource link_identifier] )
        // bool mysqli_set_charset ( mysqli link, string charset_name )

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // mysqli_set_charset(<charset_name>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('$<charset_name>')));
        $this->assertNull($warning);
        $this->assertEquals(
            sprintf('((bool)%s(%s, $<charset_name>))', $this->gen->new_name, $this->default_conn),
            $code
        );

        // mysqli_set_charset(<$database_name>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<charset_name>')));
        $this->assertNull($warning);
        $this->assertEquals(
            sprintf('((bool)%s(%s, constant(\'<charset_name>\')))', $this->gen->new_name, $this->default_conn),
            $code
        );

        // mysqli_set_charset("<charset_name>", <link_identifier>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('"<charset_name>"', '<link_identifier>')));
        $this->assertNull($warning);
        $this->assertEquals(
            sprintf('((bool)%s(<link_identifier>, <charset_name>))', $this->gen->new_name),
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<charset_name>', '<link_identifier>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_UnitTests_Function_SetCharsetTest::main') {
    MySQLConverterTool_UnitTests_Function_SetCharsetTest::main();
}
