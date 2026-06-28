<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var array    $project        Project with site engineer fields
 * @var array    $assignments    All active assigned employees
 * @var int      $workerCount    Count of non-engineer workers
 * @var int|null $daysRemaining  Days until end date (negative = overdue)
 */
?>

<?= $this->section('content') ?>

<?php
$overdue = $daysRemaining !== null && $daysRemaining < 0;
$statusColor = [
    'Active'    => 'statusgreen',
    'Completed' => 'blue',
    'On Hold'   => 'amber',
    'Cancelled' => 'red',
];
?>

<!-- Page Header -->
<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('projects', '', '13') ?>
            <a href="<?= base_url('/projects') ?>">Projects</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="ts-page-title mb-0"><?= esc($project['project_name']) ?></h1>
        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
            <span class="text-muted-sm tabular-nums"><?= esc($project['project_code']) ?></span>
            <span class="ts-badge <?= $statusColor[$project['status']] ?? 'gray' ?>">
                <?= esc($project['status']) ?>
            </span>
            <?php if ($overdue): ?>
                <span class="ts-badge red">
                    <?= abs($daysRemaining) ?> days overdue
                </span>
            <?php elseif ($daysRemaining !== null): ?>
                <span class="ts-badge blue">
                    <?= $daysRemaining ?> days remaining
                </span>
            <?php endif; ?>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('/projects/edit/' . $project['id']) ?>"
            class="btn btn-ts-primary btn-sm">
            <?= svg_icon('edit', 'me-1', '14') ?> Edit
        </a>
        <a href="<?= base_url('/projects') ?>"
            class="btn btn-outline-secondary btn-sm">
            <?= svg_icon('back', 'me-1', '14') ?> Back
        </a>
    </div>
</div>

