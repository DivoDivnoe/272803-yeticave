<?php

require_once 'init.php';

if (!$user->is_auth_user()) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

date_default_timezone_set('Europe/Moscow');

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";

$categories = $db->get_data_from_db($query_categories);

$options = ['Выберите категорию'];

foreach($categories as $category) {
    $options[] = $category['name'];
}

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
    $lot_category = $db->get_data_from_db($query_lot_category, [$category_post['value']]);

    $query_author_id = "SELECT `id` FROM `users` WHERE `email` = ?";
    $author_id = $this->get_data_from_db($query_author_id, [$_SESSION['email']]);

    $data = [$lot_category[0]['id'], $author_id[0]['id'], $_POST['lot-name'], $_POST['message'], $user_file_post['url'], $_POST['lot-rate'], date('Y-m-d H:i:s' ,strtotime($_POST['lot-date'])), $_POST['lot-step']];

    $query = "INSERT INTO `lots` (`category_id`, `author_id`, `register_date`, `title`, `description`, `image`, `start_price`, `expire`, `step`) 
              VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?);";
    $result = $db->insert_data_to_db($query, $data);

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
