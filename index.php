<?php

require_once 'lots_array.php';
require_once 'functions.php';

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

$connection = mysqli_connect('localhost', 'root', '', 'yeticave');
if (mysqli_connect_errno()) {
    exit("Ошибка соединения с базой данных. " . mysqli_connect_error());
}

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";

$categories = get_data_from_db($connection, $query_categories);
if (!$categories) {
    exit('Ошибка запроса к базе данных. ' . mysqli_error($connection));
}

$query_lots = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`, `lots`.`expire`, `categories`.`name` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               ORDER BY `lots`.`id`;";
$lots = get_data_from_db($connection, $query_lots);
if (!$lots) {
    exit('Ошибка запроса к базе данных. ' . mysqli_error($connection));
}

session_start();
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
<?php if (isset($_SESSION['user'])): ?>
<?= includeTemplate('templates/header.php'); ?>
<?php else: ?>
<?= includeTemplate('templates/header.php'); ?>
<?php endif; ?>
<?= includeTemplate('templates/main.php', ['categories' => $categories, 'equip_items' => $lots, 'classes' => ['boards', 'attachment', 'boots', 'clothing', 'tools', 'other']]);?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>