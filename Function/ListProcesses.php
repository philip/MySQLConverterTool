<?php
require_once('Generic.php');

/**
* Converter: mysql_list_processes
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
class MySQLConverterTool_Function_ListProcesses extends MySQLConverterTool_Function_Generic {
  
    
    public $new_name = 'mysqli_query';

    
    public function __construct() {   
    }
  
    
    function handle(Array $params = array()) {
        
        // resource mysql_list_processes ( [resource link_identifier] ) 
        // mixed mysqli_query ( mysqli link, string query [, int resultmode] )        
        
        if (count($params) > 1)
            return array(self::PARSE_ERROR_WRONG_PARAMS, NULL);
        
        @list($conn) = $this->extractParamValues($params);
        if (is_null($conn)) 
            $conn = $this->ston_name;
      
        return array(NULL, sprintf('mysqli_query(%s, "SHOW PROCESSLIST")', $conn)); 
    }

    
    function getConversionHint() {
        
        return 'Emulated using mysqli_query() and SHOW PROCESSLIST.';
    }

}
?>