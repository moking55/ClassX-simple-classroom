<?php

namespace App\Models;
use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table      = 'annoucements';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'class_id',
        'title',
        'content'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
