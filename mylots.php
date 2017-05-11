<?php
require 'functions.php';
require 'lots_array.php';

session_start();
if (!isset($_SESSION['user'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

$cookies = [];

if (isset($_COOKIE['my_bets'])) {
    foreach ($_COOKIE['my_bets'] as $id => $my_bet) {
        $cookies[$id] = json_decode($my_bet, true);
    }
}
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
<?= includeTemplate('templates/header.php'); ?>
<?= includeTemplate('templates/my_lots_main.php', ['my_bets' => $cookies, 'equip_items' => $items]); ?>
<?= includeTemplate('templates/footer.php'); ?>
</body>
</html>
