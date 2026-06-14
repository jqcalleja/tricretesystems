<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectAssignmentModel extends Model
{
    protected $table          = 'project_assignments';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
}
