<?php
require_once 'init.php';

if (!isset($_GET['lot_id'])) {
    send_header('HTTP/1.1 404 Not Found');
}

date_default_timezone_set('Europe/Moscow');

$id_get = $_GET['lot_id'];
$cost_post = checkNumberInput('cost');
$idIsValid = false;

$categories = $categories_queries->get_all_categories();
$lot = $lots_queries->get_lot_by_id($id_get);
$bets = $bets_queries->get_bets_by_lot_id($id_get);

if ($user->is_auth_user()) {
    $user_id = $user->get_user_data()['id'];
    $my_bet = $bets_queries->get_bets_by_userId_and_lotId($user_id, $id_get);
    $is_my_lot = $lots_queries->get_lot_by_lotId_and_authorId($user_id, $id_get);
}

if (isset($_POST['cost']) && !$cost_post['error']) {
    $bet_id = $bets_queries->add_new_bet($user_id, $id_get, $_POST['cost']);

    send_header('Location: mylots.php');
}
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
<?= includeTemplate('templates/header.php', array_merge($user->get_user_data())); ?>
<?= includeTemplate('templates/lot-main.php', ['bets' => $bets, 'equip_item' => $lot, 'cost' => $cost_post, 'categories' => $categories, 'my_bet' => $my_bet, 'is_my_lot' => $is_my_lot, 'isAuth' => $user->is_auth_user()]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>

