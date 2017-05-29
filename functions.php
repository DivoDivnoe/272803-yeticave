<?php

/**
 * вставляет шаблон
 * @param string $path путь к шаблону
 * @param array $data массив данных для подстановки в шаблон
 * @return string шаблон со вставленными данными
 */
function include_template($path, $data = []) {
    if (!file_exists($path)) {
        return '';
    }
    array_walk_recursive($data, function(&$value) {
        $value = htmlspecialchars($value);
    });
    extract($data);

    ob_start();

    include "{$path}";

    return ob_get_clean();
}

/**
 * переводит timestamp в относительный формат
 * @param integer $ts timestamp
 * @return string строка отформатированной даты в относительном формате
 */
function ts_2_relative($ts) {
    $twenty_four_hours = 24 * 60 * 60;
    $dif = time() - $ts;

    if ($dif > $twenty_four_hours) {
        $date = date('d.m.y в H:i' , $ts);
    }  else {
        $date = format_time($dif) . ' назад';
    }

    return $date;
}

/**
 * показывает сколько времени прошло с метки timestamp
 * @param integer $ts timestamp
 * @return string строка отформатированной даты
 */
function format_time($ts) {
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

/**
 * показывает, сколько осталось времени до метки timestamp
 * @param string $time строка с датой
 * @return string строка оставшегося времени
 */
function show_left_time($time) {
    $ts_left = strtotime($time) - time();
    $twenty_four_hours = 24 * 60 * 60;

    if ($ts_left < $twenty_four_hours) {
        $left_time = gmdate('H:i', $ts_left);
    } else {
        $left_time = format_time($ts_left);
    }

    return $left_time;
}

/**
 * проверяет поле ввода текста на корректность
 * @param string $text строка введенного текста
 * @return array массив с классом формы, текстом ошибки и введенным в проверяемое поле значением
 */
function check_text_input($text) {
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

/**
 * проверяет введенный email на корректность
 * @param string $email введенный email
 * @return array массив с классом формы, текстом ошибки и введенным в проверяемое поле значением
 */
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

/**
 * проверяет, выбрана ли одна из опций в поле ввода типа select
 * @param string $name имя поля ввода
 * @param array $options массив опций
 * @return array массив с классом формы, текстом ошибки, выбранной опцией, массив опций и индекс выбранной опции
 */
function check_select_input($name, $options) {
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

/**
 * проверяет на корректность поле ввода чисел
 * @param integer $num введенное число
 * @return array массив с классом формы, текстом ошибки и введенным в проверяемое поле значением
 */
function check_number_input($num) {
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

/**
 * проверяет поле загрузки файла
 * @param string $user_file имя поля загрузки файла
 * @param string $image_folder директория, куда будет загружен файл
 * @param bool $required наличие required
 * @return array массив с классом формы, текстом ошибки и строкой адреса загруженного файла
 */
function check_file_input($user_file, $image_folder, $required = false) {
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

/**
 * проверяет поле ввода даты на корректность
 * @param string $date имя поля ввода даты
 * @return array массив с классом формы, текстом ошибки и введенным в проверяемое поле значением
 */
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
            $date_is_valid = checkdate($date_array[1], $date_array[0], $date_array[2]) &&
                             strtotime($value) > strtotime('today midnight');

            if (!$date_is_valid) {
                $class = 'form__item--invalid';
                $error = 'Введите корректную дату';
            }
        }
    }

    return ['class' => $class, 'error' => $error, 'value' => $value];
}

/**
 * проверяет всю форму целиком на корректность
 * @param array $checkedFields массив проверяемых полей
 * @return string строка с классом формы
 */
function check_lot_form($checkedFields) {
    foreach ($checkedFields as $value) {
        if ($value['class']) {
            return 'form--invalid';
        }
    }

    return '';
}

/**
 * показывает ошибку в случае неудачной аутентификации
 * @param User $user объект класса пользователь
 * @return array массив с классом формы и текстом ошибки
 */
function show_auth_user(User $user) {
    $class = 'form__item--invalid';
    $error = 'Комбинация пользователь - пароль неверна';
    
    if ($user->is_auth_user()) {
        $class = '';
        $error = '';
    }

    return ['class' => $class, 'error' => $error];
}

/**
 * регистрация пользователя
 * @param UsersRepository $users_repository объект запросов, связанных с пользователем
 * @param string $email поле ввода email
 * @param string $name поле ввода имени пользователя
 * @param string $password поле ввода пароля
 * @param string $avatar поле загрузки изображения пользователя
 * @param string $contacts поле ввода контактов пользователя
 * @return array массив с классом формы и текстом ошибки
 */
function register_user(UsersRepository $users_repository, $email, $name, $password, $avatar, $contacts) {
    $class = 'form__item--invalid';
    $error = 'Пользователь с таким email уже зарегистрирован';

    $email_in_db = $users_repository->check_email_in_db($email);
    if (!$email_in_db) {
        $class = '';
        $error = '';

        $users_repository->add_new_user($email, $name, $password, $avatar, $contacts);
    }

    return ['class' => $class, 'error' => $error];
}

/**
 * генерирует случайное имя файла изображения
 * @param string $name оригинальное имя файла
 * @return string уникальное имя файла
 */
function generate_unique_name($name) {
    $extension = get_extension($name);
    return md5($name) . time() . '.' . $extension;
}

/**
 * получает расширение файла
 * @param string $filename имя файла
 * @return string mixed расширения файла
 */
function get_extension($filename) {
    return array_pop(explode('.', $filename));
}

/**
 * осуществляет поиск в базе данных по ключевому слову
 * @param LotsRepository $lots_queries объект запросов, связанных с лотами
 * @param string $search_query строка запроса для поиска
 * @param string $page текущая страница
 * @param integer $lots_per_page количество лотов на странице
 * @return array массив с результатом поиска, общим количеством лотов, строкой запроса и количеством страниц
 */
function search(LotsRepository $lots_queries, $search_query, $page, $lots_per_page) {
    $query = trim($search_query);
    $num_of_lots = $lots_queries->get_num_of_lots_by_key($query);
    $num_of_pages = ceil($num_of_lots / $lots_per_page);
    $result = [];

    if ($query) {
        $result = $lots_queries->get_lots_by_key($query, ($page - 1) * $lots_per_page, $lots_per_page);
    }

    return ['result' => $result, 'num_of_lots' => $num_of_lots, 'query' => $query, 'num_of_pages' => $num_of_pages];
}

/**
 * возвращает строку с количеством ставок
 * @param integer $num_of_bets количество ставок
 * @return string строка с количеством ставок
 */
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
