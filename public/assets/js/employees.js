/**
 * Tricrete Systems — Employees JS
 */

// ----------------------------------------------------------
// Tab restore from URL hash — runs immediately on script parse
// ----------------------------------------------------------
(function () {
    const hash = window.location.hash;
    if (!hash) return;

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

    // Wait for Bootstrap to be ready, then activate the tab
    window.addEventListener('load', function () {
        const tabEl = document.querySelector('[data-bs-target="' + target + '"]');
        if (tabEl) bootstrap.Tab.getOrCreateInstance(tabEl).show();
    });
})();

// ----------------------------------------------------------
// AJAX form submit helper
// Submits a form via fetch with XMLHttpRequest header.
// Automatically refreshes the CI4 CSRF token from the response.
// ----------------------------------------------------------
function ajaxSubmitForm(form) {
    const formData = new FormData(form);
    return fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData,
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            // Refresh CSRF token in every form on the page after each AJAX call
            if (data.csrf) {
                document.querySelectorAll('input[name="csrf_test_name"]').forEach(function (el) {
                    el.value = data.csrf;
                    el.defaultValue = data.csrf;
                });
            }
            return data;
        });
}

// ----------------------------------------------------------
// Inline error helpers
// ----------------------------------------------------------
function showModalErrors(containerEl, errors) {
    if (!containerEl) return;
    containerEl.innerHTML = Object.values(errors).join('<br>');
    containerEl.classList.remove('d-none');
}

function clearModalErrors(containerEl) {
    if (!containerEl) return;
    containerEl.innerHTML = '';
    containerEl.classList.add('d-none');
}

// ----------------------------------------------------------
// HTML escape — used when building table rows from JSON data
// ----------------------------------------------------------
function setModalFormMode(form, mode) {
    if (!form) return;

    form.dataset.mode = mode;

    const addAnotherBtn = form.querySelector('[id$="AddAnother"]');
    if (!addAnotherBtn) return;

    const isCreate = mode !== 'edit';
    addAnotherBtn.disabled = !isCreate;
    addAnotherBtn.classList.toggle('d-none', !isCreate);
}

