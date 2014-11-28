--
-- Migration file: "add column hidden to pizzas" created at 28.11.2014 11:44:05 (+05:00)
--

ALTER TABLE  `pizzas` ADD  `hidden` TINYINT UNSIGNED NOT NULL DEFAULT  '0' COMMENT  'Скрытая/удалённая пицца, недоступна для заказа',
ADD INDEX (  `hidden` );
