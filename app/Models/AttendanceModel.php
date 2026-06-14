<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table          = 'attendance';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
}
