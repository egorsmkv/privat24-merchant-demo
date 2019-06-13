-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `product_id` int(11) NOT NULL COMMENT 'Product''s ID',
  `created_at` datetime NOT NULL COMMENT 'Created At',
  `payment_data` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Returned payment data from Privat24',
  `order_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Order ID',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payments';


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Description',
  `amount` decimal(10,2) NOT NULL COMMENT 'Amount',
  `currency` enum('UAH','USD','EUR') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Currency',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='List of products';

INSERT INTO `products` (`id`, `name`, `description`, `amount`, `currency`) VALUES
(1,	'Телевизор LG',	'Хороший телевизор от компании LG',	5000.00,	'UAH'),
(2,	'Пульт для телевизора',	'Удобный пульт',	5.00,	'USD'),
(3,	'Булавка',	'Очень маленькая',	0.01,	'EUR');

-- 2019-06-13 22:57:46
