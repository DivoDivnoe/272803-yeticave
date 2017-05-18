-- Получение списка всех категорий
SELECT `name` FROM `categories`;

-- Получение открытых лотов. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, количество ставок, название категории
SELECT `lots`.`title`, `lots`.`start_price`, `lots`.`image`, CASE WHEN MAX(`bets`.`sum`) IS NULL THEN `lots`.`start_price` ELSE MAX(`bets`.`sum`)END as `price`, COUNT(`bets`.`id`) AS `num_of_bets`, `categories`.`name` FROM `lots`
LEFT JOIN `bets` ON `bets`.`lot_id` = `lots`.`id`
INNER JOIN `categories` ON `bets`.`category_id` = `categories`.`id`
WHERE `lots`.expire > NOW()
GROUP BY `lots`.`title`
ORDER BY `lots`.`expire` DESC;

-- Найти лот по его названию или описанию
SELECT * FROM `lots`
WHERE `title` LIKE '%сноуборд%' OR `description` LIKE '%сноуборд%';

-- Добавление нового лота
INSERT INTO `lots` (`title`, `category_id`, `author_id`, `register_date`, `description`, `image`, `start_price`, `step`, `expire`)
VALUES ('Гитара Gibson Les Paul Standard', 1, 3, NOW(),
        'электрогитара c 6 струнами, фиксированный бридж TonePros™ Tune-o-matic bridge and stopbar tailpiece, 22 лада, мензура 24.75", вклеенный гриф, корпус: красное дерево, топ: клен, гриф: красное дерево, накладка: палисандр',
        'images/guitar.jpg', 30000, 1000, '2017-08-11 23:59:59');

-- Обновление названия лота по его идентификатору
UPDATE `lots`
SET `title` = 'Guitar Gibson Les Paul Standard'
WHERE `id` = 1;

-- Добавление новой ставки для лота
INSERT INTO `bets` (`user_id`, `lot_id`, `date`, `sum`)
VALUES (3, 5, NOW(), 5000);

-- Получение списка ставок для лота по его идентификатору
SELECT * FROM `bets`
WHERE `lot_id` = 1
ORDER BY `date` DESC;
