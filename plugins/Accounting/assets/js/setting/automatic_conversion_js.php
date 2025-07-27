<script type="text/javascript">
var site_url = $('input[name="site_url"]').val();
var fnServerParams, id, inventory_asset_account, income_account, expense_account, item_id, tax_id, category_id, payment_account, deposit_to, expense_payment_account, expense_deposit_to, payment_mode_id, preferred_payment_method;
$(document).ready(function () {
  
(function($) {
	"use strict";
  $(".select2").select2();

	$('input[name="acc_invoice_automatic_conversion"]').on('change', function() {
	    if($('input[name="acc_invoice_automatic_conversion"]').is(':checked') == true){
	      $('#div_invoice_automatic_conversion').removeClass('hide');
	    }else{
	      $('#div_invoice_automatic_conversion').addClass('hide');
	    }
	});

  $('input[name="acc_payment_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_payment_automatic_conversion"]').is(':checked') == true){
        $('#div_payment_automatic_conversion').removeClass('hide');
      }else{
        $('#div_payment_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_payment_expense_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_payment_expense_automatic_conversion"]').is(':checked') == true){
        $('#div_payment_expense_automatic_conversion').removeClass('hide');
      }else{
        $('#div_payment_expense_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_credit_note_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_credit_note_automatic_conversion"]').is(':checked') == true){
        $('#div_credit_note_automatic_conversion').removeClass('hide');
      }else{
        $('#div_credit_note_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_expense_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_expense_automatic_conversion"]').is(':checked') == true){
        $('#div_expense_automatic_conversion').removeClass('hide');
      }else{
        $('#div_expense_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_tax_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_tax_automatic_conversion"]').is(':checked') == true){
        $('#div_tax_automatic_conversion').removeClass('hide');
      }else{
        $('#div_tax_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_pl_total_insurance_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_pl_total_insurance_automatic_conversion"]').is(':checked') == true){
        $('#div_pl_total_insurance_automatic_conversion').removeClass('hide');
      }else{
        $('#div_pl_total_insurance_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_pl_tax_paye_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_pl_tax_paye_automatic_conversion"]').is(':checked') == true){
        $('#div_pl_tax_paye_automatic_conversion').removeClass('hide');
      }else{
        $('#div_pl_tax_paye_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_pl_net_pay_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_pl_net_pay_automatic_conversion"]').is(':checked') == true){
        $('#div_pl_net_pay_automatic_conversion').removeClass('hide');
      }else{
        $('#div_pl_net_pay_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_wh_stock_import_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_wh_stock_import_automatic_conversion"]').is(':checked') == true){
        $('#div_wh_stock_import_automatic_conversion').removeClass('hide');
      }else{
        $('#div_wh_stock_import_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_wh_stock_export_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_wh_stock_export_automatic_conversion"]').is(':checked') == true){
        $('#div_wh_stock_export_automatic_conversion').removeClass('hide');
      }else{
        $('#div_wh_stock_export_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_wh_loss_adjustment_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_wh_loss_adjustment_automatic_conversion"]').is(':checked') == true){
        $('#div_wh_loss_adjustment_automatic_conversion').removeClass('hide');
      }else{
        $('#div_wh_loss_adjustment_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_wh_opening_stock_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_wh_opening_stock_automatic_conversion"]').is(':checked') == true){
        $('#div_wh_opening_stock_automatic_conversion').removeClass('hide');
      }else{
        $('#div_wh_opening_stock_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_pur_order_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_pur_order_automatic_conversion"]').is(':checked') == true){
        $('#div_pur_order_automatic_conversion').removeClass('hide');
      }else{
        $('#div_pur_order_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_pur_invoice_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_pur_invoice_automatic_conversion"]').is(':checked') == true){
        $('#div_pur_invoice_automatic_conversion').removeClass('hide');
      }else{
        $('#div_pur_invoice_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_pur_payment_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_pur_payment_automatic_conversion"]').is(':checked') == true){
        $('#div_pur_payment_automatic_conversion').removeClass('hide');
      }else{
        $('#div_pur_payment_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_mrp_manufacturing_order_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_mrp_manufacturing_order_automatic_conversion"]').is(':checked') == true){
        $('#div_mrp_manufacturing_order_automatic_conversion').removeClass('hide');
      }else{
        $('#div_mrp_manufacturing_order_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_fe_asset_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_fe_asset_automatic_conversion"]').is(':checked') == true){
        $('#div_fe_asset_automatic_conversion').removeClass('hide');
      }else{
        $('#div_fe_asset_automatic_conversion').addClass('hide');
      }
  });

   $('input[name="acc_fe_license_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_fe_license_automatic_conversion"]').is(':checked') == true){
        $('#div_fe_license_automatic_conversion').removeClass('hide');
      }else{
        $('#div_fe_license_automatic_conversion').addClass('hide');
      }
  });

    $('input[name="acc_fe_component_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_fe_component_automatic_conversion"]').is(':checked') == true){
        $('#div_fe_component_automatic_conversion').removeClass('hide');
      }else{
        $('#div_fe_component_automatic_conversion').addClass('hide');
      }
  });

     $('input[name="acc_fe_consumable_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_fe_consumable_automatic_conversion"]').is(':checked') == true){
        $('#div_fe_consumable_automatic_conversion').removeClass('hide');
      }else{
        $('#div_fe_consumable_automatic_conversion').addClass('hide');
      }
  });

      $('input[name="acc_fe_maintenance_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_fe_maintenance_automatic_conversion"]').is(':checked') == true){
        $('#div_fe_maintenance_automatic_conversion').removeClass('hide');
      }else{
        $('#div_fe_maintenance_automatic_conversion').addClass('hide');
      }
  });

       $('input[name="acc_fe_depreciation_automatic_conversion"]').on('change', function() {
      if($('input[name="acc_fe_depreciation_automatic_conversion"]').is(':checked') == true){
        $('#div_fe_depreciation_automatic_conversion').removeClass('hide');
      }else{
        $('#div_fe_depreciation_automatic_conversion').addClass('hide');
      }
  });

  $('input[name="acc_active_payment_mode_mapping"]').on('change', function() {
      var status = 0;
      if($('input[name="acc_active_payment_mode_mapping"]').is(':checked') == true){
        status = 1;
      }
      requestGet(site_url+ 'accounting/change_active_payment_mode_mapping/' + status).done(function(response) {
        
      });
  });

  $('input[name="acc_active_expense_category_mapping"]').on('change', function() {
      var status = 0;
      if($('input[name="acc_active_expense_category_mapping"]').is(':checked') == true){
        status = 1;
      }
      requestGet(site_url+ 'accounting/change_active_expense_category_mapping/' + status).done(function(response) {
        
      });
  });

  init_item_automatic_table();
  init_expense_category_mapping_table();
  init_payment_mode_mapping_table();
  init_tax_mapping_table();

  var addMoreLadderInputKey = $('.list-payment-method-mapping #payment_method_mapping').length;
  $("body").on('click', '.new_item_ladder', function() {
    if ($(this).hasClass('disabled')) { return false; }

    addMoreLadderInputKey++;
    var newItem = $('.list-payment-method-mapping').find('#payment_method_mapping').eq(0).clone().appendTo('.list-payment-method-mapping');
    newItem.find('.select2-container').remove();
    newItem.find('select2').select2();

    newItem.find('label[for="payment_mode[0]"]').attr('for', 'payment_mode[' + addMoreLadderInputKey + ']');
    newItem.find('select[name="payment_mode[0]"]').attr('name', 'payment_mode[' + addMoreLadderInputKey + ']');
    newItem.find('select[id="payment_mode[0]"]').attr('id', 'payment_mode[' + addMoreLadderInputKey + ']').select2();

    newItem.find('label[for="payment_account_detail[0]"]').attr('for', 'payment_account_detail[' + addMoreLadderInputKey + ']');
    newItem.find('select[name="payment_account_detail[0]"]').attr('name', 'payment_account_detail[' + addMoreLadderInputKey + ']');
    newItem.find('select[id="payment_account_detail[0]"]').attr('id', 'payment_account_detail[' + addMoreLadderInputKey + ']').select2();

    newItem.find('label[for="deposit_to_detail[0]"]').attr('for', 'deposit_to_detail[' + addMoreLadderInputKey + ']');
    newItem.find('select[name="deposit_to_detail[0]"]').attr('name', 'deposit_to_detail[' + addMoreLadderInputKey + ']');
    newItem.find('select[id="deposit_to_detail[0]"]').attr('id', 'deposit_to_detail[' + addMoreLadderInputKey + ']').select2();
    newItem.find('.select2-container').attr('style', '');

    newItem.find('button[name="add"]').html('<i data-feather="minus" class="icon-16"></i>');
    newItem.find('button[name="add"]').removeClass('new_item_ladder').addClass('remove_item_ladder').removeClass('btn-success').addClass('btn-danger');
  });

  $("body").on('click', '.remove_item_ladder', function() {
      $(this).parents('#payment_method_mapping').remove();
  });

  $('#edit-expense-category-mapping-modal input[name="preferred_payment_method"]').on('change', function() {
    if($('#edit-expense-category-mapping-modal input[name="preferred_payment_method"]').is(':checked') == true){
      $('#edit-expense-category-mapping-modal .list-payment-method-mapping').removeClass('hide');
    }else{
      $('#edit-expense-category-mapping-modal .list-payment-method-mapping').addClass('hide');
    }
  });

  $('#expense-category-mapping-modal input[name="preferred_payment_method"]').on('change', function() {
    if($('#expense-category-mapping-modal input[name="preferred_payment_method"]').is(':checked') == true){
      $('#expense-category-mapping-modal .list-payment-method-mapping').removeClass('hide');
    }else{
      $('#expense-category-mapping-modal .list-payment-method-mapping').addClass('hide');
    }
  });
})(jQuery);
  });

