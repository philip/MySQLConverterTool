<?php
// Call MySQLConverterTool_ConnectTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_ConnectTest::main');
}

require_once 'MySQLConverterTool/Converter.php';
require_once 'MySQLConverterTool/UnitTests/Converter/ConverterTest.php';

/**
 * UnitTests: real life tests, PHPUnit test of Function/Connect.
 *
 * @category   Real-life UnitTests
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
class MySQLConverterTool_UnitTests_Converter_ConnectTest extends MySQLConverterTool_UnitTests_Converter_ConverterTest
{
    public function testConvertFile()
    {
        $files = array('connect001.php', 'connect002.php', 'connect003.php',
                        'connect004.php', 'connect005.php', 'connect006.php',
                        'connect007.php',
                        );

        foreach ($files as $k => $file) {
            $file = dirname(__FILE__).'/TestCode/'.$file;
            $test_spec = $this->parseTestFile($file);
            if ($err = $this->validateTestSpec($test_spec)) {
                $this->fail(sprintf("[%s]\n%s\n", $file, $err));

                return;
            }
            if ($err = $this->runTestSpec($test_spec)) {
                $this->fail(sprintf("[%s]\n%s\n", $file, $err));

                return;
            };
        }
    }
}

// Call MySQLConverterTool_ConnectTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_ConnectTest::main') {
    MySQLConverterTool_ConnectTest::main();
}
