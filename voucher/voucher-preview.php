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

require_once __DIR__ . '/../init.php';

ob_start("ob_gzhandler");

if (!isset($_SESSION["mikhmon"])) {
    header("Location:../admin.php?id=login");
} else {

    // load session MikroTik
    $session = get_parameter('session');

    // load config
    include('utils.php');
    include('../include/config.php');
    include('../include/readcfg.php');
    include('../lib/formatbytesbites.php');

    $template_param = get_parameter("template");

    $i = 1;
    $users = [];
    $profiles = hotspot_config("profiles");

    // create dummy data
    foreach ($profiles as $key => $value) {
        $users[] = [
            "username" => "mikhmon$i",
            "password" => "1234",
            "price" => $value["price"],
            "profile" => $key,
        ];
        $i++;
    }

    $template = get_template($template_param);
    if ($template) {
        render_template($template['header'], [
            "hotspotname" => $hotspotname ?? "",
            "getuprofile" => $getuprofile ?? "",
            "id" => $id ?? ""
        ]);

        foreach ($users as $user) {
            render_template($template['row'], [
                'profile' => $user['profile'],
                'username' => $user['username'],
                'password' => $user['password'],
                'price' => $user['price'],
            ]);
        }

        render_template($template["footer"]);

    } else {
        echo "Template not found";
    }
}
