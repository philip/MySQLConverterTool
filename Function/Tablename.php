<?php
require_once('Generic.php');

/**
* Converter: mysql_tablename
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
class MySQLConverterTool_Function_Tablename extends MySQLConverterTool_Function_Generic {

    public $new_name = 'mysqli_fetch_row';


    public function __construct() {
    }


    function handle(Array $params = array()) {

        // mysql_db_name ( resource result, int row [, mixed field] )
        // string mysql_tablename ( resource result, int i )    

        if (count($params) != 2)
            return array(self::PARSE_ERROR_WRONG_PARAMS, NULL);
            
        list($res, $i) = $this->extractParamValues($params);
               
        return array(NULL, sprintf('((mysqli_data_seek(%s, %s) && (($___mysqli_tmp = mysqli_fetch_row(%s)) !== NULL)) ? array_shift($___mysqli_tmp) : false)', $res, $i, $res));
    }

    
    function getConversionHint() {
        
        return 'Emulated using mysqli_data_seek(), mysqli_fetch_row() and a conditional expression.';
    }

}
?>