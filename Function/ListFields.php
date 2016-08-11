<?php

require_once 'Generic.php';

/**
 * Converter: mysql_list_fields.
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
class MySQLConverterTool_Function_ListFields extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_query';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // mysql_list_fields ( string database_name, string table_name [, resource link_identifier] )
        // mixed mysqli_query ( mysqli link, string query [, int resultmode] )       

        if (count($params) < 2 || count($params) > 3) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($db, $table, $conn) = $this->extractParamValues($params);
        if (is_null($conn)) {
            $conn = $this->ston_name;
        }

        list($db, $db_type) = $this->extractValueAndType(trim($db));
        list($table, $table_type) = $this->extractValueAndType(trim($table));

        $sql = '"SHOW COLUMNS FROM ';
        if ($db_type == 'const') {
            $sql .= '" . constant(\''.$db.'\') . ".';
        } else {
            $sql .= $db.'.';
        }

        if ($table_type == 'const') {
            $sql .= '" . constant(\''.$table.'\')';
        } else {
            $sql .= $table.'"';
        }

        $ret = sprintf('(($___mysqli_tmp = mysqli_query(%s, %s)) ? $___mysqli_tmp : false)', $conn, $sql);

        return array(
            'mysql_list_fields(string database_name, string table_name [...]) is emulated using mysqli_query() and SHOW COLUMNS FROM database_name.table_name . This is a possible SQL injection security bug as no tests are performed what value database_name and/or table_name have. Check your script! Additionally, this is not fully compatible to the original expression, check the mysql_list_fields() documentation on php.net!', $ret, );
    }

    public function getConversionHint()
    {
        return 'Emulated using mysqli_query() and SHOW COLUMNS FROM table';
    }
}
