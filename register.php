<?php

require_once 'functions.php';

session_start();

if (isset($_SESSION['user'])) {
    send_header('Location: http://yeticave/index.php');
}

$connection = connect_to_db('localhost', 'root', '', 'yeticave');

$query = "SELECT `name` FROM `categories` ORDER BY `id`;";
$categories = get_data_from_db($connection, $query);
check_query_result($connection, $categories);

$email_post = check_email($connection, 'email', false);
$password_post = checkTextInput('password');
$name_post = checkTextInput('name');
$contacts_post = checkTextInput('message');
$avatar_post = checkFileInput('user_file', 'avatar');
$validate_form = checkLotForm([$email_post, $password_post, $name_post, $contacts_post, $avatar_post]);

if (isset($_POST['submit']) && !$validate_form) {
    $data = ['email' => $_POST['email'], 'name' => $_POST['name'], 'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), 'avatar' => $avatar_post['url'], 'contacts' => $_POST['message']];
    $has_avatar = $avatar_post['url'] ? true : false;
    $register_result = register_user($connection, $data, $has_avatar);

    if (!$register_result['error']) {
        send_header('Location: http://yeticave/login.php');
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
<?= includeTemplate('templates/header.php'); ?>
<?= includeTemplate('templates/register_main.php', [ 'email' => $email_post,
        'password' => $password_post,
        'name' => $name_post,
        'user_file' => $avatar_post,
        'contacts' => $contacts_post,
        'form_class' => $validate_form ]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>