<?php
require_once 'init.php';

if (!isset($_GET['search'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

date_default_timezone_set('Europe/Moscow');

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$categories = $categories_repository->get_all_categories();
$search = search($lots_repository, $_GET['search'], $page, 9);

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
<?= include_template('templates/search_main.php', ['categories' => $categories, 'search' => $search, 'page' => $_GET['page']]);?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
