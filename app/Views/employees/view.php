<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$statusColor = [
    'Regular'       => 'green',
    'Probationary'  => 'amber',
    'Project-Based' => 'blue',
    'Casual'        => 'gray',
];
?>

<!-- Page Header -->
<div class="ts-page-header">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <!-- Photo -->
        <?php if ($employee['photo']): ?>
            <img src="<?= base_url('assets/images/uploads/employees/' . $employee['photo']) ?>"
                class="rounded-circle"
                style="width:64px;height:64px;object-fit:cover;
                        border:3px solid var(--ts-green-light);">
        <?php else: ?>
            <div class="ts-avatar lg">
                <?= strtoupper(substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1)) ?>
            </div>
        <?php endif; ?>
        <div>
            <div class="ts-breadcrumb">
                <?= svg_icon('employees', '', '13') ?>
                <a href="<?= base_url('/employees') ?>">Employees</a>
                <span>/</span>
                <span>Profile</span>
            </div>
            <h1 class="ts-page-title mb-0">
                <?= esc($employee['last_name'] . ', ' . $employee['first_name']) ?>
                <?= $employee['middle_name']
                    ? esc(' ' . substr($employee['middle_name'], 0, 1) . '.')
                    : '' ?>
                <?php if ($employee['nickname']): ?>
                    <small class="text-muted-sm fw-normal">
                        "<?= esc($employee['nickname']) ?>"
                    </small>
                <?php endif; ?>
            </h1>
            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                <span class="text-muted-sm"><?= esc($employee['employee_no']) ?></span>
                <span class="ts-badge <?= $statusColor[$employee['employment_status']] ?? 'gray' ?>">
                    <?= esc($employee['employment_status']) ?>
                </span>
                <?php if ($employee['is_active']): ?>
                    <span class="ts-badge green">Active</span>
                <?php else: ?>
                    <span class="ts-badge red">Inactive</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= base_url('/employees/edit/' . $employee['id']) ?>"
            class="btn btn-ts-primary btn-sm">
            <?= svg_icon('edit', 'me-1', '14') ?> Edit
        </a>
        <a href="<?= base_url('/employees/toggle-status/' . $employee['id']) ?>"
            class="btn btn-sm <?= $employee['is_active'] ? 'btn-outline-danger' : 'btn-outline-success' ?>"
            data-confirm-delete="<?= base_url('/employees/toggle-status/' . $employee['id']) ?>"
            data-confirm-type="<?= $employee['is_active'] ? 'deactivate' : 'activate' ?>"
            data-label="<?= esc($employee['last_name'] . ', ' . $employee['first_name']) ?>">
            <?= svg_icon($employee['is_active'] ? 'x' : 'check', 'me-1', '14') ?>
            <?= $employee['is_active'] ? 'Deactivate' : 'Activate' ?>
        </a>
        <a href="<?= base_url('/employees') ?>"
            class="btn btn-outline-secondary btn-sm">
            <?= svg_icon('back', 'me-1', '14') ?> Back
        </a>
    </div>
</div>

<!-- ── Tabs ── -->
<ul class="nav nav-tabs mb-3 flex-nowrap overflow-auto" id="profileTabs" role="tablist"
    style="white-space:nowrap;">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab"
            data-bs-target="#tabOverview" type="button">
            <?= svg_icon('id-card', 'me-1', '14') ?> Overview
        </button>
    </li>
    <li class="nav-item" id="emergency">
        <button class="nav-link" data-bs-toggle="tab"
            data-bs-target="#tabEmergency" type="button">
            <?= svg_icon('bell', 'me-1', '14') ?> Emergency Contacts
            <span class="ts-badge gray ms-1"><?= count($emergency) ?></span>
        </button>
    </li>
    <li class="nav-item" id="children">
        <button class="nav-link" data-bs-toggle="tab"
            data-bs-target="#tabChildren" type="button">
            <?= svg_icon('employees', 'me-1', '14') ?> Children
            <span class="ts-badge gray ms-1"><?= count($children) ?></span>
        </button>
    </li>
    <li class="nav-item" id="education">
        <button class="nav-link" data-bs-toggle="tab"
            data-bs-target="#tabEducation" type="button">
            <?= svg_icon('reports', 'me-1', '14') ?> Education
            <span class="ts-badge gray ms-1"><?= count($education) ?></span>
        </button>
    </li>
    <li class="nav-item" id="history">
        <button class="nav-link" data-bs-toggle="tab"
            data-bs-target="#tabHistory" type="button">
            <?= svg_icon('clock', 'me-1', '14') ?> Employment History
            <span class="ts-badge gray ms-1"><?= count($history) ?></span>
        </button>
    </li>
    <li class="nav-item" id="references">
        <button class="nav-link" data-bs-toggle="tab"
            data-bs-target="#tabReferences" type="button">
            <?= svg_icon('users', 'me-1', '14') ?> References
            <span class="ts-badge gray ms-1"><?= count($references) ?></span>
        </button>
    </li>
