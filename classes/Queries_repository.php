<?php

class Queries_repository
{
    protected $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function get_all_categories() {
        $query_categories = "SELECT * FROM `categories` ORDER BY `id`;";
        $categories = $this->db->get_data_from_db($query_categories);
        
        return $categories;
    }

    public function get_categoryId_by_name($name) {
        $query_lot_category = "SELECT `id` FROM `categories` WHERE `name` = ?";
        $lot_category = $this->db->get_data_from_db($query_lot_category, [$name])[0]['id'];

        return $lot_category;
    }

    public function get_all_opened_lots() {
        $query_lots = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`, `lots`.`expire`, `categories`.`name` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`expire` > NOW()
               ORDER BY `lots`.`register_date` DESC;";
        $lots = $this->db->get_data_from_db($query_lots);

        return $lots;
    }

    public function get_lot_by_id($id) {
        $query_lot = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`description`, `lots`.`image`, `lots`.`start_price`,
               IFNULL(MAX(`bets`.`sum`), `lots`.`start_price`) as `price`,
               `lots`.`expire`, `lots`.`step`, `categories`.`name` FROM `lots`
               LEFT JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               WHERE `lots`.`id` = ? AND `lots`.`expire` > NOW()
               GROUP BY `lots`.`id`;";
        $lot = $this->db->get_data_from_db($query_lot, [$id]);

        return $lot;
    }

    public function get_bets_by_lot_id($id) {
        $query_bets = "SELECT `bets`.`sum`, `bets`.`date`, `users`.`name` FROM `lots`
               LEFT JOIN `bets` ON `lots`.`id` = `bets`.`lot_id`
               INNER JOIN `users` ON `users`.`id` = `bets`.`user_id`
               WHERE `lots`.`id` = ?
               ORDER BY `bets`.`date` DESC;";
        $bets = $this->db->get_data_from_db($query_bets, [$id]);

        return $bets;
    }

    public function add_new_bet($user_id, $lot_id, $sum) {
        $query_add_bet = "INSERT INTO `bets` (`user_id`, `lot_id`, `date`, `sum`)
                      VALUES (?, ?, NOW(), ?);";
        $bet_id = $this->db->insert_data_to_db($query_add_bet, [$user_id, $lot_id, $sum]);

        return $bet_id;
    }

    public function get_bets_by_userId_and_lotId($user_id, $lot_id) {
        $query_made_bet = "SELECT * FROM `bets` WHERE `user_id` = ? AND `lot_id` = ?";
        $my_bet = $this->db->get_data_from_db($query_made_bet, [$user_id, $lot_id]);

        return $my_bet;
    }

    public function get_lot_by_lotId_and_authorId($author_id, $lot_id) {
        $query_is_my_lot = "SELECT * FROM `lots` WHERE `author_id` = ? AND `id` = ?;";
        $is_my_lot = $this->db->get_data_from_db($query_is_my_lot, [$author_id, $lot_id]);

        return $is_my_lot;
    }

    public function get_user_by_email($email) {
        $query = "SELECT `id`, `name`, `register_date`, `avatar`, `contacts` FROM `users` WHERE `email` = ?";
        $result = $this->db->get_data_from_db($query, [$email]);

        return $result;
    }
    
    public function get_password_by_email($email) {
        $query = "SELECT `password` FROM `users` WHERE `email` = ?";
        $result = $this->db->get_data_from_db($query, [$email]);
        
        return $result;
    }

    public function get_lots_by_user_email($email) {
        $query_bets = "SELECT `lots`.`id`, `lots`.`category_id`, `lots`.`title`, `lots`.`expire`, `categories`.`name`, `bets`.`sum`, `bets`.`date`, `lots`.`image` FROM `lots` 
               INNER JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
               INNER JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
               INNER JOIN `users` ON `bets`.`user_id` = `users`.`id`
               WHERE `users`.`email` = ?
               ORDER BY `bets`.`date` DESC;";
        $bets = $this->db->get_data_from_db($query_bets, [$email]);

        return $bets;
    }

    public function add_new_lot($category_id, $author_id, $title, $description, $image, $start_price, $expire, $step) {
        $query = "INSERT INTO `lots` (`category_id`, `author_id`, `register_date`, `title`, `description`, `image`, `start_price`, `expire`, `step`) 
              VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?);";
        $result = $this->db->insert_data_to_db($query, [$category_id, $author_id, $title, $description, $image, $start_price, $expire, $step]);

        return $result;
    }
}