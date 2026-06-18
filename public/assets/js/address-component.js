/**
 * Tricrete Systems — Philippine Address Combobox Component
 * Cascading Province -> City/Municipality -> Barangay, each typeable with suggestions.
 * Final concatenated string is written into a hidden input for form submission.
 */

(function () {

    let phData = null;
    let phDataPromise = null;

    function loadPhData() {
        if (phDataPromise) return phDataPromise;
        const dataUrl = (window.TS_BASE_URL || '') + 'assets/data/ph_locations.json';
        phDataPromise = fetch(dataUrl)
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('Failed to load ph_locations.json: ' + res.status);
                }
                return res.json();
            })
            .then(function (data) { phData = data; return data; });
        return phDataPromise;
    }

    /**
     * Creates a typeable combobox: a text input + a datalist-like suggestion dropdown.
     * Returns an object with .setOptions(arr), .getValue(), .setValue(str), .onSelect(cb)
     */
    function createCombobox(inputEl, listEl) {
        let options = [];
        let onSelectCb = null;

        function renderSuggestions(filter) {
            const f = filter.trim().toUpperCase();
            const matches = f === ''
                ? options.slice(0, 50)
                : options.filter(o => o.toUpperCase().includes(f)).slice(0, 50);

            if (matches.length === 0) {
                listEl.innerHTML = '<div class="ts-combo-empty">No matches</div>';
                listEl.classList.add('show');
                return;
            }

            listEl.innerHTML = matches.map(function (opt) {
                return '<div class="ts-combo-item" data-value="' + opt.replace(/"/g, '&quot;') + '">' + opt + '</div>';
            }).join('');
            listEl.classList.add('show');
        }

        inputEl.addEventListener('focus', function () {
            renderSuggestions(inputEl.value);
        });

        inputEl.addEventListener('input', function () {
            renderSuggestions(inputEl.value);
            if (onSelectCb) onSelectCb(inputEl.value, false);
        });

        inputEl.addEventListener('blur', function () {
            setTimeout(function () { listEl.classList.remove('show'); }, 150);
        });

        listEl.addEventListener('mousedown', function (e) {
            const item = e.target.closest('.ts-combo-item');
            if (!item) return;
            e.preventDefault();
            const value = item.getAttribute('data-value');
            inputEl.value = value;
            listEl.classList.remove('show');
            if (onSelectCb) onSelectCb(value, true);
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
                inputEl.placeholder = placeholder || '';
            }
        };
    }

    /**
     * Initializes a full address group (province + city + barangay + street + hidden concat field)
     * @param {string} prefix - e.g. "city_address" or "provincial_address"
     */
    function initAddressGroup(prefix) {
        const provinceInput = document.getElementById(prefix + '_province_input');
        const provinceList = document.getElementById(prefix + '_province_list');
        const cityInput = document.getElementById(prefix + '_city_input');
        const cityList = document.getElementById(prefix + '_city_list');
        const brgyInput = document.getElementById(prefix + '_barangay_input');
        const brgyList = document.getElementById(prefix + '_barangay_list');
        const streetInput = document.getElementById(prefix + '_street_input');
        const hiddenField = document.getElementById(prefix);

        if (!provinceInput || !cityInput || !brgyInput || !streetInput || !hiddenField) {
            return null;
        }

        const provinceCombo = createCombobox(provinceInput, provinceList);
        const cityCombo = createCombobox(cityInput, cityList);
        const brgyCombo = createCombobox(brgyInput, brgyList);

        cityCombo.disable();
        brgyCombo.disable();

        function updateHiddenField() {
            const parts = [
                streetInput.value.trim(),
                brgyInput.value.trim(),
                cityInput.value.trim(),
                provinceInput.value.trim()
            ].filter(function (p) { return p.length > 0; });
            hiddenField.value = parts.join(', ');
        }

        loadPhData().then(function (data) {
            provinceCombo.setOptions(data.provinces);
        });

        provinceCombo.onSelect(function (value, isSelection) {
            updateHiddenField();
            if (!isSelection) return;

            const cities = (phData.cities[value] || []);
            cityCombo.setOptions(cities);
            cityCombo.enable('Type or select city/municipality');
            cityCombo.clear();
            brgyCombo.clear();
            brgyCombo.disable();
            updateHiddenField();
        });

        cityCombo.onSelect(function (value, isSelection) {
            updateHiddenField();
            if (!isSelection) return;

            const provinceVal = provinceInput.value.trim();
            const key = provinceVal + '|' + value;
            const brgys = (phData.barangays[key] || []);
            brgyCombo.setOptions(brgys);
            brgyCombo.enable('Type or select barangay');
            brgyCombo.clear();
            updateHiddenField();
        });

        brgyCombo.onSelect(function () {
            updateHiddenField();
        });

        streetInput.addEventListener('input', updateHiddenField);

        return {
            setFromString: function () {
                // Used when "same as" checkbox copies values across groups
            },
            getParts: function () {
                return {
                    province: provinceInput.value.trim(),
                    city: cityInput.value.trim(),
                    barangay: brgyInput.value.trim(),
                    street: streetInput.value.trim()
                };
            },
            setParts: function (parts) {
                provinceInput.value = parts.province || '';
                if (parts.province && phData) {
                    cityCombo.setOptions(phData.cities[parts.province] || []);
                    cityCombo.enable('Type or select city/municipality');
                }
                cityInput.value = parts.city || '';
                if (parts.province && parts.city && phData) {
                    const key = parts.province + '|' + parts.city;
                    brgyCombo.setOptions(phData.barangays[key] || []);
                    brgyCombo.enable('Type or select barangay');
                }
                brgyInput.value = parts.barangay || '';
                streetInput.value = parts.street || '';
                updateHiddenField();
            },
            refresh: updateHiddenField
        };
    }

    // ----------------------------------------------------------
    // Initialize on page load
    // ----------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function () {

        const cityAddressGroup = initAddressGroup('city_address');
        const provincialAddressGroup = initAddressGroup('provincial_address');

        // "Same as City Address" checkbox
        const sameAsCheckbox = document.getElementById('same_as_city_address');
        if (sameAsCheckbox && cityAddressGroup && provincialAddressGroup) {
            sameAsCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    const parts = cityAddressGroup.getParts();
                    provincialAddressGroup.setParts(parts);
                    document.getElementById('provincial_address_fieldset')
                        ?.classList.add('ts-disabled-overlay');
                } else {
                    document.getElementById('provincial_address_fieldset')
                        ?.classList.remove('ts-disabled-overlay');
                }
            });
        }

        // Initialize for any other address groups present on the page (e.g. spouse_address, parents_address)
        ['spouse_address', 'parents_address'].forEach(function (prefix) {
            if (document.getElementById(prefix + '_province_input')) {
                initAddressGroup(prefix);
            }
        });

    });

    window.TricreteAddress = { initAddressGroup: initAddressGroup, loadPhData: loadPhData };

})();