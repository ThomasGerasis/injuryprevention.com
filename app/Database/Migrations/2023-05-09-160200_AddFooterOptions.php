<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFooterOptions extends Migration
{
    public function up()
    {
        $this->db->query("INSERT INTO `options` (`name`, `title`) VALUES
			('footer', 'footer');");
    }

    public function down()
    {
        $this->db->query("DELETE FROM `options` WHERE name='footer'");
    }
}