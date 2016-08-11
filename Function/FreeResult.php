<?php

require_once 'Generic.php';

/**
 * Converter: mysql_free_result.
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
class MySQLConverterTool_Function_FreeResult extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_free_result';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {
        if (count($params) != 1) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        list($res) = $this->extractParamValues($params);

        return array(null, sprintf('((mysqli_free_result(%s) || (is_object(%s) && (get_class(%s) == "mysqli_result"))) ? true : false)', $res, $res, $res));
    }

    public function getConversionHint()
    {
        return 'Emulated using a conditional expression and mysqli_free_result().';
    }
}
