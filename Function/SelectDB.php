<?php

require_once 'Generic.php';

/**
 * Converter: mysql_select_db.
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
class MySQLConverterTool_Function_SelectDB extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_select_db';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // bool mysql_select_db ( string database_name [, resource link_identifier] )
        // mixed mysqli_query ( mysqli link, string query [, int resultmode] )

        if (count($params) < 1 || count($params) > 2) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($db, $conn) = $this->extractParamValues($params);
        if (is_null($conn)) {
            $conn = $this->ston_name;
        }

        list($db, $db_type) = $this->extractValueAndType(trim($db));
        if ('const' == $db_type) {
            $ret = sprintf('mysqli_select_db(%s, constant(\'%s\'))', $conn, $db);
        } else {
            $ret = sprintf('mysqli_select_db(%s, %s)', $conn, $db);
        }

        return array('', $ret);
    }

    public function getConversionHint()
    {
        return 'Converted to mysqli_select_db().';
    }
}
