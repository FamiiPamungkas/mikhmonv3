<?php

require_once 'util/helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// hide all error
//error_reporting(0);