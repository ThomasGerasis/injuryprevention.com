<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Options extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE `options` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(80) NOT NULL,
            `title` varchar(255) NOT NULL,
            `value` longtext DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `name` (`name`)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        
        $this->db->query("INSERT INTO `options` (`id`, `name`, `title`) VALUES
			(1, 'exitPage', 'exit page')");

    }

    public function down()
    {
        
    }
}
