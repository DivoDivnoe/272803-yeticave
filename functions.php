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
    $value = '';

    if (isset($_POST[$text])) {
        $value = $_POST[$text];

        if (!$value) {
            $class = 'form__item--invalid';
            $error = 'Заполните это поле';
        }
    }

    return ['class' => $class, 'error' => $error, 'value' => $value];
}

function checkSelectInput($name) {
    $class = '';
    $error = '';
    $options = ['Выберите категорию' => '', 'Доски и лыжи' => '', 'Крепления' => '', 'Ботинки' => '', 'Одежда' => '', 'Инструменты' => '', 'Разное' => ''];
    $value = array_keys($options)[0];

    if (isset($_POST[$name])) {
        $value = $_POST[$name];

        if ($value === array_keys($options)[0]) {
            $class = 'form__item--invalid';
            $error = 'Выберите значение';
        } else {
            $options[$value] = 'selected';
        }
    }

    return ['class' => $class, 'error' => $error, 'value' => $value, 'options' => $options];
}

function checkNumberInput($num) {
    $class = '';
    $error = '';
    $value = '';

    if (isset($_POST[$num])) {
        $value = $_POST[$num];
        $class = 'form__item--invalid';

        if ($value) {
            if (!is_numeric($value)) {
                $error =  'Вы ввели не число';
            } else if ($value <= 0) {
                $error =  'Число должно быть положительным';
            } else {
                $class = '';
            }
        } else {
            $error =  'Заполните это поле';
        }
    }

    return ['class' => $class, 'error' => $error, 'value' => $value];
}

function checkFileInput($user_file) {
    $class = '';
    $error = '';

    if (isset($_FILES[$user_file])) {
        $file = $_FILES[$user_file];
        $class = 'form__item--invalid';

        if (!$file['name']) {
            $error =  'Выберите изображение лота';
        }

        if (is_uploaded_file($file['tmp_name'])) {
            if (move_uploaded_file($file['tmp_name'], "img/{$file['name']}")) {
                $class = '';
            } else {
                $error = 'Ошибка при перемещении загруженного файла';
            }
        } else {
            $error = "Ошибка {$file['error']} при загрузке файла";
        }
    }

    return ['class' => $class, 'error' => $error];
}

function checkLotForm($checkedFields) {
    foreach ($checkedFields as $value) {
        if ($value['class']) {
            return 'form--invalid';
        }
    }

    return '';
}

function authUser($email, $pass) {
    global $users;

    foreach ($users as $index => $user) {
        if ($user['email'] === $email && password_verify($pass, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user['name'];
        }
    }
    $class = 'form__item--invalid';
    $error = 'Комбинация пользователь - пароль неверна';

    return ['class' => $class, 'error' => $error];
}
