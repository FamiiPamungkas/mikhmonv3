<?php

function get_comment_group($profile = ""): array
{
    $users = RouterosAPI::getInstance()->comm(
        "/ip/hotspot/user/print", $profile == "all" ? [] : ["?profile" => "$profile"]
    );

    $comment_group = [];
    foreach ($users as $user) {
        $comment = $user['comment'];

        $current_count = 0;
        if ($comment_group[$comment]['count']) {
            $current_count = $comment_group[$comment]['count'];
        }

        $comment_group[$comment]['profile'] = $user['profile'];
        $comment_group[$comment]['count'] = $current_count + 1;
    }
    return $comment_group;
}