<?php
require_once 'init.php';
require_once 'find_winner.php';

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
<?= include_template('templates/main.php', ['categories' => $categories, 'equip_items' => $lots, 'classes' => $classes]);?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
