<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateTraineeProgressTable extends Migration {
    public function up() {
        $this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'enrollment_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'outcome_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'status'=>['type'=>'ENUM','constraint'=>['pending','completed'],'default'=>'pending'],'completed_date'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('outcome_id', 'learning_outcomes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('trainee_progress');
    }
    public function down() { $this->forge->dropTable('trainee_progress'); }
}