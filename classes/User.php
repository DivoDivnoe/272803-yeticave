<?php
/**
 * Класс пользователь. Содержит методы для управления
 * пользователем.
 * @package yeticave/classes
 */

class User
{
    /**
     * @var integer $id идентификатор пользователя
     */
    protected $id;
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
    protected $is_auth;
    /**
     * @var string $register_date дата регистрации пользователя
     */
    protected $register_date;

    /**
     * User constructor.
     * @param UsersRepository $users_queries объект библиотеки запросов связанных с пользователем
     */
    public function __construct(UsersRepository $users_queries)
    {
        if (isset($_SESSION['email'])) {
            $this->email = $_SESSION['email'];
            $result = $users_queries->get_user_by_email($this->email);
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
            $data = ['id' => $this->id, 'name' => $this->name, 'email' => $this->email, 'avatar' => $this->avatar, 'is_auth' => true];
        } else {
            $data = ['is_auth' => false];
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
     * @param UsersRepository $users_queries репозиторий запросов, связанных с пользователями
     * @param string $email введённый пользователем email
     * @param string $pass введённый пользователем пароль

     */
    public function auth_user(UsersRepository $users_queries, $email, $pass)
    {
        $result = $users_queries->get_password_by_email($email);

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