<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_ConnParamTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/ConnParam.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/ConnParam.
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
class MySQLConverterTool_UnitTests_Function_ConnParamTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_ConnParamTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function testHandle()
    {

        // mysql_affected_rows ( [resource link_identifier] )
        // mysqli_affected_rows ( mysqli link )        
        $this->doTestHandle('mysqli_affected_rows');

        // mysql_client_encoding ( [resource link_identifier] )
        // mysqli_character_set_name ( mysqli link )
        $this->doTestHandle('mysqli_character_set_name');

        // mysql_stat ( [resource link_identifier] )
        // mysqli_stat ( mysqli link )
        $this->doTestHandle('mysqli_stat');

        // mysql_thread_id ( [resource link_identifier] )
        // mysqli_thread_id ( mysqli link )
        $this->doTestHandle('mysqli_thread_id');

        // mysql_ping ( [resource link_identifier] )
        // mysqli_ping ( mysqli link )
        $this->doTestHandle('mysqli_ping');

        // mysql_info ( [resource link_identifier] )
        // mysqli_info ( mysqli link )
        $this->doTestHandle('mysqli_info');
    }

    protected function setUp($function = '')
    {
        $this->gen = new MySQLConverterTool_Function_ConnParam($function);
        $this->default_conn = $this->gen->ston_name;
    }

    protected function doTestHandle($function)
    {
        $this->setUp($function);

        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNull($warning);
        $this->assertEquals(
            $this->gen->new_name.'('.$this->default_conn.')',
            $code
        );

        list($warning, $code) = $this->gen->handle($this->buildParams(array('<link_identifier>')));
        $this->assertNull($warning);
        $this->assertEquals(
            $this->gen->new_name.'(<link_identifier>)',
            $code
        );

        // too many paramters
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<link_identifier>', '<ERROR>')));
        $this->assertNull($code);
        $this->assertNotNull($warning);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_ConnParamTest::main') {
    MySQLConverterTool_Function_ConnParamTest::main();
}
