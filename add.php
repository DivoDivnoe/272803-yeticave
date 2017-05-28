<?php

require_once 'init.php';

if (!$user->is_auth_user()) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

date_default_timezone_set('Europe/Moscow');

$categories = $categories_queries->get_all_categories();
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
    $lot_category = $categories_queries->get_categoryId_by_name($category_post['value']);
    $author_id = $user->get_user_data()['id'];
    $title = $title_post['value'];
    $description = $message_post['value'];
    $image = $user_file_post['url'];
    $start_price = $lot_rate_post['value'];
    $expire = date('Y-m-d H:i:s' ,strtotime($lot_date_post['value']));
    $step = $lot_step_post['value'];
    $new_lot_id = $lots_queries->add_new_lot($lot_category, $author_id, $title, $description, $image, $start_price, $expire, $step);

    send_header("Location: http://yeticave/lot.php?lot_id=$new_lot_id");
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
<?= includeTemplate('templates/header.php', array_merge($user->get_user_data())); ?>
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
