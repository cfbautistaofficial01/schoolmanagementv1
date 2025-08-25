<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddRegulationTypeToQualifications extends Migration {
    public function up() {
        $this->forge->addColumn('qualifications', [
            'regulation_type' => [
                'type' => 'ENUM',
                'constraint' => ['TR', 'CS'],
                'default' => 'TR',
                'after' => 'description'
            ]
        ]);
    }
    public function down() {
        $this->forge->dropColumn('qualifications', 'regulation_type');
    }
}