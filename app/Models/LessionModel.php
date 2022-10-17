<?php

namespace App\Models;
use CodeIgniter\Model;
class LessionModel extends Model
{
    protected $table      = 'class_lession';
    protected $primaryKey = 'les_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'class_id',
        'less_title',
        'less_content'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
