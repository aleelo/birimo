<?php

/**
 * Vendor Bill – create/edit view (MODERN DESIGN)
 * IDs/field names preserved: vendor-bill-form, vendor_id, accounting_date, bill_date,
 * items_json, bill-lines, subtotal_cell, total_cell, add-line, etc.
 */

$mi = isset($model_info) ? $model_info : (object)[
  "id"              => "",
  "vendor_id"       => "",
  "accounting_date" => date("Y-m-d"),
  "bill_date"       => date("Y-m-d"),
];

function _options_from($map, $placeholder = "-")
{
  $out = "<option value=\"\">{$placeholder}</option>";
  if (is_array($map)) {
    foreach ($map as $k => $v) {
      $k = htmlspecialchars((string)$k);
      $v = htmlspecialchars((string)$v);
      $out .= "<option value=\"{$k}\">{$v}</option>";
    }
  }
  return $out;
}

$existing_lines = $existing_lines ?? [];
?>
<style>
  /* ===== MODERN VENDOR BILL FORM DESIGN ===== */
  :root {
    --primary: #2563eb;
    --primary-hover: #1d4ed8;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --bg: #f8fafc;
    --surface: #ffffff;
    --border: #e2e8f0;
    --text: #1e293b;
    --text-muted: #64748b;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --radius: 8px;
    --radius-lg: 12px;
  }

  body {
    background: var(--bg);
  }

  #page-content {
    padding: 20px;
  }

  #page-content .card {
    background: var(--surface);
    border: none;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    margin-bottom: 0;
  }

  /* Header */
  .form-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
    color: white;
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: none;
  }

  .form-header h4 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
  }

  /* Form Body */
  .form-body {
    padding: 2rem;
  }

  /* Section Styling */
  .form-section {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
  }

  .form-section:last-child {
    margin-bottom: 0;
  }

  .section-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--border);
  }

  .section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .section-title::before {
    content: '';
    width: 4px;
    height: 20px;
    background: var(--primary);
    border-radius: 2px;
  }

  /* Form Groups */
  #vendor-bill-form .form-group {
    margin-bottom: 1.25rem;
  }

  #vendor-bill-form label {
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    display: block;
  }

  /* Required field indicator */
  #vendor-bill-form label.required::after {
    content: " *";
    color: var(--danger);
    font-weight: 700;
    margin-left: 2px;
  }

  /* Validation error styling */
  #vendor-bill-form .form-control.is-invalid,
  #vendor-bill-form .select2-container .select2-selection.is-invalid {
    border-color: var(--danger) !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
  }

  #vendor-bill-form .select2-container--default .select2-selection.is-invalid {
    border-color: var(--danger) !important;
  }

  #vendor-bill-form .invalid-feedback,
  #vendor-bill-form .field-error {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: var(--danger);
    font-weight: 500;
  }

  #vendor-bill-form .has-error label {
    color: var(--danger);
  }

  /* Table row validation */
  #bill-lines td .invalid-feedback {
    position: absolute;
    background: #fee2e2;
    color: var(--danger);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    z-index: 10;
    margin-top: 2px;
    white-space: nowrap;
  }

  #bill-lines td {
    position: relative;
  }

  /* Required field indicator animation */
  #vendor-bill-form label.required {
    position: relative;
  }

  /* Focus state for required fields */
  #vendor-bill-form .form-control:required:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  #vendor-bill-form .form-control {
    height: 42px;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    padding: 0 12px;
    font-size: 0.9375rem;
    transition: all 0.2s ease;
    background: var(--surface);
  }

  #vendor-bill-form .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
  }

  /* Select2 Styling */
  .select2-container {
    width: 100% !important;
  }

  .select2-container .select2-selection--single {
    height: 42px !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius) !important;
    transition: all 0.2s ease;
  }

  .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
  }

  .select2-selection__rendered {
    line-height: 42px !important;
    padding-left: 12px !important;
    color: var(--text) !important;
  }

  .select2-selection__arrow {
    height: 42px !important;
    right: 10px !important;
  }

  /* Table Styling */
  #bill-lines {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    background: var(--surface);
    box-shadow: var(--shadow);
  }

  #bill-lines thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  }

  #bill-lines thead th {
    padding: 1rem 0.75rem;
    font-weight: 600;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
  }

  #bill-lines tbody td {
    padding: 0.875rem 0.75rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    background: var(--surface);
  }

  #bill-lines tbody tr:last-child td {
    border-bottom: none;
  }

  #bill-lines tbody tr:hover {
    background: #f8fafc;
  }

  #bill-lines tbody tr:hover td {
    background: #f8fafc;
  }

  #bill-lines tfoot {
    background: #f8fafc;
    border-top: 2px solid var(--border);
  }

  #bill-lines tfoot td {
    padding: 1rem 0.75rem;
    font-weight: 600;
    font-size: 0.9375rem;
  }

  #bill-lines tfoot tr:last-child {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    font-size: 1rem;
  }

  #bill-lines tfoot tr:last-child td {
    padding: 1.25rem 0.75rem;
  }

  #subtotal_cell,
  #total_cell {
    font-variant-numeric: tabular-nums;
    font-size: 1.125rem;
    color: var(--text);
  }

  #total_cell {
    font-size: 1.25rem;
    color: var(--primary);
    font-weight: 700;
  }

  /* Table Inputs */
  #bill-lines input[type="text"],
  #bill-lines input[type="number"] {
    width: 100%;
    height: 38px;
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 0 10px;
    font-size: 0.875rem;
    background: var(--surface);
    transition: all 0.2s ease;
  }

  #bill-lines input[type="text"]:focus,
  #bill-lines input[type="number"]:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    outline: none;
  }

  #bill-lines .select2-container {
    width: 100% !important;
  }

  #bill-lines .select2-selection--single {
    height: 38px !important;
    border: 1px solid var(--border) !important;
    border-radius: 6px !important;
  }

  #bill-lines .select2-selection__rendered {
    line-height: 38px !important;
    padding-left: 10px !important;
  }

  #bill-lines .line-amount {
    font-weight: 600;
    color: var(--text);
    font-variant-numeric: tabular-nums;
  }

  /* Action Buttons */
  .btn {
    border-radius: var(--radius);
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
  }

  .btn-primary {
    background: var(--primary);
    color: white;
    box-shadow: var(--shadow-md);
  }

  .btn-primary:hover {
    background: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
  }

  .btn-default {
    background: var(--surface);
    color: var(--text);
    border: 1.5px solid var(--border);
  }

  .btn-default:hover {
    background: #f8fafc;
    border-color: var(--primary);
    color: var(--primary);
  }

  #add-line {
    margin-top: 1rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
  }

  /* Delete Button */
  .del-line {
    background: transparent;
    border: none;
    color: var(--danger);
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .del-line:hover {
    background: rgba(239, 68, 68, 0.1);
    transform: scale(1.1);
  }

  /* Footer Actions */
  .form-footer {
    background: var(--surface);
    border-top: 1px solid var(--border);
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .form-body {
      padding: 1rem;
    }

    .form-section {
      padding: 1rem;
    }

    #bill-lines {
      font-size: 0.8125rem;
    }

    #bill-lines thead th,
    #bill-lines tbody td {
      padding: 0.5rem;
    }
  }

  /* Text Alignment */
  .text-right {
    text-align: right;
  }

  .text-center {
    text-align: center;
  }

  /* Column Widths */
  #bill-lines th:nth-child(1),
  #bill-lines td:nth-child(1) {
    width: 40px;
  }

  #bill-lines th:nth-child(2),
  #bill-lines td:nth-child(2) {
    width: 18%;
  }

  #bill-lines th:nth-child(3),
  #bill-lines td:nth-child(3) {
    width: 15%;
  }

  #bill-lines th:nth-child(4),
  #bill-lines td:nth-child(4) {
    width: 20%;
  }

  #bill-lines th:nth-child(5),
  #bill-lines td:nth-child(5),
  #bill-lines th:nth-child(6),
  #bill-lines td:nth-child(6),
  #bill-lines th:nth-child(7),
  #bill-lines td:nth-child(7) {
    width: 10%;
  }

  /* Delete button column */
  #bill-lines th:nth-child(8),
  #bill-lines td:nth-child(8) {
    width: 50px;
    text-align: center;
  }
