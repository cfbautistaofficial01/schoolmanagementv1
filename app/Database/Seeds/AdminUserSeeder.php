<?php namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;
use App\Models\UserModel;
class AdminUserSeeder extends Seeder {
    public function run() {
        $userModel = new UserModel();
        if (!$userModel->where('user_id_code', 'ADMIN-001')->first()) {
            $userModel->insert(['user_id_code'=>'ADMIN-001','password'=>'adminpass','role'=>'admin','status'=>'active']);
            echo "Admin user created.\n";
        } else { echo "Admin user already exists.\n"; }
    }
}