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
    protected $email;

    /**
     * @var string $avatar url изображения пользователя
     */
    protected $avatar;

    /**
     * @var bool $isAuth указывает, авторизован ли пользователь
     */
    protected $isAuth;

    /**
     * User constructor.
     * @param array $data массив данных о пользователе
     */
    public function __construct($data)
    {
        list($this->name, $this->email, $this->avatar, $this->isAuth) = $data;
    }

    /**
     * Возвращает данные о пользователе
     * @return array
     */
    public function get_user_data()
    {
        if ($this->isAuth) {
            $data = ['name' => $this->name, 'email' => $this->email, 'avatar' => $this->avatar];
        } else {
            $data = [];
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
     * @param $email введённый пользователем email
     * @param $pass введённый пользователем пароль
     */
    public function auth_user(Database $db, $email, $pass)
    {
        $query = "SELECT `email`, `password`, `name`, `avatar`, `contacts` FROM `users` WHERE `email` = ?";
        $db->get_data_from_db($query, [$email]);
        $result = $db->get_last_query_result();

        if ($result && password_verify($pass, $result[0]['password'])) {
            $_SESSION['user'] = $result[0]['name'];
            $_SESSION['email'] = $email;
            $_SESSION['avatar']= $result[0]['avatar'] ? $result[0]['avatar'] : 'user.jpg';
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