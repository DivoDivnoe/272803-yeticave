<?php

function includeTemplate($path, $data) {
    if (file_exists($path)) {
        foreach ($data as &$value) {
            $value = my_strip_tags($value);
        }
        include "{$path}";
    } else {
        return '';
    }
}

function my_strip_tags($data) {
    if (is_array($data)) {
        foreach ($data as &$value) {
            $value = my_strip_tags($value);
        }
        return $data;
    } else {
        return is_string($data) ? strip_tags($data) : $data;
    }
}