function init_item_automatic_table() {
  "use strict";

  $('.table-item-automatic').appTable({
    source: site_url + 'accounting/item_automatic_table',
            columns: [
              {title: "<?php echo app_lang("title") ?>"},
              {title: "<?php echo app_lang("description") ?>"},
              {title: "<?php echo app_lang("category") ?>"},
              {title: "<?php echo app_lang("rate") ?>"},
              {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4])
  });

}

function init_expense_category_mapping_table() {
  "use strict";

  $('.table-expense-category-mapping').appTable({
    source: site_url + 'accounting/expense_category_mapping_table',
            columns: [
              {title: "<?php echo app_lang("id") ?>"},
              {title: "<?php echo app_lang("name") ?>"},
              {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3])
  });

}

function init_tax_mapping_table() {
  "use strict";

   $('.table-tax-mapping').appTable({
    source: site_url + 'accounting/tax_mapping_table',
            columns: [
              {title: "<?php echo app_lang("id") ?>"},
              {title: "<?php echo app_lang("name") ?>"},
              {title: "<?php echo app_lang("rate") ?>"},
              {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3])
  });
}

function init_payment_mode_mapping_table() {
  "use strict";

  $('.table-payment-mode-mapping').appTable({
    source: site_url + 'accounting/payment_mode_mapping_table',
            columns: [
              {title: "<?php echo app_lang("name") ?>"},
              {title: "<?php echo app_lang("description") ?>"},
              {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3])
  });
}

