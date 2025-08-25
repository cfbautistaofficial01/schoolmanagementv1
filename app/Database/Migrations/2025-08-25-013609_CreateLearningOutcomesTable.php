<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateLearningOutcomesTable extends Migration {
    public function up() {
        $this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'unit_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'outcome_description'=>['type'=>'TEXT'],'requires_evidence'=>['type'=>'BOOLEAN','default'=>false],'outcome_order'=>['type'=>'INT','constraint'=>5,'default'=>0]]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('unit_id', 'competency_units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('learning_outcomes');
    }
    public function down() { $this->forge->dropTable('learning_outcomes'); }
}