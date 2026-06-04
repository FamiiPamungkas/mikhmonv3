<?php

function initialize_user_profiles($profiles)
{
    foreach ($profiles as $k => $v) {
        $getprofile = RouterosAPI::getInstance()
            ->comm("/ip/hotspot/user/profile/print", array("?name" => $k));

        if (!$getprofile) {
            $new_profile = [
                "name" => $k,
                "add-mac-cookie" => "yes",
                "mac-cookie-timeout" => $v["mac_cookie_timeout"],
                "rate-limit" => $v["rate_limit"],
                "shared-users" => $v["shared_users"],
                "status-autorefresh" => "1m",
                "on-login" => "",
//                "parent-queue" => ""
            ];
            $r = RouterosAPI::getInstance()->comm("/ip/hotspot/user/profile/add", $new_profile);
            error_log_array($r, "PROFILE ADD ");
            //todo handle jika error
        }
    }
}