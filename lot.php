<?php
session_start();

require 'lots_array.php';
require 'functions.php';

$idIsValid = isset($_GET['lot_id']) && array_key_exists($_GET['lot_id'], $items);

if ($idIsValid):?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>DC Ply Mens 2016/2017 Snowboard</title>
        <link href="css/normalize.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
    <?= includeTemplate('templates/header.php', []); ?>
    <?= includeTemplate('templates/lot-main.php', ['bets' => $bets, 'equip_item' => $items[$_GET['lot_id']]]); ?>
    <?= includeTemplate('templates/footer.php', []); ?>
    </body>
    </html>
<?php else:
    header('HTTP/1.1 404 Not Found');
    exit;
endif;?>
