<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_GenericTest::main');
}

require_once 'MySQLConverterTool/Function/Generic.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/Generic, acts as a base class for other tests.
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
class MySQLConverterTool_UnitTests_Function_GenericTest extends PHPUnit_Framework_TestCase
{
    protected $default_conn = null;
    protected $gen = null;

    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_Function_GenericTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_Generic('mysqli_query');
        $this->default_conn = $this->gen->ston_name;
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->gen = null;
        $this->default_conn = null;
    }

    /**
     * 
     */
    public function testSmart_concat()
    {
        $this->assertEquals('"A" . $B', $this->gen->smart_concat('A', '$B'));
        $this->assertEquals('"AB"', $this->gen->smart_concat('A', '"B"'));
        $this->assertEquals('"AB"', $this->gen->smart_concat('A', '"B"'));
        $this->assertEquals("'AB'", $this->gen->smart_concat('A', "'B'"));
    }

    public function testHandle()
    {
        list($warning, $code) = $this->gen->handle($this->buildParams(array('A', 'B')));
        $this->assertNull($warning);
        $this->assertEquals('mysqli_query(A, B)', $code);
    }

    public function testGetConversionHint()
    {
        $this->assertNotEquals('', $this->gen->getConversionHint());
    }

    public function testGetConversionPHPComment()
    {
        $this->assertNotEquals('', $this->gen->getConversionPHPComment());
    }

    protected function buildParams(array $params, $dynamic = false)
    {
        $ret = array();
        foreach ($params as $k => $param) {
            $ret[$k] = array('value' => $param, 'dynamic' => $dynamic);
        }

        return $ret;
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_GenericTest::main') {
    MySQLConverterTool_Function_GenericTest::main();
}
