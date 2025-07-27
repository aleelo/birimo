<script>
var fnOrderParams = {};
var fnInvoiceParams = {};
var fnPaymentParams = {};
var id, type, amount;
var admin_url = $('input[name="site_url"]').val();

$('.select2').select2();
setDatePicker("#purchase_order #from_date");
setDatePicker("#purchase_order #to_date");

setDatePicker("#purchase_invoice #from_date");
setDatePicker("#purchase_invoice #to_date");

setDatePicker("#purchase_payment #from_date");
setDatePicker("#purchase_payment #to_date");

(function($) {
  "use strict";
  fnOrderParams = {
      "status": '#purchase_order [name="status"]',
      "from_date": '#purchase_order [name="from_date"]',
      "to_date": '#purchase_order [name="to_date"]',
    };

  fnInvoiceParams = {
      "status": '#purchase_invoice [name="status"]',
      "from_date": '#purchase_invoice [name="from_date"]',
      "to_date": '#purchase_invoice [name="to_date"]',
    };

  fnPaymentParams = {
      "status": '#purchase_payment [name="status"]',
      "from_date": '#purchase_payment [name="from_date"]',
      "to_date": '#purchase_payment [name="to_date"]',
    };
    

  $('#purchase_order select[name="status"]').on('change', function() {
      init_purchase_order_table();
  });

  $('#purchase_order input[name="from_date"]').on('change', function() {
    init_purchase_order_table();
  });

  $('#purchase_order input[name="to_date"]').on('change', function() {
    init_purchase_order_table();
  });

  $('#purchase_invoice select[name="status"]').on('change', function() {
      init_purchase_invoice_table();
  });

  $('#purchase_invoice input[name="from_date"]').on('change', function() {
    init_purchase_invoice_table();
  });

  $('#purchase_invoice input[name="to_date"]').on('change', function() {
    init_purchase_invoice_table();
  });

  $('#purchase_payment select[name="status"]').on('change', function() {
      init_purchase_payment_table();
  });

  $('#purchase_payment input[name="from_date"]').on('change', function() {
    init_purchase_payment_table();
  });

  $('#purchase_payment input[name="to_date"]').on('change', function() {
    init_purchase_payment_table();
  });

  
  init_purchase_order_table();
  init_purchase_invoice_table();
  init_purchase_payment_table();
  
  $("body").on('click', '.edit_conversion_rate_action', function() {
      $('input[name="exchange_rate"]').val($('input[name="edit_exchange_rate"]').val());

      $('.amount_after_convert').html(format_money(($('input[name="exchange_rate"]').val() * $('input[name="convert_amount"]').val())));
      $('.currency_converter_label').html('1 '+$('input[name="currency_from"]').val() +' = '+$('input[name="edit_exchange_rate"]').val()+' '+ $('input[name="currency_to"]').val());
  });

  $('#purchase_order input[name="mass_convert"]').on('change', function() {
    if($('#purchase_order input[name="mass_convert"]').is(':checked') == true){
      $('#purchase_order input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#purchase_order input[name="mass_delete_convert"]').on('change', function() {
    if($('#purchase_order input[name="mass_delete_convert"]').is(':checked') == true){
      $('#purchase_order input[name="mass_convert"]').prop( "checked", false );
    }
  });
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#purchase_order #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });

  $('#purchase_invoice input[name="mass_convert"]').on('change', function() {
    if($('#purchase_invoice input[name="mass_convert"]').is(':checked') == true){
      $('#purchase_invoice input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#purchase_invoice input[name="mass_delete_convert"]').on('change', function() {
    if($('#purchase_invoice input[name="mass_delete_convert"]').is(':checked') == true){
      $('#purchase_invoice input[name="mass_convert"]').prop( "checked", false );
    }
  });
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#purchase_invoice #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });

  $('#purchase_payment input[name="mass_convert"]').on('change', function() {
    if($('#purchase_payment input[name="mass_convert"]').is(':checked') == true){
      $('#purchase_payment input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#purchase_payment input[name="mass_delete_convert"]').on('change', function() {
    if($('#purchase_payment input[name="mass_delete_convert"]').is(':checked') == true){
      $('#purchase_payment input[name="mass_convert"]').prop( "checked", false );
    }
  });
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#purchase_payment #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);

function init_purchase_order_table() {
"use strict";
  if ($.fn.DataTable.isDataTable('.table-purchase-order')) {
    $('.table-purchase-order').DataTable().destroy();
  }
  initDataTable('.table-purchase-order', "<?php echo get_uri('accounting/purchase_order_table/'); ?>", [0], [0], fnOrderParams, [2, 'desc'], [10]);
}

function init_purchase_invoice_table() {
"use strict";
 if ($.fn.DataTable.isDataTable('.table-purchase-invoice')) {
    $('.table-purchase-invoice').DataTable().destroy();
  }
  initDataTable('.table-purchase-invoice', "<?php echo get_uri('accounting/purchase_invoice_table/'); ?>" , [0], [0], fnInvoiceParams, [1, 'desc'], [10]);
}

function init_purchase_payment_table() {
"use strict";
  if ($.fn.DataTable.isDataTable('.table-purchase-payment')) {
    $('.table-purchase-payment').DataTable().destroy();
  }
  initDataTable('.table-purchase-payment', "<?php echo get_uri('accounting/purchase_payment_table/'); ?>", [0], [0], fnPaymentParams, [1, 'desc'], [6]);
}


function purchase_order_transaction_bulk_actions(){
    "use strict";
    $('#purchase_order_bulk_actions').modal('show');
}

// purchase_order bulk actions action
function purchase_order_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#purchase_order input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#purchase_order input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#purchase_order input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-purchase-order').find('tbody tr');

        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function() {
            $.post(admin_url + 'accounting/transaction_bulk_action', data).done(function() {
                window.location.reload();
            });
        }, 200);
    }
}


function purchase_invoice_transaction_bulk_actions(){
    "use strict";
    $('#purchase_invoice_bulk_actions').modal('show');
}

// purchase_invoice bulk actions action
function purchase_invoice_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#purchase_invoice input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#purchase_invoice input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#purchase_invoice input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-purchase-invoice').find('tbody tr');

        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function() {
            $.post(admin_url + 'accounting/transaction_bulk_action', data).done(function() {
                window.location.reload();
            });
        }, 200);
    }
}

function purchase_payment_transaction_bulk_actions(){
    "use strict";
    $('#purchase_payment_bulk_actions').modal('show');
}

// purchase_payment bulk actions action
function purchase_payment_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#purchase_payment input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#purchase_payment input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#purchase_payment input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-purchase-payment').find('tbody tr');

        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function() {
            $.post(admin_url + 'accounting/transaction_bulk_action', data).done(function() {
                window.location.reload();
            });
        }, 200);
    }
}

// Will give alert to confirm delete
function confirm_delete() {
    var message = 'Are you sure you want to perform this action?';

    // Clients area
    if (typeof(app) != 'undefined') {
        message = app.lang.confirm_action_prompt;
    }

    var r = confirm(message);
    if (r == false) { return false; }
    return true;
}
</script>