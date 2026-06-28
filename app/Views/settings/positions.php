<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var array      $positions
 * @var array      $departments
 * @var array|null $editingPosition
 */
$isEditing = ! empty($editingPosition);
?>

<?= $this->section('content') ?>

<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('settings', '', '13') ?>
            <a href="<?= base_url('/settings') ?>">Settings</a>
            <span>/</span>
            <span>Positions</span>
        </div>
        <h1 class="ts-page-title">Position Settings</h1>
        <p class="ts-page-subtitle">Maintain job titles and their assigned departments.</p>
    </div>
</div>

<ul class="nav nav-tabs mb-3 flex-wrap">
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/settings/departments') ?>">
            <?= svg_icon('employees', 'me-1', '14') ?> Departments
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="<?= base_url('/settings/positions') ?>">
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
            <p class="ts-section-title"><?= $isEditing ? 'Edit Position' : 'Add Position' ?></p>

            <?php if (empty($departments)): ?>
                <div class="alert alert-warning mb-0" style="font-size:13px;">
                    Add at least one department before creating positions.
                </div>
            <?php else: ?>
                <form method="post"
                    action="<?= $isEditing
                        ? base_url('/settings/positions/update/' . $editingPosition['id'])
                        : base_url('/settings/positions/store') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="ts-form-label">Position Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm"
                            value="<?= esc(old('title', $editingPosition['title'] ?? '')) ?>"
                            maxlength="120" required>
                    </div>

                    <div class="mb-3">
                        <label class="ts-form-label">Department <span class="text-danger">*</span></label>
                        <select name="department_id" class="form-select form-select-sm" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"
                                    <?= old('department_id', $editingPosition['department_id'] ?? '') == $dept['id']
                                        ? 'selected' : '' ?>>
                                    <?= esc($dept['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <?php if ($isEditing): ?>
                            <a href="<?= base_url('/settings/positions') ?>"
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
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('id-card', 'text-primary-ts', '16') ?>
                    Positions
                    <span class="ts-badge gray ms-1"><?= count($positions) ?></span>
                </h6>
            </div>

            <?php if (empty($positions)): ?>
                <div class="ts-empty">
                    <?= svg_icon('id-card', '', '36') ?>
                    <p>No positions yet.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Department</th>
                                <th class="text-center">Employees</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($positions as $pos): ?>
                                <tr>
                                    <td class="fw-600"><?= esc($pos['title']) ?></td>
                                    <td class="text-muted-sm">
                                        <?= esc($pos['department_name'] ?? 'Unassigned') ?>
                                    </td>
                                    <td class="text-center tabular-nums">
                                        <?= (int) $pos['employee_count'] ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= base_url('/settings/positions/edit/' . $pos['id']) ?>"
                                                class="ts-icon-btn" title="Edit">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </a>
                                            <?php if ((int) $pos['employee_count'] === 0): ?>
                                                <a href="<?= base_url('/settings/positions/delete/' . $pos['id']) ?>"
                                                    class="ts-icon-btn text-danger" title="Delete"
                                                    data-confirm-delete="<?= base_url('/settings/positions/delete/' . $pos['id']) ?>"
                                                    data-label="<?= esc($pos['title'], 'attr') ?>">
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
