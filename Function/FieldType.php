<?php

require_once 'Generic.php';

/**
 * Converter: mysql_field_type.
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
class MySQLConverterTool_Function_FieldType extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_fetch_field_direct';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // string mysql_field_type ( resource result, int field_offset )
        // object mysqli_fetch_field_direct ( mysqli_result result, int fieldnr )

        if (count($params) != 2) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        /*      
        
        The following types reported by ext/mysqli are not reported by ext/mysql:
        
           REGISTER_LONG_CONSTANT("MYSQLI_TYPE_CHAR", FIELD_TYPE_CHAR, CONST_CS | CONST_PERSISTENT);
           REGISTER_LONG_CONSTANT("MYSQLI_TYPE_INTERVAL", FIELD_TYPE_INTERVAL, CONST_CS | CONST_PERSISTENT);
            #if MYSQL_VERSION_ID > 50002	
               REGISTER_LONG_CONSTANT("MYSQLI_TYPE_BIT", FIELD_TYPE_BIT, CONST_CS | CONST_PERSISTENT);
            #endif 
        */
        list($res, $i) = $this->extractParamValues($params);

        // (($__f = <fetch_field>->type) ? (string)((substr((($__f == FLAG_CONSTANT) ? 'flag_string' : '')) [. ()], -1) : false)
        // (($__f = <fetch_field>->type) ? (string)((substr((( ($__f == FLAG_CONSTANT) || (...) ) ? 'flag_string ' : '')) [. ()], -1) : false)
        // ----------- 1 ----------   ------------------- n ------------------------                                                  ------- 3 --------------
        // refinement of 1:
        // ((is_object($__f = <fetch_field>) && !is_null($__f = $f__->type)) ?         
        // refinement of n:
        // ((($__f == substr()) == "") ? "unknown" : $__f)

        // ----------- 1 ----------
        $ret = '(';
        $ret .= sprintf('(is_object($___mysqli_tmp = mysqli_fetch_field_direct(%s, %d)) && !is_null($___mysqli_tmp = $___mysqli_tmp->type)) ? ', $res, $i);

         // ------------------- n ------------------------
        $ret .= '((($___mysqli_tmp = ';
        $ret .= '(string)(substr(';
        $ret .=      '( (($___mysqli_tmp == MYSQLI_TYPE_STRING) || ($___mysqli_tmp == MYSQLI_TYPE_VAR_STRING) ) ? "string " : "" ) . ';
        $ret .=      '( (in_array($___mysqli_tmp, array(MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_LONG, MYSQLI_TYPE_LONGLONG, MYSQLI_TYPE_INT24))) ? "int " : "" ) . ';
        $ret .=      '( (in_array($___mysqli_tmp, array(MYSQLI_TYPE_FLOAT, MYSQLI_TYPE_DOUBLE, MYSQLI_TYPE_DECIMAL, ((defined("MYSQLI_TYPE_NEWDECIMAL")) ? constant("MYSQLI_TYPE_NEWDECIMAL") : -1)))) ? "real " : "" ) . ';
        $ret .=      '( ($___mysqli_tmp == MYSQLI_TYPE_TIMESTAMP) ? "timestamp " : "" ) . ';
        $ret .=      '( ($___mysqli_tmp == MYSQLI_TYPE_YEAR) ? "year " : "" ) . ';
        $ret .=      '( (($___mysqli_tmp == MYSQLI_TYPE_DATE) || ($___mysqli_tmp == MYSQLI_TYPE_NEWDATE) ) ? "date " : "" ) . ';
        $ret .=      '( ($___mysqli_tmp == MYSQLI_TYPE_TIME) ? "time " : "" ) . ';
        $ret .=      '( ($___mysqli_tmp == MYSQLI_TYPE_SET) ? "set " : "" ) . ';
        $ret .=      '( ($___mysqli_tmp == MYSQLI_TYPE_ENUM) ? "enum " : "" ) . ';
        $ret .=      '( ($___mysqli_tmp == MYSQLI_TYPE_GEOMETRY) ? "geometry " : "" ) . ';
        $ret .=      '( ($___mysqli_tmp == MYSQLI_TYPE_DATETIME) ? "datetime " : "" ) . ';
        $ret .=      '( (in_array($___mysqli_tmp, array(MYSQLI_TYPE_TINY_BLOB, MYSQLI_TYPE_BLOB, MYSQLI_TYPE_MEDIUM_BLOB, MYSQLI_TYPE_LONG_BLOB))) ? "blob " : "" ) . ';
        $ret .=      '( ($___mysqli_tmp == MYSQLI_TYPE_NULL) ? "null " : "" )';
        $ret .= ', 0, -1))';
        $ret .= ') == "") ? "unknown" : $___mysqli_tmp)';

        // ------- 3 --------------
        $ret .= ' : false)';

        return array(null, $ret);
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_fetch_field_direct() and a conditional expression. The following types reported by ext/mysqli are not reposted by ext/mysql: MYSQLI_TYPE_CHAR, MYSQLI_TYPE_INTERVAL, MYSQLI_TYPE_BIT';
    }
}
