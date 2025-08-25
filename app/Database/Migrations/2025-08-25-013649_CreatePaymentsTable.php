<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreatePaymentsTable extends Migration {
    public function up() {
        $this->forge->addField(['id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],'enrollment_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true],'amount'=>['type'=>'DECIMAL','constraint'=>'10,2'],'payment_method'=>['type'=>'ENUM','constraint'=>['Cash','GCASH','Gotyme','Maya']],'transaction_date'=>['type'=>'DATETIME'],'recorded_by_user_id'=>['type'=>'INT','constraint'=>11,'unsigned'=>true]]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('recorded_by_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }
    public function down() { $this->forge->dropTable('payments'); }
}