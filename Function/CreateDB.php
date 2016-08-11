<?php

require_once 'Generic.php';

/**
 * Converter: mysql_create_db.
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
class MySQLConverterTool_Function_CreateDB extends MySQLConverterTool_Function_Generic
{
    // not used, but anyway
    public $new_name = 'mysqli_query';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // mysql_create_db ( string database_name [, resource link_identifier] )

        if (count($params) < 1 || count($params) > 2) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($db, $conn) = $this->extractParamValues($params);
        if (is_null($conn)) {
            $conn = $this->ston_name;
        }

        list($db, $db_type) = $this->extractValueAndType(trim($db));

        if ('const' == $db_type) {
            $ret = sprintf('((is_null($___mysqli_res = %s(%s, "CREATE DATABASE " . constant(\'%s\')))) ? false : $___mysqli_res)',
                $this->new_name,
                $conn,
                $db
              );
        } else {
            $ret = sprintf('((is_null($___mysqli_res = %s(%s, "CREATE DATABASE %s"))) ? false : $___mysqli_res)',
                $this->new_name,
                $conn,
                $db
              );
        }

        return array('mysql_create_db(string database_name [...]) is emulated using mysqli_query() and CREATE DATABASE database_name. This is a possible SQL injection security bug as no tests are performed what value database_name has. Check your script!', $ret);
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_query and CREATE DATABASE.';
    }
}
