<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateQualificationsTable extends Migration {
    public function up() {
        $this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'qualification_name'=>['type'=>'VARCHAR','constraint'=>'255'],'description'=>['type'=>'TEXT','null'=>true],'is_active'=>['type'=>'BOOLEAN','default'=>true]]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('qualifications');
    }
    public function down() { $this->forge->dropTable('qualifications'); }
}