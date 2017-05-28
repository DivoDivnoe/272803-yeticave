<?php

if ($lots_expired = $lots_queries->get_expired_lots()) {
    foreach ($lots_expired as $index => $lot_expired) {
        $data['winner_id'] = $lot_expired['user_id'];
        $where_data['id'] = $lot_expired['id'];
        $result = $db->update_db_data('lots', $data, $where_data);

        mail("{$lot_expired['email']}", "Поздравление", "Поздравляем! Вы выиграли торги за {$lot_expired['title']}");
    }
}
