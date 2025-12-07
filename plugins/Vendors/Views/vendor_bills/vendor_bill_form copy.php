<?php
/**
 * Vendor Bill – create/edit view (PRO style)
 * IDs/field names preserved: vendor-bill-form, vendor_id, accounting_date, bill_date,
 * items_json, bill-lines, subtotal_cell, total_cell, add-line, etc.
 */

$mi = isset($model_info) ? $model_info : (object)[
  "id"              => "",
  "vendor_id"       => "",
  "accounting_date" => date("Y-m-d"),
  "bill_date"       => date("Y-m-d"),
];

function _options_from($map, $placeholder = "-") {
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
  /* ===== PRO THEME (subtle, enterprise) ===== */
  :root{
    --pro-bg:#f6f7fb;
    --pro-surface:#ffffff;
    --pro-text:#0f172a;
    --pro-muted:#6b7280;
    --pro-border:#e6e8ee;
    --pro-strong:#cfd3de;
    --pro-accent:#2b6aea;             /* action color */
    --pro-accent-2:#1e40af;
    --pro-r-lg:16px;
    --pro-r:12px;
    --pro-shadow:0 8px 24px rgba(17,24,39,.06);
  }
  body{ background:var(--pro-bg); }

  #page-content .card{
    background:var(--pro-surface);
    border:1px solid var(--pro-border);
    border-radius:var(--pro-r-lg);
    box-shadow:var(--pro-shadow);
  }

  /* Pro header strip */
  .pro-header{
    display:flex; align-items:center; gap:12px;
    padding:16px 18px;
    border-bottom:1px solid var(--pro-border);
    background:linear-gradient(180deg,#fff,rgba(255,255,255,.96));
    border-top-left-radius:var(--pro-r-lg);
    border-top-right-radius:var(--pro-r-lg);
  }
  .pro-title{
    margin:0; font-weight:800; color:var(--pro-text); display:flex; align-items:center; gap:10px;
  }
  .pro-sub{ color:var(--pro-muted); font-size:.9rem; margin-left:4px; }
  .pro-actions{ margin-left:auto; display:flex; gap:8px; }
  .pro-actions .btn{ border-radius:10px; }

  /* Body spacing */
  .pro-body{ padding:16px 18px; }

  /* Fieldset look */
  .pro-section{
    border:1px solid var(--pro-border);
    border-radius:12px;
    padding:12px;
    margin-bottom:12px;
    background:#fff;
  }
  .pro-section .section-title{
    font-weight:700; color:#334155; margin:0 0 8px 0; font-size:.95rem;
  }

  /* Form controls */
  #vendor-bill-form label{ font-weight:600; color:#5b6472; margin-bottom:6px; }
  #vendor-bill-form .form-control{
    height:40px; border-radius:10px; border:1px solid var(--pro-border);
  }
  /* Select2 */
  .select2-container .select2-selection--single{
    height:40px !important; border-radius:10px !important; border:1px solid var(--pro-border) !important;
    transition:border-color .15s ease;
  }
  .select2-selection__rendered{ line-height:40px !important; padding-left:.7rem; }
  .select2-selection__arrow{ height:40px !important; }

  /* Lines table (crisp) */
  #bill-lines{
    border:1px solid var(--pro-strong);
    border-radius:10px;
    overflow:hidden;
    background:#fff;
  }
  #bill-lines thead th{
    background:#f2f4f8; color:#364152; font-weight:700;
    border-bottom:1px solid var(--pro-strong);
    padding:.55rem .6rem;
  }
  #bill-lines td{
    padding:.55rem .6rem; vertical-align:middle; border-top:1px solid var(--pro-border);
  }
  #bill-lines tbody tr:hover{ background:#f7f9fc; }
  #bill-lines tfoot td{
    background:#f7f8fb; border-top:1px solid var(--pro-strong);
    font-weight:700;
  }
  #subtotal_cell, #total_cell{ font-variant-numeric:tabular-nums; }

  /* Dimensions helpers */
  .w17p{width:17%;} .w18p{width:18%;} .w14p{width:14%;} .w10p{width:10%;}

  /* Buttons */
  .btn-primary{
    background:var(--pro-accent); border-color:var(--pro-accent); border-radius:10px;
  }
  .btn-primary:hover{ background:var(--pro-accent-2); border-color:var(--pro-accent-2); }
  .btn-default{ border-radius:10px; }

  /* Sticky bottom bar for actions (professional feel) */
  .pro-sticky{
    position:sticky; bottom:0; z-index:5;
    background:linear-gradient(180deg, rgba(255,255,255,.7), #fff);
    border-top:1px solid var(--pro-border);
    padding:10px 16px;
    display:flex; justify-content:flex-end; gap:10px;
    border-bottom-left-radius:var(--pro-r-lg);
    border-bottom-right-radius:var(--pro-r-lg);
  }

  /* Subtle focus state for inputs */
  #vendor-bill-form .form-control:focus,
  .select2-container--default.select2-container--open .select2-selection--single{
    border-color:#c7d2fe !important;
    box-shadow:0 0 0 .12rem rgba(43,106,234,.15);
    outline:0;
  }
</style>

<div id="page-content" class="page-wrapper clearfix">
  <div class="card clearfix">

    <!-- PRO header -->
    <div class="pro-header">
      <h4 class="pro-title">
        <span data-feather="file-text" class="icon-16"></span>
        <?php echo app_lang("vendor_bills"); ?>
      </h4>
      <div class="pro-sub"><?php echo app_lang("create_or_edit") ?: "Create or edit a vendor bill"; ?></div>
      <div class="pro-actions">
        <!-- optional secondary action could go here -->
      </div>
    </div>

    <?php echo form_open(get_uri("vendor_bills/save"), ["id" => "vendor-bill-form", "class" => "general-form", "role" => "form"]); ?>

      <div class="pro-body">

        <input type="hidden" name="id" value="<?php echo $mi->id; ?>">
        <input type="hidden" name="items" id="items_json" value="[]">

        <!-- Details section -->
        <div class="pro-section">
          <p class="section-title"><?php echo app_lang("details") ?: "Details"; ?></p>
          <div class="row">
            <div class="form-group col-md-4">
              <label for="vendor_id"><?php echo app_lang("vendor"); ?></label>
              <select id="vendor_id" name="vendor_id"
                      class="form-control select2"
                      data-rule-required="true"
                      data-msg-required="<?php echo app_lang("field_required"); ?>">
                <?php
                  echo _options_from($vendors_dropdown ?? [], "- " . app_lang("vendor") . " -");
                  if ($mi->vendor_id !== "") {
                    echo "<script>document.addEventListener('DOMContentLoaded',function(){ $('#vendor_id').val('".htmlspecialchars((string)$mi->vendor_id)."').trigger('change'); });</script>";
                  }
                ?>
              </select>
            </div>

            <div class="form-group col-md-4">
              <label for="accounting_date"><?php echo app_lang("accounting_date"); ?></label>
              <input id="accounting_date" name="accounting_date" type="date"
                     class="form-control"
                     value="<?php echo htmlspecialchars($mi->accounting_date ?: date('Y-m-d')); ?>"
                     data-rule-required="true"
                     data-msg-required="<?php echo app_lang("field_required"); ?>">
            </div>

            <div class="form-group col-md-4">
              <label for="bill_date"><?php echo app_lang("bill_date"); ?></label>
              <input id="bill_date" name="bill_date" type="date"
                     class="form-control"
                     value="<?php echo htmlspecialchars($mi->bill_date ?: date('Y-m-d')); ?>"
                     data-rule-required="true"
                     data-msg-required="<?php echo app_lang("field_required"); ?>">
            </div>
          </div>
        </div>

        <!-- Lines section -->
        <div class="pro-section">
          <p class="section-title"><?php echo app_lang("line_items") ?: "Line items"; ?></p>

          <div class="table-responsive">
            <table class="table table-bordered mb10" id="bill-lines">
              <thead>
                <tr>
                  <th class="w50 text-center">#</th>
                  <th class="w17p"><?php echo app_lang("item"); ?></th>
                  <th class="w17p"><?php echo app_lang("account"); ?></th>
                  <th class="w18p"><?php echo app_lang("description"); ?></th>
                  <th class="w10p text-right"><?php echo app_lang("quantity"); ?></th>
                  <th class="w10p text-right"><?php echo app_lang("unit_price"); ?></th>
                  <th class="w10p text-right"><?php echo app_lang("amount"); ?></th>
                  <th class="w14p"><?php echo app_lang("project"); ?></th>
                  <th class="w14p"><?php echo app_lang("phase"); ?></th>
                  <th class="w50 text-center"></th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr>
                  <td colspan="6" class="text-right strong"><?php echo app_lang('subtotal') ?: 'Subtotal'; ?> :</td>
                  <td class="text-right" id="subtotal_cell">0.00</td>
                  <td colspan="3"></td>
                </tr>
                <tr>
                  <td colspan="6" class="text-right strong"><?php echo app_lang('total') ?: 'Total'; ?> :</td>
                  <td class="text-right" id="total_cell">0.00</td>
                  <td colspan="3"></td>
                </tr>
              </tfoot>
            </table>
          </div>

          <button type="button" class="btn btn-default mt5" id="add-line">
            <span data-feather="plus-circle" class="icon-16"></span>
            <?php echo app_lang('add_line') ?: "Add line"; ?>
          </button>
        </div>

      </div>

      <!-- Sticky bottom action -->
      <div class="pro-sticky">
        <button type="submit" class="btn btn-primary">
          <span data-feather="check-circle" class="icon-16"></span>
          <?php echo app_lang('save'); ?>
        </button>
      </div>

    <?php echo form_close(); ?>
  </div>
</div>

<?php
  // JS bootstrap (unchanged)
  $existing_lines_json = json_encode($existing_lines ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  require FCPATH . 'plugins/Vendors/assets/js/vendor_bill_js.php';
?>
<script>
  window.VB_DATA = window.VB_DATA || {};
  window.VB_DATA.lines = <?php echo $existing_lines_json; ?>;
  document.addEventListener('DOMContentLoaded', function () {
    if (window.feather) feather.replace();
  });
</script>
