<?php

require_once 'Queries_repository.php';

/**
 * Class Bets_repository
 * Репозиторий запросов, связанных со ставками
 */
class BetsRepository extends QueriesRepository
{

    /**
     * находит все ставки по идентификатору лота
     * @param integer $id идентификатору лота
     * @return array массив данных о ставках
     */
    public function get_bets_by_lot_id($id)
    {
        $query_bets = "SELECT `bets`.`sum`, `bets`.`date`, `users`.`name` FROM `lots`
               LEFT JOIN `bets` ON `lots`.`id` = `bets`.`lot_id`
               INNER JOIN `users` ON `users`.`id` = `bets`.`user_id`
               WHERE `lots`.`id` = ?
               ORDER BY `bets`.`date` DESC;";
        $bets = $this->db->get_data_from_db($query_bets, [$id]);

        return $bets;
    }

    /**
     * находит ставки по email пользователя
     * @param string $email email пользователя
     * @return array массив данных о ставках
     */
    public function get_bets_by_user_email($email)
    {
        $query_bets = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`expire`, `categories`.`name`, `bets`.`sum`, `bets`.`date`, `lots`.`image` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               INNER JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `users` ON `bets`.`user_id` = `users`.`id`
               WHERE `users`.`email` = ?
               ORDER BY `bets`.`date` DESC;";
        $bets = $this->db->get_data_from_db($query_bets, [$email]);

        return $bets;
    }

    /**
     * находит ставки по идентификатору пользователя и по лоту
     * @param integer $user_id идентификатор пользователя
     * @param integer $lot_id идентификатор лота
     * @return array массив данных о ставках
     */
    public function get_bets_by_user_id_and_lot_id($user_id, $lot_id)
    {
        $query_made_bet = "SELECT * FROM `bets` WHERE `user_id` = ? AND `lot_id` = ?";
        $my_bet = $this->db->get_data_from_db($query_made_bet, [$user_id, $lot_id]);

        return $my_bet;
    }

    /**
     * добавляет новую ставку в базу данных
     * @param integer $user_id идентификатор пользователя
     * @param integer $lot_id идентификатор лота
     * @param integer $sum величина ставки
     * @return integer идентификатор новой ставки
     */
    public function add_new_bet($user_id, $lot_id, $sum)
    {
        $query_add_bet = "INSERT INTO `bets` (`user_id`, `lot_id`, `date`, `sum`)
                      VALUES (?, ?, NOW(), ?);";
        $bet_id = $this->db->insert_data_to_db($query_add_bet, [$user_id, $lot_id, $sum]);

        return $bet_id;
    }
}
