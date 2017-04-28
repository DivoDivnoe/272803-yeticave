<?php
// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

// записать в эту переменную оставшееся время в этом формате (ЧЧ:ММ)
$lot_time_remaining = "00:00";

// временная метка для полночи следующего дня
$tomorrow = strtotime('tomorrow midnight');

// временная метка для настоящего времени
$now = time();

// далее нужно вычислить оставшееся время до начала следующих суток и записать его в переменную $lot_time_remaining

$lot_time_remaining = gmdate('H:i', $tomorrow - $now);

$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$items = [
    ['title' => '2014 Rossignol District Snowboard',                 'category' => 'Доски и лыжи', 'price' => 10999,  'url' => 'img/lot-1.jpg'],
    ['title' => 'DC Ply Mens 2016/2017 Snowboard',                   'category' => 'Доски и лыжи', 'price' => 159999, 'url' => 'img/lot-2.jpg'],
    ['title' => 'Крепления Union Contact Pro 2015 года размер L/XL', 'category' => 'Крепления',    'price' => 8000,   'url' => 'img/lot-3.jpg'],
    ['title' => 'Ботинки для сноуборда DC Mutiny Charocal',          'category' => 'Ботинки',      'price' => 10999,  'url' => 'img/lot-4.jpg'],
    ['title' => 'Куртка для сноуборда DC Mutiny Charocal',           'category' => 'Одежда',       'price' => 7500,   'url' => 'img/lot-5.jpg'],
    ['title' => 'Маска Oakley Canopy',                               'category' => 'Разное',       'price' => 5400,   'url' => 'img/lot-6.jpg']
];

require 'functions.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?= includeTemplate('templates/header.php', []); ?>
<?= includeTemplate('templates/main.php', [$categories, $items, $lot_time_remaining]); ?>
<?= includeTemplate('templates/footer.php', []); ?>
</body>
</html>