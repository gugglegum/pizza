--
-- Migration file: "changes_in_pizzas_list" created at 13.02.2015 14:56:29 (+05:00)
--

INSERT INTO `pizzas` (`id`, `title`, `description`, `url`, `image_url_large`, `image_url_medium`, `image_url_small`, `price`, `hidden`) VALUES
(29, 'Сливочный бекон', 'Бекон, сыры Моцарелла и Пармезан, оливки, красный лук и сливки.', 'http://www.eda1.ru/6/37/190/2482', 'http://www.eda1.ru/upload/images/7592f.jpg', 'http://www.eda1.ru/upload/images/7592m.jpg', 'http://www.eda1.ru/upload/images/7592.jpg', 385, 0),
(30, 'Сырная', 'Очень просто и очень вкусно: сливки, сыры Моцарелла и Пармезан.', 'http://www.eda1.ru/6/37/190/2482', 'http://www.eda1.ru/upload/images/6016f.jpg', 'http://www.eda1.ru/upload/images/6016m.jpg', 'http://www.eda1.ru/upload/images/6016.jpg', 235, 0);

UPDATE `pizzas` SET `hidden` = 1 WHERE `id` IN (1, 3, 17);
