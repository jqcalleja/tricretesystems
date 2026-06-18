<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('dashboard', '', '13') ?>
            <span>Dashboard</span>
        </div>
        <h1 class="ts-page-title">Dashboard</h1>
        <p class="ts-page-subtitle">
            <?= date('l, F d, Y') ?>
        </p>
    </div>
</div>

<!-- ── Stat Cards ── -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="ts-stat-card h-100">
            <div class="ts-stat-icon primary">
                <?= svg_icon('employees', '', '22') ?>
            </div>
            <div>
                <div class="ts-stat-value"><?= $totalActive ?></div>
                <div class="ts-stat-label">Active Employees</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="ts-stat-card h-100">
            <div class="ts-stat-icon gray" style="background:#F3F4F6;color:#6B7280;">
                <?= svg_icon('employees', '', '22') ?>
            </div>
            <div>
                <div class="ts-stat-value"><?= $totalInactive ?></div>
                <div class="ts-stat-label">Inactive Employees</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="ts-stat-card h-100">
            <div class="ts-stat-icon blue">
                <?= svg_icon('check', '', '22') ?>
            </div>
            <div>
                <div class="ts-stat-value"><?= $presentToday ?></div>
                <div class="ts-stat-label">Present Today</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="ts-stat-card h-100">
            <div class="ts-stat-icon red">
                <?= svg_icon('x', '', '22') ?>
            </div>
            <div>
                <div class="ts-stat-value"><?= $absentToday ?></div>
                <div class="ts-stat-label">Absent Today</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="ts-stat-card h-100">
            <div class="ts-stat-icon amber">
                <?= svg_icon('projects', '', '22') ?>
            </div>
            <div>
                <div class="ts-stat-value"><?= $activeProjects ?></div>
                <div class="ts-stat-label">Active Projects</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="ts-stat-card h-100">
            <div class="ts-stat-icon teal">
                <?= svg_icon('employees', '', '22') ?>
            </div>
            <div>
                <div class="ts-stat-value"><?= $totalManpower ?></div>
                <div class="ts-stat-label">Total Manpower</div>
            </div>
        </div>
    </div>
</div>

<!-- ── Row 2: Chart + Project Manpower ── -->
<div class="row g-3 mb-3">

    <div class="col-12 col-lg-7">
        <div class="ts-card h-100 mb-0 h-100">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('chart-bar', 'text-primary-ts', '16') ?>
                    Attendance Trend — Last 7 Days
                </h6>
            </div>
            <canvas id="attendanceChart" height="110"></canvas>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="ts-card h-100 mb-0 h-100">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('projects', 'text-primary-ts', '16') ?>
                    Manpower Per Project
                </h6>
                <a href="<?= base_url('/projects') ?>"
                    class="btn btn-sm btn-ts-primary">View All</a>
            </div>

            <?php if (empty($projectSummary)): ?>
                <div class="ts-empty">
                    <?= svg_icon('projects', '', '36') ?>
                    <p>No active projects yet.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Location</th>
                                <th class="text-center">Workers</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projectSummary as $p): ?>
                                <tr>
                                    <td class="fw-600"><?= esc($p['project_name']) ?></td>
                                    <td class="text-muted-sm">
                                        <?= svg_icon('location', '', '12') ?>
                                        <?= esc($p['location'] ?? '—') ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="ts-badge <?= $p['manpower_count'] > 0 ? 'primary' : 'gray' ?>">
                                            <?= $p['manpower_count'] ?>
                                        </span>
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

