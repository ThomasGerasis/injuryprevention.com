<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class HomePageFaq extends Migration
{
    public function up()
    {
        $fields = [
            "faq_title" => [
                "type" => "VARCHAR",
                "constraint" => 255,
            ],
            "faq_subtitle" => [
                "type" => "VARCHAR",
                "constraint" => 1048,
            ],
            "faq_heading" => [
                "type" => "VARCHAR",
                "constraint" => 50,
            ],
            "faq_content" => [
                "type" => "TEXT"
            ],
        ];
        $this->forge->addColumn('homepage', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('homepage', 'faq_title');
        $this->forge->dropColumn('homepage', 'faq_subtitle');
        $this->forge->dropColumn('homepage', 'faq_heading');
        $this->forge->dropColumn('homepage', 'faq_content');
    }
}
