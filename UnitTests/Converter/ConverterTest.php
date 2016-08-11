<?php
// Call MySQLConverterTool_ConverterTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_ConverterTest::main');
}

require_once 'MySQLConverterTool/Converter.php';

/**
 * UnitTests: real life tests, base class for all function tests.
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
abstract class MySQLConverterTool_UnitTests_Converter_ConverterTest extends PHPUnit_Framework_TestCase
{
    /*
    * Converter instance
    * 
    * @var  object MySQLConverterTool_Converter
    */
    public $conv = null;

    /**
     * Testfile parser state: normal.
     * 
     * @const 
     */
    const STATE_NORMAL = 'normal';

    /**
     * Testfile parser state.
     * 
     * @const 
     */
    const STATE_TEST = 'name of the test';

    /**
     * Testfile parser state.
     * 
     * @const 
     */
    const STATE_FILE = 'php code';

    /**
     * Testfile parser state.
     * 
     * @const 
     */
    const STATE_MYSQL_OUTPUT = 'ext/mysql output';

    /**
     * Testfile parser state.
     * 
     * @const 
     */
    const STATE_MYSQL_ERRORS = 'ext/mysql PHP errors';

    /**
     * Testfile parser state.
     * 
     * @const 
     */
    const STATE_MYSQLI_OUTPUT = 'ext/mysqli (converted) output';

    /**
     * Testfile parser state.
     * 
     * @const 
     */
    const STATE_MYSQLI_ERRORS = 'ext/mysqli (converted) PHP errors';

    /**
     * Testfile parser state.
     * 
     * @const 
     */
    const STATE_CONVERTER_ERRORS = 'errors thrown during conversion';

    /**
     * Runs the test methods of this class.
     *
     * @static
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_ConverterTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->conv = new MySQLConverterTool_Converter(false, false);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * TODO.
     */
    public function testConvertString()
    {
        return;
    }

    /**
     * 
     */
    abstract public function testConvertFile();

    /**
     * 
     */
    public function testGetSupportedFunctions()
    {
        $func = $this->conv->getSupportedFunctions();
        $this->assertTrue((is_array($func)) && (count($func) > 0));
    }

    /**
     * 
     */
    public function testGetUnsupportedFunctions()
    {
        $func = $this->conv->getUnsupportedFunctions();
        $this->assertTrue(is_array($func));
    }

    //
    // protected
    //

    /**
     * Runs the test file specified by the test spec hash.
     *
     * @param    array
     *
     * @return mixed Error message or NULL on success
     */
    protected function runTestSpec($test_spec)
    {

        // run the ext/mysql code to check that we have valid code which can be run (and converted at all)
        $eval = $this->evalCode($test_spec['php']);
        if ($eval['output'] != $test_spec['mysql_output']) {
            // let's ignore the errors for now
            $error = '['.$test_spec['testname']."]\n";
            $error .= "The execution of the ext/mysql test did not return the expected output.\n";
            $error .= "Please check that the test is OK. We got:\n\n<";
            $error .= $eval['output'];
            $error .= ">\n";
            $error .= "Expected:\n\n<";
            $error .= $test_spec['mysql_output'].">\n";

            return $error;
        }

        // convert the code        
        $conv = $this->conv->convertString("<?php\n ".$test_spec['php']."\n?>");
        if ('' == $conv['output']) {
            // Uuups, Houston we have a problem
            $error = '['.$test_spec['testname']."]\n";
            $error .= "The conversion of the test failed. No PHP code was returned.\n";
            $error .= "The following errors occured:\n\n";
            foreach ($conv['errors'] as $k => $msg) {
                $error .= sprintf("- [Line %d] %s\n", $msg['line'], $msg['msg']);
            }

            return $error;
        }

        // var_dump($conv['output']);

        // run the converted code
        $this->conv->unsetGlobalConnection();
        $code = substr($conv['output'], 5, -2);
        $eval = $this->evalCode($code);

        if ($eval['output'] != $test_spec['mysqli_output']) {
            $error = '['.$test_spec['testname']."]\n";
            $error .= "The execution of the converted code did not return the expected output.\n";
            $error .= "Please check that the test is OK. We got:\n\n<";
            $error .= $eval['output'];
            $error .= ">\n";
            $error .= "Expected:\n\n<";
            $error .= $test_spec['mysqli_output'].">\n";

            if (!empty($eval['errors'])) {
                $error .= "\n";
                $error .= "The following PHP errors occured during the execution:\n\n";
                foreach ($eval['errors'] as $k => $msg) {
                    $error .= sprintf("%d, %s, %s\n", $msg['line'], $msg['errno'], $msg['msg']);
                }
            }

            return $error;
        }

        if (!empty($eval['errors'])) {
            if (empty($test_spec['mysqli_errors'])) {
                $error = '['.$test_spec['testname']."]\n";
                $error .= "Errors occured during the execution of the converted code.\n";
                $error .= "According to the test no errors can be expected.\n";
                $error .= "Please check the test and the converted code.\n";
                $error .= "We got the following errors:\n\n";
                foreach ($eval['errors'] as $k => $msg) {
                    $error .= sprintf("%d, %s, %s\n", $msg['line'], $msg['errno'], $msg['msg']);
                }
                $error .= "\n\n";
                $error .= "The converter has generated the following PHP code which caused the errors:\n\n<";
                $error .= $code.">\n";

                return $error;
            }

            $unexpected = array();
            foreach ($eval['errors'] as $k => $got_msg) {
                $found = false;
                foreach ($test_spec['mysqli_errors'] as $i => $spec_msg) {
                    if ($spec_msg['line']   == $got_msg['line'] &&
                        $spec_msg['errno'] == $got_msg['errno'] &&
                        substr(trim($got_msg['msg']), 0, strlen(trim($spec_msg['msg']))) == trim($spec_msg['msg'])) {
                        // specified error is equal to error which occured
                        unset($test_spec['mysqli_errors'][$i]);
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $unexpected[$k] = $k;
                }
            }

            if (!empty($unexpected)) {

                // not all errors which occured have been specified
                $error = '['.$test_spec['testname']."]\n";
                $error .= "Errors occured during the execution of the converted code.\n";
                $error .= "The following errors occured but are not listed among the expected errors:\n\n";
                foreach ($unexpected as $k) {
                    $error .= sprintf("%d, %s, %s\n", $eval['errors'][$k]['line'], $eval['errors'][$k]['errno'], $eval['errors'][$k]['msg']);
                }
                $error .= "\nComplete list of errors as it should be contained in the test:\n\n";
                foreach ($eval['errors'] as $k => $msg) {
                    $error .= sprintf("%d, %s, %s\n", $msg['line'], $msg['errno'], $msg['msg']);
                }
                $error .= "\n";
                if (!empty($test_spec['mysqli_errors'])) {
                    $error .= "\nThe following errors have been specified but did not occur:\n\n";
                    foreach ($test_spec['mysqli_errors'] as $k => $msg) {
                        $error .= sprintf("%d, %s, %s\n", $msg['line'], $msg['errno'], $msg['msg']);
                    }
                }

                return $error;
            }
        }

        if (!empty($test_spec['mysqli_errors'])) {
            $error = '['.$test_spec['testname']."]\n";
            $error .= "You have specified more errors than occured during the conversion.\n";
            $error .= 'Number of specified/expected errors: '.(string) (count($test_spec['mysqli_errors']) + count($eval['errors']))."\n";
            $error .= 'Number of errors that occured: '.count($eval['errors'])."\n";
            $error .= "The following errors have been specified but did not occur:\n\n";
            foreach ($test_spec['mysqli_errors'] as $k => $msg) {
                $error .= sprintf("%d, %s, %s\n", $msg['line'], $msg['errno'], $msg['msg']);
            }
            $error .= "\nComplete list of errors as it should be contained in the test:\n\n";
            foreach ($eval['errors'] as $k => $msg) {
                $error .= sprintf("%d, %s, %s\n", $msg['line'], $msg['errno'], $msg['msg']);
            }

            return $error;
        }

        if (!empty($conv['errors'])) {
            if (empty($test_spec['converter_errors'])) {
                $error = '['.$test_spec['testname']."]\n";
                $error .= "The converter has thrown errors/warnings but no such have been expected.\n";
                $error .= "The following errors/warnings occured:\n\n";
                foreach ($conv['errors'] as $k => $msg) {
                    $error .= sprintf("%d, %s\n", $msg['line'], $msg['msg']);
                }
                $error .= "\nYou should check the test and the converter. If you think the test is wrong\n";
                $error .= "add the following list of line numbers to the test:\n\n";
                foreach ($conv['errors'] as $k => $msg) {
                    $error .= sprintf('%d, ', $msg['line']);
                }
                $error .= "\n";

                return $error;
            }

            $unexpected = array();

            foreach ($conv['errors'] as $k => $got_msg) {
                $found = false;
                foreach ($test_spec['converter_errors'] as $i => $v) {
                    if ($v == $got_msg['line']) {
                        unset($test_spec['converter_errors'][$i]);
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $unexpected[$k] = $k;
                }
            }

            if (!empty($unexpected)) {
                $error = '['.$test_spec['testname']."]\n";
                $error .= "The converter has thrown more errors/warnings than expected.\n";
                $error .= "The following errors/warnings have not been expected:\n\n";
                foreach ($unexpected as $k) {
                    $error .= sprintf("%d, %s\n", $conv['errors'][$k]['line'], $conv['errors'][$k]['msg']);
                }
                if (!empty($test_spec['converter_errors'])) {
                    $error .= "\nThe following specified errors/warnings did not occur:\n\n";
                    foreach ($test_spec['converter_errors'] as $line) {
                        $error .= sprintf("%d, [message cannot be specified in test]\n", $line);
                    }
                }
                $error .= "\nYou should check the test and the converter. If you think the test is wrong\n";
                $error .= "add the following list of line numbers to the test:\n\n";
                foreach ($conv['errors'] as $k => $msg) {
                    $error .= sprintf('%d, ', $msg['line']);
                }
                $error .= "\n";

                return $error;
            }
        }

        if (!empty($test_spec['converter_errors'])) {
            $error = '['.$test_spec['testname']."]\n";
            $error .= "The converter has thrown lesses errors/warnings than expected.\n";
            $error .= "The following specified errors/warnings did not occur:\n\n";
            foreach ($test_spec['converter_errors'] as $line) {
                $error .= sprintf("%d, [message cannot be specified in test]\n", $line);
            }
            $error .= "\nYou should check the test and the converter. If you think the test is wrong\n";
            $error .= "add the following list of line numbers to the test:\n\n";
            foreach ($conv['errors'] as $k => $msg) {
                $error .= sprintf('%d, ', $msg['line']);
            }
            $error .= "\n";

            return $error;
        }

        return;
    }

    /**
     * Validates a given test specification.
     *
     * @param    array
     *
     * @return mixed Error message if the test spec seems wrong, NULL on success
     */
    protected function validateTestSpec($test_spec)
    {
        if (is_null($test_spec)) {
            return 'Test could not be found and/or parsed!';
        }

        if ('' == $test_spec['testname']) {
            return 'No name specified for test.';
        }

        if ('' == $test_spec['php']) {
            return 'No PHP code found in test file.';
        }

        return;
    }

    /**
     * Runs (eval()'s) the code and returns it output and a list of error messages if any.
     *
     * @param    string
     *
     * @return array
     */
    protected function evalCode($__code)
    {
        ConverterTestEvalErrorHandler(-1, null);
        set_error_handler('ConverterTestEvalErrorHandler');
        ini_set('display_errors', 'on');

        ob_start();
        eval($__code);
        $__output = ob_get_contents();
        ob_end_clean();

        restore_error_handler();

        return array('output' => $__output, 'errors' => ConverterTestEvalErrorHandler(-2, null));
    }

    /**
     * Parses a test file and returns a hash with it's parsed components.
     *
     * @param    string
     *
     * @return mixed null in case of errors, otherwise hash
     */
    protected function parseTestFile($file)
    {
        if (!($content = file_get_contents($file))) {
            return;
        }

        $ret = array(
            'testname' => null,
            'php' => null,
            'mysql_output' => null,
            'mysql_errors' => null,
            'mysqli_output' => null,
            'mysqli_errors' => null,
            'converter_errors' => null,
        );

        $len = strlen($content);
        $state = self::STATE_NORMAL;
        $enclosed_by = null;
        $token = '';
        $string = '';

        for ($pos = 0; $pos < $len; ++$pos) {
            $char = $content[$pos];

            $string .= $char;
            $token  .= $char;

            if (trim($char) == '') {
                $token = '';
                continue;
            }

            // printf("[%10s] '%s'\n", $state, $token);

            switch ($token) {
                case '--TEST--':
                    if ($state != self::STATE_NORMAL) {
                        return;
                    }
                    $string = $token = '';
                    $state = self::STATE_TEST;
                    break;

                case '--FILE--':
                    if ($state != self::STATE_TEST) {
                        return;
                    }
                    $ret['testname'] = substr($string, 1, max(0, strlen($string) - strlen($token) - 2));
                    $string = $token = '';
                    $state = self::STATE_FILE;
                    break;

                case '--EXPECT-EXT/MYSQL-OUTPUT--':
                    if ($state != self::STATE_FILE) {
                        return;
                    }
                    $ret['php'] = substr($string, 1, max(0, strlen($string) - strlen($token) - 2));
                    if ('' != $ret['php']) {
                        // remove <?php, <? and _>
                        if (preg_match('/^\s*<\?php/i', $ret['php'], $matches)) {
                            $ret['php'] = substr($ret['php'], strlen($matches[0]));
                        }
                        if (preg_match('/^\s*<\?/i', $ret['php'], $matches)) {
                            $ret['php'] = substr($ret['php'], strlen($matches[0]));
                        }
                        if (preg_match('/\?>$/i', $ret['php'], $matches)) {
                            $ret['php'] = substr($ret['php'], 0, -1 * strlen($matches[0]));
                        }
                    }
                    $string = $token = '';
                    $state = self::STATE_MYSQL_OUTPUT;
                    break;

                case '--EXPECT-EXT/MYSQL-PHP-ERRORS--':
                    if ($state != self::STATE_MYSQL_OUTPUT) {
                        return;
                    }
                    $ret['mysql_output'] = substr($string, 1, max(0, strlen($string) - strlen($token) - 2));
                    $string = $token = '';
                    $state = self::STATE_MYSQL_ERRORS;
                    break;

                case '--EXPECT-EXT/MYSQLI-OUTPUT--':
                    if ($state != self::STATE_MYSQL_ERRORS) {
                        return;
                    }
                    $ret['mysql_errors'] = substr($string, 1, max(0, strlen($string) - strlen($token) - 2));
                    if ('' != $ret['mysql_errors']) {
                        $lines = explode("\n", $ret['mysql_errors']);
                        $ret['mysql_errors'] = array();
                        foreach ($lines as $k => $line) {
                            $line = explode(',', $line);
                            $ret['mysql_errors'][] = array('line' => trim($line[0]), 'errno' => trim($line[1]), 'msg' => trim($line[2]));
                        }
                    } else {
                        $ret['mysql_errors'] = array();
                    }
                    $string = $token = '';
                    $state = self::STATE_MYSQLI_OUTPUT;
                    break;

                case '--EXPECT-EXT/MYSQLI-PHP-ERRORS--':
                    if ($state != self::STATE_MYSQLI_OUTPUT) {
                        return;
                    }
                    $ret['mysqli_output'] = substr($string, 1, max(0, strlen($string) - strlen($token) - 2));
                    $string = $token = '';
                    $state = self::STATE_MYSQLI_ERRORS;
                    break;

                case '--EXPECT-CONVERTER-ERRORS--':
                    if ($state != self::STATE_MYSQLI_ERRORS) {
                        return;
                    }
                    $ret['mysqli_errors'] = substr($string, 1, max(0, strlen($string) - strlen($token) - 2));

                    if ('' != $ret['mysqli_errors']) {
                        $lines = explode("\n", $ret['mysqli_errors']);
                        $ret['mysqli_errors'] = array();
                        foreach ($lines as $k => $line) {
                            $line = explode(',', $line);
                            $ret['mysqli_errors'][] = array('line' => trim($line[0]), 'errno' => trim($line[1]), 'msg' => trim($line[2]));
                        }
                    } else {
                        $ret['mysqli_errors'] = array();
                    }
                    $string = $token = '';
                    $state = self::STATE_CONVERTER_ERRORS;
                    break;

                case '--ENDOFTEST--':
                    if ($state != self::STATE_CONVERTER_ERRORS) {
                        return;
                    }
                    $ret['converter_errors'] = substr($string, 1, max(0, strlen($string) - strlen($token) - 2));
                    if ('' != $ret['converter_errors']) {
                        $tmp = explode(',', $ret['converter_errors']);
                        $ret['converter_errors'] = array();
                        foreach ($tmp as $k => $v) {
                            if ('' != trim($v)) {
                                $ret['converter_errors'][] = (int) $v;
                            }
                        }
                    }

                    $string = $token = '';
                    $state = self::STATE_NORMAL;
                    break;

            }
        }

        return $ret;
    }
}

/**
 * set_error_handler() callback used for eval().
 */
function ConverterTestEvalErrorHandler($errno, $errstr, $errfile = '', $errline = 0, array $errcontext = array())
{
    static $errors;

    if (-1 == $errno) {
        $errors = array();

        return;
    } elseif (-2 == $errno) {
        return $errors;
    }

    switch ($errno) {

        case E_ERROR:
            $errno = 'E_ERROR';
            break;

        case E_WARNING:
            $errno = 'E_WARNING';
            break;

        case E_PARSE:
            $errno = 'E_PARSE';
            break;

        case E_NOTICE:
            $errno = 'E_NOTICE';
            break;

        case E_CORE_ERROR:
            $errno = 'E_CORE_ERROR';
            break;

        case E_CORE_WARNING:
            $errno = 'E_CORE_WARNING';
            break;

        case E_COMPILE_ERROR:
            $errno = 'E_COMPILE_ERROR';
            break;

        case E_COMPILE_WARNING:
            $errno = 'E_COMPILE_WARNING';
            break;

        case E_USER_ERROR:
            $errno = 'E_USER_ERROR';
            break;

        case E_USER_WARNING:
            $errno = 'E_USER_WARNING';
            break;

        case E_USER_NOTICE:
            $errno = 'E_USER_NOTICE';
            break;

        case E_STRICT:
            $errno = 'E_STRICT';
            break;

        case E_RECOVERABLE_ERROR:
            $errno = 'E_RECOVERABLE_ERROR';
            break;

        case E_ALL:
            $errno = 'E_ALL';
            break;
    }

    $errors[] = array('errno' => $errno, 'line' => $errline, 'msg' => $errstr);
}

// Call MySQLConverterTool_ConverterTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_ConverterTest::main') {
    MySQLConverterTool_ConverterTest::main();
}
