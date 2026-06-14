<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeChildModel extends Model
{
    protected $table         = 'employee_children';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    protected $allowedFields = [
        'employee_id',
        'name',
        'birthday',
    ];

    protected $validationRules = [
        'employee_id' => 'required|is_natural_no_zero',
        'name'        => 'required|max_length[150]',
        'birthday'    => 'permit_empty|valid_date',
    ];

    public function getByEmployee(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('birthday', 'ASC')
            ->findAll();
    }
}
