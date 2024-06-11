<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitialSchema extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE `action_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `when` datetime NOT NULL,
            `what` varchar(500) NOT NULL,
            `json_changes` longtext DEFAULT NULL,
            `model_id` int(11) NOT NULL,
            `model_name` varchar(255) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `model_id` (`model_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE `images` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(300) DEFAULT NULL,
            `file_name` varchar(255) DEFAULT NULL,
            `mimetype` varchar(63) NOT NULL,
            `extension` varchar(15) NOT NULL,
            `added_by` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `file_name` (`file_name`),
            KEY `title` (`title`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `is_admin` tinyint(1) NOT NULL DEFAULT 0,
            `username` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT 0,
            `date_added` datetime NOT NULL,
            `ip_last_connected` varchar(40) DEFAULT NULL,
            `date_last_connected` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`),
            UNIQUE KEY `username` (`username`),
            KEY `is_admin` (`is_admin`),
            KEY `is_active` (`is_active`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE `permalinks` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `permalink` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            `type` varchar(100) NOT NULL,
            `type_id` int(11) NOT NULL,
            `published` tinyint(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `permalink` (`permalink`),
            KEY `type` (`type`),
            KEY `type_id` (`type_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE `pages` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(1023) NOT NULL,
            `seo_title` varchar(500) DEFAULT NULL,
            `seo_description` varchar(1023) DEFAULT NULL,
            `permalink` varchar(1023) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `opener_image_id` int(11) DEFAULT NULL,
            `social_image_id` int(11) DEFAULT NULL,
            `social_title` varchar(500) DEFAULT NULL,
            `date_created` datetime NOT NULL,
            `user_id` int(11) NOT NULL,
            `date_published` datetime DEFAULT NULL,
            `published` tinyint(1) DEFAULT 0,
            `faq_title` varchar(255) DEFAULT NULL,
            `faq_subtitle` varchar(1048) DEFAULT NULL,
            `faq_heading` varchar(50) DEFAULT NULL,
            `content` longtext DEFAULT NULL,
            `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
            `modified_by` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `opener_image_id` (`opener_image_id`),
            KEY `social_image_id` (`social_image_id`),
            KEY `user_id` (`user_id`),
            KEY `date_published` (`date_published`),
            KEY `published` (`published`),
            KEY `permalink` (`permalink`),
            KEY `modified_date` (`modified_date`),
            KEY `modified_by` (`modified_by`),
            CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`opener_image_id`) REFERENCES `images` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`social_image_id`) REFERENCES `images` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");


        $this->db->query("CREATE TABLE `page_faqs` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `page_id` int(11) NOT NULL,
            `question` varchar(255) NOT NULL,
            `answer` text NOT NULL,
            `order_num` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `page_id` (`page_id`),
            KEY `order_num` (`order_num`),
            CONSTRAINT `page_faqs_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `action_log`;");
        $this->db->query("DROP TABLE IF EXISTS `images`;");
        $this->db->query("DROP TABLE IF EXISTS `users`;");
        $this->db->query("DROP TABLE IF EXISTS `permalinks`;");
        $this->db->query("DROP TABLE IF EXISTS `pages`;");
        $this->db->query("DROP TABLE IF EXISTS `page_faqs`;");
        $this->db->query("DROP TABLE IF EXISTS `action_log`;");
        $this->db->query("DROP TABLE IF EXISTS `images`;");
        $this->db->query("DROP TABLE IF EXISTS `users`;");
        $this->db->query("DROP TABLE IF EXISTS `permalinks`;");
        $this->db->query("DROP TABLE IF EXISTS `pages`;");
        $this->db->query("DROP TABLE IF EXISTS `page_faqs`;");
    }
}
