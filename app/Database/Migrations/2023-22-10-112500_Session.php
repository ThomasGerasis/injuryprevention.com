<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Session extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE `ci_sessions` (
            `id` varchar(128) NOT null,
            `ip_address` varchar(45) NOT null,
            `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP NOT null,
            `data` blob NOT null,
        KEY `ci_sessions_timestamp` (`timestamp`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function down()
    {

    }
}