</ul>

<div class="tab-content">

    <!-- ══ TAB 1: OVERVIEW ══ -->
    <div class="tab-pane fade show active" id="tabOverview">
        <div class="row g-3">

            <!-- Personal -->
            <div class="col-12 col-lg-6">
                <div class="ts-card h-100 mb-0">
                    <div class="ts-card-header">
                        <h6 class="ts-card-title">
                            <?= svg_icon('id-card', 'text-green', '15') ?>
                            Personal Information
                        </h6>
                    </div>
                    <dl class="row g-0 mb-0" style="font-size:13px;">
                        <?php
                        $personalFields = [
                            'Gender'        => $employee['gender'],
                            'Date of Birth' => $employee['date_of_birth']
                                ? date('F d, Y', strtotime($employee['date_of_birth']))
                                . " (Age: {$age})"
                                : '—',
                            'Place of Birth'  => $employee['place_of_birth'],
                            'Civil Status'    => $employee['civil_status'],
                            'Citizenship'     => $employee['citizenship'],
                            'Religion'        => $employee['religion'],
                            'Height'          => $employee['height_cm'] ? $employee['height_cm'] . ' cm' : '—',
                            'Weight'          => $employee['weight_kg'] ? $employee['weight_kg'] . ' kg' : '—',
                            'City Address'    => $employee['city_address'],
                            'Prov. Address'   => $employee['provincial_address'],
                            'Contact No.'     => $employee['contact_number'],
                            'Email'           => $employee['email_address'],
                        ];
                        foreach ($personalFields as $label => $value): ?>
                            <dt class="col-5 text-muted-sm py-1
                                border-bottom border-light"><?= $label ?></dt>
                            <dd class="col-7 py-1 mb-0
                                border-bottom border-light fw-500">
                                <?= esc($value ?: '—') ?>
                            </dd>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </div>

            <!-- Employment + Government -->
            <div class="col-12 col-lg-6">

                <!-- Employment -->
                <div class="ts-card mb-3">
                    <div class="ts-card-header">
                        <h6 class="ts-card-title">
                            <?= svg_icon('attendance', 'text-green', '15') ?>
                            Employment Information
                        </h6>
                    </div>
                    <dl class="row g-0 mb-0" style="font-size:13px;">
                        <?php
                        $empFields = [
                            'Position'      => $employee['position_title']  ?? '—',
                            'Department'    => $employee['department_name'] ?? '—',
                            'Emp. Status'   => $employee['employment_status'],
                            'Rate Type'     => $employee['rate_type'],
                            'Rate'          => '₱ ' . number_format($employee['rate'], 2),
                            'Date Hired'    => $employee['date_hired']
                                ? date('F d, Y', strtotime($employee['date_hired']))
                                : '—',
                            'Contract Exp.' => $employee['contract_expiry']
                                ? date('F d, Y', strtotime($employee['contract_expiry']))
                                : '—',
                            'Date Resigned' => $employee['date_resigned']
                                ? date('F d, Y', strtotime($employee['date_resigned']))
                                : '—',
                        ];
                        foreach ($empFields as $label => $value): ?>
                            <dt class="col-5 text-muted-sm py-1
                                border-bottom border-light"><?= $label ?></dt>
                            <dd class="col-7 py-1 mb-0
                                border-bottom border-light fw-600">
                                <?= esc($value ?: '—') ?>
                            </dd>
                        <?php endforeach; ?>
                    </dl>
                </div>

                <!-- Government -->
                <div class="ts-card mb-0">
                    <div class="ts-card-header">
                        <h6 class="ts-card-title">
                            <?= svg_icon('id-card', 'text-green', '15') ?>
                            Government Information
                        </h6>
                    </div>
                    <dl class="row g-0 mb-0" style="font-size:13px;">
                        <?php
                        $govFields = [
                            'SSS Number'       => $employee['sss_number'],
                            'PhilHealth No.'   => $employee['philhealth_number'],
                            'Pag-IBIG No.'     => $employee['pagibig_number'],
                            'TIN Number'       => $employee['tin_number'],
                        ];
                        foreach ($govFields as $label => $value): ?>
                            <dt class="col-5 text-muted-sm py-1
                                border-bottom border-light"><?= $label ?></dt>
                            <dd class="col-7 py-1 mb-0
                                border-bottom border-light fw-600 tabular-nums">
                                <?= esc($value ?: '—') ?>
                            </dd>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </div>

            <!-- Spouse -->
            <?php if ($employee['spouse_name']): ?>
                <div class="col-12 col-md-6">
                    <div class="ts-card mb-0">
                        <div class="ts-card-header">
                            <h6 class="ts-card-title">Spouse Information</h6>
                        </div>
                        <dl class="row g-0 mb-0" style="font-size:13px;">
                            <?php
                            $spouseFields = [
                                'Name'       => $employee['spouse_name'],
                                'Occupation' => $employee['spouse_occupation'],
                                'Contact'    => $employee['spouse_contact_number'],
                                'Address'    => $employee['spouse_address'],
                            ];
                            foreach ($spouseFields as $label => $value): ?>
                                <dt class="col-4 text-muted-sm py-1 border-bottom border-light">
                                    <?= $label ?>
                                </dt>
                                <dd class="col-8 py-1 mb-0 border-bottom border-light">
                                    <?= esc($value ?: '—') ?>
                                </dd>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Parents -->
            <?php if ($employee['father_name'] || $employee['mother_name']): ?>
                <div class="col-12 col-md-6">
                    <div class="ts-card mb-0">
                        <div class="ts-card-header">
                            <h6 class="ts-card-title">Parents Information</h6>
                        </div>
                        <dl class="row g-0 mb-0" style="font-size:13px;">
                            <?php
                            $parentFields = [
                                "Father's Name"  => $employee['father_name'],
                                "Father's Occ."  => $employee['father_occupation'],
                                "Mother's Name"  => $employee['mother_name'],
                                "Mother's Occ."  => $employee['mother_occupation'],
                                'Address'        => $employee['parents_address'],
                            ];
                            foreach ($parentFields as $label => $value): ?>
                                <dt class="col-5 text-muted-sm py-1 border-bottom border-light">
                                    <?= $label ?>
                                </dt>
                                <dd class="col-7 py-1 mb-0 border-bottom border-light">
                                    <?= esc($value ?: '—') ?>
                                </dd>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Special Skills -->
            <?php if ($employee['special_skills']): ?>
                <div class="col-12">
                    <div class="ts-card mb-0">
                        <div class="ts-card-header">
                            <h6 class="ts-card-title">Special Skills</h6>
                        </div>
                        <p class="mb-0" style="font-size:13px;">
                            <?= nl2br(esc($employee['special_skills'])) ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- ══ TAB 2: EMERGENCY CONTACTS ══ -->
    <div class="tab-pane fade" id="tabEmergency">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('bell', 'text-green', '15') ?>
                    Emergency Contacts
                </h6>
                <button class="btn btn-sm btn-ts-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddEmergency">
                    <?= svg_icon('plus', 'me-1', '14') ?> Add Contact
                </button>
            </div>

            <?php if (empty($emergency)): ?>
                <div class="ts-empty">
                    <?= svg_icon('bell', '', '36') ?>
                    <p>No emergency contacts on record.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th class="d-none d-md-table-cell">Relationship</th>
                                <th class="d-none d-md-table-cell">Contact No.</th>
                                <th class="d-none d-lg-table-cell">Address</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($emergency as $ec): ?>
                                <tr>
                                    <td><?= $ec['sort_order'] ?></td>
                                    <td class="fw-600">
                                        <?= esc($ec['last_name'] . ', ' . $ec['first_name']) ?>
                                        <?= $ec['middle_name'] ? esc(' ' . substr($ec['middle_name'], 0, 1) . '.') : '' ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?= esc($ec['relationship'] ?? '—') ?>
                                    </td>
                                    <td class="d-none d-md-table-cell tabular-nums">
                                        <?= esc($ec['contact_number'] ?? '—') ?>
                                    </td>
                                    <td class="d-none d-lg-table-cell text-muted-sm">
                                        <?= esc($ec['address'] ?? '—') ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="ts-icon-btn"
                                                title="Edit"
                                                onclick="editEmergency(<?= htmlspecialchars(json_encode($ec)) ?>)">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </button>
                                            <a href="<?= base_url("/employees/{$employee['id']}/emergency/delete/{$ec['id']}") ?>"
                                                class="ts-icon-btn text-danger"
                                                title="Delete"
                                                data-confirm-delete="<?= base_url("/employees/{$employee['id']}/emergency/delete/{$ec['id']}") ?>"
                                                data-label="this emergency contact">
                                                <?= svg_icon('delete', '', '15') ?>
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
    </div>

    <!-- ══ TAB 3: CHILDREN ══ -->
    <div class="tab-pane fade" id="tabChildren">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('employees', 'text-green', '15') ?>
                    Children
                </h6>
                <button class="btn btn-sm btn-ts-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddChild">
                    <?= svg_icon('plus', 'me-1', '14') ?> Add Child
                </button>
            </div>

            <?php if (empty($children)): ?>
                <div class="ts-empty">
                    <?= svg_icon('employees', '', '36') ?>
                    <p>No children on record.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Birthday</th>
                                <th>Age</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($children as $i => $child): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td class="fw-600"><?= esc($child['name']) ?></td>
                                    <td class="tabular-nums">
                                        <?= $child['birthday']
                                            ? date('M d, Y', strtotime($child['birthday']))
                                            : '—' ?>
                                    </td>
                                    <td>
                                        <?= $child['birthday']
                                            ? (int) date_diff(date_create($child['birthday']), date_create('today'))->y
                                            : '—' ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="ts-icon-btn" title="Edit"
                                                onclick="editChild(<?= htmlspecialchars(json_encode($child)) ?>)">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </button>
                                            <a href="<?= base_url("/employees/{$employee['id']}/child/delete/{$child['id']}") ?>"
                                                class="ts-icon-btn text-danger" title="Delete"
                                                data-confirm-delete="<?= base_url("/employees/{$employee['id']}/child/delete/{$child['id']}") ?>"
                                                data-label="this child record">
                                                <?= svg_icon('delete', '', '15') ?>
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
    </div>

    <!-- ══ TAB 4: EDUCATION ══ -->
    <div class="tab-pane fade" id="tabEducation">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('reports', 'text-green', '15') ?>
                    Educational Background
                </h6>
                <button class="btn btn-sm btn-ts-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddEducation">
                    <?= svg_icon('plus', 'me-1', '14') ?> Add Record
                </button>
            </div>

            <?php if (empty($education)): ?>
                <div class="ts-empty">
                    <?= svg_icon('reports', '', '36') ?>
                    <p>No educational background on record.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>School / Institution</th>
                                <th>Year Graduated</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($education as $edu): ?>
                                <tr>
                                    <td>
                                        <span class="ts-badge blue">
                                            <?= esc($edu['level']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-600">
                                        <?= esc($edu['school_name'] ?? '—') ?>
                                    </td>
                                    <td class="tabular-nums">
                                        <?= esc($edu['year_graduated'] ?? '—') ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="ts-icon-btn" title="Edit"
                                                onclick="editEducation(<?= htmlspecialchars(json_encode($edu)) ?>)">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </button>
                                            <a href="<?= base_url("/employees/{$employee['id']}/education/delete/{$edu['id']}") ?>"
                                                class="ts-icon-btn text-danger" title="Delete"
                                                data-confirm-delete="<?= base_url("/employees/{$employee['id']}/education/delete/{$edu['id']}") ?>"
                                                data-label="this education record">
                                                <?= svg_icon('delete', '', '15') ?>
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
    </div>

    <!-- ══ TAB 5: EMPLOYMENT HISTORY ══ -->
    <div class="tab-pane fade" id="tabHistory">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('clock', 'text-green', '15') ?>
                    Employment History
                </h6>
                <button class="btn btn-sm btn-ts-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddHistory">
                    <?= svg_icon('plus', 'me-1', '14') ?> Add Record
                </button>
            </div>

            <?php if (empty($history)): ?>
                <div class="ts-empty">
                    <?= svg_icon('clock', '', '36') ?>
                    <p>No employment history on record.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th class="d-none d-md-table-cell">Position</th>
                                <th>From</th>
                                <th>To</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $hist): ?>
                                <tr>
                                    <td class="fw-600">
                                        <?= esc($hist['company_name']) ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?= esc($hist['position'] ?? '—') ?>
                                    </td>
                                    <td class="tabular-nums">
                                        <?= $hist['date_from']
                                            ? date('M Y', strtotime($hist['date_from']))
                                            : '—' ?>
                                    </td>
                                    <td class="tabular-nums">
                                        <?= $hist['date_to']
                                            ? date('M Y', strtotime($hist['date_to']))
                                            : 'Present' ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="ts-icon-btn" title="Edit"
                                                onclick="editHistory(<?= htmlspecialchars(json_encode($hist)) ?>)">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </button>
                                            <a href="<?= base_url("/employees/{$employee['id']}/history/delete/{$hist['id']}") ?>"
                                                class="ts-icon-btn text-danger" title="Delete"
                                                data-confirm-delete="<?= base_url("/employees/{$employee['id']}/history/delete/{$hist['id']}") ?>"
                                                data-label="this employment record">
                                                <?= svg_icon('delete', '', '15') ?>
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
    </div>

    <!-- ══ TAB 6: CHARACTER REFERENCES ══ -->
    <div class="tab-pane fade" id="tabReferences">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('users', 'text-green', '15') ?>
                    Character References
                </h6>
                <button class="btn btn-sm btn-ts-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddReference">
                    <?= svg_icon('plus', 'me-1', '14') ?> Add Reference
                </button>
            </div>

            <?php if (empty($references)): ?>
                <div class="ts-empty">
                    <?= svg_icon('users', '', '36') ?>
                    <p>No character references on record.</p>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="d-none d-md-table-cell">Occupation</th>
                                <th class="d-none d-md-table-cell">Telephone</th>
                                <th class="d-none d-lg-table-cell">Address</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($references as $ref): ?>
                                <tr>
                                    <td class="fw-600"><?= esc($ref['name']) ?></td>
                                    <td class="d-none d-md-table-cell">
                                        <?= esc($ref['occupation'] ?? '—') ?>
                                    </td>
                                    <td class="d-none d-md-table-cell tabular-nums">
                                        <?= esc($ref['telephone'] ?? '—') ?>
                                    </td>
                                    <td class="d-none d-lg-table-cell text-muted-sm">
                                        <?= esc($ref['address'] ?? '—') ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="ts-icon-btn" title="Edit"
                                                onclick="editReference(<?= htmlspecialchars(json_encode($ref)) ?>)">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </button>
                                            <a href="<?= base_url("/employees/{$employee['id']}/reference/delete/{$ref['id']}") ?>"
                                                class="ts-icon-btn text-danger" title="Delete"
                                                data-confirm-delete="<?= base_url("/employees/{$employee['id']}/reference/delete/{$ref['id']}") ?>"
                                                data-label="this reference">
                                                <?= svg_icon('delete', '', '15') ?>
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
    </div>

