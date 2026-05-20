<?php

require_once __DIR__ . '/../include/config.php';

function session_config($session, $default = null)
{
    global $data;
    return $data[$session] ?? $default;
}

function session_data($key)
{
    $cfg = session_config($_GET['session']);
    switch ($key){
        case 'iphost':
            return explode('!', $cfg[1])[1];
        case 'userhost':
            return explode('@|@', $cfg[2])[1];
        case 'passwdhost':
            return explode('#|#', $cfg[3])[1];
        case 'hotspotname':
            return explode('%', $cfg[4])[1];
        case 'dnsname':
            return explode('^', $cfg[5])[1];
        case 'currency':
            return explode('&', $cfg[6])[1];
        case 'areload':
            return explode('*', $cfg[7])[1];
        case 'iface':
            return explode('(', $cfg[8])[1];
        case 'infolp':
            return explode(')', $cfg[9])[1];
        case 'idleto':
            return explode('=', $cfg[10])[1];
        case 'sesname':
            return $_GET['session'];
        case 'livereport':
            return explode('@!@', $cfg[11])[1];
        case 'useradm':
            $mikhmon_cfg = session_config('mikhmon');
            return explode('<|<', $mikhmon_cfg[1])[1];
        case 'passadm':
            $mikhmon_cfg = session_config('mikhmon');
            return explode('>|>', $mikhmon_cfg[2])[1];
        default:
            return null;
    }
}

function session_list(): array
{
    global $data;

    $session_list = [];
    foreach ($data as $k=>$v) {
        if ($k!="mikhmon") $session_list[] = $k;
    }

    return $session_list;
}

function get_parameter($name, $default = "")
{
    if (isset($_GET[$name])){
        return $_GET[$name];
    }
    return $default;
}
