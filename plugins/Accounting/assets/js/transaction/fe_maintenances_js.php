<script type="text/javascript">
var fnMaintenanceParams = {};
var admin_url = $('input[name="site_url"]').val();

(function($) {
	"use strict";
  $('#transaction_maintenances .select2').select2();
  setDatePicker("#transaction_maintenances #from_date");
  setDatePicker("#transaction_maintenances #to_date");
  
	fnMaintenanceParams = {
      "status": '#transaction_maintenances [name="status"]',
      "from_date": '#transaction_maintenances [name="from_date"]',
      "to_date": '#transaction_maintenances [name="to_date"]',
    };

	$('#transaction_maintenances select[name="status"]').on('change', function() {
	    init_fe_maintenances_table();
	});

	$('#transaction_maintenances input[name="from_date"]').on('change', function() {
		init_fe_maintenances_table();
	});

	$('#transaction_maintenances input[name="to_date"]').on('change', function() {
		init_fe_maintenances_table();
	});

  init_fe_maintenances_table();
  
  $('#transaction_maintenances input[name="mass_convert"]').on('change', function() {
    if($('#transaction_maintenances input[name="mass_convert"]').is(':checked') == true){
      $('#transaction_maintenances input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#transaction_maintenances input[name="mass_delete_convert"]').on('change', function() {
    if($('#transaction_maintenances input[name="mass_delete_convert"]').is(':checked') == true){
      $('#transaction_maintenances input[name="mass_convert"]').prop( "checked", false );
    }
  });
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#transaction_maintenances #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);

function init_fe_maintenances_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-maintenances')) {
    $('.table-maintenances').DataTable().destroy();
  }
  initDataTable('.table-maintenances', admin_url + 'accounting/fe_maintenances_table', [0], [0], fnMaintenanceParams, [1, 'desc'], [12]);
}


function maintenances_transaction_bulk_actions(){
    "use strict";
    $('#maintenances_bulk_actions').modal('show');
}

// maintenances bulk actions action
function maintenances_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#transaction_maintenances input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#transaction_maintenances input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#transaction_maintenances input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-maintenances').find('tbody tr');

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