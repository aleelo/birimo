<script type="text/javascript">
    var site_url = $('input[name="site_url"]').val();
    var admin_url  = $('input[name="site_url"]').val();
	var list_account_type_details, fnServerParams;
	(function($) {
		"use strict";

	 	init_account_table();
})(jQuery);

function acc_add_transaction(id) {
  "use strict";
      $('.account_id').html('');
      $('.account_id').html(hidden_input('account', id));

      $('select[name="account"]').val(id).change();
      $('#account-modal').modal('show');
  
}



function formatNumber(n) {
  "use strict";
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function formatCurrency(input, blur) {
  "use strict";
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.

  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");
    var minus = input_val.substring(0, 1);
    if(minus != '-'){
      minus = '';
    }

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);
    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = minus+left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    var minus = input_val.substring(0, 1);
    if(minus != '-'){
      minus = '';
    }
    input_val = formatNumber(input_val);
    input_val = minus+input_val;

  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  //input[0].setSelectionRange(caret_pos, caret_pos);
}

function init_account_table() {
  "use strict";

  $('#registers-table').appTable({
    source: site_url + 'accounting/registers_table',
            columns: [
              {title: "Name"},
              {title: "Parent account"},
              {title: "Type"},
              {title: "Detail type"},
              {title: "Primary balance"},
              {title: "Bank balance"},
              {title: "Active"},
              {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3]),
            onInitComplete: function () {
              $('input[name="onoffswitch"]').on('change', function() {
                var status = 0;
                if($(this).is(':checked') == true){
                  status = 1;
                }

                var id = $(this).attr('data-id');

                requestGet(site_url+ 'accounting/change_account_status/' + id+'/' + status).done(function(response) {
                  
                });
            });
            },
  });

}
</script>
