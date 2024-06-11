<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FooterMenus extends Migration
{
    public function up()
    {
      $this->db->query("CREATE TABLE `footer_menus` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `total_menu_items` int(11) NOT NULL, 
        `order_num` int(11) NOT NULL,
        `title` varchar(600) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function down()
    {
        
    }
}
