<?php

require_once 'init.php';

if (!isset($_SESSION['user'])) {
    send_header('HTTP/1.1 403 Forbidden');
}

$db->connect_to_db();

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";

$db->get_data_from_db($query_categories);
$categories = $db->get_last_query_result();

$query_bets = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`expire`, `categories`.`name`, `bets`.`sum`, `bets`.`date`, `lots`.`image` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               INNER JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `users` ON `bets`.`user_id` = `users`.`id`
               WHERE `users`.`email` = ?
               ORDER BY `bets`.`date` DESC;";
$db->get_data_from_db($query_bets, [$_SESSION['email']]);
$bets = $db->get_last_query_result();
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
<?= includeTemplate('templates/my_lots_main.php', ['my_bets' => $bets, 'categories' => $categories]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
