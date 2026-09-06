-- TrackXa - Complete Database Schema
-- Version 1.0.0
-- Encoding: UTF-8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `trackxa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `trackxa`;

-- ============================================================
-- ADMINS
-- ============================================================
CREATE TABLE `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('superadmin','admin','operator') NOT NULL DEFAULT 'operator',
  `avatar` VARCHAR(255) DEFAULT NULL,
  `two_factor_secret` VARCHAR(255) DEFAULT NULL,
  `two_factor_enabled` TINYINT(1) DEFAULT 0,
  `last_login` DATETIME DEFAULT NULL,
  `last_ip` VARCHAR(45) DEFAULT NULL,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- COUNTRIES
-- ============================================================
CREATE TABLE `countries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(3) NOT NULL UNIQUE,
  `flag` VARCHAR(10) DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CARRIERS
-- ============================================================
CREATE TABLE `carriers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `logo` VARCHAR(255) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `tracking_url` VARCHAR(500) DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SHIPPING METHODS
-- ============================================================
CREATE TABLE `shipping_methods` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `estimated_days_min` INT DEFAULT NULL,
  `estimated_days_max` INT DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PACKAGE TYPES
-- ============================================================
CREATE TABLE `package_types` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `icon` VARCHAR(100) DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- EXTERNAL WEBSITES (API Clients)
-- ============================================================
CREATE TABLE `websites` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `domain` VARCHAR(255) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `contact_email` VARCHAR(150) DEFAULT NULL,
  `status` ENUM('active','inactive','suspended') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- API KEYS
-- ============================================================
CREATE TABLE `api_keys` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `website_id` INT UNSIGNED NOT NULL,
  `api_key` VARCHAR(64) NOT NULL UNIQUE,
  `api_secret` VARCHAR(128) NOT NULL,
  `permissions` JSON DEFAULT NULL,
  `rate_limit` INT DEFAULT 1000,
  `calls_today` INT DEFAULT 0,
  `calls_total` BIGINT DEFAULT 0,
  `last_used_at` DATETIME DEFAULT NULL,
  `last_ip` VARCHAR(45) DEFAULT NULL,
  `status` ENUM('active','inactive','revoked') DEFAULT 'active',
  `expires_at` DATE DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`website_id`) REFERENCES `websites`(`id`) ON DELETE CASCADE,
  INDEX `idx_api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- API LOGS
-- ============================================================
CREATE TABLE `api_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `api_key_id` INT UNSIGNED DEFAULT NULL,
  `endpoint` VARCHAR(255) NOT NULL,
  `method` VARCHAR(10) NOT NULL,
  `request_body` LONGTEXT DEFAULT NULL,
  `response_code` INT DEFAULT NULL,
  `response_body` LONGTEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `execution_time` FLOAT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_api_key_id` (`api_key_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SHIPMENTS
