<?php

function render_template($template, $vars = [])
{
    $template = base64_decode($template);
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