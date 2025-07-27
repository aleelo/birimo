<script type="text/javascript">
var fnConsumableParams = {};
var admin_url = $('input[name="site_url"]').val();

(function($) {
	"use strict";
  $('#transaction_consumables .select2').select2();
  setDatePicker("#transaction_consumables #from_date");
  setDatePicker("#transaction_consumables #to_date");
  
	fnConsumableParams = {
      "status": '#transaction_consumables [name="status"]',
      "from_date": '#transaction_consumables [name="from_date"]',
      "to_date": '#transaction_consumables [name="to_date"]',
    };

	$('#transaction_consumables select[name="status"]').on('change', function() {
	    init_fe_consumables_table();
	});

	$('#transaction_consumables input[name="from_date"]').on('change', function() {
		init_fe_consumables_table();
	});

	$('#transaction_consumables input[name="to_date"]').on('change', function() {
		init_fe_consumables_table();
	});

  init_fe_consumables_table();
  
  $('#transaction_consumables input[name="mass_convert"]').on('change', function() {
    if($('#transaction_consumables input[name="mass_convert"]').is(':checked') == true){
      $('#transaction_consumables input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#transaction_consumables input[name="mass_delete_convert"]').on('change', function() {
    if($('#transaction_consumables input[name="mass_delete_convert"]').is(':checked') == true){
      $('#transaction_consumables input[name="mass_convert"]').prop( "checked", false );
    }
  });
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#transaction_consumables #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);

function init_fe_consumables_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-consumables')) {
    $('.table-consumables').DataTable().destroy();
  }
  initDataTable('.table-consumables', admin_url + 'accounting/fe_consumables_table', [0], [0], fnConsumableParams, [1, 'desc'], [12]);
}


function consumables_transaction_bulk_actions(){
    "use strict";
    $('#consumables_bulk_actions').modal('show');
}

// consumables bulk actions action
function consumables_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#transaction_consumables input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#transaction_consumables input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#transaction_consumables input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-consumables').find('tbody tr');

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