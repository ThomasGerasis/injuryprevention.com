<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Locked extends Migration
{
    public function up()
    {
        $fields = [
            "is_locked" => [
                 "type" => "TINYINT",
                "constraint" => 1,
                "unsigned" => true,
                "default" => 0
            ],
        ];
        $this->forge->addColumn('articles', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('articles', 'is_locked');
    }
}
