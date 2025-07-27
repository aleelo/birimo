<script type="text/javascript">
var site_url = $('input[name="site_url"]').val();
var difference = 0; 
var beginning_balance = <?php echo html_entity_decode($reconcile->beginning_balance); ?>;
var ending_balance = <?php echo html_entity_decode($reconcile->ending_balance); ?>;
 $(document).ready(function () {

var fnServerParams;

(function($) {
    "use strict";
    // Init accountacy currency symbol

    setDatePicker("#ending_date");
    setDatePicker("#adjustment_date");

    fnServerParams = {
        "account": '[name="account"]',
        "reconcile": '[name="reconcile"]',
    };

  init_history_table();

  $("body").on('change', '#mass_select_all_a', function() {
        var to, rows, checked;
        to = $(this).data('to-table');

        rows = $('.table-' + to).find('tbody tr');
        checked = $(this).prop('checked');
        $.each(rows, function() {
            $($(this).find('td').eq(0)).find('input').prop('checked', checked);
        });
    });

    $('.adjustment-form-submit').on('click', function() {
      $('#adjustment-modal').find('button[type="submit"]').prop('disabled', true);

      $('input[name="adjustment_amount"]').val(difference);
      $('input[name="finish"]').val(1);
      $('#adjustment-form').submit();
    });

})(jQuery);
  });

function init_history_table() {
  "use strict";

  $('.table-reconcile-history').appTable({
    source: site_url + 'accounting/reconcile_history_table',
            filterParams: {account: "<?php echo html_entity_decode($account->id); ?>", reconcile: "<?php echo html_entity_decode($reconcile->id); ?>"},
            columns: [

             {title: ""},
             {title: "<?php echo app_lang('date'); ?>"},
             {title: "<?php echo app_lang('type'); ?>"},
             {title: "<?php echo app_lang('acc_account'); ?>"},
             {title: "<?php echo app_lang('payee'); ?>"},
             {title: "<?php echo app_lang('description'); ?>"},
             {title: "<?php echo app_lang('payment'); ?>"},
             {title: "<?php echo app_lang('deposit'); ?>"},
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3])
  });

}

function calculate(){
    "use strict";
    var history_ids = '';
    var payment = 0;
    var deposit = 0;
    var count_payment = 0;
    var count_deposit = 0;

    var rows = $('.table-reconcile-history').find('tbody tr');
    $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') == true) {
            if(parseFloat(checkbox.data('payment')) > 0){
              count_payment++;
              payment = payment + parseFloat(checkbox.data('payment'));
            }
            if(parseFloat(checkbox.data('deposit')) > 0){
              deposit = deposit + parseFloat(checkbox.data('deposit'));
              count_deposit++;
            }
            if(history_ids == ''){
               history_ids = checkbox.val();
            }else{
               history_ids += ', ' + checkbox.val();
            }
        }
    });
    
    $('#count_payment').html(count_payment + ' <?php echo app_lang('payments_uppercase'); ?>');
    $('#count_deposit').html(count_deposit + ' <?php echo app_lang('deposits_uppercase'); ?>');
    $('#payment_amount').html(format_money(payment));
    $('#deposit_amount').html(format_money(deposit));
    $('#cleared_balance_amount').html(format_money(beginning_balance - payment + deposit));
    difference = ending_balance - ((beginning_balance - payment) + deposit);
    $('#difference_amount').html(format_money(ending_balance - ((beginning_balance - payment) + deposit)));
    $('#adjustment-form input[name="history_ids"]').val(history_ids);
    $('#reconcile-account-form input[name="history_ids"]').val(history_ids);
}
function edit_info(){
  "use strict";
    $('#edit-info-modal').modal('show'); 
}

function save_for_later(){
  "use strict";
    calculate();
    $('#reconcile-account-form input[name="finish"]').val(0);
    $('#reconcile-account-form').submit();
}

function finish_now(){
  "use strict";
    calculate();
    $('#reconcile-account-form input[name="finish"]').val(1);
    
    if(difference == 0){
        $('#reconcile-account-form').submit();
    }else{
        $('#adjustment-modal').modal('show');
    }
}


function adjustment_form_handler(form) {
    "use strict";
    $('#adjustment-modal').find('button[type="submit"]').prop('disabled', true);

    $('input[name="adjustment_amount"]').val(difference);
    $('#reconcile-account-form input[name="finish"]').val(1);

    var formURL = form.action;
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success === 'close_the_book') {
          appAlert.error(response.message);

          $('#adjustment-modal').find('button[type="submit"]').prop('disabled', false);
        }else if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
          $('#reconcile-account-form').submit();
        }else{
          appAlert.error(response.message);
        }
        $('#adjustment-modal').modal('hide');
    }).fail(function(error) {
          appAlert.error(JSON.parse(error.mesage));
    });

    return false;
}


function formatNumber(n) {
  "use strict";
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function format_money(input_val) {
  "use strict";
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  input_val = input_val.toString();
  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

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
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;

  }

  return input_val;
  
}

</script>
