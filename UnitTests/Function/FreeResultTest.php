<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_FreeResultTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/FreeResult.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/FreeResult.
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
class MySQLConverterTool_UnitTests_Function_FreeResultTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_FreeResultTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_FreeResult();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // bool mysql_free_result ( resource result )
        // void mysqli_free_result ( mysqli_result result )

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // bool mysql_free_result ( resource result )
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '((mysqli_free_result(<result>) || (is_object(<result>) && (get_class(<result>) == "mysqli_result"))) ? true : false)',
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_FreeResultTest::main') {
    MySQLConverterTool_Function_FreeResultTest::main();
}
