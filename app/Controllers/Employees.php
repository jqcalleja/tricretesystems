<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\EmergencyContactModel;
use App\Models\EmployeeChildModel;
use App\Models\EducationalBackgroundModel;
use App\Models\EmploymentHistoryModel;
use App\Models\CharacterReferenceModel;
use App\Models\DepartmentModel;
use App\Models\PositionModel;

class Employees extends BaseController
{
    protected EmployeeModel             $employeeModel;
    protected EmergencyContactModel     $emergencyModel;
    protected EmployeeChildModel        $childModel;
    protected EducationalBackgroundModel $educationModel;
    protected EmploymentHistoryModel    $historyModel;
    protected CharacterReferenceModel   $referenceModel;
    protected DepartmentModel           $departmentModel;
    protected PositionModel             $positionModel;

    public function __construct()
    {
        $this->employeeModel   = new EmployeeModel();
        $this->emergencyModel  = new EmergencyContactModel();
        $this->childModel      = new EmployeeChildModel();
        $this->educationModel  = new EducationalBackgroundModel();
        $this->historyModel    = new EmploymentHistoryModel();
        $this->referenceModel  = new CharacterReferenceModel();
        $this->departmentModel = new DepartmentModel();
        $this->positionModel   = new PositionModel();
    }

    // ============================================================
    // LIST
    // ============================================================
    /**
     * Display the employee list with filters.
     *
     * @return string Rendered view with:
     *                 @var array $employees
     *                 @var array $departments
     *                 @var array $filters
     */
    public function index(): string
    {
        $statusParam = $this->request->getGet('status');

        $filters = [
            'search'            => $this->request->getGet('search'),
            'department_id'     => $this->request->getGet('department_id'),
            'employment_status' => $this->request->getGet('employment_status'),
            'is_active'         => ($statusParam !== null && $statusParam !== '')
                ? (int) $statusParam
                : null,
        ];

        return view('employees/index', [
            'pageTitle'   => 'Employees',
            'employees'   => $this->employeeModel->getList($filters),
            'departments' => $this->departmentModel->orderBy('name')->findAll(),
            'filters'     => $filters,
        ]);
    }

    // ============================================================
    // CREATE
    // ============================================================
    /**
     * Display the add employee form.
     *
     * @return string Rendered view with:
     *                 @var array  $departments
     *                 @var array  $positions
     *                 @var string $employee_no
     */
    public function create(): string
    {
        return view('employees/create', [
            'pageTitle'   => 'Add Employee',
            'departments' => $this->departmentModel->orderBy('name')->findAll(),
            'positions'   => $this->positionModel->orderBy('title')->findAll(),
            'employee_no' => $this->employeeModel->generateEmployeeNo(),
        ]);
    }

    public function store()
    {
        $rules = $this->employeeModel->validationRules;

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost($this->employeeModel->allowedFields);
        $data = $this->uppercaseFields($data, [
            'email_address',
            'gender',
            'civil_status',
            'employment_status',
            'rate_type',
        ]);

        // Explicitly set is_active — never rely on form input or DB default
        $data['is_active'] = 1;

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(FCPATH . 'assets/images/uploads/employees', $newName);
            $data['photo'] = $newName;
        }

        $id = $this->employeeModel->insert($data);

