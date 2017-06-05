<?php

/**
 * показывает ошибку в случае неудачной аутентификации
 * @param User $user объект класса пользователь
 * @return array массив с классом формы и текстом ошибки
 */
function show_auth_user(User $user)
{
    $class = 'form__item--invalid';
    $error = 'Комбинация пользователь - пароль неверна';

    if ($user->is_auth_user()) {
        $class = '';
        $error = '';
    }

    return ['class' => $class, 'error' => $error];
}

/**
 * регистрация пользователя
 * @param UsersRepository $users_repository объект запросов, связанных с пользователем
 * @param string $email поле ввода email
 * @param string $name поле ввода имени пользователя
 * @param string $password поле ввода пароля
 * @param string $avatar поле загрузки изображения пользователя
 * @param string $contacts поле ввода контактов пользователя
 * @return array массив с классом формы и текстом ошибки
 */
function register_user(UsersRepository $users_repository, $email, $name, $password, $avatar, $contacts)
{
    $class = 'form__item--invalid';
    $error = 'Пользователь с таким email уже зарегистрирован';

    $email_in_db = $users_repository->check_email_in_db($email);
    if (!$email_in_db) {
        $class = '';
        $error = '';

        $users_repository->add_new_user($email, $name, $password, $avatar, $contacts);
    }

    return ['class' => $class, 'error' => $error];
}
