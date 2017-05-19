<?php

session_start();

require 'functions.php';

if (!isset($_SESSION['user'])) {
    send_header('HTTP/1.1 403 Forbidden');
}

$connection = connect_to_db('localhost', 'root', '', 'yeticave');

$query = "SELECT `name` FROM `categories` ORDER BY `id`;";

$categories = get_data_from_db($connection, $query);
check_query_result($connection, $categories);

$query_bets = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`expire`, `categories`.`name`, `bets`.`sum`, `bets`.`date` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               INNER JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `users` ON `bets`.`user_id` = `users`.`id`
               WHERE `users`.`email` = ?
               ORDER BY `bets`.`date` DESC;";
$bets = get_data_from_db($connection, $query_bets, [$_SESSION['email']]);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои ставки</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<?= includeTemplate('templates/header.php'); ?>
<?= includeTemplate('templates/my_lots_main.php', ['my_bets' => $bets]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