function add_item_automatic(invoker) {
  "use strict";

  $('#item-automatic-modal').find('button[type="submit"]').prop('disabled', false);
  $('#item-automatic-modal').modal('show');
  $('#item-automatic-modal input[name="id"]').val('');
  $('#item-automatic-modal select[name="transfer_funds_from"]').val('').change();
  $('#item-automatic-modal select[name="transfer_funds_to"]').val('').change();
  $('#item-automatic-modal input[name="date"]').val('');
  $('#item-automatic-modal input[name="transfer_amount"]').val('');
}

function edit_item_automatic(invoker) {
  "use strict";

  	id = $(invoker).data('id');
	item_id = $(invoker).data('item-id');
	inventory_asset_account = $(invoker).data('inventory-asset-account');
	income_account = $(invoker).data('income-account');
	expense_account = $(invoker).data('expense-account');

    $('#edit-item-automatic-modal').find('button[type="submit"]').prop('disabled', false);
    $('#edit-item-automatic-modal input[name="id"]').val(id);
    $('#edit-item-automatic-modal select[name="item_id"]').val(item_id).change();
    $('#edit-item-automatic-modal select[name="inventory_asset_account"]').val(inventory_asset_account).change();
    $('#edit-item-automatic-modal select[name="income_account"]').val(income_account).change();
    $('#edit-item-automatic-modal select[name="expense_account"]').val(expense_account).change();

    $('#edit-item-automatic-modal').modal('show');
}


