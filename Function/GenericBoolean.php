<?php

require_once 'Generic.php';

/**
 * Converter: generic converter with extra boolean cast.
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
class MySQLConverterTool_Function_GenericBoolean extends MySQLConverterTool_Function_Generic
{
    public function handle(array $params = array())
    {
        $params = $this->extractParamValues($params);

        return array(null,  sprintf('(($___mysqli_tmp = %s(%s)) ? $___mysqli_tmp : false)', $this->new_name, implode(', ', $params)));
    }
}
