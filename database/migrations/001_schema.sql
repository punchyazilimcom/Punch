-- ============================================================
--  PUNCH YAZILIM - Veritabani Semasi (MySQL 8 / MariaDB)
--  Charset: utf8mb4 (Turkce + emoji uyumlu)
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --- Musteriler ---
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'customer',
  `tax_no` VARCHAR(30) DEFAULT NULL,
  `tc_no` VARCHAR(20) DEFAULT NULL,
  `company` VARCHAR(190) DEFAULT NULL,
  `address` VARCHAR(500) DEFAULT NULL,
  `city` VARCHAR(80) DEFAULT NULL,
  `status` ENUM('active','passive','pending') NOT NULL DEFAULT 'active',
  `email_verified_at` DATETIME DEFAULT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `notify_email` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Adminler ---
CREATE TABLE IF NOT EXISTS `admins` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` VARCHAR(30) NOT NULL DEFAULT 'admin',
  `totp_secret` VARCHAR(64) DEFAULT NULL,
  `last_login_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Paketler ---
CREATE TABLE IF NOT EXISTS `packages` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `title` VARCHAR(190) NOT NULL,
  `short_desc` VARCHAR(300) DEFAULT NULL,
  `description` TEXT,
  `features_json` JSON DEFAULT NULL,
  `price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'TRY',
  `is_quote_only` TINYINT(1) NOT NULL DEFAULT 0,
  `is_popular` TINYINT(1) NOT NULL DEFAULT 0,
  `image` VARCHAR(255) DEFAULT NULL,
  `icon` VARCHAR(60) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `meta_title` VARCHAR(190) DEFAULT NULL,
  `meta_description` VARCHAR(300) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Hizmetler ---
CREATE TABLE IF NOT EXISTS `services` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `title` VARCHAR(190) NOT NULL,
  `short_desc` VARCHAR(300) DEFAULT NULL,
  `description` TEXT,
  `features_json` JSON DEFAULT NULL,
  `icon` VARCHAR(60) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `meta_title` VARCHAR(190) DEFAULT NULL,
  `meta_description` VARCHAR(300) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Siparisler ---
CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `package_id` BIGINT UNSIGNED DEFAULT NULL,
  `merchant_oid` VARCHAR(64) NOT NULL UNIQUE,
  `amount` DECIMAL(12,2) NOT NULL,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'TRY',
  `status` ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paytr_response_json` TEXT,
  `paid_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_orders_user` (`user_id`),
  INDEX `idx_orders_status` (`status`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_orders_package` FOREIGN KEY (`package_id`) REFERENCES `packages`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Faturalar ---
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id` BIGINT UNSIGNED DEFAULT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `invoice_no` VARCHAR(40) NOT NULL UNIQUE,
  `title` VARCHAR(190) DEFAULT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 20.00,
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'TRY',
  `status` ENUM('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `pdf_path` VARCHAR(255) DEFAULT NULL,
  `issued_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_invoices_user` (`user_id`),
  CONSTRAINT `fk_invoices_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_invoices_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Destek talepleri ---
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `subject` VARCHAR(190) NOT NULL,
  `priority` ENUM('low','normal','high') NOT NULL DEFAULT 'normal',
  `status` ENUM('open','answered','closed') NOT NULL DEFAULT 'open',
  `assigned_admin_id` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_tickets_user` (`user_id`),
  CONSTRAINT `fk_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ticket_messages` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` BIGINT UNSIGNED NOT NULL,
  `sender_type` ENUM('user','admin') NOT NULL,
  `sender_id` BIGINT UNSIGNED NOT NULL,
  `message` TEXT NOT NULL,
  `attachment_path` VARCHAR(255) DEFAULT NULL,
  `is_internal_note` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_tm_ticket` (`ticket_id`),
  CONSTRAINT `fk_tm_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Teklifler ---
CREATE TABLE IF NOT EXISTS `quotes` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `package_id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `message` TEXT,
  `status` ENUM('new','quoted','accepted','rejected') NOT NULL DEFAULT 'new',
  `quoted_price` DECIMAL(12,2) DEFAULT NULL,
  `admin_note` TEXT,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_quotes_status` (`status`),
  CONSTRAINT `fk_quotes_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_quotes_package` FOREIGN KEY (`package_id`) REFERENCES `packages`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Blog ---
CREATE TABLE IF NOT EXISTS `categories` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `name` VARCHAR(120) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `posts` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(190) NOT NULL UNIQUE,
  `title` VARCHAR(190) NOT NULL,
  `excerpt` VARCHAR(400) DEFAULT NULL,
  `body` MEDIUMTEXT,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `author_id` BIGINT UNSIGNED DEFAULT NULL,
  `status` ENUM('draft','published','scheduled') NOT NULL DEFAULT 'draft',
  `published_at` DATETIME DEFAULT NULL,
  `reading_time` INT DEFAULT NULL,
  `views` INT NOT NULL DEFAULT 0,
  `meta_title` VARCHAR(190) DEFAULT NULL,
  `meta_description` VARCHAR(300) DEFAULT NULL,
  `og_image` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_posts_status` (`status`, `published_at`),
  CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Portfolyo ---
CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(190) NOT NULL UNIQUE,
  `title` VARCHAR(190) NOT NULL,
  `summary` VARCHAR(400) DEFAULT NULL,
  `body` TEXT,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `gallery_json` JSON DEFAULT NULL,
  `category` VARCHAR(80) DEFAULT NULL,
  `client` VARCHAR(150) DEFAULT NULL,
  `url` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Iletisim mesajlari ---
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `subject` VARCHAR(190) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `ip` VARCHAR(45) DEFAULT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Ayarlar (key/value) ---
CREATE TABLE IF NOT EXISTS `settings` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(120) NOT NULL UNIQUE,
  `setting_value` MEDIUMTEXT,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Denetim kayitlari ---
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `actor_type` VARCHAR(20) NOT NULL,
  `actor_id` BIGINT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(120) NOT NULL,
  `meta_json` TEXT,
  `ip` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Parola sifirlama ---
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(190) NOT NULL,
  `token_hash` VARCHAR(255) NOT NULL,
  `guard` VARCHAR(20) NOT NULL DEFAULT 'user',
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_pr_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Rate limit ---
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `rl_key` VARCHAR(190) NOT NULL PRIMARY KEY,
  `attempts` INT NOT NULL DEFAULT 0,
  `reset_at` BIGINT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
