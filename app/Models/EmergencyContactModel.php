<?php

namespace App\Models;

use CodeIgniter\Model;

class EmergencyContactModel extends Model
{
    protected $table         = 'emergency_contacts';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'employee_id',
        'last_name',
        'first_name',
        'middle_name',
        'relationship',
        'address',
        'contact_number',
        'sort_order',
    ];

    protected $validationRules = [
        'employee_id' => 'required|is_natural_no_zero',
        'last_name'   => 'required|max_length[80]',
        'first_name'  => 'required|max_length[80]',
    ];

    public function getByEmployee(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
