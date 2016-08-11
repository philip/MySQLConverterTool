<?php

require_once 'Generic.php';

/**
 * Converter: generic, mysql_func([resource]) -> ((bool)mysqli_func(resource)).
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
class MySQLConverterTool_Function_ConnParamBool extends MySQLConverterTool_Function_Generic
{
    public function handle(array $params = array())
    {
        if (count($params) > 1) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        @list($conn) = $this->extractParamValues($params);
        if (is_null($conn)) {
            $conn = $this->ston_name;
        }

        return array(null, '((is_null($___mysqli_res = '.$this->new_name.'('.$conn.'))) ? false : $___mysqli_res)');
    }

    public function getConversionHint()
    {
        return 'Generic class for all functions that look like mysql_foo([<link_identifier>]) -> ((bool)mysqli_bar(<link_identifier>))';
    }
}
