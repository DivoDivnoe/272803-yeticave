<?php

require_once 'init.php';

date_default_timezone_set('Europe/Moscow');

$db->connect_to_db();

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";

$db->get_data_from_db($query_categories);
$categories = $db->get_last_query_result();

/*if (isset($_GET['search']) && !search($connection)['error']) {
    $lots = search($connection)['result'];
} else {*/
    $query_lots = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`, `lots`.`expire`, `categories`.`name` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`expire` > NOW()
               ORDER BY `lots`.`register_date` DESC;";
    $db->get_data_from_db($query_lots);
    $lots = $db->get_last_query_result();
/*}*/

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
<?php if (isset($_SESSION['user'])): ?>
<?= includeTemplate('templates/header.php'); ?>
<?php else: ?>
<?= includeTemplate('templates/header.php'); ?>
<?php endif; ?>
<?= includeTemplate('templates/main.php', ['categories' => $categories, 'equip_items' => $lots, 'classes' => ['boards', 'attachment', 'boots', 'clothing', 'tools', 'other']]);?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>

<?php mysqli_close($connection); ?>