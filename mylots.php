<?php

require_once 'init.php';

if (!$user->is_auth_user()) {
    send_header('HTTP/1.1 403 Forbidden');
}

$categories = $categories_queries->get_all_categories();
$bets = $bets_queries->get_bets_by_user_email($_SESSION['email']);
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
<?= includeTemplate('templates/header.php', array_merge($user->get_user_data())); ?>
<?= includeTemplate('templates/my_lots_main.php', ['my_bets' => $bets, 'categories' => $categories]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
