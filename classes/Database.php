<?php

/**
 * Класс для работы с базой данных
 * @package yeticave/classes
 */

class Database
{
    /**
     * @var string имя хоста
     */
    private $host_name;

    /**
     * @var string имя пользователя
     */
    private $user;

    /**
     * @var string пароль для входа
     */
    private $password;

    /**
     * @var string имя базы данных
     */
    private $db;

    /**
     * @var resource ресурс соединения с базой данных
     */
    private $connection;

    /**
     * @var string текст последней ошибки
     */
    private $error = '';

    /**
     * @var mixed результат последнего запроса
     */
    private $last_query_result;

    /**
     * Database constructor.
     * @param array $data массив данных для соединения с базой данных
     */
    public function __construct($data)
    {
        list($this->host_name, $this->user, $this->password, $this->db) = $data;
    }

    /**
     * соединение с базой данных
     */
    public function connect_to_db()
    {
        $connection = mysqli_connect($this->host_name, $this->user, $this->password, $this->db);
        $this->connection = $connection;
        if (!$this->connection) {
            $this->error = "Ошибка соединения с базой данных. " . mysqli_connect_error();
            $this->exit_page();
        }
        $this->get_query_result('SET NAMES utf8');
    }

    /**
     * возвращает результат последнего запроса
     * @return mixed
     */
    public function get_last_query_result() {
        return $this->last_query_result;
    }

    /**
     * Выполненяет элементарные запросы
     * @param string $query
     */
    public function get_query_result($query) {
        $result = mysqli_query($this->connection, $query);
        $this->check_query_result($result);
    }

    /**
     * Прекращает выполнение скрипта и выводит на экран сообщение об ошибке
     */
    public function exit_page()
    {
        exit($this->error);
    }

    /**
     * проверяет результат элементарного запроса
     * @param mixed $result
     */
    private function check_query_result($result)
    {
        if (!$result) {
            $this->error = 'Ошибка запроса к базе данных. ' . mysqli_error($this->connection);
            $this->exit_page();
        }
    }

    /**
     * выполняет запрос с применением подготовленного выражения
     * по извлечению данные
     * @param string $query текст запроса
     * @param array $data массив с данным для вставки
     * в подготовленное выражения
     */
    public function get_data_from_db($query, $data = []) {
        $stmt = $this->db_get_prepare_stmt($query, $data);
        $result = mysqli_stmt_execute($stmt);

        $this->last_query_result = $result ? mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC) : [];
    }

    /**
     * выполняет запрос с применением подготовленного выражения
     * по вставке новых данных
     * @param string $query текст запроса
     * @param array $data массив с данным для вставки
     * в подготовленное выражения
     */
    public function insert_data_to_db($query, $data) {
        $result = mysqli_stmt_execute($this->db_get_prepare_stmt($query, $data));
        $this->last_query_result = $result ? mysqli_insert_id($this->connection) : $result;
    }

    /**
     * выполняет запрос с применением подготовленного выражения
     * по боновлению данных
     * @param string $table имя таблицы
     * @param array $data массив с данным для вставки
     * в подготовленное выражения
     * @param array $where_data массив с данным для вставки
     * в подготовленное выражения в качестве условия
     */
    public function update_db_data($table, $data, $where_data) {
        $where_columns = array_keys($where_data);
        $result_or_count = 0;

        foreach ($data as $index => $field) {
            $query = "UPDATE $table SET";

            foreach ($field as $column => $value) {
                $query .= " $column = ?,";
            }

            $query = rtrim($query, ',');
            $where_column = $where_columns[$index];
            $query .= " WHERE $where_column = ?;\n";
            $merged_data = array_merge($field, array_slice($where_data, $index, 1));
            $stmt = $this->db_get_prepare_stmt($query, $merged_data);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if (!$result) {
                $result_or_count = false;
                break;
            } else {
                $result_or_count++;
            }
        }

        $this->last_query_result =  $result_or_count;
    }

    /**
     * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
     *
     * @param $sql string SQL запрос с плейсхолдерами вместо значений
     * @param array $data Данные для вставки на место плейсхолдеров
     *
     * @return mysqli_stmt Подготовленное выражение
     */
    private function db_get_prepare_stmt($sql, $data = [])
    {
        $stmt = mysqli_prepare($this->connection, $sql);

        if(!$stmt) {
            $this->error = "Ошибка подготовки запроса: " . mysqli_error($this->connection);
            return false;
        }

        if ($data) {
            $types = '';
            $stmt_data = [];

            foreach ($data as $value) {
                $type = null;

                if (is_int($value)) {
                    $type = 'i';
                }
                else if (is_string($value)) {
                    $type = 's';
                }
                else if (is_double($value)) {
                    $type = 'd';
                }

                if ($type) {
                    $types .= $type;
                    $stmt_data[] = $value;
                }
            }

            $values = array_merge([$stmt, $types], $stmt_data);
            $func = 'mysqli_stmt_bind_param';

            if (!$func(...$values)) {
                exit("Ошибка связывания параметров запроса: " . mysqli_error($this->connection));
            }
        }

        return $stmt;
    }
}
