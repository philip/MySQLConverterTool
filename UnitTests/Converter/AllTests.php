<?PHP

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_UnitTests_Converter_AllTests::main');
}

require_once 'ConnectTest.php';
require_once 'ChangeUserTest.php';
require_once 'ConnParamTest.php';
require_once 'ConnParamBoolTest.php';
require_once 'CreateDBTest.php';
require_once 'DBQueryTest.php';
require_once 'DropDBTest.php';
require_once 'ErrorTest.php';
require_once 'EscapeStringTest.php';
require_once 'FetchFieldTest.php';
require_once 'FieldFlagsTest.php';
require_once 'FieldLenTest.php';
require_once 'FieldNameTest.php';
require_once 'FieldTableTest.php';
require_once 'FieldTypeTest.php';
require_once 'FreeResultTest.php';
require_once 'GenericBooleanTest.php';
require_once 'GenericTest.php';
require_once 'ListDBsTest.php';
require_once 'ListFieldsTest.php';
require_once 'ListProcessesTest.php';
require_once 'ParReversedTest.php';
require_once 'RealEscapeStringTest.php';
require_once 'SelectDBTest.php';
require_once 'TablenameTest.php';
require_once 'UnbufferedQueryTest.php';

/**
 * UnitTests: real life tests, PHPUnit AllTests file.
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
class MySQLConverterTool_UnitTests_Converter_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_Function');

        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ConnectTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ChangeUserTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ConnParamTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ConnParamBoolTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_CreateDBTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_DBQueryTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_DropDBTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ErrorTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_EscapeStringTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_FetchFieldTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_FieldFlagsTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_FieldLenTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_FieldNameTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_FieldTableTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_FieldTypeTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_FreeResultTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_GenericBooleanTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_GenericTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ListDBsTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ListFieldsTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ListProcessesTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_ParReversedTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_RealEscapeStringTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_SelectDBTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_TablenameTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Converter_UnbufferedQueryTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_UnitTests_Converter_AllTests::main') {
    MySQLConverterTool_UnitTests_Converter_AllTests::main();
}
