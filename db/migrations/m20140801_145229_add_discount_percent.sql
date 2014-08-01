--
-- Migration file: "add discount_percent" created at 01.08.2014 20:52:29 (+06:00)
--

ALTER TABLE `orders` ADD `discount_percent` FLOAT NULL COMMENT 'Процентная скидка на заказ' ;