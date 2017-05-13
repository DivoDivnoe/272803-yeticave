<?php

require_once 'mysql_helper.php';

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

function authUser($email, $pass, $users) {
    $class = 'form__item--invalid';
    $error = 'Комбинация пользователь - пароль неверна';

    foreach ($users as $index => $user) {
        if ($user['email'] === $email && password_verify($pass, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user['name'];
            $class = '';
            $error = '';
            break;
        }
    }

    return ['class' => $class, 'error' => $error];
}

function addBet($bet, $lot_id) {
    $bet_data = ['cost' => $bet, 'date' => time()];
    $bet_data = json_encode($bet_data);
    $expire = strtotime('+1 year');

    setcookie("my_bets[{$lot_id}]", $bet_data, $expire, '/');
}

function get_data_from_db($link, $query, $data = []) {
    $result = mysqli_stmt_execute(db_get_prepare_stmt($link, $query, $data));

    return ($result ? mysqli_fetch_all(mysqli_use_result($link), MYSQLI_ASSOC) : []);
}

function insert_data_to_db($link, $query, $data) {
    $result = mysqli_stmt_execute(db_get_prepare_stmt($link, $query, $data));

    return ($result ? mysqli_insert_id($link) : $result);
}

function update_db_data($link, $table, $data, $where_data) {
    $where_columns = array_keys($where_data);
    $count = 0;

    foreach ($data as $index => $field) {
        $query = "UPDATE $table";

        foreach ($field as $column => $value) {
            $query .= " SET $column = ?,";
        }

        $query = substr($query, 0, -1);
        $where_column = $where_columns[$index];
        $query .= " WHERE $where_column = ?;\n";
        $merged_data = array_merge($field, array_slice($where_data, $index, 1));
        $result = mysqli_stmt_execute(db_get_prepare_stmt($link, $query, $merged_data));

        if (!$result) {
            return $result;
        } else {
            $count++;
        }
    }

    return $count;
}
