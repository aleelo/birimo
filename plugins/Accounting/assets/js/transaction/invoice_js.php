<script type="text/javascript">
var fnStockImportParams = {};
var id, type, amount;
var admin_url = $('input[name="site_url"]').val();

(function($) {
  "use strict";
  $('#transaction_invoices .select2').select2();
  setDatePicker("#transaction_invoices #from_date");
  setDatePicker("#transaction_invoices #to_date");
  
  fnStockImportParams = {
      "status": '#transaction_invoices [name="status"]',
      "from_date": '#transaction_invoices [name="from_date"]',
      "to_date": '#transaction_invoices[name="to_date"]',
    };

  $('#transaction_invoices select[name="status"]').on('change', function() {
      init_invoice_table();
  });

  $('#transaction_invoices input[name="from_date"]').on('change', function() {
    init_invoice_table();
  });

  $('#transaction_invoices input[name="to_date"]').on('change', function() {
    init_invoice_table();
  });

  $('#transaction_invoices input[name="mass_convert"]').on('change', function() {
    if($('#transaction_invoices input[name="mass_convert"]').is(':checked') == true){
      $('#transaction_invoices input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#transaction_invoices input[name="mass_delete_convert"]').on('change', function() {
    if($('#transaction_invoices input[name="mass_delete_convert"]').is(':checked') == true){
      $('#transaction_invoices input[name="mass_convert"]').prop( "checked", false );
    }
  });
  init_invoice_table();
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#transaction_invoices #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);

function invoices_transaction_bulk_actions(){
    "use strict";
    $('#invoices_bulk_actions').modal('show');
}

function init_invoice_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-invoices')) {
    $('.table-invoices').DataTable().destroy();
  }
  initDataTable('.table-invoices', admin_url + 'accounting/sales_invoice_table', [0], [0], fnStockImportParams, [2, 'desc'], [7]);
}

// invoice bulk actions action
function invoices_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#transaction_invoices input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#transaction_invoices input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#transaction_invoices input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-invoices').find('tbody tr');

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