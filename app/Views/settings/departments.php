<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var array      $departments
 * @var array|null $editingDepartment
 */
$isEditing = ! empty($editingDepartment);
?>

<?= $this->section('content') ?>

<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('settings', '', '13') ?>
            <a href="<?= base_url('/settings') ?>">Settings</a>
            <span>/</span>
            <span>Departments</span>
        </div>
        <h1 class="ts-page-title">Department Settings</h1>
        <p class="ts-page-subtitle">Maintain the department list used in employee profiles.</p>
    </div>
</div>

<ul class="nav nav-tabs mb-3 flex-wrap">
    <li class="nav-item">
        <a class="nav-link active" href="<?= base_url('/settings/departments') ?>">
            <?= svg_icon('employees', 'me-1', '14') ?> Departments
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/settings/positions') ?>">
            <?= svg_icon('id-card', 'me-1', '14') ?> Positions
        </a>
    </li>
</ul>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3" style="font-size:13px;">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="ts-card">
            <p class="ts-section-title"><?= $isEditing ? 'Edit Department' : 'Add Department' ?></p>
            <form method="post"
                action="<?= $isEditing
                    ? base_url('/settings/departments/update/' . $editingDepartment['id'])
                    : base_url('/settings/departments/store') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="ts-form-label">Department Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-sm"
                        value="<?= esc(old('name', $editingDepartment['name'] ?? '')) ?>"
                        maxlength="100" required>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <?php if ($isEditing): ?>
                        <a href="<?= base_url('/settings/departments') ?>"
                            class="btn btn-outline-secondary btn-sm">
                            <?= svg_icon('x', 'me-1', '13') ?> Cancel
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-ts-primary btn-sm">
                        <?= svg_icon('save', 'me-1', '13') ?>
                        <?= $isEditing ? 'Update' : 'Save' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('employees', 'text-primary-ts', '16') ?>
                    Departments
                    <span class="ts-badge gray ms-1"><?= count($departments) ?></span>
                </h6>
            </div>

            <?php if (empty($departments)): ?>
                <div class="ts-empty">
                    <?= svg_icon('employees', '', '36') ?>
                    <p>No departments yet.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th class="text-center">Positions</th>
                                <th class="text-center">Employees</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departments as $dept): ?>
                                <tr>
                                    <td class="fw-600"><?= esc($dept['name']) ?></td>
                                    <td class="text-center tabular-nums">
                                        <?= (int) $dept['position_count'] ?>
                                    </td>
                                    <td class="text-center tabular-nums">
                                        <?= (int) $dept['employee_count'] ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= base_url('/settings/departments/edit/' . $dept['id']) ?>"
                                                class="ts-icon-btn" title="Edit">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </a>
                                            <?php if ((int) $dept['position_count'] === 0
                                                && (int) $dept['employee_count'] === 0): ?>
                                                <a href="<?= base_url('/settings/departments/delete/' . $dept['id']) ?>"
                                                    class="ts-icon-btn text-danger" title="Delete"
                                                    data-confirm-delete="<?= base_url('/settings/departments/delete/' . $dept['id']) ?>"
                                                    data-label="<?= esc($dept['name'], 'attr') ?>">
                                                    <?= svg_icon('delete', '', '15') ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="ts-icon-btn text-muted"
                                                    title="Cannot delete while in use">
                                                    <?= svg_icon('delete', '', '15') ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
