<?php
return [
    "profiles" => [
        "5.J.R" => [
            "label" => "5 JAM",
            "prefix" => "5jm",
            "user_length" => 4,
            "pass_length" => 4,
            "time_limit" => "5h",
            "rate_limit" => "5M/5M",
            "shared_users" => "1",
            "mac_cookie_timeout" => "5h",
            "price" => "2000",
        ],
        "1.H.R" => [
            "label" => "1 HARI",
            "prefix" => "1hm",
            "user_length" => 4,
            "pass_length" => 4,
            "time_limit" => "24h",
            "rate_limit" => "5M/5M",
            "shared_users" => "1",
            "mac_cookie_timeout" => "1d",
            "price" => "4000",
        ],
        "1.B.R" => [
            "label" => "1 BULAN",
            "prefix" => "",
            "user_length" => 4,
            "pass_length" => 4,
            "time_limit" => "30d",
            "rate_limit" => "5M/5M",
            "shared_users" => "1",
            "mac_cookie_timeout" => "3d",
            "price" => "30000",
        ]
    ]
];