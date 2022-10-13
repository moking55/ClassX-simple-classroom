<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassRoomModel extends Model
{
    protected $table      = 'classroom';
    protected $primaryKey = 'class_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'university_id',
        'class_name',
        'class_description',
        'class_owner',
        'class_code',
        'class_invite',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
