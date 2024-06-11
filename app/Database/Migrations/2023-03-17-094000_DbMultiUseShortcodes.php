<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DbMultiUseShortcodes extends Migration
{
    public function up()
    {
      /*
      DROP TABLE IF EXISTS `multi_use_shortcodes`;
    CREATE TABLE `multi_use_shortcodes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `type` enum('tabs','shortcode','tabbed_box') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'shortcode',
        `shortcode` varchar(100) NOT NULL,
        `content` text NOT NULL,
        PRIMARY KEY (`id`),
        KEY `title` (`title`),
        KEY `type` (`type`),
        KEY `shortcode` (`shortcode`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      */
      $this->db->query("CREATE TABLE `multi_use_content` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `content` text NOT NULL,
        `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
		    `modified_by` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `title` (`title`),
        KEY `modified_by` (`modified_by`),
		    KEY `modified_date` (`modified_date`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    public function down()
    {
        
    }
}
