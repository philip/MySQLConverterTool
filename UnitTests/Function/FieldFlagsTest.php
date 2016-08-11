<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'MySQLConverterTool_Function_FieldFlagsTest::main');
}

require_once 'GenericTest.php';
require_once 'MySQLConverterTool/Function/FieldFlags.php';

/**
 * UnitTests: artificial tests, PHPUnit test for Function/FieldFlags.
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
class MySQLConverterTool_UnitTests_Function_FieldFlagsTest extends MySQLConverterTool_UnitTests_Function_GenericTest
{
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('MySQLConverterTool_UnitTests_Function_FieldFlagsTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp()
    {
        $this->gen = new MySQLConverterTool_Function_FieldFlags('mysqli_real_escape_string');
        $this->default_conn = $this->gen->ston_name;
    }

    public function testHandle()
    {

        // string mysql_field_flags ( resource result, int field_offset )        
        // "not_null"           NOT_NULL_FLAG       MYSQLI_NOT_NULL_FLAG
        // "primary_key"        PRI_KEY_FLAG        MYSQLI_PRI_KEY_FLAG
        // "unique_key"         UNIQUE_KEY_FLAG     MYSQLI_UNIQUE_KEY_FLAG
        // "multiple_key"       MULTIPLE_KEY_FLAG   MYSQLI_MULTIPLE_KEY_FLAG
        // "blob"               BLOB_FLAG           MYSQLI_BLOB_FLAG
        // "unsigned"           UNSIGNED_FLAG       MYSQLI_UNSIGNED_FLAG
        // "zerofill"           ZEROFILL_FLAG       MYSQLI_ZEROFILL_FLAG
        // "binary"             BINARY_FLAG         !!!
        // "enum"               ENUM_FLAG           !!!
        // "auto_increment"     AUTO_INCREMENT_FLAG MYSQLI_AUTO_INCREMENT_FLAG
        // "timestamp"          TIMESTAMP_FLAG      MYSQLI_TIMESTAMP_FLAG
        // "set"                SET_FLAG            MYSQLI_SET_FLAG

        // object mysqli_fetch_field_direct ( mysqli_result result, int fieldnr )

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array()));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // too few parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);

        // mysql_field_flags(<result>, <field_offset>
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>', '<field_offset>')));
        $this->assertNull($warning);
        $this->assertEquals(
            '(($___mysqli_tmp = mysqli_fetch_field_direct(<result>, <field_offset>)->flags) ? (string)(substr((($___mysqli_tmp & MYSQLI_NOT_NULL_FLAG)       ? "not_null "       : "") . (($___mysqli_tmp & MYSQLI_PRI_KEY_FLAG)        ? "primary_key "    : "") . (($___mysqli_tmp & MYSQLI_UNIQUE_KEY_FLAG)     ? "unique_key "     : "") . (($___mysqli_tmp & MYSQLI_MULTIPLE_KEY_FLAG)   ? "unique_key "     : "") . (($___mysqli_tmp & MYSQLI_BLOB_FLAG)           ? "blob "           : "") . (($___mysqli_tmp & MYSQLI_UNSIGNED_FLAG)       ? "unsigned "       : "") . (($___mysqli_tmp & MYSQLI_ZEROFILL_FLAG)       ? "zerofill "       : "") . (($___mysqli_tmp & 128)                        ? "binary "         : "") . (($___mysqli_tmp & 256)                        ? "enum "           : "") . (($___mysqli_tmp & MYSQLI_AUTO_INCREMENT_FLAG) ? "auto_increment " : "") . (($___mysqli_tmp & MYSQLI_TIMESTAMP_FLAG)      ? "timestamp "      : "") . (($___mysqli_tmp & MYSQLI_SET_FLAG)            ? "set "            : ""), 0, -1)) : false)',
            $code
        );

        // too many parameters: parse error
        list($warning, $code) = $this->gen->handle($this->buildParams(array('<result>', '<field_offset>', '<ERROR>')));
        $this->assertNotNull($warning);
        $this->assertNull($code);
    }
}

if (PHPUnit_MAIN_METHOD == 'MySQLConverterTool_Function_FieldFlagsTest::main') {
    MySQLConverterTool_Function_FieldFlagsTest::main();
}
