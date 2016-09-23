# Introduction
This tool converts the deprecated (and removed as of PHP 7) ext/mysql extension into code using the newer (and fully supported) ext/mysqli extension.

This tool is not perfect, but it will help you with the conversion.

# Usage
There are two interfaces: GUI and CLI. Example usages:

### Using PHP's built-in web server
```
$ wget https://github.com/philip/MySQLConverterTool/archive/master.zip
$ unzip master.zip
$ cd MySQLConverterTool-master/GUI
$ php -S localhost:8000
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

# Other 
If you want to run any tests, check the hints given in `UnitTests/README`. Also, report bugs and feature requests here.

Have fun!
