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

use classes\VoucherTemplate;

require_once __DIR__ . '/../init.php';

ob_start("ob_gzhandler");

if (!isset($_SESSION["mikhmon"])) {
    header("Location:../admin.php?id=login");
} else {
    $prof = get_parameter("prof");

    $cfg_profiles = hotspot_config("profiles");


    $filtered_users = [];
    $get_users = RouterosAPI::getInstance()->comm("/ip/hotspot/user/print", array("?profile" => "$prof"));
    foreach ($get_users as $u) {
        $exists = false;
        foreach ($cfg_profiles as $k=>$v) {
            if (isset($u['profile']) && $u['profile']===$k){
                $exists = true;

                error_log("USER ID ".$u['id']." - ".$v['uptime_limit']);
//                RouterosAPI::getInstance()->comm("/ip/hotspot/user/set", array(
//                    ".id" => $u['.id'],
//                    "limit-uptime" => $v,
//                ));
            }
        }
        if ($exists) $filtered_users[] = $u;
    }


    echo "<script>window.location='./?hotspot=users&profile=all&session=" . $session . "'</script>";

}
