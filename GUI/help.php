<?PHP
/**
* GUI: help.
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

$snippet_title = 'MySQL ext/mysql Converter Tool';
$snippet_greeting = 'Help';
$snippet_nav_path = array($_SERVER['PHP_SELF'] => 'Help');
MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/header.php');
?>
<h3>About</h3>
<p>
This is the MySQL ext/mysql Converter Tool. The tool can be used to convert PHP
files using the PHP MySQL extension (ext/mysql) to make the scripts use the PHP MySQLi extension.
The PHP MySQL extension has been designed to work with the MySQL Server up to version 4.1.0. 
Some features introduced later, for example prepared statements, are not supported by the extension.
To use these features, you have to use the PHP MySQLi extension. This tool helps you to convert
your existing scripts from the old MySQL extension to the new MySQLi extension.
</p>
<p>
The tool uses a simple approach to map mysql_*-functions to their mysqli_*-counterparts. 
All mysql_*-functions can be converted automatically. The generated code will run out of 
the box. However, the tool does not take context and runtime information into account 
when it does the conversion. This can lead to situations where the tool is not sure if the 
generated code is semantically identical to the original code in all ways. If this happens, 
a warning will the thrown and you will be requested to check the automatically generated tool
</p>
<p>
The tool has been originally developed by MySQL AB. It is released under the terms of the 
<a href="http://www.php.net/license/3_0.txt">PHP Licence 3.0</a>. It is no longer maintained
by MySQL, but lives on as a public github repository.
</p>
<h3>Where can I get more information?</h3>
<p>
Please consult the <a href="https://github.com/philip/MySQLConverterTool/wiki">wiki</a> for additional information.
</p>
<h3>What cannot be converted automatically?</h3>
<p>
Use the function MySQLConvertTool_Converter::getUnsupportedFunctions() to get a list of all 
functions which cannot be converted automatically. Currently, the tool does not support 
the conversion of the following, rarely used functions:
<ul>
    <li>mysql_result() -- Note: it converts to mysqli_result() and suggests a mysqli_result() userland definition, 
        as the mysqli extension does not have a true alternative to mysql_result().</li>
    <li>mysql_fetch_field2()</li>
</ul>
All other mysql_*-functions can be converted automatically.
</p>
<h3>What else does this do that might affect me?</h3>
<ul>
    <li>All functions are converted to lowercase</li>
    <li>If your PHP version has short tags disabled, code using short tags will be skipped. 
        This is a limitation of the tokenizer extension</li>
</ul>
<h3>What is considered to be a warning?</h3>
<p>
The converter tool works stateless. It does not take any context or runtime information into account.
It does nothing but analyze existing mysql_*-functions and tries to translate them into their 
mysqli_*-counterparts. Most expressions can be translated into semantically identical expressions
using this approach. But there are limits. Whenever such a limit gets hit, the tool throws
an error and asks you to check your code manually.
</p>
<p>A simple example of an expression which cannot be converted without context information is mysql_error(). 
Consider the following PHP code:
</p>
<p>
<code> 
<pre>
if (!mysql_connect())
    die(sprintf("[%d] %s\n", mysql_errno(), mysql_error()));
</pre>    
</code>
</p>
<p>
The author of the code relies on the default connection feature. mysql_errno() can be called with or without a
link identifier. The function accepts one optional parameter:
</p>
<p>
<code>
<pre>
mysql_errno ( [resource link_identifier] )
</pre>
</code>
</p>
<p>
The mysqli_errno() counterpart of the MySQLi extension must be provided with a link identifier.
</p>
<p>
<code>
<pre>
mysqli_errno ( mysqli link )
</pre>
</code>
</p>
<p>
The tool is clever enough to store the return value of mysql_connect() [mysqli_connect()] in a global variable
and pass the global variable to the mysqli_errno() function call. But if mysqli_connect() fails and does not 
return a link identifier, mysqli_errno() will be called with an invalid parameter. Again, the conversion tool
is clever enough to add a conditional expression to ensure that the generated code behaves like the original
code, but this is considered as a "hack" and a "warning" will be thrown. The warning tells you to check manually
that the generated code is semantically identical to the original code. You could, for example, 
have a mysql_errno() somewhere in your code without a preceding mysql_connect(). That does not make too much sense,
but it could be. As the converter tool does not consider any state and context information it cannot detect
such problems. When the mysql_errno() call gets converted no information is available if it is preceded by a mysql_connect()
call or not.
</p>
<p>
Yes, we think it is worth to throw a warning. You can call it picky. Most of the warnings can be safely 
ignored. But we give no warranty. Check your code manually!
</p>
<p>
There are many more situations when a warning gets thrown. For example, mysql_fetch_field() will be replaced
by a semantically identical expression, but the replacement is a huge, ugly piece of code which you should streamline.
</p>
<h3>Can I safely ignore the warnings?</h3>
<p>
No, you are strongly requested to check the generated code manually. It is likely that the generated code
works as expected in most cases, but not in all cases! 
</p>
<h3>What is considered to be an error?</h3>
<p>
The tool considers a conversion as failed and all warnings as errors if the number of converted functions
differs from the number of mysql_*-functions found in the given PHP source.
</p>
<h3>Where can I learn more about limitations?</h3>
<p>
The converter tool comes with more than 50 "real-life" test cases. At least one test case exists for every 
mysql_-function which can be handled by the tool. If a test case contains code which cannot be 
converted automatically into a semantically identical mysqli*-function, the test case name is preceded with
the word "FAILURE". Check the test cases in the folder <code>UnitTests/Converter/TestCode</code>
for details.
</p>
<?php
MySQLConverterTool_GUI_Snippets::load(dirname(__FILE__).'/snippets/footer.php');
?>