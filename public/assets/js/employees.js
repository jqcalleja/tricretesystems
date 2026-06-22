/**
 * Tricrete Systems — Employees JS
 */
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
    // 2. Show/hide contract expiry field based on employment status
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
    // 3. Initialize Flatpickr on all date fields
    // ----------------------------------------------------------
    document.querySelectorAll('.flatpickr').forEach(function (el) {
        flatpickr(el, {
            dateFormat: 'Y-m-d',
            allowInput: true,
        });
    });

    // ----------------------------------------------------------
    // 4. Emergency Contact — edit modal population
    // ----------------------------------------------------------
    window.editEmergency = function (data) {
        const form = document.getElementById('emergencyForm');
        const empId = form.dataset.employeeId;

        document.getElementById('emergencyModalTitle').textContent = 'Edit Emergency Contact';
        form.action = `${window.TS_BASE_URL}employees/${empId}/emergency/update/${data.id}`;

        document.getElementById('ec_last_name').value = data.last_name || '';
        document.getElementById('ec_first_name').value = data.first_name || '';
        document.getElementById('ec_middle_name').value = data.middle_name || '';
        document.getElementById('ec_relationship').value = data.relationship || '';
        document.getElementById('ec_contact_number').value = data.contact_number || '';
        document.getElementById('ec_sort_order').value = data.sort_order || 1;

        // Populate address combobox group (province/city/barangay/street)
        if (window.TricreteAddress) {
            const group = window.TricreteAddress.initAddressGroup('ec_address');
            if (group) {
                group.setParts({
                    province: data.province || '',
                    city: data.city || '',
                    barangay: data.barangay || '',
                    street: data.street || ''
                });
            }
        }

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddEmergency')
        ).show();
    };

    document.getElementById('modalAddEmergency')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('emergencyModalTitle').textContent = 'Add Emergency Contact';
            document.getElementById('emergencyForm').reset();

            // Clear address combobox group
            if (window.TricreteAddress) {
                const group = window.TricreteAddress.initAddressGroup('ec_address');
                if (group) group.setParts({ province: '', city: '', barangay: '', street: '' });
            }

            const form = document.getElementById('emergencyForm');
            const empId = form.dataset.employeeId;
            form.action = `${window.TS_BASE_URL}employees/${empId}/emergency/store`;
        });

    // ----------------------------------------------------------
    // 5. Child — edit modal population
    // ----------------------------------------------------------
    window.editChild = function (data) {
        const form = document.getElementById('childForm');
        const empId = form.dataset.employeeId;

        document.getElementById('childModalTitle').textContent = 'Edit Child';
        form.action = `${window.TS_BASE_URL}employees/${empId}/child/update/${data.id}`;

        document.getElementById('child_name').value = data.name || '';

        const bday = document.getElementById('child_birthday');
        if (bday._flatpickr) {
            bday._flatpickr.setDate(data.birthday || '');
        } else {
            bday.value = data.birthday || '';
        }

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddChild')
        ).show();
    };

    document.getElementById('modalAddChild')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('childModalTitle').textContent = 'Add Child';
            document.getElementById('childForm').reset();
            const form = document.getElementById('childForm');
            const empId = form.dataset.employeeId;
            form.action = `${window.TS_BASE_URL}employees/${empId}/child/store`;
        });

    // ----------------------------------------------------------
    // 6. Education — edit modal population
    // ----------------------------------------------------------
    window.editEducation = function (data) {
        const form = document.getElementById('educationForm');
        const empId = form.dataset.employeeId;

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
            const form = document.getElementById('educationForm');
            const empId = form.dataset.employeeId;
            form.action = `${window.TS_BASE_URL}employees/${empId}/education/store`;
        });

    // ----------------------------------------------------------
    // 7. Employment History — edit modal population
    // ----------------------------------------------------------
    window.editHistory = function (data) {
        const form = document.getElementById('historyForm');
        const empId = form.dataset.employeeId;

        document.getElementById('historyModalTitle').textContent = 'Edit Employment History';
        form.action = `${window.TS_BASE_URL}employees/${empId}/history/update/${data.id}`;

        document.getElementById('hist_company').value = data.company_name || '';
        document.getElementById('hist_position').value = data.position || '';

        const fp_from = document.getElementById('hist_date_from');
        const fp_to = document.getElementById('hist_date_to');

        if (fp_from._flatpickr) fp_from._flatpickr.setDate(data.date_from || '');
        else fp_from.value = data.date_from || '';

        if (fp_to._flatpickr) fp_to._flatpickr.setDate(data.date_to || '');
        else fp_to.value = data.date_to || '';

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddHistory')
        ).show();
    };

    document.getElementById('modalAddHistory')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('historyModalTitle').textContent = 'Add Employment History';
            document.getElementById('historyForm').reset();
            const form = document.getElementById('historyForm');
            const empId = form.dataset.employeeId;
            form.action = `${window.TS_BASE_URL}employees/${empId}/history/store`;
        });

    // ----------------------------------------------------------
    // 8. Character Reference — edit modal population
    // ----------------------------------------------------------
    window.editReference = function (data) {
        const form = document.getElementById('referenceForm');
        const empId = form.dataset.employeeId;

        document.getElementById('referenceModalTitle').textContent = 'Edit Character Reference';
        form.action = `${window.TS_BASE_URL}employees/${empId}/reference/update/${data.id}`;

        document.getElementById('ref_name').value = data.name || '';
        document.getElementById('ref_occupation').value = data.occupation || '';
        document.getElementById('ref_telephone').value = data.telephone || '';

        // Populate address combobox group
        if (window.TricreteAddress) {
            const group = window.TricreteAddress.initAddressGroup('ref_address');
            if (group) {
                group.setParts({
                    province: data.province || '',
                    city: data.city || '',
                    barangay: data.barangay || '',
                    street: data.street || ''
                });
            }
        }

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAddReference')
        ).show();
    };

    /**
 * Opens the ID photo preview modal with the given image URL and title.
 * Used for SSS/PhilHealth/Pag-IBIG/TIN front/back photo thumbnails.
 */
    window.showIdPhotoModal = function (imageUrl, title) {
        document.getElementById('idPhotoModalImage').src = imageUrl;
        document.getElementById('idPhotoModalTitle').textContent = title;
        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalIdPhoto')
        ).show();
    };

    document.getElementById('modalAddReference')
        ?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('referenceModalTitle').textContent = 'Add Character Reference';
            document.getElementById('referenceForm').reset();

            // Clear address combobox group
            if (window.TricreteAddress) {
                const group = window.TricreteAddress.initAddressGroup('ref_address');
                if (group) group.setParts({ province: '', city: '', barangay: '', street: '' });
            }

            const form = document.getElementById('referenceForm');
            const empId = form.dataset.employeeId;
            form.action = `${window.TS_BASE_URL}employees/${empId}/reference/store`;
        });

});