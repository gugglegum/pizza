--
-- Migration file: "alter percent column" created at 28.10.2014 03:25:41 (+06:00)
--

ALTER TABLE `orders` CHANGE `discount` `discount_absolute` INT(11) NULL DEFAULT NULL COMMENT 'Фиксированная скидка на заказ';
