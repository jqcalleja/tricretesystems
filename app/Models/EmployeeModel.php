<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table          = 'employees';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $skipValidation = true;

    protected $allowedFields = [
        'employee_no',
        'last_name',
        'first_name',
        'middle_name',
        'nickname',
        'photo',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'civil_status',
        'citizenship',
        'religion',
        'height_cm',
        'weight_kg',
        'current_address_id',
        'current_address_street',
        'provincial_address_id',
        'provincial_address_street',
        'contact_number',
        'email_address',
        'spouse_name',
        'spouse_occupation',
        'spouse_address_id',
        'spouse_address_street',
        'spouse_contact_number',
        'father_name',
        'father_occupation',
        'mother_name',
        'mother_occupation',
        'parents_address_id',
        'parents_address_street',
        'sss_number',
        'philhealth_number',
        'pagibig_number',
        'tin_number',
        'position_id',
        'department_id',
        'employment_status',
        'rate_type',
        'rate',
        'date_hired',
        'contract_expiry',
        'date_resigned',
        'is_active',
        'special_skills',
    ];

    public $validationRules = [
        'employee_no'       => 'required|max_length[20]|is_unique[employees.employee_no,id,{id}]',
        'last_name'         => 'required|max_length[80]',
        'first_name'        => 'required|max_length[80]',
        'gender'            => 'required|in_list[Male,Female,Other]',
        'date_of_birth'     => 'required|valid_date',
        'civil_status'      => 'required|in_list[Single,Married,Widowed,Separated,Divorced]',
        'employment_status' => 'required|in_list[Regular,Probationary,Project-Based,Casual]',
        'rate_type'         => 'required|in_list[Monthly,Daily]',
        'rate'              => 'required|decimal',
        'date_hired'        => 'required|valid_date',
        'email_address'     => 'permit_empty|valid_email|max_length[150]',
    ];

    protected $validationMessages = [
        'employee_no' => [
            'is_unique' => 'This Employee ID is already in use.',
        ],
        'email_address' => [
            'valid_email' => 'Please enter a valid email address.',
        ],
    ];

    public function getFullName(array $employee): string
    {
        $middle = $employee['middle_name']
            ? ' ' . substr($employee['middle_name'], 0, 1) . '.'
            : '';
        return $employee['last_name'] . ', '
            . $employee['first_name']
            . $middle;
    }

    public function getList(array $filters = []): array
    {
        $builder = $this->db->table('employees e')
            ->select('e.*,
                  p.title AS position_title,
                  d.name AS department_name,
                  ca.province AS current_address_province,
                  ca.city     AS current_address_city,
                  ca.barangay AS current_address_barangay')
            ->join('positions p',   'p.id = e.position_id',   'left')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('addresses ca',  'ca.id = e.current_address_id', 'left');

        if (isset($filters['is_active']) && $filters['is_active'] !== null) {
            $builder->where('e.is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $builder->groupStart()
                ->like('e.last_name',   $s)
                ->orLike('e.first_name', $s)
                ->orLike('e.employee_no', $s)
                ->groupEnd();
        }

        if (!empty($filters['department_id'])) {
            $builder->where('e.department_id', $filters['department_id']);
        }

        if (!empty($filters['employment_status'])) {
            $builder->where('e.employment_status', $filters['employment_status']);
        }

        return $builder->orderBy('e.last_name', 'ASC')
            ->orderBy('e.first_name', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Single employee with joins — includes all 4 address records.
     * Street comes from the employee row itself; province/city/barangay
     * come from the joined addresses table.
     */
    public function getWithDetails(int $id): ?array
    {
        $row = $this->db->table('employees e')
            ->select('e.*,
                      p.title AS position_title,
                      d.name AS department_name,
                      ca.province AS current_address_province,
                      ca.city     AS current_address_city,
                      ca.barangay AS current_address_barangay,
                      pa.province AS provincial_address_province,
                      pa.city     AS provincial_address_city,
                      pa.barangay AS provincial_address_barangay,
                      sa.province AS spouse_address_province,
                      sa.city     AS spouse_address_city,
                      sa.barangay AS spouse_address_barangay,
                      pra.province AS parents_address_province,
                      pra.city     AS parents_address_city,
                      pra.barangay AS parents_address_barangay')
            ->join('positions p',   'p.id = e.position_id',   'left')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('addresses ca',  'ca.id = e.current_address_id',    'left')
            ->join('addresses pa',  'pa.id = e.provincial_address_id', 'left')
            ->join('addresses sa',  'sa.id = e.spouse_address_id',     'left')
            ->join('addresses pra', 'pra.id = e.parents_address_id',   'left')
            ->where('e.id', $id)
            ->get()->getRowArray();

        return $row ?: null;
    }

    public function calculateAge(string $dob): int
    {
        return (int) date_diff(
            date_create($dob),
            date_create('today')
        )->y;
    }

    public function generateEmployeeNo(): string
    {
        $year   = date('Y');
        $prefix = 'EMP-' . $year . '-';
        $last   = $this->db->table('employees')
            ->like('employee_no', $prefix, 'after')
            ->orderBy('employee_no', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        if ($last) {
            $num = (int) substr($last['employee_no'], -4) + 1;
        } else {
            $num = 1;
        }

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
