<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Categories_repository.php';
require_once 'classes/Lots_repository.php';
require_once 'classes/Users_repository.php';
require_once 'find_winner.php';
require_once 'configs/database_connect_data.php';
require_once 'functions/include_template_function.php';
require_once 'functions/format_functions.php';

session_start();

$db = new Database(...$connect_data);
$users_repository = new UsersRepository($db);
$user = new User($users_repository);
$categories_repository = new CategoriesRepository($db);
$lots_repository = new LotsRepository($db);

date_default_timezone_set('Europe/Moscow');

$categories = $categories_repository->get_all_categories();
$lots = $lots_repository->get_all_opened_lots();
$classes = ['boards', 'attachment', 'boots', 'clothing', 'tools', 'other'];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?= include_template('templates/header.php', $user->get_user_data()); ?>
<?= include_template('templates/main.php',
    ['categories' => $categories, 'equip_items' => $lots, 'classes' => $classes]); ?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
