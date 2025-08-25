<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddCategoryToQualifications extends Migration {
    public function up() {
        $this->forge->addColumn('qualifications', [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'after' => 'level'
            ]
        ]);
    }
    public function down() {
        $this->forge->dropColumn('qualifications', 'category');
    }
}