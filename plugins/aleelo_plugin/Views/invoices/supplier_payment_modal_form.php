<?php echo form_open(get_uri("invoice_payments/save_payment_supplier"), [
    "id" => "invoice-payment-form",
    "class" => "general-form",
    "role" => "form"
]); ?>

<div class="modal-body clearfix">
  <div class="container-fluid">
    <input type="hidden" name="id" value="<?php echo $model_info->id ?? ''; ?>" />

    <?php if (!empty($invoice_id)) { ?>
      <input type="hidden" name="invoice_id" id="invoice_id" value="<?php echo $invoice_id; ?>" />
    <?php } else { ?>
      <div class="form-group">
        <div class="row">
          <label for="invoice_id" class="col-md-3"><?php echo app_lang('invoice'); ?></label>
          <div class="col-md-9">
            <?php
              echo form_dropdown(
                "invoice_id",
                $invoices_dropdown ?? ["" => "-"],
                "",
                "class='select2 validate-hidden' id='invoice_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' "
              );
            ?>
          </div>
        </div>
      </div>
    <?php } ?>

    <div class="form-group">
      <div class="row">
        <label for="supplier_id" class="col-md-3"><?php echo app_lang('supplier'); ?></label>
        <div class="col-md-9">
          <?php
            echo form_dropdown(
              "supplier_id",
              $suppliers_dropdown ?? ['' => '-'],
              $model_info->supplier_id ?? "",
              "class='select2 validate-hidden' id='supplier_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' "
            );
          ?>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <label for="invoice_payment_method_id" class="col-md-3"><?php echo app_lang('payment_method'); ?></label>
        <div class="col-md-9">
          <?php
            helper('cookie');
            echo form_dropdown(
              "invoice_payment_method_id",
              $payment_methods_dropdown ?? [],
              [$model_info->payment_method_id ?? get_cookie("user_" . $login_user->id . "_payment_method")],
              "class='select2 selected_payment_method' id='invoice_payment_method_id'"
            );
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
              "id" => "invoice_payment_date",
              "name" => "invoice_payment_date",
              "value" => !empty($model_info->payment_date) ? $model_info->payment_date : get_my_local_time("Y-m-d"),
              "class" => "form-control",
              "placeholder" => app_lang('payment_date'),
              "autocomplete" => "off",
              "data-rule-required" => true,
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
              "id" => "invoice_payment_amount",
              "name" => "invoice_payment_amount",
              "value" => $amount ?? "",
              "class" => "form-control",
              "placeholder" => app_lang('amount'),
              "data-rule-required" => true,
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
              "id" => "invoice_payment_note",
              "name" => "invoice_payment_note",
              "value" => !empty($model_info->note) ? process_images_from_content($model_info->note, false) : "",
              "class" => "form-control",
              "placeholder" => app_lang('description'),
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

<script type="text/javascript">
$(document).ready(function () {

  $("#invoice-payment-form").appForm({
    onSuccess: function (result) {
      if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
        location.reload();
      } else {
        if ($("#invoice-status-bar").length) {
          $("#invoice-payment-table").appTable({ newData: result.data, dataId: result.id });
          $("#invoice-total-section").html(result.invoice_total_view);
          if (typeof updateInvoiceStatusBar == 'function') {
            updateInvoiceStatusBar(result.invoice_id);
          }
        }
        if ($("#invoice-payment-table").length) {
          $("#" + $(".dataTable:visible").attr("id")).appTable({ reload: true });
        }
      }
    }
  });

  $("#invoice-payment-form .select2").select2();
  setDatePicker("#invoice_payment_date");

  // remember payment method
  $(".selected_payment_method").on("change", function () {
    var paymentMethodId = $(this).val();
    if (paymentMethodId) setCookie("user_" + "<?php echo $login_user->id; ?>" + "_payment_method", paymentMethodId);
  });

  // when supplier changes, compute suggestion:
  $("#supplier_id").on("change", function () {
    var invoice_id  = $("#invoice_id").val();
    var supplier_id = $("#supplier_id").val();

    if (supplier_id && invoice_id) {
      // invoice + supplier scoped due
      $.ajax({
        url: "<?php echo get_uri('invoice_payments/get_invoice_payment_amount_suggestion_supplier'); ?>" + "/" + invoice_id + "/" + supplier_id,
        type: "POST",
        dataType: "json",
        cache: false,
        success: function (res) {
          if (res && res.success) {
            $("#invoice_payment_amount").val(res.data.supplier_due);
          }
        }
      });
    } else if (supplier_id && !invoice_id) {
      // supplier total due
      $.ajax({
        url: "<?php echo get_uri('invoice_payments/get_supplier_payment_amount_suggestion_birimo'); ?>" + "/" + supplier_id,
        type: "POST",
        dataType: "json",
        cache: false,
        success: function (res) {
          if (res && res.success) {
            $("#invoice_payment_amount").val(res.supplier_total_summary.balance_due);
          }
        }
      });
    }
  });

  // if invoice dropdown exists, also re-query when invoice changes
  $("#invoice_id").on("change", function () {
    var invoice_id  = $("#invoice_id").val();
    var supplier_id = $("#supplier_id").val();
    if (supplier_id && invoice_id) {
      $.ajax({
        url: "<?php echo get_uri('invoice_payments/get_invoice_payment_amount_suggestion_supplier'); ?>" + "/" + invoice_id + "/" + supplier_id,
        type: "POST",
        dataType: "json",
        cache: false,
        success: function (res) {
          if (res && res.success) {
            $("#invoice_payment_amount").val(res.data.supplier_due);
          }
        }
      });
    }
  });

});
</script>
