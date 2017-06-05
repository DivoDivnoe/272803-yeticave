<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Categories_repository.php';
require_once 'classes/Users_repository.php';
require_once 'classes/Users_repository.php';
require_once 'functions/form_validate_functions.php';
require_once 'functions/user_functions.php';
require_once 'functions/include_template_function.php';
require_once 'configs/database_connect_data.php';

session_start();

$db = new Database(...$connect_data);
$users_repository = new UsersRepository($db);
$user = new User($users_repository);
$categories_repository = new CategoriesRepository($db);

if ($user->is_auth_user()) {
    header('Location: index.php');
}

$categories = $categories_repository->get_all_categories();

$email_post = check_email('email');
$password_post = check_text_input('password');
$name_post = check_text_input('name');
$contacts_post = check_text_input('message');
$avatar_post = check_file_input('user_file');
$validate_form = check_lot_form([$email_post, $password_post, $name_post, $contacts_post, $avatar_post, $avatar_post]);
$register_result = ['class' => '', 'error' => ''];


if (isset($_POST['submit']) && !$validate_form) {
    $avatar_post = move_uploaded_image($_FILES['user_file'], 'avatar');

    if (!$avatar_post['error']) {
        $email = $email_post['value'];
        $name = $name_post['value'];
        $password = password_hash($password_post['value'], PASSWORD_DEFAULT);
        $avatar = $avatar_post['url'] ? $avatar_post['url'] : 'img/user.jpg';
        $contacts = $contacts_post['value'];
        $register_result = register_user($users_repository, $email, $name, $password, $avatar, $contacts);

        if (!$register_result['error']) {
            header('Location: login.php');
        } else {
            $email_post['error'] = $register_result['error'];
            $email_post['class'] = $register_result['class'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<?= include_template('templates/header.php', $user->get_user_data()); ?>
<?= include_template('templates/register_main.php', [
    'email' => $email_post,
    'password' => $password_post,
    'name' => $name_post,
    'user_file' => $avatar_post,
    'contacts' => $contacts_post,
    'form_class' => $validate_form ? $validate_form : move_uploaded_image($_FILES['user_file'], 'avatar'),
    'categories' => $categories
]); ?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>
