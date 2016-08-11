<?php

require_once 'Generic.php';

/**
 * Converter: mysql_field_name.
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
class MySQLConverterTool_Function_FieldName extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_fetch_field_direct';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // string mysql_field_name ( resource result, int field_offset )
        // mysqli_fetch_field_direct ( mysqli_result result, int fieldnr )

        if (count($params) != 2) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        list($res, $i) = $this->extractParamValues($params);

        return array(null, sprintf('((($___mysqli_tmp = mysqli_fetch_field_direct(%s, %s)->name) && (!is_null($___mysqli_tmp))) ? $___mysqli_tmp : false)', $res, $i));
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_fetch_field_direct().';
    }
}
