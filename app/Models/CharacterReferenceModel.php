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
        'address',
        'telephone',
    ];

    protected $validationRules = [
        'employee_id' => 'required|is_natural_no_zero',
        'name'        => 'required|max_length[150]',
    ];

    public function getByEmployee(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}
