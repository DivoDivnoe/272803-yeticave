<?php

function includeTemplate($path, $data = []) {
    if (!file_exists($path)) {
        return '';
    }
    array_walk_recursive($data, function(&$value) {
        $value = my_strip_tags($value);
    });
    extract($data);

    ob_start();

    include "{$path}";

    return ob_get_clean();
}

function my_strip_tags($data) {
    return is_string($data) ? htmlspecialchars(strip_tags($data)) : $data;
}

function ts2relative($ts) {
    $twenty_four_hours = 24 * 60 * 60;

    $dif = time() - $ts;

    if ($dif > $twenty_four_hours) {
        $date = date('d.m.y в H:i' , $ts);
    }  else {
        $date = formatTime($dif);
    }

    return $date;
}

function formatTime($ts) {
    $one_hour = 60 * 60;

    if ($ts >= $one_hour) {
        $timeString = ['часов', 'час', 'часа'];
        $date = gmdate('H', $ts);
    } else {
        $timeString = ['минут', 'минута', 'минуты'];
        $date = gmdate('i', $ts);
    }
    $last_char = substr($date, 1, 1);
    $first_char = substr($date, 0, 1);
    $last_chars = ['1', '2', '3', '4'];

    if (!in_array($last_char, $last_chars) || $first_char === '1') {
        $result_str = $date .  ' ' . $timeString[0] . ' назад';
    } else if ($last_char === '1') {
        $result_str = $date .  ' ' . $timeString[1] . ' назад';
    } else {
        $result_str = $date .  ' ' . $timeString[2] . ' назад';
    }

    return $result_str;
}

function checkTextInput($text) {
    $class = '';
    $error = '';

    if (empty($_POST[$text])) {
        $class = 'form__item--invalid';
        $error = 'Заполните это поле';
    }

    return ['class' => $class, 'error' => $error];
}

function checkNumberInput($num) {
    $class = 'form__item--invalid';
    $error = '';

    if (!empty($_POST[$num])) {
        if (!is_numeric($_POST[$num])) {
            $error =  'Вы ввели не число';
        } else if ($_POST[$num] <= 0) {
            $error =  'Число должно быть положительным';
        } else {
            $class = '';
        }
    } else {
        $error =  'Заполните это поле';
    }

    return ['class' => $class, 'error' => $error];
}

function checkFileInput($user_file) {
    $class = 'form__item--invalid';
    $error = '';

    if (isset($_FILES[$user_file])) {
        $file = $_FILES[$user_file];
        if (is_uploaded_file($file['tmp_name'])) {
            move_uploaded_file($file['tmp_name'], "img/{$file['name']}");
            $class = '';
        } else {
            $error = 'Ошибка при загрузке файла';
        }
    } else {
        $error =  'Выберите изображение лота';
    }
    return ['class' => $class, 'error' => $error];
}

function checkLotForm($title, $category, $message, $user_file, $lot_rate, $lot_step, $lot_date) {
    $arr = [$title, $category, $message, $user_file, $lot_rate, $lot_step, $lot_date];

    foreach ($arr as $value) {
        if (!empty($value['class'])) {
            return 'form--invalid';
        }
    }

    return '';
}