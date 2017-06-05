<?php

/**
 * осуществляет поиск в базе данных по ключевому слову
 * @param LotsRepository $lots_queries объект запросов, связанных с лотами
 * @param string $search_query строка запроса для поиска
 * @param string $page текущая страница
 * @param integer $lots_per_page количество лотов на странице
 * @return array массив с результатом поиска, общим количеством лотов, строкой запроса и количеством страниц
 */
function search(LotsRepository $lots_queries, $search_query, $page, $lots_per_page)
{
    $query = trim($search_query);
    $num_of_lots = $lots_queries->get_num_of_lots_by_key($query);
    $num_of_pages = ceil($num_of_lots / $lots_per_page);
    $result = [];

    if ($query) {
        $result = $lots_queries->get_lots_by_key($query, ($page - 1) * $lots_per_page, $lots_per_page);
    }

    return ['result' => $result, 'num_of_lots' => $num_of_lots, 'query' => $query, 'num_of_pages' => $num_of_pages];
}
