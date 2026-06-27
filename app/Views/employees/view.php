<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var array    $employee     Employee record with joined position/department names
 * @var int      $age          Calculated age from date_of_birth
 * @var array    $emergency    Emergency contacts for this employee
 * @var array    $children     Children records for this employee
 * @var array    $education    Educational background records
 * @var array    $history      Employment history records
 * @var array    $references   Character reference records
 * @var array    $idDocuments  Uploaded images for Government IDs
 * @var array    $otherIds     Other government ID records
 * @var array    $prcLicenses  PRC license records
 */
?>

<?= $this->section('content') ?>

<?php
$statusColor = [
    'Regular'       => 'statusgreen',
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
                    <span class="ts-badge teal">Active</span>
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
<ul class="nav nav-tabs mb-3 flex-wrap overflow-none" id="profileTabs" role="tablist"
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
    <li class="nav-item" id="other-ids">
        <button class="nav-link" data-bs-toggle="tab"
            data-bs-target="#tabOtherIds" type="button">
            <?= svg_icon('id-card', 'me-1', '14') ?> Other IDs
            <span class="ts-badge gray ms-1"><?= count($otherIds) ?></span>
        </button>
    </li>
    <li class="nav-item" id="prc">
        <button class="nav-link" data-bs-toggle="tab"
            data-bs-target="#tabPrc" type="button">
            <?= svg_icon('reports', 'me-1', '14') ?> PRC Licenses
            <span class="ts-badge gray ms-1"><?= count($prcLicenses) ?></span>
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
                    <?php
                    $currentAddressDisplay = trim(implode(', ', array_filter([
                        $employee['current_address_street']   ?? '',
                        $employee['current_address_barangay'] ?? '',
                        $employee['current_address_city']     ?? '',
                        $employee['current_address_province'] ?? '',
                    ])));

                    $provincialAddressDisplay = trim(implode(', ', array_filter([
                        $employee['provincial_address_street']   ?? '',
                        $employee['provincial_address_barangay'] ?? '',
                        $employee['provincial_address_city']     ?? '',
                        $employee['provincial_address_province'] ?? '',
                    ])));
                    ?>
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
                            'Current Address' => $currentAddressDisplay,
                            'Prov. Address'    => $provincialAddressDisplay,
                            'Contact No.'     => $employee['contact_number'],
                            'Email'           => $employee['email_address'],
                        ];
                        foreach ($personalFields as $label => $value): ?>
                            <dt class="col-5 text-muted-sm py-1 border-bottom border-light"><?= $label ?></dt>
                            <dd class="col-7 py-1 mb-0 border-bottom border-light fw-500">
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
                            <?= svg_icon('id-card', 'text-primary-ts', '15') ?>
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

                    <?php
                    $idTypeLabels = ['SSS' => 'SSS', 'PhilHealth' => 'PhilHealth', 'Pag-IBIG' => 'Pag-IBIG', 'TIN' => 'TIN'];
                    $hasAnyIdPhoto = false;
                    foreach ($idDocuments as $doc) {
                        if ($doc['photo_front'] || $doc['photo_back']) {
                            $hasAnyIdPhoto = true;
                            break;
                        }
                    }
                    ?>
                    <?php if ($hasAnyIdPhoto): ?>
                        <p class="text-muted-sm fw-600 mt-3 mb-2">ID PHOTOS</p>
                        <div class="row g-2">
                            <?php foreach ($idDocuments as $type => $doc): ?>
                                <?php if (! $doc['photo_front'] && ! $doc['photo_back']) continue; ?>
                                <div class="col-6">
                                    <div class="text-muted-sm mb-1"><?= esc($idTypeLabels[$type] ?? $type) ?></div>
                                    <div class="d-flex gap-1">
                                        <?php if ($doc['photo_front']): ?>
                                            <img src="<?= base_url('employees/id-photo/' . $doc['id'] . '/front') ?>"
                                                class="ts-id-thumb"
                                                style="width:60px;height:40px;object-fit:cover;border-radius:4px;
                                    border:1px solid var(--ts-border);cursor:pointer;"
                                                onclick="showIdPhotoModal('<?= base_url('employees/id-photo/' . $doc['id'] . '/front') ?>', '<?= esc($idTypeLabels[$type] ?? $type) ?> — Front')">
                                        <?php endif; ?>
                                        <?php if ($doc['photo_back']): ?>
                                            <img src="<?= base_url('employees/id-photo/' . $doc['id'] . '/back') ?>"
                                                class="ts-id-thumb"
                                                style="width:60px;height:40px;object-fit:cover;border-radius:4px;
                                    border:1px solid var(--ts-border);cursor:pointer;"
                                                onclick="showIdPhotoModal('<?= base_url('employees/id-photo/' . $doc['id'] . '/back') ?>', '<?= esc($idTypeLabels[$type] ?? $type) ?> — Back')">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Spouse -->
            <?php if ($employee['spouse_name']): ?>
                <?php
                $spouseAddressDisplay = trim(implode(', ', array_filter([
                    $employee['spouse_address_street']   ?? '',
                    $employee['spouse_address_barangay'] ?? '',
                    $employee['spouse_address_city']     ?? '',
                    $employee['spouse_address_province'] ?? '',
                ])));
                ?>
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
                                'Address'    => $spouseAddressDisplay,
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
                <?php
                $parentsAddressDisplay = trim(implode(', ', array_filter([
                    $employee['parents_address_street']   ?? '',
                    $employee['parents_address_barangay'] ?? '',
                    $employee['parents_address_city']     ?? '',
                    $employee['parents_address_province'] ?? '',
                ])));
                ?>
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
                                'Address'        => $parentsAddressDisplay,
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
                <div class="ts-empty" data-empty-for="emergencyBody">
                    <?= svg_icon('bell', '', '36') ?>
                    <p>No emergency contacts on record.</p>
                </div>
                <div class="ts-table-wrap d-none" data-table-for="emergencyBody" style="border:none;">
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
                        <tbody id="emergencyBody"></tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" data-table-for="emergencyBody" style="border:none;">
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
                        <tbody id="emergencyBody">
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
                                        <?php
                                        $ecAddress = trim(implode(', ', array_filter([
                                            $ec['address_street'] ?? '',
                                            $ec['barangay']       ?? '',
                                            $ec['city']           ?? '',
                                            $ec['province']       ?? '',
                                        ])));
                                        echo esc($ecAddress ?: '—');
                                        ?>
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
                <div class="ts-empty" data-empty-for="childBody">
                    <?= svg_icon('employees', '', '36') ?>
                    <p>No children on record.</p>
                </div>
                <div class="ts-table-wrap d-none" data-table-for="childBody" style="border:none;">
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
                        <tbody id="childBody"></tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" data-table-for="childBody" style="border:none;">
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
                        <tbody id="childBody">
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
                <div class="ts-empty" data-empty-for="educationBody">
                    <?= svg_icon('reports', '', '36') ?>
                    <p>No educational background on record.</p>
                </div>
                <div class="ts-table-wrap d-none" data-table-for="educationBody" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>School / Institution</th>
                                <th>Year Graduated</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="educationBody"></tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" data-table-for="educationBody" style="border:none;">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>School / Institution</th>
                                <th>Year Graduated</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="educationBody">
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
                <div class="ts-empty" data-empty-for="historyBody">
                    <?= svg_icon('clock', '', '36') ?>
                    <p>No employment history on record.</p>
                </div>
                <div class="ts-table-wrap d-none" data-table-for="historyBody" style="border:none;">
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
                        <tbody id="historyBody"></tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" data-table-for="historyBody" style="border:none;">
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
                        <tbody id="historyBody">
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
                <div class="ts-empty" data-empty-for="referenceBody">
                    <?= svg_icon('users', '', '36') ?>
                    <p>No character references on record.</p>
                </div>
                <div class="ts-table-wrap d-none" data-table-for="referenceBody" style="border:none;">
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
                        <tbody id="referenceBody"></tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" data-table-for="referenceBody" style="border:none;">
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
                        <tbody id="referenceBody">
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
                                        <?php
                                        $refAddress = trim(implode(', ', array_filter([
                                            $ref['address_street'] ?? '',
                                            $ref['barangay']       ?? '',
                                            $ref['city']           ?? '',
                                            $ref['province']       ?? '',
                                        ])));
                                        echo esc($refAddress ?: '—');
                                        ?>
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

    <!-- ══ TAB 7: OTHER GOVERNMENT IDs ══ -->
    <div class="tab-pane fade" id="tabOtherIds">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('id-card', 'text-green', '15') ?>
                    Other Government IDs
                </h6>
                <button class="btn btn-sm btn-ts-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddOtherId">
                    <?= svg_icon('plus', 'me-1', '14') ?> Add ID
                </button>
            </div>

            <?php if (empty($otherIds)): ?>
                <div class="ts-empty" data-empty-for="otherIdsBody">
                    <?= svg_icon('id-card', '', '36') ?>
                    <p>No other ID records on file.</p>
                </div>
                <div class="ts-table-wrap d-none" data-table-for="otherIdsBody" style="border:none;">
                    <table class="ts-table" id="otherIdsTable">
                        <thead>
                            <tr>
                                <th>ID Type</th>
                                <th>ID Number</th>
                                <th>Expiration</th>
                                <th class="d-none d-md-table-cell">Remarks</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="otherIdsBody"></tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" data-table-for="otherIdsBody" style="border:none;">
                    <table class="ts-table" id="otherIdsTable">
                        <thead>
                            <tr>
                                <th>ID Type</th>
                                <th>ID Number</th>
                                <th>Expiration</th>
                                <th class="d-none d-md-table-cell">Remarks</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="otherIdsBody">
                            <?php foreach ($otherIds as $oid): ?>
                                <tr id="other-id-row-<?= $oid['id'] ?>">
                                    <td class="fw-600"><?= esc($oid['id_type']) ?></td>
                                    <td class="tabular-nums"><?= esc($oid['id_number'] ?: '—') ?></td>
                                    <td class="tabular-nums">
                                        <?= $oid['expiration']
                                            ? date('M d, Y', strtotime($oid['expiration']))
                                            : '—' ?>
                                    </td>
                                    <td class="d-none d-md-table-cell text-muted-sm">
                                        <?= esc($oid['remarks'] ?: '—') ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="ts-icon-btn" title="Edit"
                                                onclick="editOtherId(<?= htmlspecialchars(json_encode($oid)) ?>)">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </button>
                                            <a href="<?= base_url("/employees/{$employee['id']}/other-id/delete/{$oid['id']}") ?>"
                                                class="ts-icon-btn text-danger" title="Delete"
                                                data-confirm-delete="<?= base_url("/employees/{$employee['id']}/other-id/delete/{$oid['id']}") ?>"
                                                data-label="this ID record">
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

    <!-- ══ TAB 8: PRC LICENSES ══ -->
    <div class="tab-pane fade" id="tabPrc">
        <div class="ts-card">
            <div class="ts-card-header">
                <h6 class="ts-card-title">
                    <?= svg_icon('reports', 'text-green', '15') ?>
                    PRC Licenses
                </h6>
                <button class="btn btn-sm btn-ts-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddPrc">
                    <?= svg_icon('plus', 'me-1', '14') ?> Add License
                </button>
            </div>

            <?php if (empty($prcLicenses)): ?>
                <div class="ts-empty" data-empty-for="prcBody">
                    <?= svg_icon('reports', '', '36') ?>
                    <p>No PRC license records on file.</p>
                </div>
                <div class="ts-table-wrap d-none" data-table-for="prcBody" style="border:none;">
                    <table class="ts-table" id="prcTable">
                        <thead>
                            <tr>
                                <th>Profession / Board Exam</th>
                                <th>License No.</th>
                                <th>Expiration</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="prcBody"></tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="ts-table-wrap" data-table-for="prcBody" style="border:none;">
                    <table class="ts-table" id="prcTable">
                        <thead>
                            <tr>
                                <th>Profession / Board Exam</th>
                                <th>License No.</th>
                                <th>Expiration</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="prcBody">
                            <?php foreach ($prcLicenses as $prc): ?>
                                <tr id="prc-row-<?= $prc['id'] ?>">
                                    <td class="fw-600"><?= esc($prc['profession']) ?></td>
                                    <td class="tabular-nums"><?= esc($prc['license_number'] ?: '—') ?></td>
                                    <td class="tabular-nums">
                                        <?= $prc['expiration']
                                            ? date('M d, Y', strtotime($prc['expiration']))
                                            : '—' ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="ts-icon-btn" title="Edit"
                                                onclick="editPrc(<?= htmlspecialchars(json_encode($prc)) ?>)">
                                                <?= svg_icon('edit', '', '15') ?>
                                            </button>
                                            <a href="<?= base_url("/employees/{$employee['id']}/prc/delete/{$prc['id']}") ?>"
                                                class="ts-icon-btn text-danger" title="Delete"
                                                data-confirm-delete="<?= base_url("/employees/{$employee['id']}/prc/delete/{$prc['id']}") ?>"
                                                data-label="this PRC license">
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
                method="post" data-employee-id="<?= $employee['id'] ?>">
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
                            <?= view('partials/address_fields', [
                                'prefix' => 'ec_address',
                                'label'  => 'Address',
                            ]) ?>
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
                    <div id="emergencyErrors" class="alert alert-danger mt-2 d-none" style="font-size:12.5px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        id="btnEmergencyAddAnother">
                        <?= svg_icon('plus', 'me-1', '13') ?> Add Another
                    </button>
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
                method="post" data-employee-id="<?= $employee['id'] ?>">
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
                    <div id="childErrors" class="alert alert-danger mt-2 d-none" style="font-size:12.5px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        id="btnChildAddAnother">
                        <?= svg_icon('plus', 'me-1', '13') ?> Add Another
                    </button>
                    <button type="submit" class="btn btn-ts-primary btn-sm">
                        <?= svg_icon('save', 'me-1', '14') ?> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ID Photo Preview Modal -->
