<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTraineeDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'age' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
            ],
            'contact_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'educational_bg' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'registration_date' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('trainee_details');
    }

    public function down()
    {
        $this->forge->dropTable('trainee_details');
    }
}