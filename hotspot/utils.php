<?php

function hotspot_config(string $key = null, $default = null)
{
    static $config = null;

    // Load once
    if ($config === null) {
        $config = require __DIR__ . '/config.php';
    }

    // Return all config
    if ($key === null) {
        return $config;
    }

    // Support dot notation
    $keys = explode('.', $key);

    $value = $config;

    foreach ($keys as $segment) {

        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

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
                "parent-queue" => ""
            ];
            $r = RouterosAPI::getInstance()->comm("/ip/hotspot/user/profile/add", $new_profile);
            //todo handle jika error
        }
    }
}