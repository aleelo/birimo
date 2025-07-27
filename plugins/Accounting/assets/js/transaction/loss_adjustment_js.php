<script type="text/javascript">
var fnLossAdjustmentParams = {};
var id, type, amount;
var admin_url = $('input[name="site_url"]').val();

(function($) {
	"use strict";
  $('#transaction_loss_adjustment .select2').select2();
  setDatePicker("#transaction_loss_adjustment #from_date");
  setDatePicker("#transaction_loss_adjustment #to_date");
  
	fnLossAdjustmentParams = {
      "status": '#transaction_loss_adjustment [name="status"]',
      "from_date": '#transaction_loss_adjustment [name="from_date"]',
      "to_date": '#transaction_loss_adjustment [name="to_date"]',
    };

	$('#transaction_loss_adjustment select[name="status"]').on('change', function() {
	    init_loss_adjustment_table();
	});

	$('#transaction_loss_adjustment input[name="from_date"]').on('change', function() {
		init_loss_adjustment_table();
	});

	$('#transaction_loss_adjustment input[name="to_date"]').on('change', function() {
		init_loss_adjustment_table();
	});

  init_loss_adjustment_table();
  
  $('#transaction_loss_adjustment input[name="mass_convert"]').on('change', function() {
    if($('#transaction_loss_adjustment input[name="mass_convert"]').is(':checked') == true){
      $('#transaction_loss_adjustment input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('#transaction_loss_adjustment input[name="mass_delete_convert"]').on('change', function() {
    if($('#transaction_loss_adjustment input[name="mass_delete_convert"]').is(':checked') == true){
      $('#transaction_loss_adjustment input[name="mass_convert"]').prop( "checked", false );
    }
  });
  
  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#transaction_loss_adjustment #mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });
})(jQuery);

function init_loss_adjustment_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-loss-adjustment')) {
    $('.table-loss-adjustment').DataTable().destroy();
  }
  initDataTable('.table-loss-adjustment', admin_url + 'accounting/loss_adjustment_table', [0], [0], fnLossAdjustmentParams, [1, 'desc'], [5]);
}

function loss_adjustment_transaction_bulk_actions(){
    "use strict";
    $('#loss_adjustment_bulk_actions').modal('show');
}

// loss_adjustment bulk actions action
function loss_adjustment_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('#transaction_loss_adjustment input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#transaction_loss_adjustment input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('#transaction_loss_adjustment input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-loss-adjustment').find('tbody tr');

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