-- Получение списка всех категорий
SELECT `name` FROM categories;

-- Получение открытых лотов. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, количество ставок, название категории
SELECT title, start_price, image, sum, COUNT(*) AS num_of_bets, `name` FROM lots
INNER JOIN bets ON lot_id = lots.id
INNER JOIN categories ON categories_id = categories.id
WHERE expire > NOW()
GROUP BY title;

-- Найти лот по его названию или описанию
SELECT * FROM lots
WHERE title LIKE '%сноуборд%' OR description LIKE '%легкий%маневренный%';

-- Добавление нового лота
INSERT INTO lots (title, category_id, author_id, register_date, description, image, start_price, step, expire)
VALUES ('Гитара Gibson Les Paul Standard', (SELECT id FROM categories WHERE `name` = 'Хобби'), (SELECT id FROM users WHERE email = 'my_email'), NOW(),
        'электрогитара c 6 струнами, фиксированный бридж TonePros™ Tune-o-matic bridge and stopbar tailpiece, 22 лада, мензура 24.75", вклеенный гриф, корпус: красное дерево, топ: клен, гриф: красное дерево, накладка: палисандр',
        'images/guitar.jpg', 30000, 1000, '2017-08-11 23:59:59');

-- Обновление названия лота по его идентификатору
UPDATE lots
SET title = 'Guitar Gibson Les Paul Standard'
WHERE id = 1;

-- Добавление новой ставки для лота
INSERT INTO bets (user_id, lot_id, `date`, `sum`)
VALUES ((SELECT id FROM users WHERE email = 'my_email'), (SELECT id FROM lots WHERE title = 'Маска Oakley Canopy'), NOW(), 5000);

-- Получение списка ставок для лота по его идентификатору
SELECT * FROM bets
WHERE lot_id = 1;
