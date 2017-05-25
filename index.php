<?php
require_once 'init.php';

date_default_timezone_set('Europe/Moscow');

$categories = $categories_queries->get_all_categories();
$lots = $lots_queries->get_all_opened_lots();

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
<?= includeTemplate('templates/header.php', $user->get_user_data()); ?>
<?= includeTemplate('templates/main.php', ['categories' => $categories, 'equip_items' => $lots, 'classes' => ['boards', 'attachment', 'boots', 'clothing', 'tools', 'other']]);?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
