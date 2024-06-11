<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class SiteUsers extends Migration
{
    public function up()
    {
        $fields = [
            "id" => [
                "type" => "BIGINT",
                "constraint" => 11,
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "email" => [
                "type" => "VARCHAR",
                "constraint" => 255,
            ],
            "firstname" => [
                "type" => "VARCHAR",
                "constraint" => 255,
            ],
            "lastname" => [
                "type" => "VARCHAR",
                "constraint" => 255,
            ],
            "is_active" => [
                "type" => "TINYINT",
                "constraint" => 1,
                "unsigned" => true,
            ],
            "date_added" => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'null' => false,
            ],
            "last_connected_ip" => [
                "type" => "VARCHAR",
                "constraint" => 44,
            ],
            "last_connected_time" => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'null' => false,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey("id");
        $this->forge->addUniqueKey("email");
        $this->forge->createTable("site_users");
    }

    public function down()
    {
        $this->forge->dropPrimaryKey("site_users", "id");
        $this->forge->dropTable("site_users");
    }
}
