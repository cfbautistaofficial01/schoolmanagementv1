<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddLevelToQualifications extends Migration {
    public function up() {
        $this->forge->addColumn('qualifications', [
            'level' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'qualification_name'
            ]
        ]);
    }
    public function down() {
        $this->forge->dropColumn('qualifications', 'level');
    }
}