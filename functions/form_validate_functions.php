<?php

require_once 'common_functions.php';
/**
 * проверяет поле ввода текста на корректность
 * @param string $text строка введенного текста
 * @return array массив с классом формы, текстом ошибки и введенным в проверяемое поле значением
 */
function check_text_input($text)
{
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
function check_email($email)
{
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
function check_select_input($name, $options)
{
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
function check_number_input($num)
{
    $class = '';
    $error = '';
    $value = '';

    if (isset($_POST[$num])) {
        $value = $_POST[$num];
        $class = 'form__item--invalid';

        if ($value) {
            if (!is_numeric($value)) {
                $error = 'Вы ввели не число';
            } elseif ($value <= 0) {
                $error = 'Число должно быть положительным';
            } else {
                $class = '';
            }
        } else {
            $error = 'Заполните это поле';
        }
    }

    return ['class' => $class, 'error' => $error, 'value' => $value];
}

/**
 * проверяет поле загрузки файла
 * @param string $user_file имя поля загрузки файла
 * @param bool $required наличие required
 * @return array массив с классом формы, текстом ошибки
 */
function check_file_input($user_file, $required = false)
{
    $class = '';
    $error = '';

    if (isset($_FILES[$user_file])) {
        $file = $_FILES[$user_file];

        if ($required && !$file['name']) {
            $error = 'Выберите изображение лота';
            $class = 'form__item--invalid';
        }
    }

    return ['class' => $class, 'error' => $error];
}

/**
 * @param array $file массив данных о загружаемом файле
 * @param string $image_folder директория, куда будет загружен файл
 * @return array массив с классом формы, текстом ошибки и строкой адреса загруженного файла
 */
function move_uploaded_image($file, $image_folder)
{
    if (!$file['name']) {
        return ['class' => '', 'error' => '', 'url' => ''];
    }

    $class = 'form__item--invalid';
    $error = '';
    $url = null;

    $extensions = ['image/png', 'image/jpeg', 'image/gif', 'image/tiff'];
    $temp_path = $file['tmp_name'];
    $file_name = generate_unique_name($file['name']);

    if (is_uploaded_file($file['tmp_name'])) {
        if (!in_array(mime_content_type($temp_path), $extensions)) {
            $error = "Неверное расширение файла!";
        } elseif (move_uploaded_file($temp_path, "$image_folder/$file_name")) {
            $url = "$image_folder/$file_name";
            $class = '';
        } else {
            $error = 'Ошибка при перемещении загруженного файла';
        }
    } else {
        $error = "Ошибка {$file['error']} при загрузке файла";
    }

    return ['class' => $class, 'error' => $error, 'url' => $url];
}

/**
 * проверяет поле ввода даты на корректность
 * @param string $date имя поля ввода даты
 * @return array массив с классом формы, текстом ошибки и введенным в проверяемое поле значением
 */
function check_date($date)
{
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
function check_lot_form($checkedFields)
{
    foreach ($checkedFields as $value) {
        if ($value['class']) {
            return 'form--invalid';
        }
    }

    return '';
}