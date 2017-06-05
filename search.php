<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Categories_repository.php';
require_once 'classes/Lots_repository.php';
require_once 'classes/Users_repository.php';
require_once 'configs/database_connect_data.php';
require_once 'functions/include_template_function.php';
require_once 'functions/search_function.php';
require_once 'functions/format_functions.php';

session_start();

$db = new Database(...$connect_data);
$users_repository = new UsersRepository($db);
$user = new User($users_repository);
$categories_repository = new CategoriesRepository($db);
$lots_repository = new LotsRepository($db);

if (!isset($_GET['search'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

date_default_timezone_set('Europe/Moscow');

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$categories = $categories_repository->get_all_categories();
$search = search($lots_repository, $_GET['search'], $page, 9);
$bets_string = ['ставок', 'ставка', 'ставки'];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты поиска</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<?= include_template('templates/header.php', $user->get_user_data()); ?>
<?= include_template('templates/search_main.php',
    ['categories' => $categories, 'search' => $search, 'page' => $_GET['page'], 'bets_string' => $bets_string]); ?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
