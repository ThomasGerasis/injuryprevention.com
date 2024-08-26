<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class BgImages extends Migration
{
    public function up()
    {
        $fields = [
            "bg_image_id" => [
                "type" => "int",
                "constraint" => 11,
                "null" => true,
               'after' => 'content',
            ],

        ];
        $this->forge->addColumn('homepage', $fields);
        $this->forge->addColumn('pages', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('homepage', 'bg_image_id');
        $this->forge->dropColumn('pages', 'bg_image_id');
    }
}
