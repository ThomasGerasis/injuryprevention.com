<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EditingLock extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'unsigned' => true,
                'auto_increment' => true,
                'constraint' => 11,
            ],
            'type' => [
                'type' => 'varchar',
                'constraint' => 300,
            ],
            'type_id' => [
                'type' => 'int',
                'constraint' => 11,
            ],
            'user_id' => [
                'type' => 'int',
            ],
            'updated' => [
                'type' => 'datetime',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('editing_lock');
    }

    public function down()
    {
        $this->forge->dropTable('editing_lock');
    }
}
