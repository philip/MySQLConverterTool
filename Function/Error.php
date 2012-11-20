<?php
require_once('Generic.php');

/**
* Converter: mysql_error, mysql_errno
*
* @category   Functions
* @package    MySQLConverterTool
* @author     Andrey Hristov <andrey@php.net>, Ulf Wendel <ulf.wendel@phpdoc.de>
* @copyright  1997-2006 The PHP Group
* @license    http://www.php.net/license/3_0.txt  PHP License 3.0
* @version    CVS: $Id:$, Release: @package_version@
* @link       http://www.mysql.com
* @since      Class available since Release 1.0
*/

class MySQLConverterTool_Function_Error extends MySQLConverterTool_Function_Generic {   
  
    function handle(Array $params = array()) {
        
        if (count($params) > 1)
            return array(self::PARSE_ERROR_WRONG_PARAMS, NULL);
      
        @list($conn) = $this->extractParamValues($params);        
        if (is_null($conn)) {
            $warning = 'mysql_error()/mysql_errno() can be called without a connection handle, mysqli_error()/mysqli_errno() not. As we do not know if a default connection has been opened, we have wrapped the function call in ((<if_default_conn_is_object>) ? mysqli_<func>(<default_conn>) : mysqli_connect_error()/mysqli_connect_errno()). This is not 100% the same as the original code in all cases. Check the generated code!';            
            $conn = $this->ston_name;
        } else {            
            $warning = null;       
        }
        if ('mysqli_error' == $this->new_name)
            $ret = sprintf('((is_object(%s)) ? %s(%s) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))', $conn, $this->new_name, $conn);
        else
            $ret = sprintf('((is_object(%s)) ? %s(%s) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false))', $conn, $this->new_name, $conn);
        
        return array($warning, $ret);       
    }
  
    function getConversionHint() {
        
        return 'mysql_error()/mysql_errno() do not require a connection handle. When using the default connection for mysqli_error()/mysqli_errno() we throw a warning. We do not know for sure if a connection exists and add some extra code for the case it does not exist.';
    }

}
?>