<div class="modal fade" id="modalIdPhoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600" id="idPhotoModalTitle">ID Photo</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="idPhotoModalImage" src="" alt="ID Photo"
                    style="max-width:100%;max-height:70vh;border-radius:6px;">
            </div>
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
                method="post" data-employee-id="<?= $employee['id'] ?>">
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
                    <div id="educationErrors" class="alert alert-danger mt-2 d-none" style="font-size:12.5px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        id="btnEducationAddAnother">
                        <?= svg_icon('plus', 'me-1', '13') ?> Add Another
                    </button>
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
                method="post" data-employee-id="<?= $employee['id'] ?>">
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
                    <div id="historyErrors" class="alert alert-danger mt-2 d-none" style="font-size:12.5px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        id="btnHistoryAddAnother">
                        <?= svg_icon('plus', 'me-1', '13') ?> Add Another
                    </button>
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
                method="post" data-employee-id="<?= $employee['id'] ?>">
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
                            <?= view('partials/address_fields', [
                                'prefix' => 'ref_address',
                                'label'  => 'Address',
                            ]) ?>
                        </div>
                    </div>
                    <div id="referenceErrors" class="alert alert-danger mt-2 d-none" style="font-size:12.5px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        id="btnReferenceAddAnother">
                        <?= svg_icon('plus', 'me-1', '13') ?> Add Another
                    </button>
                    <button type="submit" class="btn btn-ts-primary btn-sm">
                        <?= svg_icon('save', 'me-1', '14') ?> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Other ID -->
