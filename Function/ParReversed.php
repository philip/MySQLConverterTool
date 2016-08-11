<?php

require_once 'Generic.php';

/**
 * Converter: mysql_query, mysql_real_escape_string.
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
class MySQLConverterTool_Function_ParReversed extends MySQLConverterTool_Function_Generic
{
    public function handle(array $params = array())
    {

        // mysql_query ( string query [, resource link_identifier] )
        // mixed mysqli_query ( mysqli link, string query [, int resultmode] )

        if (count($params) < 1 || count($params) > 2) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($par1, $conn) = $this->extractParamValues($params);
        if (is_null($conn)) {
            $conn = $this->ston_name;
        }

        return array(null, "$this->new_name($conn, $par1)");
    }

    public function getConversionHint()
    {
        return 'Generic function for: mysql_func(param1, param2) => mysqli_func(param2, param1). Currently used for mysql_query(), mysql_real_escape_string()';
    }
}
