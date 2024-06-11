<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserGroup extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'unsigned' => true,
                'auto_increment' => true,
                'constraint' => 11,
                'null' => false
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => 255,
                'null' => false
            ],
            'plural_name' => [
                'type' => 'varchar',
                'constraint' => 255,
                'null' => false
            ],
            'order_num' => [
                'type' => 'int',
                'unsigned' => true,
                'constraint' => 11,
                'null' => false
            ],
            'relations' => [
                'type' => 'varchar',
                'constraint' => 255,
                'null' => true,
                'default' => NULL
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_group');

        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'unsigned' => true,
                'auto_increment' => true,
                'constraint' => 11,
                'null' => false
            ],
            'user_id' => [
                'type' => 'int',
            ],
            'user_group_id' => [
                'type' => 'int',
                'unsigned' => true,
                'constraint' => 11,
                'null' => false
            ],
            'relation_ids' => [
                'type' => 'varchar',
                'constraint' => 500,
                'null' => true,
                'default'
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_group_id', 'user_group', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_to_group');
    }

    public function down()
    {
        $this->forge->dropTable('user_to_group');
        $this->forge->dropTable('user_group');
    }
}