</div><!-- /tab-content -->

<!-- ============================================================
     MODALS
     ============================================================ -->

<!-- Add Emergency Contact -->
<div class="modal fade" id="modalAddEmergency" tabindex="-1">
    <div class="modal-dialog modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">
                    <?= svg_icon('bell', 'me-2', '16') ?>
                    <span id="emergencyModalTitle">Add Emergency Contact</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="emergencyForm"
                action="<?= base_url("/employees/{$employee['id']}/emergency/store") ?>"
                method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" id="emergencyMethod" value="">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <label class="ts-form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="ec_last_name"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="ts-form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="ec_first_name"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="ts-form-label">Middle Name</label>
                            <input type="text" name="middle_name" id="ec_middle_name"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Relationship</label>
                            <input type="text" name="relationship" id="ec_relationship"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Contact Number</label>
                            <input type="text" name="contact_number" id="ec_contact_number"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="ts-form-label">Address</label>
                            <textarea name="address" id="ec_address"
                                class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="ts-form-label">Priority</label>
                            <select name="sort_order" id="ec_sort_order"
                                class="form-select form-select-sm">
                                <option value="1">1 — Primary</option>
                                <option value="2">2 — Secondary</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ts-primary btn-sm">
                        <?= svg_icon('save', 'me-1', '14') ?> Save Contact
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Child -->
<div class="modal fade" id="modalAddChild" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">
                    <?= svg_icon('employees', 'me-2', '16') ?>
                    <span id="childModalTitle">Add Child</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="childForm"
                action="<?= base_url("/employees/{$employee['id']}/child/store") ?>"
                method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="ts-form-label">Child's Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="child_name"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12">
                            <label class="ts-form-label">Birthday</label>
                            <input type="text" name="birthday" id="child_birthday"
                                class="form-control form-control-sm flatpickr"
                                placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ts-primary btn-sm">
                        <?= svg_icon('save', 'me-1', '14') ?> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Education -->
