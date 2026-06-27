<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeePrcLicenseModel extends Model
{
    protected $table          = 'employee_prc_licenses';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $skipValidation = true;

    protected $allowedFields = [
        'employee_id',
        'profession',
        'license_number',
        'expiration',
    ];

    public function getByEmployee(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('profession')
            ->findAll();
    }
}
