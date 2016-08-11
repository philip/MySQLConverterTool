<?php

require_once 'Generic.php';

/**
 * Converter: mysql_change_user.
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
class MySQLConverterTool_Function_ChangeUser extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_change_user';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {

        // mysql_change_user ( string user, string password [, string database [, resource link_identifier]] )
        // mysqli_change_user ( mysqli link, string user, string password, string database )

        $warning = null;

        if (4 == count($params)) {
            list($user, $pass, $db, $conn) = $this->extractParamValues($params);
            $ret = "mysqli_change_user($conn, $user, $pass, $db)";
        } elseif (3 == count($params)) {
            list($user, $pass, $db) = $this->extractParamValues($params);
            $conn = $this->ston_name;
            $ret = "mysqli_change_user($conn, $user, $pass, $db)";
        } elseif (2 == count($params)) {
            list($user, $pass) = $this->extractParamValues($params);
            $conn = $this->ston_name;

            // [1] $res = mysqli_query(conn, 'SELECT DATABASE()')
            // [2] $row = mysqli_fetch_row($res);
            // [3] $row = array_shift($row);
            // [4] unset($row);
            // [5] mysqli_free_result($res);
            // [6] unset($res);
            // [7] mysqli_change_user(conn, user, pass, $db)
            //
            // [1] ( ($res = mysqli_query() ) ? $db : false
            // [2] ( ($res = mysqli_query() && row = mysqli_fetch_row($res)) ? $db : false
            // [3] ( ($res = mysqli_query() && row = mysqli_fetch_row($res) && ((row = array_shift(row)) !== false) ) ? true : false
            // [5] ( ($res = mysqli_query() && row = mysqli_fetch_row($res) && ((row = array_shift(row)) !== false) ) && mysqli_free_result(res) ) ? true : false
            // [7] ( ($res = mysqli_query() && row = mysqli_fetch_row($res) && ((row = array_shift(row)) !== false) ) && mysqli_free_result(res) ) ? mysqli_change_user(conn, user, pass, $row) : false

            $ret = '( ';
            $ret .=  '( ';
            $ret .=      '($___mysqli_res = mysqli_query('.$conn.', "SELECT DATABASE()")) && ';
            $ret .=      '($___mysqli_tmp = mysqli_fetch_row($___mysqli_res)) && ';
            $ret .=      '(($___mysqli_tmp = array_shift($___mysqli_tmp)) !== false) && ';
            $ret .=      '(mysqli_free_result($___mysqli_res)) ';
            $ret .=  ') ? ';
            $ret .= sprintf('mysqli_change_user(%s, %s, %s, $___mysqli_tmp) : ', $conn, $user, $pass);
            $ret .= 'false ';
            $ret .= ')';
        } else {
            $warning = self::PARSE_ERROR_WRONG_PARAMS;
            $ret = null;
        }

        return array($warning, $ret);
    }

    public function getConversionHint()
    {
        return 'mysql_change_user() can be translated into mysqli_change_user if the database is given. If not, it must be emulated using mysqli_query() and SELECT DATABASE() - which looks a bit like a hack.';
    }
}
