<?php
/**
 * Класс пользователь. Содержит методы для управления
 * пользователем.
 * @package yeticave/classes
 */

class User
{
    /**
     * @var string $name Имя пользователя
     */
    private $name;

    /**
     * @var string $email email пользователя
     */
    private $email;

    /**
     * @var string $avatar url изображения пользователя
     */
    private $avatar;

    /**
     * @var bool $isAuth указывает, авторизован ли пользователь
     */
    private $isAuth;

    /**
     * User constructor.
     * @param Database $db объект база данных
     */
    public function __construct(Database $db)
    {
        if (isset($_SESSION['email'])) {
            $this->email = $_SESSION['email'];
            $query = "SELECT `id`, `name`, `register_date`, `avatar`, `contacts` FROM `users` WHERE `email` = ?";
            $result = $db->get_data_from_db($query, [$this->email])[0];
            $this->id = $result['id'];
            $this->name = $result['name'];
            $this->register_date = $result['register_date'];
            $this->avatar = $result['avatar'];
            $this->contacts = $result['contacts'];
            $this->isAuth = true;
        } else {
            $this->isAuth = false;
        }
    }

    /**
     * Возвращает данные о пользователе
     * @return array
     */
    public function get_user_data()
    {
        if ($this->isAuth) {
            $data = ['name' => $this->name, 'email' => $this->email, 'avatar' => $this->avatar, 'isAuth' => true];
        } else {
            $data = ['isAuth' => false];
        }
        return $data;
    }

    /**
     * возвращает значение свойства isAuth
     * @return bool
     */
    public function is_auth_user()
    {
        return $this->isAuth;
    }

    /**
     * производит аутентификацию пользователя
     * @param Database $db объект класса база данных
     * @param string $email введённый пользователем email
     * @param string $pass введённый пользователем пароль
     */
    public function auth_user(Database $db, $email, $pass)
    {
        $query = "SELECT `email`, `password`, `name`, `avatar`, `contacts` FROM `users` WHERE `email` = ?";
        $result = $db->get_data_from_db($query, [$email]);

        if ($result && password_verify($pass, $result[0]['password'])) {
            $_SESSION['email'] = $email;
            $this->isAuth = true;
        } else {
            $this->isAuth = false;
        }
    }

    /**
     * завершение сессии
     */
    public function logout()
    {
        session_unset();
    }
}