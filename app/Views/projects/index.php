<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var array $projects  Active projects with worker_count, site engineer fields
 * @var array $filters   Current filter values
 */
?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('projects', '', '13') ?>
            <span>Projects</span>
        </div>
        <h1 class="ts-page-title">Active Projects</h1>
        <p class="ts-page-subtitle">Currently ongoing construction projects.</p>
    </div>
    <a href="<?= base_url('/projects/create') ?>" class="btn btn-ts-primary">
        <?= svg_icon('plus', 'me-1', '16') ?> Add Project
    </a>
</div>

<!-- Search -->
<div class="ts-card mb-3">
    <form method="get" action="<?= base_url('/projects') ?>"
        class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <label class="ts-form-label">Search</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">
                    <?= svg_icon('search', '', '14') ?>
                </span>
                <input type="text" name="search"
                    class="form-control form-control-sm"
                    placeholder="Project name, code, or client"
                    value="<?= esc($filters['search'] ?? '') ?>">
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-ts-primary btn-sm">
                <?= svg_icon('filter', 'me-1', '14') ?> Filter
            </button>
        </div>
        <div class="col-auto">
            <a href="<?= base_url('/projects') ?>"
                class="btn btn-outline-secondary btn-sm">
                <?= svg_icon('refresh', 'me-1', '14') ?> Reset
            </a>
        </div>
    </form>
</div>

<!-- Project Cards -->
<?php if (empty($projects)): ?>
    <div class="ts-card">
        <div class="ts-empty">
            <?= svg_icon('projects', '', '40') ?>
            <p>No active projects found.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($projects as $proj):
            $daysLeft = $proj['end_date']
                ? (int) date_diff(date_create('today'), date_create($proj['end_date']))->days
                * (date_create($proj['end_date']) >= date_create('today') ? 1 : -1)
                : null;
            $overdue = $daysLeft !== null && $daysLeft < 0;
        ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="ts-card mb-0 h-100 d-flex flex-column"
                    style="border-left:4px solid var(--ts-primary);">

                    <!-- Card Header -->
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <div class="text-muted-sm tabular-nums"
                                style="font-size:11px;letter-spacing:0.5px;">
                                <?= esc($proj['project_code']) ?>
                            </div>
                            <h6 class="fw-700 mb-0" style="font-size:14.5px;line-height:1.3;">
                                <?= esc($proj['project_name']) ?>
                            </h6>
                            <?php if ($proj['client_name']): ?>
                                <div class="text-muted-sm mt-1">
                                    <?= svg_icon('users', 'me-1', '12') ?>
                                    <?= esc($proj['client_name']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="ts-badge statusgreen ms-2 flex-shrink-0">Active</span>
                    </div>

                    <!-- Location -->
                    <?php if ($proj['location']): ?>
                        <div class="d-flex align-items-center gap-1 mb-2 text-muted-sm">
                            <?= svg_icon('location', '', '13') ?>
                            <span style="font-size:12px;"><?= esc($proj['location']) ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Dates -->
                    <div class="d-flex gap-3 mb-3" style="font-size:12px;">
                        <div>
                            <div class="text-muted-sm">Start</div>
                            <div class="fw-600">
                                <?= $proj['start_date']
                                    ? date('M d, Y', strtotime($proj['start_date']))
                                    : '—' ?>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted-sm">End</div>
                            <div class="fw-600 <?= $overdue ? 'text-danger' : '' ?>">
                                <?= $proj['end_date']
                                    ? date('M d, Y', strtotime($proj['end_date']))
                                    : '—' ?>
                            </div>
                        </div>
                        <?php if ($daysLeft !== null): ?>
                            <div>
                                <div class="text-muted-sm"><?= $overdue ? 'Overdue' : 'Remaining' ?></div>
                                <div class="fw-600 <?= $overdue ? 'text-danger' : 'text-primary-ts' ?>">
                                    <?= abs($daysLeft) ?> days
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Site Engineer + Worker Count -->
                    <div class="d-flex align-items-center justify-content-between mt-auto pt-2"
                        style="border-top:1px solid var(--ts-border);">
                        <div class="d-flex align-items-center gap-2">
                            <?php if ($proj['site_engineer_photo']): ?>
                                <img src="<?= base_url('assets/images/uploads/employees/' . $proj['site_engineer_photo']) ?>"
                                    class="ts-avatar sm" style="object-fit:cover;">
                            <?php elseif ($proj['site_engineer_first']): ?>
                                <div class="ts-avatar sm">
                                    <?= strtoupper(substr($proj['site_engineer_first'], 0, 1)
                                        . substr($proj['site_engineer_last'], 0, 1)) ?>
                                </div>
                            <?php else: ?>
                                <div class="ts-avatar sm" style="background:#E5E7EB;color:#9CA3AF;">
                                    <?= svg_icon('employees', '', '12') ?>
                                </div>
                            <?php endif; ?>
                            <div style="font-size:12px;">
                                <div class="text-muted-sm">Site Engineer</div>
                                <div class="fw-600">
                                    <?= $proj['site_engineer_first']
                                        ? esc($proj['site_engineer_last'] . ', ' . $proj['site_engineer_first'])
                                        : 'Not assigned' ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" style="font-size:12px;">
                            <div class="fw-700" style="font-size:20px;color:var(--ts-primary);line-height:1;">
                                <?= (int) $proj['worker_count'] ?>
                            </div>
                            <div class="text-muted-sm">Workers</div>
                        </div>
                    </div>

                    <!-- View Button -->
                    <a href="<?= base_url('/projects/view/' . $proj['id']) ?>"
                        class="btn btn-outline-secondary btn-sm w-100 mt-3"
                        style="font-size:12px;">
                        <?= svg_icon('eye', 'me-1', '13') ?> View Details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>