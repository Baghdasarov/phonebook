<?php
    ini_set('display_errors',1);
    session_start();

    define('APPROOT', dirname(dirname(__FILE__)));
    define('URLROOT', '');
    define('SITENAME', 'Phonebook');

    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'phonebook');

    include_once ('helper.php');
