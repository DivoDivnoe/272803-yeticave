<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Categories_repository.php';
require_once 'classes/Bets_repository.php';
require_once 'classes/Users_repository.php';
require_once 'configs/database_connect_data.php';
require_once 'functions/include_template_function.php';
require_once 'functions/format_functions.php';

session_start();

$db = new Database(...$connect_data);
$users_repository = new UsersRepository($db);
$user = new User($users_repository);
$categories_repository = new CategoriesRepository($db);
$bets_repository = new BetsRepository($db);

if (!$user->is_auth_user()) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

$categories = $categories_repository->get_all_categories();
$bets = $bets_repository->get_bets_by_user_email($_SESSION['email']);
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
<?= include_template('templates/header.php', $user->get_user_data()); ?>
<?= include_template('templates/my_lots_main.php', ['my_bets' => $bets, 'categories' => $categories]); ?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
