--
-- Migration file: "orders money" created at 29.10.2014 21:04:30 (+06:00)
--

CREATE TABLE `orders_money` (
  `order_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `amount` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`, `user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Данные о сборе денег на пиццу';

ALTER TABLE `orders_money`
  ADD CONSTRAINT `orders_money_orders_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orders_money_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);