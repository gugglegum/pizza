--
-- Migration file: "initial structure" created at 01.08.2014 20:50:58 (+06:00)
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `pizza`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delivery` datetime NOT NULL COMMENT 'Доставка на дату и время',
  `created_ts` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `is_active` enum('1') DEFAULT NULL COMMENT 'Активный заказ',
  `discount` int(11) NOT NULL DEFAULT '0' COMMENT 'Фиксированная скидка на заказ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `is_active` (`is_active`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Заказы пиццы' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Структура таблицы `pizzas`
--

CREATE TABLE IF NOT EXISTS `pizzas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `url` varchar(255) DEFAULT NULL,
  `image_url_large` varchar(255) DEFAULT NULL,
  `image_url_medium` varchar(255) DEFAULT NULL,
  `image_url_small` varchar(255) DEFAULT NULL,
  `price` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Список доступных пицц' AUTO_INCREMENT=29 ;

--
-- Дамп данных таблицы `pizzas`
--

INSERT INTO `pizzas` (`id`, `title`, `description`, `url`, `image_url_large`, `image_url_medium`, `image_url_small`, `price`) VALUES
(1, 'Сытый Тони', 'Пицца с характером! Говяжья вырезка, шампиньоны, сыры Моцарелла и Пармезан, красный лук и специальный соус.\r\n251 ккал/100 г.\r\n(890 г)', 'http://www.eda1.ru/6/37/190/2040', 'http://www.eda1.ru/upload/images/3154f.jpg', 'http://www.eda1.ru/upload/images/3154m.jpg', 'http://www.eda1.ru/upload/images/3154.jpg', 655),
(2, 'Пепперони без перца', 'Непреходящая классика, вкуснейшая пицца по традиционному рецепту. Попробуйте вкус настоящей Италии!\r\n254,9 ккал/100 г.\r\n(615 г)', 'http://www.eda1.ru/6/37/190/1995', 'http://www.eda1.ru/upload/images/6666f.jpg', 'http://www.eda1.ru/upload/images/6666m.jpg', 'http://www.eda1.ru/upload/images/6666.jpg', 385),
(3, 'Чили', 'Обжигающая острота. Сыры Моцарелла и Пармезан, ветчина, бекон, помидоры, кольца красного лука… и порция настоящего пламени.', 'http://www.eda1.ru/6/37/190/2000', 'http://www.eda1.ru/upload/images/6239f.jpg', 'http://www.eda1.ru/upload/images/6239m.jpg', 'http://www.eda1.ru/upload/images/6239.jpg', 425),
(4, 'Дабл-Пицца', 'Не знаете, какую пиццу выбрать? Берите две в одной: Пепперони + Шампиньони', 'http://www.eda1.ru/6/37/190/1997', 'http://www.eda1.ru/upload/images/7441f.jpg', 'http://www.eda1.ru/upload/images/7441m.jpg', 'http://www.eda1.ru/upload/images/7441.jpg', 445),
(5, 'Как была в детстве', 'Настоящая разноцветная феерия вкуса: Сыр Моцарелла, колбаса Молочная, помидоры, корнишоны, яицо, зелень и соус Домашний. Большая пицца для дружной компании. На пышном тесте.', 'http://www.eda1.ru/6/37/190/1998', 'http://www.eda1.ru/upload/images/6198f.jpg', 'http://www.eda1.ru/upload/images/6198m.jpg', 'http://www.eda1.ru/upload/images/6198.jpg', 495),
(6, 'Амиго Мексикано', 'Изысканное сочетание сыров Моцарелла и Пармезан оттеняет острое салями и нежные шампиньоны. Четыре удовольствия в одном!', 'http://www.eda1.ru/6/37/190/1999', 'http://www.eda1.ru/upload/images/4711f.jpg', 'http://www.eda1.ru/upload/images/4711m.jpg', 'http://www.eda1.ru/upload/images/4711.jpg', 445),
(7, 'Грибная пицца', 'Грибная пицца для гурманов. Ассорти из белых грибов, шампиньонов, рыжиков и опят не оставит никого равнодушным.', 'http://www.eda1.ru/6/37/190/1996', 'http://www.eda1.ru/upload/images/6710f.jpg', 'http://www.eda1.ru/upload/images/6710m.jpg', 'http://www.eda1.ru/upload/images/6710.jpg', 435),
(8, 'Прошутто ди Парма', 'Изысканное сочетание вяленых томатов, Пармской ветчины, Рукколы и груши под сыром Пармезан и Бальзамическим уксусом. Только представьте – тонкие кусочки ветчины с мягким пряным вкусом!', 'http://www.eda1.ru/6/37/190/1813', 'http://www.eda1.ru/upload/images/8190f.jpg', 'http://www.eda1.ru/upload/images/8190m.jpg', 'http://www.eda1.ru/upload/images/8190.jpg', 575),
(9, 'с брезолой', 'Потрясающее сочетание сыровяленой говядины, салями Наполи, грибов и свежих томатов, заправленных Рукколой, моцареллой и сливочным сыром.', 'http://www.eda1.ru/6/37/190/1814', 'http://www.eda1.ru/upload/images/1798f.jpg', 'http://www.eda1.ru/upload/images/1798m.jpg', 'http://www.eda1.ru/upload/images/1798.jpg', 635),
(10, 'с угрем и гребешком', 'Великолепный коктейль из тигровых креветок, копченого угря и морского гребешка, приправленных сырами Моцарелла и Пармезан, сливками, красным луком и чесноком.', 'http://www.eda1.ru/6/37/190/1815', 'http://www.eda1.ru/upload/images/7500f.jpg', 'http://www.eda1.ru/upload/images/7500m.jpg', 'http://www.eda1.ru/upload/images/7500.jpg', 695),
(11, 'Деревенская с картофельным пюре', 'Бекон, жареный с репчатым луком, картофельное пюре, сыр моцарелла, щедрая горсть пармезана и зеленый лук. Сытный вариант для дружной компании голодных сеньоров.', 'http://www.eda1.ru/6/37/190/1441', 'http://www.eda1.ru/upload/images/1732f.jpg', 'http://www.eda1.ru/upload/images/1732m.jpg', 'http://www.eda1.ru/upload/images/1732.jpg', 475),
(12, 'Примавера', 'Классическое сочетание салями с шампиньонами, цукини, каперсами, свежими помидорами, болгарским перцем, карамелизированным луком и соусом Песто (в составе соуса присутствуют орехи).', 'http://www.eda1.ru/6/37/190/1432', 'http://www.eda1.ru/upload/images/3717f.jpg', 'http://www.eda1.ru/upload/images/3717m.jpg', 'http://www.eda1.ru/upload/images/3717.jpg', 635),
(13, 'Квадро Формаджи', 'Четыре знаменитых сыра: Моцарелла, Пармезан, Дор-блю и Чеддер.', 'http://www.eda1.ru/6/37/190/1433', 'http://www.eda1.ru/upload/images/3441f.jpg', 'http://www.eda1.ru/upload/images/3441m.jpg', 'http://www.eda1.ru/upload/images/3441.jpg', 395),
(14, 'Гавайская', 'Куриное филе, сыры Моцарелла и Пармезан среди ломтиков ананаса и киви.', 'http://www.eda1.ru/6/37/190/1434', 'http://www.eda1.ru/upload/images/9503f.jpg', 'http://www.eda1.ru/upload/images/9503m.jpg', 'http://www.eda1.ru/upload/images/9503.jpg', 415),
(15, 'Сицилия', 'Салями и бекон с болгарским перцем, помидорами, шампиньонами и карамелизированным луком.', 'http://www.eda1.ru/6/37/190/1435', 'http://www.eda1.ru/upload/images/3958f.jpg', 'http://www.eda1.ru/upload/images/3958m.jpg', 'http://www.eda1.ru/upload/images/3958.jpg', 565),
(16, 'Маргарита', 'Идентичная той, что была подана королеве Маргарите Савойской в легендарном 1889 году.', 'http://www.eda1.ru/6/37/190/1436', 'http://www.eda1.ru/upload/images/781f.jpg', 'http://www.eda1.ru/upload/images/781m.jpg', 'http://www.eda1.ru/upload/images/781.jpg', 360),
(17, '66.ру', 'Нежное куриное филе, говяжья вырезка, лук, помидоры, листья салата - все это под знаменитыми сырами Моцарелла и Пармезан. Завершает праздник вкуса наш специальный Шеф-соус.', 'http://www.eda1.ru/6/37/190/1499', 'http://www.eda1.ru/upload/images/1344f.jpg', 'http://www.eda1.ru/upload/images/1344m.jpg', 'http://www.eda1.ru/upload/images/1344.jpg', 495),
(18, 'Карбонаре', 'Благородная пицца с беконом, кольцами красного лука, свежими шампиньонами, оливками, пармезаном и моцареллой.', 'http://www.eda1.ru/6/37/190/1437', 'http://www.eda1.ru/upload/images/3363f.jpg', 'http://www.eda1.ru/upload/images/3363m.jpg', 'http://www.eda1.ru/upload/images/3363.jpg', 475),
(19, 'Пепперони', 'Отличное сочетание пикантной колбаски Пепперони и сыра Моцарелла с болгарским перцем. Для крутых перцев и их подруг!', 'http://www.eda1.ru/6/37/190/1438', 'http://www.eda1.ru/upload/images/8284f.jpg', 'http://www.eda1.ru/upload/images/8284m.jpg', 'http://www.eda1.ru/upload/images/8284.jpg', 435),
(20, 'Дель Песто', 'Наш фирменный шедевр с салями, беконом, ветчиной, болгарским перцем, помидорами Черри, шампиньонами, луком и базиликовым соусом Песто (в составе соуса присутствуют орехи).', 'http://www.eda1.ru/6/37/190/1439', 'http://www.eda1.ru/upload/images/7786f.jpg', 'http://www.eda1.ru/upload/images/7786m.jpg', 'http://www.eda1.ru/upload/images/7786.jpg', 635),
(21, 'Чикен', 'Сочетание аппетитных кусочков куриного филе, кукурузы, болгарского перца, корнишонов, сыров Моцарелла и Пармезана.', 'http://www.eda1.ru/6/37/190/1440', 'http://www.eda1.ru/upload/images/4834f.jpg', 'http://www.eda1.ru/upload/images/4834m.jpg', 'http://www.eda1.ru/upload/images/4834.jpg', 425),
(22, 'Дон Корлеоне', 'Брутальная пицца с шампиньонами, беконом, ветчиной, солеными огурцами и помидорами, которую приятно безжалостно резать огромным ножом.', 'http://www.eda1.ru/6/37/190/1443', 'http://www.eda1.ru/upload/images/8857f.jpg', 'http://www.eda1.ru/upload/images/8857m.jpg', 'http://www.eda1.ru/upload/images/8857.jpg', 475),
(23, 'Ветчина и грибы', 'Аппетитная пицца в славянском стиле с щедро нарезанными кусками вкуснейшей ветчины, свежими грибами и сочными помидорами.', 'http://www.eda1.ru/6/37/190/1445', 'http://www.eda1.ru/upload/images/2088f.jpg', 'http://www.eda1.ru/upload/images/2088m.jpg', 'http://www.eda1.ru/upload/images/2088.jpg', 445),
(24, 'с лососем', 'Базиликово-сливочный соус Песто (в составе соуса присутствуют орехи), семга и помидоры Черри с каперсами.', 'http://www.eda1.ru/6/37/190/1446', 'http://www.eda1.ru/upload/images/5698f.jpg', 'http://www.eda1.ru/upload/images/5698m.jpg', 'http://www.eda1.ru/upload/images/5698.jpg', 785),
(25, 'Шампиньони', 'Жюльен в итальянском формате из отборных шампиньонов с зеленью.', 'http://www.eda1.ru/6/37/190/1454', 'http://www.eda1.ru/upload/images/8525f.jpg', 'http://www.eda1.ru/upload/images/8525m.jpg', 'http://www.eda1.ru/upload/images/8525.jpg', 455),
(26, 'Коррида', 'Сытная пицца с экологически чистой говядиной, солеными корнишонами, фантастическим сыром Моцарелла, небольшим количеством красного лука и кунжута.', 'http://www.eda1.ru/6/37/190/1457', 'http://www.eda1.ru/upload/images/3354f.jpg', 'http://www.eda1.ru/upload/images/3354m.jpg', 'http://www.eda1.ru/upload/images/3354.jpg', 575),
(27, 'Дон Стилетто', 'Острая как нож пицца для отчаянных гангстеров. Уникальное сочетание говядины, колбасок Пепперони, корнишонов, маслин, перчика Халапеньо, сыров Моцарелла и Пармезан. И не забудьте заказать воды.', 'http://www.eda1.ru/6/37/190/1458', 'http://www.eda1.ru/upload/images/7944f.jpg', 'http://www.eda1.ru/upload/images/7944m.jpg', 'http://www.eda1.ru/upload/images/7944.jpg', 585),
(28, 'Донна Груша', 'Экзотическая пицца с фирменным сливочным соусом, свежими фруктами, сладким виноградом, грецкими орехами и молочным шоколадом.', 'http://www.eda1.ru/6/37/190/1460', 'http://www.eda1.ru/upload/images/2305f.jpg', 'http://www.eda1.ru/upload/images/2305m.jpg', 'http://www.eda1.ru/upload/images/2305.jpg', 495);

-- --------------------------------------------------------

--
-- Структура таблицы `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `pizza_id` int(10) unsigned NOT NULL,
  `pieces` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `pizza_id` (`pizza_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Заявки на куски пиццы в заказ' AUTO_INCREMENT=79 ;


-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) DEFAULT NULL,
  `password_hash` varchar(32) NOT NULL,
  `password_salt` varchar(8) NOT NULL,
  `real_name` varchar(64) NOT NULL,
  `created_ts` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `created_ts` (`created_ts`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Пользователи сайта' AUTO_INCREMENT=29 ;

--
-- Ограничения внешнего ключа таблицы `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_pizza_id` FOREIGN KEY (`pizza_id`) REFERENCES `pizzas` (`id`),
  ADD CONSTRAINT `requests_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `requests_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
