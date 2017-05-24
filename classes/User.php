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
    protected $name;

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
     * @var string $register_date дата регистрации пользователя
     */
    protected $register_date;

    /**
     * User constructor.
     * @param Queries_repository $query_result объект библиотеки запросов
     */
    public function __construct(Queries_repository $query_result)
    {
        if (isset($_SESSION['email'])) {
            $this->email = $_SESSION['email'];
            $result = $query_result->get_user_by_email($this->email)[0];
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
     * @param Queries_repository $query_result
     * @param string $email введённый пользователем email
     * @param string $pass введённый пользователем пароль

     */
    public function auth_user(Queries_repository $query_result, $email, $pass)
    {
        $result = $query_result->get_password_by_email($email)[0]['password'];

        if ($result && password_verify($pass, $result)) {
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