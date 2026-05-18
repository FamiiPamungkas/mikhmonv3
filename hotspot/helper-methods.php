<?php

function get_comment_group($users): array
{
    error_log("=== GET COMMENT GROUP " . count($users));

    $comment_group = [];
    foreach ($users as $u) {
        $comment = $u['comment'];

        $current_count = 0;
        if ($comment_group[$comment]['count']) {
            $current_count = $comment_group[$comment]['count'];
        }

        $comment_group[$comment]['profile'] = $u['profile'];
        $comment_group[$comment]['count'] = $current_count + 1;
    }

    error_log("=== FINISH COMMENT GROUP");
    return $comment_group;
}