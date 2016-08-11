<?PHP
/**
* GUI: convert a single file.
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
* @since      Available since Release 1.0
*/

if (isset($_POST['cancel'])) {
    // Cancel button
    header('Location: index.php');
    exit(0);
}

require_once 'snippets/MySQLConverterTool_GUI_Snippets.php';

$snippet_title = 'MySQL ext/mysql Converter Tool';
$snippet_greeting = 'Convert a file';
$snippet_nav_path = array($_SERVER['PHP_SELF'] => 'Convert a file');
MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/header.php');

if (empty($_POST) || !isset($_POST['start'])) {
    // show the form
    MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/form_file.php');
} else {
    // process the form
    $snippet_errors = array();
    if ('' == trim($_POST['file'])) {
        $snippet_errors['file'] = 'Please specify a file.';
    }

    if (!file_exists($_POST['file']) || !is_file($_POST['file']) || !is_readable($_POST['file'])) {
        $snippet_errors['file'] = 'Problems reading the file. Please verify that it exists and can be read.';
    }

    if (!empty($snippet_errors)) {
        // show the form
        MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/form_file.php');
    } else {
        // let's try to convert some files... 

        require_once '../Converter.php';
        $conv = new MySQLConverterTool_Converter();

        $snippet_file = $_POST['file'];
        $snippet_conv = $conv->convertFile($_POST['file']);

        if (isset($_POST['update']) && $_POST['update'] == 'yes') {
            if (isset($_POST['backup']) && $_POST['backup'] == 'on') {
                if (file_exists($snippet_file.'.org')) {
                    unlink($snippet_file.'.org');
                }
                $snippet_conv['rename'] = rename($snippet_file, $snippet_file.'.org');
            } else {
                $snippet_conv['rename'] = false;
            }
            $fp = fopen($snippet_file, 'w');
            if (!$fp) {
                $snippet_conv['update'] = false;
            } else {
                $snippet_conv['update'] = true;
                fwrite($fp, $snippet_conv['output']);
                fclose($fp);
            }
        } else {
            $snippet_conv['rename'] = false;
            $snippet_conv['update'] = false;
        }
        $snippet_show_details = true;

        MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/show_converted_file.php');
    }
}
MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/footer.php');
