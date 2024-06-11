<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InsertAdminUsers extends Migration
{
    public function up()
    {
        $this->db->query("INSERT INTO `users` (`id`, `is_admin`, `username`, `email`, `is_active`, `date_added`) VALUES
			(1, 1, 'thomas', 'thomasgerasis@gmail.com', 1, '2023-02-22 14:22:00')
			;");
    }

    public function down()
    {
       
    }
}
