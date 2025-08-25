<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateCompetencyUnitsTable extends Migration {
    public function up() {
        $this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'qualification_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'unit_title'=>['type'=>'VARCHAR','constraint'=>'255'],'unit_order'=>['type'=>'INT','constraint'=>5,'default'=>0]]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('qualification_id', 'qualifications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('competency_units');
    }
    public function down() { $this->forge->dropTable('competency_units'); }
}