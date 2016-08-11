<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_EscapeStringTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/EscapeString.php';

class MySQLConverterTool_UnitTests_Function_EscapeStringTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_EscapeStringTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_EscapeString('mysqli_real_escape_string');
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // mysql_escape_string ( string unescaped_string )        
        // return "$this->new_name($conn, $par1)";

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // mysql_escape_string(<unescaped_string>)
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<unescaped_string>')));
        $this->assertNotNull($warning);
        $this->assertEquals(
            sprintf('((isset(%s) && is_object(%s)) ? %s(%s, %s) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""))',
                    $this->default_conn,
                    $this->default_conn,
                    $this->gen->new_name,
                    $this->default_conn,
                    '<unescaped_string>'
                ),
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<unescaped_string>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_EscapeStringTest::main') {
    MySQLConverterTool_Function_EscapeStringTest::main();
}
