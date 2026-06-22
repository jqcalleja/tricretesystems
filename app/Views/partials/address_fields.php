<?php

/**
 * @var string      $prefix      REQUIRED. Field prefix, e.g. "current_address",
 *                                "provincial_address", "spouse_address",
 *                                "parents_address", "ec_address", "ref_address".
 *                                Must be explicitly passed on every include() call
 *                                — there is NO fallback default, by design, to
 *                                prevent silent prefix collisions between multiple
 *                                address groups rendered on the same page.
 * @var string      $label       Display label, e.g. "Current Address"
 * @var array|null  $values      ['province' => '', 'city' => '', 'barangay' => '', 'street' => '']
 * @var bool        $showLabel   Whether to render the outer <label> (default true)
 * @var bool        $disabled    Whether all fields should render disabled (used for "same as" copy mode)
 */

// Fail loudly instead of silently defaulting — a missing prefix means a bug
// upstream in the calling view that must be fixed, not papered over.
if (empty($prefix)) {
    throw new \RuntimeException(
        'address_fields partial was included without a required "prefix" value. '
            . 'Check the $this->include(\'partials/address_fields\', [...]) call site.'
    );
}

$label     = $label ?? 'Address';
$values    = $values ?? ['province' => '', 'city' => '', 'barangay' => '', 'street' => ''];
$showLabel = $showLabel ?? true;
$disabled  = $disabled ?? false;
$disAttr   = $disabled ? 'disabled' : '';
?>
<div class="address-fieldset" data-prefix="<?= esc($prefix) ?>" id="<?= esc($prefix) ?>_fieldset">
    <?php if ($showLabel): ?>
        <label class="ts-form-label"><?= esc($label) ?></label>
    <?php endif; ?>
    <div class="row g-2">
        <div class="col-12 col-sm-4">
            <div class="ts-address-group-label">Province</div>
            <div class="ts-combo-wrap">
                <input type="text" id="<?= esc($prefix) ?>_province_input"
                    class="form-control form-control-sm ts-combo-input"
                    value="<?= esc($values['province'] ?? '') ?>"
                    placeholder="Type or select province" autocomplete="off"
                    <?= $disAttr ?>>
                <div id="<?= esc($prefix) ?>_province_list" class="ts-combo-list"></div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="ts-address-group-label">City / Municipality</div>
            <div class="ts-combo-wrap">
                <input type="text" id="<?= esc($prefix) ?>_city_input"
                    class="form-control form-control-sm ts-combo-input"
                    value="<?= esc($values['city'] ?? '') ?>"
                    <?= empty($values['province']) ? 'disabled' : $disAttr ?>
                    placeholder="<?= empty($values['province']) ? 'Select province first' : 'Type or select city/municipality' ?>"
                    autocomplete="off">
                <div id="<?= esc($prefix) ?>_city_list" class="ts-combo-list"></div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="ts-address-group-label">Barangay</div>
            <div class="ts-combo-wrap">
                <input type="text" id="<?= esc($prefix) ?>_barangay_input"
                    class="form-control form-control-sm ts-combo-input"
                    value="<?= esc($values['barangay'] ?? '') ?>"
                    <?= empty($values['city']) ? 'disabled' : $disAttr ?>
                    placeholder="<?= empty($values['city']) ? 'Select city first' : 'Type or select barangay' ?>"
                    autocomplete="off">
                <div id="<?= esc($prefix) ?>_barangay_list" class="ts-combo-list"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="ts-address-group-label">House No. / Street / Subdivision</div>
            <input type="text" id="<?= esc($prefix) ?>_street_input"
                class="form-control form-control-sm"
                value="<?= esc($values['street'] ?? '') ?>"
                placeholder="e.g. 123 Mabini St., Greenview Subd."
                <?= $disAttr ?>>
            <!-- No name attribute on the visible input -->

            <input type="hidden" name="<?= esc($prefix) ?>_street"
                id="<?= esc($prefix) ?>_street"
                value="<?= esc($values['street'] ?? '') ?>">
        </div>
    </div>

    <!-- Hidden fields mapping 1:1 to province/city/barangay POST keys -->
    <input type="hidden" name="<?= esc($prefix) ?>_province" id="<?= esc($prefix) ?>_province"
        value="<?= esc($values['province'] ?? '') ?>">
    <input type="hidden" name="<?= esc($prefix) ?>_city" id="<?= esc($prefix) ?>_city"
        value="<?= esc($values['city'] ?? '') ?>">
    <input type="hidden" name="<?= esc($prefix) ?>_barangay" id="<?= esc($prefix) ?>_barangay"
        value="<?= esc($values['barangay'] ?? '') ?>">
</div>