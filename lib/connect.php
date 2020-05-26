<?php
 /* put your DB info here */
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');
define('MYSQL_HOST', 'localhost');
define('MYSQL_DATABASE', 'wintr');
/* DONT TOUCH */
$pdoOptions = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false
);
/* global PDO query */
$pdo = new PDO(
    "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE, //DSN
    MYSQL_USER, //Username
    MYSQL_PASSWORD, //Password
    $pdoOptions //Options
);
/* /end DONT TOUCH */
$install_dir = "../wintr/"; // if hosted on the main dir of a domain, leave it as "/". Otherwise, add the dir "/dir/" (example.com/dir)
$hosted_url = "http://ed3n.me/wi/"; // full url of your site
$image_dir = "i/"; // the path to the folder where the images are hosted. This dir is hidden from the hosted file URLs.
$winver = "0.2.2A"; // version of the software, this is temporary
