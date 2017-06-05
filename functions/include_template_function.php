<?php

/**
 * вставляет шаблон
 * @param string $path путь к шаблону
 * @param array $data массив данных для подстановки в шаблон
 * @return string шаблон со вставленными данными
 */
function include_template($path, $data = [])
{
    if (!file_exists($path)) {
        return '';
    }
    array_walk_recursive($data, function (&$value) {
        $value = htmlspecialchars($value);
    });
    extract($data);

    ob_start();

    include "{$path}";

    return ob_get_clean();
}




