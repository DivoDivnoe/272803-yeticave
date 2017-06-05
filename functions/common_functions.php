<?php

/**
 * генерирует случайное имя файла изображения
 * @param string $name оригинальное имя файла
 * @return string уникальное имя файла
 */
function generate_unique_name($name)
{
    $extension = get_extension($name);
    return md5($name) . time() . '.' . $extension;
}

/**
 * получает расширение файла
 * @param string $filename имя файла
 * @return string mixed расширения файла
 */
function get_extension($filename)
{
    return array_pop(explode('.', $filename));
}