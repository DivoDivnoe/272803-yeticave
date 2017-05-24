<?php

require_once 'init.php';

$categories = $query_result->get_all_categories();

$email_post = check_email('email');
$pass_post = checkTextInput('password');
$validate_form = checkLotForm([$email_post, $pass_post]);

if (isset($_POST['submit']) && !$validate_form) {
    $user->auth_user($query_result, $_POST['email'], $_POST['password']);

    if ($user->is_auth_user()) {
        header('Location: /index.php');
    }
    $data = ['email' => $email_post, 'pass' => $pass_post, 'form_class' => $validate_form, 'auth' => show_auth_user($user)];

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
<?= includeTemplate('templates/header.php', $user->get_user_data()); ?>
<?= includeTemplate('templates/login_main.php', array_merge($data, ['categories' => $categories])); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
