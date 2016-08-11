<?php

require_once 'Generic.php';

/**
 * Converter: mysql_db_query.
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
class MySQLConverterTool_Function_DBQuery extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_query';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // mysql_db_query ( string database, string query [, resource link_identifier] )
        if (count($params) < 2 || count($params) > 3) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($new_db, $query, $conn) = $this->extractParamValues($params);
        if (is_null($conn)) {
            $conn = $this->ston_name;
        }

        list($new_db, $new_db_type) = $this->extractValueAndType(trim($new_db));
        if ('const' == $new_db_type) {
            $ret = sprintf('((mysqli_query(%s, "USE " . constant(\'%s\'))) ? mysqli_query(%s, %s) : false)',
                $conn,
                $new_db,
                $conn,
                $query);
        } else {
            $ret = sprintf('((mysqli_query(%s, "USE %s")) ? mysqli_query(%s, %s) : false)',
                $conn,
                $new_db,
                $conn,
                $query);
        }

        return array('mysql_db_query(string database_name [...]) is emulated using mysqli_query() and USE database_name. This is a possible SQL injection security bug as no tests are performed what value database_name has. Check your script!', $ret);
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_query and USE.';
    }
}
