<?php

require 'functions.php';
require 'userdata.php';

$email_post = checkTextInput('email');
$pass_post = checkTextInput('password');
$validate_form = checkLotForm([$email_post, $pass_post]);

if (isset($_POST['submit']) && !$validate_form) {
    $auth_form = authUser($_POST['email'], $_POST['password'], $users);

    if (!$auth_form['error']) {
        header('Location: /index.php');
        exit;
    }
    $data = ['email' => $email_post, 'pass' => $pass_post, 'form_class' => $validate_form, 'auth' => $auth_form];

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
<?= includeTemplate('templates/login_main.php', $data); ?>
<?= includeTemplate('templates/footer.php'); ?>
</body>
</html>
