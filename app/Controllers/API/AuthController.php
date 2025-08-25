<?php namespace App\Controllers\API;
use App\Controllers\BaseController;
use App\Models\UserModel;
class AuthController extends BaseController {
    public function login() {
        $userModel = new UserModel();
        $userIdCode = $this->request->getJSON()->user_id_code ?? '';
        $password = $this->request->getJSON()->password ?? '';
        $user = $userModel->where('user_id_code', $userIdCode)->first();
        if ($user && password_verify($password, $user['password'])) {
            if ($user['role'] === 'admin' || $user['role'] === 'staff') {
                return $this->response->setJSON(['status'=>'success','message'=>'Login successful','user'=>['user_id_code'=>$user['user_id_code'],'role'=>$user['role']]]);
            }
        }
        return $this->response->setStatusCode(401)->setJSON(['status'=>'error','message'=>'Invalid credentials.']);
    }
}