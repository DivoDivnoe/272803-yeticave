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
        $days = ceil($ts / $twenty_four_hours);
        $date = $days < 10 ? '0' . (string) $days : (string) $days;
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
        $left_time = gmdate('H:i', $ts_left);
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

function check_email_in_db(Database $db, $email) {
    $query = "SELECT `email` FROM `users` WHERE email = ?";
    $db->get_data_from_db($query, [$email]);
    $email_list = $db->get_last_query_result();

    return $email_list ? true : false;
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

function show_auth_user(User $user) {
    $class = 'form__item--invalid';
    $error = 'Комбинация пользователь - пароль неверна';
    
    if ($user->is_auth_user()) {
        $class = '';
        $error = '';
    }

    return ['class' => $class, 'error' => $error];
}

function register_user(Database $db, Users_repository $users_queries, $email, $name, $password, $avatar, $contacts) {
    $class = 'form__item--invalid';
    $error = 'Пользователь с таким email уже зарегистрирован';

    $email_in_db = check_email_in_db($db, $email);
    if (!$email_in_db) {
        $class = '';
        $error = '';

        $users_queries->add_new_user($email, $name, $password, $avatar, $contacts);
    }

    return ['class' => $class, 'error' => $error];
}

function addBet($bet, $lot_id) {
    $bet_data = ['cost' => $bet, 'date' => time()];
    $bet_data = json_encode($bet_data);
    $expire = strtotime('+1 year');

    setcookie("my_bets[{$lot_id}]", $bet_data, $expire, '/');
}

function generate_unique_name($name) {
    $extension = get_extension($name);
    return md5($name) . time() . '.' . $extension;
}

function get_extension($filename) {
    return array_pop(explode('.', $filename));
}

function search(Lots_repository $lots_queries, $search_query, $page, $lots_per_page) {
    $query = htmlspecialchars(trim($search_query));
    $num_of_lots = $lots_queries->get_num_of_lots_by_key($query);
    $num_of_pages = ceil($num_of_lots / $lots_per_page);
    $result = [];

    if ($query) {
        $result = $lots_queries->get_lots_by_key($query, ($page - 1) * $lots_per_page, $lots_per_page);
    }

    return ['result' => $result, 'num_of_lots' => $num_of_lots, 'query' => $query, 'num_of_pages' => $num_of_pages];
}

function format_bets_string($num_of_bets) {
    $bet_string = ['ставок', 'ставка', 'ставки'];

    $num_of_bets = (string) $num_of_bets;
    $num_of_bets_length = strlen($num_of_bets);

    if ($num_of_bets_length === 1) {
        $last_char = $num_of_bets;
        $before_last_char = 0;
    } else {
        $last_char = substr($num_of_bets, -1);
        $before_last_char = substr($num_of_bets, -2, 1);
    }

    $last_chars = ['1', '2', '3', '4'];

    if (!in_array($last_char, $last_chars) || $before_last_char === '1') {
        $result_str = $num_of_bets .  ' ' . $bet_string[0];
    } else if ($last_char === '1') {
        $result_str = $num_of_bets .  ' ' . $bet_string[1];
    } else {
        $result_str = $num_of_bets .  ' ' . $bet_string[2];
    }

    return $result_str;
}
