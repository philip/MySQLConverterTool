<?php

require_once 'Generic.php';

/**
 * Converter: mysql_unbuffered_query.
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
class MySQLConverterTool_Function_UnbufferedQuery extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_query';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // mysql_unbuffered_query ( string query [, resource link_identifier] )      
        if (count($params) < 1 || count($params) > 2) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($q, $conn) = $this->extractParamValues($params);
        if (is_null($conn)) {
            $conn = $this->ston_name;
        }

        return array(null, "mysqli_query($conn, $q, MYSQLI_USE_RESULT)");
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_query and MYSQLI_USE_RESULT.';
    }
}
