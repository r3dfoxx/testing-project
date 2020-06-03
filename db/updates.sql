ALTER table `products` CHANGE COLUMN `count` `quantity` INT(11) NOT NULL;
ALTER TABLE products ADD COLUMN `image` VARCHAR(250) DEFAULT '';
RENAME TABLE orders TO cart_products;
ALTER table `cart_products` CHANGE COLUMN `date` `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

CREATE TABLE `cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

ALTER table `cart_products` CHANGE COLUMN `user_id` `cart_id` int(10) unsigned NOT NULL;
ALTER TABLE cart_products ADD COLUMN `is_deleted` tinyint(1) DEFAULT '0';

ALTER TABLE `users` ADD COLUMN `incorrect_login_attempts` int(11) DEFAULT 0;
ALTER TABLE `users` ADD COLUMN `locked_time` TIMESTAMP;