function item_automatic_form_handler(form) {
    "use strict";
    $('#item-automatic-modal').find('button[type="submit"]').prop('disabled', true);

    var formURL = form.action;
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success == 'close_the_book' || $.isNumeric(response.success)) {
          appAlert.error(response.message);

        }else if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
          appAlert.success(response.message);
          init_item_automatic_table();
        }else {
          appAlert.error(response.message);
        }
        $('#item-automatic-modal').modal('hide');
        $('#edit-item-automatic-modal').modal('hide');
    }).fail(function(error) {
        appAlert.error(JSON.parse(response.mesage));
    });

    return false;
}

function add_tax_mapping(invoker) {
  "use strict";

  $('#tax-mapping-modal').find('button[type="submit"]').prop('disabled', false);
  $('#tax-mapping-modal').modal('show');
  $('#tax-mapping-modal input[name="id"]').val('');
  $('#tax-mapping-modal select[name="tax[]"]').val('').change();
  $('#tax-mapping-modal select[name="payment_account"]').val($('#acc_tax_payment_account').val()).change();
  $('#tax-mapping-modal select[name="deposit_to"]').val($('#acc_tax_deposit_to').val()).change();
  $('#tax-mapping-modal select[name="expense_payment_account"]').val($('#acc_expense_tax_payment_account').val()).change();
  $('#tax-mapping-modal select[name="expense_deposit_to"]').val($('#acc_expense_tax_deposit_to').val()).change();
}

function edit_tax_mapping(invoker) {
  "use strict";
    id = $(invoker).data('id');
    tax_id = $(invoker).data('tax-id');
    payment_account = $(invoker).data('payment-account');
    deposit_to = $(invoker).data('deposit-to');
    expense_payment_account = $(invoker).data('expense-payment-account');
    expense_deposit_to = $(invoker).data('expense-deposit-to');

    $('#edit-tax-mapping-modal').find('button[type="submit"]').prop('disabled', false);
    $('#edit-tax-mapping-modal input[name="id"]').val(id);
    $('#edit-tax-mapping-modal select[name="tax_id"]').val(tax_id).change();
    $('#edit-tax-mapping-modal select[name="payment_account"]').val(payment_account).change();
    $('#edit-tax-mapping-modal select[name="deposit_to"]').val(deposit_to).change();
    $('#edit-tax-mapping-modal select[name="expense_payment_account"]').val(expense_payment_account).change();
    $('#edit-tax-mapping-modal select[name="expense_deposit_to"]').val(expense_deposit_to).change();

    $('#edit-tax-mapping-modal').modal('show');
}

function tax_mapping_form_handler(form) {
    "use strict";
    $('#tax-mapping-modal').find('button[type="submit"]').prop('disabled', true);
    $('#edit-tax-mapping-modal').find('button[type="submit"]').prop('disabled', true);

    var formURL = form.action;
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success == 'close_the_book' || $.isNumeric(response.success)) {
          appAlert.error(response.message);

        }else if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
          appAlert.success(response.message);
          init_tax_mapping_table();
        }else {
          appAlert.error(response.message);
        }
        $('#tax-mapping-modal').modal('hide');
        $('#edit-tax-mapping-modal').modal('hide');
    }).fail(function(error) {
        appAlert.error(JSON.parse(response.mesage));
    });

    return false;
}


function add_expense_category_mapping(invoker) {
  "use strict";

  $('#expense-category-mapping-modal').find('button[type="submit"]').prop('disabled', false);
  $('#expense-category-mapping-modal input[name="id"]').val('');
  $('#expense-category-mapping-modal select[name="category[]"]').val('').change();
  $('#expense-category-mapping-modal select[name="payment_account"]').val($('#acc_expense_payment_account').val()).change();
  $('#expense-category-mapping-modal select[name="deposit_to"]').val($('#acc_expense_deposit_to').val()).change();
  $('#expense-category-mapping-modal #preferred_payment_method').attr('checked', false).change();

  requestGetJSON(site_url + 'accounting/get_list_payment_method_mapping').done(function(response) {
    $('#expense-category-mapping-modal .list-payment-method-mapping').html(response.html);
    $("#expense-category-mapping-modal .list-payment-method-mapping .select2").select2();
    $('#expense-category-mapping-modal').modal('show');
  });

}

