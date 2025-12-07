<?php
// Expects in $view_data:
// - $model_info (obj) of vendor_bill_payment row (may be empty on create)
// - $vendor_bill_id (int|null) if modal is launched for a specific bill
// - $bills_dropdown (array id=>label) when $vendor_bill_id is not set
// - $payment_methods_dropdown (JSON for select2)
// - $amount (prefill suggested)
// - $login_user available from template

echo form_open(
    get_uri("vendor_bill_payment/save_payment"),
    ["id" => "vendor-bill-payment-form", "class" => "general-form", "role" => "form"]
);
?>
<div class="modal-body clearfix">
  <div class="container-fluid">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <?php if (!empty($vendor_bill_id)) { ?>
      <input type="hidden" name="vendor_bill_id" value="<?php echo $vendor_bill_id; ?>" />
    <?php } else { ?>
      <div class="form-group">
        <div class="row">
          <label for="vendor_bill_id" class="col-md-3"><?php echo app_lang('bill_number'); ?></label>
          <div class="col-md-9">
            <?php
            echo form_dropdown(
              "vendor_bill_id",
              $bills_dropdown ?? [],
              "",
              "class='select2 validate-hidden' id='vendor_bill_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"
            );
            ?>
          </div>
        </div>
      </div>
    <?php } ?>

    <div class="form-group">
      <div class="row">
        <label for="invoice_payment_method_id" class="col-md-3"><?php echo app_lang('payment_method'); ?></label>
        <div class="col-md-9">
          <?php
          echo form_input([
            "id"          => "invoice_payment_method_id",
            "name"        => "invoice_payment_method_id",   // keep name to match controller rules
            "value"       => $model_info->payment_method_id ?: get_cookie("user_" . $login_user->id . "_payment_method"),
            "class"       => "form-control selected_payment_method",
            "placeholder" => app_lang('payment_method')
          ]);
          ?>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <label for="invoice_payment_date" class="col-md-3"><?php echo app_lang('payment_date'); ?></label>
        <div class="col-md-9">
          <?php
          echo form_input([
            "id"                => "invoice_payment_date",
            "name"              => "invoice_payment_date",
            "value"             => $model_info->payment_date ?: get_my_local_time("Y-m-d"),
            "class"             => "form-control",
            "placeholder"       => app_lang('payment_date'),
            "autocomplete"      => "off",
            "data-rule-required"=> true,
            "data-msg-required" => app_lang("field_required")
          ]);
          ?>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <label for="invoice_payment_amount" class="col-md-3"><?php echo app_lang('amount'); ?></label>
        <div class="col-md-9">
          <?php
          echo form_input([
            "id"                => "invoice_payment_amount",
            "name"              => "invoice_payment_amount",
            "value"             => $amount,
            "class"             => "form-control",
            "placeholder"       => app_lang('amount'),
            "data-rule-required"=> true,
            "data-msg-required" => app_lang("field_required"),
          ]);
          ?>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <label for="invoice_payment_note" class="col-md-3"><?php echo app_lang('note'); ?></label>
        <div class="col-md-9">
          <?php
          echo form_textarea([
            "id"                    => "invoice_payment_note",
            "name"                  => "invoice_payment_note",
            "value"                 => $model_info->note ? process_images_from_content($model_info->note, false) : "",
            "class"                 => "form-control",
            "placeholder"           => app_lang('description'),
            "data-rich-text-editor" => true
          ]);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-bs-dismiss="modal">
    <span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?>
  </button>
  <button type="submit" class="btn btn-primary">
    <span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?>
  </button>
</div>
<?php echo form_close(); ?>

<script>
$(function () {
  $("#vendor-bill-payment-form").appForm({
    onSuccess: function (res) {
      if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
        location.reload();
      } else if ($("#invoice-payment-table").length) {
        $("#" + $(".dataTable:visible").attr("id")).appTable({ reload: true });
      }
    }
  });

  // payment method select2
  $("#invoice_payment_method_id").select2({ data: <?php echo $payment_methods_dropdown; ?> });

  setDatePicker("#invoice_payment_date");

  // remember payment method per-user
  $(".selected_payment_method").on("change", function () {
    var id = $(this).val();
    if (id) setCookie("user_" + "<?php echo $login_user->id; ?>" + "_payment_method", id);
  });

  // when picking a bill from dropdown, suggest due balance
  $("#vendor_bill_id").select2().on("change", function () {
    var billId = $(this).val();
    if (!billId) return;

    $.ajax({
      url: "<?php echo get_uri('vendor_bill_payment/get_bill_payment_amount_suggestion'); ?>/" + billId,
      type: "POST",
      dataType: "json",
      cache: false,
      success: function (resp) {
        if (resp && resp.success) {
          $("#invoice_payment_amount").val(resp.balance_due);
        }
      }
    });
  });
});
</script>
