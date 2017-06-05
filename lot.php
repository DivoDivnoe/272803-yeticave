<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Categories_repository.php';
require_once 'classes/Lots_repository.php';
require_once 'classes/Bets_repository.php';
require_once 'classes/Users_repository.php';
require_once 'functions/form_validate_functions.php';
require_once 'functions/include_template_function.php';
require_once 'functions/format_functions.php';
require_once 'configs/database_connect_data.php';

session_start();

$db = new Database(...$connect_data);
$users_repository = new UsersRepository($db);
$user = new User($users_repository);
$categories_repository = new CategoriesRepository($db);
$lots_repository = new LotsRepository($db);
$bets_repository = new BetsRepository($db);

if (!isset($_GET['lot_id'])) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

date_default_timezone_set('Europe/Moscow');

$id_get = $_GET['lot_id'];
$cost_post = check_number_input('cost');
$idIsValid = false;

$categories = $categories_repository->get_all_categories();
$lot = $lots_repository->get_lot_by_id($id_get);
$bets = $bets_repository->get_bets_by_lot_id($id_get);

if ($user->is_auth_user()) {
    $user_id = $user->get_user_data()['id'];
    $my_bet = $bets_repository->get_bets_by_user_id_and_lot_id($user_id, $id_get);
    $is_my_lot = $lots_repository->get_lot_by_lot_id_and_author_id($user_id, $id_get);
}

if (isset($_POST['cost']) && !$cost_post['error']) {
    $bet_id = $bets_repository->add_new_bet($user_id, $id_get, $_POST['cost']);

    header('Location: mylots.php');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $lot['title'] ?></title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?= include_template('templates/header.php', $user->get_user_data()); ?>
<?= include_template('templates/lot-main.php', [
    'bets' => $bets,
    'equip_item' => $lot,
    'cost' => $cost_post,
    'categories' => $categories,
    'my_bet' => $my_bet,
    'is_my_lot' => $is_my_lot,
    'is_auth' => $user->is_auth_user()
]); ?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
