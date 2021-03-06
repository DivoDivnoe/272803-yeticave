USE `yeticave`;

SET NAMES utf8;

INSERT INTO `categories` (`name`)
VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');

INSERT INTO `users` (`register_date`, `email`, `name`, `password`)
VALUES (NOW(), 'ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'),
       (NOW(), 'kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'),
       (NOW(), 'warrior07@mail.ru', 'Руслан', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW');

INSERT INTO `lots` (`category_id`, `author_id`, `register_date`, `title`, `description`, `image`, `start_price`, `expire`, `step`)
VALUES (1, 1, NOW(), '2014 Rossignol District Snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами.
Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь,
крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
'img/lot-1.jpg', 10999, '2017-08-11 23:59:59', 500),
       (1, 1, NOW(), 'DC Ply Mens 2016/2017 Snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами.
Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь,
крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
   'img/lot-2.jpg', 159999, '2017-05-31 23:59:59', 2000),
       (2, 2, NOW(), 'Крепления Union Contact Pro 2015 года размер L/XL', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами.
Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь,
крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
   'img/lot-3.jpg', 8000, '2017-06-30 23:59:59', 1000),
       (3, 3, NOW(), 'Ботинки для сноуборда DC Mutiny Charocal', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами.
Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь,
крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
   'img/lot-4.jpg', 10999, '2017-07-19 23:59:59', 500),
       (4, 3, NOW(), 'Куртка для сноуборда DC Mutiny Charocal', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами.
Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь,
крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
   'img/lot-5.jpg', 7500, '2017-06-12 23:59:59', 1000),
       (5, 2, NOW(), 'Маска Oakley Canopy', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами.
Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь,
крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
   'img/lot-6.jpg', 5400, '2017-09-12 23:59:59', 200);

INSERT INTO `bets` (`user_id`, `lot_id`, `date`, `sum`)
VALUES (2, 1, '2017-05-10 13:07:52', 11500),
       (1, 3, '2017-05-11 19:17:35', 11000),
       (2, 5, '2017-04-27 03:46:20', 10500),
       (3, 6, '2017-05-12 09:00:00', 10000);
