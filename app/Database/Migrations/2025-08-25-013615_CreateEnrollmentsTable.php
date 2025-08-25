<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateEnrollmentsTable extends Migration {
    public function up() {
        $this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'trainee_user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'qualification_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'trainer_user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true],'enrollment_date'=>['type'=>'DATETIME'],'status'=>['type'=>'ENUM','constraint'=>['queued','enrolled','completed','dropped'],'default'=>'queued']]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('trainee_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('qualification_id', 'qualifications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('trainer_user_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('enrollments');
    }
    public function down() { $this->forge->dropTable('enrollments'); }
}