<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateEvidenceSubmissionsTable extends Migration {
    public function up() {
        $this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'progress_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'file_path'=>['type'=>'VARCHAR','constraint'=>'255'],'file_type'=>['type'=>'VARCHAR','constraint'=>'100'],'submission_status'=>['type'=>'ENUM','constraint'=>['pending','approved','revision_needed'],'default'=>'pending'],'trainer_feedback'=>['type'=>'TEXT','null'=>true],'uploaded_at'=>['type'=>'TIMESTAMP']]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('progress_id', 'trainee_progress', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('evidence_submissions');
    }
    public function down() { $this->forge->dropTable('evidence_submissions'); }
}