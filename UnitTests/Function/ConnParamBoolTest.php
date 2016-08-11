<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_ConnParamBoolTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/ConnParamBool.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/ConnParamBool.
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
class MySQLConverterTool_UnitTests_Function_ConnParamBoolTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_ConnParamBoolTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function testHandle()
    {

        // mysql_close ( [resource link_identifier] )
        // mysqli_close ( mysqli link )
        $this->doTestHandle('mysqli_close');

        // mysql_get_host_info ( [resource link_identifier] )
        // mysqli_get_host_info ( mysqli link )
        $this->doTestHandle('mysqli_get_host_info');

        // mysql_get_proto_info ( [resource link_identifier] )
        // mysqli_get_proto_info ( mysqli link )
        $this->doTestHandle('mysqli_get_proto_info');

        // mysql_get_server_info ( [resource link_identifier] )
        // mysqli_get_server_info ( mysqli link )
        $this->doTestHandle('mysqli_get_server_info');

        // mysql_insert_id ( [resource link_identifier] )
        // mysqli_insert_id ( mysqli link )
        $this->doTestHandle('mysqli_insert_id');
    }

    protected function setUp($function = '')
    {
        $this->gen = new MySQLConverterTool_Function_ConnParamBool($function);
        $this->default_conn = $this->gen->ston_name;
    }

    protected function doTestHandle($function)
    {
        $this->setUp($function);

        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNull($warning);
        $this->assertEquals(
            '((is_null($___mysqli_res = '.$this->gen->new_name.'('.$this->default_conn.'))) ? false : $___mysqli_res)',
            $code
        );

        list($warning, $code) = $this->gen->handle($this->buildParams(array('<link_identifier>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '((is_null($___mysqli_res = '.$this->gen->new_name.'(<link_identifier>))) ? false : $___mysqli_res)',
            $code
        );

        // too many paramters
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<link_identifier>', '<ERROR>')));
        $this->assertNull($code);
        $this->assertNotNull($warning);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_ConnParamBoolTest::main') {
    MySQLConverterTool_Function_ConnParamBoolTest::main();
}
