<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeOtherIdModel extends Model
{
    protected $table         = 'employee_other_ids';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $skipValidation = true;

    protected $allowedFields = [
        'employee_id',
        'id_type',
        'id_number',
        'expiration',
        'remarks',
    ];

    public const ID_TYPES = [
        'Driver\'s License',
        'Passport',
        'Voter\'s ID',
        'PhilSys ID (National ID)',
        'NBI Clearance',
        'Police Clearance',
        'Barangay Clearance',
        'UMID',
        'Postal ID',
        'Senior Citizen ID',
        'PWD ID',
        'OWWA ID',
        'OFW ID',
        'Seaman\'s Book',
        'MARINA Certificate',
        'TESDA Certificate',
        'HDMF (Pag-IBIG) ID',
        'Professional ID',
        'Company ID',
        'School ID',
        'Other',
    ];

    public function getByEmployee(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('id_type')
            ->findAll();
    }
}
