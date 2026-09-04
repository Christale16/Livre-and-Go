-- Base de données LivraBen
-- Importer ce fichier dans phpMyAdmin / MySQL.
CREATE DATABASE IF NOT EXISTS `livraben` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `livraben`;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('client','livreur','admin') NOT NULL DEFAULT 'client',
  `vehicle_type` varchar(255) DEFAULT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `current_lat` decimal(10,7) DEFAULT NULL,
  `current_lng` decimal(10,7) DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL, `token` varchar(255) NOT NULL, `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL, `user_id` bigint unsigned DEFAULT NULL, `ip_address` varchar(45) DEFAULT NULL, `user_agent` text, `payload` longtext NOT NULL, `last_activity` int NOT NULL,
  PRIMARY KEY (`id`), KEY `sessions_user_id_index` (`user_id`), KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL, `livreur_id` bigint unsigned DEFAULT NULL,
  `pickup_address` varchar(255) NOT NULL, `pickup_lat` decimal(10,7) DEFAULT NULL, `pickup_lng` decimal(10,7) DEFAULT NULL,
  `delivery_address` varchar(255) NOT NULL, `delivery_lat` decimal(10,7) DEFAULT NULL, `delivery_lng` decimal(10,7) DEFAULT NULL,
  `description` text, `price` decimal(8,2) DEFAULT NULL,
  `status` enum('pending','accepted','refused','in_progress','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `orders_client_id_foreign` (`client_id`), KEY `orders_livreur_id_foreign` (`livreur_id`),
  CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_livreur_id_foreign` FOREIGN KEY (`livreur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `orders`
  ADD COLUMN `payment_method` enum('mtn_momo','moov_celtiis','bank_transfer') DEFAULT NULL AFTER `price`,
  ADD COLUMN `payment_reference` varchar(255) DEFAULT NULL AFTER `payment_method`,
  ADD COLUMN `payment_status` enum('non_paye','paye') NOT NULL DEFAULT 'non_paye' AFTER `payment_reference`;

CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT, `order_id` bigint unsigned NOT NULL, `sender_id` bigint unsigned NOT NULL, `receiver_id` bigint unsigned NOT NULL, `content` text NOT NULL, `read_at` timestamp NULL DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `messages_order_id_foreign` (`order_id`), KEY `messages_sender_id_foreign` (`sender_id`), KEY `messages_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT, `migration` varchar(255) NOT NULL, `batch` int NOT NULL, PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`,`batch`) VALUES
('2024_01_01_000000_create_users_table',1),
('2024_01_02_000000_create_orders_table',1),
('2024_01_03_000000_create_messages_table',1);

-- Comptes de démonstration : mot de passe = password
INSERT INTO `users` (`name`,`email`,`phone`,`password`,`role`,`vehicle_type`,`is_online`,`current_lat`,`current_lng`,`created_at`,`updated_at`) VALUES
('Administrateur','admin@livraben.test','9700000000','$2y$12$87uvV8jBNXAELBFh6q2ARetB7t7JjWMRIM8J9rThMJrFjJdQ03ipC','admin',NULL,0,NULL,NULL,NOW(),NOW()),
('Client Démo','client@livraben.test','9700000001','$2y$12$87uvV8jBNXAELBFh6q2ARetB7t7JjWMRIM8J9rThMJrFjJdQ03ipC','client',NULL,0,NULL,NULL,NOW(),NOW()),
('Livreur Démo','livreur@livraben.test','9700000002','$2y$12$87uvV8jBNXAELBFh6q2ARetB7t7JjWMRIM8J9rThMJrFjJdQ03ipC','livreur','moto',1,6.3703000,2.3912000,NOW(),NOW());
