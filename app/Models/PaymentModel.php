<?php namespace App\Models;
use CodeIgniter\Model;
class PaymentModel extends Model {
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['enrollment_id', 'amount', 'payment_method', 'transaction_date', 'recorded_by_user_id'];
}