<div class="modal fade" id="modalAddOtherId" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">
                    <?= svg_icon('id-card', 'me-2', '16') ?>
                    <span id="otherIdModalTitle">Add Government ID</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="otherIdForm"
                action="<?= base_url("/employees/{$employee['id']}/other-id/store") ?>"
                method="post"
                data-employee-id="<?= $employee['id'] ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="ts-form-label">ID Type <span class="text-danger">*</span></label>
                            <select name="id_type" id="otherId_type"
                                class="form-select form-select-sm" required>
                                <option value="">— Select ID Type —</option>
                                <?php foreach (\App\Models\EmployeeOtherIdModel::ID_TYPES as $idType): ?>
                                    <option value="<?= esc($idType) ?>"><?= esc($idType) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">ID / Document Number</label>
                            <input type="text" name="id_number" id="otherId_number"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Expiration Date</label>
                            <input type="text" name="expiration" id="otherId_expiration"
                                class="form-control form-control-sm flatpickr"
                                placeholder="YYYY-MM-DD">
                        </div>
                        <div class="col-12">
                            <label class="ts-form-label">Remarks</label>
                            <input type="text" name="remarks" id="otherId_remarks"
                                class="form-control form-control-sm"
                                placeholder="e.g. Valid, Expired, Lost">
                        </div>
                    </div>
                    <div id="otherIdErrors" class="alert alert-danger mt-2 d-none"
                        style="font-size:12.5px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        id="btnOtherIdAddAnother">
                        <?= svg_icon('plus', 'me-1', '13') ?> Add Another
                    </button>
                    <button type="submit" class="btn btn-ts-primary btn-sm"
                        id="btnOtherIdSave">
                        <?= svg_icon('save', 'me-1', '14') ?> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add PRC License -->
