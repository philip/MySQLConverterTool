<?PHP

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_UnitTests_Function_AllTests::main');
}

require_once 'ChangeUserTest.php';
require_once 'ConnectTest.php';
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
require_once 'GenericTest.php';
require_once 'GenericBooleanTest.php';
require_once 'ListDBsTest.php';
require_once 'ListFieldsTest.php';
require_once 'ListProcessesTest.php';
require_once 'ListTablesTest.php';
require_once 'ParReversedTest.php';
require_once 'RealEscapeStringTest.php';
require_once 'SelectDBTest.php';
require_once 'TablenameTest.php';
require_once 'UnbufferedQueryTest.php';

/**
 * UnitTests: artificial tests, PHPUnit Alltests.
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
class MySQLConverterTool_UnitTests_Function_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_Function');

        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ChangeUserTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ConnectTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ConnParamTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ConnParamBoolTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_CreateDBTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_DBQueryTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_DropDBTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ErrorTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_EscapeStringTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_FetchFieldTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_FieldFlagsTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_FieldLenTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_FieldNameTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_FieldTableTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_FieldTypeTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_FreeResultTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_GenericTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_GenericBooleanTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ListDBsTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ListFieldsTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ListProcessesTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ListTablesTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_ParReversedTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_RealEscapeStringTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_SelectDBTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_TablenameTest');
        $suite->addTestSuite('MySQLConverterTool_UnitTests_Function_UnbufferedQueryTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_UnitTests_Function_AllTests::main') {
    MySQLConverterTool_UnitTests_Function_AllTests::main();
}
