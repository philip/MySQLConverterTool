<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_ErrorTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/Error.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/Error.
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
class MySQLConverterTool_UnitTests_Function_ErrorTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_ErrorTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function testHandle()
    {

        // mysql_error ( [resource link_identifier] )
        // mysqli_error ( mysqli link )
        $this->doTestHandle('mysqli_error');

        // mysql_errno ( [resource link_identifier] )
        // mysqli_errno ( mysqli link )
        $this->doTestHandle('mysqli_errno');
    }

    protected function setUp($function = '')
    {
        $this->gen = new MySQLConverterTool_Function_Error($function);
        $this->default_conn = $this->gen->ston_name;
    }

    protected function doTestHandle($function)
    {
        $this->setUp($function);

        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('((is_object(%s)) ? %s(%s) : (($___mysqli_res = mysqli_connect_%s()) ? $___mysqli_res : false))',
                $this->default_conn,
                $this->gen->new_name,
                $this->default_conn,
                ('mysqli_error' == $function) ? 'error' : 'errno'
            ),
            $code
        );

        list($warning, $code) = $this->gen->handle($this->buildParams(array('<link_identifier>')));
        $this->assertNull($warning);
        $this->assertEquals(
            sprintf('((is_object(%s)) ? %s(%s) : (($___mysqli_res = mysqli_connect_%s()) ? $___mysqli_res : false))',
                '<link_identifier>',
                $this->gen->new_name,
                '<link_identifier>',
                ('mysqli_error' == $function) ? 'error' : 'errno'
            ),
            $code
        );

        // too many paramters
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<link_identifier>', '<ERROR>')));
        $this->assertNull($code);
        $this->assertNotNull($warning);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_ErrorTest::main') {
    MySQLConverterTool_Function_ErrorTest::main();
}
