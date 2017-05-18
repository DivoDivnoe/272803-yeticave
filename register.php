<?php

require_once 'functions.php';

if (isset($_SESSION['name'])) {
    send_header('Location: http://yeticave/index.php');
}

$connection = connect_to_db('localhost', 'root', '', 'yeticave');

$query = "SELECT `name` FROM `categories` ORDER BY `id`;";
$categories = get_data_from_db($connection, $query);
check_query_result($connection, $categories);

$email_post = checkTextInput('email');
$password_post = checkTextInput('password');
$name_post = checkTextInput('name');
$contacts_post = checkTextInput('message');
$avatar_post = checkFileInput('user_file');
$validate_form = checkLotForm([$email_post, $password_post, $name_post, $contacts_post, $avatar_post]);
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
<?php if (!isset($_POST['submit']) || $validate_form): ?>
    <?= includeTemplate('templates/register_main.php', [ 'email' => $email_post,
        'password' => $password_post,
        'name' => $name_post,
        'user_file' => $avatar_post,
        'contacts' => $contacts_post,
        'form_class' => $validate_form ]); ?>
<?php else:
    session_start();
    $_SESSION['user'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
endif; ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>