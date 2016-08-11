<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_CreateDBTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/CreateDB.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/CreateDB.
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
class MySQLConverterTool_UnitTests_Function_CreateDBTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_CreateDBTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_CreateDB();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // mysql_create_db ( string database_name [, resource link_identifier] )
        // emulation with mysqli_query and CREATE DATABASE

        // too few parameters
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // mysql_create_db(<database_name>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('"<database_name>"')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('((is_null($___mysqli_res = %s(%s, "CREATE DATABASE <database_name>"))) ? false : $___mysqli_res)',
                $this->gen->new_name,
                $this->default_conn),
            $code
        );

        // mysql_create_db(<database_name>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('$<database_name>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('((is_null($___mysqli_res = %s(%s, "CREATE DATABASE $<database_name>"))) ? false : $___mysqli_res)',
                $this->gen->new_name,
                $this->default_conn),
            $code
        );

        // mysql_create_db(<database_name>, <link_identifier>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database_name>', '<link_identifier>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('((is_null($___mysqli_res = %s(%s, "CREATE DATABASE " . constant(\'<database_name>\')))) ? false : $___mysqli_res)',
                $this->gen->new_name,
                '<link_identifier>'),
            $code
        );

        // too many parameters
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database_name>', '<link_identifier>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_CreateDBTest::main') {
    MySQLConverterTool_Function_CreateDBTest::main();
}
