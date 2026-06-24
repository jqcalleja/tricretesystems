<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var string $project_code  Auto-generated project code
 * @var array  $employees     All active employees for assignment
 */
?>

<?= $this->section('content') ?>

<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('projects', '', '13') ?>
            <a href="<?= base_url('/projects') ?>">Projects</a>
            <span>/</span>
            <span>Add Project</span>
        </div>
        <h1 class="ts-page-title">Add New Project</h1>
    </div>
    <a href="<?= base_url('/projects') ?>" class="btn btn-outline-secondary btn-sm">
        <?= svg_icon('back', 'me-1', '14') ?> Back
    </a>
</div>

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

<form action="<?= base_url('/projects/store') ?>"
    method="post" id="projectForm">
    <?= csrf_field() ?>

    <!-- ── 1. Project Information ── -->
    <div class="ts-card">
        <p class="ts-section-title">Project Information</p>
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label class="ts-form-label">Project Code <span class="text-danger">*</span></label>
                <input type="text" name="project_code" class="form-control form-control-sm"
                    value="<?= esc(old('project_code', $project_code)) ?>" required>
            </div>
            <div class="col-12 col-md-8">
                <label class="ts-form-label">Project Name <span class="text-danger">*</span></label>
                <input type="text" name="project_name" class="form-control form-control-sm"
                    value="<?= esc(old('project_name')) ?>" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="ts-form-label">Client Name</label>
                <input type="text" name="client_name" class="form-control form-control-sm"
                    value="<?= esc(old('client_name')) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="ts-form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select form-select-sm" required>
                    <?php foreach (['Active', 'Completed', 'On Hold', 'Cancelled'] as $s): ?>
                        <option value="<?= $s ?>"
                            <?= old('status', 'Active') === $s ? 'selected' : '' ?>>
                            <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="ts-form-label">Contract Amount (₱)</label>
                <input type="number" name="contract_amount" class="form-control form-control-sm"
                    step="0.01" min="0"
                    value="<?= esc(old('contract_amount')) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="ts-form-label">Start Date</label>
                <input type="text" name="start_date" class="form-control form-control-sm flatpickr"
                    value="<?= esc(old('start_date')) ?>" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-12 col-md-3">
                <label class="ts-form-label">End Date</label>
                <input type="text" name="end_date" class="form-control form-control-sm flatpickr"
                    value="<?= esc(old('end_date')) ?>" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-12">
                <label class="ts-form-label">Description</label>
                <textarea name="description" class="form-control form-control-sm"
                    rows="3"><?= esc(old('description')) ?></textarea>
            </div>
        </div>
    </div>

    <!-- ── 2. Location & Map ── -->
    <div class="ts-card">
        <p class="ts-section-title">Location</p>
        <div class="row g-3">
            <div class="col-12 col-md-8">
                <label class="ts-form-label">Project Location / Address</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="location" id="projectLocation"
                        class="form-control form-control-sm"
                        value="<?= esc(old('location')) ?>"
                        placeholder="e.g. Brgy. Centro, San Mateo, Rizal">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        id="btnGeocode">
                        <?= svg_icon('location', 'me-1', '14') ?> Locate on Map
                    </button>
                </div>
                <div id="geocodeStatus" class="text-muted-sm mt-1" style="font-size:12px;"></div>
            </div>
            <div class="col-6 col-md-2">
                <label class="ts-form-label">Latitude</label>
                <input type="text" name="latitude" id="projectLat"
                    class="form-control form-control-sm ts-no-uppercase"
                    value="<?= esc(old('latitude')) ?>" placeholder="auto-filled">
            </div>
            <div class="col-6 col-md-2">
                <label class="ts-form-label">Longitude</label>
                <input type="text" name="longitude" id="projectLng"
                    class="form-control form-control-sm ts-no-uppercase"
                    value="<?= esc(old('longitude')) ?>" placeholder="auto-filled">
            </div>
            <div class="col-12">
                <div id="previewMap"
                    style="height:260px;border-radius:6px;border:1px solid var(--ts-border);
                            display:none;"></div>
            </div>
        </div>
    </div>

    <!-- ── 3. Employee Assignment ── -->
    <div class="ts-card">
        <p class="ts-section-title">Assigned Employees</p>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="ts-form-label">Site Engineer</label>
                <select name="site_engineer_id" class="form-select form-select-sm">
                    <option value="">— Select Site Engineer —</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= $emp['id'] ?>"
                            <?= old('site_engineer_id') == $emp['id'] ? 'selected' : '' ?>>
                            <?= esc($emp['last_name'] . ', ' . $emp['first_name']) ?>
                            (<?= esc($emp['employee_no']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="text-muted-sm mt-1" style="font-size:11.5px;">
                    The site engineer must also be included in the employee list below.
                </div>
            </div>
            <div class="col-12">
                <label class="ts-form-label">Assigned Employees</label>
                <div class="border rounded p-2" style="max-height:280px;overflow-y:auto;background:#FAFAFA;">
                    <div class="row g-1">
                        <?php foreach ($employees as $emp): ?>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-check">
                                    <input type="checkbox"
                                        class="form-check-input"
                                        name="assigned_employees[]"
                                        value="<?= $emp['id'] ?>"
                                        id="emp_<?= $emp['id'] ?>"
                                        <?= in_array($emp['id'], old('assigned_employees', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label"
                                        for="emp_<?= $emp['id'] ?>"
                                        style="font-size:12.5px;">
                                        <span class="fw-600">
                                            <?= esc($emp['last_name'] . ', ' . $emp['first_name']) ?>
                                        </span>
                                        <br>
                                        <span class="text-muted-sm">
                                            <?= esc($emp['employee_no']) ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="d-flex gap-2 justify-content-end mb-4">
        <a href="<?= base_url('/projects') ?>" class="btn btn-outline-secondary">
            <?= svg_icon('x', 'me-1', '15') ?> Cancel
        </a>
        <button type="submit" class="btn btn-ts-primary">
            <?= svg_icon('save', 'me-1', '15') ?> Save Project
        </button>
    </div>

</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let previewMap = null;
        let previewMarker = null;

        function initPreviewMap(lat, lng) {
            const mapEl = document.getElementById('previewMap');
            mapEl.style.display = 'block';

            if (!previewMap) {
                previewMap = L.map('previewMap').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap',
                    maxZoom: 19
                }).addTo(previewMap);
            } else {
                previewMap.setView([lat, lng], 15);
            }

            if (previewMarker) previewMarker.remove();
            previewMarker = L.marker([lat, lng]).addTo(previewMap);

            // Fix Leaflet tile rendering issue inside hidden divs
            setTimeout(function() {
                previewMap.invalidateSize();
            }, 100);
        }

        // Geocode button
        document.getElementById('btnGeocode').addEventListener('click', function() {
            const location = document.getElementById('projectLocation').value.trim();
            const status = document.getElementById('geocodeStatus');

            if (!location) {
                status.textContent = 'Please enter a location first.';
                return;
            }

            status.textContent = 'Locating...';
            this.disabled = true;
            const btn = this;

            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' +
                    encodeURIComponent(location) + '&limit=1&countrycodes=ph', {
                        headers: {
                            'Accept-Language': 'en'
                        }
                    })
                .then(r => r.json())
                .then(function(results) {
                    btn.disabled = false;
                    if (!results || results.length === 0) {
                        status.textContent = 'Location not found. Try a more specific address.';
                        return;
                    }

                    const lat = parseFloat(results[0].lat);
                    const lng = parseFloat(results[0].lon);

                    document.getElementById('projectLat').value = lat.toFixed(7);
                    document.getElementById('projectLng').value = lng.toFixed(7);
                    status.textContent = '✓ Located: ' + results[0].display_name;

                    initPreviewMap(lat, lng);
                })
                .catch(function() {
                    btn.disabled = false;
                    status.textContent = 'Geocoding failed. Please try again or enter coordinates manually.';
                });
        });

        // If lat/lng already pre-filled (e.g. validation error redirect), show map immediately
        const existingLat = parseFloat(document.getElementById('projectLat').value);
        const existingLng = parseFloat(document.getElementById('projectLng').value);
        if (existingLat && existingLng) {
            initPreviewMap(existingLat, existingLng);
        }
    });
</script>
<?= $this->endSection() ?>