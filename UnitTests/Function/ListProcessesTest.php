<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_ListProcessesTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/ListProcesses.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/ListProcesses.
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
class MySQLConverterTool_UnitTests_Function_ListProcessesTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_ListProcessesTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_ListProcesses();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // resource mysql_list_processes ( [resource link_identifier] ) 
        // mixed mysqli_query ( mysqli link, string query [, int resultmode] )        

        // mysql_list_processes()
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNull($warning);
        $this->assertEquals(
            sprintf('%s(%s, "SHOW PROCESSLIST")', $this->gen->new_name, $this->default_conn),
            $code
        );

        // mysql_list_processes(<link_identifier>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<link_identifier>')));
        $this->assertNull($warning);
        $this->assertEquals(
            sprintf('%s(<link_identifier>, "SHOW PROCESSLIST")', $this->gen->new_name),
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<link_identifier>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_ListProcessesTest::main') {
    MySQLConverterTool_Function_ListProcessesTest::main();
}
