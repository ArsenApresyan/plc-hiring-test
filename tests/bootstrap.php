<?php

// PHP 8.5: Laravel vendor config still references deprecated PDO constants.
if (PHP_VERSION_ID >= 80500) {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
}

require dirname(__DIR__).'/vendor/autoload.php';
