<?PHP
/**
* GUI index page.
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
require_once 'snippets/MySQLConverterTool_GUI_Snippets.php';

$snippet_title = 'Welcome to the MySQL ext/mysql Converter Tool';
$snippet_greeting = 'Welcome to the MySQL ext/mysql Converter Tool';

MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/header.php');
?>
<div class="maintextbox">
    You have successfully installed the MySQL ext/mysql Converter Tool.
    The tool helps you to migrate existing PHP code using the PHP MySQL Extension
    (ext/mysql) to the PHP MySQLi Extension (ext/mysqli). The tool reads the source code
    and converts all MySQL functions into their MySQLi counterparts.
</div>
<div class="maintextbox">
    Select one of the following actions:
</div>
<div class="maintextbox" style="padding-left:1em">
    <a href="convert_directory.php"><h2>Convert a directory</h2></a>
    Use this to convert all PHP files contained in one
    directory.        
</div>
<div class="maintextbox" style="padding-left:1em">
    <a href="convert_file.php"><h2>Convert a file</h2></a>
    Use this to convert a single PHP file.
</div>
<div class="maintextbox" style="padding-left:1em">
    <a href="convert_snippet.php"><h2>Convert a code snippet</h2></a>
    Use this to convert a snippet, a piece of PHP code.
</div>    
<?php
MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/footer.php');
?>