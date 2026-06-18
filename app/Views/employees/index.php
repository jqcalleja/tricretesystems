<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Page Header -->
<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('employees', '', '13') ?>
            <span>Employees</span>
        </div>
        <h1 class="ts-page-title">Employee Master File</h1>
        <p class="ts-page-subtitle">Manage all employee records.</p>
    </div>
    <div>
        <a href="<?= base_url('/employees/create') ?>"
            class="btn btn-ts-primary">
            <?= svg_icon('plus', 'me-1', '16') ?> Add Employee
        </a>
    </div>
</div>

<!-- Filters -->
<div class="ts-card mb-3">
    <form method="get" action="<?= base_url('/employees') ?>"
        class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <label class="ts-form-label">Search</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">
                    <?= svg_icon('search', '', '14') ?>
                </span>
                <input type="text" name="search"
                    class="form-control form-control-sm"
                    placeholder="Name or Employee ID"
                    value="<?= esc($filters['search'] ?? '') ?>">
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-3 col-lg-2">
            <label class="ts-form-label">Department</label>
            <select name="department_id" class="form-select form-select-sm">
                <option value="">All Departments</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>"
                        <?= ($filters['department_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>>
                        <?= esc($dept['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-sm-6 col-md-3 col-lg-2">
            <label class="ts-form-label">Status</label>
            <select name="employment_status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <?php foreach (['Regular', 'Probationary', 'Project-Based', 'Casual'] as $s): ?>
                    <option value="<?= $s ?>"
                        <?= ($filters['employment_status'] ?? '') === $s ? 'selected' : '' ?>>
                        <?= $s ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-sm-6 col-md-3 col-lg-2">
            <label class="ts-form-label">Active</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="1" <?= ($filters['is_active'] ?? '') === 1 ? 'selected' : '' ?>>
                    Active
                </option>
                <option value="0" <?= ($filters['is_active'] ?? '') === 0 ? 'selected' : '' ?>>
                    Inactive
                </option>
            </select>
        </div>
        <div class="col-6 col-sm-6 col-md-auto">
            <button type="submit" class="btn btn-ts-primary btn-sm w-100">
                <?= svg_icon('filter', 'me-1', '14') ?> Filter
            </button>
        </div>
        <div class="col-6 col-sm-6 col-md-auto">
            <a href="<?= base_url('/employees') ?>"
                class="btn btn-outline-secondary btn-sm w-100">
                <?= svg_icon('refresh', 'me-1', '14') ?> Reset
            </a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="ts-card">
    <div class="ts-card-header">
        <h6 class="ts-card-title">
            <?= svg_icon('employees', 'text-primary-ts', '16') ?>
            Employees
            <span class="ts-badge gray ms-1"><?= count($employees) ?></span>
        </h6>
        <button class="btn btn-sm btn-outline-secondary no-print"
            onclick="window.print()">
            <?= svg_icon('print', 'me-1', '14') ?> Print
        </button>
    </div>

    <?php if (empty($employees)): ?>
        <div class="ts-empty">
            <?= svg_icon('employees', '', '40') ?>
            <p>No employees found.</p>
        </div>
    <?php else: ?>
        <div class="ts-table-wrap px-3 py-2">
            <table class="ts-table" id="employeeTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>ID No.</th>
                        <th class="d-none d-md-table-cell">Position</th>
                        <th class="d-none d-lg-table-cell">Department</th>
                        <th class="d-none d-md-table-cell">Date Hired</th>
                        <th>Emp. Status</th>
                        <th class="text-center">Active</th>
                        <th class="text-center no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $emp): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($emp['photo']): ?>
                                        <img src="<?= base_url('assets/images/uploads/employees/' . $emp['photo']) ?>"
                                            class="ts-avatar" style="object-fit:cover;"
                                            alt="<?= esc($emp['first_name']) ?>">
                                    <?php else: ?>
                                        <div class="ts-avatar">
                                            <?= strtoupper(substr($emp['first_name'], 0, 1)) . strtoupper(substr($emp['last_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-600" style="font-size:13px;">
                                            <?= esc($emp['last_name'] . ', ' . $emp['first_name']) ?>
                                            <?= $emp['middle_name'] ? esc(' ' . substr($emp['middle_name'], 0, 1) . '.') : '' ?>
                                        </div>
                                        <?php if ($emp['nickname']): ?>
                                            <div class="text-muted-sm">"<?= esc($emp['nickname']) ?>"</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="tabular-nums" style="font-size:12px;">
                                <?= esc($emp['employee_no']) ?>
                            </td>
                            <td class="d-none d-md-table-cell text-muted-sm">
                                <?= esc($emp['position_title'] ?? '—') ?>
                            </td>
                            <td class="d-none d-lg-table-cell text-muted-sm">
                                <?= esc($emp['department_name'] ?? '—') ?>
                            </td>
                            <td class="d-none d-md-table-cell text-muted-sm tabular-nums">
                                <?= $emp['date_hired'] ? date('M d, Y', strtotime($emp['date_hired'])) : '—' ?>
                            </td>
                            <td>
                                <?php
                                $statusColor = [
                                    'Regular'       => 'primary',
                                    'Probationary'  => 'amber',
                                    'Project-Based' => 'blue',
                                    'Casual'        => 'gray',
                                ];
                                ?>
                                <span class="ts-badge <?= $statusColor[$emp['employment_status']] ?? 'gray' ?>">
                                    <?= esc($emp['employment_status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($emp['is_active']): ?>
                                    <span class="ts-badge primary">Active</span>
                                <?php else: ?>
                                    <span class="ts-badge red">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center no-print">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="<?= base_url('/employees/view/' . $emp['id']) ?>"
                                        class="ts-icon-btn" title="View Profile">
                                        <?= svg_icon('eye', '', '15') ?>
                                    </a>
                                    <a href="<?= base_url('/employees/edit/' . $emp['id']) ?>"
                                        class="ts-icon-btn" title="Edit">
                                        <?= svg_icon('edit', '', '15') ?>
                                    </a>
                                    <a href="<?= base_url('/employees/toggle-status/' . $emp['id']) ?>"
                                        class="ts-icon-btn"
                                        title="<?= $emp['is_active'] ? 'Deactivate' : 'Activate' ?>"
                                        data-confirm-delete="<?= base_url('/employees/toggle-status/' . $emp['id']) ?>"
                                        data-confirm-type="<?= $emp['is_active'] ? 'deactivate' : 'activate' ?>"
                                        data-label="<?= esc($emp['first_name']) ?>">
                                        <?= svg_icon($emp['is_active'] ? 'x' : 'check', '', '15') ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('employeeTable')) {
            $('#employeeTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [7]
                }],
                language: {
                    search: '',
                    searchPlaceholder: 'Search table...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ employees',
                    infoEmpty: 'No employees found',
                    paginate: {
                        previous: '&lsaquo;',
                        next: '&rsaquo;',
                    }
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>