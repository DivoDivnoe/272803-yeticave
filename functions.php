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
        $date = formatTime($dif) . ' назад';
    }

    return $date;
}

function formatTime($ts) {
    $one_hour = 60 * 60;
    $twenty_four_hours = 24 * 60 * 60;

    if ($ts > $twenty_four_hours) {
        $timeString = ['дней', 'день', 'дня'];
        $date = (string) ceil($ts / $twenty_four_hours);
    } elseif ($ts >= $one_hour) {
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
        $result_str = $date .  ' ' . $timeString[0];
    } else if ($last_char === '1') {
        $result_str = $date .  ' ' . $timeString[1];
    } else {
        $result_str = $date .  ' ' . $timeString[2];
    }

    return $result_str;
}

function show_left_time($time) {
    $ts = strtotime($time);
    $ts_left = $ts - time();
    $twenty_four_hours = 24 * 60 * 60;

    if ($ts < $twenty_four_hours) {
        $left_time = date('H:i', $ts_left);
    } else {
        $left_time = formatTime($ts_left);
    }

    return $left_time;
}

function send_header($text) {
    header($text);
    exit;
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
            $_SESSION['email'] = $user['email'];
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
    $stmt = db_get_prepare_stmt($link, $query, $data);
    $result = mysqli_stmt_execute($stmt);

    return ($result ? mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC) : []);
}

function insert_data_to_db($link, $query, $data) {
    $result = mysqli_stmt_execute(db_get_prepare_stmt($link, $query, $data));

    return ($result ? mysqli_insert_id($link) : $result);
}

function update_db_data($link, $table, $data, $where_data) {
    $where_columns = array_keys($where_data);
    $result_or_count = 0;

    foreach ($data as $index => $field) {
        $query = "UPDATE $table SET";

        foreach ($field as $column => $value) {
            $query .= " $column = ?,";
        }

        $query = rtrim($query, ',');
        $where_column = $where_columns[$index];
        $query .= " WHERE $where_column = ?;\n";
        $merged_data = array_merge($field, array_slice($where_data, $index, 1));
        $stmt = db_get_prepare_stmt($link, $query, $merged_data);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (!$result) {
            $result_or_count = false;
            break;
        } else {
            $result_or_count++;
        }
    }

    return $result_or_count;
}

function connect_to_db($host, $user, $password, $db) {
    $connection = mysqli_connect($host, $user, $password, $db);

    if (mysqli_connect_errno()) {
        exit("Ошибка соединения с базой данных. " . mysqli_connect_error());
    }

    return $connection;
}

function check_query_result($connection, $result) {
    if (!$result) {
        exit('Ошибка запроса к базе данных. ' . mysqli_error($connection));
    }
}
