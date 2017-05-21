<?php

require 'functions.php';

$connection = connect_to_db('localhost', 'root', '', 'yeticave');

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";

$categories = get_data_from_db($connection, $query_categories);
check_query_result($connection, $categories);

$email_post = check_email('email');
$pass_post = checkTextInput('password');
$validate_form = checkLotForm([$email_post, $pass_post]);

if (isset($_POST['submit']) && !$validate_form) {
    $auth_result = auth_user($connection, $_POST['email'], $_POST['password']);

    if (!$auth_result['error']) {
        send_header('Location: /index.php');
    }
    $data = ['email' => $email_post, 'pass' => $pass_post, 'form_class' => $validate_form, 'auth' => $auth_result];

} else {
    $data = ['email' => $email_post, 'pass' => $pass_post, 'form_class' => $validate_form];
} ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?= includeTemplate('templates/header.php'); ?>
<?= includeTemplate('templates/login_main.php', array_merge($data, ['categories' => $categories])); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
