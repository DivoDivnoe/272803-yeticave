<?php
require_once 'init.php';

if (!isset($_GET['category_id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$category_id = $_GET['category_id'];
$categories = $categories_queries->get_all_categories();
$lots = $lots_queries->get_all_opened_lots_by_category_id($category_id, ($page - 1) * 9, 9);
$num_of_pages = ceil($lots_queries->get_num_of_lots_by_category_id($category_id) / 9);

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
<?= includeTemplate('templates/header.php', $user->get_user_data()); ?>
<?= includeTemplate('templates/all_lots_main.php', ['categories' => $categories, 'category_id' => $category_id, 'lots' => $lots, 'num_of_pages' => $num_of_pages, 'page' => $page]);?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>