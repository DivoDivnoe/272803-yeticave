<?php

require_once 'init.php';

if ($user->is_auth_user()) {
    send_header('Location: http://yeticave/index.php');
}

$categories = $categories_repository->get_all_categories();

$email_post = check_email('email');
$password_post = check_text_input('password');
$name_post = check_text_input('name');
$contacts_post = check_text_input('message');
$avatar_post = check_file_input('user_file', 'avatar');
$validate_form = check_lot_form([$email_post, $password_post, $name_post, $contacts_post, $avatar_post]);
$register_result = ['class' => '', 'error' => ''];


if (isset($_POST['submit']) && !$validate_form) {
    $email = $email_post['value'];
    $name = $name_post['value'];
    $password = password_hash($password_post['value'], PASSWORD_DEFAULT);
    $avatar = $avatar_post['url'] ? $avatar_post['url'] : 'img/user.jpg';
    $contacts = $contacts_post['value'];
    $register_result = register_user($users_repository, $email, $name, $password, $avatar, $contacts);

    if (!$register_result['error']) {
        header('Location: http://yeticave/login.php');
    } else {
        $email_post['error'] = $register_result['error'];
        $email_post['class'] = $register_result['class'];
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
<?= include_template('templates/register_main.php', [ 'email' => $email_post,
        'password' => $password_post,
        'name' => $name_post,
        'user_file' => $avatar_post,
        'contacts' => $contacts_post,
        'form_class' => $validate_form,
        'categories' => $categories ]); ?>
<?= include_template('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>