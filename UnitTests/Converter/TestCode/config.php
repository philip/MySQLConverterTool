<?PHP
/*
CREATE DATABASE _conv_test;
CREATE TABLE _conv_test.root (id INT AUTO_INCREMENT PRIMARY KEY, msg VARCHAR(255));

CREATE USER '_conv_root' IDENTIFIED BY '_conv_root';
GRANT ALL ON _conv_test.* TO '_conv_root';
GRANT CREATE, DROP ON *.* TO '_conv_root';

CREATE TABLE _conv_test.nobody (id INT, msg VARCHAR(255));
CREATE USER '_conv_nobody' IDENTIFIED BY '_conv_nobody';
GRANT ALL ON _conv_test.nobody TO '_conv_nobody';

DROP USER '_conv_nobody';
DROP DATABASE _conv_test;
DROP USER '_conv_root';
*/

$host = '127.0.0.1';

$user = '_conv_root';
$pass = '_conv_root';
$db = '_conv_test';

$user_nobody = '_conv_nobody';
$pass_nobody = '_conv_nobody';
