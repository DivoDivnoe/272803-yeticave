<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Categories_repository.php';
require_once 'classes/Users_repository.php';
require_once 'functions/form_validate_functions.php';
require_once 'configs/database_connect_data.php';
require_once 'functions/include_template_function.php';
require_once 'functions/user_functions.php';

session_start();

$db = new Database(...$connect_data);
$users_repository = new UsersRepository($db);
$user = new User($users_repository);
$categories_repository = new CategoriesRepository($db);

$categories = $categories_repository->get_all_categories();

$email_post = check_email('email');
$pass_post = check_text_input('password');
$validate_form = check_lot_form([$email_post, $pass_post]);

if (isset($_POST['submit']) && !$validate_form) {
    $user->auth_user($users_repository, $_POST['email'], $_POST['password']);

    if ($user->is_auth_user()) {
        header('Location: index.php');
    }
    $data = [
        'email' => $email_post,
        'pass' => $pass_post,
        'form_class' => $validate_form,
        'auth' => show_auth_user($user)
    ];

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
<?= include_template('templates/header.php', $user->get_user_data()); ?>
<?= include_template('templates/login_main.php', array_merge($data, ['categories' => $categories])); ?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
