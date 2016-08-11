<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_ListFieldsTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/ListFields.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/ListFields.
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
class MySQLConverterTool_UnitTests_Function_ListFieldsTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_ListFieldsTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_ListFields();
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // mysql_list_fields ( string database_name, string table_name [, resource link_identifier] )
        // mixed mysqli_query ( mysqli link, string query [, int resultmode] )           

        // too few parameters - parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // too few parameters - parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database_name>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // mysql_list_fields(<database_name>, <table_name>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database_name>', '<table_name>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('(($___mysqli_tmp = %s(%s, "SHOW COLUMNS FROM " . constant(\'<database_name>\') . "." . constant(\'<table_name>\'))) ? $___mysqli_tmp : false)', $this->gen->new_name, $this->default_conn),
            $code
        );

        // mysql_list_fields(<database_name>, <table_name>, <link_identifier>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('"<database_name>"', '"$<table_name>"', '<link_identifier>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('(($___mysqli_tmp = %s(<link_identifier>, "SHOW COLUMNS FROM <database_name>.$<table_name>")) ? $___mysqli_tmp : false)', $this->gen->new_name, $this->default_conn),
            $code
        );

        // mysql_list_fields(<database_name>, <table_name>, <link_identifier>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database_name>', '"$<table_name>"', '<link_identifier>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('(($___mysqli_tmp = %s(<link_identifier>, "SHOW COLUMNS FROM " . constant(\'<database_name>\') . ".$<table_name>")) ? $___mysqli_tmp : false)', $this->gen->new_name, $this->default_conn),
            $code
        );

        // mysql_list_fields(<database_name>, <table_name>, <link_identifier>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('"<database_name>"', '<table_name>', '<link_identifier>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('(($___mysqli_tmp = %s(<link_identifier>, "SHOW COLUMNS FROM <database_name>." . constant(\'<table_name>\'))) ? $___mysqli_tmp : false)', $this->gen->new_name, $this->default_conn),
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<database_name>', '<table_name>', '<link_identifier>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_ListFieldsTest::main') {
    MySQLConverterTool_Function_ListFieldsTest::main();
}
