<?php

/**
 * Class Categories_repository
 * Репозиторий запросов, связанных с категориями
 */
class Categories_repository extends Queries_repository
{
    /**
     * @return array массив категорий
     */
    public function get_all_categories() {
        $query_categories = "SELECT * FROM `categories` ORDER BY `id`;";
        $categories = $this->db->get_data_from_db($query_categories);

        return $categories;
    }

    /**
     * @param string $name название категории
     * @return integer id категории
     */
    public function get_categoryId_by_name($name) {
        $query_lot_category = "SELECT `id` FROM `categories` WHERE `name` = ?";
        $lot_category = $this->db->get_data_from_db($query_lot_category, [$name])[0]['id'];

        return $lot_category;
    }
}
