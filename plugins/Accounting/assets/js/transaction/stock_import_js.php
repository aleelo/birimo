<script type="text/javascript">
var fnStockImportParams = {};
var id, type, amount;
var admin_url = $('input[name="site_url"]').val();

(function($) {
	"use strict";
  $('#transaction_stock_import .select2').select2();
  setDatePicker("#transaction_stock_import #from_date");
  setDatePicker("#transaction_stock_import #to_date");
  
	fnStockImportParams = {
      "status": '#transaction_stock_import [name="status"]',
      "from_date": '#transaction_stock_import [name="from_date"]',
      "to_date": '#transaction_stock_import[name="to_date"]',
    };

	$('#transaction_stock_import select[name="status"]').on('change', function() {
	    init_stock_import_table();
	});

	$('#transaction_stock_import input[name="from_date"]').on('change', function() {
		init_stock_import_table();
	});

	$('#transaction_stock_import input[name="to_date"]').on('change', function() {
		init_stock_import_table();
	});

  $('#transaction_stock_import input[name="mass_convert"]').on('change', function() {
    if($('#transaction_stock_import input[name="mass_convert"]').is(':checked') == true){
      $('#transaction_stock_import input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#transaction_stock_import input[name="mass_delete_convert"]').on('change', function() {
    if($('#transaction_stock_import input[name="mass_delete_convert"]').is(':checked') == true){
      $('#transaction_stock_import input[name="mass_convert"]').prop( "checked", false );
    }
  });
  init_stock_import_table();
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#transaction_stock_import #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);

function stock_import_transaction_bulk_actions(){
    "use strict";
    $('#stock_import_bulk_actions').modal('show');
}

function init_stock_import_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-stock-import')) {
    $('.table-stock-import').DataTable().destroy();
  }
  initDataTable('.table-stock-import', admin_url + 'accounting/stock_import_table', [0], [0], fnStockImportParams, [1, 'desc'], [9]);
}

// stock_import bulk actions action
function stock_import_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#transaction_stock_import input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#transaction_stock_import input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#transaction_stock_import input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-stock-import').find('tbody tr');

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