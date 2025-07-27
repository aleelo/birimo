<script type="text/javascript">
    var site_url = $('input[name="site_url"]').val();
    var admin_url = $('input[name="site_url"]').val();
$(document).ready(function () { 

var fnServerParams;
var difference = 0;

(function($) {
    "use strict";

    $(".select2").select2();
    setDatePicker("#make_adjusting_date");
    
    $('li.menu-item-accounting_banking').addClass('active');
    $('li.sub-menu-item-accounting_reconcile_bank_account').addClass('active');

    $('button[type=submit]').click(function() {
        $(window).unbind('beforeunload');
    });
    // Init accountacy currency symbol
    fnServerParams = {
        "account": '[name="account"]',
        "reconcile": '[name="reconcile"]',
    };

    init_transactions_table();
    init_posted_bank_table();

    $("body").on('change', '#mass_select_all_a', function() {
        var to, rows, checked;
        to = $(this).data('to-table');

        rows = $('.table-' + to).find('tbody tr');
        checked = $(this).prop('checked');
        $.each(rows, function() {
            $($(this).find('td').eq(0)).find('input').prop('checked', checked);
        });
    });

    $("body").on('click', '#btn-finish', function() {
        $(window).unbind('beforeunload');
        $('input[name="closing_date"]').val($('input[name="closing_date_2"]').val());
        $('input[name="manager"]').val($('select[name="manager_2"]').val());
        $('input[name="date_reconciled"]').val($('input[name="date_reconciled_2"]').val());

        $('#reconcile-account-form').submit();
    });

    $("body").on('click', '#btn-make-adjusting-entry-submit', function() {
        $.post(admin_url + 'accounting/make_adjusting_entry_save', {
            transaction_bank_id: $('#make-adjusting-entry-modal [name=transaction_bank_id]').val(),
            account: $('#make-adjusting-entry-modal [name=make_adjusting_account]').val(),
            payee: $('#make-adjusting-entry-modal [name=make_adjusting_payee]').val(),
            type: $('#make-adjusting-entry-modal [name=make_adjusting_type]:checked').val(),
            transaction: $('#make-adjusting-entry-modal [name=make_adjusting_transaction]').val(),
            date: $('#make-adjusting-entry-modal [name=make_adjusting_date]').val(),
            withdrawal: $('#make-adjusting-entry-modal [name=make_adjusting_withdrawal]').val(),
            deposit: $('#make-adjusting-entry-modal [name=make_adjusting_deposit]').val(),
            reconcile: $('#reconcile-account-form input[name="reconcile"]').val(),
        }, function(response) {
            response = JSON.parse(response);
            if(response.success){
                appAlert.success(response.message);
            }

            $('#make-adjusting-entry-modal').modal('hide');
            complete_reconcile();
        });
    });

    $("body").on('click', '#btn-complete-reconcile-submit', function() {
        $('#reconcile-account-form').submit();
    });

    $("body").on('change', 'input[name=make_adjusting_type]', function() {
        var value = $(this).val();
        if(value == 'update_transaction'){
            $('.div-add-transaction').addClass('hide');
            $('.div-update-transaction').removeClass('hide');
        }else{
            $('.div-add-transaction').removeClass('hide');
            $('.div-update-transaction').addClass('hide');
        }
    });

    $("body").on('change', 'select[name="make_adjusting_transaction"]', function() {
        $.get(admin_url + 'accounting/make_adjusting_transaction_change/'+$(this).val(), function(response) {
            response = JSON.parse(response);
            $('input[name=make_adjusting_withdrawal]').val(response.withdrawal);
            $('input[name=make_adjusting_deposit]').val(response.deposit);
        });
    });

})(jQuery);
});