<div class="modal fade" id="modalAddEducation" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">
                    <?= svg_icon('reports', 'me-2', '16') ?>
                    <span id="educationModalTitle">Add Education Record</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="educationForm"
                action="<?= base_url("/employees/{$employee['id']}/education/store") ?>"
                method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="ts-form-label">Level <span class="text-danger">*</span></label>
                            <select name="level" id="edu_level"
                                class="form-select form-select-sm" required>
                                <?php foreach (['Elementary', 'High School', 'Vocational', 'College', 'MA/PhD', 'Others'] as $lvl): ?>
                                    <option value="<?= $lvl ?>"><?= $lvl ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="ts-form-label">School / Institution</label>
                            <input type="text" name="school_name" id="edu_school"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="ts-form-label">Year Graduated</label>
                            <input type="number" name="year_graduated" id="edu_year"
                                class="form-control form-control-sm"
                                min="1950" max="<?= date('Y') ?>"
                                placeholder="e.g. 2005">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ts-primary btn-sm">
                        <?= svg_icon('save', 'me-1', '14') ?> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Employment History -->
<div class="modal fade" id="modalAddHistory" tabindex="-1">
    <div class="modal-dialog modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">
                    <?= svg_icon('clock', 'me-2', '16') ?>
                    <span id="historyModalTitle">Add Employment History</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="historyForm"
                action="<?= base_url("/employees/{$employee['id']}/history/store") ?>"
                method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="hist_company"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Position</label>
                            <input type="text" name="position" id="hist_position"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Date From</label>
                            <input type="text" name="date_from" id="hist_date_from"
                                class="form-control form-control-sm flatpickr"
                                placeholder="YYYY-MM-DD">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Date To</label>
                            <input type="text" name="date_to" id="hist_date_to"
                                class="form-control form-control-sm flatpickr"
                                placeholder="YYYY-MM-DD (leave blank if current)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ts-primary btn-sm">
                        <?= svg_icon('save', 'me-1', '14') ?> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Character Reference -->
<div class="modal fade" id="modalAddReference" tabindex="-1">
    <div class="modal-dialog modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">
                    <?= svg_icon('users', 'me-2', '16') ?>
                    <span id="referenceModalTitle">Add Character Reference</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="referenceForm"
                action="<?= base_url("/employees/{$employee['id']}/reference/store") ?>"
                method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="ref_name"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Occupation</label>
                            <input type="text" name="occupation" id="ref_occupation"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Telephone</label>
                            <input type="text" name="telephone" id="ref_telephone"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="ts-form-label">Address</label>
                            <textarea name="address" id="ref_address"
                                class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ts-primary btn-sm">
                        <?= svg_icon('save', 'me-1', '14') ?> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/employees.js') ?>"></script>
<?= $this->endSection() ?>