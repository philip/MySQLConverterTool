<?PHP
/**
* GUI: very simple "template" engine.
*
* @category   GUI
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
class MySQLConverterTool_GUI_Snippets
{
    public static function load($___file)
    {
        if (!file_exists($___file)) {
            echo $___file;

            return false;
        }

        foreach ($GLOBALS as $___k => $___v) {
            if (substr($___k, 0, 8) == 'snippet_') {
                $$___k = $___v;
            }
        }

        include $___file;

        return true;
    }
}
