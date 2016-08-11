<?php

require_once 'Generic.php';

/**
 * Converter: mysql_connect, mysql_pconnect.
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
class MySQLConverterTool_Function_Connect extends MySQLConverterTool_Function_Generic
{
    public $new_name = 'mysqli_connect';

    public function __construct()
    {
    }

    public function handle(array $params = array())
    {
        static $last_params = array();

        /*
        Known deficiencies:
        - will try to handle unix_socket but not the best solution        
        */

        // mysql_connect ( [string server [, string username [, string password [, bool new_link [, int client_flags]]]]] )                
        // mysql_pconnect ( [string server [, string username [, string password [, int client_flags]]]] )        
        // mysqli_connect ( [string host [, string username [, string passwd [, string dbname [, int port [, string socket]]]]]] )

        $warning = null;
        $socket = null;
        $port = null;

        @list($server, $user, $password, $new_link, $client_flags) = $this->extractParamValues($params);
        if (!is_null($server)) {
            if ($params[0]['dynamic']) {
                $warning = 'Cannot analyze server parameter to extract host, socket and port! Conversion cannot be performed automatically. You must manually check the result of the conversion.';
            } else {
                list($server, $socket, $port) = $this->expandServerParam($server);
            }
        }

        $found = false;
        if (is_null($new_link) || ($new_link == false)) {
            // Maybe someone relies on:
            // If a second call is made to mysql_connect()  with the same arguments, 
            // no new link will be established, but instead, the link identifier of the already 
            // opened link will be returned. The new_link parameter modifies this behavior 
            // and makes mysql_connect() always open a new link, even if mysql_connect() 
            // was called before with the same parameters.
            // The current converter version cannot catch this. All we can do is throw a warning

            foreach ($last_params as $k => $lparams) {
                if ($params == $lparams) {
                    $warning .= " You're calling mysql_connect() twice with the same parameters. We don't know for sure if you want a new connection or reuse the old connection. You must check your code.";
                    $found = true;
                    break;
                }
            }
        }
        if (!$found) {
            $last_params[] = $params;
        }

        $dbname = 'NULL';
        $ret = null;
        $num_params = count($params);

        if ($num_params < 5) {
            if (!is_null($socket)) {
                $ret = sprintf('(%s = %s(%s, %s, %s, %s, %d, \'%s\'))',
                      $this->ston_name,
                      $this->new_name,
                      is_null($server) ? 'NULL' : $server,
                      is_null($user) ? 'NULL' : $user,
                      is_null($password) ? 'NULL' : $password,
                      is_null($dbname) ? 'NULL' : $dbname,
                      is_null($port) ? 'NULL' : $port,
                      $socket);
            } else {
                if (is_null($server)) {
                    // mysql_connect()       
                    $ret = sprintf('(%s = %s())',
                        $this->ston_name,
                        $this->new_name);
                } elseif (!is_null($port)) {
                    // port used - no chance to generate pretty code

                    $ret = sprintf('(%s = %s(%s, %s, %s, %s, %d))',
                        $this->ston_name,
                        $this->new_name,
                        is_null($server) ? 'NULL' : $server,
                        is_null($user) ? 'NULL' : $user,
                        is_null($password) ? 'NULL' : $password,
                        is_null($dbname) ? 'NULL' : $dbname,
                        $port
                        );
                } elseif (!is_null($password)) {

                    // mysql_connect(<server>, <username>, <password>)
                    $ret = sprintf('(%s = %s(%s, %s, %s))',
                        $this->ston_name,
                        $this->new_name,
                        is_null($server) ? 'NULL' : $server,
                        is_null($user) ? 'NULL' : $user,
                        $password);
                } elseif (!is_null($user)) {
                    // mysql_connect(<server>, <username>
                    $ret = sprintf('(%s = %s(%s, %s))',
                        $this->ston_name,
                        $this->new_name,
                        is_null($server) ? 'NULL' : $server,
                        $user);
                } else {
                    // mysql_connect(<server>) but not mysql_connect(<server:port>)

                    $ret = sprintf('(%s = %s(%s))',
                        $this->ston_name,
                        $this->new_name,
                        $server);
                }
            }
        } elseif ($num_params == 5) {
            $ret = sprintf('(((%s = mysqli_init()) && (mysqli_real_connect(%s, %s, %s, %s, %s, %d, %s, %s))) ? %s : FALSE)',
                      $this->ston_name,
                      $this->ston_name,
                      $server,
                      $user,
                      $password,
                      $dbname,
                      is_null($port) ? 3306 : $port,
                      is_null($socket) ? 'NULL' : $socket,
                      $client_flags,
                      $this->ston_name);
        } else {
            // too many parameters

            $warning = self::PARSE_ERROR_WRONG_PARAMS;
            $ret = null;
        }

        return array($warning, $ret);
    }

    protected function expandServerParam($server)
    {
        $socket = null;
        $port = null;

        $pos = strpos($server, ':/');
        if (false !== $pos) {
            // Unix Socket

            $first_ch = $server[0];
            $last_ch = substr($server, -1, 1);

            if (($last_ch == '"' || $last_ch == "'") && $last_ch == $first_ch) {
                $server = substr($server, 1, -1);
            }

            $socket = substr($server, $pos + 1);
            $server = '"localhost"';
        } elseif (preg_match('/(["\']*)([^:]+):(\d+)/iu', $server, $matches)) {
            // host:port

            $server = $matches[1].$matches[2].$matches[1];
            $port = $matches[3];
        }

        return array($server, $socket, $port);
    }

    public function getConversionHint()
    {
        return 'mysql_connect can be mapped to mysqli_connect with some parameter swapping if no client_flags are used. If client_flags are used, mysqli_init()/mysqli_real_connect() are needed.';
    }
}
