<?php

require_once 'init.php';

if (!$user->is_auth_user()) {
    send_header('HTTP/1.1 403 Forbidden');
}

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";
$categories = $db->get_data_from_db($query_categories);

$query_bets = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`expire`, `categories`.`name`, `bets`.`sum`, `bets`.`date`, `lots`.`image` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               INNER JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `users` ON `bets`.`user_id` = `users`.`id`
               WHERE `users`.`email` = ?
               ORDER BY `bets`.`date` DESC;";
$bets = $db->get_data_from_db($query_bets, [$_SESSION['email']]);
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
<?= includeTemplate('templates/header.php', $user->get_user_data()); ?>
<?= includeTemplate('templates/my_lots_main.php', ['my_bets' => $bets, 'categories' => $categories]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
