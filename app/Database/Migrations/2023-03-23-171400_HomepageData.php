<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HomepageData extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE `homepage` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(1023) NOT NULL,
            `seo_title` varchar(500) DEFAULT NULL,
            `seo_description` varchar(1023) DEFAULT NULL,
            `social_image_id` int(11) DEFAULT NULL,
            `social_title` varchar(500) DEFAULT NULL,
            `date_created` datetime NOT NULL,
            `date_published` datetime DEFAULT NULL,
            `content` longtext DEFAULT NULL,
            `modified_date` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
            `modified_by` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `social_image_id` (`social_image_id`),
            KEY `date_published` (`date_published`),
            KEY `modified_date` (`modified_date`),
            KEY `modified_by` (`modified_by`),
            CONSTRAINT `homepage_ibfk_1` FOREIGN KEY (`social_image_id`) REFERENCES `images` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
          ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("INSERT INTO `homepage` (`id`, `title`, `date_created`, `date_published`, `modified_date`, `modified_by`) VALUES
        (1, 'Home page', '2023-03-23 14:22:00', '2023-03-23 14:22:00', '2023-03-23 14:22:00', '1')");
    }

    public function down()
    {
        
    }
}
