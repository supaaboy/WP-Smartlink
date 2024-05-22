<?php
if (!defined('ABSPATH')) {
    exit;
}

function get_template($url)
{
    $file = file_get_contents(SMARTLINK_ROOT . $url);

    return $file;
}