-- ============================================================
CREATE TABLE `shipments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `tracking_number` VARCHAR(30) NOT NULL UNIQUE,
  `reference_number` VARCHAR(100) DEFAULT NULL,
  `order_number` VARCHAR(100) DEFAULT NULL,
  `website_id` INT UNSIGNED DEFAULT NULL,
  `carrier_id` INT UNSIGNED DEFAULT NULL,
  `shipping_method_id` INT UNSIGNED DEFAULT NULL,
  `package_type_id` INT UNSIGNED DEFAULT NULL,
  `status` ENUM(
    'order_received','shipment_created','preparing','picked_up',
    'at_warehouse','in_transit','arrived_airport','departed_airport',
    'customs_clearance','released_customs','out_for_delivery',
    'delivered','delivery_failed','returned','cancelled','delayed','on_hold'
  ) NOT NULL DEFAULT 'order_received',
  `sender_name` VARCHAR(150) DEFAULT NULL,
  `sender_phone` VARCHAR(30) DEFAULT NULL,
  `sender_email` VARCHAR(150) DEFAULT NULL,
  `sender_address` TEXT DEFAULT NULL,
  `sender_city` VARCHAR(100) DEFAULT NULL,
  `sender_country_id` INT UNSIGNED DEFAULT NULL,
  `sender_postal_code` VARCHAR(20) DEFAULT NULL,
  `recipient_name` VARCHAR(150) NOT NULL,
  `recipient_phone` VARCHAR(30) DEFAULT NULL,
  `recipient_email` VARCHAR(150) DEFAULT NULL,
  `recipient_address` TEXT NOT NULL,
  `recipient_city` VARCHAR(100) NOT NULL,
  `recipient_country_id` INT UNSIGNED DEFAULT NULL,
  `recipient_postal_code` VARCHAR(20) DEFAULT NULL,
  `origin_city` VARCHAR(100) DEFAULT NULL,
  `origin_country_id` INT UNSIGNED DEFAULT NULL,
  `destination_city` VARCHAR(100) DEFAULT NULL,
  `destination_country_id` INT UNSIGNED DEFAULT NULL,
  `weight` DECIMAL(8,3) DEFAULT NULL,
  `weight_unit` ENUM('kg','lb','g') DEFAULT 'kg',
  `length` DECIMAL(8,2) DEFAULT NULL,
  `width` DECIMAL(8,2) DEFAULT NULL,
  `height` DECIMAL(8,2) DEFAULT NULL,
  `dimension_unit` ENUM('cm','in') DEFAULT 'cm',
  `declared_value` DECIMAL(10,2) DEFAULT NULL,
  `currency` VARCHAR(5) DEFAULT 'USD',
  `description` TEXT DEFAULT NULL,
  `special_instructions` TEXT DEFAULT NULL,
  `internal_notes` TEXT DEFAULT NULL,
  `current_location` VARCHAR(255) DEFAULT NULL,
  `shipping_date` DATE DEFAULT NULL,
  `estimated_delivery` DATE DEFAULT NULL,
  `actual_delivery` DATETIME DEFAULT NULL,
  `service_level` VARCHAR(100) DEFAULT NULL,
  `is_archived` TINYINT(1) DEFAULT 0,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_tracking` (`tracking_number`),
  INDEX `idx_reference` (`reference_number`),
  INDEX `idx_order` (`order_number`),
  INDEX `idx_status` (`status`),
  INDEX `idx_website` (`website_id`),
  FOREIGN KEY (`website_id`) REFERENCES `websites`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`carrier_id`) REFERENCES `carriers`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`package_type_id`) REFERENCES `package_types`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`sender_country_id`) REFERENCES `countries`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`recipient_country_id`) REFERENCES `countries`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`origin_country_id`) REFERENCES `countries`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`destination_country_id`) REFERENCES `countries`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TRACKING HISTORY
-- ============================================================
CREATE TABLE `tracking_history` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT UNSIGNED NOT NULL,
  `status` ENUM(
    'order_received','shipment_created','preparing','picked_up',
    'at_warehouse','in_transit','arrived_airport','departed_airport',
    'customs_clearance','released_customs','out_for_delivery',
    'delivered','delivery_failed','returned','cancelled','delayed','on_hold'
  ) NOT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `operator_notes` TEXT DEFAULT NULL,
  `occurred_at` DATETIME NOT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipments`(`id`) ON DELETE CASCADE,
  INDEX `idx_shipment_id` (`shipment_id`),
  INDEX `idx_occurred_at` (`occurred_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- WEBHOOKS
-- ============================================================
CREATE TABLE `webhooks` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `website_id` INT UNSIGNED NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  `events` JSON DEFAULT NULL,
  `secret` VARCHAR(128) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `last_triggered_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`website_id`) REFERENCES `websites`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- WEBHOOK LOGS
-- ============================================================
CREATE TABLE `webhook_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `webhook_id` INT UNSIGNED NOT NULL,
  `event` VARCHAR(100) NOT NULL,
  `payload` LONGTEXT DEFAULT NULL,
  `response_code` INT DEFAULT NULL,
  `response_body` TEXT DEFAULT NULL,
  `attempts` INT DEFAULT 1,
  `status` ENUM('success','failed','pending') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`webhook_id`) REFERENCES `webhooks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- BLOG CATEGORIES
-- ============================================================
CREATE TABLE `blog_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `meta_title` VARCHAR(200) DEFAULT NULL,
  `meta_description` VARCHAR(300) DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- BLOG POSTS
