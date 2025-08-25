<?php namespace App\Models;
use CodeIgniter\Model;
class EnrollmentModel extends Model {
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['trainee_user_id', 'qualification_id', 'trainer_user_id', 'enrollment_date', 'status'];
}