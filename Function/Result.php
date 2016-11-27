<?php

require_once 'Generic.php';

/**
 * Converter: mysql_result.
 *
 * @category   Functions
 *
 * @author     Philip Olson <philip@php.net>
 */
class MySQLConverterTool_Function_Result extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_result';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {
        $pcount = count($params);

        if ($pcount != 2 && $pcount != 3) {
            return array(self::PARSE_ERROR_WRONG_PARAMS, null);
        }

        if ($pcount === 3) {
            list($res, $row, $field) = $this->extractParamValues($params);
            $warning = $this->getConversionHint();

            return array($warning, sprintf('mysqli_result(%s, %s, %s)', $res, $row, $field));
        }

        if ($pcount === 2) {
            list($res, $row) = $this->extractParamValues($params);
            $warning = $this->getConversionHint();

            return array($warning, sprintf('mysqli_result(%s, %s)', $res, $row));
        }
    }

    public function getConversionHint()
    {
        return 'Emulated by creating mysqli_result(). YOU must manually add a mysqli_result() definition to your code, such as:

        function mysqli_result($result, $number, $field=0) {
            mysqli_data_seek($result, $number);
            $type = is_numeric($field) ? MYSQLI_NUM : MYSQLI_ASSOC;
            $out = mysqli_fetch_array($result, $type);
            if ($out === NULL || $out === FALSE || (!isset($out[$field]))) {
                return FALSE;
            }
            return $out[$field];
        }
        ';
    }
}
