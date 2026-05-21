<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once __DIR__.'/../init.php';

ob_start("ob_gzhandler");

if (!isset($_SESSION["mikhmon"])) {
  header("Location:../admin.php?id=login");
} else {

    date_default_timezone_set($_SESSION['timezone']);

    // load session MikroTik
    $session = get_parameter('session');

    // load config
    include('utils.php');
    include('../include/config.php');
    include('../include/readcfg.php');
    include('../lib/formatbytesbites.php');

    $id = get_parameter('id');
    $qr = get_parameter('qr');
    $small = get_parameter('small');
    $userp = get_parameter('user');

    require('../lib/routeros_api.class.php');

    if ($userp != "") {
        $usermode = explode('-', $userp)[0];
        $pulluser = explode('-', $userp);
        $iuser = count($pulluser);
        $prefix = explode('-', $userp)[$iuser - 2];
        $user = explode('-', $userp)[$iuser - 1];
        if ($iuser == 3) {
            $user = $prefix . "-" . $user;
        } else {
            $user = $user;
        }
        $getuser = RouterosAPI::getInstance()->comm("/ip/hotspot/user/print", array("?name" => "$user"));
        $TotalReg = count($getuser);
    } elseif ($id != "") {
        $getuser =  RouterosAPI::getInstance()->comm('/ip/hotspot/user/print', array("?comment" => "$id", "?uptime" => "0s"));
        $TotalReg = count($getuser);
    }

    $getuprofile = $getuser[0]['profile'];
    $getprofile =  RouterosAPI::getInstance()->comm("/ip/hotspot/user/profile/print", array("?name" => "$getuprofile"));

    $cfg_profiles = hotspot_config("profiles");
    $cfg_profile = $cfg_profiles[$getuprofile];

    $validity = "";
    $getprice = $cfg_profile["price"] ?? "";
}

$templates = require 'template-new.php';
$template = $templates["default"];
render_template($template['header'], [
    "hotspotname" => $hotspotname,
    "getuprofile" => $getuprofile,
    "id" => $id
]);

for ($i = 0; $i < $TotalReg; $i++) {
    $regtable = $getuser[$i];

    render_template($template['row'], [
        'profile' => $regtable['profile'],
        'username' => $regtable['name'],
        'password' => $regtable['password'],
        'price' => $getprice,
    ]);
}

render_template($template["footer"]);
