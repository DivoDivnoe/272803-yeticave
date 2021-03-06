<?php

require_once 'Queries_repository.php';

/**
 * Class Users_repository
 * Репозиторий запросов, связанных с пользователями
 */
class UsersRepository extends QueriesRepository
{
    /**
     * находит пользователя по его email
     * @param string $email email пользоватеся
     * @return array массив данных о пользователе
     */
    public function get_user_by_email($email)
    {
        $query = "SELECT `id`, `name`, `register_date`, `avatar`, `contacts` FROM `users` WHERE `email` = ?";
        $result = $this->db->get_data_from_db($query, [$email])[0];

        return $result;
    }

    /**
     * находит пароль пользователя по его email
     * @param string $email email пользоватеся
     * @return string пароль пользоватеся
     */
    public function get_password_by_email($email)
    {
        $query = "SELECT `password` FROM `users` WHERE `email` = ?";
        $result = $this->db->get_data_from_db($query, [$email])[0]['password'];

        return $result;
    }

    /**
     * добавляет нового пользователя в базу данных
     * @param string $email email пользователя
     * @param string $name имя пользователя
     * @param string $password хэш пароля пользователя
     * @param string $avatar ссылка на аватар пользователя
     * @param string $contacts контактная информация о пользователе
     * @return integer id нового пользователя
     */
    public function add_new_user($email, $name, $password, $avatar, $contacts)
    {
        $query = "INSERT INTO `users` (`register_date`, `email`, `name`, `password`, `avatar`, `contacts`) VALUES (NOW(), ?, ?, ?, ?, ?);";
        $result = $this->db->insert_data_to_db($query, [$email, $name, $password, $avatar, $contacts]);

        return $result;
    }

    /**
     * проаеряет, существует ли email в базе данных
     * @param string $email email пользователя
     * @return bool
     */
    public function check_email_in_db($email)
    {
        $query = "SELECT `email` FROM `users` WHERE email = ?";
        $email_list = $this->db->get_data_from_db($query, [$email]);

        return $email_list ? true : false;
    }
}
