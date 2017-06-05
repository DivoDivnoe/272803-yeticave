<?php

/**
 * показывает сколько времени прошло с метки timestamp
 * @param integer $ts timestamp
 * @return string строка отформатированной даты
 */
function format_time($ts)
{
    $one_hour = 60 * 60;
    $twenty_four_hours = 24 * 60 * 60;

    if ($ts > $twenty_four_hours) {
        $timeStrings = ['дней', 'день', 'дня'];
        $days = ceil($ts / $twenty_four_hours);
        $date = $days < 10 ? '0' . (string)$days : (string)$days;
    } elseif ($ts >= $one_hour) {
        $timeStrings = ['часов', 'час', 'часа'];
        $date = gmdate('H', $ts);
    } else {
        $timeStrings = ['минут', 'минута', 'минуты'];
        $date = gmdate('i', $ts);
    }

    return format_string($date, $timeStrings);
}

/**
 * возвращает строку с количеством ставок
 * @param integer $num количество ставок
 * @param  string $string_arr массив возможных значений строки
 * @return string строка с количеством ставок
 */
function format_string($num, $string_arr) // ['ставок', 'ставка', 'ставки']
{
    $num = (string)$num;
    $num_length = strlen($num);

    if ($num_length === 1) {
        $last_char = $num;
        $before_last_char = 0;
    } else {
        $last_char = substr($num, -1);
        $before_last_char = substr($num, -2, 1);
    }

    $last_chars = ['1', '2', '3', '4'];

    if (!in_array($last_char, $last_chars) || $before_last_char === '1') {
        $result_str = $num . ' ' . $string_arr[0];
    } else {
        if ($last_char === '1') {
            $result_str = $num . ' ' . $string_arr[1];
        } else {
            $result_str = $num . ' ' . $string_arr[2];
        }
    }

    return $result_str;
}

/**
 * переводит timestamp в относительный формат
 * @param integer $ts timestamp
 * @return string строка отформатированной даты в относительном формате
 */
function ts_2_relative($ts)
{
    $twenty_four_hours = 24 * 60 * 60;
    $dif = time() - $ts;

    if ($dif > $twenty_four_hours) {
        $date = date('d.m.y в H:i', $ts);
    } else {
        $date = format_time($dif) . ' назад';
    }

    return $date;
}

/**
 * показывает, сколько осталось времени до метки timestamp
 * @param string $time строка с датой
 * @return string строка оставшегося времени
 */
function show_left_time($time)
{
    $ts_left = strtotime($time) - time();
    $twenty_four_hours = 24 * 60 * 60;

    if ($ts_left < $twenty_four_hours) {
        $left_time = gmdate('H:i', $ts_left);
    } else {
        $left_time = format_time($ts_left);
    }

    return $left_time;
}
