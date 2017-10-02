<?PHP

require_once 'Function/ChangeUser.php';
require_once 'Function/Connect.php';
require_once 'Function/ConnParam.php';
require_once 'Function/ConnParamBool.php';
require_once 'Function/CreateDB.php';
require_once 'Function/DBQuery.php';
require_once 'Function/DropDB.php';
require_once 'Function/Error.php';
require_once 'Function/EscapeString.php';
require_once 'Function/FetchField.php';
require_once 'Function/FieldFlags.php';
require_once 'Function/FieldLen.php';
require_once 'Function/FieldName.php';
require_once 'Function/FieldTable.php';
require_once 'Function/FieldType.php';
require_once 'Function/FreeResult.php';
require_once 'Function/Generic.php';
require_once 'Function/GenericBoolean.php';
require_once 'Function/ListDBs.php';
require_once 'Function/ListFields.php';
require_once 'Function/ListProcesses.php';
require_once 'Function/ListTables.php';
require_once 'Function/ParReversed.php';
require_once 'Function/RealEscapeString.php';
require_once 'Function/Result.php';
require_once 'Function/SelectDB.php';
require_once 'Function/SetCharset.php';
require_once 'Function/Tablename.php';
require_once 'Function/UnbufferedQuery.php';

/**
 * ext/mysql->ext/mysqli Converter.
 *
 * @category   Converter
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
class MySQLConverterTool_Converter
{
    // 
    // protected
    //

    /**
     * Scanner state constant.
     * 
     * @const
     */
    const STATE_NORMAL = 'normal_state';

    /**
     * Scanner state constant.
     *
     * @const
     */
    const STATE_FUNC_FOUND = 'func_found';

    /**
     * Scanner state constant.
     *
     * @const
     */
    const STATE_PARAM_LIST = 'parameter_list';

    /**
     * Converted (= resulting) source code.
     * 
     * @var string
     */
    protected $output = '';

    /**
     * List of errors which occured during the conversion.
     *
     * @var array
     */
    protected $errors = array();

    /**
     * List of PHP token.
     *
     * @var string
     */
    protected $tokens = array();

    /**
     * Mapping of ext/mysql -> ext/mysqli constants.
     */
    protected $mysql_ext_consts = array(
        'MYSQL_CLIENT_COMPRESS' => 'MYSQLI_CLIENT_COMPRESS',
        'MYSQL_CLIENT_IGNORE_SPACE' => 'MYSQLI_CLIENT_IGNORE_SPACE',
        'MYSQL_CLIENT_INTERACTIVE' => 'MYSQLI_CLIENT_INTERACTIVE',
        'MYSQL_CLIENT_SSL' => 'MYSQLI_CLIENT_SSL',
        'MYSQL_ASSOC' => 'MYSQLI_ASSOC',
        'MYSQL_BOTH' => 'MYSQLI_BOTH',
        'MYSQL_NUM' => 'MYSQLI_NUM',
    );

    /**
     * Number of functions found in the given source.
     * 
     * @var int
     */
    protected $funcs_found = 0;

    /**
     * Number of functions converted in the given source.
     *
     * @var int
     */
    protected $funcs_converted = 0;

    /**
     * List of functions that are not supported.
     *
     * @var array
     */
    protected $mysql_funcs_not_supported = array(
            // rewrite the ugly mysql_result calls manually!
            // 'mysql_result'          => true,
            // whatever that function is...
            'mysql_fetch_field2' => true,
        );

    /**
     * List of functions that can be converted.
     *
     * Acts as kind of a function pointer list, but with objects
     *
     * @var array
     */
    protected $mysql_funcs = array();

    /**
     * Flag: enable token debug output?
     *
     * @var bool
     */
    protected $debug_dump_tokens = false;

    /**
     * Flag: enable parameter parsing debug output?
     * 
     * @var bool
     */
    protected $debug_dump_params = false;

    /**
     * Current line number during the parsing process.
     *
     * @var int
     */
    protected $lineno = 0;

    //
    // public
    //

    /**
     * File pattern to skip when scanning a directory
     * Pass in . to skip files that begin with ., or a full pattern 
     * for preg_match including delimiters.
     *
     * @var string
     */
    public $skip_pattern = false;

    /**
     * @param    bool    Turn on debug output of scanner token?
     * @param    bool    Turn on debug output of function parameters?
     */
    public function __construct($debug_token = false, $debug_params = false)
    {
        if (!function_exists('token_name')) {
            // HACK FIXME
            die('ext/tokenizer is needed!\n');
        }

        if (!defined('T_ML_COMMENT')) {
            define('T_ML_COMMENT', T_COMMENT);
        }

        if (!defined('T_DOC_COMMENT')) {
            define('T_DOC_COMMENT', T_ML_COMMENT);
        }

        if (!defined('T_SIMPLE_TOKEN')) {
            define('T_SIMPLE_TOKEN', 32567);
        }

        $this->debug_dump_tokens = $debug_token;
        $this->debug_dump_params = $debug_params;

        $this->mysql_funcs =
            array(
                // PHP_FALIAS(mysql,				mysql_db_query,		NULL)
                'mysql' => new MySQLConverterTool_Function_DBQuery(),
                // in OO this is a property
                'mysql_affected_rows' => new MySQLConverterTool_Function_ConnParam('mysqli_affected_rows'),
                'mysql_change_user' => new MySQLConverterTool_Function_ChangeUser(),
                'mysql_client_encoding' => new MySQLConverterTool_Function_ConnParam('mysqli_character_set_name'),
                'mysql_close' => new MySQLConverterTool_Function_ConnParamBool('mysqli_close'),
                // pconnect has less params but they are not significant for mysqli_connect                
                'mysql_connect' => new MySQLConverterTool_Function_Connect(),
                // PHP_FALIAS(mysql_createdb,		mysql_create_db,	NULL)
                'mysql_createdb' => new MySQLConverterTool_Function_CreateDB(),
                'mysql_create_db' => new MySQLConverterTool_Function_CreateDB(),
                'mysql_data_seek' => new MySQLConverterTool_Function_Generic('mysqli_data_seek'),
                'mysql_dbname' => new MySQLConverterTool_Function_Tablename(),
                'mysql_db_name' => new MySQLConverterTool_Function_Tablename(),
                'mysql_db_query' => new MySQLConverterTool_Function_DBQuery(),
                // PHP_FALIAS(mysql_dropdb,		mysql_drop_db,		NULL)
                'mysql_dropdb' => new MySQLConverterTool_Function_DropDB(),
                'mysql_drop_db' => new MySQLConverterTool_Function_DropDB(),
                // in OO this is a property
                'mysql_error' => new MySQLConverterTool_Function_Error('mysqli_error'),
                // in OO this is a property
                'mysql_errno' => new MySQLConverterTool_Function_Error('mysqli_errno'),
                'mysql_escape_string' => new MySQLConverterTool_Function_EscapeString('mysqli_real_escape_string'),
                'mysql_fetch_array' => new MySQLConverterTool_Function_Generic('mysqli_fetch_array'),
                'mysql_fetch_assoc' => new MySQLConverterTool_Function_Generic('mysqli_fetch_assoc'),
                'mysql_fetch_field' => new MySQLConverterTool_Function_FetchField(),
                'mysql_fetch_lengths' => new MySQLConverterTool_Function_GenericBoolean('mysqli_fetch_lengths'),
                'mysql_fetch_object' => new MySQLConverterTool_Function_Generic('mysqli_fetch_object'),
                'mysql_fetch_row' => new MySQLConverterTool_Function_Generic('mysqli_fetch_row'),
                // PHP_FALIAS(mysql_fieldflags,	mysql_field_flags,	NULL)
                'mysql_fieldflags' => new MySQLConverterTool_Function_FieldFlags(),
                'mysql_field_flags' => new MySQLConverterTool_Function_FieldFlags(),
                // PHP_FALIAS(mysql_fieldlen,		mysql_field_len,	NULL)
                'mysql_fieldlen' => new MySQLConverterTool_Function_FieldLen(),
                'mysql_field_len' => new MySQLConverterTool_Function_FieldLen(),
                // PHP_FALIAS(mysql_fieldname,		mysql_field_name,	NULL)
                'mysql_fieldname' => new MySQLConverterTool_Function_FieldName(),
                'mysql_field_name' => new MySQLConverterTool_Function_FieldName(),
                'mysql_field_seek' => new MySQLConverterTool_Function_GenericBoolean('mysqli_field_seek'),
                // PHP_FALIAS(mysql_fieldtable,	mysql_field_table,	NULL)
                'mysql_fieldtable' => new MySQLConverterTool_Function_FieldTable(),
                'mysql_field_table' => new MySQLConverterTool_Function_FieldTable(),
                // PHP_FALIAS(mysql_fieldtype,		mysql_field_type,	NULL)
                'mysql_fieldtype' => new MySQLConverterTool_Function_FieldType(),
                'mysql_field_type' => new MySQLConverterTool_Function_FieldType(),
                // PHP_FALIAS(mysql_freeresult,	mysql_free_result,	NULL)
                'mysql_freeresult' => new MySQLConverterTool_Function_FreeResult(),
                'mysql_free_result' => new MySQLConverterTool_Function_FreeResult(),
                'mysql_get_client_info' => new MySQLConverterTool_Function_Generic('mysqli_get_client_info'),
                // in OO this is a property
                'mysql_get_host_info' => new MySQLConverterTool_Function_ConnParamBool('mysqli_get_host_info'),
                // in OO this is a property
                'mysql_get_proto_info' => new MySQLConverterTool_Function_ConnParamBool('mysqli_get_proto_info'),
                // in OO this is a property
                'mysql_get_server_info' => new MySQLConverterTool_Function_ConnParamBool('mysqli_get_server_info'),
                'mysql_info' => new MySQLConverterTool_Function_ConnParam('mysqli_info'),
                'mysql_insert_id' => new MySQLConverterTool_Function_ConnParamBool('mysqli_insert_id'),
                // PHP_FALIAS(mysql_listdbs,		mysql_list_dbs,		NULL)
                'mysql_listdbs' => new MySQLConverterTool_Function_ListDBs(),
                'mysql_list_dbs' => new MySQLConverterTool_Function_ListDBs(),
                // PHP_FALIAS(mysql_listfields,	mysql_list_fields,	NULL)
                'mysql_listfields' => new MySQLConverterTool_Function_ListFields(),
                'mysql_list_fields' => new MySQLConverterTool_Function_ListFields(),
                'mysql_list_processes' => new MySQLConverterTool_Function_ListProcesses(),
                // PHP_FALIAS(mysql_listtables,	mysql_list_tables,	NULL)
                'mysql_listtables' => new MySQLConverterTool_Function_ListTables(),
                'mysql_list_tables' => new MySQLConverterTool_Function_ListTables(),
                // PHP_FALIAS(mysql_numfields,		mysql_num_fields,	NULL)
                'mysql_numfields' => new MySQLConverterTool_Function_Generic('mysqli_num_fields'),
                // in OO this is a property
                'mysql_num_fields' => new MySQLConverterTool_Function_GenericBoolean('mysqli_num_fields'),
                // PHP_FALIAS(mysql_numrows,		mysql_num_rows,		NULL)
                'mysql_numrows' => new MySQLConverterTool_Function_Generic('mysqli_num_rows'),
                // in OO this is a property
                'mysql_num_rows' => new MySQLConverterTool_Function_Generic('mysqli_num_rows'),
                // pconnect has less params but they are not significant for mysqli_connect
                'mysql_pconnect' => new MySQLConverterTool_Function_Connect(),
                'mysql_ping' => new MySQLConverterTool_Function_ConnParam('mysqli_ping'),
                'mysql_query' => new MySQLConverterTool_Function_ParReversed('mysqli_query'),
                'mysql_real_escape_string' => new MySQLConverterTool_Function_RealEscapeString('mysqli_real_escape_string'),
                // mysql_result -- Get result data
                'mysql_result' => new MySQLConverterTool_Function_Result(),
                // PHP_FALIAS(mysql_selectdb,		mysql_select_db,	NULL)
                'mysql_selectdb' => new MySQLConverterTool_Function_SelectDB(),
                'mysql_select_db' => new MySQLConverterTool_Function_SelectDB(),
                'mysql_set_charset' => new MySQLConverterTool_Function_SetCharset(),
                'mysql_stat' => new MySQLConverterTool_Function_ConnParam('mysqli_stat'),
                'mysql_tablename' => new MySQLConverterTool_Function_Tablename(),
                'mysql_table_name' => new MySQLConverterTool_Function_Tablename(),
                'mysql_thread_id' => new MySQLConverterTool_Function_ConnParam('mysqli_thread_id'),
                'mysql_unbuffered_query' => new MySQLConverterTool_Function_UnbufferedQuery(),
            );
    } // end func __construct

    /**
     * Converts the given source code.
     *
     * @param    string  Source code to be converted
     *
     * @return array Converted source code and status information
     */
    public function convertString($source)
    {
        $source = $this->lowerCaseMysqlFunctions($source);

        $this->lineno = 0;
        $this->output = '';
        $this->tokens = token_get_all($source);
        $this->errors = array();
        $this->lineno = 1;

        $this->funcs_found = 0;
        $this->funcs_converted = 0;

        $this->scanner(0);

        return array(
            'output' => $this->output,
            'found' => $this->funcs_found,
            'converted' => $this->funcs_converted,
            'errors' => $this->errors,
         );
    } // end func convertString

    /**
     * Reads a file and returns the converted source code.
     * 
     * @param    strng   filename
     * @param    array   Converted source and status information
     */
    public function convertFile($filename)
    {
        if (!file_exists($filename) || !is_readable($filename) || !is_file($filename)) {
            $ret = $this->convertString('');
            $ret['errors'][] = array('line' => -1, 'msg' => 'Cannot open file "'.$filename.'".');

            return $ret;
        }

        $c = file_get_contents($filename);

        $code = $this->lowerCaseMysqlFunctions($c);

        return $this->convertString($code);
    }

    /**
     * Convert MySQL functions to lowercase.
     *
     * @param string source
     */
    public function lowerCaseMysqlFunctions($source)
    {

        // Convert all uppercase and mixed case names to lowercase
        // like MYSQL_RESULT => mysql_result
        // But this can be dangerous because of possible unwanted constants conversions
        // To be safe we convert only supported names
        foreach ($this->mysql_funcs as $func_name => $val) {
            $patterns[]     = '#\b'. $func_name . '\b#i';
            $replacements[] = strtolower($func_name);
        }
        return preg_replace($patterns, $replacements, $source);
    }

    /**
     * Returns all files of a directory that have a certain name.
     */
    public function getFilesOfDirectory($dir, $file_pattern = '*', array $files = array())
    {
        if (!is_dir($dir) || !is_readable($dir)) {
            return $files;
        }

        $patterns = $this->buildRegularExpression($file_pattern);
        if (empty($patterns)) {
            return $files;
        }

        $dh = opendir($dir);
        if (!$dh) {
            return $files;
        }

        while ($file = readdir($dh)) {
            $file = trim($file);
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if ($this->skip_pattern !== false) {
                if ($this->skip_pattern === '.') {
                    if ($file{0} === '.') {
                        continue;
                    }
                } else {
                    if (preg_match('@'.$this->skip_pattern.'@', $file)) {
                        continue;
                    }
                }
            }
            $ffile = $dir.'/'.$file;
            if (is_dir($ffile)) {
                $files = $this->getFilesOfDirectory($ffile, $file_pattern, $files);
            }

            if (!is_file($ffile)) {
                continue;
            }

            $accept = false;
            foreach ($patterns as $k => $pattern) {
                if (preg_match('@'.$pattern.'$@i', $file)) {
                    $accept = true;
                    break;
                }
            }

            if ($accept) {
                $files[] = $ffile;
            }
        }
        closedir($dh);

        return $files;
    }

    /**
     * Unsets the variable which stores the global (default) connection of ext/mysql.
     *
     * This is a hack required by the test framework.
     */
    public function unsetGlobalConnection()
    {
        $obj = new MySQLConverterTool_Function_Generic('mysqli_query');
        eval(sprintf('@mysqli_close(%s);', $obj->ston_name));
        eval(sprintf('if (isset(%s)) unset(%s);', $obj->ston_name, $obj->ston_name));
    }

    /**
     * Returns a list of functions that can be converted automatically.
     *
     * @return array
     */
    public function getSupportedFunctions()
    {
        $ret = array_keys($this->mysql_funcs);
        sort($ret);

        return $ret;
    }

    /**
     * Returns a list of functions that cannot be converted automatically.
     *
     * @return array
     */
    public function getUnsupportedFunctions()
    {
        $ret = array_keys($this->mysql_funcs_not_supported);
        sort($ret);

        return $ret;
    }

    //
    // protected
    //

    /**
     * Scans the source and rebuilds a converted version of it.
     *
     * @param    int
     */
    protected function scanner($level)
    {

        // current parser state
        $state = self::STATE_NORMAL;

        // list of function parameters
        $func_params = array();
        // current parameter value
        $curr_param = '';
        // current bracket nesting level
        $bracket_level = 0;
        // name of the currently processed function
        $func_name = '';
        // flag: does the currently parameter contain variables or constants?
        $param_dynamic = false;
        // flag for something that looks like a mysql_func call but is not followed by parameters
        $expect_param_brace_open = false;

        while (list(, $token) = each($this->tokens)) {
            if (is_string($token)) {
                $id = T_SIMPLE_TOKEN;
                $token_name = 'T_SIMPLE_TOKEN';
                $text = $token;
            } else {
                list($id, $text) = $token;
                $token_name = token_name($id);
            }

            // remember the current line number
            $lineno = $this->lineno;
            $this->lineno += $this->countLines($text);

            if ($this->debug_dump_tokens) {
                $this->debug_print('dump token',
                    sprintf('[%-05d - %10s - %d - %-10s - %03d - %05d] %s',
                        $lineno,
                        $token_name,
                        $expect_param_brace_open,
                        $state,
                        $bracket_level,
                        strlen($text),
                        htmlspecialchars($text))
                );
            }

            switch ($id) {

            case T_COMMENT:
            case T_ML_COMMENT:
            case T_DOC_COMMENT:

                switch ($state) {
                case self::STATE_NORMAL:
                    $this->output .= $text;
                    break;

                case self::STATE_FUNC_FOUND:
                    // comment between func_name and comma
                    break;

                case self::STATE_PARAM_LIST:
                    $curr_param .= $text;
                    break;
                }

                break;

            case T_SIMPLE_TOKEN:

                switch ($state) {

                case self::STATE_NORMAL:
                    $this->output .= $text;
                    break;

                case self::STATE_FUNC_FOUND:

                    switch ($text) {
                    case '(':
                        $state = self::STATE_PARAM_LIST;
                        $func_params = array();
                        $curr_param = null;
                        $param_dynamic = false;
                        $expect_param_brace_open = false;
                        break;

                    case ')':
                    default:
                        if ($expect_param_brace_open) {
                            // something that looks like <mysql_func[...]> is not followed by an opening brace for <mysql_func([...]> 
                            $state = self::STATE_NORMAL;
                            --$this->funcs_found;
                            $expect_param_brace_open = 0;
                            $steps = 0;
                            $tmp = '';
                            while (($token = prev($this->tokens)) && ($tmp != $func_name)) {
                                if (is_array($token)) {
                                    $tmp = $token[1];
                                } else {
                                    $tmp = $token;
                                }
                                ++$steps;
                            }
                            while ($steps-- > 1) {
                                $token = next($this->tokens);
                                if (is_array($token)) {
                                    $tmp = $token[1];
                                } else {
                                    $tmp = $token;
                                }
                                $this->output .= $tmp;
                            }
                            next($this->tokens);
                            break 3;
                        }
                        break;
                    }
                    break;

                case self::STATE_PARAM_LIST:

                    switch ($text) {
                    case ',':
                        if ($bracket_level == 1) {
                            $func_params[] = array('value' => $curr_param, 'dynamic' => $param_dynamic);
                            $curr_param = '';
                            $param_dynamic = false;
                        } else {
                            $curr_param .= $text;
                        }
                        // don't add to the curr_param                        
                        break(2);

                    case '(':
                        $bracket_level++;
                        break;

                    case ')':
                        $bracket_level--;
                        if ($bracket_level == 0) {
                            // end of the parameter specification

                            if (!is_null($curr_param)) {
                                $func_params[] = array('value' => $curr_param, 'dynamic' => $param_dynamic);
                            }

                            $curr_param = null;
                            $param_dynamic = false;
                            $state = self::STATE_NORMAL;

                            if ($this->debug_dump_params) {
                                $this->debug_print(
                                    'params',
                                    array('name' => $func_name, 'params' => $func_params)
                                );
                            }

                            if (!$level) {
                                list($handler_warning, $handler_code) = $this->mysql_funcs[$func_name]->handle($func_params);
                                if (!is_null($handler_warning)) {
                                    $this->errors[] = array('line' => $lineno, 'msg' => sprintf('[Line %d] %s', $lineno, $handler_warning));
                                }

                                if (is_null($handler_code)) {
                                    $this->output .= $this->rebuildFunctionCode($func_name, $func_params, $handler_warning);
                                } else {
                                    ++$this->funcs_converted;
                                    $this->output .= $handler_code;
                                }
                            } else {
                                // we are in recursive call - return

                                list($handler_warning, $handler_code) = $this->mysql_funcs[$func_name]->handle($func_params);
                                if (!is_null($handler_warning)) {
                                    $this->errors[] = array('line' => $lineno, 'msg' => sprintf('[Line %d] %s', $lineno, $handler_warning));
                                }

                                if (is_null($handler_code)) {
                                    return $this->rebuildFunctionCode($func_name, $func_params, $handler_warning);
                                } else {
                                    ++$this->funcs_converted;

                                    return $handler_code;
                                }
                            }
                        }
                        break;

                    case '.':
                    case '&':
                    case '^':
                    case '|':
                    case '&&':
                    case '||':
                    case 'and':
                    case 'or':
                    case 'xor':
                    case '<<':
                    case '>>':
                    case '<':
                    case '<=':
                    case '>':
                    case '>=':
                    case '=':
                    case '*':
                    case '/':
                    case '%':
                    case '+':
                    case '-':
                    case '++':
                    case '--':
                    case '!':
                    case '~':
                    case '=':
                    case '+=':
                    case '-=':
                    case '*=':
                    case '/=':
                    case '.=':
                    case '%=':
                    case '&=':
                    case '|=':
                    case '^=':
                    case '<<=':
                    case '>>=':
                    case '?':
                    case '==':
                    case '!=':
                    case '===':
                    case '!==':
                    case '(':
                    case 'new':
                        // forget it - this parameter value is build dynamically
                        // we won't try to guess it's value using static code analysis
                        // this needs to be checked manually
                        $param_dynamic = true;
                        if ($this->debug_dump_params) {
                            $this->debug_print(
                                    'params',
                                    'Operator found in parameter. Assuming that the paramter value will be dynamic and cannot be foreseen.'
                                );
                        }
                        break;
                    }

                    $curr_param .= $text;
                    break;
                }
                break;

            case T_STRING:

                if (isset($this->mysql_ext_consts[$text])) {
                    $text = $this->mysql_ext_consts[$text];
                } else {
                    switch ($state) {
                    case self::STATE_PARAM_LIST:
                        // maybe a function call or constant as part of a parameter value?

                        if (isset($this->mysql_funcs_not_supported[$text])) {
                            $this->errors[] = array('line' => $lineno, 'msg' => sprintf('[Line %d] Function "%s" is not supported for conversion! You must rewrite the converted code. The conversion is not complete. Consider rewriting your code first and rerunning the conversion tool"', $lineno, $text));
                        }

                        if (isset($this->mysql_funcs[$text])) {
                            // return the current token and reparse it
                            prev($this->tokens);
                            // in the default of the outter switch add this to $curr_param
                            $text = $this->scanner($level + 1);
                            $param_dynamic = true;
                        }
                        // fall to default                        
                        break;

                    case self::STATE_FUNC_FOUND:

                        $this->errors[] = array('line' => $lineno, 'msg' => sprintf('[Line %d] We found a string between the function name "%s" and the parameter list. Looks like a syntax error in the source file. Please check!', $lineno, $func_name));

                        break;

                    case self::STATE_NORMAL:
                    default:

                        if (isset($this->mysql_funcs_not_supported[$text])) {
                            $this->errors[] = array('line' => $lineno, 'msg' => sprintf('[Line %d] Function "%s" is not supported for conversion! You must rewrite the converted code code. The conversion is not complete. Consider rewriting your code first and rerunning the conversion tool."', $lineno, $text));
                        }

                        if (isset($this->mysql_funcs[$text])) {
                            $state = self::STATE_FUNC_FOUND;
                            $func_name = $text;
                            $bracket_level = 1;
                            ++$this->funcs_found;
                            $expect_param_brace_open = true;

                            break(2);
                        }

                        if ($text == 'is_resource') {
                            $this->errors[] = array('line' => $lineno, 'msg' => sprintf('[Line %d] You are using is_resource(). Make sure that you do not use it to test for the return value of mysql_query(). mysql_query() returns a resource or a boolean value. mysqli_query() returns an object or a boolean value. If you check the return value of mysql_query like < if (is_resource($res = mysql_query())) [...] > the converted code will fail, because $res will be an object in ext/mysqli."', $lineno, $text));
                        }
                        // here we fall-through
                    }
                }

            default:

                if ($id === T_CONSTANT_ENCAPSED_STRING) {
                    /* try to detect function_exists('some_mysql_func'); */
                    $prev_steps = 0;

                    if (isset($this->mysql_funcs[substr($text, 1, -1)]) &&
                        $this->mysql_funcs[substr($text, 1, -1)]->new_name) {
                        // strip quotes
                        // move back because now 'some_mysql_func' will be returned by prev()
                        $prev_steps = 1;
                        $tmp_token = prev($this->tokens);

                        do {
                            $tmp_token = prev($this->tokens);
                            ++$prev_steps;
                        } while ($tmp_token[0] === T_WHITESPACE);

                        if ($tmp_token === '(') {
                            $tmp_token = prev($this->tokens);
                            ++$prev_steps;

                            if ($tmp_token[0] === T_STRING &&
                                $tmp_token[1] === 'function_exists') {
                                $text = $text[0].$this->mysql_funcs[substr($text, 1, -1)]->new_name.$text[0];
                            }
                        }

                        while ($prev_steps--) {
                            each($this->tokens);
                        }
                    }
                }

                switch ($state) {
                case self::STATE_PARAM_LIST:

                    switch ($id) {

                    case T_VARIABLE:
                    case T_FUNCTION:
                    case T_CONST:
                        // this is a variable, we cannot guess value of the parameter
                        // using this static conversion approach
                        if ($this->debug_dump_params) {
                            $this->debug_print(
                                    'params',
                                    "Adding variable '$text' to current parameter."
                                );
                        }
                        $param_dynamic = true;
                        $curr_param .= $text;
                        break;

                    case T_WHITESPACE:
                    default:
                        if ($this->debug_dump_params) {
                            $this->debug_print(
                                    'params',
                                    "Adding '$text' to current parameter."
                                );
                        }

                        $curr_param .= $text;
                        break;
                    }
                    break;

                case self::STATE_FUNC_FOUND:

                    $this->errors[] = array('line' => $lineno, 'msg' => sprintf('[Line %d] Please check your code for parse errors, we failed to parse "%s". Conversion will be incomplete!".', $lineno, $text));
                    break;

                default:
                case self::STATE_NORMAL:
                    $this->output .= $text;
                    break;
                }
                break;
            }
        }
    }

    /**
     * Print a debug message.
     *
     * @param    string  Message prefix
     * @param    mixed   Array or literal to print
     */
    protected function debug_print($prefix, $var)
    {
        if (is_scalar($var)) {
            printf("[%s/%s] %s\n",
                get_class($this),
                $prefix,
                $var);
        } else {
            printf('[%s/%s] ', get_class($this), $prefix);
            var_dump($var);
            printf("\n");
        }
    } // end func debug_print

    /**
     * Returns the number of lines of a string.
     *
     * @param    string  
     *
     * @return int
     */
    protected function countLines($text)
    {
        $last_pos = 0;
        $lines = 0;
        while (($pos = strpos($text, "\n", $last_pos)) !== false) {
            $last_pos = $pos + 1;
            ++$lines;
        }

        return $lines;
    }

    /**
     * Rebuilds the original function code based on function name and parameters and adds a comment with a warning after the last parameter.
     *
     * @param    string  
     * @param    array   
     * @param    string  
     */
    protected function rebuildFunctionCode($name, $params, $warning)
    {
        $ret = $name.'(';
        if (count($params) > 0) {
            foreach ($params as $k => $param) {
                $ret .= $param['value'].', ';
            }
            $ret = substr($ret, 0, -2);
        }
        $ret .= ')';
        // $ret .= ' /* [MySQLConverterTool] ' . $warning . '*/)';

        return $ret;
    }

    /**
     * Expands a user specified file pattern with * as the only pattern to a regular expression.
     *
     * @param    string
     *
     * @return array
     */
    protected function buildRegularExpression($file_pattern)
    {
        $parts = explode(',', $file_pattern);
        if (empty($parts)) {
            return array();
        }

        $ret = array();
        foreach ($parts as $k => $v) {
            $ret[] = str_replace('\*', '.*', preg_quote($v, '@'));
        }

        return $ret;
    }
}
