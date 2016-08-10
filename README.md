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
Then, go to http://localhost:8000

### Use CLI
Download as you did above, but use php -f cli.php

# Important Warning
That absolutely NO security checks are performed by the tool which prevent users from trying to read and convert files (e.g. /etc/passwd). Make sure to add security measures, if needed.

# Other  
If you want to run any tests, check the hints given in UnitTests/README. Also, report bugs and feature requests here.

Have fun!
