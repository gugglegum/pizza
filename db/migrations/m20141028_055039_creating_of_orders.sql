--
-- Migration file: "creating of orders" created at 28.10.2014 11:50:39 (+06:00)
--

ALTER TABLE `orders` ADD `creator` INT(10) UNSIGNED NOT NULL COMMENT 'Создатель заказа' AFTER `status`, ADD INDEX (`creator`);
UPDATE `orders` SET `creator` = 1;
ALTER TABLE `orders` ADD FOREIGN KEY (`creator`) REFERENCES `pizza`.`users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `orders` ADD `note` TEXT NULL DEFAULT NULL COMMENT 'Примечание к заказу';
