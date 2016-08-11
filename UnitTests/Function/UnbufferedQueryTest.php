<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_UnbufferedQueryTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/UnbufferedQuery.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/UnbufferedQuery.
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
class MySQLConverterTool_UnitTests_Function_UnbufferedQueryTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_UnbufferedQueryTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_UnbufferedQuery();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // mysql_unbuffered_query ( string query [, resource link_identifier] )

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // mysql_unbuffered_query(<query>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<query>')));
        $this->assertEquals(
            sprintf('%s(%s, <query>, MYSQLI_USE_RESULT)', $this->gen->new_name, $this->default_conn),
            $code
        );

        // mysql_unbuffered_query(<query>, <link_identifier>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<query>', '<link_identifier>')));
        $this->assertEquals(
            sprintf('%s(<link_identifier>, <query>, MYSQLI_USE_RESULT)', $this->gen->new_name),
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<query>', '<link_identifier>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_UnbufferedQueryTest::main') {
    MySQLConverterTool_Function_UnbufferedQueryTest::main();
}
