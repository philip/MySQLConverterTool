<?PHP
/**
* GUI: convert all files from a director.
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
$snippet_greeting = 'Convert all files from a directory';
$snippet_nav_path = array($_SERVER['PHP_SELF'] => 'Convert a directory');
MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/header.php');

if (empty($_POST) || !isset($_POST['start'])) {
    // show the form
    MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/form_directory.php');
} else {
    // process the form
    $snippet_errors = array();
    if ('' == trim($_POST['directory'])) {
        $snippet_errors['directory'] = 'Please specify a directory';
    }

    if ('' == trim($_POST['pattern'])) {
        $_POST['pattern'] = '*.*';
    }

    require_once '../Converter.php';
    $conv = new MySQLConverterTool_Converter();

    if (!empty($_POST['skip_pattern'])) {
        $conv->skip_pattern = trim($_POST['skip_pattern']);
    }

    $files = $conv->getFilesOfDirectory($_POST['directory'], $_POST['pattern']);

    if (empty($files)) {
        $snippet_errors['directory'] = 'No matching files found in the specified directory';
    }

    if (!empty($snippet_errors)) {
        // show the form
        MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/form_directory.php');
    } else {
        // let's try to convert some files...        

        $snippet_conv_found = 0;
        $snippet_conv_converted = 0;
        $snippet_conv_ok = 0;
        $snippet_conv_warnings = 0;
        $snippet_conv_errors = 0;
        $snippet_conv_count = count($files);
        $snippet_conv_length = 0;
        $outputs = array();
        foreach ($files as $k => $snippet_file) {
            $output = $conv->convertFile($snippet_file);
            $snippet_conv_found     += $output['found'];
            $snippet_conv_converted += $output['converted'];
            $snippet_conv_ok        += ($output['found'] == $output['converted']) && (count($output['errors']) == 0) ? 1 : 0;
            $snippet_conv_warnings  += ($output['found'] == $output['converted']) && (count($output['errors']) > 0) ? 1 : 0;
            $snippet_conv_errors    += ($output['found'] != $output['converted']) && (count($output['errors']) > 0) ? 1 : 0;
            $snippet_conv_length    += strlen($output['output']);

            if (isset($_POST['update']) && $_POST['update'] == 'yes') {
                if (isset($_POST['backup']) && $_POST['backup'] == 'on') {
                    if (file_exists($snippet_file.'.org')) {
                        unlink($snippet_file.'.org');
                    }
                    $output['rename'] = rename($snippet_file, $snippet_file.'.org');
                } else {
                    $output['rename'] = false;
                }
                $fp = fopen($snippet_file, 'w');
                if (!$fp) {
                    $output['update'] = false;
                } else {
                    $output['update'] = true;
                    fwrite($fp, $output['output']);
                    fclose($fp);
                }
            } else {
                $output['rename'] = false;
                $output['update'] = false;
            }

            $outputs[$snippet_file] = $output;
        }

        // Display some summary information...
        MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/show_converted_directory.php');

        // Display the results for every file
        $snippet_show_details = (count($files) == 1) ? true : false;
        foreach ($outputs as $snippet_file => $snippet_conv) {
            MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/show_converted_file.php');
        }
    }
}
MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/footer.php');
