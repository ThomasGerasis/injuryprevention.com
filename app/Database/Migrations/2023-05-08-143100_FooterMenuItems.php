<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FooterMenuItems extends Migration
{
    public function up()
    {
      $this->db->query("CREATE TABLE `footer_menu_items` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `footer_menu_id` int(11) NOT NULL,
        `type` enum('link','parent','child','child_parent','second_child') NOT NULL DEFAULT 'link',
        `order_num` int(11) NOT NULL,
        `title` varchar(600) NOT NULL,
        `image_id` int(11) DEFAULT NULL,
        `relative_url` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
        `external_url` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function down()
    {
        
    }
}
