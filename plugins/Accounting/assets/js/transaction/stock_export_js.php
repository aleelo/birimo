<script type="text/javascript">
var fnStockExportParams = {};
var id, type, amount;
var admin_url = $('input[name="site_url"]').val();

(function($) {
	"use strict";
  $('#transaction_stock_export .select2').select2();
  setDatePicker("#transaction_stock_export #from_date");
  setDatePicker("#transaction_stock_export #to_date");

	fnStockExportParams = {
      "status": '#transaction_stock_export [name="status"]',
      "from_date": '#transaction_stock_export [name="from_date"]',
      "to_date": '#transaction_stock_export[name="to_date"]',
    };

	$('#transaction_stock_export select[name="status"]').on('change', function() {
	    init_stock_export_table();
	});

	$('#transaction_stock_export input[name="from_date"]').on('change', function() {
		init_stock_export_table();
	});

	$('#transaction_stock_export input[name="to_date"]').on('change', function() {
		init_stock_export_table();
	});

  $('#transaction_stock_export input[name="mass_convert"]').on('change', function() {
    if($('#transaction_stock_export input[name="mass_convert"]').is(':checked') == true){
      $('#transaction_stock_export input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#transaction_stock_export input[name="mass_delete_convert"]').on('change', function() {
    if($('#transaction_stock_export input[name="mass_delete_convert"]').is(':checked') == true){
      $('#transaction_stock_export input[name="mass_convert"]').prop( "checked", false );
    }
  });
  init_stock_export_table();
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#transaction_stock_export #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);

function init_stock_export_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-stock-export')) {
    $('.table-stock-export').DataTable().destroy();
  }
  initDataTable('.table-stock-export', admin_url + 'accounting/stock_export_table', [0], [0], fnStockExportParams, [1, 'desc'], [7]);
}

function stock_export_transaction_bulk_actions(){
    "use strict";
    $('#stock_export_bulk_actions').modal('show');
}

// stock_export bulk actions action
function stock_export_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#transaction_stock_export input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#transaction_stock_export input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#transaction_stock_export input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-stock-export').find('tbody tr');

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