<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table          = 'projects';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $skipValidation = true;

    protected $allowedFields = [
        'project_code',
        'project_name',
        'client_name',
        'location',
        'latitude',
        'longitude',
        'status',
        'start_date',
        'end_date',
        'contract_amount',
        'description',
    ];

    public $validationRules = [
        'project_code' => 'required|max_length[30]|is_unique[projects.project_code,id,{id}]',
        'project_name' => 'required|max_length[150]',
        'status'       => 'required|in_list[Active,Completed,On Hold,Cancelled]',
    ];

    protected $validationMessages = [
        'project_code' => [
            'is_unique' => 'This project code is already in use.',
        ],
    ];

    /**
     * Get active projects with worker count and site engineer.
     */
    public function getActiveList(array $filters = []): array
    {
        $builder = $this->db->table('projects p')
            ->select('p.*,
                      COUNT(DISTINCT CASE WHEN pa.is_active = 1 AND pa.is_site_engineer = 0 THEN pa.employee_id END) AS worker_count,
                      e.first_name AS site_engineer_first,
                      e.last_name  AS site_engineer_last,
                      e.photo      AS site_engineer_photo')
            ->join('project_assignments pa', 'pa.project_id = p.id AND pa.is_active = 1', 'left')
            ->join('project_assignments se', 'se.project_id = p.id AND se.is_active = 1 AND se.is_site_engineer = 1', 'left')
            ->join('employees e', 'e.id = se.employee_id', 'left')
            ->where('p.status', 'Active')
            ->groupBy('p.id');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $builder->groupStart()
                ->like('p.project_name', $s)
                ->orLike('p.project_code', $s)
                ->orLike('p.client_name', $s)
                ->groupEnd();
        }

        return $builder->orderBy('p.start_date', 'DESC')
            ->get()->getResultArray();
    }

    /**
     * Get single project with site engineer details.
     */
    public function getWithDetails(int $id): ?array
    {
        $row = $this->db->table('projects p')
            ->select('p.*,
                      e.id         AS site_engineer_id,
                      e.first_name AS site_engineer_first,
                      e.last_name  AS site_engineer_last,
                      e.photo      AS site_engineer_photo,
                      e.contact_number AS site_engineer_contact,
                      pos.title    AS site_engineer_position')
            ->join('project_assignments se', 'se.project_id = p.id AND se.is_active = 1 AND se.is_site_engineer = 1', 'left')
            ->join('employees e', 'e.id = se.employee_id', 'left')
            ->join('positions pos', 'pos.id = e.position_id', 'left')
            ->where('p.id', $id)
            ->get()->getRowArray();

        return $row ?: null;
    }

    /**
     * Generate next project code.
     */
    public function generateProjectCode(): string
    {
        $year   = date('Y');
        $prefix = 'PRJ-' . $year . '-';
        $last   = $this->db->table('projects')
            ->like('project_code', $prefix, 'after')
            ->orderBy('project_code', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        $num = $last ? (int) substr($last['project_code'], -4) + 1 : 1;
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get days remaining or overdue.
     */
    public function getDaysRemaining(string $endDate): int
    {
        return (int) date_diff(date_create('today'), date_create($endDate))->days
            * (date_create($endDate) >= date_create('today') ? 1 : -1);
    }
}
