# Introduction
The old MySQL extension (`ext/mysql`) was deprecated in PHP 5.5 and removed in PHP 7.0.

This tool converts `ext/mysql` code to `ext/mysqli` code, as `ext/mysqli` is the current MySQL extension. There's also `PDO_MySQL` but this tool does not use that. This tool is not perfect but will help with the conversion.

> **Alternatively**, a quicker and simpler short-term fix is to use a bundled native PHP library such as [php7-mysql-shim](https://github.com/dshafik/php7-mysql-shim). Instead of converting code (e.g., `mysql_connect()` to `mysqli_connect()`) it uses `ext/mysqli` to define `ext/mysql` functions in PHP. Much easier but more of a short-term fix. Although `php7-mysql-shim` contains PHP 7 in the name, it works with older PHP versions but only if `ext/mysql` is not installed on your system.

# Usage
There are two interfaces: GUI and CLI. Example usages:

### Using PHP's built-in web server
For example, in a terminal you might do this:
```
shell> wget https://github.com/philip/MySQLConverterTool/archive/master.zip
shell> unzip master.zip
shell> cd MySQLConverterTool-master/GUI
shell> php -S localhost:8000
```
Then, go to `http://localhost:8000`. From there you can choose to convert a snippet (copy-n-paste), a single file, or a directory. You can choose to convert the files themselves (assuming the web server has rights), make backups, or simply output the code to your browser.

### Use CLI
Download as you did above, and here is an example to convert a file named `/tmp/my.php` 
```
$ cd MySQLConverterTool-master
$ php cli.php -f /tmp/my.php
```
Execute `php cli.php -h` to see the available options.

# Important Warning
That absolutely NO security checks are performed by the tool which prevent users from trying to read and convert files (e.g. `/etc/passwd`). Make sure to add security measures, if needed.

# Additional Documentation
See the [wiki](https://github.com/philip/MySQLConverterTool/wiki) for additional information, including screenshots. 

# Limitations
With `short-open-tag` disabled, short tags (<? and <?=) are essentially ignored due to how the `tokenizer` extension works. So, if you use the likes of <? (instead of <?php) then enable `short-open-tag` before executing the conversion otherwise that PHP code will be ignored (not converted). For details, see issue [#16](https://github.com/philip/MySQLConverterTool/issues/16). 

Also, a `mysql_result` equivelant does not exist in `ext/mysqli` so you must define `mysqli_result`. The tool does suggest code for this but it does not insert this definition into your converted markup.

# Alternatives
The [php7-mysql-shim](https://github.com/dshafik/php7-mysql-shim) PHP library defines all mysql_ functions for you, so simply include it (a single PHP file) and your code should work without a need to convert. It uses ext/mysqli and works fine in PHP 5.3 or greater. There are pros and cons to each approach.

Also, consider refactoring your code. Whether you convert your code with MySQLConverterTool or define ext/mysql functions using a library such as php7-mysql-shim, these are considered stop-gap measures until you rewrite your code. For example, your new code will probably use prepared statements.

# Other
Although unit tests exist, they were not maintained by the lazy developer here. Perhaps one day, see `UnitTests/README`. 

All forms of feedback is welcome and encouraged! Either as bug reports, feature requests, pull requests, complaints, and so on.
