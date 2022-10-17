<?php

namespace App\Models;
use CodeIgniter\Model;

class AssignmentModel extends Model
{
    protected $table      = 'assignments';
    protected $primaryKey = 'a_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'a_classid',
        'a_name',
        'a_instruction',
        'due_date',
        'a_score',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
