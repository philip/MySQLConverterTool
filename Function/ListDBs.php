<?php

require_once 'Generic.php';

/**
 * Converter: mysql_list_dbs.
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
class MySQLConverterTool_Function_ListDBs extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_query';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // mysql_list_dbs ( [resource link_identifier] )
        // mixed mysqli_query ( mysqli link, string query [, int resultmode] )
        if (count($params) > 1) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($conn) = $this->extractParamValues($params);
        if (is_null($conn)) {
            $conn = $this->ston_name;
        }

        return array(null, sprintf('(($___mysqli_tmp = mysqli_query(%s, "SHOW DATABASES")) ? $___mysqli_tmp : false)', $conn));
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_query() and SHOW DATABASES. Returns NULL instead of false in case of an error';
    }
}