<div class="row g-3">

    <!-- Left: Project Details -->
    <div class="col-12 col-lg-5">

        <!-- Details Card -->
        <div class="ts-card mb-3">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('projects', 'text-primary-ts', '15') ?>
                    Project Information
                </h6>
            </div>
            <dl class="row g-0 mb-0" style="font-size:13px;">
                <?php
                $details = [
                    'Project Code'    => $project['project_code'],
                    'Client'          => $project['client_name'] ?: '—',
                    'Location'        => $project['location'] ?: '—',
                    'Status'          => $project['status'],
                    'Start Date'      => $project['start_date']
                        ? date('F d, Y', strtotime($project['start_date'])) : '—',
                    'End Date'        => $project['end_date']
                        ? date('F d, Y', strtotime($project['end_date'])) : '—',
                    'Contract Amount' => $project['contract_amount']
                        ? '₱ ' . number_format($project['contract_amount'], 2) : '—',
                    'Total Workers'   => $workerCount,
                ];
                foreach ($details as $label => $value): ?>
                    <dt class="col-5 text-muted-sm py-1 border-bottom border-light">
                        <?= $label ?>
                    </dt>
                    <dd class="col-7 py-1 mb-0 border-bottom border-light fw-600">
                        <?= esc($value) ?>
                    </dd>
                <?php endforeach; ?>
            </dl>
            <?php if ($project['description']): ?>
                <div class="mt-3" style="font-size:13px;">
                    <div class="text-muted-sm fw-600 mb-1">DESCRIPTION</div>
                    <p class="mb-0"><?= nl2br(esc($project['description'])) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Site Engineer Card -->
        <?php if ($project['site_engineer_first']): ?>
            <div class="ts-card mb-3">
                <div class="ts-card-header">
                    <h6 class="ts-card-title">
                        <?= svg_icon('id-card', 'text-primary-ts', '15') ?>
                        Site Engineer
                    </h6>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <?php if ($project['site_engineer_photo']): ?>
                        <img src="<?= base_url('assets/images/uploads/employees/' . $project['site_engineer_photo']) ?>"
                            class="rounded-circle"
                            style="width:56px;height:56px;object-fit:cover;
                                border:2px solid var(--ts-primary-light);">
                    <?php else: ?>
                        <div class="ts-avatar lg">
                            <?= strtoupper(
                                substr($project['site_engineer_first'], 0, 1)
                                    . substr($project['site_engineer_last'], 0, 1)
                            ) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <div class="fw-700" style="font-size:14px;">
                            <?= esc($project['site_engineer_last'] . ', ' . $project['site_engineer_first']) ?>
                        </div>
                        <?php if ($project['site_engineer_position']): ?>
                            <div class="text-muted-sm"><?= esc($project['site_engineer_position']) ?></div>
                        <?php endif; ?>
                        <?php if ($project['site_engineer_contact']): ?>
                            <div class="text-muted-sm mt-1">
                                <?= svg_icon('bell', 'me-1', '12') ?>
                                <?= esc($project['site_engineer_contact']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="<?= base_url('/employees/view/' . $project['site_engineer_id']) ?>"
                    class="btn btn-outline-secondary btn-sm w-100 mt-3" style="font-size:12px;">
                    <?= svg_icon('eye', 'me-1', '13') ?> View Employee Profile
                </a>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="row g-2">
            <div class="col-6">
                <div class="ts-stat-card">
                    <div class="ts-stat-icon primary">
                        <?= svg_icon('employees', '', '22') ?>
                    </div>
                    <div>
                        <div class="ts-stat-value"><?= (int) $workerCount ?></div>
                        <div class="ts-stat-label">Workers</div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="ts-stat-card">
                    <div class="ts-stat-icon <?= $overdue ? 'red' : 'teal' ?>">
                        <?= svg_icon('clock', '', '22') ?>
                    </div>
                    <div>
                        <div class="ts-stat-value" style="font-size:20px;">
                            <?= $daysRemaining !== null ? abs($daysRemaining) : '—' ?>
                        </div>
                        <div class="ts-stat-label">
                            <?= $overdue ? 'Days Overdue' : 'Days Left' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Map + Assigned Workers -->
    <div class="col-12 col-lg-7">

        <!-- Map Card -->
        <div class="ts-card mb-3">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('location', 'text-primary-ts', '15') ?>
                    Project Location
                </h6>
            </div>
            <?php if ($project['latitude'] && $project['longitude']): ?>
                <div id="projectMap" style="height:320px;border-radius:6px;
                     border:1px solid var(--ts-border);"></div>
            <?php else: ?>
                <div class="ts-empty" style="padding:40px 20px;">
                    <?= svg_icon('location', '', '36') ?>
                    <p>No map coordinates saved for this project.</p>
                    <p class="text-muted-sm" style="font-size:12px;">
                        Edit the project and use the "Locate on Map" button to set coordinates.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Workers Table -->
        <div class="ts-card mb-0">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('employees', 'text-primary-ts', '15') ?>
                    Assigned Employees
                    <span class="ts-badge gray ms-1"><?= (int) $workerCount ?></span>
                </h6>
            </div>
            <?php if ($workerCount === 0): ?>
                <div class="ts-empty">
                    <?= svg_icon('employees', '', '36') ?>
                    <p>No workers assigned to this project.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th class="d-none d-md-table-cell">Position</th>
                                <th>Role</th>
                                <th class="d-none d-md-table-cell">Since</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $a): ?>
                                <?php if ($a['is_site_engineer']) continue; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($a['photo']): ?>
                                                <img src="<?= base_url('assets/images/uploads/employees/' . $a['photo']) ?>"
                                                    class="ts-avatar sm" style="object-fit:cover;">
                                            <?php else: ?>
                                                <div class="ts-avatar sm">
                                                    <?= strtoupper(substr($a['first_name'], 0, 1)
                                                        . substr($a['last_name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <a href="<?= base_url('/employees/view/' . $a['employee_id']) ?>"
                                                    class="fw-600" style="font-size:13px;color:var(--ts-text);">
                                                    <?= esc($a['last_name'] . ', ' . $a['first_name']) ?>
                                                </a>
                                                <div class="text-muted-sm"><?= esc($a['employee_no']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell text-muted-sm">
                                        <?= esc($a['position_title'] ?? '—') ?>
                                    </td>
                                    <td>
                                        <span class="text-muted-sm">
                                            <?= esc($a['role'] ?: 'Worker') ?>
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell text-muted-sm tabular-nums">
                                        <?= $a['date_assigned']
                                            ? date('M d, Y', strtotime($a['date_assigned']))
                                            : '—' ?>
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

<?= $this->section('scripts') ?>
<?php if ($project['latitude'] && $project['longitude']): ?>
    <!-- Leaflet CSS + JS — free, no API key required -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = <?= (float) $project['latitude'] ?>;
            const lng = <?= (float) $project['longitude'] ?>;
            const map = L.map('projectMap').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19
            }).addTo(map);

            const marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(
                '<strong><?= esc(addslashes($project['project_name'])) ?></strong><br>' +
                '<?= esc(addslashes($project['location'] ?? '')) ?>'
            ).openPopup();
        });
    </script>
<?php endif; ?>
<?= $this->endSection() ?>
