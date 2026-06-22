<?php

namespace App\Models;

use CodeIgniter\Model;

class CharacterReferenceModel extends Model
{
    protected $table         = 'character_references';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'employee_id',
        'name',
        'occupation',
        'address_id',
        'address_street',
        'telephone',
    ];

    protected $validationRules = [
        'employee_id' => 'required|is_natural_no_zero',
        'name'        => 'required|max_length[150]',
    ];

    public function getByEmployee(int $employeeId): array
    {
        return $this->db->table('character_references cr')
            ->select('cr.*, a.province, a.city, a.barangay')
            ->join('addresses a', 'a.id = cr.address_id', 'left')
            ->where('cr.employee_id', $employeeId)
            ->orderBy('cr.name', 'ASC')
            ->get()->getResultArray();
    }
}
