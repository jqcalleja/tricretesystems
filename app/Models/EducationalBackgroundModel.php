<?php

namespace App\Models;

use CodeIgniter\Model;

class EducationalBackgroundModel extends Model
{
    protected $table         = 'educational_backgrounds';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'employee_id',
        'level',
        'school_name',
        'year_graduated',
    ];

    protected $validationRules = [
        'employee_id'    => 'required|is_natural_no_zero',
        'level'          => 'required|in_list[Elementary,High School,Vocational,College,MA/PhD,Others]',
        'year_graduated' => 'permit_empty|integer|greater_than[1900]',
    ];

    protected $levelOrder = [
        'Elementary'  => 1,
        'High School' => 2,
        'Vocational'  => 3,
        'College'     => 4,
        'MA/PhD'      => 5,
        'Others'      => 6,
    ];

    public function getByEmployee(int $employeeId): array
    {
        $rows = $this->where('employee_id', $employeeId)->findAll();

        usort($rows, function ($a, $b) {
            $oa = $this->levelOrder[$a['level']] ?? 99;
            $ob = $this->levelOrder[$b['level']] ?? 99;
            return $oa - $ob;
        });

        return $rows;
    }
}