function edit_expense_category_mapping(invoker) {
  "use strict";

    id = $(invoker).data('id');
    category_id = $(invoker).data('category-id');
    payment_account = $(invoker).data('payment-account');
    deposit_to = $(invoker).data('deposit-to');
    preferred_payment_method = $(invoker).data('preferred-payment-method') == 1 ? true : false;

    $('#edit-expense-category-mapping-modal').find('button[type="submit"]').prop('disabled', false);
    $('#edit-expense-category-mapping-modal input[name="id"]').val(id);
    $('#edit-expense-category-mapping-modal select[name="category_id"]').val(category_id).change();
    $('#edit-expense-category-mapping-modal select[name="payment_account"]').val(payment_account).change();
    $('#edit-expense-category-mapping-modal select[name="deposit_to"]').val(deposit_to).change();
    $('#edit-expense-category-mapping-modal #edit_preferred_payment_method').attr('checked', preferred_payment_method).change();

    requestGetJSON(site_url + 'accounting/get_list_payment_method_mapping/'+category_id).done(function(response) {
      $('#edit-expense-category-mapping-modal .list-payment-method-mapping').html(response.html);
      $("#edit-expense-category-mapping-modal .list-payment-method-mapping .select2").select2();
      $('#edit-expense-category-mapping-modal').modal('show');
    });
}

function expense_category_mapping_form_handler(form) {
    "use strict";
    $('#expense-category-mapping-modal').find('button[type="submit"]').prop('disabled', true);
    $('#edit-expense-category-mapping-modal').find('button[type="submit"]').prop('disabled', true);

    var formURL = form.action;
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success == 'close_the_book' || $.isNumeric(response.success)) {
          appAlert.error(response.message);

        }else if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
          appAlert.success(response.message);
          init_expense_category_mapping_table();
        }else {
          appAlert.error(response.message);
        }
        $('#expense-category-mapping-modal').modal('hide');
        $('#edit-expense-category-mapping-modal').modal('hide');
    }).fail(function(error) {
        appAlert.error(JSON.parse(response.mesage));
    });

    return false;
}

function add_payment_mode_mapping(invoker) {
  "use strict";

  $('#payment-mode-mapping-modal').find('button[type="submit"]').prop('disabled', false);
  $('#payment-mode-mapping-modal').modal('show');
  $('#payment-mode-mapping-modal input[name="id"]').val('');
  $('#payment-mode-mapping-modal select[name="payment_mode[]"]').val('').change();
  $('#payment-mode-mapping-modal select[name="payment_account"]').val($('#acc_payment_payment_account').val()).change();
  $('#payment-mode-mapping-modal select[name="deposit_to"]').val($('#acc_payment_deposit_to').val()).change();
}

function edit_payment_mode_mapping(invoker) {
  "use strict";

    id = $(invoker).data('id');
    payment_mode_id = $(invoker).data('payment-mode-id');
    payment_account = $(invoker).data('payment-account');
    deposit_to = $(invoker).data('deposit-to');
    expense_payment_account = $(invoker).data('expense-payment-account');
    expense_deposit_to = $(invoker).data('expense-deposit-to');

    $('#edit-payment-mode-mapping-modal').find('button[type="submit"]').prop('disabled', false);
    $('#edit-payment-mode-mapping-modal input[name="id"]').val(id);
    $('#edit-payment-mode-mapping-modal select[name="payment_mode_id"]').val(payment_mode_id).change();
    $('#edit-payment-mode-mapping-modal select[name="payment_account"]').val(payment_account).change();
    $('#edit-payment-mode-mapping-modal select[name="deposit_to"]').val(deposit_to).change();
    $('#edit-payment-mode-mapping-modal select[name="expense_payment_account"]').val(expense_payment_account).change();
    $('#edit-payment-mode-mapping-modal select[name="expense_deposit_to"]').val(expense_deposit_to).change();

    $('#edit-payment-mode-mapping-modal').modal('show');
}

function payment_mode_mapping_form_handler(form) {
    "use strict";
    $('#payment-mode-mapping-modal').find('button[type="submit"]').prop('disabled', true);
    $('#edit-payment-mode-mapping-modal').find('button[type="submit"]').prop('disabled', true);

    var formURL = form.action;
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success == 'close_the_book' || $.isNumeric(response.success)) {
          appAlert.error(response.message);

        }else if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
          appAlert.success(response.message);
          init_payment_mode_mapping_table();
        }else {
          appAlert.error(response.message);
        }
        $('#payment-mode-mapping-modal').modal('hide');
        $('#edit-payment-mode-mapping-modal').modal('hide');
    }).fail(function(error) {
        appAlert.error(JSON.parse(response.mesage));
    });

    return false;
}
</script>
