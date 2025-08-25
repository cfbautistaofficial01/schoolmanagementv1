<?php namespace App\Models;
use CodeIgniter\Model;
class QualificationModel extends Model {
    protected $table            = 'qualifications';
    protected $primaryKey       = 'id';
    protected $allowedFields = ['qualification_name', 'level', 'category', 'description', 'regulation_type', 'is_active'];
    
    
    // Enable soft deletes
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    // We don't need these for this model, but it's good practice
    protected $useTimestamps    = false;
}