-- ============================================================
CREATE TABLE `blog_posts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `admin_id` INT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(300) NOT NULL UNIQUE,
  `excerpt` TEXT DEFAULT NULL,
  `content` LONGTEXT DEFAULT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `tags` VARCHAR(500) DEFAULT NULL,
  `meta_title` VARCHAR(200) DEFAULT NULL,
  `meta_description` VARCHAR(300) DEFAULT NULL,
  `meta_keywords` VARCHAR(300) DEFAULT NULL,
  `og_image` VARCHAR(255) DEFAULT NULL,
  `views` INT DEFAULT 0,
  `status` ENUM('draft','published','scheduled') DEFAULT 'draft',
  `published_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE SET NULL,
  INDEX `idx_slug` (`slug`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- FAQ
-- ============================================================
CREATE TABLE `faqs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `question` TEXT NOT NULL,
  `answer` LONGTEXT NOT NULL,
  `category` VARCHAR(100) DEFAULT 'general',
  `sort_order` INT DEFAULT 0,
  `lang` VARCHAR(5) DEFAULT 'en',
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PARTNERS / SUPPORTED CARRIERS (public logos)
-- ============================================================
CREATE TABLE `partners` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `logo` VARCHAR(255) NOT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SETTINGS
-- ============================================================
CREATE TABLE `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` LONGTEXT DEFAULT NULL,
  `group` VARCHAR(50) DEFAULT 'general',
  `type` VARCHAR(20) DEFAULT 'text',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CONTACT MESSAGES
-- ============================================================
CREATE TABLE `contact_messages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `subject` VARCHAR(255) DEFAULT NULL,
  `message` LONGTEXT NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `reply` LONGTEXT DEFAULT NULL,
  `replied_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- ADMIN ACTIVITY LOGS
-- ============================================================
CREATE TABLE `admin_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `model` VARCHAR(100) DEFAULT NULL,
  `model_id` INT DEFAULT NULL,
  `details` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_admin_id` (`admin_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- EMAIL NOTIFICATIONS LOG
-- ============================================================
CREATE TABLE `email_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `to_email` VARCHAR(150) NOT NULL,
  `subject` VARCHAR(300) NOT NULL,
  `body` LONGTEXT DEFAULT NULL,
  `status` ENUM('sent','failed','pending') DEFAULT 'pending',
  `error` TEXT DEFAULT NULL,
  `shipment_id` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- VISITOR STATS
-- ============================================================
CREATE TABLE `visitor_stats` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `date` DATE NOT NULL,
  `page` VARCHAR(255) DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `referer` VARCHAR(500) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_date` (`date`),
  INDEX `idx_page` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PASSWORD RESETS
-- ============================================================
CREATE TABLE `password_resets` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(150) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DEFAULT DATA
-- ============================================================

-- Default admin (password: Admin@123456)
INSERT INTO `admins` (`name`,`email`,`password`,`role`) VALUES
('Super Admin','admin@trackxa.com','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','superadmin');

-- Default settings
INSERT INTO `settings` (`key`,`value`,`group`,`type`) VALUES
('site_name','TrackXa','general','text'),
('site_tagline','Professional Shipment Tracking Platform','general','text'),
('site_email','info@trackxa.com','general','email'),
('site_phone','+1 (555) 000-0000','general','text'),
('site_address','123 Logistics Ave, New York, USA','general','text'),
('site_logo','','general','image'),
('site_favicon','','general','image'),
('google_maps_key','','general','text'),
('smtp_host','','email','text'),
('smtp_port','587','email','text'),
('smtp_user','','email','text'),
('smtp_pass','','email','password'),
('smtp_secure','tls','email','text'),
('recaptcha_site_key','','security','text'),
('recaptcha_secret_key','','security','text'),
('tracking_prefix','TXA','general','text'),
('whatsapp_number','','contact','text'),
('facebook_url','','social','text'),
('twitter_url','','social','text'),
('instagram_url','','social','text'),
('linkedin_url','','social','text'),
('default_language','en','general','text'),
('timezone','UTC','general','text'),
('currency','USD','general','text'),
('meta_title','TrackXa - Professional Shipment Tracking','seo','text'),
('meta_description','Track your shipments in real time with TrackXa. Fast, reliable and accurate shipment tracking.','seo','text'),
('meta_keywords','shipment tracking, parcel tracking, package tracking, courier tracking','seo','text'),
('google_analytics','','analytics','text'),
('maintenance_mode','0','general','text');

-- Countries
INSERT INTO `countries` (`name`,`code`) VALUES
('United States','US'),('United Kingdom','GB'),('France','FR'),('Germany','DE'),
('Spain','ES'),('Italy','IT'),('Portugal','PT'),('Canada','CA'),('Australia','AU'),
('Japan','JP'),('China','CN'),('Brazil','BR'),('Mexico','MX'),('India','IN'),
('United Arab Emirates','AE'),('Saudi Arabia','SA'),('Morocco','MA'),('Algeria','DZ'),
('Tunisia','TN'),('Netherlands','NL'),('Belgium','BE'),('Switzerland','CH'),
('Sweden','SE'),('Norway','NO'),('Denmark','DK'),('Poland','PL'),('Russia','RU'),
('Turkey','TR'),('South Africa','ZA'),('Egypt','EG'),('Nigeria','NG'),
('Singapore','SG'),('South Korea','KR'),('Hong Kong','HK'),('Thailand','TH');

-- Carriers
INSERT INTO `carriers` (`name`,`code`,`website`,`tracking_url`) VALUES
('DHL Express','DHL','https://dhl.com','https://www.dhl.com/en/express/tracking.html?AWB={tracking}'),
('FedEx','FEDEX','https://fedex.com','https://www.fedex.com/fedextrack/?trknbr={tracking}'),
('UPS','UPS','https://ups.com','https://www.ups.com/track?tracknum={tracking}'),
('USPS','USPS','https://usps.com','https://tools.usps.com/go/TrackConfirmAction?tLabels={tracking}'),
('TNT','TNT','https://tnt.com','https://www.tnt.com/express/en_gb/site/tracking.html?searchType=con&cons={tracking}'),
('Aramex','ARAMEX','https://aramex.com','https://www.aramex.com/track/shipments?mode=0&ShipmentNumber={tracking}'),
('DPD','DPD','https://dpd.com','https://tracking.dpd.de/status/en_US/parcel/{tracking}'),
('GLS','GLS','https://gls-group.eu',NULL),
('Royal Mail','ROYALMAIL','https://royalmail.com','https://www.royalmail.com/portal/rm/track?trackNumber={tracking}'),
('La Poste','LAPOSTE','https://laposte.fr','https://www.laposte.fr/outils/suivre-vos-envois?code={tracking}');

-- Shipping Methods
INSERT INTO `shipping_methods` (`name`,`code`,`estimated_days_min`,`estimated_days_max`) VALUES
('Express Air','express_air',1,3),
('Standard Air','standard_air',3,7),
('Economy Air','economy_air',7,14),
('Sea Freight','sea_freight',20,45),
('Road Freight','road_freight',2,10),
('Same Day Delivery','same_day',0,1),
('Next Day Delivery','next_day',1,1);

-- Package Types
INSERT INTO `package_types` (`name`,`icon`) VALUES
('Parcel','fa-box'),('Document','fa-file'),('Pallet','fa-pallet'),
('Envelope','fa-envelope'),('Fragile','fa-wine-glass'),('Oversized','fa-cube');

-- FAQ
INSERT INTO `faqs` (`question`,`answer`,`sort_order`,`lang`) VALUES
('How do I track my shipment?','Enter your tracking number in the search box on our homepage and click "Track Shipment". You will see real-time updates on your package location.',1,'en'),
('How long does delivery take?','Delivery times vary by service level. Express shipping takes 1-3 days, standard 3-7 days, and economy 7-14 days.',2,'en'),
('What does "In Transit" mean?','In Transit means your package is currently moving between facilities toward its destination.',3,'en'),
('Can I change the delivery address?','Address changes depend on the carrier and shipment status. Contact our support team as soon as possible.',4,'en'),
('What happens if delivery fails?','If delivery fails, the carrier will attempt redelivery. You will receive a notification with instructions.',5,'en'),
('How are tracking numbers generated?','Tracking numbers are automatically generated when a shipment is created and are unique to each package.',6,'en');

-- Blog categories
INSERT INTO `blog_categories` (`name`,`slug`,`status`) VALUES
('Tracking Guides','tracking-guides',1),
('Shipping Tips','shipping-tips',1),
('International Shipping','international-shipping',1),
('Customs Information','customs-information',1),
('News','news',1);
