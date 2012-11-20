<?php
require_once('Generic.php');

/**
* Converter: mysql_select_db
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
class MySQLConverterTool_Function_SelectDB extends MySQLConverterTool_Function_Generic {
  
    
    public $new_name = 'mysqli_query';

    
    public function __construct() {   
    }
  
    
    function handle(Array $params = array()) {
      
        // bool mysql_select_db ( string database_name [, resource link_identifier] )
        // mixed mysqli_query ( mysqli link, string query [, int resultmode] )
        
        if (count($params) < 1 || count($params) > 2)
            return array(self::PARSE_ERROR_WRONG_PARAMS, NULL);
        
        @list($db, $conn) = $this->extractParamValues($params);
        if (is_null($conn)) 
            $conn = $this->ston_name;
            
        list($db, $db_type) = $this->extractValueAndType(trim($db));            
        if ('const' == $db_type) {
            $ret = sprintf('((bool)mysqli_query(%s, "USE " . constant(\'%s\')))', $conn, $db);
        } else {
            $ret = sprintf('((bool)mysqli_query(%s, "USE %s"))', $conn, $db);
        }
        
        return array('mysql_select_db(string database_name [...]) is emulated using mysqli_query() and USE database_name. This is a possible SQL injection security bug as no tests are performed what value database_name has. Check your script!', $ret);
    }
    
    
    function getConversionHint() {
        
        return 'Emulated using mysqli_query() and USE.';
    }


}
?>