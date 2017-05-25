<?php

/**
 * Class Lots_repository
 * Класс запросов, связанных с лотами
 */
class Lots_repository extends Queries_repository
{
    /**
     * находит все открытые лоты
     * @return array
     */
    public function get_all_opened_lots() {
        $query_lots = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`, `lots`.`expire`, `categories`.`name` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`expire` > NOW()
               ORDER BY `lots`.`register_date` DESC;";
        $lots = $this->db->get_data_from_db($query_lots);

        return $lots;
    }

    /**
     * находит лот по его идентификатору
     * @param $id
     * @return array
     */
    public function get_lot_by_id($id) {
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
    public function get_lot_by_lotId_and_authorId($author_id, $lot_id) {
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
    public function add_new_lot($category_id, $author_id, $title, $description, $image, $start_price, $expire, $step) {
        $query = "INSERT INTO `lots` (`category_id`, `author_id`, `register_date`, `title`, `description`, `image`, `start_price`, `expire`, `step`) 
              VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?);";
        $result = $this->db->insert_data_to_db($query, [$category_id, $author_id, $title, $description, $image, $start_price, $expire, $step]);

        return $result;
    }
}
