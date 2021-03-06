<?php

require_once 'Queries_repository.php';

/**
 * Class Lots_repository
 * Класс запросов, связанных с лотами
 */
class LotsRepository extends QueriesRepository
{
    /**
     * находит все открытые лоты
     * @return array массив данных о всех открытых лотах
     */
    public function get_all_opened_lots()
    {
        $query_lots = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`, `lots`.`expire`, `categories`.`name` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`expire` > NOW()
               ORDER BY `lots`.`register_date` DESC;";
        $lots = $this->db->get_data_from_db($query_lots);

        return $lots;
    }

    /**
     * находит все открытые лоты определенной категории
     * @param integer $category_id идентификатор категории
     * @param integer $offset отступ
     * @param integer $limit максимальное количество выводимых даннх
     * @return array массив данных о всех открытых лотах, либо пустой массив
     */

    public function get_all_opened_lots_by_category_id($category_id, $offset, $limit)
    {
        $query_lots = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`, `lots`.`expire`, `categories`.`name`, COUNT(`bets`.`id`) as `num_of_bets` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               LEFT JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               WHERE `lots`.`expire` > NOW() AND `categories`.`id` = ?
               GROUP BY `lots`.`id`
               ORDER BY `lots`.`register_date` DESC
               LIMIT ?, ?;";

        $lots = $this->db->get_data_from_db($query_lots, [$category_id, $offset, $limit]);

        return $lots;
    }

    /**
     * находит лот по его идентификатору
     * @param integer $id идентификатор лота
     * @return array массив данных о лоте, либо пустой массив
     */
    public function get_lot_by_id($id)
    {
        $query_lot = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`,
               IFNULL(MAX(`bets`.`sum`), `lots`.`start_price`) as `price`,
               `lots`.`expire`, `lots`.`step`, `categories`.`name` FROM `lots`
               LEFT JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`id` = ? AND `lots`.`expire` > NOW()
               GROUP BY `lots`.`id`;";
        $lot = $this->db->get_data_from_db($query_lot, [$id])[0];

        return $lot;
    }

    /**
     * находит лот по его идентификатору и идентификатору его автора, другими словами, проверяет,
     * принадлежит ли лот определенному пользователю
     * @param integer $author_id идентификатор автора лота
     * @param integer $lot_id идентификатор лота
     * @return array массив данных о лоте, либо пустой массив
     */
    public function get_lot_by_lot_id_and_author_id($author_id, $lot_id)
    {
        $query_is_my_lot = "SELECT * FROM `lots` WHERE `author_id` = ? AND `id` = ?;";
        $is_my_lot = $this->db->get_data_from_db($query_is_my_lot, [$author_id, $lot_id]);

        return $is_my_lot;
    }

    /**
     * добавляем новый лот в базу данных
     * @param integer $category_id идентификатор категории
     * @param integer $author_id идентификатор автора лота
     * @param string $title название лота
     * @param string $description описание лота
     * @param string $image ссылка на изображение лота
     * @param string $start_price стартовая цена лота
     * @param string $expire дата окончания торгов за лот
     * @param string $step шаг ставки
     * @return integer id идентификатор лота
     */
    public function add_new_lot($category_id, $author_id, $title, $description, $image, $start_price, $expire, $step)
    {
        $query = "INSERT INTO `lots` (`category_id`, `author_id`, `register_date`, `title`, `description`, `image`, `start_price`, `expire`, `step`) 
              VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?);";
        $result = $this->db->insert_data_to_db($query,
            [$category_id, $author_id, $title, $description, $image, $start_price, $expire, $step]);

        return $result;
    }

    /**
     * осуществляет выборку лотов из базы данных по ключевым словам
     * @param string $key ключ поиска
     * @return integer количество лотов, удовлетворяющих поисковому запросу
     */
    public function get_num_of_lots_by_key($key)
    {
        $query_lots = "SELECT COUNT(*) as `num_of_lots` FROM `lots` 
                       WHERE (`title` LIKE ? OR `description` LIKE ?) AND `expire` > NOW();";
        $lots = $this->db->get_data_from_db($query_lots, ["%$key%", "%$key%"])[0]['num_of_lots'];

        return $lots;
    }

    /**
     * находит количество лотов по идентификатору категории
     * @param integer $category_id идентификатор категории
     * @return integer mixed количество лотов
     */
    public function get_num_of_lots_by_category_id($category_id)
    {
        $query_lots = "SELECT COUNT(*) as `num_of_lots` FROM `lots`
                       INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
                       WHERE `categories`.`id` = ? AND `expire` > NOW();";
        $lots = $this->db->get_data_from_db($query_lots, [$category_id])[0]['num_of_lots'];

        return $lots;
    }

    /**
     * находит лоты по ключевому слову
     * @param string $key ключевое слово
     * @param integer $offset отступ
     * @param integer $num количество лотов в выборке
     * @return array массив с результатом
     */
    public function get_lots_by_key($key, $offset, $num)
    {
        $query_lots = "SELECT `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`id`, `lots`.`expire`, COUNT(`bets`.`id`) as `num_of_bets`, `lots`.`start_price` FROM `lots` 
                       LEFT JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
                       WHERE (`lots`.`title` LIKE ? OR `lots`.`description` LIKE ?) AND `lots`.`expire` > NOW()
                       GROUP BY `lots`.`id`
                       LIMIT ?, ?;";
        $lots = $this->db->get_data_from_db($query_lots, ["%$key%", "%$key%", $offset, $num]);

        return $lots;
    }

    /**
     * находит простроченные лоты
     * @return array массив просроченных лотов
     */
    public function get_expired_lots()
    {
        $query_lots = "SELECT `lots`.`id`, `bets`.`user_id`, `bets`.`sum`, `users`.`email`, `lots`.`title` FROM `lots`
               LEFT JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `users` ON `bets`.`user_id` = `users`.`id`
               WHERE `lots`.`expire` <= NOW() AND `lots`.`winner_id` IS NULL
               ORDER BY `lots`.`expire` DESC";
        $lot = $this->db->get_data_from_db($query_lots);

        return $lot;
    }
}
