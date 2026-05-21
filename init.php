<?php

use classes\VoucherTemplate;

spl_autoload_register(function($class_name) {
    // Base directory for the namespace prefix
    $baseDir = __DIR__ . '/';
    $file = $baseDir . str_replace('\\', '/', $class_name) . '.php';
    if (file_exists($file)){
        include $file;
    }
});

require_once 'util/helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// hide all error
//// error_reporting(0);