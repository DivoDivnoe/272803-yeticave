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
    $ts_left = strtotime($time) - time();
    $twenty_four_hours = 24 * 60 * 60;

    if ($ts_left < $twenty_four_hours) {
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

function check_email($email) {
    $class = '';
    $error = '';
    $value = '';

    if (isset($_POST[$email])) {
        $value = filter_var($_POST[$email], FILTER_VALIDATE_EMAIL);

        if (!$value) {
            $class = 'form__item--invalid';
            $error = 'Неверный формат email';
        }
    }

    return ['class' => $class, 'error' => $error, 'value' => $value];
}

function check_email_in_db($connection, $email) {
    $query = "SELECT `email` FROM `users`";
    $email_list = get_data_from_db($connection, $query);

    foreach ($email_list as $email_field) {
        if ($email === $email_field['email']) {
            return true;
        }
    }

    return false;
}

function checkSelectInput($name, $options) {
    $class = '';
    $error = '';
    $selected = 0;
    $value = $options[$selected];

    if (isset($_POST[$name])) {
        $value = $_POST[$name];

        if ($value === $options[0]) {
            $class = 'form__item--invalid';
            $error = 'Выберите значение';
        } else {
            $selected = array_search($value, $options);
        }
    }

    return ['class' => $class, 'error' => $error, 'value' => $value, 'options' => $options, 'selected' => $selected];
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

function checkFileInput($user_file, $image_folder, $required = false) {
    $class = '';
    $error = '';
    $url = null;
    $extensions = ['image/png', 'image/jpeg', 'image/gif', 'image/tiff'];

    if (isset($_FILES[$user_file])) {
        $file = $_FILES[$user_file];

        if ($required && !$file['name']) {
            $error =  'Выберите изображение лота';
            $class = 'form__item--invalid';
        }

        if (is_uploaded_file($file['tmp_name'])) {
            $temp_path = $file['tmp_name'];
            $class = 'form__item--invalid';
            $file_name = generate_unique_name($file['name']);

            if (in_array(mime_content_type($temp_path), $extensions)) {
                if (move_uploaded_file($temp_path, "$image_folder/$file_name")) {
                    $class = '';
                    $url = "$image_folder/$file_name";
                } else {
                    $error = 'Ошибка при перемещении загруженного файла';
                }
            } else {
                $error = "Неверное расширение файла!";
            }
        } else {
            $error = "Ошибка {$file['error']} при загрузке файла";
        }
    }

    return ['class' => $class, 'error' => $error, 'url' => $url];
}

function check_date($date) {
    $class = '';
    $error = '';
    $value = '';

    if (isset($_POST[$date])) {
        $value = $_POST[$date];

        if (!$value) {
            $class = 'form__item--invalid';
            $error = 'Заполните это поле';
        } else {
            $date_array = explode('.', $value);
            $date_is_valid = checkdate($date_array[1], $date_array[0], $date_array[2]) && strtotime($value) > strtotime('today midnight');

            if (!$date_is_valid) {
                $class = 'form__item--invalid';
                $error = 'Введите корректную дату';
            }
        }
    }

    return ['class' => $class, 'error' => $error, 'value' => $value];

}

function checkLotForm($checkedFields) {
    foreach ($checkedFields as $value) {
        if ($value['class']) {
            return 'form--invalid';
        }
    }

    return '';
}

function auth_user($connection, $email, $pass) {
    $class = 'form__item--invalid';
    $error = 'Комбинация пользователь - пароль неверна';

    $query = "SELECT `email`, `password`, `name`, `avatar` FROM `users` WHERE `email` = ?";
    $result = get_data_from_db($connection, $query, [$email]);

    if ($result && password_verify($pass, $result[0]['password'])) {
        session_start();
        $_SESSION['user'] = $result[0]['name'];
        $_SESSION['email'] = $email;
        $_SESSION['avatar'] = $result[0]['avatar'] ? $result[0]['avatar'] : 'user.jpg';
        $class = '';
        $error = '';
    }

    return ['class' => $class, 'error' => $error];
}

function register_user($link, $data, $has_avatar = false) {
    $class = 'form__item--invalid';
    $error = 'Пользователь с таким email уже зарегистрирован';

    $email_in_db = check_email_in_db($link, $data['email']);
    if (!$email_in_db) {
        $class = '';
        $error = '';

        if ($has_avatar) {
            $query = "INSERT INTO `users` (`register_date`, `email`, `name`, `password`, `avatar`, `contacts`) VALUES (NOW(), ?, ?, ?, ?, ?);";
        } else {
            $query = "INSERT INTO `users` (`register_date`, `email`, `name`, `password`, `contacts`) VALUES (NOW(), ?, ?, ?, ?);";
        }

        $result = insert_data_to_db($link, $query, $data);
        check_query_result($link, $result);
    }

    return ['class' => $class, 'error' => $error];
}

function addBet($bet, $lot_id) {
    $bet_data = ['cost' => $bet, 'date' => time()];
    $bet_data = json_encode($bet_data);
    $expire = strtotime('+1 year');

    setcookie("my_bets[{$lot_id}]", $bet_data, $expire, '/');
}


/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if(!$stmt) {
        exit("Ошибка подготовки запроса: " . mysqli_error($link));
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);
        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
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
    
    $result = mysqli_query($connection, 'SET NAMES utf8');
    check_query_result($connection, $result);

    return $connection;
}

function check_query_result($connection, $result) {
    if (!$result) {
        exit('Ошибка запроса к базе данных. ' . mysqli_error($connection));
    }
}

function generate_unique_name($name)
{
    $extension = get_extension($name);
    return md5($name) . time() . '.' . $extension;
}

function get_extension($filename) {
    return array_pop(explode('.', $filename));
}

function search($connection) {
    $search_get = checkTextInput('search');
    $search = $_GET['search'];
    $result = '';

    if (!$search_get['error']) {
        $query = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`, `lots`.`expire`, `categories`.`name` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`expire` > NOW() AND (`lots`.`title` LIKE ? OR `lots`.`description` LIKE ?)
               ORDER BY `lots`.`register_date` DESC;";

        $result = get_data_from_db($connection, $query, ["%$search%", "%$search%"]);
    }

    return ['error' => $search_get['error'], 'result' => $result];
}
