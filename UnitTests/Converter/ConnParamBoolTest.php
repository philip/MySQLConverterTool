<?php
// Call MySQLConverterTool_ConnParamBoolTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_ConnParamBoolTest::main');
}

require_once 'MySQLConverterTool/Converter.php';
require_once 'MySQLConverterTool/UnitTests/Converter/ConverterTest.php';

/**
 * UnitTests: real life tests, PHPUnit test of Function/ConnParamBool.
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
class MySQLConverterTool_UnitTests_Converter_ConnParamBoolTest extends MySQLConverterTool_UnitTests_Converter_ConverterTest
{
    public function testConvertFile()
    {
        $files = array('conn_param_bool001.php', 'conn_param_bool002.php', 'conn_param_bool003.php',
                        'conn_param_bool004.php', 'conn_param_bool005.php',
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

// Call MySQLConverterTool_ConnParamBoolTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_ConnParamBoolTest::main') {
    MySQLConverterTool_ConnParamBoolTest::main();
}
