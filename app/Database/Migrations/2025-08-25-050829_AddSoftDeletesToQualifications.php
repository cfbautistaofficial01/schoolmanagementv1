<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddSoftDeletesToQualifications extends Migration {
    public function up() {
        $this->forge->addColumn('qualifications', [
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
    }
    public function down() {
        $this->forge->dropColumn('qualifications', 'deleted_at');
    }
}