<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_DBQueryTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/DBQuery.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/DBQuery.
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
class MySQLConverterTool_UnitTests_Function_DBQueryTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_DBQueryTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_DBQuery();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // mysql_db_query ( string database, string query [, resource link_identifier] )
        // emulated using mysqli_query and USE DATABASE        
        // ((mysqli_query($conn, " . $this->smart_concat('USE ', $new_db) . ") ? mysqli_query($conn, $query) : false)

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // mysql_db_query(<$database>, <query>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('$<database>', '<query>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('((%s(%s, "USE %s")) ? mysqli_query(%s, %s) : false)',
                $this->gen->new_name,
                $this->default_conn,
                '$<database>',
                $this->default_conn,
                '<query>'
                ),
            $code
        );

        // mysql_db_query(<$database>, <query>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database>', '<query>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('((%s(%s, "USE " . constant(\'%s\'))) ? mysqli_query(%s, %s) : false)',
                $this->gen->new_name,
                $this->default_conn,
                '<database>',
                $this->default_conn,
                '<query>'
                ),
            $code
        );

        // mysql_db_query("<database>", <query>, <link_identifier>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('"<database>"', '<query>', '<link_identifier>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('((%s(%s, "USE %s")) ? mysqli_query(%s, %s) : false)',
                $this->gen->new_name,
                '<link_identifier>',
                '<database>',
                '<link_identifier>',
                '<query>'
                ),
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database>', '<query>', '<link_identifier>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_DBQueryTest::main') {
    MySQLConverterTool_Function_DBQueryTest::main();
}
