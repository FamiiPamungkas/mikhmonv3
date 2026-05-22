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

// get variable
$hotspot = get_parameter('hotspot');
$hotspotuser = get_parameter('hotspot-user');
$userbyname = get_parameter('hotspot-user');
$userbyprofile = get_parameter('user-by-profile');
$removeuseractive = get_parameter('remove-user-active');
$removehost = get_parameter('remove-host');
$removecookie = get_parameter('remove-cookie');
$removeipbinding = get_parameter('remove-ip-binding');
$removehotspotuser = get_parameter('remove-hotspot-user');
$removehotspotusers = get_parameter('remove-hotspot-users');
$removeuserprofile = get_parameter('remove-user-profile');
$resethotspotuser = get_parameter('reset-hotspot-user');
$removehotspotuserbycomment = get_parameter('remove-hotspot-user-by-comment');
$removeexpiredhotspotuser = get_parameter('remove-hotspot-user-expired');
$enablehotspotuser = get_parameter('enable-hotspot-user');
$disablehotspotuser = get_parameter('disable-hotspot-user');
$enableipbinding = get_parameter('enable-ip-binding');
$disableipbinding = get_parameter('disable-ip-binding');
$userprofile = get_parameter('user-profile');
$userprofilebyname = get_parameter('user-profile');
$sys = get_parameter('system');
$enablesch = get_parameter('enable-scheduler');
$disablesch = get_parameter('disable-scheduler');
$removesch = get_parameter('remove-scheduler');
$macbinding = get_parameter('mac');
$ipbinding = get_parameter('addr');
$ppp = get_parameter('ppp');
$secretbyname = get_parameter('secret');
$enablesecr = get_parameter('enable-pppsecret');
$disablesecr = get_parameter('disable-pppsecret');
$removesecr = get_parameter('remove-pppsecret');
$removepprofile = get_parameter('remove-pprofile');
$removepactive = get_parameter('remove-pactive');
$srv = get_parameter('srv');
$prof = get_parameter('profile');
$comm = get_parameter('comment');
$serveractive = get_parameter('server');
$report = get_parameter('report');
$removereport = get_parameter('remove-report');
$minterface = get_parameter('interface');

// hide all error
//error_reporting(0);
