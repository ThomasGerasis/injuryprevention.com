<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInfoOptions extends Migration
{
    public function up()
    {
        $this->db->query("INSERT INTO `options` (`name`, `title`) VALUES
			('info', 'info');");
    }

    public function down()
    {
        $this->db->query("DELETE FROM `options` WHERE name='info'");
    }
}