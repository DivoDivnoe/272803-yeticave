<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Categories_repository.php';
require_once 'classes/Lots_repository.php';
require_once 'classes/Users_repository.php';
require_once 'configs/database_connect_data.php';
require_once 'functions/include_template_function.php';
require_once 'functions/format_functions.php';

session_start();

$db = new Database(...$connect_data);
$users_repository = new UsersRepository($db);
$user = new User($users_repository);
$categories_repository = new CategoriesRepository($db);
$lots_repository = new LotsRepository($db);

if (!isset($_GET['category_id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$category_id = $_GET['category_id'];
$categories = $categories_repository->get_all_categories();
$lots = $lots_repository->get_all_opened_lots_by_category_id($category_id, ($page - 1) * 9, 9);
$num_of_pages = ceil($lots_repository->get_num_of_lots_by_category_id($category_id) / 9);
$bet_strings = ['ставок', 'ставка', 'ставки'];

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
<?= include_template('templates/all_lots_main.php', [
    'categories' => $categories,
    'category_id' => $category_id,
    'lots' => $lots,
    'num_of_pages' => $num_of_pages,
    'page' => $page,
    'bet_strings' => $bet_strings
]); ?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
