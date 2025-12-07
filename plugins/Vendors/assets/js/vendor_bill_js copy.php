<?php
// defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Emits:
 *   - window.VB_DATA (dropdown maps + existing lines)
 *   - window.VB (URLs + i18n)
 *   - <script src="vendor_bill.core.js">
 */

$encode = function ($v) {
    return json_encode(
        $v ?? [],
        JSON_UNESCAPED_SLASHES
        | JSON_UNESCAPED_UNICODE
        | JSON_HEX_TAG
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
        | JSON_HEX_AMP
    );
};

$version = (string) get_setting('app_version');

$vbData = [
    'items'    => isset($items_dropdown)    && is_array($items_dropdown)    ? $items_dropdown    : [],
    'accounts' => isset($accounts_dropdown) && is_array($accounts_dropdown) ? $accounts_dropdown : [],
    'projects' => isset($projects_dropdown) && is_array($projects_dropdown) ? $projects_dropdown : [],
    'lines'    => isset($existing_lines) ? $existing_lines : [],
];

$vb = [
    'urls' => [
        'getPhases'   => get_uri('vendor_bills/get_phases_dropdown'),
        'getItemInfo' => get_uri('vendor_bills/get_item_info'),
        'index'       => get_uri('vendor_bills'),
    ],
    'i18n' => [
        'required'        => app_lang('field_required') ?: 'This field is required',
        'qty_gt_zero'     => 'Quantity must be greater than 0',
        'price_negative'  => "Unit price can’t be negative",
        'phase_ph'        => '- ' . (app_lang('phase') ?: 'Phase') . ' -',
    ],
];
?>
<script>
  window.VB_DATA = <?php echo $encode($vbData); ?>;
  window.VB      = <?php echo $encode($vb); ?>;
</script>
<script src="<?php echo base_url('plugins/Vendors/assets/js/vendor_bill.core.js?v=' . $version); ?>"></script>

<?php
// defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Emits:
 * - window.VB_DATA (dropdown maps + existing lines)
 * - window.VB (URLs + i18n)
 * - <script src="vendor_bill.core.js">
 */

$encode = function ($v) {
    return json_encode(
        $v ?? [],
        JSON_UNESCAPED_SLASHES
        | JSON_UNESCAPED_UNICODE
        | JSON_HEX_TAG
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
        | JSON_HEX_AMP
    );
};

$version = (string) get_setting('app_version');

$vbData = [
    'items'    => isset($items_dropdown)    && is_array($items_dropdown)    ? $items_dropdown    : [],
    'accounts' => isset($accounts_dropdown) && is_array($accounts_dropdown) ? $accounts_dropdown : [],
    'projects' => isset($projects_dropdown) && is_array($projects_dropdown) ? $projects_dropdown : [],
    'lines'    => isset($existing_lines) ? $existing_lines : [],
];

$vb = [
    'urls' => [
        'getPhases'   => get_uri('vendor_bills/get_phases_dropdown'),
        'getItemInfo' => get_uri('vendor_bills/get_item_info'),
        'index'       => get_uri('vendor_bills'),
    ],
    'i18n' => [
        'required'        => app_lang('field_required') ?: 'This field is required',
        'qty_gt_zero'     => 'Quantity must be greater than 0',
        'price_negative'  => "Unit price can’t be negative",
        'phase_ph'        => '- ' . (app_lang('phase') ?: 'Phase') . ' -',
    ],
];
?>
<script>
  window.VB_DATA = <?php echo $encode($vbData); ?>;
  window.VB      = <?php echo $encode($vb); ?>;
</script>
<script src="<?php echo base_url('plugins/Vendors/assets/js/vendor_bill.core.js?v=' . $version); ?>"></script>
