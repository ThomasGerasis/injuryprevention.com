<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ScheduledPublication extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `articles` ADD `date_scheduled` timestamp NULL DEFAULT NULL AFTER `user_id`;");
        $this->db->query("ALTER TABLE `articles` ADD INDEX(`date_scheduled`);");

    }

    public function down()
    {
    }
}