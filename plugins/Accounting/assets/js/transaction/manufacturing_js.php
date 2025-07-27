<script type="text/javascript">
var fnServerParams = {};
var id, type, amount;
var admin_url = $('input[name="site_url"]').val();

(function($) {
	"use strict";
  $('.select2').select2();

	fnServerParams = {
      "product": '[name="product"]',
      "routing": '[name="routing"]',
      "status": '[name="status"]',
    };
    

  $('select[name="product"]').on('change', function() {
      init_manufacturing_order_table();
  });

	$('select[name="routing"]').on('change', function() {
	    init_manufacturing_order_table();
	});

  $('select[name="status"]').on('change', function() {
      init_manufacturing_order_table();
  });


  $('input[name="mass_convert"]').on('change', function() {
    if($('input[name="mass_convert"]').is(':checked') == true){
      $('input[name="mass_delete_convert"]').prop( "checked", false );
    }
  });

  $('input[name="mass_delete_convert"]').on('change', function() {
    if($('input[name="mass_delete_convert"]').is(':checked') == true){
      $('input[name="mass_convert"]').prop( "checked", false );
    }
  });

  init_manufacturing_order_table();

  // On mass_select all select all the availble rows in the tables.
  $("body").on('change', '#mass_select_all', function () {
      var to, rows, checked;
      to = $(this).data('to-table');

      rows = $('.table-' + to).find('tbody tr');
      checked = $(this).prop('checked');
      $.each(rows, function () {
          $($(this).find('td').eq(0)).find('input').prop('checked', checked);
      });
  });

})(jQuery);


function init_manufacturing_order_table() {
"use strict";
  if ($.fn.DataTable.isDataTable('.table-manufacturing-order')) {
    $('.table-manufacturing-order').DataTable().destroy();
  }
  initDataTable('.table-manufacturing-order', admin_url + 'accounting/manufacturing_order_table', [0], [0], fnServerParams, [1, 'desc'], [9]);
}


function manufacturing_order_transaction_bulk_actions(){
    "use strict";
    $('#manufacturing_order_bulk_actions').modal('show');
}

// manufacturing_order bulk actions action
function manufacturing_order_bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('input[name="bulk_actions_type"]').val();
            data.mass_convert = $('input[name="mass_convert"]').prop('checked');
            data.mass_delete_convert = $('input[name="mass_delete_convert"]').prop('checked');

        var rows = $('.table-manufacturing-order').find('tbody tr');

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