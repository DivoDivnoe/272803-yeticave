<?php

require_once 'init.php';

if ($user->is_auth_user()) {
    send_header('Location: http://yeticave/index.php');
}

$query_categories = "SELECT * FROM `categories` ORDER BY `id`;";

$db->get_data_from_db($query_categories);
$categories = $db->get_last_query_result();

$email_post = check_email('email');
$password_post = checkTextInput('password');
$name_post = checkTextInput('name');
$contacts_post = checkTextInput('message');
$avatar_post = checkFileInput('user_file', 'avatar');
$validate_form = checkLotForm([$email_post, $password_post, $name_post, $contacts_post, $avatar_post]);
$register_result = ['class' => '', 'error' => ''];


if (isset($_POST['submit']) && !$validate_form) {
    $data = ['email' => $email_post['value'], 'name' => $name_post['value'], 'password' => password_hash($password_post['value'], PASSWORD_DEFAULT), 'avatar' => ($avatar_post['url'] ? $avatar_post['url'] : 'img/user.jpg'), 'contacts' => $contacts_post['value']];
    $has_avatar = $avatar_post['url'] ? true : false;
    $register_result = register_user($db, $data, $has_avatar);

    if (!$register_result['error']) {
        send_header('Location: http://yeticave/login.php');
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
<?= includeTemplate('templates/header.php', $user->get_user_data()); ?>
<?= includeTemplate('templates/register_main.php', [ 'email' => $email_post,
        'password' => $password_post,
        'name' => $name_post,
        'user_file' => $avatar_post,
        'contacts' => $contacts_post,
        'form_class' => $validate_form,
        'categories' => $categories ]); ?>
<?= includeTemplate('templates/footer.php', ['categories' => $categories]); ?>
</body>
</html>