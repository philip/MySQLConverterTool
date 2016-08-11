<?php

require_once 'Generic.php';

/**
 * Converter: mysql_fetch_field.
 *
 * NOTE: generates very ugly code, very difficult to emulate, rewrite your ext/mysql code...
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
class MySQLConverterTool_Function_FetchField extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_fetch_field_direct';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // object mysql_fetch_field ( resource result [, int field_offset] )
        // object mysqli_fetch_field_direct ( mysqli_result result, int fieldnr )

        if (count($params) < 1 || count($params) > 2) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($res, $offset) = $this->extractParamValues($params);

        /*
        [01] $f = f() && is_object($f)
        [02] $f->primary_key = ($f->flags & MYSQLI_PRI_KEY_FLAG) ? 1 : 0
        [03] $f->multiple_key = ($f->flags & MYSQLI_MULTIPLE_KEY_FLAG) ? 1 : 0
        [04] $f->unique_key = ($f->flags & MYSQLI_UNIQUE_KEY_FLAG) ? 1 : 0
        
        MySQL source! #define IS_NUM(t)	((t) <= FIELD_TYPE_INT24 || (t) == FIELD_TYPE_YEAR || (t) == FIELD_TYPE_NEWDECIMAL) 
        [05] $f->numeric = ($f->type <= MYSQLI_TYPE_INT24 || $f->fype == MYSQLI_TYPE_YEAR || ((defined('MYSQLI_TYPE_NEWDECIMAL')) ? ($f->type == MYSQLI_TYPE_NEWDECIMAL) : false)
        
        [06] $f->blob = (int)in_array($f->type, array(MYSQLI_TYPE_TINY_BLOB, MYSQLI_TYPE_BLOB, MYSQLI_TYPE_MEDIUM_BLOB, MYSQLI_TYPE_LONG_BLOB))
        
        [07] $f->unsinged = ($f->flags & MYSQLI_UNSIGNED_FLAG) ? 1 : 0
        [08] $f->zerofill = ($f->flags & MYSQLI_ZEROFILL_FLAG) ? 1 : 0
        
        [09] $type = $f->type 
        [10] $f->type = (($type == MYSQLI_TYPE_STRING) || ($type == MYSQLI_TYPE_VAR_STRING)) ? 'type' : ''
        [11] $f->type = ("" == $f-type && in_array($___mysqli_tmp, array(MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_LONG, MYSQLI_TYPE_LONGLONG, MYSQLI_TYPE_INT24))) ? "int " : ""
        [12] $f->type = ("" == $f-type && in_array($___mysqli_tmp, array(MYSQLI_TYPE_FLOAT, MYSQLI_TYPE_DOUBLE, MYSQLI_TYPE_DECIMAL, ((defined("MYSQLI_TYPE_NEWDECIMAL")) ? constant("MYSQLI_TYPE_NEWDECIMAL") : -1)))) ? "real " : ""
        [13] $f->type = ("" == $f-type && $___mysqli_tmp == MYSQLI_TYPE_TIMESTAMP) ? "timestamp " : "" 
        [14] $f->type = ("" == $f-type && $___mysqli_tmp == MYSQLI_TYPE_YEAR) ? "year " : "" 
        [15] $f->type = ("" == $f-type && ($___mysqli_tmp == MYSQLI_TYPE_DATE) || ($___mysqli_tmp == MYSQLI_TYPE_NEWDATE)) ? "date " : ""
        [16] $f->type = ("" == $f-type && $___mysqli_tmp == MYSQLI_TYPE_TIME) ? "time " : "" 
        [17] $f->type = ("" == $f-type && $___mysqli_tmp == MYSQLI_TYPE_SET) ? "set " : ""
        [18] $f->type = ("" == $f-type && ($___mysqli_tmp == MYSQLI_TYPE_ENUM) ? "enum " : ""
        [19] $f->type = ("" == $f-type && ($___mysqli_tmp == MYSQLI_TYPE_GEOMETRY) ? "geometry " : "" 
        [20] $f->type = ("" == $f-type && ($___mysqli_tmp == MYSQLI_TYPE_DATETIME) ? "datetime " : "" 
        [21] $f->type = ("" == $f-type && (in_array($___mysqli_tmp, array(MYSQLI_TYPE_TINY_BLOB, MYSQLI_TYPE_BLOB, MYSQLI_TYPE_MEDIUM_BLOB, MYSQLI_TYPE_LONG_BLOB))) ? "blob " : ""
        [22] $f->type = ("" == $f-type && ($___mysqli_tmp == MYSQLI_TYPE_NULL) ? "null " : "unknown";
        
        [23] $f->not_null = ($f->flags & MYSQLI_NOT_NULL_FLAG) ? 1 : 0
        
        (
        ([01] && is_object($f) ? (
                !is_null([02]) && !is_null([03]) && !is_null([04]) &&
                !is_null([05]) && !is_null([06]) && !is_null([07]) &&
                !is_null([08]) && !is_null([09]) ...
             ) : false) ? $___mysqli_tmp : false)
        */

        $warning = 'You should rewrite all your mysql_fetch_field() calls. The generated code should be compatible. But have a look at the generated code, it is unmaintainable! FIX your code!';

        $ret = '(';
        $ret .= '(';
        // [01]
        $ret .= sprintf('(($___mysqli_tmp = mysqli_fetch_field_direct(%s, %s)) && is_object($___mysqli_tmp))', $res,
                    (is_null($offset)) ? sprintf('mysqli_field_tell(%s)', $res) : (int) $offset);
        $ret .= ' ? ( ';
        // [02]
        $ret .= '(!is_null($___mysqli_tmp->primary_key = ($___mysqli_tmp->flags & MYSQLI_PRI_KEY_FLAG) ? 1 : 0)) && ';
        // [03]
        $ret .= '(!is_null($___mysqli_tmp->multiple_key = ($___mysqli_tmp->flags & MYSQLI_MULTIPLE_KEY_FLAG) ? 1 : 0)) && ';
        // [04]
        $ret .= '(!is_null($___mysqli_tmp->unique_key = ($___mysqli_tmp->flags & MYSQLI_UNIQUE_KEY_FLAG) ? 1 : 0)) && ';
        // [05]
        $ret .= '(!is_null($___mysqli_tmp->numeric = (int)(($___mysqli_tmp->type <= MYSQLI_TYPE_INT24) || ($___mysqli_tmp->type == MYSQLI_TYPE_YEAR) || ((defined("MYSQLI_TYPE_NEWDECIMAL")) ? ($___mysqli_tmp->type == MYSQLI_TYPE_NEWDECIMAL) : 0)))) && ';
        // [06]
        $ret .= '(!is_null($___mysqli_tmp->blob = (int)in_array($___mysqli_tmp->type, array(MYSQLI_TYPE_TINY_BLOB, MYSQLI_TYPE_BLOB, MYSQLI_TYPE_MEDIUM_BLOB, MYSQLI_TYPE_LONG_BLOB)))) && ';
        // [07]
        $ret .= '(!is_null($___mysqli_tmp->unsigned = ($___mysqli_tmp->flags & MYSQLI_UNSIGNED_FLAG) ? 1 : 0)) && ';
        // [08]
        $ret .= '(!is_null($___mysqli_tmp->zerofill = ($___mysqli_tmp->flags & MYSQLI_ZEROFILL_FLAG) ? 1 : 0)) && ';
        // [09]
        $ret .= '(!is_null($___mysqli_type = $___mysqli_tmp->type)) && ';
        // [10]
        $ret .= '(!is_null($___mysqli_tmp->type = (($___mysqli_type == MYSQLI_TYPE_STRING) || ($___mysqli_type == MYSQLI_TYPE_VAR_STRING)) ? "type" : "")) &&';
        // [11]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && in_array($___mysqli_type, array(MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_LONG, MYSQLI_TYPE_LONGLONG, MYSQLI_TYPE_INT24))) ? "int" : $___mysqli_tmp->type)) &&';
        // [12]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && in_array($___mysqli_type, array(MYSQLI_TYPE_FLOAT, MYSQLI_TYPE_DOUBLE, MYSQLI_TYPE_DECIMAL, ((defined("MYSQLI_TYPE_NEWDECIMAL")) ? constant("MYSQLI_TYPE_NEWDECIMAL") : -1)))) ? "real" : $___mysqli_tmp->type)) && ';
        // [13]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && $___mysqli_type == MYSQLI_TYPE_TIMESTAMP) ? "timestamp" : $___mysqli_tmp->type)) && ';
        // [14]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && $___mysqli_type == MYSQLI_TYPE_YEAR) ? "year" : $___mysqli_tmp->type)) && ';
        // [15]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && (($___mysqli_type == MYSQLI_TYPE_DATE) || ($___mysqli_type == MYSQLI_TYPE_NEWDATE))) ? "date " : $___mysqli_tmp->type)) && ';
        // [16]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && $___mysqli_type == MYSQLI_TYPE_TIME) ? "time" : $___mysqli_tmp->type)) && ';
        // [17]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && $___mysqli_type == MYSQLI_TYPE_SET) ? "set" : $___mysqli_tmp->type)) &&';
        // [18]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && $___mysqli_type == MYSQLI_TYPE_ENUM) ? "enum" : $___mysqli_tmp->type)) && ';
        // [19]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && $___mysqli_type == MYSQLI_TYPE_GEOMETRY) ? "geometry" : $___mysqli_tmp->type)) && ';
        // [20]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && $___mysqli_type == MYSQLI_TYPE_DATETIME) ? "datetime" : $___mysqli_tmp->type)) && ';
        // [21]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && (in_array($___mysqli_type, array(MYSQLI_TYPE_TINY_BLOB, MYSQLI_TYPE_BLOB, MYSQLI_TYPE_MEDIUM_BLOB, MYSQLI_TYPE_LONG_BLOB)))) ? "blob" : $___mysqli_tmp->type)) && ';
        // [22]
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type && $___mysqli_type == MYSQLI_TYPE_NULL) ? "null" : $___mysqli_tmp->type)) && ';
        $ret .= '(!is_null($___mysqli_tmp->type = ("" == $___mysqli_tmp->type) ? "unknown" : $___mysqli_tmp->type)) && ';
        // [23]
        $ret .= '(!is_null($___mysqli_tmp->not_null = ($___mysqli_tmp->flags & MYSQLI_NOT_NULL_FLAG) ? 1 : 0))';

        $ret .= ' ) : false';
        $ret .= ' )';
        $ret .= ' ? $___mysqli_tmp : false)';

        return array($warning, $ret);
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_fetch_field_direct() and a conditional expression.';
    }
}
