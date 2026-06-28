<?php

namespace App\Controllers;

use App\Models\DepartmentModel;
use App\Models\PositionModel;

class Settings extends BaseController
{
    protected DepartmentModel $departmentModel;
    protected PositionModel $positionModel;

    public function __construct()
    {
        $this->departmentModel = new DepartmentModel();
        $this->positionModel   = new PositionModel();
    }

    public function index()
    {
        return redirect()->to('/settings/departments');
    }

    public function departments(?int $editId = null)
    {
        $editingDepartment = null;

        if ($editId) {
            $editingDepartment = $this->departmentModel->find($editId);

            if (! $editingDepartment) {
                return redirect()->to('/settings/departments')
                    ->with('errors', ['Department not found.']);
            }
        }

        return view('settings/departments', [
            'pageTitle'         => 'Department Settings',
            'departments'       => $this->getDepartmentsWithCounts(),
            'editingDepartment' => $editingDepartment,
        ]);
    }

    public function storeDepartment()
    {
        $rules = [
            'name' => 'required|max_length[100]|is_unique[departments.name]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->uppercaseFields([
            'name' => trim((string) $this->request->getPost('name')),
        ]);

        $this->departmentModel->insert($data);

        return redirect()->to('/settings/departments')
            ->with('success', 'Department added successfully.');
    }

    public function updateDepartment(int $id)
    {
        $department = $this->departmentModel->find($id);

        if (! $department) {
            return redirect()->to('/settings/departments')
                ->with('errors', ['Department not found.']);
        }

        $rules = [
            'name' => "required|max_length[100]|is_unique[departments.name,id,{$id}]",
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->uppercaseFields([
            'name' => trim((string) $this->request->getPost('name')),
        ]);

        $this->departmentModel->update($id, $data);

        return redirect()->to('/settings/departments')
            ->with('success', 'Department updated successfully.');
    }

    public function deleteDepartment(int $id)
    {
        $department = $this->departmentModel->find($id);

        if (! $department) {
            return redirect()->to('/settings/departments')
                ->with('errors', ['Department not found.']);
        }

        $db = db_connect();
        $positionCount = $db->table('positions')
            ->where('department_id', $id)
            ->countAllResults();
        $employeeCount = $db->table('employees')
            ->where('department_id', $id)
            ->countAllResults();

        if ($positionCount > 0 || $employeeCount > 0) {
            return redirect()->to('/settings/departments')
                ->with('errors', [
                    'This department is still used by positions or employees and cannot be deleted.',
                ]);
        }

        $this->departmentModel->delete($id);

        return redirect()->to('/settings/departments')
            ->with('success', 'Department deleted successfully.');
    }

    public function positions(?int $editId = null)
    {
        $editingPosition = null;

        if ($editId) {
            $editingPosition = $this->positionModel->find($editId);

            if (! $editingPosition) {
                return redirect()->to('/settings/positions')
                    ->with('errors', ['Position not found.']);
            }
        }

        return view('settings/positions', [
            'pageTitle'       => 'Position Settings',
            'positions'       => $this->getPositionsWithCounts(),
            'departments'     => $this->departmentModel->orderBy('name')->findAll(),
            'editingPosition' => $editingPosition,
        ]);
    }

    public function storePosition()
    {
        $rules = [
            'title'         => 'required|max_length[120]|is_unique[positions.title]',
            'department_id' => 'required|is_natural_no_zero|is_not_unique[departments.id]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->uppercaseFields([
            'title'         => trim((string) $this->request->getPost('title')),
            'department_id' => (int) $this->request->getPost('department_id'),
        ], ['department_id']);

        $this->positionModel->insert($data);

        return redirect()->to('/settings/positions')
            ->with('success', 'Position added successfully.');
    }

    public function updatePosition(int $id)
    {
        $position = $this->positionModel->find($id);

        if (! $position) {
            return redirect()->to('/settings/positions')
                ->with('errors', ['Position not found.']);
        }

        $rules = [
            'title'         => "required|max_length[120]|is_unique[positions.title,id,{$id}]",
            'department_id' => 'required|is_natural_no_zero|is_not_unique[departments.id]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->uppercaseFields([
            'title'         => trim((string) $this->request->getPost('title')),
            'department_id' => (int) $this->request->getPost('department_id'),
        ], ['department_id']);

        $this->positionModel->update($id, $data);

        return redirect()->to('/settings/positions')
            ->with('success', 'Position updated successfully.');
    }

    public function deletePosition(int $id)
    {
        $position = $this->positionModel->find($id);

        if (! $position) {
            return redirect()->to('/settings/positions')
                ->with('errors', ['Position not found.']);
        }

        $employeeCount = db_connect()->table('employees')
            ->where('position_id', $id)
            ->countAllResults();

        if ($employeeCount > 0) {
            return redirect()->to('/settings/positions')
                ->with('errors', [
                    'This position is still assigned to employees and cannot be deleted.',
                ]);
        }

        $this->positionModel->delete($id);

        return redirect()->to('/settings/positions')
            ->with('success', 'Position deleted successfully.');
    }

    private function getDepartmentsWithCounts(): array
    {
        return db_connect()->table('departments d')
            ->select('d.*,
                COUNT(DISTINCT p.id) AS position_count,
                COUNT(DISTINCT e.id) AS employee_count')
            ->join('positions p', 'p.department_id = d.id', 'left')
            ->join('employees e', 'e.department_id = d.id', 'left')
            ->groupBy('d.id')
            ->orderBy('d.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function getPositionsWithCounts(): array
    {
        return db_connect()->table('positions p')
            ->select('p.*,
                d.name AS department_name,
                COUNT(DISTINCT e.id) AS employee_count')
            ->join('departments d', 'd.id = p.department_id', 'left')
            ->join('employees e', 'e.position_id = p.id', 'left')
            ->groupBy('p.id')
            ->orderBy('d.name', 'ASC')
            ->orderBy('p.title', 'ASC')
            ->get()
            ->getResultArray();
    }
}
