<script type="text/javascript">
var fnOpeningStockParams = {};
var id, type, amount;
var admin_url = $('input[name="site_url"]').val();

(function($) {
	"use strict";
  $('#transaction_opening_stock .select2').select2();
  setDatePicker("#transaction_opening_stock #from_date");
  setDatePicker("#transaction_opening_stock #to_date");
  
	fnOpeningStockParams = {
      "status": '#transaction_opening_stock [name="status"]',
      "from_date": '#transaction_opening_stock [name="from_date"]',
      "to_date": '#transaction_opening_stock [name="to_date"]',
    };

	$('#transaction_opening_stock select[name="status"]').on('change', function() {
	    init_opening_stock_table();
	});

	$('#transaction_opening_stock input[name="from_date"]').on('change', function() {
		init_opening_stock_table();
	});

	$('#transaction_opening_stock input[name="to_date"]').on('change', function() {
		init_opening_stock_table();
	});

  init_opening_stock_table();
  
  $('#transaction_opening_stock input[name="mass_convert"]').on('change', function() {
    if($('#transaction_opening_stock input[name="mass_convert"]').is(':checked') == true){
      $('#transaction_opening_stock input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#transaction_opening_stock input[name="mass_delete_convert"]').on('change', function() {
    if($('#transaction_opening_stock input[name="mass_delete_convert"]').is(':checked') == true){
      $('#transaction_opening_stock input[name="mass_convert"]').prop( "checked", false );
    }
  });
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#transaction_opening_stock #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);


function init_opening_stock_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-opening-stock')) {
    $('.table-opening-stock').DataTable().destroy();
  }
  initDataTable('.table-opening-stock', admin_url + 'accounting/opening_stock_table', [0], [0], fnOpeningStockParams, [1, 'desc'], [6]);
}

function opening_stock_transaction_bulk_actions(){
    "use strict";
    $('#opening_stock_bulk_actions').modal('show');
}

// opening_stock bulk actions action
function opening_stock_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#transaction_opening_stock input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#transaction_opening_stock input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#transaction_opening_stock input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-opening-stock').find('tbody tr');

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