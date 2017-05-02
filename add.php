<?php
require 'lots_array.php';
require 'functions.php';

    $title_post = checkTextInput('lot-name');
    $category_post = checkTextInput('category');
    $message_post = checkTextInput('message');
    $user_file_post = checkFileInput('user_file');
    $lot_rate_post = checkNumberInput('lot-rate');
    $lot_step_post = checkNumberInput('lot-step');
    $lot_date_post = checkTextInput('lot-date');
    $valid_post = checkLotForm($title_post, $category_post, $message_post, $user_file_post, $lot_rate_post, $lot_step_post, $lot_date_post);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление лота</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?= includeTemplate('templates/add_header.php'); ?>
    <?php if (!empty($valid_post)): ?>
    <?= includeTemplate('templates/add_main.php', [ 'title' => $title_post,
                                                'category' => $category_post,
                                                'message' => $message_post,
                                                'user_file' => $user_file_post,
                                                'lot_rate' => $lot_rate_post,
                                                'lot_step' => $lot_step_post,
                                                'lot_date' => $lot_date_post,
                                                'valid' => $valid_post ]); ?>
    <?php else: ?>
    <?php array_push($items, ['title' => $_POST['lot-name'], 'category' => $_POST['category'], 'price' => $_POST['lot-rate'], 'url' => "img/{$_FILES['user_file']['name']}"]); ?>
    <?= includeTemplate('templates/lot-main.php', ['bets' => $bets, 'equip_item' => $items[count($items) - 1]]); ?>
    <?php endif; ?>
    <?= includeTemplate('templates/footer.php'); ?>
</body>
</html>
