<?php

function render_template($template, $vars = [], $encoded = false)
{
    $template = $encoded ? base64_decode($template) : $template;
    foreach ($vars as $key => $value) {
        $template = str_replace(
            '{' . $key . '}',
            $value,
            $template
        );
    }

    echo $template;
}

function get_templates()
{
    return require 'template-new.php';
}

function get_template($name)
{
    $templates = get_templates();
    return $templates[$name] ?? null;
}

function read_template_data($template)
{
    return base64_decode($template);
}