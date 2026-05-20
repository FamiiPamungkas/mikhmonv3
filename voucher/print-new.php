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

    function render_template($template, $vars = [])
    {
        foreach ($vars as $key => $value) {
            $template = str_replace(
                '{' . $key . '}',
                $value,
                $template
            );
        }

        return $template;
    }
  
    // load session MikroTik
  $session = $_GET['session'];

    // load config
  include('../hotspot/utils.php');
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
        $usermode = explode('-', $id)[0];
        $getuser =  RouterosAPI::getInstance()->comm('/ip/hotspot/user/print', array("?comment" => "$id", "?uptime" => "0s"));
        $TotalReg = count($getuser);
    }

    $getuprofile = $getuser[0]['profile'];
    $getprofile =  RouterosAPI::getInstance()->comm("/ip/hotspot/user/profile/print", array("?name" => "$getuprofile"));

    $cfg_profiles = hotspot_config("profiles");
    $cfg_profile = $cfg_profiles[$getuprofile];

    $validity = "";
    $getprice = $cfg_profile["price"] ?? "";
    $getsprice = $cfg_profile["price"] ?? "";

    if ($getsprice == "0" && $getprice != "0") {
        if ($currency == in_array($currency, $cekindo['indo'])) {
            $price = $currency . " " . number_format((float)$getprice, 0, ",", ".");
        } else {
            $price = $currency . " " . number_format((float)$getprice, 2);
        }
    } else if ($getsprice != "0") {
        if ($currency == in_array($currency, $cekindo['indo'])) {
            $price = $currency . " " . number_format((float)$getsprice, 0, ",", ".");
        } else {
            $price = $currency . " " . number_format((float)$getsprice, 2);
        }
    } else if ($getsprice == "0") {
        $price = "";
    }

  $logo = "../img/logo-" . $session . ".png";
  if (file_exists($logo)) {
    $logo = "../img/logo-" . $session . ".png?t=". str_replace(" ","_",date("Y-m-d H:i:s"));
  } else {
    $logo = "../img/logo.png?t=". str_replace(" ","_",date("Y-m-d H:i:s"));
  }

}

$templates = require 'template-new.php';
$template = $templates["default"];
echo base64_decode($template['header']);

for ($i = 0; $i < $TotalReg; $i++) {
    $regtable = $getuser[$i];

    echo render_template(base64_decode($template['row']), [
        'profile' => $regtable['profile'],
        'username' => $regtable['name'],
        'password' => $regtable['password'],
        'price' => $getprice,
    ]);
}

echo base64_decode($template["footer"]);
