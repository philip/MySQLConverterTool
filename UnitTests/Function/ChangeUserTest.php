<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_ChangeUserTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/ChangeUser.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/ChangeUser.
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
class MySQLConverterTool_UnitTests_Function_ChangeUserTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_ChangeUser');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_ChangeUser('mysqli_change_user');
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // mysql_change_user ( string user, string password [, string database [, resource link_identifier]] )
        // mysqli_change_user ( mysqli link, string user, string password, string database )

        // not enough parameter - PHP parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // not enough parameter - PHP parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // two parameter: user, password
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<user>', '<password>')));
        $this->assertNull($warning);
        $this->assertEquals(
            sprintf('( ( ($___mysqli_res = mysqli_query(%s, "SELECT DATABASE()")) && ($___mysqli_tmp = mysqli_fetch_row($___mysqli_res)) && (($___mysqli_tmp = array_shift($___mysqli_tmp)) !== false) && (mysqli_free_result($___mysqli_res)) ) ? mysqli_change_user(%s, <user>, <password>, $___mysqli_tmp) : false )',
                $this->gen->ston_name,
                $this->gen->ston_name),
                            $code);

        // three parameter: user, password, database
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<user>', '<password>', '<database>')));
        $this->assertNull($warning);
        $this->assertEquals('mysqli_change_user('.$this->default_conn.', <user>, <password>, <database>)',
                            $code);

        // four parameter: user, password, database, link  
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<user>', '<password>', '<database>', '<link_identifier>')));
        $this->assertNull($warning);
        $this->assertEquals('mysqli_change_user(<link_identifier>, <user>, <password>, <database>)', $code);

        // too many parameter - PHP parse error                                        
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<user>', '<password>', '<database>', '<link_identifier>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_ChangeUserTest::main') {
    MySQLConverterTool_Function_ChangeUserTest::main();
}
