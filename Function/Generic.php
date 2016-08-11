<?php
/**
* Converter: generic converter and base class for other converter functions.
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
class MySQLConverterTool_Function_Generic
{
    /**
     * @const    PARSE_ERROR_WRONG_PARAMS
     */
    const PARSE_ERROR_WRONG_PARAMS = 'Wrong parameter count. Function call not converted, resulting code is incomplete -- original code used. You must check your source!';

    //
    // public
    // 

    /**
     * New name of the converted function.
     *
     * Used only for functions that have MySQLi counterpart that takes the same
     * parameters as the originial MySQL extension function.
     *
     *
     * @var string
     */
    public $new_name;

    /**
     * Name of a variable holding the connection identifier of all functions that rely on default connections.
     *
     * @var string
     */
    public $ston_name = '$GLOBALS["___mysqli_ston"]';

    //
    // public
    //

    public function __construct($new_name, $ston_name = null)
    {
        $this->new_name = $new_name;
        if (!is_null($ston_name)) {
            $this->ston_name = $ston_name;
        }
    }

    /*
    * Try to use less concats - dots - in the output string
    *
    * @param    string
    * @param    string
    */
    public function smart_concat($par1, $par2)
    {
        if ($par2[0] !== '"' && $par2[0] !== "'") {
            return '"'.$par1.'" . '.$par2;
        }

        return $par2[0].$par1.substr($par2, 1);
    }

    /**
     * Returns a string with the functions call.
     *
     * @param    array   list of parameters of the returned function call
     */
    public function handle(array $params = array())
    {
        $params = $this->extractParamValues($params);

        return array(null, $this->new_name.'('.implode(', ', $params).')');
    }

    /**
     * Returns a string which describes who the conversion is done.
     *
     * @return string
     */
    public function getConversionHint()
    {
        return 'Using 1:1 counterpart from the MySQLi extension';
    }

    /**
     * Returns a PHP comment which describes how the conversion is done.
     *
     * @return string
     */
    public function getConversionPHPComment()
    {
        $ret = wordwrap($this->getConversionHint(), 60);
        $ret = explode("\n", $ret);
        $ret = "\n// [MySQL->MySQLi] ".implode("\n// [MySQL->MySQLi] ", $ret);
        $ret .= "\n";

        return $ret;
    }

    //
    // protected
    //

    /**
     * Extracts the parameter values from the parameter scanner hash.
     * 
     * @param    array
     *
     * @return array
     */
    protected function extractParamValues($params)
    {
        $ret = array();
        foreach ($params as $k => $param) {
            $ret[] = $param['value'];
        }

        return $ret;
    }

    /**
     * 
     */
    protected function extractValueAndType($var)
    {
        $var_type = 'const';
        if ('' != $var && ($var[0] == '"' || $var[0] == "'") && $var[0] == substr($var, -1)) {
            $var = substr($var, 1, -1);
            $var_type = 'string_or_var';
        }
        if ('' != ($tmp = trim($var)) && $tmp[0] == '$') {
            $var_type = 'string_or_var';
        }

        return array($var, $var_type);
    }
}
