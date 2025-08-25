<?php namespace App\Models;
use CodeIgniter\Model;
class TraineeDetailModel extends Model {
    protected $table = 'trainee_details';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'first_name', 'last_name', 'address', 'age', 'contact_number', 'educational_bg', 'registration_date'];
}