</style>

<div id="page-content" class="page-wrapper clearfix">
  <div class="card clearfix">
    <div class="form-header">
      <h4>
        <i data-feather="file-text" class="icon-16"></i>
        <?php echo app_lang("vendor_bills") ?: "Vendor Bills"; ?>
      </h4>
    </div>

    <?php echo form_open(get_uri("vendor_bills/save"), ["id" => "vendor-bill-form", "class" => "general-form", "role" => "form"]); ?>

    <div class="form-body">
      <input type="hidden" name="id" value="<?php echo $mi->id; ?>">
      <input type="hidden" name="items" id="items_json" value="[]">

      <!-- Details Section -->
      <div class="form-section">
        <div class="section-header">
          <h5 class="section-title"><?php echo app_lang("details") ?: "Details"; ?></h5>
        </div>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="vendor_id" class="required">
              <i data-feather="user" class="icon-14"></i>
              <?php echo app_lang("vendor"); ?>
            </label>
            <select id="vendor_id" name="vendor_id"
              class="form-control select2"
              data-rule-required="true"
              data-msg-required="<?php echo app_lang("field_required"); ?>">
              <?php
              echo _options_from($vendors_dropdown ?? [], "- " . app_lang("vendor") . " -");
              if ($mi->vendor_id !== "") {
                echo "<script>document.addEventListener('DOMContentLoaded',function(){ $('#vendor_id').val('" . htmlspecialchars((string)$mi->vendor_id) . "'); });</script>";
              }
              ?>
            </select>
          </div>

          <div class="form-group col-md-6">
            <label for="accounting_date" class="required">
              <i data-feather="calendar" class="icon-14"></i>
              <?php echo app_lang("accounting_date"); ?>
            </label>
            <input id="accounting_date" name="accounting_date" type="date"
              class="form-control"
              value="<?php echo htmlspecialchars($mi->accounting_date ?: date('Y-m-d')); ?>"
              data-rule-required="true"
              data-msg-required="<?php echo app_lang("field_required"); ?>">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="bill_date" class="required">
              <i data-feather="calendar" class="icon-14"></i>
              <?php echo app_lang("bill_date"); ?>
            </label>
            <input id="bill_date" name="bill_date" type="date"
              class="form-control"
              value="<?php echo htmlspecialchars($mi->bill_date ?: date('Y-m-d')); ?>"
              data-rule-required="true"
              data-msg-required="<?php echo app_lang("field_required"); ?>">
          </div>

          <div class="form-group col-md-6">
            <label for="invoice_id">
              <i data-feather="file-text" class="icon-14"></i>
              <?php echo app_lang("invoice") ?: "Invoice"; ?>
            </label>
            <select id="invoice_id" name="invoice_id"
              class="form-control select2">
              <?php
              echo _options_from($invoices_dropdown ?? [], "- " . (app_lang("invoice") ?: "Invoice") . " -");
              if (isset($mi->invoice_id) && $mi->invoice_id !== "") {
                echo "<script>document.addEventListener('DOMContentLoaded',function(){ $('#invoice_id').val('" . htmlspecialchars((string)$mi->invoice_id) . "'); });</script>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="invoice_total_display">
              <i data-feather="dollar-sign" class="icon-14"></i>
              <?php echo app_lang("invoice_total") ?: "Invoice Total"; ?>
            </label>
            <input id="invoice_total_display"
              type="text"
              class="form-control"
              readonly
              value="0.00"
              style="background-color: #f8fafc; font-weight: 600; color: var(--primary);">
          </div>
        </div>
      </div>

      <!-- Line Items Section -->
      <div class="form-section">
        <div class="section-header">
          <h5 class="section-title"><?php echo app_lang("line_items") ?: "Line Items"; ?></h5>
        </div>

        <div class="table-responsive">
          <table class="table mb10" id="bill-lines">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th><?php echo app_lang("item"); ?></th>
                <th><?php echo app_lang("account"); ?></th>
                <th><?php echo app_lang("description"); ?></th>
                <th class="text-right"><?php echo app_lang("quantity"); ?></th>
                <th class="text-right"><?php echo app_lang("unit_price"); ?></th>
                <th class="text-right"><?php echo app_lang("amount"); ?></th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr>
                <td colspan="6" class="text-right" style="font-weight: 600;">
                  <?php echo app_lang('subtotal') ?: 'Subtotal'; ?>:
                </td>
                <td class="text-right" id="subtotal_cell">0</td>
                <td></td>
              </tr>
              <tr>
                <td colspan="6" class="text-right" style="font-weight: 700; font-size: 1rem;">
                  <?php echo app_lang('total') ?: 'Total'; ?>:
                </td>
                <td class="text-right" id="total_cell">0</td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <button type="button" class="btn btn-default" id="add-line">
          <i data-feather="plus-circle" class="icon-16"></i>
          <?php echo app_lang('add_line') ?: "Add Line Item"; ?>
        </button>
      </div>
    </div>

    <div class="form-footer">
      <button type="submit" class="btn btn-primary">
        <i data-feather="check-circle" class="icon-16"></i>
        <?php echo app_lang('save'); ?>
      </button>
    </div>

    <?php echo form_close(); ?>
  </div>
</div>

<?php
// JS bootstrap
$existing_lines_json = json_encode($existing_lines ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
require FCPATH . 'plugins/Vendors/assets/js/vendor_bill_js.php';
?>
<script>
  window.VB_DATA = window.VB_DATA || {};
  window.VB_DATA.lines = <?php echo $existing_lines_json; ?>;
  document.addEventListener('DOMContentLoaded', function() {
    if (window.feather) feather.replace();

    // Load invoice total if invoice is pre-selected
    <?php if (isset($mi->invoice_id) && $mi->invoice_id !== ""): ?>
      setTimeout(function() {
        if ($('#invoice_id').val()) {
          $('#invoice_id').trigger('change');
        }
      }, 500);
    <?php endif; ?>
  });
</script>