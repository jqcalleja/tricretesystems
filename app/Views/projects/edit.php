<?= $this->extend('layouts/main') ?>

<?php
/**
 * @var array  $project         Project record being edited
 * @var array  $employees       All active employees
 * @var array  $assignedIds     Currently assigned employee IDs
 * @var int    $siteEngineerId  Currently designated site engineer ID
 */
?>

<?= $this->section('content') ?>

<div class="ts-page-header">
    <div>
        <div class="ts-breadcrumb">
            <?= svg_icon('projects', '', '13') ?>
            <a href="<?= base_url('/projects') ?>">Projects</a>
            <span>/</span>
            <a href="<?= base_url('/projects/view/' . $project['id']) ?>">
                <?= esc($project['project_name']) ?>
            </a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="ts-page-title">Edit Project</h1>
        <p class="ts-page-subtitle"><?= esc($project['project_code']) ?></p>
    </div>
    <a href="<?= base_url('/projects/view/' . $project['id']) ?>"
        class="btn btn-outline-secondary btn-sm">
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

<form action="<?= base_url('/projects/update/' . $project['id']) ?>"
    method="post" id="projectForm">
    <?= csrf_field() ?>

    <!-- ── 1. Project Information ── -->
    <div class="ts-card">
        <p class="ts-section-title">Project Information</p>
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label class="ts-form-label">Project Code <span class="text-danger">*</span></label>
                <input type="text" name="project_code" class="form-control form-control-sm"
                    value="<?= esc(old('project_code', $project['project_code'])) ?>" required>
            </div>
            <div class="col-12 col-md-8">
                <label class="ts-form-label">Project Name <span class="text-danger">*</span></label>
                <input type="text" name="project_name" class="form-control form-control-sm"
                    value="<?= esc(old('project_name', $project['project_name'])) ?>" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="ts-form-label">Client Name</label>
                <input type="text" name="client_name" class="form-control form-control-sm"
                    value="<?= esc(old('client_name', $project['client_name'])) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="ts-form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select form-select-sm" required>
                    <?php foreach (['Active', 'Completed', 'On Hold', 'Cancelled'] as $s): ?>
                        <option value="<?= $s ?>"
                            <?= old('status', $project['status']) === $s ? 'selected' : '' ?>>
                            <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="ts-form-label">Contract Amount (₱)</label>
                <input type="number" name="contract_amount" class="form-control form-control-sm"
                    step="0.01" min="0"
                    value="<?= esc(old('contract_amount', $project['contract_amount'])) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="ts-form-label">Start Date</label>
                <input type="text" name="start_date" class="form-control form-control-sm flatpickr"
                    value="<?= esc(old('start_date', $project['start_date'])) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="ts-form-label">End Date</label>
                <input type="text" name="end_date" class="form-control form-control-sm flatpickr"
                    value="<?= esc(old('end_date', $project['end_date'])) ?>">
            </div>
            <div class="col-12">
                <label class="ts-form-label">Description</label>
                <textarea name="description" class="form-control form-control-sm"
                    rows="3"><?= esc(old('description', $project['description'])) ?></textarea>
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
                        value="<?= esc(old('location', $project['location'])) ?>"
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
                    value="<?= esc(old('latitude', $project['latitude'])) ?>">
            </div>
            <div class="col-6 col-md-2">
                <label class="ts-form-label">Longitude</label>
                <input type="text" name="longitude" id="projectLng"
                    class="form-control form-control-sm ts-no-uppercase"
                    value="<?= esc(old('longitude', $project['longitude'])) ?>">
            </div>
            <div class="col-12">
                <div id="previewMap"
                    style="height:260px;border-radius:6px;border:1px solid var(--ts-border);
                            display:none;"></div>
            </div>
        </div>
    </div>

    <!-- ── 3. Employee Assignment ── -->
    <?php
    $selectedSiteEngineerId = (int) old('site_engineer_id', $siteEngineerId);
    $oldAssignedIds = old('assigned_employees', $assignedIds);
    $oldAssignedIds = is_array($oldAssignedIds) ? array_map('intval', $oldAssignedIds) : [];
    ?>

    <div class="ts-card">
        <p class="ts-section-title">Assigned Employees</p>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="ts-form-label">Site Engineer</label>
                <select name="site_engineer_id" id="siteEngineerSelect" class="form-select form-select-sm">
                    <option value="">— Select Site Engineer —</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= $emp['id'] ?>"
                            <?= $selectedSiteEngineerId === (int) $emp['id'] ? 'selected' : '' ?>>
                            <?= esc($emp['last_name'] . ', ' . $emp['first_name']) ?>
                            (<?= esc($emp['employee_no']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="text-muted-sm mt-1" style="font-size:11.5px;">
                    The selected site engineer is saved separately and removed from the employee list below.
                </div>
            </div>
            <div class="col-12">
                <label class="ts-form-label">Project Employees</label>
                <div class="project-assignment-picker">
                    <div class="assignment-panel">
                        <div class="assignment-panel-header">
                            <span>Available Employees</span>
                            <span class="ts-badge gray" id="availableEmployeeCount">0</span>
                        </div>
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-white">
                                <?= svg_icon('search', '', '13') ?>
                            </span>
                            <input type="search" id="availableEmployeeSearch"
                                class="form-control form-control-sm ts-no-uppercase"
                                placeholder="Search available employees">
                        </div>
                        <div class="assignment-list" id="availableEmployeesList">
                            <?php foreach ($employees as $emp):
                                $empId = (int) $emp['id'];
                                $isAssigned = in_array($empId, $oldAssignedIds, true);
                                $isSelectedEngineer = $selectedSiteEngineerId === $empId;
                                if ($isAssigned && ! $isSelectedEngineer) continue;
                                $employeeName = trim($emp['last_name'] . ', ' . $emp['first_name']);
                                $searchText = strtolower($employeeName . ' ' . $emp['employee_no']);
                            ?>
                                <label class="assignment-item <?= $isSelectedEngineer ? 'd-none' : '' ?>"
                                    data-employee-item
                                    data-employee-id="<?= $empId ?>"
                                    data-engineer-hidden="<?= $isSelectedEngineer ? '1' : '0' ?>"
                                    data-search="<?= esc($searchText, 'attr') ?>">
                                    <input type="checkbox" class="form-check-input"
                                        <?= $isSelectedEngineer ? 'disabled' : '' ?>>
                                    <span>
                                        <span class="fw-600"><?= esc($employeeName) ?></span>
                                        <span class="text-muted-sm d-block"><?= esc($emp['employee_no']) ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div class="assignment-empty d-none" data-empty-for="availableEmployeesList">
                            No available employees found.
                        </div>
                    </div>

                    <div class="assignment-actions">
                        <button type="button" class="btn btn-ts-primary btn-sm" id="btnAddEmployees">
                            <?= svg_icon('plus', 'me-1', '13') ?> Add
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRemoveEmployees">
                            <?= svg_icon('x', 'me-1', '13') ?> Remove
                        </button>
                    </div>

                    <div class="assignment-panel">
                        <div class="assignment-panel-header">
                            <span>Project Employees</span>
                            <span class="ts-badge gray" id="assignedEmployeeCount">0</span>
                        </div>
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-white">
                                <?= svg_icon('search', '', '13') ?>
                            </span>
                            <input type="search" id="assignedEmployeeSearch"
                                class="form-control form-control-sm ts-no-uppercase"
                                placeholder="Search project employees">
                        </div>
                        <div class="assignment-list" id="assignedEmployeesList">
                        <?php foreach ($employees as $emp):
                            $empId = (int) $emp['id'];
                            $isAssigned = in_array($empId, $oldAssignedIds, true);
                            $isSelectedEngineer = $selectedSiteEngineerId === $empId;
                            if (! $isAssigned || $isSelectedEngineer) continue;
                            $employeeName = trim($emp['last_name'] . ', ' . $emp['first_name']);
                            $searchText = strtolower($employeeName . ' ' . $emp['employee_no']);
                        ?>
                            <label class="assignment-item"
                                data-employee-item
                                data-employee-id="<?= $empId ?>"
                                data-search="<?= esc($searchText, 'attr') ?>">
                                <input type="checkbox" class="form-check-input">
                                <input type="hidden" name="assigned_employees[]"
                                    value="<?= $empId ?>" data-assigned-input>
                                <span>
                                    <span class="fw-600"><?= esc($employeeName) ?></span>
                                    <span class="text-muted-sm d-block"><?= esc($emp['employee_no']) ?></span>
                                </span>
                            </label>
                        <?php endforeach; ?>
                        </div>
                        <div class="assignment-empty d-none" data-empty-for="assignedEmployeesList">
                            No project employees found.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="d-flex gap-2 justify-content-end mb-4">
        <a href="<?= base_url('/projects/view/' . $project['id']) ?>"
            class="btn btn-outline-secondary">
            <?= svg_icon('x', 'me-1', '15') ?> Cancel
        </a>
        <button type="submit" class="btn btn-ts-primary">
            <?= svg_icon('save', 'me-1', '15') ?> Update Project
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
            setTimeout(function() {
                previewMap.invalidateSize();
            }, 100);
        }

        const siteEngineerSelect = document.getElementById('siteEngineerSelect');
        const availableList = document.getElementById('availableEmployeesList');
        const assignedList = document.getElementById('assignedEmployeesList');
        const availableSearch = document.getElementById('availableEmployeeSearch');
        const assignedSearch = document.getElementById('assignedEmployeeSearch');
        const addEmployeesBtn = document.getElementById('btnAddEmployees');
        const removeEmployeesBtn = document.getElementById('btnRemoveEmployees');
        const availableCount = document.getElementById('availableEmployeeCount');
        const assignedCount = document.getElementById('assignedEmployeeCount');

        function pickerItems(list) {
            return Array.from(list.querySelectorAll('[data-employee-item]'));
        }

        function selectedPickerItems(list) {
            return pickerItems(list).filter(function(item) {
                const checkbox = item.querySelector('input[type="checkbox"]');
                return checkbox && checkbox.checked && !item.classList.contains('d-none');
            });
        }

        function ensureAssignedInput(item) {
            if (item.querySelector('[data-assigned-input]')) return;

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'assigned_employees[]';
            input.value = item.getAttribute('data-employee-id');
            input.setAttribute('data-assigned-input', '');
            item.insertBefore(input, item.querySelector('span'));
        }

        function removeAssignedInput(item) {
            const input = item.querySelector('[data-assigned-input]');
            if (input) input.remove();
        }

        function insertSorted(list, item) {
            const itemSearch = item.getAttribute('data-search') || '';
            const before = pickerItems(list).filter(function(existing) {
                return existing !== item;
            }).find(function(existing) {
                return (existing.getAttribute('data-search') || '') > itemSearch;
            });
            list.insertBefore(item, before || null);
        }

        function movePickerItem(item, targetList) {
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = false;
                checkbox.disabled = false;
            }

            if (targetList === assignedList) {
                ensureAssignedInput(item);
            } else {
                removeAssignedInput(item);
            }

            insertSorted(targetList, item);
        }

        function applyPickerFilter(list, searchInput) {
            const query = (searchInput ? searchInput.value : '').trim().toLowerCase();

            pickerItems(list).forEach(function(item) {
                const isEngineer = item.getAttribute('data-engineer-hidden') === '1';
                const matches = !query || (item.getAttribute('data-search') || '').includes(query);
                item.classList.toggle('d-none', isEngineer || !matches);
            });
        }

        function visiblePickerCount(list) {
            return pickerItems(list).filter(function(item) {
                return !item.classList.contains('d-none');
            }).length;
        }

        function refreshPickerState() {
            if (!availableList || !assignedList) return;

            applyPickerFilter(availableList, availableSearch);
            applyPickerFilter(assignedList, assignedSearch);

            const availableVisible = visiblePickerCount(availableList);
            const assignedVisible = visiblePickerCount(assignedList);

            if (availableCount) availableCount.textContent = availableVisible;
            if (assignedCount) assignedCount.textContent = assignedVisible;

            document.querySelectorAll('.project-assignment-picker [data-empty-for]').forEach(function(empty) {
                const list = document.getElementById(empty.getAttribute('data-empty-for'));
                if (!list) return;
                empty.classList.toggle('d-none', visiblePickerCount(list) > 0);
            });

            if (addEmployeesBtn) addEmployeesBtn.disabled = selectedPickerItems(availableList).length === 0;
            if (removeEmployeesBtn) removeEmployeesBtn.disabled = selectedPickerItems(assignedList).length === 0;
        }

        function syncEngineerEmployeeList() {
            if (!availableList || !assignedList) return;

            const selectedId = siteEngineerSelect ? siteEngineerSelect.value : '';
            document.querySelectorAll('[data-employee-item]').forEach(function(item) {
                const isSelectedEngineer = selectedId !== '' &&
                    item.getAttribute('data-employee-id') === selectedId;
                const checkbox = item.querySelector('input[type="checkbox"]');

                item.setAttribute('data-engineer-hidden', isSelectedEngineer ? '1' : '0');

                if (isSelectedEngineer) {
                    movePickerItem(item, availableList);
                    if (checkbox) checkbox.disabled = true;
                } else if (checkbox) {
                    checkbox.disabled = false;
                }
            });

            refreshPickerState();
        }

        if (addEmployeesBtn) {
            addEmployeesBtn.addEventListener('click', function() {
                selectedPickerItems(availableList).forEach(function(item) {
                    movePickerItem(item, assignedList);
                });
                refreshPickerState();
            });
        }

        if (removeEmployeesBtn) {
            removeEmployeesBtn.addEventListener('click', function() {
                selectedPickerItems(assignedList).forEach(function(item) {
                    movePickerItem(item, availableList);
                });
                refreshPickerState();
            });
        }

        [availableList, assignedList].forEach(function(list) {
            if (list) list.addEventListener('change', refreshPickerState);
        });

        [availableSearch, assignedSearch].forEach(function(input) {
            if (input) input.addEventListener('input', refreshPickerState);
        });

        if (siteEngineerSelect) {
            siteEngineerSelect.addEventListener('change', syncEngineerEmployeeList);
        }
        syncEngineerEmployeeList();

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

        // Pre-fill map if coordinates already exist
        const existingLat = parseFloat(document.getElementById('projectLat').value);
        const existingLng = parseFloat(document.getElementById('projectLng').value);
        if (existingLat && existingLng) {
            initPreviewMap(existingLat, existingLng);
        }
    });
</script>
<?= $this->endSection() ?>
