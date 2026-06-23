/**
 * Tricrete Systems — Philippine Address Combobox Component
 * Cascading Province -> City/Municipality -> Barangay, typeable with
 * suggestions, keyboard navigable (Arrow Up/Down, Enter/Tab to select, Esc to close).
 * Street is a plain text field, not part of the combobox cascade.
 *
 * Also handles "Same as Current Address" auto-copy for Spouse and Parents
 * address groups — checked by default on create, live-syncs while checked.
 */

(function () {

    let phData = null;
    let phDataPromise = null;

    function loadPhData() {
        if (phDataPromise) return phDataPromise;
        const dataUrl = (window.TS_BASE_URL || '') + 'assets/data/ph_locations.json';
        phDataPromise = fetch(dataUrl)
            .then(function (res) {
                if (!res.ok) throw new Error('Failed to load ph_locations.json: ' + res.status);
                return res.json();
            })
            .then(function (data) { phData = data; return data; });
        return phDataPromise;
    }

    /**
     * Creates a typeable, keyboard-navigable combobox.
     */
    function createCombobox(inputEl, listEl) {
        let options = [];
        let onSelectCb = null;
        let currentMatches = [];
        let highlightedIndex = -1;

        function renderSuggestions(filter) {
            const f = filter.trim().toUpperCase();
            currentMatches = f === ''
                ? options.slice(0, 50)
                : options.filter(o => o.toUpperCase().includes(f)).slice(0, 50);
            highlightedIndex = -1;

            if (currentMatches.length === 0) {
                listEl.innerHTML = '<div class="ts-combo-empty">No matches</div>';
                listEl.classList.add('show');
                return;
            }

            listEl.innerHTML = currentMatches.map(function (opt, i) {
                return '<div class="ts-combo-item" data-index="' + i + '" data-value="' + opt.replace(/"/g, '&quot;') + '">' + opt + '</div>';
            }).join('');
            listEl.classList.add('show');
        }

        function updateHighlight() {
            const items = listEl.querySelectorAll('.ts-combo-item');
            items.forEach(function (item, i) {
                item.classList.toggle('highlighted', i === highlightedIndex);
            });
            if (highlightedIndex >= 0 && items[highlightedIndex]) {
                items[highlightedIndex].scrollIntoView({ block: 'nearest' });
            }
        }

        function selectValue(value) {
            inputEl.value = value;
            listEl.classList.remove('show');
            highlightedIndex = -1;
            if (onSelectCb) onSelectCb(value, true);
        }

        inputEl.addEventListener('focus', function () {
            if (inputEl.disabled) return;
            renderSuggestions(inputEl.value);
        });

        inputEl.addEventListener('input', function () {
            if (inputEl.disabled) return;
            renderSuggestions(inputEl.value);
            if (onSelectCb) onSelectCb(inputEl.value, false);
        });

        inputEl.addEventListener('keydown', function (e) {
            if (inputEl.disabled) return;
            if (!listEl.classList.contains('show') || currentMatches.length === 0) {
                return;
            }

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                highlightedIndex = Math.min(highlightedIndex + 1, currentMatches.length - 1);
                updateHighlight();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                highlightedIndex = Math.max(highlightedIndex - 1, 0);
                updateHighlight();
            } else if (e.key === 'Enter') {
                if (highlightedIndex >= 0) {
                    e.preventDefault();
                    selectValue(currentMatches[highlightedIndex]);
                }
            } else if (e.key === 'Tab') {
                if (highlightedIndex >= 0) {
                    selectValue(currentMatches[highlightedIndex]);
                } else {
                    listEl.classList.remove('show');
                }
            } else if (e.key === 'Escape') {
                listEl.classList.remove('show');
                highlightedIndex = -1;
            }
        });

        inputEl.addEventListener('blur', function () {
            setTimeout(function () { listEl.classList.remove('show'); }, 150);
        });

        listEl.addEventListener('mousedown', function (e) {
            const item = e.target.closest('.ts-combo-item');
            if (!item) return;
            e.preventDefault();
            selectValue(item.getAttribute('data-value'));
        });

        listEl.addEventListener('mousemove', function (e) {
            const item = e.target.closest('.ts-combo-item');
            if (!item) return;
            highlightedIndex = parseInt(item.getAttribute('data-index'), 10);
            updateHighlight();
        });

        document.addEventListener('click', function (e) {
            if (!inputEl.contains(e.target) && !listEl.contains(e.target)) {
                listEl.classList.remove('show');
            }
        });

        return {
            setOptions: function (arr) { options = arr || []; },
            getValue: function () { return inputEl.value; },
            setValue: function (val) { inputEl.value = val || ''; },
            onSelect: function (cb) { onSelectCb = cb; },
            clear: function () { inputEl.value = ''; },
            disable: function () {
                inputEl.disabled = true;
                inputEl.placeholder = 'Select previous field first';
            },
            enable: function (placeholder) {
                inputEl.disabled = false;
                if (placeholder) inputEl.placeholder = placeholder;
            }
        };
    }

    /**
     * Initializes (or returns the already-initialized) address group for a prefix.
     * Address groups are cached on `window.TricreteAddressGroups` so repeated
     * calls to initAddressGroup() with the same prefix return the SAME instance
     * instead of re-binding duplicate event listeners.
     */
    function initAddressGroup(prefix) {
        window.TricreteAddressGroups = window.TricreteAddressGroups || {};
        if (window.TricreteAddressGroups[prefix]) {
            return window.TricreteAddressGroups[prefix];
        }

        const provinceInput = document.getElementById(prefix + '_province_input');
        const provinceList = document.getElementById(prefix + '_province_list');
        const cityInput = document.getElementById(prefix + '_city_input');
        const cityList = document.getElementById(prefix + '_city_list');
        const brgyInput = document.getElementById(prefix + '_barangay_input');
        const brgyList = document.getElementById(prefix + '_barangay_list');
        const streetInput = document.getElementById(prefix + '_street_input');

        const hiddenProvince = document.getElementById(prefix + '_province');
        const hiddenCity = document.getElementById(prefix + '_city');
        const hiddenBarangay = document.getElementById(prefix + '_barangay');
        const hiddenStreet = document.getElementById(prefix + '_street');

        if (!provinceInput || !cityInput || !brgyInput) {
            return null;
        }

        const provinceCombo = createCombobox(provinceInput, provinceList);
        const cityCombo = createCombobox(cityInput, cityList);
        const brgyCombo = createCombobox(brgyInput, brgyList);

        function syncHidden() {
            if (hiddenProvince) hiddenProvince.value = provinceInput.value.trim();
            if (hiddenCity) hiddenCity.value = cityInput.value.trim();
            if (hiddenBarangay) hiddenBarangay.value = brgyInput.value.trim();
            if (hiddenStreet)   hiddenStreet.value   = streetInput ? streetInput.value.trim() : '';
        }

        function notifyChanged() {
            document.dispatchEvent(new CustomEvent('ts:address-changed', { detail: { prefix: prefix } }));
        }

        const hasPrefilledProvince = provinceInput.value.trim() !== '';
        const hasPrefilledCity = cityInput.value.trim() !== '';

        if (!hasPrefilledProvince) cityCombo.disable();
        if (!hasPrefilledCity) brgyCombo.disable();

        loadPhData().then(function (data) {
            provinceCombo.setOptions(data.provinces);

            if (hasPrefilledProvince) {
                const provinceVal = provinceInput.value.trim();
                cityCombo.setOptions(data.cities[provinceVal] || []);
                if (!provinceInput.disabled) cityCombo.enable('Type or select city/municipality');

                if (hasPrefilledCity) {
                    const cityVal = cityInput.value.trim();
                    const key = provinceVal + '|' + cityVal;
                    brgyCombo.setOptions(data.barangays[key] || []);
                    if (!cityInput.disabled) brgyCombo.enable('Type or select barangay');
                }
            }
        });

        provinceCombo.onSelect(function (value, isSelection) {
            syncHidden();
            if (!isSelection) { notifyChanged(); return; }

            const cities = (phData && phData.cities[value]) || [];
            cityCombo.setOptions(cities);
            cityCombo.enable('Type or select city/municipality');
            cityCombo.clear();
            brgyCombo.clear();
            brgyCombo.disable();
            syncHidden();
            notifyChanged();
        });

        cityCombo.onSelect(function (value, isSelection) {
            syncHidden();
            if (!isSelection) { notifyChanged(); return; }

            const provinceVal = provinceInput.value.trim();
            const key = provinceVal + '|' + value;
            const brgys = (phData && phData.barangays[key]) || [];
            brgyCombo.setOptions(brgys);
            brgyCombo.enable('Type or select barangay');
            brgyCombo.clear();
            syncHidden();
            notifyChanged();
        });

        brgyCombo.onSelect(function () {
            syncHidden();
            notifyChanged();
        });

        if (streetInput) {
            streetInput.addEventListener('input', function () {
                syncHidden();
                notifyChanged();
            });
        }

        // syncHidden();

        const groupApi = {
            getParts: function () {
                return {
                    province: provinceInput.value.trim(),
                    city: cityInput.value.trim(),
                    barangay: brgyInput.value.trim(),
                    street: streetInput ? streetInput.value.trim() : ''
                };
            },

            /**
             * Populates this group's fields from a parts object.
             * Always re-enables city/barangay according to what's being set
             * (so copied values are immediately visible and correct),
             * regardless of the group's current disabled state — the caller
             * is responsible for re-applying setDisabled() afterward if needed.
             */
            setParts: function (parts) {
                parts = parts || {};

                provinceInput.value = parts.province || '';

                if (parts.province && phData) {
                    cityCombo.setOptions(phData.cities[parts.province] || []);
                    cityCombo.enable('Type or select city/municipality');
                } else if (!parts.province) {
                    cityCombo.setOptions([]);
                    cityCombo.disable();
                }

                cityInput.value = parts.city || '';

                if (parts.province && parts.city && phData) {
                    const key = parts.province + '|' + parts.city;
                    brgyCombo.setOptions(phData.barangays[key] || []);
                    brgyCombo.enable('Type or select barangay');
                } else if (!parts.city) {
                    brgyCombo.setOptions([]);
                    brgyCombo.disable();
                }

                brgyInput.value = parts.barangay || '';

                if (streetInput) streetInput.value = parts.street || '';
                if (hiddenStreet) hiddenStreet.value = parts.street || '';

                syncHidden();
            },

            /**
             * Enables or disables all fields in this group. Used for the
             * "Same as Current Address" checkbox to gray out fields that
             * are being auto-copied.
             */
            setDisabled: function (disabled) {
                provinceInput.disabled = disabled;
                // Only force-enable city/barangay if NOT disabling AND they
                // actually have a valid parent selection — otherwise leave
                // them in their natural cascade-locked state.
                if (disabled) {
                    cityInput.disabled = true;
                    brgyInput.disabled = true;
                } else {
                    cityInput.disabled = provinceInput.value.trim() === '';
                    brgyInput.disabled = cityInput.value.trim() === '';
                }
                if (streetInput) streetInput.disabled = disabled;
            }
        };

        window.TricreteAddressGroups[prefix] = groupApi;
        return groupApi;
    }

    // ----------------------------------------------------------
    // Initialize on page load
    // ----------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function () {

        const knownPrefixes = [
            'current_address', 'provincial_address',
            'spouse_address', 'parents_address',
            'ec_address', 'ref_address'
        ];

        knownPrefixes.forEach(function (prefix) {
            if (document.getElementById(prefix + '_province_input')) {
                initAddressGroup(prefix);
            }
        });

        // ----------------------------------------------------------
        // "Same as Current Address" checkboxes — Spouse & Parents.
        // Checked by default on create. While checked, any change to
        // current_address (province/city/barangay/street) live-copies
        // into the target group and keeps it disabled (read-only look).
        // ----------------------------------------------------------
        function wireCopyCheckbox(checkboxId, targetPrefix) {
            const checkbox = document.getElementById(checkboxId);
            if (!checkbox) return;

            const target = window.TricreteAddressGroups[targetPrefix];
            const current = window.TricreteAddressGroups['current_address'];

            if (!target || !current) return;

            function applyCopy() {
                const parts = current.getParts();
                target.setParts(parts);
                target.setDisabled(true);
            }

            function release() {
                target.setDisabled(false);
            }

            checkbox.addEventListener('change', function () {
                if (checkbox.checked) {
                    applyCopy();
                } else {
                    release();
                }
            });

            // Live-sync: whenever the Current Address group changes,
            // re-copy into the target IF the checkbox is still checked.
            document.addEventListener('ts:address-changed', function (e) {
                if (e.detail && e.detail.prefix === 'current_address' && checkbox.checked) {
                    applyCopy();
                }
            });

            // Apply immediately on page load if checked (covers the
            // create-employee default state, even though current_address
            // is empty at that point — this just keeps fields disabled/synced).
            if (checkbox.checked) {
                applyCopy();
            }
        }

        wireCopyCheckbox('same_as_current_provincial', 'provincial_address');
        wireCopyCheckbox('same_as_current_spouse', 'spouse_address');
        wireCopyCheckbox('same_as_current_parents', 'parents_address');

    });

    window.TricreteAddress = {
        initAddressGroup: initAddressGroup,
        loadPhData: loadPhData
    };

})();