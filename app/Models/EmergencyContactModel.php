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
        'address_id',
        'address_street',
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
        return $this->db->table('emergency_contacts ec')
            ->select('ec.*, a.province, a.city, a.barangay')
            ->join('addresses a', 'a.id = ec.address_id', 'left')
            ->where('ec.employee_id', $employeeId)
            ->orderBy('ec.sort_order', 'ASC')
            ->get()->getResultArray();
    }
}
