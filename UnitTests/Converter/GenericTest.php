<?php
// Call MySQLConverterTool_GenericTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_GenericTest::main');
}

require_once 'MySQLConverterTool/Converter.php';
require_once 'MySQLConverterTool/UnitTests/Converter/ConverterTest.php';

/**
 * UnitTests: real life tests, PHPUnit test of Function/Generic.
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
class MySQLConverterTool_UnitTests_Converter_GenericTest extends MySQLConverterTool_UnitTests_Converter_ConverterTest
{
    public function testConvertFile()
    {
        $files = array('generic001.php', 'generic002.php', 'generic003.php',
                        'generic004.php', 'generic005.php', 'generic006.php',
                        'generic007.php', 'generic008.php',
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

// Call MySQLConverterTool_GenericTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_GenericTest::main') {
    MySQLConverterTool_GenericTest::main();
}