<div class="modal fade" id="modalAddPrc" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">
                    <?= svg_icon('reports', 'me-2', '16') ?>
                    <span id="prcModalTitle">Add PRC License</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="prcForm"
                action="<?= base_url("/employees/{$employee['id']}/prc/store") ?>"
                method="post"
                data-employee-id="<?= $employee['id'] ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="ts-form-label">Profession / Board Exam Title <span class="text-danger">*</span></label>
                            <input type="text" name="profession" id="prc_profession"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">PRC License Number</label>
                            <input type="text" name="license_number" id="prc_license_number"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="ts-form-label">Expiration Date</label>
                            <input type="text" name="expiration" id="prc_expiration"
                                class="form-control form-control-sm flatpickr"
                                placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                    <div id="prcErrors" class="alert alert-danger mt-2 d-none"
                        style="font-size:12.5px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        id="btnPrcAddAnother">
                        <?= svg_icon('plus', 'me-1', '13') ?> Add Another
                    </button>
                    <button type="submit" class="btn btn-ts-primary btn-sm"
                        id="btnPrcSave">
                        <?= svg_icon('save', 'me-1', '14') ?> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    window.TS_BASE_URL = "<?= base_url('/') ?>";

    // Restore active tab from hash on page load
    // Handles redirects from store/update/delete that append #tabName to the URL
    (function() {
        const hash = window.location.hash;
        if (!hash) return;

        // Map URL hash fragments to Bootstrap tab targets
        const hashMap = {
            '#emergency': '#tabEmergency',
            '#children': '#tabChildren',
            '#education': '#tabEducation',
            '#history': '#tabHistory',
            '#references': '#tabReferences',
            '#other-ids': '#tabOtherIds',
            '#prc': '#tabPrc',
        };

        const target = hashMap[hash];
        if (!target) return;

        const tabEl = document.querySelector('[data-bs-target="' + target + '"]');
        if (tabEl) {
            bootstrap.Tab.getOrCreateInstance(tabEl).show();
        }
    })();
</script>
<script src="<?= base_url('assets/js/address-component.js') ?>"></script>
<script src="<?= base_url('assets/js/employees.js') ?>"></script>
<?= $this->endSection() ?>
