<?php

function includeTemplate($path, $data = []) {
    if (!file_exists($path)) {
        return '';
    }
    ob_start();
    
    foreach ($data as &$value) {
        $value = my_strip_tags($value);
    }
    include "{$path}";

    return ob_get_clean();
}

function my_strip_tags($data) {
    if (is_array($data)) {
        foreach ($data as &$value) {
            $value = my_strip_tags($value);
        }
        return $data;
    } else {
        return is_string($data) ? htmlspecialchars(strip_tags($data)) : $data;
    }
}
