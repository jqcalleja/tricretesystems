<?php

namespace App\Models;

use CodeIgniter\Model;

class EmploymentHistoryModel extends Model
{
    protected $table         = 'employment_history';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'employee_id',
        'company_name',
        'position',
        'date_from',
        'date_to',
    ];

    protected $validationRules = [
        'employee_id'  => 'required|is_natural_no_zero',
        'company_name' => 'required|max_length[200]',
        'date_from'    => 'permit_empty|valid_date',
        'date_to'      => 'permit_empty|valid_date',
    ];

    public function getByEmployee(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('date_from', 'DESC')
            ->findAll();
    }
}
