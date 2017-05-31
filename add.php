<?php

require_once 'init.php';

if (!$user->is_auth_user()) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

date_default_timezone_set('Europe/Moscow');

$categories = $categories_repository->get_all_categories();
$options = ['Выберите категорию'];

foreach($categories as $category) {
    $options[] = $category['name'];
}

$title_post = check_text_input('lot-name');
$category_post = check_select_input('category', $options);
$message_post = check_text_input('message');
$user_file_post = check_file_input('user_file', 'img', true);
$lot_rate_post = check_number_input('lot-rate');
$lot_step_post = check_number_input('lot-step');
$lot_date_post = check_date('lot-date');
$validate_form = check_lot_form([ $title_post, $category_post, $message_post, $user_file_post, $lot_rate_post,
                                  $lot_step_post, $lot_date_post ]);

if (isset($_POST['submit']) && !$validate_form) {
    $lot_category = $categories_repository->get_category_id_by_name($category_post['value']);
    $author_id = $user->get_user_data()['id'];
    $title = $title_post['value'];
    $description = $message_post['value'];
    $image = $user_file_post['url'];
    $start_price = $lot_rate_post['value'];
    $expire = date('Y-m-d H:i:s' ,strtotime($lot_date_post['value']));
    $step = $lot_step_post['value'];
    $new_lot_id = $lots_repository->add_new_lot($lot_category, $author_id, $title, $description,
                                                $image, $start_price, $expire, $step);

    header("Location: http://yeticave/lot.php?lot_id=$new_lot_id");
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
<?= include_template('templates/header.php', $user->get_user_data()); ?>
    <?= include_template('templates/add_main.php', [ 'title' => $title_post,
                                                'category_input' => $category_post,
                                                'message' => $message_post,
                                                'user_file' => $user_file_post,
                                                'lot_rate' => $lot_rate_post,
                                                'lot_step' => $lot_step_post,
                                                'lot_date' => $lot_date_post,
                                                'form_class' => $validate_form,
                                                'categories' => $categories ]); ?>
    <?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
