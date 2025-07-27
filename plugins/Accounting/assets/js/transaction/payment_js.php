<script type="text/javascript">
var fnPaymentParams = {};
var id, type, amount;
var admin_url = $('input[name="site_url"]').val();

(function($) {
  "use strict";
  $('#transaction_payments .select2').select2();
  setDatePicker("#transaction_payments #payment_from_date");
  setDatePicker("#transaction_payments #payment_to_date");
  
  fnPaymentParams = {
      "payment_methods": '#transaction_payments [name="payment_methods"]',
      "payment_status": '#transaction_payments [name="payment_status"]',
      "from_date": '#transaction_payments [name="payment_from_date"]',
      "to_date": '#transaction_payments [name="payment_to_date"]',
    };

  $('#transaction_payments select[name="payment_methods"]').on('change', function() {
      init_payment_table();
  });

  $('#transaction_payments select[name="payment_status"]').on('change', function() {
      init_payment_table();
  });

  $('#transaction_payments input[name="payment_from_date"]').on('change', function() {
    init_payment_table();
  });

  $('#transaction_payments input[name="payment_to_date"]').on('change', function() {
    init_payment_table();
  });

  $('#transaction_payments input[name="mass_convert"]').on('change', function() {
    if($('#transaction_payments input[name="mass_convert"]').is(':checked') == true){
      $('#transaction_payments input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#transaction_payments input[name="mass_delete_convert"]').on('change', function() {
    if($('#transaction_payments input[name="mass_delete_convert"]').is(':checked') == true){
      $('#transaction_payments input[name="mass_convert"]').prop( "checked", false );
    }
  });
  init_payment_table();
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#transaction_payments #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);

function payments_transaction_bulk_actions(){
    "use strict";
    $('#payments_bulk_actions').modal('show');
}

function init_payment_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-payments')) {
    $('.table-payments').DataTable().destroy();
  }
  initDataTable('.table-payments', admin_url + 'accounting/sales_table', [0], [0], fnPaymentParams, [2, 'desc'], [6]);
}

// payment bulk actions action
function payments_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#transaction_payments input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#transaction_payments input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#transaction_payments input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-payments').find('tbody tr');

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