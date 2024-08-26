<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class HomeText extends Migration
{
    public function up()
    {
        $fields = [
            "welcome_title" => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "null" => true,
               'after' => 'date_published',
            ],
            "welcome_text" => [
                "type" => "VARCHAR",
                "constraint" => 1048,
                "null" => true,
                'after' => 'date_published',
            ],
            // "about_us_text" => [
            //     "type" => "LONGTEXT",
            //     "null" => true,
            //     'after' => 'content',
            // ],
        ];
        $this->forge->addColumn('homepage', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('homepage', 'welcome_title');
        $this->forge->dropColumn('homepage', 'welcome_text');
        $this->forge->dropColumn('homepage', 'about_us_text');
    }
}
