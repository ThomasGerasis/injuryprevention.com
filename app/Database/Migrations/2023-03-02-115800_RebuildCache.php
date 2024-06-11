<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RebuildCache extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE `rebuild_cache` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `hash` varchar(100) NOT NULL,
            `date` datetime NOT NULL,
            `type` varchar(255) NOT NULL,
            `type_id` varchar(255) NOT NULL,
            `action` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `hash` (`hash`)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS `rebuild_cache`;");
    }
}
