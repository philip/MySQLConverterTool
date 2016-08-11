<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_ConnectTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/Connect.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/Connect.
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
class MySQLConverterTool_UnitTests_Function_ConnectTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_ConnectTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_Connect('mysqli_connect');
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // mysql_connect ( [string server [, string username [, string password [, bool new_link [, int client_flags]]]]] )
        // mysqli_connect ( [string host [, string username [, string passwd [, string dbname [, int port [, string socket]]]]]] )     
        // resource mysql_pconnect ( [string server [, string username [, string password [, int client_flags]]]] )        

        // mysql_connect() / mysql_pconnect()
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertEquals(
            '('.$this->default_conn.' = '.$this->gen->new_name.'())',
            $code
            );

        // mysql_connect(<server>), mysql_pconnect(<server>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<server>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '('.$this->default_conn.' = '.$this->gen->new_name.'(<server>))',
            $code
            );

        // mysql_connect(<server>:<port>), mysql_pconnect(<server>:<port>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<server:3306>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '('.$this->default_conn.' = '.$this->gen->new_name.'(<server, NULL, NULL, NULL, 3306))',
            $code
            );

        // mysql_connect(<server:port>), mysql_pconnect(<server:port>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('"<server:3306>"')));
        $this->assertNull($warning);
        $this->assertEquals(
            '('.$this->default_conn.' = '.$this->gen->new_name.'("<server", NULL, NULL, NULL, 3306))',
            $code
            );

        list($warning, $code) = $this->gen->handle($this->buildParams(array('<server> . <port>'), true));
        $this->assertNotNull($warning);
        // no mistake - in this case we throw just a warning and try to convert the code
        $this->assertNotNull($code);

        // mysql_connect(<:/path/to/socket>), mysql_pconnect(<:/path/to/socket>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<:/path/to/socket>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '('.$this->default_conn.' = '.$this->gen->new_name.'("localhost", NULL, NULL, NULL, 0, \'/path/to/socket>\'))',
            $code
            );

        // mysql_connect ( [string server [, string username [, string password [, bool new_link [, int client_flags]]]]] )
        // mysql_connect(<server>, <username>), mysql_pconnect(<server>, <username>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<server>', '<username>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '('.$this->default_conn.' = '.$this->gen->new_name.'(<server>, <username>))',
            $code
            );

        // mysql_connect(<server>, <username>, <password>), mysql_pconnect(<server>, <username>, <password>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<server>', '<username>', '<password>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '('.$this->default_conn.' = '.$this->gen->new_name.'(<server>, <username>, <password>))',
            $code
            );

        // mysql_connect(<server>, <username>, <password>, <new_link>)
        // mysql_pconnect(<server>, <username>, <password>, <new_link>)        
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<server>', '<username>', '<password>', '<new_link>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '('.$this->default_conn.' = '.$this->gen->new_name.'(<server>, <username>, <password>))',
            $code
            );

        // mysql_connect(<server>, <username>, <password>, <new_link>, <client_flags>)            
        // mysql_pconnect(<server>, <username>, <password>, <new_link>, <client_flags>)        
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<server>', '<username>', '<password>',
            '<new_link>', '<client_flags>', )));
        $this->assertNull($warning);
        $this->assertEquals(
            sprintf('(((%s = mysqli_init()) && (mysqli_real_connect(%s, <server>, <username>, <password>, NULL, 3306, NULL, <client_flags>))) ? %s : FALSE)',
            $this->gen->ston_name,
            $this->gen->ston_name,
            $this->gen->ston_name),
            $code
            );

        // too many parameter - PHP parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<server>', '<username>', '<password>',
            '<new_link>', '<client_flags>', '<ERROR>', )));
        $this->assertNull($code);
        $this->assertNotNull($warning);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_ConnectTest::main') {
    MySQLConverterTool_Function_ConnectTest::main();
}
