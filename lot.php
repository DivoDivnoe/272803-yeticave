<?php
session_start();

require 'functions.php';

date_default_timezone_set('Europe/Moscow');
$cost_post = checkNumberInput('cost');
$idIsValid = false;

if (!isset($_GET['lot_id'])) {
    send_header('HTTP/1.1 404 Not Found');
}

$id_get = $_GET['lot_id'];
$connection = connect_to_db('localhost', 'root', '', 'yeticave');

$query_lots = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`,
               IFNULL(MAX(`bets`.`sum`), `lots`.`start_price`) as `price`,
               `lots`.`expire`, `lots`.`step`, `categories`.`name` FROM `lots`
               LEFT JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`id` = ? AND `lots`.`expire` > NOW()
               GROUP BY `lots`.`id`;";

$lots = get_data_from_db($connection, $query_lots, [$id_get]);

if (!$lots) {
    exit('Ошибка выполнения запроса: ' . mysqli_error($connection));
};

$query_bets = "SELECT `bets`.`sum`, `bets`.`date`, `users`.`name` FROM `lots`
               LEFT JOIN `bets` ON `lots`.`id` = `bets`.`lot_id`
               INNER JOIN `users` ON `users`.`id` = `bets`.`user_id`
               WHERE `lots`.`id` = ?
               ORDER BY `bets`.`date` DESC;";

$bets = get_data_from_db($connection, $query_bets, [$id_get]);

$user_query = "SELECT `id` FROM `users`
                   WHERE `email` = ?";
$user_id = get_data_from_db($connection, $user_query, [$_SESSION['email']])[0]['id'];
check_query_result($connection, $user_id);

if (isset($_POST['cost']) && !$cost_post['error']) {
    $query_add_bet = "INSERT INTO `bets` (`user_id`, `lot_id`, `date`, `sum`)
                      VALUES (?, ?, NOW(), ?);";

    $bet_id = insert_data_to_db($connection, $query_add_bet, [$user_id, $id_get, ($_POST['cost'])]);

    if (!$bet_id) {
        exit('Ошибка выполнения запроса: ' . mysqli_error($connection));
    };

    send_header('Location: mylots.php');
}

$query = "SELECT `name` FROM `categories` ORDER BY `id`;";

$categories = get_data_from_db($connection, $query);
check_query_result($connection, $categories);

$query_made_bet = "SELECT * FROM `bets` WHERE `user_id` = ? AND `lot_id` = ?";
$my_bet = get_data_from_db($connection, $query_made_bet, [$user_id, $id_get]);

$query_is_my_lot = "SELECT * FROM `lots` WHERE `author_id` = ? AND `id` = ?;";
$is_my_lot = get_data_from_db($connection, $query_is_my_lot, [$user_id, $id_get]);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>DC Ply Mens 2016/2017 Snowboard</title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?= includeTemplate('templates/header.php'); ?>
<?= includeTemplate('templates/lot-main.php', ['bets' => $bets, 'equip_item' => $lots[0], 'cost' => $cost_post, 'categories' => $categories, 'my_bet' => $my_bet, 'is_my_lot' => $is_my_lot]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>