function esc(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

// ----------------------------------------------------------
// Append a new row to a tbody and reveal the table if the tab
// was previously showing an empty state.
// ----------------------------------------------------------
function appendRowToTable(tbodyId, rowHtml) {
    const tbody = document.getElementById(tbodyId);
    if (!tbody) {
        return false;
    }
    const temp = document.createElement('tbody');
    temp.innerHTML = rowHtml;
    tbody.appendChild(temp.firstChild);

    const emptyState = document.querySelector('[data-empty-for="' + tbodyId + '"]');
    if (emptyState) emptyState.classList.add('d-none');

    const tableWrap = document.querySelector('[data-table-for="' + tbodyId + '"]');
    if (tableWrap) tableWrap.classList.remove('d-none');

    return true;
}

// ----------------------------------------------------------
// Increment badge count on the matching tab nav item
// ----------------------------------------------------------
function incrementTabBadge(tbodyId) {
    // tbodyId → e.g. "emergencyBody" → tab target "#tabEmergency"
    const map = {
        emergencyBody: '#tabEmergency',
        childBody: '#tabChildren',
        educationBody: '#tabEducation',
        historyBody: '#tabHistory',
        referenceBody: '#tabReferences',
        otherIdsBody: '#tabOtherIds',
        prcBody: '#tabPrc',
    };
    const target = map[tbodyId];
    if (!target) return;
    const badge = document.querySelector('[data-bs-target="' + target + '"] .ts-badge');
    if (badge) badge.textContent = parseInt(badge.textContent || '0', 10) + 1;
}

// ----------------------------------------------------------
// TABLE ROW BUILDERS
// Each function returns an HTML string matching the server-
// rendered rows exactly, so AJAX-appended rows look identical
// to server-rendered ones. Called from onclick attributes in
// dynamically generated HTML — must stay outside DOMContentLoaded.
// ----------------------------------------------------------

function buildEmergencyRow(row, empId) {
    const name = esc(row.last_name) + ', ' + esc(row.first_name)
        + (row.middle_name ? ' ' + esc(row.middle_name.charAt(0)) + '.' : '');
    const data = JSON.stringify(row).replace(/"/g, '&quot;');
    const editSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
    const deleteSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;
    const delUrl = `${window.TS_BASE_URL}employees/${empId}/emergency/delete/${row.id}`;
    return `<tr id="ec-row-${row.id}">
        <td>${esc(row.sort_order)}</td>
        <td class="fw-600">${name}</td>
        <td class="d-none d-md-table-cell">${esc(row.relationship || '—')}</td>
        <td class="d-none d-md-table-cell tabular-nums">${esc(row.contact_number || '—')}</td>
        <td class="d-none d-lg-table-cell text-muted-sm">—</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <button class="ts-icon-btn" title="Edit" onclick="editEmergency(${data})">${editSvg}</button>
                <a href="${delUrl}" class="ts-icon-btn text-danger" title="Delete"
                   data-confirm-delete="${delUrl}" data-label="this emergency contact">${deleteSvg}</a>
            </div>
        </td>
    </tr>`;
}

function buildChildRow(row, empId) {
    const birthday = row.birthday
        ? new Date(row.birthday + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })
        : '—';
    const age = row.birthday
        ? Math.floor((Date.now() - new Date(row.birthday + 'T00:00:00')) / (365.25 * 24 * 60 * 60 * 1000))
        : '—';
    const data = JSON.stringify(row).replace(/"/g, '&quot;');
    const editSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
    const deleteSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;
    const delUrl = `${window.TS_BASE_URL}employees/${empId}/child/delete/${row.id}`;
    const rowNum = document.getElementById('childBody')
        ? document.getElementById('childBody').querySelectorAll('tr').length + 1
        : '—';
    return `<tr id="child-row-${row.id}">
        <td>${rowNum}</td>
        <td class="fw-600">${esc(row.name)}</td>
        <td class="tabular-nums">${birthday}</td>
        <td>${age}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <button class="ts-icon-btn" title="Edit" onclick="editChild(${data})">${editSvg}</button>
                <a href="${delUrl}" class="ts-icon-btn text-danger" title="Delete"
                   data-confirm-delete="${delUrl}" data-label="this child record">${deleteSvg}</a>
            </div>
        </td>
    </tr>`;
}

function buildEducationRow(row, empId) {
    const data = JSON.stringify(row).replace(/"/g, '&quot;');
    const editSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
    const deleteSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;
    const delUrl = `${window.TS_BASE_URL}employees/${empId}/education/delete/${row.id}`;
    return `<tr id="edu-row-${row.id}">
        <td><span class="ts-badge blue">${esc(row.level)}</span></td>
        <td class="fw-600">${esc(row.school_name || '—')}</td>
        <td class="tabular-nums">${esc(row.year_graduated || '—')}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <button class="ts-icon-btn" title="Edit" onclick="editEducation(${data})">${editSvg}</button>
                <a href="${delUrl}" class="ts-icon-btn text-danger" title="Delete"
                   data-confirm-delete="${delUrl}" data-label="this education record">${deleteSvg}</a>
            </div>
        </td>
    </tr>`;
}

function buildHistoryRow(row, empId) {
    const from = row.date_from
        ? new Date(row.date_from + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', year: 'numeric' })
        : '—';
    const to = row.date_to
        ? new Date(row.date_to + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', year: 'numeric' })
        : 'Present';
    const data = JSON.stringify(row).replace(/"/g, '&quot;');
    const editSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
    const deleteSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;
    const delUrl = `${window.TS_BASE_URL}employees/${empId}/history/delete/${row.id}`;
    return `<tr id="hist-row-${row.id}">
        <td class="fw-600">${esc(row.company_name)}</td>
        <td class="d-none d-md-table-cell">${esc(row.position || '—')}</td>
        <td class="tabular-nums">${from}</td>
        <td class="tabular-nums">${to}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <button class="ts-icon-btn" title="Edit" onclick="editHistory(${data})">${editSvg}</button>
                <a href="${delUrl}" class="ts-icon-btn text-danger" title="Delete"
                   data-confirm-delete="${delUrl}" data-label="this employment record">${deleteSvg}</a>
            </div>
        </td>
    </tr>`;
}

function buildReferenceRow(row, empId) {
    const data = JSON.stringify(row).replace(/"/g, '&quot;');
    const editSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
    const deleteSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;
    const delUrl = `${window.TS_BASE_URL}employees/${empId}/reference/delete/${row.id}`;
    return `<tr id="ref-row-${row.id}">
        <td class="fw-600">${esc(row.name)}</td>
        <td class="d-none d-md-table-cell">${esc(row.occupation || '—')}</td>
        <td class="d-none d-md-table-cell tabular-nums">${esc(row.telephone || '—')}</td>
        <td class="d-none d-lg-table-cell text-muted-sm">—</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <button class="ts-icon-btn" title="Edit" onclick="editReference(${data})">${editSvg}</button>
                <a href="${delUrl}" class="ts-icon-btn text-danger" title="Delete"
                   data-confirm-delete="${delUrl}" data-label="this reference">${deleteSvg}</a>
            </div>
        </td>
    </tr>`;
}

function buildOtherIdRow(row, empId) {
    const expiry = row.expiration
        ? new Date(row.expiration + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })
        : '—';
    const data = JSON.stringify(row).replace(/"/g, '&quot;');
    const editSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
    const deleteSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;
    const delUrl = `${window.TS_BASE_URL}employees/${empId}/other-id/delete/${row.id}`;
    return `<tr id="other-id-row-${row.id}">
        <td class="fw-600">${esc(row.id_type)}</td>
        <td class="tabular-nums">${esc(row.id_number || '—')}</td>
        <td class="tabular-nums">${expiry}</td>
        <td class="d-none d-md-table-cell text-muted-sm">${esc(row.remarks || '—')}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <button class="ts-icon-btn" title="Edit" onclick="editOtherId(${data})">${editSvg}</button>
                <a href="${delUrl}" class="ts-icon-btn text-danger" title="Delete"
                   data-confirm-delete="${delUrl}" data-label="this ID record">${deleteSvg}</a>
            </div>
        </td>
    </tr>`;
}

function buildPrcRow(row, empId) {
    const expiry = row.expiration
        ? new Date(row.expiration + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })
        : '—';
    const data = JSON.stringify(row).replace(/"/g, '&quot;');
    const editSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
    const deleteSvg = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;
    const delUrl = `${window.TS_BASE_URL}employees/${empId}/prc/delete/${row.id}`;
    return `<tr id="prc-row-${row.id}">
        <td class="fw-600">${esc(row.profession)}</td>
        <td class="tabular-nums">${esc(row.license_number || '—')}</td>
        <td class="tabular-nums">${expiry}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
                <button class="ts-icon-btn" title="Edit" onclick="editPrc(${data})">${editSvg}</button>
                <a href="${delUrl}" class="ts-icon-btn text-danger" title="Delete"
                   data-confirm-delete="${delUrl}" data-label="this PRC license">${deleteSvg}</a>
            </div>
        </td>
    </tr>`;
}

// ----------------------------------------------------------
// Edit functions for new record types — on window so onclick
// attributes in dynamically generated rows can call them
// ----------------------------------------------------------
window.editOtherId = function (data) {
    const form = document.getElementById('otherIdForm');
    const empId = form.dataset.employeeId;
    setModalFormMode(form, 'edit');

    document.getElementById('otherIdModalTitle').textContent = 'Edit Government ID';
    form.action = `${window.TS_BASE_URL}employees/${empId}/other-id/update/${data.id}`;

    document.getElementById('otherId_type').value = data.id_type || '';
    document.getElementById('otherId_number').value = data.id_number || '';
    document.getElementById('otherId_remarks').value = data.remarks || '';

    const expEl = document.getElementById('otherId_expiration');
    if (expEl && expEl._flatpickr) {
        expEl._flatpickr.setDate(data.expiration || '');
    } else if (expEl) {
        expEl.value = data.expiration || '';
    }

    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('modalAddOtherId')
    ).show();
};

window.editPrc = function (data) {
    const form = document.getElementById('prcForm');
    const empId = form.dataset.employeeId;
    setModalFormMode(form, 'edit');

    document.getElementById('prcModalTitle').textContent = 'Edit PRC License';
    form.action = `${window.TS_BASE_URL}employees/${empId}/prc/update/${data.id}`;

    document.getElementById('prc_profession').value = data.profession || '';
    document.getElementById('prc_license_number').value = data.license_number || '';

    const expEl = document.getElementById('prc_expiration');
    if (expEl && expEl._flatpickr) {
        expEl._flatpickr.setDate(data.expiration || '');
    } else if (expEl) {
        expEl.value = data.expiration || '';
    }

    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('modalAddPrc')
    ).show();
};

// ============================================================
// INSIDE DOMContentLoaded
// ============================================================
document.addEventListener('DOMContentLoaded', function () {

    // ----------------------------------------------------------
    // 1. Photo preview
    // ----------------------------------------------------------
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');

    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => photoPreview.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
    }

    // ----------------------------------------------------------
    // 2. Show/hide contract expiry field
    // ----------------------------------------------------------
    const empStatus = document.getElementById('empStatus');
    const contractExpiryField = document.getElementById('contractExpiryField');

    function toggleContractExpiry() {
        if (!empStatus || !contractExpiryField) return;
        contractExpiryField.style.display =
            empStatus.value === 'Project-Based' ? 'block' : 'none';
    }

    if (empStatus) {
        empStatus.addEventListener('change', toggleContractExpiry);
        toggleContractExpiry();
    }

    // ----------------------------------------------------------
    // 3. Flatpickr on all date fields
    // ----------------------------------------------------------
    document.querySelectorAll('.flatpickr').forEach(function (el) {
        flatpickr(el, { dateFormat: 'Y-m-d', allowInput: true });
    });

    // ----------------------------------------------------------
    // 4. Emergency Contact — edit modal
    // ----------------------------------------------------------
    window.editEmergency = function (data) {
        const form = document.getElementById('emergencyForm');
        const empId = form.dataset.employeeId;
        setModalFormMode(form, 'edit');

        document.getElementById('emergencyModalTitle').textContent = 'Edit Emergency Contact';
        form.action = `${window.TS_BASE_URL}employees/${empId}/emergency/update/${data.id}`;

        document.getElementById('ec_last_name').value = data.last_name || '';
        document.getElementById('ec_first_name').value = data.first_name || '';
        document.getElementById('ec_middle_name').value = data.middle_name || '';
        document.getElementById('ec_relationship').value = data.relationship || '';
        document.getElementById('ec_contact_number').value = data.contact_number || '';
        document.getElementById('ec_sort_order').value = data.sort_order || 1;

        setTimeout(function () {
            const ecGroup = window.TricreteAddressGroups
                ? window.TricreteAddressGroups['ec_address']
                : null;
            if (ecGroup) {
                ecGroup.setParts({
                    province: data.province || '',
                    city: data.city || '',
                    barangay: data.barangay || '',
                    street: data.address_street || '',
                });
            }
        }, 50);

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddEmergency')
        ).show();
    };

    document.getElementById('modalAddEmergency')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('emergencyModalTitle').textContent = 'Add Emergency Contact';
            document.getElementById('emergencyForm').reset();
            clearModalErrors(document.getElementById('emergencyErrors'));

            if (window.TricreteAddress) {
                const group = window.TricreteAddress.initAddressGroup('ec_address');
                if (group) group.setParts({ province: '', city: '', barangay: '', street: '' });
            }

            const form = document.getElementById('emergencyForm');
            const empId = form.dataset.employeeId;
            setModalFormMode(form, 'create');
            form.action = `${window.TS_BASE_URL}employees/${empId}/emergency/store`;
        });

    // ----------------------------------------------------------
    // 5. Child — edit modal
    // ----------------------------------------------------------
    window.editChild = function (data) {
        const form = document.getElementById('childForm');
        const empId = form.dataset.employeeId;
        setModalFormMode(form, 'edit');

        document.getElementById('childModalTitle').textContent = 'Edit Child';
        form.action = `${window.TS_BASE_URL}employees/${empId}/child/update/${data.id}`;

        document.getElementById('child_name').value = data.name || '';

        const bday = document.getElementById('child_birthday');
        if (bday && bday._flatpickr) bday._flatpickr.setDate(data.birthday || '');
        else if (bday) bday.value = data.birthday || '';

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddChild')
        ).show();
    };

    document.getElementById('modalAddChild')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('childModalTitle').textContent = 'Add Child';
            document.getElementById('childForm').reset();
            clearModalErrors(document.getElementById('childErrors'));
            const form = document.getElementById('childForm');
            const empId = form.dataset.employeeId;
            setModalFormMode(form, 'create');
            form.action = `${window.TS_BASE_URL}employees/${empId}/child/store`;
        });

    // ----------------------------------------------------------
    // 6. Education — edit modal
    // ----------------------------------------------------------
    window.editEducation = function (data) {
        const form = document.getElementById('educationForm');
        const empId = form.dataset.employeeId;
        setModalFormMode(form, 'edit');

        document.getElementById('educationModalTitle').textContent = 'Edit Education Record';
        form.action = `${window.TS_BASE_URL}employees/${empId}/education/update/${data.id}`;

        document.getElementById('edu_level').value = data.level || '';
        document.getElementById('edu_school').value = data.school_name || '';
        document.getElementById('edu_year').value = data.year_graduated || '';

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddEducation')
        ).show();
    };

    document.getElementById('modalAddEducation')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('educationModalTitle').textContent = 'Add Education Record';
            document.getElementById('educationForm').reset();
            clearModalErrors(document.getElementById('educationErrors'));
            const form = document.getElementById('educationForm');
            const empId = form.dataset.employeeId;
            setModalFormMode(form, 'create');
            form.action = `${window.TS_BASE_URL}employees/${empId}/education/store`;
        });

    // ----------------------------------------------------------
    // 7. Employment History — edit modal
    // ----------------------------------------------------------
    window.editHistory = function (data) {
        const form = document.getElementById('historyForm');
        const empId = form.dataset.employeeId;
        setModalFormMode(form, 'edit');

        document.getElementById('historyModalTitle').textContent = 'Edit Employment History';
        form.action = `${window.TS_BASE_URL}employees/${empId}/history/update/${data.id}`;

        document.getElementById('hist_company').value = data.company_name || '';
        document.getElementById('hist_position').value = data.position || '';

        const fpFrom = document.getElementById('hist_date_from');
        const fpTo = document.getElementById('hist_date_to');

        if (fpFrom && fpFrom._flatpickr) fpFrom._flatpickr.setDate(data.date_from || '');
        else if (fpFrom) fpFrom.value = data.date_from || '';

        if (fpTo && fpTo._flatpickr) fpTo._flatpickr.setDate(data.date_to || '');
        else if (fpTo) fpTo.value = data.date_to || '';

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddHistory')
        ).show();
    };

    document.getElementById('modalAddHistory')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('historyModalTitle').textContent = 'Add Employment History';
            document.getElementById('historyForm').reset();
            clearModalErrors(document.getElementById('historyErrors'));
            const form = document.getElementById('historyForm');
            const empId = form.dataset.employeeId;
            setModalFormMode(form, 'create');
            form.action = `${window.TS_BASE_URL}employees/${empId}/history/store`;
        });

    // ----------------------------------------------------------
    // 8. Character Reference — edit modal
    // ----------------------------------------------------------
    window.editReference = function (data) {
        const form = document.getElementById('referenceForm');
        const empId = form.dataset.employeeId;
        setModalFormMode(form, 'edit');

        document.getElementById('referenceModalTitle').textContent = 'Edit Character Reference';
        form.action = `${window.TS_BASE_URL}employees/${empId}/reference/update/${data.id}`;

        document.getElementById('ref_name').value = data.name || '';
        document.getElementById('ref_occupation').value = data.occupation || '';
        document.getElementById('ref_telephone').value = data.telephone || '';

        setTimeout(function () {
            const refGroup = window.TricreteAddressGroups
                ? window.TricreteAddressGroups['ref_address']
                : null;
            if (refGroup) {
                refGroup.setParts({
                    province: data.province || '',
                    city: data.city || '',
                    barangay: data.barangay || '',
                    street: data.address_street || '',
                });
            }
        }, 50);

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddReference')
        ).show();
    };

    document.getElementById('modalAddReference')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('referenceModalTitle').textContent = 'Add Character Reference';
            document.getElementById('referenceForm').reset();
            clearModalErrors(document.getElementById('referenceErrors'));

            if (window.TricreteAddress) {
                const group = window.TricreteAddress.initAddressGroup('ref_address');
                if (group) group.setParts({ province: '', city: '', barangay: '', street: '' });
            }

            const form = document.getElementById('referenceForm');
            const empId = form.dataset.employeeId;
            setModalFormMode(form, 'create');
            form.action = `${window.TS_BASE_URL}employees/${empId}/reference/store`;
        });

    // ----------------------------------------------------------
    // 9. ID Photo Modal
    // ----------------------------------------------------------
    window.showIdPhotoModal = function (imageUrl, title) {
        document.getElementById('idPhotoModalImage').src = imageUrl;
        document.getElementById('idPhotoModalTitle').textContent = title;
        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalIdPhoto')
        ).show();
    };

    // ----------------------------------------------------------
    // 10. Other IDs modal — reset on close
    // ----------------------------------------------------------
    document.getElementById('modalAddOtherId')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('otherIdModalTitle').textContent = 'Add Government ID';
            document.getElementById('otherIdForm').reset();
            clearModalErrors(document.getElementById('otherIdErrors'));
            const form = document.getElementById('otherIdForm');
            const empId = form.dataset.employeeId;
            setModalFormMode(form, 'create');
            form.action = `${window.TS_BASE_URL}employees/${empId}/other-id/store`;
        });

    // ----------------------------------------------------------
    // 11. PRC modal — reset on close
    // ----------------------------------------------------------
    document.getElementById('modalAddPrc')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('prcModalTitle').textContent = 'Add PRC License';
            document.getElementById('prcForm').reset();
            clearModalErrors(document.getElementById('prcErrors'));
            const form = document.getElementById('prcForm');
            const empId = form.dataset.employeeId;
            setModalFormMode(form, 'create');
            form.action = `${window.TS_BASE_URL}employees/${empId}/prc/store`;
        });

    // ----------------------------------------------------------
    // 12. AJAX "Add Another" — generic wiring function
    // ----------------------------------------------------------
    function wireAddAnother(config) {
        const btn = document.getElementById(config.addAnotherId);
        if (!btn) return;

        btn.addEventListener('click', function () {
            const form = document.getElementById(config.formId);
            const empId = form.dataset.employeeId;
            const errors = document.getElementById(config.errorsId);

            if (form.dataset.mode === 'edit') return;

            clearModalErrors(errors);

            ajaxSubmitForm(form).then(function (data) {
                if (!data.success) {
                    showModalErrors(errors, data.errors || { msg: 'An error occurred.' });
                    return;
                }

                // Append new row into the table
                const rowAdded = appendRowToTable(
                    config.tbodyId,
                    config.buildRow(data.row, empId)
                );

                if (!rowAdded) {
                    showModalErrors(errors, {
                        msg: 'Saved, but the table could not be updated. Please refresh the page.',
                    });
                    return;
                }

                // Update the tab badge count
                incrementTabBadge(config.tbodyId);

                // Reset the form fields for the next entry
                form.reset();

                // Clear any flatpickr date fields
                form.querySelectorAll('.flatpickr').forEach(function (el) {
                    if (el._flatpickr) el._flatpickr.clear();
                });

                // Clear address comboboxes if this modal has one
                if (config.addressPrefix && window.TricreteAddress) {
                    const group = window.TricreteAddress.initAddressGroup(config.addressPrefix);
                    if (group) group.setParts({ province: '', city: '', barangay: '', street: '' });
                }

                // Re-focus the first input for quick next entry
                const firstInput = form.querySelector('input[type="text"], select');
                if (firstInput) firstInput.focus();
            })
                .catch(function () {
                    showModalErrors(errors, { msg: 'Network error. Please try again.' });
                });
        });
    }

    // Wire all seven modals
    wireAddAnother({
        addAnotherId: 'btnEmergencyAddAnother',
        formId: 'emergencyForm',
        tbodyId: 'emergencyBody',
        errorsId: 'emergencyErrors',
        buildRow: buildEmergencyRow,
        addressPrefix: 'ec_address',
    });

    wireAddAnother({
        addAnotherId: 'btnChildAddAnother',
        formId: 'childForm',
        tbodyId: 'childBody',
        errorsId: 'childErrors',
        buildRow: buildChildRow,
    });

    wireAddAnother({
        addAnotherId: 'btnEducationAddAnother',
        formId: 'educationForm',
        tbodyId: 'educationBody',
        errorsId: 'educationErrors',
        buildRow: buildEducationRow,
    });

    wireAddAnother({
        addAnotherId: 'btnHistoryAddAnother',
        formId: 'historyForm',
        tbodyId: 'historyBody',
        errorsId: 'historyErrors',
        buildRow: buildHistoryRow,
    });

    wireAddAnother({
        addAnotherId: 'btnReferenceAddAnother',
        formId: 'referenceForm',
        tbodyId: 'referenceBody',
        errorsId: 'referenceErrors',
        buildRow: buildReferenceRow,
        addressPrefix: 'ref_address',
    });

    wireAddAnother({
        addAnotherId: 'btnOtherIdAddAnother',
        formId: 'otherIdForm',
        tbodyId: 'otherIdsBody',
        errorsId: 'otherIdErrors',
        buildRow: buildOtherIdRow,
    });

    wireAddAnother({
        addAnotherId: 'btnPrcAddAnother',
        formId: 'prcForm',
        tbodyId: 'prcBody',
        errorsId: 'prcErrors',
        buildRow: buildPrcRow,
    });

}); // end DOMContentLoaded
