<?php
session_start();

require 'lots_array.php';
require 'functions.php';

date_default_timezone_set('Europe/Moscow');
$cost_post = checkNumberInput('cost');
$idIsValid = false;

if (isset($_GET['lot_id'])) {
    $id_get = $_GET['lot_id'];

    if (isset($_POST['cost']) && !$cost_post['error']) {
        addBet($_POST['cost'], $id_get);
        header('Location: mylots.php');
        exit;
    }
    $idIsValid = array_key_exists($id_get, $items);
}

if (!$idIsValid) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$connection = mysqli_connect('localhost', 'root', '', 'yeticave');
if (mysqli_connect_errno()) {
    exit("Ошибка соединения с базой данных. " . mysqli_connect_error());
}

$query = "SELECT `name` FROM `categories` ORDER BY `id`;";

$categories = get_data_from_db($connection, $query);
if (!$categories) {
    exit('Ошибка запроса к базе данных. ' . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>DC Ply Mens 2016/2017 Snowboard</title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?= includeTemplate('templates/header.php'); ?>
<?= includeTemplate('templates/lot-main.php', ['bets' => $bets, 'equip_item' => $items[$id_get], 'id' => $id_get, 'cost' => $cost_post, 'categories' => $categories]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
