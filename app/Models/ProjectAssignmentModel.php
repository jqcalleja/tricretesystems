<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectAssignmentModel extends Model
{
    protected $table          = 'project_assignments';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $skipValidation = true;

    protected $allowedFields = [
        'project_id',
        'employee_id',
        'date_assigned',
        'date_removed',
        'is_active',
        'role',
        'is_site_engineer',
        'remarks',
    ];

    /**
     * Get all active assignments for a project, site engineer first.
     */
    public function getByProject(int $projectId): array
    {
        return $this->db->table('project_assignments pa')
            ->select('pa.*,
                      e.first_name, e.last_name, e.middle_name,
                      e.employee_no, e.photo, e.contact_number,
                      p.title AS position_title')
            ->join('employees e', 'e.id = pa.employee_id', 'left')
            ->join('positions p', 'p.id = e.position_id', 'left')
            ->where('pa.project_id', $projectId)
            ->where('pa.is_active', 1)
            ->orderBy('pa.is_site_engineer', 'DESC')
            ->orderBy('e.last_name', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Get IDs of employees currently assigned to a project (for form pre-selection).
     */
    public function getAssignedEmployeeIds(int $projectId): array
    {
        $rows = $this->where('project_id', $projectId)
            ->where('is_active', 1)
            ->findAll();
        return array_column($rows, 'employee_id');
    }

    /**
     * Deactivate all current assignments for a project.
     */
    public function deactivateAll(int $projectId): void
    {
        $this->where('project_id', $projectId)
            ->set(['is_active' => 0, 'date_removed' => date('Y-m-d')])
            ->update();
    }
}
