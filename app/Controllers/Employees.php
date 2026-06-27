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
use App\Models\AddressModel;
use App\Models\EmployeeIdDocumentModel;
use App\Models\EmployeeOtherIdModel;
use App\Models\EmployeePrcLicenseModel;

class Employees extends BaseController
{
    protected EmployeeModel              $employeeModel;
    protected EmergencyContactModel      $emergencyModel;
    protected EmployeeChildModel         $childModel;
    protected EducationalBackgroundModel $educationModel;
    protected EmploymentHistoryModel     $historyModel;
    protected CharacterReferenceModel    $referenceModel;
    protected DepartmentModel            $departmentModel;
    protected PositionModel              $positionModel;
    protected AddressModel               $addressModel;
    protected EmployeeIdDocumentModel    $idDocumentModel;
    protected EmployeeOtherIdModel       $otherIdModel;
    protected EmployeePrcLicenseModel    $prcModel;

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
        $this->addressModel    = new AddressModel();
        $this->idDocumentModel = new EmployeeIdDocumentModel();
        $this->otherIdModel    = new EmployeeOtherIdModel();
        $this->prcModel        = new EmployeePrcLicenseModel();
    }

    /**
     * Extract province/city/barangay parts from POST for a given prefix.
     * e.g. prefix "current_address" reads current_address_province,
     * current_address_city, current_address_barangay.
     */
    private function getAddressPartsFromPost(string $prefix): array
    {
        return [
            'province' => $this->request->getPost($prefix . '_province'),
            'city'     => $this->request->getPost($prefix . '_city'),
            'barangay' => $this->request->getPost($prefix . '_barangay'),
        ];
    }

    /**
     * Handle upload of front/back photos for all 4 government ID types.
     * Saves each to public/assets/images/uploads/ids and upserts the
     * corresponding employee_id_documents row.
     */
    private function handleIdDocumentUploads(int $employeeId): void
    {
        foreach (EmployeeIdDocumentModel::ID_TYPES as $type) {
            $slug = strtolower(str_replace(['-', ' '], '_', $type)); // e.g. "pag_ibig"

            $frontFile = $this->request->getFile($slug . '_photo_front');
            $backFile  = $this->request->getFile($slug . '_photo_back');

            $photos = ['photo_front' => null, 'photo_back' => null];

            if ($frontFile && $frontFile->isValid() && ! $frontFile->hasMoved()) {
                $newName = $frontFile->getRandomName();
                $frontFile->move(FCPATH . 'assets/images/uploads/ids', $newName);
                $photos['photo_front'] = $newName;
            }

            if ($backFile && $backFile->isValid() && ! $backFile->hasMoved()) {
                $newName = $backFile->getRandomName();
                $backFile->move(FCPATH . 'assets/images/uploads/ids', $newName);
                $photos['photo_back'] = $newName;
            }

            $this->idDocumentModel->upsertPhoto($employeeId, $type, $photos);
        }
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
        $rules['employee_no'] = 'required|max_length[20]|is_unique[employees.employee_no]';

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();
        $data = array_intersect_key($post, array_flip($this->employeeModel->allowedFields));
        $data = $this->uppercaseFields($data, [
            'email_address',
            'gender',
            'civil_status',
            'employment_status',
            'rate_type',
        ]);

        $data['is_active'] = 1;

        $currentParts    = $this->uppercaseFields($this->getAddressPartsFromPost('current_address'));
        $provincialParts = $this->uppercaseFields($this->getAddressPartsFromPost('provincial_address'));
        $spouseParts     = $this->uppercaseFields($this->getAddressPartsFromPost('spouse_address'));
        $parentsParts    = $this->uppercaseFields($this->getAddressPartsFromPost('parents_address'));


        $data['current_address_id']    = $this->addressModel->findOrCreate($currentParts);
        $data['provincial_address_id'] = $this->addressModel->findOrCreate($provincialParts);
        $data['current_address_street']    = mb_strtoupper(trim((string) $this->request->getPost('current_address_street')));
        $data['provincial_address_street'] = mb_strtoupper(trim((string) $this->request->getPost('provincial_address_street')));

        // Only resolve spouse address if a spouse name was provided
        $data['spouse_address_id']    = null;
        $data['spouse_address_street'] = null;
        if (!empty($data['spouse_name'])) {
            $data['spouse_address_id']     = $this->addressModel->findOrCreate($spouseParts);
            $data['spouse_address_street'] = mb_strtoupper(trim((string) $this->request->getPost('spouse_address_street')));
        }

        // Only resolve parents address if at least one parent name was provided
        $data['parents_address_id']    = null;
        $data['parents_address_street'] = null;
        if (!empty($data['father_name']) || !empty($data['mother_name'])) {
            $data['parents_address_id']     = $this->addressModel->findOrCreate($parentsParts);
            $data['parents_address_street'] = mb_strtoupper(trim((string) $this->request->getPost('parents_address_street')));
        }

        // Set null values for nullable fields
        $data['position_id']     = ($data['position_id']     ?? '') ?: null;
        $data['department_id']   = ($data['department_id']   ?? '') ?: null;
        $data['contract_expiry'] = ($data['contract_expiry'] ?? '') ?: null;
        $data['date_resigned']   = ($data['date_resigned']   ?? '') ?: null;

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(FCPATH . 'assets/images/uploads/employees', $newName);
            $data['photo'] = $newName;
        }

        $id = $this->employeeModel->insert($data);

        if ($id) {
            $this->handleIdDocumentUploads($id);
        }

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
     *                 @var array $employee   Includes joined address parts
     *                 @var int   $age
     *                 @var array $emergency  Each row includes joined address parts
     *                 @var array $children
     *                 @var array $education
     *                 @var array $history
     *                 @var array $references Each row includes joined address parts
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
            'idDocuments' => $this->idDocumentModel->getByEmployee($id),
            'otherIds'    => $this->otherIdModel->getByEmployee($id),
            'prcLicenses' => $this->prcModel->getByEmployee($id),
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
     *                 @var array $employee  Includes joined address parts for pre-fill
     *                 @var array $departments
     *                 @var array $positions
     */
    public function edit(int $id): string
    {
        $employee = $this->employeeModel->getWithDetails($id);

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
            'idDocuments' => $this->idDocumentModel->getByEmployee($id),
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
        $rules['employee_no'] = str_replace('{id}', (string) $id, $rules['employee_no']);

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();
        $data = array_intersect_key($post, array_flip($this->employeeModel->allowedFields));
        $data = $this->uppercaseFields($data, [
            'email_address',
            'gender',
            'civil_status',
            'employment_status',
            'rate_type',
        ]);

        $currentParts     = $this->uppercaseFields($this->getAddressPartsFromPost('current_address'));
        $provincialParts  = $this->uppercaseFields($this->getAddressPartsFromPost('provincial_address'));
        $spouseParts       = $this->uppercaseFields($this->getAddressPartsFromPost('spouse_address'));
        $parentsParts      = $this->uppercaseFields($this->getAddressPartsFromPost('parents_address'));

        $data['current_address_id']    = $this->addressModel->findOrCreate($currentParts);
        $data['provincial_address_id'] = $this->addressModel->findOrCreate($provincialParts);

        $data['current_address_street']     = mb_strtoupper(trim((string) $this->request->getPost('current_address_street')));
        $data['provincial_address_street']  = mb_strtoupper(trim((string) $this->request->getPost('provincial_address_street')));

        // Only resolve spouse address if a spouse name was provided
        $data['spouse_address_id']    = null;
        $data['spouse_address_street'] = null;
        if (!empty($data['spouse_name'])) {
            $data['spouse_address_id']     = $this->addressModel->findOrCreate($spouseParts);
            $data['spouse_address_street'] = mb_strtoupper(trim((string) $this->request->getPost('spouse_address_street')));
        }

        // Only resolve parents address if at least one parent name was provided
        $data['parents_address_id']    = null;
        $data['parents_address_street'] = null;
        if (!empty($data['father_name']) || !empty($data['mother_name'])) {
            $data['parents_address_id']     = $this->addressModel->findOrCreate($parentsParts);
            $data['parents_address_street'] = mb_strtoupper(trim((string) $this->request->getPost('parents_address_street')));
        }

        // Set null values for nullable fields
        $data['position_id']     = ($data['position_id']     ?? '') ?: null;
        $data['department_id']   = ($data['department_id']   ?? '') ?: null;
        $data['contract_expiry'] = ($data['contract_expiry'] ?? '') ?: null;
        $data['date_resigned']   = ($data['date_resigned']   ?? '') ?: null;

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
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

        if ($result) {
            $this->handleIdDocumentUploads($id);
        }

        if (! $result) {
            log_message('error', 'Employee update failed for ID ' . $id . ': ' . json_encode($this->employeeModel->errors()));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update employee.');
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
        $rules = [
            'last_name'  => 'required|max_length[80]',
            'first_name' => 'required|max_length[80]',
        ];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                    'csrf'    => csrf_hash(),
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Please fill in required fields.');
        }

        $addressId = $this->addressModel->findOrCreate(
            $this->uppercaseFields($this->getAddressPartsFromPost('ec_address'))
        );

        $data = [
            'employee_id'    => $employeeId,
            'last_name'      => $this->request->getPost('last_name'),
            'first_name'     => $this->request->getPost('first_name'),
            'middle_name'    => $this->request->getPost('middle_name'),
            'relationship'   => $this->request->getPost('relationship'),
            'address_id'     => $addressId,
            'address_street' => $this->request->getPost('ec_address_street'),
            'contact_number' => $this->request->getPost('contact_number'),
            'sort_order'     => $this->request->getPost('sort_order') ?? 1,
        ];
        $data = $this->uppercaseFields($data);

        $newId = $this->emergencyModel->insert($data);

        if ($this->request->isAJAX()) {
            $row = $this->emergencyModel->getByEmployee($employeeId);
            $row = array_values(array_filter($row, fn($r) => $r['id'] == $newId))[0] ?? null;
            return $this->response->setJSON([
                'success' => true,
                'row'     => $row,
                'csrf'    => csrf_hash(),
            ]);
        }

        return redirect()->to("/employees/view/{$employeeId}#emergency")
            ->with('success', 'Emergency contact added.');
    }

    public function updateEmergency(int $employeeId, int $contactId)
    {
        $existing  = $this->emergencyModel->find($contactId);
        $addressId = $this->addressModel->findOrCreate(
            $this->uppercaseFields($this->getAddressPartsFromPost('ec_address'))
        );

        $data = [
            'last_name'      => $this->request->getPost('last_name'),
            'first_name'     => $this->request->getPost('first_name'),
            'middle_name'    => $this->request->getPost('middle_name'),
            'relationship'   => $this->request->getPost('relationship'),
            'address_id'     => $addressId,
            'address_street' => $this->request->getPost('ec_address_street'),
            'contact_number' => $this->request->getPost('contact_number'),
            'sort_order'     => $this->request->getPost('sort_order') ?? 1,
        ];
        $data = $this->uppercaseFields($data);

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
        $rules = ['name' => 'required|max_length[150]'];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                    'csrf'    => csrf_hash(),
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Please fill in required fields.');
        }

        $data = [
            'employee_id' => $employeeId,
            'name'        => $this->request->getPost('name'),
            'birthday'    => $this->request->getPost('birthday') ?: null,
        ];
        $data = $this->uppercaseFields($data);

        $newId = $this->childModel->insert($data);
        $row   = $this->childModel->find($newId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'row'     => $row,
                'csrf'    => csrf_hash(),
            ]);
        }

        return redirect()->to("/employees/view/{$employeeId}#children")
            ->with('success', 'Child record added.');
    }

    public function updateChild(int $employeeId, int $childId)
    {
        $data = [
            'name'     => $this->request->getPost('name'),
            'birthday' => $this->request->getPost('birthday') ?: null,
        ];
        $data = $this->uppercaseFields($data);

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
        $rules = ['level' => 'required'];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                    'csrf'    => csrf_hash(),
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Please fill in required fields.');
        }

        $data = [
            'employee_id'    => $employeeId,
            'level'          => $this->request->getPost('level'),
            'school_name'    => $this->request->getPost('school_name'),
            'year_graduated' => $this->request->getPost('year_graduated') ?: null,
        ];
        $data = $this->uppercaseFields($data, ['level']);

        $newId = $this->educationModel->insert($data);
        $row   = $this->educationModel->find($newId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'row'     => $row,
                'csrf'    => csrf_hash(),
            ]);
        }

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
        $data = $this->uppercaseFields($data, ['email_address', 'level']);

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
        $rules = ['company_name' => 'required|max_length[150]'];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                    'csrf'    => csrf_hash(),
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Please fill in required fields.');
        }

        $data = [
            'employee_id'  => $employeeId,
            'company_name' => $this->request->getPost('company_name'),
            'position'     => $this->request->getPost('position'),
            'date_from'    => $this->request->getPost('date_from') ?: null,
            'date_to'      => $this->request->getPost('date_to') ?: null,
        ];
        $data = $this->uppercaseFields($data);

        $newId = $this->historyModel->insert($data);
        $row   = $this->historyModel->find($newId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'row'     => $row,
                'csrf'    => csrf_hash(),
            ]);
        }

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
        $data = $this->uppercaseFields($data);

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
        $rules = ['name' => 'required|max_length[150]'];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                    'csrf'    => csrf_hash(),
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Please fill in required fields.');
        }

        $addressId = $this->addressModel->findOrCreate(
            $this->uppercaseFields($this->getAddressPartsFromPost('ref_address'))
        );

        $data = [
            'employee_id'    => $employeeId,
            'name'           => $this->request->getPost('name'),
            'occupation'     => $this->request->getPost('occupation'),
            'address_id'     => $addressId,
            'address_street' => $this->request->getPost('ref_address_street'),
            'telephone'      => $this->request->getPost('telephone'),
        ];
        $data = $this->uppercaseFields($data);

        $newId = $this->referenceModel->insert($data);
        $row   = $this->referenceModel->find($newId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'row'     => $row,
                'csrf'    => csrf_hash(),
            ]);
        }

        return redirect()->to("/employees/view/{$employeeId}#references")
            ->with('success', 'Character reference added.');
    }

    public function updateReference(int $employeeId, int $referenceId)
    {
        $existing  = $this->referenceModel->find($referenceId);
        $addressId = $this->addressModel->findOrCreate(
            $this->uppercaseFields($this->getAddressPartsFromPost('ref_address'))
        );

        $data = [
            'name'           => $this->request->getPost('name'),
            'occupation'     => $this->request->getPost('occupation'),
            'address_id'     => $addressId,
            'address_street' => $this->request->getPost('ref_address_street'),
            'telephone'      => $this->request->getPost('telephone'),
        ];
        $data = $this->uppercaseFields($data);

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

    // ============================================================
    // OTHER GOVERNMENT IDs
    // ============================================================
    public function storeOtherId(int $employeeId)
    {
        $rules = [
            'id_type' => 'required',
        ];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                    'csrf'    => csrf_hash(),
                ]);
            }
            return redirect()->back()->with('error', 'Please fill in required fields.');
        }

        $data = [
            'employee_id' => $employeeId,
            'id_type'     => $this->request->getPost('id_type'),
            'id_number'   => $this->request->getPost('id_number'),
            'expiration'  => $this->request->getPost('expiration') ?: null,
            'remarks'     => $this->request->getPost('remarks'),
        ];
        $data = $this->uppercaseFields($data, ['id_type']);

        $newId = $this->otherIdModel->insert($data);
        $row   = $this->otherIdModel->find($newId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'row'     => $row,
                'csrf'    => csrf_hash(),
            ]);
        }

        return redirect()->to("/employees/view/{$employeeId}#other-ids")
            ->with('success', 'ID record added.');
    }

    public function updateOtherId(int $employeeId, int $otherIdId)
    {
        $data = [
            'id_type'    => $this->request->getPost('id_type'),
            'id_number'  => $this->request->getPost('id_number'),
            'expiration' => $this->request->getPost('expiration') ?: null,
            'remarks'    => $this->request->getPost('remarks'),
        ];
        $data = $this->uppercaseFields($data, ['id_type']);

        $this->otherIdModel->update($otherIdId, $data);

        return redirect()->to("/employees/view/{$employeeId}#other-ids")
            ->with('success', 'ID record updated.');
    }

    public function deleteOtherId(int $employeeId, int $otherIdId)
    {
        $this->otherIdModel->delete($otherIdId);

        return redirect()->to("/employees/view/{$employeeId}#other-ids")
            ->with('success', 'ID record removed.');
    }

    // ============================================================
    // PRC LICENSES
    // ============================================================
    public function storePrc(int $employeeId)
    {
        $rules = ['profession' => 'required|max_length[150]'];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                    'csrf'    => csrf_hash(),
                ]);
            }
            return redirect()->back()->with('error', 'Please fill in required fields.');
        }

        $data = [
            'employee_id'    => $employeeId,
            'profession'     => $this->request->getPost('profession'),
            'license_number' => $this->request->getPost('license_number'),
            'expiration'     => $this->request->getPost('expiration') ?: null,
        ];
        $data = $this->uppercaseFields($data);

        $newId = $this->prcModel->insert($data);
        $row   = $this->prcModel->find($newId);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'row'     => $row,
                'csrf'    => csrf_hash(),
            ]);
        }

        return redirect()->to("/employees/view/{$employeeId}#prc")
            ->with('success', 'PRC license added.');
    }

    public function updatePrc(int $employeeId, int $prcId)
    {
        $data = [
            'profession'     => $this->request->getPost('profession'),
            'license_number' => $this->request->getPost('license_number'),
            'expiration'     => $this->request->getPost('expiration') ?: null,
        ];
        $data = $this->uppercaseFields($data);

        $this->prcModel->update($prcId, $data);

        return redirect()->to("/employees/view/{$employeeId}#prc")
            ->with('success', 'PRC license updated.');
    }

    public function deletePrc(int $employeeId, int $prcId)
    {
        $this->prcModel->delete($prcId);

        return redirect()->to("/employees/view/{$employeeId}#prc")
            ->with('success', 'PRC license removed.');
    }

    /**
     * Serve an ID document photo through the server rather than exposing
     * the raw filename/path directly in HTML. Streams the image file
     * with the correct content type instead of redirecting to a static URL.
     *
     * @param int    $documentId The employee_id_documents row ID
     * @param string $side       "front" or "back"
     */
    public function idPhoto(int $documentId, string $side)
    {
        if (! in_array($side, ['front', 'back'], true)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid photo side.');
        }

        $doc = $this->idDocumentModel->find($documentId);

        if (! $doc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found.');
        }

        $filename = $side === 'front' ? $doc['photo_front'] : $doc['photo_back'];

        if (! $filename) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Photo not found.');
        }

        $path = FCPATH . 'assets/images/uploads/ids/' . $filename;

        if (! file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Photo file missing.');
        }

        return $this->response
            ->setContentType(mime_content_type($path))
            ->setBody(file_get_contents($path));
    }
}
