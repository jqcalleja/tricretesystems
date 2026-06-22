<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var array  $departments  All departments for the dropdown
 * @var array  $positions    All positions for the dropdown
 * @var string $employee_no  Auto-generated next employee ID number
 */
?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('employees', '', '13') ?>
            <a href="<?= base_url('/employees') ?>">Employees</a>
            <span>/</span>
            <span>Add Employee</span>
        </div>
        <h1 class="ts-page-title">Add New Employee</h1>
        <p class="ts-page-subtitle">Fill in the employee information below.</p>
    </div>
    <a href="<?= base_url('/employees') ?>"
        class="btn btn-outline-secondary btn-sm">
        <?= svg_icon('back', 'me-1', '14') ?> Back
    </a>
</div>

<!-- Validation Errors -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3" style="font-size:13px;">
        <?= svg_icon('alert', 'me-2', '15') ?>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= base_url('/employees/store') ?>"
    method="post" enctype="multipart/form-data" id="employeeForm">
    <?= csrf_field() ?>

    <!-- ── 1. Employment Information ── -->
    <div class="ts-card">
        <p class="ts-section-title">Employment Information</p>
        <div class="row g-3">
            <div class="col-12 col-md-4 col-lg-3">
                <label class="ts-form-label">Employee ID No. <span class="text-danger">*</span></label>
                <input type="text" name="employee_no" class="form-control form-control-sm"
                    value="<?= esc(old('employee_no', $employee_no)) ?>" required>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <label class="ts-form-label">Date Hired <span class="text-danger">*</span></label>
                <input type="text" name="date_hired" class="form-control form-control-sm flatpickr"
                    value="<?= esc(old('date_hired')) ?>" placeholder="YYYY-MM-DD" required>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <label class="ts-form-label">Position</label>
                <select name="position_id" class="form-select form-select-sm">
                    <option value="">— Select Position —</option>
                    <?php foreach ($positions as $pos): ?>
                        <option value="<?= $pos['id'] ?>"
                            <?= old('position_id') == $pos['id'] ? 'selected' : '' ?>>
                            <?= esc($pos['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <label class="ts-form-label">Department</label>
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">— Select Department —</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>"
                            <?= old('department_id') == $dept['id'] ? 'selected' : '' ?>>
                            <?= esc($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <label class="ts-form-label">Employment Status <span class="text-danger">*</span></label>
                <select name="employment_status" class="form-select form-select-sm" required
                    id="empStatus">
                    <?php foreach (['Regular', 'Probationary', 'Project-Based', 'Casual'] as $s): ?>
                        <option value="<?= $s ?>"
                            <?= old('employment_status', 'Probationary') === $s ? 'selected' : '' ?>>
                            <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <label class="ts-form-label">Rate Type <span class="text-danger">*</span></label>
                <select name="rate_type" class="form-select form-select-sm" required>
                    <option value="Daily" <?= old('rate_type', 'Daily')   === 'Daily'   ? 'selected' : '' ?>>Daily</option>
                    <option value="Monthly" <?= old('rate_type', 'Daily')   === 'Monthly' ? 'selected' : '' ?>>Monthly</option>
                </select>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <label class="ts-form-label">Rate (₱) <span class="text-danger">*</span></label>
                <input type="number" name="rate" class="form-control form-control-sm"
                    step="0.01" min="0"
                    value="<?= esc(old('rate', '0.00')) ?>" required>
            </div>
            <div class="col-12 col-md-4 col-lg-3" id="contractExpiryField" style="display:none;">
                <label class="ts-form-label">Contract Expiry</label>
                <input type="text" name="contract_expiry"
                    class="form-control form-control-sm flatpickr"
                    value="<?= esc(old('contract_expiry')) ?>"
                    placeholder="YYYY-MM-DD">
            </div>
        </div>
    </div>

    <!-- ── 2. Personal Information ── -->
    <div class="ts-card">
        <p class="ts-section-title">Personal Information</p>
        <div class="row g-3 align-items-start">

            <!-- Photo -->
            <div class="col-12 col-md-3 col-lg-2 text-center">
                <label class="ts-form-label d-block">Photo (1x1)</label>
                <div class="mb-2">
                    <img id="photoPreview"
                        src="<?= base_url('assets/images/default-avatar.jpg') ?>"
                        class="rounded" style="width:100px;height:100px;object-fit:cover;
                                border:2px solid var(--ts-border);">
                </div>
                <input type="file" name="photo" id="photoInput"
                    class="form-control form-control-sm"
                    accept="image/*">
            </div>

            <div class="col-12 col-md-9 col-lg-10">
                <div class="row g-3">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="ts-form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control form-control-sm"
                            value="<?= esc(old('last_name')) ?>" required>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="ts-form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control form-control-sm"
                            value="<?= esc(old('first_name')) ?>" required>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="ts-form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control form-control-sm"
                            value="<?= esc(old('middle_name')) ?>">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ts-form-label">Nickname</label>
                        <input type="text" name="nickname" class="form-control form-control-sm"
                            value="<?= esc(old('nickname')) ?>">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ts-form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select form-select-sm" required>
                            <option value="">— Select —</option>
                            <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                                <option value="<?= $g ?>"
                                    <?= old('gender') === $g ? 'selected' : '' ?>>
                                    <?= $g ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ts-form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="text" name="date_of_birth"
                            class="form-control form-control-sm flatpickr"
                            value="<?= esc(old('date_of_birth')) ?>"
                            placeholder="YYYY-MM-DD" required id="dob">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ts-form-label">Place of Birth</label>
                        <input type="text" name="place_of_birth" class="form-control form-control-sm"
                            value="<?= esc(old('place_of_birth')) ?>">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ts-form-label">Civil Status <span class="text-danger">*</span></label>
                        <select name="civil_status" class="form-select form-select-sm" required>
                            <option value="">— Select —</option>
                            <?php foreach (['Single', 'Married', 'Widowed', 'Separated', 'Divorced'] as $cs): ?>
                                <option value="<?= $cs ?>"
                                    <?= old('civil_status') === $cs ? 'selected' : '' ?>>
                                    <?= $cs ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ts-form-label">Citizenship</label>
                        <input type="text" name="citizenship" class="form-control form-control-sm"
                            value="<?= esc(old('citizenship', 'Filipino')) ?>">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="ts-form-label">Religion</label>
                        <input type="text" name="religion" class="form-control form-control-sm"
                            value="<?= esc(old('religion')) ?>">
                    </div>
                    <div class="col-6 col-sm-3 col-md-2">
                        <label class="ts-form-label">Height (cm)</label>
                        <input type="number" name="height_cm" class="form-control form-control-sm"
                            step="0.01" min="0"
                            value="<?= esc(old('height_cm')) ?>">
                    </div>
                    <div class="col-6 col-sm-3 col-md-2">
                        <label class="ts-form-label">Weight (kg)</label>
                        <input type="number" name="weight_kg" class="form-control form-control-sm"
                            step="0.01" min="0"
                            value="<?= esc(old('weight_kg')) ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── 3. Address & Contact ── -->
    <div class="ts-card">
        <p class="ts-section-title">Address &amp; Contact</p>
        <div class="row g-3">
            <div class="col-12">
                <?= view('partials/address_fields', [
                    'prefix' => 'current_address',
                    'label'  => 'Current Address',
                ]) ?>
            </div>

            <div class="col-12">
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input"
                        id="same_as_current_provincial">
                    <label class="form-check-label" for="same_as_current_provincial" style="font-size:13px;">
                        Same as Current Address
                    </label>
                </div>
                <?= view('partials/address_fields', [
                    'prefix' => 'provincial_address',
                    'label'  => 'Provincial Address',
                ]) ?>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <label class="ts-form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control form-control-sm"
                    value="<?= esc(old('contact_number')) ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="ts-form-label">Email Address</label>
                <input type="email" name="email_address" class="form-control form-control-sm ts-no-uppercase"
                    value="<?= esc(old('email_address')) ?>">
            </div>
        </div>
    </div>

    <!-- ── 4. Spouse Information ── -->
    <div class="ts-card">
        <p class="ts-section-title">Spouse Information</p>
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4">
                <label class="ts-form-label">Name of Spouse</label>
                <input type="text" name="spouse_name" class="form-control form-control-sm"
                    value="<?= esc(old('spouse_name')) ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="ts-form-label">Occupation</label>
                <input type="text" name="spouse_occupation" class="form-control form-control-sm"
                    value="<?= esc(old('spouse_occupation')) ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="ts-form-label">Contact Number</label>
                <input type="text" name="spouse_contact_number"
                    class="form-control form-control-sm"
                    value="<?= esc(old('spouse_contact_number')) ?>">
            </div>

            <div class="col-12">
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input"
                        id="same_as_current_spouse">
                    <label class="form-check-label" for="same_as_current_spouse" style="font-size:13px;">
                        Same as Current Address
                    </label>
                </div>
                <?= view('partials/address_fields', [
                    'prefix' => 'spouse_address',
                    'label'  => 'Address'
                ]) ?>
            </div>
        </div>
    </div>

    <!-- ── 5. Parents Information ── -->
    <div class="ts-card">
        <p class="ts-section-title">Parents Information</p>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="ts-form-label">Father's Name</label>
                <input type="text" name="father_name" class="form-control form-control-sm"
                    value="<?= esc(old('father_name')) ?>">
            </div>
            <div class="col-12 col-md-6">
                <label class="ts-form-label">Father's Occupation</label>
                <input type="text" name="father_occupation" class="form-control form-control-sm"
                    value="<?= esc(old('father_occupation')) ?>">
            </div>

            <div class="col-12 col-md-6">
                <label class="ts-form-label">Mother's Name</label>
                <input type="text" name="mother_name" class="form-control form-control-sm"
                    value="<?= esc(old('mother_name')) ?>">
            </div>
            <div class="col-12 col-md-6">
                <label class="ts-form-label">Mother's Occupation</label>
                <input type="text" name="mother_occupation" class="form-control form-control-sm"
                    value="<?= esc(old('mother_occupation')) ?>">
            </div>

            <div class="col-12">
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input"
                        id="same_as_current_parents">
                    <label class="form-check-label" for="same_as_current_parents" style="font-size:13px;">
                        Same as Current Address
                    </label>
                </div>
                <?= view('partials/address_fields', [
                    'prefix' => 'parents_address',
                    'label'  => "Parents' Address"
                ]) ?>
            </div>
        </div>
    </div>

    <!-- ── 6. Government Numbers ── -->
    <div class="ts-card">
        <p class="ts-section-title">Government Information</p>
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-3">
                <label class="ts-form-label">SSS Number</label>
                <input type="text" name="sss_number" class="form-control form-control-sm"
                    value="<?= esc(old('sss_number')) ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="ts-form-label">PhilHealth Number</label>
                <input type="text" name="philhealth_number" class="form-control form-control-sm"
                    value="<?= esc(old('philhealth_number')) ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="ts-form-label">Pag-IBIG Number</label>
                <input type="text" name="pagibig_number" class="form-control form-control-sm"
                    value="<?= esc(old('pagibig_number')) ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="ts-form-label">TIN Number</label>
                <input type="text" name="tin_number" class="form-control form-control-sm"
                    value="<?= esc(old('tin_number')) ?>">
            </div>
        </div>

        <hr class="my-3">

        <p class="ts-section-title">ID Photos</p>
        <div class="row g-3">
            <?php
            $idLabels = [
                'sss'        => 'SSS ID',
                'philhealth' => 'PhilHealth ID',
                'pag_ibig'   => 'Pag-IBIG ID',
                'tin'        => 'TIN ID',
            ];
            foreach ($idLabels as $slug => $label): ?>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3" style="background:#FAFAFA;">
                        <p class="fw-600 mb-2" style="font-size:13px;"><?= $label ?></p>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="ts-form-label">Front</label>
                                <input type="file" name="<?= $slug ?>_photo_front"
                                    class="form-control form-control-sm" accept="image/*">
                            </div>
                            <div class="col-6">
                                <label class="ts-form-label">Back</label>
                                <input type="file" name="<?= $slug ?>_photo_back"
                                    class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ── 7. Special Skills ── -->
    <div class="ts-card">
        <p class="ts-section-title">Special Skills</p>
        <div class="row g-3">
            <div class="col-12">
                <textarea name="special_skills" class="form-control form-control-sm"
                    rows="3"
                    placeholder="List any special skills or certifications..."><?= esc(old('special_skills')) ?></textarea>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="d-flex gap-2 justify-content-end mb-4 flex-wrap">
        <a href="<?= base_url('/employees') ?>"
            class="btn btn-outline-secondary">
            <?= svg_icon('x', 'me-1', '15') ?> Cancel
        </a>
        <button type="submit" class="btn btn-ts-primary">
            <?= svg_icon('save', 'me-1', '15') ?> Save Employee
        </button>
    </div>

</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    window.TS_BASE_URL = "<?= base_url('/') ?>";
</script>
<script src="<?= base_url('assets/js/address-component.js') ?>"></script>
<script src="<?= base_url('assets/js/employees.js') ?>"></script>
<?= $this->endSection() ?>