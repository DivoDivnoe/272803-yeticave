<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

require 'functions.php';
date_default_timezone_set('Europe/Moscow');

$connection = connect_to_db('localhost', 'root', '', 'yeticave');

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";
$categories = get_data_from_db($connection, $query_categories);
check_query_result($connection, $categories);

$options = ['Выберите категорию'];

foreach($categories as $category) {
    $options[] = $category['name'];
}

$options = ['Выберите категорию', 'Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

$title_post = checkTextInput('lot-name');
$category_post = checkSelectInput('category', $options);
$message_post = checkTextInput('message');
$user_file_post = checkFileInput('user_file', 'img', true);
$lot_rate_post = checkNumberInput('lot-rate');
$lot_step_post = checkNumberInput('lot-step');
$lot_date_post = check_date('lot-date');
$validate_form = checkLotForm([$title_post, $category_post, $message_post, $user_file_post, $lot_rate_post, $lot_step_post, $lot_date_post]);

if (isset($_POST['submit']) && !$validate_form) {
    $query_lot_category = "SELECT `id` FROM `categories` WHERE `name` = ?";
    $lot_category = get_data_from_db($connection, $query_lot_category, [$category_post['value']]);
    check_query_result($connection, $lot_category);


    $query_author_id = "SELECT `id` FROM `users` WHERE `email` = ?";
    $author_id = get_data_from_db($connection, $query_author_id, [$_SESSION['email']]);
    check_query_result($connection, $author_id);

    $data = [$lot_category[0]['id'], $author_id[0]['id'], $_POST['lot-name'], $_POST['message'], $user_file_post['url'], $_POST['lot-rate'], date('Y-m-d H:i:s' ,strtotime($_POST['lot-date'])), $_POST['lot-step']];

    $query = "INSERT INTO `lots` (`category_id`, `author_id`, `register_date`, `title`, `description`, `image`, `start_price`, `expire`, `step`) 
              VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?);";
    $result = insert_data_to_db($connection, $query, $data);
    check_query_result($connection, $result);

    send_header("Location: http://yeticave/lot.php?lot_id=$result");
}
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
    <?= includeTemplate('templates/header.php'); ?>
    <?= includeTemplate('templates/add_main.php', [ 'title' => $title_post,
                                                'category_input' => $category_post,
                                                'message' => $message_post,
                                                'user_file' => $user_file_post,
                                                'lot_rate' => $lot_rate_post,
                                                'lot_step' => $lot_step_post,
                                                'lot_date' => $lot_date_post,
                                                'form_class' => $validate_form,
                                                'categories' => $categories ]); ?>
    <?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