        return redirect()->to("/employees/view/{$id}")
            ->with('success', 'Employee added successfully.');
    }

    // ============================================================
    // VIEW / PROFILE
    // ============================================================
    /**
     * Display the employee profile with all related records.
     *
     * @param int $id Employee ID
     * @return string Rendered view with:
     *                 @var array $employee
     *                 @var int   $age
     *                 @var array $emergency
     *                 @var array $children
     *                 @var array $education
     *                 @var array $history
     *                 @var array $references
     */
    public function view(int $id): string
    {
        $employee = $this->employeeModel->getWithDetails($id);

        if (! $employee) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                "Employee #{$id} not found."
            );
        }

        return view('employees/view', [
            'pageTitle'   => $employee['last_name'] . ', ' . $employee['first_name'],
            'employee'    => $employee,
            'age'         => $this->employeeModel->calculateAge($employee['date_of_birth']),
            'emergency'   => $this->emergencyModel->getByEmployee($id),
            'children'    => $this->childModel->getByEmployee($id),
            'education'   => $this->educationModel->getByEmployee($id),
            'history'     => $this->historyModel->getByEmployee($id),
            'references'  => $this->referenceModel->getByEmployee($id),
        ]);
    }

    // ============================================================
    // EDIT
    // ============================================================
    /**
     * Display the edit employee form.
     *
     * @param int $id Employee ID
     * @return string Rendered view with:
     *                 @var array $employee
     *                 @var array $departments
     *                 @var array $positions
     */
    public function edit(int $id): string
    {
        $employee = $this->employeeModel->find($id);

        if (! $employee) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                "Employee #{$id} not found."
            );
        }

        return view('employees/edit', [
            'pageTitle'   => 'Edit — ' . $employee['last_name'] . ', ' . $employee['first_name'],
            'employee'    => $employee,
            'departments' => $this->departmentModel->orderBy('name')->findAll(),
            'positions'   => $this->positionModel->orderBy('title')->findAll(),
        ]);
    }

    /**
     * Update an existing employee record.
     *
     * @param int $id Employee ID
     */
    public function update(int $id)
    {
        $employee = $this->employeeModel->find($id);

        if (! $employee) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                "Employee #{$id} not found."
            );
        }

        $rules = $this->employeeModel->validationRules;

        // Manually substitute {id} since we're not using $model->save()'s built-in validation
        $rules['employee_no'] = str_replace('{id}', (string) $id, $rules['employee_no']);

        // TEMPORARY DEBUG
        log_message('debug', 'Employee_no rule being used: ' . $rules['employee_no']);

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost($this->employeeModel->allowedFields);
        $data = $this->uppercaseFields($data, [
            'email_address',
            'gender',
            'civil_status',
            'employment_status',
            'rate_type',
        ]);

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            // Delete old photo
            if ($employee['photo']) {
                $oldPath = FCPATH . 'assets/images/uploads/employees/' . $employee['photo'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $newName = $photo->getRandomName();
            $photo->move(FCPATH . 'assets/images/uploads/employees', $newName);
            $data['photo'] = $newName;
        } else {
            unset($data['photo']);
        }

        $result = $this->employeeModel->update($id, $data);

        if (! $result) {
            log_message('error', 'Employee update failed for ID ' . $id . ': ' . json_encode($this->employeeModel->errors()));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update employee. ' . implode(' ', $this->employeeModel->errors() ?? []));
        }

        return redirect()->to("/employees/view/{$id}")
            ->with('success', 'Employee updated successfully.');
    }

    // ============================================================
    // TOGGLE ACTIVE STATUS
    // ============================================================
    public function toggleStatus(int $id)
    {
        $employee = $this->employeeModel->find($id);

        if (! $employee) {
            return redirect()->to('/employees')
                ->with('error', 'Employee not found.');
        }

        $this->employeeModel->update($id, [
            'is_active' => $employee['is_active'] ? 0 : 1,
        ]);

        $status = $employee['is_active'] ? 'deactivated' : 'activated';

        return redirect()->to("/employees/view/{$id}")
            ->with('success', "Employee {$status} successfully.");
    }

    // ============================================================
    // EMERGENCY CONTACTS
    // ============================================================
    public function storeEmergency(int $employeeId)
    {
        $data = [
            'employee_id'    => $employeeId,
            'last_name'      => $this->request->getPost('last_name'),
            'first_name'     => $this->request->getPost('first_name'),
            'middle_name'    => $this->request->getPost('middle_name'),
            'relationship'   => $this->request->getPost('relationship'),
            'address'        => $this->request->getPost('address'),
            'contact_number' => $this->request->getPost('contact_number'),
            'sort_order'     => $this->request->getPost('sort_order') ?? 1,
        ];

        if (! $this->validate($this->emergencyModel->validationRules)) {
            return redirect()->back()->with('error', 'Please fill in required fields.');
        }

        $this->emergencyModel->insert($data);

        return redirect()->to("/employees/view/{$employeeId}#emergency")
            ->with('success', 'Emergency contact added.');
    }

    public function updateEmergency(int $employeeId, int $contactId)
    {
        $data = [
            'last_name'      => $this->request->getPost('last_name'),
            'first_name'     => $this->request->getPost('first_name'),
            'middle_name'    => $this->request->getPost('middle_name'),
            'relationship'   => $this->request->getPost('relationship'),
            'address'        => $this->request->getPost('address'),
            'contact_number' => $this->request->getPost('contact_number'),
            'sort_order'     => $this->request->getPost('sort_order') ?? 1,
        ];

        $this->emergencyModel->update($contactId, $data);

        return redirect()->to("/employees/view/{$employeeId}#emergency")
            ->with('success', 'Emergency contact updated.');
    }

    public function deleteEmergency(int $employeeId, int $contactId)
    {
        $this->emergencyModel->delete($contactId);

        return redirect()->to("/employees/view/{$employeeId}#emergency")
            ->with('success', 'Emergency contact removed.');
    }

    // ============================================================
    // CHILDREN
    // ============================================================
    public function storeChild(int $employeeId)
    {
        $data = [
            'employee_id' => $employeeId,
            'name'        => $this->request->getPost('name'),
            'birthday'    => $this->request->getPost('birthday') ?: null,
        ];

        $this->childModel->insert($data);

        return redirect()->to("/employees/view/{$employeeId}#children")
            ->with('success', 'Child record added.');
    }

    public function updateChild(int $employeeId, int $childId)
    {
        $data = [
            'name'     => $this->request->getPost('name'),
            'birthday' => $this->request->getPost('birthday') ?: null,
        ];

        $this->childModel->update($childId, $data);

        return redirect()->to("/employees/view/{$employeeId}#children")
            ->with('success', 'Child record updated.');
    }

    public function deleteChild(int $employeeId, int $childId)
    {
        $this->childModel->delete($childId);

        return redirect()->to("/employees/view/{$employeeId}#children")
            ->with('success', 'Child record removed.');
    }

    // ============================================================
    // EDUCATION
    // ============================================================
    public function storeEducation(int $employeeId)
    {
        $data = [
            'employee_id'    => $employeeId,
            'level'          => $this->request->getPost('level'),
            'school_name'    => $this->request->getPost('school_name'),
            'year_graduated' => $this->request->getPost('year_graduated') ?: null,
        ];

        $this->educationModel->insert($data);

        return redirect()->to("/employees/view/{$employeeId}#education")
            ->with('success', 'Education record added.');
    }

    public function updateEducation(int $employeeId, int $educationId)
    {
        $data = [
            'level'          => $this->request->getPost('level'),
            'school_name'    => $this->request->getPost('school_name'),
            'year_graduated' => $this->request->getPost('year_graduated') ?: null,
        ];

        $this->educationModel->update($educationId, $data);

        return redirect()->to("/employees/view/{$employeeId}#education")
            ->with('success', 'Education record updated.');
    }

    public function deleteEducation(int $employeeId, int $educationId)
    {
        $this->educationModel->delete($educationId);

        return redirect()->to("/employees/view/{$employeeId}#education")
            ->with('success', 'Education record removed.');
    }

    // ============================================================
    // EMPLOYMENT HISTORY
    // ============================================================
    public function storeHistory(int $employeeId)
    {
        $data = [
            'employee_id'  => $employeeId,
            'company_name' => $this->request->getPost('company_name'),
            'position'     => $this->request->getPost('position'),
            'date_from'    => $this->request->getPost('date_from') ?: null,
            'date_to'      => $this->request->getPost('date_to') ?: null,
        ];

        $this->historyModel->insert($data);

        return redirect()->to("/employees/view/{$employeeId}#history")
            ->with('success', 'Employment history added.');
    }

    public function updateHistory(int $employeeId, int $historyId)
    {
        $data = [
            'company_name' => $this->request->getPost('company_name'),
            'position'     => $this->request->getPost('position'),
            'date_from'    => $this->request->getPost('date_from') ?: null,
            'date_to'      => $this->request->getPost('date_to') ?: null,
        ];

        $this->historyModel->update($historyId, $data);

        return redirect()->to("/employees/view/{$employeeId}#history")
            ->with('success', 'Employment history updated.');
    }

    public function deleteHistory(int $employeeId, int $historyId)
    {
        $this->historyModel->delete($historyId);

        return redirect()->to("/employees/view/{$employeeId}#history")
            ->with('success', 'Employment history removed.');
    }

    // ============================================================
    // CHARACTER REFERENCES
    // ============================================================
    public function storeReference(int $employeeId)
    {
        $data = [
            'employee_id' => $employeeId,
            'name'        => $this->request->getPost('name'),
            'occupation'  => $this->request->getPost('occupation'),
            'address'     => $this->request->getPost('address'),
            'telephone'   => $this->request->getPost('telephone'),
        ];

        $this->referenceModel->insert($data);

        return redirect()->to("/employees/view/{$employeeId}#references")
            ->with('success', 'Character reference added.');
    }

    public function updateReference(int $employeeId, int $referenceId)
    {
        $data = [
            'name'       => $this->request->getPost('name'),
            'occupation' => $this->request->getPost('occupation'),
            'address'    => $this->request->getPost('address'),
            'telephone'  => $this->request->getPost('telephone'),
        ];

        $this->referenceModel->update($referenceId, $data);

        return redirect()->to("/employees/view/{$employeeId}#references")
            ->with('success', 'Character reference updated.');
    }

    public function deleteReference(int $employeeId, int $referenceId)
    {
        $this->referenceModel->delete($referenceId);

        return redirect()->to("/employees/view/{$employeeId}#references")
            ->with('success', 'Character reference removed.');
    }
}
