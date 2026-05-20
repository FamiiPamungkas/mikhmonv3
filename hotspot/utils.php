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