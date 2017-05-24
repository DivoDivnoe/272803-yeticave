<?php
require_once 'init.php';

date_default_timezone_set('Europe/Moscow');
$cost_post = checkNumberInput('cost');
$idIsValid = false;

if (!isset($_GET['lot_id'])) {
    send_header('HTTP/1.1 404 Not Found');
}

$id_get = $_GET['lot_id'];

$lot = $query_result->get_lot_by_id($id_get);
$bets = $query_result->get_bets_by_lot_id($id_get);

if (isset($_POST['cost']) && !$cost_post['error']) {
    $bet_id = $query_result->add_new_bet($user_id, $id_get,$_POST['cost']);

    send_header('Location: mylots.php');
}

$categories = $query_result->get_all_categories();

if ($user->is_auth_user()) {
    $user_id = $user->get_user_data()['id'];
    
    $my_bet = $query_result->get_bets_by_userId_and_lotId($user_id, $id_get);
    $is_my_lot = $query_result->get_lot_by_lotId_and_authorId($user_id, $id_get);
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
<?= includeTemplate('templates/header.php', $user->get_user_data()); ?>
<?= includeTemplate('templates/lot-main.php', ['bets' => $bets, 'equip_item' => $lot[0], 'cost' => $cost_post, 'categories' => $categories, 'my_bet' => $my_bet, 'is_my_lot' => $is_my_lot, 'isAuth' => $user->is_auth_user()]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>

