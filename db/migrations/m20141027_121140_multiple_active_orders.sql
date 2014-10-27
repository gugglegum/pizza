--
-- Migration file: "multiple active orders" created at 27.10.2014 18:11:40 (+06:00)
--

ALTER TABLE `orders` DROP `is_active`;
