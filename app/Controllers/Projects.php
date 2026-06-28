<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\ProjectAssignmentModel;
use App\Models\EmployeeModel;

class Projects extends BaseController
{
    protected ProjectModel           $projectModel;
    protected ProjectAssignmentModel $assignmentModel;
    protected EmployeeModel          $employeeModel;

    public function __construct()
    {
        $this->projectModel    = new ProjectModel();
        $this->assignmentModel = new ProjectAssignmentModel();
        $this->employeeModel   = new EmployeeModel();
    }

    // ============================================================
    // LIST — Active Projects
    // ============================================================
    /**
     * Display active project cards with worker count and site engineer.
     *
     * @return string Rendered view with:
     *                 @var array  $projects  Active projects with aggregated data
     *                 @var array  $filters   Current filter values
     */
    public function index(): string
    {
        $filters = [
            'search' => $this->request->getGet('search'),
        ];

        return view('projects/index', [
            'pageTitle' => 'Projects',
            'projects'  => $this->projectModel->getActiveList($filters),
            'filters'   => $filters,
        ]);
    }

    // ============================================================
    // VIEW / DETAIL
    // ============================================================
    /**
     * Display project detail page with map and assigned employees.
     *
     * @param int $id Project ID
     * @return string Rendered view with:
     *                 @var array $project      Project record with site engineer
     *                 @var array $assignments  All active assigned employees
     *                 @var int   $workerCount  Total active workers (non-engineer)
     *                 @var int   $daysRemaining
     */
    public function view(int $id): string
    {
        $project = $this->projectModel->getWithDetails($id);

        if (! $project) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                "Project #{$id} not found."
            );
        }

        $assignments  = $this->assignmentModel->getByProject($id);
        $workerCount  = count(array_filter($assignments, fn($a) => ! $a['is_site_engineer']));
        $daysRemaining = $project['end_date']
            ? $this->projectModel->getDaysRemaining($project['end_date'])
            : null;

        return view('projects/view', [
            'pageTitle'     => $project['project_name'],
            'project'       => $project,
            'assignments'   => $assignments,
            'workerCount'   => $workerCount,
            'daysRemaining' => $daysRemaining,
        ]);
    }

    // ============================================================
    // CREATE
    // ============================================================
    /**
     * Display the create project form.
     *
     * @return string Rendered view with:
     *                 @var string $project_code  Auto-generated project code
     *                 @var array  $employees     All active employees for assignment
     */
    public function create(): string
    {
        return view('projects/create', [
            'pageTitle'    => 'Add Project',
            'project_code' => $this->projectModel->generateProjectCode(),
            'employees'    => $this->employeeModel->where('is_active', 1)
                ->orderBy('last_name')
                ->findAll(),
        ]);
    }

    public function store()
    {
        $rules = $this->projectModel->validationRules;

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost($this->projectModel->allowedFields);
        $data = $this->uppercaseFields($data, [
            'status',
            'latitude',
            'longitude',
            'contract_amount',
        ]);

        // Nullable fields
        $data['start_date']       = ($data['start_date']       ?? '') ?: null;
        $data['end_date']         = ($data['end_date']         ?? '') ?: null;
        $data['contract_amount']  = ($data['contract_amount']  ?? '') ?: null;
        $data['latitude']         = ($data['latitude']         ?? '') ?: null;
        $data['longitude']        = ($data['longitude']        ?? '') ?: null;

        $id = $this->projectModel->insert($data);

        // Handle employee assignments
        $this->syncAssignments(
            $id,
            $this->request->getPost('assigned_employees') ?? [],
            $this->request->getPost('site_engineer_id')
        );

        return redirect()->to("/projects/view/{$id}")
            ->with('success', 'Project created successfully.');
    }

    // ============================================================
    // EDIT
    // ============================================================
    /**
     * Display the edit project form.
     *
     * @param int $id Project ID
     * @return string Rendered view with:
     *                 @var array  $project           Project record
     *                 @var array  $employees         All active employees
     *                 @var array  $assignedIds       Currently assigned employee IDs
     *                 @var int    $siteEngineerId    Currently designated site engineer ID
     */
    public function edit(int $id): string
    {
        $project = $this->projectModel->find($id);

        if (! $project) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                "Project #{$id} not found."
            );
        }

        $assignments     = $this->assignmentModel->getByProject($id);
        $assignedIds     = [];
        $siteEngineerId  = null;

        foreach ($assignments as $a) {
            if ($a['is_site_engineer']) {
                $siteEngineerId = (int) $a['employee_id'];
                continue;
            }

            $assignedIds[] = (int) $a['employee_id'];
        }

        return view('projects/edit', [
            'pageTitle'      => 'Edit — ' . $project['project_name'],
            'project'        => $project,
            'employees'      => $this->employeeModel->where('is_active', 1)
                ->orderBy('last_name')
                ->findAll(),
            'assignedIds'    => $assignedIds,
            'siteEngineerId' => $siteEngineerId,
        ]);
    }

    public function update(int $id)
    {
        $project = $this->projectModel->find($id);

        if (! $project) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                "Project #{$id} not found."
            );
        }

        $rules = $this->projectModel->validationRules;
        $rules['project_code'] = str_replace('{id}', (string) $id, $rules['project_code']);

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost($this->projectModel->allowedFields);
        $data = $this->uppercaseFields($data, [
            'status',
            'latitude',
            'longitude',
            'contract_amount',
        ]);

        $data['start_date']       = ($data['start_date']       ?? '') ?: null;
        $data['end_date']         = ($data['end_date']         ?? '') ?: null;
        $data['contract_amount']  = ($data['contract_amount']  ?? '') ?: null;
        $data['latitude']         = ($data['latitude']         ?? '') ?: null;
        $data['longitude']        = ($data['longitude']        ?? '') ?: null;

        $this->projectModel->update($id, $data);

        // Re-sync assignments
        $this->syncAssignments(
            $id,
            $this->request->getPost('assigned_employees') ?? [],
            $this->request->getPost('site_engineer_id')
        );

        return redirect()->to("/projects/view/{$id}")
            ->with('success', 'Project updated successfully.');
    }

    // ============================================================
    // HELPERS
    // ============================================================

    /**
     * Sync employee assignments for a project.
     * Deactivates all existing, then re-inserts the submitted list.
     *
     * @param int      $projectId
     * @param array    $employeeIds    Selected employee IDs from the multi-select
     * @param int|null $siteEngineerId The designated site engineer's employee ID
     */
    private function syncAssignments(int $projectId, array $employeeIds, ?string $siteEngineerId): void
    {
        // Deactivate all current assignments
        $this->assignmentModel->deactivateAll($projectId);

        $siteEngineerId = (int) ($siteEngineerId ?: 0);
        $employeeIds = array_map('intval', $employeeIds);
        $employeeIds = array_values(array_unique(array_filter(
            $employeeIds,
            fn($empId) => $empId > 0 && $empId !== $siteEngineerId
        )));

        if ($siteEngineerId) {
            array_unshift($employeeIds, $siteEngineerId);
        }

        if (empty($employeeIds)) return;

        $today = date('Y-m-d');

        foreach ($employeeIds as $empId) {
            $empId = (int) $empId;
            if (! $empId) continue;

            $isSiteEngineer = ($siteEngineerId === $empId) ? 1 : 0;

            // Check if a deactivated assignment exists — reactivate if so
            $existing = $this->assignmentModel
                ->where('project_id', $projectId)
                ->where('employee_id', $empId)
                ->first();

            if ($existing) {
                $this->assignmentModel->update($existing['id'], [
                    'is_active'       => 1,
                    'date_assigned'   => $existing['date_assigned'],
                    'date_removed'    => null,
                    'is_site_engineer' => $isSiteEngineer,
                ]);
            } else {
                $this->assignmentModel->insert([
                    'project_id'       => $projectId,
                    'employee_id'      => $empId,
                    'date_assigned'    => $today,
                    'is_active'        => 1,
                    'is_site_engineer' => $isSiteEngineer,
                ]);
            }
        }
    }
}
