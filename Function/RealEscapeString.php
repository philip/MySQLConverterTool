<?php

require_once 'Generic.php';

/**
 * Converter: mysql_real_escape_string.
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
class MySQLConverterTool_Function_RealEscapeString extends MySQLConverterTool_Function_Generic
{
    public function handle(array $params = array())
    {

        // string mysql_real_escape_string ( string unescaped_string [, resource link_identifier] )
        // string mysqli_real_escape_string ( mysqli link, string escapestr )

        if (count($params) == 1) {
            list($string) = $this->extractParamValues($params);
            $conn = $this->ston_name;
            $warning = null;
            $ret = sprintf('%s(%s, %s)', $this->new_name, $conn, $string);              
            
        } else if (count($params) == 2) {

            list($string, $conn) = @$this->extractParamValues($params);
            $warning = null;
            $ret = sprintf('%s(%s, %s)', $this->new_name, $conn, $string);
        } else {
            $warning = self::PARSE_ERROR_WRONG_PARAMS;
            $ret = null;
        }

        return array($warning, $ret);
    }
    
    function getConversionHint() {
        
        return 'Emulated using mysqli_real_escape_string and the default connection if no connection is given. A default connection has to be made before.';
    }
}
