<?php

require_once 'Generic.php';

/**
 * Converter: mysql_field_flags().
 * 
 * Generates ugly code, rewrite the ext/mysql code
 *
 * @category   Functions
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
class MySQLConverterTool_Function_FieldFlags extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_fetch_field_direct';

    public function __construct()
    {
    }

    public function handle(array $params = array())
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

        if (count($params) != 2) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        list($res, $i) = $this->extractParamValues($params);

        // (($__f = <fetch_field>) ? (string)((substr((($__f & FLAG_CONSTANT) ? 'flag_string' : '')) [. ()], -1)) : false)
        // ----------- 1 ----------   ------------------- n --------------------------------------------------- ------- 3 --

        // ----------- 1 ----------
        $ret = sprintf('(($___mysqli_tmp = %s(%s, %s)->flags) ? ',
                    $this->new_name,
                    $res,
                    $i
                    );

        // ------------------- n ------------------------
        $ret .= '(string)(substr(';
        $ret .=      '(($___mysqli_tmp & MYSQLI_NOT_NULL_FLAG)       ? "not_null "       : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_PRI_KEY_FLAG)        ? "primary_key "    : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_UNIQUE_KEY_FLAG)     ? "unique_key "     : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_MULTIPLE_KEY_FLAG)   ? "unique_key "     : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_BLOB_FLAG)           ? "blob "           : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_UNSIGNED_FLAG)       ? "unsigned "       : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_ZEROFILL_FLAG)       ? "zerofill "       : "") . ';
        // FIXME - Constants are missing in ext/mysqli, added to CVS on 20.07.2006
        $ret .=      '(($___mysqli_tmp & 128)                        ? "binary "         : "") . ';
        $ret .=      '(($___mysqli_tmp & 256)                        ? "enum "           : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_AUTO_INCREMENT_FLAG) ? "auto_increment " : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_TIMESTAMP_FLAG)      ? "timestamp "      : "") . ';
        $ret .=      '(($___mysqli_tmp & MYSQLI_SET_FLAG)            ? "set "            : "")';
        $ret .= ', 0, -1)';

        // ------- 3 --------------
        $ret .= ')';
        $ret .= ' : false)';

        return array(null, $ret);
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_fetch_field_direct and a conditional espression.';
    }
}