<!-- ── Row 3: Today's Log + Recent Employees ── -->
<div class="row g-3">

    <div class="col-12 col-lg-7">
        <div class="ts-card mb-0 h-100">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('clock', 'text-primary-ts', '16') ?>
                    Today's Attendance Log
                </h6>
                <a href="<?= base_url('/attendance') ?>"
                    class="btn btn-sm btn-ts-primary">View All</a>
            </div>

            <?php if (empty($recentAttendance)): ?>
                <div class="ts-empty">
                    <?= svg_icon('attendance', '', '36') ?>
                    <p>No attendance records logged today.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Project</th>
                                <th>AM In</th>
                                <th>AM Out</th>
                                <th>PM In</th>
                                <th>PM Out</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentAttendance as $rec): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="ts-avatar">
                                                <?= strtoupper(substr($rec['first_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-600" style="font-size:13px;">
                                                    <?= esc($rec['last_name'] . ', ' . $rec['first_name']) ?>
                                                </div>
                                                <div class="text-muted-sm">
                                                    <?= esc($rec['employee_no']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted-sm">
                                        <?= esc($rec['project_name'] ?? '—') ?>
                                    </td>
                                    <td class="tabular-nums" style="font-size:12px;">
                                        <?= $rec['am_time_in']  ? date('h:i A', strtotime($rec['am_time_in']))  : '—' ?>
                                    </td>
                                    <td class="tabular-nums" style="font-size:12px;">
                                        <?= $rec['am_time_out'] ? date('h:i A', strtotime($rec['am_time_out'])) : '—' ?>
                                    </td>
                                    <td class="tabular-nums" style="font-size:12px;">
                                        <?= $rec['pm_time_in']  ? date('h:i A', strtotime($rec['pm_time_in']))  : '—' ?>
                                    </td>
                                    <td class="tabular-nums" style="font-size:12px;">
                                        <?= $rec['pm_time_out'] ? date('h:i A', strtotime($rec['pm_time_out'])) : '—' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($rec['is_absent']): ?>
                                            <span class="ts-badge red">Absent</span>
                                        <?php else: ?>
                                            <span class="ts-badge primary">Present</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="ts-card mb-0 h-100">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('employees', 'text-primary-ts', '16') ?>
                    Recently Added Employees
                </h6>
                <a href="<?= base_url('/employees/create') ?>"
                    class="btn btn-sm btn-ts-primary">
                    <?= svg_icon('plus', 'me-1', '14') ?> Add
                </a>
            </div>

            <?php if (empty($recentEmployees)): ?>
                <div class="ts-empty">
                    <?= svg_icon('employees', '', '36') ?>
                    <p>No employees on record yet.</p>
                </div>
            <?php else: ?>
                <?php
                $statusColor = [
                    'Regular'       => 'primary',
                    'Probationary'  => 'amber',
                    'Project-Based' => 'blue',
                    'Casual'        => 'gray',
                ];
                ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($recentEmployees as $emp): ?>
                        <li class="d-flex align-items-center gap-3 py-2"
                            style="border-bottom:1px solid var(--ts-border);">
                            <div class="ts-avatar">
                                <?= strtoupper(substr($emp['first_name'], 0, 1)) ?>
                            </div>
                            <div class="flex-1" style="min-width:0;">
                                <div class="fw-600"
                                    style="font-size:13px;white-space:nowrap;
                                        overflow:hidden;text-overflow:ellipsis;">
                                    <?= esc($emp['last_name'] . ', ' . $emp['first_name']) ?>
                                </div>
                                <div class="text-muted-sm">
                                    <?= esc($emp['employee_no']) ?>
                                    &middot;
                                    Hired <?= date('M d, Y', strtotime($emp['date_hired'])) ?>
                                </div>
                            </div>
                            <span class="ts-badge <?= $statusColor[$emp['employment_status']] ?? 'gray' ?>">
                                <?= esc($emp['employment_status']) ?>
                            </span>
                            <a href="<?= base_url('/employees/view/' . $emp['id']) ?>"
                                class="ts-icon-btn" title="View Profile">
                                <?= svg_icon('eye', '', '15') ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $trendDays ?>,
                datasets: [{
                        label: 'Present',
                        data: <?= $trendPresent ?>,
                        backgroundColor: 'rgba(29,122,29,0.75)',
                        borderColor: 'rgba(29,122,29,1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Absent',
                        data: <?= $trendAbsent ?>,
                        backgroundColor: 'rgba(220,38,38,0.65)',
                        borderColor: 'rgba(220,38,38,1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            boxWidth: 12,
                            padding: 16,
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            family: 'Inter',
                            size: 12
                        },
                        titleFont: {
                            family: 'Inter',
                            size: 12
                        },
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                family: 'Inter',
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                family: 'Inter',
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>