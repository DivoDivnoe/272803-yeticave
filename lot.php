<?php

require_once 'init.php';

date_default_timezone_set('Europe/Moscow');
$cost_post = checkNumberInput('cost');
$idIsValid = false;

if (!isset($_GET['lot_id'])) {
    send_header('HTTP/1.1 404 Not Found');
}

$id_get = $_GET['lot_id'];

$db->connect_to_db();

$query_lots = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`,
               IFNULL(MAX(`bets`.`sum`), `lots`.`start_price`) as `price`,
               `lots`.`expire`, `lots`.`step`, `categories`.`name` FROM `lots`
               LEFT JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`id` = ? AND `lots`.`expire` > NOW()
               GROUP BY `lots`.`id`;";

$db->get_data_from_db($query_lots, [$id_get]);
$lots = $db->get_last_query_result();

$query_bets = "SELECT `bets`.`sum`, `bets`.`date`, `users`.`name` FROM `lots`
               LEFT JOIN `bets` ON `lots`.`id` = `bets`.`lot_id`
               INNER JOIN `users` ON `users`.`id` = `bets`.`user_id`
               WHERE `lots`.`id` = ?
               ORDER BY `bets`.`date` DESC;";

$db->get_data_from_db($query_bets, [$id_get]);
$bets = $db->get_last_query_result();

$user_query = "SELECT `id` FROM `users`
                   WHERE `email` = ?";
$db->get_data_from_db($user_query, [$_SESSION['email']])[0]['id'];
$user_id = $db->get_last_query_result()[0]['id'];

if (isset($_POST['cost']) && !$cost_post['error']) {
    $query_add_bet = "INSERT INTO `bets` (`user_id`, `lot_id`, `date`, `sum`)
                      VALUES (?, ?, NOW(), ?);";

    $db->insert_data_to_db($query_add_bet, [$user_id, $id_get, ($_POST['cost'])]);
    $bet_id = $db->get_last_query_result();
    send_header('Location: mylots.php');
}

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";

$db->get_data_from_db($query_categories);
$categories = $db->get_last_query_result();

$query_made_bet = "SELECT * FROM `bets` WHERE `user_id` = ? AND `lot_id` = ?";
$db->get_data_from_db($query_made_bet, [$user_id, $id_get]);
$my_bet = $db->get_last_query_result();

$query_is_my_lot = "SELECT * FROM `lots` WHERE `author_id` = ? AND `id` = ?;";
$db->get_data_from_db($query_is_my_lot, [$user_id, $id_get]);
$is_my_lot = $db->get_last_query_result();
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