function init_transactions_table(){
  "use strict";

  $('.table-reconcile-transactions').appTable({
    source: site_url + 'accounting/reconcile_transactions_table',
            filterParams: {account: "<?php echo html_entity_decode($account->id); ?>", reconcile: "<?php echo html_entity_decode($reconcile->id); ?>"},
            columns: [
              {title: "<?php echo app_lang("date") ?>"},
              {title: "<?php echo app_lang("payee") ?>"},
              {title: "<?php echo app_lang("description") ?>"},
              {title: "<?php echo app_lang("withdrawals") ?>"},
              {title: "<?php echo app_lang("deposits") ?>"},
              {title: '<?php echo app_lang("match") ?>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6])
  });
}

function init_posted_bank_table(){
   "use strict";

  $('.table-reconcile-posted-bank').appTable({
    source: site_url + 'accounting/reconcile_posted_bank_table',
            filterParams: {account: "<?php echo html_entity_decode($account->id); ?>", reconcile: "<?php echo html_entity_decode($reconcile->id); ?>"},
            columns: [
              {title: "<?php echo app_lang("date") ?>"},
              {title: "<?php echo app_lang("payee") ?>"},
              {title: "<?php echo app_lang("description") ?>"},
              {title: "<?php echo app_lang("withdrawals") ?>"},
              {title: "<?php echo app_lang("deposits") ?>"},
              {title: '<?php echo app_lang("match") ?>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6])
  });
}

function edit_info(){
  "use strict";
    $('#edit-info-modal').modal('show'); 
}

function save_for_later(){
  "use strict";
    $('input[name="finish"]').val(0);
    $('#reconcile-account-form').submit();
}

function finish_now(){
  "use strict";
    $('input[name="finish"]').val(1);
    
    if(difference == 0){
        $('#finish_difference').addClass('hide');
        $('#finish_difference_header').addClass('hide');
        $('#finish_now_header').removeClass('hide');
        $('#btn-add-adjustment-and-finish').addClass('hide');
        $('#btn-finish').removeClass('hide');
    }else{
        $('#finish_difference').removeClass('hide');
        $('#finish_difference_header').removeClass('hide');
        $('#finish_now_header').addClass('hide');
        $('#btn-add-adjustment-and-finish').removeClass('hide');
        $('#btn-finish').addClass('hide');
    }

    $('#adjustment-modal').modal('show');
}


function adjustment_form_handler(form) {
    "use strict";
    $('#adjustment-modal').find('button[type="submit"]').prop('disabled', true);

    $('input[name="adjustment_amount"]').val(difference);
    $('input[name="finish"]').val(1);
    $('input[name="closing_date"]').val($('input[name="closing_date_2"]').val());
    $('input[name="manager"]').val($('select[name="manager_2"]').val());
    $('input[name="date_reconciled"]').val($('input[name="date_reconciled_2"]').val());

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
        appAlert.error(response.message);
    });

    return false;
}

function match_transactions(){
  "use strict";
  var reconcile_id = $('#reconcile-account-form input[name="reconcile"]').val();
  var account_id = $('#reconcile-account-form input[name="account"]').val();
  requestGetJSON(site_url + 'accounting/match_transactions/' + reconcile_id + '/'+account_id).done(function(response) { 
    if(response.success === 1 || response.success === '1'){
        appAlert.success(response.message);
        location.reload();
    }else{
        appAlert.error(response.message);
        location.reload();
    }

  });
}

function unmatch_transactions(){
  "use strict";
  var reconcile_id = $('#reconcile-account-form input[name="reconcile"]').val();
  var account_id = $('#reconcile-account-form input[name="account"]').val();

  requestGetJSON(site_url + 'accounting/unmatch_transactions/' + reconcile_id+'/'+account_id).done(function(response) { 
    
    if(response.success === true || response.success === 'true'){
        appAlert.success(response.message);
        $('.approval_btn').addClass('hide');
        $('.finish_btn').removeClass('hide');
        location.reload();
    }else{
        appAlert.error(response.message);
        location.reload();
    }

  });
}

function complete_reconcile(){
  "use strict";
    $.post(admin_url + 'accounting/get_transaction_uncleared', {
        reconcile_id: $('input[name=reconcile_id]').val(),
    }, function(response) {
        response = JSON.parse(response);

        if(response.status == 1){
            $('#transaction-uncleared-tbody').html(response.html);
            $('#transaction-uncleared-modal').modal('show');
        }else{
            $.post(admin_url + 'accounting/check_complete_reconcile', {
                reconcile_id: $('input[name=reconcile_id]').val(),
            }, function(res) {
                res = JSON.parse(res);

                if(res.leave_uncleared == 1){
                    $('#finish_uncleared_transactions').html(res.html);
                    $('#finish_uncleared_transactions').removeClass('hide');
                    $('#finish_closing_date').addClass('hide');
                    $('#complete_reconcile_transactions').html(res.html);
                    $('#complete_reconcile_transactions').removeClass('hide');
                }else{

                    $('#complete_reconcile_transactions').addClass('hide');
                    $('#finish_uncleared_transactions').addClass('hide');
                    $('#finish_closing_date').removeClass('hide')
                }
                $('#transaction-uncleared-modal').modal('hide');
                $('#complete-reconcile-modal').modal('show');
            });
        }
    });

}

function make_adjusting_entry(transaction_bank_id){
    "use strict";

    $.post(admin_url + 'accounting/get_make_adjusting_entry', {
        transaction_bank_id: transaction_bank_id,
        reconcile_id: $('input[name=reconcile_id]').val(),
    }, function(response) {
        response = JSON.parse(response);
        $('#transaction-uncleared-modal').modal('hide');
        $('#make-adjusting-entry-modal [name=transaction_bank_id]').val(transaction_bank_id);

        $('#make-adjusting-entry-modal input[name=make_adjusting_date]').val(response.date_value);
        $('#make-adjusting-entry-modal input[name=make_adjusting_withdrawal]').val(response.tran_withdrawal);
        $('#make-adjusting-entry-modal input[name=make_adjusting_deposit]').val(response.tran_deposit);
        $('select[name="make_adjusting_transaction"]').html(response.tran_html);
        $('select[name="make_adjusting_transaction"]').select2();

        $('#make-adjusting-entry-date').html(response.date);
        $('#make-adjusting-entry-amount').html(response.amount);
        $('#make-adjusting-entry-transaction').html(response.date + ' ' + response.payee + ' ' + response.amount);
        $('#make-adjusting-entry-modal').modal('show');
    });
}


function leave_it_uncleared(invoker){
    "use strict";
    $.post(admin_url + 'accounting/leave_it_uncleared', {
        transaction_bank_id:   $(invoker).data('id'),
    }, function(response) {
        response = JSON.parse(response);

        if(response.success){
            appAlert.success(response.message);
        }
        $(invoker).closest('tr').remove();

        complete_reconcile();
    });
}

</script>
